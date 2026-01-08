<?php
$pageTitle = "MUNdig - Pizza Rechner";
$extraCss = "calculators";
include 'includes/header.php';
?>

<div class="calc-container">
    <header style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 3rem; margin-bottom: 10px; background: linear-gradient(to right, #fff, #a1c4fd); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Pizza Rechner</h1>
        <p style="opacity: 0.8;">Flächenberechnung & optimale Zusammensetzung.</p>
        <a href="index.php" style="color: #a1c4fd; text-decoration: none; margin-top: 20px; display: inline-block;">
            <i class="fa-solid fa-arrow-left"></i> Zurück zum Dashboard
        </a>
    </header>

    <!-- Bedarf -->
    <section class="calc-card">
        <h2><i class="fa-solid fa-users"></i> 1. Bedarf ermitteln</h2>
        <div class="row" style="display: flex; gap: 20px;">
            <div class="input-group" style="flex: 1;">
                <label for="people">Anzahl Personen</label>
                <input type="number" id="people" value="53" min="1" oninput="calculate()">
            </div>
            <div class="input-group" style="flex: 1;">
                <label for="area-per-person">Fläche pro Person (cm²)</label>
                <input type="number" id="area-per-person" value="560" oninput="calculate()">
            </div>
        </div>
        <div class="result-item" style="margin-top: 20px;">
            <span class="label">Gesamt benötigte Fläche</span>
            <span class="value" id="total-area">0</span>
            <span class="unit">cm²</span>
        </div>
    </section>

    <!-- Pizza Typen -->
    <section class="calc-card">
        <h2><i class="fa-solid fa-pizza-slice"></i> 2. Pizza Größen (variabel)</h2>
        <table class="ingredient-table">
            <thead>
                <tr>
                    <th>Typ</th>
                    <th>Größe (cm)</th>
                    <th>Fläche (cm²)</th>
                    <th>Benötigte Anzahl</th>
                </tr>
            </thead>
            <tbody id="pizza-types-body">
                <!-- Round Pizzas -->
                <tr>
                    <td>Rund (Standard)</td>
                    <td><input type="number" step="0.5" value="32" class="size-input" oninput="updatePizzaTypes()"> ø</td>
                    <td class="type-area">0</td>
                    <td class="type-count">0</td>
                </tr>
                <tr>
                    <td>Rund (Groß)</td>
                    <td><input type="number" step="0.5" value="40" class="size-input" oninput="updatePizzaTypes()"> ø</td>
                    <td class="type-area">0</td>
                    <td class="type-count">0</td>
                </tr>
                <!-- Rectangular Pizzas -->
                <tr>
                    <td>Familien-Pizza</td>
                    <td>
                        <input type="number" step="0.5" value="45" class="size-input-w" oninput="updatePizzaTypes()"> x 
                        <input type="number" step="0.5" value="32" class="size-input-h" oninput="updatePizzaTypes()">
                    </td>
                    <td class="type-area">0</td>
                    <td class="type-count">0</td>
                </tr>
                <tr>
                    <td>Party-Pizza</td>
                    <td>
                        <input type="number" step="0.5" value="60" class="size-input-w" oninput="updatePizzaTypes()"> x 
                        <input type="number" step="0.5" value="40" class="size-input-h" oninput="updatePizzaTypes()">
                    </td>
                    <td class="type-area">0</td>
                    <td class="type-count">0</td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- Empfehlung -->
    <section class="calc-card">
        <h2><i class="fa-solid fa-lightbulb"></i> Optimale Zusammensetzung (Mix)</h2>
        <p style="margin-bottom: 20px; opacity: 0.8;">Vorschlag basierend auf Party-Pizzen als Basis:</p>
        <div id="combination-result" style="font-size: 1.2rem; line-height: 1.6;">
            <!-- Generierte Empfehlung -->
        </div>
    </section>
</div>

<script>
function calculate() {
    const people = parseFloat(document.getElementById('people').value) || 0;
    const areaPp = parseFloat(document.getElementById('area-per-person').value) || 0;
    const totalArea = people * areaPp;
    document.getElementById('total-area').innerText = Math.round(totalArea).toLocaleString();
    updatePizzaTypes();
}

function updatePizzaTypes() {
    const totalArea = (parseFloat(document.getElementById('people').value) || 0) * 
                      (parseFloat(document.getElementById('area-per-person').value) || 0);
    
    const rows = document.querySelectorAll('#pizza-types-body tr');
    
    rows.forEach((row, index) => {
        let area = 0;
        if (index < 2) { // Round
            const diameter = parseFloat(row.querySelector('.size-input').value) || 0;
            area = Math.PI * Math.pow(diameter / 2, 2);
        } else { // Rectangular
            const w = parseFloat(row.querySelector('.size-input-w').value) || 0;
            const h = parseFloat(row.querySelector('.size-input-h').value) || 0;
            area = w * h;
        }
        
        row.querySelector('.type-area').innerText = Math.round(area).toLocaleString();
        const count = area > 0 ? Math.ceil(totalArea / area) : 0;
        row.querySelector('.type-count').innerText = count;
    });

    generateCombination(totalArea);
}

function generateCombination(totalArea) {
    // Basic greedy optimization
    const partyW = parseFloat(document.querySelector('#pizza-types-body tr:nth-child(4) .size-input-w').value) || 60;
    const partyH = parseFloat(document.querySelector('#pizza-types-body tr:nth-child(4) .size-input-h').value) || 40;
    const partyArea = partyW * partyH;

    const roundD = parseFloat(document.querySelector('#pizza-types-body tr:nth-child(1) .size-input').value) || 32;
    const roundArea = Math.PI * Math.pow(roundD / 2, 2);

    let partyCount = Math.floor(totalArea / partyArea);
    let remaining = totalArea - (partyCount * partyArea);
    let roundCount = Math.ceil(remaining / roundArea);

    let html = `
        <div style="display: flex; gap: 40px; align-items: center;">
            <div class="result-item" style="flex: 1;">
                <span class="label">Party-Pizzen</span>
                <span class="value">${partyCount}</span>
            </div>
            <div style="font-size: 2rem; opacity: 0.3;">+</div>
            <div class="result-item" style="flex: 1;">
                <span class="label">Runde Pizzen (Standard)</span>
                <span class="value">${roundCount}</span>
            </div>
        </div>
        <p style="margin-top: 20px; font-size: 0.9rem; opacity: 0.6;">
            Hinweis: Ergibt eine Gesamtfläche von ${Math.round(partyCount * partyArea + roundCount * roundArea).toLocaleString()} cm² 
            (Bedarf: ${Math.round(totalArea).toLocaleString()} cm²).
        </p>
    `;
    document.getElementById('combination-result').innerHTML = html;
}

window.onload = calculate;
</script>

<style>
.size-input, .size-input-w, .size-input-h {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 5px;
    border-radius: 4px;
    width: 60px;
    text-align: center;
}
.type-area, .type-count {
    font-weight: 600;
    color: #a1c4fd;
}
</style>

<?php include 'includes/footer.php'; ?>
