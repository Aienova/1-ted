<?php
/**
 * Met à jour le statut d'une candidature
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
    
    // Accepter à la fois les rôles 'recruiter' et 'company'
    if ($_SESSION['role'] !== 'recruiter' && $_SESSION['role'] !== 'company') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès réservé aux entreprises'
        ]);
        exit;
    }
    
    // Récupérer les données JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['application_id']) || !isset($input['status'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Paramètres manquants'
        ]);
        exit;
    }
    
    $applicationId = intval($input['application_id']);
    $status = trim($input['status']);
    $comment = isset($input['comment']) ? trim($input['comment']) : null;
    
    // Valider le statut
    $validStatuses = ['pending', 'in-process', 'question', 'rejected', 'validate'];
    if (!in_array($status, $validStatuses)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Statut invalide'
        ]);
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer l'ID de l'entreprise
    if (isset($_SESSION['company_id'])) {
        $companyId = $_SESSION['company_id'];
    } else {
        $stmt = $db->prepare("SELECT company_id FROM account WHERE id = :account_id");
        $stmt->execute([':account_id' => $_SESSION['accountId']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $companyId = $result['company_id'] ?? null;
        
        if ($companyId) {
            $_SESSION['company_id'] = $companyId;
        }
    }
    
    if (!$companyId) {
        echo json_encode([
            'success' => false,
            'error' => 'Aucune entreprise associée'
        ]);
        exit;
    }
    
    // Vérifier que la candidature appartient à une offre de l'entreprise
    $stmtCheck = $db->prepare("
        SELECT a.id
        FROM application a
        JOIN job j ON a.job_id = j.id
        WHERE a.id = :application_id AND j.company_id = :company_id
    ");
    $stmtCheck->execute([
        ':application_id' => $applicationId,
        ':company_id' => $companyId
    ]);
    
    if (!$stmtCheck->fetch()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Candidature non trouvée ou non autorisée'
        ]);
        exit;
    }
    
    // Mettre à jour le statut
    if ($comment !== null) {
        $stmtUpdate = $db->prepare("
            UPDATE application
            SET status = :status, comment = :comment, updated_at = NOW()
            WHERE id = :application_id
        ");
        $stmtUpdate->execute([
            ':status' => $status,
            ':comment' => $comment,
            ':application_id' => $applicationId
        ]);
    } else {
        $stmtUpdate = $db->prepare("
            UPDATE application
            SET status = :status, updated_at = NOW()
            WHERE id = :application_id
        ");
        $stmtUpdate->execute([
            ':status' => $status,
            ':application_id' => $applicationId
        ]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Statut mis à jour avec succès'
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
