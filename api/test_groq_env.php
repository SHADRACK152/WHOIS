<?php
// Simple test for Groq API key and environment loading
require_once __DIR__ . '/../app/bootstrap.php';

$key = getenv('GROQ_API_KEY');
if (!$key) {
    echo "GROQ_API_KEY not found in environment.\n";
    exit(1);
}
echo "GROQ_API_KEY loaded: " . substr($key, 0, 8) . "...\n";

// Try a simple POST to Groq API if possible
$url = getenv('GROQ_BASE_URL') . '/chat/completions';
$model = getenv('GROQ_MODEL') ?: 'llama-3.3-70b-versatile';
$payload = [
    'model' => $model,
    'messages' => [
        ['role' => 'system', 'content' => 'You are a test system.'],
        ['role' => 'user', 'content' => 'Say hello!'],
    ],
    'temperature' => 0.1,
    'max_tokens' => 20,
];

$options = [
    'http' => [
        'method' => 'POST',
        'header' => [
            'Authorization: Bearer ' . $key,
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        'content' => json_encode($payload),
        'timeout' => 20,
        'ignore_errors' => true,
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "Failed to contact Groq API.\n";
    exit(2);
}

$data = json_decode($response, true);
if (!is_array($data)) {
    echo "Groq API did not return valid JSON.\n";
    echo $response . "\n";
    exit(3);
}

if (isset($data['choices'][0]['message']['content'])) {
    echo "Groq API test success: " . $data['choices'][0]['message']['content'] . "\n";
    exit(0);
}

print_r($data);
echo "\nGroq API test completed, but no message content found.\n";
exit(4);

