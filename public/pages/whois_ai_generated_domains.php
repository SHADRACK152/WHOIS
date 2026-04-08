<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');

$idea = trim((string) ($_GET['idea'] ?? ''));
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | AI Generated Domain Ideas</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary": "#3a3c3c",
                        "on-surface": "#1a1c1c",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f3f3",
                        "on-secondary-fixed": "#1b1c1c",
                        "on-primary-fixed": "#ffffff",
                        "on-background": "#1a1c1c",
                        "primary-container": "#3b3b3b",
                        "inverse-primary": "#c6c6c6",
                        "inverse-on-surface": "#f1f1f1",
                        "tertiary-fixed-dim": "#454747",
                        "error": "#ba1a1a",
                        "surface-tint": "#5e5e5e",
                        "surface-container-highest": "#e2e2e2",
                        "on-primary-container": "#ffffff",
                        "surface-container-high": "#e8e8e8",
                        "primary-fixed-dim": "#474747",
                        "tertiary-fixed": "#5d5f5f",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-tertiary": "#e2e2e2",
                        "on-secondary": "#ffffff",
                        "secondary-fixed-dim": "#acabab",
                        "secondary-container": "#d5d4d4",
                        "surface": "#f9f9f9",
                        "primary-fixed": "#5e5e5e",
                        "inverse-surface": "#2f3131",
                        "on-tertiary-fixed": "#ffffff",
                        "tertiary-container": "#737575",
                        "on-error-container": "#410002",
                        "error-container": "#ffdad6",
                        "surface-variant": "#e2e2e2",
                        "on-error": "#ffffff",
                        "background": "#f9f9f9",
                        "surface-dim": "#dadada",
                        "outline-variant": "#c6c6c6",
                        "on-secondary-container": "#1b1c1c",
                        "on-surface-variant": "#474747",
                        "surface-container": "#eeeeee",
                        "primary": "#000000",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-tertiary-container": "#ffffff",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "secondary": "#5e5e5e",
                        "outline": "#777777",
                        "on-primary": "#e2e2e2",
                        "surface-bright": "#f9f9f9",
                        "secondary-fixed": "#c7c6c6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "1rem",
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
  .hero-gradient {
    background:
      radial-gradient(circle at top left, rgba(0, 0, 0, 0.04), transparent 30%),
      radial-gradient(circle at bottom right, rgba(0, 0, 0, 0.03), transparent 28%),
      linear-gradient(180deg, #ffffff 0%, #f9f9f9 100%);
  }
</style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-24">
<section class="hero-gradient px-6 pb-12 pt-10">
  <div class="mx-auto max-w-7xl">
    <div class="max-w-3xl">
      <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">AI Name Generator</p>
      <h1 class="mt-3 font-headline text-4xl font-black tracking-tight text-primary md:text-5xl">Generated Domain Name Ideas</h1>
      <p class="mt-3 text-on-surface-variant">Describe your business, get short names with pricing, then proceed using check availability or purchase here.</p>
    </div>

    <div class="mt-6 rounded-3xl border border-outline-variant/20 bg-white/90 p-4 shadow-sm md:p-5">
      <div class="flex flex-col gap-3 md:flex-row md:items-center">
        <input id="idea-input" class="w-full rounded-full border border-outline-variant/30 bg-white px-5 py-3 text-sm text-primary placeholder:text-neutral-400 focus:border-black focus:ring-0" placeholder="Describe your business idea" type="text" value="<?php echo htmlspecialchars($idea, ENT_QUOTES, 'UTF-8'); ?>"/>
        <button id="idea-generate" class="rounded-full bg-black px-6 py-3 text-xs font-bold uppercase tracking-[0.16em] text-white hover:bg-neutral-800" type="button">Generate names</button>
        <a class="rounded-full border border-outline-variant/30 px-6 py-3 text-xs font-bold uppercase tracking-[0.16em] text-primary hover:border-black" href="/pages/whois_premium_domain_intelligence_landing_page.php">Back to landing</a>
      </div>
      <p id="idea-status" class="mt-3 text-sm text-on-surface-variant"></p>
    </div>

    <div id="idea-results" class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3"></div>
  </div>
</section>
</main>
<?php require __DIR__ . '/_footer.php'; ?>
<script>
(function () {
  const input = document.getElementById('idea-input');
  const button = document.getElementById('idea-generate');
  const status = document.getElementById('idea-status');
  const results = document.getElementById('idea-results');

  if (!input || !button || !status || !results) {
    return;
  }

  function escapeHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#39;');
  }

  function renderItems(items) {
    results.innerHTML = '';

    if (!Array.isArray(items) || !items.length) {
      status.textContent = 'No names returned. Try a clearer business description.';
      return;
    }

    status.textContent = items.length + ' short name ideas generated';

    items.forEach((item) => {
      const purchasable = item && item.purchasable === true;
      const purchaseButton = purchasable
        ? '<a class="inline-flex items-center justify-center rounded-full bg-black px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.15em] text-white hover:bg-neutral-800" href="' + escapeHtml(String(item.purchaseUrl || '#')) + '" target="_blank" rel="noopener">Purchase here</a>'
        : '<span class="inline-flex items-center justify-center rounded-full border border-outline-variant/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.15em] text-neutral-500">Unavailable</span>';

      const card = document.createElement('article');
      card.className = 'rounded-2xl border border-outline-variant/20 bg-white p-5 shadow-sm';
      card.innerHTML =
        '<p class="text-lg font-black text-primary">' + escapeHtml(String(item.name || 'Name')) + '</p>' +
        '<p class="mt-1 break-all text-sm text-on-surface-variant">' + escapeHtml(String(item.domain || '')) + '</p>' +
        '<p class="mt-4 text-[10px] font-bold uppercase tracking-[0.16em] text-neutral-400">Price</p>' +
        '<p class="text-base font-bold text-primary">' + escapeHtml(String(item.price || 'Price unavailable')) + '</p>' +
        '<div class="mt-4 flex flex-wrap items-center gap-2">' +
          '<a class="inline-flex items-center justify-center rounded-full border border-outline-variant/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.15em] text-primary hover:border-black" href="/pages/whois_ai_domain_search.php?query=' + encodeURIComponent(String(item.domain || '')) + '">Check availability</a>' +
          purchaseButton +
        '</div>';
      results.appendChild(card);
    });
  }

  async function generate() {
    const description = input.value.trim();

    if (!description) {
      input.focus();
      return;
    }

    status.textContent = 'Generating ideas...';
    results.innerHTML = '';

    try {
      const response = await fetch('/api/name-generator.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          description,
          limit: 15,
        }),
      });

      const data = await response.json();

      if (!response.ok || !data.ok) {
        throw new Error(data.error || 'Unable to generate names right now.');
      }

      renderItems(Array.isArray(data.items) ? data.items : []);
    } catch (error) {
      status.textContent = error instanceof Error ? error.message : 'Unable to generate names right now.';
    }
  }

  button.addEventListener('click', generate);
  input.addEventListener('keydown', function (event) {
    if (event.key !== 'Enter') {
      return;
    }

    event.preventDefault();
    generate();
  });

  if (input.value.trim() !== '') {
    generate();
  }
}());
</script>
<script src="../assets/js/nav-state.js"></script>
</body></html>
