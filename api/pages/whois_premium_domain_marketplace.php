<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../../app/db-client.php';

// Fetch all marketplace items (Live + Sold) for the audit trail
$allMarketplaceItems = whois_db_list_marketplace_items([]);

// If empty, trigger seed (standard for this project)
if ($allMarketplaceItems === [] && whois_db_is_available()) {
    $db = whois_db_connection();
    if ($db) {
        require_once __DIR__ . '/../../app/db-schema.php';
        whois_db_seed_marketplace_items($db);
        $allMarketplaceItems = whois_db_list_marketplace_items([]);
    }
}

// Split into Live vs Sold
$liveItems = array_values(array_filter($allMarketplaceItems, static function (array $item): bool {
    return strtolower((string)($item['status'] ?? '')) === 'live';
}));

$soldItems = array_values(array_filter($allMarketplaceItems, static function (array $item): bool {
    return strtolower((string)($item['status'] ?? '')) === 'sold';
}));

// Split Live into Featured vs Regular
$featuredItems = array_values(array_filter($liveItems, static function (array $item): bool {
    return (string) ($item['listing_type'] ?? 'row') === 'featured';
}));

$gridItems = array_values(array_filter($liveItems, static function (array $item): bool {
    return (string) ($item['listing_type'] ?? 'row') !== 'featured';
}));

// Default fallback image for items without a custom URL
$defaultImage = 'https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&q=80&w=800';


/**
 * Renders a large, high-impact featured card
 */
function render_featured_card(array $item, string $defaultImage): void {
    $domain = (string)($item['domain_name'] ?? '');
    $img = !empty($item['background_image_url']) ? $item['background_image_url'] : $defaultImage;
    $price = number_format((float)($item['price'] ?? 0));

    $appraisal = number_format((float)($item['appraisal_price'] ?? 0));
    $badge = !empty($item['badge_text']) ? $item['badge_text'] : 'Featured';
    ?>
    <div class="group relative overflow-hidden rounded-[2rem] bg-surface-container-low border border-outline-variant/30 hover:shadow-2xl hover:shadow-primary/10 transition-all duration-500" 
         data-marketplace-item="true" data-type="live" data-extension="<?php echo htmlspecialchars((string)$item['extension'], ENT_QUOTES, 'UTF-8'); ?>" data-price="<?php echo (float)$item['price']; ?>" data-date="<?php echo (string)$item['created_at']; ?>">
        <div class="aspect-[16/9] overflow-hidden">
            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter grayscale brightness-75 group-hover:grayscale-0 group-hover:brightness-100">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-primary text-on-primary text-[10px] font-bold uppercase tracking-widest rounded-full"><?php echo htmlspecialchars($badge, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="text-xs font-medium text-white/70">Appraisal: $<?php echo $appraisal; ?></span>
            </div>
            <h3 class="text-4xl font-black tracking-tighter mb-2 group-hover:text-primary transition-colors"><?php echo htmlspecialchars(strtoupper($domain), ENT_QUOTES, 'UTF-8'); ?></h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-white/60 mb-1">Buy Now Price</p>
                    <p class="text-3xl font-bold font-headline text-white">$<?php echo $price; ?></p>
                </div>
                <button class="bg-white text-black px-8 py-3 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-primary hover:text-white transition-all transform active:scale-95" 
                        onclick="location.href='whois_domain_details.php?domain=<?php echo urlencode($domain); ?>'">View Details</button>
            </div>

        </div>
    </div>
    <?php
}

/**
 * Renders a professional grid item
 */
function render_grid_item(array $item): void {
    $domain = (string)($item['domain_name'] ?? '');
    $price = number_format((float)($item['price'] ?? 0));
    $isPremium = (bool)($item['is_premium'] ?? false);
    $badge = !empty($item['badge_text']) ? $item['badge_text'] : ($isPremium ? 'Premium' : '');
    ?>
    <div class="group bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-6 hover:border-primary/40 hover:shadow-xl transition-all duration-300"
         data-marketplace-item="true" data-type="live" data-extension="<?php echo htmlspecialchars((string)$item['extension'], ENT_QUOTES, 'UTF-8'); ?>" data-price="<?php echo (float)$item['price']; ?>" data-date="<?php echo (string)$item['created_at']; ?>">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-surface-container rounded-xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined"><?php echo htmlspecialchars((string)($item['icon_name'] ?: 'language'), ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <?php if ($badge): ?>
            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold uppercase tracking-tighter rounded"><?php echo htmlspecialchars($badge, ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endif; ?>
        </div>
        <h4 class="text-xl font-bold tracking-tight mb-1 truncate"><?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?></h4>
        <p class="text-xs text-secondary mb-6 truncate"><?php echo htmlspecialchars((string)$item['categories'], ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="pt-6 border-t border-outline-variant/10 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-secondary uppercase tracking-widest">Price</p>
                <p class="text-lg font-black text-primary">$<?php echo $price; ?></p>
            </div>
            <button class="w-10 h-10 bg-surface-container-high rounded-full flex items-center justify-center hover:bg-primary hover:text-on-primary transition-all"
                    onclick="location.href='whois_domain_details.php?domain=<?php echo urlencode($domain); ?>'">
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </div>

    </div>
    <?php
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>WHOIS | Premium Domain Marketplace</title>
    <?php require __DIR__ . '/_head.php'; ?>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .marketplace-hero { 
            background: 
                radial-gradient(circle at 10% 20%, rgba(0,0,0,0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(0,0,0,0.02) 0%, transparent 20%),
                linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface selection:bg-primary/20 selection:text-primary antialiased">
    <?php require __DIR__ . '/_top_nav.php'; ?>

    <div class="flex pt-20">
        <!-- Sidebar Filters -->
        <aside class="hidden lg:block w-72 h-[calc(100vh-80px)] border-r border-outline-variant/20 bg-surface-container-lowest sticky top-20 p-8">
            <div class="mb-10">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-secondary mb-2">Refine Search</h3>
                <h2 class="text-2xl font-black tracking-tighter">Market Filters</h2>
            </div>

            <div class="space-y-10">
                <!-- Sell Link -->
                <div>
                     <a href="whois_submit_domain_for_auction.php" class="flex items-center justify-between w-full p-4 bg-primary text-on-primary rounded-2xl hover:opacity-90 transition-opacity">
                        <span class="text-[10px] font-black uppercase tracking-widest">List Your Domain</span>
                        <span class="material-symbols-outlined text-sm">add_circle</span>
                     </a>
                </div>

                <!-- Keyword Search -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-4">Keyword Search</label>
                    <div class="relative">
                        <input type="text" id="side-search" class="w-full bg-surface-container-low border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20" placeholder="e.g. 'AI' or 'Tech'">
                    </div>
                </div>

                <!-- TLD Filter -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-4">Extensions</label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (['all', 'com', 'ai', 'io', 'net', 'org'] as $ext): ?>
                        <button class="filter-ext-btn px-4 py-2 bg-surface-container overflow-hidden rounded-xl text-xs font-bold uppercase tracking-widest border border-transparent hover:border-primary/20 transition-all" 
                                data-ext="<?php echo htmlspecialchars($ext, ENT_QUOTES, 'UTF-8'); ?>">.<?php echo htmlspecialchars($ext, ENT_QUOTES, 'UTF-8'); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary mb-4">Sort By</label>
                    <select id="market-sort" class="w-full bg-surface-container-low border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20">
                        <option value="newest">Recently Added</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="mt-20 pt-10 border-t border-outline-variant/10">
                <div class="flex items-center gap-3 text-secondary">
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Secure Escrow Guaranteed</span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-h-screen">
            <!-- Hero -->
            <section class="marketplace-hero px-8 py-20 lg:px-20 border-b border-outline-variant/10">
                <div class="max-w-4xl">
                    <span class="inline-block px-4 py-1.5 bg-secondary/10 text-secondary rounded-full text-[10px] font-bold uppercase tracking-widest mb-6">Premium Inventory</span>
                    <h1 class="text-6xl md:text-8xl font-black text-primary tracking-[-0.04em] mb-8 leading-[0.9]">The Elite Digital<br>Real Estate.</h1>
                    <p class="text-xl text-on-surface-variant max-w-2xl mb-12">Hand-curated premium domain names with verified appraisal values and instant ownership transfer via secured escrow.</p>
                    
                    <div class="relative group max-w-2xl">
                        <div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary rounded-3xl blur opacity-25 group-focus-within:opacity-50 transition duration-500"></div>
                        <input type="text" id="main-search" class="relative w-full bg-white border-none rounded-3xl px-8 py-6 text-xl shadow-xl focus:ring-0 placeholder:text-neutral-300" placeholder="Search the marketplace...">
                    </div>
                </div>
            </section>

            <!-- Sell CTA Banner -->
            <section class="px-8 mt-12 lg:px-20 max-w-[1600px] mx-auto">
                <div class="bg-neutral-900 rounded-[2rem] p-8 md:p-12 text-white flex flex-col lg:flex-row items-center justify-between gap-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 blur-[100px] -mr-32 -mt-32"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-black tracking-tighter mb-4">Have a premium domain to sell?</h2>
                        <p class="text-neutral-400 max-w-xl">List your asset in our global auction network and reach over 100k+ verified investors and business builders.</p>
                    </div>
                    <div class="relative z-10 shrink-0">
                        <a href="whois_submit_domain_for_auction.php" class="inline-flex items-center justify-center bg-white text-black px-10 py-5 rounded-full text-sm font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all transform hover:scale-105 active:scale-95">List My Domain</a>
                    </div>
                </div>
            </section>

            <!-- Grid Content -->
            <section class="px-8 py-20 lg:px-20 max-w-[1600px] mx-auto">
                <!-- Trending / Featured -->
                <?php if ($featuredItems !== []): ?>
                <div class="mb-20">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-3xl font-black tracking-tighter">Trending Highlights</h2>
                        <div class="h-px flex-1 bg-outline-variant/20"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php foreach ($featuredItems as $f): render_featured_card($f, $defaultImage); endforeach; ?>
                    </div>
                </div>

                <?php endif; ?>

                <!-- Main Grid -->
                <div class="mb-20">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-black tracking-tighter">Live Inventory</h2>
                        <span class="text-xs font-bold text-secondary uppercase tracking-widest" id="item-count"><?php echo count($gridItems); ?> Domains available</span>
                    </div>
                    <div id="marketplace-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
                        <?php foreach ($gridItems as $g): render_grid_item($g); endforeach; ?>
                    </div>
                    <div id="no-results" class="hidden py-40 text-center bg-surface-container-low rounded-[2rem] border border-dashed border-outline-variant/40">
                        <span class="material-symbols-outlined text-4xl text-secondary mb-4">search_off</span>
                        <p class="text-secondary font-bold uppercase tracking-widest text-sm">No domains found matching those criteria.</p>
                    </div>
                </div>

                <!-- Sales History (Audit Trail) -->
                <?php if ($soldItems !== []): ?>
                <div class="mt-40 pt-20 border-t border-outline-variant/20">
                    <div class="flex items-center gap-4 mb-12">
                        <h2 class="text-3xl font-black tracking-tighter">Market Record</h2>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-widest rounded-full">Sales Audit Trail</span>
                        <div class="h-px flex-1 bg-outline-variant/20"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        <?php foreach ($soldItems as $s): ?>
                        <div class="bg-surface-container-low/50 border border-outline-variant/10 rounded-2xl p-4 grayscale opacity-60">
                            <div class="flex justify-between items-center mb-3">
                                <span class="material-symbols-outlined text-sm">verified</span>
                                <span class="bg-neutral-200 text-neutral-600 px-2 py-0.5 text-[8px] font-bold uppercase rounded">SOLD</span>
                            </div>
                            <h4 class="text-sm font-bold text-primary mb-1"><?php echo htmlspecialchars((string)$s['domain_name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                            <p class="text-[10px] text-secondary font-bold">$<?php echo number_format((float)($s['sold_price'] ?: (float)$s['price'])); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-8 text-xs text-secondary italic">This audit trail is public to ensure transparency and show verified historical sales within the WHOIS ecosystem.</p>
                </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <?php require __DIR__ . '/_footer.php'; ?>

    <script>
    (function() {
        // Elements
        const mainSearch = document.getElementById('main-search');
        const sideSearch = document.getElementById('side-search');
        const sortSelect = document.getElementById('market-sort');
        const extBtns = document.querySelectorAll('.filter-ext-btn');
        const items = document.querySelectorAll('[data-marketplace-item][data-type="live"]');
        const grid = document.getElementById('marketplace-grid');
        const countSpan = document.getElementById('item-count');
        const emptyState = document.getElementById('no-results');

        let activeExt = 'all';

        function updateFilters() {
            const query = (mainSearch.value || sideSearch.value || '').toLowerCase().trim();
            let visibleCount = 0;

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                const domain = item.getAttribute('data-domain')?.toLowerCase() || '';
                const ext = item.getAttribute('data-extension')?.toLowerCase() || '';
                
                const matchesSearch = !query || text.includes(query) || domain.includes(query);
                const matchesExt = activeExt === 'all' || ext === activeExt;

                if (matchesSearch && matchesExt) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            countSpan.textContent = visibleCount + ' Domains available';
            emptyState.classList.toggle('hidden', visibleCount > 0);
            grid.classList.toggle('hidden', visibleCount === 0);
        }

        function sortItems() {
            const val = sortSelect.value;
            const container = grid;
            const list = Array.from(items);

            list.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price') || 0);
                const priceB = parseFloat(b.getAttribute('data-price') || 0);
                const dateA = new Date(a.getAttribute('data-date') || 0);
                const dateB = new Date(b.getAttribute('data-date') || 0);

                if (val === 'price-low') return priceA - priceB;
                if (val === 'price-high') return priceB - priceA;
                return dateB - dateA; // newest
            });

            list.forEach(el => container.appendChild(el));
        }

        // Listeners
        mainSearch.addEventListener('input', (e) => { sideSearch.value = e.target.value; updateFilters(); });
        sideSearch.addEventListener('input', (e) => { mainSearch.value = e.target.value; updateFilters(); });
        sortSelect.addEventListener('change', sortItems);

        extBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                activeExt = btn.getAttribute('data-ext');
                extBtns.forEach(b => b.classList.remove('bg-primary', 'text-on-primary'));
                btn.classList.add('bg-primary', 'text-on-primary');
                updateFilters();
            });
        });

        // Init
        const allBtn = document.querySelector('.filter-ext-btn[data-ext="all"]');
        if (allBtn) allBtn.classList.add('bg-primary', 'text-on-primary');
    })();
    </script>
    <script src="../assets/js/nav-state.js"></script>
</body>
</html>
