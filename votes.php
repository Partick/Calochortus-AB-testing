<?php
// ---------------------------------------------------------------------
// Duplicate-vote prevention: a long-lived cookie stops the same browser
// from voting twice, and an IP-address check catches repeat visits with
// the cookie cleared. Neither is bulletproof (shared IPs, incognito
// windows) but together they cover normal repeat-visit behavior.
// ---------------------------------------------------------------------

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

define('VOTE_COOKIE_NAME', 'ab_test_voted');

function client_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function cookie_base_path(): string {
    $path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    return $path === '' ? '/' : $path;
}

function has_already_voted(): bool {
    if (isset($_COOKIE[VOTE_COOKIE_NAME])) {
        return true;
    }

    $ip = client_ip();
    if ($ip === '') {
        return false;
    }

    $stmt = get_db()->prepare('SELECT COUNT(*) FROM responses WHERE ip_address = :ip');
    $stmt->execute([':ip' => $ip]);
    return (int) $stmt->fetchColumn() > 0;
}

function mark_voted(): void {
    setcookie(VOTE_COOKIE_NAME, '1', [
        'expires' => time() + 60 * 60 * 24 * 365,
        'path' => cookie_base_path(),
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function render_already_voted(): void {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanks again!</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="wrap">
        <div class="thanks">
            <div class="check">👍</div>
            <h1>Looks like you've already responded</h1>
            <p class="subtitle">We can only count one response per visitor for this test &mdash; thanks again for your feedback!</p>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit;
}
