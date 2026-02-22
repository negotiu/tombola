<?php
header('Content-Type: application/json');

// Soubor, kam se budou ukládat data (vytvoří se automaticky)
$dataFile = 'data.json';

// Přečtení dat (pro mobily i načtení stránky)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($dataFile)) {
        echo file_get_contents($dataFile);
    } else {
        echo '{}';
    }
    exit;
}

// Zápis nebo smazání dat (přijímá z index.html)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Smazání celé historie
    if (isset($input['action']) && $input['action'] === 'clear') {
        file_put_contents($dataFile, '{}');
        echo json_encode(['status' => 'cleared']);
        exit;
    }

    // Přidání nového výherce
    if (isset($input['ticket']) && isset($input['winner'])) {
        $currentData = [];
        if (file_exists($dataFile)) {
            $currentData = json_decode(file_get_contents($dataFile), true);
        }
        
        $currentData[$input['ticket']] = $input['winner'];
        file_put_contents($dataFile, json_encode($currentData));
        
        echo json_encode(['status' => 'success']);
        exit;
    }
}
?>