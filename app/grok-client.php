<?php

declare(strict_types=1);

function whois_ai_env(string $name): ?string
{
    $value = getenv($name);

    if ($value === false || $value === '') {
        $value = $_SERVER[$name] ?? '';
    }

    $value = trim((string) $value);

    return $value === '' ? null : $value;
}

function whois_ai_config(): array
{
    $timeout = (int) (whois_ai_env('GROQ_TIMEOUT') ?? '45');
    $insecureSsl = in_array(strtolower(whois_ai_env('GROQ_INSECURE_SSL') ?? ''), ['1', 'true', 'yes', 'on'], true);

    return [
        'apiKey' => whois_ai_env('GROQ_API_KEY'),
        'baseUrl' => rtrim(whois_ai_env('GROQ_BASE_URL') ?? 'https://api.groq.com/openai/v1', '/'),
        'model' => whois_ai_env('GROQ_MODEL') ?? 'llama-3.3-70b-versatile',
        'timeout' => $timeout > 0 ? $timeout : 45,
        'insecureSsl' => $insecureSsl,
    ];
}

function whois_ai_workflows(): array
{
    return [
        'domain_search' => [
            'label' => 'Domain Search',
            'temperature' => 0.25,
            'maxTokens' => 700,
            'system' => 'You are a WHOIS analyst. Explain the current query, summarize likely registration status, recommend acquisition moves, and suggest a short list of alternatives. Keep the answer concise, practical, and easy to scan.',
        ],
        'premium_search' => [
            'label' => 'Premium Search',
            'temperature' => 0.35,
            'maxTokens' => 700,
            'system' => 'You are a premium domain strategist. Evaluate the query with a focus on brand value, scarcity, resale potential, and acquisition urgency. Return short, decision-oriented guidance and a few premium alternatives.',
        ],
        'brand_assistant' => [
            'label' => 'Mira',
            'temperature' => 0.6,
            'maxTokens' => 800,
            'system' => 'You are a warm, emotionally intelligent brand strategist. Understand the user\'s intent, respond gently and clearly, and guide them step by step. Use the recent conversation context to infer what the user means, especially if they reply with fragments like casual, both, all, yes, no, this, or that. Treat short follow-up messages as continuation of the same task unless the context clearly changes. Ask at most one short clarifying question only when the intent is still ambiguous after using the context. Never repeat the same clarification twice in a row. Keep the structure easy to scan with short sections, supportive language, and practical next steps. Respond in plain text only. Do not use markdown, bullet markers, headings, code blocks, bold, italics, or other formatting symbols. Avoid dumping a huge wall of text.',
        ],
        'business_idea' => [
            'label' => 'Business Idea',
            'temperature' => 0.65,
            'maxTokens' => 800,
            'system' => 'You are a venture architect. Generate a clear concept summary, target audience, monetization path, and domain naming ideas for the user input. Keep the response concise but useful.',
        ],
        'domain_name_generator' => [
            'label' => 'Domain Name Generator',
            'temperature' => 0.7,
            'maxTokens' => 800,
            'system' => 'You are a domain naming engine. Produce brandable, memorable domain candidates with a short reason for each name and a quick note on style. Keep the output structured and succinct.',
        ],
        'appraisal' => [
            'label' => 'Appraisal',
            'temperature' => 0.25,
            'maxTokens' => 700,
            'system' => 'You are a domain appraisal analyst. Estimate value, liquidity, and key drivers. Make clear that estimates are indicative rather than guaranteed. Keep the answer short and professional.',
        ],
    ];
}

function whois_ai_workflow_spec(string $workflow): array
{
    $workflows = whois_ai_workflows();

    if (!isset($workflows[$workflow])) {
        throw new InvalidArgumentException('Unsupported AI workflow: ' . $workflow);
    }

    return $workflows[$workflow];
}

function whois_ai_http_post_json(string $url, array $payload, string $apiKey, int $timeout): array
{
    $config = whois_ai_config();
    $body = json_encode($payload, JSON_UNESCAPED_SLASHES);

    if ($body === false) {
        throw new RuntimeException('Unable to encode Grok request payload.');
    }

    if (function_exists('curl_init')) {
        $handle = curl_init($url);

        if ($handle === false) {
            throw new RuntimeException('Unable to initialize Grok request.');
        }

        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => min(15, $timeout),
            CURLOPT_SSL_VERIFYPEER => !$config['insecureSsl'],
            CURLOPT_SSL_VERIFYHOST => $config['insecureSsl'] ? 0 : 2,
        ]);

        $responseBody = curl_exec($handle);

        if ($responseBody === false) {
            $error = curl_error($handle);
            throw new RuntimeException($error !== '' ? $error : 'Grok request failed.');
        }

        $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
    } else {
        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $headers),
                'content' => $body,
                'timeout' => $timeout,
                'ignore_errors' => true,
            ],
            'ssl' => $config['insecureSsl']
                ? [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
                : [],
        ]);

        $responseBody = file_get_contents($url, false, $context);

        if ($responseBody === false) {
            throw new RuntimeException('Grok request failed.');
        }

        $statusCode = 0;

        foreach ($http_response_header ?? [] as $headerLine) {
            if (preg_match('/^HTTP\/\d(?:\.\d)?\s+(\d{3})\b/', $headerLine, $matches) === 1) {
                $statusCode = (int) $matches[1];
                break;
            }
        }
    }

    $decoded = json_decode($responseBody, true);

    if (!is_array($decoded)) {
        throw new RuntimeException('Grok returned an invalid response.');
    }

    if ($statusCode >= 400) {
        $message = $decoded['error']['message'] ?? ('Grok request failed with status ' . $statusCode . '.');
        throw new RuntimeException((string) $message);
    }

    return $decoded;
}

function whois_ai_request(string $workflow, string $input, array $context = []): array
{
    $config = whois_ai_config();
    $spec = whois_ai_workflow_spec($workflow);

    if ($config['apiKey'] === null) {
        throw new RuntimeException('GROQ_API_KEY is not configured.');
    }

    $messages = [
        [
            'role' => 'system',
            'content' => $spec['system'],
        ],
    ];

    if ($context !== []) {
        $contextText = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if ($contextText !== false) {
            $messages[] = [
                'role' => 'system',
                'content' => 'Context for the current workflow:\n' . $contextText,
            ];
        }
    }

    $messages[] = [
        'role' => 'user',
        'content' => trim($input),
    ];

    $payload = [
        'model' => $config['model'],
        'messages' => $messages,
        'temperature' => $spec['temperature'],
        'max_tokens' => $spec['maxTokens'],
    ];

    $response = whois_ai_http_post_json($config['baseUrl'] . '/chat/completions', $payload, $config['apiKey'], $config['timeout']);

    $content = $response['choices'][0]['message']['content'] ?? null;

    if (!is_string($content) || trim($content) === '') {
        throw new RuntimeException('Grok returned an empty response.');
    }

    return [
        'ok' => true,
        'workflow' => $workflow,
        'label' => $spec['label'],
        'model' => $config['model'],
        'input' => trim($input),
        'output' => $content,
        'usage' => $response['usage'] ?? null,
    ];
}