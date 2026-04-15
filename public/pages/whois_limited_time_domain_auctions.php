<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Limited-Time Domain Auctions</title>
    

    <?php require __DIR__ . '/_head.php'; ?>
    
    </head>
<body class="text-on-surface" style="background: #f9f9f9; min-height: 100vh;">

<div style="background: #ff0000; color: #fff; text-align: center; padding: 16px; font-size: 20px; font-weight: bold;">
    DEBUG: This auctions page is being executed (<?= __FILE__ ?>)
</div>

<?php // require __DIR__ . '/_top_nav.php'; ?>

<main class="pt-32 pb-20 px-8 max-w-screen-2xl mx-auto">
    <section class="mb-24 text-center">
        <h1 class="text-6xl md:text-7xl font-extrabold tracking-tighter text-primary mb-6">
            Limited-Time Domain Auctions
        </h1>
        <p class="text-xl text-on-surface-variant max-w-2xl mx-auto mb-12">
            Bid, Win, and Own Premium Domains via Our Dynamic Auctions Platform
        </p>
        <div class="max-w-3xl mx-auto relative group">
            <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-outline">search</span>
            </div>
            <input class="w-full pl-16 pr-40 py-6 bg-surface-container-lowest border border-outline-variant/40 rounded-full text-lg focus:ring-0 focus:border-primary transition-all shadow-lg shadow-black/5" placeholder="Search for premium domains in auction..." type="text"/>
            <button class="absolute right-3 top-3 bottom-3 px-8 bg-primary text-on-primary-fixed-variant rounded-full font-bold hover:bg-primary-container hover:text-primary transition-colors">
                Search
            </button>
        </div>
    </section>

    <section class="mb-24">
        <div class="bg-surface-container-low rounded-3xl p-1 bg-[url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop')] bg-cover bg-center overflow-hidden" data-alt="abstract grayscale architectural structure with deep shadows and sharp geometric lines in a minimalist composition">
            <div class="w-full h-full bg-black/60 backdrop-blur-sm p-12 md:p-16 flex flex-col md:flex-row justify-between items-center rounded-[1.4rem]">
                <div class="text-center md:text-left mb-8 md:mb-0">
                    <h2 class="text-4xl font-bold text-white mb-2">Get Full Access To Atom Auctions</h2>
                    <p class="text-white opacity-80 text-lg">Unlock verified high-tier domains and expert appraisal metrics.</p>
                </div>
                <button class="px-10 py-4 bg-white text-black font-bold rounded-full hover:scale-105 transition-transform">
                    Learn More
                </button>
            </div>
        </div>
    </section>

    <section>
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold tracking-tight mb-2">Trending Auctions</h2>
                <p class="text-on-surface-variant">Real-time bidding on highly-appraised digital assets.</p>
            </div>
            <div class="flex space-x-2">
                <button class="p-3 rounded-full border border-outline-variant hover:bg-surface-container transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined">filter_list</span>
                </button>
                <button class="p-3 rounded-full border border-outline-variant hover:bg-surface-container transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined">sort</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            
            <div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-outline-variant/20 hover:border-primary/50">
                <div class="h-56 relative bg-neutral-200 overflow-hidden">
                    <img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" alt="diamond" src="https://images.unsplash.com/photo-1599643478524-fb524b0a0a5d?q=80&w=800&auto=format&fit=crop"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="text-2xl font-black text-white tracking-tighter">Marquise.ai</span>
                    </div>
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs text-white font-bold uppercase tracking-widest">
                        AI INSIGHTS
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-outline mb-1">Current Bid</p>
                            <p class="text-xl font-bold">$12,450</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-outline mb-1">Total Bids</p>
                            <p class="text-xl font-bold">48</p>
                        </div>
                    </div>
                    <div class="mb-8 p-3 bg-surface-container-low rounded-xl border-l-2 border-primary">
                        <p class="text-[10px] uppercase tracking-widest text-outline mb-1">WHOIS Appraisal</p>
                        <p class="text-lg font-bold font-body">$45,000+</p>
                    </div>
                    <button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container group-hover:text-primary transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-outline-variant/20 hover:border-primary/50">
                <div class="h-56 relative bg-neutral-200 overflow-hidden">
                    <img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" alt="heartbeat" src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=800&auto=format&fit=crop"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="text-2xl font-black text-white tracking-tighter">SoulTrait.com</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-outline mb-1">Current Bid</p>
                            <p class="text-xl font-bold">$8,200</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-outline mb-1">Total Bids</p>
                            <p class="text-xl font-bold">32</p>
                        </div>
                    </div>
                    <div class="mb-8 p-3 bg-surface-container-low rounded-xl border-l-2 border-primary">
                        <p class="text-[10px] uppercase tracking-widest text-outline mb-1">WHOIS Appraisal</p>
                        <p class="text-lg font-bold font-body">$22,500</p>
                    </div>
                    <button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container group-hover:text-primary transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-outline-variant/20 hover:border-primary/50">
                <div class="h-56 relative bg-neutral-200 overflow-hidden">
                    <img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" alt="tech" src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?q=80&w=800&auto=format&fit=crop"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="text-2xl font-black text-white tracking-tighter">Advantech.ai</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-outline mb-1">Current Bid</p>
                            <p class="text-xl font-bold">$15,700</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-outline mb-1">Total Bids</p>
                            <p class="text-xl font-bold">67</p>
                        </div>
                    </div>
                    <div class="mb-8 p-3 bg-surface-container-low rounded-xl border-l-2 border-primary">
                        <p class="text-[10px] uppercase tracking-widest text-outline mb-1">WHOIS Appraisal</p>
                        <p class="text-lg font-bold font-body">$68,000</p>
                    </div>
                    <button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container group-hover:text-primary transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-outline-variant/20 hover:border-primary/50">
                <div class="h-56 relative bg-neutral-200 overflow-hidden">
                    <img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" alt="network" src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80&w=800&auto=format&fit=crop"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="text-2xl font-black text-white tracking-tighter">ChatCopilot.com</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-outline mb-1">Current Bid</p>
                            <p class="text-xl font-bold">$4,500</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-outline mb-1">Total Bids</p>
                            <p class="text-xl font-bold">21</p>
                        </div>
                    </div>
                    <div class="mb-8 p-3 bg-surface-container-low rounded-xl border-l-2 border-primary">
                        <p class="text-[10px] uppercase tracking-widest text-outline mb-1">WHOIS Appraisal</p>
                        <p class="text-lg font-bold font-body">$18,500</p>
                    </div>
                    <button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container group-hover:text-primary transition-colors">
                        View Details
                    </button>
                </div>
            </div>

        </div>

        <div class="mt-16 text-center">
            <button class="px-12 py-4 border-2 border-primary text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-all">
                Load All 16 Auctions
            </button>
        </div>
    </section>
</main>

<?php // require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body>
</html>