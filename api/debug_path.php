<?php
$root = realpath(__DIR__ . '/..');
$requestPath = '/pages/whois_dns_checker.php';
$subPath = substr($requestPath, 7);
$target = $root . '/api/pages/' . $subPath;

echo "Root: " . $root . "\n";
echo "SubPath: " . $subPath . "\n";
echo "Target: " . $target . "\n";
echo "Exists? " . (file_exists($target) ? 'YES' : 'NO') . "\n";

$target_alt = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'whois_dns_checker.php';
echo "Target Alt: " . $target_alt . "\n";
echo "Exists? " . (file_exists($target_alt) ? 'YES' : 'NO') . "\n";
