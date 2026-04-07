<?php
declare(strict_types=1);

require __DIR__ . '/_layout.php';

whois_admin_render_page([
    'title' => 'WHOIS.ARCHITECT | Admin Analytics',
    'active' => 'analytics',
    'eyebrow' => 'Admin Side',
  'headline' => 'Analytics.',
  'description' => '',
], function (): void {
    ?>
    <section class="grid gap-6 lg:grid-cols-4">
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Response SLA</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary">2h</p>
        <p class="mt-2 text-sm text-on-surface-variant">Median review time</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Approval rate</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary">71%</p>
        <p class="mt-2 text-sm text-on-surface-variant">Listings accepted</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Queue depth</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary">18</p>
        <p class="mt-2 text-sm text-on-surface-variant">Open review items</p>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Seller response</p>
        <p class="mt-3 text-4xl font-black tracking-tight text-primary">94%</p>
        <p class="mt-2 text-sm text-on-surface-variant">Follow-up completion</p>
      </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-2">
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Trend</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-6">Approval velocity</h2>
        <div class="space-y-4">
          <div>
            <div class="flex items-center justify-between text-sm mb-2"><span>Monday</span><span>68%</span></div>
            <div class="h-2 rounded-full bg-surface-container-high overflow-hidden"><div class="h-full w-[68%] bg-primary"></div></div>
          </div>
          <div>
            <div class="flex items-center justify-between text-sm mb-2"><span>Tuesday</span><span>72%</span></div>
            <div class="h-2 rounded-full bg-surface-container-high overflow-hidden"><div class="h-full w-[72%] bg-primary"></div></div>
          </div>
          <div>
            <div class="flex items-center justify-between text-sm mb-2"><span>Wednesday</span><span>77%</span></div>
            <div class="h-2 rounded-full bg-surface-container-high overflow-hidden"><div class="h-full w-[77%] bg-primary"></div></div>
          </div>
          <div>
            <div class="flex items-center justify-between text-sm mb-2"><span>Thursday</span><span>71%</span></div>
            <div class="h-2 rounded-full bg-surface-container-high overflow-hidden"><div class="h-full w-[71%] bg-primary"></div></div>
          </div>
        </div>
      </div>
      <div class="rounded-[1.5rem] admin-panel p-6">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Workflow</p>
        <h2 class="text-2xl font-extrabold tracking-tight mt-2 mb-6">Operational breakdown</h2>
        <div class="grid gap-4 sm:grid-cols-2">
          <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Moderation</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-primary">29</p>
            <p class="mt-2 text-sm text-on-surface-variant">Items reviewed this week</p>
          </div>
          <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Escrow</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-primary">14</p>
            <p class="mt-2 text-sm text-on-surface-variant">Transfers in progress</p>
          </div>
          <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Views</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-primary">8.4k</p>
            <p class="mt-2 text-sm text-on-surface-variant">Marketplace visits</p>
          </div>
          <div class="rounded-2xl bg-surface-container-low p-5 border border-outline-variant/20">
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary">Conversion</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-primary">4.1%</p>
            <p class="mt-2 text-sm text-on-surface-variant">Search to action</p>
          </div>
        </div>
      </div>
    </section>
    <?php
});
