<?php
declare(strict_types=1);

$currentPage = strtolower((string) basename((string) ($_SERVER['SCRIPT_NAME'] ?? '')));

$navItems = [
    ['label' => 'Home', 'href' => 'whois_premium_domain_intelligence_landing_page.php', 'match' => ['whois_premium_domain_intelligence_landing_page.php', 'index.php']],
  ['label' => 'WHOIS Search', 'href' => 'whois_professional_lookup_tool.php', 'match' => ['whois_professional_lookup_tool.php']],
  ['label' => 'Search', 'href' => 'whois_ai_domain_search.php', 'match' => ['whois_ai_domain_search.php', 'whois_premium_ai_domain_search.php', 'whois_domain_search_results.php', 'whois_domain_search_results_atom_inspired.php', 'whois_search_results_with_premium_logos.php', 'whois_optimized_premium_search_results.php', 'whois_comprehensive_search_results.php', 'whois_comprehensive_search_results_atom_inspired.php', 'whois_domain_search_atom_inspired.php']],
    ['label' => 'AI Assistants', 'href' => 'whois_ai_brand_assistant.php', 'match' => ['whois_ai_brand_assistant.php', 'whois_ai_business_idea_generator.php', 'whois_ai_domain_name_generator.php']],
    ['label' => 'Marketplace', 'href' => 'whois_premium_domain_marketplace.php', 'match' => ['whois_premium_domain_marketplace.php', 'whois_submit_domain_for_auction.php', 'whois_limited_time_domain_auctions.php']],
    ['label' => 'Tools', 'href' => 'whois_domain_tools_overview.php', 'match' => ['whois_domain_tools_overview.php', 'whois_domain_appraisal_tool.php']],
    ['label' => 'Insights', 'href' => 'whois_industry_insights_blog.php', 'match' => ['whois_industry_insights_blog.php']],
    ['label' => 'Partner', 'href' => 'whois_partner_with_us.php', 'match' => ['whois_partner_with_us.php']],
];
?>
<nav class="fixed top-0 z-50 w-full border-b border-outline-variant/20 bg-white/85 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
  <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-4 lg:px-8">
    <a class="flex shrink-0 items-baseline gap-3 text-black" href="whois_premium_domain_intelligence_landing_page.php">
      <span class="font-['Manrope'] text-xl font-black tracking-tighter">WHOIS</span>
      <span class="hidden text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-400 sm:inline">Intelligence Suite</span>
    </a>
    <div class="hidden items-center gap-6 text-sm font-semibold tracking-tight xl:flex">
      <?php foreach ($navItems as $navItem): ?>
        <?php $isActive = in_array($currentPage, $navItem['match'], true); ?>
        <a class="<?php echo $isActive ? 'text-black' : 'text-neutral-500 hover:text-black'; ?> transition-colors" href="<?php echo htmlspecialchars((string) $navItem['href'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $navItem['label'], ENT_QUOTES, 'UTF-8'); ?></a>
      <?php endforeach; ?>
    </div>
    <div class="flex items-center gap-3">
      <details class="group relative">
        <summary class="list-none cursor-pointer rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black transition-colors hover:bg-surface-container-low">Explore</summary>
        <div class="absolute right-0 mt-3 w-72 rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm shadow-[0_18px_40px_rgba(0,0,0,0.08)]">
          <div class="space-y-2">
            <?php foreach ($navItems as $navItem): ?>
              <a class="block text-neutral-500 transition-colors hover:text-black" href="<?php echo htmlspecialchars((string) $navItem['href'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $navItem['label'], ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </details>
    </div>
  </div>
</nav>
