<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
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
                        "tertiary-fixed-dim": "#454747",
                        "on-surface": "#1a1c1c",
                        "error-container": "#ffdad6",
                        "on-tertiary": "#e2e2e2",
                        "surface-bright": "#f9f9f9",
                        "secondary-fixed": "#c7c6c6",
                        "tertiary": "#3a3c3c",
                        "surface-variant": "#e2e2e2",
                        "on-primary": "#e2e2e2",
                        "surface-dim": "#dadada",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "inverse-on-surface": "#f1f1f1",
                        "on-secondary-container": "#1b1c1c",
                        "surface-container-lowest": "#ffffff",
                        "surface": "#f9f9f9",
                        "background": "#f9f9f9",
                        "on-primary-container": "#ffffff",
                        "surface-container": "#eeeeee",
                        "on-surface-variant": "#474747",
                        "primary-container": "#3b3b3b",
                        "error": "#ba1a1a",
                        "tertiary-container": "#737575",
                        "secondary-fixed-dim": "#acabab",
                        "surface-container-highest": "#e2e2e2",
                        "primary": "#000000",
                        "primary-fixed": "#5e5e5e",
                        "on-tertiary-fixed": "#ffffff",
                        "surface-tint": "#5e5e5e",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "surface-container-low": "#f3f3f3",
                        "surface-container-high": "#e8e8e8",
                        "primary-fixed-dim": "#474747",
                        "on-background": "#1a1c1c",
                        "inverse-surface": "#2f3131"
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
<style>
        body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; }
        h1, h2, h3, .font-headline { font-family: 'Manrope', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-nav { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="text-on-surface">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-32 pb-20 px-8 max-w-screen-2xl mx-auto">
<!-- Hero Section -->
<section class="mb-24 text-center">
<h1 class="text-6xl md:text-7xl font-extrabold tracking-tighter text-primary mb-6">
                Limited-Time Domain Auctions
            </h1>
<p class="text-xl text-on-surface-variant max-w-2xl mx-auto mb-12">
                Bid, Win, and Own Premium Domains via Our Dynamic Auctions Platform
            </p>
<!-- Hero Search Bar -->
<div class="max-w-3xl mx-auto relative group">
<div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-outline">search</span>
</div>
<input class="w-full pl-16 pr-40 py-6 bg-surface-container-lowest border border-outline-variant/40 rounded-full text-lg focus:ring-0 focus:border-primary transition-all shadow-lg shadow-black/5" placeholder="Search for premium domains in auction..." type="text"/>
<button class="absolute right-3 top-3 bottom-3 px-8 bg-primary text-on-primary-fixed-variant rounded-full font-bold hover:bg-primary-container transition-colors">
                    Search
                </button>
</div>
</section>
<!-- Access Card Banner -->
<section class="mb-24">
<div class="bg-surface-container-low rounded-3xl p-1 bg-[url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&amp;w=2564&amp;auto=format&amp;fit=crop')] bg-cover bg-center overflow-hidden" data-alt="abstract grayscale architectural structure with deep shadows and sharp geometric lines in a minimalist composition">
<div class="w-full h-full bg-black/60 backdrop-blur-sm p-12 md:p-16 flex flex-col md:flex-row justify-between items-center rounded-[1.4rem]">
<div class="text-center md:text-left mb-8 md:mb-0">
<h2 class="text-4xl font-bold text-white mb-2">Get Full Access To Atom Auctions</h2>
<p class="text-on-primary-fixed-variant opacity-80 text-lg">Unlock verified high-tier domains and expert appraisal metrics.</p>
</div>
<button class="px-10 py-4 bg-white text-black font-bold rounded-full hover:scale-105 transition-transform">
                        Learn More
                    </button>
</div>
</div>
</section>
<!-- Auction Grid -->
<section>
<div class="flex justify-between items-end mb-12">
<div>
<h2 class="text-3xl font-bold tracking-tight mb-2">Trending Auctions</h2>
<p class="text-on-surface-variant">Real-time bidding on highly-appraised digital assets.</p>
</div>
<div class="flex space-x-2">
<button class="p-3 rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined">filter_list</span>
</button>
<button class="p-3 rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined">sort</span>
</button>
</div>
</div>
<!-- The Bento-ish Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
<!-- Card 1: Marquise.ai -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="dramatic macro shot of a faceted diamond on a dark velvet surface with high contrast lighting and sharp reflections" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-ikeS9HhKN5GGXdWG2us1uhSpJR2wYMnEKBGbbFFHo46w83mQPtCZWxixgGAVoIYtHx3WbLl2FJK5l7Gj5N-8lWh1XWxIc3ZSlKr_3cg_GQQvfUGjgAWuhDH-EvEIcyX2_4oj43evR8cZ8fWOLrqgsk0lx3XboNg_-vud0O7Zlrm7wAv13goVyTQN8DZG_C1AnmbciVE8vSlt3MzPKpvrAoS1Jt4_2UM0mKnvdGCS5u7T2fYabD_6ifxyoL7SJ49igljrFRfzwqrt"/>
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
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- Card 2: SoulTrait.com -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="monochromatic representation of a heartbeat rhythm monitor on a clean digital screen with soft lighting effects" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB-YT4h-sjyi1pY5v4j-X_7ifDy1BTblXi71urjdDzz4PgbqLmvuYUFRTIHSt8jTXB-b9_RF8JBuXVNVyMRsV7cwVxVUb1ISI_g_NMosi25Nwpsb9Zo2PjJXI9wf8yiRWwdYPOn328ZG5ILeweDxflxN-zkZHwzhqqfMdQJKi5fTf4Awwl0DQgUCw_mEsRiRJmBj25N9ODbGK9Yz7wZ12eGOSioYdacMTah6pynChTpVc25Hss4BfSgdT2UPaPHDq7GOeVz0wZuxWYb"/>
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
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- Card 3: Advantech.ai -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="ultra-modern robotic hand interacting with a holographic interface in a minimalist high-tech lab setting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD9UAtnQFV-mydtQ10EhD6XoBZUfRjYaj_qmA9R3xncXMoL9VDrIHdon-GgoQ2bV6DJx27gwcwnkwMxeS5lfc1UVWBlu7Df1k8OlCBLhlgPoQaeNdpf3l6zzP5OZ2XkxHUsrf9mmySahhDMGzHAyBGKR3B-c0eV70viQo2FdyTiAuG0OAYeNA3kNBFkqgamsvqrIAK3AmOzqlV-VSrLRTC8lpw82F8tay9KLPtAxM6NdTY7Am672Dr8YF3hXnhkCtyAXmhlQ2fifmXv"/>
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
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- Card 4: ChatCopilot.com -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="conceptual art of network nodes connecting in a neural web structure with subtle glowing lines on dark background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDEk2DY23fAg8xFJ42siQxitmcGy_y4mh4CbE3eCFQe6CqYt8tr3tL1TQVRwLMBMvZcqwNb3iOSFCjUDbdqcMRHnvJXhN-Z0Z_x3ALi6M4l1HTY_yA_vbwyfFWAfZxWy3Ytvoz4DVlxXFHVYIFkE-yiL0joTKiQchdgHqGOzhtmyfhUTqgx6P0k02_EanocSTjgylCAZHGASPKBdv5K6E7opUsySR0vLotrGM2v8ZdBcpenmSc2jd0rWRz8105ZilfING_5ZcwQszvB"/>
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
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- Card 5: FeatHealth.com -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="minimalist studio shot of high-performance running shoes on a clean grey pedestal with dramatic top lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDiKjoxzz3aN478VgzIK8Xj2tp8zDIv_9x42rfjRvvuEZYorspAMY73zQ6yiVSv2l08Edygq2E9dO584m3zdrpEFV548-6QMF3NbvvVzUD5nQ33l2PLdD1g-j8rXjuZ8baYlQt79WkNzsfkgdWPudBAWAl4_KJCqPFdvhCm8URArt_Xzyy4dqjtufsgiTsw9gaeup-hmCTtZaZvG33mt3scXhnkxyrjyoFQFRMHjInhsV6GzlxerRXlMJ8L3CBYbpRBV5YW6ddhnVTd"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="text-2xl font-black text-white tracking-tighter">FeatHealth.com</span>
</div>
</div>
<div class="p-6">
<div class="grid grid-cols-2 gap-4 mb-6">
<div>
<p class="text-[10px] uppercase tracking-widest text-outline mb-1">Current Bid</p>
<p class="text-xl font-bold">$3,100</p>
</div>
<div>
<p class="text-[10px] uppercase tracking-widest text-outline mb-1">Total Bids</p>
<p class="text-xl font-bold">14</p>
</div>
</div>
<div class="mb-8 p-3 bg-surface-container-low rounded-xl border-l-2 border-primary">
<p class="text-[10px] uppercase tracking-widest text-outline mb-1">WHOIS Appraisal</p>
<p class="text-lg font-bold font-body">$9,800</p>
</div>
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- Additional cards (Simplified for brevity but maintaining style) -->
<!-- Card 6: eTourism.com -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="silhouetted airplane tail against a cloudy sky with sharp monochromatic tonal gradients and soft lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCvCx6QoW_JvrXSP2iEcqPDiuIKs6oVk66Q97JcuElqLwsRC8YC7r1JE4oc_4deCcY5KpvOkxejsA0U4ahGDG0NMWWaab_w5Pr1Lkci0emEB9CJtDYMczPACdEqdnBVIdoQtDA6NqEN9pmDf3aMmG4qfknEXKNOMo8d9Jb04ZLnwQAqpuxpvLHmmamtIi30BZDHF4pmXZN7STpz8x9QDIAx-JppH7Rm5bFVYd-xS_6cAlB5n8laHjyRg-I6egi9psLOKqO6qKm1o4G5"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="text-2xl font-black text-white tracking-tighter">eTourism.com</span>
</div>
</div>
<div class="p-6">
<div class="grid grid-cols-2 gap-4 mb-6">
<div>
<p class="text-xl font-bold">$21,000</p>
</div>
<div class="text-right">
<p class="text-xl font-bold">89 Bids</p>
</div>
</div>
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- Card 7: GeekFit.com -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="zen style studio shot of smooth river stones stacked in balance against a clean white backdrop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuALqWXKVFBLthlpmqvtTYc2OStp9pNrZSjvvGmNLAe_I16L49Ew9u8AVrAh_mLOMqUryN4OOuHcYsMmPYOi8VyF-2yx_KUXSKLRhARI1Cn3p7Hg3VbfvZrAaYWDXB-_3YFIbAtCiLhqnt9RV_KKs9StSzrWVyWK96-sUrR3FlWTcMB0Ec1s9v_ThmjbQrStaRPWBOXEMyu9zxD7HNMXYcccop2Pm8HxGEqu2XfpwBNhSETI7R1iAZYKlkslLm8GXRrFFGHnGdWBK1CK"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="text-2xl font-black text-white tracking-tighter">GeekFit.com</span>
</div>
</div>
<div class="p-6">
<div class="grid grid-cols-2 gap-4 mb-6">
<div>
<p class="text-xl font-bold">$1,900</p>
</div>
<div class="text-right">
<p class="text-xl font-bold">5 Bids</p>
</div>
</div>
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- Card 8: WireNetwork.com -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="abstract close-up of industrial cables and fiber optic lines woven together in a high-tech geometric pattern" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFqmZpt_bHrvKuXAp6i8i2Drjj9m11wr5Wxy0Gt3HHzXXGxNxo3e_B4uFYbiqDPCE9X1RQqcdqw8VYnpUwmCS9T-dGr7C6gDBPQTfjdYhD7ZUbyGAaXrJvFaZ9NFINsgE0LSmZuPrH3Ibp9VdtlJpkhEw8YF_TKcskfu3Az5ylZRTa73NWXo1sGdlctdRvjsD3viO2SdFqxp-hc-umOjLgIzhYjmRvd-aEjU4WO-rCiN_2M0mKt4LAA3bop1u-2SwkxMDNyeMmj8lW"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="text-2xl font-black text-white tracking-tighter">WireNetwork.com</span>
</div>
</div>
<div class="p-6">
<div class="grid grid-cols-2 gap-4 mb-6">
<div>
<p class="text-xl font-bold">$5,600</p>
</div>
<div class="text-right">
<p class="text-xl font-bold">29 Bids</p>
</div>
</div>
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- Repeatable style for remaining requested domains -->
<!-- TacticLab.com -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="top-down minimalist view of a tactical chess board with sharp shadows on each piece in black and white" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCynX8RaT9UDL74EW-6GjAc6-lzO2NdWkWfL-UdRHCZvYzf9xuJF0mf5kLElZJ0tOEpBBLgqeN6LxkqAJAv_D-37sB5o2glh8vEF_ZSJ3BaFj2v126_CVxpVzL2MDYAFEvu2Iam0pjYqA3yVv27wMuB4-v0qvnBBCRWNdO-XoPwFNg_3__aR8ctvH09xsDEWw2O4Apmr3qRimtIuauhcdrfuOum80PKF-Nzi60JcTe87eSGR8UP0irV62VctcMkXIlZiPHuddEEc7PW"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="text-2xl font-black text-white tracking-tighter">TacticLab.com</span>
</div>
</div>
<div class="p-6">
<div class="grid grid-cols-2 gap-4 mb-6">
<div>
<p class="text-xl font-bold">$2,800</p>
</div>
<div class="text-right">
<p class="text-xl font-bold">12 Bids</p>
</div>
</div>
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- CryptoGuide.ai -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="macro close-up of an integrated circuit board with intricate pathways and metallic textures in shades of grey" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDZRJHbBybA_HYmREsTZz52St3LG1xXLYOO_ZLgDaAAE_WHV9a2cP5dtAjEavvBvzo9VlXkqRW3pliWjCGPuBCqo89Bhp5c5rgCmEYoTxKZNyPb1fY4Mc6_puN2gZSVlHzorGpecVuAYM9BIbA0HHP73ibWUkon7Z9oiKalzX2st8-VC2xzDyQ-N1FgvyhXzssuNkxnmJB0CcZJ0cLw1QAoV65XqjWw8MRLbWfRpNXtdfNSME4JLel-p6vSALbMfgW1XNa-OjztUf_i"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="text-2xl font-black text-white tracking-tighter">CryptoGuide.ai</span>
</div>
</div>
<div class="p-6">
<div class="grid grid-cols-2 gap-4 mb-6">
<div>
<p class="text-xl font-bold">$9,200</p>
</div>
<div class="text-right">
<p class="text-xl font-bold">41 Bids</p>
</div>
</div>
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- RealmAI.ai -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="vast minimalist mountain landscape under a heavy grey sky with atmospheric fog and deep tonal shadows" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGqG7aluCGy9NfPCcY9HD-zbe2hwWcF2wGRNcpKBSZDMGAV7jYtPJeEW7HAu07je02lo2lbThT6Pz-1REaIxUqzSoIKRQBOoPbljUjkxKWg1FNiwpiuwzfuElJvY7gk4gY-BP8ltkgfFktVywSDaF9mOLaF6Xm7yxK5GHmEiLeDyZqa3ybnVZgSsOa_tSMK3lQ8UQ29rTRy0ditL6Vd726mNksWgZZvch4Zs7BtpL8syd0dOosT-5yY5IU-hU1H6u9FNIZoECOnSaJ"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="text-2xl font-black text-white tracking-tighter">RealmAI.ai</span>
</div>
</div>
<div class="p-6">
<div class="grid grid-cols-2 gap-4 mb-6">
<div>
<p class="text-xl font-bold">$11,500</p>
</div>
<div class="text-right">
<p class="text-xl font-bold">54 Bids</p>
</div>
</div>
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
<!-- VisionHub.ai -->
<div class="group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-transparent hover:border-outline-variant/20">
<div class="h-56 relative bg-neutral-200">
<img class="w-full h-full object-cover grayscale brightness-75 group-hover:scale-105 transition-transform duration-700" data-alt="extreme close-up of a human eye iris with hyper-realistic textures and deep contrast in black and white" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA0wCbLz5zcGJ3NvPIhS7Hd2Dga4yWqWN1uE9Gf9X-Gm0hndy0INa3Y8SbJuK-xvpXg58MXRF7VZKTF_LOUWtqf-bfc9niAwTxbjZU4FJzSzLdtoY-kbPAmqAcUGOMw_1aXj-XqOjWCr3masOmTqCHypEkMIOIdQWCWR6JM6L-MCdSnX0us1SonoChCT1h30Do_I7P6TPd_rbkW0aouwp6XDbB7uuR6S--ETzHMc56-T1K0YLDGixMbiQx7AYBCs-sYVuIOEp35tT8x"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="text-2xl font-black text-white tracking-tighter">VisionHub.ai</span>
</div>
</div>
<div class="p-6">
<div class="grid grid-cols-2 gap-4 mb-6">
<div>
<p class="text-xl font-bold">$7,300</p>
</div>
<div class="text-right">
<p class="text-xl font-bold">33 Bids</p>
</div>
</div>
<button class="w-full py-4 bg-primary text-white font-bold rounded-xl group-hover:bg-primary-container transition-colors">
                            View Details
                        </button>
</div>
</div>
</div>
<!-- Show More -->
<div class="mt-16 text-center">
<button class="px-12 py-4 border border-primary text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-all">
                    Load All 16 Auctions
                </button>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-neutral-50 dark:bg-neutral-950 w-full py-20 px-8">
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-12 max-w-screen-2xl mx-auto font-['Inter'] text-sm leading-relaxed">
<div class="col-span-2">
<div class="text-lg font-black text-black dark:text-white mb-6">WHOIS</div>
<p class="text-neutral-500 dark:text-neutral-400 mb-8 max-w-xs">The authority in premium domain acquisition and AI-driven market valuations. Secure your digital legacy.</p>
</div>
<div class="flex flex-col space-y-4">
<span class="font-semibold text-black dark:text-white mb-2">Services</span>
<a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Domain Search</a>
<a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Appraisal</a>
<a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Transfer</a>
</div>
<div class="flex flex-col space-y-4">
<span class="font-semibold text-black dark:text-white mb-2">Legal</span>
<a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Privacy</a>
<a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Terms</a>
</div>
<div class="flex flex-col space-y-4">
<span class="font-semibold text-black dark:text-white mb-2">Company</span>
<a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">About</a>
<a class="text-neutral-500 dark:text-neutral-400 hover:underline decoration-1 underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Careers</a>
</div>
<div class="col-span-full pt-12 mt-12 border-t border-neutral-200 dark:border-neutral-800 flex justify-between items-center text-neutral-400">
<span>&copy; 2024 WHOIS Authority. All rights reserved.</span>
<div class="flex space-x-6">
<span class="material-symbols-outlined text-xl cursor-pointer hover:text-black">public</span>
<span class="material-symbols-outlined text-xl cursor-pointer hover:text-black">terminal</span>
<span class="material-symbols-outlined text-xl cursor-pointer hover:text-black">security</span>
</div>
</div>
</div>
</footer>
<script src="../assets/js/nav-state.js"></script>
</body></html>




