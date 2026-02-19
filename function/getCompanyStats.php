<?php
/**
 * Récupère les statistiques de l'entreprise connectée
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
    
    // Vérifier que c'est une entreprise/recruteur (accepter les deux rôles)
    if ($_SESSION['role'] !== 'recruiter' && $_SESSION['role'] !== 'company') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès réservé aux entreprises'
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
            'error' => 'Aucune entreprise associée à ce compte'
        ]);
        exit;
    }
    
    // Compter les offres d'emploi
    $stmtJobs = $db->prepare("
        SELECT 
            COUNT(*) as total_jobs,
            SUM(CASE WHEN open = 1 THEN 1 ELSE 0 END) as open_jobs,
            SUM(CASE WHEN approved = 1 THEN 1 ELSE 0 END) as approved_jobs
        FROM job
        WHERE company_id = :company_id
    ");
    $stmtJobs->execute([':company_id' => $companyId]);
    $jobsStats = $stmtJobs->fetch(PDO::FETCH_ASSOC);
    
    // Compter les candidatures
    $stmtApplications = $db->prepare("
        SELECT 
            COUNT(*) as total_applications,
            SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN a.status = 'in-process' THEN 1 ELSE 0 END) as in_process,
            SUM(CASE WHEN a.status = 'question' THEN 1 ELSE 0 END) as question,
            SUM(CASE WHEN a.status = 'validate' THEN 1 ELSE 0 END) as validated,
            SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM application a
        JOIN job j ON a.job_id = j.id
        WHERE j.company_id = :company_id
    ");
    $stmtApplications->execute([':company_id' => $companyId]);
    $applicationsStats = $stmtApplications->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'total_jobs' => (int)$jobsStats['total_jobs'],
        'open_jobs' => (int)$jobsStats['open_jobs'],
        'approved_jobs' => (int)$jobsStats['approved_jobs'],
        'total_applications' => (int)$applicationsStats['total_applications'],
        'pending_applications' => (int)$applicationsStats['pending'],
        'in_process_applications' => (int)$applicationsStats['in_process'],
        'question_applications' => (int)$applicationsStats['question'],
        'validated_applications' => (int)$applicationsStats['validated'],
        'rejected_applications' => (int)$applicationsStats['rejected']
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
