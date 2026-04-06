<?php
declare(strict_types=1);

$initialQuery = trim((string) ($_GET['query'] ?? ''));
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | AI-Powered Domain Search</title>
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
<section class="pt-40 pb-24 px-6 text-center max-w-5xl mx-auto">
<h1 class="text-6xl md:text-7xl font-extrabold text-primary tracking-tight mb-6">
            Search. Discover.<br/>Own Your Brand.
        </h1>
<p class="text-on-surface-variant text-xl mb-12 max-w-2xl mx-auto font-medium">
            Find and secure the perfect domain name in seconds with the world's most advanced AI engine.
        </p>
<!-- Search Bar -->
<div class="relative max-w-3xl mx-auto mb-6 group">
<div class="bg-surface-container-lowest border border-outline-variant p-2 rounded-full flex items-center shadow-sm focus-within:ring-1 focus-within:ring-black transition-all duration-300">
<span class="material-symbols-outlined ml-4 text-outline" data-icon="search">search</span>
<input id="ai-domain-search-input" data-ai-input="true" class="w-full bg-transparent border-none focus:ring-0 px-4 text-lg font-medium text-primary placeholder:text-neutral-400" placeholder="Search domain name (e.g. yourbrand.com)" type="text" value="<?php echo htmlspecialchars($initialQuery, ENT_QUOTES, 'UTF-8'); ?>"/>
<button data-ai-submit="true" class="bg-black text-white px-8 py-3 rounded-full font-bold transition-transform active:scale-95">Search</button>
</div>
</div>
<div class="flex justify-center gap-6 text-sm font-medium text-neutral-500">
<span>.com - <span class="text-primary">$11.30/year</span></span>
<span class="w-1.5 h-1.5 bg-outline-variant rounded-full mt-2"></span>
<span>.ai - <span class="text-primary">$78.88/year</span></span>
</div>
</section>
<script src="../assets/js/ai-workflows.js"></script>
<script>
  (function () {
    const input = document.getElementById('ai-domain-search-input');
    const params = new URLSearchParams(window.location.search);

    function goToResults() {
      const query = input ? input.value.trim() : '';

      if (!query) {
        if (input) {
          input.focus();
        }
        return;
      }

      if (window.WhoisAIHistory) {
        window.WhoisAIHistory.record({
          workflow: 'domain_search',
          title: query,
          prompt: query,
          message: 'Search submitted.',
          status: 'done',
        });
      }

      window.location.href = '/pages/whois_comprehensive_search_results.php?query=' + encodeURIComponent(query);
    }

    const button = document.querySelector('[data-ai-submit="true"]');

    if (button) {
      button.addEventListener('click', function (event) {
        event.preventDefault();
        goToResults();
      });
    }

    if (params.get('query') && input) {
      input.value = params.get('query');
    }

    if (params.get('query')) {
      goToResults();
    }
  })();
</script>
<!-- Search Results / Domain Status -->
<section class="py-12 px-6 max-w-7xl mx-auto">
<div class="bg-surface-container-low rounded-3xl p-12 flex flex-col md:flex-row items-center justify-between gap-8 border border-outline-variant/30">
<div>
<div class="flex items-center gap-4 mb-2">
<h2 class="text-3xl font-black text-primary">yourbrand.com</h2>
<span class="bg-neutral-800 text-white text-[10px] uppercase tracking-widest px-3 py-1 rounded-full font-bold">Already Registered</span>
</div>
<p class="text-on-surface-variant text-lg">Choose a strong alternative below recommended by WHOIS AI.</p>
</div>
<div class="flex gap-4">
<button class="bg-surface-container-lowest border border-outline-variant text-primary px-6 py-3 rounded-xl font-bold hover:bg-surface-container-high transition-colors">See WHOIS Data</button>
<button class="bg-primary text-on-primary px-6 py-3 rounded-xl font-bold active:scale-95 transition-transform">Back to Search</button>
</div>
</div>
</section>
<!-- Domain Alternatives Grid -->
<section class="py-20 px-6 max-w-7xl mx-auto">
<div class="flex items-end justify-between mb-10">
<div>
<h3 class="text-2xl font-black mb-2">Smart Alternatives</h3>
<p class="text-on-surface-variant font-medium">Available domains based on your brand vision.</p>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Best Option Card -->
<div class="bg-surface-container-lowest border-2 border-black p-8 rounded-3xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.06)] transform hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
<div class="absolute top-4 right-4">
<span class="bg-black text-white text-[10px] uppercase tracking-widest px-3 py-1 rounded-full font-bold">Available</span>
</div>
<h4 class="text-2xl font-bold text-primary mb-1">getyourbrand.com</h4>
<p class="text-neutral-500 mb-8 font-medium">$12.00/year</p>
<button class="w-full bg-black text-white py-4 rounded-xl font-black text-sm tracking-tight hover:bg-neutral-800 transition-colors">Get Domain</button>
</div>
<!-- Normal Card 1 -->
<div class="bg-surface-container-lowest border border-outline-variant/50 p-8 rounded-3xl hover:border-black/20 transition-all duration-300">
<div class="flex justify-between items-start mb-1">
<h4 class="text-2xl font-bold text-primary">yourbrand.ai</h4>
<span class="bg-black text-white text-[10px] uppercase tracking-widest px-3 py-1 rounded-full font-bold">Available</span>
</div>
<p class="text-neutral-500 mb-8 font-medium">$78.88/year</p>
<button class="w-full bg-surface-container-low text-primary py-4 rounded-xl font-black text-sm tracking-tight hover:bg-surface-container-high transition-colors">Get Domain</button>
</div>
<!-- Normal Card 2 -->
<div class="bg-surface-container-lowest border border-outline-variant/50 p-8 rounded-3xl hover:border-black/20 transition-all duration-300">
<div class="flex justify-between items-start mb-1">
<h4 class="text-2xl font-bold text-primary">yourbrand.app</h4>
<span class="bg-black text-white text-[10px] uppercase tracking-widest px-3 py-1 rounded-full font-bold">Available</span>
</div>
<p class="text-neutral-500 mb-8 font-medium">$24.50/year</p>
<button class="w-full bg-surface-container-low text-primary py-4 rounded-xl font-black text-sm tracking-tight hover:bg-surface-container-high transition-colors">Get Domain</button>
</div>
</div>
</section>
<!-- Premium Brand Names Section (Tonal Shift) -->
<section class="bg-surface-container-low py-24 px-6 border-y border-outline-variant/20">
<div class="max-w-7xl mx-auto">
<h2 class="text-3xl font-black mb-4">Premium Brand Names</h2>
<p class="text-on-surface-variant font-medium mb-12 max-w-xl">Curated dictionary-word domains for ventures that demand authority.</p>
<div class="grid grid-cols-2 md:grid-cols-4 gap-8">
<div class="group cursor-pointer">
<p class="text-sm font-bold text-neutral-400 mb-2 uppercase tracking-widest">Finance</p>
<h4 class="text-2xl font-extrabold text-primary mb-1 group-hover:underline underline-offset-8 transition-all">Yield.io</h4>
<p class="text-on-surface-variant font-medium">$4,500</p>
</div>
<div class="group cursor-pointer">
<p class="text-sm font-bold text-neutral-400 mb-2 uppercase tracking-widest">AI / ML</p>
<h4 class="text-2xl font-extrabold text-primary mb-1 group-hover:underline underline-offset-8 transition-all">Neural.co</h4>
<p class="text-on-surface-variant font-medium">$12,800</p>
</div>
<div class="group cursor-pointer">
<p class="text-sm font-bold text-neutral-400 mb-2 uppercase tracking-widest">SaaS</p>
<h4 class="text-2xl font-extrabold text-primary mb-1 group-hover:underline underline-offset-8 transition-all">Flow.ai</h4>
<p class="text-on-surface-variant font-medium">$8,200</p>
</div>
<div class="group cursor-pointer">
<p class="text-sm font-bold text-neutral-400 mb-2 uppercase tracking-widest">Growth</p>
<h4 class="text-2xl font-extrabold text-primary mb-1 group-hover:underline underline-offset-8 transition-all">Scale.io</h4>
<p class="text-on-surface-variant font-medium">$15,000</p>
</div>
</div>
</div>
</section>
<!-- AI Name Suggestions -->
<section class="py-24 px-6 max-w-5xl mx-auto text-center">
<span class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4 block">Engineered Insights</span>
<h2 class="text-4xl font-black mb-12">AI-Powered Name Ideas</h2>
<div class="flex flex-wrap justify-center gap-4">
<span class="px-6 py-3 bg-surface-container-high rounded-full font-bold text-primary hover:bg-neutral-300 cursor-pointer transition-colors">ElevateYourBrand</span>
<span class="px-6 py-3 bg-surface-container-high rounded-full font-bold text-primary hover:bg-neutral-300 cursor-pointer transition-colors">BrandFlow</span>
<span class="px-6 py-3 bg-surface-container-high rounded-full font-bold text-primary hover:bg-neutral-300 cursor-pointer transition-colors">VividStudio</span>
<span class="px-6 py-3 bg-surface-container-high rounded-full font-bold text-primary hover:bg-neutral-300 cursor-pointer transition-colors">NexusHQ</span>
<span class="px-6 py-3 bg-surface-container-high rounded-full font-bold text-primary hover:bg-neutral-300 cursor-pointer transition-colors">OrbitDigital</span>
<span class="px-6 py-3 bg-surface-container-high rounded-full font-bold text-primary hover:bg-neutral-300 cursor-pointer transition-colors">PulseCore</span>
<span class="px-6 py-3 bg-surface-container-high rounded-full font-bold text-primary hover:bg-neutral-300 cursor-pointer transition-colors">ZenithSystems</span>
<span class="px-6 py-3 bg-surface-container-high rounded-full font-bold text-primary hover:bg-neutral-300 cursor-pointer transition-colors">OmniLogic</span>
</div>
</section>
<!-- Brand Preview Section (Bento Style) -->
<section class="py-24 px-6 max-w-7xl mx-auto">
<div class="grid grid-cols-1 md:grid-cols-12 gap-8 h-[600px]">
<div class="md:col-span-8 bg-black rounded-[2rem] p-12 flex flex-col justify-center items-center text-center relative overflow-hidden group">
<div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
<div class="relative z-10">
<div class="text-6xl font-black text-white tracking-tighter mb-4">WHOIS</div>
<div class="h-px w-24 bg-white/20 mx-auto mb-6"></div>
<p class="text-white/60 text-xl font-medium tracking-wide uppercase italic">Define the Future.</p>
</div>
</div>
<div class="md:col-span-4 flex flex-col gap-8">
<div class="flex-1 bg-surface-container-highest rounded-[2rem] p-8 border border-outline-variant flex flex-col justify-between">
<p class="font-bold text-xs uppercase tracking-widest text-neutral-400">Palette</p>
<div class="flex gap-2">
<div class="h-16 flex-1 bg-black rounded-lg"></div>
<div class="h-16 flex-1 bg-neutral-500 rounded-lg"></div>
<div class="h-16 flex-1 bg-neutral-200 rounded-lg"></div>
<div class="h-16 flex-1 bg-white border border-outline-variant rounded-lg"></div>
</div>
</div>
<div class="flex-1 bg-white rounded-[2rem] p-8 border border-outline-variant flex flex-col justify-between">
<p class="font-bold text-xs uppercase tracking-widest text-neutral-400">Typography</p>
<div>
<p class="text-4xl font-black tracking-tighter">Aa</p>
<p class="text-sm font-medium text-neutral-500">Manrope Bold</p>
</div>
</div>
</div>
</div>
</section>
<!-- Final CTA Section -->
<section class="py-32 px-6 text-center">
<div class="max-w-4xl mx-auto bg-white rounded-[3rem] py-24 border border-outline-variant shadow-[0_100px_80px_-40px_rgba(0,0,0,0.03)]">
<h2 class="text-5xl font-extrabold text-primary mb-8 tracking-tight">Secure Your Brand Today</h2>
<button class="bg-black text-white px-12 py-5 rounded-full text-lg font-black tracking-tight active:scale-95 transition-transform">Search Domain</button>
</div>
</section>
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




