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

    <h2>Feedback trends</h2>
    <div class="card">
        <p class="hint" style="margin-bottom:20px;">
            Manual read-through of the 11 written responses collected so far (as of 2026-08-17). Overall split: 8 chose Option A, 4 chose Option B. This is a snapshot &mdash; worth re-reading as more responses come in.
        </p>

        <h3 style="margin-bottom:4px;">Repeated themes</h3>
        <ul style="margin-top:8px; line-height:1.7;">
            <li><strong>Simpler copy/headline wins people over (5 mentions).</strong> The most repeated point by far. Respondents who picked A specifically called out fewer words, an easier-to-read headline, and a clearer CTA as the deciding factor.</li>
            <li><strong>A's calmer color palette preferred over B's orange/red intensity (4 mentions).</strong> Several respondents said B's red buttons or orange/yellow combo felt "harsh" or "didn't feel right," and explicitly preferred A's more muted blue tone.</li>
            <li><strong>B explains what the collective actually does more clearly (3 mentions).</strong> Notably, this came from people who <em>chose</em> B &mdash; they felt A's simpler copy left the collective's purpose vague, while B's "pilot" / concrete language gave them more confidence in what they'd be signing up for.</li>
            <li><strong>B's illustration pattern and fuller color palette read as more "vibrant" and "abundant" (2 mentions).</strong> Tied to the diversity of plants/flowers the brand represents, per one respondent.</li>
        </ul>

        <h3 style="margin-bottom:4px; margin-top:20px;">One-off notes worth a look</h3>
        <ul style="margin-top:8px; line-height:1.7;">
            <li>Make A's CTA buttons stand out more &mdash; maybe white (1 mention).</li>
            <li>Consider more earth tones in the palette overall (1 mention).</li>
            <li>Suggestion to test copy and visual design as separate variables in a future round, rather than bundled together (1 mention).</li>
        </ul>
    </div>
</div>
</body>
</html>
