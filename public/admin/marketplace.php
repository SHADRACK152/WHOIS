<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';
require_once __DIR__ . '/../../app/db-client.php';

$marketplaceItems = whois_db_list_marketplace_items(['status' => 'live']);

whois_admin_render_page([
    'title' => 'WHOIS.ARCHITECT | Admin Marketplace',
    'active' => 'marketplace',
    'eyebrow' => 'Admin Side',
    'headline' => 'Marketplace.',
    'description' => '',
], function () use ($marketplaceItems): void {
    ?>
    <section class="grid gap-6 lg:grid-cols-3">
      <div class="rounded-[1.5rem] admin-panel p-6 lg:col-span-2">
        <div class="flex items-center justify-between gap-4 mb-6">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Inventory</p>
            <h2 class="text-2xl font-extrabold tracking-tight mt-2">Curation</h2>
          </div>
          <a class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" href="../pages/whois_premium_domain_marketplace.php">Public view</a>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
          <?php foreach (array_slice($marketplaceItems, 0, 4) as $item): ?>
            <div class="rounded-2xl bg-surface-container-lowest p-5 border border-outline-variant/30">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary"><?php echo htmlspecialchars((string) ($item['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
              <p class="mt-2 text-sm text-on-surface-variant"><?php echo htmlspecialchars((string) ($item['categories'] ?? 'Live inventory'), ENT_QUOTES, 'UTF-8'); ?></p>
              <div class="mt-4 flex items-center justify-between">
                <span class="font-bold text-primary">$<?php echo number_format((float) ($item['price'] ?? 0)); ?></span>
                <span class="rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-[10px] font-bold uppercase tracking-widest">Live</span>
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
    <?php
});
