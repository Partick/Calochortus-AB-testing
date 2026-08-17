<?php
// ---------------------------------------------------------------------
// Database helper: creates/opens a small SQLite file and ensures the
// responses table exists. SQLite needs no server setup, which keeps
// this whole test self-contained inside one folder.
// ---------------------------------------------------------------------

require_once __DIR__ . '/config.php';

function get_db(): PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $isNew = !file_exists(DB_PATH);
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS responses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            option_chosen TEXT NOT NULL,
            email TEXT,
            follow_up_ok TEXT NOT NULL,
            reason TEXT,
            ip_address TEXT,
            created_at TEXT NOT NULL
        )
    ');

    // Older databases (created before duplicate-vote prevention existed)
    // won't have this column yet, so add it if it's missing.
    $columns = $pdo->query('PRAGMA table_info(responses)')->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('ip_address', $columns, true)) {
        $pdo->exec('ALTER TABLE responses ADD COLUMN ip_address TEXT');
    }

    if ($isNew) {
        @chmod(DB_PATH, 0640);
    }

    return $pdo;
}
