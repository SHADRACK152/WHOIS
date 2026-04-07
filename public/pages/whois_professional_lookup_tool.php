<?php
declare(strict_types=1);

require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../app/domain-lookup.php';

$initialInput = trim((string) ($_GET['domain'] ?? $_GET['query'] ?? $_GET['q'] ?? ''));
$initialDomain = $initialInput !== '' ? whois_domain_normalize($initialInput) : '';

$hasInitialLookup = $initialDomain !== '';
$initialLookup = $hasInitialLookup ? whois_domain_lookup_cached($initialDomain) : [
  'domain' => '',
  'status' => 'unknown',
  'statusLabel' => 'Awaiting search',
  'registrar' => null,
  'created' => null,
  'expiration' => null,
  'updated' => null,
  'nameservers' => [],
  'availabilityNote' => 'Search a domain to load live WHOIS data.',
  'rdapSource' => null,
];
$initialSummary = $hasInitialLookup ? whois_domain_lookup_summary($initialLookup) : 'Search a domain to load live WHOIS data.';
$initialStatus = (string) ($initialLookup['status'] ?? 'unknown');
$initialBadge = $hasInitialLookup ? whois_domain_lookup_badge($initialLookup) : 'Awaiting search';
$initialNameservers = $hasInitialLookup ? array_slice(is_array($initialLookup['nameservers'] ?? null) ? $initialLookup['nameservers'] : [], 0, 6) : [];

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>WHOIS Domain Lookup</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
tailwind.config = {
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        background: '#f8f8f6',
        surface: '#f8f8f6',
        'surface-container-lowest': '#ffffff',
        'surface-container-low': '#f2f2ef',
        'surface-container': '#e8e7e1',
        'surface-container-high': '#dfddd6',
        primary: '#111111',
        'on-primary': '#ffffff',
        secondary: '#5f5c55',
        outline: '#7a776f',
        'outline-variant': '#d0cfc7',
        'on-surface': '#171717',
        'on-surface-variant': '#5c5953',
        'error-container': '#fde4e1',
      },
      fontFamily: {
        headline: ['Manrope'],
        body: ['Inter'],
      },
      borderRadius: {
        full: '9999px',
      },
    },
  },
};
</script>
<style>
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  }
  body { font-family: 'Inter', sans-serif; }
  h1, h2, h3 { font-family: 'Manrope', sans-serif; }
</style>
</head>
<body class="bg-background text-on-surface">
<nav class="sticky top-0 z-50 border-b border-outline-variant/40 bg-white/90 backdrop-blur-xl">
  <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4 lg:px-8">
    <a class="flex items-baseline gap-3 text-black" href="whois_premium_domain_intelligence_landing_page.php">
      <span class="text-xl font-black tracking-tighter">WHOIS</span>
      <span class="hidden sm:inline text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-400">Intelligence Suite</span>
    </a>
    <div class="hidden xl:flex items-center gap-6 text-sm font-semibold tracking-tight">
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_intelligence_landing_page.php">Home</a>
      <a class="text-black transition-colors" href="whois_professional_lookup_tool.php">WHOIS Search</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_ai_domain_search.php">Search</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_premium_domain_marketplace.php">Marketplace</a>
      <a class="text-neutral-500 hover:text-black transition-colors" href="whois_domain_tools_overview.php">Tools</a>
    </div>
    <a class="rounded-full bg-primary px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-on-primary hover:bg-neutral-800 transition-colors" href="whois_professional_lookup_tool.php">WHOIS Search</a>
  </div>
</nav>

<main class="mx-auto max-w-7xl px-6 py-10 lg:px-8 lg:py-14">
  <section class="rounded-[2rem] border border-outline-variant/30 bg-white px-6 py-8 shadow-[0_24px_70px_rgba(0,0,0,0.05)] lg:px-10 lg:py-10">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
      <div class="max-w-3xl">
        <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-400">WHOIS Domain Lookup</p>
        <h1 class="mt-3 text-4xl font-black tracking-tight lg:text-6xl">WHOIS lookup</h1>
      </div>
      <div class="grid grid-cols-2 gap-3 text-sm lg:min-w-[18rem]">
        <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-4">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Status</p>
          <p id="whois-status-label" class="mt-2 text-lg font-black text-primary"><?php echo htmlspecialchars($initialBadge, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-4">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Registry</p>
          <p class="mt-2 text-lg font-black text-primary">RDAP</p>
        </div>
      </div>
    </div>

    <form id="whois-lookup-form" class="mt-8">
      <div class="flex flex-col gap-3 rounded-full border border-outline-variant/30 bg-surface-container-lowest p-2 shadow-sm lg:flex-row lg:items-center">
        <div class="flex flex-1 items-center min-w-0">
          <span class="material-symbols-outlined ml-4 text-neutral-400">search</span>
          <input id="whois-lookup-input" name="domain" class="w-full border-0 bg-transparent px-4 py-3 text-lg font-medium text-primary placeholder:text-neutral-400 focus:ring-0" type="text" value="<?php echo htmlspecialchars($initialDomain, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search domain ownership"/>
        </div>
        <button class="rounded-full bg-primary px-8 py-3 font-bold text-white transition-colors hover:bg-neutral-800" type="submit">Search</button>
      </div>
    </form>

    <div class="mt-4 flex flex-wrap gap-3 text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">
      <span class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2">Live</span>
      <span class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2">RDAP</span>
      <span class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-4 py-2">Inline</span>
    </div>
  </section>

  <section class="mt-10 grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
    <article class="rounded-[2rem] border border-outline-variant/30 bg-white p-6 shadow-[0_18px_50px_rgba(0,0,0,0.04)] lg:p-8">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Result</p>
          <h2 id="whois-domain-heading" class="mt-3 break-all text-3xl font-black tracking-tight lg:text-4xl"><?php echo $hasInitialLookup ? htmlspecialchars($initialDomain, ENT_QUOTES, 'UTF-8') : 'No domain searched yet'; ?></h2>
          <p id="whois-summary" class="mt-3 max-w-3xl text-base leading-7 text-on-surface-variant"><?php echo htmlspecialchars($initialSummary, ENT_QUOTES, 'UTF-8'); ?></p>
          <p id="whois-updated-relative" class="mt-2 text-sm font-semibold text-on-surface-variant"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_relative_time_label((string) ($initialLookup['updated'] ?? null)), ENT_QUOTES, 'UTF-8') : ''; ?></p>
        </div>
        <span id="whois-domain-badge" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] <?php echo !$hasInitialLookup ? 'bg-neutral-200 text-neutral-700' : ($initialStatus === 'available' ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-900 text-white'); ?>"><?php echo htmlspecialchars($initialBadge, ENT_QUOTES, 'UTF-8'); ?></span>
      </div>

      <div class="mt-8 grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Details</p>
          <div class="mt-4 grid gap-4 text-sm">
            <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Domain Name</span><span id="whois-domain-name" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars(strtoupper($initialDomain), ENT_QUOTES, 'UTF-8') : '---'; ?></span></div>
            <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Registrar</span><span id="whois-registrar" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['registrar'] ?? 'Unavailable'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
            <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Creation Date</span><span id="whois-created" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['created'] ?? 'Not listed'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
            <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Expiration Date</span><span id="whois-expiration" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['expiration'] ?? 'Not listed'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
            <div class="flex items-center justify-between gap-4"><span class="text-on-surface-variant">Updated Date</span><span id="whois-updated" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['updated'] ?? 'Not listed'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
          </div>
        </div>

        <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Registry</p>
          <div class="mt-8 grid gap-4 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Domain Information</p>
              <div class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Domain:</span><span id="whois-domain-name" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars($initialDomain, ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Registered On:</span><span id="whois-created" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_date_only((string) ($initialLookup['created'] ?? null)), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Expires On:</span><span id="whois-expiration" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_date_only((string) ($initialLookup['expiration'] ?? null)), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Updated On:</span><span id="whois-updated" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_date_only((string) ($initialLookup['updated'] ?? null)), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4"><span class="text-on-surface-variant">Status:</span><span id="whois-status-text" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['statusLabel'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
              </div>
            </section>
            <section class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Registrar Information</p>
              <div class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Registrar:</span><span id="whois-registrar-name" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['registrar'] ?? 'Not listed'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">IANA ID:</span><span id="whois-registrar-iana" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['registrarIanaId'] ?? 'Not listed'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">URL:</span><span id="whois-registrar-url" class="font-bold text-primary"><?php echo 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Email:</span><span id="whois-registrar-email" class="font-bold text-primary"><?php echo 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3"><span class="text-on-surface-variant">Abuse Email:</span><span id="whois-registrar-abuse-email" class="font-bold text-primary"><?php echo 'Search required'; ?></span></div>
                <div class="flex items-center justify-between gap-4"><span class="text-on-surface-variant">Abuse Phone:</span><span id="whois-registrar-abuse-phone" class="font-bold text-primary"><?php echo 'Search required'; ?></span></div>
              </div>
            </section>
          </div>

          <div class="mt-8 grid gap-4 xl:grid-cols-3">
            <section class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Registrant Contact</p>
              <div id="whois-registrant-contact" class="mt-4 space-y-2 text-sm text-on-surface-variant">
                <div class="rounded-2xl border border-outline-variant/20 bg-white p-4">Search required</div>
              </div>
            </section>
            <section class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Administrative Contact</p>
              <div id="whois-administrative-contact" class="mt-4 space-y-2 text-sm text-on-surface-variant">
                <div class="rounded-2xl border border-outline-variant/20 bg-white p-4">Search required</div>
              </div>
            </section>
            <section class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Technical Contact</p>
              <div id="whois-technical-contact" class="mt-4 space-y-2 text-sm text-on-surface-variant">
                <div class="rounded-2xl border border-outline-variant/20 bg-white p-4">Search required</div>
              </div>
            </section>
          </div>

          <details class="mt-8 rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-[0_18px_50px_rgba(0,0,0,0.04)]">
            <summary class="cursor-pointer list-none text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">More Record Data</summary>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
              <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Notices</p>
                <div id="whois-notices" class="mt-4 space-y-3">
                  <div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">Search to load notices.</div>
                </div>
              </div>
              <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Remarks</p>
                <div id="whois-remarks" class="mt-4 space-y-3">
                  <div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">Search to load remarks.</div>
                </div>
              </div>
            </div>
            <div class="mt-6">
              <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Events</p>
              <div id="whois-events" class="mt-4 space-y-3">
                <div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">Search to load events.</div>
              </div>
            </div>
            <details class="mt-6 rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
              <summary class="cursor-pointer list-none text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Raw RDAP</summary>
              <pre id="whois-raw-rdap" class="mt-4 max-h-[28rem] overflow-auto rounded-2xl bg-neutral-950 p-5 text-xs leading-6 text-neutral-100">Search to load raw data.</pre>
            </details>
          </details>
        <div id="whois-events" class="mt-4 space-y-3">
          <div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">Search to load events.</div>
        </div>
      </div>

      <div class="mt-8 rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
        <div class="grid gap-4 md:grid-cols-2">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Notices</p>
            <div id="whois-notices" class="mt-4 space-y-3">
              <div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">Search to load notices.</div>
            </div>
          </div>
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Remarks</p>
            <div id="whois-remarks" class="mt-4 space-y-3">
              <div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">Search to load remarks.</div>
            </div>
          </div>
        </div>
      </div>

      <details class="mt-8 rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-[0_18px_50px_rgba(0,0,0,0.04)]">
        <summary class="cursor-pointer list-none text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Raw RDAP</summary>
        <pre id="whois-raw-rdap" class="mt-4 max-h-[28rem] overflow-auto rounded-2xl bg-neutral-950 p-5 text-xs leading-6 text-neutral-100">Search to load raw data.</pre>
      </details>
    </article>

    <aside class="space-y-6">
      <div class="rounded-[2rem] border border-outline-variant/30 bg-white p-6 shadow-[0_18px_50px_rgba(0,0,0,0.04)]">
        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Alternatives</p>
        <div id="whois-alternatives" class="mt-5 space-y-4">
          <div class="rounded-2xl border border-outline-variant/20 bg-surface-container-low p-4 text-sm text-on-surface-variant">Search to load alternatives.</div>
        </div>
      </div>

      <div class="rounded-[2rem] border border-outline-variant/30 bg-black p-6 text-white shadow-[0_18px_50px_rgba(0,0,0,0.08)]">
        <div class="flex items-center gap-2">
          <h3 class="text-lg font-bold">Fit Check</h3>
          <span class="material-symbols-outlined text-sm opacity-60">info</span>
        </div>
        <p class="mt-3 text-sm text-white/70">Compare it against your brand name.</p>
        <div class="mt-5 space-y-3">
          <input id="brand-fit-input" class="w-full rounded-xl border border-zinc-800 bg-zinc-900 px-4 py-3 text-sm text-white placeholder:text-zinc-500 focus:ring-0" type="text" placeholder="Your business name"/>
          <button id="brand-fit-button" class="w-full rounded-xl bg-white px-4 py-3 text-sm font-bold text-black transition-colors hover:bg-neutral-200" type="button">Check Fit</button>
          <p id="brand-fit-result" class="text-sm text-white/70">Enter a business name to compare it with the current domain.</p>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
          <div class="flex items-center justify-between">
            <span class="material-symbols-outlined text-neutral-400 text-lg">public</span>
            <span class="material-symbols-outlined text-neutral-400 text-sm">info</span>
          </div>
          <p id="whois-supported-tlds" class="mt-4 text-2xl font-black text-primary">-</p>
          <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Extensions Reg.</p>
        </div>
        <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
          <div class="flex items-center justify-between">
            <span class="material-symbols-outlined text-neutral-400 text-lg">history</span>
            <span class="material-symbols-outlined text-neutral-400 text-sm">info</span>
          </div>
          <p id="whois-alternative-count" class="mt-4 text-2xl font-black text-primary">-</p>
          <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Alternatives</p>
        </div>
      </div>
    </aside>
  </section>
</main>

<script>
(function () {
  const form = document.getElementById('whois-lookup-form');
  const input = document.getElementById('whois-lookup-input');
  const domainHeading = document.getElementById('whois-domain-heading');
  const domainBadge = document.getElementById('whois-domain-badge');
  const statusLabel = document.getElementById('whois-status-label');
  const summary = document.getElementById('whois-summary');
  const updatedRelative = document.getElementById('whois-updated-relative');
  const domainName = document.getElementById('whois-domain-name');
  const created = document.getElementById('whois-created');
  const expiration = document.getElementById('whois-expiration');
  const updated = document.getElementById('whois-updated');
  const statusText = document.getElementById('whois-status-text');
  const nameservers = document.getElementById('whois-nameservers');
  const registrarName = document.getElementById('whois-registrar-name');
  const registrarIana = document.getElementById('whois-registrar-iana');
  const registrarUrl = document.getElementById('whois-registrar-url');
  const registrarEmail = document.getElementById('whois-registrar-email');
  const registrarAbuseEmail = document.getElementById('whois-registrar-abuse-email');
  const registrarAbusePhone = document.getElementById('whois-registrar-abuse-phone');
  const registrantContact = document.getElementById('whois-registrant-contact');
  const administrativeContact = document.getElementById('whois-administrative-contact');
  const technicalContact = document.getElementById('whois-technical-contact');
  const events = document.getElementById('whois-events');
  const notices = document.getElementById('whois-notices');
  const remarks = document.getElementById('whois-remarks');
  const rawRdap = document.getElementById('whois-raw-rdap');
  const alternatives = document.getElementById('whois-alternatives');
  const alternativeCount = document.getElementById('whois-alternative-count');
  const brandFitInput = document.getElementById('brand-fit-input');
  const brandFitButton = document.getElementById('brand-fit-button');
  const brandFitResult = document.getElementById('brand-fit-result');

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function setBadge(status, label) {
    if (!domainBadge) return;

    const isAvailable = status === 'available';
    domainBadge.className = 'rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] ' + (isAvailable ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-900 text-white');
    domainBadge.textContent = label;
  }

  function renderNameservers(list) {
    if (!nameservers) return;

    if (!Array.isArray(list) || list.length === 0) {
      nameservers.innerHTML = '<span class="text-sm text-on-surface-variant">No nameservers returned by the registry.</span>';
      return;
    }

    nameservers.innerHTML = list.map((item) => '<span class="rounded-full border border-outline-variant/30 bg-white px-4 py-2 text-sm font-semibold text-primary">' + escapeHtml(item) + '</span>').join('');
  }

  function renderDefinitionRows(container, rows, emptyMessage) {
    if (!container) return;

    if (!Array.isArray(rows) || rows.length === 0) {
      container.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">' + escapeHtml(emptyMessage) + '</div>';
      return;
    }

    container.innerHTML = rows.map((row) => {
      const value = row.value ? escapeHtml(row.value) : 'Not listed';
      return '<div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3 last:border-b-0 last:pb-0"><span class="text-on-surface-variant">' + escapeHtml(row.label) + '</span><span class="font-bold text-primary break-all text-right">' + value + '</span></div>';
    }).join('');
  }

  function renderContactCard(container, contact) {
    if (!container) return;

    if (!contact || typeof contact !== 'object') {
      container.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">Search required</div>';
      return;
    }

    if (contact.redacted) {
      container.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">Redacted for privacy</div>';
      return;
    }

    const fields = [
      ['Name', contact.name],
      ['Street', contact.street],
      ['City', contact.city],
      ['State', contact.state],
      ['Postal Code', contact.postalCode],
      ['Country', contact.country],
      ['Phone', contact.phone],
      ['Email', contact.email],
    ];

    container.innerHTML = fields.map(([label, value]) => {
      return '<div class="flex items-center justify-between gap-4 border-b border-outline-variant/20 pb-3 last:border-b-0 last:pb-0"><span class="text-on-surface-variant">' + escapeHtml(label) + ':</span><span class="font-bold text-primary break-all text-right">' + escapeHtml(value || 'Not listed') + '</span></div>';
    }).join('');
  }

  function renderChipList(container, items, emptyMessage) {
    if (!container) return;

    if (!Array.isArray(items) || items.length === 0) {
      container.innerHTML = '<span class="text-sm text-on-surface-variant">' + escapeHtml(emptyMessage) + '</span>';
      return;
    }

    container.innerHTML = items.map((item) => '<span class="rounded-full border border-outline-variant/30 bg-white px-3 py-2 text-xs font-bold uppercase tracking-[0.18em] text-primary">' + escapeHtml(item) + '</span>').join('');
  }

  function renderSecureDns(block) {
    if (!secureDns) return;

    if (!block || typeof block !== 'object') {
      secureDns.textContent = 'Search required';
      return;
    }

    const parts = [];

    if (Object.prototype.hasOwnProperty.call(block, 'delegationSigned')) {
      parts.push('Delegation signed: ' + (block.delegationSigned ? 'Yes' : 'No'));
    }

    if (Object.prototype.hasOwnProperty.call(block, 'zoneSigned')) {
      parts.push('Zone signed: ' + (block.zoneSigned ? 'Yes' : 'No'));
    }

    if (Array.isArray(block.dsData)) {
      parts.push('DS records: ' + block.dsData.length);
    }

    secureDns.textContent = parts.length > 0 ? parts.join(' · ') : 'Search required';
  }

  function renderEntities(list) {
    if (!contactEntities) return;

    if (!Array.isArray(list) || list.length === 0) {
      contactEntities.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">No contact entities were returned by the registry.</div>';
      return;
    }

    contactEntities.innerHTML = list.map((entity) => {
      const roles = Array.isArray(entity.roles) && entity.roles.length > 0 ? entity.roles : ['unlabeled'];
      const roleChips = roles.map((role) => '<span class="rounded-full bg-neutral-200 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-700">' + escapeHtml(role) + '</span>').join(' ');
      const publicIds = Array.isArray(entity.publicIds) && entity.publicIds.length > 0
        ? entity.publicIds.map((publicId) => publicId.identifier ? escapeHtml((publicId.type || 'id') + ': ' + publicId.identifier) : '').filter(Boolean)
        : [];
      const statusTags = Array.isArray(entity.status) && entity.status.length > 0
        ? entity.status.map((status) => '<span class="rounded-full border border-outline-variant/30 bg-surface-container-low px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-primary">' + escapeHtml(status) + '</span>').join(' ')
        : '<span class="text-xs text-on-surface-variant">No entity status returned.</span>';

      return [
        '<article class="rounded-2xl border border-outline-variant/20 bg-white p-4">',
        '<div class="flex items-start justify-between gap-4">',
        '<div>',
        '<h4 class="text-lg font-bold text-primary break-all">' + escapeHtml(entity.name || entity.organization || entity.handle || 'Redacted entity') + '</h4>',
        '<div class="mt-2 flex flex-wrap gap-2">' + roleChips + '</div>',
        '</div>',
        '<span class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">' + escapeHtml(entity.handle || 'No handle') + '</span>',
        '</div>',
        '<div class="mt-4 space-y-2 text-sm text-on-surface-variant">',
        '<div><span class="font-semibold text-primary">Organization:</span> ' + escapeHtml(entity.organization || 'Not listed') + '</div>',
        '<div><span class="font-semibold text-primary">Title:</span> ' + escapeHtml(entity.title || 'Not listed') + '</div>',
        '<div><span class="font-semibold text-primary">Email:</span> ' + escapeHtml(entity.email || 'Not listed') + '</div>',
        '<div><span class="font-semibold text-primary">Phone:</span> ' + escapeHtml(entity.phone || 'Not listed') + '</div>',
        '<div><span class="font-semibold text-primary">Address:</span> ' + escapeHtml(entity.address || 'Not listed') + '</div>',
        '<div><span class="font-semibold text-primary">URL:</span> ' + escapeHtml(entity.url || 'Not listed') + '</div>',
        '</div>',
        '<div class="mt-4 flex flex-wrap gap-2">' + statusTags + '</div>',
        publicIds.length > 0 ? '<div class="mt-4 flex flex-wrap gap-2">' + publicIds.map((item) => '<span class="rounded-full border border-outline-variant/30 bg-surface-container-low px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-primary">' + item + '</span>').join('') + '</div>' : '',
        '</article>'
      ].join('');
    }).join('');
  }

  function renderEvents(list) {
    if (!events) return;

    if (!Array.isArray(list) || list.length === 0) {
      events.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">No registry events were returned.</div>';
      return;
    }

    events.innerHTML = list.map((event) => {
      const title = event.action || 'event';
      const date = event.date || 'Not listed';
      const actor = event.actor || '';

      return [
        '<div class="rounded-2xl border border-outline-variant/20 bg-white p-4">',
        '<div class="flex items-start justify-between gap-4">',
        '<div>',
        '<p class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">' + escapeHtml(title) + '</p>',
        '<p class="mt-2 text-sm font-semibold text-primary">' + escapeHtml(date) + '</p>',
        '</div>',
        '<span class="rounded-full bg-surface-container-low px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-primary">' + escapeHtml(actor || 'registry') + '</span>',
        '</div>',
        '</div>'
      ].join('');
    }).join('');
  }

  function renderTextBlocks(container, list, emptyMessage) {
    if (!container) return;

    if (!Array.isArray(list) || list.length === 0) {
      container.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant">' + escapeHtml(emptyMessage) + '</div>';
      return;
    }

    container.innerHTML = list.map((item) => {
      const descriptions = Array.isArray(item.description) ? item.description : [];
      const links = Array.isArray(item.links) ? item.links : [];

      return [
        '<article class="rounded-2xl border border-outline-variant/20 bg-white p-4">',
        item.title ? '<p class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-400">' + escapeHtml(item.title) + '</p>' : '',
        descriptions.length > 0 ? '<div class="mt-2 space-y-1 text-sm text-on-surface-variant">' + descriptions.map((line) => '<p>' + escapeHtml(line) + '</p>').join('') + '</div>' : '<p class="mt-2 text-sm text-on-surface-variant">No description returned.</p>',
        links.length > 0 ? '<div class="mt-3 flex flex-wrap gap-2">' + links.map((link) => '<span class="rounded-full border border-outline-variant/30 bg-surface-container-low px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-primary">' + escapeHtml((link.rel || 'link') + (link.title ? ' · ' + link.title : '')) + '</span>').join('') + '</div>' : '',
        '</article>'
      ].join('');
    }).join('');
  }

  function renderRawRdap(raw) {
    if (!rawRdap) return;

    if (!raw || typeof raw !== 'object') {
      rawRdap.textContent = 'Search a domain to view the raw RDAP payload.';
      return;
    }

    rawRdap.textContent = JSON.stringify(raw, null, 2);
  }

  function renderAlternatives(list) {
    if (!alternatives) return;

    if (!Array.isArray(list) || list.length === 0) {
      alternatives.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-surface-container-low p-4 text-sm text-on-surface-variant">No alternative extensions were returned.</div>';
      if (alternativeCount) alternativeCount.textContent = '0';
      return;
    }

    if (alternativeCount) alternativeCount.textContent = String(list.length);

    alternatives.innerHTML = list.map((item) => {
      const isAvailable = item.available === true;
      const label = item.statusLabel || 'Unknown';
      const summaryText = item.summary || '';

      return [
        '<article class="rounded-2xl border border-outline-variant/20 bg-surface-container-low p-4">',
        '<div class="flex items-start justify-between gap-4">',
        '<div>',
        '<h4 class="text-lg font-bold text-primary break-all">' + escapeHtml(item.domain || '') + '</h4>',
        '<p class="mt-1 text-xs font-bold uppercase tracking-[0.2em] ' + (isAvailable ? 'text-emerald-700' : 'text-neutral-500') + '">' + escapeHtml(label) + '</p>',
        '</div>',
        '<span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] ' + (isAvailable ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-200 text-neutral-700') + '">' + (isAvailable ? 'Open' : 'Taken') + '</span>',
        '</div>',
        '<p class="mt-3 text-sm leading-relaxed text-on-surface-variant">' + escapeHtml(summaryText) + '</p>',
        '</article>'
      ].join('');
    }).join('');
  }

  async function lookupDomain(domain) {
    const query = domain.trim();

    if (!query) {
      if (input) input.focus();
      return;
    }

    if (statusLabel) statusLabel.textContent = 'Loading...';
    if (summary) summary.textContent = 'Looking up live registry data...';

    try {
      const response = await fetch('/api/whois.php?domain=' + encodeURIComponent(query), {
        headers: { 'Accept': 'application/json' }
      });

      const data = await response.json();

      if (!response.ok || !data.ok) {
        throw new Error(data.error || 'Lookup failed.');
      }

      const lookup = data.lookup || {};
      const profile = data.profile || {};
      const domainInfo = profile.domainInformation || {};
      const registrarInfo = profile.registrarInformation || {};
      const contacts = profile.contacts || {};

      if (domainHeading) domainHeading.textContent = data.domain || query;
      if (updatedRelative) updatedRelative.textContent = profile.updatedRelative || '';
      if (domainName) domainName.textContent = String(domainInfo.domain || lookup.domain || data.domain || query).toLowerCase();
      if (created) created.textContent = domainInfo.registeredOn || 'Not listed';
      if (expiration) expiration.textContent = domainInfo.expiresOn || 'Not listed';
      if (updated) updated.textContent = domainInfo.updatedOn || 'Not listed';
      if (statusText) statusText.textContent = (Array.isArray(domainInfo.statuses) && domainInfo.statuses.length > 0 ? domainInfo.statuses.join(', ') : lookup.statusLabel) || 'Unknown';
      if (summary) summary.textContent = data.summary || 'Live registry data returned.';
      if (statusLabel) statusLabel.textContent = data.availabilityHeadline || 'Unknown';
      if (registrarName) registrarName.textContent = registrarInfo.registrar || 'Not listed';
      if (registrarIana) registrarIana.textContent = registrarInfo.ianaId || 'Not listed';
      if (registrarUrl) registrarUrl.textContent = registrarInfo.url || 'Not listed';
      if (registrarEmail) registrarEmail.textContent = registrarInfo.email || 'Not listed';
      if (registrarAbuseEmail) registrarAbuseEmail.textContent = registrarInfo.abuseEmail || 'Not listed';
      if (registrarAbusePhone) registrarAbusePhone.textContent = registrarInfo.abusePhone || 'Not listed';

      setBadge(lookup.status || 'unknown', data.availabilityHeadline || lookup.statusLabel || 'Unknown');
      renderNameservers(domainInfo.nameServers || lookup.nameservers || []);
      renderContactCard(registrantContact, contacts.registrant || null);
      renderContactCard(administrativeContact, contacts.administrative || null);
      renderContactCard(technicalContact, contacts.technical || null);
      renderEvents(lookup.events || []);
      renderTextBlocks(notices, lookup.notices || [], 'No notices returned.');
      renderTextBlocks(remarks, lookup.remarks || [], 'No remarks returned.');
      renderRawRdap(lookup.rawRdap || data.rawRdap || null);
      renderAlternatives(data.alternatives || []);

      if (brandFitResult) {
        brandFitResult.textContent = 'Live result loaded.';
      }

      if (window.history && window.history.replaceState) {
        const nextUrl = new URL(window.location.href);
        nextUrl.searchParams.set('domain', data.domain || query);
        window.history.replaceState({}, '', nextUrl.toString());
      }
    } catch (error) {
      const message = error instanceof Error ? error.message : 'Lookup failed.';

      if (statusLabel) statusLabel.textContent = 'Error';
      if (summary) summary.textContent = message;
      if (brandFitResult) brandFitResult.textContent = message;
      if (alternatives) {
        alternatives.innerHTML = '<div class="rounded-2xl border border-error-container bg-error-container p-4 text-sm text-black">' + escapeHtml(message) + '</div>';
      }
    }
  }

  if (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      lookupDomain(input ? input.value : '');
    });
  }

  if (brandFitButton && brandFitInput) {
    brandFitButton.addEventListener('click', function () {
      const businessName = brandFitInput.value.trim();

      if (!businessName) {
        brandFitResult.textContent = 'Enter a business name to compare it with the current domain.';
        return;
      }

      const domainText = domainName ? domainName.textContent : (input ? input.value : '');
      const score = Math.max(52, 96 - Math.min(30, businessName.length + String(domainText).length));
      brandFitResult.textContent = businessName + ' vs ' + domainText + ': fit score ' + score + '/100.';
    });
  }

  if (input && input.value.trim()) {
    lookupDomain(input.value);
  }
})();
</script>
<script src="../assets/js/nav-state.js"></script>
</body>
</html>
