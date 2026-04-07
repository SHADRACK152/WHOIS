# WHOIS PHP Backend

This folder is the PHP-only backend scaffold for the WHOIS pages suite.

## Layout

- `backend/app/` holds the shared site map and helper bootstrap.
- `backend/public/index.php` redirects to the PHP landing page.
- `backend/public/api/` exposes JSON endpoints for navigation and page metadata.
- `backend/public/pages/` contains the rendered PHP versions of the suite pages.
- `backend/public/assets/js/nav-state.js` powers the shared active-page nav state.

## Endpoints

- `backend/public/api/nav.php` returns primary navigation and explore groups.
- `backend/public/api/pages.php` returns the page manifest and supports `group` and `q` query parameters.
- `backend/public/index.php` is a lightweight backend landing page for quick inspection.

## Notes

- The backend is intentionally framework-free.
- It reads the static page map from `backend/app/site-map.php`.
- The original HTML source set has been retired; the PHP pages are the runtime source of truth.
- The frontend can consume these endpoints later to render navigation and page listings dynamically.
- Set `GROQ_API_KEY` in the environment before using the AI workflows.
- Optional overrides: `GROQ_MODEL`, `GROQ_BASE_URL`, and `GROQ_TIMEOUT`.
- Set `WHOIS_INSECURE_SSL=1` if the RDAP lookup needs to bypass local certificate verification in this environment.
- Set `TRUEHOST_ENDPOINT`, `TRUEHOST_IDENTIFIER`, `TRUEHOST_SECRET`, and `TRUEHOST_ACCESSKEY` to fetch live domain pricing and availability from Truehost.
- Optional Truehost overrides: `TRUEHOST_CURRENCYID`, `TRUEHOST_TIMEOUT`, and `TRUEHOST_INSECURE_SSL`.
- Set `DOMAINR_RAPIDAPI_KEY` and optionally `DOMAINR_RAPIDAPI_HOST=domainr.p.rapidapi.com` to verify premium or priced domains through Domainr's RapidAPI tier.
- Optional Domainr override: `DOMAINR_TIMEOUT`.

## Start The App

- Double-click `backend/start.bat`, or run it from a Windows terminal.
- It starts the PHP built-in server at `http://localhost:8000` and opens the browser automatically.

## Vercel Deployment

- Deploy from the `backend/` directory so Vercel picks up `vercel.json`.
- The deployment uses `backend/api/index.php` as the front controller with the `vercel-php@0.9.0` runtime.
- The function bundle includes `app/**` and `public/**` so the existing PHP pages and assets resolve correctly.
- Set `GROQ_API_KEY` for the AI workflows and add the Truehost variables if you want live pricing and availability.
