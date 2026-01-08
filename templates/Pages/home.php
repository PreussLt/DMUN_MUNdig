<?php
/**
 * @var \App\View\AppView $this
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DMUN MUNdig - Willkommen</title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?= $this->Html->css(['normalize.min', 'dashboard']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <div class="login-section">
        <a href="#" class="google-btn">
            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Logo">
            Mit Google anmelden
        </a>
    </div>

    <div class="dashboard-container">
        <header>
            <h1>DMUN MUNdig</h1>
            <p>Deine OpenSource Rezept und Calculator APP</p>
        </header>

        <main class="tile-grid">
            <a href="/rezepte" class="tile tile-recipes">
                <div class="tile-top">
                    <i class="fa-solid fa-utensils"></i>
                    <h3>Rezepte</h3>
                </div>
                <p>Entdecke und verwalte deine Lieblingsrezepte.</p>
            </a>

            <a href="/calculator" class="tile tile-calc">
                <div class="tile-top">
                    <i class="fa-solid fa-calculator"></i>
                    <h3>Calculator</h3>
                </div>
                <p>Präzise Berechnungen für deine Küche.</p>
            </a>

            <a href="/einkaufsliste" class="tile tile-list">
                <div class="tile-top">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <h3>Einkaufsliste</h3>
                </div>
                <p>Vergiss nie wieder eine Zutat beim Einkauf.</p>
            </a>

            <a href="/profile" class="tile tile-profile">
                <div class="tile-top">
                    <i class="fa-solid fa-user-gear"></i>
                    <h3>Profil</h3>
                </div>
                <p>Einstellungen und persönliche Daten.</p>
            </a>
        </main>
    </div>

    <?= $this->fetch('script') ?>
</body>
</html>
