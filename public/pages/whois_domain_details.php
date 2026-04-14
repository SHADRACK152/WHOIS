<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../../app/db-client.php';

$domainName = $_GET['domain'] ?? '';
$item = null;

if (!empty($domainName)) {
    $item = whois_db_get_marketplace_item_by_domain($domainName);
}

// Redirect if not found or empty
if (!$item || strtolower((string)($item['status'] ?? '')) !== 'live') {
    header('Location: whois_premium_domain_marketplace.php');
    exit;
}

$domain = (string)($item['domain_name'] ?? '');
$extension = (string)($item['extension'] ?? '');
$price = number_format((float)($item['price'] ?? 0));
$appraisal = number_format((float)($item['appraisal_price'] ?? 0));
$categories = explode(',', (string)($item['categories'] ?? ''));
$badge = !empty($item['badge_text']) ? $item['badge_text'] : 'Premium';

// Fallback images (same as marketplace for consistency if no background_image_url)
$imageMap = [
    'neural.ai' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&q=80&w=800',
    'flow.io' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&q=80&w=800',
    'zenith.ai' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800',
    'cryptopulse.com' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?auto=format&fit=crop&q=80&w=800',
    'default' => 'https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&q=80&w=800'
];
$img = !empty($item['background_image_url']) ? $item['background_image_url'] : ($imageMap[$domain] ?? $imageMap['default']);
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?> | Domain Details | WHOIS</title>
    <?php require __DIR__ . '/_head.php'; ?>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .details-hero {
            background-image: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.8)), url('<?php echo $img; ?>');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
    <?php require __DIR__ . '/_top_nav.php'; ?>

    <main class="pt-20">
        <!-- Hero Section -->
        <section class="details-hero min-h-[60vh] flex items-center px-8 lg:px-20 relative overflow-hidden">
            <div class="max-w-4xl relative z-10">
                <div class="flex items-center gap-4 mb-6">
                    <span class="px-4 py-1.5 bg-primary text-on-primary rounded-full text-[10px] font-bold uppercase tracking-widest"><?php echo htmlspecialchars($badge, ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="text-white/70 text-sm font-medium">Verified Asset #<?php echo str_pad((string)$item['id'], 5, '0', STR_PAD_LEFT); ?></span>
                </div>
                <h1 class="text-6xl md:text-8xl font-black text-white tracking-[-0.04em] mb-8 leading-tight">
                    <?php echo htmlspecialchars(strtoupper($domain), ENT_QUOTES, 'UTF-8'); ?>
                </h1>
                <div class="flex flex-wrap gap-6 text-white/80">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        <span class="text-lg font-bold">Appraised at $<?php echo $appraisal; ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">verified</span>
                        <span class="text-lg font-bold">Secure Escrow Transfer</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="px-8 py-20 lg:px-20 max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-16">
            <!-- Left: Domain Details -->
            <div class="lg:col-span-2 space-y-12">
                <div>
                    <h2 class="text-3xl font-black tracking-tighter mb-6">Asset Intelligence</h2>
                    <p class="text-xl text-on-surface-variant leading-relaxed mb-8">
                        <?php 
                        echo !empty($item['ai_description']) 
                            ? nl2br(htmlspecialchars($item['ai_description'], ENT_QUOTES, 'UTF-8')) 
                            : "This premium digital asset is strategically positioned within the <strong>." . htmlspecialchars($extension, ENT_QUOTES, 'UTF-8') . "</strong> ecosystem. It represents a high-liquidity naming opportunity with significant brandable potential."; 
                        ?>
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach($categories as $cat): if(trim($cat)): ?>
                        <div class="flex items-center gap-3 p-4 bg-surface-container-low rounded-2xl border border-outline-variant/20">
                            <span class="material-symbols-outlined text-primary"><?php echo htmlspecialchars((string)($item['icon_name'] ?: 'label'), ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="font-bold text-sm uppercase tracking-wider"><?php echo htmlspecialchars(trim($cat), ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>

                <?php if (!empty($item['ai_technical_log'])): ?>
                <div class="p-8 bg-surface-container-lowest rounded-[2rem] border border-outline-variant/30">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-secondary mb-4">Technical Anatomy & Semantic Log</h3>
                    <p class="text-on-surface-variant leading-relaxed font-mono text-sm bg-surface-container-low p-6 rounded-xl border border-outline-variant/20">
                        <?php echo nl2br(htmlspecialchars($item['ai_technical_log'], ENT_QUOTES, 'UTF-8')); ?>
                    </p>
                </div>
                <?php endif; ?>

                <div class="p-8 bg-neutral-900 rounded-[2rem] text-white overflow-hidden relative shadow-2xl">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 blur-[100px] -mr-32 -mt-32"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-6">Why this domain?</h3>
                        <ul class="space-y-4 text-neutral-400">
                            <?php 
                            $bullets = !empty($item['ai_why_bullets']) ? explode("\n", $item['ai_why_bullets']) : [
                                "High memorability and raw recall scores.",
                                "Exact match keywords for maximum SEO impact.",
                                "Aged domain status with clean historical record."
                            ];
                            foreach($bullets as $bullet): if(trim($bullet)): ?>
                            <li class="flex gap-4">
                                <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                                <span class="text-lg"><?php echo htmlspecialchars(trim($bullet), ENT_QUOTES, 'UTF-8'); ?></span>
                            </li>
                            <?php endif; endforeach; ?>
                        </ul>
                    </div>
                </div>

                <?php if (!empty($item['ai_use_cases'])): ?>
                <div class="p-8 bg-primary/5 rounded-[2rem] border border-primary/10">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-primary mb-4">Strategic Use-Case Lab</h3>
                    <p class="text-on-surface-variant text-lg leading-relaxed italic">
                        "<?php echo htmlspecialchars($item['ai_use_cases'], ENT_QUOTES, 'UTF-8'); ?>"
                    </p>
                </div>
                <?php endif; ?>
            </div>


            <!-- Right: Action Card -->
            <aside class="space-y-8">
                <div class="sticky top-24 p-8 bg-surface-container-lowest rounded-[2rem] border border-outline-variant/30 shadow-2xl shadow-primary/5">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-secondary mb-2">Acquisition Price</p>
                    <div class="flex items-baseline gap-2 mb-8">
                        <span class="text-5xl font-black text-primary">$<?php echo $price; ?></span>
                        <span class="text-secondary font-medium">USD</span>
                    </div>

                    <div class="space-y-4 mb-8">
                        <button onclick="location.href='whois_submit_bid.php?domain=<?php echo urlencode($domain); ?>'" class="w-full py-5 bg-primary text-on-primary rounded-full font-black uppercase tracking-widest hover:opacity-90 transition-all transform active:scale-95">Buy It Now</button>
                        <button onclick="location.href='whois_submit_bid.php?domain=<?php echo urlencode($domain); ?>&type=offer'" class="w-full py-5 border border-outline-variant/40 rounded-full font-black uppercase tracking-widest hover:bg-surface-container-low transition-all">Make An Offer</button>
                    </div>

                    <div class="pt-8 border-t border-outline-variant/10 space-y-4">
                        <div class="flex items-center gap-3 text-xs text-secondary">
                            <span class="material-symbols-outlined text-sm">shield</span>
                            <span>Secured via WHOIS Escrow Service</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-secondary">
                            <span class="material-symbols-outlined text-sm">bolt</span>
                            <span>Instant Transfer (Within 24 Hours)</span>
                        </div>
                    </div>
                </div>

                <!-- Related Links -->
                <div class="p-6 bg-surface-container-low rounded-2xl border border-outline-variant/10">
                    <h4 class="text-sm font-bold uppercase tracking-widest mb-4">Market Stats</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-secondary">Views (Last 24h)</span>
                            <span class="font-bold">142</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-secondary">Related Offers</span>
                            <span class="font-bold">3 Bids</span>
                        </div>
                    </div>
                </div>
            </aside>
        </section>
    </main>

    <?php require __DIR__ . '/_footer.php'; ?>
    <script src="../assets/js/nav-state.js"></script>
</body>
</html>
