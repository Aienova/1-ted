<?php
/**
 * Vérifie si le candidat connecté a déjà postulé à une offre d'emploi
 * Retourne un JSON avec le statut
 */

header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Récupérer l'ID de l'offre depuis les paramètres GET
    $offerId = $_GET['offer_id'] ?? null;
    
    if (!$offerId) {
        echo json_encode([
            'success' => false,
            'applied' => false,
            'error' => 'ID de l\'offre manquant'
        ]);
        exit;
    }
    
    // Vérifier si l'utilisateur est connecté en tant que candidat
    if (!isset($_SESSION['accountId']) || $_SESSION['role'] !== 'candidate') {
        echo json_encode([
            'success' => true,
            'applied' => false
        ]);
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer le candidate_id depuis la session ou depuis la table account
    if (isset($_SESSION['candidate_id'])) {
        $candidateId = $_SESSION['candidate_id'];
    } else {
        $stmt = $db->prepare("SELECT candidate_id FROM account WHERE id = :account_id");
        $stmt->execute([':account_id' => $_SESSION['accountId']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $candidateId = $result['candidate_id'] ?? null;
        
        if ($candidateId) {
            $_SESSION['candidate_id'] = $candidateId;
        }
    }
    
    if (!$candidateId) {
        echo json_encode([
            'success' => true,
            'applied' => false
        ]);
        exit;
    }
    
    // Vérifier si une candidature existe
    $stmt = $db->prepare("
        SELECT 1
        FROM application app
        WHERE app.job_id = :job_id AND app.candidate_id = :candidate_id
        LIMIT 1
    ");
    
    $stmt->execute([
        ':job_id' => $offerId,
        ':candidate_id' => $candidateId
    ]);
    
    $hasApplied = $stmt->fetchColumn() !== false;
    
    echo json_encode([
        'success' => true,
        'applied' => $hasApplied
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'applied' => false,
        'error' => 'Erreur lors de la vérification',
        'details' => $e->getMessage()
    ]);
}
