<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | AI-Powered Domain Branding</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;400;500;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "#454747",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "on-primary-fixed": "#ffffff",
                        "background": "#f9f9f9",
                        "tertiary-fixed": "#5d5f5f",
                        "surface-bright": "#f9f9f9",
                        "primary-fixed": "#5e5e5e",
                        "on-secondary-container": "#1b1c1c",
                        "tertiary-container": "#737575",
                        "outline": "#777777",
                        "surface-variant": "#e2e2e2",
                        "on-tertiary": "#e2e2e2",
                        "surface-container-lowest": "#ffffff",
                        "primary-container": "#3b3b3b",
                        "secondary-fixed": "#c7c6c6",
                        "on-error-container": "#410002",
                        "on-primary-container": "#ffffff",
                        "inverse-on-surface": "#f1f1f1",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-background": "#1a1c1c",
                        "inverse-surface": "#2f3131",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "error": "#ba1a1a",
                        "surface-tint": "#5e5e5e",
                        "surface-container-low": "#f3f3f3",
                        "on-surface": "#1a1c1c",
                        "on-primary": "#e2e2e2",
                        "inverse-primary": "#c6c6c6",
                        "primary": "#000000",
                        "on-secondary-fixed": "#1b1c1c",
                        "on-surface-variant": "#474747",
                        "tertiary": "#3a3c3c",
                        "surface-container-high": "#e8e8e8",
                        "surface-dim": "#dadada",
                        "secondary": "#5e5e5e",
                        "on-error": "#ffffff",
                        "secondary-container": "#d5d4d4",
                        "on-tertiary-container": "#ffffff",
                        "outline-variant": "#c6c6c6",
                        "on-tertiary-fixed": "#ffffff",
                        "on-secondary": "#ffffff",
                        "primary-fixed-dim": "#474747",
                        "surface-container": "#eeeeee",
                        "secondary-fixed-dim": "#acabab",
                        "surface-container-highest": "#e2e2e2",
                        "surface": "#f9f9f9",
                        "error-container": "#ffdad6"
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
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .tonal-shift-bottom {
            background: linear-gradient(to bottom, rgba(249, 249, 249, 0.8), rgba(249, 249, 249, 0));
        }
        .glass-search {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="bg-background font-body text-on-background antialiased selection:bg-primary selection:text-on-primary">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-24">
<!-- Hero Section -->
<section class="relative overflow-hidden pt-20 pb-32 px-6">
<!-- Background Decoration -->
<div class="absolute inset-0 -z-10 bg-[radial-gradient(#e5e5e5_1px,transparent_1px)] [background-size:32px_32px] opacity-40"></div>
<div class="max-w-4xl mx-auto text-center relative">
<!-- Floating Suggestion Chips (Background Depth) -->
<div class="absolute -top-10 -left-20 opacity-10 blur-sm pointer-events-none select-none hidden lg:block">
<span class="bg-surface-container-highest px-4 py-2 rounded-full text-xs font-headline font-bold">LUMINA.AI</span>
</div>
<div class="absolute top-20 -right-20 opacity-10 blur-[1px] pointer-events-none select-none hidden lg:block">
<span class="bg-surface-container-highest px-4 py-2 rounded-full text-xs font-headline font-bold">VORTEX.IO</span>
</div>
<h1 class="font-headline font-extrabold text-5xl md:text-7xl tracking-tight text-primary mb-8">
                    Search. Discover.<br/>Own Your Brand.
                </h1>
<!-- Search Bar Hero -->
<div class="relative max-w-2xl mx-auto mb-12">
<div class="glass-search p-2 rounded-full shadow-[0_20px_50px_rgba(0,0,0,0.06)] flex items-center border border-outline-variant/40">
<div class="pl-6 pr-4">
<span class="material-symbols-outlined text-neutral-400">search</span>
</div>
<form id="ai-domain-search-form" class="flex-1 flex" action="#" method="get" autocomplete="off" onsubmit="return false;">
  <input id="ai-domain-search-input" name="query" class="w-full bg-transparent border-none focus:ring-0 text-lg font-body placeholder:text-neutral-400" placeholder="Find your next digital identity..." type="text" autocomplete="off" />
  <button id="ai-domain-search-btn" type="submit" class="bg-primary text-white rounded-full px-8 py-4 font-headline font-bold hover:bg-neutral-800 transition-colors">Search</button>
</form>
</div>
</div>
<!-- AI Pill Suggestions -->
<div id="ai-suggestions-bar" class="flex flex-wrap justify-center gap-3 items-center">
  <div class="flex items-center gap-2 mr-2">
    <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
    <span class="text-xs font-label uppercase tracking-widest font-medium text-neutral-500">AI Suggested</span>
  </div>
  <!-- AI suggestions will be injected here -->
</div>
<?php
// --- Dynamic AI Suggestions (PHP or mock for now) ---
$aiSuggestions = [
  'quantum.io',
  'ethereal.cc',
  'monolith.ai',
  'brandflow.app',
  'pulse.ai',
  'orbit.dev',
];
?>
</div>
</section>
<script src="../assets/js/ai-workflows.js"></script>
<script>
// --- Inject AI Suggestions dynamically ---
document.addEventListener('DOMContentLoaded', function () {
  const bar = document.getElementById('ai-suggestions-bar');
  if (bar) {
    const suggestions = <?php echo json_encode($aiSuggestions); ?>;
    suggestions.forEach(function(domain) {
      const btn = document.createElement('button');
      btn.className = 'ai-suggestion bg-surface-container-lowest border border-outline-variant/30 px-5 py-2 rounded-full text-sm font-medium hover:bg-surface-container-high transition-colors';
      btn.setAttribute('data-domain', domain);
      btn.textContent = domain;
      bar.appendChild(btn);
    });
  }
});
</script>
<script>
// --- AI Domain Search & Suggestions ---
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('ai-domain-search-form');
  const input = document.getElementById('ai-domain-search-input');
  const btn = document.getElementById('ai-domain-search-btn');
  const aiSuggestions = document.querySelectorAll('.ai-suggestion');
  const params = new URLSearchParams(window.location.search);

  function goToResults(query) {
    if (!query) return;
    window.location.href = '/pages/whois_comprehensive_search_results.php?query=' + encodeURIComponent(query);
  }

  if (form && input && btn) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const query = input.value.trim();
      if (!query) {
        input.focus();
        return;
      }
      goToResults(query);
    });
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const query = input.value.trim();
      if (!query) {
        input.focus();
        return;
      }
      goToResults(query);
    });
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        btn.click();
      }
    });
  }

  aiSuggestions.forEach(function (el) {
    el.addEventListener('click', function () {
      const domain = el.getAttribute('data-domain');
      if (domain) {
        input.value = domain;
        goToResults(domain);
      }
    });
  });

  // Autofill from query param
  if (params.get('query') && input) {
    input.value = params.get('query');
  }
});
</script>
<!-- Search Results & Alternatives Section -->
<section class="max-w-7xl mx-auto px-8 py-20 grid grid-cols-1 lg:grid-cols-12 gap-12">
<!-- Primary Search Result -->
<div class="lg:col-span-12">
<div class="bg-surface-container-lowest p-8 rounded-3xl shadow-[0_4px_30px_rgba(0,0,0,0.03)] border border-outline-variant/20 flex flex-col md:flex-row items-center justify-between gap-6">
<div class="flex items-center gap-6">
<div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center">
<span class="material-symbols-outlined text-white text-3xl">language</span>
</div>
<div>
<h2 class="font-headline font-extrabold text-3xl">whois.com</h2>
<p class="text-neutral-500">Global Digital Standard</p>
</div>
</div>
<div class="flex items-center gap-4">
<div class="flex items-center gap-2 bg-error-container text-on-error-container px-4 py-2 rounded-xl">
<span class="material-symbols-outlined text-sm">close</span>
<span class="font-headline font-bold text-sm">REGISTERED</span>
</div>
<button class="bg-surface-container-high px-6 py-3 rounded-xl font-headline font-bold text-sm hover:bg-surface-container-highest transition-colors">WHOIS Data</button>
</div>
</div>
</div>
<!-- Domain Alternatives Grid -->
<div class="lg:col-span-8 space-y-6">
<div class="flex items-center justify-between mb-8">
<h3 class="font-headline font-extrabold text-2xl">Available Extensions</h3>
<div class="h-px flex-grow mx-6 bg-outline-variant/30"></div>
</div>
<div id="available-extensions" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
<?php
// --- Extension candidates for dynamic lookup ---
$extensionCandidates = ['ai', 'app', 'io', 'co', 'dev'];
?>
<script>
// --- Dynamic Available Extensions Section ---
function renderExtensionCard(ext) {
  const statusIcon = ext.available ? 'check' : 'close';
  const statusBg = ext.available ? 'bg-green-100 text-green-700' : 'bg-error-container text-on-error-container';
  const price = ext.price ? ext.price : '--';
  const desc = ext.desc || '';
  return `
    <div class="bg-surface-container-low p-6 rounded-2xl border border-transparent hover:border-outline-variant/50 hover:bg-surface-container-lowest hover:shadow-xl transition-all duration-300 group">
      <div class="flex justify-between items-start mb-4">
        <span class="material-symbols-outlined text-neutral-400 group-hover:text-primary transition-colors">${ext.icon}</span>
        <div class="${statusBg} p-1 rounded-full">
          <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 1;">${statusIcon}</span>
        </div>
      </div>
      <h4 class="font-headline font-bold text-xl mb-1">${ext.domain}</h4>
      <p class="text-neutral-500 text-sm mb-6">${desc}</p>
      <div class="flex items-center justify-between">
        <span class="font-headline font-extrabold text-lg">${price}<span class="text-xs text-neutral-400 font-normal">/yr</span></span>
        <button class="bg-primary text-white p-3 rounded-xl hover:scale-105 active:scale-95 transition-all">
          <span class="material-symbols-outlined">add_shopping_cart</span>
        </button>
      </div>
    </div>
  `;
}

function fetchAndRenderExtensions(domainRoot) {
  const container = document.getElementById('available-extensions');
  if (!container) return;
  container.innerHTML = '';
  const tlds = <?php echo json_encode($extensionCandidates); ?>;
  // For demo: fetch from backend or use mock
  Promise.all(tlds.map(tld => {
    const domain = domainRoot + '.' + tld;
    return fetch(`/api/appraise.php?domain=${encodeURIComponent(domain)}`)
      .then(r => r.json())
      .then(data => ({
        domain,
        available: data.lookup && data.lookup.status === 'available',
        price: data.estimatedValue ? ('$' + data.estimatedValue) : '--',
        desc: tld === 'ai' ? 'Premium AI extension' : (tld === 'app' ? 'Perfect for startups' : ''),
        icon: tld === 'ai' ? 'public' : (tld === 'app' ? 'rocket_launch' : 'language'),
      }))
      .catch(() => ({
        domain,
        available: false,
        price: '--',
        desc: '',
        icon: 'language',
      }));
  })).then(results => {
    results.forEach(ext => {
      container.innerHTML += renderExtensionCard(ext);
    });
  });
}

// On search or page load with query, render extensions
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('ai-domain-search-input');
  const params = new URLSearchParams(window.location.search);
  let root = '';
  if (params.get('query')) {
    root = params.get('query').split('.')[0];
    fetchAndRenderExtensions(root);
  }
  if (input) {
    input.addEventListener('change', function () {
      const val = input.value.trim();
      if (val) {
        fetchAndRenderExtensions(val.split('.')[0]);
      }
    });
  }
});
</script>
</div>
<!-- Premium Domains Sidebar -->
<div class="lg:col-span-4">
<div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/40 shadow-[0_10px_40px_rgba(0,0,0,0.05)] relative overflow-hidden">
<div class="absolute top-0 right-0 p-4 opacity-5">
<span class="material-symbols-outlined text-9xl">workspace_premium</span>
</div>
<div class="flex items-center gap-2 mb-8">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">stars</span>
<h3 class="font-headline font-extrabold text-xl">Curated Assets</h3>
</div>
<div id="curated-assets-list" class="space-y-6 relative z-10"></div>
<button class="w-full mt-8 py-4 border-2 border-primary rounded-2xl font-headline font-extrabold text-sm hover:bg-primary hover:text-white transition-all" onclick="window.location.href='whois_premium_domain_marketplace.php'">
  View Marketplace
</button>
</div>
</div>
<?php
// --- Curated premium assets (mock or backend) ---
$curatedAssets = [
  [
    'domain' => 'W.CO',
    'label' => 'One Character',
    'price' => '14,500',
    'currency' => '$',
    'action' => 'BIDDING',
    'actionClass' => 'text-green-600',
  ],
  [
    'domain' => 'IDENTITY.ETH',
    'label' => 'Web3 Native',
    'price' => '2,400',
    'currency' => '$',
    'action' => 'BUY NOW',
    'actionClass' => 'text-primary',
  ],
  [
    'domain' => 'BRAND.AI',
    'label' => 'AI Brand',
    'price' => '7,900',
    'currency' => '$',
    'action' => 'BUY NOW',
    'actionClass' => 'text-primary',
  ],
];
?>
<script>
// --- Render curated premium assets dynamically ---
document.addEventListener('DOMContentLoaded', function () {
  const curated = <?php echo json_encode($curatedAssets); ?>;
  const list = document.getElementById('curated-assets-list');
  if (list) {
    curated.forEach(function(asset) {
      list.innerHTML += `
        <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-surface-container-low transition-colors cursor-pointer">
          <div>
            <div class="font-headline font-bold text-sm">${asset.domain}</div>
            <div class="text-[10px] uppercase tracking-widest text-neutral-400 mt-1">${asset.label}</div>
          </div>
          <div class="text-right">
            <div class="font-headline font-extrabold">${asset.currency}${asset.price}</div>
            <div class="text-[10px] ${asset.actionClass} font-bold">${asset.action}</div>
          </div>
        </div>
      `;
    });
  }
});
</script>
</section>
<!-- Brand Preview Section (Bento Inspired) -->
<section class="max-w-7xl mx-auto px-8 py-20">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
<div class="order-2 lg:order-1">
<div class="bg-surface p-12 rounded-[3rem] border border-outline-variant/30 shadow-2xl relative">
<!-- Mockup laptop screen -->
<div class="w-full aspect-video bg-surface-container-lowest rounded-xl border-4 border-neutral-900 overflow-hidden shadow-inner flex flex-col">
<div class="h-6 bg-neutral-900 flex items-center px-4 gap-1.5">
<div class="w-2 h-2 rounded-full bg-neutral-700"></div>
<div class="w-2 h-2 rounded-full bg-neutral-700"></div>
<div class="w-2 h-2 rounded-full bg-neutral-700"></div>
</div>
<div class="flex-grow flex items-center justify-center bg-[#F1F1F1] p-10">
<div class="text-center space-y-6">
<div class="font-headline text-5xl font-black tracking-tighter text-black opacity-20">WHOIS</div>
<div class="space-y-2 opacity-10">
<div class="h-2 w-48 bg-black rounded-full mx-auto"></div>
<div class="h-2 w-32 bg-black rounded-full mx-auto"></div>
</div>
</div>
</div>
</div>
<!-- Depth Card -->
<div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-3xl shadow-2xl border border-outline-variant/20 max-w-[200px] hidden md:block">
<div class="flex gap-2 mb-3">
<div class="w-6 h-6 rounded-md bg-black"></div>
<div class="w-6 h-6 rounded-md bg-neutral-400"></div>
<div class="w-6 h-6 rounded-md bg-neutral-100 border border-outline-variant"></div>
</div>
<div class="text-[10px] font-label font-bold text-neutral-400 uppercase tracking-widest">Brand Palette</div>
</div>
</div>
</div>
<div class="order-1 lg:order-2 space-y-8 pl-0 lg:pl-12">
<div class="inline-flex items-center gap-2 bg-surface-container-highest px-4 py-2 rounded-full">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
<span class="text-xs font-label uppercase tracking-widest font-bold">Brand Preview</span>
</div>
<h2 class="font-headline font-extrabold text-4xl md:text-5xl tracking-tight leading-tight">Visualize your brand before you buy.</h2>
<p class="text-neutral-500 text-lg leading-relaxed max-w-md">
                        Our AI generates instant mockups of your chosen domain on landing pages, mobile apps, and brand assets. See the vision, then claim it.
                    </p>
<div class="flex gap-4">
<a href="whois_brand_preview.php" class="bg-primary text-white px-8 py-4 rounded-full font-headline font-bold flex items-center justify-center">Try Brand AI</a>
</div>
</div>
</div>
</section>
<!-- Features Grid -->
<section class="bg-surface-container-low py-24 px-8">
<div class="max-w-7xl mx-auto">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
<div class="space-y-4">
<div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center border border-outline-variant/20">
<span class="material-symbols-outlined text-primary">psychology</span>
</div>
<h4 class="font-headline font-extrabold text-lg">AI Name Generator</h4>
<p class="text-sm text-neutral-500 leading-relaxed">Advanced LLM-powered suggestions that actually make sense for your business niche.</p>
</div>
<div class="space-y-4">
<div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center border border-outline-variant/20">
<span class="material-symbols-outlined text-primary">speed</span>
</div>
<h4 class="font-headline font-extrabold text-lg">Instant Check</h4>
<p class="text-sm text-neutral-500 leading-relaxed">Real-time availability status for 1500+ TLDs with sub-second latency.</p>
</div>
<div class="space-y-4">
<div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center border border-outline-variant/20">
<span class="material-symbols-outlined text-primary">hub</span>
</div>
<h4 class="font-headline font-extrabold text-lg">Premium Market</h4>
<p class="text-sm text-neutral-500 leading-relaxed">Exclusive access to high-value aftermarket domains and private brokerage services.</p>
</div>
<div class="space-y-4">
<div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center border border-outline-variant/20">
<span class="material-symbols-outlined text-primary">view_quilt</span>
</div>
<h4 class="font-headline font-extrabold text-lg">Brand Preview</h4>
<p class="text-sm text-neutral-500 leading-relaxed">See your chosen domain identity rendered on modern UI templates instantly.</p>
</div>
</div>
</div>
</section>
<!-- CTA Section -->
<section class="py-32 px-8">
<div class="max-w-7xl mx-auto bg-primary rounded-[3rem] p-16 md:p-24 text-center text-white relative overflow-hidden">
<!-- Tonal Pattern -->
<div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,#333,transparent)] opacity-40"></div>
<div class="relative z-10">
<h2 class="font-headline font-extrabold text-4xl md:text-6xl mb-10">Ready to secure your future?</h2>
<div class="flex flex-col md:flex-row justify-center gap-6 items-center">
<div class="w-full max-w-md bg-white p-2 rounded-full flex items-center shadow-xl">
<input class="flex-grow bg-transparent border-none text-black px-6 focus:ring-0 font-body" placeholder="Enter domain..." type="text"/>
<button class="bg-black text-white px-8 py-3 rounded-full font-headline font-bold flex items-center gap-2 hover:bg-neutral-800 transition-colors">
                                Claim <span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</div>
</div>
<p class="mt-8 text-neutral-400 text-sm font-label uppercase tracking-widest">No hidden fees. Instant transfer. 24/7 Support.</p>
</div>
</div>
</section>
</main>
<!-- Footer Shell -->
<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body></html>




