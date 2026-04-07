<?php

declare(strict_types=1);

require_once __DIR__ . '/domain-lookup.php';
require_once __DIR__ . '/currency.php';

function whois_domainr_env(string $name): ?string
{
    $value = getenv($name);

    if ($value === false || $value === '') {
        $value = $_SERVER[$name] ?? '';
    }

    $value = trim((string) $value);

    return $value === '' ? null : $value;
}

function whois_domainr_config(): array
{
    return [
        'apiKey' => whois_domainr_env('DOMAINR_RAPIDAPI_KEY') ?? whois_domainr_env('DOMAINR_API_KEY'),
        'host' => whois_domainr_env('DOMAINR_RAPIDAPI_HOST') ?? 'domainr.p.rapidapi.com',
        'timeout' => max(8, (int) (whois_domainr_env('DOMAINR_TIMEOUT') ?? '15')),
        'insecureSsl' => in_array(strtolower(whois_domainr_env('DOMAINR_INSECURE_SSL') ?? whois_domainr_env('WHOIS_INSECURE_SSL') ?? ''), ['1', 'true', 'yes', 'on'], true),
    ];
}

function whois_domainr_amount(mixed $value): ?float
{
    if (is_int($value) || is_float($value)) {
        return (float) $value;
    }

    if (!is_string($value)) {
        return null;
    }

    if (preg_match('/(-?\d[\d,]*(?:\.\d+)?)/', $value, $matches) !== 1) {
        return null;
    }

    return (float) str_replace(',', '', $matches[1]);
}

function whois_domainr_status(string $domain): array
{
    $config = whois_domainr_config();
    $domain = whois_domain_normalize($domain);

    if ($domain === '') {
        return [
            'ok' => false,
            'domain' => '',
            'status' => 'unknown',
            'isPremium' => false,
            'offers' => [],
            'error' => 'A domain is required.',
        ];
    }

    if ($config['apiKey'] === null) {
        return [
            'ok' => false,
            'domain' => $domain,
            'status' => 'unknown',
            'isPremium' => false,
            'offers' => [],
            'error' => 'DOMAINR_RAPIDAPI_KEY is not configured.',
        ];
    }

    $url = 'https://' . $config['host'] . '/v2/status?domain=' . rawurlencode($domain);

    $headers = [
        'X-RapidAPI-Key: ' . $config['apiKey'],
        'X-RapidAPI-Host: ' . $config['host'],
        'Accept: application/json',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0 Safari/537.36',
    ];

    $responseBody = false;
    $statusCode = 0;

    if (function_exists('curl_init')) {
        $handle = curl_init($url);

        if ($handle !== false) {
            curl_setopt_array($handle, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPGET => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => $config['timeout'],
                CURLOPT_CONNECTTIMEOUT => min(10, $config['timeout']),
                CURLOPT_SSL_VERIFYPEER => !$config['insecureSsl'],
                CURLOPT_SSL_VERIFYHOST => $config['insecureSsl'] ? 0 : 2,
            ]);

            $responseBody = curl_exec($handle);

            if ($responseBody !== false) {
                $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
            }
        }
    }

    if (!is_string($responseBody) || trim($responseBody) === '') {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
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

        $responseBody = file_get_contents($url, false, $context);

        if ($responseBody === false) {
            return [
                'ok' => false,
                'domain' => $domain,
                'status' => 'unknown',
                'isPremium' => false,
                'offers' => [],
                'error' => 'Domainr request failed.',
            ];
        }

        $statusCode = 0;

        foreach ($http_response_header ?? [] as $headerLine) {
            if (preg_match('/^HTTP\/\d(?:\.\d)?\s+(\d{3})\b/', $headerLine, $matches) === 1) {
                $statusCode = (int) $matches[1];
                break;
            }
        }
    }

    $decoded = json_decode((string) $responseBody, true);

    if (!is_array($decoded)) {
        return [
            'ok' => false,
            'domain' => $domain,
            'status' => 'unknown',
            'isPremium' => false,
            'offers' => [],
            'error' => 'Domainr returned an invalid response.',
        ];
    }

    if ($statusCode >= 400) {
        return [
            'ok' => false,
            'domain' => $domain,
            'status' => 'unknown',
            'isPremium' => false,
            'offers' => [],
            'error' => (string) ($decoded['message'] ?? $decoded['error'] ?? ('Domainr request failed with status ' . $statusCode . '.')),
        ];
    }

    $records = [];

    if (is_array($decoded['status'] ?? null)) {
        $records = $decoded['status'];
    } elseif (is_array($decoded['results'] ?? null)) {
        $records = $decoded['results'];
    }

    $record = null;

    foreach ($records as $candidate) {
        if (!is_array($candidate)) {
            continue;
        }

        if (strcasecmp((string) ($candidate['domain'] ?? ''), $domain) === 0) {
            $record = $candidate;
            break;
        }

        if ($record === null) {
            $record = $candidate;
        }
    }

    if (!is_array($record)) {
        return [
            'ok' => false,
            'domain' => $domain,
            'status' => 'unknown',
            'isPremium' => false,
            'offers' => [],
            'error' => 'Domainr returned no status record for this domain.',
        ];
    }

    $status = strtolower(trim((string) ($record['status'] ?? 'unknown')));
    $tags = strtolower(trim((string) ($record['tags'] ?? '')));
    $offers = [];

    if (is_array($record['offers'] ?? null)) {
        foreach ($record['offers'] as $offer) {
            if (!is_array($offer)) {
                continue;
            }

            $offerPrice = whois_domainr_amount($offer['price'] ?? null);

            if ($offerPrice === null) {
                continue;
            }

            $offers[] = [
                'currency' => strtoupper(trim((string) ($offer['currency'] ?? 'USD'))),
                'price' => $offerPrice,
                'vendor' => trim((string) ($offer['vendor'] ?? '')),
            ];
        }
    }

    $isPremium = str_contains($status, 'premium') || str_contains($status, 'priced') || str_contains($status, 'marketed') || $offers !== [];

    return [
        'ok' => true,
        'domain' => $domain,
        'status' => $status !== '' ? $status : 'unknown',
        'tags' => $tags !== '' ? $tags : null,
        'isPremium' => $isPremium,
        'offers' => $offers,
        'record' => $record,
    ];
}