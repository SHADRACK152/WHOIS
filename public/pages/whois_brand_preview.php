<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Brand Preview | WHOIS Intelligence Suite</title>
<?php require __DIR__ . '/_head.php'; ?>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-20 pb-24">

  <!-- Hero -->
  <section class="hero-gradient px-6 py-16 text-center">
    <div class="max-w-3xl mx-auto">
      <span class="inline-block px-4 py-1 rounded-full bg-surface-container-high text-[10px] font-bold uppercase tracking-[0.2em] text-outline mb-6">AI Tools</span>
      <h1 class="font-headline text-5xl md:text-6xl font-extrabold tracking-tighter text-primary mb-6">Brand Preview</h1>
      <p class="text-on-surface-variant text-lg md:text-xl max-w-2xl mx-auto mb-10">
        Instantly visualize your domain on modern landing pages, mobile apps, and brand assets. Enter a domain below to see the vision before you buy.
      </p>
      <form id="brand-preview-form" class="flex flex-col sm:flex-row gap-4 justify-center items-center max-w-xl mx-auto" onsubmit="return false;">
        <input id="brand-preview-input" class="w-full px-6 py-4 rounded-full border border-outline-variant/30 text-lg font-medium focus:ring-2 focus:ring-black focus:border-transparent outline-none transition-all bg-white" placeholder="yourbrand.com" type="text"/>
        <button id="brand-preview-btn" class="bg-black text-white px-8 py-4 rounded-full font-bold text-base hover:bg-neutral-800 transition-colors whitespace-nowrap" type="submit">Preview Brand</button>
      </form>
    </div>
  </section>

  <!-- Results  -->
  <section id="brand-preview-results" class="hidden max-w-5xl mx-auto px-6 mt-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

      <!-- Landing Page Mockup -->
      <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-outline-variant/20">
        <div class="w-full h-10 bg-gradient-to-r from-primary to-tertiary" id="brand-preview-header-bar"></div>
        <div class="p-8 flex flex-col items-center">
          <span class="material-symbols-outlined text-5xl mb-3 text-on-surface-variant">web</span>
          <h2 class="font-headline text-2xl font-bold mb-4">Landing Page</h2>
          <div id="brand-preview-landing" class="w-full rounded-2xl p-6 flex flex-col items-center justify-center border border-outline-variant/20 bg-surface-container-lowest text-center">
            <span class="text-2xl font-extrabold" id="brand-preview-landing-domain">yourbrand.com</span>
            <span class="text-sm text-on-surface-variant mt-2">Modern. Clean. Trustworthy.</span>
            <button class="mt-4 px-5 py-2 rounded-full text-white text-sm font-bold" id="brand-preview-landing-btn">Get Started</button>
          </div>
        </div>
      </div>

      <!-- Mobile App Mockup -->
      <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-outline-variant/20 flex flex-col items-center py-8">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3" id="brand-preview-icon-bg">
          <span class="material-symbols-outlined text-white text-2xl">smartphone</span>
        </div>
        <h2 class="font-headline text-2xl font-bold mb-4">Mobile App</h2>
        <div class="w-40 h-72 bg-surface-container-lowest rounded-3xl border-4 flex flex-col items-center justify-center shadow-inner" id="brand-preview-phone-frame">
          <span class="text-base font-extrabold text-primary" id="brand-preview-mobile-domain">yourbrand.com</span>
          <span class="text-xs text-on-surface-variant mt-2">Splash Screen</span>
        </div>
      </div>

      <!-- Brand Asset — full width -->
      <div class="md:col-span-2 bg-white rounded-3xl shadow-xl overflow-hidden border border-outline-variant/20 p-8">
        <div class="flex items-center gap-4 mb-6">
          <span class="material-symbols-outlined text-4xl text-on-surface-variant">branding_watermark</span>
          <h2 class="font-headline text-2xl font-bold">Brand Asset</h2>
        </div>
        <div id="brand-preview-asset" class="w-full h-32 rounded-2xl flex items-center justify-center">
          <span class="text-4xl font-extrabold text-white drop-shadow" id="brand-preview-asset-domain">yourbrand.com</span>
        </div>
        <div class="mt-5 flex items-center gap-3">
          <span class="w-8 h-8 rounded-full border-2 border-white shadow" id="palette-0"></span>
          <span class="w-8 h-8 rounded-full border-2 border-white shadow" id="palette-1"></span>
          <span class="w-8 h-8 rounded-full border-2 border-white shadow" id="palette-2"></span>
          <span class="text-xs text-on-surface-variant ml-2">Sample brand palette for <strong id="palette-domain-label">yourbrand.com</strong></span>
        </div>
      </div>
    </div>

    <!-- Action links -->
    <div class="mt-10 flex flex-wrap gap-4 justify-center">
      <a id="brand-check-availability" href="whois_ai_domain_search.php" class="rounded-full bg-black px-6 py-3 text-sm font-bold uppercase tracking-widest text-white hover:bg-neutral-800 transition-colors">Check Availability</a>
      <a href="whois_domain_appraisal_tool.php" class="rounded-full border border-outline-variant/40 px-6 py-3 text-sm font-bold uppercase tracking-widest text-primary hover:border-black transition-colors">Get Appraisal</a>
    </div>
  </section>

  <!-- Placeholder when no input -->
  <section id="brand-preview-placeholder" class="max-w-5xl mx-auto px-6 mt-16 text-center">
    <div class="rounded-3xl border border-dashed border-outline-variant/50 p-16 bg-surface-container-lowest">
      <span class="material-symbols-outlined text-6xl text-outline mb-4 block">palette</span>
      <p class="text-on-surface-variant font-medium">Enter a domain name above to generate your brand preview.</p>
    </div>
  </section>

</main>
<?php require __DIR__ . '/_footer.php'; ?>
<script>
(function () {
  var form      = document.getElementById('brand-preview-form');
  var input     = document.getElementById('brand-preview-input');
  var btn       = document.getElementById('brand-preview-btn');
  var results   = document.getElementById('brand-preview-results');
  var placeholder = document.getElementById('brand-preview-placeholder');

  // DOM refs
  var landingDomain  = document.getElementById('brand-preview-landing-domain');
  var mobileDomain   = document.getElementById('brand-preview-mobile-domain');
  var assetDomain    = document.getElementById('brand-preview-asset-domain');
  var assetEl        = document.getElementById('brand-preview-asset');
  var headerBar      = document.getElementById('brand-preview-header-bar');
  var iconBg         = document.getElementById('brand-preview-icon-bg');
  var phoneFrame     = document.getElementById('brand-preview-phone-frame');
  var landingEl      = document.getElementById('brand-preview-landing');
  var landingBtn     = document.getElementById('brand-preview-landing-btn');
  var p0 = document.getElementById('palette-0');
  var p1 = document.getElementById('palette-1');
  var p2 = document.getElementById('palette-2');
  var palLabel = document.getElementById('palette-domain-label');
  var checkLink = document.getElementById('brand-check-availability');

  function hashColor(str, offset, s, l) {
    var h = 0;
    for (var i = 0; i < str.length; i++) {
      h = ((h << 5) - h + str.charCodeAt(i)) | 0;
    }
    return 'hsl(' + (((h + offset) % 360 + 360) % 360) + ',' + s + '%,' + l + '%)';
  }

  function preview(domain) {
    var c1 = hashColor(domain, 0,   68, 42);
    var c2 = hashColor(domain, 120, 55, 32);
    var c3 = hashColor(domain, 240, 70, 95);

    // Update all text labels
    landingDomain.textContent = domain;
    mobileDomain.textContent  = domain;
    assetDomain.textContent   = domain;
    palLabel.textContent      = domain;

    // Apply generated colours
    assetEl.style.background   = 'linear-gradient(135deg, ' + c1 + ' 0%, ' + c2 + ' 100%)';
    headerBar.style.background = 'linear-gradient(90deg, ' + c1 + ' 0%, ' + c2 + ' 100%)';
    iconBg.style.background    = 'linear-gradient(135deg, ' + c1 + ' 0%, ' + c2 + ' 100%)';
    phoneFrame.style.borderColor = c1;
    landingEl.style.background   = 'linear-gradient(180deg, ' + c3 + ' 0%, #fff 100%)';
    landingBtn.style.background  = c1;

    if (p0) p0.style.background = c1;
    if (p1) p1.style.background = c2;
    if (p2) p2.style.background = c3;

    // Availability link
    if (checkLink) checkLink.href = 'whois_ai_domain_search.php?query=' + encodeURIComponent(domain);

    // Show results
    results.classList.remove('hidden');
    placeholder.classList.add('hidden');
  }

  function run() {
    var d = input.value.trim();
    if (!d) { input.focus(); return; }
    preview(d);
  }

  form.addEventListener('submit', run);
  btn.addEventListener('click', run);
  input.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); run(); } });
}());
</script>
<script src="../assets/js/nav-state.js"></script>
</body>
</html>
