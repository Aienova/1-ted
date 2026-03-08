<?php
/**
 * Annulation de candidature par le candidat
 * Met à jour candidate_validation = 0 et envoie un email de notification à l'entreprise
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/sendApplicationNotification.php';

try {
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['token']) || !isset($_SESSION['role'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Non autorisé. Veuillez vous connecter.'
        ]);
        exit;
    }
    
    // Vérifier que c'est bien un candidat
    if ($_SESSION['role'] !== 'candidate') {
        echo json_encode([
            'success' => false,
            'error' => 'Seuls les candidats peuvent annuler leurs candidatures.'
        ]);
        exit;
    }
    
    // Récupérer les données JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!isset($data['application_id'])) {
        echo json_encode([
            'success' => false,
            'error' => 'ID de candidature manquant.'
        ]);
        exit;
    }
    
    $applicationId = (int)$data['application_id'];
    $comment = isset($data['comment']) ? trim($data['comment']) : '';
    
    // Connexion à la base de données
    $pdo = getDatabaseConnection();
    
    // Récupérer les informations de la candidature et vérifier qu'elle appartient bien au candidat connecté
    $stmt = $pdo->prepare("
        SELECT 
            a.id,
            a.candidate_id,
            a.job_id,
            a.candidate_validation,
            a.recruiter_validation,
            a.status
        FROM application a
        JOIN account acc ON acc.candidate_id = a.candidate_id
        WHERE a.id = :application_id 
        AND acc.token = :token
    ");
    
    $stmt->execute([
        ':application_id' => $applicationId,
        ':token' => $_SESSION['token']
    ]);
    
    $application = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$application) {
        echo json_encode([
            'success' => false,
            'error' => 'Candidature introuvable ou vous n\'êtes pas autorisé à l\'annuler.'
        ]);
        exit;
    }
    
    // Vérifier que la candidature n'est pas déjà annulée
    if ($application['candidate_validation'] == 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Cette candidature est déjà annulée.'
        ]);
        exit;
    }
    
    // Commencer une transaction
    $pdo->beginTransaction();
    
    try {
        // Mettre à jour la candidature (candidate_validation = 0)
        $updateStmt = $pdo->prepare("
            UPDATE application 
            SET candidate_validation = 0,
                status = 'cancelled',
                comment = :comment,
                updated_at = NOW()
            WHERE id = :application_id
        ");
        
        $updateStmt->execute([
            ':comment' => $comment,
            ':application_id' => $applicationId
        ]);
        
        // Valider la transaction
        $pdo->commit();
        
        // Envoyer l'email de notification à l'entreprise (CAS 4)
        $emailResult = sendApplicationNotification(
            $application['candidate_id'],  // candidateId
            $application['job_id'],         // jobId
            false,                          // isCompanyResponse (c'est le candidat qui agit)
            false,                          // companyValidation (non applicable ici)
            false,                          // candidateValidation (le candidat retire sa candidature)
            false,                          // isredflag
            $comment                        // comment
        );
        
        // Retourner le résultat
        echo json_encode([
            'success' => true,
            'message' => 'Candidature annulée avec succès',
            'email_sent' => $emailResult['success'],
            'email_details' => $emailResult
        ]);
        
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    error_log("Erreur cancelApplication: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de l\'annulation de la candidature: ' . $e->getMessage()
    ]);
}
