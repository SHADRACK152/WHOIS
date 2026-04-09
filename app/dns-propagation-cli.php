<?php
// CLI script to test connectivity to a DNS resolver using the existing dns-propagation logic
// Usage: php dns-propagation-cli.php <resolver_ip> [domain]

require_once __DIR__ . '/dns-propagation.php';

if ($argc < 2) {
    fwrite(STDERR, "Usage: php dns-propagation-cli.php <resolver_ip> [domain]\n");
    exit(1);
}

$resolver = $argv[1];
$domain = $argv[2] ?? 'example.com';

try {
    // whois_dns_propagation_query($resolver, $domain, $type = 'A', $timeout = 2)
    $result = whois_dns_propagation_query($resolver, $domain, 'A', 2);
    if ($result && isset($result['status']) && $result['status'] === 'ok') {
        echo "SUCCESS: $resolver resolved $domain to: ";
        if (isset($result['answer']) && is_array($result['answer'])) {
            $ips = array_map(fn($a) => $a['ip'] ?? $a['data'] ?? '', $result['answer']);
            echo implode(', ', array_filter($ips));
        } else {
            echo json_encode($result['answer']);
        }
        echo "\n";
    } else {
        echo "FAIL: $resolver did not resolve $domain\n";
    }
} catch (Throwable $e) {
    echo "ERROR: $resolver threw exception: ".$e->getMessage()."\n";
    exit(2);
}
