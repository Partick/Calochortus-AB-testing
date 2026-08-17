<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login();

$db = get_db();

$countA = (int) $db->query("SELECT COUNT(*) FROM responses WHERE option_chosen = 'a'")->fetchColumn();
$countB = (int) $db->query("SELECT COUNT(*) FROM responses WHERE option_chosen = 'b'")->fetchColumn();
$total = $countA + $countB;

$pctA = $total > 0 ? round($countA / $total * 100) : 0;
$pctB = $total > 0 ? round($countB / $total * 100) : 0;

$recent = $db->query('
    SELECT option_chosen, email, follow_up_ok, reason, created_at
    FROM responses
    ORDER BY id DESC
    LIMIT 100
')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>A/B test results</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrap">
    <div class="top-bar">
        <div>
            <h1>Homepage A/B test results</h1>
            <p class="subtitle" style="margin-bottom:0;"><?= $total ?> response<?= $total === 1 ? '' : 's' ?> so far</p>
        </div>
        <div class="actions">
            <a class="btn btn-secondary" href="export.php">Export CSV</a>
            <a class="btn btn-secondary" href="logout.php">Log out</a>
        </div>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h2><?= htmlspecialchars(OPTION_A_LABEL) ?></h2>
            <div class="count"><?= $countA ?> <span class="pct">(<?= $pctA ?>%)</span></div>
            <div class="bar-bg"><div class="bar-fill" style="width: <?= $pctA ?>%;"></div></div>
        </div>
        <div class="stat-card">
            <h2><?= htmlspecialchars(OPTION_B_LABEL) ?></h2>
            <div class="count"><?= $countB ?> <span class="pct">(<?= $pctB ?>%)</span></div>
            <div class="bar-bg"><div class="bar-fill" style="width: <?= $pctB ?>%;"></div></div>
        </div>
    </div>

    <h2>Recent responses</h2>
    <?php if (empty($recent)): ?>
        <div class="card empty">No responses yet.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Option</th>
                        <th>Reason</th>
                        <th>Email</th>
                        <th>Ok to follow up?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <span class="tag <?= $row['option_chosen'] === 'a' ? 'tag-a' : 'tag-b' ?>">
                                    <?= $row['option_chosen'] === 'a' ? htmlspecialchars(OPTION_A_LABEL) : htmlspecialchars(OPTION_B_LABEL) ?>
                                </span>
                            </td>
                            <td><?= $row['reason'] ? nl2br(htmlspecialchars($row['reason'])) : '<span class="hint">—</span>' ?></td>
                            <td><?= $row['email'] ? htmlspecialchars($row['email']) : '<span class="hint">—</span>' ?></td>
                            <td><?= $row['follow_up_ok'] === 'yes' ? 'Yes' : 'No' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
