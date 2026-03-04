<?php
/**
 * Mettre à jour la priorité d'une offre d'emploi
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Démarrer la session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Vérifier l'authentification
    if (!isset($_SESSION['accountId'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Non authentifié'
        ]);
        exit;
    }
    
    // Lire les données JSON
    $jsonInput = file_get_contents('php://input');
    $data = json_decode($jsonInput, true);
    
    $jobId = $data['job_id'] ?? null;
    $priority = $data['priority'] ?? 0;
    
    if (!$jobId) {
        echo json_encode([
            'success' => false,
            'error' => 'job_id requis'
        ]);
        exit;
    }
    
    // Valider la priorité (0-3)
    $priority = (int)$priority;
    if ($priority < 0 || $priority > 3) {
        echo json_encode([
            'success' => false,
            'error' => 'Priorité invalide (doit être entre 0 et 3)'
        ]);
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Vérifier que l'utilisateur a accès à cette offre (optionnel mais recommandé)
    // On vérifie que l'offre appartient à la company de l'utilisateur
    if (isset($_SESSION['role']) && ($_SESSION['role'] === 'company' || $_SESSION['role'] === 'recruiter')) {
        $checkStmt = $db->prepare("
            SELECT j.id 
            FROM job j
            INNER JOIN account a ON a.company_id = j.company_id
            WHERE j.id = :job_id AND a.id = :account_id
        ");
        $checkStmt->execute([
            ':job_id' => $jobId,
            ':account_id' => $_SESSION['accountId']
        ]);
        
        if (!$checkStmt->fetch()) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'Accès non autorisé à cette offre'
            ]);
            exit;
        }
    }
    
    // Mettre à jour la priorité
    $stmt = $db->prepare("UPDATE job SET priority = :priority WHERE id = :job_id");
    $stmt->execute([
        ':priority' => $priority,
        ':job_id' => $jobId
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Priorité mise à jour avec succès',
        'job_id' => $jobId,
        'priority' => $priority
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
