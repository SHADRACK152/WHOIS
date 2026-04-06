<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | Search Results - trovalabs.com</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Manrope:wght@700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "background": "#f9f9f9",
                        "on-primary-container": "#ffffff",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "inverse-on-surface": "#f1f1f1",
                        "on-secondary-container": "#1b1c1c",
                        "surface-container-lowest": "#ffffff",
                        "surface": "#f9f9f9",
                        "tertiary": "#3a3c3c",
                        "surface-variant": "#e2e2e2",
                        "on-primary": "#e2e2e2",
                        "surface-dim": "#dadada",
                        "on-tertiary": "#e2e2e2",
                        "surface-bright": "#f9f9f9",
                        "secondary-fixed": "#c7c6c6",
                        "tertiary-fixed-dim": "#454747",
                        "on-surface": "#1a1c1c",
                        "error-container": "#ffdad6",
                        "tertiary-fixed": "#5d5f5f",
                        "on-secondary-fixed": "#1b1c1c",
                        "outline": "#777777",
                        "on-tertiary-container": "#ffffff",
                        "on-error-container": "#410002",
                        "secondary": "#5e5e5e",
                        "secondary-container": "#d5d4d4",
                        "on-primary-fixed": "#ffffff",
                        "inverse-primary": "#c6c6c6",
                        "outline-variant": "#c6c6c6",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "on-error": "#ffffff",
                        "on-secondary": "#ffffff",
                        "on-background": "#1a1c1c",
                        "inverse-surface": "#2f3131",
                        "surface-container-high": "#e8e8e8",
                        "primary-fixed-dim": "#474747",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "surface-container-low": "#f3f3f3",
                        "on-tertiary-fixed": "#ffffff",
                        "surface-tint": "#5e5e5e",
                        "secondary-fixed-dim": "#acabab",
                        "surface-container-highest": "#e2e2e2",
                        "primary": "#000000",
                        "primary-fixed": "#5e5e5e",
                        "error": "#ba1a1a",
                        "tertiary-container": "#737575",
                        "on-surface-variant": "#474747",
                        "primary-container": "#3b3b3b",
                        "surface-container": "#eeeeee"
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
        }
        body {
            background-color: #f9f9f9;
            color: #1a1c1c;
            font-family: 'Inter', sans-serif;
        }
        .tonal-shift-bg {
            background-color: #f3f3f3;
        }
    </style>
</head>
<body class="bg-surface font-body antialiased">
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
<main class="pt-24 pb-20">
<!-- Sticky Search Bar Section -->
<section class="sticky top-16 z-40 bg-white border-b border-outline-variant/30 py-6 px-6">
<div class="max-w-4xl mx-auto">
<div class="relative flex items-center">
<span class="material-symbols-outlined absolute left-4 text-neutral-400">search</span>
<input class="w-full pl-12 pr-32 py-4 rounded-full border border-outline-variant bg-surface-container-lowest focus:ring-1 focus:ring-primary focus:border-primary outline-none text-lg transition-all" type="text" value="trovalabs.com"/>
<button class="absolute right-2 bg-primary text-on-primary-container px-8 py-2.5 rounded-full font-semibold hover:bg-primary-container transition-all active:scale-95">
                        Search
                    </button>
</div>
<div class="mt-3 flex gap-6 px-4">
<span class="text-xs font-medium text-on-surface-variant"><span class="text-primary">.com</span> - $11.30/year</span>
<span class="text-xs font-medium text-on-surface-variant"><span class="text-primary">.ai</span> - $78.88/year</span>
</div>
</div>
</section>
<div class="max-w-6xl mx-auto px-6 mt-12 grid grid-cols-1 lg:grid-cols-12 gap-8">
<!-- Left Column: Results -->
<div class="lg:col-span-8 space-y-8">
<!-- Domain Status Block -->
<div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_2px_15px_rgba(0,0,0,0.02)] border border-outline-variant/20">
<div class="flex justify-between items-start">
<div>
<h1 class="text-4xl font-headline font-extrabold tracking-tight text-primary">trovalabs.com</h1>
<div class="mt-4 flex items-center gap-2 px-3 py-1 bg-surface-container-low w-fit rounded-lg border border-outline-variant/40">
<span class="material-symbols-outlined text-sm text-secondary">info</span>
<span class="text-sm font-semibold text-secondary">Registered</span>
</div>
<p class="mt-4 text-on-surface-variant font-medium">Choose a strong alternative below or try to acquire this domain.</p>
</div>
</div>
</div>
<!-- Exact Matches Section -->
<section>
<h2 class="text-xl font-headline font-bold mb-6 flex items-center gap-2">
                        Exact Matches
                    </h2>
<div class="space-y-3">
<!-- Best Value Item -->
<div class="group flex items-center justify-between p-5 bg-surface-container-lowest rounded-xl border-2 border-primary ring-4 ring-primary/5 transition-all">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary">check_circle</span>
<div>
<div class="flex items-center gap-2">
<span class="text-lg font-bold text-primary">trovalabs.ai</span>
<span class="text-[10px] uppercase tracking-widest font-bold bg-primary text-white px-2 py-0.5 rounded">Best Value</span>
</div>
<span class="text-sm text-on-surface-variant">$85.30/year</span>
</div>
</div>
<button class="bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-container transition-all">Get It</button>
</div>
<!-- Regular Item -->
<div class="group flex items-center justify-between p-5 bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-primary/40 transition-all">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-neutral-300 group-hover:text-primary transition-colors">add_circle</span>
<div>
<span class="text-lg font-semibold text-primary">trovalabs.io</span>
<div class="text-sm text-on-surface-variant">$34.80/year</div>
</div>
</div>
<button class="border border-outline text-primary px-6 py-2 rounded-lg font-semibold hover:bg-surface-container-low transition-all">Get It</button>
</div>
<!-- Regular Item -->
<div class="group flex items-center justify-between p-5 bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-primary/40 transition-all">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-neutral-300 group-hover:text-primary transition-colors">add_circle</span>
<div>
<span class="text-lg font-semibold text-primary">trovalabs.net</span>
<div class="text-sm text-on-surface-variant">$14.99/year</div>
</div>
</div>
<button class="border border-outline text-primary px-6 py-2 rounded-lg font-semibold hover:bg-surface-container-low transition-all">Get It</button>
</div>
</div>
</section>
<!-- AI-Generated Ideas Section -->
<section>
<div class="flex items-center gap-2 mb-6">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
<h2 class="text-xl font-headline font-bold">AI-Generated Ideas</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="p-6 bg-white border border-outline-variant rounded-xl hover:shadow-xl hover:shadow-black/5 transition-all flex flex-col justify-between h-40">
<div>
<span class="text-xs uppercase tracking-widest font-bold text-neutral-400">Branding Tool</span>
<h3 class="text-xl font-bold mt-1">LabsTrova.com</h3>
</div>
<div class="flex justify-between items-center">
<span class="text-sm font-medium text-on-surface-variant">$12.00/yr</span>
<button class="text-primary font-bold text-sm hover:underline">View Options</button>
</div>
</div>
<div class="p-6 bg-white border border-outline-variant rounded-xl hover:shadow-xl hover:shadow-black/5 transition-all flex flex-col justify-between h-40">
<div>
<span class="text-xs uppercase tracking-widest font-bold text-neutral-400">Modern Twist</span>
<h3 class="text-xl font-bold mt-1">TrovaGen.ai</h3>
</div>
<div class="flex justify-between items-center">
<span class="text-sm font-medium text-on-surface-variant">$78.88/yr</span>
<button class="text-primary font-bold text-sm hover:underline">View Options</button>
</div>
</div>
<div class="p-6 bg-white border border-outline-variant rounded-xl hover:shadow-xl hover:shadow-black/5 transition-all flex flex-col justify-between h-40">
<div>
<span class="text-xs uppercase tracking-widest font-bold text-neutral-400">Short &amp; Bold</span>
<h3 class="text-xl font-bold mt-1">Trova.ly</h3>
</div>
<div class="flex justify-between items-center">
<span class="text-sm font-medium text-on-surface-variant">$45.00/yr</span>
<button class="text-primary font-bold text-sm hover:underline">View Options</button>
</div>
</div>
<div class="p-6 bg-white border border-outline-variant rounded-xl hover:shadow-xl hover:shadow-black/5 transition-all flex flex-col justify-between h-40">
<div>
<span class="text-xs uppercase tracking-widest font-bold text-neutral-400">Infrastructure</span>
<h3 class="text-xl font-bold mt-1">TrovaStack.com</h3>
</div>
<div class="flex justify-between items-center">
<span class="text-sm font-medium text-on-surface-variant">$11.30/yr</span>
<button class="text-primary font-bold text-sm hover:underline">View Options</button>
</div>
</div>
</div>
</section>
<!-- FAQ Section -->
<section class="mt-12 py-12 border-t border-outline-variant">
<h2 class="text-2xl font-headline font-bold mb-8">Frequently Asked Questions</h2>
<div class="space-y-4">
<div class="border-b border-outline-variant pb-4">
<button class="w-full flex justify-between items-center text-left py-2 group">
<span class="font-semibold group-hover:text-primary transition-colors">What is a domain registrar?</span>
<span class="material-symbols-outlined text-neutral-400">expand_more</span>
</button>
</div>
<div class="border-b border-outline-variant pb-4">
<button class="w-full flex justify-between items-center text-left py-2 group">
<span class="font-semibold group-hover:text-primary transition-colors">How do I transfer a domain?</span>
<span class="material-symbols-outlined text-neutral-400">expand_more</span>
</button>
</div>
<div class="border-b border-outline-variant pb-4">
<button class="w-full flex justify-between items-center text-left py-2 group">
<span class="font-semibold group-hover:text-primary transition-colors">What happens after I buy a domain?</span>
<span class="material-symbols-outlined text-neutral-400">expand_more</span>
</button>
</div>
</div>
</section>
</div>
<!-- Right Column: Sidebar -->
<div class="lg:col-span-4 space-y-8">
<!-- Domain Brokerage Box -->
<div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant">
<div class="bg-primary-container/10 p-3 w-fit rounded-lg mb-6">
<span class="material-symbols-outlined text-primary">support_agent</span>
</div>
<h3 class="text-xl font-headline font-bold mb-3 text-primary">Try to acquire this domain</h3>
<p class="text-sm text-on-surface-variant leading-relaxed mb-6">
                        Although trovalabs.com is registered, we can attempt to contact the current owner on your behalf to negotiate a purchase.
                    </p>
<button class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-neutral-800 transition-all active:scale-95 shadow-lg shadow-black/5">
                        Request Broker Service
                    </button>
</div>
<!-- Premium Domains Section -->
<div>
<h2 class="text-xl font-headline font-bold mb-6">Premium Domains</h2>
<div class="grid grid-cols-1 gap-4">
<div class="p-6 bg-white rounded-xl border border-outline-variant hover:shadow-xl transition-all group">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-neutral-300 group-hover:text-amber-500 transition-colors" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-2xl font-bold font-headline">$3,495</span>
</div>
<h4 class="text-lg font-bold text-primary mb-4">trova.com</h4>
<button class="w-full py-2 border border-primary text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-all">Buy Premium</button>
</div>
<div class="p-6 bg-white rounded-xl border border-outline-variant hover:shadow-xl transition-all group">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-neutral-300 group-hover:text-amber-500 transition-colors" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-2xl font-bold font-headline">$5,200</span>
</div>
<h4 class="text-lg font-bold text-primary mb-4">thetrova.com</h4>
<button class="w-full py-2 border border-primary text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-all">Buy Premium</button>
</div>
<div class="p-6 bg-white rounded-xl border border-outline-variant hover:shadow-xl transition-all group">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-neutral-300 group-hover:text-amber-500 transition-colors" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-2xl font-bold font-headline">$1,850</span>
</div>
<h4 class="text-lg font-bold text-primary mb-4">labstrova.io</h4>
<button class="w-full py-2 border border-primary text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-all">Buy Premium</button>
</div>
</div>
<button class="w-full mt-6 text-sm font-bold text-primary flex items-center justify-center gap-2 group">
                        Explore Premium Collection 
                        <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="w-full border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 font-inter text-sm antialiased mt-20">
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 px-8 py-16 max-w-7xl mx-auto">
<div class="lg:col-span-2">
<span class="font-manrope font-black text-black dark:text-white text-2xl">WHOIS</span>
<p class="mt-4 text-neutral-500 dark:text-neutral-400 max-w-xs leading-relaxed">The global authority for digital identity and domain intelligence. Built for the modern architect.</p>
</div>
<div>
<h4 class="font-semibold text-black dark:text-white mb-6">Domain Services</h4>
<ul class="space-y-4">
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Search Domains</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Transfer Tools</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">WHOIS Lookup</a></li>
</ul>
</div>
<div>
<h4 class="font-semibold text-black dark:text-white mb-6">Marketplace</h4>
<ul class="space-y-4">
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Premium Domains</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Brokers</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Auctions</a></li>
</ul>
</div>
<div>
<h4 class="font-semibold text-black dark:text-white mb-6">Branding Tools</h4>
<ul class="space-y-4">
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Name Generator</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">AI Insights</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Logo Kit</a></li>
</ul>
</div>
<div>
<h4 class="font-semibold text-black dark:text-white mb-6">Support</h4>
<ul class="space-y-4">
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Help Center</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">API Access</a></li>
<li><a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity" href="#">Privacy Policy</a></li>
</ul>
</div>
</div>
<div class="border-t border-neutral-200 dark:border-neutral-800 py-8 px-8 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
<p class="text-neutral-500 text-xs">&copy; 2024 WHOIS Architecture. All rights reserved.</p>
<div class="flex gap-6">
<a class="text-neutral-400 hover:text-black transition-colors" href="#"><span class="material-symbols-outlined text-lg">public</span></a>
<a class="text-neutral-400 hover:text-black transition-colors" href="#"><span class="material-symbols-outlined text-lg">mail</span></a>
<a class="text-neutral-400 hover:text-black transition-colors" href="#"><span class="material-symbols-outlined text-lg">shield</span></a>
</div>
</div>
</footer>
<script src="../assets/js/nav-state.js"></script>
</body></html>




