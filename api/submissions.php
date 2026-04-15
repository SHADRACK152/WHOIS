<?php

declare(strict_types=1);

require __DIR__ . '/../app/db-client.php';
require_once __DIR__ . '/../app/admin-auth.php';

$rawInput = file_get_contents('php://input');
$payload = [];

if (is_string($rawInput) && trim($rawInput) !== '') {
    $decoded = json_decode($rawInput, true);

    if (is_array($decoded)) {
        $payload = $decoded;
    }
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($method === 'GET') {
    $filters = [];

    if (!empty($_GET['status'])) {
        $filters['status'] = (string) $_GET['status'];
    }

    if (!empty($_GET['domain_name'])) {
        $filters['domain_name'] = (string) $_GET['domain_name'];
    }

    if (isset($_GET['limit'])) {
        $filters['limit'] = (int) $_GET['limit'];
    }

    $submissions = whois_db_list_submissions($filters);

    whois_json([
        'ok' => true,
        'count' => count($submissions),
        'submissions' => $submissions,
    ]);
}

if ($method !== 'POST') {
    whois_json([
        'ok' => false,
        'error' => 'Method not allowed.',
    ], 405);
}

$data = array_merge($_POST, $payload);
$action = strtolower(trim((string) ($data['action'] ?? 'submit')));

try {
    if ($action === 'approve' || $action === 'reject') {
        if (!whois_admin_is_authenticated()) {
            whois_json([
                'ok' => false,
                'error' => 'Admin authentication required.',
            ], 403);
        }
        $submissionId = (int) ($data['submission_id'] ?? 0);

        if ($submissionId <= 0) {
            whois_json([
                'ok' => false,
                'error' => 'Submission id is required.',
            ], 400);
        }

        if ($action === 'approve') {
            $result = whois_db_approve_submission($submissionId);

            if (!$result) {
                whois_json([
                    'ok' => false,
                    'error' => 'Submission not found or database unavailable.',
                ], 404);
            }

            whois_json([
                'ok' => true,
                'submission' => $result['submission'],
                'listing' => $result['listing'],
            ]);
        }

        $submission = whois_db_reject_submission($submissionId);

        if (!$submission) {
            whois_json([
                'ok' => false,
                'error' => 'Submission not found or database unavailable.',
            ], 404);
        }

        whois_json([
            'ok' => true,
            'submission' => $submission,
        ]);
    }

    $domainName = trim((string) ($data['domain_name'] ?? ''));

    if ($domainName === '') {
        whois_json([
            'ok' => false,
            'error' => 'Domain name is required.',
        ], 400);
    }

    $submission = whois_db_save_submission([
        'domain_name' => $domainName,
        'category' => trim((string) ($data['category'] ?? '')),
        'keywords' => trim((string) ($data['keywords'] ?? '')),
        'reserve_price' => trim((string) ($data['reserve_price'] ?? '')),
        'bin_price' => trim((string) ($data['bin_price'] ?? '')),
        'starting_bid' => trim((string) ($data['starting_bid'] ?? '')),
        'auction_type' => trim((string) ($data['auction_type'] ?? '')),
        'duration_days' => trim((string) ($data['duration_days'] ?? '')),
        'start_date' => trim((string) ($data['start_date'] ?? '')),
        'source_page' => 'whois_submit_domain_for_auction',
    ]);

    if (!$submission) {
        whois_json([
            'ok' => false,
            'error' => 'Database connection is not available.',
        ], 503);
    }

    whois_json([
        'ok' => true,
        'submission' => $submission,
    ]);
} catch (Throwable $exception) {
    whois_json([
        'ok' => false,
        'error' => $exception->getMessage(),
    ], 500);
}
