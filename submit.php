<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/votes.php';

function render_error(string $message): void {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Something went wrong</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="wrap">
        <a class="back" href="index.php">&larr; Back to both options</a>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    </div>
    </body>
    </html>
    <?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$option = strtolower(trim($_GET['option'] ?? $_POST['option'] ?? ''));
$email = trim($_POST['email'] ?? '');
$followUpOk = $_POST['follow_up_ok'] ?? '';
$reason = trim($_POST['reason'] ?? '');

if ($option !== 'a' && $option !== 'b') {
    render_error('Missing or invalid option. Please start over.');
}

if (has_already_voted()) {
    render_already_voted();
}

if ($followUpOk !== 'yes' && $followUpOk !== 'no') {
    render_error('Please go back and answer the follow-up question.');
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    render_error('That email address doesn\'t look right. Please go back and check it.');
}

$label = $option === 'a' ? OPTION_A_LABEL : OPTION_B_LABEL;

$stmt = get_db()->prepare('
    INSERT INTO responses (option_chosen, email, follow_up_ok, reason, ip_address, created_at)
    VALUES (:option_chosen, :email, :follow_up_ok, :reason, :ip_address, :created_at)
');
$stmt->execute([
    ':option_chosen' => $option,
    ':email' => $email !== '' ? $email : null,
    ':follow_up_ok' => $followUpOk,
    ':reason' => $reason !== '' ? $reason : null,
    ':ip_address' => client_ip(),
    ':created_at' => gmdate('Y-m-d H:i:s'),
]);

mark_voted();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Thank you!</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrap">
    <div class="thanks">
        <div class="check">🎉</div>
        <h1>Thanks for your opinion!</h1>
        <p class="subtitle">We really appreciate you comparing <?= htmlspecialchars($label) ?> for us.</p>
        <?php if ($followUpOk === 'yes'): ?>
            <div class="followup-note">We will be in touch</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
