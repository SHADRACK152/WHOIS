<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/db-client.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
if ($slug === '') {
    header('Location: whois_industry_insights_blog.php');
    exit;
}

$article = whois_db_fetch_one("SELECT * FROM articles WHERE slug = :slug AND status = 'published'", ['slug' => $slug]);

if (!$article) {
    header('Location: whois_industry_insights_blog.php');
    exit;
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>WHOIS | <?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <?php require __DIR__ . '/_head.php'; ?>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface selection:bg-primary-container selection:text-white">
<?php require __DIR__ . '/_top_nav.php'; ?>

<main class="pt-24 pb-24 max-w-4xl mx-auto px-8">
    <a href="whois_industry_insights_blog.php" class="inline-flex items-center text-sm font-bold text-on-surface-variant hover:text-black transition-colors mb-12">
        <span class="material-symbols-outlined text-sm mr-2" data-icon="arrow_back">arrow_back</span> Back to Insights
    </a>

    <header class="mb-12">
    <span class="inline-block bg-surface-container-high text-black text-[10px] uppercase tracking-[0.2em] font-bold px-3 py-1 mb-6 rounded-full"><?php echo htmlspecialchars($article['category'], ENT_QUOTES, 'UTF-8'); ?></span>
    <h1 class="text-4xl md:text-6xl font-extrabold font-headline tracking-tighter text-black mb-6 leading-tight">
        <?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <div class="flex items-center space-x-4 text-on-surface-variant font-medium text-sm border-t border-b border-outline-variant/30 py-4">
        <span class="flex items-center">
            <span class="material-symbols-outlined text-base mr-2" data-icon="person">person</span>
            <?php echo htmlspecialchars($article['author_string'] ?: 'System Administrator', ENT_QUOTES, 'UTF-8'); ?>
        </span>
        <span class="flex items-center">
            <span class="material-symbols-outlined text-base mr-2" data-icon="schedule">schedule</span>
            <?php echo (int)($article['read_time_minutes'] ?? 5); ?> min read
        </span>
        <span class="flex items-center border-l border-outline-variant/30 pl-4">
            <?php echo date('M d, Y', strtotime($article['published_at'] ?? $article['created_at'])); ?>
        </span>
    </div>
</header>

<?php if (!empty($article['image_url'])): ?>
<div class="aspect-[21/9] w-full bg-surface-container-high rounded-2xl overflow-hidden mb-12">
    <img alt="Article Cover" class="w-full h-full object-cover filter grayscale" src="<?php echo htmlspecialchars($article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"/>
</div>
<?php endif; ?>

<article class="prose prose-lg max-w-none text-on-surface-variant leading-relaxed">
    <?php echo $article['content']; // Safe: raw HTML stored securely in CMS ?>
</article>

    <div class="mt-24 pt-12 border-t border-outline-variant/30 flex justify-between items-center">
        <div class="text-sm font-bold text-on-surface-variant">Share this insight</div>
        <div class="flex space-x-4">
            <button class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center transition-colors">
                 <span class="material-symbols-outlined text-sm" data-icon="link">link</span>
            </button>
        </div>
    </div>
</main>

<?php require __DIR__ . '/_footer.php'; ?>
<script src="../assets/js/nav-state.js"></script>
</body>
</html>
