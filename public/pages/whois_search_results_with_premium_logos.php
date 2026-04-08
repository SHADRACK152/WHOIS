<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS Domain Search - ARCHITECT.AI</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
        }
        h1, h2, h3, .headline {
            font-family: 'Manrope', sans-serif;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-dim": "#dadada",
                        "secondary-container": "#d5d4d4",
                        "tertiary-fixed-dim": "#454747",
                        "on-secondary-fixed": "#1b1c1c",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-surface-variant": "#474747",
                        "surface-bright": "#f9f9f9",
                        "on-tertiary-fixed": "#ffffff",
                        "on-secondary": "#ffffff",
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
                        "primary-container": "#3b3b3b",
                        "primary-fixed": "#5e5e5e",
                        "outline": "#777777",
                        "on-secondary-container": "#1b1c1c",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "surface-variant": "#e2e2e2",
                        "on-primary-fixed": "#ffffff",
                        "outline-variant": "#c6c6c6",
                        "secondary-fixed-dim": "#acabab",
                        "tertiary": "#3a3c3c",
                        "surface-tint": "#5e5e5e",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-error": "#ffffff",
                        "inverse-on-surface": "#f1f1f1",
                        "surface-container": "#eeeeee",
                        "tertiary-fixed": "#5d5f5f",
                        "error-container": "#ffdad6",
                        "primary-fixed-dim": "#474747",
                        "on-tertiary": "#e2e2e2",
                        "surface-container-highest": "#e2e2e2",
                        "surface": "#f9f9f9",
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
                }
            }
        }
    </script>
</head>
<body class="bg-surface text-on-surface">
<?php require __DIR__ . '/_top_nav.php'; ?>
<!-- Hero Search Section -->
<header class="bg-surface-container-low pt-16 pb-20 px-8">
<div class="max-w-[1440px] mx-auto text-center">
<h1 class="text-5xl font-extrabold tracking-tighter text-primary mb-4 leading-tight">Register Your Perfect Domain</h1>
<p class="text-on-surface-variant max-w-2xl mx-auto mb-10 text-lg">Search and buy your domain in minutes. Atom is an award-winning, ICANN-accredited domain registrar providing secure infrastructure for your digital identity.</p>
<div class="max-w-3xl mx-auto mb-8">
<div class="relative group">
<input class="w-full h-16 pl-6 pr-40 rounded-full border-outline-variant bg-surface-container-lowest text-xl font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all shadow-sm group-hover:shadow-md" type="text" value="trovalabs.com"/>
<button class="absolute right-2 top-2 bottom-2 px-10 rounded-full bg-primary text-on-primary font-bold hover:bg-primary-container transition-colors">Search</button>
</div>
</div>
<div class="flex flex-wrap justify-center gap-8 items-center">
<div class="flex items-center gap-2">
<span class="font-bold text-primary">.com</span>
<span class="text-on-surface-variant">- only $11.30/yr</span>
</div>
<div class="flex items-center gap-3">
<span class="font-bold text-primary">.ai</span>
<div class="flex items-center gap-2">
<span class="text-on-surface-variant line-through">$94.62</span>
<span class="font-bold text-primary">$78.88/yr</span>
<span class="bg-surface-container-highest px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-tighter">Promo</span>
</div>
</div>
</div>
</div>
</header>
<main class="max-w-[1440px] mx-auto px-8 py-12">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
<!-- LEFT COLUMN: Status & Brokerage -->
<aside class="lg:col-span-3 space-y-8">
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30">
<div class="flex justify-between items-start mb-4">
<h2 class="text-2xl font-extrabold tracking-tighter">trovalabs.com</h2>
<span class="bg-surface-container text-secondary text-[10px] font-bold px-2 py-1 rounded uppercase">Registered</span>
</div>
<p class="text-sm text-on-surface-variant mb-6 leading-relaxed">This domain is currently unavailable for direct registration. Choose a strong alternative from our list below.</p>
<div class="h-1 bg-surface-variant w-full overflow-hidden rounded-full">
<div class="bg-primary h-full w-full opacity-10"></div>
</div>
</div>
<div class="bg-primary text-on-primary p-6 rounded-xl">
<h3 class="text-lg font-bold mb-2 font-['Manrope']">Domain Brokerage Box</h3>
<p class="text-sm text-on-primary/70 mb-6 font-['Inter'] leading-relaxed">Our experts can try to acquire this domain on your behalf from its current owner.</p>
<button class="w-full bg-surface-container-lowest text-primary py-3 rounded-lg font-bold text-sm hover:bg-on-primary transition-colors">Request Broker Service</button>
<p class="mt-4 text-[10px] text-on-primary/40 text-center uppercase tracking-widest">Starting at $49.00 + Commission</p>
</div>
</aside>
<!-- CENTER COLUMN: Exact Matches -->
<section class="lg:col-span-5">
<div class="mb-6 flex items-center justify-between">
<h3 class="text-sm font-bold uppercase tracking-[0.2em] text-on-surface-variant">Exact Matches</h3>
<span class="text-xs text-neutral-400">8 Results found</span>
</div>
<div class="bg-surface-container-lowest rounded-xl divide-y divide-outline-variant/20 overflow-hidden border border-outline-variant/30">
<!-- Domain Rows -->
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight">trovalabs.ai</p>
<p class="text-xs text-secondary">Premium tech extension</p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary">$85.30</span>
<button class="bg-primary text-on-primary px-5 py-2 rounded-lg text-xs font-bold hover:scale-105 transition-transform">Get It</button>
</div>
</div>
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight">trovalabs.io</p>
<p class="text-xs text-secondary">Developer favorite</p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary">$34.80</span>
<button class="bg-primary text-on-primary px-5 py-2 rounded-lg text-xs font-bold hover:scale-105 transition-transform">Get It</button>
</div>
</div>
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight">trovalabs.org</p>
<p class="text-xs text-secondary">Trusted non-profit</p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary">$9.20</span>
<button class="bg-primary text-on-primary px-5 py-2 rounded-lg text-xs font-bold hover:scale-105 transition-transform">Get It</button>
</div>
</div>
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight">trovalabs.net</p>
<p class="text-xs text-secondary">Network infrastructure</p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary">$14.00</span>
<button class="bg-primary text-on-primary px-5 py-2 rounded-lg text-xs font-bold hover:scale-105 transition-transform">Get It</button>
</div>
</div>
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight">trovalabs.online</p>
<p class="text-xs text-secondary">Global reach</p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary">$1.90</span>
<button class="bg-primary text-on-primary px-5 py-2 rounded-lg text-xs font-bold hover:scale-105 transition-transform">Get It</button>
</div>
</div>
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight">trovalabs.xyz</p>
<p class="text-xs text-secondary">Web3 and innovation</p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary">$2.50</span>
<button class="bg-primary text-on-primary px-5 py-2 rounded-lg text-xs font-bold hover:scale-105 transition-transform">Get It</button>
</div>
</div>
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight">trovalabs.tech</p>
<p class="text-xs text-secondary">For modern builders</p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary">$9.90</span>
<button class="bg-primary text-on-primary px-5 py-2 rounded-lg text-xs font-bold hover:scale-105 transition-transform">Get It</button>
</div>
</div>
<div class="p-5 flex items-center justify-between hover:bg-surface-container-low transition-colors group">
<div>
<p class="text-lg font-bold tracking-tight">trovalabs.store</p>
<p class="text-xs text-secondary">E-commerce ready</p>
</div>
<div class="flex items-center gap-6">
<span class="font-bold text-primary">$1.90</span>
<button class="bg-primary text-on-primary px-5 py-2 rounded-lg text-xs font-bold hover:scale-105 transition-transform">Get It</button>
</div>
</div>
</div>
</section>
<!-- RIGHT COLUMN: Premium Domains -->
<section class="lg:col-span-4">
<div class="mb-6">
<h3 class="text-sm font-bold uppercase tracking-[0.2em] text-on-surface-variant">Premium Domains</h3>
</div>
<div class="grid grid-cols-1 gap-6">
<!-- TruvoLabs -->
<div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 group cursor-pointer hover:border-primary transition-all shadow-sm hover:shadow-md">
<div class="flex flex-col items-center text-center">
<div class="w-20 h-20 bg-neutral-100 rounded-xl mb-6 flex items-center justify-center group-hover:bg-neutral-200 transition-colors">
<span class="material-symbols-outlined text-4xl text-neutral-800">deployed_code</span>
</div>
<h4 class="text-2xl font-extrabold tracking-tight font-['Manrope'] mb-1">TruvoLabs.com</h4>
<p class="text-[10px] font-bold text-secondary uppercase tracking-[0.2em] mb-6">Highly Brandable</p>
<div class="w-full h-px bg-outline-variant/20 mb-6"></div>
<div class="flex items-center justify-between w-full">
<p class="text-2xl font-black font-['Manrope']">$3,495</p>
<span class="px-6 py-2 bg-primary text-on-primary rounded-lg text-xs font-bold">Buy Now</span>
</div>
</div>
</div>
<!-- Trovally -->
<div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 group cursor-pointer hover:border-primary transition-all shadow-sm hover:shadow-md">
<div class="flex flex-col items-center text-center">
<div class="w-20 h-20 bg-neutral-100 rounded-xl mb-6 flex items-center justify-center group-hover:bg-neutral-200 transition-colors">
<span class="material-symbols-outlined text-4xl text-neutral-800">pentagon</span>
</div>
<h4 class="text-2xl font-extrabold tracking-tight font-['Manrope'] mb-1">Trovally.com</h4>
<p class="text-[10px] font-bold text-secondary uppercase tracking-[0.2em] mb-6">Abstract Authority</p>
<div class="w-full h-px bg-outline-variant/20 mb-6"></div>
<div class="flex items-center justify-between w-full">
<p class="text-2xl font-black font-['Manrope']">$3,499</p>
<span class="px-6 py-2 bg-primary text-on-primary rounded-lg text-xs font-bold">Buy Now</span>
</div>
</div>
</div>
<!-- TrovaHub -->
<div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 group cursor-pointer hover:border-primary transition-all shadow-sm hover:shadow-md">
<div class="flex flex-col items-center text-center">
<div class="w-20 h-20 bg-neutral-100 rounded-xl mb-6 flex items-center justify-center group-hover:bg-neutral-200 transition-colors">
<span class="material-symbols-outlined text-4xl text-neutral-800">hub</span>
</div>
<h4 class="text-2xl font-extrabold tracking-tight font-['Manrope'] mb-1">TrovaHub.com</h4>
<p class="text-[10px] font-bold text-secondary uppercase tracking-[0.2em] mb-6">Ecosystem Ready</p>
<div class="w-full h-px bg-outline-variant/20 mb-6"></div>
<div class="flex items-center justify-between w-full">
<p class="text-2xl font-black font-['Manrope']">$4,250</p>
<span class="px-6 py-2 bg-primary text-on-primary rounded-lg text-xs font-bold">Buy Now</span>
</div>
</div>
</div>
<!-- Latrova -->
<div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 group cursor-pointer hover:border-primary transition-all shadow-sm hover:shadow-md">
<div class="flex flex-col items-center text-center">
<div class="w-20 h-20 bg-neutral-100 rounded-xl mb-6 flex items-center justify-center group-hover:bg-neutral-200 transition-colors">
<span class="material-symbols-outlined text-4xl text-neutral-800">diamond</span>
</div>
<h4 class="text-2xl font-extrabold tracking-tight font-['Manrope'] mb-1">Latrova.com</h4>
<p class="text-[10px] font-bold text-secondary uppercase tracking-[0.2em] mb-6">Modern Luxury</p>
<div class="w-full h-px bg-outline-variant/20 mb-6"></div>
<div class="flex items-center justify-between w-full">
<p class="text-2xl font-black font-['Manrope']">$2,800</p>
<span class="px-6 py-2 bg-primary text-on-primary rounded-lg text-xs font-bold">Buy Now</span>
</div>
</div>
</div>
</div>
<button class="w-full mt-8 py-4 rounded-xl border border-primary text-primary font-bold hover:bg-primary hover:text-on-primary transition-all flex items-center justify-center gap-2 font-['Manrope']">
                    Explore Premium Collection
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</section>
</div>
<!-- AI GENERATED IDEAS SECTION -->
<section class="mt-20">
<div class="flex items-center gap-4 mb-8">
<div class="h-px bg-outline-variant flex-grow"></div>
<h3 class="label-md font-bold text-on-surface-variant tracking-[0.3em] uppercase">AI-Generated Ideas</h3>
<div class="h-px bg-outline-variant flex-grow"></div>
</div>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
<div class="bg-surface-container-low p-6 rounded-xl text-center group cursor-pointer hover:bg-surface-container-high transition-colors border border-transparent hover:border-outline-variant">
<p class="font-bold text-lg mb-1">LabsTrova</p>
<p class="text-[10px] uppercase text-secondary font-bold tracking-widest mb-4">.com .net .io</p>
<span class="text-xs text-primary font-medium group-hover:underline">Click to view options</span>
</div>
<div class="bg-surface-container-low p-6 rounded-xl text-center group cursor-pointer hover:bg-surface-container-high transition-colors border border-transparent hover:border-outline-variant">
<p class="font-bold text-lg mb-1">TrovaLabsPro</p>
<p class="text-[10px] uppercase text-secondary font-bold tracking-widest mb-4">.ai .tech .com</p>
<span class="text-xs text-primary font-medium group-hover:underline">Click to view options</span>
</div>
<div class="bg-surface-container-low p-6 rounded-xl text-center group cursor-pointer hover:bg-surface-container-high transition-colors border border-transparent hover:border-outline-variant">
<p class="font-bold text-lg mb-1">TrovaSphere</p>
<p class="text-[10px] uppercase text-secondary font-bold tracking-widest mb-4">.org .net .io</p>
<span class="text-xs text-primary font-medium group-hover:underline">Click to view options</span>
</div>
<div class="bg-surface-container-low p-6 rounded-xl text-center group cursor-pointer hover:bg-surface-container-high transition-colors border border-transparent hover:border-outline-variant">
<p class="font-bold text-lg mb-1">TrovaFlow</p>
<p class="text-[10px] uppercase text-secondary font-bold tracking-widest mb-4">.com .online</p>
<span class="text-xs text-primary font-medium group-hover:underline">Click to view options</span>
</div>
<div class="bg-surface-container-low p-6 rounded-xl text-center group cursor-pointer hover:bg-surface-container-high transition-colors border border-transparent hover:border-outline-variant">
<p class="font-bold text-lg mb-1">NexusTrova</p>
<p class="text-[10px] uppercase text-secondary font-bold tracking-widest mb-4">.ai .com .net</p>
<span class="text-xs text-primary font-medium group-hover:underline">Click to view options</span>
</div>
</div>
</section>
<!-- FAQ Section -->
<section class="mt-24 max-w-4xl mx-auto">
<h3 class="text-3xl font-extrabold tracking-tight mb-10 text-center">Frequently Asked Questions</h3>
<div class="space-y-4">
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold">What is a domain brokerage service?</span>
<span class="material-symbols-outlined">add</span>
</button>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold">How long does domain transfer take?</span>
<span class="material-symbols-outlined">add</span>
</button>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30">
<button class="w-full flex justify-between items-center text-left">
<span class="font-bold">Are there hidden fees for renewals?</span>
<span class="material-symbols-outlined">add</span>
</button>
</div>
</div>
</section>
</main>
<!-- Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script>
(() => {
    const premiumSection = document.querySelector('section.lg\\:col-span-4');

    if (!premiumSection) {
        return;
    }

    premiumSection.querySelectorAll('span').forEach((element) => {
        if ((element.textContent || '').trim().toLowerCase() !== 'buy now') {
            return;
        }

        element.classList.add('cursor-pointer');

        element.addEventListener('click', () => {
            const card = element.closest('.group');
            const title = card?.querySelector('h4');
            const domain = (title?.textContent || '').trim().toLowerCase();

            if (!domain) {
                return;
            }

            window.location.href = '/pages/whois_submit_bid.php?domain=' + encodeURIComponent(domain);
        });
    });
})();
</script>
<script src="../assets/js/nav-state.js"></script>
</body></html>




