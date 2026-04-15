<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/domain-lookup.php';

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
  'whoisSource' => null,
  'lookupSourceLabel' => null,
  'rawWhois' => null,
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
<?php require __DIR__ . '/_head.php'; ?>
<style>
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  }
  body { font-family: 'Inter', sans-serif; }
  h1, h2, h3 { font-family: 'Manrope', sans-serif; }
</style>
</head>
<body class="bg-background text-on-surface">
<?php require __DIR__ . '/_top_nav.php'; ?>

<main class="mx-auto max-w-7xl px-6 py-8 lg:px-8 lg:py-10">
  <section class="border-b border-outline-variant/40 pb-6">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
      <div>
        <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-400">WHOIS Domain Lookup</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight lg:text-5xl">WHOIS lookup</h1>
      </div>
    </div>

    <form id="whois-lookup-form" class="mt-6">
      <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
        <label class="flex min-w-0 flex-1 items-center gap-3 border-b-2 border-outline-variant/60 bg-white px-2 py-2 focus-within:border-primary">
          <span class="material-symbols-outlined text-neutral-400">search</span>
          <input id="whois-lookup-input" name="domain" class="w-full border-0 bg-transparent px-0 py-2 text-lg font-semibold text-primary placeholder:text-neutral-400 focus:ring-0" type="text" value="<?php echo htmlspecialchars($initialDomain, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search domain ownership"/>
        </label>
        <button class="rounded-full bg-primary px-7 py-3 text-sm font-bold uppercase tracking-[0.18em] text-white transition-colors hover:bg-neutral-800" type="submit">Search</button>
      </div>
    </form>
  </section>

  <section class="mt-6 border-b border-outline-variant/40 pb-6">
    <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Result</p>
    <div class="mt-2 flex flex-wrap items-center gap-3">
      <h2 id="whois-domain-heading" class="break-all text-3xl font-black tracking-tight lg:text-4xl"><?php echo $hasInitialLookup ? htmlspecialchars($initialDomain, ENT_QUOTES, 'UTF-8') : 'No domain searched yet'; ?></h2>
      <span id="whois-domain-badge" class="rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] <?php echo !$hasInitialLookup ? 'bg-neutral-200 text-neutral-700' : ($initialStatus === 'available' ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-900 text-white'); ?>"><?php echo htmlspecialchars($initialBadge, ENT_QUOTES, 'UTF-8'); ?></span>
      <span class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-500">Source: <span id="whois-registry-source" class="text-primary">Registry</span></span>
      <span class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-500">Status: <span id="whois-status-label" class="text-primary"><?php echo htmlspecialchars($initialBadge, ENT_QUOTES, 'UTF-8'); ?></span></span>
    </div>
    <p id="whois-summary" class="mt-3 max-w-4xl text-base leading-7 text-on-surface-variant"><?php echo htmlspecialchars($initialSummary, ENT_QUOTES, 'UTF-8'); ?></p>
    <p id="whois-updated-relative" class="mt-2 text-sm font-semibold text-on-surface-variant"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_relative_time_label((string) ($initialLookup['updated'] ?? null)), ENT_QUOTES, 'UTF-8') : ''; ?></p>
  </section>

  <section class="mt-6 grid gap-8 xl:grid-cols-[minmax(0,1.45fr)_minmax(0,0.95fr)]">
    <article class="space-y-7">
      <section class="rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-sm">
        <h3 class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Domain Information</h3>
        <dl class="mt-3 divide-y divide-outline-variant/35 border-y border-outline-variant/35 text-sm">
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Domain</dt><dd id="whois-domain-name" class="break-all font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars($initialDomain, ENT_QUOTES, 'UTF-8') : '---'; ?></dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Status</dt><dd id="whois-status-text" class="break-words font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['statusLabel'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Registered On</dt><dd id="whois-domain-registered-on" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_date_only((string) ($initialLookup['created'] ?? null)), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Created</dt><dd id="whois-created" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_date_only((string) ($initialLookup['created'] ?? null)), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Expires On</dt><dd id="whois-expiration" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_date_only((string) ($initialLookup['expiration'] ?? null)), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Updated On</dt><dd id="whois-updated" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars(whois_rdap_date_only((string) ($initialLookup['updated'] ?? null)), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></dd></div>
        </dl>
        <div id="whois-nameservers" class="mt-4 flex flex-wrap gap-2">
          <?php if ($hasInitialLookup && $initialNameservers !== []): ?>
            <?php foreach ($initialNameservers as $nameServer): ?>
              <span class="rounded-full border border-outline-variant/40 bg-white px-3 py-1.5 text-xs font-semibold text-primary"><?php echo htmlspecialchars((string) $nameServer, ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endforeach; ?>
          <?php else: ?>
            <span class="text-sm text-on-surface-variant">No nameservers returned by the registry.</span>
          <?php endif; ?>
        </div>
      </section>

      <section class="rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-sm">
        <h3 class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Registrar Information</h3>
        <dl class="mt-3 divide-y divide-outline-variant/35 border-y border-outline-variant/35 text-sm">
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Registrar</dt><dd id="whois-registrar-name" class="break-all font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['registrar'] ?? 'Not listed'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">IANA ID</dt><dd id="whois-registrar-iana" class="font-bold text-primary"><?php echo $hasInitialLookup ? htmlspecialchars((string) ($initialLookup['registrarIanaId'] ?? 'Not listed'), ENT_QUOTES, 'UTF-8') : 'Search required'; ?></dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">URL</dt><dd id="whois-registrar-url" class="break-all font-bold text-primary">Search required</dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Email</dt><dd id="whois-registrar-email" class="break-all font-bold text-primary">Search required</dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Abuse Email</dt><dd id="whois-registrar-abuse-email" class="break-all font-bold text-primary">Search required</dd></div>
          <div class="grid grid-cols-[150px_1fr] gap-4 py-3"><dt class="font-semibold text-on-surface-variant">Abuse Phone</dt><dd id="whois-registrar-abuse-phone" class="break-all font-bold text-primary">Search required</dd></div>
        </dl>
      </section>

      <section class="rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-sm">
        <h3 class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Contacts</h3>
        <div class="mt-3 grid gap-4 md:grid-cols-3">
          <div class="rounded-xl border border-outline-variant/25 bg-surface-container-lowest p-3">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-500">Registrant</p>
            <div id="whois-registrant-contact" class="mt-2 text-sm text-on-surface-variant">Search required</div>
          </div>
          <div class="rounded-xl border border-outline-variant/25 bg-surface-container-lowest p-3">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-500">Administrative</p>
            <div id="whois-administrative-contact" class="mt-2 text-sm text-on-surface-variant">Search required</div>
          </div>
          <div class="rounded-xl border border-outline-variant/25 bg-surface-container-lowest p-3">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-500">Technical</p>
            <div id="whois-technical-contact" class="mt-2 text-sm text-on-surface-variant">Search required</div>
          </div>
        </div>
      </section>

      <section class="rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-sm">
        <h3 class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Timeline</h3>
        <div id="whois-events" class="mt-3 space-y-2 text-sm text-on-surface-variant">Search to load events.</div>
      </section>

      <section class="rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-sm">
        <h3 class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Remarks</h3>
        <div id="whois-remarks" class="mt-3 space-y-3 text-sm text-on-surface-variant">Search to load remarks.</div>
      </section>

      <details class="rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-sm">
        <summary class="cursor-pointer text-xs font-bold uppercase tracking-[0.22em] text-neutral-500">Raw Registry Data</summary>
        <pre id="whois-raw-rdap" class="mt-3 max-h-[16rem] overflow-auto rounded-2xl bg-neutral-950 p-4 text-xs leading-6 text-neutral-100">Search to load raw registry data.</pre>
      </details>
    </article>

    <aside class="space-y-6 xl:sticky xl:top-24 self-start">
      <section class="rounded-2xl border border-outline-variant/30 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
          <h3 class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-400">Alternatives</h3>
          <p class="text-xs font-bold uppercase tracking-[0.2em] text-on-surface-variant"><span id="whois-alternative-count">-</span> options</p>
        </div>
        <div id="whois-alternatives" class="mt-3 divide-y divide-outline-variant/30 border-y border-outline-variant/30">
          <div class="py-3 text-sm text-on-surface-variant">Search to load alternatives.</div>
        </div>
      </section>

      <section class="rounded-2xl bg-black p-5 text-white">
        <div class="flex items-center gap-2">
          <h3 class="text-lg font-bold">Fit Check</h3>
          <span class="material-symbols-outlined text-sm opacity-60">info</span>
        </div>
        <p class="mt-2 text-sm text-white/70">Compare it against your brand name.</p>
        <div class="mt-4 space-y-3">
          <input id="brand-fit-input" class="w-full rounded-xl border border-zinc-800 bg-zinc-900 px-4 py-3 text-sm text-white placeholder:text-zinc-500 focus:ring-0" type="text" placeholder="Your business name"/>
          <button id="brand-fit-button" class="w-full rounded-xl bg-white px-4 py-3 text-sm font-bold text-black transition-colors hover:bg-neutral-200" type="button">Check Fit</button>
          <p id="brand-fit-result" class="text-sm text-white/70">Enter a business name to compare it with the current domain.</p>
        </div>
      </section>

      <section class="grid grid-cols-2 gap-4 border-t border-outline-variant/35 pt-4">
        <div>
          <p id="whois-supported-tlds" class="text-2xl font-black text-primary">-</p>
          <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Extensions Reg.</p>
        </div>
        <div>
          <p class="text-2xl font-black text-primary" id="whois-alternative-count-clone">-</p>
          <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Alternatives</p>
        </div>
      </section>
    </aside>
  </section>
</main>
<?php require __DIR__ . '/_footer.php'; ?>

<script>
(function () {
  const form = document.getElementById('whois-lookup-form');
  const input = document.getElementById('whois-lookup-input');
  const domainHeading = document.getElementById('whois-domain-heading');
  const domainBadge = document.getElementById('whois-domain-badge');
  const registrySource = document.getElementById('whois-registry-source');
  const statusLabel = document.getElementById('whois-status-label');
  const summary = document.getElementById('whois-summary');
  const updatedRelative = document.getElementById('whois-updated-relative');
  const domainName = document.getElementById('whois-domain-name');
  const domainRegisteredOn = document.getElementById('whois-domain-registered-on');
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
  const alternativeCountClone = document.getElementById('whois-alternative-count-clone');
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
      container.innerHTML = '<p class="text-sm text-on-surface-variant">Search required</p>';
      return;
    }

    if (contact.redacted) {
      container.innerHTML = '<p class="text-sm font-semibold text-on-surface-variant">Redacted for privacy</p>';
      return;
    }

    if (contact.hasData === false) {
      container.innerHTML = '<p class="text-sm text-on-surface-variant">Not available for this domain status.</p>';
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

    container.innerHTML = '<dl class="divide-y divide-outline-variant/25 border-y border-outline-variant/25">' + fields.map(([label, value]) => {
      return '<div class="grid grid-cols-[110px_1fr] gap-2 py-2"><dt class="text-xs font-semibold uppercase tracking-[0.16em] text-neutral-500">' + escapeHtml(label) + '</dt><dd class="break-all font-semibold text-primary">' + escapeHtml(value || 'Not listed') + '</dd></div>';
    }).join('') + '</dl>';
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
      events.innerHTML = '<p class="text-sm text-on-surface-variant">No registry events were returned.</p>';
      return;
    }

    events.innerHTML = list.map((event) => {
      const title = event.action || event.eventAction || 'event';
      const date = event.date || event.eventDate || 'Not listed';
      const actor = event.actor || event.eventActor || '';

      return [
        '<div class="grid grid-cols-[120px_1fr_auto] items-start gap-3 border-b border-outline-variant/30 py-2 last:border-b-0">',
        '<p class="text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-500">' + escapeHtml(title) + '</p>',
        '<p class="text-sm font-semibold text-primary">' + escapeHtml(date) + '</p>',
        '<p class="text-xs font-bold uppercase tracking-[0.16em] text-on-surface-variant">' + escapeHtml(actor || 'registry') + '</p>',
        '</div>'
      ].join('');
    }).join('');
  }

  function renderTextBlocks(container, list, emptyMessage) {
    if (!container) return;

    if (!Array.isArray(list) || list.length === 0) {
      container.innerHTML = '<p class="text-sm text-on-surface-variant">' + escapeHtml(emptyMessage) + '</p>';
      return;
    }

    container.innerHTML = list.map((item) => {
      const descriptions = Array.isArray(item.description) ? item.description : [];
      const links = Array.isArray(item.links) ? item.links : [];

      return [
        '<article class="border-b border-outline-variant/30 pb-3 last:border-b-0">',
        item.title ? '<p class="text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">' + escapeHtml(item.title) + '</p>' : '',
        descriptions.length > 0 ? '<div class="mt-1 space-y-1 text-sm text-on-surface-variant">' + descriptions.map((line) => '<p>' + escapeHtml(line) + '</p>').join('') + '</div>' : '<p class="mt-1 text-sm text-on-surface-variant">No description returned.</p>',
        links.length > 0 ? '<div class="mt-2 flex flex-wrap gap-2">' + links.map((link) => '<span class="rounded-full border border-outline-variant/30 px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.16em] text-primary">' + escapeHtml((link.rel || 'link') + (link.title ? ' · ' + link.title : '')) + '</span>').join('') + '</div>' : '',
        '</article>'
      ].join('');
    }).join('');
  }

  function renderRawRdap(raw) {
    if (!rawRdap) return;

    if (raw === null || raw === undefined || raw === '') {
      rawRdap.textContent = 'Search a domain to view the raw registry payload.';
      return;
    }

    if (typeof raw === 'string') {
      rawRdap.textContent = raw;
      return;
    }

    if (typeof raw !== 'object') {
      rawRdap.textContent = String(raw);
      return;
    }

    rawRdap.textContent = JSON.stringify(raw, null, 2);
  }

  function renderAlternatives(list) {
    if (!alternatives) return;

    if (!Array.isArray(list) || list.length === 0) {
      alternatives.innerHTML = '<div class="py-3 text-sm text-on-surface-variant">No alternative extensions were returned.</div>';
      if (alternativeCount) alternativeCount.textContent = '0';
      if (alternativeCountClone) alternativeCountClone.textContent = '0';
      return;
    }

    if (alternativeCount) alternativeCount.textContent = String(list.length);
    if (alternativeCountClone) alternativeCountClone.textContent = String(list.length);

    alternatives.innerHTML = list.map((item) => {
      const isAvailable = item.available === true;
      const label = item.statusLabel || 'Unknown';
      const summaryText = item.summary || '';

      return [
        '<article class="grid grid-cols-[1fr_auto] items-start gap-4 py-3">',
        '<div>',
        '<h4 class="text-base font-black text-primary break-all">' + escapeHtml(item.domain || '') + '</h4>',
        '<p class="mt-1 text-[11px] font-bold uppercase tracking-[0.18em] ' + (isAvailable ? 'text-emerald-700' : 'text-neutral-500') + '">' + escapeHtml(label) + '</p>',
        '<p class="mt-1 text-sm leading-relaxed text-on-surface-variant">' + escapeHtml(summaryText) + '</p>',
        '</div>',
        '<span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-[0.16em] ' + (isAvailable ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-200 text-neutral-700') + '">' + (isAvailable ? 'Open' : 'Taken') + '</span>',
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
      const response = await fetch('<?=$assetBase?>/api/whois.php?domain=' + encodeURIComponent(query), {
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
      const eventRows = Array.isArray(data.eventRows) && data.eventRows.length > 0 ? data.eventRows : (Array.isArray(lookup.eventRows) ? lookup.eventRows : lookup.events || []);

      if (domainHeading) domainHeading.textContent = data.domain || query;
      if (registrySource) registrySource.textContent = lookup.lookupSourceLabel || data.lookup?.lookupSourceLabel || 'Registry';
      if (updatedRelative) updatedRelative.textContent = profile.updatedRelative || '';
      if (domainName) domainName.textContent = String(domainInfo.domain || lookup.domain || data.domain || query).toLowerCase();
      if (domainRegisteredOn) domainRegisteredOn.textContent = domainInfo.registeredOn || 'Not listed';
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
      renderEvents(eventRows);
      renderTextBlocks(notices, lookup.notices || [], 'No notices returned.');
      renderTextBlocks(remarks, lookup.remarks || [], 'No remarks returned.');
      renderRawRdap(lookup.rawWhois || lookup.rawRdap || data.rawWhois || data.rawRdap || null);
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


