<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/admin-auth.php';

if (whois_admin_is_authenticated()) {
	header('Location: overview.php', true, 302);
} else {
	header('Location: login.php', true, 302);
}

exit;
