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
        <div class="flex items-center justify-between gap-4 mb-6">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Inventory</p>
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
                  </tr>
                <?php endforeach; ?>
                <?php if ($soldMarketplaceItems === []): ?>
                  <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-sm text-secondary">No sold domains yet.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>
            <h2 class="text-2xl font-extrabold tracking-tight mt-2">Curation</h2>
          </div>
          <a class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" href="../pages/whois_premium_domain_marketplace.php">Public view</a>
        </div>
        <div class="grid gap-4 md:grid-cols-2" id="marketplace-admin-list">
          <?php foreach ($marketplaceItems as $item): ?>
            <div class="rounded-2xl bg-surface-container-lowest p-5 border border-outline-variant/30">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary"><?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
              <p class="mt-2 text-sm text-on-surface-variant"><?php echo htmlspecialchars((string) ($item['categories'] ?? 'Live inventory'), ENT_QUOTES, 'UTF-8'); ?></p>
              <div class="mt-4 grid gap-2">
                <input class="rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs" type="number" min="1" step="0.01" placeholder="Final sale price (USD)" data-sold-price="true"/>
                <input class="rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs" type="text" placeholder="Buyer name" data-buyer-name="true"/>
                <input class="rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs" type="email" placeholder="Buyer email" data-buyer-email="true"/>
              </div>
              <div class="mt-4 flex items-center justify-between">
                <span class="font-bold text-primary">$<?php echo number_format((float) ($item['price'] ?? 0)); ?></span>
                <div class="flex items-center gap-2">
                  <span class="rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-[10px] font-bold uppercase tracking-widest">Live</span>
                  <button class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-black" type="button" data-marketplace-sold="true" data-item-id="<?php echo (int) ($item['id'] ?? 0); ?>">Mark Sold</button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <?php if ($marketplaceItems === []): ?>
            <div class="rounded-2xl bg-surface-container-lowest p-5 border border-dashed border-outline-variant/30 text-sm text-secondary">No live items yet. Approve a submission to publish it here.</div>
          <?php endif; ?>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Controls</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Actions</h2>
        <div class="space-y-3 text-sm text-on-surface-variant">
          <p>• Promote featured assets</p>
          <p>• Adjust pricing tiers</p>
          <p>• Flag unverified inventory</p>
          <p>• Move items to auction</p>
        </div>
      </div>
    </section>
    ?>
    <script>
      (() => {
        const list = document.getElementById('marketplace-admin-list');

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

        list?.addEventListener('click', async (event) => {
          const button = event.target.closest('[data-marketplace-sold="true"]');

          if (!button) {
            return;
          }

          const itemId = Number(button.dataset.itemId || 0);

          if (!itemId) {
            return;
          }

          const card = button.closest('.rounded-2xl');
          const soldPrice = Number(card?.querySelector('[data-sold-price="true"]')?.value || 0);
          const buyerName = (card?.querySelector('[data-buyer-name="true"]')?.value || '').trim();
          const buyerEmail = (card?.querySelector('[data-buyer-email="true"]')?.value || '').trim();

          if (soldPrice <= 0 || !buyerName || !buyerEmail) {
            window.alert('Provide final sale price, buyer name, and buyer email before marking sold.');
            return;
          }

          button.disabled = true;

          try {
            await markSold(itemId, soldPrice, buyerName, buyerEmail);
            window.location.reload();
          } catch (error) {
            button.disabled = false;
            window.alert(error instanceof Error ? error.message : 'Unable to mark item sold.');
          }
        });
      })();
    </script>
    <?php
});
