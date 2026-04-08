<?php

declare(strict_types=1);

function whois_dns_type_code(string $type): int
{
    return match (strtoupper($type)) {
        'A' => 1,
        'NS' => 2,
        'CNAME' => 5,
        'SOA' => 6,
        'PTR' => 12,
        'MX' => 15,
        'TXT' => 16,
        'SRV' => 33,
        'DS' => 43,
        'RRSIG' => 46,
        'DNSKEY' => 48,
        'CAA' => 257,
        'AAAA' => 28,
        default => 1,
    };
}

function whois_dns_type_label(int $typeCode): string
{
    return match ($typeCode) {
        1 => 'A',
        2 => 'NS',
        5 => 'CNAME',
        6 => 'SOA',
        12 => 'PTR',
        15 => 'MX',
        16 => 'TXT',
        28 => 'AAAA',
        33 => 'SRV',
        43 => 'DS',
        46 => 'RRSIG',
        48 => 'DNSKEY',
        257 => 'CAA',
        default => 'OTHER',
    };
}

function whois_dns_encode_name(string $domain): string
{
    $labels = explode('.', trim($domain, '.'));
    $encoded = '';

    foreach ($labels as $label) {
        $len = strlen($label);

        if ($len === 0 || $len > 63) {
            return '';
        }

        $encoded .= chr($len) . $label;
    }

    return $encoded . "\0";
}

function whois_dns_read_name(string $packet, int &$offset): string
{
    $labels = [];
    $jumped = false;
    $cursor = $offset;
    $maxJumps = 20;
    $jumps = 0;

    while (true) {
        if (!isset($packet[$cursor])) {
            break;
        }

        $len = ord($packet[$cursor]);

        if (($len & 0xC0) === 0xC0) {
            if (!isset($packet[$cursor + 1])) {
                break;
            }

            $ptr = (($len & 0x3F) << 8) | ord($packet[$cursor + 1]);

            if (!$jumped) {
                $offset = $cursor + 2;
            }

            $cursor = $ptr;
            $jumped = true;
            $jumps++;

            if ($jumps > $maxJumps) {
                break;
            }

            continue;
        }

        $cursor++;

        if ($len === 0) {
            if (!$jumped) {
                $offset = $cursor;
            }

            break;
        }

        $labels[] = substr($packet, $cursor, $len);
        $cursor += $len;
    }

    return implode('.', $labels);
}

function whois_dns_query_resolver(string $resolverIp, string $domain, string $type): array
{
    $typeCode = whois_dns_type_code($type);
    $qname = whois_dns_encode_name($domain);

    if ($qname === '') {
        return ['ok' => false, 'error' => 'Invalid domain labels'];
    }

    $id = random_int(0, 65535);
    $header = pack('nnnnnn', $id, 0x0100, 1, 0, 0, 0);
    $question = $qname . pack('nn', $typeCode, 1);
    $packet = $header . $question;

    $warnings = [];
    $handler = static function (int $severity, string $message) use (&$warnings): bool {
        $warnings[] = $message;

        return true;
    };

    set_error_handler($handler);

    try {
        $socket = stream_socket_client(
            'udp://' . $resolverIp . ':53',
            $errno,
            $errstr,
            0.6,
            STREAM_CLIENT_CONNECT
        );
    } finally {
        restore_error_handler();
    }

    if (!is_resource($socket)) {
        $message = $errstr !== '' ? $errstr : (isset($warnings[0]) ? $warnings[0] : 'Socket connection failed');

        return ['ok' => false, 'error' => $message];
    }

    stream_set_timeout($socket, 0, 600000);
    fwrite($socket, $packet);
    $response = fread($socket, 4096);
    $meta = stream_get_meta_data($socket);
    fclose($socket);

    if (!is_string($response) || $response === '') {
        return ['ok' => false, 'error' => 'No response'];
    }

    if (!empty($meta['timed_out'])) {
        return ['ok' => false, 'error' => 'Timeout'];
    }

    if (strlen($response) < 12) {
        return ['ok' => false, 'error' => 'Short DNS response'];
    }

    $headerParts = unpack('nid/nflags/nqdcount/nancount/nnscount/narcount', substr($response, 0, 12));

    if (!is_array($headerParts)) {
        return ['ok' => false, 'error' => 'Malformed DNS header'];
    }

    $flags = (int) ($headerParts['flags'] ?? 0);
    $rcode = $flags & 0x000F;
    $ancount = (int) ($headerParts['ancount'] ?? 0);
    $qdcount = (int) ($headerParts['qdcount'] ?? 0);

    $offset = 12;

    for ($i = 0; $i < $qdcount; $i++) {
        whois_dns_read_name($response, $offset);
        $offset += 4;

        if ($offset > strlen($response)) {
            return ['ok' => false, 'error' => 'Malformed question section'];
        }
    }

    $answers = [];

    for ($i = 0; $i < $ancount; $i++) {
        whois_dns_read_name($response, $offset);

        if ($offset + 10 > strlen($response)) {
            break;
        }

        $rr = unpack('ntype/nclass/Nttl/nrdlength', substr($response, $offset, 10));
        $offset += 10;

        if (!is_array($rr)) {
            break;
        }

        $rdLength = (int) ($rr['rdlength'] ?? 0);

        if ($offset + $rdLength > strlen($response)) {
            break;
        }

        $rdataOffset = $offset;
        $rdata = substr($response, $offset, $rdLength);
        $offset += $rdLength;

        $rrType = (int) ($rr['type'] ?? 0);
        $value = '';

        if ($rrType === 1 && strlen($rdata) === 4) {
            $value = inet_ntop($rdata) ?: '';
        } elseif ($rrType === 28 && strlen($rdata) === 16) {
            $value = inet_ntop($rdata) ?: '';
        } elseif ($rrType === 2 || $rrType === 5 || $rrType === 12) {
            $tmpOffset = $rdataOffset;
            $value = whois_dns_read_name($response, $tmpOffset);
        } elseif ($rrType === 6) {
            $tmpOffset = $rdataOffset;
            $mName = whois_dns_read_name($response, $tmpOffset);
            $rName = whois_dns_read_name($response, $tmpOffset);

            if ($tmpOffset + 20 <= strlen($response)) {
                $soa = unpack('Nserial/Nrefresh/Nretry/Nexpire/Nminimum', substr($response, $tmpOffset, 20));
                $serial = (int) ($soa['serial'] ?? 0);
                $value = $mName . ' ' . $rName . ' serial=' . $serial;
            } else {
                $value = $mName . ' ' . $rName;
            }
        } elseif ($rrType === 15 && strlen($rdata) >= 2) {
            $tmpOffset = $rdataOffset + 2;
            $exchange = whois_dns_read_name($response, $tmpOffset);
            $preference = unpack('npreference', substr($rdata, 0, 2));
            $value = (string) ((int) ($preference['preference'] ?? 0)) . ' ' . $exchange;
        } elseif ($rrType === 33 && strlen($rdata) >= 6) {
            $srv = unpack('npriority/nweight/nport', substr($rdata, 0, 6));
            $tmpOffset = $rdataOffset + 6;
            $target = whois_dns_read_name($response, $tmpOffset);
            $value = 'pri=' . (int) ($srv['priority'] ?? 0)
                . ' w=' . (int) ($srv['weight'] ?? 0)
                . ' port=' . (int) ($srv['port'] ?? 0)
                . ' ' . $target;
        } elseif ($rrType === 43 && strlen($rdata) >= 4) {
            $ds = unpack('nkeyTag/Calgorithm/CdigestType', substr($rdata, 0, 4));
            $digest = bin2hex(substr($rdata, 4));
            $value = 'keyTag=' . (int) ($ds['keyTag'] ?? 0)
                . ' alg=' . (int) ($ds['algorithm'] ?? 0)
                . ' digestType=' . (int) ($ds['digestType'] ?? 0)
                . ' ' . strtoupper(substr($digest, 0, 32));
        } elseif ($rrType === 48 && strlen($rdata) >= 4) {
            $dnskey = unpack('nflags/Calgorithm/Cprotocol', substr($rdata, 0, 4));
            $keyData = base64_encode(substr($rdata, 4));
            $value = 'flags=' . (int) ($dnskey['flags'] ?? 0)
                . ' alg=' . (int) ($dnskey['algorithm'] ?? 0)
                . ' key=' . substr($keyData ?: '', 0, 28) . '...';
        } elseif ($rrType === 257 && strlen($rdata) >= 2) {
            $flag = ord($rdata[0]);
            $tagLen = ord($rdata[1]);
            $tag = substr($rdata, 2, $tagLen);
            $caaValue = substr($rdata, 2 + $tagLen);
            $value = 'flag=' . $flag . ' ' . $tag . ' ' . $caaValue;
        } elseif ($rrType === 16 && $rdLength > 0) {
            $txtParts = [];
            $cursor = 0;

            while ($cursor < $rdLength) {
                $partLen = ord($rdata[$cursor]);
                $cursor++;

                if ($partLen === 0) {
                    continue;
                }

                $txtParts[] = substr($rdata, $cursor, $partLen);
                $cursor += $partLen;
            }

            $value = implode(' ', $txtParts);
        }

        $answers[] = [
            'type' => whois_dns_type_label($rrType),
            'ttl' => (int) ($rr['ttl'] ?? 0),
            'value' => $value,
        ];
    }

    $resolved = $rcode === 0 && $ancount > 0;

    return [
        'ok' => true,
        'resolved' => $resolved,
        'rcode' => $rcode,
        'answerCount' => $ancount,
        'answers' => array_slice($answers, 0, 5),
    ];
}

function whois_dns_propagation_check(string $domain, string $type, array $nodes): array
{
    $results = [];

    foreach ($nodes as $node) {
        $resolverIp = (string) ($node['resolver'] ?? '');

        if ($resolverIp === '') {
            continue;
        }

        $query = whois_dns_query_resolver($resolverIp, $domain, $type);

        $results[] = [
            'markerId' => (string) ($node['markerId'] ?? ''),
            'country' => (string) ($node['country'] ?? ''),
            'location' => (string) ($node['location'] ?? ''),
            'provider' => (string) ($node['provider'] ?? ''),
            'resolver' => $resolverIp,
            'ok' => (bool) ($query['ok'] ?? false),
            'resolved' => (bool) ($query['resolved'] ?? false),
            'rcode' => (int) ($query['rcode'] ?? -1),
            'answerCount' => (int) ($query['answerCount'] ?? 0),
            'answers' => is_array($query['answers'] ?? null) ? $query['answers'] : [],
            'error' => (string) ($query['error'] ?? ''),
        ];
    }

    return $results;
}
