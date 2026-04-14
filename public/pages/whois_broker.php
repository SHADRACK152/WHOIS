<?php
require_once __DIR__ . '/../../app/bootstrap.php';

$domain = isset($_GET['domain']) ? htmlspecialchars(trim($_GET['domain']), ENT_QUOTES, 'UTF-8') : '';
$submitted = false;
$selectedTier = 'standard';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic CSRF/Validation could go here
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $budget = htmlspecialchars(trim($_POST['budget'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    $targetDomain = htmlspecialchars(trim($_POST['domain'] ?? ''));
    $selectedTier = htmlspecialchars(trim($_POST['service_tier'] ?? 'standard'));

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $submitted = true;
        // Process the brokerage request & payment (e.g., Stripe API, save to DB)
        // Simulate processing for now.
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Hire a Domain Broker <?php echo $domain ? '- ' . $domain : ''; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800;900&family=Inter:wght@400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#000000",
                        surface: "#f6f7f9",
                        "on-surface": "#1a1c1c",
                        outline: "#777777",
                        "outline-variant": "#c6c6c6",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f3f3",
                        "surface-container-high": "#e8e8e8",
                        "on-surface-variant": "#474747"
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle;}
        body { font-family: 'Inter', sans-serif; background-color: #f6f7f9;}
        h1, h2, h3, h4 { font-family: 'Manrope', sans-serif; }
        
        /* Custom Radio Button Styling for Tiers */
        .tier-radio:checked + div {
            border-color: #000000;
            background-color: #f9fafb;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .tier-radio:checked + div .check-icon {
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>
<body class="text-on-surface antialiased selection:bg-black selection:text-white overflow-x-hidden pb-24">
    
    <?php if (file_exists(__DIR__ . '/_top_nav.php')) require __DIR__ . '/_top_nav.php'; ?>

    <main class="pt-32 px-6 max-w-7xl mx-auto">
        
        <?php if ($submitted): ?>
            <div class="max-w-2xl mx-auto mt-12 text-center bg-white p-12 rounded-[2rem] border border-outline-variant/30 shadow-sm">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-emerald-50 rounded-full mb-6 border border-emerald-100 shadow-inner">
                    <span class="material-symbols-outlined text-5xl text-emerald-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-primary mb-4 tracking-tight">Request & Payment Received</h1>
                <p class="text-lg text-on-surface-variant mb-8 leading-relaxed">
                    Thank you, <?php echo htmlspecialchars($name); ?>. Your <span class="font-bold text-black uppercase"><?php echo $selectedTier; ?></span> brokerage intake fee has been processed, and your case for 
                    <span class="font-bold text-black border-b-2 border-emerald-400"><?php echo $targetDomain; ?></span> has been securely assigned to a senior broker.
                </p>
                <div class="bg-surface-container-low rounded-2xl border border-outline-variant/30 p-8 text-left shadow-sm mb-8">
                    <h3 class="font-extrabold text-xl mb-4 tracking-tight">Your Next Steps</h3>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <span class="material-symbols-outlined text-primary text-2xl">policy</span>
                            <div>
                                <p class="font-bold text-primary">1. Confidentiality Lock</p>
                                <p class="text-sm text-neutral-600 mt-1">We have locked your target domain in our system. Do not perform public WHOIS searches to avoid triggering automated price hikes.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="material-symbols-outlined text-primary text-2xl">person_search</span>
                            <div>
                                <p class="font-bold text-primary">2. Owner Investigation</p>
                                <p class="text-sm text-neutral-600 mt-1">Our team is utilizing proprietary tools to bypass privacy protection and locate the ultimate decision-maker.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="material-symbols-outlined text-primary text-2xl">mail</span>
                            <div>
                                <p class="font-bold text-primary">3. Strategy Briefing</p>
                                <p class="text-sm text-neutral-600 mt-1">You will receive an email within 24-48 hours detailing our initial appraisal and the opening negotiation strategy.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="/" class="inline-block bg-primary text-white rounded-xl px-10 py-4 font-bold tracking-wide hover:bg-neutral-800 transition-colors shadow-md">Return to Dashboard</a>
                </div>
            </div>

        <?php else: ?>
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 rounded-full bg-surface-container-low border border-outline-variant/40 px-4 py-1.5 text-[10px] font-bold tracking-widest text-primary uppercase mb-6 shadow-sm">
                    <span class="material-symbols-outlined text-[14px]">shield_person</span> Anonymous Acquisition
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-primary tracking-tighter mb-6 leading-tight">
                    Secure the Domain You Really Want.
                </h1>
                <p class="text-lg md:text-xl text-on-surface-variant font-medium leading-relaxed">
                    Let our elite brokers negotiate discreetly on your behalf to acquire 
                    <?php if ($domain): ?>
                        <span class="text-black font-black px-1 border-b-2 border-black"><?php echo $domain; ?></span>
                    <?php else: ?>
                        your target domain
                    <?php endif; ?>
                    without signaling your budget or inflating the price.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <div class="lg:col-span-6 space-y-10">
                    
                    <section class="bg-white p-8 rounded-[2rem] border border-outline-variant/30 shadow-sm">
                        <h2 class="text-2xl font-extrabold mb-6 tracking-tight">The Brokerage Process</h2>
                        <div class="space-y-6">
                            <div class="flex gap-5">
                                <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <span class="material-symbols-outlined text-[20px]">search</span>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-primary">1. Discovery & Appraisal</h4>
                                    <p class="text-sm text-neutral-600 mt-1.5 leading-relaxed">We bypass WHOIS privacy to locate the true owner and provide you with an honest, data-driven maximum valuation.</p>
                                </div>
                            </div>
                            <div class="flex gap-5">
                                <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-primary">2. Stealth Negotiation</h4>
                                    <p class="text-sm text-neutral-600 mt-1.5 leading-relaxed">We act as an anonymous proxy. The seller never knows your identity or company, preventing corporate price gouging.</p>
                                </div>
                            </div>
                            <div class="flex gap-5">
                                <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="material-symbols-outlined text-[20px]">gavel</span>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-primary">3. Secure Escrow Transfer</h4>
                                    <p class="text-sm text-neutral-600 mt-1.5 leading-relaxed">Once a price is agreed upon, we manage the legal transfer of the domain and funds through our secure Escrow partners.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/30">
                        <h2 class="text-lg font-extrabold mb-4 uppercase tracking-widest text-primary text-[11px]">Pricing Transparency</h2>
                        <p class="text-sm text-on-surface-variant mb-6 leading-relaxed">
                            Brokerage requires significant manual research and outreach. To ensure we dedicate our resources to serious buyers, we charge a small non-refundable intake fee to begin the process.
                        </p>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-outline-variant/20">
                                <span class="text-sm font-bold text-primary">Initial Intake Fee</span>
                                <span class="text-sm text-neutral-600">Paid today (See tiers)</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-outline-variant/20">
                                <span class="text-sm font-bold text-primary">Success Commission</span>
                                <span class="text-sm text-neutral-600">15% of final purchase price</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-primary">Escrow Fees</span>
                                <span class="text-sm text-neutral-600">Split 50/50 with seller</span>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="lg:col-span-6 relative">
                    <div class="absolute -inset-1 bg-gradient-to-br from-neutral-200 to-transparent rounded-[2.5rem] blur-xl opacity-60 z-0"></div>
                    
                    <div class="relative bg-white rounded-[2rem] border border-outline-variant/40 shadow-xl p-8 z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-2xl font-black tracking-tight">Initiate Acquisition</h3>
                                <p class="text-xs text-neutral-500 mt-1 font-medium">Complete the secure intake form below.</p>
                            </div>
                            <span class="material-symbols-outlined text-emerald-600 text-3xl">lock</span>
                        </div>
                        
                        <form method="post" action="" class="space-y-6" id="brokerForm">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-neutral-700 uppercase tracking-widest mb-1.5" for="domain">Target Domain</label>
                                    <input class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-bold text-primary focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
                                        type="text" id="domain" name="domain" value="<?php echo $domain; ?>" <?php echo $domain ? 'readonly' : 'required'; ?> placeholder="e.g., mybrand.com">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-neutral-700 uppercase tracking-widest mb-1.5" for="name">Full Name</label>
                                        <input class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
                                            type="text" id="name" name="name" required placeholder="Jane Doe">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-neutral-700 uppercase tracking-widest mb-1.5" for="email">Work Email</label>
                                        <input class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
                                            type="email" id="email" name="email" required placeholder="jane@company.com">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-neutral-700 uppercase tracking-widest mb-3">Select Service Tier</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="service_tier" value="standard" class="peer sr-only tier-radio" checked onchange="updateTotal(69)">
                                        <div class="border-2 border-outline-variant/30 rounded-xl p-4 transition-all hover:border-neutral-400 h-full flex flex-col">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="font-bold text-primary">Standard</span>
                                                <span class="font-black text-primary">$69</span>
                                            </div>
                                            <p class="text-[10px] text-neutral-500 leading-relaxed">Dedicated broker, standard outreach, up to 30 days of negotiation.</p>
                                            <span class="material-symbols-outlined absolute top-4 right-4 text-primary opacity-0 scale-50 transition-all check-icon" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="service_tier" value="corporate" class="peer sr-only tier-radio" onchange="updateTotal(199)">
                                        <div class="border-2 border-outline-variant/30 rounded-xl p-4 transition-all hover:border-neutral-400 h-full flex flex-col relative overflow-hidden">
                                            <div class="absolute top-0 left-0 w-full h-1 bg-amber-400"></div>
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="font-bold text-primary">Corporate</span>
                                                <span class="font-black text-primary">$199</span>
                                            </div>
                                            <p class="text-[10px] text-neutral-500 leading-relaxed">NDA protection, urgent priority, custom acquisition strategy & legal prep.</p>
                                            <span class="material-symbols-outlined absolute top-4 right-4 text-primary opacity-0 scale-50 transition-all check-icon" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-neutral-700 uppercase tracking-widest mb-1.5" for="budget">Acquisition Budget</label>
                                <select class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-white" id="budget" name="budget" required>
                                    <option value="" disabled selected>Select your maximum budget...</option>
                                    <option value="$1k - $5k">$1,000 - $5,000</option>
                                    <option value="$5k - $25k">$5,000 - $25,000</option>
                                    <option value="$25k - $100k">$25,000 - $100,000</option>
                                    <option value="$100k+">$100,000+</option>
                                </select>
                            </div>

                            <div class="pt-6 border-t border-outline-variant/30">
                                <label class="flex items-center gap-2 text-[10px] font-bold text-neutral-700 uppercase tracking-widest mb-4">
                                    <span class="material-symbols-outlined text-[14px]">credit_card</span> Secure Payment
                                </label>
                                
                                <div class="space-y-4">
                                    <div class="relative">
                                        <input type="text" class="w-full border border-outline-variant/50 rounded-xl pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all font-mono placeholder:font-sans" placeholder="Card Number" required>
                                        <span class="material-symbols-outlined absolute left-3 top-3 text-neutral-400 text-[20px]">payment</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="text" class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all text-center" placeholder="MM / YY" required>
                                        <input type="text" class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all text-center" placeholder="CVC" required>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 pb-2">
                                <span class="font-bold text-primary">Total Due Today</span>
                                <span class="text-3xl font-black text-primary" id="totalPriceDisplay">$69.00</span>
                            </div>

                            <button type="submit" class="w-full bg-primary text-white font-bold text-lg py-4 rounded-xl hover:bg-neutral-800 transition-transform active:scale-[0.98] shadow-lg flex justify-center items-center gap-2 group">
                                Pay & Initiate Brokerage <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </button>
                            
                            <div class="flex items-center justify-center gap-2 text-[10px] text-neutral-400 mt-4 uppercase tracking-widest font-bold">
                                <span class="material-symbols-outlined text-[14px]">lock</span> 256-Bit SSL Encrypted
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </main>

    <?php if (file_exists(__DIR__ . '/_footer.php')) require __DIR__ . '/_footer.php'; ?>

    <script>
        // Simple script to update the total price based on the selected tier
        function updateTotal(amount) {
            const display = document.getElementById('totalPriceDisplay');
            if(display) {
                display.innerHTML = '$' + amount + '.00';
                
                // Small animation effect
                display.style.transform = 'scale(1.1)';
                display.style.color = '#10b981'; // emerald-500
                setTimeout(() => {
                    display.style.transform = 'scale(1)';
                    display.style.color = '#000000';
                }, 200);
            }
        }
    </script>
</body>
</html>