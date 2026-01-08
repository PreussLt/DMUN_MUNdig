<?php
$pageTitle = "MUNdig - Calculatoren";
$extraCss = "calculators";
include 'includes/header.php';
?>

<div class="calc-container">
    <header style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 3rem; margin-bottom: 10px; background: linear-gradient(to right, #fff, #a1c4fd); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Küchen-Calculatoren</h1>
        <p style="opacity: 0.8;">Präzise Mengenberechnung basierend auf der MUN-SH Erfahrung.</p>
        <a href="index.php" style="color: #a1c4fd; text-decoration: none; margin-top: 20px; display: inline-block;">
            <i class="fa-solid fa-arrow-left"></i> Zurück zum Dashboard
        </a>
    </header>

    <!-- Reis & Nudeln Rechner -->
    <section class="calc-card" id="reis-nudeln">
        <h2><i class="fa-solid fa-bowl-rice"></i> Reis & Nudeln Rechner</h2>
        <div class="row" style="display: flex; gap: 20px;">
            <div class="column" style="flex: 1;">
                <div class="input-group">
                    <label for="people-count">Anzahl Personen</label>
                    <input type="number" id="people-count" value="53" min="1" oninput="calculateReisNudeln()">
                </div>
            </div>
        </div>

        <div class="results-grid">
            <div class="result-item">
                <span class="label">Reis (trocken)</span>
                <span class="value" id="res-reis">0</span>
                <span class="unit">g</span>
            </div>
            <div class="result-item">
                <span class="label">Nudeln (trocken)</span>
                <span class="value" id="res-nudeln">0</span>
                <span class="unit">g</span>
            </div>
            <div class="result-item">
                <span class="label">Salat (Eisberg)</span>
                <span class="value" id="res-salat">0</span>
                <span class="unit">Stk</span>
            </div>
            <div class="result-item">
                <span class="label">Gurken</span>
                <span class="value" id="res-gurken">0</span>
                <span class="unit">Stk</span>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <h3>Detaillierte Einkaufsliste (Salat)</h3>
            <table class="ingredient-table">
                <thead>
                    <tr>
                        <th>Zutat</th>
                        <th>Menge</th>
                    </tr>
                </thead>
                <tbody id="salad-details">
                    <!-- Dynamic details -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- Pizza Rechner -->
    <section class="calc-card" id="pizza-rechner">
        <h2><i class="fa-solid fa-pizza-slice"></i> Pizza Rechner</h2>
        <div class="row" style="display: flex; gap: 20px;">
            <div class="column" style="flex: 1;">
                <div class="input-group">
                    <label for="pizza-people">Anzahl Personen</label>
                    <input type="number" id="pizza-people" value="53" min="1" oninput="calculatePizza()">
                </div>
            </div>
            <div class="column" style="flex: 1;">
                <div class="input-group">
                    <label for="pizza-size">Größe Party-Pizza (cm²)</label>
                    <input type="number" id="pizza-size" value="2400" oninput="calculatePizza()">
                </div>
            </div>
        </div>

        <div class="results-grid">
            <div class="result-item">
                <span class="label">Benötigte Fläche</span>
                <span class="value" id="res-area">0</span>
                <span class="unit">cm²</span>
            </div>
            <div class="result-item">
                <span class="label">Anzahl Party-Pizzen</span>
                <span class="value" id="res-pizzas">0</span>
                <span class="unit">Stk</span>
            </div>
            <div class="result-item">
                <span class="label">Davon Vegan (ca.)</span>
                <span class="value" id="res-vegan">0</span>
                <span class="unit">Stk</span>
            </div>
        </div>
    </section>
</div>

<script>
function calculateReisNudeln() {
    const people = parseFloat(document.getElementById('people-count').value) || 0;
    
    // Values from Excel/Logic
    const ricePerPerson = 100; 
    const noodlePerPerson = 125;
    
    document.getElementById('res-reis').innerText = Math.round(people * ricePerPerson).toLocaleString();
    document.getElementById('res-nudeln').innerText = Math.round(people * noodlePerPerson).toLocaleString();
    
    const factor = people / 53;
    
    const salads = Math.ceil(3 * factor);
    const cucumbers = Math.ceil(2 * factor);
    const peppers = Math.ceil(2 * factor);
    const tomatoes = Math.ceil(8 * factor);
    
    document.getElementById('res-salat').innerText = salads;
    document.getElementById('res-gurken').innerText = cucumbers;
    
    const details = [
        { name: 'Eisbergsalat', amount: salads + ' Stk' },
        { name: 'Gurken', amount: cucumbers + ' Stk' },
        { name: 'Paprika', amount: peppers + ' Stk' },
        { name: 'Tomaten', amount: tomatoes + ' Stk' },
        { name: 'Mais (Dose)', amount: Math.ceil(2 * factor) + ' Stk' },
        { name: 'Kidneybohnen (Dose)', amount: Math.ceil(4 * factor) + ' Stk' }
    ];
    
    let html = '';
    details.forEach(d => {
        html += `<tr><td>${d.name}</td><td>${d.amount}</td></tr>`;
    });
    document.getElementById('salad-details').innerHTML = html;
}

function calculatePizza() {
    const people = parseFloat(document.getElementById('pizza-people').value) || 0;
    const pizzaSizeArea = parseFloat(document.getElementById('pizza-size').value) || 2400;
    
    const areaPerPerson = 560;
    const totalArea = people * areaPerPerson;
    const numPizzas = totalArea / pizzaSizeArea;
    const vegan = numPizzas * (3/16); 
    
    document.getElementById('res-area').innerText = Math.round(totalArea).toLocaleString();
    document.getElementById('res-pizzas').innerText = numPizzas.toFixed(1);
    document.getElementById('res-vegan').innerText = Math.ceil(vegan);
}

window.onload = function() {
    calculateReisNudeln();
    calculatePizza();
};
</script>

<style>
.unit {
    font-size: 0.9rem;
    opacity: 0.6;
    margin-left: 5px;
}
</style>

<?php include 'includes/footer.php'; ?>
