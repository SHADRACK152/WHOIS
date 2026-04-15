<?php

declare(strict_types=1);

require_once __DIR__ . '/domain-lookup.php';
require_once __DIR__ . '/domain-research-client.php';
require_once __DIR__ . '/currency.php';

function whois_premium_market_candidate_domains(string $searchDomain): array
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

    $baseRoots = array_values(array_unique(array_filter([
        $searchStem,
        preg_replace('/s$/', '', $searchStem) ?: $searchStem,
        $searchStem . 'labs',
        $searchStem . 'hq',
        $searchStem . 'cloud',
        $searchStem . 'studio',
        $searchStem . 'group',
        $searchStem . 'app',
        $searchStem . 'data',
    ])));

    $tlds = ['com', 'ai', 'io', 'co', 'net'];
    $candidates = [];

    foreach ($baseRoots as $baseRoot) {
        foreach ($tlds as $tld) {
            $candidates[] = $baseRoot . '.' . $tld;
        }
    }

    return array_values(array_unique($candidates));
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

function whois_premium_market_status_details(string $status, array $offers = []): array
{
    $status = strtolower(trim($status));

    if (str_contains($status, 'priced')) {
        return [
            'state' => 'priced',
            'label' => 'Priced',
        ];
    }

    if (str_contains($status, 'marketed')) {
        return [
            'state' => 'marketed',
            'label' => 'Marketed',
        ];
    }

    if (str_contains($status, 'premium')) {
        return [
            'state' => 'premium',
            'label' => 'Verified premium',
        ];
    }

    if ($offers !== []) {
        return [
            'state' => 'offer',
            'label' => 'Offer available',
        ];
    }

    return [
        'state' => 'verified',
        'label' => 'Verified premium',
    ];
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
    $status = trim((string) ($listing['status'] ?? 'Verified premium'));
    $statusLabel = trim((string) ($listing['statusLabel'] ?? ''));
    $state = trim((string) ($listing['state'] ?? ''));
    $offerAmount = whois_premium_market_amount($listing['offerPrice'] ?? ($listing['askPrice'] ?? ($listing['ask'] ?? null)));
    $appraisalAmount = whois_premium_market_amount($listing['appraisal'] ?? $offerAmount);
    $askAmount = whois_premium_market_amount($listing['askPrice'] ?? $offerAmount);
    $statusDetails = whois_premium_market_status_details($status);

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
        'status' => $status !== '' ? $status : 'Verified premium',
        'statusLabel' => $statusLabel !== '' ? $statusLabel : $statusDetails['label'],
        'state' => $state !== '' ? $state : $statusDetails['state'],
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
    $displayCurrency = whois_currency_normalize_code($displayCurrency ?? (string) ($context['currency'] ?? 'USD'), 'USD');

    $currency = 'USD';
    $listings = [];

    foreach (whois_premium_market_candidate_domains($searchDomain) as $candidateDomain) {
        $research = whois_domainr_status($candidateDomain);

        if (!($research['ok'] ?? false) || !($research['isPremium'] ?? false)) {
            continue;
        }

        $offer = is_array($research['offers'][0] ?? null) ? $research['offers'][0] : null;
        $offerPrice = whois_premium_market_amount($offer['price'] ?? null);
        $offerCurrency = strtoupper(trim((string) ($offer['currency'] ?? 'USD')));

        if ($offerPrice === null) {
            continue;
        }

        $candidate = [
            'domain' => $candidateDomain,
            'category' => strtoupper(trim((string) ($research['status'] ?? 'Verified Premium'))),
            'reason' => trim((string) (is_array($research['record'] ?? null) ? ($research['record']['summary'] ?? '') : '')),
            'status' => strtoupper(trim((string) ($research['status'] ?? 'premium'))),
            'statusLabel' => whois_premium_market_status_details((string) ($research['status'] ?? ''), $research['offers'] ?? [])['label'],
            'state' => whois_premium_market_status_details((string) ($research['status'] ?? ''), $research['offers'] ?? [])['state'],
            'appraisal' => $offerPrice,
            'askPrice' => $offerPrice,
        ];

        if ($candidate['reason'] === '') {
            $vendor = trim((string) ($offer['vendor'] ?? 'Domainr'));

            $candidate['reason'] = $vendor !== ''
                ? 'Verified premium status with an offer from ' . $vendor . '.'
                : 'Verified premium status with an active offer.';
        }

        $normalized = whois_premium_market_normalize_listing($candidate, $candidateDomain, $offerCurrency, $displayCurrency);

        if ($normalized !== null) {
            $normalized['status'] = strtoupper(trim((string) ($research['status'] ?? $normalized['status'])));
            $normalized['statusLabel'] = whois_premium_market_status_details((string) ($research['status'] ?? ''), $research['offers'] ?? [])['label'];
            $normalized['state'] = whois_premium_market_status_details((string) ($research['status'] ?? ''), $research['offers'] ?? [])['state'];
            $listings[] = $normalized;
        }
    }

    if ($listings === []) {
        return [
            'ok' => false,
            'workflow' => 'premium_search',
            'market' => 'Verified Premium Offers',
            'currency' => $displayCurrency,
            'listings' => [],
            'error' => 'No verified premium offers were returned. Set DOMAINR_RAPIDAPI_KEY to enable premium-status checks.',
        ];
    }

    return [
        'ok' => $listings !== [],
        'workflow' => 'premium_search',
        'market' => 'Verified Premium Offers',
        'currency' => $displayCurrency,
        'sourceCurrency' => 'USD',
        'listings' => $listings,
    ];
}
