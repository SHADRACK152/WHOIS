<?php
require_once __DIR__ . '/../../app/bootstrap.php';

$domain = isset($_GET['domain']) ? htmlspecialchars(trim($_GET['domain']), ENT_QUOTES, 'UTF-8') : '';
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic CSRF/Validation could go here
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $budget = htmlspecialchars(trim($_POST['budget'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    $targetDomain = htmlspecialchars(trim($_POST['domain'] ?? ''));

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $submitted = true;
        // Process the brokerage request (e.g., save to DB, send email to broker team)
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
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#000000",
                        "surface": "#f9f9f9",
                        "on-surface": "#1a1c1c",
                        "outline": "#777777",
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
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-surface text-on-surface selection:bg-black selection:text-white overflow-x-hidden">
    
    <?php if (file_exists(__DIR__ . '/_top_nav.php')) require __DIR__ . '/_top_nav.php'; ?>

    <main class="pt-32 pb-24 px-6 max-w-7xl mx-auto">
        
        <?php if ($submitted): ?>
            <div class="max-w-2xl mx-auto mt-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 rounded-full mb-6 shadow-sm">
                    <span class="material-symbols-outlined text-4xl text-emerald-600">check_circle</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-primary mb-4">Request Received</h1>
                <p class="text-lg text-on-surface-variant mb-8">
                    Thank you, <?php echo htmlspecialchars($name); ?>. Your confidential brokerage request for 
                    <span class="font-bold text-black"><?php echo $targetDomain; ?></span> has been securely assigned to a senior broker.
                </p>
                <div class="bg-white rounded-2xl border border-outline-variant/30 p-6 text-left shadow-sm">
                    <h3 class="font-bold text-lg mb-2">What happens next?</h3>
                    <ul class="space-y-3 text-sm text-neutral-600">
                        <li class="flex gap-3"><span class="material-symbols-outlined text-black text-[20px]">search</span> Our team will perform a quiet ownership lookup.</li>
                        <li class="flex gap-3"><span class="material-symbols-outlined text-black text-[20px]">mail</span> You will receive an email within 24 hours with an initial appraisal and strategy.</li>
                    </ul>
                </div>
                <div class="mt-8">
                    <a href="/" class="inline-block border border-neutral-300 rounded-full px-8 py-3 text-sm font-bold uppercase tracking-widest text-primary hover:border-black transition-colors">Return to Search</a>
                </div>
            </div>

        <?php else: ?>
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <div class="inline-block rounded-full bg-black px-4 py-1.5 text-[11px] font-bold tracking-widest text-white uppercase mb-6 shadow-sm">
                    Professional Acquisition
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold text-primary tracking-tight mb-6">
                    Secure the Domain You Really Want.
                </h1>
                <p class="text-lg md:text-xl text-on-surface-variant font-medium">
                    Let our expert brokers negotiate anonymously on your behalf to acquire 
                    <?php if ($domain): ?>
                        <span class="text-black font-bold px-1 underline decoration-outline-variant/50 underline-offset-4"><?php echo $domain; ?></span>
                    <?php else: ?>
                        your target domain
                    <?php endif; ?>
                    without inflating the price.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-7 space-y-12">
                    
                    <section>
                        <h2 class="text-2xl font-extrabold mb-6">Why use a domain broker?</h2>
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div class="bg-white p-6 rounded-[1.5rem] border border-outline-variant/20 shadow-sm">
                                <span class="material-symbols-outlined text-black mb-3 text-3xl">visibility_off</span>
                                <h3 class="text-lg font-bold mb-2">Absolute Privacy</h3>
                                <p class="text-sm text-neutral-600 leading-relaxed">If the current owner knows you are a funded business, the price skyrockets. We act as an anonymous proxy.</p>
                            </div>
                            <div class="bg-white p-6 rounded-[1.5rem] border border-outline-variant/20 shadow-sm">
                                <span class="material-symbols-outlined text-black mb-3 text-3xl">handshake</span>
                                <h3 class="text-lg font-bold mb-2">Expert Negotiation</h3>
                                <p class="text-sm text-neutral-600 leading-relaxed">Our brokers buy domains daily. We know true market valuations and use proven tactics to close the deal.</p>
                            </div>
                            <div class="bg-white p-6 rounded-[1.5rem] border border-outline-variant/20 shadow-sm">
                                <span class="material-symbols-outlined text-black mb-3 text-3xl">gavel</span>
                                <h3 class="text-lg font-bold mb-2">Secure Transfer</h3>
                                <p class="text-sm text-neutral-600 leading-relaxed">We handle the complex legal transfer and Escrow process to ensure your funds and the domain are completely safe.</p>
                            </div>
                            <div class="bg-white p-6 rounded-[1.5rem] border border-outline-variant/20 shadow-sm">
                                <span class="material-symbols-outlined text-black mb-3 text-3xl">money_off</span>
                                <h3 class="text-lg font-bold mb-2">Pay on Success</h3>
                                <p class="text-sm text-neutral-600 leading-relaxed">Aside from a small non-refundable assessment fee, our commission is strictly performance-based.</p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-extrabold mb-6 border-t border-neutral-200 pt-10">How it works</h2>
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-black text-white font-bold text-sm">1</div>
                                <div>
                                    <h4 class="text-base font-bold">Submit Your Request</h4>
                                    <p class="text-sm text-neutral-600 mt-1">Provide the domain name and your maximum budget via our secure form.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-neutral-200 text-black font-bold text-sm">2</div>
                                <div>
                                    <h4 class="text-base font-bold">Strategy & Outreach</h4>
                                    <p class="text-sm text-neutral-600 mt-1">Your assigned broker appraises the domain, locates the owner, and initiates discreet contact.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-neutral-200 text-black font-bold text-sm">3</div>
                                <div>
                                    <h4 class="text-base font-bold">Negotiation & Escrow</h4>
                                    <p class="text-sm text-neutral-600 mt-1">We negotiate the lowest possible price. If agreed, we handle the secure transaction via Escrow.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="lg:col-span-5 relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-neutral-200 to-neutral-100 rounded-[2.5rem] blur-xl opacity-50 z-0"></div>
                    
                    <div class="relative bg-white rounded-[2rem] border border-outline-variant/30 shadow-[0_20px_60px_rgba(0,0,0,0.08)] p-8 z-10">
                        <h3 class="text-2xl font-black mb-2">Hire a Broker</h3>
                        <p class="text-sm text-neutral-500 mb-6">No commitment required to initiate a strategy review.</p>
                        
                        <form method="post" action="" class="space-y-5">
                            
                            <div>
                                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-wide mb-1.5" for="domain">Target Domain</label>
                                <input class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 font-medium text-black placeholder:text-neutral-400 focus:bg-white focus:ring-2 focus:ring-black focus:border-black transition-all" 
                                    type="text" id="domain" name="domain" value="<?php echo $domain; ?>" <?php echo $domain ? 'readonly' : 'required'; ?> placeholder="e.g., example.com">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-wide mb-1.5" for="name">Full Name</label>
                                <input class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-black transition-all" 
                                    type="text" id="name" name="name" required placeholder="Jane Doe">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-wide mb-1.5" for="email">Work Email</label>
                                <input class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-black transition-all" 
                                    type="email" id="email" name="email" required placeholder="jane@company.com">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-wide mb-1.5" for="budget">Maximum Budget (USD)</label>
                                <select class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-black transition-all bg-white" id="budget" name="budget" required>
                                    <option value="" disabled selected>Select a range...</option>
                                    <option value="Under $500">Under $500</option>
                                    <option value="$500 - $2,000">$500 - $2,000</option>
                                    <option value="$2,000 - $5,000">$2,000 - $5,000</option>
                                    <option value="$5,000 - $10,000">$5,000 - $10,000</option>
                                    <option value="$10,000+">$10,000+</option>
                                </select>
                                <p class="text-[11px] text-neutral-500 mt-1.5">Helps us determine if a deal is realistic.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-wide mb-1.5" for="message">Additional Context (Optional)</label>
                                <textarea class="w-full border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-black transition-all" 
                                    id="message" name="message" rows="3" placeholder="Any details about your brand or why you need this name..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-black text-white font-bold text-lg py-4 rounded-xl hover:bg-neutral-800 transition-transform active:scale-[0.98] shadow-md mt-4">
                                Submit Request
                            </button>
                            
                            <p class="text-[11px] text-center text-neutral-400 mt-4 leading-tight">
                                By submitting, you agree to our Terms of Service. Your information is kept strictly confidential.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </main>

    <?php if (file_exists(__DIR__ . '/_footer.php')) require __DIR__ . '/_footer.php'; ?>

</body>
</html>