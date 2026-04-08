<?php

declare(strict_types=1);

require __DIR__ . '/../../app/db-client.php';
require_once __DIR__ . '/../../app/admin-auth.php';

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($method === 'GET') {
    $filters = [
        'status' => 'live',
        'extension' => $_GET['extension'] ?? '',
        'max_price' => $_GET['max_price'] ?? null,
        'query' => $_GET['q'] ?? '',
    ];

    $items = whois_db_list_marketplace_items($filters);

    whois_json([
        'ok' => true,
        'count' => count($items),
        'items' => $items,
    ]);
}

if ($method !== 'POST') {
    whois_json([
        'ok' => false,
        'error' => 'Method not allowed.',
    ], 405);
}

if (!whois_admin_is_authenticated()) {
    whois_json([
        'ok' => false,
        'error' => 'Admin authentication required.',
    ], 403);
}

$rawInput = file_get_contents('php://input');
$payload = [];

if (is_string($rawInput) && trim($rawInput) !== '') {
    $decoded = json_decode($rawInput, true);

    if (is_array($decoded)) {
        $payload = $decoded;
    }
}

$data = array_merge($_POST, $payload);
$action = strtolower(trim((string) ($data['action'] ?? '')));

if ($action !== 'mark_sold') {
    whois_json([
        'ok' => false,
        'error' => 'Unsupported action.',
    ], 400);
}

$itemId = (int) ($data['item_id'] ?? 0);

if ($itemId <= 0) {
    whois_json([
        'ok' => false,
        'error' => 'Item id is required.',
    ], 400);
}

$soldPrice = (float) ($data['sold_price'] ?? 0);
$buyerName = trim((string) ($data['buyer_name'] ?? ''));
$buyerEmail = trim((string) ($data['buyer_email'] ?? ''));

if ($soldPrice <= 0) {
    whois_json([
        'ok' => false,
        'error' => 'Final sale price is required.',
    ], 400);
}

if ($buyerName === '' || $buyerEmail === '') {
    whois_json([
        'ok' => false,
        'error' => 'Buyer name and email are required.',
    ], 400);
}

if (!filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
    whois_json([
        'ok' => false,
        'error' => 'Buyer email must be valid.',
    ], 400);
}

$existingItem = whois_db_fetch_one('SELECT * FROM marketplace_items WHERE id = :id', ['id' => $itemId]);

if (!$existingItem) {
    whois_json([
        'ok' => false,
        'error' => 'Marketplace item not found.',
    ], 404);
}

if (strtolower((string) ($existingItem['status'] ?? 'live')) !== 'live') {
    whois_json([
        'ok' => false,
        'error' => 'This domain is already marked as sold.',
    ], 409);
}

$item = whois_db_mark_marketplace_item_sold($itemId, [
    'sold_price' => $soldPrice,
    'buyer_name' => $buyerName,
    'buyer_email' => $buyerEmail,
    'search_text' => (string) ($existingItem['search_text'] ?? ''),
]);

if (!$item) {
    whois_json([
        'ok' => false,
        'error' => 'Marketplace item not found.',
    ], 404);
}

whois_json([
    'ok' => true,
    'item' => $item,
]);