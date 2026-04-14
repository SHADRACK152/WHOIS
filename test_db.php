<?php
require 'app/bootstrap.php';
require 'app/db-client.php';
require 'app/db-schema.php';
$db = whois_db_get_connection();
whois_db_initialize($db);
echo 'DB Migrated';