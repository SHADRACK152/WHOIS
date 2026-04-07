<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS AI | Premium Domain Discovery</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "error": "#ba1a1a",
                    "tertiary-container": "#737575",
                    "secondary-fixed-dim": "#acabab",
                    "surface-container-highest": "#e2e2e2",
                    "primary": "#000000",
                    "primary-fixed": "#5e5e5e",
                    "surface-container": "#eeeeee",
                    "on-surface-variant": "#474747",
                    "primary-container": "#3b3b3b",
                    "surface-container-high": "#e8e8e8",
                    "primary-fixed-dim": "#474747",
                    "on-background": "#1a1c1c",
                    "inverse-surface": "#2f3131",
                    "on-tertiary-fixed": "#ffffff",
                    "surface-tint": "#5e5e5e",
                    "on-secondary-fixed-variant": "#3b3b3c",
                    "surface-container-low": "#f3f3f3",
                    "tertiary-fixed-dim": "#454747",
                    "on-surface": "#1a1c1c",
                    "error-container": "#ffdad6",
                    "outline-variant": "#c6c6c6",
                    "on-primary-fixed-variant": "#e2e2e2",
                    "on-error": "#ffffff",
                    "on-secondary": "#ffffff",
                    "tertiary-fixed": "#5d5f5f",
                    "on-secondary-fixed": "#1b1c1c",
                    "outline": "#777777",
                    "on-tertiary-container": "#ffffff",
                    "on-error-container": "#410002",
                    "secondary": "#5e5e5e",
                    "secondary-container": "#d5d4d4",
                    "on-primary-fixed": "#ffffff",
                    "inverse-primary": "#c6c6c6",
                    "on-tertiary-fixed-variant": "#e2e2e2",
                    "inverse-on-surface": "#f1f1f1",
                    "on-secondary-container": "#1b1c1c",
                    "surface-container-lowest": "#ffffff",
                    "surface": "#f9f9f9",
                    "background": "#f9f9f9",
                    "on-primary-container": "#ffffff",
                    "on-tertiary": "#e2e2e2",
                    "surface-bright": "#f9f9f9",
                    "secondary-fixed": "#c7c6c6",
                    "tertiary": "#3a3c3c",
                    "surface-variant": "#e2e2e2",
                    "on-primary": "#e2e2e2",
                    "surface-dim": "#dadada"
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
        .tonal-shift { background-color: #f3f3f3; }
        @media (prefers-color-scheme: dark) {
            .tonal-shift { background-color: #1a1a1a; }
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-32 pb-20 px-8 max-w-7xl mx-auto" data-ai-endpoint="/api/ai.php" data-ai-workflow="domain_name_generator" data-ai-prompt="Generate a set of premium, brandable domain names for Trovalabs using the keywords nexus, trove, orbit, and seek. Keep the names concise, commercially strong, and easy to remember. Include a brief reason for each name.">
<!-- Preferences Header -->
<section class="mb-16">
<div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
<div>
<span class="text-xs font-bold tracking-widest uppercase text-outline mb-2 block">Configuration</span>
<h1 class="text-4xl font-black font-headline tracking-tighter">Your Preferences</h1>
</div>
<div class="flex gap-2">
<button class="p-2 rounded-lg bg-surface-container-low hover:bg-surface-container-high transition-colors">
<span class="material-symbols-outlined">refresh</span>
</button>
<button data-ai-submit="true" class="bg-black text-white px-6 py-2 rounded-xl font-bold flex items-center gap-2">
<span class="material-symbols-outlined text-sm">auto_awesome</span>
                        Generate New
                    </button>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Project Details -->
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-outline">corporate_fare</span>
<button class="text-xs font-bold text-outline hover:text-black">Edit</button>
</div>
<h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-1">Project Details</h3>
<p class="text-xl font-headline font-extrabold">trovalabs</p>
</div>
<!-- Keywords -->
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-outline">key</span>
<button class="text-xs font-bold text-outline hover:text-black">Edit</button>
</div>
<h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-1">Keywords</h3>
<div class="flex flex-wrap gap-2">
<span class="text-sm font-medium bg-surface-container-low px-2 py-0.5 rounded">nexus</span>
<span class="text-sm font-medium bg-surface-container-low px-2 py-0.5 rounded">trove</span>
<span class="text-sm font-medium bg-surface-container-low px-2 py-0.5 rounded">orbit</span>
<span class="text-sm font-medium bg-surface-container-low px-2 py-0.5 rounded">seek</span>
</div>
</div>
<!-- Naming Style -->
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-outline">style</span>
<button class="text-xs font-bold text-outline hover:text-black">Edit</button>
</div>
<h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-1">Naming Style</h3>
<p class="text-xl font-headline font-extrabold">Any Style</p>
</div>
</div>
</section>
<!-- Brand-ready .coms (Horizontal Scroller/List) -->
<section class="mb-20">
<div class="flex items-center gap-4 mb-8">
<h2 class="text-2xl font-black font-headline tracking-tight">Brand-ready .coms</h2>
<span class="bg-black text-white text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter">Premium selection</span>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<!-- Premium Card 1 -->
<div class="group bg-white rounded-2xl p-6 flex items-center justify-between border border-outline-variant/20 hover:border-black transition-all cursor-pointer shadow-sm">
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-black rounded-xl flex items-center justify-center text-white font-headline font-black text-xl">PF</div>
<div>
<p class="text-lg font-headline font-bold">PrismForge.com</p>
<p class="text-sm text-outline">$6,000.00</p>
</div>
</div>
<button class="text-outline group-hover:text-black transition-colors">
<span class="material-symbols-outlined">favorite</span>
</button>
</div>
<!-- Premium Card 2 -->
<div class="group bg-white rounded-2xl p-6 flex items-center justify-between border border-outline-variant/20 hover:border-black transition-all cursor-pointer shadow-sm">
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-surface-container-high rounded-xl flex items-center justify-center text-black font-headline font-black text-xl border border-outline-variant">NV</div>
<div>
<p class="text-lg font-headline font-bold">NovaQuest.com</p>
<p class="text-sm text-outline">$12,500.00</p>
</div>
</div>
<button class="text-outline group-hover:text-black transition-colors">
<span class="material-symbols-outlined">favorite</span>
</button>
</div>
<!-- Premium Card 3 -->
<div class="group bg-white rounded-2xl p-6 flex items-center justify-between border border-outline-variant/20 hover:border-black transition-all cursor-pointer shadow-sm">
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-black rounded-xl flex items-center justify-center text-white font-headline font-black text-xl">VT</div>
<div>
<p class="text-lg font-headline font-bold">VividTrove.com</p>
<p class="text-sm text-outline">$4,200.00</p>
</div>
</div>
<button class="text-outline group-hover:text-black transition-colors">
<span class="material-symbols-outlined">favorite</span>
</button>
</div>
</div>
</section>
<!-- Main Content: Available Domains Grid -->
<section class="mb-20">
<div class="flex justify-between items-center mb-8">
<h2 class="text-2xl font-black font-headline tracking-tight">Available Domains <span class="text-outline font-medium">(27)</span></h2>
<div class="flex gap-4">
<select class="bg-transparent border-none text-sm font-bold focus:ring-0 cursor-pointer">
<option>Sort by: Relevant</option>
<option>Price: Low to High</option>
<option>Alphabetical</option>
</select>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
<!-- Domain Result Card Generator (Representative Sample) -->
<script>
                    const names = ["Trovalis", "Nexusly", "Orbital", "Seekio", "Vivida", "Questor", "Seekly", "Troveen", "Nevalis", "Novanet", "Trovia", "Seekly", "Nexgen", "Vividos", "Orbitis", "Questum"];
                    const tags = ["Nova", "Rune", "Sage", "Blaze", "Premium"];
                </script>
<!-- Repeat logic simulated with static items for reliability -->
<div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/10 hover:border-outline-variant transition-colors group">
<div class="flex justify-between items-start mb-6">
<span class="text-[10px] font-black uppercase tracking-widest text-outline py-1 px-2 bg-surface-container rounded">Nova</span>
<span class="material-symbols-outlined text-outline group-hover:text-black cursor-pointer transition-colors text-sm">favorite</span>
</div>
<h3 class="text-xl font-headline font-extrabold mb-4">Trovalis</h3>
<div class="flex flex-wrap gap-2">
<div class="flex-1 text-center py-2 rounded-lg bg-surface border border-outline-variant/20 hover:border-black transition-all cursor-pointer">
<span class="text-[10px] font-bold block text-outline">.com</span>
<span class="text-xs font-black">$12/y</span>
</div>
<div class="flex-1 text-center py-2 rounded-lg bg-black text-white cursor-pointer">
<span class="text-[10px] font-bold block opacity-70">.ai</span>
<span class="text-xs font-black">$65/y</span>
</div>
<div class="flex-1 text-center py-2 rounded-lg bg-surface border border-outline-variant/20 hover:border-black transition-all cursor-pointer">
<span class="text-[10px] font-bold block text-outline">.io</span>
<span class="text-xs font-black">$38/y</span>
</div>
</div>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/10 hover:border-outline-variant transition-colors group">
<div class="flex justify-between items-start mb-6">
<span class="text-[10px] font-black uppercase tracking-widest text-outline py-1 px-2 bg-surface-container rounded">Sage</span>
<span class="material-symbols-outlined text-outline group-hover:text-black cursor-pointer transition-colors text-sm">favorite</span>
</div>
<h3 class="text-xl font-headline font-extrabold mb-4">Nexusly</h3>
<div class="flex flex-wrap gap-2">
<div class="flex-1 text-center py-2 rounded-lg bg-black text-white cursor-pointer">
<span class="text-[10px] font-bold block opacity-70">.com</span>
<span class="text-xs font-black">$12/y</span>
</div>
<div class="flex-1 text-center py-2 rounded-lg bg-surface border border-outline-variant/20 hover:border-black transition-all cursor-pointer">
<span class="text-[10px] font-bold block text-outline">.ai</span>
<span class="text-xs font-black">$65/y</span>
</div>
<div class="flex-1 text-center py-2 rounded-lg bg-surface border border-outline-variant/20 hover:border-black transition-all cursor-pointer">
<span class="text-[10px] font-bold block text-outline">.io</span>
<span class="text-xs font-black">$38/y</span>
</div>
</div>
</div>
<!-- Repeat multiple times to fill 27 results as requested -->
<!-- Adding more variations manually for visual weight -->
<div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/10 hover:border-outline-variant transition-colors group">
<div class="flex justify-between items-start mb-6">
<span class="text-[10px] font-black uppercase tracking-widest text-outline py-1 px-2 bg-surface-container rounded">Blaze</span>
<span class="material-symbols-outlined text-outline group-hover:text-black cursor-pointer transition-colors text-sm">favorite</span>
</div>
<h3 class="text-xl font-headline font-extrabold mb-4">Vivida</h3>
<div class="flex flex-wrap gap-2">
<div class="flex-1 text-center py-2 rounded-lg bg-surface border border-outline-variant/20 hover:border-black transition-all cursor-pointer">
<span class="text-[10px] font-bold block text-outline">.com</span>
<span class="text-xs font-black">$12/y</span>
</div>
<div class="flex-1 text-center py-2 rounded-lg bg-surface border border-outline-variant/20 hover:border-black transition-all cursor-pointer">
<span class="text-[10px] font-bold block text-outline">.ai</span>
<span class="text-xs font-black">$65/y</span>
</div>
<div class="flex-1 text-center py-2 rounded-lg bg-black text-white cursor-pointer">
<span class="text-[10px] font-bold block opacity-70">.io</span>
<span class="text-xs font-black">$38/y</span>
</div>
</div>
</div>
<div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/10 hover:border-outline-variant transition-colors group">
<div class="flex justify-between items-start mb-6">
<span class="text-[10px] font-black uppercase tracking-widest text-outline py-1 px-2 bg-surface-container rounded">Premium</span>
<span class="material-symbols-outlined text-outline group-hover:text-black cursor-pointer transition-colors text-sm">favorite</span>
</div>
<h3 class="text-xl font-headline font-extrabold mb-4">Questor</h3>
<div class="flex flex-wrap gap-2">
<div class="flex-1 text-center py-2 rounded-lg bg-black text-white cursor-pointer">
<span class="text-[10px] font-bold block opacity-70">.com</span>
<span class="text-xs font-black">$12/y</span>
</div>
<div class="flex-1 text-center py-2 rounded-lg bg-surface border border-outline-variant/20 hover:border-black transition-all cursor-pointer">
<span class="text-[10px] font-bold block text-outline">.ai</span>
<span class="text-xs font-black">$65/y</span>
</div>
<div class="flex-1 text-center py-2 rounded-lg bg-surface border border-outline-variant/20 hover:border-black transition-all cursor-pointer">
<span class="text-[10px] font-bold block text-outline">.io</span>
<span class="text-xs font-black">$38/y</span>
</div>
</div>
</div>
<!-- Placeholder cards to imply the dense grid -->
<div class="hidden lg:block bg-surface-container-low/30 p-5 rounded-2xl border border-dashed border-outline-variant h-44"></div>
<div class="hidden lg:block bg-surface-container-low/30 p-5 rounded-2xl border border-dashed border-outline-variant h-44"></div>
<div class="hidden lg:block bg-surface-container-low/30 p-5 rounded-2xl border border-dashed border-outline-variant h-44"></div>
<div class="hidden lg:block bg-surface-container-low/30 p-5 rounded-2xl border border-dashed border-outline-variant h-44"></div>
</div>
<div class="mt-12 text-center">
<button class="bg-surface-container text-black font-bold px-12 py-3 rounded-full hover:bg-surface-container-high transition-colors">Load More Suggestions</button>
</div>
</section>
<!-- Educational Section: 3 Easy Steps -->
<section class="mb-32">
<h2 class="text-3xl font-black font-headline tracking-tighter text-center mb-16">Find Your Perfect Domain Name in 3 Easy Steps</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
<div class="text-center px-4">
<div class="w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl">
<span class="material-symbols-outlined text-3xl">lightbulb</span>
</div>
<h3 class="text-xl font-bold mb-3">Define Core Identity</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Input your project values and keywords to feed our neural architecture the DNA of your brand.</p>
</div>
<div class="text-center px-4">
<div class="w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl">
<span class="material-symbols-outlined text-3xl">psychology</span>
</div>
<h3 class="text-xl font-bold mb-3">AI Synthesis</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Our AI scans millions of linguistic patterns and availability databases to generate phonetically balanced options.</p>
</div>
<div class="text-center px-4">
<div class="w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl">
<span class="material-symbols-outlined text-3xl">verified</span>
</div>
<h3 class="text-xl font-bold mb-3">Secure &amp; Launch</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Select your preferred extension and complete registration in seconds with our one-click checkout.</p>
</div>
</div>
</section>
<!-- Educational Section: Checklist -->
<section class="mb-32 bg-white rounded-[2rem] p-12 border border-outline-variant/10 shadow-sm">
<div class="max-w-4xl mx-auto">
<div class="mb-10 text-center">
<h2 class="text-3xl font-black font-headline tracking-tight mb-4">Domain Naming Checklist</h2>
<p class="text-outline">The architectural blueprint for a world-class digital identity.</p>
</div>
<div class="overflow-hidden">
<table class="w-full text-left">
<thead>
<tr class="border-b border-outline-variant">
<th class="py-4 font-black uppercase text-[10px] tracking-widest text-outline">Category</th>
<th class="py-4 font-black uppercase text-[10px] tracking-widest text-outline">Evaluation Criteria</th>
<th class="py-4 font-black uppercase text-[10px] tracking-widest text-outline text-right">Impact Score</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/20">
<tr>
<td class="py-6 font-bold text-sm">Visual Clarity</td>
<td class="py-6 text-sm text-on-surface-variant">Does the name read clearly in lower-case URLs without unintended words?</td>
<td class="py-6 text-right">
<div class="flex justify-end gap-1">
<div class="w-6 h-1.5 bg-black rounded-full"></div>
<div class="w-6 h-1.5 bg-black rounded-full"></div>
</div>
</td>
</tr>
<tr>
<td class="py-6 font-bold text-sm">Brevity</td>
<td class="py-6 text-sm text-on-surface-variant">Is the name under 12 characters and easy to type on mobile devices?</td>
<td class="py-6 text-right">
<div class="flex justify-end gap-1">
<div class="w-6 h-1.5 bg-black rounded-full"></div>
<div class="w-6 h-1.5 bg-surface-container rounded-full"></div>
</div>
</td>
</tr>
<tr>
<td class="py-6 font-bold text-sm">Extension Trust</td>
<td class="py-6 text-sm text-on-surface-variant">Does the chosen TLD (.com, .ai) align with industry expectations?</td>
<td class="py-6 text-right">
<div class="flex justify-end gap-1">
<div class="w-6 h-1.5 bg-black rounded-full"></div>
<div class="w-6 h-1.5 bg-black rounded-full"></div>
</div>
</td>
</tr>
<tr>
<td class="py-6 font-bold text-sm">Pronunciation</td>
<td class="py-6 text-sm text-on-surface-variant">Is it "radio-ready"? Can it be understood easily when spoken aloud?</td>
<td class="py-6 text-right">
<div class="flex justify-end gap-1">
<div class="w-6 h-1.5 bg-black rounded-full"></div>
<div class="w-6 h-1.5 bg-surface-container rounded-full"></div>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</section>
<!-- FAQ Section -->
<section class="mb-32">
<h2 class="text-3xl font-black font-headline tracking-tighter mb-12">Common Inquiries</h2>
<div class="space-y-4">
<div class="bg-white rounded-xl p-6 border border-outline-variant/10 shadow-sm">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold text-lg">How does the AI generator work?</span>
<span class="material-symbols-outlined text-outline">expand_more</span>
</button>
</div>
<div class="bg-white rounded-xl p-6 border border-outline-variant/10 shadow-sm">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold text-lg">Are these domains available for immediate purchase?</span>
<span class="material-symbols-outlined text-outline">expand_more</span>
</button>
</div>
<div class="bg-white rounded-xl p-6 border border-outline-variant/10 shadow-sm">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold text-lg">Can I generate names for a specific industry?</span>
<span class="material-symbols-outlined text-outline">expand_more</span>
</button>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="w-full border-t border-[#E5E5E5] dark:border-[#262626] mt-20 bg-white dark:bg-black">
<div class="max-w-7xl mx-auto px-8 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
<div class="col-span-2 md:col-span-1">
<div class="text-lg font-black text-black dark:text-white mb-4">WHOIS AI</div>
<p class="font-['Inter'] text-xs uppercase tracking-widest text-[#737373] max-w-xs leading-relaxed">
                    &copy; 2024 WHOIS AI. Architecting Digital Identities.
                </p>
</div>
<div>
<h4 class="font-bold mb-6 text-sm uppercase tracking-widest">Platform</h4>
<ul class="space-y-4 font-['Inter'] text-xs uppercase tracking-widest text-[#737373]">
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">Marketplace</a></li>
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">Extensions</a></li>
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">Pricing</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-6 text-sm uppercase tracking-widest">Legal</h4>
<ul class="space-y-4 font-['Inter'] text-xs uppercase tracking-widest text-[#737373]">
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">Privacy Policy</a></li>
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">Terms of Service</a></li>
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">API Documentation</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-6 text-sm uppercase tracking-widest">Connect</h4>
<ul class="space-y-4 font-['Inter'] text-xs uppercase tracking-widest text-[#737373]">
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">Twitter</a></li>
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">LinkedIn</a></li>
<li><a class="hover:text-black dark:hover:text-white transition-colors" href="#">Support</a></li>
</ul>
</div>
</div>
</footer>
<script src="../assets/js/ai-workflows.js"></script>
<script src="../assets/js/ai-workflows.js"></script>
<script src="../assets/js/nav-state.js"></script>
</body></html>




