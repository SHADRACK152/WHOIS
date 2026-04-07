<?php

declare(strict_types=1);

require __DIR__ . '/../../app/db-client.php';

$filters = [
    'status' => 'live',
    'extension' => $_GET['extension'] ?? '',
    'max_price' => $_GET['max_price'] ?? null,
    'query' => $_GET['q'] ?? '',
];

whois_json([
    'ok' => true,
    'count' => 0,
    'items' => whois_db_list_marketplace_items($filters),
]);