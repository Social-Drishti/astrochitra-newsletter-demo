<?php
/**
 * Admin API: Clear newsletter tracking data.
 * POST only. Requires admin session.
 *
 * Params:
 *   newsletter_id  (int, required)
 *   from           (datetime string, optional — for range delete)
 *   to             (datetime string, optional — for range delete)
 *   clear_all      (1, optional — clears everything for the newsletter)
 */
session_start();
require_once __DIR__ . '/../admin/db.php';

header('Content-Type: application/json');

if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Not authenticated']);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'POST required']);
    exit;
}

$nlId = (int)($_POST['newsletter_id'] ?? 0);
if ($nlId <= 0) {
    http_response_code(400);
    echo json_encode(['!ok' => false, 'error' => 'Invalid newsletter_id']);
    exit;
}

$stmt = $pdo->prepare('SELECT id, slug, title FROM newsletters WHERE id = ?');
$stmt->execute([$nlId]);
$nl = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$nl) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Newsletter not found']);
    exit;
}

$clearAll = !empty($_POST['clear_all']);
$from = $_POST['from'] ?? null;
$to = $_POST['to'] ?? null;

if ($clearAll) {
    $deleted = ac_clear_newsletter_data($pdo, $nlId);
    echo json_encode(['ok' => true, 'deleted' => $deleted, 'message' => 'All data cleared for ' . $nl['slug']]);
} elseif ($from !== null && $to !== null) {
    $deleted = ac_clear_newsletter_data($pdo, $nlId, $from, $to);
    echo json_encode(['ok' => true, 'deleted' => $deleted, 'message' => "Cleared $deleted records from $from to $to"]);
} else {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Provide either clear_all=1 or both from and to dates']);
}
