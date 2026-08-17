<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login();

$db = get_db();
$rows = $db->query('
    SELECT id, option_chosen, email, follow_up_ok, reason, ip_address, created_at
    FROM responses
    ORDER BY id ASC
')->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="ab-test-responses-' . date('Y-m-d') . '.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['ID', 'Option', 'Option Label', 'Email', 'Ok to follow up', 'Reason', 'IP Address', 'Submitted at (UTC)']);

foreach ($rows as $row) {
    $label = $row['option_chosen'] === 'a' ? OPTION_A_LABEL : OPTION_B_LABEL;
    fputcsv($out, [
        $row['id'],
        strtoupper($row['option_chosen']),
        $label,
        $row['email'],
        $row['follow_up_ok'],
        $row['reason'],
        $row['ip_address'],
        $row['created_at'],
    ]);
}

fclose($out);
