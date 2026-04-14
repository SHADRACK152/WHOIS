<?php
require_once 'app/bootstrap.php';
require_once 'app/db-client.php';

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'action' => 'save',
    'id' => 0,
    'title' => 'API Test Title',
    'content' => 'API Test Content',
    'status' => 'published'
];

ob_start();
require 'public/api/articles.php';
$out = ob_get_clean();
echo "OUT: $out";

$articles = whois_db_fetch_all('SELECT id, title FROM articles');
print_r($articles);