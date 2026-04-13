<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/grok-client.php';

$rawInput = file_get_contents('php://input');
$payload = [];

if (is_string($rawInput) && trim($rawInput) !== '') {
    $decoded = json_decode($rawInput, true);

    if (is_array($decoded)) {
        $payload = $decoded;
    }
}

$workflow = whois_current_page((string) ($payload['workflow'] ?? ($_GET['workflow'] ?? '')));
$input = trim((string) ($payload['input'] ?? ($_GET['input'] ?? '')));
$context = is_array($payload['context'] ?? null) ? $payload['context'] : [];

if ($workflow === '') {
    whois_json([
        'ok' => false,
        'error' => 'A workflow is required.',
    ], 400);
}

if ($input === '') {
    whois_json([
        'ok' => false,
        'error' => 'An input prompt is required.',
    ], 400);
}

try {
    $result = whois_ai_request($workflow, $input, $context);
    whois_json($result);
} catch (Throwable $exception) {
    whois_json([
        'ok' => false,
        'workflow' => $workflow,
        'error' => $exception->getMessage(),
    ], 502);
}