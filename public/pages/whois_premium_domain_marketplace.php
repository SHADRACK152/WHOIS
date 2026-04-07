<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../../app/db-client.php';

$marketplaceItems = whois_db_list_marketplace_items(['status' => 'live']);
$imageMap = [
  'neural.ai' => [
    'src' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWRevZjxASkDW6dMTXUcg9ns0piYMgEvre9vRz09eqeijUPKFNHad0Yw_OXGv4_Wovu6G1XM5bgyd6CCgHyXf2DvolzF7u_ORG3GVWCK6SSD8d_Mm1kpQVGxMOmdT1OVKQxdYRO6M0PBZuu_cslEtWxCSZWedayiCl47n-kpun7bDRI3pQ-9BmOpVNMGSGhqEBlhDnsbIZ7H-LibUvtbaGDSASqgGwJGGAAp1mTR61MBTcvnR6Y1Vb6DlBuC9w-gqQoKoBTECyHAZ_',
    'alt' => 'minimalist aesthetic computer monitor on desk top view black and white',
  ],
  'flow.io' => [
    'src' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDyPk8PcZdTLMqnUKY39MOrHKtQ3g_XyA2-Gn8VkbvlkN7xOPAaoTmype7gCwq62KvJg4-XPQynpjAcpHeXbSijAyTVenFZv9EIKyrxVSyFF_EmVI6bDRh5Y-FQdH-rvHrcYgKwQAfhmPzlsVbL0dqqgmiYl6up1FYCbfgXvP2aL1VbpWhq3xROsnWGoOOt93aXS9PPtP1fBA_GZCPq8E_bdKp88uTuY8_hWiwI7WWTlbvsj9AEduou77uHQtVbfBLvrNDKVbnNiZVU',
    'alt' => 'abstract geometric white shapes shadow play architectural detail',
  ],
];

if ($marketplaceItems === []) {
  $pdo = whois_db_pdo();

  if ($pdo instanceof PDO) {
    whois_db_seed_marketplace_items($pdo);
    $marketplaceItems = whois_db_list_marketplace_items(['status' => 'live']);
  }
}

$featuredItems = array_values(array_filter($marketplaceItems, static function (array $item): bool {
  return (string) ($item['listing_type'] ?? 'row') === 'featured';
}));

$rowItems = array_values(array_filter($marketplaceItems, static function (array $item): bool {
  return (string) ($item['listing_type'] ?? 'row') !== 'featured';
}));

if (!function_exists('whois_render_marketplace_featured_card')) {
  function whois_render_marketplace_featured_card(array $item, array $imageMap): void
  {
    $domainName = (string) ($item['domain_name'] ?? '');
    $image = $imageMap[$domainName] ?? [
      'src' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWRevZjxASkDW6dMTXUcg9ns0piYMgEvre9vRz09eqeijUPKFNHad0Yw_OXGv4_Wovu6G1XM5bgyd6CCgHyXf2DvolzF7u_ORG3GVWCK6SSD8d_Mm1kpQVGxMOmdT1OVKQxdYRO6M0PBZuu_cslEtWxCSZWedayiCl47n-kpun7bDRI3pQ-9BmOpVNMGSGhqEBlhDnsbIZ7H-LibUvtbaGDSASqgGwJGGAAp1mTR61MBTcvnR6Y1Vb6DlBuC9w-gqQoKoBTECyHAZ_',
      'alt' => 'premium marketplace visual',
    ];
    $displayName = strtoupper($domainName);
    $badgeText = (string) ($item['badge_text'] ?? 'Available');
    $categories = (string) ($item['categories'] ?? 'Premium');
    $appraisalPrice = '$' . number_format((float) ($item['appraisal_price'] ?? 0));
    $price = '$' . number_format((float) ($item['price'] ?? 0));
    $extension = '.' . strtoupper((string) ($item['extension'] ?? ''));
    ?>
    <div class="group bg-surface-container-lowest rounded-xl p-8 border border-outline-variant/10 hover:border-primary/20 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,0,0,0.04)] relative overflow-hidden flex flex-col md:flex-row gap-8" data-marketplace-item="true" data-domain="<?php echo htmlspecialchars($domainName, ENT_QUOTES, 'UTF-8'); ?>" data-extension="<?php echo htmlspecialchars((string) ($item['extension'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-price="<?php echo htmlspecialchars((string) ($item['price'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>" data-search-text="<?php echo htmlspecialchars((string) ($item['search_text'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
    <div class="w-32 h-32 bg-surface-container-low rounded-lg flex items-center justify-center shrink-0">
    <img class="w-20 h-20 object-contain grayscale opacity-60" data-alt="<?php echo htmlspecialchars($image['alt'], ENT_QUOTES, 'UTF-8'); ?>" src="<?php echo htmlspecialchars($image['src'], ENT_QUOTES, 'UTF-8'); ?>"/>
    </div>
    <div class="flex flex-col justify-between flex-1">
    <div>
    <div class="flex justify-between items-start">
    <span class="text-sm font-bold text-primary font-headline tracking-tighter uppercase mb-2 block"><?php echo htmlspecialchars($badgeText, ENT_QUOTES, 'UTF-8'); ?></span>
    <span class="material-symbols-outlined text-neutral-300 hover:text-primary cursor-pointer" data-icon="star">star</span>
    </div>
    <h3 class="text-2xl font-bold tracking-tight mb-2"><?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></h3>
    <div class="flex gap-4 items-center">
    <span class="px-2 py-0.5 bg-surface-container-high rounded text-[10px] font-bold uppercase tracking-widest text-neutral-500"><?php echo htmlspecialchars($extension, ENT_QUOTES, 'UTF-8'); ?></span>
    <span class="text-xs text-neutral-400">Appraisal: <?php echo htmlspecialchars($appraisalPrice, ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    </div>
    <div class="flex items-center justify-between mt-6">
    <span class="text-2xl font-bold font-headline"><?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?></span>
    <button class="px-6 py-2.5 bg-primary text-on-primary rounded-lg text-xs font-bold uppercase tracking-widest hover:scale-95 transition-all">Buy Now</button>
    </div>
    </div>
    </div>
    <?php
  }
}

if (!function_exists('whois_render_marketplace_row_card')) {
  function whois_render_marketplace_row_card(array $item): void
  {
    $domainName = (string) ($item['domain_name'] ?? '');
    $categories = (string) ($item['categories'] ?? '');
    $appraisalPrice = '$' . number_format((float) ($item['appraisal_price'] ?? 0));
    $price = '$' . number_format((float) ($item['price'] ?? 0));
    $iconName = (string) ($item['icon_name'] ?? 'circle');
    ?>
    <div class="flex items-center justify-between p-6 bg-surface-container-lowest hover:bg-surface-container-high transition-colors rounded-xl border border-outline-variant/10" data-marketplace-item="true" data-domain="<?php echo htmlspecialchars($domainName, ENT_QUOTES, 'UTF-8'); ?>" data-extension="<?php echo htmlspecialchars((string) ($item['extension'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-price="<?php echo htmlspecialchars((string) ($item['price'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>" data-search-text="<?php echo htmlspecialchars((string) ($item['search_text'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
    <div class="flex items-center gap-8">
    <div class="w-12 h-12 bg-surface-container flex items-center justify-center rounded-lg text-neutral-400 shrink-0">
    <span class="material-symbols-outlined" data-icon="<?php echo htmlspecialchars($iconName, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($iconName, ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    <div>
    <h4 class="text-lg font-bold"><?php echo htmlspecialchars($domainName, ENT_QUOTES, 'UTF-8'); ?></h4>
    <p class="text-xs text-neutral-400"><?php echo htmlspecialchars($categories, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    </div>
    <div class="hidden md:flex gap-12 items-center">
    <div class="text-right">
    <p class="text-[10px] uppercase font-bold text-neutral-400 tracking-widest">Appraisal</p>
    <p class="text-sm font-semibold"><?php echo htmlspecialchars($appraisalPrice, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    <div class="text-right">
    <p class="text-[10px] uppercase font-bold text-neutral-400 tracking-widest">Price</p>
    <p class="text-sm font-bold"><?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    <button class="px-5 py-2 border border-primary text-primary rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-primary hover:text-white transition-all">Buy Now</button>
    </div>
    </div>
    <?php
  }
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS.ARCHITECT | Premium Domain Marketplace</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-highest": "#e2e2e2",
                        "surface-container": "#eeeeee",
                        "tertiary-fixed": "#5d5f5f",
                        "on-tertiary": "#e2e2e2",
                        "inverse-on-surface": "#f1f1f1",
                        "on-primary-fixed": "#ffffff",
                        "outline-variant": "#c6c6c6",
                        "on-secondary": "#ffffff",
                        "inverse-surface": "#2f3131",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "secondary-fixed": "#c7c6c6",
                        "primary-fixed-dim": "#474747",
                        "error-container": "#ffdad6",
                        "surface-tint": "#5e5e5e",
                        "on-primary": "#e2e2e2",
                        "on-error": "#ffffff",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "surface-container-high": "#e8e8e8",
                        "tertiary-fixed-dim": "#454747",
                        "primary": "#000000",
                        "tertiary": "#3a3c3c",
                        "primary-fixed": "#5e5e5e",
                        "on-tertiary-fixed": "#ffffff",
                        "surface-bright": "#f9f9f9",
                        "primary-container": "#3b3b3b",
                        "error": "#ba1a1a",
                        "on-surface": "#1a1c1c",
                        "on-primary-container": "#ffffff",
                        "secondary-fixed-dim": "#acabab",
                        "inverse-primary": "#c6c6c6",
                        "on-background": "#1a1c1c",
                        "background": "#f9f9f9",
                        "surface-dim": "#dadada",
                        "surface-container-low": "#f3f3f3",
                        "outline": "#777777",
                        "secondary": "#5e5e5e",
                        "on-secondary-container": "#1b1c1c",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "on-error-container": "#410002",
                        "tertiary-container": "#737575",
                        "on-surface-variant": "#474747",
                        "surface": "#f9f9f9",
                        "secondary-container": "#d5d4d4",
                        "on-tertiary-container": "#ffffff",
                        "surface-variant": "#e2e2e2",
                        "on-secondary-fixed": "#1b1c1c"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface-variant antialiased">
<nav class="fixed top-0 w-full z-50 bg-white/85 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border-b border-outline-variant/20">
  <div class="flex items-center justify-between gap-6 px-6 lg:px-8 py-4 max-w-7xl mx-auto">
    <a class="flex items-baseline gap-3 shrink-0 text-black" href="whois_premium_domain_intelligence_landing_page.php">
      <span class="text-xl font-black tracking-tighter font-['Manrope']">WHOIS</span>
      <span class="hidden sm:inline text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-400">Intelligence Suite</span>
    </a>
    <div class="hidden xl:flex items-center gap-6 text-sm font-semibold font-['Manrope'] tracking-tight">
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_intelligence_landing_page.php">Home</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_ai_domain_search.php">Search</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_ai_brand_assistant.php">AI Assistants</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_marketplace.php">Marketplace</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_domain_tools_overview.php">Tools</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_industry_insights_blog.php">Insights</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_partner_with_us.php">Partner</a>
    </div>
    <div class="flex items-center gap-3">
      <details class="relative group">
        <summary class="list-none cursor-pointer rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors">Explore</summary>
        <div class="absolute right-0 mt-3 w-[min(92vw,56rem)] rounded-[1.5rem] border border-outline-variant/20 bg-white/95 p-6 shadow-[0_30px_60px_rgba(0,0,0,0.08)] backdrop-blur-xl">
          <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Search</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_ai_domain_search.php">AI Domain Search</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_premium_ai_domain_search.php">Premium AI Search</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_professional_lookup_tool.php">Professional Lookup</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_optimized_premium_search_results.php">Optimized Results</a>
              </div>
            </div>
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">AI Assistants</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_ai_brand_assistant.php">Brand Assistant</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_ai_business_idea_generator.php">Business Idea Generator</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_ai_domain_name_generator.php">Domain Name Generator</a>
              </div>
            </div>
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Marketplace</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_marketplace.php">Premium Marketplace</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_limited_time_domain_auctions.php">Limited Auctions</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_submit_domain_for_auction.php">Submit for Auction</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_search_results_with_premium_logos.php">Premium Logos Results</a>
              </div>
            </div>
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Tools</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_domain_tools_overview.php">Tools Overview</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_domain_appraisal_tool.php">Domain Appraisal</a>
              </div>
            </div>
                        <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Results</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_comprehensive_search_results.php">Comprehensive Results</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_domain_search_results.php">Search Results</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_optimized_premium_search_results.php">Optimized Premium Search Results</a>
              </div>
            </div>
            <div>
              <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">More</p>
              <div class="space-y-2 text-sm">
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_intelligence_landing_page.php">Landing Page</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_industry_insights_blog.php">Industry Insights</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_partner_with_us.php">Partner With Us</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="whois_professional_lookup_tool.php">Professional Lookup</a>
                <a class="block text-neutral-500 hover:text-black transition-colors" href="../admin/index.php">Admin Console</a>
              </div>
            </div>
          </div>
        </div>
      </details>
      <a class="rounded-full bg-primary px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-on-primary hover:bg-primary-container transition-colors" href="whois_ai_domain_search.php">Search</a>
    </div>
  </div>
</nav>
<div class="flex pt-20 min-h-screen">
<!-- SideNavBar -->
<aside class="hidden lg:flex flex-col h-[calc(100vh-80px)] w-64 border-r border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-950 sticky top-20 p-6 space-y-8 overflow-y-auto">
<div>
<h3 class="font-['Inter'] text-sm uppercase tracking-widest text-neutral-900 dark:text-neutral-50 font-bold mb-1">Filters</h3>
<p class="text-[10px] uppercase tracking-tighter text-neutral-400">Refine Identity</p>
</div>
<div class="pt-4 space-y-6">
<div>
<span class="block text-[10px] font-bold uppercase tracking-widest mb-4">Price Range</span>
<div class="relative h-6">
<div class="absolute left-0 right-0 top-2 h-1 bg-surface-container-high rounded-full"></div>
<div class="absolute left-0 top-2 h-1 bg-primary rounded-full" id="marketplace-price-fill" style="width: 100%;"></div>
<input aria-label="Maximum price" class="absolute inset-0 w-full cursor-pointer opacity-0" id="marketplace-price-range" max="50000" min="0" step="500" type="range" value="50000"/>
</div>
<div class="flex justify-between mt-3 text-[10px] font-medium">
<span>$0</span>
<span id="marketplace-price-value">$50k+</span>
</div>
</div>
<div>
<span class="block text-[10px] font-bold uppercase tracking-widest mb-2">Extension</span>
<div class="grid grid-cols-2 gap-2">
<button class="text-[10px] border border-outline-variant/30 py-1 rounded hover:bg-surface-container transition-colors" data-marketplace-extension-button="true" data-extension-value="com" type="button">.com</button>
<button class="text-[10px] border border-outline-variant/30 py-1 rounded hover:bg-surface-container transition-colors" data-marketplace-extension-button="true" data-extension-value="ai" type="button">.ai</button>
<button class="text-[10px] border border-outline-variant/30 py-1 rounded hover:bg-surface-container transition-colors" data-marketplace-extension-button="true" data-extension-value="io" type="button">.io</button>
<button class="text-[10px] border border-outline-variant/30 py-1 rounded hover:bg-surface-container transition-colors" data-marketplace-extension-button="true" data-extension-value="net" type="button">.net</button>
</div>
</div>
</div>
<button class="w-full py-3 bg-primary text-on-primary rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-all" id="marketplace-apply-filters" type="button">Apply Filters</button>
<div class="mt-auto pt-8 border-t border-neutral-100 dark:border-neutral-900 space-y-4">
<a class="flex items-center gap-3 text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-300 transition-colors" href="#">
<span class="material-symbols-outlined text-lg" data-icon="help_outline">help_outline</span>
<span class="font-['Inter'] text-[10px] uppercase tracking-widest">Support</span>
</a>
<a class="flex items-center gap-3 text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-300 transition-colors" href="#">
<span class="material-symbols-outlined text-lg" data-icon="settings">settings</span>
<span class="font-['Inter'] text-[10px] uppercase tracking-widest">Settings</span>
</a>
</div>
</aside>
<!-- Main Canvas -->
<main class="flex-1 px-8 py-12 lg:px-16 overflow-x-hidden">
<!-- Hero Section -->
<section class="max-w-5xl mx-auto text-center mb-24">
<span class="inline-block px-4 py-1.5 bg-surface-container-high rounded-full text-[10px] font-bold uppercase tracking-widest mb-6">Identity Marketplace</span>
<h1 class="text-5xl md:text-7xl font-extrabold font-headline tracking-tighter text-primary mb-6">Premium Domain Marketplace</h1>
<p class="text-lg text-neutral-500 max-w-2xl mx-auto mb-12">Find the foundation for your next great idea. Curated digital assets with AI-backed appraisal and secure ownership transfer.</p>
<div class="relative max-w-3xl mx-auto group">
<div class="absolute -inset-1 bg-gradient-to-r from-neutral-200 to-neutral-300 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
<div class="relative flex items-center bg-surface-container-lowest border border-outline-variant/40 rounded-full p-2 shadow-sm transition-all duration-300 focus-within:ring-2 focus-within:ring-primary focus-within:shadow-xl">
<span class="material-symbols-outlined ml-6 text-neutral-400" data-icon="search">search</span>
<input class="flex-1 bg-transparent border-none focus:ring-0 px-4 py-4 text-lg font-medium placeholder:text-neutral-300" id="marketplace-search-input" placeholder="Search the perfect domain name..." type="text"/>
<button class="bg-primary text-on-primary px-10 py-4 rounded-full font-bold text-sm tracking-tight hover:scale-[0.98] transition-transform" id="marketplace-search-button" type="button">Find My Domain</button>
</div>
</div>
</section>
<!-- Featured Section -->
<section class="max-w-7xl mx-auto mb-20">
<div class="flex justify-between items-end mb-10">
<div>
<h2 class="text-3xl font-bold font-headline tracking-tight">Trending Domains</h2>
<p class="text-neutral-400 text-sm"><span id="marketplace-visible-count"><?php echo count($marketplaceItems); ?></span> premium assets shown this week.</p>
</div>
<a class="text-sm font-bold border-b border-primary pb-1" href="#">View All Trending</a>
</div>
<div class="mb-8 rounded-3xl border border-outline-variant/20 bg-gradient-to-r from-neutral-950 to-neutral-800 p-8 md:p-10 text-white overflow-hidden relative">
<div class="absolute inset-y-0 right-0 w-64 bg-white/5 blur-3xl"></div>
<div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
<div class="max-w-2xl">
<p class="text-[10px] font-bold uppercase tracking-[0.24em] text-white/60 mb-3">Have a domain to sell?</p>
<h3 class="text-2xl md:text-3xl font-extrabold tracking-tight mb-3">Submit it for auction through the marketplace.</h3>
<p class="text-white/70 leading-relaxed">Turn your domain into a listing, set your floor, and reach buyers who are already browsing premium inventory.</p>
</div>
<div class="flex flex-col sm:flex-row gap-3 shrink-0">
<a class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-xs font-black uppercase tracking-[0.2em] text-black hover:bg-neutral-200 transition-colors" href="whois_submit_domain_for_auction.php">Submit Domain</a>
<a class="inline-flex items-center justify-center rounded-full border border-white/20 px-6 py-3 text-xs font-black uppercase tracking-[0.2em] text-white hover:bg-white/10 transition-colors" href="whois_submit_domain_for_auction.php">Auction Flow</a>
</div>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<?php foreach ($featuredItems as $marketplaceItem): ?>
  <?php whois_render_marketplace_featured_card($marketplaceItem, $imageMap); ?>
<?php endforeach; ?>
</div>
</section>
<!-- Main Listing Grid -->
<section class="max-w-7xl mx-auto mb-20">
<h2 class="text-xl font-bold font-headline tracking-tight mb-8">Recently Indexed</h2>
<div class="space-y-4">
<?php foreach ($rowItems as $marketplaceItem): ?>
  <?php whois_render_marketplace_row_card($marketplaceItem); ?>
<?php endforeach; ?>
</div>
<div class="hidden rounded-xl border border-dashed border-outline-variant/40 bg-surface-container-lowest p-6 text-center text-sm text-secondary" id="marketplace-empty-state">
No listings match the current filters. Try a different extension or raise the price ceiling.
</div>
<div class="mt-12 text-center">
<button class="px-8 py-3 bg-surface-container-highest text-primary font-bold rounded-lg text-sm transition-all hover:bg-surface-container" type="button">Load More Listings</button>
</div>
</section>
<!-- Bottom CTA -->
<section class="max-w-7xl mx-auto">
<div class="bg-neutral-900 rounded-3xl p-12 md:p-20 text-center text-white relative overflow-hidden">
<div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
<div class="relative z-10">
<h2 class="text-3xl md:text-5xl font-bold font-headline mb-6 tracking-tight">Have a domain to sell?</h2>
<p class="text-neutral-400 max-w-xl mx-auto mb-10 text-lg">List your premium asset on the world's most trusted boutique marketplace. Professional brokerage and secure escrow included.</p>
<button class="px-12 py-5 bg-white text-black font-bold rounded-full text-sm uppercase tracking-widest hover:scale-105 transition-all">List it here</button>
</div>
</div>
</section>
</main>
</div>
<!-- Footer -->
<footer class="w-full border-t border-neutral-100 dark:border-neutral-800 bg-white dark:bg-neutral-900 mt-20">
<div class="max-w-7xl mx-auto px-8 py-16 grid grid-cols-1 md:grid-cols-4 gap-12">
<div class="col-span-1 md:col-span-1">
<span class="text-lg font-black text-black dark:text-white font-headline tracking-tighter">WHOIS.ARCHITECT</span>
<p class="mt-4 text-neutral-400 text-xs leading-relaxed max-w-xs">The premium destination for digital real estate. Curating the world's most valuable top-level domains for visionary creators.</p>
<div class="flex gap-4 mt-6">
<div class="w-8 h-8 rounded-full bg-neutral-100 flex items-center justify-center cursor-pointer hover:bg-neutral-200 transition-colors">
<span class="material-symbols-outlined text-sm" data-icon="share">share</span>
</div>
<div class="w-8 h-8 rounded-full bg-neutral-100 flex items-center justify-center cursor-pointer hover:bg-neutral-200 transition-colors">
<span class="material-symbols-outlined text-sm" data-icon="language">language</span>
</div>
</div>
</div>
<div>
<h4 class="font-bold text-[10px] uppercase tracking-widest mb-6">Marketplace</h4>
<ul class="space-y-3">
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Browse All</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Premium .AI</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Short Names</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">New Extensions</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-[10px] uppercase tracking-widest mb-6">Resources</h4>
<ul class="space-y-3">
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">API Docs</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Valuation Tool</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Brokerage</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Secure Escrow</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-[10px] uppercase tracking-widest mb-6">Company</h4>
<ul class="space-y-3">
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">About Us</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Contact</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Privacy Policy</a></li>
<li><a class="text-neutral-400 dark:text-neutral-500 hover:text-black dark:hover:text-white transition-opacity text-xs" href="#">Terms of Service</a></li>
</ul>
</div>
</div>
<div class="max-w-7xl mx-auto px-8 py-8 border-t border-neutral-100 flex flex-col md:flex-row justify-between items-center gap-6">
<p class="font-['Inter'] text-xs tracking-normal text-neutral-400">&copy; 2024 WHOIS.ARCHITECT. All rights reserved.</p>
<div class="flex items-center gap-4 grayscale opacity-40">
<span class="text-[10px] font-bold uppercase tracking-widest mr-2">ICANN Accredited</span>
<img class="h-6 w-auto object-contain" data-alt="minimalist monochrome logo for icann accreditation official seal grayscale" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCNfkQhDToRlD5HtRMPkkFVyk4hd2t_3V1vCHNsGHmPA3ULMX867tYa1VJHGlFjJjlQHqat7WHJbz99pjGSiKF2XpUlpvSOIF8etfQxglD9cQ_uiB80jBtqzfnUJhdgPGYQuFkuzygUHFEluKWQ_NqZ_dXtFUdr7eadevqlngmaLxoLfO0EJY974YLv6XF_5nCLwIIMgAijx2DRyj6humK5qNPYlLaUIeyVbsX91PsuOgwuaACXno2VlBMP2Omg10M795saOOKK7lbn"/>
</div>
</div>
</footer>
<script>
(function () {
  const searchInput = document.getElementById('marketplace-search-input');
  const searchButton = document.getElementById('marketplace-search-button');
  const priceRange = document.getElementById('marketplace-price-range');
  const priceFill = document.getElementById('marketplace-price-fill');
  const priceValue = document.getElementById('marketplace-price-value');
  const applyButton = document.getElementById('marketplace-apply-filters');
  const emptyState = document.getElementById('marketplace-empty-state');
  const visibleCount = document.getElementById('marketplace-visible-count');
  const extensionButtons = Array.from(document.querySelectorAll('[data-marketplace-extension-button="true"]'));
  const items = Array.from(document.querySelectorAll('[data-marketplace-item="true"]'));

  let selectedExtension = 'all';

  function formatPriceLabel(value) {
    const numericValue = Number(value);

    if (!Number.isFinite(numericValue) || numericValue >= 50000) {
      return '$50k+';
    }

    return '$' + numericValue.toLocaleString('en-US');
  }

  function updateExtensionButtons() {
    extensionButtons.forEach((button) => {
      const isActive = button.dataset.extensionValue === selectedExtension;

      button.classList.toggle('bg-primary', isActive);
      button.classList.toggle('text-on-primary', isActive);
      button.classList.toggle('border-primary', isActive);
      button.classList.toggle('bg-surface-container-lowest', !isActive);
      button.classList.toggle('text-primary', !isActive);
    });
  }

  function applyFilters() {
    const query = (searchInput?.value || '').trim().toLowerCase();
    const maxPrice = Number(priceRange?.value || 50000);
    let visibleItems = 0;

    if (priceValue) {
      priceValue.textContent = formatPriceLabel(maxPrice);
    }

    if (priceFill) {
      const width = Math.max(0, Math.min(100, (maxPrice / 50000) * 100));
      priceFill.style.width = width + '%';
    }

    items.forEach((item) => {
      const domain = (item.dataset.domain || '').toLowerCase();
      const extension = (item.dataset.extension || '').toLowerCase();
      const price = Number(item.dataset.price || '0');
      const searchText = (item.dataset.searchText || '').toLowerCase();
      const matchesQuery = query === '' || domain.includes(query) || searchText.includes(query);
      const matchesExtension = selectedExtension === 'all' || extension === selectedExtension;
      const matchesPrice = price <= maxPrice;
      const isVisible = matchesQuery && matchesExtension && matchesPrice;

      item.classList.toggle('hidden', !isVisible);

      if (isVisible) {
        visibleItems += 1;
      }
    });

    if (visibleCount) {
      visibleCount.textContent = String(visibleItems);
    }

    if (emptyState) {
      emptyState.classList.toggle('hidden', visibleItems > 0);
    }
  }

  extensionButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const nextExtension = button.dataset.extensionValue || 'all';
      selectedExtension = selectedExtension === nextExtension ? 'all' : nextExtension;
      updateExtensionButtons();
      applyFilters();
    });
  });

  searchInput?.addEventListener('input', applyFilters);
  searchButton?.addEventListener('click', applyFilters);
  priceRange?.addEventListener('input', applyFilters);
  applyButton?.addEventListener('click', applyFilters);

  updateExtensionButtons();
  applyFilters();
})();
</script>
<script src="../assets/js/nav-state.js"></script>
</body></html>




