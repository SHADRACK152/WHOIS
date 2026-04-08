<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');

require __DIR__ . '/../../app/dns-checker-nodes.php';

$domainValue = trim((string) ($_GET['domain'] ?? 'cheapestdomains.co.ke'));

$nodes = whois_dns_checker_nodes();
$nodesJson = json_encode($nodes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

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
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

  .status-icon {
    align-items: center;
    display: inline-flex;
    font-size: 12px;
    font-weight: 900;
    justify-content: center;
    line-height: 1;
    width: 10px;
  }

  .status-pending {
    background: #f5f5f5;
    color: #616161;
  }

  .status-pending .status-icon {
    color: #9e9e9e;
  }

  .status-resolved {
    background: #eaf7ea;
    color: #1b5e20;
  }

  .status-resolved .status-icon {
    color: #2e7d32;
  }

  .status-failed {
    background: #fdeaea;
    color: #b71c1c;
  }

  .status-failed .status-icon {
    color: #d32f2f;
  }

  #dns-leaflet-map {
    width: 100%;
    min-width: 0;
    height: 620px;
    border-radius: 18px;
  }

  .dns-split-layout {
    display: grid;
    gap: 1rem;
    grid-template-columns: minmax(300px, 360px) minmax(0, 1fr);
    align-items: start;
  }

  .dns-node-list {
    max-height: 620px;
    overflow-y: auto;
    padding-right: 0.35rem;
  }

  .leaflet-dns-icon {
    background: transparent;
    border: 0;
  }

  .leaflet-dns-pin {
    width: 14px;
    height: 14px;
    border-radius: 9999px;
    border: 2px solid #fff;
    color: #ffffff;
    display: block;
    font-size: 11px;
    font-weight: 900;
    line-height: 10px;
    position: relative;
    text-align: center;
    box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.28), 0 0 0 6px rgba(158, 158, 158, 0.16);
  }

  .leaflet-dns-pin .leaflet-dns-symbol {
    display: block;
    transform: translateY(-1px);
  }

  .leaflet-dns-pin.is-pending {
    background: #9e9e9e;
  }

  .leaflet-dns-pin.is-resolved {
    background: #2e7d32;
    box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.28), 0 0 0 6px rgba(46, 125, 50, 0.2);
  }

  .leaflet-dns-pin.is-failed {
    background: #d32f2f;
    box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.28), 0 0 0 6px rgba(211, 47, 47, 0.2);
  }

  @media (max-width: 1024px) {
    .dns-split-layout {
      grid-template-columns: 1fr;
    }

    .dns-node-list {
      max-height: 340px;
    }
  }

  @media (max-width: 900px) {
    #dns-leaflet-map {
      height: 460px;
    }
  }

  .map-card-focus {
    outline: 2px solid rgba(46, 125, 50, 0.35);
    box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.14);
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
        <button id="dns-refresh-btn" class="rounded-full bg-neutral-700 px-6 py-2.5 text-xs font-bold uppercase tracking-[0.16em] text-white hover:bg-neutral-800 ml-2" type="button">Refresh</button>
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

    <section class="mt-8 rounded-3xl border border-outline-variant/20 bg-white p-6 shadow-sm">
      <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Check DNS Propagation</p>
      <h2 class="mt-2 font-headline text-3xl font-black tracking-tight text-primary">Global DNS Propagation Overview</h2>
      <p class="mt-3 max-w-4xl text-sm leading-7 text-on-surface-variant">
        Whether you have recently changed your DNS records, switched web host, or started a new website, checking whether DNS records are propagated globally is essential. This DNS checker view helps verify DNS records against multiple DNS servers in worldwide regions to confirm full propagation.
      </p>
    </section>

    <section class="mt-6 rounded-3xl border border-outline-variant/20 bg-white p-4 shadow-sm md:p-6">
      <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h3 class="font-headline text-xl font-bold text-primary">DNS Propagation Map</h3>
        <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-500">
          <span class="status-pill status-resolved"><span class="status-icon">&#10003;</span>Resolved</span>
          <span class="status-pill status-failed"><span class="status-icon">&times;</span>Not Resolved</span>
          <span class="status-pill status-pending"><span class="status-icon">&times;</span>No Response</span>
        </div>
      </div>

      <div class="dns-split-layout">
        <aside class="rounded-2xl border border-outline-variant/20 bg-surface-container-low p-3 md:p-4">
          <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-500">Resolvers</p>
          <div class="dns-node-list space-y-3">
            <?php foreach ($nodes as $node): ?>
              <article class="rounded-xl border border-outline-variant/20 bg-white p-3 shadow-sm" data-node-card="<?php echo htmlspecialchars($node['markerId'], ENT_QUOTES, 'UTF-8'); ?>" data-country="<?php echo htmlspecialchars($node['country'], ENT_QUOTES, 'UTF-8'); ?>" data-continent="<?php echo htmlspecialchars($node['continent'], ENT_QUOTES, 'UTF-8'); ?>" data-ip-family="<?php echo strpos((string) $node['resolver'], ':') === false ? 'ipv4' : 'ipv6'; ?>">
                <div class="flex items-start gap-3">
                  <span class="inline-flex h-6 min-w-[2.1rem] items-center justify-center rounded-full bg-surface-container-low px-2 text-[10px] font-black uppercase tracking-[0.18em] text-neutral-600"><?php echo htmlspecialchars($node['country'], ENT_QUOTES, 'UTF-8'); ?></span>
                  <div>
                    <p class="text-sm font-bold text-primary"><?php echo htmlspecialchars($node['location'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mt-1 text-xs text-on-surface-variant"><?php echo htmlspecialchars($node['provider'], ENT_QUOTES, 'UTF-8'); ?> • <?php echo htmlspecialchars($node['resolver'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="mt-2">
                      <span class="status-pill status-pending" data-node-status="<?php echo htmlspecialchars($node['markerId'], ENT_QUOTES, 'UTF-8'); ?>">
                        <span class="status-icon">&times;</span>
                        No Response
                      </span>
                    </div>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </aside>

        <div class="rounded-2xl border border-outline-variant/20 bg-surface-container-lowest p-3">
          <div id="dns-leaflet-map"></div>
        </div>
      </div>

      <p class="mt-4 text-xs text-on-surface-variant">Note: Complete DNS resolution may take up to 48 hours.</p>
    </section>

    <section class="mt-6 rounded-3xl border border-outline-variant/20 bg-white p-5 shadow-sm">
      <div class="flex flex-wrap items-center justify-between gap-2">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-400">Quick Filters</p>
        <p class="text-xs text-on-surface-variant">Use the top dropdowns for full filtering; these are shortcuts.</p>
      </div>

      <div class="mt-3 grid gap-4 lg:grid-cols-2">
        <div>
          <h4 class="text-[11px] font-black uppercase tracking-[0.14em] text-neutral-600">IP Shortcuts</h4>
          <div class="mt-2 flex flex-wrap gap-2">
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="ip" data-value="ipv4">Public IPv4</button>
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="ip" data-value="ipv6">Public IPv6</button>
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="ip" data-value="all">All</button>
          </div>
        </div>

        <div>
          <h4 class="text-[11px] font-black uppercase tracking-[0.14em] text-neutral-600">Continent Shortcuts</h4>
          <div class="mt-2 flex flex-wrap gap-2">
            <?php foreach ($continentLabels as $continentCode => $continentName): ?>
              <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="continent" data-value="<?php echo htmlspecialchars($continentCode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($continentName, ENT_QUOTES, 'UTF-8'); ?></button>
            <?php endforeach; ?>
            <button type="button" class="rounded-full border border-outline-variant/30 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="continent" data-value="all">All</button>
          </div>
        </div>
      </div>

      <details class="mt-4 rounded-2xl border border-outline-variant/20 bg-surface-container-low p-3">
        <summary class="cursor-pointer text-[11px] font-black uppercase tracking-[0.14em] text-neutral-600">Country Shortcuts</summary>
        <div class="mt-3 flex max-h-40 flex-wrap gap-2 overflow-y-auto pr-2">
          <?php foreach ($countries as $countryCode => $countryName): ?>
            <button type="button" class="rounded-full border border-outline-variant/30 bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="country" data-value="<?php echo htmlspecialchars($countryCode, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($countryCode . ' ' . $countryName, ENT_QUOTES, 'UTF-8'); ?></button>
          <?php endforeach; ?>
          <button type="button" class="rounded-full border border-outline-variant/30 bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]" data-quick-filter="country" data-value="all">All</button>
        </div>
      </details>
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
  const nodeMeta = <?php echo is_string($nodesJson) ? $nodesJson : '[]'; ?>;
  const input = document.getElementById('dns-domain');
  const button = document.getElementById('dns-check');
  const typeSelect = document.getElementById('dns-type');
  const refreshSelect = document.getElementById('dns-refresh');
  const ipFamilySelect = document.getElementById('dns-ip-family');
  const continentSelect = document.getElementById('dns-continent');
  const countrySelect = document.getElementById('dns-country');
  const quickFilters = document.querySelectorAll('[data-quick-filter]');
  const summary = document.getElementById('dns-summary');
  const mapEl = document.getElementById('dns-leaflet-map');

  let refreshTimer = null;
  let map = null;
  let activeRunToken = 0;

  const markerColors = {
    pending: '#9e9e9e',
    resolved: '#2e7d32',
    failed: '#d32f2f'
  };

  const statusByMarker = new Map();
  const markerElements = new Map();

  function markerSymbol(status) {
    if (status === 'resolved') {
      return '&#10003;';
    }

    return '&times;';
  }

  function badgeSymbol(state) {
    if (state === 'status-resolved') {
      return '&#10003;';
    }

    return '&times;';
  }

  function markerIcon(status) {
    return L.divIcon({
      className: 'leaflet-dns-icon',
      html: '<span class="leaflet-dns-pin is-' + status + '"><span class="leaflet-dns-symbol">' + markerSymbol(status) + '</span></span>',
      iconSize: [14, 14],
      iconAnchor: [7, 7],
    });
  }

  function buildLeafletMap() {
    if (!mapEl || typeof window.L === 'undefined' || !Array.isArray(nodeMeta)) {
      return;
    }

    map = L.map(mapEl, {
      worldCopyJump: true,
      minZoom: 2,
      maxZoom: 8,
      zoomControl: true,
    }).setView([20, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 8,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    markerElements.clear();

    nodeMeta.forEach(function (node) {
      const markerId = String((node && node.markerId) || '');
      const lat = Number(node && node.lat);
      const lon = Number(node && node.lon);

      if (!markerId || Number.isNaN(lat) || Number.isNaN(lon)) {
        return;
      }

      const marker = L.marker([lat, lon], {
        icon: markerIcon('pending'),
        title: String((node && node.location) || markerId),
      }).addTo(map);

      marker.on('click', function () {
        focusServerCard(markerId);
      });

      markerElements.set(markerId, marker);
    });
  }

  function setMarkerTooltip(markerId, text) {
    if (!markerId) {
      return;
    }

    const marker = markerElements.get(markerId);

    if (!marker) {
      return;
    }

    marker.bindTooltip(text, {
      sticky: true,
      direction: 'top',
      offset: [0, -10],
      opacity: 0.95,
    });
  }

  function focusServerCard(markerId) {
    const card = document.querySelector('[data-node-card="' + markerId + '"]');

    if (!card) {
      return;
    }

    document.querySelectorAll('.map-card-focus').forEach(function (node) {
      node.classList.remove('map-card-focus');
    });

    card.classList.add('map-card-focus');
    card.scrollIntoView({ behavior: 'smooth', block: 'center' });

    setTimeout(function () {
      card.classList.remove('map-card-focus');
    }, 1800);
  }

  function bindMarkerInteractions() {
    if (markerElements.size === 0) {
      return;
    }

    markerElements.forEach(function (marker, markerId) {
      marker.off('click');
      marker.on('click', function () {
        focusServerCard(markerId);
      });
    });
  }

  function initializeMarkerTooltips() {
    if (!Array.isArray(nodeMeta)) {
      return;
    }

    nodeMeta.forEach(function (node) {
      const markerId = String((node && node.markerId) || '');
      const location = String((node && node.location) || 'Unknown location');
      const provider = String((node && node.provider) || 'Unknown resolver');
      const resolver = String((node && node.resolver) || 'n/a');
      const base = location + ' | ' + provider + ' | ' + resolver + ' | Pending';
      setMarkerTooltip(markerId, base);
    });
  }

  function setSummary(text) {
    if (summary) {
      summary.textContent = text;
    }
  }

  function setMarkerStatus(markerId, status) {
    if (!markerId) {
      return;
    }

    const marker = markerElements.get(markerId);

    if (!marker) {
      return;
    }

    const normalized = status === 'resolved' || status === 'failed' ? status : 'pending';
    marker.setIcon(markerIcon(normalized));
  }

  function setCardStatus(markerId, state, label) {
    const badge = document.querySelector('[data-node-status="' + markerId + '"]');

    if (!badge) {
      return;
    }

    badge.classList.remove('status-pending', 'status-resolved', 'status-failed');
    badge.classList.add(state);
    badge.innerHTML = '<span class="status-icon">' + badgeSymbol(state) + '</span>' + label;
  }

  function resetStatuses() {
    document.querySelectorAll('[data-node-status]').forEach(function (el) {
      el.classList.remove('status-resolved', 'status-failed');
      el.classList.add('status-pending');
      el.innerHTML = '<span class="status-icon">&times;</span>No Response';
    });

    markerElements.forEach(function (_marker, markerId) {
      setMarkerStatus(markerId, 'pending');
    });
  }

  function applyVisibility() {
    const ipFamily = String((ipFamilySelect && ipFamilySelect.value) || 'all').toLowerCase();
    const continent = String((continentSelect && continentSelect.value) || 'all').toLowerCase();
    const country = String((countrySelect && countrySelect.value) || 'all').toLowerCase();

    const visibleMarkerIds = [];

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

      if (visible && markerId !== '') {
        visibleMarkerIds.push(markerId);
      }

      if (markerId !== '') {
        const marker = markerElements.get(markerId);

        if (marker) {
          marker.setOpacity(visible ? 1 : 0.15);
        }
      }
    });

    return visibleMarkerIds;
  }

  function applyResults(rows) {
    const safeRows = Array.isArray(rows) ? rows : [];
    let resolved = 0;

    safeRows.forEach(function (row) {
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

      const location = String(row.location || 'Unknown location');
      const provider = String(row.provider || 'Unknown resolver');
      const resolver = String(row.resolver || 'n/a');
      const details = label + (row.answerCount ? ' (' + row.answerCount + ')' : '');
      setMarkerTooltip(markerId, location + ' | ' + provider + ' | ' + resolver + ' | ' + details);
    });

    return {
      total: safeRows.length,
      resolved: resolved,
    };
  }

  function chunkMarkerIds(markerIds, size) {
    const chunks = [];

    for (let i = 0; i < markerIds.length; i += size) {
      chunks.push(markerIds.slice(i, i + size));
    }

    return chunks;
  }

  async function fetchBatch(params, markerIds, runToken) {
    const batchParams = new URLSearchParams(params.toString());
    batchParams.set('markerIds', markerIds.join(','));

    const response = await fetch('/api/dns-checker.php?' + batchParams.toString(), {
      headers: {
        'Accept': 'application/json'
      }
    });

    if (runToken !== activeRunToken) {
      return null;
    }

    const payload = await response.json();

    if (runToken !== activeRunToken) {
      return null;
    }

    if (!response.ok || !payload.ok) {
      throw new Error((payload && payload.error) ? payload.error : 'DNS check failed.');
    }

    return payload;
  }

  let isChecking = false;
  async function runCheck() {
    if (isChecking) {
      setSummary('Please wait for all DNS checks to finish.');
      return;
    }
    isChecking = true;
    const runToken = ++activeRunToken;
    const query = String(input.value || '').trim();

    if (query === '') {
      input.focus();
      isChecking = false;
      return;
    }

    const type = String((typeSelect && typeSelect.value) || 'A').trim().toUpperCase();
    const ipFamily = String((ipFamilySelect && ipFamilySelect.value) || 'all').toLowerCase();
    const continent = String((continentSelect && continentSelect.value) || 'all').toLowerCase();
    const country = String((countrySelect && countrySelect.value) || 'all').toLowerCase();
    const markerIds = applyVisibility();

    if (markerIds.length === 0) {
      setSummary('No resolvers match the selected filters.');
      isChecking = false;
      return;
    }

    const params = new URLSearchParams({
      domain: query,
      type: type,
      ipFamily: ipFamily,
      continent: continent,
      country: country
    });

    resetStatuses();
    setSummary('Checking global resolvers... 0/' + markerIds.length + ' checked.');
    button.disabled = true;
    button.textContent = 'Checking...';

    try {
      // Increased batch size and concurrency for speed
      const batchSize = 32;
      const maxConcurrent = 16;
      const batches = chunkMarkerIds(markerIds, batchSize);
      let checked = 0;
      let resolved = 0;
      let cursor = 0;

      async function worker() {
        while (cursor < batches.length) {
          const current = cursor;
          cursor += 1;
          const batchMarkerIds = batches[current];
          const payload = await fetchBatch(params, batchMarkerIds, runToken);

          if (payload === null || runToken !== activeRunToken) {
            return;
          }

          const stats = applyResults(payload.results);
          checked += stats.total;
          resolved += stats.resolved;

          const percent = markerIds.length > 0 ? Math.round((resolved / markerIds.length) * 100) : 0;
          setSummary('Checking global resolvers... ' + checked + '/' + markerIds.length + ' checked (' + resolved + ' resolved, ' + percent + '%).');
        }
      }

      const workers = [];
      const workerCount = Math.min(maxConcurrent, batches.length);

      for (let i = 0; i < workerCount; i++) {
        workers.push(worker());
      }

      await Promise.all(workers);

      if (runToken !== activeRunToken) {
        isChecking = false;
        return;
      }

      const finalPercent = markerIds.length > 0 ? Math.round((resolved / markerIds.length) * 100) : 0;
      setSummary('Propagation: ' + resolved + '/' + markerIds.length + ' servers resolved (' + finalPercent + '%).');
      const nextUrl = '/pages/whois_dns_checker.php?domain=' + encodeURIComponent(query);
      window.history.replaceState({}, '', nextUrl);
    } catch (error) {
      if (runToken === activeRunToken) {
        setSummary((error && error.message) ? error.message : 'Request failed. Please try again.');
      }
    } finally {
      if (runToken === activeRunToken) {
        button.disabled = false;
        button.textContent = 'DNS Check';
      }
      isChecking = false;
    }
  }



  if (!input || !button) {
    return;
  }

  buildLeafletMap();

  button.addEventListener('click', function () {
    runCheck();
  });

  input.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
      event.preventDefault();
      runCheck();
    }
  });


  const refreshBtn = document.getElementById('dns-refresh-btn');
  if (refreshBtn) {
    refreshBtn.addEventListener('click', function () {
      runCheck();
    });
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
  initializeMarkerTooltips();
  bindMarkerInteractions();
  applyVisibility();
  runCheck();
}());
</script>
<script src="../assets/js/nav-state.js"></script>
</body>
</html>
