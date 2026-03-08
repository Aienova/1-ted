<?php

session_start();

require_once __DIR__ . '/connectionDatabase.php';

/**
 * Supprime une offre d'emploi et toutes ses données liées (cascade delete)
 * @param int $jobId Job ID to delete
 * @param int $companyId Company ID (for verification)
 * @return array ['success' => bool, 'message' => string]
 */
function deleteJob($jobId, $companyId = null) {
    try {
        $pdo = getDatabaseConnection();
        $pdo->beginTransaction();
        
        // 1. Récupérer les données du job
        $jobQuery = "SELECT * FROM job WHERE id = :id";
        $jobStmt = $pdo->prepare($jobQuery);
        $jobStmt->bindValue(':id', $jobId, PDO::PARAM_INT);
        $jobStmt->execute();
        $job = $jobStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$job) {
            $pdo->rollBack();
            return ['success' => false, 'message' => 'Offre d\'emploi introuvable'];
        }
        
        // 2. Vérifier que l'entreprise connectée est bien propriétaire de cette offre
        if ($companyId !== null && $job['company_id'] != $companyId) {
            $pdo->rollBack();
            return ['success' => false, 'message' => 'Vous n\'êtes pas autorisé à supprimer cette offre'];
        }
        
        // 2. Supprimer toutes les candidatures liées à cette offre
        $deleteApplicationsQuery = "DELETE FROM application WHERE job_id = :job_id";
        $deleteApplicationsStmt = $pdo->prepare($deleteApplicationsQuery);
        $deleteApplicationsStmt->bindValue(':job_id', $jobId, PDO::PARAM_INT);
        $deleteApplicationsStmt->execute();
        $deletedApplications = $deleteApplicationsStmt->rowCount();
        
        // 3. Supprimer le requirement si présent
        if (!empty($job['requirement_id'])) {
            $deleteRequirementQuery = "DELETE FROM requirement WHERE id = :id";
            $deleteRequirementStmt = $pdo->prepare($deleteRequirementQuery);
            $deleteRequirementStmt->bindValue(':id', $job['requirement_id'], PDO::PARAM_INT);
            $deleteRequirementStmt->execute();
        }
        
        // 4. Supprimer le fichier HTML statique si présent
        if (!empty($job['media_code'])) {
            $htmlPath = __DIR__ . '/../data/jobs/' . $job['media_code'] . '.html';
            if (file_exists($htmlPath)) {
                unlink($htmlPath);
            }
        }
        
        // 5. Supprimer l'offre d'emploi elle-même
        $deleteJobQuery = "DELETE FROM job WHERE id = :id";
        $deleteJobStmt = $pdo->prepare($deleteJobQuery);
        $deleteJobStmt->bindValue(':id', $jobId, PDO::PARAM_INT);
        $deleteJobStmt->execute();
        
        $pdo->commit();
        
        $message = 'Offre d\'emploi supprimée avec succès';
        if ($deletedApplications > 0) {
            $message .= ' (' . $deletedApplications . ' candidature(s) supprimée(s))';
        }
        
        return ['success' => true, 'message' => $message];
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error deleting job $jobId: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erreur lors de la suppression: ' . $e->getMessage()];
    }
}

// Point d'entrée pour les requêtes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['job_id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de l\'offre manquant']);
        exit;
    }
    
    // Récupérer le company_id depuis la session si connecté
    $companyId = null;
    if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'company') {
        // Récupérer le company_id lié au compte
        try {
            $pdo = getDatabaseConnection();
            $accountQuery = "SELECT company_id FROM account WHERE id = :account_id";
            $accountStmt = $pdo->prepare($accountQuery);
            $accountStmt->bindValue(':account_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $accountStmt->execute();
            $account = $accountStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($account && !empty($account['company_id'])) {
                $companyId = $account['company_id'];
            }
        } catch (Exception $e) {
            error_log("Error fetching company_id: " . $e->getMessage());
        }
    }
    
    $result = deleteJob($input['job_id'], $companyId);
    echo json_encode($result);
    exit;
}
