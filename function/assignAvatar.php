<?php

require_once 'connectionDatabase.php';

function assignAvatar($candidateId, $avatarUrl) {
    try {
        $db = getDatabaseConnection();
        
        // Mettre à jour l'avatar_url du candidat
        $stmt = $db->prepare("UPDATE candidate SET avatar_url = :avatarUrl WHERE id = :candidateId");
        $stmt->bindParam(':avatarUrl', $avatarUrl, PDO::PARAM_STR);
        $stmt->bindParam(':candidateId', $candidateId, PDO::PARAM_INT);
        $stmt->execute();
        
        return true;
    } catch (Exception $e) {
        error_log("Erreur assignAvatar: " . $e->getMessage());
        return false;
    }
}

// Si appelé en tant qu'API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    $candidateId = $input['candidateId'] ?? null;
    $avatarUrl = $input['avatarUrl'] ?? null;
    
    if (!$candidateId || !$avatarUrl) {
        echo json_encode([
            'success' => false,
            'error' => 'Paramètres manquants'
        ]);
        exit;
    }
    
    $result = assignAvatar($candidateId, $avatarUrl);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Avatar assigné avec succès'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Erreur lors de l\'assignation de l\'avatar'
        ]);
    }
    exit;
}
