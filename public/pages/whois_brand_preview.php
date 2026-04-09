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
      <div class="bg-white rounded-3xl shadow-xl p-8 flex flex-col items-center">
        <span class="material-symbols-outlined text-5xl mb-4">web</span>
        <h2 class="font-headline text-2xl font-bold mb-2">Landing Page</h2>
        <div id="brand-preview-landing" class="w-full h-40 bg-surface-container-lowest rounded-xl flex items-center justify-center text-2xl font-black text-primary">yourbrand.com</div>
      </div>
      <div class="bg-white rounded-3xl shadow-xl p-8 flex flex-col items-center">
        <span class="material-symbols-outlined text-5xl mb-4">smartphone</span>
        <h2 class="font-headline text-2xl font-bold mb-2">Mobile App</h2>
        <div id="brand-preview-mobile" class="w-full h-40 bg-surface-container-lowest rounded-xl flex items-center justify-center text-2xl font-black text-primary">yourbrand.com</div>
      </div>
      <div class="bg-white rounded-3xl shadow-xl p-8 flex flex-col items-center md:col-span-2">
        <span class="material-symbols-outlined text-5xl mb-4">branding_watermark</span>
        <h2 class="font-headline text-2xl font-bold mb-2">Brand Asset</h2>
        <div id="brand-preview-asset" class="w-full h-32 bg-surface-container-lowest rounded-xl flex items-center justify-center text-2xl font-black text-primary">yourbrand.com</div>
      </div>
    </div>
  </section>
</main>
<script>
// Brand Preview JS
const form = document.getElementById('brand-preview-form');
const input = document.getElementById('brand-preview-input');
const results = document.getElementById('brand-preview-results');
const landing = document.getElementById('brand-preview-landing');
const mobile = document.getElementById('brand-preview-mobile');
const asset = document.getElementById('brand-preview-asset');
const btn = document.getElementById('brand-preview-btn');

function updateBrandPreview(domain) {
  landing.textContent = domain;
  mobile.textContent = domain;
  asset.textContent = domain;
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
