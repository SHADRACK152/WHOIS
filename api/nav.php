<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

$siteMap = whois_site_map();
$current = whois_current_page($_GET['current'] ?? null);

$primaryNav = array_map(static function (array $item) use ($current): array {
    $file = whois_public_page_name($item['file']);

    return [
        'label' => $item['label'],
        'file' => $file,
        'url' => whois_page_url($item['file']),
        'group' => $item['group'],
        'active' => $current !== '' && $current === $file,
    ];
}, $siteMap['primaryNav']);

$exploreGroups = array_map(static function (array $group) use ($current): array {
    $pages = array_map(static function (array $page) use ($current): array {
        $file = whois_public_page_name($page['file']);

        return [
            'label' => $page['label'],
            'file' => $file,
            'url' => whois_page_url($page['file']),
            'active' => $current !== '' && $current === $file,
        ];
    }, $group['pages']);

    return [
        'label' => $group['label'],
        'summary' => $group['summary'],
        'pages' => $pages,
    ];
}, $siteMap['exploreGroups']);

whois_json([
    'brand' => [
        'name' => $siteMap['brand']['name'],
        'shortName' => $siteMap['brand']['shortName'],
        'landingPage' => whois_public_page_name($siteMap['brand']['landingPage']),
    ],
    'current' => $current,
    'primaryNav' => $primaryNav,
    'exploreGroups' => $exploreGroups,
]);
