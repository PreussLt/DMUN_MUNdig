<?php
/**
 * MUNdig Database Configuration & API Wrapper
 */

define('NOCO_URL', 'http://localhost:8080');
define('NOCO_TOKEN', 'krmdjfNKDfPW9D5iwifxM77eGOTO6sEMtjsY6eDF');

// Base ID wird nach dem Setup hier eingetragen oder dynamisch geladen
// Für die erste Verwendung lassen wir es dynamisch suchen (Cache in Session/File empfohlen)
define('NOCO_BASE_TITLE', 'MUNdig');

class DB {
    private static $baseId = null;

    public static function request($method, $endpoint, $data = null) {
        $ch = curl_init(NOCO_URL . $endpoint);
        $headers = [
            "xc-token: " . NOCO_TOKEN,
            "Content-Type: application/json"
        ];
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    public static function getBaseId() {
        if (self::$baseId) return self::$baseId;
        
        // Suche Base ID anhand des Titels
        // 1. Get Workspace (wir nehmen den ersten verfügbaren)
        $workspaces = self::request("GET", "/api/v1/workspaces");
        if (!$workspaces || empty($workspaces['list'])) return null;
        
        $wsId = $workspaces['list'][0]['hash'] ?? $workspaces['list'][0]['id'];
        
        // 2. Suche in Bases
        $bases = self::request("GET", "/api/v1/workspaces/$wsId/bases");
        foreach ($bases['list'] as $base) {
            if ($base['title'] === NOCO_BASE_TITLE) {
                self::$baseId = $base['id'];
                return self::$baseId;
            }
        }
        return null;
    }

    public static function getTableData($tableName, $params = []) {
        $baseId = self::getBaseId();
        if (!$baseId) return [];
        
        $queryString = http_build_query($params);
        $endpoint = "/api/v1/db/data/noco/$baseId/$tableName" . ($queryString ? "?$queryString" : "");
        
        $resp = self::request("GET", $endpoint);
        return $resp['list'] ?? [];
    }
}
?>
