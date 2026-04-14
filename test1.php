<?php
\['query'] = 'supercloud';
\ = [];
require __DIR__ . '/public/pages/whois_ai_domain_search.php';
// We output a file to avoid header issues etc
file_put_contents('test_out1.txt', 'With supercloud: ' . count(\));