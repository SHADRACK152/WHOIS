<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/domain-appraisal.php'; // Assuming this holds whois_domain_appraisal_analyze

$initialInput = trim((string) ($_GET['domain'] ?? $_GET['query'] ?? 'trovalabs.com'));
$selectedCurrency = whois_currency_normalize_code((string) ($_GET['currency'] ?? 'USD'), 'USD');

// Run the full appraisal logic (which now includes Grok AI internally)
$appraisal = whois_domain_appraisal_analyze($initialInput, $selectedCurrency);

$domain = (string) ($appraisal['domain'] ?? 'trovalabs.com');
$reportUrl = '/pages/whois_comprehensive_search_results.php?query=' . rawurlencode($domain) . '&currency=' . rawurlencode($selectedCurrency);
$submitUrl = '/pages/whois_submit_domain_for_auction.php?domain=' . rawurlencode($domain);
$marketplaceUrl = '/pages/whois_premium_domain_marketplace.php?query=' . rawurlencode((string) ($appraisal['rootWord'] ?? $domain));
$assistantUrl = '/pages/whois_ai_brand_assistant.php';

$aiEnabled = function_exists('whois_ai_config') && whois_ai_config()['apiKey'] !== null;

// Determine which price to show prominently
$hasAiPrice = !empty($appraisal['ai_price']);
$displayPrice = $hasAiPrice ? $appraisal['ai_price'] : ($appraisal['valueLow'] . ' - ' . $appraisal['valueHigh']);
$displayInsight = $hasAiPrice ? $appraisal['ai_insight'] : ($appraisal['aiInsight']['summary'] ?? 'Algorithmic baseline valuation based on current market heuristics.');
$aiConfidence = $hasAiPrice ? $appraisal['ai_confidence'] : ($appraisal['aiInsight']['confidence'] ?? 75);
$aiTags = $appraisal['ai_tags'] ?? [];

$lookupStatus = (string) ($appraisal['lookup']['status'] ?? 'unknown');
$availabilityHeadline = $lookupStatus === 'available' ? 'Available to Register' : ($lookupStatus === 'unavailable' || $lookupStatus === 'registered' ? 'Already Registered' : 'Status Unknown');
$availabilityBadgeClass = $lookupStatus === 'available' ? 'bg-emerald-100 text-emerald-800' : 'bg-neutral-800 text-white';

?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Domain Appraisal | <?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?></title>
<?php require __DIR__ . '/_head.php'; ?>
                    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        body { font-family: 'Inter', sans-serif; background-color: #f6f7f9; }
        h1, h2, h3, h4, .headline { font-family: 'Manrope', sans-serif; }
        
        .ai-glass-card {
            background: linear-gradient(135deg, #111111 0%, #000000 100%);
            position: relative;
            overflow: hidden;
        }
        .ai-glass-card::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at center, rgba(255,255,255,0.05) 0%, transparent 60%);
            transform: rotate(30deg);
            pointer-events: none;
        }
    </style>
</head>
<body class="text-on-surface antialiased selection:bg-black selection:text-white pb-24">
    <?php require __DIR__ . '/_top_nav.php'; ?>

    <!-- HERO SEARCH -->
    <header class="bg-white border-b border-outline-variant/30 pt-28 pb-12 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-surface-container-low rounded-full border border-outline-variant/40 mb-6 shadow-sm">
                <span class="material-symbols-outlined text-[16px] text-amber-500" style="font-variation-settings: 'FILL' 1;">stars</span>
                <span class="text-[10px] font-bold tracking-widest uppercase text-primary">Intelligence Suite</span>
            </div>
            
            <form action="" method="get" class="relative max-w-3xl mx-auto group">
                <input type="hidden" name="currency" value="<?php echo htmlspecialchars($selectedCurrency, ENT_QUOTES, 'UTF-8'); ?>"/>
                <div class="flex items-center bg-surface-container-lowest border-2 border-outline-variant/40 rounded-full p-2 shadow-sm focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10 transition-all duration-300">
                    <span class="material-symbols-outlined ml-4 text-outline">search</span>
                    <input name="domain" class="w-full bg-transparent border-none focus:ring-0 px-4 text-xl font-bold text-primary placeholder:text-neutral-400" placeholder="Appraise any domain..." type="text" value="<?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?>"/>
                    <button type="submit" class="bg-primary text-white px-8 py-4 rounded-full font-bold hover:bg-neutral-800 transition-colors shadow-md active:scale-95">Appraise</button>
                </div>
            </form>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-12">
        
        <!-- HEADER ROW -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight text-primary break-all"><?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-widest <?php echo $availabilityBadgeClass; ?>">
                        <?php echo htmlspecialchars($availabilityHeadline, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="px-3 py-1 rounded-full bg-white border border-outline-variant/40 text-[11px] font-bold uppercase tracking-widest text-secondary">
                        <?php echo htmlspecialchars($selectedCurrency, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <?php if ($hasAiPrice): ?>
                        <span class="px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-[11px] font-bold uppercase tracking-widest text-indigo-700 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">auto_awesome</span> AI Verified
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo htmlspecialchars($submitUrl, ENT_QUOTES, 'UTF-8'); ?>" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-neutral-800 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">sell</span> Sell Domain
                </a>
                <?php if ($lookupStatus === 'available'): ?>
                    <a href="https://truehost.com/cloud/cart.php?a=add&domain=register&query=<?php echo urlencode($domain); ?>" target="_blank" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">shopping_cart</span> Register Now
                    </a>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars($marketplaceUrl, ENT_QUOTES, 'UTF-8'); ?>" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-outline-variant/40 text-primary text-sm font-bold hover:border-primary transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">shopping_bag</span> View Alternatives
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- VALUATION TOP CARDS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
            
            <!-- AI EXPERT VALUATION CARD (Focus) -->
            <div class="lg:col-span-2 ai-glass-card rounded-[2rem] p-8 md:p-10 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <span class="text-xs font-bold text-neutral-400 uppercase tracking-[0.2em] flex items-center gap-2">
                            <?php echo $hasAiPrice ? 'AI Broker Valuation' : 'Algorithmic Baseline Valuation'; ?> 
                            <?php if ($hasAiPrice): ?><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span><?php endif; ?>
                        </span>
                        
                        <!-- AI Tags -->
                        <?php if (!empty($aiTags)): ?>
                            <div class="flex flex-wrap gap-2 justify-end max-w-[50%]">
                                <?php foreach (array_slice($aiTags, 0, 3) as $tag): ?>
                                    <span class="px-2 py-1 bg-white/10 rounded text-[9px] font-bold text-white uppercase tracking-widest border border-white/10"><?php echo htmlspecialchars((string)$tag, ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h2 class="text-4xl md:text-6xl font-black tracking-tighter text-white mb-2">
                        <?php echo htmlspecialchars($displayPrice, ENT_QUOTES, 'UTF-8'); ?>
                    </h2>
                    
                    <?php if ($hasAiPrice): ?>
                        <p class="text-sm font-medium text-neutral-400 mb-8 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">model_training</span>
                            Base algorithm estimated <?php echo htmlspecialchars($appraisal['valueLow'] . ' - ' . $appraisal['valueHigh'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="mt-4 pt-6 border-t border-white/10 grid grid-cols-1 md:grid-cols-[1fr_auto] gap-8 items-end">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500 mb-3">AI Broker Insight</p>
                        <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                            <p class="text-sm text-neutral-300 leading-relaxed font-medium">"<?php echo htmlspecialchars($displayInsight, ENT_QUOTES, 'UTF-8'); ?>"</p>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500 mb-1">AI Confidence</p>
                        <div class="flex items-baseline gap-1 md:justify-end">
                            <p class="text-3xl font-black text-white"><?php echo (int) $aiConfidence; ?>%</p>
                        </div>
                        <p class="text-[10px] text-neutral-400 mt-1">Based on market data</p>
                    </div>
                </div>
            </div>

            <!-- SCORE & METRICS COLUMN -->
            <div class="flex flex-col gap-6">
                <!-- Score Card -->
                <div class="bg-white rounded-[2rem] p-8 border border-outline-variant/30 shadow-sm flex flex-col justify-center items-center text-center h-full hover:border-outline-variant/60 transition-colors">
                    <p class="text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-4">Domain Score</p>
                    <div class="flex items-baseline justify-center gap-1 mb-2">
                        <span class="text-6xl font-black text-primary tracking-tighter"><?php echo htmlspecialchars((string) ($appraisal['score'] ?? '0.0'), ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="text-xl font-bold text-neutral-400">/10</span>
                    </div>
                    <span class="px-3 py-1 bg-surface-container rounded-full text-xs font-bold text-primary uppercase tracking-widest mt-2 mb-3">
                        <?php echo htmlspecialchars((string) ($appraisal['scoreLabel'] ?? 'Solid'), ENT_QUOTES, 'UTF-8'); ?> Asset
                    </span>
                    <p class="text-[10px] text-neutral-400 max-w-[200px]">Calculated using length, keywords, TLD strength, and market appeal.</p>
                </div>

                <!-- Liquidity Card -->
                <div class="bg-white rounded-[2rem] p-8 border border-outline-variant/30 shadow-sm flex flex-col justify-center h-full hover:border-outline-variant/60 transition-colors">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-secondary uppercase tracking-[0.2em]">Market Liquidity</p>
                        <span class="material-symbols-outlined text-primary">water_drop</span>
                    </div>
                    <p class="text-4xl font-black text-primary tracking-tighter mb-2"><?php echo (int) ($appraisal['liquidityPercent'] ?? 0); ?>%</p>
                    <div class="w-full bg-surface-container rounded-full h-1.5 mb-3">
                        <div class="bg-primary h-1.5 rounded-full" style="width: <?php echo (int) ($appraisal['liquidityPercent'] ?? 0); ?>%"></div>
                    </div>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed">
                        <?php echo htmlspecialchars((string) ($appraisal['liquiditySummary'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <p class="text-[10px] text-neutral-400 mt-3 pt-3 border-t border-outline-variant/30">Estimated probability of a fast sale.</p>
                </div>
            </div>
        </div>

        <!-- DETAILS GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-16">
            
            <!-- Valuation Factors -->
            <div class="bg-white rounded-[2rem] border border-outline-variant/30 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-outline-variant/20 bg-surface-container-lowest">
                    <h3 class="text-xl font-extrabold tracking-tight text-primary">Valuation Drivers</h3>
                    <p class="text-xs text-secondary mt-1">The algorithmic components that shape the baseline price.</p>
                </div>
                <div class="divide-y divide-outline-variant/20">
                    <?php foreach ((array) ($appraisal['valuationFactors'] ?? []) as $factor): ?>
                        <div class="p-6 flex justify-between items-center hover:bg-surface-container-low transition-colors">
                            <div class="w-2/3 pr-4">
                                <p class="text-sm font-bold text-primary mb-1"><?php echo htmlspecialchars((string) ($factor['factor'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="text-[11px] font-semibold text-secondary leading-snug"><?php echo htmlspecialchars((string) ($factor['indicator'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="text-[10px] text-neutral-400 mt-1"><?php echo htmlspecialchars((string) ($factor['note'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                            <div class="w-1/3 text-right">
                                <span class="text-xl font-black text-primary"><?php echo (int) ($factor['score'] ?? 0); ?></span>
                                <span class="text-[10px] font-bold text-neutral-400">/100</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex flex-col gap-10">
                <!-- Pricing Strategy / Tiers -->
                <div class="bg-white rounded-[2rem] border border-outline-variant/30 shadow-sm p-8">
                    <h3 class="text-xl font-extrabold tracking-tight text-primary mb-2">Pricing Strategy</h3>
                    <p class="text-xs text-secondary mb-6">Value fluctuates based on who is buying the asset.</p>
                    
                    <div class="space-y-3">
                        <?php 
                        $tierDescriptions = [
                            'retail' => 'End-User / Business Buyer',
                            'investor' => 'Domain Flipper / Speculator',
                            'liquid' => 'Wholesale / Fast Cash'
                        ];
                        foreach ((array) ($appraisal['pricingTiers'] ?? []) as $key => $tier): 
                            $isRetail = $key === 'retail';
                        ?>
                            <div class="flex items-center justify-between p-4 rounded-xl <?php echo $isRetail ? 'bg-primary text-white shadow-md' : 'bg-surface-container-lowest border border-outline-variant/30 text-primary hover:bg-surface-container-low transition-colors'; ?>">
                                <div>
                                    <p class="text-sm font-bold <?php echo $isRetail ? 'text-white' : 'text-primary'; ?>">
                                        <?php echo htmlspecialchars((string) ($tier['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                    <p class="text-[10px] uppercase tracking-widest mt-1 <?php echo $isRetail ? 'text-neutral-400' : 'text-secondary'; ?>">
                                        <?php echo $tierDescriptions[$key] ?? 'Market Value'; ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-base font-black"><?php echo htmlspecialchars((string) ($tier['low'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="text-xs <?php echo $isRetail ? 'text-neutral-300' : 'text-secondary'; ?>">to <?php echo htmlspecialchars((string) ($tier['high'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-6 pt-6 border-t border-outline-variant/20">
                        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[14px]">lightbulb</span> Recommendation
                        </p>
                        <p class="text-sm text-primary font-medium leading-relaxed">
                            <?php echo htmlspecialchars((string) ($appraisal['pricingStrategy']['auctionNote'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                </div>

                <!-- Market Fit Quick Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach (array_slice($appraisal['signals'], 0, 4) as $signal): ?>
                        <div class="bg-white p-5 rounded-2xl border border-outline-variant/30 shadow-sm flex flex-col items-center text-center hover:border-outline-variant/60 transition-colors">
                            <span class="material-symbols-outlined text-primary mb-3 text-2xl" style="font-variation-settings: 'FILL' 1;"><?php echo htmlspecialchars((string) ($signal['icon'] ?? 'analytics'), ENT_QUOTES, 'UTF-8'); ?></span>
                            <div class="text-xl font-black text-primary tracking-tight break-all"><?php echo htmlspecialchars((string) ($signal['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="text-[9px] font-bold text-secondary uppercase tracking-[0.15em] mt-1"><?php echo htmlspecialchars((string) ($signal['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- COMPARABLE SALES TABLE -->
        <?php if (!empty($appraisal['comparableSales'])): ?>
        <section class="mb-16">
            <div class="bg-white rounded-[2rem] border border-outline-variant/30 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-lowest">
                    <div>
                        <h3 class="text-2xl font-extrabold tracking-tight text-primary">Historical Comparables</h3>
                        <p class="text-sm text-secondary mt-1">Verified public sales used to anchor the algorithmic model.</p>
                    </div>
                    <div class="bg-surface-container-low px-5 py-3 rounded-xl border border-outline-variant/20 text-center sm:text-right">
                        <div class="text-[10px] font-bold text-secondary uppercase tracking-widest">Category Median</div>
                        <div class="text-xl font-black text-primary mt-1">
                            <?php echo htmlspecialchars(whois_currency_format_amount(whois_currency_convert_amount((float) ($appraisal['category']['medianUsd'] ?? 0), 'USD', $selectedCurrency), $selectedCurrency), ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-surface-container-low text-[10px] font-bold uppercase tracking-widest text-secondary border-b border-outline-variant/20">
                                <th class="px-8 py-5">Domain Asset</th>
                                <th class="px-8 py-5">Sold Price</th>
                                <th class="px-8 py-5">Year</th>
                                <th class="px-8 py-5">Match Quality</th>
                                <th class="px-8 py-5 text-right">Data Source</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10 text-sm">
                            <?php foreach ($appraisal['comparableSales'] as $sale): ?>
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="px-8 py-5 font-bold text-primary text-base"><?php echo htmlspecialchars((string) ($sale['domain'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="px-8 py-5 font-black text-emerald-700 text-base"><?php echo htmlspecialchars((string) ($sale['soldPrice'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="px-8 py-5 text-secondary"><?php echo htmlspecialchars((string) ($sale['year'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="px-8 py-5">
                                        <?php 
                                            $sim = strtoupper((string)($sale['similarity'] ?? 'MED'));
                                            $simClass = $sim === 'EXACT' || $sim === 'HIGH' ? 'bg-indigo-100 text-indigo-800 border-indigo-200' : 'bg-surface-container text-secondary border-outline-variant/30';
                                        ?>
                                        <span class="px-3 py-1.5 border rounded text-[10px] font-bold tracking-widest <?php echo $simClass; ?>">
                                            <?php echo htmlspecialchars($sim, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right text-neutral-400 text-xs"><?php echo htmlspecialchars((string) ($sale['source'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- BUYER DEMOGRAPHICS -->
        <section class="mb-16">
            <h3 class="text-2xl font-extrabold tracking-tight mb-6 text-primary">Potential End Users</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <?php foreach (array_slice($appraisal['endUsers'], 0, 5) as $endUser): ?>
                    <div class="bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-start text-left">
                        <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center mb-4 text-primary">
                            <span class="material-symbols-outlined text-[20px]"><?php echo htmlspecialchars((string) ($endUser['icon'] ?? 'public'), ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <h4 class="font-bold text-sm text-primary mb-2 tracking-tight"><?php echo htmlspecialchars((string) ($endUser['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed"><?php echo htmlspecialchars((string) ($endUser['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    <?php require __DIR__ . '/_footer.php'; ?>
    <script src="../assets/js/nav-state.js"></script>
</body>
</html>