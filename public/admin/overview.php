<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';
require_once __DIR__ . '/../../app/db-client.php';

// --- Live stats from Neon DB ---
$pendingSubmissions = 0;
$liveListings = 0;
$soldListings = 0;
$totalBids = 0;

if (whois_db_is_available()) {
    $row = whois_db_fetch_one(
        "SELECT
            COUNT(*) FILTER (WHERE status = 'new')      AS pending_submissions,
            COUNT(*) FILTER (WHERE status = 'approved') AS approved_submissions
         FROM auction_submissions"
    );
    $pendingSubmissions = (int) ($row['pending_submissions'] ?? 0);

    $mktRow = whois_db_fetch_one(
        "SELECT
            COUNT(*) FILTER (WHERE status = 'live') AS live_listings,
            COUNT(*) FILTER (WHERE status = 'sold') AS sold_listings
         FROM marketplace_items"
    );
    $liveListings = (int) ($mktRow['live_listings'] ?? 0);
    $soldListings = (int) ($mktRow['sold_listings'] ?? 0);

    $bidRow = whois_db_fetch_one("SELECT COUNT(*) AS total FROM marketplace_bids");
    $totalBids = (int) ($bidRow['total'] ?? 0);

    // Recent submissions for queue preview
    $recentSubmissions = whois_db_list_submissions(['limit' => 5]);
} else {
    $recentSubmissions = [];
}

whois_admin_render_page([
    'title'       => 'WHOIS.ARCHITECT | Admin Overview',
    'active'      => 'overview',
    'eyebrow'     => 'Admin Dashboard',
    'headline'    => 'Operations Overview',
    'description' => 'Live stats from your Neon PostgreSQL database.',
], function () use ($pendingSubmissions, $liveListings, $soldListings, $totalBids, $recentSubmissions): void {
    ?>
    <!-- Live Stats -->
    <section class="grid gap-6 lg:grid-cols-4">
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Pending reviews</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary"><?php echo $pendingSubmissions; ?></p>
        <p class="mt-2 text-sm text-on-surface-variant">Auction submissions awaiting review</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Live listings</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary"><?php echo $liveListings; ?></p>
        <p class="mt-2 text-sm text-on-surface-variant">Active marketplace assets</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Domains sold</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary"><?php echo $soldListings; ?></p>
        <p class="mt-2 text-sm text-on-surface-variant">Completed escrow transactions</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Total bids</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary"><?php echo $totalBids; ?></p>
        <p class="mt-2 text-sm text-on-surface-variant">Bids recorded across all domains</p>
      </div>
    </section>

    <!-- Queue + Quick Actions -->
    <section class="mt-8 grid gap-6 lg:grid-cols-3">
      <div class="rounded-[1.5rem] admin-panel p-6 lg:col-span-2">
        <div class="flex items-center justify-between gap-4 mb-6">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Priority</p>
            <h2 class="text-2xl font-extrabold tracking-tight mt-2">Recent Submissions</h2>
          </div>
          <a class="rounded-full bg-primary px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-on-primary hover:bg-primary-container transition-colors" href="submissions.php">View all</a>
        </div>
        <div class="space-y-3">
          <?php if ($recentSubmissions === []): ?>
            <div class="rounded-xl border border-dashed border-outline-variant/40 bg-surface-container-lowest p-5 text-sm text-secondary">
              No submissions yet. Share the submission form to start receiving domains.
            </div>
          <?php else: ?>
            <?php foreach ($recentSubmissions as $sub): ?>
              <?php
                $status = strtolower((string) ($sub['status'] ?? 'new'));
                $badgeClass = match ($status) {
                    'approved' => 'bg-emerald-100 text-emerald-700',
                    'rejected' => 'bg-rose-100 text-rose-700',
                    default    => 'bg-amber-100 text-amber-800',
                };
                $badgeLabel = match ($status) {
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    default    => 'Pending',
                };
              ?>
              <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20 flex items-center justify-between gap-4">
                <div>
                  <p class="font-bold"><?php echo htmlspecialchars((string) ($sub['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                  <p class="text-xs text-secondary mt-0.5"><?php echo htmlspecialchars(trim((string) ($sub['category'] ?? '') . ' ' . (string) ($sub['keywords'] ?? '')), ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="rounded-[1.5rem] admin-panel p-6 flex flex-col gap-4">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Quick Links</p>
        <h2 class="text-2xl font-extrabold tracking-tight">Jump To</h2>
        <div class="space-y-2 mt-2">
          <a href="submissions.php" class="flex items-center gap-3 rounded-xl px-4 py-3 bg-surface-container-low border border-outline-variant/20 hover:bg-surface-container transition-colors text-sm font-semibold">
            <span class="material-symbols-outlined text-sm">inbox</span>Submission Queue
          </a>
          <a href="marketplace.php" class="flex items-center gap-3 rounded-xl px-4 py-3 bg-surface-container-low border border-outline-variant/20 hover:bg-surface-container transition-colors text-sm font-semibold">
            <span class="material-symbols-outlined text-sm">storefront</span>Marketplace Manager
          </a>
          <a href="analytics.php" class="flex items-center gap-3 rounded-xl px-4 py-3 bg-surface-container-low border border-outline-variant/20 hover:bg-surface-container transition-colors text-sm font-semibold">
            <span class="material-symbols-outlined text-sm">query_stats</span>Analytics
          </a>
          <a href="settings.php" class="flex items-center gap-3 rounded-xl px-4 py-3 bg-surface-container-low border border-outline-variant/20 hover:bg-surface-container transition-colors text-sm font-semibold">
            <span class="material-symbols-outlined text-sm">settings</span>Settings
          </a>
          <div class="pt-2 border-t border-outline-variant/10">
            <a href="../pages/whois_premium_domain_marketplace.php" class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-surface-container-low transition-colors text-xs text-secondary">
              <span class="material-symbols-outlined text-xs">open_in_new</span>Public Marketplace
            </a>
            <a href="../pages/whois_submit_domain_for_auction.php" class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-surface-container-low transition-colors text-xs text-secondary">
              <span class="material-symbols-outlined text-xs">open_in_new</span>Submission Form
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Marketplace snapshot -->
    <section class="mt-8 rounded-[1.5rem] admin-panel p-6">
      <div class="flex items-center justify-between gap-4 mb-6">
        <div>
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Marketplace</p>
          <h2 class="text-2xl font-extrabold tracking-tight mt-2">Status Summary</h2>
        </div>
        <a class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" href="marketplace.php">Manage</a>
      </div>
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Live Listings</p>
          <p class="mt-3 text-3xl font-black tracking-tight text-primary"><?php echo $liveListings; ?></p>
          <p class="mt-2 text-sm text-on-surface-variant">Currently for sale</p>
        </div>
        <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Sold</p>
          <p class="mt-3 text-3xl font-black tracking-tight text-primary"><?php echo $soldListings; ?></p>
          <p class="mt-2 text-sm text-on-surface-variant">Completed transfers</p>
        </div>
        <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Bids Received</p>
          <p class="mt-3 text-3xl font-black tracking-tight text-primary"><?php echo $totalBids; ?></p>
          <p class="mt-2 text-sm text-on-surface-variant">Across all domains</p>
        </div>
        <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Pending</p>
          <p class="mt-3 text-3xl font-black tracking-tight text-primary"><?php echo $pendingSubmissions; ?></p>
          <p class="mt-2 text-sm text-on-surface-variant">Awaiting review</p>
        </div>
      </div>
    </section>
    <?php
});
