<?php
/**
 * Met à jour le statut d'une candidature
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/sendApplicationNotification.php';

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
        SELECT a.id, a.candidate_id, a.job_id
        FROM application a
        JOIN job j ON a.job_id = j.id
        WHERE a.id = :application_id AND j.company_id = :company_id
    ");
    $stmtCheck->execute([
        ':application_id' => $applicationId,
        ':company_id' => $companyId
    ]);
    
    $applicationData = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    
    if (!$applicationData) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Candidature non trouvée ou non autorisée'
        ]);
        exit;
    }
    
    $candidateId = $applicationData['candidate_id'];
    $jobId = $applicationData['job_id'];
    
    // Déterminer recruiter_validation selon le statut
    $recruiterValidation = null;
    if ($status === 'validate') {
        $recruiterValidation = 1; // Entreprise accepte
    } elseif ($status === 'rejected') {
        $recruiterValidation = 0; // Entreprise refuse
    }
    // Pour les autres statuts (pending, in-process, question), on ne change pas recruiter_validation
    
    // Mettre à jour le statut et recruiter_validation si nécessaire
    if ($recruiterValidation !== null) {
        if ($comment !== null) {
            $stmtUpdate = $db->prepare("
                UPDATE application
                SET status = :status, recruiter_validation = :recruiter_validation, comment = :comment, updated_at = NOW()
                WHERE id = :application_id
            ");
            $stmtUpdate->execute([
                ':status' => $status,
                ':recruiter_validation' => $recruiterValidation,
                ':comment' => $comment,
                ':application_id' => $applicationId
            ]);
        } else {
            $stmtUpdate = $db->prepare("
                UPDATE application
                SET status = :status, recruiter_validation = :recruiter_validation, updated_at = NOW()
                WHERE id = :application_id
            ");
            $stmtUpdate->execute([
                ':status' => $status,
                ':recruiter_validation' => $recruiterValidation,
                ':application_id' => $applicationId
            ]);
        }
    } else {
        // Si recruiter_validation n'est pas modifié
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
    }
    
    // Envoyer les emails de notification selon le statut
    $emailsSent = false;
    if ($status === 'validate' || $status === 'rejected') {
        error_log("📧 [updateApplicationStatus] Préparation envoi email pour statut: $status, candidateId: $candidateId, jobId: $jobId");
        
        $emailResult = sendApplicationNotification(
            $candidateId,
            $jobId,
            true,  // isCompanyResponse (c'est l'entreprise qui répond)
            ($status === 'validate'), // companyValidation (true si accepté, false si rejeté)
            true,  // candidateValidation (il a postulé, donc true)
            false, // isredflag
            $comment ?? '' // commentaire de l'entreprise
        );
        
        $emailsSent = $emailResult['success'] ?? false;
        
        error_log("📧 [updateApplicationStatus] Résultat envoi email: " . ($emailsSent ? '✅ succès' : '❌ échec'));
        if (!$emailsSent) {
            error_log('❌ [updateApplicationStatus] Erreur envoi email pour application #' . $applicationId . ': ' . json_encode($emailResult));
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Statut mis à jour avec succès',
        'emails_sent' => $emailsSent
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
