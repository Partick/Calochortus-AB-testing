<?php
require_once __DIR__ . '/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if (hash_equals(RESULTS_PASSWORD, $password)) {
        $_SESSION['ab_test_authed'] = true;
        header('Location: results.php');
        exit;
    }
    $error = 'Incorrect password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Results login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrap">
    <div class="login-wrap card">
        <h1>Results login</h1>
        <p class="subtitle">Enter the results password to view responses.</p>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autofocus>
            </div>
            <button type="submit">View results</button>
        </form>
    </div>
</div>
</body>
</html>
