<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Tools | WHOIS Premium Domain Services</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;400;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "background": "#f9f9f9",
                        "surface-dim": "#dadada",
                        "surface-variant": "#e2e2e2",
                        "on-error": "#ffffff",
                        "outline-variant": "#c6c6c6",
                        "on-secondary-container": "#1b1c1c",
                        "on-surface-variant": "#474747",
                        "surface": "#f9f9f9",
                        "primary-fixed": "#5e5e5e",
                        "inverse-surface": "#2f3131",
                        "error-container": "#ffdad6",
                        "on-tertiary-fixed": "#ffffff",
                        "tertiary-container": "#737575",
                        "on-error-container": "#410002",
                        "surface-bright": "#f9f9f9",
                        "secondary-fixed": "#c7c6c6",
                        "on-tertiary-container": "#ffffff",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "secondary": "#5e5e5e",
                        "surface-container": "#eeeeee",
                        "primary": "#000000",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-primary": "#e2e2e2",
                        "outline": "#777777",
                        "inverse-primary": "#c6c6c6",
                        "inverse-on-surface": "#f1f1f1",
                        "tertiary-fixed-dim": "#454747",
                        "error": "#ba1a1a",
                        "primary-container": "#3b3b3b",
                        "surface-tint": "#5e5e5e",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f3f3",
                        "on-secondary-fixed": "#1b1c1c",
                        "tertiary": "#3a3c3c",
                        "on-surface": "#1a1c1c",
                        "on-primary-fixed": "#ffffff",
                        "on-background": "#1a1c1c",
                        "secondary-fixed-dim": "#acabab",
                        "secondary-container": "#d5d4d4",
                        "surface-container-high": "#e8e8e8",
                        "primary-fixed-dim": "#474747",
                        "surface-container-highest": "#e2e2e2",
                        "on-primary-container": "#ffffff",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed": "#5d5f5f",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-tertiary": "#e2e2e2"
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
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-surface text-on-surface">
<!-- TopNavBar -->
<header class="fixed top-0 w-full z-50 bg-white/80 dark:bg-black/80 backdrop-blur-md border-b border-neutral-100 dark:border-neutral-900 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
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
</header>
<main class="pt-32 pb-24">
<!-- Hero Section -->
<section class="max-w-7xl mx-auto px-6 mb-24 text-center md:text-left">
<div class="inline-block px-4 py-1.5 mb-6 rounded-full bg-surface-container-high text-on-surface-variant font-label text-xs tracking-[0.1em] font-semibold uppercase">
                Premium Ecosystem
            </div>
<h1 class="text-5xl md:text-7xl font-extrabold tracking-tighter text-primary mb-6 leading-[1.1]">
                Professional <br/>Domain Tools
            </h1>
<p class="text-xl text-on-surface-variant max-w-2xl leading-relaxed">
                Advanced diagnostic and discovery utilities designed for domain investors, architects, and technical professionals.
            </p>
</section>
<!-- Tools Grid -->
<section class="max-w-7xl mx-auto px-6 mb-32">
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Tool Card 1 -->
<div class="group bg-surface-container-lowest p-10 rounded-xl border border-outline-variant/40 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] transition-all duration-500 flex flex-col h-full">
<div class="mb-12">
<span class="material-symbols-outlined text-4xl text-primary" data-icon="auto_awesome" data-weight="fill" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
</div>
<h3 class="text-2xl font-bold mb-4 tracking-tight">Domain Name Generator</h3>
<p class="text-on-surface-variant mb-12 leading-relaxed">Get hundreds of smart domain ideas in seconds using our semantic AI engine.</p>
<div class="mt-auto">
<button class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-primary-container active:scale-95 transition-all">
                            Launch Tool
                            <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
<!-- Tool Card 2 -->
<div class="group bg-surface-container-lowest p-10 rounded-xl border border-outline-variant/40 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] transition-all duration-500 flex flex-col h-full">
<div class="mb-12">
<span class="material-symbols-outlined text-4xl text-primary" data-icon="monitoring">monitoring</span>
</div>
<h3 class="text-2xl font-bold mb-4 tracking-tight">Domain Appraisal</h3>
<p class="text-on-surface-variant mb-12 leading-relaxed">Instantly check your domain's market value based on historical sales and SEO potential.</p>
<div class="mt-auto">
<button class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-primary-container active:scale-95 transition-all">
                            Launch Tool
                            <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
<!-- Tool Card 3 -->
<div class="group bg-surface-container-lowest p-10 rounded-xl border border-outline-variant/40 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] transition-all duration-500 flex flex-col h-full">
<div class="mb-12">
<span class="material-symbols-outlined text-4xl text-primary" data-icon="person_search">person_search</span>
</div>
<h3 class="text-2xl font-bold mb-4 tracking-tight">WHOIS Lookup</h3>
<p class="text-on-surface-variant mb-12 leading-relaxed">Find domain ownership and registration details with our deep-scanning registry database.</p>
<div class="mt-auto">
<button class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-primary-container active:scale-95 transition-all">
                            Launch Tool
                            <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
</div>
</section>
<!-- Secondary Section: Why use WHOIS -->
<section class="bg-surface-container-low py-24 border-y border-outline-variant/20">
<div class="max-w-7xl mx-auto px-6">
<div class="flex flex-col md:flex-row items-center justify-between gap-16">
<div class="max-w-md">
<h2 class="text-3xl font-bold mb-6 tracking-tight">Why use WHOIS tools?</h2>
<p class="text-on-surface-variant leading-relaxed">We combine authoritative registry data with proprietary valuation models to give you the industry's most accurate insights.</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-8 flex-1">
<div class="text-center md:text-left">
<span class="material-symbols-outlined text-3xl mb-4 block" data-icon="verified">verified</span>
<h4 class="font-bold mb-2">ICANN Accredited</h4>
<p class="text-sm text-on-surface-variant">Direct access to primary domain registrars.</p>
</div>
<div class="text-center md:text-left">
<span class="material-symbols-outlined text-3xl mb-4 block" data-icon="psychology">psychology</span>
<h4 class="font-bold mb-2">AI-Powered</h4>
<p class="text-sm text-on-surface-variant">Semantic discovery engines for naming.</p>
</div>
<div class="text-center md:text-left">
<span class="material-symbols-outlined text-3xl mb-4 block" data-icon="update">update</span>
<h4 class="font-bold mb-2">Real-time Data</h4>
<p class="text-sm text-on-surface-variant">Live updates from 1,200+ TLD registries.</p>
</div>
</div>
</div>
</div>
</section>
<!-- CTA Section -->
<section class="max-w-7xl mx-auto px-6 py-32 text-center">
<h2 class="text-4xl font-extrabold mb-12 tracking-tight">Ready to find your domain?</h2>
<div class="relative max-w-2xl mx-auto group">
<div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-neutral-400 group-focus-within:text-primary transition-colors" data-icon="search">search</span>
</div>
<input class="w-full bg-surface-container-lowest border border-outline-variant text-lg px-16 py-6 rounded-full focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition-all shadow-sm" placeholder="Search for your next digital identity..." type="text"/>
<button class="absolute right-3 top-3 bottom-3 bg-primary text-on-primary px-8 rounded-full font-bold hover:bg-primary-container active:scale-95 transition-all">
                    Search
                </button>
</div>
</section>
</main>
<!-- Footer -->
<footer class="w-full border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950">
<div class="py-12 px-6 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6 font-['Inter'] text-sm">
<div class="flex flex-col items-center md:items-start gap-4">
<div class="text-lg font-bold text-black dark:text-white">WHOIS</div>
<p class="text-neutral-500 dark:text-neutral-400">&copy; 2024 WHOIS Premium Domain Services. All rights reserved.</p>
</div>
<div class="flex flex-wrap justify-center gap-8">
<a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Privacy Policy</a>
<a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Terms of Service</a>
<a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">API Documentation</a>
<a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Contact Support</a>
</div>
</div>
</footer>
<script src="../assets/js/nav-state.js"></script>
</body></html>




