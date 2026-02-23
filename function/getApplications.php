<?php
/**
 * Récupère la liste de toutes les candidatures avec les informations du candidat, du job et de la société
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Connexion à la base de données
    $db = getDatabaseConnection();
    
    // Récupérer les paramètres de recherche
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : 'all'; // all, pending, in-process, question, rejected, validate
    
    // Requête pour récupérer toutes les candidatures avec les informations associées
    $query = "
        SELECT 
            a.id as application_id,
            a.status,
            a.comment,
            a.recruiter_validation,
            a.candidate_validation,
            
            -- Informations candidat
            c.id as candidate_id,
            c.media_code as candidate_media_code,
            i.first_name,
            i.last_name,
            i.email as candidate_email,
            i.phone as candidate_phone,
            
            -- Informations job
            j.id as job_id,
            j.title as job_title,
            
            -- Informations société
            co.id as company_id,
            co.name as company_name,
            
            -- Contact société
            cont.email as company_email,
            cont.phone as company_phone,
            
            -- Account recruteur (associé au job via company)
            acc.email as recruiter_email
            
        FROM application a
        
        -- JOIN candidat
        LEFT JOIN candidate c ON a.candidate_id = c.id
        LEFT JOIN identity i ON c.identity_id = i.id
        
        -- JOIN job
        LEFT JOIN job j ON a.job_id = j.id
        
        -- JOIN société
        LEFT JOIN company co ON j.company_id = co.id
        LEFT JOIN contact cont ON co.contact_id = cont.id
        
        -- JOIN account recruteur (on récupère le premier account de la société)
        LEFT JOIN account acc ON acc.company_id = co.id AND acc.role = 'recruiter'
        
        WHERE 1=1
    ";
    
    // Ajouter les filtres de recherche si présents
    $params = [];
    
    // Filtre par statut
    if ($status !== 'all') {
        $query .= " AND a.status = :status";
        $params[':status'] = $status;
    }
    
    if (!empty($keyword)) {
        $keywordParam = '%' . $keyword . '%';
        $query .= " AND (
            i.first_name LIKE :keyword1 
            OR i.last_name LIKE :keyword2 
            OR j.title LIKE :keyword3 
            OR co.name LIKE :keyword4
        )";
        $params[':keyword1'] = $keywordParam;
        $params[':keyword2'] = $keywordParam;
        $params[':keyword3'] = $keywordParam;
        $params[':keyword4'] = $keywordParam;
    }
    
    $query .= " ORDER BY a.id DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Enrichir les données
    foreach ($applications as &$app) {
        // Construire l'URL de la photo du candidat
        if ($app['candidate_media_code']) {
            $app['candidate_photo_url'] = 'data/candidates/media/' . $app['candidate_media_code'] . '/photo.jpg';
            $app['candidate_cv_url'] = 'data/candidates/media/' . $app['candidate_media_code'] . '/cv.html';
        } else {
            $app['candidate_photo_url'] = '/media/image/icon/avatar/default.png';
            $app['candidate_cv_url'] = null;
        }
        
        // Les jobs n'ont pas de fichier HTML généré (pas de media_code)
        // L'URL pourrait pointer vers une page de détail du job si nécessaire
        $app['job_url'] = null;
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
