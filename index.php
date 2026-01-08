<?php
$pageTitle = "MUNdig - Dashboard";
include 'includes/header.php';
?>

<div class="dashboard-container">
    <header>
        <img src="assets/img/dmunlogo.png" alt="DMUN Logo" class="app-logo">
        <h1>MUNdig</h1>
        <p>Deine OpenSource Rezept und Calculator APP</p>
    </header>

    <main class="tile-grid">
        <a href="rezepte.php" class="tile tile-recipes">
            <div class="tile-top">
                <i class="fa-solid fa-utensils"></i>
                <h3>Rezepte</h3>
            </div>
            <p>Entdecke und verwalte deine Lieblingsrezepte.</p>
        </a>

        <a href="reis-nudel-rechner.php" class="tile tile-calc" style="--tile-gradient: var(--list-gradient);">
            <div class="tile-top">
                <i class="fa-solid fa-bowl-rice"></i>
                <h3>Reis & Nudel</h3>
            </div>
            <p>Mengen für Reis, Nudeln und Salat buffet.</p>
        </a>

        <a href="pizza-rechner.php" class="tile tile-calc">
            <div class="tile-top">
                <i class="fa-solid fa-pizza-slice"></i>
                <h3>Pizza Rechner</h3>
            </div>
            <p>Optimale Pizza-Kombinationen berechnen.</p>
        </a>

        <a href="einkaufsliste.php" class="tile tile-list">
            <div class="tile-top">
                <i class="fa-solid fa-basket-shopping"></i>
                <h3>Einkaufsliste</h3>
            </div>
            <p>Vergiss nie wieder eine Zutat beim Einkauf.</p>
        </a>

        <a href="profile.php" class="tile tile-profile">
            <div class="tile-top">
                <i class="fa-solid fa-user-gear"></i>
                <h3>Profil</h3>
            </div>
            <p>Einstellungen und persönliche Daten.</p>
        </a>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
