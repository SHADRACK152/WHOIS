<?php
declare(strict_types=1);

$initialDomain = trim((string) ($_GET['domain'] ?? $_GET['query'] ?? ''));
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Submit Domain | WHOIS Authority</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-high": "#e8e8e8",
                        "surface-container-lowest": "#ffffff",
                        "secondary": "#5e5e5e",
                        "on-primary": "#e2e2e2",
                        "inverse-primary": "#c6c6c6",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "tertiary-fixed-dim": "#454747",
                        "on-secondary-fixed": "#1b1c1c",
                        "surface-dim": "#dadada",
                        "secondary-container": "#d5d4d4",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed": "#ffffff",
                        "surface-bright": "#f9f9f9",
                        "on-surface-variant": "#474747",
                        "outline": "#777777",
                        "primary-fixed": "#5e5e5e",
                        "primary-container": "#3b3b3b",
                        "on-secondary-container": "#1b1c1c",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "secondary-fixed": "#c7c6c6",
                        "on-tertiary-container": "#ffffff",
                        "on-error-container": "#410002",
                        "background": "#f9f9f9",
                        "on-surface": "#1a1c1c",
                        "on-background": "#1a1c1c",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f3f3f3",
                        "tertiary": "#3a3c3c",
                        "inverse-on-surface": "#f1f1f1",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-error": "#ffffff",
                        "surface-tint": "#5e5e5e",
                        "on-primary-fixed": "#ffffff",
                        "surface-variant": "#e2e2e2",
                        "secondary-fixed-dim": "#acabab",
                        "outline-variant": "#c6c6c6",
                        "on-primary-container": "#ffffff",
                        "inverse-surface": "#2f3131",
                        "tertiary-container": "#737575",
                        "primary": "#000000",
                        "tertiary-fixed": "#5d5f5f",
                        "error-container": "#ffdad6",
                        "surface-container": "#eeeeee",
                        "surface": "#f9f9f9",
                        "surface-container-highest": "#e2e2e2",
                        "primary-fixed-dim": "#474747",
                        "on-tertiary": "#e2e2e2"
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
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        .editorial-shadow {
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.04);
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
        }
        .step-inactive { opacity: 0.5; filter: grayscale(1); }
    </style>
</head>
<body class="bg-background text-on-surface font-body antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-32 pb-24 px-6 max-w-6xl mx-auto">
<!-- Hero Header -->
<header class="text-center mb-20">
<span class="font-label text-[10px] tracking-[0.2em] uppercase text-neutral-500 mb-4 block">Auction Portal</span>
<h1 class="font-headline text-5xl md:text-6xl font-extrabold tracking-tight text-black mb-6">Auction Your Premium Domain</h1>
<p class="text-on-surface-variant text-lg max-w-2xl mx-auto leading-relaxed">Maximize your reach and get the best value through our global auction network.</p>
</header>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
<!-- Form Section -->
<form class="lg:col-span-8 space-y-16" id="auction-submission-form">
<input name="auction_type" type="hidden" value="standard" id="auction-type-input"/>
<input name="duration_days" type="hidden" value="7" id="auction-duration-input"/>
<!-- Step 1: Domain Details -->
<section class="relative">
<div class="flex items-center gap-4 mb-8">
<div class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center bg-white shadow-sm">
<span class="material-symbols-outlined text-black text-xl">language</span>
</div>
<h2 class="font-headline text-2xl font-bold tracking-tight">Step 1: Domain Details</h2>
</div>
<div class="bg-surface-container-lowest editorial-shadow rounded-xl p-8 border border-outline-variant/30 space-y-6">
<div class="space-y-2">
<label class="block text-xs font-semibold uppercase tracking-wider text-neutral-500">Domain Name</label>
<input class="w-full bg-surface-container-low border border-outline-variant/40 rounded-lg px-4 py-3 focus:ring-0 focus:border-black transition-all font-medium placeholder:text-neutral-400" id="auction-domain-name" name="domain_name" placeholder="e.g., example.com" type="text" value="<?php echo htmlspecialchars($initialDomain, ENT_QUOTES, 'UTF-8'); ?>"/>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="space-y-2">
<label class="block text-xs font-semibold uppercase tracking-wider text-neutral-500">Category</label>
<select class="w-full bg-surface-container-low border border-outline-variant/40 rounded-lg px-4 py-3 focus:ring-0 focus:border-black transition-all appearance-none cursor-pointer" id="auction-category" name="category">
<option>Technology</option>
<option>Finance</option>
<option>Real Estate</option>
<option>Entertainment</option>
</select>
</div>
<div class="space-y-2">
<label class="block text-xs font-semibold uppercase tracking-wider text-neutral-500">Keywords</label>
<input class="w-full bg-surface-container-low border border-outline-variant/40 rounded-lg px-4 py-3 focus:ring-0 focus:border-black transition-all" id="auction-keywords" name="keywords" placeholder="Short description..." type="text"/>
</div>
</div>
</div>
</section>
<!-- Step 2: Valuation & Pricing -->
<section class="relative">
<div class="flex items-center gap-4 mb-8">
<div class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center bg-white shadow-sm">
<span class="material-symbols-outlined text-black text-xl">payments</span>
</div>
<h2 class="font-headline text-2xl font-bold tracking-tight">Step 2: Valuation &amp; Pricing</h2>
</div>
<div class="bg-surface-container-lowest editorial-shadow rounded-xl p-8 border border-outline-variant/30 space-y-8">
<div class="flex items-center justify-between p-4 bg-surface-container-low rounded-lg border-l-4 border-black">
<div>
<p class="text-xs font-bold text-black uppercase tracking-widest mb-1">WHOIS Appraisal Estimate</p>
<p class="text-sm text-on-surface-variant italic">Estimated value based on historical sales data</p>
</div>
<div class="text-right">
<span class="font-headline text-2xl font-extrabold text-black">$4,250 - $6,100</span>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="space-y-2">
<label class="block text-xs font-semibold uppercase tracking-wider text-neutral-500">Reserve Price</label>
<div class="relative">
<span class="absolute left-4 top-3 text-neutral-400">$</span>
<input class="w-full bg-surface-container-low border border-outline-variant/40 rounded-lg pl-8 pr-4 py-3 focus:ring-0 focus:border-black" id="auction-reserve-price" name="reserve_price" placeholder="Optional" type="number"/>
</div>
</div>
<div class="space-y-2">
<label class="block text-xs font-semibold uppercase tracking-wider text-neutral-500">Buy It Now (BIN)</label>
<div class="relative">
<span class="absolute left-4 top-3 text-neutral-400">$</span>
<input class="w-full bg-surface-container-low border border-outline-variant/40 rounded-lg pl-8 pr-4 py-3 focus:ring-0 focus:border-black" id="auction-bin-price" name="bin_price" placeholder="Optional" type="number"/>
</div>
</div>
<div class="space-y-2">
<label class="block text-xs font-semibold uppercase tracking-wider text-neutral-500">Starting Bid</label>
<div class="relative">
<span class="absolute left-4 top-3 text-neutral-400">$</span>
<input class="w-full bg-surface-container-low border border-outline-variant/40 rounded-lg pl-8 pr-4 py-3 focus:ring-0 focus:border-black" id="auction-starting-bid" name="starting_bid" placeholder="100" type="number"/>
</div>
</div>
</div>
</div>
</section>
<!-- Step 3: Auction Configuration -->
<section class="relative">
<div class="flex items-center gap-4 mb-8">
<div class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center bg-white shadow-sm">
<span class="material-symbols-outlined text-black text-xl">schedule</span>
</div>
<h2 class="font-headline text-2xl font-bold tracking-tight">Step 3: Auction Configuration</h2>
</div>
<div class="bg-surface-container-lowest editorial-shadow rounded-xl p-8 border border-outline-variant/30">
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
<div class="space-y-4">
<p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Auction Type</p>
<div class="grid grid-cols-1 gap-3">
<label class="flex items-center gap-3 p-4 rounded-lg border border-black bg-surface-container-low cursor-pointer">
<input checked="" class="text-black focus:ring-0" name="auction_type_option" type="radio" value="standard"/>
<div>
<p class="font-semibold text-sm">Standard Listing</p>
<p class="text-xs text-on-surface-variant">2.5% Success Fee</p>
</div>
</label>
<label class="flex items-center gap-3 p-4 rounded-lg border border-outline-variant/40 hover:border-black transition-all cursor-pointer">
<input class="text-black focus:ring-0" name="auction_type_option" type="radio" value="premium_feature"/>
<div>
<p class="font-semibold text-sm">Premium Feature</p>
<p class="text-xs text-on-surface-variant">Home page placement + Newsletter inclusion</p>
</div>
</label>
</div>
</div>
<div class="space-y-4">
<p class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Duration &amp; Scheduling</p>
<div class="grid grid-cols-3 gap-2 mb-4">
<button class="py-3 text-xs font-bold border border-outline-variant/40 rounded-lg hover:border-black transition-all" data-auction-duration="3" type="button">3 DAYS</button>
<button class="py-3 text-xs font-bold border border-black bg-black text-white rounded-lg" data-auction-duration="7" type="button">7 DAYS</button>
<button class="py-3 text-xs font-bold border border-outline-variant/40 rounded-lg hover:border-black transition-all" data-auction-duration="14" type="button">14 DAYS</button>
</div>
<div class="space-y-2">
<label class="block text-[10px] font-bold text-neutral-400 uppercase">Start Date</label>
<input class="w-full bg-surface-container-low border border-outline-variant/40 rounded-lg px-4 py-2 text-sm focus:ring-0 focus:border-black" id="auction-start-date" name="start_date" type="date"/>
</div>
</div>
</div>
<!-- Submission Confirmation -->
<div class="pt-10 border-t border-outline-variant/40">
<div class="bg-black p-8 rounded-xl text-white flex flex-col md:flex-row justify-between items-center gap-6">
<div class="text-center md:text-left">
<h3 class="text-xl font-bold mb-1">Ready to launch?</h3>
<p class="text-neutral-400 text-sm">Your auction will be visible to 100k+ global buyers.</p>
</div>
<button class="w-full md:w-auto bg-white text-black px-10 py-4 rounded-full font-bold text-sm tracking-tight hover:bg-neutral-200 transition-colors" type="submit">
                                    Submit to Auction
                                </button>
</div>
<p class="mt-4 text-sm text-center md:text-left text-neutral-500" id="auction-submit-feedback" aria-live="polite"></p>
</div>
</div>
</section>
</div>
<!-- Sidebar Info -->
<div class="lg:col-span-4 space-y-8">
<div class="bg-surface-container-low rounded-2xl p-8 sticky top-24 border border-outline-variant/20">
<h3 class="font-headline text-lg font-extrabold mb-6">Why Auction with WHOIS?</h3>
<ul class="space-y-6">
<li class="flex gap-4">
<span class="material-symbols-outlined text-black text-xl">verified_user</span>
<div>
<p class="font-semibold text-sm">ICANN Accredited</p>
<p class="text-xs text-on-surface-variant mt-1 leading-relaxed">Full legal compliance and secure escrow transfer for all transactions.</p>
</div>
</li>
<li class="flex gap-4">
<span class="material-symbols-outlined text-black text-xl">public</span>
<div>
<p class="font-semibold text-sm">Global Buyer Network</p>
<p class="text-xs text-on-surface-variant mt-1 leading-relaxed">Instant access to 100,000+ domain investors and end-users worldwide.</p>
</div>
</li>
<li class="flex gap-4">
<span class="material-symbols-outlined text-black text-xl">support_agent</span>
<div>
<p class="font-semibold text-sm">24/7 Concierge Support</p>
<p class="text-xs text-on-surface-variant mt-1 leading-relaxed">Dedicated brokers available to help you close high-value sales.</p>
</div>
</li>
</ul>
<div class="mt-12 rounded-xl overflow-hidden grayscale contrast-125 brightness-110">
<img alt="Data visualization" class="w-full h-40 object-cover" data-alt="Modern minimalist data visualization chart on a computer screen in a bright office setting with soft shadows" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4Hnd9sfrN26UkRAzEsnTBu-XoCF8I6-iPmcPMpp1jG-F5KamClmYTrBWTOUPyo_UpJtB3Lm1Db__Ucs_T_SyVvG--rJkSXXBtLxXW6r-IKKD6pPqSMmOqUIxxIKfrpwHuszpv3omE1p39zhMvcQ39e7f3YivC87Cg29Ghq-4Gl9z_YI6DoPnIzI769M6JIAoNn18_ecqyCHB_GOrLPkumXnOFgqVkI36t9uHG6FX_VzCW0VHaHFbD4680swndBAk_VHyBm2Gkiswc"/>
</div>
</div>
</div>
</div>
</form>
</main>
<!-- Footer -->
<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
<script>
(function () {
  const form = document.getElementById('auction-submission-form');
  const feedback = document.getElementById('auction-submit-feedback');
  const auctionTypeInput = document.getElementById('auction-type-input');
  const durationInput = document.getElementById('auction-duration-input');
  const durationButtons = Array.from(document.querySelectorAll('[data-auction-duration]'));
  const typeInputs = Array.from(document.querySelectorAll('input[name="auction_type_option"]'));

  function setFeedback(message, isError) {
    if (!feedback) {
      return;
    }

    feedback.textContent = message;
    feedback.className = 'mt-4 text-sm text-center md:text-left ' + (isError ? 'text-red-400' : 'text-emerald-300');
  }

  function syncDuration(activeValue) {
    if (durationInput) {
      durationInput.value = String(activeValue);
    }

    durationButtons.forEach((button) => {
      const isActive = button.dataset.auctionDuration === String(activeValue);
      button.classList.toggle('border-black', isActive);
      button.classList.toggle('bg-black', isActive);
      button.classList.toggle('text-white', isActive);
      button.classList.toggle('border-outline-variant/40', !isActive);
    });
  }

  function syncAuctionType() {
    const active = typeInputs.find((input) => input.checked);

    if (auctionTypeInput) {
      auctionTypeInput.value = active?.value || 'standard';
    }
  }

  durationButtons.forEach((button) => {
    button.addEventListener('click', () => {
      syncDuration(button.dataset.auctionDuration || '7');
    });
  });

  typeInputs.forEach((input) => {
    input.addEventListener('change', syncAuctionType);
  });

  syncDuration(durationInput?.value || '7');
  syncAuctionType();

  form?.addEventListener('submit', async (event) => {
    event.preventDefault();

    const payload = {
      domain_name: document.getElementById('auction-domain-name')?.value || '',
      category: document.getElementById('auction-category')?.value || '',
      keywords: document.getElementById('auction-keywords')?.value || '',
      reserve_price: document.getElementById('auction-reserve-price')?.value || '',
      bin_price: document.getElementById('auction-bin-price')?.value || '',
      starting_bid: document.getElementById('auction-starting-bid')?.value || '',
      auction_type: auctionTypeInput?.value || 'standard',
      duration_days: durationInput?.value || '7',
      start_date: document.getElementById('auction-start-date')?.value || '',
    };

    try {
      setFeedback('Submitting...', false);

      const response = await fetch('../api/submissions.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (!response.ok || !result.ok) {
        throw new Error(result.error || 'Unable to submit domain.');
      }

      setFeedback('Submission saved. It is now queued in the Neon database.', false);
      form.reset();
      syncDuration('7');
      syncAuctionType();
    } catch (error) {
      setFeedback(error instanceof Error ? error.message : 'Unable to submit domain.', true);
    }
  });
})();
</script>
</body></html>




