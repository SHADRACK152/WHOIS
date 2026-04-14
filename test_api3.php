<?php
require_once 'app/bootstrap.php';
require_once 'app/db-client.php';
$articles = whois_db_fetch_all('SELECT id, title, slug FROM articles');
print_r($articles);