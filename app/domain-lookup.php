<?php

declare(strict_types=1);

function whois_domain_normalize(string $value): string
{
    $value = trim(strtolower($value));
    $value = preg_replace('#^https?://#', '', $value) ?? $value;
    $value = preg_replace('#/.*$#', '', $value) ?? $value;
    $value = preg_replace('/\s+/', '', $value) ?? $value;

    if ($value === '') {
        return '';
    }

    if (strpos($value, '.') === false) {
        $value .= '.com';
    }

    return $value;
}

function whois_http_get_json(string $url): array
{
    $insecureSsl = in_array(strtolower(getenv('WHOIS_INSECURE_SSL') ?: ''), ['1', 'true', 'yes', 'on'], true);

    if (function_exists('curl_init')) {
        $handle = curl_init($url);

        if ($handle === false) {
            throw new RuntimeException('Unable to initialize lookup request.');
        }

        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => !$insecureSsl,
            CURLOPT_SSL_VERIFYHOST => $insecureSsl ? 0 : 2,
        ]);

        $responseBody = curl_exec($handle);

        if ($responseBody === false) {
            $error = curl_error($handle);
            throw new RuntimeException($error !== '' ? $error : 'Lookup request failed.');
        }

        $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Accept: application/json\r\n",
                'timeout' => 10,
                'ignore_errors' => true,
            ],
            'ssl' => $insecureSsl
                ? [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
                : [],
        ]);

        $responseBody = file_get_contents($url, false, $context);

        if ($responseBody === false) {
            throw new RuntimeException('Lookup request failed.');
        }

        $statusCode = 0;

        foreach ($http_response_header ?? [] as $headerLine) {
            if (preg_match('/^HTTP\/\d(?:\.\d)?\s+(\d{3})\b/', $headerLine, $matches) === 1) {
                $statusCode = (int) $matches[1];
                break;
            }
        }
    }

    $decoded = json_decode($responseBody, true);

    if (!is_array($decoded)) {
        return [
            'statusCode' => $statusCode,
            'body' => null,
        ];
    }

    return [
        'statusCode' => $statusCode,
        'body' => $decoded,
    ];
}

function whois_rdap_bootstrap_services(): array
{
    static $cache = null;

    if (is_array($cache)) {
        return $cache;
    }

    try {
        $bootstrap = whois_http_get_json('https://data.iana.org/rdap/dns.json');
        $services = is_array($bootstrap['body']['services'] ?? null) ? $bootstrap['body']['services'] : [];
    } catch (Throwable $exception) {
        $services = [];
    }

    $cache = $services;

    return $cache;
}

function whois_rdap_base_for_tld(string $tld): ?string
{
    static $cache = [];

    $tld = strtolower(trim($tld));

    if ($tld === '') {
        return null;
    }

    if (array_key_exists($tld, $cache)) {
        return $cache[$tld];
    }

    foreach (whois_rdap_bootstrap_services() as $service) {
        if (!is_array($service) || count($service) < 2) {
            continue;
        }

        $tlds = $service[0] ?? [];
        $endpoints = $service[1] ?? [];

        if (!is_array($tlds) || !is_array($endpoints)) {
            continue;
        }

        if (in_array($tld, $tlds, true)) {
            $cache[$tld] = is_string($endpoints[0] ?? null) ? $endpoints[0] : null;
            return $cache[$tld];
        }
    }

    $cache[$tld] = null;

    return null;
}

function whois_rdap_supported_tlds(): array
{
    static $cache = null;

    if (is_array($cache)) {
        return $cache;
    }

    $tlds = [];

    foreach (whois_rdap_bootstrap_services() as $service) {
        if (!is_array($service) || count($service) < 2) {
            continue;
        }

        $serviceTlds = $service[0] ?? [];

        if (!is_array($serviceTlds)) {
            continue;
        }

        foreach ($serviceTlds as $tld) {
            if (!is_string($tld)) {
                continue;
            }

            $normalized = strtolower(trim($tld));

            if ($normalized === '') {
                continue;
            }

            $tlds[$normalized] = true;
        }
    }

    $cache = array_keys($tlds);
    sort($cache, SORT_STRING);

    return $cache;
}

function whois_domain_lookup(string $input): array
{
    $domain = whois_domain_normalize($input);

    if ($domain === '') {
        return [
            'domain' => '',
            'status' => 'unknown',
            'statusLabel' => 'Enter a domain to begin',
            'registrar' => null,
            'created' => null,
            'updated' => null,
            'nameservers' => [],
            'availabilityNote' => 'Type a domain to check registration status.',
            'rdapSource' => null,
        ];
    }

    $tld = substr($domain, (int) strrpos($domain, '.') + 1);
    $rdapBase = whois_rdap_base_for_tld($tld);

    if (!is_string($rdapBase) || $rdapBase === '') {
        return [
            'domain' => $domain,
            'status' => 'unknown',
            'statusLabel' => 'Registry lookup unavailable',
            'registrar' => null,
            'created' => null,
            'updated' => null,
            'nameservers' => [],
            'availabilityNote' => 'No RDAP endpoint was found for this TLD.',
            'rdapSource' => null,
        ];
    }

    $rdapUrl = rtrim($rdapBase, '/') . '/domain/' . rawurlencode($domain);

    try {
        $response = whois_http_get_json($rdapUrl);
    } catch (Throwable $exception) {
        return [
            'domain' => $domain,
            'status' => 'unknown',
            'statusLabel' => 'Lookup failed',
            'registrar' => null,
            'created' => null,
            'updated' => null,
            'nameservers' => [],
            'availabilityNote' => $exception->getMessage(),
            'rdapSource' => $rdapUrl,
        ];
    }

    $statusCode = $response['statusCode'] ?? 0;
    $body = $response['body'] ?? null;

    if ($statusCode === 404) {
        return [
            'domain' => $domain,
            'status' => 'available',
            'statusLabel' => 'Available',
            'registrar' => null,
            'created' => null,
            'updated' => null,
            'nameservers' => [],
            'availabilityNote' => 'RDAP returned no registration record for this domain.',
            'rdapSource' => $rdapUrl,
        ];
    }

    if (!is_array($body)) {
        return [
            'domain' => $domain,
            'status' => 'unknown',
            'statusLabel' => 'Lookup returned no data',
            'registrar' => null,
            'created' => null,
            'updated' => null,
            'nameservers' => [],
            'availabilityNote' => 'The registry response could not be parsed.',
            'rdapSource' => $rdapUrl,
        ];
    }

    $events = is_array($body['events'] ?? null) ? $body['events'] : [];
    $nameservers = [];

    foreach ($body['nameservers'] ?? [] as $nameserver) {
        if (is_array($nameserver) && isset($nameserver['ldhName']) && is_string($nameserver['ldhName'])) {
            $nameservers[] = $nameserver['ldhName'];
        }
    }

    $created = null;
    $updated = null;

    foreach ($events as $event) {
        if (!is_array($event)) {
            continue;
        }

        if (($event['eventAction'] ?? '') === 'registration' && is_string($event['eventDate'] ?? null)) {
            $created = $event['eventDate'];
        }

        if (($event['eventAction'] ?? '') === 'last changed' && is_string($event['eventDate'] ?? null)) {
            $updated = $event['eventDate'];
        }
    }

    $registrar = null;
    foreach ($body['entities'] ?? [] as $entity) {
        if (!is_array($entity)) {
            continue;
        }

        $roles = $entity['roles'] ?? [];
        if (is_array($roles) && in_array('registrar', $roles, true)) {
            $registrar = $entity['vcardArray'][1][3][3] ?? null;
            if (!is_string($registrar)) {
                $registrar = $entity['handle'] ?? null;
            }
            break;
        }
    }

    return [
        'domain' => $domain,
        'status' => 'registered',
        'statusLabel' => 'Registered',
        'registrar' => is_string($registrar) ? $registrar : null,
        'created' => $created,
        'updated' => $updated,
        'nameservers' => $nameservers,
        'availabilityNote' => 'RDAP reports an active registration record for this domain.',
        'rdapSource' => $rdapUrl,
    ];
}

function whois_domain_lookup_cached(string $input): array
{
    static $cache = [];

    $domain = whois_domain_normalize($input);

    if ($domain === '') {
        return whois_domain_lookup($input);
    }

    if (!array_key_exists($domain, $cache)) {
        $cache[$domain] = whois_domain_lookup($domain);
    }

    return $cache[$domain];
}

function whois_domain_candidate_domains(string $stem, array $tlds): array
{
    $normalizedStem = strtolower($stem);
    $normalizedStem = preg_replace('/[^a-z0-9]+/', '', $normalizedStem) ?? '';

    if ($normalizedStem === '') {
        $normalizedStem = 'brand';
    }

    $domains = [];

    foreach ($tlds as $tld) {
        $tld = strtolower(trim((string) $tld));
        $tld = ltrim($tld, '.');

        if ($tld === '') {
            continue;
        }

        $domains[] = $normalizedStem . '.' . $tld;
    }

    return array_values(array_unique($domains));
}

function whois_domain_lookup_many(array $domains): array
{
    $results = [];

    foreach ($domains as $domain) {
        if (!is_string($domain) || trim($domain) === '') {
            continue;
        }

        $lookup = whois_domain_lookup_cached($domain);
        $results[$lookup['domain']] = $lookup;
    }

    return $results;
}

function whois_domain_lookup_summary(array $lookup): string
{
    $status = (string) ($lookup['status'] ?? 'unknown');
    $registrar = is_string($lookup['registrar'] ?? null) ? trim((string) $lookup['registrar']) : '';
    $availabilityNote = is_string($lookup['availabilityNote'] ?? null) ? trim((string) $lookup['availabilityNote']) : '';

    if ($status === 'available') {
        return 'Live RDAP lookup confirms this domain is available.';
    }

    if ($status === 'registered') {
        if ($registrar !== '') {
            return 'Registered through ' . $registrar . '.';
        }

        return 'Live RDAP lookup confirms this domain is registered.';
    }

    return $availabilityNote !== '' ? $availabilityNote : 'Live registry data is not available for this domain.';
}

function whois_domain_lookup_badge(array $lookup): string
{
    $status = (string) ($lookup['status'] ?? 'unknown');

    if ($status === 'available') {
        return 'Available';
    }

    if ($status === 'registered') {
        return 'Registered';
    }

    return 'Unknown';
}