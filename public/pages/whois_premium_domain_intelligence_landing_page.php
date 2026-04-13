<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS Intelligence | Professional Domain Search</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary": "#3a3c3c",
                        "on-surface": "#1a1c1c",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f3f3",
                        "on-secondary-fixed": "#1b1c1c",
                        "on-primary-fixed": "#ffffff",
                        "on-background": "#1a1c1c",
                        "primary-container": "#3b3b3b",
                        "inverse-primary": "#c6c6c6",
                        "inverse-on-surface": "#f1f1f1",
                        "tertiary-fixed-dim": "#454747",
                        "error": "#ba1a1a",
                        "surface-tint": "#5e5e5e",
                        "surface-container-highest": "#e2e2e2",
                        "on-primary-container": "#ffffff",
                        "surface-container-high": "#e8e8e8",
                        "primary-fixed-dim": "#474747",
                        "tertiary-fixed": "#5d5f5f",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-tertiary": "#e2e2e2",
                        "on-secondary": "#ffffff",
                        "secondary-fixed-dim": "#acabab",
                        "secondary-container": "#d5d4d4",
                        "surface": "#f9f9f9",
                        "primary-fixed": "#5e5e5e",
                        "inverse-surface": "#2f3131",
                        "on-tertiary-fixed": "#ffffff",
                        "tertiary-container": "#737575",
                        "on-error-container": "#410002",
                        "error-container": "#ffdad6",
                        "surface-variant": "#e2e2e2",
                        "on-error": "#ffffff",
                        "background": "#f9f9f9",
                        "surface-dim": "#dadada",
                        "outline-variant": "#c6c6c6",
                        "on-secondary-container": "#1b1c1c",
                        "on-surface-variant": "#474747",
                        "surface-container": "#eeeeee",
                        "primary": "#000000",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-tertiary-container": "#ffffff",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "secondary": "#5e5e5e",
                        "outline": "#777777",
                        "on-primary": "#e2e2e2",
                        "surface-bright": "#f9f9f9",
                        "secondary-fixed": "#c7c6c6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "1rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .hero-gradient {
              background:
                radial-gradient(circle at top left, rgba(0, 0, 0, 0.04), transparent 30%),
                radial-gradient(circle at bottom right, rgba(0, 0, 0, 0.03), transparent 28%),
                linear-gradient(180deg, #ffffff 0%, #f9f9f9 100%);
        }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
            .hero-grid {
              display: grid;
              gap: 2rem;
              grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
              align-items: center;
            }
            .hero-panel {
              background: rgba(255, 255, 255, 0.9);
              border: 1px solid rgba(198, 198, 198, 0.55);
              box-shadow: 0 30px 80px rgba(0, 0, 0, 0.06);
            }
            .signal-card {
              background: linear-gradient(180deg, #ffffff 0%, #f3f3f3 100%);
              border: 1px solid rgba(198, 198, 198, 0.55);
            }
            .metric-pill {
              background: rgba(255, 255, 255, 0.7);
              border: 1px solid rgba(198, 198, 198, 0.5);
            }
            @media (max-width: 1024px) {
              .hero-grid {
                grid-template-columns: 1fr;
              }
            }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-24">
<!-- Hero Section -->
<section class="hero-gradient min-h-[840px] flex items-center px-6 relative overflow-hidden">
<div class="max-w-7xl w-full mx-auto z-10 hero-grid">
<div class="max-w-3xl">
<span class="inline-flex items-center gap-2 px-4 py-2 rounded-full metric-pill text-[10px] font-bold uppercase tracking-[0.2em] text-outline mb-6">
<span class="w-2 h-2 rounded-full bg-black"></span>
WHOIS Intelligence Suite
</span>
<h1 class="font-headline text-5xl md:text-7xl font-extrabold tracking-tighter text-primary mb-6 leading-tight">
          Discover what a domain tells you <br/> before you buy it.
        </h1>
<p class="text-on-surface-variant text-lg md:text-xl max-w-2xl mb-10">
          Instant WHOIS lookup, AI-powered domain insights, and premium recommendations in one search flow. Start with a name, then see ownership, availability, and acquisition paths immediately.
        </p>
<div class="mb-4 flex flex-wrap items-center gap-2">
<span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Mode</span>
<button id="hero-mode-search" class="rounded-full bg-black px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white" data-hero-mode="search" type="button">Search domain</button>
<button id="hero-mode-ai" class="rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-primary" data-hero-mode="ai" type="button">Generate name with AI</button>
</div>
<div class="relative max-w-2xl glass-panel p-1.5 rounded-full shadow-lg flex items-center border border-outline-variant/30 transition-all hover:shadow-primary/5">
<form action="/pages/whois_ai_domain_search.php" method="GET" class="w-full flex items-center">
  <input type="hidden" name="currency" value="USD">
  <span class="material-symbols-outlined pl-6 text-outline">search</span>
  <input 
    name="query" 
    type="text" 
    class="w-full bg-transparent border-none focus:ring-0 text-lg px-4 py-4 font-medium text-primary placeholder:text-neutral-400" 
    placeholder="enter-your-dream-domain.com" 
    required
  />
  <button type="submit" class="bg-primary text-on-primary px-8 py-4 rounded-full font-bold text-sm tracking-wide uppercase flex items-center gap-2 hover:bg-primary-container transition-all active:scale-95">
    Search Domain
  </button>
</form>
</div>
<div id="hero-quick-tlds" class="mt-5 flex flex-wrap items-center gap-3">
<span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Quick extensions</span>
<button class="tld-chip px-4 py-2 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".com" type="button">.com</button>
<button class="tld-chip px-4 py-2 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".ai" type="button">.ai</button>
<button class="tld-chip px-4 py-2 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".io" type="button">.io</button>
<button class="tld-chip px-4 py-2 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".co" type="button">.co</button>
<button class="tld-chip px-4 py-2 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".app" type="button">.app</button>
<button class="tld-chip px-4 py-2 rounded-full bg-white border border-outline-variant/30 text-sm font-semibold text-primary hover:bg-surface-container-high transition-colors" data-tld=".net" type="button">.net</button>
</div>
<div class="mt-8 flex flex-wrap gap-3 text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">
<span class="px-4 py-2 rounded-full bg-surface-container-low">WHOIS lookup</span>
<span class="px-4 py-2 rounded-full bg-surface-container-low">AI appraisal</span>
<span class="px-4 py-2 rounded-full bg-surface-container-low">Premium matches</span>
</div>
</div>
<div class="hero-panel rounded-[2rem] p-6 md:p-8 relative overflow-hidden">
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
<div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center border border-outline-variant/30 shrink-0">
<span class="material-symbols-outlined">language</span>
</div>
<div class="flex-1">
<div class="flex items-center justify-between gap-3 mb-1">
<h3 class="font-bold text-primary">Search ownership</h3>
<span class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">WHOIS</span>
</div>
<p class="text-sm text-on-surface-variant">See registrar, creation date, and status without leaving the search flow.</p>
</div>
</div>
</div>
<div class="signal-card rounded-2xl p-4">
<div class="flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center border border-outline-variant/30 shrink-0">
<span class="material-symbols-outlined">auto_awesome</span>
</div>
<div class="flex-1">
<div class="flex items-center justify-between gap-3 mb-1">
<h3 class="font-bold text-primary">Score the brand fit</h3>
<span class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">AI insight</span>
</div>
<p class="text-sm text-on-surface-variant">Estimate memorability, demand, and resale potential in a glance.</p>
</div>
</div>
</div>
<div class="signal-card rounded-2xl p-4">
<div class="flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center border border-outline-variant/30 shrink-0">
<span class="material-symbols-outlined">shopping_cart</span>
</div>
<div class="flex-1">
<div class="flex items-center justify-between gap-3 mb-1">
<h3 class="font-bold text-primary">Move to acquisition</h3>
<span class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">Marketplace</span>
</div>
<p class="text-sm text-on-surface-variant">Jump from discovery into premium listings, auctions, or brokerage.</p>
</div>
</div>
</div>
</div>
<div class="mt-6 grid grid-cols-2 gap-3">
<div class="metric-pill rounded-2xl p-4">
<p class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400 mb-2">Available now</p>
<p class="text-xl font-black tracking-tight text-primary">1,204</p>
</div>
<div class="metric-pill rounded-2xl p-4">
<p class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400 mb-2">Premium picks</p>
<p class="text-xl font-black tracking-tight text-primary">284</p>
</div>
</div>
</div>
</div>
</section>
<script>
  (function () {
    const input = document.getElementById('hero-domain-input');
    const searchButton = document.getElementById('hero-search-button');
    const chips = document.querySelectorAll('.tld-chip');
    const modeSearch = document.getElementById('hero-mode-search');
    const modeAi = document.getElementById('hero-mode-ai');
    const quickTlds = document.getElementById('hero-quick-tlds');
    let mode = 'search';

    if (!input || !chips.length) {
      return;
    }

    function setMode(nextMode) {
      mode = nextMode === 'ai' ? 'ai' : 'search';

      if (mode === 'ai') {
        modeAi.className = 'rounded-full bg-black px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white';
        modeSearch.className = 'rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-primary';
        input.placeholder = 'Describe your business in one sentence';
        searchButton.textContent = 'Generate Names';
        quickTlds.classList.add('hidden');
      } else {
        modeSearch.className = 'rounded-full bg-black px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white';
        modeAi.className = 'rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-primary';
        input.placeholder = 'enter-your-dream-domain.com';
        searchButton.textContent = 'Search Domain';
        quickTlds.classList.remove('hidden');
      }
    }

    function generateNamesWithAi(description) {
      if (!description.trim()) {
        input.focus();
        return;
      }

      window.location.href = '/pages/whois_ai_generated_domains.php?idea=' + encodeURIComponent(description.trim());
    }

    function applyTld(nextTld) {
      const currentValue = input.value.trim();

      if (!currentValue) {
        input.value = nextTld.replace(/^\./, '') === nextTld ? nextTld : nextTld;
        input.focus();
        return;
      }

      const baseName = currentValue.split('/').pop().split('?')[0].split('#')[0].replace(/\.[^.]+$/, '');
      input.value = baseName + nextTld;
      input.focus();
      input.setSelectionRange(input.value.length, input.value.length);
    }

    chips.forEach((chip) => {
      chip.addEventListener('click', () => {
        const nextTld = chip.getAttribute('data-tld') || '.com';
        applyTld(nextTld);
      });
    });

    if (searchButton) {
      searchButton.addEventListener('click', () => {
        const query = input.value.trim();

        if (!query) {
          input.focus();
          return;
        }

        if (mode === 'ai') {
          generateNamesWithAi(query);
          return;
        }

        window.location.href = '/pages/whois_comprehensive_search_results.php?query=' + encodeURIComponent(query);
      });
    }

    if (modeSearch && modeAi) {
      modeSearch.addEventListener('click', () => setMode('search'));
      modeAi.addEventListener('click', () => setMode('ai'));
    }

    input.addEventListener('keydown', (event) => {
      if (event.key !== 'Enter') {
        return;
      }

      event.preventDefault();
      searchButton.click();
    });

    setMode('search');
  })();
</script>
<!-- Key Features -->
<section class="py-24 px-8 max-w-7xl mx-auto">
<div class="text-center mb-16">
<span class="text-sm font-bold uppercase tracking-[0.2em] text-outline">Core Intelligence</span>
<h2 class="font-headline text-4xl font-bold mt-4">Advanced Infrastructure Tools</h2>
</div>
<div class="bento-grid">
<!-- Feature Card 1 -->
<div class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1">
<div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined">database</span>
</div>
<h3 class="font-headline text-xl font-bold mb-3">WHOIS Lookup</h3>
<p class="text-on-surface-variant leading-relaxed">Get instant, comprehensive domain ownership information and historical data registrations.</p>
</div>
<!-- Feature Card 2 -->
<div class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1">
<div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined">auto_awesome</span>
</div>
<h3 class="font-headline text-xl font-bold mb-3">AI Domain Appraisal</h3>
<p class="text-on-surface-variant leading-relaxed">Our neural networks predict precise market values and long-term liquidity potential for any domain name.</p>
</div>
<!-- Feature Card 3 -->
<div class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1">
<div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined">alt_route</span>
</div>
<h3 class="font-headline text-xl font-bold mb-3">Available Alternatives</h3>
<p class="text-on-surface-variant leading-relaxed">Receive creative, high-impact suggestions that align perfectly with your brand identity and SEO goals.</p>
</div>
<!-- Feature Card 4 -->
<div class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1">
<div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined">shopping_cart</span>
</div>
<h3 class="font-headline text-xl font-bold mb-3">Premium Marketplace</h3>
<p class="text-on-surface-variant leading-relaxed">Direct access to high-value assets for immediate acquisition via our secure, verified escrow process.</p>
</div>
</div>
</section>
<!-- How It Works -->
<section class="bg-surface-container-low py-24 px-8 overflow-hidden">
<div class="max-w-7xl mx-auto">
<div class="flex flex-col md:flex-row items-center justify-between gap-12">
<div class="md:w-1/3">
<h2 class="font-headline text-4xl font-bold mb-6">A Streamlined Acquisition Path</h2>
<p class="text-on-surface-variant text-lg leading-relaxed">Building your digital identity shouldn't be complex. We've distilled the process into three decisive phases.</p>
</div>
<div class="md:w-2/3 w-full grid grid-cols-1 md:grid-cols-3 gap-8 relative">
<!-- Step 1 -->
<div class="relative z-10 text-center flex flex-col items-center">
<div class="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center mb-6 border border-outline-variant/20">
<span class="material-symbols-outlined text-3xl">search</span>
</div>
<h4 class="font-bold text-lg mb-2">Search</h4>
<p class="text-sm text-on-surface-variant">Query our global databases instantly.</p>
</div>
<!-- Step 2 -->
<div class="relative z-10 text-center flex flex-col items-center">
<div class="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center mb-6 border border-outline-variant/20">
<span class="material-symbols-outlined text-3xl">analytics</span>
</div>
<h4 class="font-bold text-lg mb-2">Analyze</h4>
<p class="text-sm text-on-surface-variant">Review AI appraisals and history logs.</p>
</div>
<!-- Step 3 -->
<div class="relative z-10 text-center flex flex-col items-center">
<div class="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center mb-6 border border-outline-variant/20">
<span class="material-symbols-outlined text-3xl">rocket_launch</span>
</div>
<h4 class="font-bold text-lg mb-2">Decide</h4>
<p class="text-sm text-on-surface-variant">Finalize your brand with confidence.</p>
</div>
<!-- Connector line -->
<div class="hidden md:block absolute top-8 left-1/4 right-1/4 h-[2px] bg-outline-variant/30 -z-0"></div>
</div>
</div>
</div>
</section>
<!-- Domain Insights AI Preview -->
<section class="py-24 px-8 max-w-7xl mx-auto">
<div class="bg-primary p-1 md:p-12 rounded-[2rem] overflow-hidden relative">
<div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px]"></div>
<div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
<div class="lg:w-1/2 text-on-primary">
  <span class="inline-block px-4 py-1 bg-white/10 rounded-full text-xs font-bold tracking-widest uppercase mb-6">Live AI Simulation</span>
  <h2 class="font-headline text-4xl font-bold mb-6">Experience Real-Time Domain Intelligence</h2>
  <p class="text-on-primary/70 text-lg leading-relaxed mb-8">Our proprietary "Pulse" AI provides dynamic scoring based on brandability, syllable count, and existing market trends.</p>
  <form id="ai-appraisal-form" class="flex items-center gap-2 mb-8" onsubmit="return false;">
    <input id="ai-appraisal-domain" class="rounded-l-full px-6 py-4 w-full text-lg font-bold border-none focus:ring-2 focus:ring-black" type="text" placeholder="Enter your domain (e.g. example.com)" autocomplete="off" />
    <button id="ai-appraisal-search" class="rounded-r-full px-8 py-4 bg-black text-white font-bold uppercase tracking-widest hover:bg-primary-container transition-all" type="submit">Appraise</button>
  </form>
  <div class="space-y-4">
    <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl">
      <span class="material-symbols-outlined" data-weight="fill">verified</span>
      <div>
        <h5 class="font-bold">Liquidity Prediction</h5>
        <p class="text-sm text-on-primary/60">Estimated resell speed based on TLD demand.</p>
      </div>
    </div>
    <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl">
      <span class="material-symbols-outlined" data-weight="fill">token</span>
      <div>
        <h5 class="font-bold">Brand Affinity</h5>
        <p class="text-sm text-on-primary/60">Phonetic balance and memorability score.</p>
      </div>
    </div>
  </div>
</div>
<div class="lg:w-1/2 w-full">
  <div id="ai-appraisal-card" class="bg-white rounded-3xl p-8 shadow-2xl min-h-[420px] flex flex-col justify-between">
    <!-- Skeleton loader -->
    <div id="ai-appraisal-skeleton" class="hidden animate-pulse space-y-6">
      <div class="h-6 bg-surface-container-high rounded w-1/3"></div>
      <div class="h-10 bg-surface-container-high rounded w-2/3"></div>
      <div class="h-8 bg-surface-container-high rounded w-1/2"></div>
      <div class="grid grid-cols-2 gap-6 mb-8 mt-6">
        <div class="h-24 bg-surface-container-high rounded-2xl"></div>
        <div class="h-24 bg-surface-container-high rounded-2xl"></div>
      </div>
      <div class="h-6 bg-surface-container-high rounded w-1/4"></div>
      <div class="flex gap-2 mt-2">
        <div class="h-8 w-24 bg-surface-container-high rounded-xl"></div>
        <div class="h-8 w-24 bg-surface-container-high rounded-xl"></div>
        <div class="h-8 w-32 bg-surface-container-high rounded-xl"></div>
      </div>
    </div>
    <!-- Results -->
    <div id="ai-appraisal-result">
      <div class="flex justify-between items-start mb-8">
        <div>
          <span class="bg-surface-container-high px-3 py-1 rounded text-[10px] font-black uppercase tracking-tighter mb-2 inline-block" id="ai-appraisal-status">Analysis Complete</span>
          <h3 class="text-3xl font-black tracking-tight" id="ai-appraisal-domain-label">example.com</h3>
        </div>
        <div class="bg-green-50 text-green-700 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-1" id="ai-appraisal-availability">
          <span class="w-2 h-2 bg-green-500 rounded-full"></span> Available
        </div>
      </div>
      <div class="grid grid-cols-2 gap-6 mb-8">
        <div class="bg-surface p-4 rounded-2xl border border-outline-variant/20 flex flex-col items-center">
          <div class="relative w-24 h-24 flex items-center justify-center">
            <svg class="w-full h-full -rotate-90">
              <circle cx="48" cy="48" fill="transparent" r="40" stroke="#f3f3f3" stroke-width="8"></circle>
              <circle id="ai-appraisal-score-bar" cx="48" cy="48" fill="transparent" r="40" stroke="#000000" stroke-dasharray="251.2" stroke-dashoffset="62.8" stroke-width="8"></circle>
            </svg>
            <span class="absolute text-2xl font-black" id="ai-appraisal-score">7.5</span>
          </div>
          <p class="mt-4 font-bold text-xs uppercase tracking-widest text-outline">Domain Score</p>
        </div>
        <div class="bg-surface p-4 rounded-2xl border border-outline-variant/20 flex flex-col items-center justify-center">
          <span class="text-sm text-outline font-medium mb-1">Estimated Value</span>
          <p class="text-3xl font-black" id="ai-appraisal-value">$2,500</p>
          <span class="text-[10px] font-bold text-outline mt-1 uppercase" id="ai-appraisal-currency">USD / Annual</span>
        </div>
      </div>
      <div class="space-y-3">
        <p class="text-xs font-bold text-outline uppercase tracking-widest mb-4">Smart Alternatives</p>
        <div class="flex flex-wrap gap-2" id="ai-appraisal-alternatives">
          <button class="px-4 py-2 bg-surface rounded-xl text-sm font-medium border border-outline-variant/30 hover:border-primary transition-colors">example.ai</button>
          <button class="px-4 py-2 bg-surface rounded-xl text-sm font-medium border border-outline-variant/30 hover:border-primary transition-colors">example.io</button>
          <button class="px-4 py-2 bg-surface rounded-xl text-sm font-medium border border-outline-variant/30 hover:border-primary transition-colors">getexample.com</button>
          <button class="px-4 py-2 bg-surface rounded-xl text-sm font-medium border border-outline-variant/30 hover:border-primary transition-colors">example.tech</button>
        </div>
      </div>
      <div class="mt-8">
        <a id="ai-appraisal-details-btn" href="#" target="_blank" class="inline-block px-6 py-3 rounded-full bg-black text-white font-bold uppercase tracking-widest hover:bg-primary-container transition-all">View Full Appraisal</a>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</section>
<script>
// --- AI Appraisal AJAX logic ---
const form = document.getElementById('ai-appraisal-form');
const domainInput = document.getElementById('ai-appraisal-domain');
const card = document.getElementById('ai-appraisal-card');
const skeleton = document.getElementById('ai-appraisal-skeleton');
const result = document.getElementById('ai-appraisal-result');
const domainLabel = document.getElementById('ai-appraisal-domain-label');
const valueLabel = document.getElementById('ai-appraisal-value');
const scoreLabel = document.getElementById('ai-appraisal-score');
const scoreBar = document.getElementById('ai-appraisal-score-bar');
const statusLabel = document.getElementById('ai-appraisal-status');
const availabilityLabel = document.getElementById('ai-appraisal-availability');
const alternativesDiv = document.getElementById('ai-appraisal-alternatives');
const detailsBtn = document.getElementById('ai-appraisal-details-btn');
const currencyLabel = document.getElementById('ai-appraisal-currency');

function setSkeleton(loading) {
  skeleton.classList.toggle('hidden', !loading);
  result.classList.toggle('hidden', loading);
}

function updateAppraisalCard(data) {
  domainLabel.textContent = data.domain;
  valueLabel.textContent = data.estimatedValue;
  scoreLabel.textContent = data.score;
  currencyLabel.textContent = data.displayCurrency + ' / Annual';
  statusLabel.textContent = 'Analysis Complete';
  // Score bar
  const percent = Math.max(0, Math.min(1, (data.score / 10)));
  scoreBar.setAttribute('stroke-dashoffset', (251.2 * (1 - percent)).toFixed(1));
  // Availability
  if (data.lookup && data.lookup.status === 'available') {
    availabilityLabel.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full"></span> Available';
    availabilityLabel.className = 'bg-green-50 text-green-700 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-1';
  } else {
    availabilityLabel.innerHTML = '<span class="w-2 h-2 bg-yellow-400 rounded-full"></span> Registered';
    availabilityLabel.className = 'bg-yellow-50 text-yellow-700 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-1';
  }
  // Alternatives
  alternativesDiv.innerHTML = '';
  if (data.root) {
    const tlds = ['.ai', '.io', '.com', '.tech'];
    tlds.forEach(tld => {
      if (data.domain.endsWith(tld)) return;
      const alt = data.root + tld;
      const btn = document.createElement('button');
      btn.className = 'px-4 py-2 bg-surface rounded-xl text-sm font-medium border border-outline-variant/30 hover:border-primary transition-colors';
      btn.textContent = alt;
      alternativesDiv.appendChild(btn);
    });
    // Add get{root}.com
    if (!data.domain.startsWith('get') && !data.domain.endsWith('.com')) {
      const btn = document.createElement('button');
      btn.className = 'px-4 py-2 bg-surface rounded-xl text-sm font-medium border border-outline-variant/30 hover:border-primary transition-colors';
      btn.textContent = 'get' + data.root + '.com';
      alternativesDiv.appendChild(btn);
    }
  }
  // Details button
  detailsBtn.href = '/pages/whois_domain_appraisal_tool.php?domain=' + encodeURIComponent(data.domain);
}

form.addEventListener('submit', function () {
  const domain = domainInput.value.trim();
  if (!domain) {
    domainInput.focus();
    return;
  }
  setSkeleton(true);
  fetch('/api/appraise.php?domain=' + encodeURIComponent(domain))
    .then(r => r.json())
    .then(data => {
      setSkeleton(false);
      updateAppraisalCard(data);
    })
    .catch(() => {
      setSkeleton(false);
      domainLabel.textContent = 'Error';
      valueLabel.textContent = '--';
      scoreLabel.textContent = '--';
      alternativesDiv.innerHTML = '';
      detailsBtn.href = '#';
    });
});
</script>
<!-- Connected Suite -->
<section class="py-24 px-6 max-w-7xl mx-auto">
  <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
    <div>
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4 block">Connected Suite</span>
      <h2 class="font-black text-4xl md:text-5xl tracking-tight text-primary">Your Domain Hub</h2>
    </div>
    <p class="text-on-surface-variant max-w-xl text-lg">Everything you need for domains is here—explore, create, buy, sell, and learn, all in one place.</p>
  </div>
  <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:bg-surface-container-low" href="whois_ai_domain_search.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Search</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Find & Discover Domains</h3>
      <p class="mt-3 text-on-surface-variant">Instantly search for available domains, including AI-powered suggestions. Find premium names or run a professional lookup—start your domain journey here.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:bg-surface-container-low" href="whois_ai_brand_assistant.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">AI Assistants</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Get Naming Help</h3>
      <p class="mt-3 text-on-surface-variant">Get help naming your brand or project. Use smart generators for business ideas, domain names, and more.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:bg-surface-container-low" href="whois_premium_domain_marketplace.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Marketplace</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Buy & Sell Domains</h3>
      <p class="mt-3 text-on-surface-variant">Browse top-quality premium domains and live auctions. Submit your own domains for sale or make offers on others.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:bg-surface-container-low" href="whois_domain_tools_overview.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Tools</span>
      <h3 class="mt-3 text-2xl font-black text-primary">All Domain Tools</h3>
      <p class="mt-3 text-on-surface-variant">Access all domain utilities: appraisals, lookups, and research tools—fast and easy.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:bg-surface-container-low" href="whois_industry_insights_blog.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Insights</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Learn & Stay Updated</h3>
      <p class="mt-3 text-on-surface-variant">Stay updated with expert articles, trends, and market news. Get actionable advice for your domain strategy.</p>
    </a>
    <a class="group rounded-3xl bg-surface-container-lowest p-8 shadow-sm transition-all hover:-translate-y-1 hover:bg-surface-container-low" href="whois_partner_with_us.php">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Partner</span>
      <h3 class="mt-3 text-2xl font-black text-primary">Partner With Us</h3>
      <p class="mt-3 text-on-surface-variant">Reach out for business partnerships or collaborations. Find out how we can work together to grow your domain business.</p>
    </a>
  </div>
</section>
<!-- Testimonials -->
<section class="py-24 px-8 max-w-7xl mx-auto">
<div class="text-center mb-16">
<h2 class="font-headline text-4xl font-bold mb-4">Trusted by Industry Leaders</h2>
<p class="text-on-surface-variant">Providing clarity for over 50,000 domain transactions annually.</p>
</div>
<div class="grid md:grid-cols-3 gap-8">
<!-- Testimonial 1 -->
<div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-outline-variant/10">
<div class="flex gap-1 text-primary mb-6">
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-lg italic text-on-surface mb-8">"The AI appraisal tool gave us the data we needed to negotiate a fair price for our rebrand. Simply invaluable."</p>
<div class="flex items-center gap-4">
<img alt="Marcus V." class="w-12 h-12 rounded-full object-cover" data-alt="Portrait of a professional man in a dark grey suit, clean studio lighting, high-end corporate style grayscale image" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDiZWjb5D6pKQ2iplDF4Ooaqd4NMWry327GesZx-LgqfkgGJsU6VsCdnm4g3MM800og2iRebI4Dj-QmTcX9IJjsJOACUq-9RMf37YOy9FWQzO0hv6E6818fr83gFCQXLAo53B1tRNAV5Ws_LpPm281k8-MfrpKS8IkMQJEXnwU7KpAqQbaVDaimXeh0KDfH0c1lxLMDZe8WTyMCxkuKO0tP-pIXtiU4cyfFKNaOSf9v2AnZFMLCovQPHcCglONYjgB1e7ir_zReYRnr"/>
<div>
<p class="font-bold">Marcus V.</p>
<p class="text-xs text-outline">CTO at TechFlow</p>
</div>
</div>
</div>
<!-- Testimonial 2 -->
<div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-outline-variant/10">
<div class="flex gap-1 text-primary mb-6">
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-lg italic text-on-surface mb-8">"Finding available alternatives that actually made sense was a breeze. WHOIS Intelligence is my first stop now."</p>
<div class="flex items-center gap-4">
<img alt="Sarah L." class="w-12 h-12 rounded-full object-cover" data-alt="Portrait of a young creative professional woman, minimalist attire, soft natural lighting, high-end editorial monochromatic style" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBRPPcDGZDCT9oWCwQqyr-H92oDtV8N2-2m4wVbMpRY3QqJ1rc1Lo1YRSLd54PJlerX74H2MrKCw4qzndgfievGdBHkw-bn4MiKzQszre3vyUz495fDdZL-XVU95PaxeerrwKCot0VyD0e8OSCfmjS5yjFpE8qdF5JyiLR9PH9ZzZcuX3uXd6SXVIY8O1oUeckBjgd7-lIz3uOvOLDKygMxAVRYlpCv7KUXOwb-Y67VCypeUnYAb2KHaHLldz38MGTXDyV0QBJ6ulws"/>
<div>
<p class="font-bold">Sarah L.</p>
<p class="text-xs text-outline">Founder of Bloom Studio</p>
</div>
</div>
</div>
<!-- Testimonial 3 -->
<div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-outline-variant/10">
<div class="flex gap-1 text-primary mb-6">
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-lg italic text-on-surface mb-8">"Clean, professional, and accurate. Exactly what you want when dealing with high-stakes digital assets."</p>
<div class="flex items-center gap-4">
<img alt="David K." class="w-12 h-12 rounded-full object-cover" data-alt="Portrait of a mature businessman, thoughtful expression, sharp monochromatic studio lighting, professional corporate aesthetic" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6hJLZbRJIKPs3Sh99n-Vp7ZyF-ngw78F4dPIahu5AbZlc7318gqXGbZkiPNNqAUjF1PMrvCQXFViiDaqHi4utsuMervfqiweJ2DL4Pf1cLBLiIB2PGXnDMwv19rciv0cb_GjjlCmbnnCb94fFhHwqSXfCp9ZAsbtKWYPSbqNPZICI86PUk_o8EtB9OgOVvI9w2q1dJyurrB6gWIIBkPDByppUO153VgKgnk30k7qr1LVvYj_x0dEQRy59I7VK3NhNtk-fGSRt3G0D"/>
<div>
<p class="font-bold">David K.</p>
<p class="text-xs text-outline">Investor</p>
</div>
</div>
</div>
</div>
</section>
<!-- CTA Section -->
<section class="py-24 px-8 max-w-7xl mx-auto">
<div class="bg-gradient-to-br from-black via-neutral-900 to-black rounded-[2.5rem] p-12 md:p-24 text-center relative overflow-hidden">
<!-- Abstract floating shapes -->
<div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
<div class="absolute bottom-0 right-0 w-80 h-80 bg-white/5 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
<div class="relative z-10">
<h2 class="font-headline text-5xl md:text-6xl font-bold text-white mb-8 tracking-tighter">Start Your Domain <br/> Journey Today</h2>
<p class="text-neutral-400 text-xl max-w-xl mx-auto mb-12 leading-relaxed">Join thousands of entrepreneurs who found their perfect digital home using our advanced WHOIS suite.</p>
<div class="flex flex-col sm:flex-row gap-6 justify-center">
<a class="inline-flex items-center justify-center bg-white text-black px-12 py-5 rounded-full font-black text-lg hover:scale-105 transition-transform" href="whois_ai_domain_search.php">Search Now</a>
<a class="inline-flex items-center justify-center border-2 border-white/20 text-white px-12 py-5 rounded-full font-black text-lg hover:bg-white/5 transition-colors" href="whois_domain_tools_overview.php">Learn More</a>
</div>
</div>
</div>
</section>
</main>
<!-- Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body></html>




