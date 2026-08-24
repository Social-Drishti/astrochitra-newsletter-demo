<?php
/**
 * Generic analytics endpoint for all AstroChitra newsletters.
 * Accepts JSON (sendBeacon/fetch) or form POST:
 *   slug, type ('click'|'slide_view'), label, target, slide, sid
 */
require_once __DIR__ . '/../admin/db.php';

header('Content-Type: application/json');

/** Truncate safely when the mbstring extension is unavailable. */
function ac_cut(string $s, int $max): string {
    return function_exists('mb_substr') ? mb_substr($s, 0, $max) : substr($s, 0, $max);
}

$payload = [];
$ctype = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($ctype, 'application/json') !== false || empty($_POST)) {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw ?: '', true);
    if (is_array($decoded)) {
        $payload = $decoded;
    }
}
if (!$payload) {
    $payload = $_POST;
}

$slug  = (string)($payload['slug'] ?? '');
$type  = (string)($payload['type'] ?? '');
$sid   = substr(trim((string)($payload['sid'] ?? '')), 0, 64);
$label = ac_cut(trim((string)($payload['label'] ?? '')), 160);
$target= ac_cut(trim((string)($payload['target'] ?? '')), 255);
$slide = isset($payload['slide']) && $payload['slide'] !== '' && $payload['slide'] !== null
    ? (int)$payload['slide'] : null;

if (!in_array($type, ['click', 'slide_view'], true) || $sid === '') {
    http_response_code(422);
    echo json_encode(['ok' => false]);
    exit;
}

$nlId = ac_newsletter_id($pdo, $slug);

$ins = $pdo->prepare('INSERT INTO events (newsletter_id, session_id, event_type, label, target, slide_index)
                      VALUES (?, ?, ?, ?, ?, ?)');
$ins->execute([$nlId, $sid, $type, $label !== '' ? $label : null, $target !== '' ? $target : null, $slide]);

http_response_code(204);
