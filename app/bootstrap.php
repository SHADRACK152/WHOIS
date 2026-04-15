<?php

declare(strict_types=1);

function whois_load_env_file(): void
{
    static $loaded = false;

    if ($loaded) {
        return;
    }

    $loaded = true;
    $envPath = dirname(__DIR__) . '/.env';

    if (!is_file($envPath)) {
        return;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES);

    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        $separator = strpos($line, '=');

        if ($separator === false) {
            continue;
        }

        $name = trim(substr($line, 0, $separator));
        $value = trim(substr($line, $separator + 1));

        if ($name === '') {
            continue;
        }

        if ($value !== '' && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
            $value = substr($value, 1, -1);
        }

        if (getenv($name) === false) {
            putenv($name . '=' . $value);
        }

        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

whois_load_env_file();

function whois_site_map(): array
{
    return require __DIR__ . '/site-map.php';
}

function whois_public_page_name(string $file): string
{
    $name = strtolower(basename($file));

    if ($name === '') {
        return '';
    }

    return preg_replace('/\.html?$/i', '.php', $name) ?? $name;
}

function whois_page_url(string $file): string
{
    return '/pages/' . whois_public_page_name($file);
}

function whois_current_page(?string $value = null): string
{
    $page = $value ?? ($_GET['current'] ?? '');
    $page = trim((string) $page);

    if ($page === '') {
        return '';
    }

    return whois_public_page_name($page);
}

function whois_json(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');

    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}
