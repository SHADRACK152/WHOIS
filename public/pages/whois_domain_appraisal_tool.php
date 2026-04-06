<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;family=Inter:wght@100..900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed": "#ffffff",
                        "inverse-primary": "#c6c6c6",
                        "primary-fixed": "#5e5e5e",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "surface-container-highest": "#e2e2e2",
                        "tertiary": "#3a3c3c",
                        "surface-variant": "#e2e2e2",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "primary-container": "#3b3b3b",
                        "on-tertiary-container": "#ffffff",
                        "on-background": "#1a1c1c",
                        "error": "#ba1a1a",
                        "surface-tint": "#5e5e5e",
                        "background": "#f9f9f9",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-primary-container": "#ffffff",
                        "inverse-on-surface": "#f1f1f1",
                        "on-primary": "#e2e2e2",
                        "surface-container-low": "#f3f3f3",
                        "surface-bright": "#f9f9f9",
                        "tertiary-fixed": "#5d5f5f",
                        "outline": "#777777",
                        "primary-fixed-dim": "#474747",
                        "tertiary-fixed-dim": "#454747",
                        "tertiary-container": "#737575",
                        "primary": "#000000",
                        "secondary-container": "#d5d4d4",
                        "on-surface-variant": "#474747",
                        "on-error": "#ffffff",
                        "on-secondary-fixed": "#1b1c1c",
                        "surface-container": "#eeeeee",
                        "on-tertiary": "#e2e2e2",
                        "surface": "#f9f9f9",
                        "on-tertiary-fixed": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#e8e8e8",
                        "error-container": "#ffdad6",
                        "inverse-surface": "#2f3131",
                        "outline-variant": "#c6c6c6",
                        "secondary": "#5e5e5e",
                        "on-secondary": "#ffffff",
                        "secondary-fixed": "#c7c6c6",
                        "on-error-container": "#410002",
                        "surface-dim": "#dadada",
                        "on-surface": "#1a1c1c",
                        "secondary-fixed-dim": "#acabab",
                        "on-secondary-container": "#1b1c1c"
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
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .headline { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-surface">
<!-- TopNavBar -->
<header class="fixed top-0 w-full z-50 bg-white/80 dark:bg-black/80 backdrop-blur-xl border-b border-neutral-100 dark:border-neutral-900 shadow-sm dark:shadow-none">
<div class="flex justify-between items-center h-16 px-6 max-w-screen-2xl mx-auto">
<div class="flex items-center gap-8">
<span class="text-2xl font-black tracking-tighter text-black dark:text-white">WHOIS</span>
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
</div>
<div class="flex items-center gap-4">
<button class="text-neutral-500 dark:text-neutral-400 font-medium hover:text-black transition-colors">Contact Us</button>
<button class="bg-primary text-on-primary px-5 py-2 rounded-xl font-bold scale-95 active:opacity-80 transition-all">Sign In</button>
</div>
</div>
</header>
<main class="pt-24 pb-20 px-6 max-w-screen-2xl mx-auto">
<!-- Hero Section -->
<section class="mb-16">
<div class="flex flex-col md:flex-row items-center gap-12 bg-surface-container-lowest p-8 md:p-16 rounded-[2rem] shadow-sm border border-outline-variant/20">
<div class="flex-1 space-y-6">
<div class="inline-flex items-center gap-2 px-3 py-1 bg-surface-container-low rounded-full border border-outline-variant/40">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">stars</span>
<span class="text-xs font-bold tracking-widest uppercase">Professional Appraisal</span>
</div>
<h1 class="text-5xl md:text-7xl font-extrabold tracking-tighter text-primary leading-none">
                        Appraise Any Domain
                    </h1>
<p class="text-lg text-on-surface-variant max-w-xl leading-relaxed">
                        Instant valuation powered by real-time market data and historical sales patterns. Add our extension to get values anywhere.
                    </p>
<div class="flex flex-wrap gap-4 pt-4">
<button class="bg-primary text-on-primary px-8 py-4 rounded-full font-bold flex items-center gap-3 hover:bg-primary-container transition-all">
<span class="material-symbols-outlined">extension</span>
                            Install Chrome Extension
                        </button>
<div class="flex items-center gap-2 text-on-surface-variant text-sm font-medium">
<span class="material-symbols-outlined text-primary">verified</span>
                            Trusted by 50k+ Investors
                        </div>
</div>
</div>
<div class="flex-1 w-full relative">
<div class="aspect-video bg-surface-container-low rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/40 relative">
<img alt="Software Dashboard Preview" class="w-full h-full object-cover grayscale opacity-20" data-alt="Modern clean abstract background with soft grey gradients and minimal geometric textures reflecting high-end software aesthetic" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9x8BX3xug5S-eACRkEfJNTqBUUH6e6vhZ6DhJynRzPazlE8cgVTXIGZSdp2KG5_L16orizpJiQ7WLO2EDNZH_MTE0Z0iGpYuMgDZvVcM-1zS6MtPgtWg6rP-HEiiWpLk_UAZcI8OMRZFRYMoS0ghKWFy_p-PfTgwA7vBPTCFZUg0iMFgL8Lp4z6trTpc9t5bX-vv5QwcIAiS6CewExBkcace9-VAubIzhpWf42KUMwC6zNmD2uL7maQVc3PQNzxJKoaKD998WyhV0"/>
<div class="absolute inset-0 flex items-center justify-center">
<div class="bg-white p-8 rounded-2xl shadow-xl border border-outline-variant w-4/5">
<div class="flex items-center gap-3 mb-4">
<div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-black">W</div>
<div class="h-2 w-32 bg-surface-container rounded"></div>
</div>
<div class="space-y-3">
<div class="h-12 w-full bg-surface-container-low rounded-xl border border-outline-variant/40 flex items-center px-4">
<span class="text-sm font-bold text-primary">Trovalabs.com</span>
</div>
<div class="grid grid-cols-2 gap-3">
<div class="h-16 bg-primary rounded-xl flex flex-col justify-center px-4">
<div class="text-[10px] text-on-primary/60 font-bold">VALUATION</div>
<div class="text-on-primary font-black">$1,799</div>
</div>
<div class="h-16 bg-surface-container rounded-xl"></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Summary Block -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
<div class="md:col-span-2 bg-primary text-on-primary p-10 rounded-[2rem] flex flex-col justify-between">
<div>
<span class="text-sm font-bold opacity-60 tracking-widest uppercase">Target Domain</span>
<h2 class="text-4xl md:text-6xl font-black tracking-tighter mt-2">Trovalabs.com</h2>
</div>
<div class="flex items-end justify-between mt-12">
<div class="space-y-1">
<span class="text-sm font-bold opacity-60">ESTIMATED VALUE</span>
<div class="flex items-baseline gap-2">
<span class="text-5xl font-black">$1,799</span>
<span class="text-xs font-bold px-2 py-1 bg-white/10 rounded">BETA</span>
</div>
</div>
<div class="text-right">
<span class="text-sm font-bold opacity-60">DOMAIN SCORE</span>
<div class="text-5xl font-black">6.1<span class="text-2xl opacity-40">/10</span></div>
</div>
</div>
</div>
<div class="bg-surface-container-lowest p-10 rounded-[2rem] border border-outline-variant/40 flex flex-col justify-center items-center text-center">
<div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-4xl text-primary">analytics</span>
</div>
<h3 class="text-xl font-bold mb-2">Market Liquidity</h3>
<p class="text-on-surface-variant text-sm px-4">This domain sits in the top 15% of brandable .com assets within the lab-tech niche.</p>
<button class="mt-8 text-sm font-black border-b-2 border-primary pb-1">View Full Report</button>
</div>
</section>
<!-- Key Signals -->
<section class="mb-16">
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
<div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant/20">
<span class="material-symbols-outlined text-primary mb-4" style="font-variation-settings: 'FILL' 1;">short_text</span>
<div class="text-2xl font-black">9 Letters</div>
<div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Length</div>
</div>
<div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant/20">
<span class="material-symbols-outlined text-primary mb-4">reorder</span>
<div class="text-2xl font-black">3 Syllables</div>
<div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pronunciation</div>
</div>
<div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant/20">
<span class="material-symbols-outlined text-primary mb-4" style="font-variation-settings: 'FILL' 1;">pentagon</span>
<div class="text-2xl font-black">Root Word</div>
<div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">"STR" Pattern</div>
</div>
<div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant/20">
<span class="material-symbols-outlined text-primary mb-4">public</span>
<div class="text-2xl font-black">TLD .com</div>
<div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Global Standard</div>
</div>
</div>
</section>
<!-- Comparable Sales & Root Analytics (Bento) -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16">
<div class="lg:col-span-2 bg-surface-container-lowest rounded-[2rem] border border-outline-variant/40 overflow-hidden">
<div class="p-8 border-b border-outline-variant/20 flex justify-between items-center">
<h3 class="text-xl font-bold tracking-tight">Comparable Sales</h3>
<div class="flex items-center gap-4">
<div class="text-right">
<div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Median Price</div>
<div class="text-lg font-black">$3,273</div>
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
<tr class="hover:bg-surface-container-low transition-colors">
<td class="px-8 py-5 font-bold">Lavenderlabs.com</td>
<td class="px-8 py-5 font-black">$4,500</td>
<td class="px-8 py-5 text-on-surface-variant">2023</td>
<td class="px-8 py-5"><span class="px-2 py-1 bg-surface-container text-[10px] font-bold rounded">HIGH</span></td>
<td class="px-8 py-5 text-sm">NameBio.com</td>
</tr>
<tr class="hover:bg-surface-container-low transition-colors">
<td class="px-8 py-5 font-bold">Pancakelabs.com</td>
<td class="px-8 py-5 font-black">$2,150</td>
<td class="px-8 py-5 text-on-surface-variant">2022</td>
<td class="px-8 py-5"><span class="px-2 py-1 bg-surface-container text-[10px] font-bold rounded">MED</span></td>
<td class="px-8 py-5 text-sm">Atom.com</td>
</tr>
<tr class="hover:bg-surface-container-low transition-colors">
<td class="px-8 py-5 font-bold">Cipherlabs.com</td>
<td class="px-8 py-5 font-black">$8,888</td>
<td class="px-8 py-5 text-on-surface-variant">2024</td>
<td class="px-8 py-5"><span class="px-2 py-1 bg-surface-container text-[10px] font-bold rounded">HIGH</span></td>
<td class="px-8 py-5 text-sm">NameBio.com</td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="space-y-6">
<!-- Root Word Analytics -->
<div class="bg-white p-8 rounded-[2rem] border border-outline-variant/40 shadow-sm h-full">
<h3 class="text-xl font-bold mb-6">Root Word: 'Labs'</h3>
<div class="space-y-6">
<div>
<div class="flex justify-between text-xs font-bold mb-2">
<span>MARKET POPULARITY</span>
<span>92%</span>
</div>
<div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-primary w-[92%]"></div>
</div>
</div>
<div>
<div class="flex justify-between text-xs font-bold mb-2">
<span>INVESTOR INTEREST</span>
<span>85%</span>
</div>
<div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-primary w-[85%]"></div>
</div>
</div>
<div class="pt-4 border-t border-outline-variant/20">
<p class="text-sm text-on-surface-variant leading-relaxed">
                                'Labs' is a premium suffix for R&amp;D, technology, and biotechnology sectors, consistently yielding 3.5x higher resale value.
                            </p>
</div>
</div>
</div>
</div>
</section>
<!-- Market Fit & Registration -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
<div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/40">
<h3 class="text-xl font-bold mb-6">Infrastructure Status</h3>
<div class="space-y-4">
<div class="flex items-center justify-between p-4 bg-white rounded-xl border border-outline-variant/20">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-green-600">check_circle</span>
<span class="font-bold text-sm">ICANN Accreditation</span>
</div>
<span class="text-xs font-bold text-on-surface-variant">VERIFIED</span>
</div>
<div class="flex items-center justify-between p-4 bg-white rounded-xl border border-outline-variant/20">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-green-600">check_circle</span>
<span class="font-bold text-sm">Extensions Registered (.com)</span>
</div>
<span class="text-xs font-bold text-on-surface-variant">ACTIVE</span>
</div>
<div class="flex items-center justify-between p-4 bg-white rounded-xl border border-outline-variant/20">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">pending</span>
<span class="font-bold text-sm">Atom Listing Status</span>
</div>
<span class="text-xs font-bold text-on-surface-variant">NOT LISTED</span>
</div>
</div>
</div>
<div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/40">
<h3 class="text-xl font-bold mb-6">Market Fit Analytics</h3>
<div class="flex items-center gap-6">
<div class="flex-1 text-center p-6 bg-white rounded-2xl border border-outline-variant/20">
<div class="text-3xl font-black">94%</div>
<div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Memorability</div>
</div>
<div class="flex-1 text-center p-6 bg-white rounded-2xl border border-outline-variant/20">
<div class="text-3xl font-black">88%</div>
<div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Brandability</div>
</div>
</div>
<p class="mt-6 text-sm text-on-surface-variant leading-relaxed">
                    Trovalabs offers a phonetically balanced structure that aligns with high-growth startup naming conventions.
                </p>
</div>
</section>
<!-- AI End Users -->
<section class="mb-16">
<h3 class="text-3xl font-black tracking-tight mb-8">Potential End Users</h3>
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
<div class="bg-white p-6 rounded-2xl border border-outline-variant/40 hover:shadow-lg transition-shadow">
<span class="material-symbols-outlined text-primary mb-4">biotech</span>
<h4 class="font-bold mb-2">Biotechnology</h4>
<p class="text-xs text-on-surface-variant leading-relaxed">High demand for 'Labs' terminology in drug discovery and clinical research firms.</p>
</div>
<div class="bg-white p-6 rounded-2xl border border-outline-variant/40 hover:shadow-lg transition-shadow">
<span class="material-symbols-outlined text-primary mb-4">rocket_launch</span>
<h4 class="font-bold mb-2">Tech Startups</h4>
<p class="text-xs text-on-surface-variant leading-relaxed">Ideal for software innovation hubs or AI-driven development agencies.</p>
</div>
<div class="bg-white p-6 rounded-2xl border border-outline-variant/40 hover:shadow-lg transition-shadow">
<span class="material-symbols-outlined text-primary mb-4">eco</span>
<h4 class="font-bold mb-2">Environmental</h4>
<p class="text-xs text-on-surface-variant leading-relaxed">Sustainability focused research centers and carbon capture labs.</p>
</div>
<div class="bg-white p-6 rounded-2xl border border-outline-variant/40 hover:shadow-lg transition-shadow">
<span class="material-symbols-outlined text-primary mb-4">school</span>
<h4 class="font-bold mb-2">Education</h4>
<p class="text-xs text-on-surface-variant leading-relaxed">Academic accelerators and university-linked research initiatives.</p>
</div>
<div class="bg-white p-6 rounded-2xl border border-outline-variant/40 hover:shadow-lg transition-shadow">
<span class="material-symbols-outlined text-primary mb-4">health_and_safety</span>
<h4 class="font-bold mb-2">Health</h4>
<p class="text-xs text-on-surface-variant leading-relaxed">Medical testing facilities and innovative health-tech incubators.</p>
</div>
</div>
</section>
<!-- Listing Services -->
<section class="mb-16">
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-primary p-8 rounded-[2rem] text-on-primary flex flex-col justify-between items-start">
<div>
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined">stars</span>
<span class="text-xs font-bold tracking-widest uppercase">Premium Service</span>
</div>
<h3 class="text-2xl font-black mb-4">Submit to Premium Marketplace</h3>
<p class="text-on-primary/70 text-sm mb-8 leading-relaxed">Get your domain featured in front of elite buyers and Fortune 500 companies.</p>
</div>
<button class="w-full bg-white text-black font-black py-4 rounded-xl hover:bg-neutral-200 transition-colors">Start Submission</button>
</div>
<div class="bg-white p-8 rounded-[2rem] border border-outline-variant/40 flex flex-col justify-between items-start">
<div>
<h3 class="text-2xl font-black mb-4">Standard Listing</h3>
<p class="text-on-surface-variant text-sm mb-8 leading-relaxed">List on our global directory accessible by thousands of daily domain seekers.</p>
</div>
<button class="w-full bg-primary text-white font-black py-4 rounded-xl hover:bg-primary-container transition-colors">List Domain</button>
</div>
<div class="bg-white p-8 rounded-[2rem] border border-outline-variant/40 flex flex-col justify-between items-start">
<div>
<h3 class="text-2xl font-black mb-4">White Label Marketplace</h3>
<p class="text-on-surface-variant text-sm mb-8 leading-relaxed">Create your own branded storefront to showcase your entire portfolio.</p>
</div>
<button class="w-full border-2 border-primary text-black font-black py-4 rounded-xl hover:bg-surface-container-low transition-colors">Setup Store</button>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-neutral-50 dark:bg-neutral-950 w-full border-t border-neutral-200 dark:border-neutral-800">
<div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-16 px-6 max-w-screen-2xl mx-auto font-['Inter'] text-sm leading-relaxed">
<div class="col-span-2 md:col-span-1">
<span class="text-xl font-bold tracking-tighter text-black dark:text-white mb-6 block">WHOIS</span>
<p class="text-neutral-500 dark:text-neutral-400 max-w-xs">
                    The global authority for domain intelligence, appraisal, and marketplace insights. Powered by AI.
                </p>
</div>
<div>
<h4 class="font-bold text-black dark:text-white mb-6">Products</h4>
<ul class="space-y-4">
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Domain Search</a></li>
<li><a class="text-black dark:text-white underline font-bold" href="#">Appraisal Tool</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Bulk WHOIS</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Marketplace</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-black dark:text-white mb-6">Resources</h4>
<ul class="space-y-4">
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">API Access</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Portfolio Management</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Seller Hub</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Branding Guide</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-black dark:text-white mb-6">Legal</h4>
<ul class="space-y-4">
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Privacy Policy</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-colors" href="#">Terms of Service</a></li>
</ul>
</div>
</div>
<div class="px-6 py-8 max-w-screen-2xl mx-auto border-t border-neutral-200/50 dark:border-neutral-800/50">
<p class="text-neutral-400 text-xs text-center">&copy; 2024 WHOIS Global Authority. All rights reserved.</p>
</div>
</footer>
<script src="../assets/js/nav-state.js"></script>
</body></html>




