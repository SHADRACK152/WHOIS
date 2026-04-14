<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>AI Business Architect | ARCHITECT AI</title>
<?php require __DIR__ . '/_head.php'; ?>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary selection:text-on-primary">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-32 pb-24 px-8 max-w-screen-2xl mx-auto">
<!-- Hero Section -->
<header class="max-w-3xl mb-20">
<h1 class="font-headline text-6xl font-extrabold tracking-tight text-primary mb-6">
                AI Business Architect
            </h1>
<p class="text-xl text-on-surface-variant leading-relaxed font-light">
                Turn your keywords into a complete brand concept in seconds. Our engine constructs high-authority identities for the next generation of commerce.
            </p>
</header>
<!-- Input Section -->
<section class="mb-24" data-ai-endpoint="/api/ai.php" data-ai-workflow="business_idea">
<div class="bg-surface-container-lowest rounded-xl p-10 shadow-sm border border-outline-variant/30">
<div class="flex flex-col gap-8">
<div class="space-y-4">
<label class="font-label text-xs font-bold uppercase tracking-widest text-secondary">Describe your interest or keywords</label>
<input data-ai-input="true" class="w-full text-3xl font-headline font-light bg-transparent border-none focus:ring-0 p-0 placeholder:text-neutral-300" placeholder="e.g., sustainable fashion for Gen Z" type="text"/>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-4">
<div class="space-y-4">
<label class="font-label text-xs font-bold uppercase tracking-widest text-secondary">Industry</label>
<div class="flex flex-wrap gap-2">
<button class="px-6 py-2.5 rounded-full border border-primary bg-primary text-on-primary text-sm font-medium transition-all">Tech</button>
<button class="px-6 py-2.5 rounded-full border border-outline-variant text-on-surface-variant text-sm font-medium hover:border-primary transition-all">Lifestyle</button>
<button class="px-6 py-2.5 rounded-full border border-outline-variant text-on-surface-variant text-sm font-medium hover:border-primary transition-all">Finance</button>
<button class="px-6 py-2.5 rounded-full border border-outline-variant text-on-surface-variant text-sm font-medium hover:border-primary transition-all">Health</button>
</div>
</div>
<div class="space-y-4">
<label class="font-label text-xs font-bold uppercase tracking-widest text-secondary">Tone</label>
<div class="flex flex-wrap gap-2">
<button class="px-6 py-2.5 rounded-full border border-outline-variant text-on-surface-variant text-sm font-medium hover:border-primary transition-all">Professional</button>
<button class="px-6 py-2.5 rounded-full border border-primary bg-primary text-on-primary text-sm font-medium transition-all">Creative</button>
<button class="px-6 py-2.5 rounded-full border border-outline-variant text-on-surface-variant text-sm font-medium hover:border-primary transition-all">Minimalist</button>
</div>
</div>
</div>
<div class="pt-6 border-t border-neutral-100 flex justify-end">
<button data-ai-submit="true" class="bg-primary text-on-primary px-10 py-4 rounded-full font-headline font-bold text-lg hover:bg-primary-container transition-all flex items-center gap-2 group">
                            Architect Idea
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</div>
</div>
</div>
</section>
<!-- Result Section: Asymmetric Layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
<!-- Idea Card Column -->
<div class="lg:col-span-7 space-y-12">
<div class="bg-surface-container-lowest rounded-xl p-12 shadow-sm border border-outline-variant/20 relative overflow-hidden">
<div class="absolute top-0 right-0 p-8">
<span class="bg-surface-container-highest px-3 py-1 rounded text-[10px] font-bold uppercase tracking-tighter">Selected Concept</span>
</div>
<header class="mb-12">
<h2 class="text-4xl font-headline font-extrabold mb-4">Verdant Loop</h2>
<p class="text-on-surface-variant text-lg leading-relaxed">A circular economy fashion platform connecting Gen Z creators with high-quality recycled materials and AI-driven design tools.</p>
</header>
<div class="grid grid-cols-1 md:grid-cols-2 gap-12">
<div class="space-y-6">
<div>
<h4 class="font-label text-xs font-bold uppercase tracking-widest text-secondary mb-3">Mission Statement</h4>
<p class="text-on-surface-variant leading-relaxed">To democratize sustainable fashion by turning waste into wealth through decentralized creative collaboration.</p>
</div>
<div>
<h4 class="font-label text-xs font-bold uppercase tracking-widest text-secondary mb-3">Target Audience</h4>
<ul class="space-y-2 text-on-surface-variant">
<li class="flex items-center gap-2">
<span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                        Eco-conscious Gen Z (18-26)
                                    </li>
<li class="flex items-center gap-2">
<span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                        Independent clothing upcyclers
                                    </li>
</ul>
</div>
</div>
<div class="space-y-6">
<div>
<h4 class="font-label text-xs font-bold uppercase tracking-widest text-secondary mb-3">Revenue Streams</h4>
<div class="bg-surface-container-low p-4 rounded-lg space-y-3">
<div class="flex justify-between items-center">
<span class="text-sm font-medium">SaaS Design Tools</span>
<span class="text-xs text-secondary">$19/mo</span>
</div>
<div class="flex justify-between items-center">
<span class="text-sm font-medium">Marketplace Commission</span>
<span class="text-xs text-secondary">8%</span>
</div>
</div>
</div>
</div>
</div>
<div class="mt-12 pt-8 border-t border-neutral-100">
<h4 class="font-label text-xs font-bold uppercase tracking-widest text-secondary mb-6">High-Value Domain Suggestions</h4>
<div class="space-y-3">
<div class="flex items-center justify-between p-4 bg-surface hover:bg-surface-container-high transition-colors rounded-lg group">
<span class="font-headline font-bold text-lg">verdantloop.com</span>
<div class="flex items-center gap-4">
<span class="text-[10px] bg-primary text-on-primary px-2 py-0.5 rounded font-bold">AVAILABLE</span>
<span class="material-symbols-outlined text-neutral-300 group-hover:text-primary transition-colors">add_circle</span>
</div>
</div>
<div class="flex items-center justify-between p-4 bg-surface hover:bg-surface-container-high transition-colors rounded-lg group">
<span class="font-headline font-bold text-lg">vloop.ai</span>
<div class="flex items-center gap-4">
<span class="text-[10px] bg-primary text-on-primary px-2 py-0.5 rounded font-bold">AVAILABLE</span>
<span class="material-symbols-outlined text-neutral-300 group-hover:text-primary transition-colors">add_circle</span>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Brand Preview Column -->
<div class="lg:col-span-5 sticky top-32">
<div class="bg-surface-container-low rounded-xl p-8 border border-outline-variant/10">
<h4 class="font-label text-xs font-bold uppercase tracking-widest text-secondary mb-8 text-center">Brand Identity Preview</h4>
<div class="aspect-square bg-white rounded-xl shadow-inner flex items-center justify-center mb-10 overflow-hidden relative group">
<div class="absolute inset-0 bg-neutral-50 flex items-center justify-center">
<!-- Minimal Logo Placeholder -->
<div class="w-32 h-32 flex items-center justify-center border-[12px] border-primary rounded-full">
<div class="w-12 h-12 bg-primary transform rotate-45"></div>
</div>
</div>
<div class="absolute bottom-6 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
<span class="text-[10px] text-secondary font-medium italic">Logo variation: V1.2 (Abstract Loop)</span>
</div>
</div>
<div class="space-y-8">
<div>
<h5 class="text-sm font-bold mb-4">Color Palette</h5>
<div class="grid grid-cols-5 gap-2 h-12">
<div class="bg-neutral-900 rounded-md"></div>
<div class="bg-neutral-700 rounded-md"></div>
<div class="bg-neutral-500 rounded-md"></div>
<div class="bg-neutral-300 rounded-md"></div>
<div class="bg-neutral-100 rounded-md border border-neutral-200"></div>
</div>
</div>
<div>
<h5 class="text-sm font-bold mb-4">Typographic Signature</h5>
<div class="bg-white p-4 rounded border border-neutral-100">
<p class="font-headline text-2xl font-black mb-1">Verdant Loop</p>
<p class="text-xs text-secondary font-body">Manrope ExtraBold / Tight Tracking</p>
</div>
</div>
<button class="w-full bg-primary text-on-primary py-5 rounded-xl font-headline font-extrabold text-lg flex items-center justify-center gap-3 hover:bg-primary-container transition-all shadow-xl shadow-black/10">
                            Secure the Brand
                            <span class="material-symbols-outlined">shield</span>
</button>
<p class="text-center text-[11px] text-neutral-400 font-medium">Includes domain registration, brand guidelines, and AI assets.</p>
</div>
</div>
</div>
</div>
<!-- Secondary Bento Grid for Insights -->
<section class="mt-32">
<h3 class="font-headline text-3xl font-bold mb-12">Market Validation Insights</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="bg-surface-container-low p-8 rounded-xl">
<span class="material-symbols-outlined text-4xl mb-4">trending_up</span>
<h4 class="font-bold mb-2">Market Velocity</h4>
<p class="text-sm text-on-surface-variant">The circular fashion economy is projected to reach $77B by 2025. Your timing is optimal.</p>
</div>
<div class="bg-surface-container-low p-8 rounded-xl">
<span class="material-symbols-outlined text-4xl mb-4">psychology</span>
<h4 class="font-bold mb-2">AI Advantage</h4>
<p class="text-sm text-on-surface-variant">Using generative patterns for upcycling reduces production waste by 42% compared to linear models.</p>
</div>
<div class="bg-surface-container-low p-8 rounded-xl">
<span class="material-symbols-outlined text-4xl mb-4">search_check</span>
<h4 class="font-bold mb-2">Domain Authority</h4>
<p class="text-sm text-on-surface-variant">Selected keywords have low competition but high purchase intent in premium demographic clusters.</p>
</div>
</div>
</section>
</main>
<!-- Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/ai-workflows.js"></script>
<script src="../assets/js/nav-state.js"></script>
</body></html>




