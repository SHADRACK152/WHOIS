<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');

require __DIR__ . '/../../app/dns-checker-nodes.php';

$domainValue = trim((string) ($_GET['domain'] ?? 'cheapestdomains.co.ke'));

$mapSvg = '';

$mapSvgCandidates = [
  dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'DNSChecker_Map.svg',
  dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'DNSChecker_Map.svg',
];

foreach ($mapSvgCandidates as $mapSvgPath) {
  if (!is_file($mapSvgPath)) {
    continue;
  }

  $raw = file_get_contents($mapSvgPath);

  if (is_string($raw) && trim($raw) !== '') {
    $mapSvg = $raw;
    break;
  }
}

$nodes = whois_dns_checker_nodes();

$continentLabels = [
  'africa' => 'Africa',
  'asia' => 'Asia',
  'europe' => 'Europe',
  'north-america' => 'North America',
  'australia' => 'Australia',
  'south-america' => 'South America',
];

$countries = [];

foreach ($nodes as $node) {
  $countryCode = strtolower((string) ($node['country'] ?? ''));
  $countryName = (string) ($node['countryName'] ?? strtoupper($countryCode));

  if ($countryCode !== '' && !isset($countries[$countryCode])) {
    $countries[$countryCode] = $countryName;
  }
}

asort($countries);
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS | DNS Checker</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        primary: "#000000",
        surface: "#f9f9f9",
        "surface-container-low": "#f3f3f3",
        "surface-container-lowest": "#ffffff",
        "on-surface": "#1a1c1c",
        "on-surface-variant": "#474747",
        "outline-variant": "#c6c6c6"
      },
      fontFamily: {
        headline: ["Manrope"],
        body: ["Inter"]
      }
    }
  }
}
</script>
<style>
  .hero-bg {
    background:
      radial-gradient(circle at top left, rgba(0,0,0,0.04), transparent 34%),
      radial-gradient(circle at bottom right, rgba(0,0,0,0.03), transparent 30%),
      linear-gradient(180deg, #ffffff 0%, #f7f7f7 100%);
  }

  .status-pill {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    padding: 0.22rem 0.55rem;
    text-transform: uppercase;
  }

  .status-dot {
    border-radius: 9999px;
    display: inline-block;
    height: 8px;
    width: 8px;
  }

  .status-pending {
    background: #f5f5f5;
    color: #616161;
  }

  .status-pending .status-dot {
    background: #9e9e9e;
  }

  .status-resolved {
    background: #eaf7ea;
    color: #1b5e20;
  }

  .status-resolved .status-dot {
    background: #2e7d32;
  }

  .status-failed {
    background: #fdeaea;
    color: #b71c1c;
  }

  .status-failed .status-dot {
    background: #d32f2f;
  }
</style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
<?php require __DIR__ . '/_top_nav.php'; ?>
<main class="pt-24">
<section class="hero-bg px-6 py-10">
  <div class="mx-auto max-w-7xl">
    <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">
      <a class="hover:text-black" href="whois_premium_domain_intelligence_landing_page.php">Home</a>
      <span>/</span>
      <a class="hover:text-black" href="whois_domain_tools_overview.php">All Tools</a>
      <span>/</span>
      <span>DNS Lookup</span>
      <span>/</span>
      <span>Public DNS List</span>
    </div>

    <div class="mt-5 rounded-3xl border border-outline-variant/20 bg-white p-4 shadow-sm md:p-6">
      <div class="grid gap-3 md:grid-cols-[1fr_auto]">
        <div class="flex items-center gap-2 rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2">
          <span class="material-symbols-outlined text-neutral-500">dns</span>
          <input id="dns-domain" class="w-full border-none bg-transparent text-sm font-semibold text-primary placeholder:text-neutral-400 focus:ring-0" value="<?php echo htmlspecialchars($domainValue, ENT_QUOTES, 'UTF-8'); ?>" type="text"/>
        </div>
        <button id="dns-check" class="rounded-full bg-black px-6 py-2.5 text-xs font-bold uppercase tracking-[0.16em] text-white hover:bg-neutral-800" type="button">DNS Check</button>
      </div>

      <div class="mt-4 flex flex-wrap items-center gap-3 text-xs">
        <label class="inline-flex items-center gap-2 rounded-full border border-outline-variant/30 bg-surface-container-low px-3 py-1.5">
          <span class="font-bold uppercase tracking-[0.14em] text-neutral-500">Type</span>
          <select id="dns-type" class="border-none bg-transparent p-0 text-sm font-bold text-primary focus:ring-0">
            <option selected>A</option>
            <option>AAAA</option>
            <option>CNAME</option>
            <option>MX</option>
            <option>NS</option>
            <option>PTR</option>
            <option>SRV</option>
            <option>SOA</option>
            <option>TXT</option>
            <option>CAA</option>
            <option>DS</option>
            <option>DNSKEY</option>
          </select>
        </label>
        <label class="inline-flex items-center gap-2 rounded-full border border-outline-variant/30 bg-surface-container-low px-3 py-1.5">
          <input id="dns-cd-flag" type="checkbox" class="h-4 w-4 rounded border-outline-variant"/>
          <span class="font-bold uppercase tracking-[0.14em] text-neutral-500">CD Flag</span>
        </label>
        <label class="inline-flex items-center gap-2 rounded-full border border-outline-variant/30 bg-surface-container-low px-3 py-1.5">
          <span class="font-bold uppercase tracking-[0.14em] text-neutral-500">Refresh</span>
          <select id="dns-refresh" class="border-none bg-transparent p-0 text-sm font-bold text-primary focus:ring-0">
            <option selected>20 sec.</option>
            <option>30 sec.</option>
            <option>60 sec.</option>
            <option>Off</option>
          </select>
        </label>
        <label class="inline-flex items-center gap-2 rounded-full border border-outline-variant/30 bg-surface-container-low px-3 py-1.5">
          <span class="font-bold uppercase tracking-[0.14em] text-neutral-500">IP</span>
          <select id="dns-ip-family" class="border-none bg-transparent p-0 text-sm font-bold text-primary focus:ring-0">
            <option value="all" selected>All</option>
            <option value="ipv4">Public IPv4</option>
            <option value="ipv6">Public IPv6</option>
          </select>
        </label>
        <label class="inline-flex items-center gap-2 rounded-full border border-outline-variant/30 bg-surface-container-low px-3 py-1.5">
          <span class="font-bold uppercase tracking-[0.14em] text-neutral-500">Continent</span>
          <select id="dns-continent" class="border-none bg-transparent p-0 text-sm font-bold text-primary focus:ring-0">
            <option value="all" selected>All</option>
            <?php foreach ($continentLabels as $continentCode => $continentName): ?>
              <option value="<?php echo htmlspecialchars($continentCode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($continentName, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="inline-flex items-center gap-2 rounded-full border border-outline-variant/30 bg-surface-container-low px-3 py-1.5">
          <span class="font-bold uppercase tracking-[0.14em] text-neutral-500">Country</span>
          <select id="dns-country" class="border-none bg-transparent p-0 text-sm font-bold text-primary focus:ring-0">
            <option value="all" selected>All</option>
            <?php foreach ($countries as $countryCode => $countryName): ?>
              <option value="<?php echo htmlspecialchars($countryCode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($countryCode . ' ' . $countryName, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <span id="dns-summary" class="text-xs font-semibold text-neutral-500">Ready to check propagation.</span>
      </div>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
      <?php foreach ($nodes as $node): ?>
        <article class="rounded-2xl border border-outline-variant/20 bg-white p-4 shadow-sm" data-node-card="<?php echo htmlspecialchars($node['markerId'], ENT_QUOTES, 'UTF-8'); ?>" data-country="<?php echo htmlspecialchars($node['country'], ENT_QUOTES, 'UTF-8'); ?>" data-continent="<?php echo htmlspecialchars($node['continent'], ENT_QUOTES, 'UTF-8'); ?>" data-ip-family="<?php echo strpos((string) $node['resolver'], ':') === false ? 'ipv4' : 'ipv6'; ?>">
          <div class="flex items-start gap-3">
            <span class="inline-flex h-6 min-w-[2.1rem] items-center justify-center rounded-full bg-surface-container-low px-2 text-[10px] font-black uppercase tracking-[0.18em] text-neutral-600"><?php echo htmlspecialchars($node['country'], ENT_QUOTES, 'UTF-8'); ?></span>
            <div>
              <p class="text-sm font-bold text-primary"><?php echo htmlspecialchars($node['location'], ENT_QUOTES, 'UTF-8'); ?></p>
              <p class="mt-1 text-xs text-on-surface-variant"><?php echo htmlspecialchars($node['provider'], ENT_QUOTES, 'UTF-8'); ?> • <?php echo htmlspecialchars($node['resolver'], ENT_QUOTES, 'UTF-8'); ?></p>
              <div class="mt-2">
                <span class="status-pill status-pending" data-node-status="<?php echo htmlspecialchars($node['markerId'], ENT_QUOTES, 'UTF-8'); ?>">
                  <span class="status-dot"></span>
                  Pending
                </span>
              </div>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <p class="mt-4 text-xs text-on-surface-variant">Note: Complete DNS resolution may take up to 48 hours.</p>

    <section class="mt-8 rounded-3xl border border-outline-variant/20 bg-white p-6 shadow-sm">
      <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Check DNS Propagation</p>
      <h2 class="mt-2 font-headline text-3xl font-black tracking-tight text-primary">Global DNS Propagation Overview</h2>
      <p class="mt-3 max-w-4xl text-sm leading-7 text-on-surface-variant">
        Whether you have recently changed your DNS records, switched web host, or started a new website, checking whether DNS records are propagated globally is essential. This DNS checker view helps verify DNS records against multiple DNS servers in worldwide regions to confirm full propagation.
      </p>
    </section>

    <section class="mt-6 rounded-3xl border border-outline-variant/20 bg-white p-4 shadow-sm md:p-6">
      <div class="mb-3 flex items-center justify-between">
        <h3 class="font-headline text-xl font-bold text-primary">DNS Propagation Map</h3>
        <a href="https://www.mapchart.net/world.html" target="_blank" rel="noopener noreferrer" class="rounded-full border border-outline-variant/30 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-neutral-600 hover:text-black">Open World Map</a>
      </div>
      <div class="mb-4 flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-500">
        <span class="status-pill status-resolved"><span class="status-dot"></span>Resolved</span>
        <span class="status-pill status-failed"><span class="status-dot"></span>Not Resolved</span>
        <span class="status-pill status-pending"><span class="status-dot"></span>No Response</span>
      </div>
      <?php if ($mapSvg !== ''): ?>
        <div class="overflow-x-auto rounded-2xl border border-outline-variant/20 bg-surface-container-lowest p-3">
          <div class="min-w-[760px] [&>svg]:h-auto [&>svg]:w-full">
            <?php echo $mapSvg; ?>
          </div>
        </div>
      <?php else: ?>
        <div class="rounded-2xl border border-dashed border-outline-variant/40 bg-surface-container-low p-6 text-sm text-on-surface-variant">
          DNSChecker_Map.svg was not found in the repository root.
        </div>
      <?php endif; ?>
    </section>

    <section class="mt-6 rounded-3xl border border-outline-variant/20 bg-white p-6 shadow-sm">
      <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">DNS Lists</p>
      <div class="mt-3 grid gap-6 md:grid-cols-3">
        <div>
          <h4 class="text-sm font-black uppercase tracking-[0.14em] text-neutral-600">IPs</h4>
          <div class="mt-2 flex flex-wrap gap-2">
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="ip" data-value="ipv4">Public IPv4</button>
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="ip" data-value="ipv6">Public IPv6</button>
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="ip" data-value="all">All</button>
          </div>
        </div>
        <div>
          <h4 class="text-sm font-black uppercase tracking-[0.14em] text-neutral-600">Continents</h4>
          <div class="mt-2 flex flex-wrap gap-2">
            <?php foreach ($continentLabels as $continentCode => $continentName): ?>
              <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="continent" data-value="<?php echo htmlspecialchars($continentCode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($continentName, ENT_QUOTES, 'UTF-8'); ?></button>
            <?php endforeach; ?>
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="continent" data-value="all">All</button>
          </div>
        </div>
        <div>
          <h4 class="text-sm font-black uppercase tracking-[0.14em] text-neutral-600">Countries</h4>
          <div class="mt-2 flex max-h-48 flex-wrap gap-2 overflow-y-auto pr-2">
            <?php foreach ($countries as $countryCode => $countryName): ?>
              <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="country" data-value="<?php echo htmlspecialchars($countryCode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($countryCode . ' ' . $countryName, ENT_QUOTES, 'UTF-8'); ?></button>
            <?php endforeach; ?>
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="country" data-value="all">All</button>
          </div>
        </div>
      </div>
    </section>

    <section class="mt-6 rounded-3xl border border-outline-variant/20 bg-white p-6 shadow-sm">
      <h3 class="font-headline text-2xl font-black tracking-tight text-primary">DNS Propagation Checker - How to Check DNS Propagation Globally</h3>
      <ol class="mt-4 list-decimal space-y-3 pl-5 text-sm leading-7 text-on-surface-variant">
        <li>Enter the domain or hostname you want to check.</li>
        <li>Select the DNS record type (A, AAAA, CNAME, MX, NS, PTR, SRV, SOA, TXT, CAA, DS, DNSKEY).</li>
        <li>Optionally scope the check by IP list, continent, or country.</li>
        <li>Click DNS Check to run propagation across all selected resolvers.</li>
        <li>Green means resolved, red means not resolved, gray means no response.</li>
      </ol>
    </section>
  </div>
</section>
</main>
<?php require __DIR__ . '/_footer.php'; ?>
<script>
(function () {
  const input = document.getElementById('dns-domain');
  const button = document.getElementById('dns-check');
  const typeSelect = document.getElementById('dns-type');
  const refreshSelect = document.getElementById('dns-refresh');
  const ipFamilySelect = document.getElementById('dns-ip-family');
  const continentSelect = document.getElementById('dns-continent');
  const countrySelect = document.getElementById('dns-country');
  const quickFilters = document.querySelectorAll('[data-quick-filter]');
  const summary = document.getElementById('dns-summary');
  const svg = document.getElementById('mapSvg');

  let refreshTimer = null;

  const markerColors = {
    pending: '#9e9e9e',
    resolved: '#2e7d32',
    failed: '#d32f2f'
  };

  const statusByMarker = new Map();

  function setSummary(text) {
    if (summary) {
      summary.textContent = text;
    }
  }

  function setMarkerStatus(markerId, status) {
    if (!svg || !markerId) {
      return;
    }

    const marker = svg.querySelector('#' + markerId);

    if (!marker) {
      return;
    }

    const color = markerColors[status] || markerColors.pending;
    marker.setAttribute('fill', color);
    marker.style.fill = color;
  }

  function setCardStatus(markerId, state, label) {
    const badge = document.querySelector('[data-node-status="' + markerId + '"]');

    if (!badge) {
      return;
    }

    badge.classList.remove('status-pending', 'status-resolved', 'status-failed');
    badge.classList.add(state);
    badge.innerHTML = '<span class="status-dot"></span>' + label;
  }

  function resetStatuses() {
    document.querySelectorAll('[data-node-status]').forEach(function (el) {
      el.classList.remove('status-resolved', 'status-failed');
      el.classList.add('status-pending');
      el.innerHTML = '<span class="status-dot"></span>Pending';
    });

    if (svg) {
      svg.querySelectorAll('path.marker[id]').forEach(function (marker) {
        marker.setAttribute('fill', markerColors.pending);
        marker.style.fill = markerColors.pending;
      });
    }
  }

  function applyVisibility() {
    const ipFamily = String((ipFamilySelect && ipFamilySelect.value) || 'all').toLowerCase();
    const continent = String((continentSelect && continentSelect.value) || 'all').toLowerCase();
    const country = String((countrySelect && countrySelect.value) || 'all').toLowerCase();

    document.querySelectorAll('[data-node-card]').forEach(function (card) {
      const markerId = String(card.getAttribute('data-node-card') || '');
      const cardIp = String(card.getAttribute('data-ip-family') || '').toLowerCase();
      const cardContinent = String(card.getAttribute('data-continent') || '').toLowerCase();
      const cardCountry = String(card.getAttribute('data-country') || '').toLowerCase();

      const ipOk = ipFamily === 'all' || ipFamily === cardIp;
      const continentOk = continent === 'all' || continent === cardContinent;
      const countryOk = country === 'all' || country === cardCountry;
      const visible = ipOk && continentOk && countryOk;

      card.style.display = visible ? '' : 'none';

      if (svg && markerId !== '') {
        const marker = svg.querySelector('#' + markerId);

        if (marker) {
          marker.style.opacity = visible ? '1' : '0.15';
        }
      }
    });
  }

  function applyResults(payload) {
    const rows = Array.isArray(payload.results) ? payload.results : [];
    let resolved = 0;

    rows.forEach(function (row) {
      const markerId = String(row.markerId || '');
      const isResolved = Boolean(row.resolved);
      const isOk = Boolean(row.ok);

      let state = 'status-pending';
      let markerState = 'pending';
      let label = 'No Response';

      if (isResolved) {
        state = 'status-resolved';
        markerState = 'resolved';
        label = 'Resolved';
        resolved += 1;
      } else if (isOk) {
        state = 'status-failed';
        markerState = 'failed';
        label = 'Not Resolved';
      }

      statusByMarker.set(markerId, markerState);
      setMarkerStatus(markerId, markerState);
      setCardStatus(markerId, state, label);
    });

    const total = rows.length;
    const percentage = total > 0 ? Math.round((resolved / total) * 100) : 0;
    setSummary('Propagation: ' + resolved + '/' + total + ' servers resolved (' + percentage + '%).');
  }

  async function runCheck() {
    const query = String(input.value || '').trim();

    if (query === '') {
      input.focus();
      return;
    }

    const type = String((typeSelect && typeSelect.value) || 'A').trim().toUpperCase();
    const ipFamily = String((ipFamilySelect && ipFamilySelect.value) || 'all').toLowerCase();
    const continent = String((continentSelect && continentSelect.value) || 'all').toLowerCase();
    const country = String((countrySelect && countrySelect.value) || 'all').toLowerCase();
    const params = new URLSearchParams({
      domain: query,
      type: type,
      ipFamily: ipFamily,
      continent: continent,
      country: country
    });

    resetStatuses();
    setSummary('Checking global resolvers...');
    button.disabled = true;
    button.textContent = 'Checking...';

    try {
      const response = await fetch('/api/dns-checker.php?' + params.toString(), {
        headers: {
          'Accept': 'application/json'
        }
      });

      const payload = await response.json();

      if (!response.ok || !payload.ok) {
        setSummary((payload && payload.error) ? payload.error : 'DNS check failed.');
        return;
      }

      applyResults(payload);
      applyVisibility();
      const nextUrl = '/pages/whois_dns_checker.php?domain=' + encodeURIComponent(query);
      window.history.replaceState({}, '', nextUrl);
    } catch (error) {
      setSummary('Request failed. Please try again.');
    } finally {
      button.disabled = false;
      button.textContent = 'DNS Check';
    }
  }

  function refreshIntervalMs() {
    const raw = String((refreshSelect && refreshSelect.value) || '').toLowerCase();

    if (raw.indexOf('20') !== -1) {
      return 20000;
    }

    if (raw.indexOf('30') !== -1) {
      return 30000;
    }

    if (raw.indexOf('60') !== -1) {
      return 60000;
    }

    return 0;
  }

  function restartTimer() {
    if (refreshTimer) {
      clearInterval(refreshTimer);
      refreshTimer = null;
    }

    const interval = refreshIntervalMs();

    if (interval > 0) {
      refreshTimer = setInterval(function () {
        runCheck();
      }, interval);
    }
  }

  if (!input || !button) {
    return;
  }

  button.addEventListener('click', function () {
    runCheck();
  });

  input.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
      event.preventDefault();
      runCheck();
    }
  });

  if (refreshSelect) {
    refreshSelect.addEventListener('change', restartTimer);
  }

  if (ipFamilySelect) {
    ipFamilySelect.addEventListener('change', function () {
      applyVisibility();
      runCheck();
    });
  }

  if (continentSelect) {
    continentSelect.addEventListener('change', function () {
      applyVisibility();
      runCheck();
    });
  }

  if (countrySelect) {
    countrySelect.addEventListener('change', function () {
      applyVisibility();
      runCheck();
    });
  }

  quickFilters.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const kind = String(btn.getAttribute('data-quick-filter') || '');
      const value = String(btn.getAttribute('data-value') || 'all');

      if (kind === 'ip' && ipFamilySelect) {
        ipFamilySelect.value = value;
      }

      if (kind === 'continent' && continentSelect) {
        continentSelect.value = value;
      }

      if (kind === 'country' && countrySelect) {
        countrySelect.value = value;
      }

      applyVisibility();
      runCheck();
    });
  });

  resetStatuses();
  applyVisibility();
  restartTimer();
  runCheck();
}());
</script>
<script src="../assets/js/nav-state.js"></script>
</body>
</html>
