<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/db-client.php';

$domain = strtolower(trim((string) ($_GET['domain'] ?? '')));
$type = strtolower(trim((string) ($_GET['type'] ?? 'bid'))); // bid or offer
$item = $domain !== '' ? whois_db_get_marketplace_item_by_domain($domain) : null;
$price = $item ? (float) ($item['price'] ?? 0) : 0;
$badgeText = $item ? (string) ($item['badge_text'] ?? 'Available') : '';
$isLive = $item && strtolower((string) ($item['status'] ?? 'live')) === 'live';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>WHOIS | <?php echo $type === 'offer' ? 'Make An Offer' : 'Submit Bid'; ?></title>
    <?php require __DIR__ . '/_head.php'; ?>
    <style>
        .bid-card {
            background: #ffffff;
            border: 1px solid rgba(198, 198, 198, 0.4);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased selection:bg-primary/20 selection:text-primary">
    <?php require __DIR__ . '/_top_nav.php'; ?>

    <main class="pt-32 pb-20 px-6">
        <div class="mx-auto max-w-4xl">
            <div class="mb-12 text-center">
                <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-[10px] font-bold uppercase tracking-widest mb-4">
                    <?php echo $type === 'offer' ? 'Direct Negotiation' : 'Marketplace Auction'; ?>
                </span>
                <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-black mb-4">
                    <?php echo $type === 'offer' ? 'Make An Offer' : 'Place Your Bid'; ?>
                </h1>
                <p class="text-lg text-on-surface-variant max-w-2xl mx-auto">
                    Submit your interest for <span class="font-bold text-black"><?php echo htmlspecialchars($domain ?: 'this asset', ENT_QUOTES, 'UTF-8'); ?></span>. 
                    All submissions are reviewed by our brokerage team within 24 hours.
                </p>
            </div>

            <?php if (!$item): ?>
                <div class="bid-card rounded-[2.5rem] p-12 text-center">
                    <span class="material-symbols-outlined text-4xl text-neutral-300 mb-4">search_off</span>
                    <p class="text-secondary font-bold uppercase tracking-widest text-sm italic">Domain Asset Not Found</p>
                    <a href="whois_premium_domain_marketplace.php" class="mt-6 inline-block text-primary font-bold text-sm underline underline-offset-4 tracking-tight">Return to Marketplace</a>
                </div>
            <?php elseif (!$isLive): ?>
                <div class="bid-card rounded-[2.5rem] p-12 text-center">
                    <span class="material-symbols-outlined text-4xl text-emerald-400 mb-4">verified</span>
                    <p class="text-secondary font-bold uppercase tracking-widest text-sm italic">Asset Already Secured</p>
                    <p class="mt-2 text-on-surface-variant text-sm tracking-tight">This domain has transitioned to a new owner and is no longer open for active bidding.</p>
                    <a href="whois_premium_domain_marketplace.php" class="mt-8 inline-block text-primary font-bold text-sm underline underline-offset-4 tracking-tight">Browse Live Inventory</a>
                </div>
            <?php else: ?>
                <div class="grid lg:grid-cols-5 gap-8">
                    <!-- Form Side -->
                    <div class="lg:col-span-3">
                        <form id="bid-form" class="bid-card rounded-[2.5rem] p-8 md:p-10 space-y-8">
                            <input type="hidden" name="domain_name" value="<?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?>"/>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-secondary" for="bidder-name">Full Name</label>
                                    <input class="w-full rounded-2xl border-none bg-surface-container-low px-5 py-4 text-sm focus:ring-2 focus:ring-primary/20" id="bidder-name" name="bidder_name" type="text" placeholder="John Doe" required/>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-secondary" for="bidder-email">Email Address</label>
                                    <input class="w-full rounded-2xl border-none bg-surface-container-low px-5 py-4 text-sm focus:ring-2 focus:ring-primary/20" id="bidder-email" name="bidder_email" type="email" placeholder="john@company.com" required/>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-secondary" for="bid-amount">
                                    <?php echo $type === 'offer' ? 'Your Offer' : 'Bid Amount'; ?> (USD)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 font-bold text-neutral-400 text-lg">$</span>
                                    <input class="w-full rounded-2xl border-none bg-surface-container-low pl-10 pr-5 py-6 text-2xl font-black focus:ring-2 focus:ring-primary/20" id="bid-amount" name="bid_amount" type="number" step="0.01" min="1" placeholder="0.00" required/>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-secondary" for="bid-note">Brokerage Note (Optional)</label>
                                <textarea class="w-auto w-full rounded-2xl border-none bg-surface-container-low px-5 py-4 text-sm focus:ring-2 focus:ring-primary/20 min-h-[120px]" id="bid-note" name="note" placeholder="Tell us about your project or preferred timeline..."></textarea>
                            </div>

                            <button class="w-full rounded-full bg-black py-5 text-sm font-black uppercase tracking-widest text-white hover:bg-neutral-800 transition-all transform active:scale-95 shadow-xl shadow-black/5" type="submit">
                                <?php echo $type === 'offer' ? 'Submit Official Offer' : 'Confirm & Place Bid'; ?>
                            </button>
                            
                            <p id="bid-status" class="text-center text-xs font-bold uppercase tracking-widest text-primary"></p>
                        </form>
                    </div>

                    <!-- Info Side -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bid-card rounded-[2.5rem] p-8">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-secondary mb-3">Asset Snapshot</p>
                            <h3 class="text-2xl font-black tracking-tight mb-2"><?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <div class="flex items-center gap-2 mb-8">
                                <span class="px-3 py-1 bg-amber-100 text-amber-800 text-[8px] font-bold uppercase tracking-widest rounded"><?php echo htmlspecialchars($badgeText, ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="text-xs text-secondary font-medium">Verified Inventory</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl">
                                    <span class="text-xs text-secondary font-bold uppercase tracking-widest">Listing Price</span>
                                    <span id="current-price" class="text-lg font-black text-primary">$<?php echo number_format($price, 2); ?></span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl">
                                    <span class="text-xs text-secondary font-bold uppercase tracking-widest">Commission</span>
                                    <span class="text-sm font-bold">$0.00 <span class="text-[10px] text-emerald-500">(Seller Pays)</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 bg-neutral-900 rounded-[2.5rem] text-white space-y-6 relative overflow-hidden">
                            <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-primary/20 blur-3xl"></div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-primary">Trust & Safety</h4>
                            <div class="space-y-4">
                                <div class="flex gap-4">
                                    <span class="material-symbols-outlined text-primary text-xl">shield</span>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-widest mb-1">Escrow Secured</p>
                                        <p class="text-xs text-neutral-400 leading-relaxed">Funds are held by WHOIS Escrow until the asset is fully transferred to your registrar.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <span class="material-symbols-outlined text-primary text-xl">bolt</span>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-widest mb-1">Instant Transfer</p>
                                        <p class="text-xs text-neutral-400 leading-relaxed">98% of our premium assets are transferred within 24 hours of payment verification.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require __DIR__ . '/_footer.php'; ?>

    <script>
    (() => {
        const form = document.getElementById('bid-form');
        const status = document.getElementById('bid-status');
        const currentPriceElem = document.getElementById('current-price');

        if (!form) return;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            
            status.textContent = 'Processing request...';
            btn.disabled = true;
            btn.style.opacity = '0.7';

            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('../api/bids.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });

                const result = await response.json();

                if (!response.ok || !result.ok) {
                    status.textContent = result.error || 'Unable to process submission.';
                    status.className = 'text-center text-xs font-bold uppercase tracking-widest text-red-500 mt-4';
                } else {
                    const latestPrice = Number(result.item?.price || 0);
                    status.textContent = 'Submission successful. Your account representative will contact you shortly.';
                    status.className = 'text-center text-xs font-bold uppercase tracking-widest text-emerald-500 mt-4';
                    
                    if (currentPriceElem) {
                        currentPriceElem.textContent = '$' + latestPrice.toLocaleString(undefined, {minimumFractionDigits: 2});
                    }
                    form.reset();
                }
            } catch (err) {
                status.textContent = 'Network error. Please try again.';
                status.className = 'text-center text-xs font-bold uppercase tracking-widest text-red-500 mt-4';
            } finally {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = originalText;
            }
        });
    })();
    </script>
    <script src="../assets/js/nav-state.js"></script>
</body>
</html>
