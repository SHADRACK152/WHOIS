<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/admin-auth.php';

whois_admin_logout();

header('Location: login.php', true, 302);
exit;