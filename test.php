<?php
\['query'] = 'supercloud';
require __DIR__ . '/public/pages/whois_ai_domain_search.php';
echo 'Bundle Count (supercloud): ' . count($bundleItems) . PHP_EOL;

\['query'] = 'supercloud.com';
require __DIR__ . '/public/pages/whois_ai_domain_search.php';
echo 'Bundle Count (supercloud.com): ' . count($bundleItems) . PHP_EOL;