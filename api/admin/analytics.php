<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';
require_once __DIR__ . '/../../app/db-client.php';

// --- Live analytics from Neon DB ---
$stats = [
    'total_submissions'  => 0,
    'pending'            => 0,
    'approved'           => 0,
    'rejected'           => 0,
    'live_listings'      => 0,
    'sold_listings'      => 0,
    'total_bids'         => 0,
    'avg_bid'            => 0.0,
    'approval_rate'      => 0,
];

$velocityRows = [];
$recentBids   = [];

if (whois_db_is_available()) {
    $subRow = whois_db_fetch_one(
        "SELECT
            COUNT(*)                                      AS total,
            COUNT(*) FILTER (WHERE status = 'new')       AS pending,
            COUNT(*) FILTER (WHERE status = 'approved')  AS approved,
            COUNT(*) FILTER (WHERE status = 'rejected')  AS rejected
         FROM auction_submissions"
    );
    if ($subRow) {
        $stats['total_submissions'] = (int) $subRow['total'];
        $stats['pending']           = (int) $subRow['pending'];
        $stats['approved']          = (int) $subRow['approved'];
        $stats['rejected']          = (int) $subRow['rejected'];
        $total = $stats['total_submissions'];
        $stats['approval_rate'] = $total > 0
            ? (int) round(($stats['approved'] / $total) * 100)
            : 0;
    }

    $mktRow = whois_db_fetch_one(
        "SELECT
            COUNT(*) FILTER (WHERE status = 'live') AS live_listings,
            COUNT(*) FILTER (WHERE status = 'sold') AS sold_listings
         FROM marketplace_items"
    );
    if ($mktRow) {
        $stats['live_listings'] = (int) $mktRow['live_listings'];
        $stats['sold_listings'] = (int) $mktRow['sold_listings'];
    }

    $bidRow = whois_db_fetch_one(
        "SELECT
            COUNT(*)              AS total_bids,
            COALESCE(AVG(bid_amount), 0) AS avg_bid
         FROM marketplace_bids"
    );
    if ($bidRow) {
        $stats['total_bids'] = (int) $bidRow['total_bids'];
        $stats['avg_bid']    = round((float) $bidRow['avg_bid'], 2);
    }

    // Recent bids
    $recentBids = whois_db_fetch_all(
        "SELECT domain_name, bid_amount, bidder_name, created_at
         FROM marketplace_bids
         ORDER BY created_at DESC LIMIT 8"
    );

    // Submissions by day of week (last 30 days)
    $velocityRows = whois_db_fetch_all(
        "SELECT
            TO_CHAR(created_at AT TIME ZONE 'UTC', 'Dy') AS day_name,
            COUNT(*) FILTER (WHERE status = 'approved')  AS approved,
            COUNT(*)                                       AS total
         FROM auction_submissions
         WHERE created_at >= NOW() - INTERVAL '30 days'
         GROUP BY TO_CHAR(created_at AT TIME ZONE 'UTC', 'Dy'),
                  EXTRACT(DOW FROM created_at AT TIME ZONE 'UTC')
         ORDER BY EXTRACT(DOW FROM created_at AT TIME ZONE 'UTC')"
    );
} else {
    // DB unavailable — show zeros
    $velocityRows = [];
    $recentBids   = [];
}

$dbConnected = whois_db_is_available();

whois_admin_render_page([
    'title'       => 'WHOIS.ARCHITECT | Admin Analytics',
    'active'      => 'analytics',
    'eyebrow'     => 'Admin Dashboard',
    'headline'    => 'Analytics',
    'description' => $dbConnected ? 'Live metrics from Neon PostgreSQL.' : 'Database unavailable — showing empty state.',
], function () use ($stats, $velocityRows, $recentBids, $dbConnected): void {
    ?>
    <?php if (!$dbConnected): ?>
    <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 px-5 py-4 text-sm text-amber-800 flex items-center gap-3">
      <span class="material-symbols-outlined text-amber-500">warning</span>
      Database unavailable. Check your <code>NEON_DATABASE_URL</code> environment variable.
    </div>
    <?php endif; ?>

    <!-- KPI Cards -->
    <section class="grid gap-6 lg:grid-cols-4">
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Total Submissions</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary"><?php echo $stats['total_submissions']; ?></p>
        <p class="mt-2 text-sm text-on-surface-variant"><?php echo $stats['pending']; ?> pending / <?php echo $stats['approved']; ?> approved</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Approval Rate</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary"><?php echo $stats['approval_rate']; ?>%</p>
        <p class="mt-2 text-sm text-on-surface-variant"><?php echo $stats['rejected']; ?> rejected overall</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Live Listings</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary"><?php echo $stats['live_listings']; ?></p>
        <p class="mt-2 text-sm text-on-surface-variant"><?php echo $stats['sold_listings']; ?> sold to date</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Total Bids</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary"><?php echo $stats['total_bids']; ?></p>
        <p class="mt-2 text-sm text-on-surface-variant">Avg $<?php echo number_format($stats['avg_bid'], 2); ?> per bid</p>
      </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-2">
      <!-- Approval velocity by day -->
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Trend (last 30 days)</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-6">Submission Velocity</h2>
        <?php if ($velocityRows === []): ?>
          <p class="text-sm text-secondary">No submission data in the last 30 days.</p>
        <?php else: ?>
          <div class="space-y-4">
            <?php foreach ($velocityRows as $v):
              $dayTotal = max(1, (int) ($v['total'] ?? 1));
              $dayApproved = (int) ($v['approved'] ?? 0);
              $pct = min(100, (int) round(($dayApproved / $dayTotal) * 100));
            ?>
            <div>
              <div class="flex items-center justify-between text-sm mb-2">
                <span><?php echo htmlspecialchars((string) ($v['day_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="text-secondary"><?php echo $dayApproved; ?> / <?php echo $dayTotal; ?> (<?php echo $pct; ?>%)</span>
              </div>
              <div class="h-2 rounded-full bg-surface-container-high overflow-hidden">
                <div class="h-full bg-primary transition-all" style="width:<?php echo $pct; ?>%"></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Recent bids -->
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Activity</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-6">Recent Bids</h2>
        <?php if ($recentBids === []): ?>
          <p class="text-sm text-secondary">No bids recorded yet.</p>
        <?php else: ?>
          <div class="space-y-3">
            <?php foreach ($recentBids as $bid): ?>
            <div class="rounded-xl bg-surface-container-low p-3 border border-outline-variant/20 flex items-center justify-between gap-3 text-sm">
              <div>
                <p class="font-bold text-primary"><?php echo htmlspecialchars((string) ($bid['domain_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="text-xs text-secondary mt-0.5"><?php echo htmlspecialchars((string) ($bid['bidder_name'] ?? 'Anonymous'), ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
              <span class="font-black text-primary">$<?php echo number_format((float) ($bid['bid_amount'] ?? 0), 2); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Operational Breakdown -->
    <section class="mt-8 rounded-[1.5rem] admin-panel p-6">
      <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Workflow</p>
      <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-6">Operational Breakdown</h2>
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Pending Review</p>
          <p class="mt-3 text-3xl font-black tracking-tight text-primary"><?php echo $stats['pending']; ?></p>
          <p class="mt-2 text-sm text-on-surface-variant">Awaiting admin action</p>
        </div>
        <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Approved</p>
          <p class="mt-3 text-3xl font-black tracking-tight text-primary"><?php echo $stats['approved']; ?></p>
          <p class="mt-2 text-sm text-on-surface-variant">Published to marketplace</p>
        </div>
        <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Total Bids</p>
          <p class="mt-3 text-3xl font-black tracking-tight text-primary"><?php echo $stats['total_bids']; ?></p>
          <p class="mt-2 text-sm text-on-surface-variant">Across all domains</p>
        </div>
        <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Sold</p>
          <p class="mt-3 text-3xl font-black tracking-tight text-primary"><?php echo $stats['sold_listings']; ?></p>
          <p class="mt-2 text-sm text-on-surface-variant">Completed transfers</p>
        </div>
      </div>
    </section>
    <?php
});
