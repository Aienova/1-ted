<?php
/**
 * Récupère les statistiques du candidat connecté
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Démarrer la session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Vérifier l'authentification
    if (!isset($_SESSION['accountId']) || !isset($_SESSION['role'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Non authentifié'
        ]);
        exit;
    }
    
    if ($_SESSION['role'] !== 'candidate') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès réservé aux candidats'
        ]);
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer l'ID du candidat
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
            'success' => false,
            'error' => 'Aucun profil candidat associé'
        ]);
        exit;
    }
    
    // Vérifier si le candidat a un CV
    $stmtCV = $db->prepare("SELECT media_code FROM candidate WHERE id = :id");
    $stmtCV->execute([':id' => $candidateId]);
    $candidate = $stmtCV->fetch(PDO::FETCH_ASSOC);
    $hasCV = !empty($candidate['media_code']);
    
    // Compter les candidatures par statut
    $stmtStats = $db->prepare("
        SELECT 
            COUNT(*) as total_applications,
            SUM(CASE WHEN status = 'in-process' OR status = 'question' THEN 1 ELSE 0 END) as in_process,
            SUM(CASE WHEN status = 'validate' THEN 1 ELSE 0 END) as validated,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
        FROM application
        WHERE candidate_id = :candidate_id
    ");
    $stmtStats->execute([':candidate_id' => $candidateId]);
    $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'has_cv' => $hasCV,
        'total_applications' => (int)$stats['total_applications'],
        'in_process' => (int)$stats['in_process'],
        'validated' => (int)$stats['validated'],
        'rejected' => (int)$stats['rejected'],
        'pending' => (int)$stats['pending']
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
