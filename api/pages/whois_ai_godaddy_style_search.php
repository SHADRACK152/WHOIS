<?php
// --- Helper functions and includes ---
require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/domain-lookup.php';
require_once __DIR__ . '/../../app/currency.php';
require_once __DIR__ . '/../../app/truehost-client.php';
require_once __DIR__ . '/../../app/premium-market.php';

function whois_ai_search_detect_country(): string {
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
        if (preg_match('/^[A-Z]{2}$/', $country)) return $country;
    }
    return 'KE';
}
function whois_ai_search_resolve_domain(string $input, string $tld): string {
    $input = trim(strtolower($input));
    $tld = trim(strtolower($tld));
    if ($input === '') return '';
    if (strpos($input, '.') !== false) return whois_domain_normalize($input);
    if ($tld !== '') return whois_domain_normalize($input . '.' . ltrim($tld, '.'));
    return whois_domain_normalize($input . '.com');
}
function whois_ai_search_status_meta(string $status): array {
    $status = strtolower(trim($status));
    if ($status === 'available') return ['label' => 'Available', 'class' => 'bg-emerald-100 text-emerald-700'];
    if ($status === 'registered' || $status === 'unavailable') return ['label' => 'Registered', 'class' => 'bg-neutral-900 text-white'];
    if (str_contains($status, 'premium') || str_contains($status, 'priced') || str_contains($status, 'marketed')) return ['label' => 'Verified premium', 'class' => 'bg-amber-100 text-amber-800'];
    return ['label' => 'Unknown', 'class' => 'bg-surface-container-high text-secondary'];
}
function whois_ai_search_bundle_tld_candidates(string $country): array {
    return ['com', 'net', 'org', 'co', 'io', 'ai'];
}
function whois_ai_search_bundle_domain(string $stem, string $tld): string {
    $stem = preg_replace('/[^a-z0-9]/', '', strtolower($stem)) ?? '';
    $tld = strtolower(trim($tld));
    if ($stem === '' || $tld === '') return '';
    return $stem . '.' . ltrim($tld, '.');
}
function whois_ai_search_tld_matches_country(string $tld, string $country): bool {
    $tld = strtolower(trim($tld));
    $country = strtolower(trim($country));
    return $tld === $country || $tld === ('co.' . $country);
}

// --- Variable assignments and search logic ---
$searchInput = trim((string)($_GET['query'] ?? ''));
$tld = trim((string)($_GET['tld'] ?? ''));
$selectedCurrency = whois_currency_normalize_code((string)($_GET['currency'] ?? 'USD'), 'USD');
$searchDomain = whois_ai_search_resolve_domain($searchInput, $tld);
$hasSearch = $searchDomain !== '' && str_contains($searchDomain, '.');
$searchRoot = $searchDomain !== '' ? (preg_replace('/\.[^.]+$/', '', $searchDomain) ?? $searchDomain) : '';
$searchStem = preg_replace('/[^a-z0-9]/', '', strtolower($searchRoot)) ?? '';
if ($searchStem === '') $searchStem = 'brand';
$countryCode = whois_ai_search_detect_country();

// --- Results logic ---
$rdapLookup = $hasSearch ? whois_domain_lookup_cached($searchDomain) : null;
$lookupStatus = strtolower((string)($rdapLookup['status'] ?? 'unknown'));
$lookupMeta = whois_ai_search_status_meta($lookupStatus);
$lookupSummary = $hasSearch ? whois_domain_lookup_summary($rdapLookup) : '';

$alternativeCards = [];
$tldAlternatives = [];
if ($hasSearch) {
    foreach (whois_domain_candidate_domains($searchStem, whois_ai_search_bundle_tld_candidates($countryCode)) as $candidateDomain) {
        $candidateLookup = whois_truehost_domain_lookup($candidateDomain);
        $candidateStatus = strtolower((string)($candidateLookup['status'] ?? 'unknown'));
        $candidateMeta = whois_ai_search_status_meta($candidateStatus);
        $candidateTld = substr($candidateDomain, (int)strrpos($candidateDomain, '.') + 1);
        $candidatePrice = null;
        if (isset($candidateLookup['pricing']['register'])) {
            $candidatePrice = whois_ai_search_price_label($candidateLookup['pricing']['register'], $selectedCurrency);
        } else {
            $candidatePrice = whois_ai_search_price_label(whois_truehost_tld_price($candidateTld), $selectedCurrency);
        }
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
$bundleItems = [];
if ($hasSearch) {
    foreach (whois_ai_search_bundle_tld_candidates($countryCode) as $bundleTld) {
        $bundleDomain = whois_ai_search_bundle_domain($searchStem, $bundleTld);
        if ($bundleDomain === '') continue;
        $bundleLookup = whois_truehost_domain_lookup($bundleDomain);
        $bundleMeta = whois_ai_search_status_meta((string)($bundleLookup['status'] ?? 'unknown'));
        $bundlePriceData = whois_truehost_tld_price($bundleTld);
        $bundlePrice = whois_ai_search_price_label($bundlePriceData, $selectedCurrency);
        if ((string)($bundleLookup['status'] ?? '') !== 'available') continue;
        $bundleItems[] = [
            'domain' => $bundleDomain,
            'tld' => $bundleTld,
            'status' => $bundleMeta['label'],
            'statusClass' => $bundleMeta['class'],
            'price' => $bundlePrice,
        ];
        if (count($bundleItems) >= 3) break;
    }
}
$premiumMarketData = $hasSearch ? whois_premium_market_listings($searchDomain, [ 'currency' => $selectedCurrency ], $selectedCurrency) : [ 'listings' => [], 'error' => null ];
$premiumListings = is_array($premiumMarketData['listings'] ?? null) ? $premiumMarketData['listings'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WHOIS | AI Domain Search<?php echo $hasSearch ? ' - ' . htmlspecialchars($searchDomain, ENT_QUOTES, 'UTF-8') : ''; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
<div class="min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="text-3xl font-extrabold tracking-tight">WHOIS <span class="text-emerald-600">Intelligence</span> Suite</h1>
            <form action="whois_ai_godaddy_style_search.php" method="get" class="mt-4 md:mt-0 flex w-full md:w-auto">
                <input name="query" type="text" class="flex-1 border border-gray-300 rounded-l-full px-6 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Find your domain" value="<?php echo htmlspecialchars($searchInput, ENT_QUOTES, 'UTF-8'); ?>" />
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-3 rounded-r-full">Search</button>
            </form>
        </div>
    </header>
    <main class="flex-1 w-full max-w-4xl mx-auto px-4 py-10">
        <?php if ($hasSearch): ?>
        <!-- Main Result Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-block px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest">Domain</span>
                    <span class="text-2xl font-black text-gray-900"><?php echo htmlspecialchars($searchDomain); ?></span>
                    <span class="rounded-full px-2 py-1 text-xs font-bold uppercase tracking-widest <?php echo $lookupMeta['class']; ?>"><?php echo $lookupMeta['label']; ?></span>
                </div>
                <div class="text-gray-600 mb-2"><?php echo htmlspecialchars($lookupSummary); ?></div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xl font-bold text-emerald-700"><?php echo $alternativeCards[0]['price'] ?? 'Price unavailable'; ?></span>
                    <span class="text-gray-400 line-through text-sm">$22.99</span>
                    <span class="text-xs text-gray-500">for first year</span>
                </div>
                <a href="#" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-full font-bold text-base mt-2">Make It Yours</a>
            </div>
            <div class="mt-6 md:mt-0 md:ml-8 flex flex-col gap-2">
                <a href="#" class="text-emerald-700 font-bold hover:underline">Open comprehensive report</a>
                <a href="<?=$assetBase?>/pages/whois_submit_domain_for_auction.php" class="text-gray-700 font-bold hover:underline">Submit for auction</a>
            </div>
        </div>
        <!-- Bundle Card -->
        <?php if (!empty($bundleItems)): ?>
        <div class="bg-amber-50 border border-amber-200 rounded-2xl shadow p-6 mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-widest mb-2">Bundle and Save</span>
                <div class="text-lg font-bold text-gray-900 mb-1">
                    <?php echo implode(' ', array_map(fn($i) => '.' . strtoupper($i['tld']), $bundleItems)); ?>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-lg font-bold text-amber-800"><?php echo $bundleItems[0]['price'] ?? 'Price unavailable'; ?></span>
                    <span class="text-xs text-gray-500">for first year</span>
                </div>
                <a href="#" class="inline-block bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-full font-bold text-base mt-2">Get Bundle</a>
            </div>
            <div class="mt-4 md:mt-0 md:ml-8 text-xs text-gray-700">
                Protect your brand by registering these endings: <?php echo implode(', ', array_map(fn($i) => strtoupper($i['tld']), $bundleItems)); ?>
            </div>
        </div>
        <?php endif; ?>
        <!-- Premium Market -->
        <?php if (!empty($premiumListings)): ?>
        <div class="bg-white border border-amber-200 rounded-2xl shadow p-6 mb-8">
            <div class="mb-4">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-700">Premium Market</span>
                <h2 class="text-xl font-black text-gray-900">Verified Premium Candidates</h2>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <?php foreach ($premiumListings as $premium): ?>
                <div class="border rounded-xl p-4 flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900"><?php echo htmlspecialchars($premium['domain'] ?? ''); ?></span>
                        <span class="rounded-full px-2 py-1 text-xs font-bold <?php echo htmlspecialchars($premium['statusClass'] ?? 'bg-amber-100 text-amber-800'); ?>"><?php echo htmlspecialchars($premium['statusLabel'] ?? 'Verified premium'); ?></span>
                    </div>
                    <div class="text-gray-600 text-xs"><?php echo htmlspecialchars($premium['reason'] ?? ''); ?></div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-700 font-bold"><?php echo htmlspecialchars($premium['ask'] ?? 'Unavailable'); ?></span>
                        <span class="text-gray-400 text-xs">Appraisal: <?php echo htmlspecialchars($premium['appraisal'] ?? 'Unavailable'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <!-- Alternatives -->
        <?php if (!empty($alternativeCards)): ?>
        <div class="bg-white rounded-2xl shadow p-6 mb-8">
            <div class="mb-4">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Smart Alternatives</span>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <?php foreach ($alternativeCards as $card): ?>
                <div class="border rounded-xl p-4 flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900"><?php echo htmlspecialchars($card['domain']); ?></span>
                        <span class="rounded-full px-2 py-1 text-xs font-bold <?php echo $card['statusClass']; ?>"><?php echo $card['status']; ?></span>
                    </div>
                    <div class="text-gray-600 text-xs"><?php echo htmlspecialchars($card['note']); ?></div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-700 font-bold"><?php echo htmlspecialchars($card['price']); ?></span>
                        <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($card['domain']); ?>" target="_blank" rel="noopener" class="ml-auto bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Add to cart</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </main>
    <footer class="bg-white border-t mt-10 py-6 text-center text-xs text-gray-500">
        &copy; <?php echo date('Y'); ?> WHOIS Intelligence Suite. All rights reserved.
    </footer>
</div>
</body>
</html>

