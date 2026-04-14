<?php
require 'app/bootstrap.php';
require 'app/db-client.php';
$articles = whois_db_fetch_all('SELECT * FROM articles');
print_r($articles);