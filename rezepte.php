<?php
$pageTitle = "MUNdig - Rezepte";
$extraCss = "calculators"; // Reuse calc styles for consistency
include 'includes/header.php';
require_once 'includes/config.php';

// Fetch recipes
$recipes = DB::getTableData('Recipes');
?>

<div class="calc-container">
    <header style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 3rem; margin-bottom: 10px; background: linear-gradient(to right, #fff, #a1c4fd); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Rezepte</h1>
        <p style="opacity: 0.8;">Verwalte deine kulinarischen Meisterwerke.</p>
        <div style="margin-top: 20px;">
            <a href="index.php" style="color: #a1c4fd; text-decoration: none; margin-right: 20px;">
                <i class="fa-solid fa-arrow-left"></i> Dashboard
            </a>
            <a href="rezept_neu.php" class="google-btn" style="display: inline-flex; position: static;">
                <i class="fa-solid fa-plus" style="margin-right: 8px;"></i> Neues Rezept
            </a>
        </div>
    </header>

    <div class="tile-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
        <?php if (empty($recipes)): ?>
            <p style="text-align: center; grid-column: 1/-1; opacity: 0.5;">Noch keine Rezepte vorhanden.</p>
        <?php else: ?>
            <?php foreach ($recipes as $r): ?>
                <div class="tile" style="height: auto; min-height: 250px;">
                    <div class="tile-top">
                        <span style="font-size: 0.8rem; opacity: 0.6; display: block; margin-bottom: 5px;">
                            <?php echo htmlspecialchars($r['Type'] ?? 'Standard'); ?>
                        </span>
                        <h3><?php echo htmlspecialchars($r['Title']); ?></h3>
                    </div>
                    <p style="font-size: 0.9rem; margin-bottom: 15px;">
                        <?php echo htmlspecialchars(substr($r['Description'] ?? '', 0, 100)) . '...'; ?>
                    </p>
                    
                    <div style="margin-top: auto;">
                        <div style="color: #ffd700; margin-bottom: 10px;">
                            <?php 
                            $stars = intval($r['Difficulty'] ?? 1);
                            for($i=1; $i<=5; $i++) {
                                echo $i <= $stars ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                            }
                            ?>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            <?php 
                            $tags = explode(',', $r['Tags'] ?? '');
                            foreach($tags as $tag): if(trim($tag)):
                            ?>
                                <span style="background: rgba(161, 196, 253, 0.2); padding: 2px 8px; border-radius: 10px; font-size: 0.7rem;">
                                    #<?php echo htmlspecialchars(trim($tag)); ?>
                                </span>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
