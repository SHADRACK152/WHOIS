// --- Grok AI Integration for Domain Price Checking ---
function getAIValuation(array $domainData): array {
    $apiKey = whois_ai_config()['apiKey'];
    $apiUrl = whois_ai_config()['baseUrl'] . '/chat/completions';
    if (!$apiKey || !$apiUrl) return ['error' => 'AI unavailable'];

    // Build master prompt
    $prompt = <<<PROMPT
You are a professional domain name valuation expert.
A domain has already been evaluated using a rule-based scoring engine.
Your job is to refine the valuation using human-like reasoning, not to ignore the base data.

INPUT:
Domain: {$domainData['domain']}
Base Price Range: {$domainData['base_price']}
Score: {$domainData['score']}/100

Breakdown:
Length Score: {$domainData['length_score']}
Keyword Score: {$domainData['keyword_score']}
Brandability Score: {$domainData['brand_score']}
TLD Score: {$domainData['tld_score']}
Comparable Sales Score: {$domainData['comp_score']}

INSTRUCTIONS:
Analyze the domain’s brandability, market appeal, and potential real-world use.
Consider startup trends (AI, SaaS, fintech, etc.).
Adjust the price range ONLY if justified.
Do NOT give extreme or unrealistic prices.
Keep adjustments within a reasonable range of the base price.

OUTPUT FORMAT (STRICT JSON):
{
"adjusted_price_min": number,
"adjusted_price_max": number,
"confidence_score": number,
"reasoning": "short explanation",
"tags": ["brandable", "tech", "low_keyword", etc]
}
PROMPT;

    $payload = [
        'model' => whois_ai_config()['model'],
        'messages' => [
            ['role' => 'system', 'content' => $prompt],
        ],
        'temperature' => 0.35,
        'max_tokens' => 400,
    ];

    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($response === false || $err) return ['error' => 'AI request failed: ' . $err];

    $json = json_decode($response, true);
    $content = $json['choices'][0]['message']['content'] ?? '';
    $ai = json_decode($content, true);
    if (!is_array($ai) || !isset($ai['adjusted_price_min'], $ai['adjusted_price_max'])) {
        return ['error' => 'Invalid AI response', 'raw' => $content];
    }

    // Clamp values
    [$baseMin, $baseMax] = array_map('floatval', explode('-', str_replace(['$', ' '], '', $domainData['base_price'])));
    $aiMin = max($baseMin * 0.5, (float)$ai['adjusted_price_min']);
    $aiMax = min($baseMax * 2, (float)$ai['adjusted_price_max']);
    $ai['adjusted_price_min'] = $aiMin;
    $ai['adjusted_price_max'] = $aiMax;

    return [
        'ai_price' => '$' . number_format($aiMin) . ' - $' . number_format($aiMax),
        'confidence' => (int)($ai['confidence_score'] ?? 0),
        'insight' => (string)($ai['reasoning'] ?? ''),
        'tags' => $ai['tags'] ?? [],
        'raw' => $ai,
    ];
}
<?php

declare(strict_types=1);

require_once __DIR__ . '/domain-lookup.php';
require_once __DIR__ . '/currency.php';
require_once __DIR__ . '/truehost-client.php';
require_once __DIR__ . '/grok-client.php';

function whois_appraisal_catalog(): array
{
    return [
        [
            'key' => 'labs',
            'label' => 'Lab-Tech',
            'keywords' => ['labs', 'lab', 'biotech', 'bio', 'science', 'research', 'clinical', 'health', 'med', 'medical'],
            'summary' => 'Labs-style naming is strong in R&D, AI tooling, biotech, and product engineering.',
            'medianUsd' => 3273,
            'marketPopularity' => 92,
            'investorInterest' => 85,
            'liquidityBoost' => 20,
            'memorability' => 94,
            'brandability' => 88,
            'comparables' => [
                ['domain' => 'Lavenderlabs.com', 'soldPrice' => 4500, 'year' => 2023, 'similarity' => 'HIGH', 'source' => 'NameBio.com'],
                ['domain' => 'Pancakelabs.com', 'soldPrice' => 2150, 'year' => 2022, 'similarity' => 'MED', 'source' => 'Atom.com'],
                ['domain' => 'Cipherlabs.com', 'soldPrice' => 8888, 'year' => 2024, 'similarity' => 'HIGH', 'source' => 'NameBio.com'],
            ],
            'endUsers' => [
                ['icon' => 'biotech', 'label' => 'Biotechnology', 'description' => 'Drug discovery, diagnostics, and clinical research brands use labs naming heavily.'],
                ['icon' => 'rocket_launch', 'label' => 'Tech Startups', 'description' => 'Great fit for software innovation hubs and AI-driven teams.'],
                ['icon' => 'eco', 'label' => 'Environmental', 'description' => 'Research centers and sustainability-focused labs use this pattern well.'],
                ['icon' => 'school', 'label' => 'Education', 'description' => 'Academic accelerators and research programs value the clear, modern tone.'],
                ['icon' => 'health_and_safety', 'label' => 'Health', 'description' => 'Medical testing, lab services, and health-tech incubators benefit from the trust signal.'],
            ],
        ],
        [
            'key' => 'ai',
            'label' => 'AI / Software',
            'keywords' => ['ai', 'cloud', 'data', 'software', 'saas', 'platform', 'app', 'dev', 'bot', 'system'],
            'summary' => 'AI and software names win when they are short, sharp, and easy to say aloud.',
            'medianUsd' => 6200,
            'marketPopularity' => 90,
            'investorInterest' => 89,
            'liquidityBoost' => 16,
            'memorability' => 90,
            'brandability' => 92,
            'comparables' => [
                ['domain' => 'Neural.ai', 'soldPrice' => 12800, 'year' => 2024, 'similarity' => 'HIGH', 'source' => 'NameBio.com'],
                ['domain' => 'Signal.io', 'soldPrice' => 7600, 'year' => 2023, 'similarity' => 'MED', 'source' => 'Atom.com'],
                ['domain' => 'Vector.ai', 'soldPrice' => 9200, 'year' => 2024, 'similarity' => 'HIGH', 'source' => 'NameBio.com'],
            ],
            'endUsers' => [
                ['icon' => 'memory', 'label' => 'AI Tools', 'description' => 'High-value fit for machine learning products and agent platforms.'],
                ['icon' => 'cloud', 'label' => 'SaaS', 'description' => 'Natural for product-led software, developer tools, and infrastructure brands.'],
                ['icon' => 'analytics', 'label' => 'Data', 'description' => 'Strong for dashboards, analytics, and automation companies.'],
                ['icon' => 'terminal', 'label' => 'DevOps', 'description' => 'Works well for tooling, APIs, and deployment infrastructure.'],
                ['icon' => 'public', 'label' => 'Platforms', 'description' => 'Suitable for marketplaces and network-effect software.'],
            ],
        ],
        [
            'key' => 'finance',
            'label' => 'Finance',
            'keywords' => ['finance', 'pay', 'bank', 'fund', 'capital', 'cash', 'invest', 'trade', 'wallet', 'wealth'],
            'summary' => 'Finance domains need trust, brevity, and strong commercial intent.',
            'medianUsd' => 7400,
            'marketPopularity' => 88,
            'investorInterest' => 87,
            'liquidityBoost' => 14,
            'memorability' => 86,
            'brandability' => 83,
            'comparables' => [
                ['domain' => 'Yield.io', 'soldPrice' => 4500, 'year' => 2023, 'similarity' => 'MED', 'source' => 'NameBio.com'],
                ['domain' => 'Ledger.ai', 'soldPrice' => 12000, 'year' => 2024, 'similarity' => 'HIGH', 'source' => 'NameBio.com'],
                ['domain' => 'Pulsepay.com', 'soldPrice' => 6800, 'year' => 2022, 'similarity' => 'HIGH', 'source' => 'Atom.com'],
            ],
            'endUsers' => [
                ['icon' => 'account_balance', 'label' => 'FinTech', 'description' => 'Payment, lending, and banking products value clear finance language.'],
                ['icon' => 'trending_up', 'label' => 'Trading', 'description' => 'Great for investing, research, and market-intelligence brands.'],
                ['icon' => 'verified_user', 'label' => 'Trust Services', 'description' => 'Names that imply security and credibility convert better in regulated markets.'],
                ['icon' => 'payments', 'label' => 'Payments', 'description' => 'Useful for wallets, checkout, and cashflow software.'],
                ['icon' => 'savings', 'label' => 'Wealth', 'description' => 'Matches advisory, portfolio, and wealth management products.'],
            ],
        ],
        [
            'key' => 'commerce',
            'label' => 'Commerce',
            'keywords' => ['shop', 'store', 'market', 'sell', 'buy', 'cart', 'retail', 'commerce', 'trade', 'vendor'],
            'summary' => 'Commerce domains benefit from direct intent and immediate recall.',
            'medianUsd' => 4200,
            'marketPopularity' => 84,
            'investorInterest' => 80,
            'liquidityBoost' => 12,
            'memorability' => 83,
            'brandability' => 81,
            'comparables' => [
                ['domain' => 'Shoply.net', 'soldPrice' => 3200, 'year' => 2023, 'similarity' => 'MED', 'source' => 'Atom.com'],
                ['domain' => 'Cartlane.com', 'soldPrice' => 5400, 'year' => 2024, 'similarity' => 'HIGH', 'source' => 'NameBio.com'],
                ['domain' => 'Merchbox.io', 'soldPrice' => 6100, 'year' => 2024, 'similarity' => 'MED', 'source' => 'NameBio.com'],
            ],
            'endUsers' => [
                ['icon' => 'shopping_bag', 'label' => 'Retail', 'description' => 'Online retail brands and niche storefronts buy direct commerce language.'],
                ['icon' => 'storefront', 'label' => 'Marketplaces', 'description' => 'B2B and B2C marketplaces benefit from obvious buying intent.'],
                ['icon' => 'local_shipping', 'label' => 'Logistics', 'description' => 'Fulfillment and shipping brands use commerce naming for trust.'],
                ['icon' => 'sell', 'label' => 'Resale', 'description' => 'Resale and secondhand platforms like concise, buyer-focused names.'],
                ['icon' => 'payments', 'label' => 'Checkout', 'description' => 'Checkout and cart products need short, conversion-oriented naming.'],
            ],
        ],
        [
            'key' => 'security',
            'label' => 'Security',
            'keywords' => ['security', 'safe', 'guard', 'shield', 'privacy', 'secure', 'trust', 'vault'],
            'summary' => 'Security names sell when they feel solid, short, and credible.',
            'medianUsd' => 5600,
            'marketPopularity' => 86,
            'investorInterest' => 84,
            'liquidityBoost' => 13,
            'memorability' => 85,
            'brandability' => 84,
            'comparables' => [
                ['domain' => 'Shield.ai', 'soldPrice' => 9800, 'year' => 2024, 'similarity' => 'HIGH', 'source' => 'NameBio.com'],
                ['domain' => 'Vault.io', 'soldPrice' => 7200, 'year' => 2023, 'similarity' => 'MED', 'source' => 'Atom.com'],
                ['domain' => 'Guardlane.com', 'soldPrice' => 6400, 'year' => 2022, 'similarity' => 'MED', 'source' => 'NameBio.com'],
            ],
            'endUsers' => [
                ['icon' => 'shield', 'label' => 'Cybersecurity', 'description' => 'Protective naming works well for threat detection and identity tooling.'],
                ['icon' => 'lock', 'label' => 'Privacy', 'description' => 'Privacy-first products benefit from trust and clarity.'],
                ['icon' => 'security', 'label' => 'Compliance', 'description' => 'Governance and compliance brands buy assurance-driven names.'],
                ['icon' => 'key', 'label' => 'Identity', 'description' => 'Identity, SSO, and access platforms value credible naming.'],
                ['icon' => 'crisis_alert', 'label' => 'Risk', 'description' => 'Risk management and fraud prevention teams benefit from clear authority.'],
            ],
        ],
        [
            'key' => 'education',
            'label' => 'Education',
            'keywords' => ['school', 'edu', 'academy', 'learn', 'course', 'class', 'teach', 'study'],
            'summary' => 'Education domains perform best when they are welcoming and easy to remember.',
            'medianUsd' => 3200,
            'marketPopularity' => 81,
            'investorInterest' => 77,
            'liquidityBoost' => 11,
            'memorability' => 86,
            'brandability' => 82,
            'comparables' => [
                ['domain' => 'Coursepath.com', 'soldPrice' => 3100, 'year' => 2023, 'similarity' => 'MED', 'source' => 'Atom.com'],
                ['domain' => 'Learnstack.io', 'soldPrice' => 4600, 'year' => 2024, 'similarity' => 'HIGH', 'source' => 'NameBio.com'],
                ['domain' => 'Academyhub.com', 'soldPrice' => 3900, 'year' => 2022, 'similarity' => 'MED', 'source' => 'NameBio.com'],
            ],
            'endUsers' => [
                ['icon' => 'school', 'label' => 'EdTech', 'description' => 'Learning platforms and course marketplaces prefer warm, simple naming.'],
                ['icon' => 'psychology', 'label' => 'Coaching', 'description' => 'Coaches and training products need approachable, memorable names.'],
                ['icon' => 'workspace_premium', 'label' => 'Accreditation', 'description' => 'Certification brands benefit from the trust signal of education terms.'],
                ['icon' => 'rocket_launch', 'label' => 'Cohorts', 'description' => 'Bootcamps and cohort-based products convert better with clarity.'],
                ['icon' => 'menu_book', 'label' => 'Publishing', 'description' => 'Authors and content brands often want direct educational phrasing.'],
            ],
        ],
        [
            'key' => 'default',
            'label' => 'Brandable',
            'keywords' => [],
            'summary' => 'Brandable names work when they are short, pronounceable, and flexible across categories.',
            'medianUsd' => 1800,
            'marketPopularity' => 74,
            'investorInterest' => 69,
            'liquidityBoost' => 10,
            'memorability' => 79,
            'brandability' => 78,
            'comparables' => [
                ['domain' => 'Northstar.com', 'soldPrice' => 3500, 'year' => 2023, 'similarity' => 'MED', 'source' => 'NameBio.com'],
                ['domain' => 'Luma.io', 'soldPrice' => 2700, 'year' => 2022, 'similarity' => 'HIGH', 'source' => 'Atom.com'],
                ['domain' => 'Aurora.co', 'soldPrice' => 4100, 'year' => 2024, 'similarity' => 'MED', 'source' => 'NameBio.com'],
            ],
            'endUsers' => [
                ['icon' => 'palette', 'label' => 'Creative', 'description' => 'Studios and agencies want names that sound clean and flexible.'],
                ['icon' => 'public', 'label' => 'General SaaS', 'description' => 'Early-stage software brands value short, universal names.'],
                ['icon' => 'campaign', 'label' => 'Marketing', 'description' => 'Growth teams prefer names that are easy to say and remember.'],
                ['icon' => 'hub', 'label' => 'Product Labs', 'description' => 'Internal labs and experimental products like abstract brands.'],
                ['icon' => 'design_services', 'label' => 'Design', 'description' => 'Design and creative businesses often favor modern, compact names.'],
            ],
        ],
    ];
}

function whois_domain_appraisal_normalize_target(string $value): string
{
    $value = trim($value);

    if ($value === '') {
        return 'trovalabs.com';
    }

    return whois_domain_normalize($value);
}

function whois_domain_appraisal_landmark_sale(string $domain): ?array
{
    $domain = strtolower(trim($domain));

    $landmarks = [
        'ai.com' => [
            'soldPriceUsd' => 70000000,
            'year' => 2025,
            'source' => 'GetYourDomain.com',
            'note' => 'Landmark sale with strategic global brand relevance.',
        ],
        'carinsurance.com' => [
            'soldPriceUsd' => 49700000,
            'year' => 2010,
            'source' => 'Public record',
            'note' => 'Historic benchmark for headline domain transactions.',
        ],
    ];

    return $landmarks[$domain] ?? null;
}

function whois_domain_appraisal_strip_tld(string $domain): string
{
    $root = preg_replace('/\.[^.]+$/', '', strtolower(trim($domain))) ?? strtolower(trim($domain));
    $root = preg_replace('/[^a-z0-9-]/', '', $root) ?? $root;

    return trim($root, '-');
}

function whois_domain_appraisal_count_syllables(string $value): int
{
    $value = strtolower(preg_replace('/[^a-z]/', '', $value) ?? '');

    if ($value === '') {
        return 0;
    }

    $value = preg_replace('/(?:[^laeiouy]|^|cl)es|ed$/', '', $value) ?? $value;
    $value = preg_replace('/^y/', '', $value) ?? $value;
    $count = preg_match_all('/[aeiouy]{1,2}/', $value) ?: 0;

    return max(1, (int) $count);
}

function whois_domain_appraisal_keyword_matches(string $root): array
{
    $root = strtolower($root);

    foreach (whois_appraisal_catalog() as $entry) {
        foreach ($entry['keywords'] as $keyword) {
            if ($keyword !== '' && str_contains($root, $keyword)) {
                return $entry;
            }
        }
    }

    return whois_appraisal_catalog()[count(whois_appraisal_catalog()) - 1];
}

function whois_domain_appraisal_tld_multiplier(string $tld): float
{
    $tld = strtolower(trim($tld));

    return match ($tld) {
        'com' => 1.00,
        'ai' => 0.97,
        'io' => 0.92,
        'co' => 0.86,
        'net' => 0.78,
        'org' => 0.76,
        default => 0.70,
    };
}

function whois_domain_appraisal_length_score(int $length): float
{
    if ($length <= 4) {
        return 9.8;
    }

    if ($length <= 5) {
        return 9.0;
    }

    if ($length <= 6) {
        return 8.2;
    }

    if ($length <= 7) {
        return 7.4;
    }

    if ($length <= 8) {
        return 6.7;
    }

    if ($length <= 9) {
        return 6.1;
    }

    if ($length <= 10) {
        return 5.6;
    }

    if ($length <= 12) {
        return 4.9;
    }

    return 4.2;
}

function whois_domain_appraisal_pronounceability_score(string $root): float
{
    $letters = preg_replace('/[^a-z]/', '', strtolower($root)) ?? '';

    if ($letters === '') {
        return 0.0;
    }

    $vowels = preg_match_all('/[aeiouy]/', $letters) ?: 0;
    $length = strlen($letters);
    $vowelRatio = $length > 0 ? $vowels / $length : 0.0;
    $clusterPenalty = preg_match_all('/[^aeiouy]{3,}/', $letters) ?: 0;
    $score = 8.6 - abs($vowelRatio - 0.42) * 8.5 - ($clusterPenalty * 0.8);

    return max(3.5, min(9.6, $score));
}

function whois_domain_appraisal_brand_score(string $root): float
{
    $letters = preg_replace('/[^a-z]/', '', strtolower($root)) ?? '';

    if ($letters === '') {
        return 0.0;
    }

    $alliteration = preg_match('/^([bcdfghjklmnpqrstvwxyz])\1?/', $letters) === 1 ? 0.7 : 0.0;
    $distinctLetters = strlen((string) count_chars($letters, 3));
    $repeatPenalty = max(0, strlen($letters) - $distinctLetters) * 0.15;
    $shapeScore = whois_domain_appraisal_pronounceability_score($letters);

    return max(3.0, min(9.5, $shapeScore - $repeatPenalty + $alliteration));
}

function whois_domain_appraisal_category_score(array $category, string $root, string $tld): float
{
    $baseScore = 4.8;

    if (isset($category['key']) && $category['key'] !== 'default') {
        $baseScore = 6.8;
    }

    $rootLower = strtolower($root);
    $premiumSuffix = ['labs', 'ai', 'io', 'hq', 'studio', 'cloud', 'data', 'tech'];

    foreach ($premiumSuffix as $suffix) {
        if (str_ends_with($rootLower, $suffix)) {
            $baseScore += 0.7;
            break;
        }
    }

    if ($tld === 'com') {
        $baseScore += 0.6;
    } elseif ($tld === 'ai' || $tld === 'io') {
        $baseScore += 0.4;
    }

    return max(3.8, min(9.6, $baseScore));
}

function whois_domain_appraisal_liquidity_score(float $score, array $category, string $tld): float
{
    $liquidity = 35 + ($score * 6) + (int) ($category['liquidityBoost'] ?? 10);

    if ($tld === 'com') {
        $liquidity += 6;
    } elseif ($tld === 'ai' || $tld === 'io') {
        $liquidity += 4;
    }

    return max(18, min(98, (float) round($liquidity)));
}

function whois_domain_appraisal_value_range(float $medianUsd, float $score, string $tld): array
{
    $tldMultiplier = whois_domain_appraisal_tld_multiplier($tld);
    $midpoint = $medianUsd * (0.22 + (0.055 * $score)) * $tldMultiplier;
    $spread = 0.16 + max(0.0, (7.5 - $score) * 0.015);

    return [
        'midUsd' => max(250.0, $midpoint),
        'lowUsd' => max(150.0, $midpoint * (1 - $spread)),
        'highUsd' => max(350.0, $midpoint * (1 + $spread)),
    ];
}

function whois_domain_appraisal_comparable_anchor(array $comparables, float $fallbackMedianUsd): float
{
    $weightedSum = 0.0;
    $weightTotal = 0.0;

    foreach ($comparables as $comparable) {
        if (!is_array($comparable)) {
            continue;
        }

        $soldPrice = (float) ($comparable['soldPrice'] ?? 0);

        if ($soldPrice <= 0) {
            continue;
        }

        $similarity = strtoupper(trim((string) ($comparable['similarity'] ?? 'MED')));
        $year = (int) ($comparable['year'] ?? (int) date('Y'));
        $ageYears = max(0, ((int) date('Y')) - $year);

        $similarityWeight = match ($similarity) {
            'HIGH' => 1.0,
            'MED' => 0.7,
            default => 0.45,
        };
        $recencyWeight = max(0.75, 1.0 - ($ageYears * 0.06));
        $weight = $similarityWeight * $recencyWeight;

        $weightedSum += $soldPrice * $weight;
        $weightTotal += $weight;
    }

    if ($weightTotal <= 0.0) {
        return max(350.0, $fallbackMedianUsd);
    }

    return max(350.0, $weightedSum / $weightTotal);
}

function whois_domain_appraisal_live_multiplier(float $score, int $liquidityPercent, string $lookupStatus): float
{
    $base = 0.34 + ($score * 0.055);
    $liquidityAdjustment = ($liquidityPercent - 70) / 2000;
    $status = strtolower(trim($lookupStatus));

    $statusAdjustment = match ($status) {
        'registered', 'unavailable' => 0.06,
        'available' => -0.08,
        default => 0.0,
    };

    return max(0.48, min(1.08, $base + $liquidityAdjustment + $statusAdjustment));
}

function whois_domain_appraisal_word_count(string $root): int
{
    $root = trim(strtolower($root));

    if ($root === '') {
        return 0;
    }

    if (str_contains($root, '-')) {
        $parts = array_values(array_filter(array_map('trim', explode('-', $root))));
        return max(1, count($parts));
    }

    $chunks = preg_split('/(?:labs|tech|cloud|data|pay|bank|shop|ai|io)+/i', $root) ?: [];
    $nonEmpty = array_values(array_filter(array_map('trim', $chunks)));

    return max(1, count($nonEmpty));
}

function whois_domain_appraisal_trademark_risk(string $root): array
{
    $rootLower = strtolower($root);
    $watchlist = ['google', 'apple', 'microsoft', 'amazon', 'meta', 'tesla', 'openai', 'chatgpt', 'tiktok', 'netflix'];

    foreach ($watchlist as $term) {
        if (str_contains($rootLower, $term)) {
            return [
                'label' => 'High',
                'score' => 25,
                'note' => 'Potential conflict with well-known trademark patterns.',
            ];
        }
    }

    return [
        'label' => 'Low',
        'score' => 88,
        'note' => 'No immediate high-profile trademark collision pattern detected.',
    ];
}

function whois_domain_appraisal_pricing_tiers(float $retailUsd): array
{
    $retailUsd = max(100.0, $retailUsd);

    return [
        'retail' => [
            'label' => 'Retail (End-user)',
            'lowUsd' => $retailUsd * 0.95,
            'highUsd' => $retailUsd * 1.15,
        ],
        'investor' => [
            'label' => 'Investor',
            'lowUsd' => $retailUsd * 0.40,
            'highUsd' => $retailUsd * 0.60,
        ],
        'liquid' => [
            'label' => 'Liquid (Fast sale)',
            'lowUsd' => $retailUsd * 0.20,
            'highUsd' => $retailUsd * 0.30,
        ],
    ];
}

function whois_domain_appraisal_ai_insight(array $analysis): array
{
    $fallback = [
        'summary' => sprintf(
            '%s reads as a %s with solid commercial intent. The strongest lift comes from the TLD, the root shape, and the category demand profile.',
            $analysis['domain'],
            $analysis['category']['label'] ?? 'brandable asset'
        ),
        'drivers' => [
            'Short, easy-to-scan structure with a clean brand surface.',
            'Category demand supports steady investor attention.',
            'The TLD keeps the asset inside mainstream buyer behavior.',
        ],
        'risks' => [
            'Longer roots usually compress liquidity versus ultra-short names.',
            'The buyer pool narrows if the brand leans too niche.',
        ],
        'recommendation' => 'Hold for a strategic buyer or list with a clear reserve if you want faster exit velocity.',
        'confidence' => 72,
    ];

    $config = whois_ai_config();

    if ($config['apiKey'] === null) {
        return $fallback;
    }

    $prompt = sprintf(
        'Return ONLY JSON with keys summary, drivers, risks, recommendation, and confidence. Keep summary to 1-2 sentences and the arrays to 3 items max. Analyze the domain %s using this context: %s',
        $analysis['domain'],
        json_encode([
            'category' => $analysis['category']['label'] ?? 'Brandable',
            'score' => $analysis['score'],
            'estimatedValue' => $analysis['estimatedValue'],
            'valueRange' => $analysis['valueRange'],
            'signals' => $analysis['signals'],
            'marketLiquidity' => $analysis['marketLiquidity'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    );

    try {
        $response = whois_ai_request('appraisal', $prompt, $analysis);
        $content = trim((string) ($response['output'] ?? ''));

        $content = preg_replace('/^```(?:json)?\s*/i', '', $content) ?? $content;
        $content = preg_replace('/\s*```$/', '', $content) ?? $content;

        $decoded = json_decode($content, true);

        if (is_array($decoded)) {
            $confidence = (int) ($decoded['confidence'] ?? $fallback['confidence']);

            return [
                'summary' => trim((string) ($decoded['summary'] ?? $fallback['summary'])),
                'drivers' => array_values(array_filter(array_map('trim', (array) ($decoded['drivers'] ?? $fallback['drivers'])))),
                'risks' => array_values(array_filter(array_map('trim', (array) ($decoded['risks'] ?? $fallback['risks'])))),
                'recommendation' => trim((string) ($decoded['recommendation'] ?? $fallback['recommendation'])),
                'confidence' => max(35, min(98, $confidence > 0 ? $confidence : $fallback['confidence'])),
            ];
        }

        return [
            'summary' => $content !== '' ? $content : $fallback['summary'],
            'drivers' => $fallback['drivers'],
            'risks' => $fallback['risks'],
            'recommendation' => $fallback['recommendation'],
            'confidence' => max(35, min(98, (int) $fallback['confidence'])),
        ];
    } catch (Throwable $exception) {
        return $fallback;
    }
}

function whois_domain_appraisal_analyze(string $input, string $displayCurrency = 'USD'): array
{
    $displayCurrency = whois_currency_normalize_code($displayCurrency, 'USD');
    $domain = whois_domain_appraisal_normalize_target($input);
    $root = whois_domain_appraisal_strip_tld($domain);
    $tld = strtolower(substr($domain, (int) strrpos($domain, '.') + 1));
    $letters = preg_replace('/[^a-z0-9]/', '', $root) ?? '';
    $letterCount = strlen($letters);
    $syllableCount = whois_domain_appraisal_count_syllables($root);
    $category = whois_domain_appraisal_keyword_matches($root);
    $lookup = whois_truehost_domain_lookup($domain);
    $landmarkSale = whois_domain_appraisal_landmark_sale($domain);
    $tldPrice = whois_truehost_tld_price($tld);
    $tldPriceRaw = is_array($tldPrice) && isset($tldPrice['raw']) && is_numeric($tldPrice['raw']) ? (float) $tldPrice['raw'] : null;

    $lengthScore = whois_domain_appraisal_length_score($letterCount);
    $pronounceabilityScore = whois_domain_appraisal_pronounceability_score($root);
    $brandScore = whois_domain_appraisal_brand_score($root);
    $categoryScore = whois_domain_appraisal_category_score($category, $root, $tld);
    $tldScore = match ($tld) {
        'com' => 9.4,
        'ai' => 9.0,
        'io' => 8.6,
        'co' => 7.8,
        'net' => 6.9,
        default => 6.2,
    };

    $score = round((
        ($lengthScore * 0.25) +
        ($pronounceabilityScore * 0.18) +
        ($brandScore * 0.22) +
        ($categoryScore * 0.20) +
        ($tldScore * 0.15)
    ), 1);

    $categoryMedianUsd = (float) ($category['medianUsd'] ?? 1800);

    $comparableAnchorUsd = whois_domain_appraisal_comparable_anchor((array) ($category['comparables'] ?? []), $categoryMedianUsd);
    $liquidityPercent = (int) whois_domain_appraisal_liquidity_score($score, $category, $tld);
    $liveMultiplier = whois_domain_appraisal_live_multiplier($score, $liquidityPercent, (string) ($lookup['status'] ?? 'unknown'));
    $tldMultiplier = whois_domain_appraisal_tld_multiplier($tld);

    $anchorUsd = (($categoryMedianUsd * 0.6) + ($comparableAnchorUsd * 0.4));
    $midUsd = max(250.0, $anchorUsd * $liveMultiplier * $tldMultiplier);
    $spread = max(0.12, min(0.26, 0.24 - (($score - 6.0) * 0.02) - (($liquidityPercent - 70) * 0.0015)));

    // --- ULTRA-PREMIUM ONE-WORD LOGIC (all TLDs) ---
    $isUltraPremium = false;
    $ultraPremiumFloor = 0;
    $majorTlds = ['com', 'net', 'org', 'ai', 'io', 'co', 'app', 'dev'];
    $isMajorTld = in_array($tld, $majorTlds, true);
    $isShort = $letterCount <= 4;
    $isSingleWord = whois_domain_appraisal_word_count($root) === 1;

    // --- Robust dictionary word detection ---
    $isDictionary = false;
    if (function_exists('pspell_new')) {
        $pspell = pspell_new('en');
        if ($pspell && pspell_check($pspell, $root)) {
            $isDictionary = true;
        }
    } else {
        // Fallback: check against a local wordlist (words_alpha.txt, 370k+ English words)
        static $wordlist = null;
        if ($wordlist === null) {
            $wordlist = [];
            $dictPath = __DIR__ . '/words_alpha.txt';
            if (is_readable($dictPath)) {
                $lines = file($dictPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $w) {
                    $wordlist[strtolower(trim($w))] = true;
                }
            }
        }
        if ($wordlist && isset($wordlist[strtolower($root)])) {
            $isDictionary = true;
        }
    }

    // Heuristic: treat as ultra-premium if single, short, highly brandable, or dictionary word
    if ($isMajorTld && $isSingleWord && ($isShort || $score >= 8.8 || $isDictionary)) {
        $isUltraPremium = true;
        // Set floor based on TLD and length
        if ($tld === 'com') {
            $ultraPremiumFloor = $isShort ? 1500000 : 500000;
        } elseif ($tld === 'ai' || $tld === 'io') {
            $ultraPremiumFloor = $isShort ? 350000 : 120000;
        } elseif ($tld === 'net' || $tld === 'org') {
            $ultraPremiumFloor = $isShort ? 90000 : 35000;
        } else {
            $ultraPremiumFloor = $isShort ? 25000 : 9000;
        }
        // If dictionary word, boost floor
        if ($isDictionary) {
            $ultraPremiumFloor *= 1.5;
        }
        $midUsd = max($midUsd, $ultraPremiumFloor);
        $spread = min($spread, 0.13);
    }

    if (is_array($landmarkSale)) {
        $landmarkUsd = (float) ($landmarkSale['soldPriceUsd'] ?? 0);

        if ($landmarkUsd > 0) {
            $score = max($score, 9.8);
            $liquidityPercent = max($liquidityPercent, 98);
            $midUsd = max($midUsd, $landmarkUsd);
            $spread = 0.09;
        }
    }

    $valueRange = [
        'midUsd' => $midUsd,
        'lowUsd' => max(150.0, $midUsd * (1 - $spread)),
        'highUsd' => max(350.0, $midUsd * (1 + $spread)),
    ];

    $estimatedValue = whois_currency_convert_amount($valueRange['midUsd'], 'USD', $displayCurrency);
    $valueLow = whois_currency_convert_amount($valueRange['lowUsd'], 'USD', $displayCurrency);
    $valueHigh = whois_currency_convert_amount($valueRange['highUsd'], 'USD', $displayCurrency);
    $categoryName = (string) ($category['label'] ?? 'Brandable');

    $memorabilityPercent = (int) round(min(98, max(40, ($score * 8.2) + ((int) ($category['memorability'] ?? 78) * 0.25))));
    $brandabilityPercent = (int) round(min(98, max(35, ($score * 7.5) + ((int) ($category['brandability'] ?? 76) * 0.28))));

    $pattern = match (true) {
        str_ends_with($root, 'labs') => 'Premium suffix',
        str_ends_with($root, 'ai') => 'AI-forward pattern',
        str_ends_with($root, 'io') => 'Developer-friendly pattern',
        str_contains($root, '-') => 'Compound brand',
        default => 'Single-root brand',
    };

    $rootWord = strtoupper(preg_replace('/[^a-z0-9]/', '', $root) ?: $root);
    $rootWord = $rootWord !== '' ? $rootWord : strtoupper($domain);
    $wordCount = whois_domain_appraisal_word_count($root);

    $lookupStatus = strtolower((string) ($lookup['status'] ?? 'unknown'));
    $lookupBadge = (string) ($lookup['statusLabel'] ?? 'Unknown');
    $lookupSummary = (string) ($lookup['availabilityNote'] ?? 'Registry data unavailable.');

    $referencePrice = is_array($tldPrice) && isset($tldPrice['formatted']) ? (string) $tldPrice['formatted'] : 'Price unavailable';

    $statusSummary = match ($lookupStatus) {
        'available' => 'This domain is available and can be acquired immediately.',
        'registered', 'unavailable' => 'This domain is already registered, so valuation leans toward acquisition interest and resale potential.',
        default => $lookupSummary,
    };

    $infrastructure = [
        [
            'label' => 'Registry lookup',
            'status' => $lookupBadge,
            'tone' => $lookupStatus === 'available' ? 'success' : ($lookupStatus === 'registered' ? 'neutral' : 'warning'),
            'details' => $statusSummary,
        ],
        [
            'label' => 'TLD pricing feed',
            'status' => $tldPriceRaw !== null ? 'ACTIVE' : 'UNAVAILABLE',
            'tone' => $tldPriceRaw !== null ? 'success' : 'warning',
            'details' => $tldPriceRaw !== null ? 'Live renewal pricing is loaded for .' . $tld . '.' : 'The renewal feed could not be loaded for this extension.',
        ],
        [
            'label' => 'AI appraisal memo',
            'status' => whois_ai_config()['apiKey'] !== null ? 'ACTIVE' : 'FALLBACK',
            'tone' => whois_ai_config()['apiKey'] !== null ? 'success' : 'warning',
            'details' => whois_ai_config()['apiKey'] !== null ? 'Groq can provide a narrative explanation alongside the score.' : 'AI commentary is using the local heuristic fallback.',
        ],
        [
            'label' => 'Marketplace path',
            'status' => 'READY',
            'tone' => 'success',
            'details' => 'The submit-for-auction flow is available from the appraisal page.',
        ],
    ];

    $signals = [
        [
            'icon' => 'short_text',
            'label' => $letterCount . ' letters',
            'title' => 'Length',
            'value' => $letterCount,
        ],
        [
            'icon' => 'reorder',
            'label' => $syllableCount . ' syllables',
            'title' => 'Pronunciation',
            'value' => $syllableCount,
        ],
        [
            'icon' => 'pentagon',
            'label' => $rootWord,
            'title' => 'Root Word',
            'value' => $pattern,
        ],
        [
            'icon' => 'public',
            'label' => '.' . $tld,
            'title' => 'TLD',
            'value' => $referencePrice,
        ],
    ];

    $aiInsight = whois_domain_appraisal_ai_insight([
        'domain' => $domain,
        'category' => $category,
        'score' => $score,
        'estimatedValue' => $estimatedValue,
        'valueRange' => [
            'low' => $valueLow,
            'high' => $valueHigh,
        ],
        'signals' => $signals,
        'marketLiquidity' => $liquidityPercent,
    ]);

    $drivers = [
        [
            'label' => 'Market liquidity',
            'value' => $liquidityPercent . '%',
            'note' => $category['summary'] ?? 'Brandable domains perform best when they remain short and easy to say.',
        ],
        [
            'label' => 'Memorability',
            'value' => $memorabilityPercent . '%',
            'note' => 'Balanced phonetics and a compact root keep recall high.',
        ],
        [
            'label' => 'Brandability',
            'value' => $brandabilityPercent . '%',
            'note' => 'The name is flexible enough to support multiple product stories.',
        ],
    ];

    $comparableSales = [];

    if (is_array($landmarkSale) && (float) ($landmarkSale['soldPriceUsd'] ?? 0) > 0) {
        $landmarkPrice = (float) $landmarkSale['soldPriceUsd'];
        $comparableSales[] = [
            'domain' => strtoupper($domain),
            'soldPrice' => whois_currency_format_amount(whois_currency_convert_amount($landmarkPrice, 'USD', $displayCurrency), $displayCurrency),
            'year' => (string) ($landmarkSale['year'] ?? ''),
            'similarity' => 'EXACT',
            'source' => (string) ($landmarkSale['source'] ?? 'Public record'),
        ];
    }

    foreach ((array) ($category['comparables'] ?? []) as $comparable) {
        if (!is_array($comparable)) {
            continue;
        }

        $soldPrice = (float) ($comparable['soldPrice'] ?? 0);
        $comparableSales[] = [
            'domain' => (string) ($comparable['domain'] ?? ''),
            'soldPrice' => whois_currency_format_amount(whois_currency_convert_amount($soldPrice, 'USD', $displayCurrency), $displayCurrency),
            'year' => (string) ($comparable['year'] ?? ''),
            'similarity' => (string) ($comparable['similarity'] ?? 'MED'),
            'source' => (string) ($comparable['source'] ?? 'Reference'),
        ];
    }

    $createdAt = (string) ($lookup['created'] ?? '');
    $ageYears = 0;

    if ($createdAt !== '') {
        try {
            $createdYear = (int) (new DateTimeImmutable($createdAt))->format('Y');
            $ageYears = max(0, ((int) date('Y')) - $createdYear);
        } catch (Throwable $exception) {
            $ageYears = 0;
        }
    }

    $keywordStrength = min(98, max(35, (int) round(((int) ($category['marketPopularity'] ?? 70) * 0.6) + ((int) ($category['investorInterest'] ?? 65) * 0.4))));
    $trafficSeoScore = min(96, max(30, (int) round(($liquidityPercent * 0.45) + ($keywordStrength * 0.40) + ($score * 2.0))));
    $trademarkRisk = whois_domain_appraisal_trademark_risk($root);

    $tldRankText = match ($tld) {
        'com' => '.com primary premium tier',
        'net', 'org' => '.' . $tld . ' secondary trust tier',
        'ai', 'io' => '.' . $tld . ' niche premium tier',
        default => '.' . $tld . ' long-tail extension tier',
    };

    $valuationFactors = [
        [
            'factor' => 'TLD',
            'indicator' => $tldRankText,
            'score' => (int) round($tldScore * 10),
            'note' => 'Extension quality strongly influences retail demand and liquidity.',
        ],
        [
            'factor' => 'Length',
            'indicator' => $letterCount <= 4 ? '1-4 chars premium band' : ($letterCount . ' characters'),
            'score' => (int) round($lengthScore * 10),
            'note' => 'Shorter names generally command higher valuations.',
        ],
        [
            'factor' => 'Words',
            'indicator' => $wordCount <= 2 ? $wordCount . ' words (strong)' : $wordCount . ' words (longer phrase)',
            'score' => $wordCount <= 2 ? 86 : 58,
            'note' => 'One to two words typically outperform longer constructions.',
        ],
        [
            'factor' => 'Keywords',
            'indicator' => $categoryName,
            'score' => $keywordStrength,
            'note' => 'Commercial-intent keyword categories lift buyer competition.',
        ],
        [
            'factor' => 'Brandability',
            'indicator' => $pattern,
            'score' => $brandabilityPercent,
            'note' => 'Spelling clarity and memorability influence end-user pricing.',
        ],
        [
            'factor' => 'Age & History',
            'indicator' => $ageYears > 0 ? ($ageYears . ' years old') : 'Recent or unknown history',
            'score' => $ageYears > 0 ? min(95, 55 + ($ageYears * 2)) : 52,
            'note' => 'Older clean domains generally carry stronger trust and SEO residuals.',
        ],
        [
            'factor' => 'Traffic & SEO',
            'indicator' => $lookupStatus === 'registered' ? 'Registered asset with resale profile' : 'No live traffic signal detected',
            'score' => $trafficSeoScore,
            'note' => 'Traffic/backlink proxies are estimated when live analytics are unavailable.',
        ],
        [
            'factor' => 'Trademark Risk',
            'indicator' => $trademarkRisk['label'] . ' risk',
            'score' => (int) $trademarkRisk['score'],
            'note' => (string) $trademarkRisk['note'],
        ],
    ];

    $pricingTiersUsd = whois_domain_appraisal_pricing_tiers($valueRange['midUsd']);
    $pricingTiers = [];

    foreach ($pricingTiersUsd as $key => $tier) {
        $tierLow = whois_currency_convert_amount((float) ($tier['lowUsd'] ?? 0), 'USD', $displayCurrency);
        $tierHigh = whois_currency_convert_amount((float) ($tier['highUsd'] ?? 0), 'USD', $displayCurrency);
        $pricingTiers[$key] = [
            'label' => (string) ($tier['label'] ?? ucfirst($key)),
            'low' => whois_currency_format_amount($tierLow, $displayCurrency),
            'high' => whois_currency_format_amount($tierHigh, $displayCurrency),
        ];
    }

    $binTargetLow = whois_currency_convert_amount($valueRange['midUsd'] * 1.10, 'USD', $displayCurrency);
    $binTargetHigh = whois_currency_convert_amount($valueRange['midUsd'] * 1.30, 'USD', $displayCurrency);

    // --- AI Valuation Integration ---
    $aiValuation = getAIValuation([
        'domain' => $domain,
        'base_price' => '$' . number_format($valueRange['lowUsd']) . ' - $' . number_format($valueRange['highUsd']),
        'score' => $score,
        'length_score' => $lengthScore,
        'keyword_score' => $categoryScore,
        'brand_score' => $brandScore,
        'tld_score' => $tldScore,
        'comp_score' => $categoryScore,
    ]);

    return [
        'domain' => $domain,
        'root' => $root,
        'rootWord' => $rootWord,
        'tld' => $tld,
        'category' => $category,
        'score' => $score,
        'scoreLabel' => $score >= 8.5 ? 'Elite' : ($score >= 7.0 ? 'Strong' : ($score >= 5.5 ? 'Solid' : 'Speculative')),
        'estimatedValueUsd' => $midUsd,
        'estimatedValue' => whois_currency_format_amount($estimatedValue, $displayCurrency),
        'valueLow' => whois_currency_format_amount($valueLow, $displayCurrency),
        'valueHigh' => whois_currency_format_amount($valueHigh, $displayCurrency),
        'valueRange' => [
            'low' => $valueLow,
            'high' => $valueHigh,
        ],
        'ai_price' => $aiValuation['ai_price'] ?? null,
        'ai_confidence' => $aiValuation['confidence'] ?? null,
        'ai_insight' => $aiValuation['insight'] ?? null,
        'ai_tags' => $aiValuation['tags'] ?? [],
        'liquidityPercent' => $liquidityPercent,
        'liquiditySummary' => $liquidityPercent >= 90
            ? 'This domain sits in the top 15% of brandable assets within this niche.'
            : ($liquidityPercent >= 80 ? 'This domain has a strong secondary-market profile.' : 'This domain has moderate resale liquidity.'),
        'marketPopularity' => (int) ($category['marketPopularity'] ?? 74),
        'investorInterest' => (int) ($category['investorInterest'] ?? 68),
        'memorability' => $memorabilityPercent,
        'brandability' => $brandabilityPercent,
        'summary' => is_array($landmarkSale)
            ? (($landmarkSale['note'] ?? 'Landmark sale detected for this exact domain.') . ' The model anchors this valuation to publicly known transaction history.')
            : ($category['summary'] ?? 'A balanced brandable domain with flexible positioning.'),
        'statusSummary' => $statusSummary,
        'lookup' => $lookup,
        'referencePrice' => $referencePrice,
        'tldPrice' => $tldPrice,
        'signals' => $signals,
        'drivers' => $drivers,
        'valuationFactors' => $valuationFactors,
        'pricingTiers' => $pricingTiers,
        'pricingStrategy' => [
            'binLow' => whois_currency_format_amount($binTargetLow, $displayCurrency),
            'binHigh' => whois_currency_format_amount($binTargetHigh, $displayCurrency),
            'auctionNote' => 'Auction pricing should be demand-driven with reserve aligned to investor tier.',
            'tip' => 'A domain is worth what a qualified buyer is willing to pay. List, test demand, and iterate.',
        ],
        'comparableSales' => $comparableSales,
        'endUsers' => array_map(static function (array $item): array {
            return [
                'icon' => (string) ($item['icon'] ?? 'public'),
                'label' => (string) ($item['label'] ?? ''),
                'description' => (string) ($item['description'] ?? ''),
            ];
        }, (array) ($category['endUsers'] ?? [])),
        'infrastructure' => $infrastructure,
        'aiInsight' => $aiInsight,
        'displayCurrency' => $displayCurrency,
    ];
}

// No closing PHP tag and no trailing whitespace