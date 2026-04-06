<?php

declare(strict_types=1);

function whois_currency_normalize_code(?string $currency, string $default = 'KES'): string
{
    $code = strtoupper(trim((string) $currency));

    if (in_array($code, ['KES', 'USD'], true)) {
        return $code;
    }

    $fallback = strtoupper(trim($default));

    return in_array($fallback, ['KES', 'USD'], true) ? $fallback : 'KES';
}

function whois_currency_symbol(string $currency): string
{
    return whois_currency_normalize_code($currency, 'KES') === 'KES' ? 'Ksh' : '$';
}

function whois_currency_fallback_rate(string $fromCurrency, string $toCurrency): float
{
    $fromCurrency = whois_currency_normalize_code($fromCurrency, 'KES');
    $toCurrency = whois_currency_normalize_code($toCurrency, 'USD');
    $envRate = getenv('WHOIS_KES_USD_RATE');

    if ($envRate !== false && $envRate !== '' && is_numeric($envRate) && (float) $envRate > 0) {
        $kesToUsd = (float) $envRate;

        if ($fromCurrency === 'KES' && $toCurrency === 'USD') {
            return $kesToUsd;
        }

        if ($fromCurrency === 'USD' && $toCurrency === 'KES') {
            return 1 / $kesToUsd;
        }
    }

    if ($fromCurrency === 'KES' && $toCurrency === 'USD') {
        return 0.0077;
    }

    if ($fromCurrency === 'USD' && $toCurrency === 'KES') {
        return 130.0;
    }

    return 1.0;
}

function whois_currency_exchange_rate(string $fromCurrency, string $toCurrency): float
{
    $fromCurrency = whois_currency_normalize_code($fromCurrency, 'KES');
    $toCurrency = whois_currency_normalize_code($toCurrency, 'USD');

    if ($fromCurrency === $toCurrency) {
        return 1.0;
    }

    static $cache = [];
    $cacheKey = $fromCurrency . '->' . $toCurrency;

    if (array_key_exists($cacheKey, $cache)) {
        return $cache[$cacheKey];
    }

    try {
        $response = whois_http_get_json('https://open.er-api.com/v6/latest/' . rawurlencode($fromCurrency));
        $rates = is_array($response['body']['rates'] ?? null) ? $response['body']['rates'] : [];

        if (isset($rates[$toCurrency]) && is_numeric($rates[$toCurrency]) && (float) $rates[$toCurrency] > 0) {
            $cache[$cacheKey] = (float) $rates[$toCurrency];

            return $cache[$cacheKey];
        }
    } catch (Throwable $exception) {
    }

    $cache[$cacheKey] = whois_currency_fallback_rate($fromCurrency, $toCurrency);

    return $cache[$cacheKey];
}

function whois_currency_convert_amount(float $amount, string $fromCurrency, string $toCurrency): float
{
    if ($amount === 0.0) {
        return 0.0;
    }

    $rate = whois_currency_exchange_rate($fromCurrency, $toCurrency);

    return $amount * $rate;
}

function whois_currency_format_amount(float $amount, string $currency): string
{
    $currency = whois_currency_normalize_code($currency, 'KES');

    return whois_currency_symbol($currency) . number_format($amount, 2, '.', ',');
}
