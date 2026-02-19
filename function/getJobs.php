<?php
/**
 * Récupère la liste de toutes les offres d'emploi avec leurs informations de base
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Connexion à la base de données
    $db = getDatabaseConnection();
    
    // Récupérer les paramètres de recherche
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $location = isset($_GET['location']) ? trim($_GET['location']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : 'approved'; // all, approved, pending, rejected (défaut: approved pour la page publique)
    
    // Requête pour récupérer toutes les offres avec leurs entreprises
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
            j.need_vehicle,
            j.work_condition,
            j.open,
            j.approved,
            c.name as company_name,
            c.description as company_description,
            c.domain as company_domain,
            c.hq_location as company_location
        FROM job j
        LEFT JOIN company c ON j.company_id = c.id
        WHERE 1=1
    ";
    
    // Ajouter les filtres de recherche si présents
    $params = [];
    
    // Filtre par statut d'approbation
    if ($status === 'approved') {
        $query .= " AND j.approved = 1";
    } elseif ($status === 'pending') {
        $query .= " AND j.approved IS NULL";
    } elseif ($status === 'rejected') {
        $query .= " AND j.approved = 0";
    }
    // Si 'all', on ne filtre pas
    
    if (!empty($keyword)) {
        $keywordParam = '%' . $keyword . '%';
        $query .= " AND (j.title LIKE :keyword1 OR j.description LIKE :keyword2 OR c.name LIKE :keyword3)";
        $params[':keyword1'] = $keywordParam;
        $params[':keyword2'] = $keywordParam;
        $params[':keyword3'] = $keywordParam;
    }
    
    if (!empty($location)) {
        $query .= " AND j.location LIKE :location";
        $params[':location'] = '%' . $location . '%';
    }
    
    $query .= " ORDER BY j.id DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater les données pour chaque offre
    foreach ($jobs as &$job) {
        // Formater la date de début
        if ($job['start_date']) {
            $job['start_date_formatted'] = date('d/m/Y', strtotime($job['start_date']));
        } else {
            $job['start_date_formatted'] = 'Non spécifiée';
        }
        
        // Formater le salaire
        if ($job['salary']) {
            $job['salary_formatted'] = number_format((float)$job['salary'], 0, ',', ' ') . ' €';
        } else {
            $job['salary_formatted'] = 'Non communiqué';
        }
    }
    
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
