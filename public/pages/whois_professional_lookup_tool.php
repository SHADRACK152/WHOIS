<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS Lookup | ARCHITECT</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                "surface-dim": "#dadada",
                "secondary-container": "#d5d4d4",
                "on-secondary-fixed-variant": "#3b3b3c",
                "tertiary-fixed-dim": "#454747",
                "on-secondary-fixed": "#1b1c1c",
                "surface-bright": "#f9f9f9",
                "on-surface-variant": "#474747",
                "on-secondary": "#ffffff",
                "on-tertiary-fixed": "#ffffff",
                "surface-container-lowest": "#ffffff",
                "secondary": "#5e5e5e",
                "on-primary": "#e2e2e2",
                "surface-container-high": "#e8e8e8",
                "inverse-primary": "#c6c6c6",
                "on-error-container": "#410002",
                "background": "#f9f9f9",
                "secondary-fixed": "#c7c6c6",
                "on-tertiary-container": "#ffffff",
                "surface-container-low": "#f3f3f3",
                "on-surface": "#1a1c1c",
                "on-background": "#1a1c1c",
                "error": "#ba1a1a",
                "primary-fixed": "#5e5e5e",
                "primary-container": "#3b3b3b",
                "outline": "#777777",
                "on-secondary-container": "#1b1c1c",
                "on-primary-fixed-variant": "#e2e2e2",
                "surface-variant": "#e2e2e2",
                "on-primary-fixed": "#ffffff",
                "outline-variant": "#c6c6c6",
                "secondary-fixed-dim": "#acabab",
                "tertiary": "#3a3c3c",
                "surface-tint": "#5e5e5e",
                "inverse-on-surface": "#f1f1f1",
                "on-tertiary-fixed-variant": "#e2e2e2",
                "on-error": "#ffffff",
                "tertiary-fixed": "#5d5f5f",
                "error-container": "#ffdad6",
                "surface-container": "#eeeeee",
                "primary-fixed-dim": "#474747",
                "on-tertiary": "#e2e2e2",
                "surface": "#f9f9f9",
                "surface-container-highest": "#e2e2e2",
                "on-primary-container": "#ffffff",
                "inverse-surface": "#2f3131",
                "primary": "#000000",
                "tertiary-container": "#737575"
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
        h1, h2, h3, .brand-font { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased">
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
<main class="pt-24 pb-20 px-8 max-w-screen-2xl mx-auto">
<!-- Breadcrumb -->
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
<!-- Hero Search Section -->
<section class="mb-16">
<div class="max-w-3xl">
<h1 class="text-5xl font-extrabold tracking-tighter mb-8">WHOIS Domain Lookup</h1>
<div class="relative group">
<div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline">search</span>
</div>
<input class="w-full pl-14 pr-40 py-6 bg-surface-container-lowest border border-outline-variant rounded-full text-xl focus:ring-0 focus:border-primary transition-all shadow-sm" placeholder="Search domain ownership..." type="text" value="trovalabs.com"/>
<button class="absolute right-3 top-3 bottom-3 bg-primary text-white px-8 rounded-full font-semibold hover:bg-primary-container transition-all flex items-center gap-2">
<span>Search</span>
</button>
</div>
</div>
</section>
<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
<!-- Left Column: Primary Data -->
<div class="lg:col-span-8 space-y-8">
<!-- Domain Status Card -->
<div class="bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
<div class="flex items-center gap-6">
<div class="w-16 h-16 bg-error-container/20 rounded-full flex items-center justify-center text-error">
<span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">warning</span>
</div>
<div>
<h2 class="text-3xl font-bold tracking-tight">trovalabs.com</h2>
<p class="text-on-surface-variant font-medium mt-1">This domain is taken</p>
</div>
</div>
<button class="px-6 py-3 border border-outline-variant text-black rounded-xl font-semibold hover:bg-surface-container-low transition-colors">Learn More</button>
</div>
<!-- WHOIS Information -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 overflow-hidden">
<div class="px-8 py-6 border-b border-outline-variant/30 bg-surface-container-low/50">
<h3 class="font-bold text-lg">WHOIS Information</h3>
</div>
<div class="p-8">
<div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
<div class="space-y-1">
<span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Domain Name</span>
<p class="text-black font-semibold">TROVALABS.COM</p>
</div>
<div class="space-y-1">
<span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Registrar</span>
<p class="text-black font-semibold">Architect Domains, LLC</p>
</div>
<div class="space-y-1">
<span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Creation Date</span>
<p class="text-black font-semibold">2021-05-14T15:22:01Z</p>
</div>
<div class="space-y-1">
<span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Expiration Date</span>
<p class="text-black font-semibold">2025-05-14T15:22:01Z</p>
</div>
<div class="space-y-1">
<span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Updated Date</span>
<p class="text-black font-semibold">2024-03-10T09:44:12Z</p>
</div>
<div class="space-y-1">
<span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Status</span>
<p class="text-black font-semibold flex items-center gap-2">clientTransferProhibited <span class="material-symbols-outlined text-xs text-on-surface-variant">info</span></p>
</div>
<div class="md:col-span-2 space-y-1">
<span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Nameservers</span>
<p class="text-black font-semibold">NS1.ARCHITECT-DNS.COM<br/>NS2.ARCHITECT-DNS.COM</p>
</div>
</div>
</div>
</div>
<!-- Contact Information (Masked) -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 overflow-hidden">
<div class="px-8 py-6 border-b border-outline-variant/30 flex justify-between items-center">
<h3 class="font-bold text-lg">Contact Information</h3>
<span class="text-xs bg-surface-container-high px-3 py-1 rounded-full text-on-surface-variant font-medium">REDACTED FOR PRIVACY</span>
</div>
<div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="space-y-4">
<h4 class="text-sm font-bold text-black border-b border-outline-variant pb-2">Registrant</h4>
<div class="text-sm text-on-surface-variant space-y-2">
<p class="italic">Data Redacted</p>
<p class="italic">Data Redacted</p>
<p class="italic">US</p>
</div>
</div>
<div class="space-y-4">
<h4 class="text-sm font-bold text-black border-b border-outline-variant pb-2">Administrative</h4>
<div class="text-sm text-on-surface-variant space-y-2">
<p class="italic">Data Redacted</p>
<p class="italic">Data Redacted</p>
<p class="italic">US</p>
</div>
</div>
<div class="space-y-4">
<h4 class="text-sm font-bold text-black border-b border-outline-variant pb-2">Technical</h4>
<div class="text-sm text-on-surface-variant space-y-2">
<p class="italic">Data Redacted</p>
<p class="italic">Data Redacted</p>
<p class="italic">US</p>
</div>
</div>
</div>
</div>
</div>
<!-- Right Column: Sidebar Actions & Insights -->
<div class="lg:col-span-4 space-y-8">
<!-- Alternative Extensions -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 p-6">
<h3 class="font-bold mb-6">Alternative Extensions</h3>
<div class="space-y-4">
<div class="flex items-center justify-between group">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-zinc-400">check_circle</span>
<span class="font-medium">trovalabs.net</span>
</div>
<button class="text-sm font-bold text-black flex items-center gap-1 hover:underline transition-all">
                                Add to Cart <span class="material-symbols-outlined text-xs">arrow_forward</span>
</button>
</div>
<div class="flex items-center justify-between group">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-zinc-400">check_circle</span>
<span class="font-medium">trovalabs.io</span>
</div>
<button class="text-sm font-bold text-black flex items-center gap-1 hover:underline transition-all">
                                Add to Cart <span class="material-symbols-outlined text-xs">arrow_forward</span>
</button>
</div>
<div class="flex items-center justify-between group">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-zinc-400">check_circle</span>
<span class="font-medium">trovalabs.ai</span>
</div>
<button class="text-sm font-bold text-black flex items-center gap-1 hover:underline transition-all">
                                Add to Cart <span class="material-symbols-outlined text-xs">arrow_forward</span>
</button>
</div>
</div>
</div>
<!-- Brand Fit Check -->
<div class="bg-black text-white rounded-xl p-8">
<div class="flex items-center gap-2 mb-4">
<h3 class="font-bold text-lg">Brand Fit Check</h3>
<span class="material-symbols-outlined text-sm opacity-60">info</span>
</div>
<p class="text-zinc-400 text-sm mb-6">See how this domain resonates with your specific business goals.</p>
<div class="space-y-4">
<input class="w-full bg-zinc-900 border-zinc-800 rounded-lg py-3 px-4 text-sm focus:ring-zinc-700" placeholder="Your business name" type="text"/>
<button class="w-full bg-white text-black font-bold py-3 rounded-lg hover:bg-zinc-200 transition-colors">Check Fit</button>
</div>
</div>
<!-- Domain Insights -->
<div class="grid grid-cols-2 gap-4">
<div class="bg-surface-container-low p-5 rounded-xl border border-outline-variant/30">
<div class="flex items-center justify-between mb-2">
<span class="material-symbols-outlined text-zinc-400 text-lg">public</span>
<span class="material-symbols-outlined text-error text-sm">warning</span>
</div>
<p class="text-2xl font-bold">0</p>
<p class="text-[10px] uppercase font-bold tracking-wider text-on-surface-variant mt-1">Extensions Reg.</p>
</div>
<div class="bg-surface-container-low p-5 rounded-xl border border-outline-variant/30">
<div class="flex items-center justify-between mb-2">
<span class="material-symbols-outlined text-zinc-400 text-lg">history</span>
<span class="material-symbols-outlined text-zinc-400 text-sm">info</span>
</div>
<p class="text-2xl font-bold">3</p>
<p class="text-[10px] uppercase font-bold tracking-wider text-on-surface-variant mt-1">Past Owners</p>
</div>
</div>
</div>
</div>
<!-- Trademark Check Section -->
<section class="mt-20 border-y border-outline-variant py-16">
<div class="flex flex-col md:flex-row justify-between items-center gap-8">
<div class="max-w-2xl">
<h2 class="text-3xl font-bold tracking-tight mb-4">Protect Your Identity</h2>
<p class="text-on-surface-variant leading-relaxed">Ensure "Trovalabs" doesn't infringe on existing trademarks before you commit to a purchase. Our comprehensive check analyzes global databases.</p>
</div>
<button class="bg-black text-white px-10 py-4 rounded-xl font-bold hover:opacity-80 transition-all">Get Started</button>
</div>
</section>
<!-- Premium Domains Grid -->
<section class="mt-20">
<div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
<div>
<h2 class="text-3xl font-bold tracking-tight mb-2">Premium Opportunities</h2>
<p class="text-on-surface-variant">Curated high-value domains available for acquisition.</p>
</div>
<div class="flex gap-2 overflow-x-auto pb-2 w-full md:w-auto">
<span class="px-4 py-2 bg-black text-white text-xs font-bold rounded-full whitespace-nowrap cursor-pointer">All</span>
<span class="px-4 py-2 bg-surface-container-low text-on-surface-variant text-xs font-bold rounded-full border border-outline-variant/30 whitespace-nowrap cursor-pointer hover:bg-surface-container-high">Tech</span>
<span class="px-4 py-2 bg-surface-container-low text-on-surface-variant text-xs font-bold rounded-full border border-outline-variant/30 whitespace-nowrap cursor-pointer hover:bg-surface-container-high">Clothing</span>
<span class="px-4 py-2 bg-surface-container-low text-on-surface-variant text-xs font-bold rounded-full border border-outline-variant/30 whitespace-nowrap cursor-pointer hover:bg-surface-container-high">Finance</span>
<span class="px-4 py-2 bg-surface-container-low text-on-surface-variant text-xs font-bold rounded-full border border-outline-variant/30 whitespace-nowrap cursor-pointer hover:bg-surface-container-high">Creative</span>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="bg-surface-container-lowest border border-outline-variant/30 p-6 rounded-xl group hover:shadow-xl transition-all cursor-pointer">
<div class="h-40 bg-zinc-100 rounded-lg mb-6 overflow-hidden">
<img class="w-full h-full object-cover grayscale opacity-80 group-hover:opacity-100 transition-opacity" data-alt="Abstract cyber geometric shapes in monochrome tones with sharp lines and high contrast studio lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCoQ0O1qww5qxXHKvABcGyQGjATqbfN3Bn4Af1ggRjim7j4unBuRUsYVRVG6UEqEvEZlfqE9CGhVjpFAOH4oMntZv77ltn_fTzO91bE-zNDn0jBRAZFDaqlH7ijKf8Frn4peeVYHFAa_5i0BK9Pk3YbNN3fZCt8t4hmPto5mDUIwJoeG8-li67ovL8_lLnAXAllPVog0tuup4bKfb4A69BkIUYSv1HHFQdz5pCgptFq6a4aN8CEJtiNNsMrdgWgYN9dCL_U4-LIekt_"/>
</div>
<h3 class="text-xl font-bold mb-1">cipher.io</h3>
<p class="text-xs text-on-surface-variant mb-4">Modern Security Tech</p>
<div class="flex justify-between items-center">
<span class="text-lg font-bold">$12,499</span>
<span class="material-symbols-outlined text-black">shopping_bag</span>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant/30 p-6 rounded-xl group hover:shadow-xl transition-all cursor-pointer">
<div class="h-40 bg-zinc-100 rounded-lg mb-6 overflow-hidden">
<img class="w-full h-full object-cover grayscale opacity-80 group-hover:opacity-100 transition-opacity" data-alt="Minimalist fashion boutique interior with clean white walls and structured clothing racks in black and white photography" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBI-p8A-Weqx0VPvB_Iv4sgQH5wb6D6ve7jrNGgb-5JSuZPeQvq1hWPxN4WIIxDZi_lBQSRHPd38Iaq-K__crqgAEzMRLaqG38wsejd3O0wiqRwG3TipMyYfGUcxbzOkN9lWCNuWa0UQ5ojLEHGqttiFx-oVW3qrE2PYmfktfJlMclV-4NuMXDcXedaUIeUlg_z8mbd9hNtZ5v10TU5m0vgQBbYFqSHSBt9KLGqQmw4hT7q98vdumjjtmBoo5Vmip62JUtLXa6oFMrV"/>
</div>
<h3 class="text-xl font-bold mb-1">thread.ly</h3>
<p class="text-xs text-on-surface-variant mb-4">Fashion &amp; Apparel</p>
<div class="flex justify-between items-center">
<span class="text-lg font-bold">$4,200</span>
<span class="material-symbols-outlined text-black">shopping_bag</span>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant/30 p-6 rounded-xl group hover:shadow-xl transition-all cursor-pointer">
<div class="h-40 bg-zinc-100 rounded-lg mb-6 overflow-hidden">
<img class="w-full h-full object-cover grayscale opacity-80 group-hover:opacity-100 transition-opacity" data-alt="Complex architectural blueprint with clean geometric patterns and subtle grid lines in a grayscale technical style" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgYqMRcsCiv5lvStk-0MrAA9_UO3dytdQY6nPMaYvibzu59v3NOdJZAJE7dRqGsM1TfAT6ImHm5Gx-ArrZlbrocCGSYMd2013QyuCojQpQUdIYaa-Dfp-Z-IOoAVvLMGOiR8ADawmo5WrGNJO8AbVeVq-En1o0vF40eOH_V2ifEuFQJBe-AbmeoBCvBX1XH8KyYtPx_vkmBKuTe8haosXUc7kmbgElFSQQZdk8Gv5pk67rd4CP1zlrPIKiTA88DwJ9pxQoswuqje8U"/>
</div>
<h3 class="text-xl font-bold mb-1">vault.com</h3>
<p class="text-xs text-on-surface-variant mb-4">Premium FinTech</p>
<div class="flex justify-between items-center">
<span class="text-lg font-bold">$85,000</span>
<span class="material-symbols-outlined text-black">shopping_bag</span>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant/30 p-6 rounded-xl group hover:shadow-xl transition-all cursor-pointer">
<div class="h-40 bg-zinc-100 rounded-lg mb-6 overflow-hidden">
<img class="w-full h-full object-cover grayscale opacity-80 group-hover:opacity-100 transition-opacity" data-alt="Spacious modern office atrium with high ceilings and glass walls in soft natural light black and white aesthetics" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcQo_cn-hapVSrSmrIy1zqIOFQLQpOSiZA5rpnLZNQXu1NSvP2SP_swiTXSL5EtbDVnrzbmXG_oCcLWFXSf9S14QYoZz03JLPSVUKO78aCaPjyv6_CFBpbK7b_6B6uP8CgS5RyuPfTlnhMcs9uaqdFNFBv9rv_EbqIqd94RbMkFsc67NsZAfAfn9AFaG2NioPuu_6GgUgZ7f1-iVE98BGA9f3nafW1jluwF3Wb4LTihgl6UfMoghMbeqxlnCYhiZ3xKhVxq5L_xNqw"/>
</div>
<h3 class="text-xl font-bold mb-1">atrio.app</h3>
<p class="text-xs text-on-surface-variant mb-4">Software Solutions</p>
<div class="flex justify-between items-center">
<span class="text-lg font-bold">$3,150</span>
<span class="material-symbols-outlined text-black">shopping_bag</span>
</div>
</div>
</div>
</section>
<!-- Domain Services Feature Cards -->
<section class="mt-32 grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="relative group overflow-hidden rounded-2xl bg-zinc-100 h-80 flex flex-col justify-end p-10 border border-outline-variant/30">
<img class="absolute inset-0 w-full h-full object-cover grayscale opacity-40 group-hover:scale-105 transition-transform duration-700" data-alt="High-end designer workspace with minimal tech tools and clean white surfaces in bright morning light" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHxhacwDxaW6cKoNXPsFZX8eV5-ljUe9VTNMU7klkc_2pM7Ts07y0LJP3I8O4XW8x7Xphj3rJ8N7k1OOfrd1SCnhfppT-mS8h9N3zA4A9t8gISW312ZJtPzE806rmhv_7kjQ_hWjdviPvnTGCrXz-uiHSR-LBOXJR5jUueK7z2ATOJHgbT4-T5_yCfvv5POmVAH6jnmpnO3rFK55BXSiQ4T4f3H5LDKG_9ivwZDJJGZP680dxXkqdLn2A0QVE6iDRuIL-r0zmQDPQJ"/>
<div class="relative z-10">
<h3 class="text-3xl font-bold mb-4">Find The Perfect Domain</h3>
<p class="text-on-surface-variant max-w-sm mb-6">Let our expert brokers negotiate the acquisition of your dream digital identity.</p>
<button class="flex items-center gap-2 font-bold group-hover:gap-4 transition-all">Explore Brokerage <span class="material-symbols-outlined">arrow_forward</span></button>
</div>
</div>
<div class="relative group overflow-hidden rounded-2xl bg-black text-white h-80 flex flex-col justify-end p-10">
<img class="absolute inset-0 w-full h-full object-cover grayscale opacity-20 group-hover:scale-105 transition-transform duration-700" data-alt="Collaborative meeting scene showing diverse hands working on a large white table with architectural sketches" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCP0HWs4f7TQigNlatJrq_sToMXK3VuYWNOgAcOvkoAzjCkhPZp4oDLWoXJ93QjOkl1Hxb6o_F1O52nLMJceq9t4XCBRHbQtvYU-54nA_KmmDgAvfED8gKjNaLg_WGS-mELJ3JnBG7Nz-uZbbeF26di0oSFJCaTJWVeyvaFDoCPyk3nnRtuFX82hEL3QBVsJUGlmasznh8xjjVsalPr5G5SoQcb28Cf3P1SNum3WdP091n-ApoTFLCKkDoURLlvGWDDLUAOuFQxLS4O"/>
<div class="relative z-10">
<h3 class="text-3xl font-bold mb-4">Launch a Naming Contest</h3>
<p class="text-zinc-400 max-w-sm mb-6">Leverage our community of 100,000+ creatives to find a name that perfectly fits your brand.</p>
<button class="flex items-center gap-2 font-bold group-hover:gap-4 transition-all">Start Contest <span class="material-symbols-outlined">arrow_forward</span></button>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="w-full border-t border-zinc-200 bg-zinc-50 font-inter text-sm">
<div class="grid grid-cols-1 md:grid-cols-4 gap-12 px-8 py-16 max-w-screen-2xl mx-auto">
<div class="space-y-6">
<div class="text-lg font-bold text-black tracking-tighter">ARCHITECT</div>
<p class="text-zinc-500 leading-relaxed">Providing the infrastructure for the next generation of digital identity and brand ownership.</p>
</div>
<div class="space-y-4">
<h4 class="font-bold text-black uppercase text-xs tracking-widest">Resources</h4>
<ul class="space-y-3 text-zinc-500">
<li><a class="hover:text-black transition-colors" href="#">Help Center</a></li>
<li><a class="hover:text-black transition-colors" href="#">Domain Academy</a></li>
<li><a class="hover:text-black transition-colors" href="#">Naming Guides</a></li>
<li><a class="hover:text-black transition-colors" href="#">API Docs</a></li>
</ul>
</div>
<div class="space-y-4">
<h4 class="font-bold text-black uppercase text-xs tracking-widest">Company</h4>
<ul class="space-y-3 text-zinc-500">
<li><a class="hover:text-black transition-colors" href="#">About Us</a></li>
<li><a class="hover:text-black transition-colors" href="#">Our History</a></li>
<li><a class="hover:text-black transition-colors" href="#">Contact</a></li>
<li><a class="hover:text-black transition-colors" href="#">Careers</a></li>
</ul>
</div>
<div class="space-y-4">
<h4 class="font-bold text-black uppercase text-xs tracking-widest">Legal</h4>
<ul class="space-y-3 text-zinc-500">
<li><a class="hover:text-black transition-colors" href="#">Legal</a></li>
<li><a class="hover:text-black transition-colors" href="#">Privacy</a></li>
<li><a class="hover:text-black transition-colors" href="#">WHOIS Policy</a></li>
<li><a class="hover:text-black transition-colors" href="#">Abuse</a></li>
</ul>
</div>
</div>
<div class="px-8 py-8 border-t border-zinc-200 max-w-screen-2xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
<p class="text-zinc-400">&copy; 2024 Architect Identity. All rights reserved.</p>
<div class="flex gap-6">
<a class="text-zinc-400 hover:text-black transition-colors" href="#"><span class="material-symbols-outlined">public</span></a>
<a class="text-zinc-400 hover:text-black transition-colors" href="#"><span class="material-symbols-outlined">alternate_email</span></a>
<a class="text-zinc-400 hover:text-black transition-colors" href="#"><span class="material-symbols-outlined">verified</span></a>
</div>
</div>
</footer>
<script src="../assets/js/nav-state.js"></script>
</body></html>




