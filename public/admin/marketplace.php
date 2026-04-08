<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';
require_once __DIR__ . '/../../app/db-client.php';

$marketplaceItems = whois_db_list_marketplace_items(['status' => 'live']);
$soldMarketplaceItems = whois_db_list_marketplace_items(['status' => 'sold']);

whois_admin_render_page([
    'title' => 'WHOIS.ARCHITECT | Admin Marketplace',
    'active' => 'marketplace',
    'eyebrow' => 'Admin Side',
    'headline' => 'Marketplace.',
    'description' => '',
], function () use ($marketplaceItems, $soldMarketplaceItems): void {
    ?>
    <section class="grid gap-6 lg:grid-cols-3">
      <div class="rounded-[1.5rem] admin-panel p-6 lg:col-span-2">
        <div class="mb-6 flex items-center justify-between gap-4">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Inventory</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight">Curation</h2>
          </div>
          <a class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" href="../pages/whois_premium_domain_marketplace.php">Public view</a>
        </div>
        <div class="grid gap-4 md:grid-cols-2" id="marketplace-admin-list">
          <?php foreach ($marketplaceItems as $item): ?>
            <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-lowest p-5" data-domain-card="true" data-domain-name="<?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary"><?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
              <p class="mt-2 text-sm text-on-surface-variant"><?php echo htmlspecialchars((string) ($item['categories'] ?? 'Live inventory'), ENT_QUOTES, 'UTF-8'); ?></p>
              <div class="mt-4 grid gap-2">
                <input class="rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs" type="number" min="1" step="0.01" placeholder="Final sale price (USD)" data-sold-price="true"/>
                <input class="rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs" type="text" placeholder="Buyer name" data-buyer-name="true"/>
                <input class="rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs" type="email" placeholder="Buyer email" data-buyer-email="true"/>
              </div>
              <div class="mt-4 flex items-center justify-between gap-2">
                <span class="font-bold text-primary">$<?php echo number_format((float) ($item['price'] ?? 0), 2); ?></span>
                <div class="flex items-center gap-2">
                  <button class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-black" type="button" data-view-bids="true" data-domain="<?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">View Bids</button>
                  <button class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-black" type="button" data-marketplace-sold="true" data-item-id="<?php echo (int) ($item['id'] ?? 0); ?>">Mark Sold</button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <?php if ($marketplaceItems === []): ?>
            <div class="rounded-2xl border border-dashed border-outline-variant/30 bg-surface-container-lowest p-5 text-sm text-secondary">No live items yet. Approve a submission to publish it here.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Controls</p>
        <h2 class="mb-4 mt-2 text-2xl font-extrabold tracking-tight">Bid History</h2>
        <p id="bids-domain-label" class="mb-3 text-xs text-on-surface-variant">Select a domain to view all submitted bids.</p>
        <div class="max-h-[480px] overflow-y-auto rounded-2xl border border-outline-variant/30 bg-surface-container-lowest">
          <table class="min-w-full text-left text-xs">
            <thead class="border-b border-outline-variant/30 bg-surface-container-low">
              <tr>
                <th class="px-3 py-2 font-bold uppercase tracking-widest text-secondary">Time</th>
                <th class="px-3 py-2 font-bold uppercase tracking-widest text-secondary">Bid</th>
                <th class="px-3 py-2 font-bold uppercase tracking-widest text-secondary">Bidder</th>
              </tr>
            </thead>
            <tbody id="domain-bids-body">
              <tr>
                <td colspan="3" class="px-3 py-4 text-center text-sm text-secondary">No domain selected.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <section class="mt-6 rounded-[1.5rem] admin-panel p-6">
      <div class="mb-4 flex items-center justify-between gap-4">
        <div>
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Audit Trail</p>
          <h2 class="mt-2 text-2xl font-extrabold tracking-tight">Sold History</h2>
        </div>
        <span class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-black"><?php echo count($soldMarketplaceItems); ?> Sold</span>
      </div>
      <div class="overflow-x-auto rounded-2xl border border-outline-variant/30 bg-surface-container-lowest">
        <table class="min-w-full text-left text-xs">
          <thead class="border-b border-outline-variant/30 bg-surface-container-low">
            <tr>
              <th class="px-4 py-3 font-bold uppercase tracking-widest text-secondary">Domain</th>
              <th class="px-4 py-3 font-bold uppercase tracking-widest text-secondary">Sold Price</th>
              <th class="px-4 py-3 font-bold uppercase tracking-widest text-secondary">Buyer</th>
              <th class="px-4 py-3 font-bold uppercase tracking-widest text-secondary">Email</th>
              <th class="px-4 py-3 font-bold uppercase tracking-widest text-secondary">Sold Date</th>
              <th class="px-4 py-3 font-bold uppercase tracking-widest text-secondary">Premium</th>
              <th class="px-4 py-3 font-bold uppercase tracking-widest text-secondary">Bids</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($soldMarketplaceItems as $item): ?>
              <tr class="border-b border-outline-variant/20 last:border-b-0">
                <td class="px-4 py-3 font-bold text-primary"><?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="px-4 py-3">$<?php echo number_format((float) ($item['sold_price'] ?? 0), 2); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars((string) ($item['sold_buyer_name'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars((string) ($item['sold_buyer_email'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars((string) ($item['sold_at'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="px-4 py-3">
                  <?php if ((bool) ($item['is_premium'] ?? false)): ?>
                    <span class="rounded-full bg-amber-100 px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-amber-800">Premium</span>
                  <?php else: ?>
                    <span class="rounded-full bg-neutral-200 px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-neutral-700">Standard</span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                  <button class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-black" type="button" data-view-bids="true" data-domain="<?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">View Bids</button>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if ($soldMarketplaceItems === []): ?>
              <tr>
                <td colspan="7" class="px-4 py-6 text-center text-sm text-secondary">No sold domains yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <script>
      (() => {
        const list = document.getElementById('marketplace-admin-list');
        const bidsBody = document.getElementById('domain-bids-body');
        const bidsLabel = document.getElementById('bids-domain-label');

        function formatDate(value) {
          if (!value) {
            return '-';
          }

          const date = new Date(value);

          if (Number.isNaN(date.getTime())) {
            return String(value);
          }

          return date.toLocaleString();
        }

        async function markSold(itemId, soldPrice, buyerName, buyerEmail) {
          const response = await fetch('../api/marketplace.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              action: 'mark_sold',
              item_id: itemId,
              sold_price: soldPrice,
              buyer_name: buyerName,
              buyer_email: buyerEmail,
            }),
          });

          const result = await response.json();

          if (!response.ok || !result.ok) {
            throw new Error(result.error || 'Unable to mark item sold.');
          }

          return result;
        }

        async function loadBids(domain) {
          if (!domain) {
            return;
          }

          if (bidsLabel) {
            bidsLabel.textContent = 'Loading bids for ' + domain + '...';
          }

          const response = await fetch('../api/bids.php?domain_name=' + encodeURIComponent(domain));
          const result = await response.json();

          if (!response.ok || !result.ok) {
            throw new Error(result.error || 'Unable to fetch bids.');
          }

          if (!bidsBody) {
            return;
          }

          bidsBody.innerHTML = '';

          if (!Array.isArray(result.bids) || result.bids.length === 0) {
            bidsBody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-sm text-secondary">No bids found for this domain.</td></tr>';
            if (bidsLabel) {
              bidsLabel.textContent = 'No bids recorded for ' + domain + '.';
            }
            return;
          }

          result.bids.forEach((bid) => {
            const row = document.createElement('tr');
            row.className = 'border-b border-outline-variant/20 last:border-b-0';

            const bidderName = (bid.bidder_name || '').trim() || 'Anonymous';
            const bidderEmail = (bid.bidder_email || '').trim();
            const bidder = bidderEmail ? bidderName + ' (' + bidderEmail + ')' : bidderName;

            row.innerHTML =
              '<td class="px-3 py-2">' + formatDate(bid.created_at || '') + '</td>' +
              '<td class="px-3 py-2 font-bold text-primary">$' + Number(bid.bid_amount || 0).toFixed(2) + '</td>' +
              '<td class="px-3 py-2">' + bidder + '</td>';

            bidsBody.appendChild(row);
          });

          if (bidsLabel) {
            bidsLabel.textContent = 'Showing ' + result.bids.length + ' bid(s) for ' + domain + '.';
          }
        }

        document.addEventListener('click', async (event) => {
          const viewBidsButton = event.target.closest('[data-view-bids="true"]');

          if (viewBidsButton) {
            const domain = (viewBidsButton.dataset.domain || '').trim();

            if (!domain) {
              return;
            }

            try {
              await loadBids(domain);
            } catch (error) {
              if (bidsBody) {
                bidsBody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-sm text-red-600">Unable to load bids.</td></tr>';
              }
              if (bidsLabel) {
                bidsLabel.textContent = error instanceof Error ? error.message : 'Unable to load bids.';
              }
            }

            return;
          }

          const soldButton = event.target.closest('[data-marketplace-sold="true"]');

          if (!soldButton) {
            return;
          }

          const itemId = Number(soldButton.dataset.itemId || 0);

          if (!itemId) {
            return;
          }

          const card = soldButton.closest('[data-domain-card="true"]');
          const soldPrice = Number(card?.querySelector('[data-sold-price="true"]')?.value || 0);
          const buyerName = (card?.querySelector('[data-buyer-name="true"]')?.value || '').trim();
          const buyerEmail = (card?.querySelector('[data-buyer-email="true"]')?.value || '').trim();

          if (soldPrice <= 0 || !buyerName || !buyerEmail) {
            window.alert('Provide final sale price, buyer name, and buyer email before marking sold.');
            return;
          }

          soldButton.disabled = true;

          try {
            await markSold(itemId, soldPrice, buyerName, buyerEmail);
            window.location.reload();
          } catch (error) {
            soldButton.disabled = false;
            window.alert(error instanceof Error ? error.message : 'Unable to mark item sold.');
          }
        });
      })();
    </script>
    <?php
});
