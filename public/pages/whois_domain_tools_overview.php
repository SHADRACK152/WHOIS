<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Domain Tools Overview | WHOIS Intelligence Suite</title>
<?php require __DIR__ . '/_head.php'; ?>
</head>
<body class="bg-surface text-on-surface antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-20 pb-24">

  <!-- Hero -->
  <section class="hero-gradient max-w-7xl mx-auto px-6 lg:px-8 py-20 mb-8">
    <div class="inline-block px-4 py-1.5 mb-6 rounded-full bg-surface-container-high text-on-surface-variant font-label text-xs tracking-[0.14em] font-bold uppercase">Premium Ecosystem</div>
    <h1 class="font-headline text-5xl md:text-7xl font-extrabold tracking-tighter text-primary mb-6 leading-none">
      Professional<br/>Domain Tools
    </h1>
    <p class="text-xl text-on-surface-variant max-w-2xl leading-relaxed">
      Advanced diagnostic and discovery utilities designed for domain investors, architects, and technical professionals.
    </p>
  </section>

  <!-- Tools Grid -->
  <section class="max-w-7xl mx-auto px-6 lg:px-8 mb-24">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

      <!-- AI Name Generator -->
      <a href="whois_ai_domain_name_generator.php" class="group bg-surface-container-lowest p-10 rounded-2xl border border-outline-variant/30 hover:shadow-[0_24px_60px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center mb-8 border border-outline-variant/20">
          <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">auto_awesome</span>
        </div>
        <h3 class="font-headline text-xl font-bold mb-3 tracking-tight">Domain Name Generator</h3>
        <p class="text-on-surface-variant mb-8 leading-relaxed text-sm flex-1">Get short, brandable domain name ideas in seconds using our AI engine. Includes pricing and availability.</p>
        <div class="flex items-center gap-2 text-sm font-bold text-primary group-hover:gap-4 transition-all mt-auto">
          Launch Tool <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </div>
      </a>

      <!-- Domain Appraisal -->
      <a href="whois_domain_appraisal_tool.php" class="group bg-surface-container-lowest p-10 rounded-2xl border border-outline-variant/30 hover:shadow-[0_24px_60px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center mb-8 border border-outline-variant/20">
          <span class="material-symbols-outlined text-primary">monitoring</span>
        </div>
        <h3 class="font-headline text-xl font-bold mb-3 tracking-tight">AI Domain Appraisal</h3>
        <p class="text-on-surface-variant mb-8 leading-relaxed text-sm flex-1">Instantly check your domain's market value using AI analysis of historical sales data and SEO signals.</p>
        <div class="flex items-center gap-2 text-sm font-bold text-primary group-hover:gap-4 transition-all mt-auto">
          Launch Tool <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </div>
      </a>

      <!-- WHOIS Lookup -->
      <a href="whois_professional_lookup_tool.php" class="group bg-surface-container-lowest p-10 rounded-2xl border border-outline-variant/30 hover:shadow-[0_24px_60px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center mb-8 border border-outline-variant/20">
          <span class="material-symbols-outlined text-primary">person_search</span>
        </div>
        <h3 class="font-headline text-xl font-bold mb-3 tracking-tight">WHOIS Lookup</h3>
        <p class="text-on-surface-variant mb-8 leading-relaxed text-sm flex-1">Full RDAP/WHOIS ownership records, registrar info, nameservers, and full domain history in one place.</p>
        <div class="flex items-center gap-2 text-sm font-bold text-primary group-hover:gap-4 transition-all mt-auto">
          Launch Tool <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </div>
      </a>

      <!-- DNS Checker -->
      <a href="whois_dns_checker.php" class="group bg-surface-container-lowest p-10 rounded-2xl border border-outline-variant/30 hover:shadow-[0_24px_60px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center mb-8 border border-outline-variant/20">
          <span class="material-symbols-outlined text-primary">public</span>
        </div>
        <h3 class="font-headline text-xl font-bold mb-3 tracking-tight">DNS Propagation Checker</h3>
        <p class="text-on-surface-variant mb-8 leading-relaxed text-sm flex-1">Real-time DNS propagation across global nodes. See which regions have your latest DNS changes.</p>
        <div class="flex items-center gap-2 text-sm font-bold text-primary group-hover:gap-4 transition-all mt-auto">
          Launch Tool <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </div>
      </a>

      <!-- Brand Preview -->
      <a href="whois_brand_preview.php" class="group bg-surface-container-lowest p-10 rounded-2xl border border-outline-variant/30 hover:shadow-[0_24px_60px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center mb-8 border border-outline-variant/20">
          <span class="material-symbols-outlined text-primary">branding_watermark</span>
        </div>
        <h3 class="font-headline text-xl font-bold mb-3 tracking-tight">Brand Preview</h3>
        <p class="text-on-surface-variant mb-8 leading-relaxed text-sm flex-1">Visualize your domain on landing pages, mobile app splash screens, and brand assets — before you buy.</p>
        <div class="flex items-center gap-2 text-sm font-bold text-primary group-hover:gap-4 transition-all mt-auto">
          Launch Tool <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </div>
      </a>

      <!-- AI Domain Search -->
      <a href="whois_ai_domain_search.php" class="group bg-surface-container-lowest p-10 rounded-2xl border border-outline-variant/30 hover:shadow-[0_24px_60px_rgba(0,0,0,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center mb-8 border border-outline-variant/20">
          <span class="material-symbols-outlined text-primary">manage_search</span>
        </div>
        <h3 class="font-headline text-xl font-bold mb-3 tracking-tight">AI Domain Search</h3>
        <p class="text-on-surface-variant mb-8 leading-relaxed text-sm flex-1">Intelligent domain search with availability checks, AI alternatives, and premium match recommendations.</p>
        <div class="flex items-center gap-2 text-sm font-bold text-primary group-hover:gap-4 transition-all mt-auto">
          Launch Tool <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </div>
      </a>

    </div>
  </section>

  <!-- Why WHOIS -->
  <section class="bg-surface-container-low py-20 border-y border-outline-variant/20 mb-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="flex flex-col md:flex-row items-center justify-between gap-16">
        <div class="max-w-md">
          <h2 class="font-headline text-3xl font-bold mb-6 tracking-tight">Why use WHOIS tools?</h2>
          <p class="text-on-surface-variant leading-relaxed">We combine authoritative RDAP/WHOIS registry data with proprietary AI valuation models to give you the domain industry's most accurate insights — all in one suite.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 flex-1">
          <div class="text-center">
            <span class="material-symbols-outlined text-3xl mb-4 block">verified</span>
            <h4 class="font-headline font-bold mb-2">RDAP Accredited</h4>
            <p class="text-sm text-on-surface-variant">Direct access to 1,200+ TLD registries.</p>
          </div>
          <div class="text-center">
            <span class="material-symbols-outlined text-3xl mb-4 block">psychology</span>
            <h4 class="font-headline font-bold mb-2">AI-Powered</h4>
            <p class="text-sm text-on-surface-variant">LLaMA-driven semantic discovery and appraisal.</p>
          </div>
          <div class="text-center">
            <span class="material-symbols-outlined text-3xl mb-4 block">update</span>
            <h4 class="font-headline font-bold mb-2">Real-time</h4>
            <p class="text-sm text-on-surface-variant">Live DNS propagation and registry data.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Search CTA -->
  <section class="max-w-7xl mx-auto px-6 lg:px-8 py-12 text-center">
    <h2 class="font-headline text-4xl font-extrabold mb-10 tracking-tight">Ready to find your domain?</h2>
    <form action="whois_ai_domain_search.php" method="GET" class="relative max-w-2xl mx-auto">
      <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
        <span class="material-symbols-outlined text-neutral-400">search</span>
      </div>
      <input name="query" class="w-full bg-surface-container-lowest border border-outline-variant text-lg px-16 py-6 rounded-full focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition-all shadow-sm" placeholder="Search for your next digital identity..." type="text" required/>
      <button type="submit" class="absolute right-3 top-3 bottom-3 bg-primary text-on-primary px-8 rounded-full font-bold hover:bg-primary-container active:scale-95 transition-all">Search</button>
    </form>
  </section>

</main>
<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body>
</html>
