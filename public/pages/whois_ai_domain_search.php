<?php
declare(strict_types=1);

require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/domain-lookup.php';
require __DIR__ . '/../../app/currency.php';
require __DIR__ . '/../../app/truehost-client.php';
require __DIR__ . '/../../app/premium-market.php';

function whois_ai_search_status_meta(string $status): array
{
  $status = strtolower(trim($status));

  if ($status === 'available') {
    return [
      'label' => 'Available',
      'class' => 'bg-emerald-100 text-emerald-700',
    ];
  }

  if ($status === 'registered' || $status === 'unavailable') {
    return [
      'label' => 'Registered',
      'class' => 'bg-neutral-900 text-white',
    ];
  }

  if (str_contains($status, 'premium') || str_contains($status, 'priced') || str_contains($status, 'marketed')) {
    return [
      'label' => 'Verified premium',
      'class' => 'bg-amber-100 text-amber-800',
    ];
  }

  return [
    'label' => 'Unknown',
    'class' => 'bg-surface-container-high text-secondary',
  ];
}

function whois_ai_search_price_label(?array $pricing, string $currency): string
{
  if (!is_array($pricing) || !isset($pricing['raw']) || !is_numeric($pricing['raw'])) {
    return 'Price unavailable';
  }

  return whois_currency_format_amount(
    whois_currency_convert_amount((float) $pricing['raw'], 'KES', $currency),
    $currency
  );
}

function whois_ai_search_resolve_domain(string $query, string $tld = ''): string
{
  $query = trim(strtolower($query));
  $query = preg_replace('#^https?://#', '', $query) ?? $query;
  $query = preg_replace('#/.*$#', '', $query) ?? $query;
  $query = preg_replace('/\s+/', '', $query) ?? $query;

  if ($query === '') {
    return '';
  }

  if (str_contains($query, '.')) {
    return $query;
  }

  $normalizedTld = strtolower(trim($tld));
  $normalizedTld = preg_replace('/[^a-z0-9.-]/', '', $normalizedTld) ?? $normalizedTld;
  $normalizedTld = ltrim($normalizedTld, '.');

  if ($normalizedTld === '') {
    return $query;
  }

  return $query . '.' . $normalizedTld;
}

function whois_ai_search_supported_global_tlds(): array
{
  static $cache = null;

  if (is_array($cache)) {
    return $cache;
  }

  $preferredTlds = [
    'music', 'grey', 'shop', 'dev', 'app', 'xyz', 'online', 'site', 'store', 'cloud',
    'tech', 'design', 'bio', 'health', 'art', 'news', 'blog', 'digital', 'media', 'studio',
    'agency', 'network', 'world', 'global', 'group', 'solutions', 'systems', 'expert', 'software', 'tools',
  ];

  $supported = array_flip(whois_rdap_supported_tlds());
  $tlds = [];

  foreach ($preferredTlds as $tld) {
    if (isset($supported[$tld])) {
      $tlds[] = $tld;
    }
  }

  if ($tlds === []) {
    $tlds = array_slice(whois_rdap_supported_tlds(), 0, 24);
  }

  $cache = $tlds;

  return $cache;
}

$searchInput = trim((string) ($_GET['query'] ?? ''));
$searchTld = trim((string) ($_GET['tld'] ?? ''));
$selectedCurrency = whois_currency_normalize_code((string) ($_GET['currency'] ?? 'USD'), 'USD');
$searchDomain = whois_ai_search_resolve_domain($searchInput, $searchTld);
$hasSearch = $searchDomain !== '' && str_contains($searchDomain, '.');
$searchRoot = $searchDomain !== ''
  ? (preg_replace('/\.[^.]+$/', '', $searchDomain) ?? $searchDomain)
  : '';
$searchStem = preg_replace('/[^a-z0-9]/', '', strtolower($searchRoot)) ?? '';

if ($searchStem === '') {
  $searchStem = 'brand';
}

$lookup = $hasSearch
  ? whois_truehost_domain_lookup($searchDomain)
  : [
    'domain' => '',
    'status' => 'unknown',
    'statusLabel' => 'Enter a domain to begin',
    'whois' => null,
    'availabilityNote' => 'Type a domain to check registration status.',
  ];

$rdapLookup = $hasSearch
  ? whois_domain_lookup_cached($searchDomain)
  : [
    'domain' => '',
    'status' => 'unknown',
    'statusLabel' => 'Enter a domain to begin',
    'registrar' => null,
    'created' => null,
    'updated' => null,
    'nameservers' => [],
    'availabilityNote' => 'Type a domain to check registration status.',
    'rdapSource' => null,
  ];

$lookupStatus = strtolower((string) ($rdapLookup['status'] ?? $lookup['status'] ?? 'unknown'));

if ($lookupStatus === 'unknown') {
  $lookupStatus = strtolower((string) ($lookup['status'] ?? 'unknown'));
}

$lookupMeta = whois_ai_search_status_meta($lookupStatus);
$lookupSummary = $hasSearch
  ? whois_domain_lookup_summary($rdapLookup)
  : 'Enter any root plus any delegated TLD, or type a full domain such as trovalabs.music.';

if ($hasSearch && $lookupStatus === 'unknown' && is_string($lookup['availabilityNote'] ?? null)) {
  $lookupSummary = (string) $lookup['availabilityNote'];
}

$globalTlds = whois_ai_search_supported_global_tlds();
$supportedTldCount = count(whois_rdap_supported_tlds());
$searchSuggestions = [];

if ($hasSearch) {
  $searchSuggestions = array_values(array_unique([
    $searchDomain,
    $searchStem . '.music',
    $searchStem . '.grey',
    $searchStem . '.shop',
    $searchStem . '.dev',
    $searchStem . '.app',
    $searchStem . '.xyz',
  ]));
} else {
  $searchSuggestions = ['trovalabs.music', 'trovalabs.grey', 'brand.shop', 'studio.dev', 'mosaic.app', 'northstar.xyz'];
}

$alternativeCards = [];

if ($hasSearch) {
  foreach (whois_domain_candidate_domains($searchStem, $globalTlds) as $candidateDomain) {
    $candidateLookup = whois_domain_lookup_cached($candidateDomain);
    $candidateMeta = whois_ai_search_status_meta((string) ($candidateLookup['status'] ?? 'unknown'));
    $candidateTld = substr($candidateDomain, (int) strrpos($candidateDomain, '.') + 1);
    $candidatePrice = whois_ai_search_price_label(whois_truehost_tld_price($candidateTld), $selectedCurrency);

    $alternativeCards[] = [
      'domain' => $candidateDomain,
      'status' => $candidateMeta['label'],
      'statusClass' => $candidateMeta['class'],
      'price' => $candidatePrice,
      'note' => whois_domain_lookup_summary($candidateLookup),
    ];
  }
}

$premiumMarketData = $hasSearch
  ? whois_premium_market_listings($searchDomain, [
    'lookup' => $lookup,
    'currency' => $selectedCurrency,
  ], $selectedCurrency)
  : [
    'listings' => [],
    'error' => null,
  ];

$premiumListings = is_array($premiumMarketData['listings'] ?? null) ? $premiumMarketData['listings'] : [];
$comprehensiveUrl = $hasSearch
  ? '/pages/whois_comprehensive_search_results.php?query=' . rawurlencode($searchDomain) . '&currency=' . rawurlencode($selectedCurrency)
  : '/pages/whois_comprehensive_search_results.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | AI-Powered Domain Search<?php echo $hasSearch ? ' - ' . htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8') : ''; ?></title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&amp;family=Inter:wght@400;500;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary": "#e2e2e2",
                        "secondary": "#5e5e5e",
                        "secondary-fixed": "#c7c6c6",
                        "tertiary": "#3a3c3c",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-secondary-fixed": "#1b1c1c",
                        "on-error": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#1a1c1c",
                        "surface-container-low": "#f3f3f3",
                        "surface-bright": "#f9f9f9",
                        "tertiary-fixed": "#5d5f5f",
                        "error-container": "#ffdad6",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "error": "#ba1a1a",
                        "surface-dim": "#dadada",
                        "on-primary-fixed": "#ffffff",
                        "on-tertiary": "#e2e2e2",
                        "secondary-fixed-dim": "#acabab",
                        "primary-container": "#3b3b3b",
                        "on-secondary-container": "#1b1c1c",
                        "surface": "#f9f9f9",
                        "primary": "#000000",
                        "background": "#f9f9f9",
                        "outline": "#777777",
                        "tertiary-fixed-dim": "#454747",
                        "on-error-container": "#410002",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "surface-container-high": "#e8e8e8",
                        "inverse-on-surface": "#f1f1f1",
                        "on-background": "#1a1c1c",
                        "surface-tint": "#5e5e5e",
                        "outline-variant": "#c6c6c6",
                        "inverse-primary": "#c6c6c6",
                        "tertiary-container": "#737575",
                        "on-tertiary-fixed": "#ffffff",
                        "on-surface-variant": "#474747",
                        "surface-variant": "#e2e2e2",
                        "inverse-surface": "#2f3131",
                        "secondary-container": "#d5d4d4",
                        "on-secondary": "#ffffff",
                        "on-primary-container": "#ffffff",
                        "surface-container-highest": "#e2e2e2",
                        "primary-fixed-dim": "#474747",
                        "surface-container": "#eeeeee",
                        "primary-fixed": "#5e5e5e",
                        "on-tertiary-container": "#ffffff"
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
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .no-line { border: none !important; }
        .tonal-shift-bottom { background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(249,249,249,0.8)); }
    </style>
</head>
<body class="bg-surface text-on-surface selection:bg-black selection:text-white overflow-x-hidden">
<nav class="fixed top-0 w-full z-50 bg-white/85 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border-b border-outline-variant/20">
  <div class="flex items-center justify-between gap-6 px-6 lg:px-8 py-4 max-w-7xl mx-auto">
    <a class="flex items-baseline gap-3 shrink-0 text-black" href="whois_premium_domain_intelligence_landing_page.php">
      <span class="text-xl font-black tracking-tighter font-['Manrope']">WHOIS</span>
      <span class="hidden sm:inline text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-400">Intelligence Suite</span>
    </a>
    <div class="hidden xl:flex items-center gap-6 text-sm font-semibold font-['Manrope'] tracking-tight">
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_intelligence_landing_page.php">Home</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_ai_domain_search.php">Search</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_ai_brand_assistant.php">AI Assistants</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_marketplace.php">Marketplace</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_domain_tools_overview.php">Tools</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_industry_insights_blog.php">Insights</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_partner_with_us.php">Partner</a>
    </div>
    <div class="flex items-center gap-3">
      <details class="relative group">
        <summary class="list-none cursor-pointer rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors">Explore</summary>
        <div class="absolute right-0 mt-3 w-[min(92vw,56rem)] rounded-[1.5rem] border border-outline-variant/20 bg-white/95 p-6 shadow-[0_30px_60px_rgba(0,0,0,0.08)] backdrop-blur-xl">
          <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Search</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_ai_domain_search.php">AI Domain Search</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_premium_ai_domain_search.php">Premium AI Search</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_professional_lookup_tool.php">Professional Lookup</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_optimized_premium_search_results.php">Optimized Results</a>
              </div>
            </div>
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">AI Assistants</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_ai_brand_assistant.php">Brand Assistant</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_ai_business_idea_generator.php">Business Idea Generator</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_ai_domain_name_generator.php">Domain Name Generator</a>
              </div>
            </div>
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Marketplace</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_marketplace.php">Premium Marketplace</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_limited_time_domain_auctions.php">Limited Auctions</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_submit_domain_for_auction.php">Submit for Auction</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_search_results_with_premium_logos.php">Premium Logos Results</a>
              </div>
            </div>
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Tools</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_domain_tools_overview.php">Tools Overview</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_domain_appraisal_tool.php">Domain Appraisal</a>
              </div>
            </div>
                        <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Results</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_comprehensive_search_results.php">Comprehensive Results</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_domain_search_results.php">Search Results</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_optimized_premium_search_results.php">Optimized Premium Search Results</a>
              </div>
            </div>
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">More</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_intelligence_landing_page.php">Landing Page</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_industry_insights_blog.php">Industry Insights</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_partner_with_us.php">Partner With Us</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_professional_lookup_tool.php">Professional Lookup</a>
              </div>
            </div>
          </div>
        </div>
      </details>
      <a class="rounded-full bg-primary px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-on-primary hover:bg-primary-container transition-colors" href="whois_ai_domain_search.php">Search</a>
    </div>
  </div>
</nav>
<!-- Hero Section -->
<section class="pt-40 pb-20 px-6 text-center max-w-6xl mx-auto">
<div class="relative overflow-hidden rounded-[2rem] border border-outline-variant/20 bg-white px-6 py-16 shadow-[0_30px_80px_rgba(0,0,0,0.05)] md:px-10">
<div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top,#f3f3f3_0,transparent_60%)]"></div>
<h1 class="text-5xl md:text-7xl font-extrabold text-primary tracking-tight mb-6">
            Search. Discover.<br/>Own Your Brand.
        </h1>
<p class="text-on-surface-variant text-lg md:text-xl mb-12 max-w-2xl mx-auto font-medium">
            Find live registration status, registrar data, and verified premium alternatives in one search.
        </p>
<form action="whois_ai_domain_search.php" method="get" class="relative max-w-4xl mx-auto mb-6">
<input type="hidden" name="currency" value="<?php echo htmlspecialchars($selectedCurrency, ENT_QUOTES, 'UTF-8'); ?>"/>
<div class="bg-surface-container-lowest border border-outline-variant p-2 rounded-full shadow-sm focus-within:ring-1 focus-within:ring-black transition-all duration-300">
<div class="flex flex-col gap-2 lg:flex-row lg:items-center">
<div class="flex items-center flex-1 min-w-0">
<span class="material-symbols-outlined ml-4 text-outline">search</span>
<input name="query" class="w-full bg-transparent border-none focus:ring-0 px-4 py-3 text-lg font-medium text-primary placeholder:text-neutral-400" placeholder="Search domain or root name" type="text" value="<?php echo htmlspecialchars($searchInput, ENT_QUOTES, 'UTF-8'); ?>"/>
</div>
<div class="flex items-center gap-2 lg:w-72 shrink-0 rounded-full border border-outline-variant/20 bg-surface-container-low px-3 py-2">
<span class="material-symbols-outlined text-outline text-base">public</span>
<input name="tld" class="w-full bg-transparent border-none focus:ring-0 text-sm font-semibold text-primary placeholder:text-neutral-400" placeholder="Any TLD, e.g. music" type="text" value="<?php echo htmlspecialchars($searchTld, ENT_QUOTES, 'UTF-8'); ?>"/>
</div>
<button type="submit" class="bg-black text-white px-8 py-3 rounded-full font-bold transition-transform active:scale-95">Search</button>
</div>
</div>
</form>
<div class="flex flex-wrap justify-center gap-3 mb-6 text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">
<?php foreach ($searchSuggestions as $searchSuggestion): ?>
<a class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2 hover:border-black hover:text-black transition-colors" href="whois_ai_domain_search.php?query=<?php echo rawurlencode($searchSuggestion); ?>&amp;currency=<?php echo rawurlencode($selectedCurrency); ?>"><?php echo htmlspecialchars($searchSuggestion, ENT_QUOTES, 'UTF-8'); ?></a>
<?php endforeach; ?>
</div>
<div class="flex flex-wrap items-center justify-center gap-3 text-sm font-medium text-neutral-500">
<span class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2">Free RDAP supports <?php echo (int) $supportedTldCount; ?> delegated TLDs</span>
<span class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2">Try .music, .grey, .shop, .dev, .app, .xyz</span>
</div>
<div class="mt-4 flex flex-wrap justify-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">
<?php foreach (array_slice($globalTlds, 0, 10) as $globalTld): ?>
<a class="rounded-full border border-outline-variant/30 bg-white px-3 py-2 hover:border-black hover:text-black transition-colors" href="whois_ai_domain_search.php?query=<?php echo rawurlencode($searchStem !== '' ? $searchStem . '.' . $globalTld : 'trovalabs.' . $globalTld); ?>&amp;currency=<?php echo rawurlencode($selectedCurrency); ?>"><?php echo htmlspecialchars('.' . $globalTld, ENT_QUOTES, 'UTF-8'); ?></a>
<?php endforeach; ?>
</div>
</div>
</section>
<?php if ($hasSearch): ?>
<section class="py-4 px-6 max-w-6xl mx-auto">
<div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
<article class="rounded-[2rem] border border-outline-variant/20 bg-surface-container-lowest p-8 shadow-[0_20px_60px_rgba(0,0,0,0.05)]">
<div class="flex items-start justify-between gap-4">
<div>
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Live lookup</p>
<h2 class="mt-3 text-3xl md:text-4xl font-black text-primary"><?php echo htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8'); ?></h2>
<p class="mt-3 text-on-surface-variant text-lg leading-relaxed"><?php echo htmlspecialchars($lookupSummary, ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<span class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] <?php echo htmlspecialchars($lookupMeta['class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($lookupMeta['label'], ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<div class="mt-8 grid gap-4 sm:grid-cols-2">
<div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
<p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-2">Registrar</p>
<p class="text-lg font-bold text-primary"><?php echo htmlspecialchars((string) ($rdapLookup['registrar'] ?? 'Unavailable'), ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
<p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-2">Created / Updated</p>
<p class="text-lg font-bold text-primary"><?php echo htmlspecialchars((string) ($rdapLookup['created'] ?? 'Not listed'), ENT_QUOTES, 'UTF-8'); ?></p>
<p class="text-sm text-on-surface-variant mt-1"><?php echo htmlspecialchars((string) ($rdapLookup['updated'] ?? 'No update date returned'), ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20 sm:col-span-2">
<p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-2">Nameservers</p>
<div class="flex flex-wrap gap-2">
<?php if (!empty($rdapLookup['nameservers']) && is_array($rdapLookup['nameservers'])): ?>
<?php foreach (array_slice($rdapLookup['nameservers'], 0, 4) as $nameserver): ?>
<span class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-primary border border-outline-variant/30"><?php echo htmlspecialchars((string) $nameserver, ENT_QUOTES, 'UTF-8'); ?></span>
<?php endforeach; ?>
<?php else: ?>
<span class="text-sm text-on-surface-variant">No nameservers returned by the registry.</span>
<?php endif; ?>
</div>
</div>
</div>
<div class="mt-8 flex flex-wrap gap-3">
<a class="rounded-full bg-black px-5 py-3 text-sm font-bold text-white hover:bg-neutral-800 transition-colors" href="<?php echo htmlspecialchars($comprehensiveUrl, ENT_QUOTES, 'UTF-8'); ?>">Open comprehensive report</a>
<a class="rounded-full border border-outline-variant/40 bg-white px-5 py-3 text-sm font-bold text-primary hover:border-black transition-colors" href="/pages/whois_submit_domain_for_auction.php">Submit for auction</a>
</div>
</article>
<aside class="space-y-6">
<div class="rounded-[2rem] border border-outline-variant/20 bg-white p-6 shadow-[0_20px_60px_rgba(0,0,0,0.04)]">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Snapshot</p>
<div class="mt-6 space-y-4 text-sm">
<div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3">
<span class="text-on-surface-variant">Status</span>
<span class="font-bold text-primary"><?php echo htmlspecialchars($lookupMeta['label'], ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3">
<span class="text-on-surface-variant">Currency</span>
<span class="font-bold text-primary"><?php echo htmlspecialchars($selectedCurrency, ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3">
<span class="text-on-surface-variant">Alternatives</span>
<span class="font-bold text-primary"><?php echo count($alternativeCards); ?></span>
</div>
<div class="flex items-center justify-between gap-4">
<span class="text-on-surface-variant">Premium signals</span>
<span class="font-bold text-primary"><?php echo count($premiumListings); ?></span>
</div>
</div>
</div>
<div class="rounded-[2rem] border border-outline-variant/20 bg-surface-container-low p-6">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Display</p>
<div class="mt-4 space-y-3 text-sm">
<div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3">
<span class="text-on-surface-variant">Registry source</span>
<span class="font-bold text-primary">Global RDAP</span>
</div>
<div class="flex items-center justify-between gap-4">
<span class="text-on-surface-variant">Currency</span>
<span class="font-bold text-primary">USD</span>
</div>
</div>
</div>
</aside>
</div>
</section>
<section class="py-16 px-6 max-w-6xl mx-auto">
<div class="flex items-end justify-between gap-4 mb-10">
<div>
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-2">Smart alternatives</p>
<h3 class="text-3xl font-black text-primary">Available domains based on your brand</h3>
</div>
<a class="text-sm font-bold uppercase tracking-[0.18em] text-neutral-500 hover:text-black transition-colors" href="<?php echo htmlspecialchars($comprehensiveUrl, ENT_QUOTES, 'UTF-8'); ?>">Open full results</a>
</div>
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
<?php foreach ($alternativeCards as $alternativeCard): ?>
<article class="rounded-[2rem] border border-outline-variant/20 bg-white p-7 shadow-[0_18px_50px_rgba(0,0,0,0.04)]">
<div class="flex items-start justify-between gap-4">
<h4 class="text-2xl font-black text-primary break-all"><?php echo htmlspecialchars($alternativeCard['domain'], ENT_QUOTES, 'UTF-8'); ?></h4>
<span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em] <?php echo htmlspecialchars($alternativeCard['statusClass'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($alternativeCard['status'], ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<p class="mt-4 text-sm leading-relaxed text-on-surface-variant"><?php echo htmlspecialchars($alternativeCard['note'], ENT_QUOTES, 'UTF-8'); ?></p>
<div class="mt-6 flex items-end justify-between gap-4">
<div>
<p class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">Live price</p>
<p class="text-lg font-bold text-primary"><?php echo htmlspecialchars($alternativeCard['price'], ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<a class="rounded-full border border-outline-variant/40 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-primary hover:border-black transition-colors" href="<?php echo htmlspecialchars($comprehensiveUrl, ENT_QUOTES, 'UTF-8'); ?>">View report</a>
</div>
</article>
<?php endforeach; ?>
</div>
</section>
<section class="py-16 px-6 max-w-6xl mx-auto">
<div class="rounded-[2rem] border border-outline-variant/20 bg-surface-container-low p-8">
<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
<div>
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-2">Premium market signals</p>
<h3 class="text-3xl font-black text-primary">Verified premium candidates</h3>
</div>
<p class="max-w-xl text-sm text-on-surface-variant">These results come from live premium checks. If the premium API is unavailable, the section stays explicit instead of guessing.</p>
</div>
<?php if ($premiumListings !== []): ?>
<div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
<?php foreach ($premiumListings as $premiumListing): ?>
<article class="rounded-[1.75rem] border border-outline-variant/20 bg-white p-6 shadow-[0_18px_50px_rgba(0,0,0,0.04)]">
<div class="flex items-start justify-between gap-4">
<h4 class="text-xl font-black text-primary break-all"><?php echo htmlspecialchars((string) ($premiumListing['domain'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h4>
<span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] bg-amber-100 text-amber-800"><?php echo htmlspecialchars((string) ($premiumListing['statusLabel'] ?? 'Verified premium'), ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<p class="mt-4 text-sm leading-relaxed text-on-surface-variant"><?php echo htmlspecialchars((string) ($premiumListing['reason'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
<div class="mt-6 space-y-2 text-sm">
<div class="flex items-center justify-between gap-4">
<span class="text-neutral-400 uppercase tracking-[0.18em] text-[10px]">Ask</span>
<span class="font-bold text-primary"><?php echo htmlspecialchars((string) ($premiumListing['ask'] ?? 'Unavailable'), ENT_QUOTES, 'UTF-8'); ?></span>
</div>
<div class="flex items-center justify-between gap-4">
<span class="text-neutral-400 uppercase tracking-[0.18em] text-[10px]">Appraisal</span>
<span class="font-bold text-primary"><?php echo htmlspecialchars((string) ($premiumListing['appraisal'] ?? 'Unavailable'), ENT_QUOTES, 'UTF-8'); ?></span>
</div>
</div>
</article>
<?php endforeach; ?>
</div>
<?php else: ?>
<div class="mt-8 rounded-[1.75rem] border border-outline-variant/30 bg-white p-6 text-sm text-on-surface-variant">
Verified premium checks are unavailable right now. The page will still show live availability and smart alternatives.
</div>
<?php endif; ?>
</div>
</section>
<?php else: ?>
<section class="py-16 px-6 max-w-6xl mx-auto">
<div class="grid gap-6 md:grid-cols-3">
<article class="rounded-[2rem] border border-outline-variant/20 bg-white p-8 shadow-[0_18px_50px_rgba(0,0,0,0.04)]">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-3">1. Search exact domain</p>
<h3 class="text-2xl font-black text-primary mb-3">Check live registration status</h3>
<p class="text-sm leading-relaxed text-on-surface-variant">Enter a domain or brand name and the page will normalize it, look it up, and show the registry response.</p>
</article>
<article class="rounded-[2rem] border border-outline-variant/20 bg-white p-8 shadow-[0_18px_50px_rgba(0,0,0,0.04)]">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-3">2. Explore alternatives</p>
<h3 class="text-2xl font-black text-primary mb-3">Review adjacent TLDs</h3>
<p class="text-sm leading-relaxed text-on-surface-variant">The search engine generates nearby domain options and checks live availability for each one.</p>
</article>
<article class="rounded-[2rem] border border-outline-variant/20 bg-white p-8 shadow-[0_18px_50px_rgba(0,0,0,0.04)]">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-3">3. Watch premium signals</p>
<h3 class="text-2xl font-black text-primary mb-3">See verified premium offers</h3>
<p class="text-sm leading-relaxed text-on-surface-variant">When the premium API is available, the page highlights priced premium names instead of inventing results.</p>
</article>
</div>
</section>
<?php endif; ?>
<!-- Footer -->
<footer class="w-full border-t border-neutral-200 bg-white">
<div class="flex flex-col md:flex-row justify-between items-center px-12 py-12 max-w-7xl mx-auto gap-8">
<div class="flex flex-col gap-2 items-center md:items-start">
<div class="font-black text-xl text-black">WHOIS</div>
<p class="font-['Inter'] text-[10px] uppercase tracking-widest text-neutral-400">&copy; 2024 WHOIS AI. All rights reserved.</p>
</div>
<div class="flex gap-8">
<a class="font-['Inter'] text-xs uppercase tracking-widest text-neutral-400 hover:text-black hover:underline decoration-1 underline-offset-4 transition-all" href="#">Privacy</a>
<a class="font-['Inter'] text-xs uppercase tracking-widest text-neutral-400 hover:text-black hover:underline decoration-1 underline-offset-4 transition-all" href="#">Terms</a>
<a class="font-['Inter'] text-xs uppercase tracking-widest text-neutral-400 hover:text-black hover:underline decoration-1 underline-offset-4 transition-all" href="#">Support</a>
<a class="font-['Inter'] text-xs uppercase tracking-widest text-neutral-400 hover:text-black hover:underline decoration-1 underline-offset-4 transition-all" href="#">Twitter</a>
</div>
</div>
</footer>
<script src="../assets/js/nav-state.js"></script>
</body></html>




