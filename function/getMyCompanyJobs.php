<?php
/**
 * Récupère les offres d'emploi de l'entreprise connectée (API JSON)
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
            'error' => 'Accès réservé aux entreprises et recruteurs'
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
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;
    
    // Requête pour récupérer les offres
    $query = "
        SELECT 
            j.id,
            j.title,
            j.description,
            j.location,
            j.salary,
            j.contract_type,
            j.start_date,
            j.length,
            j.work_condition,
            j.need_vehicle,
            j.open,
            j.approved,
            j.priority,
            j.created_at,
            j.updated_at,
            
            -- Contact
            cnt.first_name as contact_first_name,
            cnt.last_name as contact_last_name,
            cnt.email as contact_email,
            cnt.phone as contact_phone,
            
            -- Statistiques candidatures
            (SELECT COUNT(*) FROM application WHERE job_id = j.id) as total_applications,
            (SELECT COUNT(*) FROM application WHERE job_id = j.id AND status = 'pending') as pending_applications
            
        FROM job j
        LEFT JOIN contact cnt ON j.contact_id = cnt.id
        WHERE j.company_id = :company_id
    ";
    
    $params = [':company_id' => $companyId];
    
    // Filtre par statut
    if ($status === 'open') {
        $query .= " AND j.open = 1";
    } elseif ($status === 'closed') {
        $query .= " AND j.open = 0";
    } elseif ($status === 'approved') {
        $query .= " AND j.approved = 1";
    } elseif ($status === 'pending') {
        $query .= " AND (j.approved IS NULL OR j.approved = 0)";
    }
    
    // Filtre par mot-clé
    if (!empty($keyword)) {
        $keywordParam = '%' . $keyword . '%';
        $query .= " AND (j.title LIKE :keyword1 OR j.description LIKE :keyword2 OR j.location LIKE :keyword3)";
        $params[':keyword1'] = $keywordParam;
        $params[':keyword2'] = $keywordParam;
        $params[':keyword3'] = $keywordParam;
    }
    
    $query .= " ORDER BY j.created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT :limit";
        $params[':limit'] = $limit;
    }
    
    $stmt = $db->prepare($query);
    
    foreach ($params as $key => $value) {
        if ($key === ':limit') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'jobs' => $jobs,
        'count' => count($jobs)
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
