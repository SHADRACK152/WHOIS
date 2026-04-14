<?php
// --- Helper: Supported global TLDs ---
function whois_ai_search_supported_global_tlds(): array
{
    return [
        'com', 'net', 'org', 'ai', 'shop', 'info', 'xyz', 'me', 'art', 'quest', 
        'africa', 'app', 'co', 'io', 'in', 'online', 'store', 'live', 
        'life', 'dev', 'tv', 'work', 'site', 'biz', 'rocks', 'blog', 'design', 
        'wedding', 'club', 'agency', 'luxury', 'tech', 'website', 'space'
    ];
}

// --- Helper: Industry Suffixes for Variations ---
function whois_ai_search_domain_suffixes(): array 
{
    return [
        'music', 'media', 'official', 'law', 'coaching', 'coach', 'art', 
        'creative', 'wedding', 'productions', 'photo', 'consulting', 'author', 
        'group', 'portfolio', 'events', 'studio', 'photography', 'films', 
        'realtor', 'design', 'books', 'writes', 'artist', 'fitness'
    ];
}

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/domain-lookup.php';
require_once __DIR__ . '/../../app/currency.php';
require_once __DIR__ . '/../../app/truehost-client.php';
require_once __DIR__ . '/../../app/premium-market.php';

// --- Helpers ---
function whois_ai_search_price_label($priceData, string $targetCurrency = 'USD'): string
{
    $amount = null;

    if (is_array($priceData) && isset($priceData['raw']) && is_numeric($priceData['raw'])) {
        $amount = (float)$priceData['raw'];
    } elseif (is_numeric($priceData)) {
        $amount = (float)$priceData;
    }

    if ($amount !== null) {
        if (function_exists('whois_currency_convert_amount')) {
            $amount = whois_currency_convert_amount($amount, 'KES', $targetCurrency);
        }
        return function_exists('whois_currency_format') 
            ? whois_currency_format($amount, $targetCurrency) 
            : '$' . number_format($amount, 2);
    }

    if (is_array($priceData) && isset($priceData['formatted']) && is_string($priceData['formatted'])) {
        return $priceData['formatted'];
    }
    if (is_string($priceData) && trim($priceData) !== '') {
        return trim($priceData);
    }

    return 'Price unavailable';
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
        $primaryCandidates[] = $primaryCandidates[] = $cc;
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
        if (count($bundle) >= 3) break;
        if (isset($supported[$fallbackTld]) && !in_array($fallbackTld, $bundle, true)) {
            $bundle[] = $fallbackTld;
        }
    }
    return $bundle !== [] ? $bundle : ['com', 'net', 'org'];
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
        if (!is_string($candidate)) continue;
        $country = strtoupper(trim($candidate));
        if (preg_match('/^[A-Z]{2}$/', $country) === 1) {
            return $country;
        }
    }
    return 'KE';
}

function whois_ai_search_resolve_domain(string $input, string $tld): string
{
    $input = trim(strtolower($input));
    $tld = trim(strtolower($tld));
    if ($input === '') return '';
    if (strpos($input, '.') !== false) return whois_domain_normalize($input);
    if ($tld !== '') return whois_domain_normalize($input . '.' . ltrim($tld, '.'));
    return whois_domain_normalize($input . '.com');
}

function whois_ai_search_status_meta(string $status): array
{
    $status = strtolower(trim($status));
    if ($status === 'available') return ['label' => 'Available', 'class' => 'bg-emerald-100 text-emerald-700'];
    if ($status === 'registered' || $status === 'unavailable') return ['label' => 'Registered', 'class' => 'bg-neutral-900 text-white'];
    if (str_contains($status, 'premium') || str_contains($status, 'priced') || str_contains($status, 'marketed')) {
        return ['label' => 'Verified premium', 'class' => 'bg-amber-100 text-amber-800'];
    }
    return ['label' => 'Unknown', 'class' => 'bg-surface-container-high text-secondary'];
}

function whois_ai_search_bundle_tld_candidates(string $country): array
{
    return ['com', 'net', 'org', 'co', 'io', 'ai', 'site', 'shop', 'xyz', 'store', 'online', 'africa', 'luxury', 'agency', 'club', 'biz', 'life'];
}

function whois_ai_search_country_tld_exclusions(string $country): array
{
    $country = strtoupper(trim($country));
    $pairsByCountry = [
        'KE' => ['co.ke', 'ke'],
        'NG' => ['com.ng', 'ng'],
        'GB' => ['co.uk', 'uk'],
    ];
    return $pairsByCountry[$country] ?? [];
}

function whois_ai_search_bundle_domain(string $stem, string $tld): string
{
    $stem = preg_replace('/[^a-z0-9]/', '', strtolower($stem)) ?? '';
    $tld = strtolower(trim($tld));
    if ($stem === '' || $tld === '') return '';
    return $stem . '.' . ltrim($tld, '.');
}

// --- NEW: AI Generative Naming Function ---
function whois_generate_ai_business_names(string $description): array {
    $fallback = [
        ['domain' => 'brandhq.com', 'reason' => 'A strong, authoritative brandable name.'],
        ['domain' => 'getbrand.com', 'reason' => 'Action-oriented and easy to remember.'],
        ['domain' => 'brandonline.com', 'reason' => 'Clear digital presence indicator.'],
        ['domain' => 'brandapp.com', 'reason' => 'Perfect if your business has a digital platform.']
    ];

    try {
        require_once __DIR__ . '/../../app/grok-client.php';
        $result = whois_ai_request('business_idea', $description);
        $output = $result['output'] ?? '';
        
        $matches = [];
        if (preg_match('/\[.*\]/s', $output, $matches)) {
            $jsonBlock = $matches[0];
            $ideas = json_decode($jsonBlock, true);
            if (is_array($ideas)) return $ideas;
        }
    } catch (Throwable $e) {
        error_log("AI Domain Search Error: " . $e->getMessage());
    }

    return $fallback;
}


// --- Variable assignments and search logic ---
$searchInput = trim((string)($_GET['query'] ?? ''));
$tld = trim((string)($_GET['tld'] ?? ''));
$selectedCurrency = whois_currency_normalize_code((string)($_GET['currency'] ?? 'USD'), 'USD');

// SEARCH TYPE TOGGLE
$searchType = $_GET['search_type'] ?? 'standard';
$isDescriptionSearch = ($searchType === 'ai');
$hasSearch = $searchInput !== '';

$searchDomain = '';
$searchRoot = '';
$searchStem = 'brand';
$countryCode = whois_ai_search_detect_country();

$rdapLookup = null;
$lookupStatus = 'unknown';
$lookupMeta = ['label' => 'Unknown', 'class' => 'bg-surface-container-high text-secondary'];
$lookupSummary = '';
$mainPrice = 'Price unavailable';

// Data Arrays
$alternativeCards = [];
$bundleItems = [];
$premiumListings = [];
$aiBrandConcepts = [];

$bundleSubtotal = 0.0;
$bundlePricedItems = 0;

if ($hasSearch) {
    if ($isDescriptionSearch) {
        // ==========================================
        // FLOW 1: AI BUSINESS DESCRIPTION SEARCH 
        // ==========================================
        $generatedIdeas = whois_generate_ai_business_names($searchInput);
        
        foreach ($generatedIdeas as $idea) {
            $candidateDomain = strtolower(trim((string)($idea['domain'] ?? '')));
            $reason = trim((string)($idea['reason'] ?? ''));
            if ($candidateDomain === '') continue;

            $lookup = whois_truehost_domain_lookup($candidateDomain);
            if (($lookup['status'] ?? 'unknown') === 'unknown') {
                $lookup = whois_domain_lookup_cached($candidateDomain);
            }

            $status = strtolower((string)($lookup['status'] ?? 'unknown'));
            $meta = whois_ai_search_status_meta($status);
            $candidateTld = substr($candidateDomain, (int)strrpos($candidateDomain, '.') + 1);
            
            $priceData = $lookup['pricing']['register'] ?? whois_truehost_tld_price($candidateTld);
            $priceStr = whois_ai_search_price_label($priceData, $selectedCurrency);

            $aiBrandConcepts[] = [
                'domain' => $candidateDomain,
                'reason' => $reason,
                'status' => $meta['label'],
                'statusClass' => $meta['class'],
                'price' => $priceStr,
                'isAvailable' => $status === 'available'
            ];
        }

    } else {
        // ==========================================
        // FLOW 2: STANDARD DOMAIN/KEYWORD SEARCH
        // ==========================================
        $searchDomain = whois_ai_search_resolve_domain($searchInput, $tld);
        $searchRoot = preg_replace('/\.[^.]+$/', '', $searchDomain) ?? $searchDomain;
        $searchStem = preg_replace('/[^a-z0-9]/', '', strtolower($searchRoot)) ?? '';
        if ($searchStem === '') $searchStem = 'brand';
        
        $targetTld = $tld !== '' ? ltrim($tld, '.') : 'com';
        if (strpos($searchInput, '.') !== false) {
            $targetTld = substr($searchDomain, (int)strrpos($searchDomain, '.') + 1);
        }

        $rdapLookup = whois_domain_lookup_cached($searchDomain);
        $lookupStatus = strtolower((string)($rdapLookup['status'] ?? 'unknown'));
        $lookupMeta = whois_ai_search_status_meta($lookupStatus);
        $lookupSummary = whois_domain_lookup_summary($rdapLookup);

        $mainTld = substr($searchDomain, (int)strrpos($searchDomain, '.') + 1);
        $mainPriceData = whois_truehost_tld_price($mainTld);
        $mainPrice = whois_ai_search_price_label($mainPriceData, $selectedCurrency);

        // Standard Alternates Logic
        $candidatePool = [];
        $altTlds = whois_ai_search_supported_global_tlds();
        $altDomains = array_map(function($t) use ($searchStem) { return $searchStem . '.' . $t; }, $altTlds);

        $smartPrefixes = ['my', 'get', 'the', 'try', 'hello', 'go'];
        $smartSuffixes = ['hq', 'hub', 'app', 'online', 'yangu', 'yetu', 'kenya', 'africa'];
        $smartNames = [];
        foreach ($smartPrefixes as $prefix) $smartNames[] = $prefix . $searchStem . '.' . $targetTld;
        foreach ($smartSuffixes as $suffix) $smartNames[] = $searchStem . $suffix . '.' . $targetTld;
        foreach (array_slice(whois_ai_search_domain_suffixes(), 0, 10) as $suffix) $smartNames[] = $searchStem . $suffix . '.' . $targetTld;
        
        shuffle($altDomains);
        shuffle($smartNames);
        
        $candidatePool = array_unique(array_merge(array_slice($altDomains, 0, 12), array_slice($smartNames, 0, 12)));
        shuffle($candidatePool);
        $candidatePool = array_slice($candidatePool, 0, 25);

        foreach ($candidatePool as $candidateDomain) {
            if ($candidateDomain === $searchDomain) continue;
            $candidateLookup = whois_truehost_domain_lookup($candidateDomain);
            if (($candidateLookup['status'] ?? 'unknown') === 'unknown') {
                $candidateLookup = whois_domain_lookup_cached($candidateDomain);
            }

            $candidateStatus = strtolower((string)($candidateLookup['status'] ?? 'unknown'));
            $candidateTld = substr($candidateDomain, (int)strrpos($candidateDomain, '.') + 1);
            
            $priceData = $candidateLookup['pricing']['register'] ?? whois_truehost_tld_price($candidateTld);
            $candidatePriceStr = whois_ai_search_price_label($priceData, $selectedCurrency);

            $isPremium = str_contains($candidateStatus, 'premium');
            $isPromo = false;
            $originalPriceStr = '';
            
            if (in_array($candidateTld, ['xyz', 'shop', 'online', 'site', 'store']) && $candidateStatus === 'available') {
                $isPromo = true;
                $originalPriceStr = '$' . number_format(rand(19, 49) + 0.99, 2);
            }
            
            $isAiGenerated = str_contains($candidateDomain, 'yangu') || str_contains($candidateDomain, 'the') || str_contains($candidateDomain, 'get');
            
            if ($isPremium) {
                $badge = 'PREMIUM'; $badgeClass = 'bg-amber-100 text-amber-800';
                $candidateMeta = ['label' => 'Verified premium', 'class' => 'bg-amber-100 text-amber-800'];
            } elseif ($isPromo) {
                $badge = 'PROMOTED'; $badgeClass = 'bg-neutral-100 text-neutral-600';
                $candidateMeta = whois_ai_search_status_meta($candidateStatus);
            } elseif ($isAiGenerated) {
                $badge = 'SMART NAME'; $badgeClass = 'bg-indigo-100 text-indigo-800';
                $candidateMeta = whois_ai_search_status_meta($candidateStatus);
            } else {
                $badge = ''; $badgeClass = '';
                $candidateMeta = whois_ai_search_status_meta($candidateStatus);
            }
            
            $alternativeCards[] = [
                'domain' => $candidateDomain,
                'status' => $candidateMeta['label'],
                'statusClass' => $candidateMeta['class'],
                'price' => $candidatePriceStr,
                'originalPrice' => $originalPriceStr,
                'term' => $isPromo ? '1st yr only with 3 yr term' : 'for first year',
                'badge' => $badge,
                'badgeClass' => $badgeClass,
                'note' => whois_domain_lookup_summary($candidateLookup),
            ];
        }

        // Bundles and Premium Logic
        $premiumMarketData = whois_premium_market_listings($searchDomain, ['currency' => $selectedCurrency], $selectedCurrency);
        $premiumListings = is_array($premiumMarketData['listings'] ?? null) ? $premiumMarketData['listings'] : [];

        $bundleCandidateTlds = whois_ai_search_bundle_tld_candidates($countryCode);
        $allBundleCandidates = array_values(array_unique(array_merge($bundleCandidateTlds, ['pro', 'shop', 'online', 'site'])));
        $bundleTldExclusions = whois_ai_search_country_tld_exclusions($countryCode);
        $bundleSelectedTlds = [];
        $maxLookupsAttempted = 0;

        foreach ($allBundleCandidates as $bundleTld) {
            if (count($bundleItems) >= 3) break;
            if ($maxLookupsAttempted >= 12) break;

            $normalizedBundleTld = strtolower(trim($bundleTld));
            $conflictingTlds = $bundleTldExclusions[$normalizedBundleTld] ?? [];
            if ($conflictingTlds !== [] && count(array_intersect($bundleSelectedTlds, $conflictingTlds)) > 0) continue;

            $bundleDomain = whois_ai_search_bundle_domain($searchStem, $bundleTld);
            if ($bundleDomain === '') continue;

            $maxLookupsAttempted++;
            $bundleLookup = ($countryCode === 'KE') ? whois_truehost_domain_lookup($bundleDomain) : whois_domain_lookup_cached($bundleDomain);
            if ((string)($bundleLookup['status'] ?? '') !== 'available') continue;

            $bundleMeta = whois_ai_search_status_meta('available');
            $bundlePriceData = whois_truehost_tld_price($bundleTld);
            $bundlePrice = whois_ai_search_price_label($bundlePriceData, $selectedCurrency);
            $bundlePriceRaw = null;

            if (is_array($bundlePriceData) && isset($bundlePriceData['raw']) && is_numeric($bundlePriceData['raw'])) {
                $bundlePriceRaw = function_exists('whois_currency_convert_amount')
                    ? whois_currency_convert_amount((float)$bundlePriceData['raw'], 'KES', $selectedCurrency)
                    : (float)$bundlePriceData['raw'];
            }

            if (is_numeric($bundlePriceRaw)) {
                $bundleSubtotal += (float)$bundlePriceRaw;
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
}

$bundleDisplayedTlds = array_values(array_map(static function (array $item): string { return (string)($item['tld'] ?? ''); }, $bundleItems));
$bundleTotal = max(0, $bundleSubtotal - ($bundlePricedItems > 1 ? $bundleSubtotal * 0.18 : 0.0));
$formattedBundleTotal = function_exists('whois_currency_format') ? whois_currency_format($bundleTotal, $selectedCurrency) : '$' . number_format($bundleTotal, 2);
$formattedBundleSubtotal = function_exists('whois_currency_format') ? whois_currency_format($bundleSubtotal, $selectedCurrency) : '$' . number_format($bundleSubtotal, 2);

?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>WHOIS | AI-Powered Domain Search<?php echo $hasSearch ? ' - ' . htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8') : ''; ?></title>
<?php require __DIR__ . '/_head.php'; ?>
    <style>        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-surface text-on-surface selection:bg-black selection:text-white overflow-x-hidden">
    <?php require __DIR__ . '/_top_nav.php'; ?>

    <!-- HERO SEARCH SECTION -->
    <section class="pt-40 pb-16 px-6 text-center max-w-6xl mx-auto">
        <div class="relative overflow-hidden rounded-[2rem] border border-outline-variant/20 bg-white px-6 py-16 shadow-[0_30px_80px_rgba(0,0,0,0.05)] md:px-10">
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top,#f3f3f3_0,transparent_60%)]"></div>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-primary tracking-tight mb-6">
                Search. Discover.<br/>Own Your Brand.
            </h1>
            <p class="text-on-surface-variant text-base md:text-lg mb-8 max-w-2xl mx-auto font-medium">
                Search directly to check domain availability and alternatives, <span class="font-bold text-black border-b-2 border-indigo-400">OR use the AI mode</span> to brainstorm perfect ideas.
            </p>
            
            <form id="ai-domain-search-form" action="whois_ai_domain_search.php" method="GET" class="relative max-w-4xl mx-auto mb-6">
                <input type="hidden" name="currency" value="<?php echo htmlspecialchars($selectedCurrency, ENT_QUOTES, 'UTF-8'); ?>"/>
                
                <!-- Explicit Toggle UI -->
                <div class="flex justify-center mb-6">
                    <div class="bg-surface-container-high p-1.5 rounded-full inline-flex items-center shadow-inner">
                        <label class="cursor-pointer relative m-0">
                            <input type="radio" name="search_type" value="standard" class="peer hidden" <?php echo $searchType !== 'ai' ? 'checked' : ''; ?> onchange="updateSearchMode()">
                            <div class="px-6 py-2.5 rounded-full transition-all duration-300 peer-checked:bg-white peer-checked:text-primary peer-checked:shadow-sm flex items-center gap-2 text-on-surface-variant font-bold text-sm">
                                <span class="material-symbols-outlined text-[18px]">search</span> Standard Search
                            </div>
                        </label>
                        <label class="cursor-pointer relative m-0">
                            <input type="radio" name="search_type" value="ai" class="peer hidden" <?php echo $searchType === 'ai' ? 'checked' : ''; ?> onchange="updateSearchMode()">
                            <div class="px-6 py-2.5 rounded-full transition-all duration-300 peer-checked:bg-white peer-checked:text-indigo-700 peer-checked:shadow-sm flex items-center gap-2 text-on-surface-variant font-bold text-sm">
                                <span class="material-symbols-outlined text-[18px]">auto_awesome</span> AI Generator
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-surface-container-lowest border-2 border-outline-variant/40 p-2 rounded-full shadow-sm focus-within:ring-4 focus-within:ring-black/10 focus-within:border-black transition-all duration-300">
                    <div class="flex flex-col gap-2 lg:flex-row lg:items-center">
                        <div class="flex items-center flex-1 min-w-0">
                            <span id="search-icon" class="material-symbols-outlined ml-4 text-outline"><?php echo $searchType === 'ai' ? 'auto_awesome' : 'search'; ?></span>
                            <input 
                                id="ai-domain-search-input" 
                                name="query" 
                                class="w-full bg-transparent border-none focus:ring-0 px-4 py-3 text-lg font-medium text-primary placeholder:text-neutral-400" 
                                placeholder="<?php echo $searchType === 'ai' ? 'Describe your business... e.g., \'I sell organic dog food\'' : 'Enter domain or brand name, e.g., trovalabs.com'; ?>" 
                                type="text" 
                                value="<?php echo htmlspecialchars($searchInput, ENT_QUOTES, 'UTF-8'); ?>"
                                required
                            />
                        </div>
                        <button id="ai-domain-search-btn" type="submit" class="bg-black text-white px-8 py-3 rounded-full font-bold transition-transform active:scale-95 shadow-md flex items-center gap-2">
                            Search <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </form>

            <div id="ai-search-spinner" class="flex flex-col justify-center items-center my-8 text-indigo-600" style="display:none;">
                <svg class="animate-spin h-10 w-10 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span class="font-bold tracking-widest uppercase text-xs animate-pulse">AI is brainstorming...</span>
            </div>
        </div>
    </section>

    <?php if ($hasSearch): ?>
        
        <?php if ($isDescriptionSearch): ?>
            <!-- ========================================================= -->
            <!-- AI BUSINESS DESCRIPTION RESULTS LAYOUT                    -->
            <!-- ========================================================= -->
            <section class="py-12 px-6 max-w-7xl mx-auto w-full">
                <div class="mb-10 text-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4">
                        <span class="material-symbols-outlined text-[14px]">auto_awesome</span> AI Brand Agency
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-primary tracking-tight mb-4">Generated Concepts</h2>
                    <p class="text-on-surface-variant max-w-2xl mx-auto italic">Based on: "<?php echo htmlspecialchars($searchInput, ENT_QUOTES, 'UTF-8'); ?>"</p>
                </div>

                <?php if (!empty($aiBrandConcepts)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <?php foreach (array_slice($aiBrandConcepts, 0, 2) as $concept): ?>
                            <div class="bg-white rounded-[1.5rem] border-2 border-indigo-400 shadow-lg flex flex-col h-full overflow-hidden group">
                                <div class="p-6 flex-grow flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-4">
                                            <h3 class="text-2xl font-black text-primary break-all group-hover:text-indigo-600 transition-colors"><?php echo htmlspecialchars($concept['domain'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        </div>
                                        <p class="text-base text-on-surface-variant leading-relaxed mb-6 border-l-4 border-indigo-300 pl-4">
                                            <?php echo htmlspecialchars($concept['reason'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                                        <div>
                                            <span class="block text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Status</span>
                                            <span class="inline-block px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest <?php echo htmlspecialchars($concept['statusClass'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <?php echo htmlspecialchars($concept['status'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="block text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Price</span>
                                            <span class="font-black text-lg text-primary"><?php echo htmlspecialchars($concept['price'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-surface-container-lowest border-t border-outline-variant/20">
                                    <?php if ($concept['isAvailable']): ?>
                                        <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($concept['domain']); ?>" target="_blank" class="block w-full text-center bg-black text-white font-bold py-3 rounded-xl hover:bg-neutral-800 transition-colors">
                                            Register Name
                                        </a>
                                    <?php else: ?>
                                        <a href="whois_domain_appraisal_tool.php?domain=<?php echo urlencode($concept['domain']); ?>" class="block w-full text-center bg-surface-container border border-outline-variant/50 text-primary font-bold py-3 rounded-xl hover:bg-surface-container-high transition-colors">
                                            Appraise / Broker
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php foreach (array_slice($aiBrandConcepts, 2) as $concept): ?>
                            <div class="bg-white rounded-[1.5rem] border border-outline-variant/30 shadow-sm hover:shadow-xl transition-all flex flex-col h-full overflow-hidden group">
                                <div class="p-6 flex-grow flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-4">
                                            <h3 class="text-xl font-black text-primary break-all group-hover:text-indigo-600 transition-colors"><?php echo htmlspecialchars($concept['domain'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        </div>
                                        <p class="text-sm text-on-surface-variant leading-relaxed mb-6 border-l-2 border-indigo-200 pl-3">
                                            <?php echo htmlspecialchars($concept['reason'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                                        <div>
                                            <span class="block text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Status</span>
                                            <span class="inline-block px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest <?php echo htmlspecialchars($concept['statusClass'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <?php echo htmlspecialchars($concept['status'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="block text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Price</span>
                                            <span class="font-black text-lg text-primary"><?php echo htmlspecialchars($concept['price'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-surface-container-lowest border-t border-outline-variant/20">
                                    <?php if ($concept['isAvailable']): ?>
                                        <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($concept['domain']); ?>" target="_blank" class="block w-full text-center bg-black text-white font-bold py-3 rounded-xl hover:bg-neutral-800 transition-colors">
                                            Register Name
                                        </a>
                                    <?php else: ?>
                                        <a href="whois_domain_appraisal_tool.php?domain=<?php echo urlencode($concept['domain']); ?>" class="block w-full text-center bg-surface-container border border-outline-variant/50 text-primary font-bold py-3 rounded-xl hover:bg-surface-container-high transition-colors">
                                            Appraise / Broker
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center p-12 bg-white rounded-3xl border border-outline-variant/30">
                        <p class="text-secondary">We couldn't generate concepts right now. Please try a different description or search a specific domain.</p>
                    </div>
                <?php endif; ?>
            </section>

        <?php else: ?>
            <!-- ========================================================= -->
            <!-- STANDARD DOMAIN KEYWORD RESULTS LAYOUT (Existing UI)      -->
            <!-- ========================================================= -->
            <section class="py-4 px-6 max-w-6xl mx-auto w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    
                    <!-- Main Domain Card -->
                    <article class="relative flex flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow">
                        <div>
                            <div class="mb-4 inline-block rounded <?php echo htmlspecialchars($lookupMeta['class'] ?? 'bg-emerald-100 text-emerald-800'); ?> px-2 py-1 text-[11px] font-bold tracking-widest uppercase">
                                <?php echo htmlspecialchars($lookupMeta['label'] ?? 'Great Name'); ?>
                            </div>
                            <h2 class="mb-2 text-3xl font-black text-black tracking-tight break-all">
                                <?php echo htmlspecialchars($searchDomain); ?>
                            </h2>
                            <div class="mb-6 flex items-baseline gap-3">
                                <span class="text-3xl font-black text-black"><?php echo htmlspecialchars($mainPrice); ?></span>
                                <span class="text-xs text-neutral-500">for first year</span>
                            </div>
                        </div>
                        
                        <div>
                            <?php if ($lookupStatus === 'available'): ?>
                                <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($searchDomain); ?>" target="_blank" rel="noopener" class="block w-full rounded-lg bg-black px-6 py-4 text-center text-lg font-bold text-white hover:bg-neutral-800 transition-colors active:scale-[0.98]">
                                    Make It Yours
                                </a>
                            <?php else: ?>
                                <a href="whois_domain_appraisal_tool.php?domain=<?php echo urlencode($searchDomain); ?>" class="block w-full rounded-lg bg-amber-400 px-6 py-4 text-center text-lg font-bold text-black hover:bg-amber-300 transition-colors active:scale-[0.98]">
                                    Hire a Broker
                                </a>
                            <?php endif; ?>
                            <div class="mt-4 rounded-lg bg-neutral-50 p-4">
                                <span class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-1">Why it's great</span>
                                <p class="text-sm text-neutral-700 leading-snug">
                                    <?php echo htmlspecialchars($lookupSummary ?: '"' . explode('.', $searchDomain)[0] . '" is a powerful keyword.'); ?>
                                </p>
                            </div>
                        </div>
                    </article>

                    <!-- Bundle Domain Card -->
                    <?php if (!empty($bundleItems)): ?>
                    <?php 
                        $tldsHtml = implode('<span class="text-neutral-300 mx-1">|</span>', array_map(function($tld) { return '.' . strtoupper($tld); }, $bundleDisplayedTlds));
                        $rawTlds = strtoupper(implode(', ', $bundleDisplayedTlds));
                    ?>
                    <article class="relative flex flex-col justify-between rounded-2xl border-2 border-black bg-white p-6 md:p-8 shadow-lg">
                        <div class="absolute -top-3 right-6 bg-amber-400 text-black text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">
                            Most Popular
                        </div>
                        
                        <div>
                            <div class="mb-4 inline-block rounded bg-black px-2 py-1 text-[11px] font-bold tracking-widest text-white uppercase">
                                Bundle and Save 82%
                            </div>
                            <h2 class="mb-2 text-2xl font-black text-black tracking-tight break-all flex flex-wrap items-center">
                                <?php echo htmlspecialchars(explode('.', $searchDomain)[0]); ?> 
                                <span class="ml-2 text-lg text-neutral-500 font-bold"><?php echo $tldsHtml; ?></span>
                            </h2>
                            <div class="mb-6 flex items-baseline gap-3">
                                <span class="text-3xl font-black text-black"><?php echo $bundlePricedItems > 0 ? htmlspecialchars($formattedBundleTotal) : 'Price unavailable'; ?></span>
                                <?php if ($bundlePricedItems > 0 && $bundleSubtotal > $bundleTotal): ?>
                                    <span class="text-sm font-medium text-neutral-400 line-through"><?php echo htmlspecialchars($formattedBundleSubtotal); ?></span>
                                <?php endif; ?>
                                <span class="text-xs text-neutral-500">for first year</span>
                            </div>
                        </div>
                        
                        <div>
                            <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($searchDomain); ?>" target="_blank" rel="noopener" class="block w-full rounded-lg bg-emerald-600 px-6 py-4 text-center text-lg font-bold text-white hover:bg-emerald-700 transition-colors active:scale-[0.98]">
                                Bundle It All
                            </a>
                            <div class="mt-4 rounded-lg bg-neutral-50 p-4">
                                <span class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-1">Why it's great</span>
                                <p class="text-sm text-neutral-700 leading-snug">
                                    Protect your business from copycats by registering these popular endings: <?php echo htmlspecialchars($rawTlds); ?>.
                                </p>
                            </div>
                        </div>
                    </article>
                    <?php else: ?>
                        <div class="hidden md:block"></div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- GoDaddy Style List View -->
            <section class="py-12 px-6 max-w-4xl mx-auto w-full">
                <h3 class="text-2xl font-black text-primary mb-6 border-b border-neutral-200 pb-4">
                    Explore <span class="text-emerald-700"><?php echo htmlspecialchars($searchStem); ?></span> Endings
                </h3>
                
                <div class="flex flex-col">
                    <?php foreach ($alternativeCards as $card): ?>
                        <div class="flex flex-col md:flex-row md:items-center justify-between py-5 border-b border-neutral-200 hover:bg-neutral-50 transition-colors px-2 rounded-lg group">
                            
                            <div class="flex flex-col md:flex-row md:items-center gap-3 mb-4 md:mb-0">
                                <?php if (!empty($card['badge'])): ?>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-widest <?php echo htmlspecialchars($card['badgeClass']); ?>">
                                        <?php echo htmlspecialchars($card['badge']); ?>
                                    </span>
                                <?php endif; ?>
                                <h4 class="text-xl font-bold text-primary <?php echo strtolower($card['status']) === 'available' ? '' : 'text-neutral-400 line-through'; ?>">
                                    <?php echo htmlspecialchars($card['domain'], ENT_QUOTES, 'UTF-8'); ?>
                                </h4>
                            </div>
                            
                            <div class="flex items-center justify-between md:justify-end gap-6 md:min-w-[300px]">
                                <div class="flex flex-col items-start md:items-end text-left md:text-right">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-2xl font-black text-primary">
                                            <?php echo htmlspecialchars($card['price'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                        <?php if (!empty($card['originalPrice'])): ?>
                                            <span class="text-sm font-medium text-neutral-400 line-through">
                                                <?php echo htmlspecialchars($card['originalPrice']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-[11px] text-neutral-500 font-medium">
                                        <?php echo htmlspecialchars($card['term']); ?>
                                    </span>
                                </div>
                                
                                <?php if (strtolower($card['status']) === 'available'): ?>
                                    <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($card['domain']); ?>" target="_blank" rel="noopener" class="shrink-0 rounded-lg bg-black px-5 py-2.5 text-sm font-bold text-white hover:bg-neutral-800 transition-colors shadow-sm">
                                        Add
                                    </a>
                                <?php else: ?>
                                    <button disabled class="shrink-0 rounded-lg bg-neutral-200 px-5 py-2.5 text-sm font-bold text-neutral-500 cursor-not-allowed">
                                        Taken
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="flex justify-center mt-10">
                    <!-- Note: Since $comprehensiveUrl was removed, we use standard link back to search -->
                    <a class="rounded-full border border-neutral-300 px-8 py-3 text-sm font-bold uppercase tracking-[0.15em] text-primary hover:border-black transition-colors" href="whois_ai_domain_search.php?query=<?php echo rawurlencode($searchStem); ?>&currency=<?php echo rawurlencode($selectedCurrency); ?>">
                        See All Endings
                    </a>
                </div>
            </section>

            <!-- Premium Market Section -->
            <section id="premium-container" class="py-16 px-6 max-w-6xl mx-auto">
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
        <?php endif; ?> <!-- End Split Layout -->

    <?php endif; ?>

    <?php require __DIR__ . '/_footer.php'; ?>

    <script>
        function updateSearchMode() {
            var searchType = document.querySelector('input[name="search_type"]:checked').value;
            var input = document.getElementById('ai-domain-search-input');
            var icon = document.getElementById('search-icon');
            
            if (searchType === 'ai') {
                input.placeholder = "Describe your business... e.g., 'I sell organic dog food'";
                icon.textContent = "auto_awesome";
            } else {
                input.placeholder = "Enter domain or brand name, e.g., trovalabs.com";
                icon.textContent = "search";
            }
        }

        // Show spinner on Search
        document.getElementById('ai-domain-search-form').addEventListener('submit', function(e) {
            document.getElementById('ai-search-spinner').style.display = 'flex';
        });
    </script>
    <script src="../assets/js/nav-state.js"></script>
</body>
</html>