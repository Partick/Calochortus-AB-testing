<?php
// ---------------------------------------------------------------------
// Basic configuration for the A/B test.
// ---------------------------------------------------------------------

// Password used to view the results dashboard and export data.
define('RESULTS_PASSWORD', 'asclepias-agave-aster');

// Human-readable labels shown to visitors and on the results page.
define('OPTION_A_LABEL', 'Homepage Option A');
define('OPTION_B_LABEL', 'Homepage Option B');

// Image files (inside the images/ folder) for each option.
define('OPTION_A_IMAGE', 'images/option-a.png');
define('OPTION_B_IMAGE', 'images/option-b.png');
define('LOGO_IMAGE', 'images/logo.svg');

// Where the SQLite database file lives. Keep it inside data/ so the
// included .htaccess can block direct web access to it.
define('DB_PATH', __DIR__ . '/data/responses.sqlite');

session_start();
