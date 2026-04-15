<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/db-client.php';

// Rate limiting: max 10 contact submissions per IP per hour
function whois_contact_check_rate_limit(string $ip): bool
{
    // Simplified: allow all if no DB (you can add Redis/file-based later)
    return true;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    whois_json(['ok' => false, 'error' => 'Method not allowed.'], 405);
}

$raw = file_get_contents('php://input');
$data = is_string($raw) ? json_decode($raw, true) : null;

if (!is_array($data)) {
    whois_json(['ok' => false, 'error' => 'Invalid request body.'], 400);
}

$name    = trim((string) ($data['name'] ?? ''));
$email   = trim((string) ($data['email'] ?? ''));
$subject = trim((string) ($data['subject'] ?? 'General inquiry'));
$message = trim((string) ($data['message'] ?? ''));
$type    = trim((string) ($data['type'] ?? 'contact'));    // contact | partner | broker

if ($name === '' || strlen($name) > 120) {
    whois_json(['ok' => false, 'error' => 'Name is required (max 120 characters).'], 422);
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    whois_json(['ok' => false, 'error' => 'A valid email address is required.'], 422);
}

if ($message === '' || strlen($message) > 4000) {
    whois_json(['ok' => false, 'error' => 'Message is required (max 4,000 characters).'], 422);
}

$allowedTypes = ['contact', 'partner', 'broker'];
if (!in_array($type, $allowedTypes, true)) {
    $type = 'contact';
}

// Try to store in DB
$stored = false;
if (whois_db_is_available()) {
    try {
        whois_db_execute(
            "INSERT INTO contact_submissions (name, email, subject, message, type, ip_address, created_at)
             VALUES (:name, :email, :subject, :message, :type, :ip, NOW())",
            [
                'name'    => $name,
                'email'   => $email,
                'subject' => $subject !== '' ? $subject : 'General inquiry',
                'message' => $message,
                'type'    => $type,
                'ip'      => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            ]
        );
        $stored = true;
    } catch (Throwable $e) {
        // Table might not exist yet — log silently and continue
        error_log('contact_submissions insert error: ' . $e->getMessage());
    }
}

whois_json([
    'ok'      => true,
    'stored'  => $stored,
    'message' => 'Thank you ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! We received your message and will get back to you soon.',
]);
