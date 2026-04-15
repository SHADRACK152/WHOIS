<?php
// Minimal JSON API for domain appraisal, for AJAX use

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/domain-appraisal.php';

$domain = trim((string) ($_GET['domain'] ?? ''));
if ($domain === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing domain parameter']);
    exit;
}

$currency = whois_currency_normalize_code((string) ($_GET['currency'] ?? 'USD'), 'USD');

try {
    $appraisal = whois_domain_appraisal_analyze($domain, $currency);
    // Only return the fields needed for the landing page widget
    echo json_encode([
        'domain' => $appraisal['domain'],
        'root' => $appraisal['root'],
        'score' => $appraisal['score'],
        'estimatedValue' => $appraisal['estimatedValue'],
        'displayCurrency' => $appraisal['displayCurrency'],
        'lookup' => $appraisal['lookup'],
        'ai_price' => $appraisal['ai_price'] ?? null,
        'ai_insight' => !empty($appraisal['ai_insight']) ? $appraisal['ai_insight'] : ($appraisal['aiInsight']['summary'] ?? null),
        'ai_confidence' => $appraisal['ai_confidence'] ?? null,
        'ai_tags' => $appraisal['ai_tags'] ?? [],
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Appraisal failed', 'details' => $e->getMessage()]);
}

