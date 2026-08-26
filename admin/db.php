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

    $stmt = $pdo->prepare('SELECT id FROM admin_users WHERE username = ?');
    $stmt->execute(['admin']);
    if (!$stmt->fetch()) {
        $ins = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
        $ins->execute(['admin', password_hash('astrochitra', PASSWORD_DEFAULT)]);
    }

    // Subscribers table
    $pdo->exec("CREATE TABLE IF NOT EXISTS subscribers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT,
        phone TEXT,
        rashi TEXT,
        source TEXT DEFAULT '',
        sub_type TEXT NOT NULL DEFAULT 'email' CHECK (sub_type IN ('email','whatsapp')),
        subscribed_at TEXT NOT NULL DEFAULT (datetime('now'))
    )");
    $pdo->exec('CREATE INDEX IF NOT EXISTS ix_subscribers_type ON subscribers(sub_type)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS ix_subscribers_email ON subscribers(email)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS ix_subscribers_phone ON subscribers(phone)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS ix_subscribers_source ON subscribers(source)');

    // Add source column if missing (migration for existing DBs)
    $subCols = $pdo->query("PRAGMA table_info(subscribers)")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('source', $subCols, true)) {
        $pdo->exec("ALTER TABLE subscribers ADD COLUMN source TEXT DEFAULT ''");
    }

    // Migrate existing JSON subscribers into DB
    ac_migrate_json_subscribers($pdo);
}
ac_migrate($pdo);

/**
 * Auto-discover newsletters from /year/month directory structure.
 * Scans the project root for directories matching YYYY/MONTH/index.php.
 */
function ac_discover_newsletters(PDO $pdo): void {
    $root = dirname(__DIR__);
    $months = [
        'january'=>1,'february'=>2,'march'=>3,'april'=>4,'may'=>5,'june'=>6,
        'july'=>7,'august'=>8,'september'=>9,'october'=>10,'november'=>11,'december'=>12
    ];

    $dirs = glob($root . '/[0-9][0-9][0-9][0-9]', GLOB_ONLYDIR);
    foreach ($dirs as $yearDir) {
        $year = basename($yearDir);
        $monthDirs = glob($yearDir . '/*', GLOB_ONLYDIR);
        foreach ($monthDirs as $monthDir) {
            $monthName = basename($monthDir);
            if (!isset($months[$monthName])) continue;
            if (!file_exists($monthDir . '/index.php')) continue;

            $slug = $monthName . '-' . $year;
            $title = ucfirst($monthName) . ' ' . $year . ' | AstroChitra Monthly Newsletter';
            $monthLabel = ucfirst($monthName) . ' ' . $year;

            $find = $pdo->prepare('SELECT id FROM newsletters WHERE slug = ?');
            $find->execute([$slug]);
            if (!$find->fetch()) {
                $ins = $pdo->prepare('INSERT INTO newsletters (slug, title, month_label) VALUES (?, ?, ?)');
                $ins->execute([$slug, $title, $monthLabel]);
            }
        }
    }
}
ac_discover_newsletters($pdo);

/**
 * Get the /year/month path for a newsletter slug.
 */
function ac_newsletter_path(string $slug): ?string {
    $root = dirname(__DIR__);
    if (preg_match('/^([a-z]+)-(\d{4})$/', $slug, $m)) {
        $path = $root . '/' . $m[2] . '/' . $m[1] . '/index.php';
        if (file_exists($path)) {
            return '/' . $m[2] . '/' . $m[1];
        }
    }
    return null;
}

/**
 * Clear events (and interactions) for a newsletter within a date range.
 * If $from/$to are null, clears everything.
 */
function ac_clear_newsletter_data(PDO $pdo, int $nlId, ?string $from = null, ?string $to = null): int {
    $total = 0;

    if ($from !== null && $to !== null) {
        $ev = $pdo->prepare('DELETE FROM events WHERE newsletter_id = ? AND created_at BETWEEN ? AND ?');
        $ev->execute([$nlId, $from, $to]);
        $total += $ev->rowCount();

        $iv = $pdo->prepare('DELETE FROM interactions WHERE newsletter_id = ? AND created_at BETWEEN ? AND ?');
        $iv->execute([$nlId, $from, $to]);
        $total += $iv->rowCount();
    } else {
        $ev = $pdo->prepare('DELETE FROM events WHERE newsletter_id = ?');
        $ev->execute([$nlId]);
        $total += $ev->rowCount();

        $iv = $pdo->prepare('DELETE FROM interactions WHERE newsletter_id = ?');
        $iv->execute([$nlId]);
        $total += $iv->rowCount();
    }

    // Recalculate counters from remaining data
    $viewCount = $pdo->prepare('SELECT COUNT(*) FROM interactions WHERE newsletter_id = ? AND kind = \'view\'');
    $viewCount->execute([$nlId]);
    $shareCount = $pdo->prepare('SELECT COUNT(*) FROM interactions WHERE newsletter_id = ? AND kind = \'share\'');
    $shareCount->execute([$nlId]);
    $commentCount = $pdo->prepare('SELECT COUNT(*) FROM interactions WHERE newsletter_id = ? AND kind = \'comment\'');
    $commentCount->execute([$nlId]);

    $pdo->prepare('UPDATE newsletters SET views = ?, shares = ?, comments_count = ? WHERE id = ?')
        ->execute([(int)$viewCount->fetchColumn(), (int)$shareCount->fetchColumn(), (int)$commentCount->fetchColumn(), $nlId]);

    return $total;
}

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

// ─── Subscriber helpers ──────────────────────────────────────────────────

/**
 * Migrate existing JSON subscribers into the SQLite subscribers table.
 * Runs once; after migration the JSON file is left untouched.
 */
function ac_migrate_json_subscribers(PDO $pdo): void {
    $jsonFile = dirname(__DIR__) . '/data/subscribers.json';
    if (!file_exists($jsonFile)) return;

    // Check if table exists and has data, but handle missing 'source' column gracefully
    $cols = $pdo->query("PRAGMA table_info(subscribers)")->fetchAll(PDO::FETCH_COLUMN);
    $hasSource = in_array('source', $cols, true);
    
    if ($hasSource) {
        $check = $pdo->query('SELECT COUNT(*) FROM subscribers')->fetchColumn();
        if ((int)$check > 0) return;
    } else {
        // Table exists but missing source column - skip JSON migration to avoid errors
        return;
    }

    $data = json_decode(file_get_contents($jsonFile), true);
    if (!is_array($data)) return;

    $ins = $pdo->prepare('INSERT OR IGNORE INTO subscribers (name, email, phone, rashi, sub_type, subscribed_at) VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($data as $row) {
        $name  = $row['name'] ?? '';
        $email = $row['email'] ?? null;
        $rashi = $row['rashi'] ?? null;
        $date  = $row['subscribed_at'] ?? date('Y-m-d H:i:s');

        // JSON subscribers were email-only
        if ($email) {
            $ins->execute([$name, $email, null, $rashi, 'email', $date]);
        }
    }
}

/**
 * Add a single subscriber. If both email and phone are given, inserts two rows.
 * Returns ['status' => ..., 'message' => ...].
 */
function ac_add_subscriber(PDO $pdo, string $name, string $email, string $phone, string $rashi = '', string $source = ''): array {
    $name   = htmlspecialchars(strip_tags(trim($name)));
    $phone  = preg_replace('/[^0-9+]/', '', trim($phone));
    $rashi  = htmlspecialchars(strip_tags(trim($rashi)));
    $source = htmlspecialchars(strip_tags(trim($source)));
    $added  = 0;

    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $dup = $pdo->prepare('SELECT id FROM subscribers WHERE sub_type = ? AND LOWER(email) = LOWER(?)');
        $dup->execute(['email', $email]);
        if (!$dup->fetch()) {
            $pdo->prepare('INSERT INTO subscribers (name, email, rashi, source, sub_type) VALUES (?, ?, ?, ?, ?)')
                ->execute([$name, $email, $rashi, $source, 'email']);
            $added++;
        }
    }

    if ($phone) {
        $norm = preg_replace('/[^0-9]/', '', $phone);
        $dup = $pdo->prepare('SELECT id FROM subscribers WHERE sub_type = ? AND REPLACE(REPLACE(phone,\'-\',\'\'),\' \',\'\') = ?');
        $dup->execute(['whatsapp', $norm]);
        if (!$dup->fetch()) {
            $pdo->prepare('INSERT INTO subscribers (name, phone, rashi, source, sub_type) VALUES (?, ?, ?, ?, ?)')
                ->execute([$name, $phone, $rashi, $source, 'whatsapp']);
            $added++;
        }
    }

    if ($added === 0) {
        return ['status' => 'exists', 'message' => 'This subscriber already exists.'];
    }
    return ['status' => 'success', 'message' => "Added $added subscriber(s)."];
}

/**
 * Get subscribers with optional filters, sorting, and date range.
 */
function ac_get_subscribers(
    PDO $pdo,
    string $type = '',
    string $search = '',
    string $sortBy = 'subscribed_at',
    string $sortDir = 'DESC',
    ?string $dateFrom = null,
    ?string $dateTo = null,
    int $limit = 500,
    string $source = ''
): array {
    $where = [];
    $params = [];

    if ($type === 'email' || $type === 'whatsapp') {
        $where[] = 'sub_type = ?';
        $params[] = $type;
    }

    if ($source !== '') {
        $where[] = 'source = ?';
        $params[] = $source;
    }

    if ($search !== '') {
        $where[] = '(name LIKE ? OR email LIKE ? OR phone LIKE ? OR source LIKE ?)';
        $s = '%' . $search . '%';
        $params[] = $s;
        $params[] = $s;
        $params[] = $s;
        $params[] = $s;
    }

    if ($dateFrom !== null && $dateFrom !== '') {
        $where[] = 'subscribed_at >= ?';
        $params[] = $dateFrom;
    }
    if ($dateTo !== null && $dateTo !== '') {
        $where[] = 'subscribed_at <= ?';
        $params[] = $dateTo . ' 23:59:59';
    }

    $allowedSort = ['name', 'subscribed_at', 'email', 'phone', 'source'];
    if (!in_array($sortBy, $allowedSort, true)) $sortBy = 'subscribed_at';
    $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

    $sql = 'SELECT * FROM subscribers';
    if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= " ORDER BY $sortBy $sortDir LIMIT $limit";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Count subscribers matching filters.
 */
function ac_count_subscribers(PDO $pdo, string $type = '', ?string $dateFrom = null, ?string $dateTo = null, string $source = ''): int {
    $where = [];
    $params = [];
    if ($type === 'email' || $type === 'whatsapp') {
        $where[] = 'sub_type = ?';
        $params[] = $type;
    }
    if ($source !== '') {
        $where[] = 'source = ?';
        $params[] = $source;
    }
    if ($dateFrom !== null && $dateFrom !== '') {
        $where[] = 'subscribed_at >= ?';
        $params[] = $dateFrom;
    }
    if ($dateTo !== null && $dateTo !== '') {
        $where[] = 'subscribed_at <= ?';
        $params[] = $dateTo . ' 23:59:59';
    }
    $sql = 'SELECT COUNT(*) FROM subscribers';
    if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}

/**
 * Delete subscribers by IDs.
 */
function ac_delete_subscribers(PDO $pdo, array $ids): int {
    if (empty($ids)) return 0;
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("DELETE FROM subscribers WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    return $stmt->rowCount();
}
