<?php

declare(strict_types=1);

require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/grok-client.php';
require __DIR__ . '/../../app/currency.php';
require __DIR__ . '/../../app/premium-market.php';

$rawInput = file_get_contents('php://input');
$payload = [];

if (is_string($rawInput) && trim($rawInput) !== '') {
    $decoded = json_decode($rawInput, true);

    if (is_array($decoded)) {
        $payload = $decoded;
    }
}

$query = trim((string) ($payload['query'] ?? ($_GET['query'] ?? '')));
$currency = whois_currency_normalize_code((string) ($payload['currency'] ?? ($_GET['currency'] ?? 'USD')), 'USD');

if ($query === '') {
    whois_json([
        'ok' => false,
        'error' => 'A query is required.',
    ], 400);
}

try {
    $result = whois_premium_market_listings($query, [
        'query' => $query,
        'currency' => $currency,
    ], $currency);

    whois_json($result);
} catch (Throwable $exception) {
    whois_json([
        'ok' => false,
        'error' => $exception->getMessage(),
    ], 502);
}