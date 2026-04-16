<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<?php require __DIR__ . '/_head.php'; ?>
<title>WHOIS Intelligence | Professional Domain Search &amp; AI Appraisal</title>
<meta name="description" content="Instant WHOIS lookup, AI-powered domain appraisal, name generation, and a premium marketplace. Discover, research, and acquire your perfect domain."/>
<style>
    .hero-panel {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(198, 198, 198, 0.55);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.06);
    }
</style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-20">

<!-- ═══ HERO ════════════════════════════════════════════════════════════════ -->
<section class="hero-gradient min-h-[820px] flex items-center px-6 relative overflow-hidden">
  <div class="max-w-7xl w-full mx-auto z-10 hero-grid py-16">

    <!-- Left: Headline + search -->
    <div class="max-w-3xl">
      <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full metric-pill text-[10px] font-bold uppercase tracking-[0.2em] text-outline mb-6">
        <span class="w-2 h-2 rounded-full bg-black"></span>
        WHOIS Intelligence Suite
      </span>
      <h1 class="font-headline text-5xl md:text-7xl font-extrabold tracking-tighter text-primary mb-6 leading-tight">
        Discover what a domain tells you <br/>before you buy it.
      </h1>
      <p class="text-on-surface-variant text-lg md:text-xl max-w-2xl mb-10">
        Instant WHOIS lookup, AI-powered domain insights, and premium recommendations in one search flow. Start with a name — see ownership, availability, and acquisition paths immediately.
      </p>

      <!-- Mode switcher -->
      <div class="mb-4 flex flex-wrap items-center gap-2">
        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Mode</span>
        <button id="hero-mode-search" class="rounded-full bg-black px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white" data-hero-mode="search" type="button">Search domain</button>
        <button id="hero-mode-ai" class="rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-primary" data-hero-mode="ai" type="button">Generate name with AI</button>
      </div>

      <!-- Search bar (fixed: input now has proper id) -->
      <form id="hero-search-form" action="<?=$assetBase?>/pages/whois_ai_domain_search.php" method="GET" class="relative max-w-2xl glass-panel p-1.5 rounded-full shadow-lg flex items-center border border-outline-variant/30 hover:shadow-primary/5 transition-all">
        <input type="hidden" name="currency" value="USD" id="hero-currency-field"/>
        <span class="material-symbols-outlined pl-5 text-outline shrink-0">search</span>
        <input
          id="hero-domain-input"
          name="query"
          type="text"
          class="w-full bg-transparent border-none focus:ring-0 text-base md:text-lg px-4 py-3.5 font-medium text-primary placeholder:text-neutral-400"
          placeholder="enter-your-dream-domain.com"
          autocomplete="off"
          required
        />
        <button id="hero-search-button" type="submit" class="bg-primary text-on-primary px-6 md:px-8 py-3.5 rounded-full font-bold text-sm tracking-wide uppercase flex items-center gap-2 hover:bg-primary-container transition-all active:scale-95 shrink-0">
          Search
        </button>
      </form>

      <!-- Quick TLD chips -->
      <div id="hero-quick-tlds" class="mt-5 flex flex-wrap items-center gap-2">
        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Quick extensions</span>
        <button class="tld-chip px-3 py-1.5 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".com" type="button">.com</button>
        <button class="tld-chip px-3 py-1.5 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".ai" type="button">.ai</button>
        <button class="tld-chip px-3 py-1.5 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".io" type="button">.io</button>
        <button class="tld-chip px-3 py-1.5 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".co" type="button">.co</button>
        <button class="tld-chip px-3 py-1.5 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".app" type="button">.app</button>
        <button class="tld-chip px-3 py-1.5 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".net" type="button">.net</button>
      </div>

      <div class="mt-6 flex flex-wrap gap-2 text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">
        <span class="px-3 py-1.5 rounded-full bg-surface-container-low">WHOIS lookup</span>
        <span class="px-3 py-1.5 rounded-full bg-surface-container-low">AI appraisal</span>
        <span class="px-3 py-1.5 rounded-full bg-surface-container-low">Premium matches</span>
        <span class="px-3 py-1.5 rounded-full bg-surface-container-low">DNS checker</span>
      </div>
    </div>

    <!-- Right: "From query to decision" panel -->
    <div class="hero-panel rounded-[2rem] p-6 md:p-8 relative overflow-hidden hidden lg:block">
      <div class="absolute top-0 right-0 w-64 h-64 bg-secondary-container/20 rounded-full blur-[100px]"></div>
      <div class="absolute bottom-0 left-0 w-72 h-72 bg-primary-container/10 rounded-full blur-[120px]"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-6">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-2">Live intent</p>
            <h2 class="text-2xl font-headline font-bold text-primary">From query to decision</h2>
          </div>
          <span class="px-3 py-1 rounded-full bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em]">3 steps</span>
        </div>
        <div class="space-y-4">
          <div class="signal-card rounded-2xl p-4">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border border-outline-variant/30 shrink-0">
                <span class="material-symbols-outlined text-sm">language</span>
              </div>
              <div>
                <div class="flex items-center justify-between gap-3 mb-1">
                  <h3 class="font-bold text-primary text-sm">Search ownership</h3>
                  <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">WHOIS</span>
                </div>
                <p class="text-xs text-on-surface-variant">Registrar, creation date, and status — instantly.</p>
              </div>
            </div>
          </div>
          <div class="signal-card rounded-2xl p-4">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border border-outline-variant/30 shrink-0">
                <span class="material-symbols-outlined text-sm">auto_awesome</span>
              </div>
              <div>
                <div class="flex items-center justify-between gap-3 mb-1">
                  <h3 class="font-bold text-primary text-sm">Score the brand fit</h3>
                  <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">AI</span>
                </div>
                <p class="text-xs text-on-surface-variant">Memorability, demand, and resale potential.</p>
              </div>
            </div>
          </div>
          <div class="signal-card rounded-2xl p-4">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border border-outline-variant/30 shrink-0">
                <span class="material-symbols-outlined text-sm">shopping_cart</span>
              </div>
              <div>
                <div class="flex items-center justify-between gap-3 mb-1">
                  <h3 class="font-bold text-primary text-sm">Move to acquisition</h3>
                  <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">Marketplace</span>
                </div>
                <p class="text-xs text-on-surface-variant">Premium listings, auctions, or brokerage.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-5 grid grid-cols-2 gap-3">
          <div class="metric-pill rounded-2xl p-4">
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400 mb-1">Available now</p>
            <p class="text-xl font-black tracking-tight text-primary">1,204</p>
          </div>
          <div class="metric-pill rounded-2xl p-4">
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400 mb-1">Premium picks</p>
            <p class="text-xl font-black tracking-tight text-primary">284</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<script>
(function () {
  var input = document.getElementById('hero-domain-input');
  var searchBtn = document.getElementById('hero-search-button');
  var form = document.getElementById('hero-search-form');
  var chips = document.querySelectorAll('.tld-chip');
  var modeSearch = document.getElementById('hero-mode-search');
  var modeAi = document.getElementById('hero-mode-ai');
  var quickTlds = document.getElementById('hero-quick-tlds');
  var mode = 'search';

  if (!input) return;

  function setMode(next) {
    mode = next === 'ai' ? 'ai' : 'search';
    if (mode === 'ai') {
      modeAi.className = 'rounded-full bg-black px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white';
      modeSearch.className = 'rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-primary';
      input.placeholder = 'Describe your business in one sentence';
      searchBtn.textContent = 'Generate Names';
      quickTlds.classList.add('hidden');
      form.removeAttribute('action');
      form.setAttribute('data-ai-mode', '1');
    } else {
      modeSearch.className = 'rounded-full bg-black px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white';
      modeAi.className = 'rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-primary';
      input.placeholder = 'enter-your-dream-domain.com';
      searchBtn.textContent = 'Search';
      quickTlds.classList.remove('hidden');
      form.action = '<?=$assetBase?>/pages/whois_ai_domain_search.php';
      form.removeAttribute('data-ai-mode');
    }
  }

  chips.forEach(function(chip) {
    chip.addEventListener('click', function() {
      var tld = chip.getAttribute('data-tld') || '.com';
      var current = input.value.trim();
      if (!current) { input.value = ''; input.focus(); return; }
      var base = current.split('.')[0];
      input.value = base + tld;
      input.focus();
    });
  });

  form.addEventListener('submit', function(e) {
    if (form.dataset.aiMode) {
      e.preventDefault();
      var q = input.value.trim();
      if (!q) { input.focus(); return; }
      window.location.href = '<?=$assetBase?>/pages/whois_ai_generated_domains.php?idea=' + encodeURIComponent(q);
    }
  });

  if (modeSearch) modeSearch.addEventListener('click', function() { setMode('search'); });
  if (modeAi) modeAi.addEventListener('click', function() { setMode('ai'); });

  setMode('search');
}());
</script>

<!-- ═══ FEATURES ═══════════════════════════════════════════════════════════ -->
<section class="py-24 px-6 max-w-7xl mx-auto">
  <div class="text-center mb-16">
    <span class="text-sm font-bold uppercase tracking-[0.2em] text-outline">Core Intelligence</span>
    <h2 class="font-headline text-4xl font-bold mt-4">Advanced Infrastructure Tools</h2>
  </div>
  <div class="bento-grid">
    <a href="whois_professional_lookup_tool.php" class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 block">
      <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
        <span class="material-symbols-outlined">database</span>
      </div>
      <h3 class="font-headline text-xl font-bold mb-3">WHOIS Lookup</h3>
      <p class="text-on-surface-variant leading-relaxed">Instant, comprehensive domain ownership records, registrar info, and RDAP data.</p>
    </a>
    <a href="whois_domain_appraisal_tool.php" class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 block">
      <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
        <span class="material-symbols-outlined">auto_awesome</span>
      </div>
      <h3 class="font-headline text-xl font-bold mb-3">AI Domain Appraisal</h3>
      <p class="text-on-surface-variant leading-relaxed">AI predicts precise market values and long-term liquidity potential for any domain.</p>
    </a>
    <a href="whois_dns_checker.php" class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 block">
      <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
        <span class="material-symbols-outlined">public</span>
      </div>
      <h3 class="font-headline text-xl font-bold mb-3">DNS Propagation Checker</h3>
      <p class="text-on-surface-variant leading-relaxed">Check DNS propagation across global nodes in real-time with a world-map overlay.</p>
    </a>
    <a href="whois_premium_domain_marketplace.php" class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 block">
      <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
        <span class="material-symbols-outlined">storefront</span>
      </div>
      <h3 class="font-headline text-xl font-bold mb-3">Premium Marketplace</h3>
      <p class="text-on-surface-variant leading-relaxed">Curated high-value domains for immediate acquisition via secure, verified escrow.</p>
    </a>
    <a href="whois_ai_brand_assistant.php" class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 block">
      <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
        <span class="material-symbols-outlined">psychology</span>
      </div>
      <h3 class="font-headline text-xl font-bold mb-3">AI Brand Assistant</h3>
      <p class="text-on-surface-variant leading-relaxed">Generate brand names, get domain suggestions, and explore business ideas with AI.</p>
    </a>
    <a href="whois_ai_domain_search.php" class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 block">
      <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
        <span class="material-symbols-outlined">alt_route</span>
      </div>
      <h3 class="font-headline text-xl font-bold mb-3">Smart Alternatives</h3>
      <p class="text-on-surface-variant leading-relaxed">Find creative, high-impact domain alternatives that align with your brand and SEO goals.</p>
    </a>
  </div>
</section>

<!-- ═══ HOW IT WORKS ════════════════════════════════════════════════════════ -->
<section class="bg-surface-container-low py-24 px-8 overflow-hidden">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row items-center justify-between gap-12">
      <div class="md:w-1/3">
        <h2 class="font-headline text-4xl font-bold mb-6">A Streamlined Acquisition Path</h2>
        <p class="text-on-surface-variant text-lg leading-relaxed">Building your digital identity shouldn't be complex. We've distilled the process into three decisive phases.</p>
        <a href="whois_ai_domain_search.php" class="inline-flex mt-8 rounded-full bg-black px-6 py-3 text-sm font-bold uppercase tracking-widest text-white hover:bg-neutral-800 transition-colors">Start Now</a>
      </div>
      <div class="md:w-2/3 w-full grid grid-cols-1 md:grid-cols-3 gap-8 relative">
        <div class="relative z-10 text-center flex flex-col items-center">
          <div class="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center mb-6 border border-outline-variant/20">
            <span class="material-symbols-outlined text-3xl">search</span>
          </div>
          <h4 class="font-bold text-lg mb-2">Search</h4>
          <p class="text-sm text-on-surface-variant">Query our global WHOIS &amp; RDAP databases instantly.</p>
        </div>
        <div class="relative z-10 text-center flex flex-col items-center">
          <div class="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center mb-6 border border-outline-variant/20">
            <span class="material-symbols-outlined text-3xl">analytics</span>
          </div>
          <h4 class="font-bold text-lg mb-2">Analyze</h4>
          <p class="text-sm text-on-surface-variant">Review AI appraisals, history logs, and brand scores.</p>
        </div>
        <div class="relative z-10 text-center flex flex-col items-center">
          <div class="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center mb-6 border border-outline-variant/20">
            <span class="material-symbols-outlined text-3xl">rocket_launch</span>
          </div>
          <h4 class="font-bold text-lg mb-2">Acquire</h4>
          <p class="text-sm text-on-surface-variant">Finalize your brand with confidence via escrow.</p>
        </div>
        <div class="hidden md:block absolute top-8 left-1/4 right-1/4 h-[2px] bg-outline-variant/30 -z-0"></div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ QUICK APPRAISAL ══════════════════════════════════════════════════════ -->
<section class="py-24 px-8 max-w-7xl mx-auto">
  <div class="bg-primary p-8 md:p-12 rounded-[2rem] overflow-hidden relative">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px]"></div>
    <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12">
      <div class="lg:w-1/2 text-on-primary">
        <span class="inline-block px-4 py-1 bg-white/10 rounded-full text-xs font-bold tracking-widest uppercase mb-6">Live AI Simulation</span>
        <h2 class="font-headline text-4xl font-bold mb-6">Try the Domain Appraisal Engine</h2>
        <p class="text-on-primary/70 text-lg leading-relaxed mb-8">Enter any domain name and our AI gives you an instant score, estimated value, and smart alternatives.</p>
        <form id="ai-appraisal-form" class="flex items-center gap-2 mb-6" onsubmit="return false;">
          <input id="ai-appraisal-domain" class="rounded-l-full px-5 py-4 w-full text-base font-bold border-none focus:ring-2 focus:ring-black" type="text" placeholder="e.g. mybrand.com" autocomplete="off"/>
          <button id="ai-appraisal-search" class="rounded-r-full px-6 py-4 bg-black text-white font-bold uppercase tracking-widest hover:bg-neutral-800 transition-all shrink-0" type="submit">Appraise</button>
        </form>
        <div class="space-y-3">
          <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl">
            <span class="material-symbols-outlined">verified</span>
            <div>
              <h5 class="font-bold text-sm">Liquidity Prediction</h5>
              <p class="text-xs text-on-primary/60">Estimated resell speed based on TLD demand.</p>
            </div>
          </div>
          <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl">
            <span class="material-symbols-outlined">token</span>
            <div>
              <h5 class="font-bold text-sm">Brand Affinity Score</h5>
              <p class="text-xs text-on-primary/60">Phonetic balance and memorability analysis.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="lg:w-1/2 w-full">
        <div id="ai-appraisal-card" class="bg-white rounded-3xl p-8 shadow-2xl min-h-[380px] flex flex-col justify-between">
          <div id="ai-appraisal-skeleton" class="hidden animate-pulse space-y-5">
            <div class="h-5 bg-surface-container-high rounded w-1/3"></div>
            <div class="h-8 bg-surface-container-high rounded w-2/3"></div>
            <div class="grid grid-cols-2 gap-4 mt-4">
              <div class="h-24 bg-surface-container-high rounded-2xl"></div>
              <div class="h-24 bg-surface-container-high rounded-2xl"></div>
            </div>
            <div class="h-5 bg-surface-container-high rounded w-1/4 mt-2"></div>
            <div class="flex gap-2">
              <div class="h-8 w-20 bg-surface-container-high rounded-xl"></div>
              <div class="h-8 w-20 bg-surface-container-high rounded-xl"></div>
            </div>
          </div>
          <div id="ai-appraisal-result">
            <div class="flex justify-between items-start mb-6">
              <div>
                <span class="bg-surface-container-high px-3 py-1 rounded text-[10px] font-black uppercase tracking-tighter mb-2 inline-block" id="ai-appraisal-status">Ready to appraise</span>
                <h3 class="text-3xl font-black tracking-tight" id="ai-appraisal-domain-label">example.com</h3>
              </div>
              <div class="bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-full text-sm font-bold flex items-center gap-1" id="ai-appraisal-availability">
                <span class="w-2 h-2 bg-neutral-400 rounded-full"></span> Enter domain
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="bg-surface p-4 rounded-2xl border border-outline-variant/20 flex flex-col items-center">
                <div class="relative w-20 h-20 flex items-center justify-center">
                  <svg class="w-full h-full -rotate-90" viewBox="0 0 96 96">
                    <circle cx="48" cy="48" fill="transparent" r="38" stroke="#f3f3f3" stroke-width="8"></circle>
                    <circle id="ai-appraisal-score-bar" cx="48" cy="48" fill="transparent" r="38" stroke="#000" stroke-dasharray="238.76" stroke-dashoffset="238.76" stroke-width="8" stroke-linecap="round"></circle>
                  </svg>
                  <span class="absolute text-xl font-black" id="ai-appraisal-score">—</span>
                </div>
                <p class="mt-3 font-bold text-xs uppercase tracking-widest text-outline">Score</p>
              </div>
              <div class="bg-surface p-4 rounded-2xl border border-outline-variant/20 flex flex-col items-center justify-center">
                <span class="text-sm text-outline font-medium mb-1">Est. Value</span>
                <p class="text-3xl font-black" id="ai-appraisal-value">—</p>
                <span class="text-[10px] font-bold text-outline mt-1 uppercase" id="ai-appraisal-currency">USD</span>
              </div>
            </div>
            <div class="flex flex-wrap gap-2 mb-5" id="ai-appraisal-alternatives"></div>
            <a id="ai-appraisal-details-btn" href="whois_domain_appraisal_tool.php" class="inline-block px-5 py-2.5 rounded-full bg-black text-white font-bold uppercase tracking-widest text-xs hover:bg-neutral-800 transition-all">Full Appraisal →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
(function() {
  var form = document.getElementById('ai-appraisal-form');
  var domainInput = document.getElementById('ai-appraisal-domain');
  var skeleton = document.getElementById('ai-appraisal-skeleton');
  var result = document.getElementById('ai-appraisal-result');
  var domainLabel = document.getElementById('ai-appraisal-domain-label');
  var valueLabel = document.getElementById('ai-appraisal-value');
  var scoreLabel = document.getElementById('ai-appraisal-score');
  var scoreBar = document.getElementById('ai-appraisal-score-bar');
  var statusLabel = document.getElementById('ai-appraisal-status');
  var availabilityLabel = document.getElementById('ai-appraisal-availability');
  var alternativesDiv = document.getElementById('ai-appraisal-alternatives');
  var detailsBtn = document.getElementById('ai-appraisal-details-btn');
  var currencyLabel = document.getElementById('ai-appraisal-currency');

  function setSkeleton(loading) {
    skeleton.classList.toggle('hidden', !loading);
    result.classList.toggle('hidden', loading);
  }

  function updateCard(data) {
    domainLabel.textContent = data.domain || 'Unknown';
    
    // Prioritize AI Price Range if available, otherwise use formatted single value
    var displayPrice = data.ai_price || data.estimatedValue || '—';
    valueLabel.textContent = displayPrice;
    
    scoreLabel.textContent = data.score != null ? data.score : '—';
    currencyLabel.textContent = (data.displayCurrency || 'USD') + (data.ai_price ? '' : ' / Annual');
    
    // Add AI Insight if available
    if (data.ai_insight) {
       statusLabel.textContent = 'AI Expert Appraisal';
       statusLabel.className = 'bg-black text-white px-3 py-1 rounded text-[10px] font-black uppercase tracking-tighter mb-2 inline-block';
    } else {
       statusLabel.textContent = 'Analysis Complete';
       statusLabel.className = 'bg-surface-container-high px-3 py-1 rounded text-[10px] font-black uppercase tracking-tighter mb-2 inline-block';
    }

    var percent = Math.max(0, Math.min(1, (parseFloat(data.score) || 0) / 10));
    scoreBar.setAttribute('stroke-dashoffset', (238.76 * (1 - percent)).toFixed(1));
    
    if (data.lookup && data.lookup.status === 'available') {
      availabilityLabel.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full"></span> Available';
      availabilityLabel.className = 'bg-green-50 text-green-700 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-1';
    } else {
      availabilityLabel.innerHTML = '<span class="w-2 h-2 bg-amber-400 rounded-full"></span> Registered';
      availabilityLabel.className = 'bg-amber-50 text-amber-700 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-1';
    }
    
    alternativesDiv.innerHTML = '';
    if (data.root) {
      ['.ai', '.io', '.co', '.app'].forEach(function(tld) {
        if (data.domain && data.domain.endsWith(tld)) return;
        var altDomain = data.root + tld;
        var btn = document.createElement('button');
        btn.className = 'px-3 py-1.5 bg-surface rounded-xl text-xs font-bold border border-outline-variant/30 hover:border-primary transition-colors';
        btn.textContent = altDomain;
        btn.onclick = function() {
            domainInput.value = altDomain;
            form.dispatchEvent(new Event('submit'));
        };
        alternativesDiv.appendChild(btn);
      });
    }
    detailsBtn.href = '<?=$assetBase?>/pages/whois_domain_appraisal_tool.php?domain=' + encodeURIComponent(data.domain || '');
  }

  form.addEventListener('submit', function(e) {
    if (e) e.preventDefault();
    var domain = domainInput.value.trim();
    if (!domain) { domainInput.focus(); return; }
    
    // Get currency from main hero form if available, or default to USD
    var currency = document.getElementById('hero-currency-field') ? document.getElementById('hero-currency-field').value : 'USD';
    
    setSkeleton(true);
    // Use root-relative path for more robust AJAX fetching
    var fetchUrl = '/api/appraise.php?domain=' + encodeURIComponent(domain) + '&currency=' + encodeURIComponent(currency);
    
    fetch(fetchUrl)
      .then(function(r) { 
        if (!r.ok) throw new Error('Network response was not ok');
        return r.json(); 
      })
      .then(function(data) { 
        setSkeleton(false); 
        updateCard(data); 
      })
      .catch(function(err) {
        console.error('Appraisal fetch error:', err);
        setSkeleton(false);
        domainLabel.textContent = 'Error'; 
        valueLabel.textContent = '—'; 
        scoreLabel.textContent = '—';
        statusLabel.textContent = 'Service Unavailable';
        statusLabel.className = 'bg-red-50 text-red-700 px-3 py-1 rounded text-[10px] font-black uppercase tracking-tighter mb-2 inline-block';
        alternativesDiv.innerHTML = '<p class="text-[10px] text-red-400">Failed to connect to appraisal intelligence server. Please try again.</p>';
      });
  });
}());
</script>

<!-- ═══ CONNECTED SUITE ═════════════════════════════════════════════════════ -->
<section class="py-24 px-6 max-w-7xl mx-auto">
  <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
    <div>
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4 block">Connected Suite</span>
      <h2 class="font-black text-4xl md:text-5xl tracking-tight text-primary">Your Domain Hub</h2>
    </div>
    <p class="text-on-surface-variant max-w-xl text-lg">Everything you need for domains — explore, create, buy, sell, and learn in one place.</p>
  </div>
  <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg" href="whois_ai_domain_search.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Search</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Find &amp; Discover</h3>
      <p class="mt-3 text-on-surface-variant text-sm">AI-powered search, availability checks, and premium name discovery tools.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg" href="whois_ai_brand_assistant.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">AI Assistants</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Get Naming Help</h3>
      <p class="mt-3 text-on-surface-variant text-sm">Brand naming, business idea generation, and domain name suggestions powered by LLaMA.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg" href="whois_premium_domain_marketplace.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Marketplace</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Buy &amp; Sell</h3>
      <p class="mt-3 text-on-surface-variant text-sm">Browse premium curated domains and live auctions. Submit your own for sale.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg" href="whois_domain_tools_overview.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Tools</span>
      <h3 class="mt-3 text-2xl font-black text-primary">All Domain Tools</h3>
      <p class="mt-3 text-on-surface-variant text-sm">Appraisals, DNS checker, WHOIS lookup, and brand preview — fast and easy.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg" href="whois_industry_insights_blog.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Insights</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Domain Intelligence Blog</h3>
      <p class="mt-3 text-on-surface-variant text-sm">Expert articles, market trends, and actionable domain strategy advice.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg" href="whois_partner_with_us.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Partner</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Partner With Us</h3>
      <p class="mt-3 text-on-surface-variant text-sm">Business partnerships, brokerage collaboration, and platform integration opportunities.</p>
    </a>
  </div>
</section>

<!-- ═══ TESTIMONIALS ════════════════════════════════════════════════════════ -->
<section class="py-24 px-8 max-w-7xl mx-auto">
  <div class="text-center mb-16">
    <h2 class="font-headline text-4xl font-bold mb-4">Trusted by Builders &amp; Investors</h2>
    <p class="text-on-surface-variant">Providing clarity for thousands of domain decisions annually.</p>
  </div>
  <div class="grid md:grid-cols-3 gap-8">
    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-outline-variant/10">
      <div class="flex gap-1 text-primary mb-6">
        <?php for ($i = 0; $i < 5; $i++): ?><span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">star</span><?php endfor; ?>
      </div>
      <p class="text-on-surface mb-8 italic">"The AI appraisal gave us the data we needed to negotiate a fair price for our rebrand. Simply invaluable."</p>
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-sm font-black text-on-surface-variant">MV</div>
        <div>
          <p class="font-bold text-sm">Marcus V.</p>
          <p class="text-xs text-outline">CTO at TechFlow</p>
        </div>
      </div>
    </div>
    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-outline-variant/10">
      <div class="flex gap-1 text-primary mb-6">
        <?php for ($i = 0; $i < 5; $i++): ?><span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">star</span><?php endfor; ?>
      </div>
      <p class="text-on-surface mb-8 italic">"Finding meaningful alternatives was a breeze. WHOIS Intelligence is my first stop for every new project."</p>
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-sm font-black text-on-surface-variant">SL</div>
        <div>
          <p class="font-bold text-sm">Sarah L.</p>
          <p class="text-xs text-outline">Founder of Bloom Studio</p>
        </div>
      </div>
    </div>
    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-outline-variant/10">
      <div class="flex gap-1 text-primary mb-6">
        <?php for ($i = 0; $i < 5; $i++): ?><span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">star</span><?php endfor; ?>
      </div>
      <p class="text-on-surface mb-8 italic">"Clean, professional, and accurate. Exactly what you want when dealing with high-stakes digital assets."</p>
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-sm font-black text-on-surface-variant">DK</div>
        <div>
          <p class="font-bold text-sm">David K.</p>
          <p class="text-xs text-outline">Domain Investor</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ CTA ══════════════════════════════════════════════════════════════════ -->
<section class="py-24 px-8 max-w-7xl mx-auto">
  <div class="bg-gradient-to-br from-black via-neutral-900 to-black rounded-[2.5rem] p-12 md:p-20 text-center relative overflow-hidden">
    <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-white/5 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
    <div class="relative z-10">
      <h2 class="font-headline text-5xl md:text-6xl font-bold text-white mb-8 tracking-tighter">Start Your Domain<br/>Journey Today</h2>
      <p class="text-neutral-400 text-xl max-w-xl mx-auto mb-12 leading-relaxed">Join builders, investors, and creators who found their perfect digital identity with WHOIS Intelligence.</p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a class="inline-flex items-center justify-center bg-white text-black px-10 py-5 rounded-full font-black text-lg hover:scale-105 transition-transform" href="whois_ai_domain_search.php">Search Now</a>
        <a class="inline-flex items-center justify-center border-2 border-white/20 text-white px-10 py-5 rounded-full font-black text-lg hover:bg-white/5 transition-colors" href="whois_domain_tools_overview.php">Explore Tools</a>
      </div>
    </div>
  </div>
</section>

</main>
<?php require __DIR__ . '/_footer.php'; ?>
<script src="<?=$assetBase?>/assets/js/nav-state.js"></script>
</body>
</html>


