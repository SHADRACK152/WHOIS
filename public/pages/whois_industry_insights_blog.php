<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/db-client.php';
$articles = whois_db_fetch_all("SELECT * FROM articles WHERE status = 'published' ORDER BY published_at DESC LIMIT 12");
?>
<!DOCTYPE html>

<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>WHOIS | Industry Insights</title>
    <?php require __DIR__ . '/_head.php'; ?>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .grayscale-img {
            filter: grayscale(100%);
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface selection:bg-primary-container selection:text-white">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-16 pb-24">
<!-- Hero Section -->
<section class="relative w-full h-[716px] min-h-[500px] bg-surface flex items-end overflow-hidden">
<img alt="Featured Article Background" class="absolute inset-0 w-full h-full object-cover grayscale-img opacity-60" data-alt="dramatic grayscale aerial view of a futuristic digital network grid glowing against a dark abstract background with bokeh highlights" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1BR4bvc_UmgiukfqH5GfsFP-hUh47N6HH7PAR3Puq1vVCgbU3wlrsoElCQ9n8hiBZ3nLE1DNwRotN5KQ-mr0f2MB7nyn6S6cO6DrXXhQn_oeWUxXQ1sGSp60Tx66EYCKP_RWLcIBDsSU8uhpN6eTJj4-tZD554WhGGU2du5mqRKICDJXtw12Zl8WjSrKFgIFnWfFYetUBAB91jW_Y6wFFEQnpkNHju6FX6bqr98SetZYc3V9sx9kQopupN5SsXPunA3n8hyTnV67F"/>
<div class="relative z-10 w-full max-w-7xl mx-auto px-8 pb-16">
<div class="max-w-3xl">
<span class="inline-block bg-primary text-on-primary text-[10px] uppercase tracking-[0.2em] font-bold px-3 py-1 mb-6 rounded-full">Featured Insight</span>
<h1 class="text-5xl md:text-7xl font-extrabold font-headline tracking-tighter text-black mb-6 leading-[0.95]">
                        The State of .AI Domains in 2026
                    </h1>
<div class="flex items-center space-x-4 text-on-surface-variant font-medium">
<span class="flex items-center">
<span class="material-symbols-outlined text-sm mr-2" data-icon="person">person</span>
                            Marcus Thorne
                        </span>
<span class="w-1 h-1 bg-outline-variant rounded-full"></span>
<span class="flex items-center">
<span class="material-symbols-outlined text-sm mr-2" data-icon="schedule">schedule</span>
                            12 min read
                        </span>
</div>
</div>
</div>
<div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
</section>
<!-- Search & Filter Cluster -->
<section class="max-w-7xl mx-auto px-8 -mt-8 relative z-20">
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/30 flex flex-col md:flex-row justify-between items-center gap-6">
<!-- Search -->
<div class="relative w-full md:w-96 group">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-neutral-400 group-focus-within:text-black transition-colors" data-icon="search">search</span>
<input class="w-full bg-surface-container-low border border-outline-variant/40 rounded-full py-3 pl-12 pr-6 focus:outline-none focus:border-black focus:ring-0 transition-all font-body text-sm" placeholder="Search insights..." type="text"/>
</div>
<!-- Filters -->
<div class="flex items-center space-x-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto scrollbar-hide">
<button class="bg-primary text-on-primary px-5 py-2 rounded-full text-xs font-bold font-headline whitespace-nowrap">All</button>
<button class="bg-surface-container-low text-on-surface-variant hover:text-black hover:bg-surface-container-high px-5 py-2 rounded-full text-xs font-bold font-headline transition-all whitespace-nowrap">Industry News</button>
<button class="bg-surface-container-low text-on-surface-variant hover:text-black hover:bg-surface-container-high px-5 py-2 rounded-full text-xs font-bold font-headline transition-all whitespace-nowrap">Branding Tips</button>
<button class="bg-surface-container-low text-on-surface-variant hover:text-black hover:bg-surface-container-high px-5 py-2 rounded-full text-xs font-bold font-headline transition-all whitespace-nowrap">Domain Strategy</button>
<button class="bg-surface-container-low text-on-surface-variant hover:text-black hover:bg-surface-container-high px-5 py-2 rounded-full text-xs font-bold font-headline transition-all whitespace-nowrap">Case Studies</button>
</div>
</div>
</section>
<!-- Article Grid -->
<section class="max-w-7xl mx-auto px-8 mt-24">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
<?php foreach ($articles as $article): ?>
    <?php $date = date('M d, Y', strtotime($article['published_at'] ?? $article['created_at'])); ?>
    <a href="whois_industry_insights_article.php?slug=<?php echo urlencode($article['slug']); ?>" class="block group cursor-pointer text-left focus:outline-none focus:ring-2 focus:ring-primary rounded-xl">
        <div class="aspect-[16/10] overflow-hidden rounded-xl bg-surface-container-low mb-6">
            <?php if (!empty($article['image_url'])): ?>
                <img alt="Blog Post Thumbnail" class="w-full h-full object-cover grayscale-img group-hover:scale-105 transition-transform duration-500" src="<?php echo htmlspecialchars($article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"/>
            <?php else: ?>
                <div class="w-full h-full bg-surface-container-high group-hover:scale-105 transition-transform duration-500"></div>
            <?php endif; ?>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400"><?php echo htmlspecialchars($article['category'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="text-[10px] font-medium text-neutral-400"><?php echo $date; ?></span>
            </div>
            <h3 class="text-2xl font-bold font-headline tracking-tight text-black group-hover:underline decoration-1 underline-offset-4 line-clamp-2"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p class="text-on-surface-variant text-sm leading-relaxed line-clamp-2"><?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="flex items-center text-[10px] font-bold text-black pt-2">
                READ ARTICLE <span class="material-symbols-outlined text-xs ml-1" data-icon="arrow_forward">arrow_forward</span>
            </div>
        </div>
    </a>
<?php endforeach; ?>
<?php if (empty($articles)): ?>
    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 text-secondary">
        No insights published yet. Check back later.
    </div>
<?php endif; ?>
</div>
<!-- Pagination -->
<div class="mt-24 flex justify-center">
<button class="px-8 py-3 border border-outline-variant text-black font-bold font-headline rounded-full hover:bg-surface-container-low transition-all">Load More Articles</button>
</div>
</section>
<!-- Newsletter Signup Section -->
<section class="max-w-7xl mx-auto px-8 mt-32">
<div class="bg-black text-white p-12 md:p-24 rounded-3xl relative overflow-hidden">
<div class="relative z-10 max-w-2xl">
<h2 class="text-4xl md:text-5xl font-black font-headline tracking-tighter mb-6 leading-tight">Subscribe to the WHOIS Insight</h2>
<p class="text-secondary-fixed text-lg mb-10 font-light">The definitive weekly briefing on digital assets, AI infrastructure, and the evolving domain economy. No fluff, just signal.</p>
<form class="flex flex-col md:flex-row gap-4" onsubmit="return false;">
<input class="flex-grow bg-white/10 border border-white/20 rounded-full py-4 px-8 focus:outline-none focus:border-white focus:ring-0 text-white placeholder:text-neutral-500 transition-all font-body" placeholder="Email address" type="email"/>
<button class="bg-white text-black px-10 py-4 rounded-full font-bold font-headline hover:bg-surface-container-highest transition-all" type="submit">Subscribe Now</button>
</form>
</div>
<!-- Decorative element -->
<div class="absolute right-[-10%] bottom-[-20%] w-[400px] h-[400px] border-[40px] border-white/5 rounded-full"></div>
</div>
</section>
</main>
<!-- Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body></html>




