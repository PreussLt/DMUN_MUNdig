<?php
/**
 * NocoDB Database Setup Script for MUNdig (v3)
 */

$nocoUrl = "http://localhost:8080";
$nocoToken = "krmdjfNKDfPW9D5iwifxM77eGOTO6sEMtjsY6eDF";

function nocoRequest($method, $endpoint, $data = null) {
    global $nocoUrl, $nocoToken;
    $url = $nocoUrl . $endpoint;
    
    $ch = curl_init($url);
    $headers = [
        "xc-token: $nocoToken",
        "Content-Type: application/json"
    ];
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'data' => json_decode($response, true),
        'raw' => $response
    ];
}

echo "Starting NocoDB Setup...\n";

// 1. Find Base
$resp = nocoRequest("GET", "/api/v1/db/meta/projects");
$baseId = null;
$list = $resp['data']['list'] ?? $resp['data'] ?? [];
foreach ($list as $base) {
    if (isset($base['title']) && $base['title'] === "MUNdig") {
        $baseId = $base['id'];
        break;
    }
}

// 2. Create Base if not exists
if (!$baseId) {
    echo "Creating Base 'MUNdig'...\n";
    $createResp = nocoRequest("POST", "/api/v1/db/meta/projects", [
        "title" => "MUNdig",
        "type" => "database"
    ]);
    if ($createResp['code'] > 299) {
        die("Error creating base: " . $createResp['raw'] . "\n");
    }
    $baseId = $createResp['data']['id'];
}
echo "Connected to Base: MUNdig ($baseId)\n";

// 3. Define Tables
$tables = [
    [
        "table_name" => "Recipes",
        "title" => "Rezepte",
        "columns" => [
            ["column_name" => "Title", "title" => "Titel", "uidt" => "SingleLineText", "pv" => true],
            ["column_name" => "Description", "title" => "Beschreibung", "uidt" => "LongText"],
            ["column_name" => "Instructions", "title" => "Zubereitung", "uidt" => "LongText"],
            ["column_name" => "PrepTime", "title" => "Vorbereitungszeit", "uidt" => "Number"]
        ]
    ],
    [
        "table_name" => "ShoppingList",
        "title" => "Einkaufsliste",
        "columns" => [
            ["column_name" => "Item", "title" => "Gegenstand", "uidt" => "SingleLineText", "pv" => true],
            ["column_name" => "Amount", "title" => "Menge", "uidt" => "Decimal"],
            ["column_name" => "Unit", "title" => "Einheit", "uidt" => "SingleLineText"],
            ["column_name" => "IsChecked", "title" => "Erledigt", "uidt" => "Checkbox"]
        ]
    ]
];

foreach ($tables as $t) {
    echo "Checking/Creating table: " . $t['title'] . "\n";
    $res = nocoRequest("POST", "/api/v1/db/meta/projects/$baseId/tables", $t);
    if ($res['code'] < 300) {
        echo "Successfully created " . $t['title'] . "\n";
    } else {
        echo "Table " . $t['title'] . " status: " . $res['code'] . ". (Response: " . substr($res['raw'], 0, 50) . ")\n";
    }
}

echo "\nSetup Complete. Dashboard is ready to use NocoDB.\n";
?>
