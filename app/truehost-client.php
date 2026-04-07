<?php

declare(strict_types=1);

require_once __DIR__ . '/domain-lookup.php';

function whois_truehost_env(string $name): ?string
{
    $value = getenv($name);

    if ($value === false || $value === '') {
        $value = $_SERVER[$name] ?? '';
    }

    $value = trim((string) $value);

    return $value === '' ? null : $value;
}

function whois_truehost_config(): array
{
    $endpoint = whois_truehost_env('TRUEHOST_ENDPOINT') ?? 'https://truehost.co.ke/cloud/includes/api.php';
    $endpoint = trim($endpoint);

    if ($endpoint !== '' && !str_ends_with($endpoint, '.php')) {
        $trimmedEndpoint = rtrim($endpoint, '/');

        if (str_ends_with(strtolower($trimmedEndpoint), '/api')) {
            $endpoint = $trimmedEndpoint . '.php';
        } else {
            $endpoint = $trimmedEndpoint . '.php';
        }
    }

    $insecureSsl = in_array(
        strtolower(whois_truehost_env('TRUEHOST_INSECURE_SSL') ?? whois_truehost_env('WHOIS_INSECURE_SSL') ?? ''),
        ['1', 'true', 'yes', 'on'],
        true
    );

    return [
        'endpoint' => $endpoint,
        'identifier' => whois_truehost_env('TRUEHOST_IDENTIFIER'),
        'secret' => whois_truehost_env('TRUEHOST_SECRET'),
        'accesskey' => whois_truehost_env('TRUEHOST_ACCESSKEY'),
        'currencyId' => (int) (whois_truehost_env('TRUEHOST_CURRENCYID') ?? '1'),
        'timeout' => max(10, (int) (whois_truehost_env('TRUEHOST_TIMEOUT') ?? '20')),
        'insecureSsl' => $insecureSsl,
    ];
}

function whois_truehost_request(string $action, array $fields = []): array
{
    $config = whois_truehost_config();

    if ($config['endpoint'] === '') {
        throw new RuntimeException('TRUEHOST_ENDPOINT is not configured.');
    }

    if ($config['identifier'] === null || $config['secret'] === null || $config['accesskey'] === null) {
        throw new RuntimeException('Truehost API credentials are not configured.');
    }

    $payload = array_merge([
        'action' => $action,
        'identifier' => $config['identifier'],
        'secret' => $config['secret'],
        'accesskey' => $config['accesskey'],
        'responsetype' => 'json',
    ], $fields);

    $body = http_build_query($payload);

    if (function_exists('curl_init')) {
        $handle = curl_init($config['endpoint']);

        if ($handle === false) {
            throw new RuntimeException('Unable to initialize Truehost request.');
        }

        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT => $config['timeout'],
            CURLOPT_CONNECTTIMEOUT => min(10, $config['timeout']),
            CURLOPT_SSL_VERIFYPEER => !$config['insecureSsl'],
            CURLOPT_SSL_VERIFYHOST => $config['insecureSsl'] ? 0 : 2,
        ]);

        $responseBody = curl_exec($handle);

        if ($responseBody === false) {
            $error = curl_error($handle);
            throw new RuntimeException($error !== '' ? $error : 'Truehost request failed.');
        }

        $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Accept: application/json',
                ]),
                'content' => $body,
                'timeout' => $config['timeout'],
                'ignore_errors' => true,
            ],
            'ssl' => $config['insecureSsl']
                ? [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
                : [],
        ]);

        $responseBody = file_get_contents($config['endpoint'], false, $context);

        if ($responseBody === false) {
            throw new RuntimeException('Truehost request failed.');
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
        throw new RuntimeException('Truehost returned an invalid response.');
    }

    if ($statusCode >= 400 || (($decoded['result'] ?? '') === 'error')) {
        $message = $decoded['message'] ?? $decoded['error'] ?? ('Truehost request failed with status ' . $statusCode . '.');
        throw new RuntimeException((string) $message);
    }

    return $decoded;
}

function whois_truehost_tld_pricing(): array
{
    static $cache = null;

    if (is_array($cache)) {
        return $cache;
    }

    $cache = whois_truehost_tld_pricing_from_page();

    if (is_array($cache) && ($cache['pricing'] ?? []) !== []) {
        return $cache;
    }

    $response = null;

    try {
        $response = whois_truehost_request('GetTLDPricing', [
            'currencyid' => whois_truehost_config()['currencyId'],
        ]);
    } catch (Throwable $exception) {
        $response = null;
    }

    if (is_array($response)) {
        $cache = [
            'currency' => is_array($response['currency'] ?? null) ? $response['currency'] : [],
            'pricing' => is_array($response['pricing'] ?? null) ? $response['pricing'] : [],
            'source' => 'api',
        ];

        if ($cache['pricing'] !== []) {
            return $cache;
        }
    }

    $cache = is_array($cache) ? $cache : whois_truehost_tld_pricing_from_page();

    return $cache;
}

function whois_truehost_tld_pricing_from_page(): array
{
    static $cache = null;

    if (is_array($cache)) {
        return $cache;
    }

    $config = whois_truehost_config();
    $pageUrl = $config['endpoint'];

    if (str_ends_with(strtolower($pageUrl), '.php')) {
        $pageUrl = preg_replace('#/api\.php$#i', '/includes/api/', $pageUrl) ?? $pageUrl;
    } else {
        $pageUrl = rtrim(dirname($pageUrl), '/') . '/';
    }

    try {
        if (function_exists('curl_init')) {
            $handle = curl_init($pageUrl);

            if ($handle === false) {
                throw new RuntimeException('Unable to initialize Truehost pricing page request.');
            }

            curl_setopt_array($handle, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPGET => true,
                CURLOPT_TIMEOUT => $config['timeout'],
                CURLOPT_CONNECTTIMEOUT => min(10, $config['timeout']),
                CURLOPT_SSL_VERIFYPEER => !$config['insecureSsl'],
                CURLOPT_SSL_VERIFYHOST => $config['insecureSsl'] ? 0 : 2,
            ]);

            $html = curl_exec($handle);

            if ($html === false) {
                $error = curl_error($handle);
                throw new RuntimeException($error !== '' ? $error : 'Truehost pricing page request failed.');
            }
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => $config['timeout'],
                    'ignore_errors' => true,
                ],
                'ssl' => $config['insecureSsl']
                    ? [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                    : [],
            ]);

            $html = file_get_contents($pageUrl, false, $context);

            if ($html === false) {
                throw new RuntimeException('Truehost pricing page request failed.');
            }
        }
    } catch (Throwable $exception) {
        $cache = [
            'currency' => [],
            'pricing' => [],
            'source' => 'fallback-unavailable',
        ];

        return $cache;
    }

    $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $currency = [
        'code' => 'KES',
        'prefix' => 'Ksh ',
        'suffix' => '',
    ];
    $pricing = [];

    if (preg_match_all('/\.(co\.ke|com|xyz|org|ke|shop|top)\s+Ksh\s+([0-9][0-9.,]*)/i', $text, $matches, PREG_SET_ORDER) > 0) {
        foreach ($matches as $match) {
            $tld = strtolower($match[1]);

            $pricing[$tld] = [
                'register' => [
                    '1' => $match[2],
                ],
                'renew' => [
                    '1' => $match[2],
                ],
            ];
        }
    }

    $cache = [
        'currency' => $currency,
        'pricing' => $pricing,
        'source' => 'page',
    ];

    return $cache;
}

function whois_truehost_tld_price(string $tld, string $mode = 'register'): ?array
{
    $tld = strtolower(ltrim(trim($tld), '.'));

    if ($tld === '') {
        return null;
    }

    $pricing = whois_truehost_tld_pricing();
    $currency = is_array($pricing['currency'] ?? null) ? $pricing['currency'] : [];
    $pricingEntry = $pricing['pricing'][$tld] ?? null;

    if (!is_array($pricingEntry) || $pricingEntry === [] || (($pricing['source'] ?? '') !== 'page' && !isset($pricingEntry[$mode]))) {
        $pagePricing = whois_truehost_tld_pricing_from_page();

        if (is_array($pagePricing['pricing'] ?? null)) {
            $fallbackEntry = $pagePricing['pricing'][$tld] ?? null;

            if (is_array($fallbackEntry) && $fallbackEntry !== []) {
                $pricing = $pagePricing;
                $currency = is_array($pricing['currency'] ?? null) ? $pricing['currency'] : [];
                $pricingEntry = $fallbackEntry;
            }
        }
    }

    if (!is_array($pricingEntry)) {
        return null;
    }

    $values = $pricingEntry[$mode] ?? null;

    if (is_array($values)) {
        $currencyId = (string) whois_truehost_config()['currencyId'];

        if (isset($values[$currencyId])) {
            $rawValue = (string) $values[$currencyId];
        } else {
            $firstValue = reset($values);
            $rawValue = is_scalar($firstValue) ? (string) $firstValue : '';
        }
    } elseif (is_scalar($values)) {
        $rawValue = (string) $values;
    } else {
        return null;
    }

    if ($rawValue === '') {
        return null;
    }

    $prefix = trim((string) ($currency['prefix'] ?? ''));
    $suffix = trim((string) ($currency['suffix'] ?? '/yr'));

    if ($suffix === '' && (($pricing['source'] ?? '') === 'page')) {
        $suffix = '';
    }

    $formatted = trim($prefix . $rawValue . $suffix);

    return [
        'tld' => $tld,
        'raw' => $rawValue,
        'formatted' => $formatted,
        'currency' => $currency,
    ];
}

function whois_truehost_domain_lookup(string $domain): array
{
    $domain = whois_domain_normalize($domain);

    if ($domain === '') {
        return [
            'domain' => '',
            'status' => 'unknown',
            'statusLabel' => 'Enter a domain to begin',
            'whois' => null,
            'availabilityNote' => 'Type a domain to check registration status.',
        ];
    }

    try {
        $response = whois_truehost_request('DomainWhois', [
            'domain' => $domain,
        ]);
    } catch (Throwable $exception) {
        $response = null;
    }

    if (!is_array($response)) {
        if (function_exists('whois_domain_lookup')) {
            $fallback = whois_domain_lookup($domain);

            return [
                'domain' => (string) ($fallback['domain'] ?? $domain),
                'status' => (string) ($fallback['status'] ?? 'unknown'),
                'statusLabel' => (string) ($fallback['statusLabel'] ?? 'Unknown'),
                'whois' => is_string($fallback['whois'] ?? null) ? $fallback['whois'] : null,
                'availabilityNote' => (string) ($fallback['availabilityNote'] ?? 'Registry lookup unavailable.'),
            ];
        }

        return [
            'domain' => $domain,
            'status' => 'unknown',
            'statusLabel' => 'Lookup failed',
            'whois' => null,
            'availabilityNote' => 'Registry lookup unavailable.',
        ];
    }

    $status = strtolower((string) ($response['status'] ?? 'unknown'));

    if ($status !== 'available' && $status !== 'unavailable') {
        if (function_exists('whois_domain_lookup')) {
            $fallback = whois_domain_lookup($domain);

            return [
                'domain' => (string) ($fallback['domain'] ?? $domain),
                'status' => (string) ($fallback['status'] ?? 'unknown'),
                'statusLabel' => (string) ($fallback['statusLabel'] ?? 'Unknown'),
                'whois' => is_string($fallback['whois'] ?? null) ? $fallback['whois'] : null,
                'availabilityNote' => (string) ($fallback['availabilityNote'] ?? 'Registry lookup unavailable.'),
            ];
        }

        $status = 'unknown';
    }

    return [
        'domain' => $domain,
        'status' => $status,
        'statusLabel' => $status === 'available' ? 'Available' : ($status === 'unavailable' ? 'Registered' : 'Unknown'),
        'whois' => is_string($response['whois'] ?? null) ? $response['whois'] : null,
        'availabilityNote' => $status === 'available'
            ? 'This domain is available.'
            : ($status === 'unavailable' ? 'This domain is already registered.' : 'The registry did not return a definitive status.'),
    ];
}
