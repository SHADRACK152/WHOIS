<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/admin-auth.php';

if (whois_admin_is_authenticated()) {
    header('Location: overview.php', true, 302);
    exit;
}

$errorMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (whois_admin_attempt_login($username, $password)) {
        header('Location: overview.php', true, 302);
        exit;
    }

    $errorMessage = 'Invalid admin credentials.';
}
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WHOIS.ARCHITECT | Admin Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
  body {
    font-family: 'Inter', sans-serif;
    background:
      radial-gradient(circle at top left, rgba(0, 0, 0, 0.05), transparent 28%),
      radial-gradient(circle at bottom right, rgba(0, 0, 0, 0.03), transparent 24%),
      linear-gradient(180deg, #fbfbfb 0%, #f1f1f1 100%);
  }
  h1, h2 {
    font-family: 'Manrope', sans-serif;
  }
</style>
</head>
<body class="min-h-screen flex items-center justify-center px-6">
  <div class="w-full max-w-md rounded-[1.75rem] border border-neutral-200 bg-white/90 p-8 shadow-[0_30px_80px_rgba(0,0,0,0.08)] backdrop-blur-xl">
    <div class="mb-8">
      <div class="mb-4 inline-flex items-center gap-3 rounded-full border border-neutral-200 bg-neutral-50 px-3 py-2">
        <img src="../assets/img/whois-icon.png" alt="WHOIS logo" class="h-7 w-7 rounded-md object-contain border border-neutral-200 bg-white"/>
        <span class="text-[10px] font-bold uppercase tracking-[0.24em] text-neutral-500">WHOIS Brand</span>
      </div>
      <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-500">Admin Access</p>
      <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-black">WHOIS Admin Login</h1>
      <p class="mt-3 text-sm leading-relaxed text-neutral-500">Sign in to review submissions and publish approved listings to the marketplace.</p>
    </div>

    <?php if ($errorMessage !== ''): ?>
      <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
      <div>
        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.2em] text-neutral-500" for="username">Username</label>
        <input class="w-full rounded-2xl border border-neutral-200 bg-neutral-50 px-4 py-3 text-sm focus:border-black focus:ring-0" id="username" name="username" type="text" autocomplete="username" required/>
      </div>
      <div>
        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.2em] text-neutral-500" for="password">Password</label>
        <input class="w-full rounded-2xl border border-neutral-200 bg-neutral-50 px-4 py-3 text-sm focus:border-black focus:ring-0" id="password" name="password" type="password" autocomplete="current-password" required/>
      </div>
      <button class="w-full rounded-full bg-black px-5 py-3 text-xs font-bold uppercase tracking-[0.2em] text-white hover:bg-neutral-800 transition-colors" type="submit">Sign in</button>
    </form>

    <div class="mt-6 flex items-center justify-between text-xs text-neutral-500">
      <a class="hover:text-black transition-colors" href="../pages/whois_premium_domain_intelligence_landing_page.php">Back to site</a>
      <a class="hover:text-black transition-colors" href="../pages/whois_premium_domain_marketplace.php">Marketplace</a>
    </div>
  </div>
</body></html>