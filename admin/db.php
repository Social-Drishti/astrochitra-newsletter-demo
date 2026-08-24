<?php
/**
 * AstroChitra Newsletter — shared SQLite bootstrap.
 * Creates/opens newsletters.db, migrates old schemas, seeds defaults,
 * and exposes helpers used by both the public newsletter pages and the admin.
 */

$dbPath = __DIR__ . '/../data/newsletters.db';
$dataDir = dirname($dbPath);
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0775, true);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA journal_mode = WAL');

function ac_migrate(PDO $pdo): void {
    // Detect legacy single-table schema (no slug column) and rebuild fresh.
    $cols = $pdo->query("PRAGMA table_info(newsletters)")->fetchAll(PDO::FETCH_ASSOC);
    if ($cols) {
        $hasSlug = false;
        foreach ($cols as $c) { if ($c['name'] === 'slug') { $hasSlug = true; } }
        if (!$hasSlug) {
            $pdo->exec('DROP TABLE newsletters');
            $pdo->exec('DROP TABLE IF EXISTS interactions');
        }
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS newsletters (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT UNIQUE NOT NULL,
        title TEXT NOT NULL,
        month_label TEXT NOT NULL,
        published INTEGER NOT NULL DEFAULT 1,
        views INTEGER NOT NULL DEFAULT 0,
        shares INTEGER NOT NULL DEFAULT 0,
        comments_count INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS interactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        newsletter_id INTEGER NOT NULL REFERENCES newsletters(id) ON DELETE CASCADE,
        kind TEXT NOT NULL CHECK (kind IN ('view','share','comment')),
        email TEXT,
        phone TEXT,
        message TEXT,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    )");
    $pdo->exec('CREATE INDEX IF NOT EXISTS ix_interactions_newsletter ON interactions(newsletter_id, created_at)');

    $pdo->exec("CREATE TABLE IF NOT EXISTS events (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        newsletter_id INTEGER NOT NULL REFERENCES newsletters(id) ON DELETE CASCADE,
        session_id TEXT NOT NULL,
        event_type TEXT NOT NULL CHECK (event_type IN ('click','slide_view')),
        label TEXT,
        target TEXT,
        slide_index INTEGER,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    )");
    $pdo->exec('CREATE INDEX IF NOT EXISTS ix_events_nl_type ON events(newsletter_id, event_type)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS ix_events_session ON events(session_id)');

    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL
    )");

    $seed = $pdo->prepare("INSERT OR IGNORE INTO newsletters (slug, title, month_label) VALUES (?, ?, ?)");
    $seed->execute(['september-2026', 'September 2026 | AstroChitra Monthly Newsletter', 'September 2026']);

    $stmt = $pdo->prepare('SELECT id FROM admin_users WHERE username = ?');
    $stmt->execute(['admin']);
    if (!$stmt->fetch()) {
        $ins = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
        $ins->execute(['admin', password_hash('astrochitra', PASSWORD_DEFAULT)]);
    }
}
ac_migrate($pdo);

/** Resolve a newsletter row by slug, auto-registering unknown slugs. Returns id. */
function ac_newsletter_id(PDO $pdo, string $slug, string $title = ''): int {
    $slug = substr(trim($slug), 0, 120);
    if ($slug === '') {
        $slug = 'unknown';
    }
    $find = $pdo->prepare('SELECT id FROM newsletters WHERE slug = ?');
    $find->execute([$slug]);
    $row = $find->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return (int)$row['id'];
    }
    $ins = $pdo->prepare('INSERT INTO newsletters (slug, title, month_label) VALUES (?, ?, ?)');
    $ins->execute([$slug, $title !== '' ? $title : ucfirst(str_replace('-', ' ', $slug)), $slug]);
    return (int)$pdo->lastInsertId();
}

/** Ensure the newsletter row exists and bump its view counter once per session. */
function track_view(PDO $pdo, string $slug, string $title, string $monthLabel = ''): void {
    $find = $pdo->prepare('SELECT id FROM newsletters WHERE slug = ?');
    $find->execute([$slug]);
    $row = $find->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $ins = $pdo->prepare('INSERT INTO newsletters (slug, title, month_label) VALUES (?, ?, ?)');
        $ins->execute([$slug, $title, $monthLabel !== '' ? $monthLabel : $slug]);
        $find->execute([$slug]);
        $row = $find->fetch(PDO::FETCH_ASSOC);
    }
    $id = (int)$row['id'];

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $key = 'viewed_nl_' . $id;
    if (empty($_SESSION[$key])) {
        $_SESSION[$key] = 1;
        $pdo->prepare('UPDATE newsletters SET views = views + 1 WHERE id = ?')->execute([$id]);
        $log = $pdo->prepare("INSERT INTO interactions (newsletter_id, kind) VALUES (?, 'view')");
        $log->execute([$id]);
    }
}

function require_admin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: login.php');
        exit;
    }
}

function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
