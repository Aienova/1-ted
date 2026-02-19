<?php
/**
 * Récupère les candidatures pour les offres de l'entreprise connectée
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
    
    // Récupérer les paramètres de filtrage
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : 'all';
    $jobId = isset($_GET['job_id']) ? intval($_GET['job_id']) : null;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;
    
    // Requête pour récupérer les candidatures
    $query = "
        SELECT 
            a.id as application_id,
            a.status,
            a.comment,
            a.created_at,
            
            -- Informations candidat
            c.id as candidate_id,
            c.media_code as candidate_media_code,
            i.first_name,
            i.last_name,
            i.email,
            i.phone,
            i.job_title as job_title_candidate,
            
            -- Informations offre
            j.id as job_id,
            j.title as job_title,
            
            -- Email compte candidat
            acc.email as candidate_email
            
        FROM application a
        
        -- JOIN job (filtrer par entreprise)
        JOIN job j ON a.job_id = j.id
        
        -- JOIN candidat
        LEFT JOIN candidate c ON a.candidate_id = c.id
        LEFT JOIN identity i ON c.identity_id = i.id
        
        -- JOIN account candidat
        LEFT JOIN account acc ON acc.candidate_id = c.id
        
        WHERE j.company_id = :company_id
    ";
    
    $params = [':company_id' => $companyId];
    
    // Filtre par offre spécifique
    if ($jobId) {
        $query .= " AND j.id = :job_id";
        $params[':job_id'] = $jobId;
    }
    
    // Filtre par statut
    if ($status !== 'all') {
        $query .= " AND a.status = :status";
        $params[':status'] = $status;
    }
    
    // Filtre par mot-clé
    if (!empty($keyword)) {
        $keywordParam = '%' . $keyword . '%';
        $query .= " AND (i.first_name LIKE :keyword1 OR i.last_name LIKE :keyword2 OR j.title LIKE :keyword3)";
        $params[':keyword1'] = $keywordParam;
        $params[':keyword2'] = $keywordParam;
        $params[':keyword3'] = $keywordParam;
    }
    
    $query .= " ORDER BY a.created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT :limit";
        $params[':limit'] = $limit;
    }
    
    $stmt = $db->prepare($query);
    
    foreach ($params as $key => $value) {
        if ($key === ':limit' || $key === ':job_id') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Enrichir avec les URLs de photo et CV
    foreach ($applications as &$app) {
        if (!empty($app['candidate_media_code'])) {
            $app['candidate_photo_url'] = "/data/candidates/media/{$app['candidate_media_code']}/photo.jpg";
            $app['candidate_cv_url'] = "/data/candidates/media/{$app['candidate_media_code']}/cv.html";
        } else {
            $app['candidate_photo_url'] = null;
            $app['candidate_cv_url'] = null;
        }
    }
    
    echo json_encode([
        'success' => true,
        'applications' => $applications,
        'count' => count($applications)
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
