<?php
/**
 * Récupère les candidatures d'un candidat connecté
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
    
    // Vérifier que c'est un candidat
    if ($_SESSION['role'] !== 'candidate') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès réservé aux candidats'
        ]);
        exit;
    }
    
    // Connexion à la base de données
    $db = getDatabaseConnection();
    
    // Récupérer l'ID du candidat depuis la session
    // Si candidate_id n'est pas en session, le récupérer depuis la BDD
    if (isset($_SESSION['candidate_id'])) {
        $candidateId = $_SESSION['candidate_id'];
    } else {
        // Récupérer le candidate_id depuis account
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
            'success' => false,
            'error' => 'Aucun profil candidat associé à ce compte'
        ]);
        exit;
    }
    
    // Récupérer les paramètres de recherche
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : 'all';
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;
    
    // Requête pour récupérer les candidatures du candidat
    $query = "
        SELECT 
            a.id as application_id,
            a.status,
            a.comment,
            a.created_at,
            
            -- Informations job
            j.id as job_id,
            j.title as job_title,
            
            -- Informations société
            co.id as company_id,
            co.name as company_name,
            
            -- Contact société (visible seulement si sélectionné)
            CASE 
                WHEN a.status IN ('validate', 'question') 
                THEN cont.email 
                ELSE NULL 
            END as company_email,
            
            CASE 
                WHEN a.status IN ('validate', 'question') 
                THEN cont.phone 
                ELSE NULL 
            END as company_phone,
            
            -- Email recruteur (visible seulement si sélectionné)
            CASE 
                WHEN a.status IN ('validate', 'question') 
                THEN acc.email 
                ELSE NULL 
            END as recruiter_email
            
        FROM application a
        
        -- JOIN job
        LEFT JOIN job j ON a.job_id = j.id
        
        -- JOIN société
        LEFT JOIN company co ON j.company_id = co.id
        LEFT JOIN contact cont ON co.contact_id = cont.id
        
        -- JOIN account recruteur
        LEFT JOIN account acc ON acc.company_id = co.id AND acc.role = 'recruiter'
        
        WHERE a.candidate_id = :candidate_id
    ";
    
    // Ajouter les filtres si présents
    $params = [':candidate_id' => $candidateId];
    
    // Filtre par statut
    if ($status !== 'all') {
        $query .= " AND a.status = :status";
        $params[':status'] = $status;
    }
    
    // Filtre par mot-clé
    if (!empty($keyword)) {
        $keywordParam = '%' . $keyword . '%';
        $query .= " AND (j.title LIKE :keyword1 OR co.name LIKE :keyword2)";
        $params[':keyword1'] = $keywordParam;
        $params[':keyword2'] = $keywordParam;
    }
    
    $query .= " ORDER BY a.created_at DESC";
    
    // Ajouter une limite si spécifiée
    if ($limit) {
        $query .= " LIMIT :limit";
        $params[':limit'] = $limit;
    }
    
    $stmt = $db->prepare($query);
    
    // Bind des paramètres
    foreach ($params as $key => $value) {
        if ($key === ':limit') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
