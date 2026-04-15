<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$siteMap = whois_site_map();
$groupFilter = strtolower(trim((string) ($_GET['group'] ?? '')));
$query = strtolower(trim((string) ($_GET['q'] ?? '')));

$pages = array_map(static function (array $page): array {
    return [
        'label' => $page['label'],
        'file' => whois_public_page_name($page['file']),
        'url' => whois_page_url($page['file']),
        'group' => $page['group'],
        'summary' => $page['summary'],
    ];
}, $siteMap['pages']);

if ($groupFilter !== '') {
    $pages = array_values(array_filter($pages, static function (array $page) use ($groupFilter): bool {
        return strtolower($page['group']) === $groupFilter;
    }));
}

if ($query !== '') {
    $pages = array_values(array_filter($pages, static function (array $page) use ($query): bool {
        return str_contains(strtolower($page['label']), $query) || str_contains(strtolower($page['file']), $query) || str_contains(strtolower($page['summary']), $query);
    }));
}

whois_json([
    'count' => count($pages),
    'pages' => $pages,
]);

