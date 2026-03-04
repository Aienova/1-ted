<?php
header('Content-Type: application/json');
require_once 'connectionDatabase.php';
require_once 'getAvatar.php';

// Récupérer l'ID du candidat depuis les paramètres GET
$candidateId = isset($_GET['candidateId']) ? intval($_GET['candidateId']) : null;

if (!$candidateId) {
    echo json_encode([
        'success' => false,
        'error' => 'ID candidat manquant'
    ]);
    exit;
}

try {
    $avatarUrl = getAvatar($candidateId);
    
    echo json_encode([
        'success' => true,
        'avatarUrl' => $avatarUrl
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
