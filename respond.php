<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/votes.php';

$option = isset($_GET['option']) ? strtolower($_GET['option']) : '';
if ($option !== 'a' && $option !== 'b') {
    header('Location: index.php');
    exit;
}

if (has_already_voted()) {
    render_already_voted();
}

$label = $option === 'a' ? OPTION_A_LABEL : OPTION_B_LABEL;
$image = $option === 'a' ? OPTION_A_IMAGE : OPTION_B_IMAGE;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tell us why - <?= htmlspecialchars($label) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrap">
    <a class="back" href="index.php">&larr; Back to both options</a>

    <div class="preview">
        <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($label) ?>">
        <div>
            <h1>You chose: <?= htmlspecialchars($label) ?></h1>
            <p class="subtitle">Great choice! A couple of quick questions before you go.</p>
        </div>
    </div>

    <div class="card">
        <form action="submit.php" method="post">
            <input type="hidden" name="option" value="<?= htmlspecialchars($option) ?>">

            <div class="field">
                <label for="email">Email address <span class="hint">(optional)</span></label>
                <input type="email" id="email" name="email" placeholder="you@example.com">
                <p class="hint">Only needed if you're open to us following up.</p>
            </div>

            <div class="field">
                <label>Would it be ok to follow up and ask about your response?</label>
                <div class="radio-group">
                    <label><input type="radio" name="follow_up_ok" value="yes" required> Yes</label>
                    <label><input type="radio" name="follow_up_ok" value="no" required> No</label>
                </div>
            </div>

            <div class="field">
                <label for="reason">Why did you choose this option?</label>
                <textarea id="reason" name="reason" placeholder="What made this one stand out to you?"></textarea>
            </div>

            <button type="submit">Submit my response</button>
        </form>
    </div>
</div>
</body>
</html>
