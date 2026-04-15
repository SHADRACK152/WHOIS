<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/dns-checker-nodes.php';
require __DIR__ . '/../../app/dns-propagation.php';

$rawDomainInput = strtolower(trim((string) ($_GET['domain'] ?? '')));
$domain = $rawDomainInput;
$type = strtoupper(trim((string) ($_GET['type'] ?? 'A')));
$ipFamily = strtolower(trim((string) ($_GET['ipFamily'] ?? 'all')));
$continent = strtolower(trim((string) ($_GET['continent'] ?? 'all')));
$country = strtolower(trim((string) ($_GET['country'] ?? 'all')));
$markerIdsRaw = trim((string) ($_GET['markerIds'] ?? ''));
$allowedTypes = ['A', 'AAAA', 'CNAME', 'MX', 'NS', 'TXT', 'PTR', 'SRV', 'SOA', 'CAA', 'DS', 'DNSKEY'];

if ($domain === '') {
    whois_json([
        'ok' => false,
        'error' => 'Domain is required.',
    ], 400);
}

$domain = preg_replace('/^https?:\/\//i', '', $domain) ?? $domain;
$domain = explode('/', $domain)[0] ?? $domain;
$domain = explode(':', $domain)[0] ?? $domain;
$domain = trim($domain, '.');

$domainIsStructurallyValid = preg_match('/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/i', $domain) === 1;
$tldCheck = strrchr($domain, '.');
$tld = $tldCheck === false ? '' : ltrim(strtolower($tldCheck), '.');
$domainHasPoisonedTld = $tld !== '' && strpos($tld, 'http') !== false;

if ($domain === '' || !$domainIsStructurallyValid || $domainHasPoisonedTld) {
    if (preg_match_all('/((?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63})/i', $rawDomainInput, $m) > 0) {
        foreach ((array) ($m[1] ?? []) as $candidate) {
            $candidate = strtolower((string) $candidate);
            $parts = explode('.', $candidate);
            $tld = (string) end($parts);

            if ($candidate === '' || $tld === '') {
                continue;
            }

            if (strpos($tld, 'http') !== false) {
                continue;
            }

            if (strlen($tld) < 2 || strlen($tld) > 24) {
                continue;
            }

            $domain = $candidate;
            break;
        }
    }
}

if ($domain === '' || preg_match('/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/i', $domain) !== 1) {
    whois_json([
        'ok' => false,
        'error' => 'Please enter a valid domain like example.com.',
    ], 400);
}

if (!in_array($type, $allowedTypes, true)) {
    $type = 'A';
}

$nodes = whois_dns_checker_nodes();

if ($ipFamily === 'ipv4') {
    $nodes = array_values(array_filter($nodes, static fn (array $node): bool => strpos((string) ($node['resolver'] ?? ''), ':') === false));
} elseif ($ipFamily === 'ipv6') {
    $nodes = array_values(array_filter($nodes, static fn (array $node): bool => strpos((string) ($node['resolver'] ?? ''), ':') !== false));
}

if ($continent !== '' && $continent !== 'all') {
    $nodes = array_values(array_filter(
        $nodes,
        static fn (array $node): bool => strtolower((string) ($node['continent'] ?? '')) === $continent
    ));
}

if ($country !== '' && $country !== 'all') {
    $nodes = array_values(array_filter(
        $nodes,
        static fn (array $node): bool => strtolower((string) ($node['country'] ?? '')) === $country
    ));
}


if ($markerIdsRaw !== '') {
    $ids = array_values(array_filter(array_map('trim', explode(',', $markerIdsRaw)), static fn (string $id): bool => $id !== ''));
    // Limit batch size to 32 for safety
    if (count($ids) > 32) {
        whois_json([
            'ok' => false,
            'error' => 'Too many markerIds in one batch (max 32).',
        ], 400);
    }
    $idLookup = [];
    foreach ($ids as $id) {
        $idLookup[$id] = true;
    }
    $nodes = array_values(array_filter(
        $nodes,
        static fn (array $node): bool => isset($idLookup[(string) ($node['markerId'] ?? '')])
    ));
    if (count($nodes) === 0) {
        whois_json([
            'ok' => false,
            'error' => 'No valid resolvers found for the given markerIds.',
        ], 400);
    }
}

$results = whois_dns_propagation_check($domain, $type, $nodes);
$resolvedCount = count(array_filter($results, static fn (array $row): bool => $row['resolved'] === true));
$total = count($results);
$checkedAt = gmdate('c');

whois_json([
    'ok' => true,
    'domain' => $domain,
    'type' => $type,
    'ipFamily' => $ipFamily,
    'continent' => $continent,
    'country' => $country,
    'checkedAt' => $checkedAt,
    'summary' => [
        'resolved' => $resolvedCount,
        'total' => $total,
        'percentage' => $total > 0 ? round(($resolvedCount / $total) * 100, 2) : 0,
    ],
    'results' => $results,
]);
