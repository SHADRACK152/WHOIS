<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function whois_admin_session_start(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $cookieParams = session_get_cookie_params();
        
        // Improved HTTPS detection for proxies/Vercel
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || $_SERVER['SERVER_PORT'] == 443
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        session_set_cookie_params([
            'lifetime' => $cookieParams['lifetime'] ?: 86400, // Default to 24h if 0
            'path' => $cookieParams['path'] ?: '/',
            'domain' => $cookieParams['domain'],
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        
        if (!session_start()) {
             error_log("Failed to start WHOIS admin session.");
        }
    }
}

function whois_admin_credentials(): array
{
    return [
        'username' => (string) (getenv('ADMIN_USERNAME') ?: 'admin'),
        'password' => (string) (getenv('ADMIN_PASSWORD') ?: 'whois-admin-2026'),
    ];
}

function whois_admin_is_authenticated(): bool
{
    whois_admin_session_start();

    return !empty($_SESSION['whois_admin_authenticated']);
}

function whois_admin_attempt_login(string $username, string $password): bool
{
    whois_admin_session_start();

    $credentials = whois_admin_credentials();
    $usernameMatches = hash_equals($credentials['username'], $username);
    $passwordMatches = hash_equals($credentials['password'], $password);

    if (!$usernameMatches || !$passwordMatches) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['whois_admin_authenticated'] = true;
    $_SESSION['whois_admin_username'] = $credentials['username'];

    return true;
}

function whois_admin_require_auth(): void
{
    if (!whois_admin_is_authenticated()) {
        header('Location: login.php', true, 302);
        exit;
    }
}

function whois_admin_logout(): void
{
    whois_admin_session_start();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}