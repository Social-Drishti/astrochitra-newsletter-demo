<?php
require_once __DIR__ . '/../admin/db.php';

function back(string $redirect, bool $success): void {
    $sep = str_contains($redirect, '?') ? '&' : '?';
    header('Location: ' . $redirect . $sep . ($success ? 'shared=1' : 'share_error=1') . '#share');
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ../newsletters/september-2026.php#share');
    exit;
}

$slug     = trim($_POST['slug'] ?? 'september-2026');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$message  = trim($_POST['message'] ?? '');
$redirect = $_POST['redirect'] ?? '';

// Only allow same-site relative redirect targets.
if ($redirect === '' || !str_starts_with($redirect, '/') || str_starts_with($redirect, '//')) {
    $redirect = '/newsletters/september-2026.php';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    back($redirect, false);
}

$stmt = $pdo->prepare('SELECT id FROM newsletters WHERE slug = ?');
$stmt->execute([$slug]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    $ins = $pdo->prepare("INSERT INTO newsletters (slug, title, month_label) VALUES (?, ?, ?)");
    $ins->execute([$slug, ucfirst($slug), $slug]);
    $newsletterId = (int)$pdo->lastInsertId();
} else {
    $newsletterId = (int)$row['id'];
}

$kind = ($message !== '') ? 'comment' : 'share';
$log = $pdo->prepare('INSERT INTO interactions (newsletter_id, kind, email, phone, message) VALUES (?, ?, ?, ?, ?)');
$log->execute([$newsletterId, $kind, $email, $phone !== '' ? $phone : null, $message !== '' ? $message : null]);

$pdo->prepare('UPDATE newsletters SET shares = shares + 1' . ($message !== '' ? ', comments_count = comments_count + 1' : '') . ' WHERE id = ?')
    ->execute([$newsletterId]);

back($redirect, true);
