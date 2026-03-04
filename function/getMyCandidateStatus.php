<?php
/**
 * Récupère le statut d'approbation du candidat connecté
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
    
    // Vérifier que c'est bien un candidat
    if ($_SESSION['role'] !== 'candidate') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès réservé aux candidats'
        ]);
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer le candidat et son statut d'approbation
    $stmt = $db->prepare("
        SELECT 
            c.id,
            c.approved,
            c.media_code,
            i.first_name,
            i.last_name,
            i.job_title
        FROM account a
        LEFT JOIN candidate c ON a.candidate_id = c.id
        LEFT JOIN identity i ON c.identity_id = i.id
        WHERE a.id = :account_id
    ");
    
    $stmt->execute([':account_id' => $_SESSION['accountId']]);
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$candidate || !$candidate['id']) {
        echo json_encode([
            'success' => false,
            'error' => 'Aucun profil candidat associé à ce compte'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'candidate' => [
            'id' => $candidate['id'],
            'approved' => $candidate['approved'] !== null ? (int)$candidate['approved'] : null,
            'is_approved' => $candidate['approved'] == 1,
            'media_code' => $candidate['media_code'],
            'first_name' => $candidate['first_name'],
            'last_name' => $candidate['last_name'],
            'job_title' => $candidate['job_title']
        ]
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
