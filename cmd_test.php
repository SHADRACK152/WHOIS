<?php
require __DIR__ . '/app/domain-lookup.php';
$tlds = ['com', 'net', 'org', 'co', 'io', 'ai', 'store', 'online', 'site', 'tech', 'app', 'shop', 'xyz'];
foreach($tlds as $tld) {
    echo $tld . ': ' . whois_domain_lookup_cached('supercloud.' . $tld)['status'] . PHP_EOL;
}