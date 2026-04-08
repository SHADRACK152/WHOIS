<?php
declare(strict_types=1);

require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/domain-lookup.php';
require __DIR__ . '/../../app/currency.php';
require __DIR__ . '/../../app/truehost-client.php';
require __DIR__ . '/../../app/grok-client.php';
require __DIR__ . '/../../app/premium-market.php';

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
  ? 'bg-green-100 text-green-700'
  : 'bg-surface-container text-secondary';
$availabilityHeadline = $lookupStatus === 'available' ? 'Available' : ($lookupStatus === 'unavailable' ? 'Registered' : 'Unknown');
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
      'class' => 'bg-green-100 text-green-700',
    ];
  }

  if ($status === 'registered' || $status === 'unavailable') {
    return [
      'label' => 'Registered',
      'class' => 'bg-rose-100 text-rose-700',
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
    'class' => 'bg-surface-container text-secondary',
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
    'marketed' => 'bg-blue-100 text-blue-700',
    'offer' => 'bg-emerald-100 text-emerald-700',
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
    'status' => (string) ($candidateLookup['statusLabel'] ?? 'Unknown'),
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
    'Suggestions unavailable - ' . $exception->getMessage(),
  ];
}

$premiumTlds = ['com', 'io', 'ai'];
$premiumDomains = [];

foreach (array_slice($aiSuggestions, 0, 4) as $aiSuggestion) {
  $ideaText = trim((string) $aiSuggestion);
  $ideaName = preg_split('/\s*-\s*/', $ideaText, 2)[0] ?? $ideaText;
  $ideaReason = preg_split('/\s*-\s*/', $ideaText, 2)[1] ?? 'Live premium candidate';
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
    'status' => (string) ($selectedLookup['statusLabel'] ?? 'Unknown'),
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

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS Comprehensive Results | <?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?></title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=Orbitron:wght@500;700;900&amp;family=Cinzel:wght@600;700;800&amp;family=Cormorant+Garamond:wght@600;700&amp;family=Bebas+Neue&amp;family=Space+Grotesk:wght@500;700;800&amp;family=Fraunces:wght@600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
        }
        h1, h2, h3, .headline {
            font-family: 'Manrope', sans-serif;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-dim": "#dadada",
                        "secondary-container": "#d5d4d4",
                        "tertiary-fixed-dim": "#454747",
                        "on-secondary-fixed": "#1b1c1c",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-surface-variant": "#474747",
                        "surface-bright": "#f9f9f9",
                        "on-tertiary-fixed": "#ffffff",
                        "on-secondary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "secondary": "#5e5e5e",
                        "on-primary": "#e2e2e2",
                        "surface-container-high": "#e8e8e8",
                        "inverse-primary": "#c6c6c6",
                        "on-error-container": "#410002",
                        "background": "#f9f9f9",
                        "secondary-fixed": "#c7c6c6",
                        "on-tertiary-container": "#ffffff",
                        "surface-container-low": "#f3f3f3",
                        "on-surface": "#1a1c1c",
                        "on-background": "#1a1c1c",
                        "error": "#ba1a1a",
                        "primary-container": "#3b3b3b",
                        "primary-fixed": "#5e5e5e",
                        "outline": "#777777",
                        "on-secondary-container": "#1b1c1c",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "surface-variant": "#e2e2e2",
                        "on-primary-fixed": "#ffffff",
                        "outline-variant": "#c6c6c6",
                        "secondary-fixed-dim": "#acabab",
                        "tertiary": "#3a3c3c",
                        "surface-tint": "#5e5e5e",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-error": "#ffffff",
                        "inverse-on-surface": "#f1f1f1",
                        "surface-container": "#eeeeee",
                        "tertiary-fixed": "#5d5f5f",
                        "error-container": "#ffdad6",
                        "primary-fixed-dim": "#474747",
                        "on-tertiary": "#e2e2e2",
                        "surface-container-highest": "#e2e2e2",
                        "surface": "#f9f9f9",
                        "on-primary-container": "#ffffff",
                        "inverse-surface": "#2f3131",
                        "primary": "#000000",
                        "tertiary-container": "#737575"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-surface text-on-surface">
<?php require __DIR__ . '/_top_nav.php'; ?>
<!-- Hero Search Section -->
<header class="bg-surface-container-low pt-16 pb-20 px-8">
<div class="max-w-[1440px] mx-auto text-center">
<h1 class="text-5xl font-extrabold tracking-tighter text-primary mb-4 leading-tight"><?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?></h1>
<p class="text-on-surface-variant max-w-2xl mx-auto mb-10 text-lg">Search and compare the live registry status, related alternatives, and AI-generated naming options for this domain.</p>
<div class="max-w-3xl mx-auto mb-8">
<div class="relative group">
<input id="comprehensive-search-input" class="w-full h-16 pl-6 pr-40 rounded-full border-outline-variant bg-surface-container-lowest text-xl font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all shadow-sm group-hover:shadow-md" type="text" value="<?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?>"/>
<button id="comprehensive-search-button" class="absolute right-2 top-2 bottom-2 px-10 rounded-full bg-primary text-on-primary font-bold hover:bg-primary-container transition-colors" type="button">Search</button>
</div>
</div>
<div class="flex flex-wrap justify-center gap-3 items-center">
<span class="px-4 py-2 rounded-full bg-surface-container-low text-sm font-semibold text-primary">Global registry availability</span>
<span class="px-4 py-2 rounded-full bg-surface-container-low text-sm font-semibold text-primary"><?php echo htmlspecialchars($lookupStatusLabel, ENT_QUOTES, 'UTF-8'); ?></span>
<span class="px-4 py-2 rounded-full bg-surface-container-low text-sm font-semibold text-primary">USD only</span>
<?php foreach ($heroPrices as $heroTld => $heroPrice): ?>
<span class="px-4 py-2 rounded-full bg-surface-container-low text-sm font-semibold text-primary">.<?php echo htmlspecialchars($heroTld, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($heroPrice['formatted'] ?? 'Price unavailable', ENT_QUOTES, 'UTF-8'); ?></span>
<?php endforeach; ?>
</div>
<div class="mt-8 grid gap-3 sm:grid-cols-3 max-w-5xl mx-auto text-left">
<div class="rounded-2xl border border-outline-variant/30 bg-surface-container-lowest p-4">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Available</p>
<p class="mt-2 text-2xl font-black text-green-700"><?php echo (string) $exactAvailableCount; ?></p>
<p class="text-xs text-on-surface-variant mt-1">Exact matches marked open for registration.</p>
</div>
<div class="rounded-2xl border border-outline-variant/30 bg-surface-container-lowest p-4">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Registered</p>
<p class="mt-2 text-2xl font-black text-rose-700"><?php echo (string) $exactRegisteredCount; ?></p>
<p class="text-xs text-on-surface-variant mt-1">Exact matches already taken in registry lookup.</p>
</div>
<div class="rounded-2xl border border-outline-variant/30 bg-surface-container-lowest p-4">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Verified premium / priced</p>
<p class="mt-2 text-2xl font-black text-amber-700"><?php echo (string) $verifiedPremiumCount; ?></p>
<p class="text-xs text-on-surface-variant mt-1">Offers confirmed by Domainr before display.</p>
</div>
</div>
</div>
</div>
</header>
<main class="max-w-[1440px] mx-auto px-8 py-12">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
<!-- LEFT COLUMN: Status & Brokerage -->
<aside class="lg:col-span-3 space-y-8">
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30">
<div class="flex justify-between items-start mb-4">
<h2 class="text-2xl font-extrabold tracking-tighter"><?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?></h2>
<span class="<?php echo htmlspecialchars($availabilityBadgeClass, ENT_QUOTES, 'UTF-8'); ?> text-[10px] font-bold px-2 py-1 rounded uppercase"><?php echo htmlspecialchars($availabilityHeadline, ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<p class="text-sm text-on-surface-variant mb-6 leading-relaxed"><?php echo htmlspecialchars($lookupNote, ENT_QUOTES, 'UTF-8'); ?></p>
<div class="space-y-2 text-[11px] uppercase tracking-[0.18em] text-secondary">
<div>Source: <span class="text-on-surface-variant normal-case tracking-normal">Global registry lookup</span></div>
<div>Currency: <span class="text-on-surface-variant normal-case tracking-normal">USD</span></div>
</div>
<div class="h-1 bg-surface-variant w-full overflow-hidden rounded-full">
<div class="bg-primary h-full w-full opacity-10"></div>
</div>
</div>
<div class="bg-primary text-on-primary p-6 rounded-xl">
<?php if ($lookupStatus === 'available'): ?>
<h3 class="text-lg font-bold mb-2 font-['Manrope']">Global availability feed</h3>
<p class="text-sm text-on-primary/70 mb-6 font-['Inter'] leading-relaxed">Live registration status is pulled from the global registry lookup and shown in USD only.</p>
<div class="space-y-3 text-sm">
<?php foreach ($heroPrices as $heroTld => $heroPrice): ?>
<div class="flex items-center justify-between gap-4">
<span>.<?php echo htmlspecialchars($heroTld, ENT_QUOTES, 'UTF-8'); ?></span>
<span class="font-bold"><?php echo htmlspecialchars($heroPrice['formatted'] ?? 'Price unavailable', ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<h3 class="text-lg font-bold mb-2 font-['Manrope']">Need this domain?</h3>
<p class="text-sm text-on-primary/70 mb-6 font-['Inter'] leading-relaxed">This name is already taken. Hire a broker to approach the owner and negotiate an acquisition on your behalf.</p>
<div class="flex items-center justify-between gap-4 mb-6 text-sm">
<span class="text-on-primary/70"><?php echo htmlspecialchars($brokerageFeeLabel, ENT_QUOTES, 'UTF-8'); ?></span>
<span class="font-bold"><?php echo htmlspecialchars($availabilityHeadline, ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<a class="block w-full bg-white text-black text-center font-black py-4 rounded-xl hover:bg-neutral-200 transition-colors" href="<?php echo htmlspecialchars($brokerageUrl, ENT_QUOTES, 'UTF-8'); ?>">Hire a Broker</a>
<?php endif; ?>
</div>
</aside>
<!-- CENTER COLUMN: Exact Matches -->
<section class="lg:col-span-5">
<div class="mb-6 flex items-center justify-between">
<h3 class="text-sm font-bold uppercase tracking-[0.2em] text-on-surface-variant">Exact Matches by State</h3>
<span class="text-xs text-neutral-400"><?php echo count($exactMatches); ?> Results found</span>
</div>
<div class="bg-surface-container-lowest rounded-xl divide-y divide-outline-variant/20 overflow-hidden border border-outline-variant/30">
<?php foreach ($exactMatches as $exactMatch): ?>
<?php $exactStatusDetails = whois_search_status_details((string) ($exactMatch['status'] ?? '')); ?>
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight"><?php echo htmlspecialchars($exactMatch['domain'], ENT_QUOTES, 'UTF-8'); ?></p>
<p class="text-xs text-secondary"><?php echo htmlspecialchars($exactMatch['description'], ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary"><?php echo htmlspecialchars($exactMatch['price'], ENT_QUOTES, 'UTF-8'); ?></span>
<span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-full <?php echo htmlspecialchars($exactStatusDetails['class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($exactStatusDetails['label'], ENT_QUOTES, 'UTF-8'); ?></span>
</div>
</div>
<?php endforeach; ?>
</div>
</section>
<!-- RIGHT COLUMN: Premium Domains -->
<section class="lg:col-span-4">
<div class="mb-6">
<h3 class="text-sm font-bold uppercase tracking-[0.2em] text-on-surface-variant">Brandable Alternatives</h3>
</div>
<div class="grid grid-cols-1 gap-4">
<?php foreach ($premiumDomains as $premiumDomain): ?>
<div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/30 flex justify-between items-center group cursor-pointer hover:border-primary transition-all">
<div>
<h4 class="text-lg font-extrabold tracking-tight"><?php echo htmlspecialchars($premiumDomain['domain'], ENT_QUOTES, 'UTF-8'); ?></h4>
<p class="text-[10px] font-bold text-secondary uppercase tracking-widest mt-1"><?php echo htmlspecialchars($premiumDomain['name'], ENT_QUOTES, 'UTF-8'); ?></p>
<p class="text-[11px] text-on-surface-variant mt-2"><?php echo htmlspecialchars($premiumDomain['summary'], ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<div class="text-right">
<span class="inline-flex items-center rounded-full px-2 py-1 text-[10px] font-bold uppercase tracking-widest <?php echo ($premiumDomain['status'] === 'Available') ? 'bg-green-100 text-green-700' : 'bg-surface-container text-secondary'; ?>"><?php echo htmlspecialchars($premiumDomain['status'], ENT_QUOTES, 'UTF-8'); ?></span>
<p class="text-sm font-bold mt-3 <?php echo ($premiumDomain['status'] === 'Available') ? 'text-green-700' : 'text-secondary'; ?>"><?php echo htmlspecialchars($premiumDomain['price'], ENT_QUOTES, 'UTF-8'); ?></p>
<span class="text-[10px] text-secondary">Live registration price</span>
</div>
</div>
<?php endforeach; ?>
</div>
<button class="w-full mt-6 py-4 rounded-xl border border-primary text-primary font-bold hover:bg-primary hover:text-on-primary transition-all flex items-center justify-center gap-2">
                    Explore Brandable Candidates
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</section>
</div>
<!-- PREMIUM MARKET SECTION -->
<section class="mt-16">
<div class="flex items-center justify-between mb-6">
<div>
<h3 class="text-sm font-bold uppercase tracking-[0.2em] text-on-surface-variant">Verified Premium Offers</h3>
<p class="text-xs text-secondary mt-2">Domainr verifies premium or priced status before any value is shown</p>
</div>
<span class="text-xs text-neutral-400"><?php echo htmlspecialchars((string) ($premiumMarket['ok'] ? 'Live API' : 'API unavailable'), ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<?php if ($premiumCollection !== []): ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<?php foreach ($premiumCollection as $collectionItem): ?>
<?php $collectionStatusDetails = whois_search_premium_status_details((array) $collectionItem); ?>
<div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/30 flex items-center justify-between gap-4 hover:border-primary transition-all">
<div>
<h4 class="text-lg font-extrabold tracking-tight"><?php echo htmlspecialchars((string) $collectionItem['domain'], ENT_QUOTES, 'UTF-8'); ?></h4>
<p class="text-[10px] font-bold text-secondary uppercase tracking-widest mt-1"><?php echo htmlspecialchars((string) $collectionItem['category'], ENT_QUOTES, 'UTF-8'); ?></p>
<p class="text-[11px] text-on-surface-variant mt-2 max-w-[22rem] leading-relaxed"><?php echo htmlspecialchars((string) $collectionItem['reason'], ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<div class="text-right shrink-0">
<p class="text-lg font-black mb-1"><?php echo htmlspecialchars((string) $collectionItem['ask'], ENT_QUOTES, 'UTF-8'); ?></p>
<p class="text-[10px] text-secondary mb-3">Offer: <?php echo htmlspecialchars((string) $collectionItem['appraisal'], ENT_QUOTES, 'UTF-8'); ?></p>
<button class="px-4 py-1.5 rounded-lg text-[11px] font-bold <?php echo htmlspecialchars($collectionStatusDetails['class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($collectionStatusDetails['label'], ENT_QUOTES, 'UTF-8'); ?></button>
</div>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<div class="rounded-xl border border-outline-variant/30 bg-surface-container-lowest p-6 text-sm text-on-surface-variant">
No verified premium offers were returned. Set DOMAINR_RAPIDAPI_KEY to enable premium-status checks, or continue using the brandable ideas below.
</div>
<?php endif; ?>
</section>
<!-- AI GENERATED IDEAS SECTION -->
<section class="mt-20">
<div class="flex items-center gap-4 mb-8">
<div class="h-px bg-outline-variant flex-grow"></div>
<h3 class="label-md font-bold text-on-surface-variant tracking-[0.3em] uppercase">AI-Generated Ideas</h3>
<div class="h-px bg-outline-variant flex-grow"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
<?php foreach ($aiSuggestions as $aiSuggestion): ?>
<?php
  $ideaParts = preg_split('/\s*[-–]\s*/', (string) $aiSuggestion, 2);
  $ideaName = trim((string) ($ideaParts[0] ?? $aiSuggestion));
  $ideaReason = trim((string) ($ideaParts[1] ?? 'Brandable domain idea for this search.'));
  $ideaTheme = whois_ai_idea_theme($ideaName . ' ' . $ideaReason);
  $ideaHash = substr(md5($searchDomain . '|' . $ideaName . '|' . (string) $ideaTheme['label']), 0, 12);
  $ideaColorOne = '#' . substr($ideaHash, 0, 6);
  $ideaColorTwo = '#' . substr($ideaHash, 6, 6);
?>
<div class="overflow-hidden rounded-[1.25rem] border border-outline-variant/30 bg-surface-container-low shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg group">
<div class="relative h-32 overflow-hidden" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($ideaColorOne, ENT_QUOTES, 'UTF-8'); ?> 0%, <?php echo htmlspecialchars($ideaColorTwo, ENT_QUOTES, 'UTF-8'); ?> 100%);">
<div class="absolute inset-0 opacity-60" style="background-image: radial-gradient(circle at 80% 20%, rgba(255,255,255,0.28) 0 12%, transparent 13%), radial-gradient(circle at 20% 80%, rgba(255,255,255,0.14) 0 10%, transparent 11%), radial-gradient(circle at 50% 50%, rgba(255,255,255,0.08) 0 18%, transparent 19%);"></div>
<div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white shadow-sm backdrop-blur-sm">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">
<?php echo htmlspecialchars((string) $ideaTheme['icon'], ENT_QUOTES, 'UTF-8'); ?>
</span>
<span><?php echo htmlspecialchars((string) $ideaTheme['label'], ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<div class="absolute inset-x-4 bottom-4">
<div class="text-white leading-none" style="font-family: <?php echo htmlspecialchars((string) $ideaTheme['font'], ENT_QUOTES, 'UTF-8'); ?>; font-weight: <?php echo htmlspecialchars((string) $ideaTheme['weight'], ENT_QUOTES, 'UTF-8'); ?>; letter-spacing: <?php echo htmlspecialchars((string) $ideaTheme['tracking'], ENT_QUOTES, 'UTF-8'); ?>; text-transform: <?php echo htmlspecialchars((string) $ideaTheme['transform'], ENT_QUOTES, 'UTF-8'); ?>; font-size: clamp(1.4rem, 3vw, 2.2rem); text-shadow: 0 10px 24px rgba(0,0,0,0.22);">
<?php echo htmlspecialchars($ideaName, ENT_QUOTES, 'UTF-8'); ?>
</div>
<div class="mt-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-white/75">
<?php echo htmlspecialchars((string) $ideaTheme['hint'], ENT_QUOTES, 'UTF-8'); ?>
</div>
</div>
</div>
<div class="p-4 text-left">
<p class="font-bold text-lg mb-1.5 leading-tight"><?php echo htmlspecialchars($ideaName, ENT_QUOTES, 'UTF-8'); ?></p>
<p class="text-xs text-on-surface-variant leading-relaxed mb-3 line-clamp-3"><?php echo htmlspecialchars($ideaReason, ENT_QUOTES, 'UTF-8'); ?></p>
<p class="text-[10px] uppercase text-secondary font-bold tracking-widest mb-2">Use case: <?php echo htmlspecialchars((string) $ideaTheme['label'], ENT_QUOTES, 'UTF-8'); ?></p>
<p class="text-[10px] uppercase text-secondary font-bold tracking-widest mb-4">Related to <?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?></p>
<span class="inline-flex items-center gap-2 text-xs font-semibold text-primary group-hover:underline">
<span>Click to view options</span>
<span class="material-symbols-outlined text-sm">arrow_forward</span>
</span>
</div>
</div>
<?php endforeach; ?>
</div>
</section>
<!-- FAQ Section -->
<section class="mt-24 max-w-4xl mx-auto">
<h3 class="text-3xl font-extrabold tracking-tight mb-10 text-center">Frequently Asked Questions</h3>
<div class="space-y-4">
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold">What is a domain brokerage service?</span>
<span class="material-symbols-outlined">add</span>
</button>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold">How long does domain transfer take?</span>
<span class="material-symbols-outlined">add</span>
</button>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold">Are there hidden fees for renewals?</span>
<span class="material-symbols-outlined">add</span>
</button>
</div>
</div>
</section>
</main>
<!-- Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script>
  (function () {
    const input = document.getElementById('comprehensive-search-input');
    const button = document.getElementById('comprehensive-search-button');
    const currency = <?php echo json_encode($selectedCurrency); ?>;

    function goToResults() {
      const query = input ? input.value.trim() : '';

      if (!query) {
        if (input) {
          input.focus();
        }

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
  })();
</script>
<script src="../assets/js/nav-state.js"></script>
</body></html>




