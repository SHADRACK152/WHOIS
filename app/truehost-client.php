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

function whois_truehost_api_url(string $endpoint): string
{
    $endpoint = trim($endpoint);

    if ($endpoint === '') {
        return '';
    }

    if (str_ends_with(strtolower($endpoint), '.php')) {
        return $endpoint;
    }

    return rtrim($endpoint, '/') . '.php';
}

function whois_truehost_pricing_page_url(string $endpoint): string
{
    $endpoint = trim($endpoint);

    if ($endpoint === '') {
        return '';
    }

    if (str_ends_with(strtolower($endpoint), '.php')) {
        $derived = preg_replace('#/api(?:\.php)?$#i', '/includes/api/', $endpoint) ?? $endpoint;

        return rtrim($derived, '/') . '/';
    }

    return rtrim($endpoint, '/') . '/';
}

function whois_truehost_request(string $action, array $fields = []): array
{
    $config = whois_truehost_config();
    $endpoint = whois_truehost_api_url($config['endpoint']);

    if ($endpoint === '') {
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
        $handle = curl_init($endpoint);

        if ($handle === false) {
            throw new RuntimeException('Unable to initialize Truehost request.');
        }

        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0 Safari/537.36',
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
        $responseBody = false;
        $statusCode = 0;
    }

    $decoded = json_decode($responseBody, true);

    if (!is_array($decoded) || trim((string) $responseBody) === '') {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0 Safari/537.36',
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

        $responseBody = @file_get_contents($endpoint, false, $context);

        if ($responseBody !== false) {
            $statusCode = 0;

            foreach ($http_response_header ?? [] as $headerLine) {
                if (preg_match('/^HTTP\/\d(?:\.\d)?\s+(\d{3})\b/', $headerLine, $matches) === 1) {
                    $statusCode = (int) $matches[1];
                    break;
                }
            }

            $decoded = json_decode($responseBody, true);
        }
    }

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

    $cache = whois_truehost_tld_pricing_from_page();

    return $cache;
}

function whois_truehost_tld_pricing_from_page(): array
{
    static $cache = null;

    if (is_array($cache)) {
        return $cache;
    }

    $config = whois_truehost_config();
    $pageUrl = whois_truehost_pricing_page_url($config['endpoint']);

    try {
        if (function_exists('curl_init')) {
            $handle = curl_init($pageUrl);

            if ($handle === false) {
                throw new RuntimeException('Unable to initialize Truehost pricing page request.');
            }

            curl_setopt_array($handle, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPGET => true,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0 Safari/537.36',
                CURLOPT_TIMEOUT => $config['timeout'],
                CURLOPT_CONNECTTIMEOUT => min(10, $config['timeout']),
                CURLOPT_SSL_VERIFYPEER => !$config['insecureSsl'],
                CURLOPT_SSL_VERIFYHOST => $config['insecureSsl'] ? 0 : 2,
            ]);

            $html = curl_exec($handle);

            if ($html === false || trim((string) $html) === '') {
                $error = curl_error($handle);
                $html = false;
            }
        } else {
            $html = false;
        }

        if ($html === false) {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => implode("\r\n", [
                        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0 Safari/537.36',
                        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    ]),
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

            $html = @file_get_contents($pageUrl, false, $context);

            if ($html === false) {
                throw new RuntimeException('Truehost pricing page request failed.');
            }
        }
    } catch (Throwable $exception) {
        $html = false;
    }

    $text = $html !== false ? html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
    $currency = [
        'code' => 'KES',
        'prefix' => 'Ksh ',
        'suffix' => '',
    ];
    $pricing = [];

    if ($text !== '' && preg_match_all('/\.(co\.ke|com|ai|io|co|net|xyz|org|ke|shop|top|app|dev)\s+Ksh\s+([0-9][0-9.,]*)/i', $text, $matches, PREG_SET_ORDER) > 0) {
        foreach ($matches as $match) {
            $tld = strtolower($match[1]);
            $pricing[$tld] = [
                'register' => ['1' => str_replace(',', '', (string)$match[2])],
                'renew' => ['1' => str_replace(',', '', (string)$match[2])],
            ];
        }
    }

    // Default rigid fallback pricing if API AND Scraper both fail completely
    if (empty($pricing)) {
        $pricing = [
            'com' => ['register' => ['1' => '1300'], 'renew' => ['1' => '1500']],
            'net' => ['register' => ['1' => '1400'], 'renew' => ['1' => '1600']],
            'org' => ['register' => ['1' => '1450'], 'renew' => ['1' => '1600']],
            'co.ke' => ['register' => ['1' => '599'], 'renew' => ['1' => '999']],
            'ke' => ['register' => ['1' => '4999'], 'renew' => ['1' => '4999']],
            'ai' => ['register' => ['1' => '10500'], 'renew' => ['1' => '11000']],
            'io' => ['register' => ['1' => '4999'], 'renew' => ['1' => '5500']],
            'co' => ['register' => ['1' => '3500'], 'renew' => ['1' => '4000']],
            'xyz' => ['register' => ['1' => '250'], 'renew' => ['1' => '1500']],
            'shop' => ['register' => ['1' => '300'], 'renew' => ['1' => '4000']],
            'app' => ['register' => ['1' => '1900'], 'renew' => ['1' => '2500']],
            'dev' => ['register' => ['1' => '1900'], 'renew' => ['1' => '2500']],
            'online' => ['register' => ['1' => '150'], 'renew' => ['1' => '4500']],
            'store' => ['register' => ['1' => '190'], 'renew' => ['1' => '5000']],
            'site' => ['register' => ['1' => '150'], 'renew' => ['1' => '4000']],
            'tech' => ['register' => ['1' => '600'], 'renew' => ['1' => '6500']],
            'info' => ['register' => ['1' => '400'], 'renew' => ['1' => '3500']],
            'club' => ['register' => ['1' => '1500'], 'renew' => ['1' => '1800']],
            'live' => ['register' => ['1' => '350'], 'renew' => ['1' => '3500']],
            'in' => ['register' => ['1' => '900'], 'renew' => ['1' => '1200']],
            'wedding' => ['register' => ['1' => '4500'], 'renew' => ['1' => '5000']],
            'art' => ['register' => ['1' => '1800'], 'renew' => ['1' => '2000']],
            
            
            'africa' => ['register' => ['1' => '3500'], 'renew' => ['1' => '4000']],
            'luxury' => ['register' => ['1' => '55000'], 'renew' => ['1' => '55000']],
            'agency' => ['register' => ['1' => '2500'], 'renew' => ['1' => '2500']],
            'blog' => ['register' => ['1' => '3000'], 'renew' => ['1' => '3000']],
            'biz' => ['register' => ['1' => '1500'], 'renew' => ['1' => '2000']],
            'website' => ['register' => ['1' => '199'], 'renew' => ['1' => '2500']],
            'rocks' => ['register' => ['1' => '1200'], 'renew' => ['1' => '1500']],
            'life' => ['register' => ['1' => '350'], 'renew' => ['1' => '3500']],
            'me' => ['register' => ['1' => '2000'], 'renew' => ['1' => '2500']]
        ];
    }

    $cache = [
        'currency' => $currency,
        'pricing' => $pricing,
        'source' => $html !== false ? 'page' : 'fallback-static',
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

    if (!is_array($pricingEntry)) {
        $rawValue = '2500';
        $currency = ['code' => 'KES', 'prefix' => 'Ksh ', 'suffix' => ''];
        $formatted = trim($currency['prefix'] . $rawValue . $currency['suffix']);
        return ['tld' => $tld, 'raw' => $rawValue, 'formatted' => $formatted, 'currency' => $currency];
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
        $rawValue = '2500';
        $currency = ['code' => 'KES', 'prefix' => 'Ksh ', 'suffix' => ''];
    }

    if ($rawValue === '') {
        $rawValue = '2500';
        $currency = ['code' => 'KES', 'prefix' => 'Ksh ', 'suffix' => ''];
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
