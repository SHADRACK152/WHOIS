<?php

declare(strict_types=1);

function whois_public_rdap_env(string $name): ?string
{
    $value = getenv($name);

    if ($value === false || $value === '') {
        $value = $_SERVER[$name] ?? '';
    }

    $value = trim((string) $value);

    return $value === '' ? null : $value;
}

function whois_public_rdap_config(): array
{
    return [
        'baseUrl' => whois_public_rdap_env('PUBLIC_RDAP_BASE_URL') ?? 'https://rdap.org',
        'timeout' => max(10, (int) (whois_public_rdap_env('PUBLIC_RDAP_TIMEOUT') ?? '20')),
        'insecureSsl' => in_array(strtolower(whois_public_rdap_env('PUBLIC_RDAP_INSECURE_SSL') ?? whois_public_rdap_env('WHOIS_INSECURE_SSL') ?? ''), ['1', 'true', 'yes', 'on'], true),
    ];
}

function whois_public_rdap_lookup_domain(string $domain): array
{
    $config = whois_public_rdap_config();
    $sourceUrl = rtrim($config['baseUrl'], '/') . '/domain/' . rawurlencode($domain);

    try {
        $response = whois_http_get_json($sourceUrl);
    } catch (Throwable $exception) {
        return whois_domain_lookup_empty_result(
            $domain,
            'unknown',
            'Lookup failed',
            $exception->getMessage(),
            null,
            null
        );
    }

    $statusCode = (int) ($response['statusCode'] ?? 0);
    $body = $response['body'] ?? null;

    if ($statusCode === 404) {
        return whois_domain_lookup_empty_result(
            $domain,
            'unknown',
            'Lookup unavailable',
            'The public RDAP proxy returned no registration record.',
            null,
            null
        );
    }

    if (!is_array($body)) {
        return whois_domain_lookup_empty_result(
            $domain,
            'unknown',
            'Lookup returned no data',
            'The public RDAP response could not be parsed.',
            null,
            null
        );
    }

    $events = is_array($body['events'] ?? null) ? $body['events'] : [];
    $normalizedEvents = whois_rdap_event_list($events);
    $nameservers = [];

    foreach ($body['nameservers'] ?? [] as $nameserver) {
        if (is_array($nameserver) && isset($nameserver['ldhName']) && is_string($nameserver['ldhName'])) {
            $nameservers[] = $nameserver['ldhName'];
        }
    }

    $created = null;
    $updated = null;
    $expiration = null;

    foreach ($events as $event) {
        if (!is_array($event)) {
            continue;
        }

        if (($event['eventAction'] ?? '') === 'registration' && is_string($event['eventDate'] ?? null)) {
            $created = $event['eventDate'];
        }

        if (($event['eventAction'] ?? '') === 'last changed' && is_string($event['eventDate'] ?? null)) {
            $updated = $event['eventDate'];
        }

        $action = strtolower(trim((string) ($event['eventAction'] ?? '')));

        if (in_array($action, ['expiration', 'registry expiry', 'expiry'], true) && is_string($event['eventDate'] ?? null)) {
            $expiration = $event['eventDate'];
        }
    }

    $statuses = [];
    foreach ($body['status'] ?? [] as $status) {
        if (is_string($status)) {
            $status = trim($status);

            if ($status !== '') {
                $statuses[] = $status;
            }
        }
    }
    $statuses = array_values(array_unique($statuses));

    $registrar = null;
    $entities = [];
    foreach ($body['entities'] ?? [] as $entity) {
        if (!is_array($entity)) {
            continue;
        }

        $entities[] = whois_rdap_entity_list([$entity])[0] ?? [];

        $roles = $entity['roles'] ?? [];
        if (is_array($roles) && in_array('registrar', $roles, true)) {
            $registrar = whois_rdap_vcard_field($entity, 'fn');
            if (!is_string($registrar)) {
                $registrar = $entity['handle'] ?? null;
            }
            break;
        }
    }

    $notices = whois_rdap_text_blocks(is_array($body['notices'] ?? null) ? $body['notices'] : []);
    $remarks = whois_rdap_text_blocks(is_array($body['remarks'] ?? null) ? $body['remarks'] : []);
    $links = whois_rdap_links(is_array($body['links'] ?? null) ? $body['links'] : []);
    $secureDns = is_array($body['secureDNS'] ?? null) ? $body['secureDNS'] : null;
    $handle = is_string($body['handle'] ?? null) ? trim((string) $body['handle']) : null;
    $objectClassName = is_string($body['objectClassName'] ?? null) ? trim((string) $body['objectClassName']) : null;
    $port43 = is_string($body['port43'] ?? null) ? trim((string) $body['port43']) : null;

    $registrarIanaId = null;
    foreach ($entities as $entity) {
        if (!is_array($entity)) {
            continue;
        }

        if (in_array('registrar', $entity['roles'] ?? [], true)) {
            foreach ($body['entities'] ?? [] as $rawEntity) {
                if (!is_array($rawEntity)) {
                    continue;
                }

                $roles = $rawEntity['roles'] ?? [];
                if (!is_array($roles) || !in_array('registrar', $roles, true)) {
                    continue;
                }

                if (is_array($rawEntity['publicIds'] ?? null)) {
                    foreach ($rawEntity['publicIds'] as $publicId) {
                        if (!is_array($publicId)) {
                            continue;
                        }

                        if (strtolower((string) ($publicId['type'] ?? '')) === 'iana registrar id') {
                            $registrarIanaId = is_string($publicId['identifier'] ?? null) ? trim((string) $publicId['identifier']) : null;
                            break 2;
                        }
                    }
                }
            }
        }
    }

    $lookup = [
        'domain' => $domain,
        'status' => 'registered',
        'statusLabel' => 'Registered',
        'registrar' => is_string($registrar) ? $registrar : null,
        'registrarIanaId' => $registrarIanaId,
        'handle' => $handle,
        'objectClassName' => $objectClassName,
        'port43' => $port43,
        'statuses' => $statuses,
        'created' => $created,
        'expiration' => $expiration,
        'updated' => $updated,
        'nameservers' => $nameservers,
        'secureDns' => $secureDns,
        'events' => $normalizedEvents,
        'entities' => $entities,
        'notices' => $notices,
        'remarks' => $remarks,
        'links' => $links,
        'availabilityNote' => 'Public RDAP confirms this domain is registered.',
        'publicRdapSource' => $sourceUrl,
        'rawRdap' => $body,
        'rawWhois' => null,
        'lookupSourceLabel' => 'Public RDAP',
    ];

    return $lookup;
}
