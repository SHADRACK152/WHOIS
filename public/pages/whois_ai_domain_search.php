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
    'com', 'net', 'org', 'co', 'io', 'ai', 'app', 'dev', 'xyz', 'shop', 'online', 'site', 'store', 'cloud',
    'tech', 'design', 'bio', 'health', 'art', 'news', 'blog', 'digital', 'media', 'studio',
    'agency', 'network', 'world', 'global', 'group', 'solutions', 'systems', 'expert', 'software', 'tools',
    'music', 'grey', // niche and less popular TLDs at the end
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

function whois_ai_search_detect_country(): string
{
  $candidates = [
    $_SERVER['HTTP_CF_IPCOUNTRY'] ?? null,
    $_SERVER['GEOIP_COUNTRY_CODE'] ?? null,
    $_SERVER['HTTP_X_COUNTRY_CODE'] ?? null,
    $_SERVER['HTTP_X_APPENGINE_COUNTRY'] ?? null,
    $_GET['country'] ?? null,
  ];

  foreach ($candidates as $candidate) {
    if (!is_string($candidate)) {
      continue;
    }

    $country = strtoupper(trim($candidate));

    if (preg_match('/^[A-Z]{2}$/', $country) === 1) {
      return $country;
    }
  }

  return 'KE';
}

function whois_ai_search_country_bundle_tlds(string $country): array
{
  $country = strtoupper(trim($country));

  $cc = strtolower($country);
  $primaryCandidates = ['com'];

  if (preg_match('/^[A-Z]{2}$/', $country) === 1) {
    $preferredSecondLevelByCountry = [
      'KE' => 'co.ke',
      'NG' => 'com.ng',
      'GB' => 'co.uk',
    ];

    $preferredSecondLevel = $preferredSecondLevelByCountry[$country] ?? ('co.' . $cc);

    $primaryCandidates[] = $preferredSecondLevel;
    $primaryCandidates[] = $cc;

    if ($country === 'GB') {
      $primaryCandidates[] = 'uk';
    }
  }

  $bundle = [];

  foreach ($primaryCandidates as $candidate) {
    if (!in_array($candidate, $bundle, true)) {
      $bundle[] = $candidate;
    }
  }

  $supported = array_flip(whois_rdap_supported_tlds());

  foreach (['net', 'org', 'io', 'ai'] as $fallbackTld) {
    if (count($bundle) >= 3) {
      break;
    }

    if (isset($supported[$fallbackTld]) && !in_array($fallbackTld, $bundle, true)) {
      $bundle[] = $fallbackTld;
    }
  }

  return $bundle !== [] ? $bundle : ['com', 'net', 'org'];
}

function whois_ai_search_country_tld_exclusions(string $country): array
{
  $country = strtoupper(trim($country));

  $pairsByCountry = [
    'KE' => ['co.ke', 'ke'],
    'NG' => ['com.ng', 'ng'],
  ];

  $pair = $pairsByCountry[$country] ?? null;

  if (!is_array($pair) || count($pair) !== 2) {
    return [];
  }

  $first = strtolower(trim((string) $pair[0]));
  $second = strtolower(trim((string) $pair[1]));

  if ($first === '' || $second === '') {
    return [];
  }

  return [
    $first => [$second],
    $second => [$first],
  ];
}

function whois_ai_search_bundle_tld_candidates(string $country): array
{
  $primary = whois_ai_search_country_bundle_tlds($country);
  $supported = array_flip(whois_rdap_supported_tlds());

  $fallbacks = [
    'net', 'org', 'shop', 'online', 'io', 'ai', 'co', 'info', 'biz', 'app', 'dev', 'site', 'store',
  ];

  $ordered = [];

  foreach ($primary as $tld) {
    if (!in_array($tld, $ordered, true)) {
      $ordered[] = $tld;
    }
  }

  foreach ($fallbacks as $tld) {
    if (isset($supported[$tld]) && !in_array($tld, $ordered, true)) {
      $ordered[] = $tld;
    }
  }

  foreach (whois_ai_search_supported_global_tlds() as $tld) {
    if (isset($supported[$tld]) && !in_array($tld, $ordered, true)) {
      $ordered[] = $tld;
    }
  }

  return $ordered;
}

function whois_ai_search_tld_matches_country(string $tld, string $country): bool
{
  $normalizedTld = strtolower(trim($tld));
  $countryCode = strtolower(trim($country));

  if ($normalizedTld === '' || $countryCode === '' || strlen($countryCode) !== 2) {
    return false;
  }

  return $normalizedTld === $countryCode || str_ends_with($normalizedTld, '.' . $countryCode);
}

function whois_ai_search_bundle_domain(string $root, string $tld): string
{
  $normalizedRoot = trim(strtolower($root));
  $normalizedTld = ltrim(trim(strtolower($tld)), '.');

  if ($normalizedRoot === '' || $normalizedTld === '') {
    return '';
  }

  return $normalizedRoot . '.' . $normalizedTld;
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
$countryCode = whois_ai_search_detect_country();

if ($searchStem === '') {
  $searchStem = 'brand';
}

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

$lookupStatus = strtolower((string) ($rdapLookup['status'] ?? 'unknown'));

$lookupMeta = whois_ai_search_status_meta($lookupStatus);
$lookupSummary = $hasSearch
  ? whois_domain_lookup_summary($rdapLookup)
  : 'Enter any root plus any delegated TLD, or type a full domain such as trovalabs.music.';

if ($hasSearch && $lookupStatus === 'unknown' && is_string($rdapLookup['availabilityNote'] ?? null)) {
  $lookupSummary = (string) $rdapLookup['availabilityNote'];
}

$globalTlds = whois_ai_search_supported_global_tlds();

$alternativeCards = [];
$tldAlternatives = [];
if ($hasSearch) {
  foreach (whois_domain_candidate_domains($searchStem, $globalTlds) as $candidateDomain) {
    $candidateLookup = whois_domain_lookup_cached($candidateDomain);
    $candidateStatus = strtolower((string) ($candidateLookup['status'] ?? 'unknown'));
    if ($candidateStatus !== 'available') {
      continue;
    }
    $candidateMeta = whois_ai_search_status_meta($candidateStatus);
    $candidateTld = substr($candidateDomain, (int) strrpos($candidateDomain, '.') + 1);
    $candidatePrice = whois_ai_search_price_label(whois_truehost_tld_price($candidateTld), $selectedCurrency);
    $tldAlternatives[] = $candidateDomain;
    $alternativeCards[] = [
      'domain' => $candidateDomain,
      'status' => $candidateMeta['label'],
      'statusClass' => $candidateMeta['class'],
      'price' => $candidatePrice,
      'note' => whois_domain_lookup_summary($candidateLookup),
    ];
  }
}

// Fetch AI-generated related domains (unique, not just TLD swaps)
$aiRelatedCards = [];
if ($hasSearch && $searchRoot !== '') {
  // Use HTTP request to avoid header issues
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $aiApiUrl = $protocol . '://' . $host . '/api/name-generator.php';
  $aiPayload = [
    'description' => $searchRoot,
    'limit' => 10,
    'currency' => $selectedCurrency,
  ];
  $aiResult = null;
  try {
    $opts = [
      'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content' => json_encode($aiPayload),
        'timeout' => 8,
      ]
    ];
    $context = stream_context_create($opts);
    $response = file_get_contents($aiApiUrl, false, $context);
    if ($response !== false) {
      $aiResult = json_decode($response, true);
    }
  } catch (Throwable $e) {
    $aiResult = null;
  }
  if (is_array($aiResult) && ($aiResult['ok'] ?? false) && is_array($aiResult['items'] ?? null)) {
    foreach ($aiResult['items'] as $item) {
      if (($item['status'] ?? '') !== 'available') continue;
      if (in_array($item['domain'], $tldAlternatives, true)) continue; // skip TLD variants
      $aiRelatedCards[] = [
        'domain' => $item['domain'],
        'status' => 'Available',
        'statusClass' => 'bg-emerald-100 text-emerald-700',
        'price' => $item['price'] ?? 'Price unavailable',
        'note' => '',
      ];
      if (count($aiRelatedCards) >= 5) break;
    }
  }
}

$premiumMarketData = $hasSearch
  ? whois_premium_market_listings($searchDomain, [
    'currency' => $selectedCurrency,
  ], $selectedCurrency)
  : [
    'listings' => [],
    'error' => null,
  ];

$premiumListings = is_array($premiumMarketData['listings'] ?? null) ? $premiumMarketData['listings'] : [];
$bundleTlds = whois_ai_search_country_bundle_tlds($countryCode);
$bundleCandidateTlds = whois_ai_search_bundle_tld_candidates($countryCode);
$bundleItems = [];
$bundleSubtotal = 0.0;
$bundlePricedItems = 0;
$bundleExcludedCount = 0;
$bundleMaxItems = 3;
$useTruehostBundleLookup = $countryCode === 'KE';
$bundleTldExclusions = whois_ai_search_country_tld_exclusions($countryCode);
$bundleSelectedTlds = [];
$bundleLocalUnavailableTlds = [];

if ($hasSearch) {
  foreach ($bundleCandidateTlds as $bundleTld) {
    if (count($bundleItems) >= $bundleMaxItems) {
      break;
    }

    $normalizedBundleTld = strtolower(trim($bundleTld));
    $conflictingTlds = $bundleTldExclusions[$normalizedBundleTld] ?? [];

    if ($conflictingTlds !== [] && count(array_intersect($bundleSelectedTlds, $conflictingTlds)) > 0) {
      continue;
    }

    $bundleDomain = whois_ai_search_bundle_domain($searchStem, $bundleTld);

    if ($bundleDomain === '') {
      continue;
    }

    $bundleLookup = $useTruehostBundleLookup
      ? whois_truehost_domain_lookup($bundleDomain)
      : whois_domain_lookup_cached($bundleDomain);
    $bundleMeta = whois_ai_search_status_meta((string) ($bundleLookup['status'] ?? 'unknown'));
    $bundlePriceData = whois_truehost_tld_price($bundleTld);
    $bundlePrice = whois_ai_search_price_label($bundlePriceData, $selectedCurrency);

    $bundlePriceRaw = null;
    if (is_array($bundlePriceData) && isset($bundlePriceData['raw']) && is_numeric($bundlePriceData['raw'])) {
      $bundlePriceRaw = whois_currency_convert_amount((float) $bundlePriceData['raw'], 'KES', $selectedCurrency);
    }

    if ((string) ($bundleLookup['status'] ?? '') !== 'available') {
      $bundleExcludedCount++;

      if (whois_ai_search_tld_matches_country($bundleTld, $countryCode)) {
        $bundleLocalUnavailableTlds[] = $bundleTld;
      }

      continue;
    }

    if (is_numeric($bundlePriceRaw)) {
      $bundleSubtotal += (float) $bundlePriceRaw;
      $bundlePricedItems++;
    }

    $bundleItems[] = [
      'domain' => $bundleDomain,
      'tld' => $bundleTld,
      'status' => $bundleMeta['label'],
      'statusClass' => $bundleMeta['class'],
      'price' => $bundlePrice,
      'available' => true,
    ];

    $bundleSelectedTlds[] = $normalizedBundleTld;
  }
}

$bundleDisplayedTlds = array_values(array_map(static function (array $item): string {
  return (string) ($item['tld'] ?? '');
}, $bundleItems));
$bundleHasLocalTld = false;

foreach ($bundleDisplayedTlds as $bundleDisplayedTld) {
  if (whois_ai_search_tld_matches_country($bundleDisplayedTld, $countryCode)) {
    $bundleHasLocalTld = true;
    break;
  }
}

$bundleLocalUnavailableTlds = array_values(array_unique(array_map(static function (string $tld): string {
  return strtolower(trim($tld));
}, $bundleLocalUnavailableTlds)));

$bundleDiscountRate = 0.18;
$bundleDiscountAmount = $bundlePricedItems > 1 ? $bundleSubtotal * $bundleDiscountRate : 0.0;
$bundleTotal = max(0, $bundleSubtotal - $bundleDiscountAmount);
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
<?php require __DIR__ . '/_top_nav.php'; ?>
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
<button type="submit" class="bg-black text-white px-8 py-3 rounded-full font-bold transition-transform active:scale-95">Search</button>
</div>
</div>
</form>
</div>
</section>
<?php if ($hasSearch): ?>
<!-- Live lookup section FIRST -->
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
    <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-4">AI Suggestions</p>
    <?php if (!empty($aiRelatedCards)): ?>
      <div class="space-y-4">
        <?php foreach ($aiRelatedCards as $alt): ?>
          <div class="border-b border-outline-variant/10 pb-4 mb-4 last:border-b-0 last:mb-0">
            <div class="flex items-center justify-between">
              <span class="font-bold text-primary break-all"><?php echo htmlspecialchars($alt['domain'], ENT_QUOTES, 'UTF-8'); ?></span>
              <span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em] <?php echo htmlspecialchars($alt['statusClass'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($alt['status'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="flex items-center justify-between mt-2">
              <span class="text-xs text-on-surface-variant">Price</span>
              <span class="font-bold text-primary text-xs"><?php echo htmlspecialchars($alt['price'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-sm text-on-surface-variant">No available AI suggestions found.</div>
    <?php endif; ?>
  </div>
</aside>
</div>
</section>
<!-- Country bundle section FOLLOWS -->
<section class="py-4 px-6 max-w-6xl mx-auto">
  <div class="rounded-[2rem] border border-outline-variant/20 bg-white p-8 shadow-[0_20px_60px_rgba(0,0,0,0.05)]">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
      <div>
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-2">Country bundle</p>
        <h3 class="text-3xl font-black text-primary">Localized starter bundle for <?php echo htmlspecialchars($countryCode, ENT_QUOTES, 'UTF-8'); ?></h3>
        <p class="mt-2 text-sm text-on-surface-variant">Recommended mix for your market. Bundle includes <?php echo htmlspecialchars(implode(', ', array_map(static fn(string $tld): string => '.' . $tld, $bundleDisplayedTlds !== [] ? $bundleDisplayedTlds : $bundleTlds)), ENT_QUOTES, 'UTF-8'); ?>.</p>
        <?php if ($bundleExcludedCount > 0): ?>
          <p class="mt-2 text-xs text-on-surface-variant"><?php echo (int) $bundleExcludedCount; ?> non-available domain(s) were excluded from this bundle.</p>
        <?php endif; ?>
        <?php if (!$bundleHasLocalTld && $bundleLocalUnavailableTlds !== []): ?>
          <p class="mt-1 text-xs text-on-surface-variant">Local extensions checked: <?php echo htmlspecialchars(implode(', ', array_map(static fn(string $tld): string => '.' . $tld, $bundleLocalUnavailableTlds)), ENT_QUOTES, 'UTF-8'); ?> (currently unavailable).</p>
        <?php endif; ?>
      </div>
      <div class="rounded-2xl border border-outline-variant/20 bg-surface-container-low px-5 py-4 text-right">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Friendly bundle total</p>
        <p class="mt-2 text-2xl font-black text-primary"><?php echo htmlspecialchars($bundlePricedItems > 0 ? whois_currency_format_amount($bundleTotal, $selectedCurrency) : 'Price unavailable', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="mt-1 text-xs text-on-surface-variant">Subtotal: <?php echo htmlspecialchars(whois_currency_format_amount($bundleSubtotal, $selectedCurrency), ENT_QUOTES, 'UTF-8'); ?><?php echo $bundlePricedItems > 1 ? ' | Discount: ' . htmlspecialchars(whois_currency_format_amount($bundleDiscountAmount, $selectedCurrency), ENT_QUOTES, 'UTF-8') : ''; ?></p>
      </div>
    </div>

    <?php if ($bundleItems !== []): ?>
      <div class="mt-6 grid gap-4 md:grid-cols-3">
        <?php foreach ($bundleItems as $bundleItem): ?>
          <article class="rounded-2xl border border-outline-variant/20 bg-surface-container-lowest p-5">
            <div class="flex items-start justify-between gap-3">
              <h4 class="text-lg font-black text-primary break-all"><?php echo htmlspecialchars((string) ($bundleItem['domain'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h4>
              <span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em] <?php echo htmlspecialchars((string) ($bundleItem['statusClass'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) ($bundleItem['status'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <p class="mt-3 text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">Live price</p>
            <p class="mt-1 text-lg font-bold text-primary"><?php echo htmlspecialchars((string) ($bundleItem['price'] ?? 'Price unavailable'), ENT_QUOTES, 'UTF-8'); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="mt-6 rounded-2xl border border-dashed border-outline-variant/30 bg-surface-container-lowest p-6 text-sm text-on-surface-variant">
        No available domains in the localized bundle right now. Try another root or extension.
      </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>
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
<!-- Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script>
// Intentionally no client-side locale override for country.
// Bundles should follow server geolocation headers first, then explicit query overrides.
</script>
<script src="../assets/js/nav-state.js"></script>
</body></html>




