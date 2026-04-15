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
      <div class="rounded-[1.5rem] admin-panel p-6 lg:col-span-2 relative z-0">
        <div class="mb-6 flex items-center justify-between gap-4">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Inventory</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight">Curation</h2>
          </div>
          <a class="relative z-20 rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" href="../pages/whois_premium_domain_marketplace.php">Public view</a>
        </div>
        <div class="grid gap-4 md:grid-cols-2 relative z-10" id="marketplace-admin-list">
          <?php foreach ($marketplaceItems as $item): ?>
            <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-lowest p-5 relative z-20" data-domain-card="true" data-domain-name="<?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary"><?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
              <p class="mt-2 text-sm text-on-surface-variant"><?php echo htmlspecialchars((string) ($item['categories'] ?? 'Live inventory'), ENT_QUOTES, 'UTF-8'); ?></p>
              <div class="mt-4 grid gap-2">
                <input class="relative z-30 rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs focus:ring-2 focus:ring-primary/20 pointer-events-auto" type="number" min="1" step="0.01" placeholder="Final sale price (USD)" data-sold-price="true"/>
                <input class="relative z-30 rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs focus:ring-2 focus:ring-primary/20 pointer-events-auto" type="text" placeholder="Buyer name" data-buyer-name="true"/>
                <input class="relative z-30 rounded-lg border border-outline-variant/40 bg-white px-3 py-2 text-xs focus:ring-2 focus:ring-primary/20 pointer-events-auto" type="email" placeholder="Buyer email" data-buyer-email="true"/>
              </div>
              <div class="mt-5 flex flex-wrap items-center gap-3">
                <button class="relative z-30 cursor-pointer rounded-full border border-outline-variant/40 bg-surface-container-lowest hover:bg-surface-container-low px-4 py-2 text-[11px] font-bold uppercase tracking-widest text-black transition-all active:scale-95 shadow-sm" type="button" data-edit-curation="true" data-item='<?php echo htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>'>Edit Curation</button>
                <button class="relative z-30 cursor-pointer rounded-full border border-outline-variant/40 bg-surface-container-lowest hover:bg-surface-container-low px-4 py-2 text-[11px] font-bold uppercase tracking-widest text-black transition-all active:scale-95 shadow-sm" type="button" data-view-bids="true" data-domain="<?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">View Bids</button>
                <button class="relative z-30 cursor-pointer rounded-full border border-outline-variant/40 bg-surface-container-lowest hover:bg-surface-container-low px-4 py-2 text-[11px] font-bold uppercase tracking-widest text-black transition-all active:scale-95 shadow-sm" type="button" data-marketplace-sold="true" data-item-id="<?php echo (int) ($item['id'] ?? 0); ?>">Mark Sold</button>
              </div>
            </div>
          <?php endforeach; ?>
          <?php if ($marketplaceItems === []): ?>
            <div class="rounded-2xl border border-dashed border-outline-variant/30 bg-surface-container-lowest p-5 text-sm text-secondary">No live items yet. Approve a submission to publish it here.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="rounded-[1.5rem] admin-panel p-6 relative z-10">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Controls</p>
        <h2 class="mb-4 mt-2 text-2xl font-extrabold tracking-tight">Bid History</h2>
        <p id="bids-domain-label" class="mb-3 text-xs text-on-surface-variant">Select a domain to view all submitted bids.</p>
        <div class="max-h-[480px] overflow-y-auto rounded-2xl border border-outline-variant/30 bg-surface-container-lowest pointer-events-auto">
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

    <section class="mt-6 rounded-[1.5rem] admin-panel p-6 relative z-10">
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
                  <button class="relative z-30 cursor-pointer rounded-full border border-outline-variant/40 bg-surface-container-lowest hover:bg-surface-container-low px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-black transition-all active:scale-95" type="button" data-view-bids="true" data-domain="<?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">View Bids</button>
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

    <!-- Edit Curation Modal -->
    <div id="curation-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 cursor-default">
      <div class="w-full max-w-2xl rounded-[2rem] bg-surface p-8 shadow-2xl overflow-y-auto max-h-[90vh] pointer-events-auto">
        <div class="mb-8 flex items-center justify-between">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Editor</p>
            <h2 class="mt-2 text-3xl font-black tracking-tighter text-black">Curate <span id="curation-domain-display">Listing</span></h2>
          </div>
          <button class="w-10 h-10 rounded-full border border-outline-variant/30 flex items-center justify-center hover:bg-surface-container-low transition-colors cursor-pointer" type="button" onclick="document.getElementById('curation-modal').classList.add('hidden'); document.getElementById('curation-modal').classList.remove('flex');">✕</button>
        </div>

        <form id="curation-form" class="space-y-8">
          <input type="hidden" name="item_id" id="curation-item-id">
          
          <div class="grid gap-6 md:grid-cols-2">
             <div class="space-y-2">
                <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Listing Type</label>
                <select name="listing_type" id="curation-listing-type" class="cursor-pointer w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20">
                    <option value="row">Standard Grid Item</option>
                    <option value="featured">Featured Highlight</option>
                </select>
             </div>
             <div class="space-y-2">
                <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Badge Text</label>
                <input type="text" name="badge_text" id="curation-badge-text" placeholder="e.g. HOT DEAL, PREMIUM" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20">
             </div>
          </div>

          <div class="grid gap-6 md:grid-cols-2">
             <div class="space-y-2">
                <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Display Price ($)</label>
                <input type="number" step="0.01" name="price" id="curation-price" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20">
             </div>
             <div class="space-y-2">
                <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Appraisal Price ($)</label>
                <input type="number" step="0.01" name="appraisal_price" id="curation-appraisal-price" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20">
             </div>
          </div>

          <div class="grid gap-6 md:grid-cols-2">
             <div class="space-y-2">
                <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Categories</label>
                <input type="text" name="categories" id="curation-categories" placeholder="e.g. AI, Tech, SaaS" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20">
             </div>
             <div class="space-y-2">
                <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Icon Name (Material)</label>
                <input type="text" name="icon_name" id="curation-icon-name" placeholder="e.g. star, bolt, language" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20">
             </div>
          </div>

          <div class="space-y-4">
            <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Background Visuals</label>
            <input type="text" name="background_image_url" id="curation-background-url" placeholder="Paste custom image URL here..." class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 mb-4">
            
            <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-secondary mb-2">Premium Presets</p>
            <div class="grid grid-cols-4 gap-3">
               <?php
               $presets = [
                   'AI / Neural' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&q=80&w=800',
                   'Flow / Abstract' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&q=80&w=800',
                   'Space / Tech' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800',
                   'Crypto / Neon' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?auto=format&fit=crop&q=80&w=800',
                   'Business' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=800',
                   'Modern' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=800',
                   'Dark Flow' => 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?auto=format&fit=crop&q=80&w=800',
                   'Luxury' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=800'
               ];
               foreach ($presets as $name => $url): ?>
               <button type="button" onclick="document.getElementById('curation-background-url').value='<?php echo $url; ?>'" class="cursor-pointer group relative aspect-[16/9] rounded-xl overflow-hidden border-2 border-transparent hover:border-primary transition-all">
                  <img src="<?php echo $url; ?>" class="w-full h-full object-cover">
                  <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                     <span class="text-[8px] font-black text-white uppercase"><?php echo $name; ?></span>
                  </div>
               </button>
               <?php endforeach; ?>
            </div>
          </div>

          <div class="pt-6 border-t border-outline-variant/20 space-y-6">
             <div class="flex items-center justify-between">
                <div>
                   <h4 class="text-sm font-bold tracking-tight">AI Asset Intelligence</h4>
                   <p class="text-[10px] text-secondary uppercase tracking-widest font-medium">Domain Strategy & Semantic Analysis</p>
                </div>
                <button type="button" id="generate-ai-intel" class="flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-primary/20 transition-all cursor-pointer shadow-sm relative z-20">
                   <span class="material-symbols-outlined text-sm">auto_awesome</span>
                   Generate Intelligence
                </button>
             </div>

             <div class="space-y-4">
                <div class="space-y-2">
                   <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Asset Description (Intelligence)</label>
                   <textarea name="ai_description" id="curation-ai-description" rows="3" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
                <div class="space-y-2">
                   <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Technical Anatomy (Log Explanation)</label>
                   <textarea name="ai_technical_log" id="curation-ai-technical-log" rows="3" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
                <div class="space-y-2">
                   <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Why This Domain? (Newline separated 3-bullets)</label>
                   <textarea name="ai_why_bullets" id="curation-ai-why-bullets" rows="3" placeholder="Bullet 1&#10;Bullet 2&#10;Bullet 3" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
                <div class="space-y-2">
                   <label class="text-[10px] font-bold uppercase tracking-widest text-secondary">Market Use Case Lab</label>
                   <textarea name="ai_use_cases" id="curation-ai-use-cases" rows="2" class="w-full rounded-2xl border-none bg-surface-container-low px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
             </div>
          </div>

          <div class="pt-8 border-t border-outline-variant/20 flex gap-4">
            <button type="submit" class="cursor-pointer flex-1 rounded-full bg-primary py-4 text-sm font-black uppercase tracking-widest text-on-primary hover:opacity-90 transition-all shadow-md active:scale-95">Save Changes</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      (() => {
        const list = document.getElementById('marketplace-admin-list');
        const bidsBody = document.getElementById('domain-bids-body');
        const bidsLabel = document.getElementById('bids-domain-label');

        function formatDate(value) {
          if (!value) return '-';
          const date = new Date(value);
          if (Number.isNaN(date.getTime())) return String(value);
          return date.toLocaleString();
        }

        async function markSold(itemId, soldPrice, buyerName, buyerEmail) {
          const response = await fetch('../api/marketplace.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              action: 'mark_sold', item_id: itemId, sold_price: soldPrice,
              buyer_name: buyerName, buyer_email: buyerEmail,
            }),
          });
          const result = await response.json();
          if (!response.ok || !result.ok) throw new Error(result.error || 'Unable to mark item sold.');
          return result;
        }

        async function loadBids(domain) {
          if (!domain) return;
          if (bidsLabel) bidsLabel.textContent = 'Loading bids for ' + domain + '...';
          const response = await fetch('../api/bids.php?domain_name=' + encodeURIComponent(domain));
          const result = await response.json();
          if (!response.ok || !result.ok) throw new Error(result.error || 'Unable to fetch bids.');
          if (!bidsBody) return;
          bidsBody.innerHTML = '';
          if (!Array.isArray(result.bids) || result.bids.length === 0) {
            bidsBody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-sm text-secondary">No bids found for this domain.</td></tr>';
            if (bidsLabel) bidsLabel.textContent = 'No bids recorded for ' + domain + '.';
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
          if (bidsLabel) bidsLabel.textContent = 'Showing ' + result.bids.length + ' bid(s) for ' + domain + '.';
        }

        document.addEventListener('click', async (event) => {
          const viewBidsButton = event.target.closest('[data-view-bids="true"]');
          if (viewBidsButton) {
            const domain = (viewBidsButton.dataset.domain || '').trim();
            if (!domain) return;
            try {
              await loadBids(domain);
            } catch (error) {
              if (bidsBody) bidsBody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-sm text-red-600">Unable to load bids.</td></tr>';
              if (bidsLabel) bidsLabel.textContent = error instanceof Error ? error.message : 'Unable to load bids.';
            }
            return;
          }

          const soldButton = event.target.closest('[data-marketplace-sold="true"]');
          if (!soldButton) return;

          const itemId = Number(soldButton.dataset.itemId || 0);
          if (!itemId) return;

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

        // Curation Modal Logic
        const modal = document.getElementById('curation-modal');
        const curationForm = document.getElementById('curation-form');
        const curationDisplay = document.getElementById('curation-domain-display');

        document.addEventListener('click', (event) => {
           const editBtn = event.target.closest('[data-edit-curation="true"]');
           if (!editBtn) return;

           const item = JSON.parse(editBtn.dataset.item);
           if (curationDisplay) curationDisplay.textContent = item.domain_name;
           document.getElementById('curation-item-id').value = item.id;
           document.getElementById('curation-listing-type').value = item.listing_type || 'row';
           document.getElementById('curation-badge-text').value = item.badge_text || '';
           document.getElementById('curation-price').value = item.price || 0;
           document.getElementById('curation-appraisal-price').value = item.appraisal_price || 0;
           document.getElementById('curation-background-url').value = item.background_image_url || '';
           document.getElementById('curation-categories').value = item.categories || '';
           document.getElementById('curation-icon-name').value = item.icon_name || '';
           document.getElementById('curation-ai-description').value = item.ai_description || '';
           document.getElementById('curation-ai-technical-log').value = item.ai_technical_log || '';
           document.getElementById('curation-ai-why-bullets').value = item.ai_why_bullets || '';
           document.getElementById('curation-ai-use-cases').value = item.ai_use_cases || '';

           modal.classList.remove('hidden');
           modal.classList.add('flex');
        });

        curationForm.addEventListener('submit', async (e) => {
           e.preventDefault();
           const formData = new FormData(curationForm);
           const payload = {
              action: 'update_item',
              item_id: formData.get('item_id'),
              listing_type: formData.get('listing_type'),
              badge_text: formData.get('badge_text'),
              price: formData.get('price'),
              appraisal_price: formData.get('appraisal_price'),
              background_image_url: formData.get('background_image_url'),
              categories: formData.get('categories'),
              icon_name: formData.get('icon_name'),
              ai_description: formData.get('ai_description'),
              ai_why_bullets: formData.get('ai_why_bullets'),
              ai_technical_log: formData.get('ai_technical_log'),
              ai_use_cases: formData.get('ai_use_cases')
           };

           try {
              const response = await fetch('../api/marketplace.php', {
                 method: 'POST',
                 headers: { 'Content-Type': 'application/json' },
                 body: JSON.stringify(payload)
              });
              const result = await response.json();
              if (!response.ok || !result.ok) throw new Error(result.error || 'Failed to update');
              window.location.reload();
           } catch (err) {
              window.alert(err.message);
           }
        });

        document.getElementById('generate-ai-intel').addEventListener('click', async function() {
           const domain = document.getElementById('curation-domain-display').textContent;
           const btn = this;
           const originalText = btn.innerHTML;
           
           btn.disabled = true;
           btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">sync</span> Generating...';

           try {
              const response = await fetch('/api/ai.php', {
                 method: 'POST',
                 headers: { 'Content-Type': 'application/json' },
                 body: JSON.stringify({
                    workflow: 'domain_intel',
                    input: domain
                 })
              });
              const data = await response.json();
              
              if (data.ok) {
                 let rawText = data.output.trim();
                 if (rawText.startsWith('```json')) {
                    rawText = rawText.substring(7);
                 } else if (rawText.startsWith('```')) {
                    rawText = rawText.substring(3);
                 }
                 if (rawText.endsWith('```')) {
                    rawText = rawText.substring(0, rawText.length - 3);
                 }
                 
                 const intel = JSON.parse(rawText.trim());
                 document.getElementById('curation-ai-description').value = intel.description || '';
                 document.getElementById('curation-ai-technical-log').value = intel.technical_log || '';
                 document.getElementById('curation-ai-why-bullets').value = (intel.why_bullets || []).join('\n');
                 document.getElementById('curation-ai-use-cases').value = intel.use_cases || '';
              } else {
                 alert('AI Generation failed: ' + data.error);
              }
           } catch (err) {
              console.error(err);
              alert('Error connecting to AI API');
           } finally {
              btn.disabled = false;
              btn.innerHTML = originalText;
           }
        });
      })();
    </script>
    <?php
});
