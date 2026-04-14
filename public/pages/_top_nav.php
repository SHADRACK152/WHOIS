<?php
declare(strict_types=1);

$currentPage = strtolower((string) basename((string) ($_SERVER['SCRIPT_NAME'] ?? '')));

$navItems = [
    ['label' => 'Home',        'href' => 'whois_premium_domain_intelligence_landing_page.php', 'match' => ['whois_premium_domain_intelligence_landing_page.php', 'index.php']],
    ['label' => 'WHOIS Lookup','href' => 'whois_professional_lookup_tool.php',                'match' => ['whois_professional_lookup_tool.php']],
    ['label' => 'Search',      'href' => 'whois_ai_domain_search.php',                         'match' => ['whois_ai_domain_search.php', 'whois_comprehensive_search_results.php', 'whois_ai_generated_domains.php']],
    ['label' => 'AI Tools',    'href' => 'whois_ai_brand_assistant.php',                       'match' => ['whois_ai_brand_assistant.php', 'whois_ai_business_idea_generator.php', 'whois_ai_domain_name_generator.php', 'whois_brand_preview.php']],
    ['label' => 'Marketplace', 'href' => 'whois_premium_domain_marketplace.php',               'match' => ['whois_premium_domain_marketplace.php', 'whois_limited_time_domain_auctions.php']],
    ['label' => 'Sell',        'href' => 'whois_submit_domain_for_auction.php',                'match' => ['whois_submit_domain_for_auction.php']],
    ['label' => 'DNS Checker', 'href' => 'whois_dns_checker.php',                              'match' => ['whois_dns_checker.php']],
    ['label' => 'Tools',       'href' => 'whois_domain_tools_overview.php',                    'match' => ['whois_domain_tools_overview.php', 'whois_domain_appraisal_tool.php']],
    ['label' => 'Insights',    'href' => 'whois_industry_insights_blog.php',                   'match' => ['whois_industry_insights_blog.php']],
    ['label' => 'Partner',     'href' => 'whois_partner_with_us.php',                          'match' => ['whois_partner_with_us.php']],
];
?>
<nav class="fixed top-0 z-50 w-full border-b border-outline-variant/20 bg-white/85 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)]" id="main-nav">
  <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-3.5 lg:px-8">

    <!-- Brand -->
    <a class="flex shrink-0 items-center gap-2.5 text-black" href="whois_premium_domain_intelligence_landing_page.php">
      <img src="/assets/img/whois-icon.png" alt="WHOIS icon" class="h-8 w-8 rounded-full object-cover"/>
      <span class="font-['Manrope'] text-xl font-black tracking-tighter">WHOIS</span>
      <span class="hidden text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-400 sm:inline">Intelligence Suite</span>
    </a>

    <!-- Desktop nav links -->
    <div class="hidden items-center gap-5 text-sm font-semibold tracking-tight xl:flex">
      <?php foreach ($navItems as $navItem): ?>
        <?php $isActive = in_array($currentPage, $navItem['match'], true); ?>
        <a class="<?php echo $isActive ? 'text-black' : 'text-neutral-500 hover:text-black'; ?> transition-colors whitespace-nowrap" href="<?php echo htmlspecialchars((string) $navItem['href'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $navItem['label'], ENT_QUOTES, 'UTF-8'); ?></a>
      <?php endforeach; ?>
    </div>

    <!-- Right actions -->
    <div class="flex items-center gap-3">
      <!-- Desktop: Search CTA -->
      <a class="hidden lg:inline-flex items-center rounded-full bg-black px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white hover:bg-neutral-800 transition-colors" href="whois_ai_domain_search.php">Search</a>
      <!-- Mobile: Hamburger -->
      <button id="mobile-nav-toggle" class="xl:hidden inline-flex items-center justify-center w-10 h-10 rounded-full border border-outline-variant/30 bg-white hover:bg-surface-container-low transition-colors" aria-label="Open menu" type="button">
        <span class="material-symbols-outlined text-lg">menu</span>
      </button>
    </div>

  </div>
</nav>

<!-- Mobile Navigation Drawer -->
<div id="mobile-nav-drawer" role="dialog" aria-modal="true" aria-label="Navigation">
  <div class="drawer-backdrop" id="mobile-nav-backdrop"></div>
  <div class="drawer-panel">
    <button class="drawer-close" id="mobile-nav-close" type="button">✕ Close</button>
    <!-- Brand in drawer -->
    <a class="flex items-center gap-2 mb-4 text-black" href="whois_premium_domain_intelligence_landing_page.php">
      <img src="/assets/img/whois-icon.png" alt="WHOIS" class="h-7 w-7 rounded-full object-cover"/>
      <span class="font-['Manrope'] text-lg font-black tracking-tighter">WHOIS</span>
    </a>
    <div class="w-full h-px bg-outline-variant/20 mb-2"></div>
    <?php foreach ($navItems as $navItem): ?>
      <?php $isActive = in_array($currentPage, $navItem['match'], true); ?>
      <a class="drawer-link<?php echo $isActive ? ' active' : ''; ?>" href="<?php echo htmlspecialchars((string) $navItem['href'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $navItem['label'], ENT_QUOTES, 'UTF-8'); ?></a>
    <?php endforeach; ?>
    <div class="w-full h-px bg-outline-variant/20 my-2"></div>
    <a class="drawer-link" href="../admin/login.php" style="color:#5e5e5e;font-size:0.75rem;">Admin Login</a>
  </div>
</div>

<script>
(function () {
    var toggle = document.getElementById('mobile-nav-toggle');
    var drawer = document.getElementById('mobile-nav-drawer');
    var backdrop = document.getElementById('mobile-nav-backdrop');
    var closeBtn = document.getElementById('mobile-nav-close');

    if (!toggle || !drawer) return;

    function openDrawer() {
        drawer.classList.add('open');
        document.body.style.overflow = 'hidden';
        closeBtn && closeBtn.focus();
    }
    function closeDrawer() {
        drawer.classList.remove('open');
        document.body.style.overflow = '';
        toggle && toggle.focus();
    }

    toggle.addEventListener('click', openDrawer);
    if (backdrop) backdrop.addEventListener('click', closeDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && drawer.classList.contains('open')) closeDrawer();
    });
})();
</script>
