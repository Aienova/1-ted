<?php
/**
 * Crée une nouvelle candidature pour un candidat sur une offre d'emploi
 */

header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/sendApplicationNotification.php';

try {
    // Vérifier que l'utilisateur est connecté en tant que candidat
    if (!isset($_SESSION['accountId']) || $_SESSION['role'] !== 'candidate') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Vous devez être connecté en tant que candidat'
        ]);
        exit;
    }
    
    // Récupérer les données POST
    $data = json_decode(file_get_contents('php://input'), true);
    $jobId = $data['job_id'] ?? null;
    
    if (!$jobId) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'ID de l\'offre manquant'
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
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Profil candidat non trouvé'
        ]);
        exit;
    }
    
    // Vérifier si le candidat a déjà postulé à cette offre
    $checkStmt = $db->prepare("
        SELECT id FROM application 
        WHERE candidate_id = :candidate_id AND job_id = :job_id
    ");
    $checkStmt->execute([
        ':candidate_id' => $candidateId,
        ':job_id' => $jobId
    ]);
    
    if ($checkStmt->fetch()) {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'error' => 'Vous avez déjà postulé à cette offre'
        ]);
        exit;
    }
    
    // Vérifier que le candidat est approuvé
    $approvalStmt = $db->prepare("SELECT approved FROM candidate WHERE id = :candidate_id");
    $approvalStmt->execute([':candidate_id' => $candidateId]);
    $approvalResult = $approvalStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$approvalResult || $approvalResult['approved'] != 1) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Votre CV doit être approuvé avant de postuler'
        ]);
        exit;
    }
    
    // Créer la candidature
    $insertStmt = $db->prepare("
        INSERT INTO application (candidate_id, job_id, status, recruiter_validation, candidate_validation) 
        VALUES (:candidate_id, :job_id, 'pending', 0, 1)
    ");
    
    $insertStmt->execute([
        ':candidate_id' => $candidateId,
        ':job_id' => $jobId
    ]);
    
    $applicationId = $db->lastInsertId();
    
    // Récupérer l'email du candidat pour l'afficher dans le message de confirmation
    $emailStmt = $db->prepare("
        SELECT acc.email 
        FROM account acc 
        WHERE acc.candidate_id = :candidate_id
    ");
    $emailStmt->execute([':candidate_id' => $candidateId]);
    $emailData = $emailStmt->fetch(PDO::FETCH_ASSOC);
    $candidateEmail = $emailData['email'] ?? '';
    
    // Envoyer les emails de notification
    // isCompanyResponse = false (candidat qui postule)
    // candidateValidation = true (le candidat donne son accord)
    $emailResult = sendApplicationNotification(
        $candidateId, 
        $jobId, 
        false,  // isCompanyResponse
        false,  // companyValidation (pas encore répondu)
        true,   // candidateValidation (candidat postule)
        false,  // isredflag
        ''      // comment
    );
    
    // Log des résultats d'envoi d'emails
    if ($emailResult['success']) {
        $sentDetails = $emailResult['emails_sent'] ?? [];
        error_log('✅ Emails candidature #' . $applicationId . ': ' . json_encode($sentDetails));
    } else {
        error_log('❌ Erreur envoi emails candidature #' . $applicationId . ': ' . ($emailResult['error'] ?? 'Inconnu'));
    }
    
    // Vérifier si au moins un email a été envoyé
    $atLeastOneEmailSent = false;
    if (isset($emailResult['emails_sent']) && is_array($emailResult['emails_sent'])) {
        foreach ($emailResult['emails_sent'] as $emailType => $sent) {
            if ($sent === true) {
                $atLeastOneEmailSent = true;
                break;
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Candidature envoyée avec succès',
        'application_id' => $applicationId,
        'candidate_email' => $candidateEmail,
        'emails_sent' => $atLeastOneEmailSent,
        'emails_sent_error_details' => $emailResult['error'] ?? "Aucun détail d'erreur disponible",
        'email_details' => $emailResult['emails_sent'] ?? []
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la création de la candidature',
        'details' => $e->getMessage()
    ]);
}