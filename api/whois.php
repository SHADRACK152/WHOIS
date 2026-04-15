<?php
declare(strict_types=1);
ini_set('max_execution_time', 0);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/domain-lookup.php';

$input = trim((string) ($_GET['domain'] ?? $_GET['query'] ?? $_GET['q'] ?? ''));

if ($input === '') {
    whois_json([
        'ok' => false,
        'error' => 'Domain name is required.',
    ], 400);
}

$lookup = null;
try {
    $lookup = whois_domain_lookup_cached($input);
} catch (Throwable $exception) {
    whois_json([
        'ok' => false,
        'error' => 'Domain lookup failed: ' . $exception->getMessage(),
    ], 500);
}
$domain = (string) ($lookup['domain'] ?? whois_domain_normalize($input));
$root = $domain !== '' ? (preg_replace('/\.[^.]+$/', '', $domain) ?? $domain) : '';
$stem = preg_replace('/[^a-z0-9]/', '', strtolower($root)) ?? '';

if ($stem === '') {
    $stem = 'brand';
}

$preferredTlds = ['com', 'net', 'io', 'ai', 'app', 'dev', 'co', 'xyz', 'site', 'online', 'music', 'shop', 'grey'];
$supportedTlds = array_flip(whois_rdap_supported_tlds());
$candidateTlds = [];

foreach ($preferredTlds as $tld) {
    if (isset($supportedTlds[$tld])) {
        $candidateTlds[] = $tld;
    }
}

if ($candidateTlds === []) {
    $candidateTlds = array_slice(whois_rdap_supported_tlds(), 0, 8);
}

$alternatives = [];

foreach (whois_domain_candidate_domains($stem, $candidateTlds) as $candidateDomain) {
    $candidateLookup = whois_domain_lookup_cached($candidateDomain);
    $candidateStatus = (string) ($candidateLookup['status'] ?? 'unknown');

    $alternatives[] = [
        'domain' => $candidateDomain,
        'status' => $candidateStatus,
        'statusLabel' => whois_domain_lookup_badge($candidateLookup),
        'summary' => whois_domain_lookup_summary($candidateLookup),
        'available' => $candidateStatus === 'available',
    ];
}

$nameservers = [];

foreach (array_slice(is_array($lookup['nameservers'] ?? null) ? $lookup['nameservers'] : [], 0, 6) as $nameserver) {
    if (is_string($nameserver) && trim($nameserver) !== '') {
        $nameservers[] = $nameserver;
    }
}

$lookupStatus = (string) ($lookup['status'] ?? 'unknown');
$availabilityHeadline = $lookupStatus === 'available'
    ? 'Available'
    : ($lookupStatus === 'registered' || $lookupStatus === 'unavailable' ? 'Taken' : 'Unknown');

$entities = is_array($lookup['entities'] ?? null) ? $lookup['entities'] : [];
$events = is_array($lookup['events'] ?? null) ? $lookup['events'] : [];
$notices = is_array($lookup['notices'] ?? null) ? $lookup['notices'] : [];
$remarks = is_array($lookup['remarks'] ?? null) ? $lookup['remarks'] : [];
$links = is_array($lookup['links'] ?? null) ? $lookup['links'] : [];
$secureDns = is_array($lookup['secureDns'] ?? null) ? $lookup['secureDns'] : null;
$statuses = is_array($lookup['statuses'] ?? null) ? $lookup['statuses'] : [];
$rawRdap = $lookup['rawRdap'] ?? null;
$updatedRelative = whois_rdap_relative_time_label($lookup['updated'] ?? null);
$eventRows = [];

foreach ($events as $event) {
    if (!is_array($event)) {
        continue;
    }

    $eventRows[] = [
        'action' => is_string($event['eventAction'] ?? null) ? trim((string) $event['eventAction']) : (is_string($event['action'] ?? null) ? trim((string) $event['action']) : ''),
        'date' => is_string($event['eventDate'] ?? null) ? trim((string) $event['eventDate']) : (is_string($event['date'] ?? null) ? trim((string) $event['date']) : ''),
        'actor' => is_string($event['eventActor'] ?? null) ? trim((string) $event['eventActor']) : (is_string($event['actor'] ?? null) ? trim((string) $event['actor']) : ''),
    ];
}

$registrarEntity = whois_rdap_find_entity_by_role($entities, 'registrar');
$abuseEntity = whois_rdap_find_entity_by_role($entities, 'abuse');
$registrantEntity = whois_rdap_find_entity_by_role($entities, 'registrant');
$administrativeEntity = whois_rdap_find_entity_by_role($entities, 'administrative');
$technicalEntity = whois_rdap_find_entity_by_role($entities, 'technical');

$buildContactCard = static function (?array $entity, string $label) use ($lookupStatus): array {
    if (!is_array($entity) || $entity === []) {
        $isRedacted = $lookupStatus === 'registered';

        return [
            'label' => $label,
            'redacted' => $isRedacted,
            'hasData' => false,
            'name' => $isRedacted ? 'Redacted for privacy' : 'Not available',
            'street' => '',
            'city' => '',
            'state' => '',
            'postalCode' => '',
            'country' => '',
            'phone' => '',
            'email' => '',
        ];
    }

    return [
        'label' => $label,
        'redacted' => false,
        'hasData' => true,
        'name' => (($entity['name'] ?? '') !== '') ? (string) $entity['name'] : 'Not listed',
        'street' => $entity['street'] ?? '',
        'city' => $entity['city'] ?? '',
        'state' => $entity['state'] ?? '',
        'postalCode' => $entity['postalCode'] ?? '',
        'country' => $entity['country'] ?? '',
        'phone' => $entity['phone'] ?? '',
        'email' => $entity['email'] ?? '',
    ];
};

$registrantCard = $buildContactCard($registrantEntity, 'Registrant Contact');
$administrativeCard = $buildContactCard($administrativeEntity, 'Administrative Contact');
$technicalCard = $buildContactCard($technicalEntity, 'Technical Contact');

$contactStateForCard = static function (array $card, string $fallback): string {
    if (($card['hasData'] ?? false) === true) {
        return 'Available';
    }

    return $fallback;
};

$registrarInformation = [
    'registrar' => $registrarEntity['name'] ?? ($lookup['registrar'] ?? null),
    'ianaId' => $lookup['registrarIanaId'] ?? null,
    'url' => $registrarEntity['url'] ?? null,
    'email' => $registrarEntity['email'] ?? null,
    'abuseEmail' => is_array($abuseEntity) ? ($abuseEntity['email'] ?? null) : null,
    'abusePhone' => is_array($abuseEntity) ? ($abuseEntity['phone'] ?? null) : null,
];

$contactState = $lookupStatus === 'registered'
    ? 'REDACTED FOR PRIVACY'
    : 'Not available';

whois_json([
    'ok' => true,
    'domain' => $domain,
    'summary' => whois_domain_lookup_summary($lookup),
    'availabilityHeadline' => $availabilityHeadline,
    'profile' => [
        'updatedRelative' => $updatedRelative,
        'domainInformation' => [
            'domain' => $domain,
            'registeredOn' => whois_rdap_date_only($lookup['created'] ?? null),
            'expiresOn' => whois_rdap_date_only($lookup['expiration'] ?? null),
            'updatedOn' => whois_rdap_date_only($lookup['updated'] ?? null),
            'status' => $lookupStatus,
            'statusLabel' => whois_domain_lookup_badge($lookup),
            'statuses' => $statuses,
            'nameServers' => $nameservers,
        ],
        'registrarInformation' => $registrarInformation,
        'contacts' => [
            'registrant' => $registrantCard,
            'administrative' => $administrativeCard,
            'technical' => $technicalCard,
        ],
    ],
    'lookup' => [
        'domain' => $domain,
        'status' => $lookupStatus,
        'statusLabel' => whois_domain_lookup_badge($lookup),
        'registrar' => $lookup['registrar'] ?? null,
        'registrarIanaId' => $lookup['registrarIanaId'] ?? null,
        'handle' => $lookup['handle'] ?? null,
        'objectClassName' => $lookup['objectClassName'] ?? null,
        'port43' => $lookup['port43'] ?? null,
        'statuses' => $statuses,
        'created' => $lookup['created'] ?? null,
        'expiration' => $lookup['expiration'] ?? null,
        'updated' => $lookup['updated'] ?? null,
        'nameservers' => $nameservers,
        'secureDns' => $secureDns,
        'events' => $events,
        'entities' => $entities,
        'notices' => $notices,
        'remarks' => $remarks,
        'links' => $links,
        'availabilityNote' => $lookup['availabilityNote'] ?? null,
        'rdapSource' => $lookup['rdapSource'] ?? null,
        'whoisSource' => $lookup['whoisSource'] ?? null,
        'publicRdapSource' => $lookup['publicRdapSource'] ?? null,
        'lookupSourceLabel' => $lookup['lookupSourceLabel'] ?? null,
        'eventRows' => $eventRows,
        'rawRdap' => $rawRdap,
        'rawWhois' => $lookup['rawWhois'] ?? null,
    ],
    'contacts' => [
        'registrant' => $contactStateForCard($registrantCard, $contactState),
        'administrative' => $contactStateForCard($administrativeCard, $contactState),
        'technical' => $contactStateForCard($technicalCard, $contactState),
        'entities' => $entities,
    ],
    'metrics' => [
        'supportedTlds' => count(whois_rdap_supported_tlds()),
        'checkedExtensions' => count($candidateTlds),
        'availableAlternatives' => count(array_filter($alternatives, static fn (array $candidate): bool => $candidate['available'] === true)),
        'statusCount' => count($statuses),
        'contactCount' => count($entities),
        'eventCount' => count($events),
        'noticeCount' => count($notices),
        'remarkCount' => count($remarks),
    ],
    'alternatives' => $alternatives,
    'rawRdap' => $rawRdap,
    'rawWhois' => $lookup['rawWhois'] ?? null,
]);