<?php
// Brand Preview Page
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Brand Preview | WHOIS</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3 { font-family: 'Manrope', sans-serif; }
  </style>
</head>
<body class="bg-background font-body text-on-background antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-24 px-6 max-w-5xl mx-auto">
  <section class="text-center mb-16">
    <h1 class="font-headline text-5xl md:text-7xl font-extrabold tracking-tight text-primary mb-6">Brand Preview</h1>
    <p class="text-on-surface-variant text-lg md:text-xl max-w-2xl mx-auto font-medium mb-8">
      Instantly visualize your domain on modern landing pages, mobile apps, and brand assets. Enter a domain below to see the vision before you buy.
    </p>
    <form id="brand-preview-form" class="flex flex-col sm:flex-row gap-4 justify-center items-center max-w-xl mx-auto" onsubmit="return false;">
      <input id="brand-preview-input" class="w-full px-6 py-4 rounded-full border border-outline-variant/30 text-lg font-medium focus:ring-2 focus:ring-primary" placeholder="yourbrand.com" type="text" />
      <button id="brand-preview-btn" class="bg-primary text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-primary-container transition-colors" type="submit">Preview</button>
    </form>
  </section>
  <section id="brand-preview-results" class="hidden mt-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- Landing Page Mockup -->
      <div class="bg-white rounded-3xl shadow-xl p-0 flex flex-col items-center overflow-hidden">
        <div class="w-full h-10 bg-gradient-to-r from-primary to-tertiary"></div>
        <div class="w-full flex-1 flex flex-col justify-center items-center p-8">
          <span class="material-symbols-outlined text-5xl mb-2">web</span>
          <h2 class="font-headline text-2xl font-bold mb-2">Landing Page</h2>
          <div id="brand-preview-landing" class="w-full h-24 bg-surface-container-lowest rounded-xl flex flex-col items-center justify-center text-2xl font-black text-primary border border-outline-variant/20">
            <span class="text-3xl font-extrabold" id="brand-preview-landing-domain">yourbrand.com</span>
            <span class="text-base text-neutral-500 mt-2">Modern. Clean. Trustworthy.</span>
            <button class="mt-4 px-6 py-2 rounded-full bg-primary text-white font-bold text-sm">Get Started</button>
          </div>
        </div>
      </div>
      <!-- Mobile App Mockup -->
      <div class="bg-white rounded-3xl shadow-xl p-0 flex flex-col items-center overflow-hidden">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary to-tertiary flex items-center justify-center mt-8 mb-2">
          <span class="material-symbols-outlined text-white text-3xl">smartphone</span>
        </div>
        <h2 class="font-headline text-2xl font-bold mb-2">Mobile App</h2>
        <div class="w-40 h-72 bg-surface-container-lowest rounded-2xl border-4 border-primary flex flex-col items-center justify-center shadow-inner mb-8">
          <span class="text-lg font-bold" id="brand-preview-mobile-domain">yourbrand.com</span>
          <span class="text-xs text-neutral-400 mt-2">Splash Screen</span>
        </div>
      </div>
      <!-- Brand Asset Mockup -->
      <div class="bg-white rounded-3xl shadow-xl p-8 flex flex-col items-center md:col-span-2">
        <span class="material-symbols-outlined text-5xl mb-4">branding_watermark</span>
        <h2 class="font-headline text-2xl font-bold mb-2">Brand Asset</h2>
        <div id="brand-preview-asset" class="w-full h-32 bg-gradient-to-r from-primary to-tertiary rounded-xl flex items-center justify-center">
          <span class="text-4xl font-extrabold text-white drop-shadow" id="brand-preview-asset-domain">yourbrand.com</span>
        </div>
        <div class="mt-4 flex gap-2">
          <span class="inline-block w-8 h-8 rounded-full bg-primary border-2 border-white"></span>
          <span class="inline-block w-8 h-8 rounded-full bg-tertiary border-2 border-white"></span>
          <span class="inline-block w-8 h-8 rounded-full bg-surface-container-lowest border-2 border-white"></span>
        </div>
        <span class="text-xs text-neutral-400 mt-2">Sample palette for your brand</span>
      </div>
    </div>
  </section>
</main>
<script>
// Brand Preview JS
const form = document.getElementById('brand-preview-form');
const input = document.getElementById('brand-preview-input');
const results = document.getElementById('brand-preview-results');
const landingDomain = document.getElementById('brand-preview-landing-domain');
const mobileDomain = document.getElementById('brand-preview-mobile-domain');
const assetDomain = document.getElementById('brand-preview-asset-domain');
const btn = document.getElementById('brand-preview-btn');

function extractBrandColors(domain) {
  // Simple color hash for demo (could use backend or AI for real branding)
  let hash = 0;
  for (let i = 0; i < domain.length; i++) {
    hash = domain.charCodeAt(i) + ((hash << 5) - hash);
  }
  // Generate 3 colors
  const color1 = `hsl(${hash % 360}, 70%, 45%)`;
  const color2 = `hsl(${(hash + 120) % 360}, 60%, 35%)`;
  const color3 = `hsl(${(hash + 240) % 360}, 80%, 95%)`;
  return [color1, color2, color3];
}

function updateBrandPreview(domain) {
  landingDomain.textContent = domain;
  mobileDomain.textContent = domain;
  assetDomain.textContent = domain;
  // Update mockup colors
  const [c1, c2, c3] = extractBrandColors(domain);
  // Landing page gradient
  document.querySelector('#brand-preview-landing').style.background = `linear-gradient(90deg, ${c1} 0%, ${c2} 100%)`;
  // Mobile app border
  document.querySelector('#brand-preview-mobile-domain').parentElement.style.borderColor = c1;
  // Brand asset gradient
  document.querySelector('#brand-preview-asset').style.background = `linear-gradient(90deg, ${c1} 0%, ${c2} 100%)`;
  // Brand palette
  const palette = document.querySelectorAll('#brand-preview-results .w-8');
  if (palette.length >= 3) {
    palette[0].style.background = c1;
    palette[1].style.background = c2;
    palette[2].style.background = c3;
  }
  results.classList.remove('hidden');
}

form.addEventListener('submit', function () {
  const domain = input.value.trim();
  if (!domain) {
    input.focus();
    return;
  }
  updateBrandPreview(domain);
});
btn.addEventListener('click', function () {
  const domain = input.value.trim();
  if (!domain) {
    input.focus();
    return;
  }
  updateBrandPreview(domain);
});
</script>
<?php require __DIR__ . '/_footer.php'; ?>
</body>
</html>
