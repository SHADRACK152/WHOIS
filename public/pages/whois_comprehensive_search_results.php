<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/domain-lookup.php';
require_once __DIR__ . '/../../app/currency.php';
require_once __DIR__ . '/../../app/truehost-client.php';
require __DIR__ . '/../../app/grok-client.php';
require_once __DIR__ . '/../../app/premium-market.php';

$searchInput = trim((string) ($_GET['query'] ?? 'trovalabs.com'));
$selectedCurrency = whois_currency_normalize_code((string) ($_GET['currency'] ?? 'USD'), 'USD');
$searchDomain = whois_domain_normalize($searchInput);

if ($searchDomain === '') {
    $searchDomain = 'trovalabs.com';
}

$searchRoot = preg_replace('/\.[^.]+$/', '', $searchDomain) ?? $searchDomain;
$searchStem = preg_replace('/[^a-z0-9]/', '', $searchRoot) ?? '';

if ($searchStem === '') {
    $searchStem = 'brand';
}

$searchLabel = ucfirst($searchStem);
$lookup = whois_truehost_domain_lookup($searchDomain);
$lookupStatus = (string) ($lookup['status'] ?? 'unknown');
$lookupStatusLabel = (string) ($lookup['statusLabel'] ?? 'Unknown');
$lookupNote = (string) ($lookup['availabilityNote'] ?? '');

$availabilityBadgeClass = $lookupStatus === 'available'
    ? 'bg-emerald-100 text-emerald-800 border border-emerald-200'
    : 'bg-neutral-800 text-white border border-neutral-700';
$availabilityHeadline = $lookupStatus === 'available' ? 'Available' : ($lookupStatus === 'unavailable' || $lookupStatus === 'registered' ? 'Registered' : 'Unknown');
$brokerageUrl = 'whois_professional_lookup_tool.php?domain=' . rawurlencode($searchDomain);
$brokerageFeeLabel = whois_currency_format_amount(
    whois_currency_convert_amount(5000.0, 'KES', $selectedCurrency),
    $selectedCurrency
);

function whois_search_status_details(string $status): array
{
    $status = strtolower(trim($status));

    if ($status === 'available') {
        return [
            'label' => 'Available now',
            'class' => 'bg-emerald-100 text-emerald-800',
        ];
    }

    if ($status === 'registered' || $status === 'unavailable') {
        return [
            'label' => 'Registered',
            'class' => 'bg-rose-100 text-rose-800',
        ];
    }

    if (str_contains($status, 'premium') || str_contains($status, 'priced') || str_contains($status, 'marketed')) {
        return [
            'label' => 'Verified premium',
            'class' => 'bg-amber-100 text-amber-800',
        ];
    }

    return [
        'label' => $status !== '' ? ucfirst($status) : 'Unknown',
        'class' => 'bg-neutral-100 text-neutral-600',
    ];
}

function whois_search_premium_status_details(array $listing): array
{
    $state = strtolower(trim((string) ($listing['state'] ?? 'verified')));
    $label = trim((string) ($listing['statusLabel'] ?? ''));

    if ($label === '') {
        $label = match ($state) {
            'priced' => 'Priced',
            'marketed' => 'Marketed',
            'offer' => 'Offer available',
            default => 'Verified premium',
        };
    }

    $class = match ($state) {
        'priced' => 'bg-amber-100 text-amber-800',
        'marketed' => 'bg-blue-100 text-blue-800',
        'offer' => 'bg-emerald-100 text-emerald-800',
        default => 'bg-amber-100 text-amber-800',
    };

    return [
        'label' => $label,
        'class' => $class,
        'state' => $state,
    ];
}

function whois_ai_idea_card_image(string $seed, string $label, array $theme = []): string
{
    $themeLabel = (string) ($theme['label'] ?? 'Brand');
    $themeHint = (string) ($theme['hint'] ?? 'Concept');
    $themeIcon = (string) ($theme['icon'] ?? 'workspace_premium');
    $hash = substr(md5($seed . '|' . $themeLabel), 0, 12);
    $colorOne = '#' . substr($hash, 0, 6);
    $colorTwo = '#' . substr($hash, 6, 6);
    $accent = '#111111';
    $initial = strtoupper(substr(preg_replace('/[^a-z0-9]/i', '', $label) ?: 'W', 0, 1));

    $scene = match ($themeLabel) {
        'Biotech' => '
            <g fill="#ffffff" fill-opacity="0.18" stroke="#ffffff" stroke-opacity="0.22" stroke-width="4">
                <circle cx="560" cy="180" r="54"/>
                <circle cx="660" cy="250" r="32"/>
                <circle cx="505" cy="280" r="22"/>
                <path d="M540 220 L600 230 M600 230 L640 280 M520 300 L560 250" fill="none"/>
            </g>',
        'Tech' => '
            <g stroke="#ffffff" stroke-opacity="0.24" stroke-width="6" stroke-linecap="round">
                <path d="M500 160 H700"/>
                <path d="M500 210 H650"/>
                <path d="M500 260 H720"/>
                <path d="M500 310 H620"/>
            </g>
            <g fill="#ffffff" fill-opacity="0.16">
                <rect x="520" y="150" width="32" height="32" rx="8"/>
                <rect x="575" y="200" width="32" height="32" rx="8"/>
                <rect x="630" y="250" width="32" height="32" rx="8"/>
            </g>',
        'Commerce' => '
            <g fill="#ffffff" fill-opacity="0.18" stroke="#ffffff" stroke-opacity="0.2" stroke-width="4">
                <rect x="520" y="180" width="88" height="88" rx="16"/>
                <rect x="620" y="220" width="88" height="88" rx="16"/>
                <path d="M525 220 H603" fill="none"/>
            </g>',
        'Finance' => '
            <g fill="none" stroke="#ffffff" stroke-opacity="0.24" stroke-width="8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M500 300 C560 270, 600 260, 650 220 S730 160, 760 170"/>
            </g>
            <g fill="#ffffff" fill-opacity="0.16">
                <circle cx="520" cy="300" r="14"/>
                <circle cx="610" cy="255" r="14"/>
                <circle cx="700" cy="195" r="14"/>
            </g>',
        'Creative' => '
            <g fill="#ffffff" fill-opacity="0.18">
                <path d="M515 230 C550 190, 610 180, 660 220 C620 255, 560 275, 515 230 Z"/>
                <circle cx="665" cy="220" r="18" fill-opacity="0.3"/>
            </g>',
        'Education' => '
            <g fill="#ffffff" fill-opacity="0.18" stroke="#ffffff" stroke-opacity="0.22" stroke-width="4">
                <rect x="510" y="220" width="110" height="70" rx="12"/>
                <path d="M565 185 L705 240 L565 295 L425 240 Z"/>
            </g>',
        'Security' => '
            <g fill="#ffffff" fill-opacity="0.18" stroke="#ffffff" stroke-opacity="0.22" stroke-width="4">
                <path d="M620 160 L700 190 V250 C700 315, 655 350, 620 370 C585 350, 540 315, 540 250 V190 Z"/>
                <path d="M620 220 V285" stroke-width="8"/>
            </g>',
        default => '
            <g fill="#ffffff" fill-opacity="0.16">
                <circle cx="620" cy="235" r="40"/>
                <circle cx="690" cy="185" r="18"/>
            </g>',
    };

    $svg = sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 500" role="img" aria-label="AI-generated domain visual">
            <defs>
                <linearGradient id="g" x1="0%%" y1="0%%" x2="100%%" y2="100%%">
                    <stop offset="0%%" stop-color="%s"/>
                    <stop offset="100%%" stop-color="%s"/>
                </linearGradient>
                <radialGradient id="glow" cx="35%%" cy="25%%" r="80%%">
                    <stop offset="0%%" stop-color="#ffffff" stop-opacity="0.36"/>
                    <stop offset="100%%" stop-color="#ffffff" stop-opacity="0"/>
                </radialGradient>
            </defs>
            <rect width="800" height="500" rx="40" fill="url(#g)"/>
            <circle cx="620" cy="120" r="180" fill="url(#glow)"/>
            <circle cx="632" cy="118" r="58" fill="#ffffff" fill-opacity="0.14"/>
            <text x="632" y="136" text-anchor="middle" font-family="Material Symbols Outlined, Inter, Arial, sans-serif" font-size="56" font-weight="400" fill="#ffffff">%s</text>
            %s
            <path d="M0 360 C130 300, 240 280, 360 325 C470 367, 575 410, 800 320 L800 500 L0 500 Z" fill="%s" fill-opacity="0.18"/>
            <path d="M0 300 C130 250, 240 230, 360 270 C500 318, 600 352, 800 260" fill="none" stroke="#ffffff" stroke-opacity="0.28" stroke-width="10" stroke-linecap="round"/>
            <circle cx="168" cy="164" r="92" fill="#ffffff" fill-opacity="0.18"/>
            <circle cx="168" cy="164" r="66" fill="#ffffff" fill-opacity="0.26"/>
            <text x="168" y="186" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="78" font-weight="800" fill="#ffffff">%s</text>
            <text x="56" y="432" font-family="Inter, Arial, sans-serif" font-size="36" font-weight="700" fill="#ffffff" fill-opacity="0.92">%s</text>
            <text x="56" y="466" font-family="Inter, Arial, sans-serif" font-size="20" font-weight="600" fill="#ffffff" fill-opacity="0.75">%s</text>
        </svg>',
        htmlspecialchars($colorOne, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($colorTwo, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($accent, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($themeIcon, ENT_QUOTES, 'UTF-8'),
        $scene,
        htmlspecialchars($initial, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($themeLabel, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($themeHint, ENT_QUOTES, 'UTF-8')
    );

    return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
}

function whois_ai_idea_theme(string $text): array
{
    $text = strtolower($text);

    $themes = [
        ['keywords' => ['lab', 'bio', 'biotech', 'health', 'science', 'med', 'clinical'], 'label' => 'Biotech', 'icon' => 'biotech', 'hint' => 'Research', 'font' => 'Cormorant Garamond, serif', 'weight' => '700', 'tracking' => '-0.03em', 'transform' => 'none'],
        ['keywords' => ['ai', 'tech', 'cloud', 'code', 'app', 'software', 'saas', 'platform'], 'label' => 'Tech', 'icon' => 'terminal', 'hint' => 'Software', 'font' => 'Orbitron, sans-serif', 'weight' => '800', 'tracking' => '-0.08em', 'transform' => 'uppercase'],
        ['keywords' => ['shop', 'store', 'market', 'retail', 'cart', 'buy', 'sell'], 'label' => 'Commerce', 'icon' => 'shopping_bag', 'hint' => 'Retail', 'font' => 'Space Grotesk, sans-serif', 'weight' => '800', 'tracking' => '-0.06em', 'transform' => 'uppercase'],
        ['keywords' => ['finance', 'pay', 'money', 'bank', 'fund', 'capital'], 'label' => 'Finance', 'icon' => 'account_balance', 'hint' => 'Capital', 'font' => 'Cinzel, serif', 'weight' => '800', 'tracking' => '-0.04em', 'transform' => 'uppercase'],
        ['keywords' => ['design', 'studio', 'media', 'creative', 'brand', 'art'], 'label' => 'Creative', 'icon' => 'palette', 'hint' => 'Branding', 'font' => 'Bebas Neue, sans-serif', 'weight' => '400', 'tracking' => '0.03em', 'transform' => 'uppercase'],
        ['keywords' => ['school', 'edu', 'academy', 'learn', 'course', 'class'], 'label' => 'Education', 'icon' => 'school', 'hint' => 'Learning', 'font' => 'Fraunces, serif', 'weight' => '700', 'tracking' => '-0.03em', 'transform' => 'none'],
        ['keywords' => ['security', 'safe', 'guard', 'shield', 'privacy'], 'label' => 'Security', 'icon' => 'shield', 'hint' => 'Trust', 'font' => 'Orbitron, sans-serif', 'weight' => '900', 'tracking' => '-0.06em', 'transform' => 'uppercase'],
    ];

    foreach ($themes as $theme) {
        foreach ($theme['keywords'] as $keyword) {
            if (str_contains($text, $keyword)) {
                return $theme;
            }
        }
    }

    return ['keywords' => [], 'label' => 'Brand', 'icon' => 'workspace_premium', 'hint' => 'General', 'font' => 'Space Grotesk, sans-serif', 'weight' => '800', 'tracking' => '-0.05em', 'transform' => 'uppercase'];
}

$heroTlds = ['com', 'ai', 'io'];
$heroPrices = [];

foreach ($heroTlds as $heroTld) {
    $heroPrice = whois_truehost_tld_price($heroTld);
    $heroRaw = is_array($heroPrice) && isset($heroPrice['raw']) && is_numeric($heroPrice['raw']) ? (float) $heroPrice['raw'] : null;

    $heroPrices[$heroTld] = [
        'formatted' => $heroRaw !== null
            ? whois_currency_format_amount(whois_currency_convert_amount($heroRaw, 'KES', $selectedCurrency), $selectedCurrency)
            : 'Price unavailable',
    ];
}

$exactCandidateDomains = whois_domain_candidate_domains($searchStem, ['com', 'ai', 'io', 'co', 'net']);
$exactMatches = [];

foreach ($exactCandidateDomains as $candidateDomain) {
    $candidateLookup = whois_truehost_domain_lookup($candidateDomain);
    $candidateTld = substr($candidateLookup['domain'], (int) strrpos($candidateLookup['domain'], '.') + 1);
    $candidatePrice = whois_truehost_tld_price($candidateTld);
    $candidateRaw = is_array($candidatePrice) && isset($candidatePrice['raw']) && is_numeric($candidatePrice['raw']) ? (float) $candidatePrice['raw'] : null;

    $exactMatches[] = [
        'domain' => $candidateLookup['domain'],
        'description' => (string) ($candidateLookup['availabilityNote'] ?? 'Live registry lookup'),
        'status' => (string) ($candidateLookup['status'] ?? 'unknown'),
        'price' => $candidateRaw !== null
            ? whois_currency_format_amount(whois_currency_convert_amount($candidateRaw, 'KES', $selectedCurrency), $selectedCurrency)
            : 'Price unavailable',
    ];
}

$ideaPrompt = sprintf(
    'Generate 5 brandable domain names related to "%s". Return each idea on its own line as "Name - short reason" with no numbering or extra commentary.',
    $searchDomain
);

$aiSuggestions = [];

try {
    $aiResponse = whois_ai_request('domain_search', $ideaPrompt, [
        'domain' => $searchDomain,
        'availability' => $lookup,
    ]);

    foreach (preg_split('/\R+/', trim((string) $aiResponse['output'])) as $line) {
        $line = trim((string) $line);
        if ($line !== '') {
            $aiSuggestions[] = $line;
        }
    }
} catch (Throwable $exception) {
    $aiSuggestions = [
        'try' . $searchStem . '.com - A great call-to-action domain.',
        'get' . $searchStem . '.com - Perfect for an app or service.',
        $searchStem . 'hq.com - Establishes authority and presence.',
        $searchStem . 'hub.com - Great for a community or platform.',
    ];
}

$premiumTlds = ['com', 'io', 'ai'];
$premiumDomains = [];

foreach (array_slice($aiSuggestions, 0, 4) as $aiSuggestion) {
    $ideaText = trim((string) $aiSuggestion);
    $ideaParts = preg_split('/\s*[-–]\s*/', $ideaText, 2);
    $ideaName = $ideaParts[0] ?? $ideaText;
    $ideaReason = $ideaParts[1] ?? 'Live premium candidate';
    $ideaSlug = preg_replace('/[^a-z0-9]+/', '', strtolower($ideaName)) ?? '';

    if ($ideaSlug === '') {
        continue;
    }

    $selectedTld = null;
    foreach ($premiumTlds as $preferredTld) {
        if (whois_truehost_tld_price($preferredTld) !== null) {
            $selectedTld = $preferredTld;
            break;
        }
    }

    if ($selectedTld === null) {
        continue;
    }

    $candidateDomain = $ideaSlug . '.' . $selectedTld;
    $selectedLookup = whois_truehost_domain_lookup($candidateDomain);
    $selectedPrice = whois_truehost_tld_price($selectedTld);
    $selectedRaw = is_array($selectedPrice) && isset($selectedPrice['raw']) && is_numeric($selectedPrice['raw']) ? (float) $selectedPrice['raw'] : null;

    $premiumDomains[] = [
        'name' => $ideaName,
        'domain' => $candidateDomain,
        'reason' => $ideaReason,
        'status' => (string) ($selectedLookup['status'] ?? 'unknown'),
        'summary' => (string) ($selectedLookup['availabilityNote'] ?? 'Live registry lookup'),
        'price' => $selectedRaw !== null
            ? whois_currency_format_amount(whois_currency_convert_amount($selectedRaw, 'KES', $selectedCurrency), $selectedCurrency)
            : 'Price unavailable',
    ];
}

$premiumMarket = whois_premium_market_listings($searchDomain, [
    'lookup' => $lookup,
    'suggestions' => array_slice($aiSuggestions, 0, 4),
    'currency' => $selectedCurrency,
], $selectedCurrency);

$premiumCollection = is_array($premiumMarket['listings'] ?? null) ? $premiumMarket['listings'] : [];
$exactAvailableCount = 0;
$exactRegisteredCount = 0;

foreach ($exactMatches as $exactMatchCountEntry) {
    $exactStatus = strtolower((string) ($exactMatchCountEntry['status'] ?? ''));
    if ($exactStatus === 'available') {
        $exactAvailableCount++;
    } elseif ($exactStatus === 'registered' || $exactStatus === 'unavailable') {
        $exactRegisteredCount++;
    }
}

$verifiedPremiumCount = count($premiumCollection);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>WHOIS Comprehensive Results | <?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=Orbitron:wght@500;700;900&family=Cinzel:wght@600;700;800&family=Cormorant+Garamond:wght@600;700&family=Bebas+Neue&family=Space+Grotesk:wght@500;700;800&family=Fraunces:wght@600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; }
        h1, h2, h3, .headline { font-family: 'Manrope', sans-serif; }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#000000",
                        surface: "#f9f9f9",
                        "on-surface": "#1a1c1c",
                        "on-surface-variant": "#474747",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f3f3",
                        "surface-container": "#eeeeee",
                        "surface-container-high": "#e8e8e8",
                        outline: "#777777",
                        "outline-variant": "#c6c6c6",
                        secondary: "#5e5e5e"
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Inter"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-surface text-on-surface selection:bg-black selection:text-white pb-24">
    <?php require __DIR__ . '/_top_nav.php'; ?>

    <!-- Hero Search Section -->
    <header class="bg-surface-container-low pt-24 pb-16 px-6 border-b border-outline-variant/30">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tighter text-primary mb-4 leading-tight break-all">
                <?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?>
            </h1>
            <p class="text-on-surface-variant max-w-2xl mx-auto mb-10 text-lg">
                Search and compare live registry status, related alternatives, and AI-generated naming options.
            </p>
            
            <div class="max-w-3xl mx-auto mb-8">
                <div class="relative group shadow-sm hover:shadow-md transition-shadow rounded-full bg-white">
                    <input id="comprehensive-search-input" class="w-full h-16 pl-8 pr-40 rounded-full border border-outline-variant bg-transparent text-xl font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all" type="text" value="<?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?>"/>
                    <button id="comprehensive-search-button" class="absolute right-2 top-2 bottom-2 px-8 rounded-full bg-primary text-white font-bold hover:bg-neutral-800 transition-colors" type="button">Search</button>
                </div>
            </div>

            <!-- Stats & TLD Pricing Bar -->
            <div class="flex flex-wrap justify-center gap-3 items-center max-w-4xl mx-auto bg-white p-3 rounded-2xl border border-outline-variant/30 shadow-sm">
                <span class="px-4 py-1.5 rounded-full bg-surface-container text-xs font-bold text-primary uppercase tracking-widest">Live Pricing</span>
                <?php foreach ($heroPrices as $heroTld => $heroPrice): ?>
                    <span class="px-3 py-1.5 rounded text-sm font-semibold text-secondary flex items-center gap-1">
                        <span class="text-primary font-bold">.<?php echo htmlspecialchars($heroTld, ENT_QUOTES, 'UTF-8'); ?></span> 
                        <?php echo htmlspecialchars($heroPrice['formatted'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                <?php endforeach; ?>
                <div class="w-px h-6 bg-outline-variant mx-2 hidden sm:block"></div>
                <span class="px-3 py-1.5 text-xs font-semibold text-secondary flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">public</span> Global Registry
                </span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <!-- Dashboard Summary Counters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-6 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-800 mb-1">Available Domains</p>
                    <p class="text-3xl font-black text-emerald-900"><?php echo (string) $exactAvailableCount; ?></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50/50 p-6 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-rose-800 mb-1">Registered Domains</p>
                    <p class="text-3xl font-black text-rose-900"><?php echo (string) $exactRegisteredCount; ?></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                    <span class="material-symbols-outlined">lock</span>
                </div>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-6 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-800 mb-1">Verified Premium</p>
                    <p class="text-3xl font-black text-amber-900"><?php echo (string) $verifiedPremiumCount; ?></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined">workspace_premium</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">
            <!-- LEFT COLUMN: Status & Brokerage -->
            <aside class="xl:col-span-4 space-y-8">
                <!-- Status Card -->
                <div class="bg-white p-8 rounded-2xl border border-outline-variant/30 shadow-sm">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-2xl font-extrabold tracking-tighter break-all pr-4"><?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?></h2>
                        <span class="<?php echo htmlspecialchars($availabilityBadgeClass, ENT_QUOTES, 'UTF-8'); ?> text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-widest shrink-0">
                            <?php echo htmlspecialchars($availabilityHeadline, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <p class="text-sm text-on-surface-variant mb-8 leading-relaxed bg-surface-container-low p-4 rounded-xl border border-outline-variant/20">
                        <?php echo htmlspecialchars($lookupNote ?: 'Live registry check completed.', ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <div class="space-y-3 text-xs uppercase tracking-[0.15em] text-secondary font-semibold">
                        <div class="flex justify-between items-center border-b border-outline-variant/20 pb-2">
                            <span>Source</span> 
                            <span class="text-primary tracking-normal font-bold">Global Registry</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Currency</span> 
                            <span class="text-primary tracking-normal font-bold"><?php echo htmlspecialchars($selectedCurrency, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Brokerage / Action Card -->
                <div class="<?php echo $lookupStatus === 'available' ? 'bg-emerald-700 text-white' : 'bg-neutral-900 text-white'; ?> p-8 rounded-2xl shadow-lg relative overflow-hidden">
                    <!-- Decorative background element -->
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-5 rounded-full blur-2xl"></div>
                    
                    <?php if ($lookupStatus === 'available'): ?>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="material-symbols-outlined text-emerald-300 text-3xl">shopping_cart_checkout</span>
                            <h3 class="text-xl font-bold font-['Manrope']">Secure it now</h3>
                        </div>
                        <p class="text-sm text-emerald-50 mb-8 leading-relaxed">This domain is publicly available. Register it immediately before someone else claims your brand.</p>
                        <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($searchDomain); ?>" target="_blank" class="block w-full bg-white text-emerald-900 text-center font-black py-4 rounded-xl hover:bg-emerald-50 transition-colors shadow-md">
                            Register Domain
                        </a>
                    <?php else: ?>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="material-symbols-outlined text-amber-400 text-3xl">handshake</span>
                            <h3 class="text-xl font-bold font-['Manrope']">Acquire this domain</h3>
                        </div>
                        <p class="text-sm text-neutral-300 mb-8 leading-relaxed">This name is already taken. Hire a professional domain broker to discreetly negotiate an acquisition on your behalf.</p>
                        
                        <div class="flex items-center justify-between gap-4 mb-6 pb-6 border-b border-neutral-700">
                            <span class="text-neutral-400 text-xs uppercase tracking-widest font-bold">Brokerage Fee</span>
                            <span class="font-black text-xl text-white"><?php echo htmlspecialchars($brokerageFeeLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        
                        <a href="<?php echo htmlspecialchars($brokerageUrl, ENT_QUOTES, 'UTF-8'); ?>" class="block w-full bg-amber-400 text-neutral-950 text-center font-black py-4 rounded-xl hover:bg-amber-300 transition-colors shadow-md flex justify-center items-center gap-2">
                            Hire a Broker <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    <?php endif; ?>
                </div>
            </aside>

            <!-- CENTER & RIGHT: Exact Matches & Premiums -->
            <div class="xl:col-span-8 space-y-10">
                
                <!-- Exact Matches Table/List -->
                <section>
                    <div class="mb-4 flex items-end justify-between">
                        <div>
                            <h3 class="text-2xl font-extrabold text-primary tracking-tight">Exact Matches</h3>
                            <p class="text-sm text-secondary mt-1">Live status across major extensions.</p>
                        </div>
                        <span class="text-xs font-bold text-neutral-400 bg-white px-3 py-1 rounded-full border border-outline-variant/30"><?php echo count($exactMatches); ?> Results</span>
                    </div>
                    
                    <div class="bg-white rounded-2xl overflow-hidden border border-outline-variant/30 shadow-sm">
                        <div class="divide-y divide-outline-variant/20">
                            <?php foreach ($exactMatches as $exactMatch): ?>
                                <?php $exactStatusDetails = whois_search_status_details((string) ($exactMatch['status'] ?? '')); ?>
                                <div class="p-4 md:p-6 flex flex-col md:flex-row md:items-center justify-between hover:bg-surface-container-low transition-colors group gap-4">
                                    <div>
                                        <p class="text-lg font-bold tracking-tight text-primary"><?php echo htmlspecialchars($exactMatch['domain'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p class="text-xs text-secondary mt-1"><?php echo htmlspecialchars($exactMatch['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                    <div class="flex items-center justify-between md:justify-end gap-6 md:w-1/2">
                                        <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full <?php echo htmlspecialchars($exactStatusDetails['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($exactStatusDetails['label'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                        <div class="flex items-center gap-4 min-w-[120px] justify-end">
                                            <span class="font-black text-lg text-primary"><?php echo htmlspecialchars($exactMatch['price'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php if (strtolower((string)$exactMatch['status']) === 'available'): ?>
                                                <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($exactMatch['domain']); ?>" target="_blank" class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center hover:bg-neutral-800 transition-transform hover:scale-105" title="Add to cart">
                                                    <span class="material-symbols-outlined text-sm">add</span>
                                                </a>
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-neutral-100 text-neutral-400 flex items-center justify-center cursor-not-allowed" title="Taken">
                                                    <span class="material-symbols-outlined text-sm">lock</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <!-- Premium Market Section -->
                <?php if ($premiumCollection !== []): ?>
                <section class="pt-6">
                    <div class="flex items-end justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-extrabold text-primary tracking-tight">Verified Premium Offers</h3>
                            <p class="text-sm text-secondary mt-1">High-value assets available for immediate acquisition.</p>
                        </div>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full border border-amber-200 shadow-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">verified</span> Live API
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($premiumCollection as $collectionItem): ?>
                            <?php $collectionStatusDetails = whois_search_premium_status_details((array) $collectionItem); ?>
                            <div class="bg-white p-6 rounded-2xl border border-outline-variant/30 flex flex-col justify-between gap-4 hover:border-amber-300 hover:shadow-md transition-all group">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-xl font-extrabold tracking-tight text-primary mb-1"><?php echo htmlspecialchars((string) $collectionItem['domain'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                        <span class="text-[10px] font-bold text-secondary uppercase tracking-widest bg-surface-container px-2 py-0.5 rounded">
                                            <?php echo htmlspecialchars((string) $collectionItem['category'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                    <span class="px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest <?php echo htmlspecialchars($collectionStatusDetails['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($collectionStatusDetails['label'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </div>
                                <p class="text-xs text-on-surface-variant leading-relaxed line-clamp-2"><?php echo htmlspecialchars((string) $collectionItem['reason'], ENT_QUOTES, 'UTF-8'); ?></p>
                                
                                <div class="mt-2 pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                                    <div>
                                        <p class="text-[10px] uppercase text-secondary font-bold tracking-widest mb-0.5">Asking Price</p>
                                        <p class="text-xl font-black text-primary"><?php echo htmlspecialchars((string) $collectionItem['ask'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                    <button class="bg-black text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-neutral-800 transition-colors">View Offer</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

            </div>
        </div>

        <!-- AI GENERATED IDEAS SECTION -->
        <section class="mt-24">
            <div class="flex items-center gap-6 mb-10">
                <div class="h-px bg-outline-variant/50 flex-grow"></div>
                <div class="flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-full text-indigo-700">
                    <span class="material-symbols-outlined text-[18px]">auto_awesome</span>
                    <h3 class="text-sm font-bold tracking-[0.2em] uppercase">AI-Generated Ideas</h3>
                </div>
                <div class="h-px bg-outline-variant/50 flex-grow"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($aiSuggestions as $aiSuggestion): ?>
                    <?php
                        $ideaParts = preg_split('/\s*[-–]\s*/', (string) $aiSuggestion, 2);
                        $ideaName = trim((string) ($ideaParts[0] ?? $aiSuggestion));
                        $ideaReason = trim((string) ($ideaParts[1] ?? 'Brandable domain idea for this search.'));
                        $ideaTheme = whois_ai_idea_theme($ideaName . ' ' . $ideaReason);
                        
                        // Generate the stunning SVG image from the function
                        $svgDataUri = whois_ai_idea_card_image($searchDomain, $ideaName, $ideaTheme);
                    ?>
                    <div class="rounded-2xl border border-outline-variant/30 bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl group flex flex-col overflow-hidden cursor-pointer">
                        
                        <!-- Top Image Banner (Using the generated SVG) -->
                        <div class="w-full aspect-[16/10] bg-surface-container overflow-hidden border-b border-outline-variant/20">
                            <img src="<?php echo $svgDataUri; ?>" alt="AI Generation for <?php echo htmlspecialchars($ideaName, ENT_QUOTES, 'UTF-8'); ?>" class="w-full h-full object-cover" />
                        </div>
                        
                        <!-- Content Area -->
                        <div class="p-5 flex-grow flex flex-col justify-between bg-white">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <p class="font-black text-xl text-primary tracking-tight"><?php echo htmlspecialchars($ideaName, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <p class="text-xs text-on-surface-variant leading-relaxed mb-4 line-clamp-3">
                                    <?php echo htmlspecialchars($ideaReason, ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>
                            
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="bg-surface-container px-2 py-1 rounded text-[9px] font-bold text-secondary uppercase tracking-widest">
                                        <?php echo htmlspecialchars((string) $ideaTheme['label'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <span class="bg-surface-container px-2 py-1 rounded text-[9px] font-bold text-secondary uppercase tracking-widest">
                                        AI Match
                                    </span>
                                </div>
                                
                                <a href="?query=<?php echo urlencode($ideaName); ?>&currency=<?php echo urlencode($selectedCurrency); ?>" class="flex items-center justify-between w-full pt-3 border-t border-outline-variant/20 text-sm font-bold text-primary group-hover:text-indigo-600 transition-colors">
                                    <span>Check Availability</span>
                                    <span class="material-symbols-outlined text-[16px] transform group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="mt-32 max-w-3xl mx-auto mb-16">
            <div class="text-center mb-10">
                <h3 class="text-3xl font-extrabold tracking-tight text-primary">Frequently Asked Questions</h3>
                <p class="text-secondary mt-3">Everything you need to know about domain acquisitions.</p>
            </div>
            
            <div class="space-y-4" id="faq-accordion">
                <!-- FAQ Item 1 -->
                <div class="bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-sm transition-all hover:border-primary/30">
                    <button class="w-full flex justify-between items-center text-left font-bold text-lg text-primary faq-toggle">
                        <span>What is a domain brokerage service?</span>
                        <span class="material-symbols-outlined transform transition-transform duration-300">expand_more</span>
                    </button>
                    <div class="faq-content hidden mt-4 text-on-surface-variant text-sm leading-relaxed">
                        Domain brokerage is a service where a professional negotiator acts on your behalf to acquire a domain name that is already registered. They leverage their industry connections and negotiation tactics to secure the asset at the best possible price, keeping your identity anonymous.
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-sm transition-all hover:border-primary/30">
                    <button class="w-full flex justify-between items-center text-left font-bold text-lg text-primary faq-toggle">
                        <span>How long does a domain transfer take?</span>
                        <span class="material-symbols-outlined transform transition-transform duration-300">expand_more</span>
                    </button>
                    <div class="faq-content hidden mt-4 text-on-surface-variant text-sm leading-relaxed">
                        Once a price is agreed upon and funds are secured in escrow, the actual technical transfer of the domain usually takes between 1 to 7 days, depending on the registrars involved and whether security holds (like ICANN's 60-day lock) apply.
                    </div>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-sm transition-all hover:border-primary/30">
                    <button class="w-full flex justify-between items-center text-left font-bold text-lg text-primary faq-toggle">
                        <span>Are there hidden fees for renewals?</span>
                        <span class="material-symbols-outlined transform transition-transform duration-300">expand_more</span>
                    </button>
                    <div class="faq-content hidden mt-4 text-on-surface-variant text-sm leading-relaxed">
                        No. The price you pay to acquire a premium domain is usually a one-time fee. Once you own the domain, you only pay the standard annual renewal fee determined by your registrar (typically $10-$50/year depending on the extension).
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php require __DIR__ . '/_footer.php'; ?>

    <script>
        // Search routing logic
        (function () {
            const input = document.getElementById('comprehensive-search-input');
            const button = document.getElementById('comprehensive-search-button');
            const currency = <?php echo json_encode($selectedCurrency); ?>;

            function goToResults() {
                const query = input ? input.value.trim() : '';
                if (!query) {
                    if (input) input.focus();
                    return;
                }
                window.location.href = '/pages/whois_comprehensive_search_results.php?query=' + encodeURIComponent(query) + '&currency=' + encodeURIComponent(currency);
            }

            if (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    goToResults();
                });
            }

            if (input) {
                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        goToResults();
                    }
                });
            }
            
            // Simple FAQ Accordion Script
            const toggles = document.querySelectorAll('.faq-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const content = toggle.nextElementSibling;
                    const icon = toggle.querySelector('.material-symbols-outlined');
                    
                    // Close all other accordions (Optional, comment out if you want multiple open at once)
                    document.querySelectorAll('.faq-content').forEach(c => {
                        if (c !== content && !c.classList.contains('hidden')) {
                            c.classList.add('hidden');
                            c.previousElementSibling.querySelector('.material-symbols-outlined').classList.remove('rotate-180');
                        }
                    });

                    // Toggle current
                    content.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                });
            });
        })();
    </script>
    <script src="../assets/js/nav-state.js"></script>
</body>
</html>