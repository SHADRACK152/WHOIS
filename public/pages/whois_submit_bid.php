<?php
declare(strict_types=1);

require __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/db-client.php';

$domain = strtolower(trim((string) ($_GET['domain'] ?? '')));
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
<title>WHOIS | Submit Bid</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>
<body class="bg-surface text-on-surface-variant">
<?php require __DIR__ . '/_top_nav.php'; ?>

<main class="mx-auto max-w-5xl px-6 py-20">
  <section class="rounded-3xl border border-outline-variant/30 bg-white p-8 shadow-[0_20px_60px_rgba(0,0,0,0.06)]">
    <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
      <div>
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Submit Bid</p>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-primary">Bid on <?php echo htmlspecialchars($domain !== '' ? $domain : 'a domain', ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="mt-3 text-sm text-on-surface-variant">Place a bid above or below the current price. The listing price updates to the latest bid.</p>
      </div>
      <?php if ($item): ?>
        <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-lowest px-5 py-4 text-right">
          <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Current Price</p>
          <p id="current-price" class="mt-2 text-2xl font-black text-primary">$<?php echo number_format($price, 2); ?></p>
          <p id="current-badge" class="mt-1 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500"><?php echo htmlspecialchars($badgeText, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>
    </div>

    <?php if (!$item): ?>
      <div class="mt-6 rounded-2xl border border-dashed border-outline-variant/40 bg-surface-container-lowest p-6 text-sm text-secondary">This domain is not listed in the marketplace.</div>
    <?php elseif (!$isLive): ?>
      <div class="mt-6 rounded-2xl border border-dashed border-outline-variant/40 bg-surface-container-lowest p-6 text-sm text-secondary">This domain has already been marked as sold and is no longer accepting bids.</div>
    <?php else: ?>
      <form id="bid-form" class="mt-8 grid gap-4 md:grid-cols-2">
        <input type="hidden" name="domain_name" value="<?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?>"/>
        <div>
          <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500" for="bidder-name">Your name</label>
          <input class="w-full rounded-xl border border-outline-variant/40 bg-surface-container-lowest px-4 py-3 text-sm focus:border-black focus:ring-0" id="bidder-name" name="bidder_name" type="text" placeholder="Jane Doe"/>
        </div>
        <div>
          <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500" for="bidder-email">Email</label>
          <input class="w-full rounded-xl border border-outline-variant/40 bg-surface-container-lowest px-4 py-3 text-sm focus:border-black focus:ring-0" id="bidder-email" name="bidder_email" type="email" placeholder="jane@company.com"/>
        </div>
        <div>
          <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500" for="bid-amount">Bid amount (USD)</label>
          <input class="w-full rounded-xl border border-outline-variant/40 bg-surface-container-lowest px-4 py-3 text-sm focus:border-black focus:ring-0" id="bid-amount" name="bid_amount" type="number" step="0.01" min="1" placeholder="1500" required/>
        </div>
        <div>
          <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500" for="bid-note">Notes</label>
          <input class="w-full rounded-xl border border-outline-variant/40 bg-surface-container-lowest px-4 py-3 text-sm focus:border-black focus:ring-0" id="bid-note" name="note" type="text" placeholder="Preferred closing timeline"/>
        </div>
        <div class="md:col-span-2 flex flex-wrap items-center gap-3">
          <button class="rounded-full bg-primary px-8 py-3 text-xs font-bold uppercase tracking-[0.2em] text-on-primary" type="submit">Submit Bid</button>
          <span id="bid-status" class="text-sm text-on-surface-variant"></span>
        </div>
      </form>
    <?php endif; ?>
  </section>
</main>

<script>
(() => {
  const form = document.getElementById('bid-form');
  const status = document.getElementById('bid-status');
  const currentPrice = document.getElementById('current-price');
  const currentBadge = document.getElementById('current-badge');

  if (!form) return;

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (status) status.textContent = 'Submitting bid...';

    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    const response = await fetch('/api/bids.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const result = await response.json();

    if (!response.ok || !result.ok) {
      if (status) status.textContent = result.error || 'Unable to submit bid.';
      return;
    }

    if (status) {
      const previousPrice = Number(currentPrice?.textContent?.replace('$', '').replace(/,/g, '') || 0);
      const latestPrice = Number(result.item?.price || 0);
      let movement = 'updated';

      if (latestPrice > previousPrice) {
        movement = 'raised';
      } else if (latestPrice < previousPrice) {
        movement = 'lowered';
      }

      status.textContent = 'Bid submitted. Price ' + movement + ' to $' + latestPrice.toFixed(2) + '.';
    }

    if (result.item && currentPrice) {
      const price = Number(result.item.price || 0).toFixed(2);
      currentPrice.textContent = '$' + price;
    }

    if (result.item && currentBadge) {
      currentBadge.textContent = result.item.is_premium ? 'Premium' : (result.item.badge_text || 'Available');
    }

    form.reset();
  });
})();
</script>
<script src="../assets/js/nav-state.js"></script>
</body>
</html>
