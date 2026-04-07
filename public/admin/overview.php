<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';

whois_admin_render_page([
    'title' => 'WHOIS.ARCHITECT | Admin Overview',
    'active' => 'overview',
    'eyebrow' => 'Admin Side',
    'headline' => 'Operations dashboard.',
    'description' => '',
], function (): void {
    ?>
    <section class="grid gap-6 lg:grid-cols-4">
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Pending submissions</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary">12</p>
        <p class="mt-2 text-sm text-on-surface-variant">Awaiting manual review</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Verified listings</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary">84</p>
        <p class="mt-2 text-sm text-on-surface-variant">Active marketplace assets</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Seller requests</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary">31</p>
        <p class="mt-2 text-sm text-on-surface-variant">Needs follow-up</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Live workflows</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary">5</p>
        <p class="mt-2 text-sm text-on-surface-variant">Ready for next phase</p>
      </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-3">
      <div class="rounded-[1.5rem] admin-panel p-6 lg:col-span-2" id="submissions">
        <div class="flex items-center justify-between gap-4 mb-6">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Priority</p>
            <h2 class="text-2xl font-extrabold tracking-tight mt-2">Submission queue</h2>
          </div>
          <a class="rounded-full bg-primary px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-on-primary hover:bg-primary-container transition-colors" href="submissions.php">Open page</a>
        </div>
        <div class="space-y-3">
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20 flex items-center justify-between gap-4">
            <div>
              <p class="font-bold">datafy.org</p>
              <p class="text-xs text-secondary">Submitted for auction</p>
            </div>
            <span class="rounded-full bg-amber-100 text-amber-800 px-3 py-1 text-[10px] font-bold uppercase tracking-widest">Review</span>
          </div>
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20 flex items-center justify-between gap-4">
            <div>
              <p class="font-bold">zenith.ai</p>
              <p class="text-xs text-secondary">Premium marketplace listing</p>
            </div>
            <span class="rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-[10px] font-bold uppercase tracking-widest">Approved</span>
          </div>
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20 flex items-center justify-between gap-4">
            <div>
              <p class="font-bold">flow.io</p>
              <p class="text-xs text-secondary">Needs validation</p>
            </div>
            <span class="rounded-full bg-surface-container-high px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-secondary">Pending</span>
          </div>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Status</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-4">Next</h2>
        <div class="space-y-3 text-sm text-on-surface-variant">
          <p>• Auth</p>
          <p>• Live data</p>
          <p>• Review actions</p>
        </div>
      </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-2">
      <div class="rounded-[1.5rem] admin-panel p-6" id="marketplace">
        <div class="flex items-center justify-between gap-4 mb-6">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Marketplace</p>
            <h2 class="text-2xl font-extrabold tracking-tight mt-2">Modules</h2>
          </div>
          <a class="rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" href="marketplace.php">Open page</a>
        </div>
        <div class="grid gap-3">
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20 flex items-center gap-3"><span class="material-symbols-outlined text-sm">dns</span> Listings management</div>
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20 flex items-center gap-3"><span class="material-symbols-outlined text-sm">fact_check</span> Submission moderation</div>
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20 flex items-center gap-3"><span class="material-symbols-outlined text-sm">storefront</span> Marketplace curation</div>
          <div class="rounded-xl bg-surface-container-low p-4 border border-outline-variant/20 flex items-center gap-3"><span class="material-symbols-outlined text-sm">lock</span> Escrow workflow</div>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6" id="analytics">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Operations</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-6">Metrics</h2>
        <div class="grid gap-4 sm:grid-cols-2">
          <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Response SLA</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-primary">2h</p>
            <p class="mt-2 text-sm text-on-surface-variant">Median review time</p>
          </div>
          <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Approval rate</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-primary">71%</p>
            <p class="mt-2 text-sm text-on-surface-variant">Listings accepted</p>
          </div>
        </div>
      </div>
    </section>
    <?php
});
