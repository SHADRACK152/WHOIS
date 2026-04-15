<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/db-client.php';

// Fetch live auctions from the marketplace database
$auctions = [];
if (whois_db_is_available()) {
    $auctions = whois_db_fetch_all("
        SELECT * FROM marketplace_items 
        WHERE status = 'live' 
        ORDER BY is_premium DESC, appraisal_price DESC 
        LIMIT 12
    ");
}

// Fallback items if DB is empty or unavailable
if (empty($auctions)) {
    $auctions = [
        [
            'domain_name' => 'Marquise.ai',
            'price' => 12450,
            'appraisal_price' => 45000,
            'badge_text' => 'AI INSIGHTS',
            'categories' => 'Artificial Intelligence, Luxury',
            'image' => 'https://images.unsplash.com/photo-1615486171542-a8cbf63da78c?q=80&w=800&auto=format&fit=crop'
        ],
        [
            'domain_name' => 'SoulTrait.com',
            'price' => 8200,
            'appraisal_price' => 22500,
            'badge_text' => 'PREMIUM',
            'categories' => 'Lifestyle, Wellness',
            'image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=800&auto=format&fit=crop'
        ],
        [
            'domain_name' => 'Advantech.ai',
            'price' => 15700,
            'appraisal_price' => 68000,
            'badge_text' => 'HIGH VALUE',
            'categories' => 'Technology, SaaS',
            'image' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?q=80&w=800&auto=format&fit=crop'
        ],
        [
            'domain_name' => 'ChatCopilot.com',
            'price' => 4500,
            'appraisal_price' => 18500,
            'badge_text' => 'TRENDING',
            'categories' => 'AI, Assistant',
            'image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80&w=800&auto=format&fit=crop'
        ]
    ];
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Premium Domain Auctions | WHOIS.ARCHITECT</title>
    <?php require __DIR__ . '/_head.php'; ?>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass-card {
            background: rgba(30, 30, 30, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .text-glow {
            text-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .gradient-border {
            position: relative;
        }
        .gradient-border::after {
            content: '';
            position: absolute;
            inset: -1px;
            background: linear-gradient(to right, #000, #444, #000);
            z-index: -1;
            border-radius: inherit;
            opacity: 0.1;
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased transition-colors duration-300">

<?php require __DIR__ . '/_top_nav.php'; ?>

<main class="relative pt-32 pb-32 overflow-hidden">
    <!-- Ambient Background Elements -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/5 rounded-full blur-[120px] -z-10"></div>
    <div class="absolute bottom-1/4 right-0 w-[500px] h-[500px] bg-secondary/5 rounded-full blur-[150px] -z-10"></div>

    <section class="px-8 max-w-screen-2xl mx-auto mb-24 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-surface-container-low border border-outline-variant/30 mb-8 animate-fade-in">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-secondary">Live Auctions in Progress</span>
        </div>
        
        <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-primary mb-8 leading-[0.9] text-glow">
            Limited-Time<br/><span class="text-on-surface-variant/40 italic font-medium">Domain</span> Auctions
        </h1>
        
        <p class="text-xl text-on-surface-variant max-w-2xl mx-auto mb-16 leading-relaxed">
            Bid on exclusive, high-appraisal digital assets curated by our AI intelligence. Secure your brand's future today.
        </p>

        <div class="max-w-3xl mx-auto relative group">
            <div class="absolute inset-y-0 left-8 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-outline text-xl">search</span>
            </div>
            <input class="w-full pl-20 pr-48 py-7 bg-surface-container-lowest/80 backdrop-blur-xl border border-outline-variant/50 rounded-full text-xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-2xl shadow-black/5" placeholder="Search premium auctions..." type="text"/>
            <button class="absolute right-3 top-3 bottom-3 px-10 bg-primary text-on-primary rounded-full font-bold text-sm uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-primary/20">
                Explore
            </button>
        </div>
    </section>

    <!-- Featured Spotlight -->
    <section class="px-8 max-w-screen-2xl mx-auto mb-32">
        <div class="relative rounded-[3rem] overflow-hidden group shadow-[0_50px_100px_rgba(0,0,0,0.1)]">
            <div class="aspect-[21/9] w-full">
                <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop" class="w-full h-full object-cover grayscale brightness-50 group-hover:scale-105 transition-transform duration-1000" alt="Premium background">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent flex items-center p-12 md:p-24">
                <div class="max-w-xl">
                    <p class="text-primary-container font-bold uppercase tracking-[0.3em] text-xs mb-6">Expert Appraisal</p>
                    <h2 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight leading-tight">Unlock Verified High-Tier Domains</h2>
                    <p class="text-white/70 text-lg mb-10 leading-relaxed">Get access to comprehensive analytics, domain history, and AI-driven growth projections before you bid.</p>
                    <div class="flex gap-4">
                        <button class="px-10 py-5 bg-white text-black font-bold rounded-full hover:bg-neutral-200 transition-colors shadow-lg">Start Bidding</button>
                        <button class="px-10 py-5 bg-white/10 backdrop-blur-md text-white border border-white/20 font-bold rounded-full hover:bg-white/20 transition-colors">How it works</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Auction Grid -->
    <section class="px-8 max-w-screen-2xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-16">
            <div>
                <p class="text-primary font-bold uppercase tracking-[0.3em] text-[10px] mb-4">Marketplace</p>
                <h2 class="text-4xl md:text-5xl font-black tracking-tighter">Trending<br/><span class="text-on-surface-variant/50">Market Opportunities</span></h2>
            </div>
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 px-6 py-3 rounded-full border border-outline-variant/50 hover:bg-surface-container transition-all text-xs font-bold uppercase tracking-widest">
                    <span class="material-symbols-outlined text-sm">filter_list</span> Filter
                </button>
                <select class="px-6 py-3 rounded-full border border-outline-variant/50 bg-surface text-xs font-bold uppercase tracking-widest outline-none focus:border-primary cursor-pointer">
                    <option>High to Low</option>
                    <option>Ending Soon</option>
                    <option>Newest</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
            <?php foreach ($auctions as $idx => $item): ?>
                <?php 
                    $price = (float)($item['price'] ?? 0);
                    $appraisal = (float)($item['appraisal_price'] ?? 0);
                    $imgUrl = $item['image'] ?? 'https://images.unsplash.com/photo-1620641788421-7a1c342ea42e?q=80&w=800&auto=format&fit=crop';
                ?>
                <div class="group relative glass-card rounded-[2.5rem] p-4 hover:-translate-y-2 transition-all duration-500 hover:shadow-[0_40px_80px_rgba(0,0,0,0.1)]">
                    <div class="relative h-64 rounded-[2rem] overflow-hidden mb-6">
                        <img src="<?php echo $imgUrl; ?>" class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-110 duration-700 transition-transform" alt="<?php echo htmlspecialchars($item['domain_name']); ?>">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        
                        <div class="absolute top-4 left-4 right-4 flex justify-between items-start">
                            <span class="px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-[9px] font-bold text-white uppercase tracking-widest border border-white/20">
                                <?php echo htmlspecialchars($item['badge_text'] ?? 'AUCTION'); ?>
                            </span>
                            <button class="w-10 h-10 rounded-full bg-black/40 backdrop-blur-md text-white flex items-center justify-center hover:bg-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">favorite</span>
                            </button>
                        </div>

                        <div class="absolute bottom-6 left-6">
                            <p class="text-3xl font-black text-white tracking-tighter leading-none mb-1"><?php echo htmlspecialchars($item['domain_name']); ?></p>
                            <p class="text-[10px] text-white/60 font-medium uppercase tracking-widest"><?php echo htmlspecialchars($item['categories'] ?? 'Uncategorized'); ?></p>
                        </div>
                    </div>

                    <div class="px-4 pb-4">
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-secondary mb-2">Current Bid</p>
                                <p class="text-2xl font-black tracking-tight">$<?php echo number_format($price); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-secondary mb-2">Appraisal</p>
                                <p class="text-2xl font-black tracking-tight text-primary/80">$<?php echo number_format($appraisal); ?>+</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low/50 border border-outline-variant/30 mb-8">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-secondary">schedule</span>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">Ends In</span>
                            </div>
                            <span class="text-[11px] font-black tracking-widest">2D : 14H : 05M</span>
                        </div>

                        <a href="whois_submit_bid.php?domain=<?php echo urlencode($item['domain_name']); ?>" class="block w-full py-5 bg-black text-white text-center font-bold text-xs uppercase tracking-[0.25em] rounded-2xl hover:bg-primary transition-all active:scale-[0.98] shadow-lg shadow-black/10">
                            Place Bid
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-24 text-center">
            <button class="px-16 py-6 border-2 border-primary text-primary font-bold text-xs uppercase tracking-[0.3em] rounded-full hover:bg-primary hover:text-white transition-all shadow-xl hover:shadow-primary/20">
                View All Opportunities
            </button>
        </div>
    </section>
</main>

<?php require __DIR__ . '/_footer.php'; ?>

<script>
    // Simple intersection observer for scroll reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-reveal');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.glass-card').forEach(card => observer.observe(card));
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s ease-out forwards;
    }
</style>

<script src="../assets/js/nav-state.js"></script>
</body>
</html>