(() => {
  const currentFile = (window.location.pathname.split('/').pop() || 'whois_premium_domain_intelligence_landing_page.html').toLowerCase();
  const navLinks = document.querySelectorAll('nav a[href]');

  if (!navLinks.length) {
    return;
  }

  const style = document.createElement('style');
  style.textContent = `
    nav a[data-active-page="true"] {
      color: #000 !important;
    }

    nav a[data-active-page="true"][data-nav-slot="top"] {
      font-weight: 700 !important;
      text-decoration: underline;
      text-decoration-thickness: 2px;
      text-underline-offset: 0.35em;
      text-decoration-color: #000;
    }

    nav a[data-active-page="true"][data-nav-slot="menu"] {
      background: #f9f9f9;
      border-radius: 0.5rem;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
      font-weight: 600 !important;
      color: #1a1c1c !important;
    }
  `;
  document.head.appendChild(style);

  navLinks.forEach((link) => {
    const href = (link.getAttribute('href') || '')
      .split('#')[0]
      .split('?')[0]
      .split('/')
      .pop()
      .toLowerCase();

    if (!href || href.startsWith('#')) {
      return;
    }

    if (href === currentFile) {
      link.setAttribute('aria-current', 'page');
      link.setAttribute('data-active-page', 'true');
      link.setAttribute('data-nav-slot', link.closest('details') ? 'menu' : 'top');
    }
  });
})();