<?php
declare(strict_types=1);

require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/domain-appraisal.php';

$initialInput = trim((string) ($_GET['domain'] ?? $_GET['query'] ?? 'trovalabs.com'));
$selectedCurrency = whois_currency_normalize_code((string) ($_GET['currency'] ?? 'USD'), 'USD');
$appraisal = whois_domain_appraisal_analyze($initialInput, $selectedCurrency);
$domain = (string) ($appraisal['domain'] ?? 'trovalabs.com');
$reportUrl = '/pages/whois_comprehensive_search_results.php?query=' . rawurlencode($domain) . '&currency=' . rawurlencode($selectedCurrency);
$submitUrl = '/pages/whois_submit_domain_for_auction.php?domain=' . rawurlencode($domain);
$marketplaceUrl = '/pages/whois_premium_domain_marketplace.php?query=' . rawurlencode((string) ($appraisal['rootWord'] ?? $domain));
$assistantUrl = '/pages/whois_ai_brand_assistant.php';
$aiEnabled = whois_ai_config()['apiKey'] !== null;

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | Professional Appraisal | <?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?></title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
  :root {
    color-scheme: light;
  }
  body {
    font-family: 'Inter', sans-serif;
    background:
      radial-gradient(circle at top left, rgba(0, 0, 0, 0.05), transparent 26%),
      radial-gradient(circle at bottom right, rgba(0, 0, 0, 0.035), transparent 24%),
      linear-gradient(180deg, #fbfbfb 0%, #f4f4f4 100%);
  }
  h1, h2, h3, h4, .headline {
    font-family: 'Manrope', sans-serif;
  }
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    vertical-align: middle;
  }
  .appraisal-panel {
    background: rgba(255, 255, 255, 0.86);
    backdrop-filter: blur(18px);
    box-shadow: 0 28px 70px rgba(0, 0, 0, 0.06);
  }
  .appraisal-card {
    background: linear-gradient(180deg, #ffffff 0%, #f4f4f4 100%);
    border: 1px solid rgba(198, 198, 198, 0.45);
  }
</style>
</head>
<body class="text-on-surface antialiased selection:bg-black selection:text-white">
<?php require __DIR__ . '/_top_nav.php'; ?>

<main class="pt-32 pb-24 px-6 max-w-7xl mx-auto">
  <section class="mb-8">
    <div class="appraisal-panel rounded-[2rem] border border-outline-variant/20 overflow-hidden">
      <div class="grid gap-10 lg:grid-cols-[1.15fr_0.85fr] p-8 md:p-12 lg:p-16">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-surface-container-low rounded-full border border-outline-variant/40">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">stars</span>
            <span class="text-xs font-bold tracking-widest uppercase">Professional Appraisal</span>
          </div>
          <h1 class="mt-6 text-5xl md:text-7xl font-extrabold tracking-tighter text-primary leading-none">
            Appraise Any Domain
          </h1>
          <p class="mt-6 text-lg text-on-surface-variant max-w-2xl leading-relaxed">
            AI-assisted valuation powered by registry data, market comparables, and domain-fit signals. Enter any domain to get a score, value range, and launch recommendation.
          </p>

          <form class="mt-8 max-w-3xl" action="whois_domain_appraisal_tool.php" method="get">
            <input type="hidden" name="currency" value="<?php echo htmlspecialchars($selectedCurrency, ENT_QUOTES, 'UTF-8'); ?>"/>
            <div class="bg-surface-container-lowest border border-outline-variant p-2 rounded-full flex items-center shadow-sm focus-within:ring-1 focus-within:ring-black transition-all duration-300">
              <span class="material-symbols-outlined ml-4 text-outline">search</span>
              <input name="domain" class="w-full bg-transparent border-none focus:ring-0 px-4 text-lg font-medium text-primary placeholder:text-neutral-400" placeholder="Appraise a domain, e.g. trovalabs.com" type="text" value="<?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?>"/>
              <button type="submit" class="bg-black text-white px-8 py-3 rounded-full font-bold transition-transform active:scale-95">Appraise</button>
            </div>
          </form>

          <div class="mt-5 flex flex-wrap gap-3">
            <a class="inline-flex items-center gap-2 rounded-full bg-black px-5 py-3 text-sm font-bold text-white hover:bg-neutral-800 transition-colors" href="<?php echo htmlspecialchars($reportUrl, ENT_QUOTES, 'UTF-8'); ?>">
              <span class="material-symbols-outlined text-sm">description</span>
              View Full Report
            </a>
            <a class="inline-flex items-center gap-2 rounded-full border border-outline-variant/40 bg-white px-5 py-3 text-sm font-bold text-primary hover:border-black transition-colors" href="<?php echo htmlspecialchars($submitUrl, ENT_QUOTES, 'UTF-8'); ?>">
              <span class="material-symbols-outlined text-sm">stars</span>
              Start Submission
            </a>
            <a class="inline-flex items-center gap-2 rounded-full border border-outline-variant/40 bg-white px-5 py-3 text-sm font-bold text-primary hover:border-black transition-colors" href="<?php echo htmlspecialchars($assistantUrl, ENT_QUOTES, 'UTF-8'); ?>">
              <span class="material-symbols-outlined text-sm">smart_toy</span>
              Open AI Assistant
            </a>
          </div>

          <div class="mt-8 flex flex-wrap gap-3 text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">
            <a class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2 hover:border-black hover:text-black transition-colors" href="?domain=<?php echo rawurlencode($domain); ?>&amp;currency=USD">USD</a>
            <a class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2 hover:border-black hover:text-black transition-colors" href="?domain=<?php echo rawurlencode($domain); ?>&amp;currency=KES">KES</a>
            <span class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2 text-neutral-400">AI <?php echo $aiEnabled ? 'ON' : 'FALLBACK'; ?></span>
            <span class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2 text-neutral-400"><?php echo htmlspecialchars((string) ($appraisal['category']['label'] ?? 'Brandable'), ENT_QUOTES, 'UTF-8'); ?></span>
          </div>
        </div>

        <div class="relative">
          <div class="absolute inset-0 rounded-[2rem] bg-[radial-gradient(circle_at_top,#ffffff_0,#f6f6f6_35%,#ececec_100%)]"></div>
          <div class="relative rounded-[2rem] border border-outline-variant/20 bg-white/90 p-6 shadow-[0_24px_60px_rgba(0,0,0,0.05)]">
            <div class="flex items-center gap-3 mb-5">
              <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-black">W</div>
              <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Target Domain</p>
                <p class="font-bold text-lg text-primary break-all"><?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
              <div class="rounded-2xl bg-black text-white p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-white/60">Estimated Value</p>
                <p class="mt-2 text-4xl font-black tracking-tight"><?php echo htmlspecialchars((string) ($appraisal['estimatedValue'] ?? 'Unavailable'), ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="mt-2 text-sm text-white/65">Range: <?php echo htmlspecialchars((string) ($appraisal['valueLow'] ?? ''), ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars((string) ($appraisal['valueHigh'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
              <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/40">
                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Domain Score</p>
                <div class="mt-2 flex items-end gap-2">
                  <span class="text-4xl font-black tracking-tight text-primary"><?php echo htmlspecialchars((string) ($appraisal['score'] ?? '0.0'), ENT_QUOTES, 'UTF-8'); ?></span>
                  <span class="pb-1 text-sm font-bold text-neutral-400">/10</span>
                </div>
                <p class="mt-2 text-sm text-on-surface-variant"><?php echo htmlspecialchars((string) ($appraisal['scoreLabel'] ?? 'Solid'), ENT_QUOTES, 'UTF-8'); ?> market profile</p>
              </div>
              <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/40 sm:col-span-2">
                <div class="flex items-center justify-between gap-4">
                  <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Market Liquidity</p>
                    <p class="mt-2 text-2xl font-black tracking-tight text-primary"><?php echo (int) ($appraisal['liquidityPercent'] ?? 0); ?>%</p>
                  </div>
                  <div class="text-right">
                    <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Registry</p>
                    <p class="mt-2 text-sm font-bold text-primary"><?php echo htmlspecialchars((string) ($appraisal['lookup']['statusLabel'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="text-xs text-on-surface-variant"><?php echo htmlspecialchars((string) ($appraisal['statusSummary'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-5 rounded-2xl border border-outline-variant/20 bg-surface-container-lowest p-4">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-2">AI summary</p>
              <p class="text-sm leading-relaxed text-on-surface-variant"><?php echo htmlspecialchars((string) ($appraisal['aiInsight']['summary'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
    <div class="appraisal-card md:col-span-2 p-8 rounded-[2rem] flex flex-col justify-between">
      <div>
        <span class="text-sm font-bold opacity-60 tracking-widest uppercase text-on-surface-variant">Estimated Range</span>
        <h2 class="text-4xl md:text-6xl font-black tracking-tighter mt-2 text-primary"><?php echo htmlspecialchars((string) ($appraisal['valueLow'] ?? ''), ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars((string) ($appraisal['valueHigh'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
      </div>
      <div class="flex items-end justify-between mt-12 gap-6">
        <div class="space-y-1">
          <span class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Estimated Value</span>
          <div class="flex items-baseline gap-2">
            <span class="text-5xl font-black text-primary"><?php echo htmlspecialchars((string) ($appraisal['estimatedValue'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="text-xs font-bold px-2 py-1 bg-surface-container-low rounded text-neutral-500">BETA</span>
          </div>
        </div>
        <div class="text-right">
          <span class="text-sm font-bold text-on-surface-variant uppercase tracking-widest">Domain Score</span>
          <div class="text-5xl font-black text-primary"><?php echo htmlspecialchars((string) ($appraisal['score'] ?? '0.0'), ENT_QUOTES, 'UTF-8'); ?><span class="text-2xl opacity-40">/10</span></div>
        </div>
      </div>
    </div>

    <div class="appraisal-card p-8 rounded-[2rem] flex flex-col justify-center items-center text-center">
      <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center mb-6 border border-outline-variant/20">
        <span class="material-symbols-outlined text-4xl text-primary">analytics</span>
      </div>
      <h3 class="text-xl font-bold mb-2 text-primary">Market Liquidity</h3>
      <p class="text-on-surface-variant text-sm px-4 leading-relaxed">
        <?php echo htmlspecialchars((string) ($appraisal['liquiditySummary'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
      </p>
      <a class="mt-8 text-sm font-black border-b-2 border-primary pb-1 text-primary" href="<?php echo htmlspecialchars($reportUrl, ENT_QUOTES, 'UTF-8'); ?>">View Full Report</a>
    </div>
  </section>

  <section class="mb-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <?php foreach ($appraisal['signals'] as $signal): ?>
        <div class="appraisal-card p-6 rounded-2xl">
          <span class="material-symbols-outlined text-primary mb-4" style="font-variation-settings: 'FILL' 1;"><?php echo htmlspecialchars((string) ($signal['icon'] ?? 'analytics'), ENT_QUOTES, 'UTF-8'); ?></span>
          <div class="text-2xl font-black text-primary"><?php echo htmlspecialchars((string) ($signal['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mt-1"><?php echo htmlspecialchars((string) ($signal['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16">
    <div class="lg:col-span-2 rounded-[2rem] border border-outline-variant/40 bg-white overflow-hidden">
      <div class="p-8 border-b border-outline-variant/20 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h3 class="text-xl font-bold tracking-tight text-primary">Comparable Sales</h3>
          <p class="text-sm text-on-surface-variant mt-1">Curated reference comps used to anchor the model.</p>
        </div>
        <div class="text-right">
          <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Median Price</div>
          <div class="text-lg font-black text-primary">
            <?php echo htmlspecialchars(whois_currency_format_amount(whois_currency_convert_amount((float) ($appraisal['category']['medianUsd'] ?? 0), 'USD', $selectedCurrency), $selectedCurrency), ENT_QUOTES, 'UTF-8'); ?>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-surface-container-low">
              <th class="px-8 py-4 text-xs font-bold text-on-surface-variant uppercase">Domain</th>
              <th class="px-8 py-4 text-xs font-bold text-on-surface-variant uppercase">Sold Price</th>
              <th class="px-8 py-4 text-xs font-bold text-on-surface-variant uppercase">Year</th>
              <th class="px-8 py-4 text-xs font-bold text-on-surface-variant uppercase">Similarity</th>
              <th class="px-8 py-4 text-xs font-bold text-on-surface-variant uppercase">Source</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-outline-variant/10">
            <?php foreach ($appraisal['comparableSales'] as $comparableSale): ?>
              <tr class="hover:bg-surface-container-low transition-colors">
                <td class="px-8 py-5 font-bold text-primary"><?php echo htmlspecialchars((string) ($comparableSale['domain'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="px-8 py-5 font-black text-primary"><?php echo htmlspecialchars((string) ($comparableSale['soldPrice'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="px-8 py-5 text-on-surface-variant"><?php echo htmlspecialchars((string) ($comparableSale['year'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="px-8 py-5"><span class="px-2 py-1 bg-surface-container text-[10px] font-bold rounded"><?php echo htmlspecialchars((string) ($comparableSale['similarity'] ?? 'MED'), ENT_QUOTES, 'UTF-8'); ?></span></td>
                <td class="px-8 py-5 text-sm text-on-surface-variant"><?php echo htmlspecialchars((string) ($comparableSale['source'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="space-y-6">
      <div class="appraisal-card p-8 rounded-[2rem] h-full">
        <h3 class="text-xl font-bold mb-6 text-primary">Root Word: <?php echo htmlspecialchars((string) ($appraisal['rootWord'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
        <div class="space-y-6">
          <div>
            <div class="flex justify-between text-xs font-bold mb-2 text-on-surface-variant">
              <span>MARKET POPULARITY</span>
              <span><?php echo (int) ($appraisal['marketPopularity'] ?? 0); ?>%</span>
            </div>
            <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
              <div class="h-full bg-primary" style="width: <?php echo (int) ($appraisal['marketPopularity'] ?? 0); ?>%;"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between text-xs font-bold mb-2 text-on-surface-variant">
              <span>INVESTOR INTEREST</span>
              <span><?php echo (int) ($appraisal['investorInterest'] ?? 0); ?>%</span>
            </div>
            <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
              <div class="h-full bg-primary" style="width: <?php echo (int) ($appraisal['investorInterest'] ?? 0); ?>%;"></div>
            </div>
          </div>
          <div class="pt-4 border-t border-outline-variant/20">
            <p class="text-sm text-on-surface-variant leading-relaxed">
              <?php echo htmlspecialchars((string) ($appraisal['summary'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
    <div class="appraisal-card p-8 rounded-[2rem]">
      <h3 class="text-xl font-bold mb-6 text-primary">Infrastructure Status</h3>
      <div class="space-y-4">
        <?php foreach ($appraisal['infrastructure'] as $infrastructureItem): ?>
          <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-outline-variant/20 gap-4">
            <div class="flex items-start gap-3">
              <span class="material-symbols-outlined <?php echo (string) ($infrastructureItem['tone'] ?? 'neutral') === 'success' ? 'text-green-600' : ((string) ($infrastructureItem['tone'] ?? 'neutral') === 'warning' ? 'text-amber-600' : 'text-primary'); ?>"><?php echo (string) ($infrastructureItem['tone'] ?? 'neutral') === 'warning' ? 'pending' : 'check_circle'; ?></span>
              <div>
                <span class="font-bold text-sm text-primary"><?php echo htmlspecialchars((string) ($infrastructureItem['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                <p class="text-xs text-on-surface-variant mt-1 leading-relaxed"><?php echo htmlspecialchars((string) ($infrastructureItem['details'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
            <span class="text-xs font-bold text-on-surface-variant whitespace-nowrap"><?php echo htmlspecialchars((string) ($infrastructureItem['status'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="appraisal-card p-8 rounded-[2rem]">
      <h3 class="text-xl font-bold mb-6 text-primary">Market Fit Analytics</h3>
      <div class="flex items-center gap-6">
        <div class="flex-1 text-center p-6 bg-white rounded-2xl border border-outline-variant/20">
          <div class="text-3xl font-black text-primary"><?php echo (int) ($appraisal['memorability'] ?? 0); ?>%</div>
          <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Memorability</div>
        </div>
        <div class="flex-1 text-center p-6 bg-white rounded-2xl border border-outline-variant/20">
          <div class="text-3xl font-black text-primary"><?php echo (int) ($appraisal['brandability'] ?? 0); ?>%</div>
          <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Brandability</div>
        </div>
      </div>
      <p class="mt-6 text-sm text-on-surface-variant leading-relaxed"><?php echo htmlspecialchars((string) ($appraisal['aiInsight']['summary'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
      <div class="mt-6 grid gap-3">
        <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/20 p-4">
          <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-2">AI Recommendation</div>
          <p class="text-sm text-on-surface-variant leading-relaxed"><?php echo htmlspecialchars((string) ($appraisal['aiInsight']['recommendation'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/20 p-4">
          <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-2">Confidence</div>
          <p class="text-sm font-bold text-primary"><?php echo (int) ($appraisal['aiInsight']['confidence'] ?? 0); ?>%</p>
        </div>
      </div>
    </div>
  </section>

  <section class="mb-16">
    <h3 class="text-3xl font-black tracking-tight mb-8 text-primary">Potential End Users</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
      <?php foreach ($appraisal['endUsers'] as $endUser): ?>
        <div class="appraisal-card p-6 rounded-2xl hover:shadow-lg transition-shadow">
          <span class="material-symbols-outlined text-primary mb-4"><?php echo htmlspecialchars((string) ($endUser['icon'] ?? 'public'), ENT_QUOTES, 'UTF-8'); ?></span>
          <h4 class="font-bold mb-2 text-primary"><?php echo htmlspecialchars((string) ($endUser['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h4>
          <p class="text-xs text-on-surface-variant leading-relaxed"><?php echo htmlspecialchars((string) ($endUser['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="mb-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-primary p-8 rounded-[2rem] text-on-primary flex flex-col justify-between items-start">
        <div>
          <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined">stars</span>
            <span class="text-xs font-bold tracking-widest uppercase">Premium Service</span>
          </div>
          <h3 class="text-2xl font-black mb-4">Submit to Premium Marketplace</h3>
          <p class="text-on-primary/70 text-sm mb-8 leading-relaxed">Get your domain featured in front of elite buyers and serious acquisition leads.</p>
        </div>
        <a class="w-full bg-white text-black font-black py-4 rounded-xl hover:bg-neutral-200 transition-colors text-center" href="<?php echo htmlspecialchars($submitUrl, ENT_QUOTES, 'UTF-8'); ?>">Start Submission</a>
      </div>
      <div class="bg-white p-8 rounded-[2rem] border border-outline-variant/40 flex flex-col justify-between items-start">
        <div>
          <h3 class="text-2xl font-black mb-4 text-primary">Standard Listing</h3>
          <p class="text-on-surface-variant text-sm mb-8 leading-relaxed">List the domain on the public marketplace and compare it against live inventory.</p>
        </div>
        <a class="w-full bg-primary text-white font-black py-4 rounded-xl hover:bg-primary-container transition-colors text-center" href="<?php echo htmlspecialchars($marketplaceUrl, ENT_QUOTES, 'UTF-8'); ?>">List Domain</a>
      </div>
      <div class="bg-white p-8 rounded-[2rem] border border-outline-variant/40 flex flex-col justify-between items-start">
        <div>
          <h3 class="text-2xl font-black mb-4 text-primary">White Label Marketplace</h3>
          <p class="text-on-surface-variant text-sm mb-8 leading-relaxed">Create your own branded storefront to showcase a portfolio or private inventory.</p>
        </div>
        <a class="w-full border-2 border-primary text-black font-black py-4 rounded-xl hover:bg-surface-container-low transition-colors text-center" href="whois_partner_with_us.php">Setup Store</a>
      </div>
    </div>
  </section>

  <section class="mb-16">
    <div class="appraisal-card rounded-[2rem] p-8 md:p-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
      <div class="max-w-2xl">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-2">AI Drivers</p>
        <h3 class="text-3xl font-black tracking-tight text-primary mb-4">What moved the valuation</h3>
        <p class="text-on-surface-variant leading-relaxed">The score blends name length, pronounceability, TLD strength, category demand, and liquidity. AI adds a short narrative so you can see why the range landed where it did.</p>
      </div>
      <div class="grid gap-3 md:grid-cols-3 flex-1">
        <?php foreach ($appraisal['drivers'] as $driver): ?>
          <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400 mb-2"><?php echo htmlspecialchars((string) ($driver['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="text-3xl font-black text-primary"><?php echo htmlspecialchars((string) ($driver['value'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="mt-2 text-xs text-on-surface-variant leading-relaxed"><?php echo htmlspecialchars((string) ($driver['note'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body></html>
