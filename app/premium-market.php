<?php

declare(strict_types=1);

require_once __DIR__ . '/domain-lookup.php';
require_once __DIR__ . '/currency.php';
require_once __DIR__ . '/grok-client.php';

function whois_premium_market_prompt(string $searchDomain, string $searchStem): string
{
    return <<<PROMPT
Return strict JSON only. No markdown, no commentary, no code fences.

Create 4 premium aftermarket domain listings inspired by "{$searchDomain}" and the brand root "{$searchStem}".
Use realistic marketplace pricing, not retail registration pricing.
Prefer short, memorable names with .com, .ai, .io, .co, or .net.

Schema:
{
  "market": "Premium Marketplace",
  "currency": "USD",
  "listings": [
    {
      "domain": "example.com",
      "category": "Brandable",
      "appraisal": 24500,
      "askPrice": 18900,
      "status": "Available now",
      "reason": "Short rationale for why the name is premium."
    }
  ]
}
PROMPT;
}

function whois_premium_market_extract_json(string $output): ?array
{
    $trimmed = trim($output);

    if ($trimmed === '') {
        return null;
    }

    $decoded = json_decode($trimmed, true);

    if (is_array($decoded)) {
        return $decoded;
    }

    if (preg_match('/```(?:json)?\s*(.*?)\s*```/is', $trimmed, $matches) === 1) {
        $decoded = json_decode(trim($matches[1]), true);

        if (is_array($decoded)) {
            return $decoded;
        }
    }

    $start = strpos($trimmed, '{');
    $end = strrpos($trimmed, '}');

    if ($start !== false && $end !== false && $end > $start) {
        $decoded = json_decode(substr($trimmed, $start, $end - $start + 1), true);

        if (is_array($decoded)) {
            return $decoded;
        }
    }

    return null;
}

function whois_premium_market_amount(mixed $value): ?float
{
    if (is_int($value) || is_float($value)) {
        return (float) $value;
    }

    if (!is_string($value)) {
        return null;
    }

    $clean = trim(str_replace([',', '$', 'USD', 'KES'], '', strtoupper($value)));

    if ($clean === '') {
        return null;
    }

    if (is_numeric($clean)) {
        return (float) $clean;
    }

    if (preg_match('/(-?\d[\d,]*(?:\.\d+)?)/', $value, $matches) === 1) {
        return (float) str_replace(',', '', $matches[1]);
    }

    return null;
}

function whois_premium_market_format_money(float $amount, string $currency): string
{
    return whois_currency_format_amount($amount, $currency);
}

function whois_premium_market_normalize_domain(string $value, string $fallbackDomain): string
{
    $domain = strtolower(trim($value));

    if ($domain === '') {
        $domain = strtolower(trim($fallbackDomain));
    }

    $domain = preg_replace('#^https?://#', '', $domain) ?? $domain;
    $domain = preg_replace('#/.*$#', '', $domain) ?? $domain;
    $domain = preg_replace('/\s+/', '', $domain) ?? $domain;

    if ($domain === '') {
        return '';
    }

    if (strpos($domain, '.') === false) {
        $domain .= '.com';
    }

    return $domain;
}

function whois_premium_market_normalize_listing(array $listing, string $fallbackDomain, string $sourceCurrency, string $displayCurrency): ?array
{
    $domain = whois_premium_market_normalize_domain((string) ($listing['domain'] ?? ''), $fallbackDomain);
    $category = trim((string) ($listing['category'] ?? 'Premium'));
    $reason = trim((string) ($listing['reason'] ?? ''));
    $status = trim((string) ($listing['status'] ?? 'Available now'));
    $appraisalAmount = whois_premium_market_amount($listing['appraisal'] ?? null);
    $askAmount = whois_premium_market_amount($listing['askPrice'] ?? ($listing['ask'] ?? null));

    if ($domain === '' || $reason === '' || $appraisalAmount === null || $askAmount === null) {
        return null;
    }

    $sourceCurrency = whois_currency_normalize_code($sourceCurrency, 'USD');
    $displayCurrency = whois_currency_normalize_code($displayCurrency, 'USD');
    $displayAppraisalAmount = whois_currency_convert_amount($appraisalAmount, $sourceCurrency, $displayCurrency);
    $displayAskAmount = whois_currency_convert_amount($askAmount, $sourceCurrency, $displayCurrency);

    return [
        'domain' => $domain,
        'category' => $category !== '' ? $category : 'Premium',
        'reason' => $reason,
        'status' => $status !== '' ? $status : 'Available now',
        'currency' => $displayCurrency,
        'sourceCurrency' => $sourceCurrency,
        'appraisalAmount' => $displayAppraisalAmount,
        'askAmount' => $displayAskAmount,
        'appraisal' => whois_premium_market_format_money($displayAppraisalAmount, $displayCurrency),
        'ask' => whois_premium_market_format_money($displayAskAmount, $displayCurrency),
    ];
}

function whois_premium_market_listings(string $searchDomain, array $context = [], ?string $displayCurrency = null): array
{
    $domain = whois_domain_normalize($searchDomain);

    if ($domain === '') {
        $domain = 'brand.com';
    }

    $searchRoot = preg_replace('/\.[^.]+$/', '', $domain) ?? $domain;
    $searchStem = preg_replace('/[^a-z0-9]/', '', $searchRoot) ?? '';

    if ($searchStem === '') {
        $searchStem = 'brand';
    }

    $displayCurrency = whois_currency_normalize_code($displayCurrency ?? (string) ($context['currency'] ?? 'USD'), 'USD');

    $prompt = whois_premium_market_prompt($domain, $searchStem);

    try {
        $response = whois_ai_request('premium_search', $prompt, array_merge([
            'domain' => $domain,
            'searchStem' => $searchStem,
        ], $context));
    } catch (Throwable $exception) {
        return [
            'ok' => false,
            'workflow' => 'premium_search',
            'market' => 'Premium Marketplace',
            'currency' => 'USD',
            'listings' => [],
            'error' => $exception->getMessage(),
        ];
    }

    $payload = whois_premium_market_extract_json((string) ($response['output'] ?? ''));

    if (!is_array($payload)) {
        return [
            'ok' => false,
            'workflow' => 'premium_search',
            'market' => 'Premium Marketplace',
            'currency' => 'USD',
            'listings' => [],
            'error' => 'Premium search returned an unstructured response.',
            'raw' => $response['output'] ?? null,
        ];
    }

    $currency = strtoupper(trim((string) ($payload['currency'] ?? 'USD')));
    $listings = [];
    $items = is_array($payload['listings'] ?? null) ? $payload['listings'] : [];

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $normalized = whois_premium_market_normalize_listing($item, $searchStem . '.com', $currency, $displayCurrency);

        if ($normalized !== null) {
            $listings[] = $normalized;
        }
    }

    return [
        'ok' => $listings !== [],
        'workflow' => 'premium_search',
        'market' => is_string($payload['market'] ?? null) && trim((string) $payload['market']) !== '' ? trim((string) $payload['market']) : 'Premium Marketplace',
        'currency' => $displayCurrency,
        'sourceCurrency' => $currency !== '' ? $currency : 'USD',
        'listings' => $listings,
        'usage' => $response['usage'] ?? null,
    ];
}
