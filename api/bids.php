<?php

declare(strict_types=1);

require __DIR__ . '/../app/db-client.php';
require_once __DIR__ . '/../app/admin-auth.php';

$rawInput = file_get_contents('php://input');
$payload = [];

if (is_string($rawInput) && trim($rawInput) !== '') {
    $decoded = json_decode($rawInput, true);

    if (is_array($decoded)) {
        $payload = $decoded;
    }
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($method === 'GET') {
    if (!whois_admin_is_authenticated()) {
        whois_json([
            'ok' => false,
            'error' => 'Admin authentication required.',
        ], 403);
    }

    $domainName = trim((string) ($_GET['domain_name'] ?? ''));
    $limit = (int) ($_GET['limit'] ?? 200);

    if ($domainName === '') {
        whois_json([
            'ok' => false,
            'error' => 'domain_name is required.',
        ], 400);
    }

    $bids = whois_db_list_marketplace_bids($domainName, $limit);

    whois_json([
        'ok' => true,
        'domain_name' => strtolower($domainName),
        'count' => count($bids),
        'bids' => $bids,
    ]);
}

if ($method !== 'POST') {
    whois_json([
        'ok' => false,
        'error' => 'Method not allowed.',
    ], 405);
}

$data = array_merge($_POST, $payload);
$domainName = trim((string) ($data['domain_name'] ?? ''));
$bidAmount = (float) ($data['bid_amount'] ?? 0);

if ($domainName === '' || $bidAmount <= 0) {
    whois_json([
        'ok' => false,
        'error' => 'Domain and bid amount are required.',
    ], 400);
}

$item = whois_db_get_marketplace_item_by_domain($domainName);

if (!$item) {
    whois_json([
        'ok' => false,
        'error' => 'Domain is not listed in the marketplace.',
    ], 404);
}

if (strtolower((string) ($item['status'] ?? 'live')) !== 'live') {
    whois_json([
        'ok' => false,
        'error' => 'This domain has already been marked as sold.',
    ], 409);
}

$result = whois_db_record_marketplace_bid([
    'domain_name' => $domainName,
    'bid_amount' => $bidAmount,
    'bidder_name' => trim((string) ($data['bidder_name'] ?? '')),
    'bidder_email' => trim((string) ($data['bidder_email'] ?? '')),
    'note' => trim((string) ($data['note'] ?? '')),
]);

if (!$result) {
    whois_json([
        'ok' => false,
        'error' => 'Unable to record bid. Ensure the domain exists in the marketplace.',
    ], 404);
}

whois_json([
    'ok' => true,
    'bid' => $result['bid'] ?? null,
    'item' => $result['item'] ?? null,
]);

