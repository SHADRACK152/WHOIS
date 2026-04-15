<?php
/**
 * _head.php — Shared <head> assets for all public pages.
 *
 * Include AFTER <meta charset>, <meta viewport>, and <title> in each page.
 * Do NOT add those tags here — they exist in each individual page.
 */
declare(strict_types=1);
// Compute asset base path relative to this file's location
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$assetBase = '.';

if (strpos($scriptName, '/pages/') !== false || strpos($scriptName, '/admin/') !== false) {
    $assetBase = '..';
} elseif (strpos($scriptName, '/api/') !== false) {
    // API endpoints moved to depth 1, so they also need '.' or '..' relative to root
    $assetBase = '.'; 
}
?>
<link rel="icon" type="image/png" href="<?=$assetBase?>/assets/img/favicon.png"/>
<link rel="apple-touch-icon" href="<?=$assetBase?>/assets/img/whois-icon.png"/>
<!-- Preconnect for font performance -->
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<!-- Fonts: Inter + Manrope — single consolidated request -->
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols — single import, no duplicates -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS — served locally, no CDN round-trip -->
<script src="<?=$assetBase?>/assets/js/tailwind.cdn.js"></script>
<!-- Shared Tailwind theme — single source of truth for all custom colors -->
<script src="<?=$assetBase?>/assets/js/tailwind-site-config.js"></script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    body {
        font-family: 'Inter', sans-serif;
        background: #f9f9f9;
        transition: background-color 180ms ease, color 180ms ease;
    }
    h1, h2, h3, h4, .headline {
        font-family: 'Manrope', sans-serif;
    }
    .glass-panel {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    .hero-gradient {
        background:
            radial-gradient(circle at top left, rgba(0, 0, 0, 0.04), transparent 30%),
            radial-gradient(circle at bottom right, rgba(0, 0, 0, 0.03), transparent 28%),
            linear-gradient(180deg, #ffffff 0%, #f9f9f9 100%);
    }
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .hero-grid {
        display: grid;
        gap: 2rem;
        grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
        align-items: center;
    }
    @media (max-width: 1024px) {
        .hero-grid { grid-template-columns: 1fr; }
    }
    .hero-panel { 
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(198, 198, 198, 0.55);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.06);
    }
    .signal-card {
        background: linear-gradient(180deg, #ffffff 0%, #f3f3f3 100%);
        border: 1px solid rgba(198, 198, 198, 0.55);
    }
    .metric-pill {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(198, 198, 198, 0.5);
    }
    /* Mobile nav drawer */
    #mobile-nav-drawer { display: none; position: fixed; inset: 0; z-index: 60; }
    #mobile-nav-drawer.open { display: block; }
    #mobile-nav-drawer .drawer-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); }
    #mobile-nav-drawer .drawer-panel { position: absolute; right: 0; top: 0; bottom: 0; width: min(320px, 90vw); background: #fff; padding: 1.5rem; overflow-y: auto; box-shadow: -20px 0 60px rgba(0,0,0,0.12); display: flex; flex-direction: column; gap: 0.5rem; }
    #mobile-nav-drawer .drawer-link { display: block; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; color: #1a1c1c; text-decoration: none; transition: background 150ms; }
    #mobile-nav-drawer .drawer-link:hover, #mobile-nav-drawer .drawer-link.active { background: #f3f3f3; }
    #mobile-nav-drawer .drawer-close { align-self: flex-end; margin-bottom: 1rem; border: none; background: #f3f3f3; border-radius: 999px; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; }
</style>
