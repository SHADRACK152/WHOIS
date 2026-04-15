<?php

declare(strict_types=1);

$publicRoot = realpath(__DIR__ . '/../public');

if ($publicRoot === false) {
    http_response_code(500);
    echo 'Public directory unavailable.';
    exit;
}

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($requestPath === false || $requestPath === null || $requestPath === '') {
    $requestPath = '/';
}

$requestPath = '/' . ltrim($requestPath, '/');

if ($requestPath === '/' || $requestPath === '/index.php') {
    $targetPath = $publicRoot . DIRECTORY_SEPARATOR . 'index.php';
} else {
    $targetPath = $publicRoot . str_replace('/', DIRECTORY_SEPARATOR, $requestPath);
}

if (is_dir($targetPath) && is_file($targetPath . DIRECTORY_SEPARATOR . 'index.php')) {
    $targetPath .= DIRECTORY_SEPARATOR . 'index.php';
}

$resolvedTarget = realpath($targetPath);

if ($resolvedTarget === false || (strncmp($resolvedTarget, $publicRoot . DIRECTORY_SEPARATOR, strlen($publicRoot) + 1) !== 0 && $resolvedTarget !== $publicRoot)) {
    http_response_code(404);
    echo 'Not found.';
    exit;
}

if (is_file($resolvedTarget) && strtolower(pathinfo($resolvedTarget, PATHINFO_EXTENSION)) === 'php') {
    require $resolvedTarget;
    exit;
}

if (!is_file($resolvedTarget)) {
    http_response_code(404);
    echo 'Not found.';
    exit;
}

$contentType = function_exists('mime_content_type') ? mime_content_type($resolvedTarget) : false;

if (!is_string($contentType) || $contentType === '') {
    $contentType = match (strtolower(pathinfo($resolvedTarget, PATHINFO_EXTENSION))) {
        'css' => 'text/css; charset=utf-8',
        'gif' => 'image/gif',
        'htm', 'html' => 'text/html; charset=utf-8',
        'ico' => 'image/x-icon',
        'jpeg', 'jpg' => 'image/jpeg',
        'js' => 'application/javascript; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        default => 'application/octet-stream',
    };
}

header('Content-Type: ' . $contentType);
readfile($resolvedTarget);