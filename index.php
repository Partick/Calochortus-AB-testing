<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Which homepage do you prefer?</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrap">
    <img class="logo" src="<?= htmlspecialchars(LOGO_IMAGE) ?>" alt="Calochortus Collective">

    <p class="intro">Dustin and Patrick have been busy launching a new business venture, and we want to get some feedback on our first one-pager website!</p>
    <p class="intro">Take a look at the designs and pick the one that piques your curiosity and / or makes you feel the most interested in clicking the button to contact us. Thank you in advance for lending your opinion!</p>

    <p class="disclaimer">Disclaimer: we won't share your personal information with anyone. We may selectively quote responses anonymously as part of a research summary or a presentation.</p>

    <div class="options">
        <div class="option-card">
            <div class="option-media">
                <img id="option-a-image" src="<?= htmlspecialchars(OPTION_A_IMAGE) ?>" alt="<?= htmlspecialchars(OPTION_A_LABEL) ?>">
                <button type="button" class="toggle-crop" data-target="option-a-image">Show full design</button>
            </div>
            <div class="option-body">
                <h2><?= htmlspecialchars(OPTION_A_LABEL) ?></h2>
                <form action="respond.php" method="get">
                    <input type="hidden" name="option" value="a">
                    <button type="submit">I prefer this one</button>
                </form>
            </div>
        </div>

        <div class="option-card">
            <div class="option-media">
                <img id="option-b-image" src="<?= htmlspecialchars(OPTION_B_IMAGE) ?>" alt="<?= htmlspecialchars(OPTION_B_LABEL) ?>">
                <button type="button" class="toggle-crop" data-target="option-b-image">Show full design</button>
            </div>
            <div class="option-body">
                <h2><?= htmlspecialchars(OPTION_B_LABEL) ?></h2>
                <form action="respond.php" method="get">
                    <input type="hidden" name="option" value="b">
                    <button type="submit">I prefer this one</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.toggle-crop').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var img = document.getElementById(btn.dataset.target);
        var expanded = img.classList.toggle('expanded');
        btn.textContent = expanded ? 'Show cropped design' : 'Show full design';
    });
});
</script>
</body>
</html>
