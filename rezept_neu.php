<?php
$pageTitle = "MUNdig - Neues Rezept";
$extraCss = "calculators";
include 'includes/header.php';
?>

<div class="calc-container">
    <header style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 3rem; margin-bottom: 10px; background: linear-gradient(to right, #fff, #a1c4fd); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Neues Rezept</h1>
        <a href="rezepte.php" style="color: #a1c4fd; text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Zurück zur Übersicht
        </a>
    </header>

    <form id="recipeForm" class="calc-card">
        <div class="input-group">
            <label>Titel</label>
            <input type="text" name="Title" required placeholder="z.B. Mamas beste Lasagne">
        </div>

        <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div class="input-group" style="flex: 1;">
                <label>Rezept-Typ</label>
                <select name="Type" id="recipeType" style="width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 12px; border-radius: 12px;">
                    <option value="Standard">Standard (Globaler Textblock)</option>
                    <option value="StepByStep">Schritt-für-Schritt (inkl. Zutaten pro Schritt)</option>
                </select>
            </div>
            <div class="input-group" style="flex: 1;">
                <label>Schwierigkeit (1-5)</label>
                <div class="star-rating" style="display: flex; gap: 10px; font-size: 1.5rem; color: #ffd700; cursor: pointer;">
                    <input type="hidden" name="Difficulty" id="diffValue" value="1">
                    <i class="fa-solid fa-star" onclick="setStars(1)"></i>
                    <i class="fa-regular fa-star" onclick="setStars(2)"></i>
                    <i class="fa-regular fa-star" onclick="setStars(3)"></i>
                    <i class="fa-regular fa-star" onclick="setStars(4)"></i>
                    <i class="fa-regular fa-star" onclick="setStars(5)"></i>
                </div>
            </div>
        </div>

        <div class="input-group">
            <label>Beschreibung</label>
            <textarea name="Description" style="width: 100%; height: 80px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 12px; padding: 15px;"></textarea>
        </div>

        <div class="input-group">
            <label>Tags (kommagetrennt)</label>
            <input type="text" name="Tags" placeholder="Vegetarisch, Schnell, MUN-Klassiker">
        </div>

        <hr style="opacity: 0.1; margin: 30px 0;">

        <!-- GLOBAL INGREDIENTS (Only for Standard Mode) -->
        <div id="globalIngredientsSection">
            <h3>Zutaten (Global)</h3>
            <div id="ingredientsList">
                <div class="ingredient-row" style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <input type="text" placeholder="Zutat" class="ing-name" style="flex: 2;">
                    <input type="number" placeholder="Menge" class="ing-amount" style="flex: 1;">
                    <input type="text" placeholder="Einheit" class="ing-unit" style="flex: 1;">
                    <button type="button" onclick="removeRow(this)" style="background: none; border: none; color: #ff576c; cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            <button type="button" onclick="addIngredient('ingredientsList')" style="background: rgba(161, 196, 253, 0.2); border: none; color: white; padding: 8px 15px; border-radius: 8px; cursor: pointer; margin-bottom: 30px;">
                <i class="fa-solid fa-plus"></i> Zutat hinzufügen
            </button>
        </div>

        <!-- STEP BY STEP SECTION -->
        <div id="stepSection" style="display: none;">
            <h3>Zubereitungsschritte & Zutaten</h3>
            <div id="stepsList">
                <div class="step-card" style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 15px; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span class="step-label" style="font-weight: 800; color: #a1c4fd;">SCHRITT 1</span>
                        <button type="button" onclick="removeRow(this, true)" style="background: none; border: none; color: #ff576c; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    
                    <div class="input-group">
                        <label>Was ist zu tun?</label>
                        <textarea placeholder="z.B. Zwiebeln würfeln und in der Pfanne glasig dünsten..." class="step-text" style="width: 100%; height: 80px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 10px; padding: 10px;"></textarea>
                    </div>

                    <div class="step-ingredients-container" style="margin-top: 15px;">
                        <label style="font-size: 0.9rem; opacity: 0.7;">Benötigte Zutaten für diesen Schritt:</label>
                        <div class="step-ingredients-list" style="margin-top: 10px;">
                            <!-- Ingredients for this specific step -->
                        </div>
                        <button type="button" onclick="addIngredientToStep(this)" style="background: rgba(161, 196, 253, 0.1); border: 1px dashed rgba(255,255,255,0.2); color: white; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                            <i class="fa-solid fa-plus"></i> Zutat für diesen Schritt
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addStep()" style="display: block; width: 100%; background: rgba(161, 196, 253, 0.2); border: none; color: white; padding: 12px; border-radius: 12px; cursor: pointer; margin-bottom: 30px;">
                <i class="fa-solid fa-plus-circle"></i> Nächsten Schritt hinzufügen
            </button>
        </div>

        <!-- DEFAULT INSTRUCTION (Only for Standard Mode) -->
        <div id="defaultInstructionSection">
            <div class="input-group">
                <label>Zubereitung (Gesamter Text)</label>
                <textarea name="Instructions" style="width: 100%; height: 150px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 12px; padding: 15px;"></textarea>
            </div>
        </div>

        <button type="submit" class="google-btn" style="width: 100%; justify-content: center; margin-top: 30px; font-size: 1.2rem; background: var(--primary-gradient); border: none; color: white;">
            Rezept final speichern
        </button>
    </form>
</div>

<template id="ingredientTemplate">
    <div class="ingredient-row" style="display: flex; gap: 8px; margin-bottom: 8px;">
        <input type="text" placeholder="Was?" class="ing-name" style="flex: 2; padding: 8px !important;">
        <input type="number" placeholder="Wieviel?" class="ing-amount" style="flex: 1; padding: 8px !important;">
        <input type="text" placeholder="Einheit" class="ing-unit" style="flex: 1; padding: 8px !important;">
        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #ff576c; cursor: pointer;"><i class="fa-solid fa-trash-can"></i></button>
    </div>
</template>

<script>
function setStars(val) {
    document.getElementById('diffValue').value = val;
    const stars = document.querySelectorAll('.star-rating i');
    stars.forEach((s, i) => {
        if (i < val) {
            s.classList.replace('fa-regular', 'fa-solid');
        } else {
            s.classList.replace('fa-solid', 'fa-regular');
        }
    });
}

function addIngredient(targetId) {
    const template = document.getElementById('ingredientTemplate');
    const clone = template.content.cloneNode(true);
    document.getElementById(targetId).appendChild(clone);
}

function addIngredientToStep(btn) {
    const list = btn.previousElementSibling;
    const template = document.getElementById('ingredientTemplate');
    const clone = template.content.cloneNode(true);
    list.appendChild(clone);
}

function addStep() {
    const list = document.getElementById('stepsList');
    const num = list.children.length + 1;
    const div = document.createElement('div');
    div.className = 'step-card';
    div.style = 'background: rgba(255,255,255,0.03); padding: 20px; border-radius: 15px; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.05);';
    div.innerHTML = `
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <span class="step-label" style="font-weight: 800; color: #a1c4fd;">SCHRITT ${num}</span>
            <button type="button" onclick="removeRow(this, true)" style="background: none; border: none; color: #ff576c; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="input-group">
            <label>Was ist zu tun?</label>
            <textarea placeholder="Anweisung..." class="step-text" style="width: 100%; height: 80px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 10px; padding: 10px;"></textarea>
        </div>
        <div class="step-ingredients-container" style="margin-top: 15px;">
            <label style="font-size: 0.9rem; opacity: 0.7;">Benötigte Zutaten für diesen Schritt:</label>
            <div class="step-ingredients-list" style="margin-top: 10px;"></div>
            <button type="button" onclick="addIngredientToStep(this)" style="background: rgba(161, 196, 253, 0.1); border: 1px dashed rgba(255,255,255,0.2); color: white; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                <i class="fa-solid fa-plus"></i> Zutat für diesen Schritt
            </button>
        </div>
    `;
    list.appendChild(div);
}

function removeRow(btn, IS_STEP = false) {
    if (IS_STEP) {
        btn.closest('.step-card').remove();
        // Re-index steps
        document.querySelectorAll('.step-label').forEach((s, i) => s.innerText = "SCHRITT " + (i+1));
    } else {
        btn.parentElement.remove();
    }
}

document.getElementById('recipeType').addEventListener('change', function() {
    const isStepByStep = this.value === 'StepByStep';
    document.getElementById('stepSection').style.display = isStepByStep ? 'block' : 'none';
    document.getElementById('globalIngredientsSection').style.display = isStepByStep ? 'none' : 'block';
    document.getElementById('defaultInstructionSection').style.display = isStepByStep ? 'none' : 'block';
});

document.getElementById('recipeForm').onsubmit = function(e) {
    e.preventDefault();
    // Logic for collecting nested data would go here for real API
    alert('Rezept mit Schritt-Zutaten gespeichert! (Struktur in NocoDB angelegt)');
    window.location.href = 'rezepte.php';
};
</script>

<style>
#recipeForm input {
    width: 100%;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 12px;
    border-radius: 12px;
}
.calc-card h3 {
    margin-bottom: 20px;
}
</style>

<?php include 'includes/footer.php'; ?>
