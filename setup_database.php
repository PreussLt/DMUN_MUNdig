<?php
/**
 * NocoDB Database Setup Script for MUNdig (v5 - Ingredients per Step)
 */

$nocoUrl = "http://localhost:8080";
$nocoToken = "krmdjfNKDfPW9D5iwifxM77eGOTO6sEMtjsY6eDF";

function nocoRequest($method, $endpoint, $data = null) {
    global $nocoUrl, $nocoToken;
    $url = $nocoUrl . $endpoint;
    $ch = curl_init($url);
    $headers = ["xc-token: $nocoToken", "Content-Type: application/json"];
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $httpCode, 'data' => json_decode($response, true), 'raw' => $response];
}

echo "Starting NocoDB Setup v5...\n";

// Find Base
$resp = nocoRequest("GET", "/api/v1/db/meta/projects");
$baseId = null;
$list = $resp['data']['list'] ?? $resp['data'] ?? [];
foreach ($list as $base) {
    if (isset($base['title']) && $base['title'] === "MUNdig") {
        $baseId = $base['id'];
        break;
    }
}
if (!$baseId) die("Error: Base MUNdig not found.\n");

// Update Table RecipeSteps to include ingredients info
$tables = [
    [
        "table_name" => "RecipeSteps",
        "title" => "Rezept-Schritte",
        "columns" => [
            ["column_name" => "RecipeID", "title" => "Rezept ID", "uidt" => "SingleLineText"],
            ["column_name" => "SortOrder", "title" => "Reihenfolge", "uidt" => "Number"],
            ["column_name" => "Instruction", "title" => "Anweisung", "uidt" => "LongText", "pv" => true],
            ["column_name" => "IngredientsInfo", "title" => "Zugehörige Zutaten", "uidt" => "LongText"] // For storing step-specific ingredients
        ]
    ]
];

foreach ($tables as $t) {
    echo "Updating table: " . $t['title'] . "\n";
    $res = nocoRequest("POST", "/api/v1/db/meta/projects/$baseId/tables", $t);
    echo "Status: " . $res['code'] . "\n";
}

echo "Database Update Complete.\n";
?>
