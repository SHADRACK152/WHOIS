<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/admin-auth.php';
whois_admin_require_auth();

function whois_admin_nav_items(): array
{
    return [
        ['key' => 'overview', 'label' => 'Overview', 'icon' => 'dashboard', 'href' => 'overview.php'],
        ['key' => 'submissions', 'label' => 'Submissions', 'icon' => 'inbox', 'href' => 'submissions.php'],
        ['key' => 'marketplace', 'label' => 'Marketplace', 'icon' => 'storefront', 'href' => 'marketplace.php'],
        ['key' => 'analytics', 'label' => 'Analytics', 'icon' => 'query_stats', 'href' => 'analytics.php'],
        ['key' => 'settings', 'label' => 'Settings', 'icon' => 'settings', 'href' => 'settings.php'],
    ];
}

function whois_admin_render_page(array $page, callable $contentRenderer): void
{
    header('Content-Type: text/html; charset=utf-8');

    $title = (string) ($page['title'] ?? 'WHOIS.ARCHITECT | Admin');
    $active = (string) ($page['active'] ?? 'overview');
    $eyebrow = (string) ($page['eyebrow'] ?? 'Admin Side');
    $headline = (string) ($page['headline'] ?? 'Operations dashboard for the WHOIS marketplace.');
    $description = (string) ($page['description'] ?? '');
    $navItems = whois_admin_nav_items();

    ?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
<link rel="icon" type="image/png" href="../assets/img/favicon.png"/>
<link rel="apple-touch-icon" href="../assets/img/whois-icon.png"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-dim": "#dadada",
                        "secondary-container": "#d5d4d4",
                        "tertiary-fixed-dim": "#454747",
                        "on-secondary-fixed": "#1b1c1c",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-surface-variant": "#474747",
                        "surface-bright": "#f9f9f9",
                        "on-tertiary-fixed": "#ffffff",
                        "secondary": "#5e5e5e",
                        "surface-container-lowest": "#ffffff",
                        "inverse-primary": "#c6c6c6",
                        "on-background": "#1a1c1c",
                        "background": "#f9f9f9",
                        "surface-container-high": "#e8e8e8",
                        "on-primary": "#e2e2e2",
                        "primary-container": "#3b3b3b",
                        "primary-fixed": "#5e5e5e",
                        "outline": "#777777",
                        "on-secondary-container": "#1b1c1c",
                        "on-primary-fixed-variant": "#e2e2e2",
                        "surface-variant": "#e2e2e2",
                        "on-primary-fixed": "#ffffff",
                        "on-surface": "#1a1c1c",
                        "inverse-on-surface": "#f1f1f1",
                        "surface-container": "#eeeeee",
                        "tertiary-fixed": "#5d5f5f",
                        "error-container": "#ffdad6",
                        "primary-fixed-dim": "#474747",
                        "on-tertiary": "#e2e2e2",
                        "surface-container-highest": "#e2e2e2",
                        "surface": "#f9f9f9",
                        "on-primary-container": "#ffffff",
                        "inverse-surface": "#2f3131",
                        "primary": "#000000",
                        "tertiary-container": "#737575",
                        "secondary-fixed-dim": "#acabab",
                        "tertiary": "#3a3c3c",
                        "error": "#ba1a1a"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1.25rem",
                        "3xl": "1.5rem",
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
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f9f9f9;
          transition: background-color 180ms ease, color 180ms ease;
        }
        h1, h2, h3, .headline {
            font-family: 'Manrope', sans-serif;
        }
        html.dark body {
          background: #1d1d1d;
          background-image:
            radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 28%),
            radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.03), transparent 26%),
            linear-gradient(180deg, #232323 0%, #1d1d1d 100%);
          color: #f4f4f4;
        }
        .admin-layout {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }
        .admin-sidebar {
            width: 280px;
            flex: 0 0 auto;
            transition: width 220ms ease;
            background: rgba(255, 255, 255, 0.9);
            border-right: 1px solid rgba(198, 198, 198, 0.55);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.03);
        }
        html.dark .admin-sidebar,
        html.dark .admin-panel,
        html.dark .admin-card,
        html.dark .admin-chip {
          background: linear-gradient(180deg, #2b2b2b 0%, #232323 100%);
          border-color: rgba(255, 255, 255, 0.08);
          box-shadow: 0 18px 42px rgba(0, 0, 0, 0.28), inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }
        html.dark .admin-sidebar {
          border-right-color: rgba(255, 255, 255, 0.08);
          background: linear-gradient(180deg, #2c2c2c 0%, #232323 100%);
          box-shadow: 0 24px 60px rgba(0, 0, 0, 0.34), inset -1px 0 0 rgba(255, 255, 255, 0.04);
        }
        html.dark .admin-hero {
          background:
            radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 30%),
            radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.02), transparent 28%),
            linear-gradient(180deg, #2d2d2d 0%, #1f1f1f 100%);
        }
        html.dark .admin-content > header {
          background: linear-gradient(180deg, rgba(42, 42, 42, 0.92) 0%, rgba(32, 32, 32, 0.9) 100%);
          box-shadow: 0 12px 34px rgba(0, 0, 0, 0.24);
          border-bottom-color: rgba(255, 255, 255, 0.08);
        }
        html.dark .bg-white\/85,
        html.dark .bg-white\/90,
        html.dark .bg-white\/95,
        html.dark .bg-surface-container-lowest,
        html.dark .bg-surface-container-low,
        html.dark .bg-surface-container,
        html.dark .bg-surface-container-high,
        html.dark .bg-surface-container-highest,
        html.dark .bg-surface-container-lowest\/95 {
          background-color: #2b2b2b !important;
        }
        html.dark .sidebar-description,
        html.dark .sidebar-note,
        html.dark .sidebar-utility-text,
        html.dark .sidebar-action-text,
        html.dark .sidebar-label,
        html.dark .text-on-surface-variant,
        html.dark .text-secondary,
        html.dark .text-black {
          color: #f4f4f4;
        }
        html.dark .text-primary,
        html.dark .sidebar-brand-text {
          color: #ffffff;
        }
        html.dark .admin-panel,
        html.dark .admin-card {
          box-shadow: 0 22px 54px rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.04) inset;
        }
        html.dark .admin-chip {
          box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }
        html.dark .admin-panel:hover,
        html.dark .admin-card:hover {
          box-shadow: 0 28px 70px rgba(0, 0, 0, 0.35), 0 1px 0 rgba(255, 255, 255, 0.05) inset;
        }
        .admin-content {
            flex: 1 1 auto;
            min-width: 0;
        }
        body.admin-sidebar-collapsed .admin-sidebar {
            width: 92px;
        }
        body.admin-sidebar-collapsed .sidebar-copy,
        body.admin-sidebar-collapsed .sidebar-description,
        body.admin-sidebar-collapsed .sidebar-label,
        body.admin-sidebar-collapsed .sidebar-action-text,
        body.admin-sidebar-collapsed .sidebar-utility-text,
        body.admin-sidebar-collapsed .sidebar-note,
        body.admin-sidebar-collapsed .sidebar-brand-text,
        body.admin-sidebar-collapsed .sidebar-top,
        body.admin-sidebar-collapsed .sidebar-theme-switcher {
            display: none;
        }
        body.admin-sidebar-collapsed .sidebar-nav-link {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        body.admin-sidebar-collapsed .sidebar-brand {
            justify-content: center;
        }
        body.admin-sidebar-fixed [data-sidebar-toggle] {
          display: none !important;
        }
        body.admin-density-compact .admin-panel {
          padding: 1.25rem !important;
        }
        body.admin-density-comfortable .admin-panel {
          padding: 1.75rem !important;
        }
        body.admin-density-comfortable .admin-content > header {
          padding-top: 1.15rem;
          padding-bottom: 1.15rem;
        }
        body.admin-density-comfortable .admin-content main {
          padding-top: 2.25rem;
          padding-bottom: 2.25rem;
        }
        body.admin-cards-flat .admin-panel,
        body.admin-cards-flat .admin-card,
        body.admin-cards-flat .admin-chip {
          box-shadow: none;
        }
        body.admin-cards-flat .admin-panel {
          background: rgba(255, 255, 255, 0.82);
        }
        body.admin-cards-flat .admin-card {
          background: #f5f5f5;
        }
        body.admin-cards-flat .admin-chip {
          background: rgba(255, 255, 255, 0.9);
        }
        .admin-hero {
            background:
                radial-gradient(circle at top left, rgba(0, 0, 0, 0.05), transparent 30%),
                radial-gradient(circle at bottom right, rgba(0, 0, 0, 0.03), transparent 28%),
                linear-gradient(180deg, #ffffff 0%, #f9f9f9 100%);
        }
        html.dark body.admin-cards-flat .admin-panel,
        html.dark body.admin-cards-flat .admin-card,
        html.dark body.admin-cards-flat .admin-chip {
          box-shadow: none;
          background: linear-gradient(180deg, #262626 0%, #202020 100%);
        }
        .admin-panel {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(198, 198, 198, 0.5);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.06);
          transition: box-shadow 180ms ease, transform 180ms ease, border-color 180ms ease;
        }
        .admin-card {
            background: linear-gradient(180deg, #ffffff 0%, #f3f3f3 100%);
            border: 1px solid rgba(198, 198, 198, 0.45);
        }
        .admin-chip {
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(198, 198, 198, 0.45);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
<div class="admin-layout">
  <aside class="admin-sidebar sticky top-0 h-screen overflow-y-auto">
    <div class="sidebar-top p-5 border-b border-outline-variant/20">
      <div class="flex items-center justify-between gap-3 sidebar-brand">
        <a class="flex items-center gap-3 text-black" href="overview.php">
          <img src="../assets/img/whois-icon.png" alt="WHOIS logo" class="h-8 w-8 rounded-lg object-contain border border-outline-variant/30 bg-white"/>
          <span class="text-xl font-black tracking-tighter font-['Manrope']">WHOIS</span>
          <span class="sidebar-brand-text hidden xl:inline text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-400">Admin</span>
        </a>
        <button class="inline-flex items-center justify-center rounded-full border border-outline-variant/40 bg-surface-container-lowest w-10 h-10 hover:bg-surface-container-low transition-colors" type="button" data-sidebar-toggle aria-label="Collapse sidebar">
          <span class="material-symbols-outlined text-sm">menu_open</span>
        </button>
      </div>
      <p class="sidebar-description mt-4 text-sm text-on-surface-variant leading-relaxed">Compact workspace.</p>
      <div class="sidebar-theme-switcher mt-4 grid grid-cols-2 gap-2">
        <button class="rounded-xl border border-outline-variant/40 bg-surface-container-lowest px-3 py-2 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-surface-container-low transition-colors" type="button" data-theme-toggle="light">Light</button>
        <button class="rounded-xl border border-outline-variant/40 bg-surface-container-lowest px-3 py-2 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-surface-container-low transition-colors" type="button" data-theme-toggle="dark">Dark</button>
      </div>
    </div>

    <nav class="p-4 space-y-2">
      <?php foreach ($navItems as $item): ?>
        <?php $isActive = $active === $item['key']; ?>
        <a class="sidebar-nav-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-colors <?php echo $isActive ? 'bg-surface-container-low text-black border border-outline-variant/30' : 'text-secondary hover:bg-surface-container-low'; ?>" href="<?php echo htmlspecialchars((string) $item['href'], ENT_QUOTES, 'UTF-8'); ?>">
          <span class="material-symbols-outlined text-sm"><?php echo htmlspecialchars((string) $item['icon'], ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="sidebar-label"><?php echo htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="sidebar-copy p-4 border-t border-outline-variant/20 space-y-4">
      <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/30 p-4">
        <p class="sidebar-note text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-3">Workspace</p>
        <div class="space-y-2 text-sm">
          <a class="sidebar-utility-text flex items-center justify-between rounded-xl px-3 py-2 hover:bg-surface-container-low transition-colors" href="../pages/whois_premium_domain_marketplace.php"><span>Marketplace</span><span class="material-symbols-outlined text-sm text-secondary">open_in_new</span></a>
          <a class="sidebar-utility-text flex items-center justify-between rounded-xl px-3 py-2 hover:bg-surface-container-low transition-colors" href="../pages/whois_submit_domain_for_auction.php"><span>Submit Flow</span><span class="material-symbols-outlined text-sm text-secondary">open_in_new</span></a>
        </div>
      </div>
    </div>
  </aside>

  <div class="admin-content">
    <header class="sticky top-0 z-40 bg-white/85 backdrop-blur-xl border-b border-outline-variant/20 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
      <div class="flex items-center justify-between gap-4 px-6 lg:px-8 py-4">
        <div>
          <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-secondary mb-1"><?php echo htmlspecialchars($eyebrow, ENT_QUOTES, 'UTF-8'); ?></p>
          <div class="flex items-center gap-2">
            <img src="../assets/img/whois-icon.png" alt="WHOIS logo" class="h-6 w-6 rounded-md object-contain border border-outline-variant/30 bg-white"/>
            <h1 class="text-xl md:text-2xl font-extrabold tracking-tight text-primary"><?php echo htmlspecialchars($headline, ENT_QUOTES, 'UTF-8'); ?></h1>
          </div>
          <?php if (trim((string) $description) !== ''): ?>
          <p class="text-sm text-on-surface-variant max-w-3xl mt-1"><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
        </div>
        <div class="flex items-center gap-3">
          <a class="hidden sm:inline-flex rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" href="logout.php">Logout</a>
          <button class="inline-flex items-center gap-2 rounded-full border border-outline-variant/40 bg-surface-container-lowest px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-surface-container-low transition-colors" type="button" data-sidebar-toggle>
            <span class="material-symbols-outlined text-sm">collapse_content</span>
            <span class="sidebar-action-text">Collapse</span>
          </button>
          <a class="hidden sm:inline-flex rounded-full bg-primary px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-on-primary hover:bg-primary-container transition-colors" href="overview.php">Overview</a>
        </div>
      </div>
    </header>

    <main class="px-6 lg:px-8 py-8">
      <?php $contentRenderer(); ?>
    </main>
  </div>
</div>
<script>
(function () {
  const body = document.body;
  const buttons = document.querySelectorAll('[data-sidebar-toggle]');
  const themeButtons = document.querySelectorAll('[data-theme-toggle]');
  let sidebarMode = 'collapsible';

  function setTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');

    try {
      localStorage.setItem('whois-admin-theme', theme);
    } catch (error) {
    }
  }

  function setSidebarMode(mode) {
    sidebarMode = mode === 'fixed' ? 'fixed' : 'collapsible';
    body.classList.toggle('admin-sidebar-fixed', sidebarMode === 'fixed');

    if (sidebarMode === 'fixed') {
      body.classList.remove('admin-sidebar-collapsed');
    }

    try {
      localStorage.setItem('whois-admin-sidebar-mode', sidebarMode);
    } catch (error) {
    }
  }

  function setCollapsed(collapsed) {
    if (sidebarMode === 'fixed') {
      body.classList.remove('admin-sidebar-collapsed');
      return;
    }

    body.classList.toggle('admin-sidebar-collapsed', collapsed);

    try {
      localStorage.setItem('whois-admin-sidebar-collapsed', collapsed ? '1' : '0');
    } catch (error) {
    }
  }

  function setDensity(density) {
    const normalized = density === 'comfortable' ? 'comfortable' : 'compact';
    body.classList.toggle('admin-density-compact', normalized === 'compact');
    body.classList.toggle('admin-density-comfortable', normalized === 'comfortable');

    try {
      localStorage.setItem('whois-admin-density', normalized);
    } catch (error) {
    }
  }

  function setCardStyle(style) {
    const normalized = style === 'flat' ? 'flat' : 'layered';
    body.classList.toggle('admin-cards-layered', normalized === 'layered');
    body.classList.toggle('admin-cards-flat', normalized === 'flat');

    try {
      localStorage.setItem('whois-admin-card-style', normalized);
    } catch (error) {
    }
  }

  try {
    if (localStorage.getItem('whois-admin-theme') === 'dark') {
      setTheme('dark');
    } else {
      setTheme('light');
    }

    setSidebarMode(localStorage.getItem('whois-admin-sidebar-mode') === 'fixed' ? 'fixed' : 'collapsible');
    setDensity(localStorage.getItem('whois-admin-density') === 'comfortable' ? 'comfortable' : 'compact');
    setCardStyle(localStorage.getItem('whois-admin-card-style') === 'flat' ? 'flat' : 'layered');

    if (sidebarMode !== 'fixed' && localStorage.getItem('whois-admin-sidebar-collapsed') === '1') {
      setCollapsed(true);
    }
  } catch (error) {
  }

  themeButtons.forEach((button) => {
    button.addEventListener('click', () => {
      setTheme(button.dataset.themeToggle || 'light');
    });
  });

  buttons.forEach((button) => {
    button.addEventListener('click', () => {
      setCollapsed(!body.classList.contains('admin-sidebar-collapsed'));
    });
  });
})();
</script>
</body></html>
<?php
}