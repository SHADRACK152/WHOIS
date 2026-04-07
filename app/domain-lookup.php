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

require_once __DIR__ . '/public-rdap-client.php';

function whois_domain_lookup_empty_result(string $domain, string $status, string $statusLabel, string $note, ?string $rdapSource = null, ?string $whoisSource = null): array
{
    $lookupSourceLabel = whois_domain_lookup_source_label([
        'rdapSource' => $rdapSource,
        'whoisSource' => $whoisSource,
        'publicRdapSource' => null,
    ]);

    return [
        'domain' => $domain,
        'status' => $status,
        'statusLabel' => $statusLabel,
        'registrar' => null,
        'registrarIanaId' => null,
        'handle' => null,
        'objectClassName' => null,
        'port43' => null,
        'statuses' => [],
            'created' => null,
            'expiration' => null,
            'updated' => null,
        'nameservers' => [],
        'secureDns' => null,
        'events' => [],
        'entities' => [],
        'notices' => [],
        'remarks' => [],
        'links' => [],
        'availabilityNote' => $note,
        'rdapSource' => $rdapSource,
        'whoisSource' => $whoisSource,
        'rawRdap' => null,
        'rawWhois' => null,
        'lookupSourceLabel' => $lookupSourceLabel,
    ];
}

function whois_whois_normalize_key(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/\s+/', ' ', $value) ?? $value;

    return $value;
}

function whois_whois_normalize_server(string $value): string
{
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    $value = preg_replace('#^[a-z][a-z0-9+.-]*://#i', '', $value) ?? $value;
    $value = trim($value, " \t\n\r\0\x0B/");

    if ($value === '') {
        return '';
    }

    $value = preg_replace('/\s+.*/', '', $value) ?? $value;

    if (preg_match('/^\[(.+)\]:(\d+)$/', $value, $matches) === 1) {
        return $matches[1] . ':' . $matches[2];
    }

    return $value;
}

function whois_whois_query_server(string $server, string $query, int $timeout = 12): string
{
    $server = whois_whois_normalize_server($server);
    $query = trim($query);

    if ($server === '' || $query === '') {
        return '';
    }

    $host = $server;
    $port = 43;

    if (preg_match('/^(.+):(\d+)$/', $server, $matches) === 1 && strpos($matches[1], ':') === false) {
        $host = $matches[1];
        $port = (int) $matches[2];
    }

    $lastWarning = '';
    set_error_handler(static function (int $severity, string $message) use (&$lastWarning): bool {
        $lastWarning = trim($message);
        return true;
    });

    try {
        $stream = stream_socket_client(
            'tcp://' . $host . ':' . $port,
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT
        );
    } finally {
        restore_error_handler();
    }

    if (!is_resource($stream)) {
        $reason = trim($errstr !== '' ? $errstr : $lastWarning);
        throw new RuntimeException('Unable to connect to WHOIS server ' . $host . '.' . ($reason !== '' ? ' ' . $reason : ''));
    }

    stream_set_timeout($stream, $timeout);
    fwrite($stream, $query . "\r\n");

    $response = '';

    while (!feof($stream)) {
        $chunk = fread($stream, 8192);

        if ($chunk === false) {
            break;
        }

        if ($chunk === '') {
            $meta = stream_get_meta_data($stream);

            if (!empty($meta['timed_out'])) {
                break;
            }

            continue;
        }

        $response .= $chunk;
    }

    fclose($stream);

    return $response;
}

function whois_whois_parse_fields(string $text): array
{
    $fields = [];
    $currentKey = null;
    $currentSectionPrefix = '';
    $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];

    foreach ($lines as $line) {
        $line = rtrim($line, "\r\n");
        $trimmed = trim($line);

        if ($trimmed === '') {
            $currentKey = null;
            $currentSectionPrefix = '';
            continue;
        }

        $sectionLabel = strtolower($trimmed);

        if (preg_match('/^(domain information|registrar information|registrant contact|administrative contact|technical contact|raw registry data|more record data|contacts|events|notices|remarks)$/i', $trimmed) === 1) {
            if (str_contains($sectionLabel, 'registrant contact')) {
                $currentSectionPrefix = 'registrant';
            } elseif (str_contains($sectionLabel, 'administrative contact')) {
                $currentSectionPrefix = 'administrative';
            } elseif (str_contains($sectionLabel, 'technical contact')) {
                $currentSectionPrefix = 'technical';
            } elseif (str_contains($sectionLabel, 'registrar information')) {
                $currentSectionPrefix = 'registrar';
            } elseif (str_contains($sectionLabel, 'domain information')) {
                $currentSectionPrefix = 'domain';
            } else {
                $currentSectionPrefix = '';
            }

            $currentKey = null;
            continue;
        }

        if (preg_match('/^\s*([^:]{2,80}):\s*(.*)$/u', $line, $matches) === 1) {
            $currentKey = whois_whois_normalize_key($matches[1]);
            $fields[$currentKey] ??= [];

            $value = trim($matches[2]);
            $fields[$currentKey][] = $value;

            if ($currentSectionPrefix !== '') {
                $sectionKey = whois_whois_normalize_key($currentSectionPrefix . ' ' . $matches[1]);
                $fields[$sectionKey] ??= [];
                $fields[$sectionKey][] = $value;
            }

            continue;
        }

        if ($currentKey !== null && preg_match('/^\s+(.+)$/u', $line, $matches) === 1) {
            $index = count($fields[$currentKey]) - 1;

            if ($index >= 0) {
                $fields[$currentKey][$index] = trim($fields[$currentKey][$index] . ' ' . trim($matches[1]));

                if ($currentSectionPrefix !== '') {
                    $sectionKey = whois_whois_normalize_key($currentSectionPrefix . ' ' . $currentKey);

                    if (array_key_exists($sectionKey, $fields)) {
                        $sectionIndex = count($fields[$sectionKey]) - 1;

                        if ($sectionIndex >= 0) {
                            $fields[$sectionKey][$sectionIndex] = $fields[$currentKey][$index];
                        }
                    }
                }
            }
        }
    }

    return $fields;
}

function whois_whois_first_value(array $fields, array $candidateKeys): string
{
    foreach ($candidateKeys as $candidateKey) {
        $normalizedKey = whois_whois_normalize_key((string) $candidateKey);

        if (!array_key_exists($normalizedKey, $fields)) {
            continue;
        }

        foreach ($fields[$normalizedKey] as $value) {
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }
    }

    return '';
}

function whois_whois_collect_values(array $fields, array $candidateKeys): array
{
    $values = [];

    foreach ($candidateKeys as $candidateKey) {
        $normalizedKey = whois_whois_normalize_key((string) $candidateKey);

        if (!array_key_exists($normalizedKey, $fields)) {
            continue;
        }

        foreach ($fields[$normalizedKey] as $value) {
            if (!is_string($value)) {
                continue;
            }

            $value = trim($value);

            if ($value === '') {
                continue;
            }

            $values[] = $value;
        }
    }

    return array_values(array_unique($values));
}

function whois_whois_lookup_server_for_tld(string $tld): ?string
{
    static $cache = [];

    $tld = whois_whois_normalize_key($tld);

    if ($tld === '') {
        return null;
    }

    if (array_key_exists($tld, $cache)) {
        return $cache[$tld];
    }

    try {
        $response = whois_whois_query_server('whois.iana.org', $tld);
    } catch (Throwable $exception) {
        $cache[$tld] = null;
        return null;
    }

    $fields = whois_whois_parse_fields($response);
    $server = whois_whois_first_value($fields, ['whois', 'refer']);
    $server = whois_whois_normalize_server($server);

    if ($server === '') {
        $cache[$tld] = null;
        return null;
    }

    $cache[$tld] = $server;

    return $cache[$tld];
}

function whois_whois_response_indicates_available(string $text): bool
{
    $haystack = strtolower($text);

    foreach (['no match', 'not found', 'no data found', 'available for registration', 'status: free', 'object does not exist', 'domain not found'] as $needle) {
        if (str_contains($haystack, $needle)) {
            return true;
        }
    }

    return false;
}

function whois_whois_contact_entity_from_fields(array $fields, string $role, array $map): ?array
{
    $role = whois_whois_normalize_key($role);

    $name = whois_whois_first_value($fields, $map['name'] ?? []);
    $organization = whois_whois_first_value($fields, $map['organization'] ?? []);
    $title = whois_whois_first_value($fields, $map['title'] ?? []);
    $email = whois_whois_first_value($fields, $map['email'] ?? []);
    $phone = whois_whois_first_value($fields, $map['phone'] ?? []);
    $street = whois_whois_first_value($fields, $map['street'] ?? []);
    $city = whois_whois_first_value($fields, $map['city'] ?? []);
    $state = whois_whois_first_value($fields, $map['state'] ?? []);
    $postalCode = whois_whois_first_value($fields, $map['postalCode'] ?? []);
    $country = whois_whois_first_value($fields, $map['country'] ?? []);
    $url = whois_whois_first_value($fields, $map['url'] ?? []);
    $handle = whois_whois_first_value($fields, $map['handle'] ?? []);

    $addressParts = array_filter([$street, $city, $state, $postalCode, $country], static fn ($value) => is_string($value) && trim($value) !== '');
    $address = trim(implode(', ', array_map(static fn ($value) => trim((string) $value), $addressParts)));

    if ($name === '' && $organization === '' && $title === '' && $email === '' && $phone === '' && $address === '' && $url === '' && $handle === '') {
        return null;
    }

    return [
        'handle' => $handle,
        'roles' => [$role],
        'name' => $name,
        'organization' => $organization,
        'title' => $title,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'street' => $street,
        'city' => $city,
        'state' => $state,
        'postalCode' => $postalCode,
        'country' => $country,
        'url' => $url,
        'status' => [],
        'publicIds' => [],
        'links' => $url !== '' ? [['href' => $url]] : [],
        'children' => [],
    ];
}

function whois_whois_lookup_domain(string $domain, string $tld): array
{
    $server = whois_whois_lookup_server_for_tld($tld);

    if (!is_string($server) || $server === '') {
        return whois_domain_lookup_empty_result($domain, 'unknown', 'Registry lookup unavailable', 'No WHOIS server was found for this TLD.');
    }

    try {
        $rawWhois = whois_whois_query_server($server, $domain);
    } catch (Throwable $exception) {
        return whois_domain_lookup_empty_result($domain, 'unknown', 'Lookup failed', $exception->getMessage(), null, $server);
    }

    if (trim($rawWhois) === '') {
        return whois_domain_lookup_empty_result($domain, 'unknown', 'Lookup returned no data', 'The WHOIS server returned no data.', null, $server);
    }

    $fields = whois_whois_parse_fields($rawWhois);
    $referralServer = whois_whois_normalize_server(whois_whois_first_value($fields, ['whois server', 'registrar whois server', 'referralserver', 'refer']));

    if ($referralServer !== '' && strtolower($referralServer) !== strtolower($server)) {
        try {
            $referralResponse = whois_whois_query_server($referralServer, $domain);

            if (trim($referralResponse) !== '') {
                $rawWhois .= "\n\n" . $referralResponse;
                $referralFields = whois_whois_parse_fields($referralResponse);

                foreach ($referralFields as $key => $values) {
                    if (!array_key_exists($key, $fields)) {
                        $fields[$key] = $values;
                        continue;
                    }

                    $fields[$key] = array_values(array_unique(array_merge($fields[$key], $values)));
                }
            }
        } catch (Throwable $exception) {
            // The first WHOIS response is still useful on its own.
        }
    }

    $availabilityMarker = whois_whois_response_indicates_available($rawWhois);
    $status = $availabilityMarker ? 'available' : 'registered';
    $statusLabel = $availabilityMarker ? 'Available' : 'Registered';

    $registrar = whois_whois_first_value($fields, ['registrar', 'sponsoring registrar']);
    $registrarIanaId = whois_whois_first_value($fields, ['registrar iana id', 'sponsoring registrar iana id']);
    $handle = whois_whois_first_value($fields, ['registry domain id', 'domain id', 'handle']);
    $objectClassName = whois_whois_first_value($fields, ['object classname']);
    $port43 = whois_whois_first_value($fields, ['whois server', 'registrar whois server', 'referralserver', 'refer']);

    $created = whois_whois_first_value($fields, ['creation date', 'created on', 'created', 'registered on', 'domain registration date']);
    $updated = whois_whois_first_value($fields, ['updated date', 'last updated', 'last changed', 'changed', 'modified']);
    $expiration = whois_whois_first_value($fields, ['registry expiry date', 'expiry date', 'expiration date', 'expires on', 'paid till', 'paid-till', 'registrar registration expiration date']);

    $nameservers = [];
    foreach (whois_whois_collect_values($fields, ['name server', 'nameserver', 'nserver']) as $nameserver) {
        $name = trim(preg_split('/\s+/', $nameserver, 2)[0] ?? '');

        if ($name !== '') {
            $nameservers[] = $name;
        }
    }
    $nameservers = array_values(array_unique($nameservers));

    $statuses = [];
    foreach (whois_whois_collect_values($fields, ['domain status', 'status']) as $rawStatus) {
        $normalizedStatus = strtolower(trim(preg_replace('#\s+https?://\S+$#i', '', $rawStatus) ?? $rawStatus));

        if ($normalizedStatus !== '') {
            $statuses[] = $normalizedStatus;
        }
    }
    $statuses = array_values(array_unique($statuses));

    $registrarEntity = whois_whois_contact_entity_from_fields($fields, 'registrar', [
        'name' => ['registrar', 'registrar name'],
        'organization' => ['registrar organization'],
        'title' => ['registrar title'],
        'email' => ['registrar email'],
        'phone' => ['registrar phone'],
        'street' => ['registrar street', 'registrar street address', 'registrar address'],
        'city' => ['registrar city'],
        'state' => ['registrar state', 'registrar state/province'],
        'postalCode' => ['registrar postal code', 'registrar postalcode'],
        'country' => ['registrar country'],
        'url' => ['registrar url'],
        'handle' => ['registrar iana id'],
    ]);

    $abuseEntity = whois_whois_contact_entity_from_fields($fields, 'abuse', [
        'name' => ['abuse contact', 'abuse'],
        'organization' => ['abuse organization', 'registrar abuse contact organization'],
        'title' => ['abuse title'],
        'email' => ['abuse contact email', 'abuse email', 'registrar abuse contact email'],
        'phone' => ['abuse contact phone', 'abuse phone', 'registrar abuse contact phone'],
        'street' => ['abuse street', 'abuse street address', 'abuse address'],
        'city' => ['abuse city'],
        'state' => ['abuse state', 'abuse state/province'],
        'postalCode' => ['abuse postal code'],
        'country' => ['abuse country'],
        'url' => ['abuse url'],
        'handle' => [],
    ]);

    $registrantEntity = whois_whois_contact_entity_from_fields($fields, 'registrant', [
        'name' => ['registrant', 'registrant name'],
        'organization' => ['registrant organization'],
        'title' => ['registrant title'],
        'email' => ['registrant email'],
        'phone' => ['registrant phone'],
        'street' => ['registrant street', 'registrant street address', 'registrant address'],
        'city' => ['registrant city'],
        'state' => ['registrant state', 'registrant state/province'],
        'postalCode' => ['registrant postal code', 'registrant postalcode'],
        'country' => ['registrant country'],
        'url' => ['registrant url'],
        'handle' => ['registrant id'],
    ]);

    $administrativeEntity = whois_whois_contact_entity_from_fields($fields, 'administrative', [
        'name' => ['administrative', 'administrative contact', 'admin', 'admin contact'],
        'organization' => ['administrative organization', 'admin organization'],
        'title' => ['administrative title', 'admin title'],
        'email' => ['administrative email', 'admin email'],
        'phone' => ['administrative phone', 'admin phone'],
        'street' => ['administrative street', 'administrative street address', 'admin street address', 'administrative address', 'admin address'],
        'city' => ['administrative city', 'admin city'],
        'state' => ['administrative state', 'admin state', 'administrative state/province', 'admin state/province'],
        'postalCode' => ['administrative postal code', 'admin postal code'],
        'country' => ['administrative country', 'admin country'],
        'url' => ['administrative url', 'admin url'],
        'handle' => ['administrative id', 'admin id'],
    ]);

    $technicalEntity = whois_whois_contact_entity_from_fields($fields, 'technical', [
        'name' => ['technical', 'technical contact', 'tech', 'tech contact'],
        'organization' => ['technical organization', 'tech organization'],
        'title' => ['technical title', 'tech title'],
        'email' => ['technical email', 'tech email'],
        'phone' => ['technical phone', 'tech phone'],
        'street' => ['technical street', 'technical street address', 'tech street address', 'technical address', 'tech address'],
        'city' => ['technical city', 'tech city'],
        'state' => ['technical state', 'tech state', 'technical state/province', 'tech state/province'],
        'postalCode' => ['technical postal code', 'tech postal code'],
        'country' => ['technical country', 'tech country'],
        'url' => ['technical url', 'tech url'],
        'handle' => ['technical id', 'tech id'],
    ]);

    $entities = array_values(array_filter([
        $registrarEntity,
        $abuseEntity,
        $registrantEntity,
        $administrativeEntity,
        $technicalEntity,
    ], static fn ($entity) => is_array($entity)));

    $events = [];
    if ($created !== '') {
        $events[] = ['eventAction' => 'registration', 'eventDate' => $created, 'eventActor' => $server];
    }
    if ($updated !== '') {
        $events[] = ['eventAction' => 'last changed', 'eventDate' => $updated, 'eventActor' => $server];
    }
    if ($expiration !== '') {
        $events[] = ['eventAction' => 'expiration', 'eventDate' => $expiration, 'eventActor' => $server];
    }

    $lookup = [
        'domain' => $domain,
        'status' => $status,
        'statusLabel' => $statusLabel,
        'registrar' => $registrar !== '' ? $registrar : null,
        'registrarIanaId' => $registrarIanaId !== '' ? $registrarIanaId : null,
        'handle' => $handle !== '' ? $handle : null,
        'objectClassName' => $objectClassName !== '' ? $objectClassName : 'domain',
        'port43' => $port43 !== '' ? $port43 : null,
        'statuses' => $statuses,
        'created' => $created !== '' ? $created : null,
        'expiration' => $expiration !== '' ? $expiration : null,
        'updated' => $updated !== '' ? $updated : null,
        'nameservers' => $nameservers,
        'secureDns' => null,
        'events' => $events,
        'entities' => $entities,
        'notices' => [],
        'remarks' => [],
        'links' => [],
        'availabilityNote' => $status === 'available'
            ? 'WHOIS data confirms this domain is available.'
            : 'WHOIS data confirms this domain is registered.',
        'rdapSource' => null,
        'whoisSource' => $referralServer !== '' ? $referralServer : $server,
        'rawRdap' => null,
        'rawWhois' => $rawWhois,
        'lookupSourceLabel' => 'WHOIS',
    ];

    return $lookup;
}

function whois_domain_lookup_merge_whois(array $lookup, array $whoisLookup): array
{
    if (($whoisLookup['status'] ?? 'unknown') === 'unknown') {
        return $lookup;
    }

    foreach (['registrar', 'registrarIanaId', 'handle', 'objectClassName', 'port43', 'created', 'expiration', 'updated'] as $field) {
        if ((empty($lookup[$field]) || $lookup[$field] === null) && !empty($whoisLookup[$field])) {
            $lookup[$field] = $whoisLookup[$field];
        }
    }

    if (($lookup['status'] ?? 'unknown') !== 'available' && ($whoisLookup['status'] ?? 'unknown') !== 'available') {
        if (($lookup['status'] ?? 'unknown') === 'unknown' && !empty($whoisLookup['status'])) {
            $lookup['status'] = $whoisLookup['status'];
        }

        if (($lookup['statusLabel'] ?? '') === '' && !empty($whoisLookup['statusLabel'])) {
            $lookup['statusLabel'] = $whoisLookup['statusLabel'];
        }
    }

    if (empty($lookup['statuses']) && !empty($whoisLookup['statuses'])) {
        $lookup['statuses'] = array_values(array_unique(array_map('strval', $whoisLookup['statuses'])));
    } elseif (!empty($whoisLookup['statuses'])) {
        $lookup['statuses'] = array_values(array_unique(array_merge($lookup['statuses'] ?? [], array_map('strval', $whoisLookup['statuses']))));
    }

    if (empty($lookup['nameservers']) && !empty($whoisLookup['nameservers'])) {
        $lookup['nameservers'] = array_values(array_unique(array_map('strval', $whoisLookup['nameservers'])));
    } elseif (!empty($whoisLookup['nameservers'])) {
        $lookup['nameservers'] = array_values(array_unique(array_merge($lookup['nameservers'] ?? [], array_map('strval', $whoisLookup['nameservers']))));
    }

    if (empty($lookup['events']) && !empty($whoisLookup['events'])) {
        $lookup['events'] = $whoisLookup['events'];
    }

    if (empty($lookup['entities']) && !empty($whoisLookup['entities'])) {
        $lookup['entities'] = $whoisLookup['entities'];
    } elseif (!empty($whoisLookup['entities'])) {
        $lookup['entities'] = array_merge($whoisLookup['entities'], $lookup['entities'] ?? []);
    }

    if (empty($lookup['notices']) && !empty($whoisLookup['notices'])) {
        $lookup['notices'] = $whoisLookup['notices'];
    }

    if (empty($lookup['remarks']) && !empty($whoisLookup['remarks'])) {
        $lookup['remarks'] = $whoisLookup['remarks'];
    }

    if (empty($lookup['links']) && !empty($whoisLookup['links'])) {
        $lookup['links'] = $whoisLookup['links'];
    }

    if (empty($lookup['secureDns']) && !empty($whoisLookup['secureDns'])) {
        $lookup['secureDns'] = $whoisLookup['secureDns'];
    }

    $lookup['whoisSource'] = $whoisLookup['whoisSource'] ?? ($lookup['whoisSource'] ?? null);
    $lookup['publicRdapSource'] = $whoisLookup['publicRdapSource'] ?? ($lookup['publicRdapSource'] ?? null);
    $lookup['rawWhois'] = $whoisLookup['rawWhois'] ?? ($lookup['rawWhois'] ?? null);

    $lookup['lookupSourceLabel'] = whois_domain_lookup_source_label($lookup);

    if (($lookup['status'] ?? 'unknown') === 'registered') {
        $lookup['availabilityNote'] = 'Live registry data confirms this domain is registered.';
    }

    if (($lookup['status'] ?? 'unknown') === 'available') {
        $lookup['availabilityNote'] = 'Live registry data confirms this domain is available.';
    }

    return $lookup;
}

function whois_domain_lookup_should_use_whois_fallback(array $lookup): bool
{
    $status = (string) ($lookup['status'] ?? 'unknown');

    if ($status === 'available') {
        return false;
    }

    if ($status !== 'registered') {
        return true;
    }

    $missingFields = 0;

    foreach (['registrar', 'created', 'expiration', 'updated'] as $field) {
        if (!is_string($lookup[$field] ?? null) || trim((string) $lookup[$field]) === '') {
            $missingFields++;
        }
    }

    if (!is_array($lookup['nameservers'] ?? null) || $lookup['nameservers'] === []) {
        $missingFields++;
    }

    if (!is_array($lookup['entities'] ?? null) || $lookup['entities'] === []) {
        $missingFields++;
    }

    return $missingFields > 0;
}

function whois_domain_lookup_has_contact_roles(array $entities): bool
{
    $requiredRoles = ['registrant', 'administrative', 'technical'];

    foreach ($requiredRoles as $role) {
        if (whois_rdap_find_entity_by_role($entities, $role) === null) {
            return false;
        }
    }

    return true;
}


function whois_domain_lookup_source_label(array $lookup): string
{
    $sources = [];

    if (!empty($lookup['rdapSource'])) {
        $sources[] = 'RDAP';
    }

    if (!empty($lookup['whoisSource'])) {
        $sources[] = 'WHOIS';
    }

    if (!empty($lookup['publicRdapSource'])) {
        $sources[] = 'Public RDAP';
    }

    if ($sources === []) {
        return 'Registry';
    }

    return implode(' + ', array_values(array_unique($sources)));
}

function whois_domain_lookup_has_public_rdap_gap(array $lookup): bool
{
    $entities = is_array($lookup['entities'] ?? null) ? $lookup['entities'] : [];

    foreach (['registrant', 'administrative', 'technical'] as $role) {
        $entity = whois_rdap_find_entity_by_role($entities, $role);

        if (!is_array($entity) || ($entity['redacted'] ?? false) === true) {
            return true;
        }

        $content = trim(implode(' ', array_filter([
            (string) ($entity['name'] ?? ''),
            (string) ($entity['organization'] ?? ''),
            (string) ($entity['email'] ?? ''),
            (string) ($entity['phone'] ?? ''),
            (string) ($entity['address'] ?? ''),
        ])));

        if ($content === '') {
            return true;
        }
    }

    return false;
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

function whois_rdap_stringify_value(mixed $value): string
{
    if (is_string($value)) {
        return trim($value);
    }

    if (is_int($value) || is_float($value) || is_bool($value)) {
        return trim((string) $value);
    }

    if (!is_array($value)) {
        return '';
    }

    $parts = [];

    foreach ($value as $item) {
        $part = whois_rdap_stringify_value($item);

        if ($part !== '') {
            $parts[] = $part;
        }
    }

    return trim(implode(', ', $parts));
}

function whois_rdap_event_list(array $events): array
{
    $normalized = [];

    foreach ($events as $event) {
        if (!is_array($event)) {
            continue;
        }

        $action = trim((string) ($event['eventAction'] ?? ''));
        $date = is_string($event['eventDate'] ?? null) ? trim((string) $event['eventDate']) : '';
        $actor = is_string($event['eventActor'] ?? null) ? trim((string) $event['eventActor']) : '';

        if ($action === '' && $date === '' && $actor === '') {
            continue;
        }

        $normalized[] = [
            'action' => $action,
            'date' => $date,
            'actor' => $actor,
        ];
    }

    return $normalized;
}

function whois_rdap_vcard_field(array $entity, string $fieldName): string
{
    $vcard = $entity['vcardArray'][1] ?? null;

    if (!is_array($vcard)) {
        return '';
    }

    foreach ($vcard as $entry) {
        if (!is_array($entry) || !isset($entry[0])) {
            continue;
        }

        if (strtolower(trim((string) $entry[0])) !== strtolower($fieldName)) {
            continue;
        }

        $value = $entry[3] ?? null;
        $text = whois_rdap_stringify_value($value);

        if ($text !== '') {
            return $text;
        }
    }

    return '';
}

function whois_rdap_vcard_value(array $entity, string $fieldName): mixed
{
    $vcard = $entity['vcardArray'][1] ?? null;

    if (!is_array($vcard)) {
        return null;
    }

    foreach ($vcard as $entry) {
        if (!is_array($entry) || !isset($entry[0])) {
            continue;
        }

        if (strtolower(trim((string) $entry[0])) !== strtolower($fieldName)) {
            continue;
        }

        return $entry[3] ?? null;
    }

    return null;
}

function whois_rdap_vcard_address(array $entity): array
{
    $value = whois_rdap_vcard_value($entity, 'adr');
    $parts = is_array($value) ? array_values($value) : [];

    return [
        'street' => is_string($parts[2] ?? null) ? trim((string) $parts[2]) : '',
        'city' => is_string($parts[3] ?? null) ? trim((string) $parts[3]) : '',
        'state' => is_string($parts[4] ?? null) ? trim((string) $parts[4]) : '',
        'postalCode' => is_string($parts[5] ?? null) ? trim((string) $parts[5]) : '',
        'country' => is_string($parts[6] ?? null) ? trim((string) $parts[6]) : '',
    ];
}

function whois_rdap_contact_value(mixed $value): string
{
    $text = whois_rdap_stringify_value($value);

    if ($text === '') {
        return '';
    }

    $normalized = preg_replace('/^tel:/i', '', $text) ?? $text;
    $normalized = preg_replace('/^mailto:/i', '', $normalized) ?? $normalized;

    return trim($normalized);
}

function whois_rdap_vcard_link(array $entity): string
{
    $links = is_array($entity['links'] ?? null) ? whois_rdap_links($entity['links']) : [];

    foreach ($links as $link) {
        if (($link['rel'] ?? '') === 'about' && ($link['href'] ?? '') !== '') {
            return (string) $link['href'];
        }
    }

    foreach ($links as $link) {
        if (($link['href'] ?? '') !== '') {
            return (string) $link['href'];
        }
    }

    return whois_rdap_contact_value(whois_rdap_vcard_value($entity, 'url'));
}

function whois_rdap_entity_list(array $entities): array
{
    $normalized = [];

    foreach ($entities as $entity) {
        if (!is_array($entity)) {
            continue;
        }

        $roles = [];
        foreach ($entity['roles'] ?? [] as $role) {
            if (is_string($role)) {
                $role = strtolower(trim($role));

                if ($role !== '') {
                    $roles[] = $role;
                }
            }
        }

        $address = whois_rdap_vcard_field($entity, 'adr');
        $addressParts = whois_rdap_vcard_address($entity);
        $email = whois_rdap_contact_value(whois_rdap_vcard_value($entity, 'email'));
        $phone = whois_rdap_contact_value(whois_rdap_vcard_value($entity, 'tel'));
        $name = whois_rdap_vcard_field($entity, 'fn');
        $organization = whois_rdap_vcard_field($entity, 'org');
        $title = whois_rdap_vcard_field($entity, 'title');
        $url = whois_rdap_vcard_link($entity);

        $publicIds = [];
        foreach ($entity['publicIds'] ?? [] as $publicId) {
            if (!is_array($publicId)) {
                continue;
            }

            $publicIds[] = [
                'type' => is_string($publicId['type'] ?? null) ? trim((string) $publicId['type']) : '',
                'identifier' => is_string($publicId['identifier'] ?? null) ? trim((string) $publicId['identifier']) : '',
            ];
        }

        $normalized[] = [
            'handle' => is_string($entity['handle'] ?? null) ? trim((string) $entity['handle']) : '',
            'roles' => $roles,
            'name' => $name,
            'organization' => $organization,
            'title' => $title,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'street' => $addressParts['street'],
            'city' => $addressParts['city'],
            'state' => $addressParts['state'],
            'postalCode' => $addressParts['postalCode'],
            'country' => $addressParts['country'],
            'url' => $url,
            'status' => is_array($entity['status'] ?? null) ? array_values(array_filter(array_map(static fn ($status) => is_string($status) ? trim($status) : '', $entity['status']))) : [],
            'publicIds' => $publicIds,
            'links' => $url !== '' ? [['href' => $url]] : [],
            'children' => whois_rdap_entity_list(is_array($entity['entities'] ?? null) ? $entity['entities'] : []),
        ];
    }

    return $normalized;
}

function whois_rdap_find_entity_by_role(array $entities, string $role): ?array
{
    foreach ($entities as $entity) {
        if (!is_array($entity)) {
            continue;
        }

        if (in_array($role, $entity['roles'] ?? [], true)) {
            return $entity;
        }

        $childEntity = whois_rdap_find_entity_by_role(is_array($entity['children'] ?? null) ? $entity['children'] : [], $role);

        if (is_array($childEntity)) {
            return $childEntity;
        }
    }

    return null;
}

function whois_rdap_text_blocks(array $items): array
{
    $normalized = [];

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $text = [];

        $description = $item['description'] ?? [];
        if (is_string($description)) {
            $description = [$description];
        }

        foreach ($description as $line) {
            if (is_string($line) && trim($line) !== '') {
                $text[] = trim($line);
            }
        }

        $title = $item['title'] ?? '';
        if (is_string($title)) {
            $title = [$title];
        }

        foreach ($title as $line) {
            if (is_string($line) && trim($line) !== '') {
                $text[] = trim($line);
            }
        }

        $normalized[] = [
            'title' => is_string($item['title'] ?? null) ? trim((string) $item['title']) : '',
            'description' => $text,
            'links' => is_array($item['links'] ?? null) ? $item['links'] : [],
        ];
    }

    return $normalized;
}

function whois_rdap_links(array $links): array
{
    $normalized = [];

    foreach ($links as $link) {
        if (!is_array($link)) {
            continue;
        }

        $normalized[] = [
            'href' => is_string($link['href'] ?? null) ? trim((string) $link['href']) : '',
            'rel' => is_string($link['rel'] ?? null) ? trim((string) $link['rel']) : '',
            'title' => is_string($link['title'] ?? null) ? trim((string) $link['title']) : '',
            'type' => is_string($link['type'] ?? null) ? trim((string) $link['type']) : '',
        ];
    }

    return $normalized;
}

function whois_rdap_date_only(?string $value): string
{
    if (!is_string($value)) {
        return '';
    }

    $value = trim($value);

    if ($value === '') {
        return '';
    }

    return substr($value, 0, 10);
}

function whois_rdap_relative_time_label(?string $value): string
{
    if (!is_string($value)) {
        return '';
    }

    $value = trim($value);

    if ($value === '') {
        return '';
    }

    try {
        $then = new DateTimeImmutable($value);
        $now = new DateTimeImmutable('now', $then->getTimezone());
        $difference = $now->diff($then);
    } catch (Throwable $exception) {
        return '';
    }

    if ($difference->days === 0) {
        return $difference->invert === 1 ? 'Updated today' : 'Updated today';
    }

    $label = $difference->days . ' day' . ($difference->days === 1 ? '' : 's');

    return $difference->invert === 1 ? 'Updated ' . $label . ' ago' : 'Updated in ' . $label;
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
            'handle' => null,
            'objectClassName' => null,
            'port43' => null,
            'statuses' => [],
            'created' => null,
            'expiration' => null,
            'updated' => null,
            'nameservers' => [],
            'secureDns' => null,
            'events' => [],
            'entities' => [],
            'notices' => [],
            'remarks' => [],
            'links' => [],
            'availabilityNote' => 'Type a domain to check registration status.',
            'rdapSource' => null,
            'rawRdap' => null,
        ];
    }

    $tld = substr($domain, (int) strrpos($domain, '.') + 1);
    $rdapBase = whois_rdap_base_for_tld($tld);

    if (!is_string($rdapBase) || $rdapBase === '') {
        $whoisLookup = whois_whois_lookup_domain($domain, $tld);

        if (($whoisLookup['status'] ?? 'unknown') !== 'unknown') {
            return $whoisLookup;
        }

        return whois_domain_lookup_empty_result(
            $domain,
            'unknown',
            'Registry lookup unavailable',
            'No registry lookup endpoint was found for this TLD.'
        );
    }

    $rdapUrl = rtrim($rdapBase, '/') . '/domain/' . rawurlencode($domain);

    try {
        $response = whois_http_get_json($rdapUrl);
    } catch (Throwable $exception) {
        $whoisLookup = whois_whois_lookup_domain($domain, $tld);

        if (($whoisLookup['status'] ?? 'unknown') !== 'unknown') {
            $whoisLookup['rdapSource'] = $rdapUrl;
            $whoisLookup['lookupSourceLabel'] = 'WHOIS';
            return $whoisLookup;
        }

        return whois_domain_lookup_empty_result($domain, 'unknown', 'Lookup failed', $exception->getMessage(), $rdapUrl);
    }

    $statusCode = $response['statusCode'] ?? 0;
    $body = $response['body'] ?? null;

    if ($statusCode === 404) {
        $whoisLookup = whois_whois_lookup_domain($domain, $tld);

        if (($whoisLookup['status'] ?? 'unknown') === 'registered') {
            $whoisLookup['rdapSource'] = $rdapUrl;
            $whoisLookup['lookupSourceLabel'] = 'RDAP + WHOIS';
            return $whoisLookup;
        }

        if (($whoisLookup['status'] ?? 'unknown') === 'available') {
            $whoisLookup['rdapSource'] = $rdapUrl;
            $whoisLookup['lookupSourceLabel'] = 'RDAP + WHOIS';
            return $whoisLookup;
        }

        return [
            'domain' => $domain,
            'status' => 'available',
            'statusLabel' => 'Available',
            'registrar' => null,
            'handle' => null,
            'objectClassName' => null,
            'port43' => null,
            'statuses' => [],
            'created' => null,
            'expiration' => null,
            'updated' => null,
            'nameservers' => [],
            'secureDns' => null,
            'events' => [],
            'entities' => [],
            'notices' => [],
            'remarks' => [],
            'links' => [],
            'availabilityNote' => 'The registry returned no registration record for this domain.',
            'rdapSource' => $rdapUrl,
            'publicRdapSource' => null,
            'rawRdap' => null,
        ];
    }

    if (!is_array($body)) {
        $whoisLookup = whois_whois_lookup_domain($domain, $tld);

        if (($whoisLookup['status'] ?? 'unknown') !== 'unknown') {
            $whoisLookup['rdapSource'] = $rdapUrl;
            $whoisLookup['lookupSourceLabel'] = 'WHOIS';
            return $whoisLookup;
        }

        return whois_domain_lookup_empty_result($domain, 'unknown', 'Lookup returned no data', 'The registry response could not be parsed.', $rdapUrl);
    }

    $events = is_array($body['events'] ?? null) ? $body['events'] : [];
    $normalizedEvents = whois_rdap_event_list($events);
    $nameservers = [];

    foreach ($body['nameservers'] ?? [] as $nameserver) {
        if (is_array($nameserver) && isset($nameserver['ldhName']) && is_string($nameserver['ldhName'])) {
            $nameservers[] = $nameserver['ldhName'];
        }
    }

    $created = null;
    $updated = null;
    $expiration = null;

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

        $action = strtolower(trim((string) ($event['eventAction'] ?? '')));

        if (in_array($action, ['expiration', 'registry expiry', 'expiry'], true) && is_string($event['eventDate'] ?? null)) {
            $expiration = $event['eventDate'];
        }
    }

    $statuses = [];
    foreach ($body['status'] ?? [] as $status) {
        if (is_string($status)) {
            $status = trim($status);

            if ($status !== '') {
                $statuses[] = $status;
            }
        }
    }
    $statuses = array_values(array_unique($statuses));

    $registrar = null;
    $entities = [];
    foreach ($body['entities'] ?? [] as $entity) {
        if (!is_array($entity)) {
            continue;
        }

        $entities[] = whois_rdap_entity_list([$entity])[0] ?? [];

        $roles = $entity['roles'] ?? [];
        if (is_array($roles) && in_array('registrar', $roles, true)) {
            $registrar = whois_rdap_vcard_field($entity, 'fn');
            if (!is_string($registrar)) {
                $registrar = $entity['handle'] ?? null;
            }
            break;
        }
    }

    $notices = whois_rdap_text_blocks(is_array($body['notices'] ?? null) ? $body['notices'] : []);
    $remarks = whois_rdap_text_blocks(is_array($body['remarks'] ?? null) ? $body['remarks'] : []);
    $links = whois_rdap_links(is_array($body['links'] ?? null) ? $body['links'] : []);
    $secureDns = is_array($body['secureDNS'] ?? null) ? $body['secureDNS'] : null;
    $handle = is_string($body['handle'] ?? null) ? trim((string) $body['handle']) : null;
    $objectClassName = is_string($body['objectClassName'] ?? null) ? trim((string) $body['objectClassName']) : null;
    $port43 = is_string($body['port43'] ?? null) ? trim((string) $body['port43']) : null;

    $registrarIanaId = null;
    foreach ($entities as $entity) {
        if (!is_array($entity)) {
            continue;
        }

        if (in_array('registrar', $entity['roles'] ?? [], true)) {
            foreach ($body['entities'] ?? [] as $rawEntity) {
                if (!is_array($rawEntity)) {
                    continue;
                }

                $roles = $rawEntity['roles'] ?? [];
                if (!is_array($roles) || !in_array('registrar', $roles, true)) {
                    continue;
                }

                if (is_array($rawEntity['publicIds'] ?? null)) {
                    foreach ($rawEntity['publicIds'] as $publicId) {
                        if (!is_array($publicId)) {
                            continue;
                        }

                        if (strtolower((string) ($publicId['type'] ?? '')) === 'iana registrar id') {
                            $registrarIanaId = is_string($publicId['identifier'] ?? null) ? trim((string) $publicId['identifier']) : null;
                            break 2;
                        }
                    }
                }
            }
        }
    }

    $lookup = [
        'domain' => $domain,
        'status' => 'registered',
        'statusLabel' => 'Registered',
        'registrar' => is_string($registrar) ? $registrar : null,
        'registrarIanaId' => $registrarIanaId,
        'handle' => $handle,
        'objectClassName' => $objectClassName,
        'port43' => $port43,
        'statuses' => $statuses,
        'created' => $created,
        'expiration' => $expiration,
        'updated' => $updated,
        'nameservers' => $nameservers,
        'secureDns' => $secureDns,
        'events' => $normalizedEvents,
        'entities' => $entities,
        'notices' => $notices,
        'remarks' => $remarks,
        'links' => $links,
        'availabilityNote' => 'Live registry data confirms this domain is registered.',
        'rdapSource' => $rdapUrl,
        'publicRdapSource' => null,
        'whoisSource' => null,
        'rawRdap' => $body,
        'rawWhois' => null,
        'lookupSourceLabel' => 'RDAP',
    ];

    $rdapHasRedactedData = is_array($body['redacted'] ?? null) && ($body['redacted'] ?? []) !== [];
    $rdapHasAllContactRoles = whois_domain_lookup_has_contact_roles($entities);

    if ($rdapHasRedactedData || !$rdapHasAllContactRoles || whois_domain_lookup_should_use_whois_fallback($lookup)) {
        $whoisLookup = whois_whois_lookup_domain($domain, $tld);

        if (($whoisLookup['status'] ?? 'unknown') !== 'unknown') {
            $lookup = whois_domain_lookup_merge_whois($lookup, $whoisLookup);
        }
    }

    if (whois_domain_lookup_has_public_rdap_gap($lookup)) {
        $publicRdapLookup = whois_public_rdap_lookup_domain($domain);

        if (($publicRdapLookup['status'] ?? 'unknown') !== 'unknown') {
            $lookup = whois_domain_lookup_merge_whois($lookup, $publicRdapLookup);
        }
    }

    $lookup['lookupSourceLabel'] = whois_domain_lookup_source_label($lookup);

    return $lookup;
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
        return 'Live registry lookup confirms this domain is available.';
    }

    if ($status === 'registered') {
        if ($registrar !== '') {
            return 'Registered through ' . $registrar . '.';
        }

        return 'Live registry lookup confirms this domain is registered.';
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