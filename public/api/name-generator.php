<?php

declare(strict_types=1);

require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/grok-client.php';
require __DIR__ . '/../../app/truehost-client.php';
require __DIR__ . '/../../app/currency.php';

$rawInput = file_get_contents('php://input');
$payload = [];

if (is_string($rawInput) && trim($rawInput) !== '') {
    $decoded = json_decode($rawInput, true);

    if (is_array($decoded)) {
        $payload = $decoded;
    }
}

$description = trim((string) ($payload['description'] ?? ($_POST['description'] ?? '')));
$requestedLimit = (int) ($payload['limit'] ?? 12);
$limit = max(8, min(15, $requestedLimit));
$currency = whois_currency_normalize_code((string) ($payload['currency'] ?? 'USD'), 'USD');

if ($description === '') {
    whois_json([
        'ok' => false,
        'error' => 'Business description is required.',
    ], 400);
}

function whois_ai_name_generator_checkout_url(string $domain): string
{
    $config = whois_truehost_config();
    $endpoint = (string) ($config['endpoint'] ?? '');

    if ($endpoint === '') {
        return '#';
    }

    $parts = parse_url($endpoint);

    if (!is_array($parts) || !isset($parts['host'])) {
        return '#';
    }

    $scheme = isset($parts['scheme']) && is_string($parts['scheme']) && $parts['scheme'] !== ''
        ? $parts['scheme']
        : 'https';

    $host = (string) $parts['host'];
    $port = isset($parts['port']) ? ':' . (int) $parts['port'] : '';
    $path = (string) ($parts['path'] ?? '');
    $cloudPrefix = str_contains($path, '/cloud/') ? '/cloud' : '';

    return $scheme . '://' . $host . $port . $cloudPrefix . '/cart.php?a=add&domain=register&query=' . rawurlencode($domain);
}

function whois_ai_name_generator_extract_names(string $text): array
{
    $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];
    $names = [];

    foreach ($lines as $line) {
        if (!is_string($line)) {
            continue;
        }

        $candidate = trim($line);

        if ($candidate === '') {
            continue;
        }

        $candidate = preg_replace('/^\s*(?:[-*•]|\d+[\.)])\s*/', '', $candidate) ?? $candidate;
        $candidate = preg_replace('/\s*\([^)]*\)\s*$/', '', $candidate) ?? $candidate;
        $candidate = preg_replace('/\s*[-:–].*$/', '', $candidate) ?? $candidate;
        $candidate = trim($candidate, " \t\n\r\0\x0B\"'");

        if ($candidate === '') {
            continue;
        }

        if (preg_match('/^[A-Za-z][A-Za-z0-9 -]{1,22}$/', $candidate) !== 1) {
            continue;
        }

        $names[] = $candidate;
    }

    return array_values(array_unique($names));
}

function whois_ai_name_generator_slug(string $name): string
{
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]/', '', $slug) ?? '';

    if ($slug === '') {
        return '';
    }

    return substr($slug, 0, 24);
}

try {
    $instruction = 'Generate 15 short and brandable business names for this idea: ' . $description
        . '. Return only the names, one per line, no numbering, no punctuation, no explanations.';

    $ai = whois_ai_request('domain_name_generator', $instruction, []);
    $rawNames = whois_ai_name_generator_extract_names((string) ($ai['output'] ?? ''));

    if ($rawNames === []) {
        throw new RuntimeException('No usable names were generated.');
    }

    $tldSequence = ['com', 'shop', 'online', 'co', 'net', 'ai'];
    $items = [];

    foreach ($rawNames as $index => $rawName) {
        if (count($items) >= $limit) {
            break;
        }

        $slug = whois_ai_name_generator_slug($rawName);

        if ($slug === '') {
            continue;
        }

        $tld = $tldSequence[$index % count($tldSequence)];
        $domain = $slug . '.' . $tld;
        $lookup = whois_truehost_domain_lookup($domain);
        $status = strtolower((string) ($lookup['status'] ?? 'unknown'));
        $priceData = whois_truehost_tld_price($tld);

        $price = 'Price unavailable';

        if (is_array($priceData) && isset($priceData['raw']) && is_numeric($priceData['raw'])) {
            $converted = whois_currency_convert_amount((float) $priceData['raw'], 'KES', $currency);
            $price = whois_currency_format_amount($converted, $currency);
        }

        $purchasable = $status === 'available';

        $items[] = [
            'name' => $rawName,
            'domain' => $domain,
            'status' => $status,
            'price' => $price,
            'purchasable' => $purchasable,
            'purchaseUrl' => $purchasable ? whois_ai_name_generator_checkout_url($domain) : null,
        ];
    }

    whois_json([
        'ok' => true,
        'workflow' => 'name_generator',
        'items' => $items,
    ]);
} catch (Throwable $exception) {
    whois_json([
        'ok' => false,
        'error' => $exception->getMessage(),
    ], 502);
}
