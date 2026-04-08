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
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#000000",
                        "on-primary": "#ffffff",
                        "background": "#ffffff",
                        "surface": "#ffffff",
                        "surface-container": "#f9f9f9",
                        "outline": "#e5e5e5",
                        "on-surface": "#000000",
                        "on-surface-variant": "#666666"
                    },
                    "borderRadius": {
                        "DEFAULT": "8px",
                        "lg": "12px",
                        "xl": "16px",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope", "sans-serif"],
                        "body": ["Manrope", "sans-serif"],
                        "label": ["Manrope", "sans-serif"]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        .soft-shadow {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }
        .sidebar-scroll::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-background font-body text-on-surface antialiased">
<!-- Navigation Header -->
<?php require __DIR__ . '/_top_nav.php'; ?>
<div class="flex pt-16 min-h-screen max-w-[1600px] mx-auto">
<!-- Left Sidebar: Filters -->
<aside class="hidden xl:flex h-[calc(100vh-64px)] w-72 border-r border-outline flex-col p-8 gap-8 sticky top-16 sidebar-scroll overflow-y-auto">
<div>
<h2 class="text-xs font-extrabold font-headline uppercase tracking-widest text-black mb-6">Search Tools</h2>
<?php require __DIR__ . '/_top_nav.php'; ?>
</div>
<div class="pt-8 border-t border-outline">
<h3 class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-on-surface-variant mb-4">Support</h3>
<div class="space-y-4">
<div class="flex items-center gap-3 text-on-surface-variant hover:text-black cursor-pointer transition-colors">
<span class="material-symbols-outlined text-lg">help_center</span>
<span class="text-xs font-semibold">Help Center</span>
</div>
<div class="flex items-center gap-3 text-on-surface-variant hover:text-black cursor-pointer transition-colors">
<span class="material-symbols-outlined text-lg">chat_bubble</span>
<span class="text-xs font-semibold">Contact Agent</span>
</div>
</div>
</div>
</aside>
<!-- Main Content Area -->
<main class="flex-1 px-8 lg:px-12 py-10 overflow-y-auto">
<!-- Search Bar Area -->
<div class="max-w-4xl mx-auto mb-12">
<div class="relative group">
<span class="absolute left-6 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-2xl">search</span>
<input class="w-full pl-16 pr-32 py-5 bg-white border-2 border-black rounded-2xl text-xl font-extrabold focus:outline-none focus:ring-4 focus:ring-black/5 transition-all shadow-xl shadow-black/5" type="text" value="yourbrand"/>
<button class="absolute right-3 top-1/2 -translate-y-1/2 bg-black text-white px-8 py-3 rounded-xl font-extrabold text-sm hover:opacity-90 active:scale-95 transition-all">Search</button>
</div>
<div class="flex items-center justify-between mt-4 px-2">
<p class="text-sm text-on-surface-variant font-medium">142 domain variations found for "yourbrand"</p>
<div class="flex gap-4">
<span class="text-[10px] font-extrabold uppercase tracking-widest text-black flex items-center gap-1.5">
<span class="material-symbols-outlined text-xs">verified</span> AI Verified
                        </span>
</div>
</div>
</div>
<!-- Featured Result -->
<div class="max-w-4xl mx-auto mb-16">
<div class="bg-white p-10 rounded-[32px] border border-outline soft-shadow flex flex-col md:flex-row items-center justify-between gap-8 group hover:border-black transition-colors">
<div class="flex-1">
<div class="flex items-center gap-3 mb-3">
<span class="px-3 py-1 bg-black text-white text-[10px] font-extrabold uppercase tracking-tighter rounded-full">Available</span>
<span class="text-xs text-on-surface-variant font-semibold">Exact Match</span>
</div>
<h2 class="text-5xl font-black font-headline tracking-tighter text-black mb-2">yourbrand.com</h2>
<p class="text-on-surface-variant font-medium max-w-sm">The premier digital destination for your identity.</p>
</div>
<div class="flex flex-col items-end gap-6 min-w-[200px]">
<div class="text-right">
<p class="text-4xl font-extrabold font-headline">$12.99</p>
<p class="text-[10px] text-on-surface-variant font-bold uppercase tracking-widest">Yearly registration</p>
</div>
<button class="w-full bg-black text-white py-4 px-10 rounded-2xl font-extrabold text-base hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-black/10">Add to Cart</button>
</div>
</div>
</div>
<!-- Alternatives Grid -->
<div class="max-w-4xl mx-auto mb-16">
<div class="flex items-center justify-between mb-8">
<h3 class="text-xs font-black uppercase tracking-[0.25em] text-black">Top Extensions</h3>
<div class="h-px flex-1 bg-outline mx-6"></div>
<button class="text-xs font-bold hover:underline transition-all">View All</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<!-- Alternative 1 -->
<div class="bg-surface-container p-6 rounded-2xl border border-transparent hover:border-black hover:bg-white transition-all group flex items-center justify-between">
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-outline">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-black">language</span>
</div>
<div>
<h4 class="font-extrabold text-lg">yourbrand.net</h4>
<p class="text-[10px] text-on-surface-variant font-bold uppercase">Renewal: $14.99</p>
</div>
</div>
<div class="flex flex-col items-end">
<span class="text-lg font-extrabold">$9.99</span>
<button class="mt-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover:text-black flex items-center gap-1">
<span class="material-symbols-outlined text-sm">add_circle</span> Add
                            </button>
</div>
</div>
<!-- Alternative 2 -->
<div class="bg-surface-container p-6 rounded-2xl border border-transparent hover:border-black hover:bg-white transition-all group flex items-center justify-between">
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-outline">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-black">smart_toy</span>
</div>
<div>
<h4 class="font-extrabold text-lg">yourbrand.ai</h4>
<p class="text-[10px] text-on-surface-variant font-bold uppercase">Renewal: $89.00</p>
</div>
</div>
<div class="flex flex-col items-end">
<span class="text-lg font-extrabold">$75.00</span>
<button class="mt-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover:text-black flex items-center gap-1">
<span class="material-symbols-outlined text-sm">add_circle</span> Add
                            </button>
</div>
</div>
<!-- Alternative 3 -->
<div class="bg-surface-container p-6 rounded-2xl border border-transparent hover:border-black hover:bg-white transition-all group flex items-center justify-between">
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-outline">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-black">apps</span>
</div>
<div>
<h4 class="font-extrabold text-lg">yourbrand.app</h4>
<p class="text-[10px] text-on-surface-variant font-bold uppercase">Renewal: $19.99</p>
</div>
</div>
<div class="flex flex-col items-end">
<span class="text-lg font-extrabold">$14.00</span>
<button class="mt-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover:text-black flex items-center gap-1">
<span class="material-symbols-outlined text-sm">add_circle</span> Add
                            </button>
</div>
</div>
<!-- Alternative 4 -->
<div class="bg-surface-container p-6 rounded-2xl border border-transparent hover:border-black hover:bg-white transition-all group flex items-center justify-between">
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-outline">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-black">token</span>
</div>
<div>
<h4 class="font-extrabold text-lg">yourbrand.io</h4>
<p class="text-[10px] text-on-surface-variant font-bold uppercase">Renewal: $45.00</p>
</div>
</div>
<div class="flex flex-col items-end">
<span class="text-lg font-extrabold">$32.00</span>
<button class="mt-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover:text-black flex items-center gap-1">
<span class="material-symbols-outlined text-sm">add_circle</span> Add
                            </button>
</div>
</div>
</div>
</div>
<!-- AI Suggestions Block -->
<div class="max-w-4xl mx-auto mb-16 bg-surface-container rounded-3xl p-10 border border-outline">
<div class="flex items-center gap-2 mb-8">
<span class="material-symbols-outlined text-black font-variation-settings-fill-1">auto_awesome</span>
<h3 class="text-sm font-black uppercase tracking-widest">Smart Variations</h3>
</div>
<div class="flex flex-wrap gap-3">
<button class="px-6 py-3 bg-white border border-outline rounded-full text-sm font-bold hover:border-black transition-all flex items-center gap-2">
                        getyourbrand.com <span class="material-symbols-outlined text-xs">bolt</span>
</button>
<button class="px-6 py-3 bg-white border border-outline rounded-full text-sm font-bold hover:border-black transition-all flex items-center gap-2">
                        yourbrandhq.io <span class="material-symbols-outlined text-xs">bolt</span>
</button>
<button class="px-6 py-3 bg-white border border-outline rounded-full text-sm font-bold hover:border-black transition-all flex items-center gap-2">
                        theyourbrand.co <span class="material-symbols-outlined text-xs">bolt</span>
</button>
<button class="px-6 py-3 bg-white border border-outline rounded-full text-sm font-bold hover:border-black transition-all flex items-center gap-2">
                        yourbrandapp.ai <span class="material-symbols-outlined text-xs">bolt</span>
</button>
</div>
</div>
<!-- Premium Marketplace -->
<div class="max-w-4xl mx-auto">
<div class="flex items-center justify-between mb-8">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-black font-variation-settings-fill-1">workspace_premium</span>
<h3 class="text-xl font-extrabold font-headline tracking-tighter">Premium Market</h3>
</div>
<a class="text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:text-black" href="#">View High-Value Assets</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="bg-white p-8 rounded-2xl border border-outline soft-shadow hover:border-black transition-all group">
<div class="flex justify-between items-start mb-12">
<span class="material-symbols-outlined text-black font-variation-settings-fill-1">diamond</span>
<span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Curated</span>
</div>
<h4 class="text-3xl font-black font-headline text-black mb-4">brand.com</h4>
<div class="flex items-end justify-between">
<div>
<p class="text-xs text-on-surface-variant font-bold uppercase tracking-tighter">Buy Now</p>
<p class="text-2xl font-extrabold">$45,000</p>
</div>
<button class="bg-black text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:opacity-90 active:scale-95 transition-all">Inquire</button>
</div>
</div>
<div class="bg-white p-8 rounded-2xl border border-outline soft-shadow hover:border-black transition-all group">
<div class="flex justify-between items-start mb-12">
<span class="material-symbols-outlined text-black font-variation-settings-fill-1">crown</span>
<span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Ultra Premium</span>
</div>
<h4 class="text-3xl font-black font-headline text-black mb-4">your.ai</h4>
<div class="flex items-end justify-between">
<div>
<p class="text-xs text-on-surface-variant font-bold uppercase tracking-tighter">Asking Price</p>
<p class="text-2xl font-extrabold">$12,500</p>
</div>
<button class="bg-black text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:opacity-90 active:scale-95 transition-all">Inquire</button>
</div>
</div>
</div>
</div>
</main>
<!-- Right Selection Sidebar (Cart) -->
<aside class="hidden lg:flex w-80 p-8 flex-col gap-6 sticky top-16 h-[calc(100vh-64px)] overflow-y-auto border-l border-outline bg-surface-container/30">
<div class="bg-white rounded-3xl border border-outline p-8 soft-shadow flex flex-col h-full">
<div class="flex items-center gap-3 mb-8 pb-4 border-b border-outline">
<span class="material-symbols-outlined text-black">shopping_cart</span>
<h2 class="text-xs font-black uppercase tracking-widest">Your Selection</h2>
</div>
<div class="flex-1 space-y-6">
<!-- Item -->
<div class="group">
<div class="flex justify-between items-start mb-1">
<p class="text-sm font-extrabold">yourbrand.com</p>
<p class="text-sm font-extrabold">$12.99</p>
</div>
<div class="flex justify-between items-center">
<p class="text-[10px] text-on-surface-variant font-bold uppercase tracking-tighter">1 Year Registration</p>
<button class="text-[10px] text-on-surface-variant hover:text-black font-black uppercase tracking-widest transition-colors">Remove</button>
</div>
</div>
</div>
<div class="mt-auto pt-8">
<div class="flex justify-between items-center mb-6">
<p class="text-xs text-on-surface-variant font-bold uppercase">Estimated Total</p>
<p class="text-2xl font-black font-headline tracking-tighter">$12.99</p>
</div>
<button class="w-full bg-black text-white py-4 rounded-2xl font-extrabold text-sm hover:opacity-90 transition-all shadow-xl shadow-black/10 active:scale-95">Checkout Now</button>
<p class="text-[10px] text-center text-on-surface-variant mt-6 px-4 font-semibold italic">Taxes and registration fees calculated at final step</p>
</div>
</div>
</aside>
</div>
<!-- Simple Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body></html>




