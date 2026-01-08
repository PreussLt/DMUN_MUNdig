<?php
$pageTitle = "MUNdig - Reis & Nudel Rechner";
$extraCss = "calculators";
include 'includes/header.php';
?>

<div class="calc-container">
    <header style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 3rem; margin-bottom: 10px; background: linear-gradient(to right, #fff, #a1c4fd); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Reis & Nudel Rechner</h1>
        <p style="opacity: 0.8;">Mengenberechnung nach MUN-SH Standard.</p>
        <a href="index.php" style="color: #a1c4fd; text-decoration: none; margin-top: 20px; display: inline-block;">
            <i class="fa-solid fa-arrow-left"></i> Zurück zum Dashboard
        </a>
    </header>

    <section class="calc-card">
        <h2><i class="fa-solid fa-bowl-rice"></i> Berechnung</h2>
        <div class="row">
            <div class="input-group">
                <label for="people-count">Anzahl Personen</label>
                <input type="number" id="people-count" value="53" min="1" oninput="calculate()">
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
                <span class="value" id="res-salat">3</span>
                <span class="unit">Stk</span>
            </div>
        </div>

        <div style="margin-top: 40px;">
            <h3>Einkaufsliste Salat-Buffet</h3>
            <table class="ingredient-table">
                <thead>
                    <tr>
                        <th>Zutat</th>
                        <th>Menge</th>
                    </tr>
                </thead>
                <tbody id="salad-list"></tbody>
            </table>
        </div>
    </section>
</div>

<script>
function calculate() {
    const people = parseFloat(document.getElementById('people-count').value) || 0;
    const factor = people / 53;

    document.getElementById('res-reis').innerText = Math.round(people * 100).toLocaleString();
    document.getElementById('res-nudeln').innerText = Math.round(people * 125).toLocaleString();
    
    const ingredients = [
        { name: 'Eisbergsalat', amount: Math.ceil(3 * factor), unit: 'Stk' },
        { name: 'Gurken', amount: Math.ceil(2 * factor), unit: 'Stk' },
        { name: 'Paprika', amount: Math.ceil(2 * factor), unit: 'Stk' },
        { name: 'Tomaten', amount: Math.ceil(8 * factor), unit: 'Stk' },
        { name: 'Karotten', amount: Math.ceil(4 * factor), unit: 'Stk' },
        { name: 'Mais (Dose)', amount: Math.ceil(2 * factor), unit: 'Stk' },
        { name: 'Kidneybohnen', amount: Math.ceil(4 * factor), unit: 'Stk' },
        { name: 'Feta', amount: Math.ceil(2 * factor), unit: 'Stk' }
    ];

    document.getElementById('res-salat').innerText = ingredients[0].amount;

    let html = '';
    ingredients.forEach(i => {
        html += `<tr><td>${i.name}</td><td>${i.amount} ${i.unit}</td></tr>`;
    });
    document.getElementById('salad-list').innerHTML = html;
}
window.onload = calculate;
</script>

<?php include 'includes/footer.php'; ?>
