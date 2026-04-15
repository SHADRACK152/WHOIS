<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | Search Results</title>
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
                        "tertiary": "#3a3c3c",
                        "primary-fixed-dim": "#474747",
                        "surface-tint": "#5e5e5e",
                        "on-secondary": "#ffffff",
                        "outline": "#777777",
                        "on-surface": "#1a1c1c",
                        "on-primary-container": "#ffffff",
                        "on-error": "#ffffff",
                        "primary-container": "#3b3b3b",
                        "surface-dim": "#dadada",
                        "on-error-container": "#410002",
                        "secondary": "#5e5e5e",
                        "on-tertiary-container": "#ffffff",
                        "tertiary-fixed-dim": "#454747",
                        "on-tertiary": "#e2e2e2",
                        "on-primary-fixed": "#ffffff",
                        "tertiary-fixed": "#5d5f5f",
                        "inverse-surface": "#2f3131",
                        "error-container": "#ffdad6",
                        "surface": "#f9f9f9",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "secondary-fixed-dim": "#acabab",
                        "primary": "#000000",
                        "on-surface-variant": "#474747",
                        "surface-container": "#eeeeee",
                        "on-primary": "#e2e2e2",
                        "on-background": "#1a1c1c",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "inverse-on-surface": "#f1f1f1",
                        "surface-bright": "#f9f9f9",
                        "secondary-fixed": "#c7c6c6",
                        "secondary-container": "#d5d4d4",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "inverse-primary": "#c6c6c6",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f3f3",
                        "surface-container-high": "#e8e8e8",
                        "on-tertiary-fixed": "#ffffff",
                        "on-secondary-container": "#1b1c1c",
                        "surface-variant": "#e2e2e2",
                        "on-secondary-fixed": "#1b1c1c",
                        "background": "#f9f9f9",
                        "tertiary-container": "#737575",
                        "surface-container-highest": "#e2e2e2",
                        "error": "#ba1a1a",
                        "outline-variant": "#c6c6c6",
                        "primary-fixed": "#5e5e5e"
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
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-surface font-body text-on-surface">
<?php require __DIR__ . '/_top_nav.php'; ?>
<div class="flex pt-16 min-h-screen">
<!-- SideNavBar (Search Tools) -->
<aside class="hidden md:flex h-[calc(100vh-64px)] w-64 border-r border-neutral-100 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 flex-col p-4 gap-2 sticky top-16">
<div class="mb-6 px-2">
<h2 class="text-sm font-black font-headline tracking-tight uppercase text-neutral-900">Search Tools</h2>
<p class="text-xs text-neutral-500">Refine your identity</p>
</div>
<?php require __DIR__ . '/_top_nav.php'; ?>
<div class="mt-auto pt-4 border-t border-neutral-200/50 space-y-1">
<div class="text-neutral-400 dark:text-neutral-500 hover:bg-neutral-100 dark:hover:bg-neutral-900 flex items-center gap-3 px-3 py-2 cursor-pointer transition-transform duration-200">
<span class="material-symbols-outlined text-[20px]">help_outline</span>
<span class="text-xs font-inter uppercase tracking-widest">Support</span>
</div>
<div class="text-neutral-400 dark:text-neutral-500 hover:bg-neutral-100 dark:hover:bg-neutral-900 flex items-center gap-3 px-3 py-2 cursor-pointer transition-transform duration-200">
<span class="material-symbols-outlined text-[20px]">settings</span>
<span class="text-xs font-inter uppercase tracking-widest">Settings</span>
</div>
</div>
</aside>
<!-- Main Content Canvas -->
<main class="flex-1 px-8 py-12 max-w-5xl mx-auto overflow-y-auto">
<!-- Header Section -->
<header class="mb-12">
<div class="flex items-baseline gap-3 mb-2">
<h1 class="text-3xl font-black font-headline tracking-tighter text-primary">Results for yourbrand</h1>
<span class="text-sm text-neutral-500 font-medium">142 variations found</span>
</div>
<div class="flex gap-2">
<span class="px-3 py-1 bg-surface-container-high rounded-full text-[10px] font-bold uppercase tracking-wider text-neutral-600">AI Verified</span>
<span class="px-3 py-1 bg-surface-container-high rounded-full text-[10px] font-bold uppercase tracking-wider text-neutral-600">Fast Registration</span>
</div>
</header>
<!-- PRIMARY RESULT: The Hero Card -->
<section class="mb-12">
<div class="bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/30 shadow-xl shadow-black/[0.02] flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
<div>
<div class="flex items-center gap-2 mb-1">
<span class="px-2 py-0.5 bg-primary text-on-primary text-[10px] font-black uppercase rounded">Available</span>
<span class="text-xs text-neutral-400 font-medium">exact match</span>
</div>
<h2 class="text-4xl font-black font-headline tracking-tighter text-primary">yourbrand.com</h2>
<p class="text-sm text-neutral-500 mt-1">The gold standard for your digital identity.</p>
</div>
<div class="text-right">
<div class="mb-4">
<p class="text-3xl font-bold font-headline">$12.99</p>
<p class="text-[10px] text-neutral-400 uppercase tracking-widest">per year</p>
</div>
<button class="bg-primary text-on-primary px-8 py-3 rounded-xl font-bold text-sm hover:bg-neutral-800 transition-colors shadow-lg shadow-black/10">Add to Cart</button>
</div>
</div>
</section>
<!-- AI SUGGESTIONS SECTION -->
<section class="mb-12">
<div class="flex items-center gap-2 mb-6">
<span class="material-symbols-outlined text-primary text-lg">auto_awesome</span>
<h3 class="text-xs font-bold uppercase tracking-widest text-neutral-400">Smart Recommendations</h3>
</div>
<div class="flex flex-wrap gap-3">
<div class="px-4 py-2 bg-white border border-outline-variant/30 rounded-full text-xs font-semibold text-primary hover:border-primary cursor-pointer transition-colors flex items-center gap-2">
<span>getyourbrand.io</span>
<span class="material-symbols-outlined text-sm">colors_spark</span>
</div>
<div class="px-4 py-2 bg-white border border-outline-variant/30 rounded-full text-xs font-semibold text-primary hover:border-primary cursor-pointer transition-colors flex items-center gap-2">
<span>yourbrand.app</span>
<span class="material-symbols-outlined text-sm">colors_spark</span>
</div>
<div class="px-4 py-2 bg-white border border-outline-variant/30 rounded-full text-xs font-semibold text-primary hover:border-primary cursor-pointer transition-colors flex items-center gap-2">
<span>theyourbrand.com</span>
<span class="material-symbols-outlined text-sm">colors_spark</span>
</div>
<div class="px-4 py-2 bg-white border border-outline-variant/30 rounded-full text-xs font-semibold text-primary hover:border-primary cursor-pointer transition-colors flex items-center gap-2">
<span>yourbrand.xyz</span>
<span class="material-symbols-outlined text-sm">colors_spark</span>
</div>
</div>
</section>
<!-- ALTERNATIVE RESULTS -->
<section class="mb-12">
<h3 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-6">Alternative Extensions</h3>
<div class="space-y-4">
<!-- Row 1 -->
<div class="bg-surface-container-low/50 hover:bg-surface-container-lowest p-5 rounded-xl border border-transparent hover:border-outline-variant/30 transition-all flex items-center justify-between group">
<div class="flex items-center gap-6">
<div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-outline-variant/20 shadow-sm">
<span class="material-symbols-outlined text-neutral-400">language</span>
</div>
<div>
<h4 class="font-bold text-neutral-900">yourbrand.net</h4>
<p class="text-[10px] text-neutral-400 uppercase font-medium">Renewal: $14.99/yr</p>
</div>
</div>
<div class="flex items-center gap-6">
<span class="text-xs font-semibold text-neutral-600 px-3 py-1 bg-surface-container-high rounded-full">Available</span>
<span class="text-sm font-bold">$9.99</span>
<button class="bg-primary text-on-primary w-10 h-10 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
<span class="material-symbols-outlined">add_shopping_cart</span>
</button>
</div>
</div>
<!-- Row 2 -->
<div class="bg-surface-container-low/50 hover:bg-surface-container-lowest p-5 rounded-xl border border-transparent hover:border-outline-variant/30 transition-all flex items-center justify-between group">
<div class="flex items-center gap-6">
<div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-outline-variant/20 shadow-sm">
<span class="material-symbols-outlined text-neutral-400">code</span>
</div>
<div>
<h4 class="font-bold text-neutral-900">yourbrand.ai</h4>
<p class="text-[10px] text-neutral-400 uppercase font-medium">Renewal: $89.00/yr</p>
</div>
</div>
<div class="flex items-center gap-6">
<span class="text-xs font-semibold text-neutral-600 px-3 py-1 bg-surface-container-high rounded-full">Available</span>
<span class="text-sm font-bold">$75.00</span>
<button class="bg-primary text-on-primary w-10 h-10 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
<span class="material-symbols-outlined">add_shopping_cart</span>
</button>
</div>
</div>
<!-- Row 3 -->
<div class="bg-surface-container-low/50 hover:bg-surface-container-lowest p-5 rounded-xl border border-transparent hover:border-outline-variant/30 transition-all flex items-center justify-between group">
<div class="flex items-center gap-6">
<div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-outline-variant/20 shadow-sm">
<span class="material-symbols-outlined text-neutral-400">phone_iphone</span>
</div>
<div>
<h4 class="font-bold text-neutral-900">yourbrand.app</h4>
<p class="text-[10px] text-neutral-400 uppercase font-medium">Renewal: $19.99/yr</p>
</div>
</div>
<div class="flex items-center gap-6">
<span class="text-xs font-semibold text-neutral-600 px-3 py-1 bg-surface-container-high rounded-full">Available</span>
<span class="text-sm font-bold">$14.00</span>
<button class="bg-primary text-on-primary w-10 h-10 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
<span class="material-symbols-outlined">add_shopping_cart</span>
</button>
</div>
</div>
</div>
</section>
<!-- PREMIUM MARKET SECTION -->
<section class="mb-12">
<div class="flex items-center justify-between mb-8">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
<h3 class="text-xl font-black font-headline tracking-tight text-primary">Premium Market</h3>
</div>
<a class="text-xs font-bold uppercase tracking-widest text-neutral-400 hover:text-primary transition-colors" href="#">View All High-Value</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="bg-white p-6 rounded-xl border border-outline-variant/20 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
<div class="flex justify-between items-start mb-6">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">diamond</span>
<span class="text-[10px] font-bold text-neutral-400 uppercase tracking-tighter">Certified Premium</span>
</div>
<h4 class="text-2xl font-black font-headline text-primary mb-2">brand.com</h4>
<div class="flex items-end justify-between">
<div>
<p class="text-xs text-neutral-400 font-medium">Listed Price</p>
<p class="text-2xl font-bold tracking-tight">$45,000</p>
</div>
<button class="bg-primary text-on-primary px-4 py-2 rounded-lg text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">Inquire</button>
</div>
</div>
<div class="bg-white p-6 rounded-xl border border-outline-variant/20 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
<div class="flex justify-between items-start mb-6">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">crown</span>
<span class="text-[10px] font-bold text-neutral-400 uppercase tracking-tighter">Curated Identity</span>
</div>
<h4 class="text-2xl font-black font-headline text-primary mb-2">your.ai</h4>
<div class="flex items-end justify-between">
<div>
<p class="text-xs text-neutral-400 font-medium">Listed Price</p>
<p class="text-2xl font-bold tracking-tight">$12,500</p>
</div>
<button class="bg-primary text-on-primary px-4 py-2 rounded-lg text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">Inquire</button>
</div>
</div>
</div>
</section>
</main>
<!-- Right Floating Sidebar (Cart) -->
<aside class="hidden lg:block w-80 p-6 sticky top-16 h-[calc(100vh-64px)] overflow-y-auto">
<div class="bg-white rounded-xl border border-outline-variant/30 p-6 shadow-2xl shadow-black/[0.03] flex flex-col h-full">
<div class="flex items-center gap-2 mb-8">
<span class="material-symbols-outlined text-primary">shopping_cart</span>
<h2 class="text-sm font-black font-headline tracking-tight uppercase">Your Selection</h2>
</div>
<div class="flex-1 space-y-6">
<!-- Cart Item -->
<div class="flex justify-between items-start">
<div>
<p class="text-xs font-bold text-primary">yourbrand.com</p>
<p class="text-[10px] text-neutral-400">1 Year Registration</p>
</div>
<div class="text-right">
<p class="text-xs font-bold">$12.99</p>
<button class="text-[10px] text-neutral-400 hover:text-error transition-colors uppercase font-bold mt-1">Remove</button>
</div>
</div>
</div>
<div class="mt-auto pt-6 border-t border-neutral-100">
<div class="flex justify-between items-center mb-6">
<p class="text-xs text-neutral-500 font-medium">Total Price</p>
<p class="text-xl font-black font-headline tracking-tighter text-primary">$12.99</p>
</div>
<button class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity shadow-lg shadow-black/10">Checkout Now</button>
<p class="text-[10px] text-center text-neutral-400 mt-4 px-4 font-medium">Taxes and ICANN fees calculated at checkout</p>
</div>
</div>
</aside>
</div>
<!-- Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body></html>




