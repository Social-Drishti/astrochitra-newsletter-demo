<?php
// Prevent direct access to config
if (count(get_included_files()) === 1) { exit("Direct access not permitted."); }

// AstroChitra Newsletter System Config
define('SITE_NAME', 'AstroChitra');
define('DATA_DIR', __DIR__ . '/data');
define('SUBSCRIBERS_FILE', DATA_DIR . '/subscribers.json');

// List of available newsletter files
$newsletters = [
    'september-2026' => [
        'title' => 'September 2026 — Autumnal Equinox & Transits',
        'file' => 'september-2026.php',
        'date' => 'September 2026',
        'description' => 'Vedic wisdom for September: Planetary alignments, vital transits, your monthly Rashi tips, and Guru Purnima reflections.',
        'accent' => '#8b4513'
    ]
];

// Helper to save subscribers securely
function add_subscriber($name, $email, $rashi) {
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    
    // Create directory protection file
    if (!file_exists(DATA_DIR . '/.htaccess')) {
        file_put_contents(DATA_DIR . '/.htaccess', "Deny from all");
    }

    $subscribers = [];
    if (file_exists(SUBSCRIBERS_FILE)) {
        $data = file_get_contents(SUBSCRIBERS_FILE);
        $subscribers = json_decode($data, true) ?: [];
    }

    // Check if email already exists
    foreach ($subscribers as $sub) {
        if (strtolower($sub['email']) === strtolower($email)) {
            return ['status' => 'exists', 'message' => 'You are already subscribed to AstroChitra updates.'];
        }
    }

    $subscribers[] = [
        'name' => htmlspecialchars(strip_tags($name)),
        'email' => filter_var($email, FILTER_VALIDATE_EMAIL),
        'rashi' => htmlspecialchars(strip_tags($rashi)),
        'subscribed_at' => date('Y-m-d H:i:s')
    ];

    if (file_put_contents(SUBSCRIBERS_FILE, json_encode($subscribers, JSON_PRETTY_PRINT))) {
        return ['status' => 'success', 'message' => 'Namaste! You have successfully subscribed to AstroChitra Monthly Wisdom.'];
    }

    return ['status' => 'error', 'message' => 'Something went wrong. Please try again later.'];
}