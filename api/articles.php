<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/db-client.php';

try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $rawInput = file_get_contents('php://input');
    $input = json_decode((string) $rawInput, true);

    if (!is_array($input)) {
        $input = $_POST;
    }

    $action = strtolower((string) ($input['action'] ?? ''));

    if ($method === 'POST' && $action === 'save') {
        $id = (int) ($input['id'] ?? 0);
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim((string) ($input['title'] ?? ''))));
        $slug = trim($slug, '-');
        $title = trim((string) ($input['title'] ?? ''));
        $category = trim((string) ($input['category'] ?? ''));
        $excerpt = trim((string) ($input['excerpt'] ?? ''));
        $content = trim((string) ($input['content'] ?? ''));
        $imageUrl = trim((string) ($input['image_url'] ?? ''));
        $authorString = trim((string) ($input['author_string'] ?? ''));
        $status = strtolower(trim((string) ($input['status'] ?? 'draft')));

        if ($title === '' || $content === '') {
            whois_json(['ok' => false, 'error' => 'Title and content are required.']);
            exit;
        }

        if ($status === 'published') {
            $publishedAt = date('Y-m-d H:i:s');
        } else {
            $publishedAt = null;
            $status = 'draft';
        }

        if ($id > 0) {
            whois_db_execute(
                "UPDATE articles SET title = :title, category = :category, excerpt = :excerpt, content = :content, image_url = :image_url, author_string = :author, status = :status, published_at = :published_at, updated_at = NOW() WHERE id = :id",
                [
                    'id' => $id,
                    'title' => $title,
                    'category' => $category,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'image_url' => $imageUrl,
                    'author' => $authorString,
                    'status' => $status,
                    'published_at' => $publishedAt
                ]
            );
        } else {
            whois_db_execute(
                "INSERT INTO articles (slug, title, category, excerpt, content, image_url, author_string, status, published_at) VALUES (:slug, :title, :category, :excerpt, :content, :image_url, :author, :status, :published_at)",
                [
                    'slug' => $slug . '-' . substr(md5(uniqid()), 0, 5),
                    'title' => $title,
                    'category' => $category,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'image_url' => $imageUrl,
                    'author' => $authorString,
                    'status' => $status,
                    'published_at' => $publishedAt
                ]
            );
        }

        whois_json(['ok' => true]);
        exit;
    }

    if ($method === 'POST' && $action === 'delete') {
        $id = (int) ($input['id'] ?? 0);
        if ($id > 0) {
            whois_db_execute("DELETE FROM articles WHERE id = :id", ['id' => $id]);
        }
        whois_json(['ok' => true]);
        exit;
    }

    whois_json(['ok' => false, 'error' => 'Invalid action']);

} catch (Throwable $e) {
    whois_json(['ok' => false, 'error' => $e->getMessage()]);
}
