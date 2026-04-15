<?php
/**
 * Consolidated Local Development Router 
 * Supports the new API-centric directory structure.
 */
declare(strict_types=1);

// Debug: Log the incoming request to the server console
error_log("Router handling request: " . ($_SERVER['REQUEST_URI'] ?? '/'));

$root = dirname(__DIR__); // More robust than realpath(..)/..
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestPath = '/' . ltrim($requestPath, '/');

// 1. Resolve Static Files (assets, etc.)
// These should stay in the /public/ folder
$publicFile = $root . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $requestPath);
if (is_file($publicFile) && !str_ends_with($publicFile, '.php')) {
    serve_static($publicFile);
}

// 2. Identify Target Script
$target = null;

if ($requestPath === '/' || $requestPath === '/index.php') {
    $target = $root . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'home.php';
} elseif (str_starts_with($requestPath, '/pages/')) {
    $subPath = substr($requestPath, 7); 
    if (empty($subPath)) $subPath = 'index.php';
    if (!str_ends_with($subPath, '.php')) $subPath .= '.php';
    $target = $root . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . $subPath;
} elseif (str_starts_with($requestPath, '/admin/')) {
    $subPath = substr($requestPath, 7);
    if (empty($subPath)) $subPath = 'index.php';
    if (!str_ends_with($subPath, '.php')) $subPath .= '.php';
    $target = $root . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $subPath;
} elseif (str_starts_with($requestPath, '/api/')) {
    $subPath = substr($requestPath, 5);
    if (!str_ends_with($subPath, '.php')) $subPath .= '.php';
    $target = $root . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $subPath;
}

// 3. Last Resort Fallback (for paths like /whois.php if they were moved to root api)
if (!$target || !is_file($target)) {
    $maybeInApi = $root . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . ltrim($requestPath, '/');
    if (is_file($maybeInApi)) {
        $target = $maybeInApi;
    }
}

// 4. Final Processing
if ($target && is_file($target)) {
    // Debug: Trace successful target resolution
    header("X-Router-Target: " . basename($target));
    require $target;
    exit;
}

// 5. 404 Response
http_response_code(404);
header("X-Router-Error: Target not found");
echo "404 - Not Found (Local Router Debug Mode)<br>";
echo "Requested: " . htmlspecialchars($requestPath) . "<br>";
if ($target) echo "Target attempted: " . htmlspecialchars($target);
exit;

function serve_static(string $path): void {
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $types = [
        'css'  => 'text/css; charset=utf-8',
        'js'   => 'application/javascript; charset=utf-8',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'json' => 'application/json; charset=utf-8',
        'webp' => 'image/webp',
    ];
    header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
    readfile($path);
    exit;
}