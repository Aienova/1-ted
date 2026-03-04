<?php
/**
 * Retourne la liste des IDs d'offres d'emploi auxquelles le candidat connecté a déjà postulé
 */

header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Vérifier que l'utilisateur est connecté en tant que candidat
    if (!isset($_SESSION['accountId']) || $_SESSION['role'] !== 'candidate') {
        echo json_encode([
            'success' => true,
            'jobIds' => [] // Pas connecté en candidat = aucune candidature
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
            'jobIds' => []
        ]);
        exit;
    }
    
    // Récupérer tous les IDs d'offres auxquelles ce candidat a postulé
    $stmt = $db->prepare("
        SELECT DISTINCT app.job_id
        FROM application app
        WHERE app.candidate_id = :candidate_id
    ");
    
    $stmt->execute([
        ':candidate_id' => $candidateId
    ]);
    
    $jobIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    
    echo json_encode([
        'success' => true,
        'jobIds' => array_map('intval', $jobIds) // Convertir en entiers
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des candidatures',
        'details' => $e->getMessage()
    ]);
}
