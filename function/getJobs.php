<?php
/**
 * Récupère la liste de toutes les offres d'emploi avec leurs informations de base
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

/**
 * Sanitize a search input: strip HTML tags, trim, limit length.
 */
function sanitizeSearchInput(string $value, int $maxLength = 100): string {
    $value = strip_tags($value);
    $value = trim($value);
    $value = mb_substr($value, 0, $maxLength);
    return $value;
}

try {
    // Connexion à la base de données
    $db = getDatabaseConnection();

    // Récupérer et sanitariser les paramètres de recherche
    $keyword  = sanitizeSearchInput(isset($_GET['keyword'])  ? (string)$_GET['keyword']  : '');
    $location = sanitizeSearchInput(isset($_GET['location']) ? (string)$_GET['location'] : '');

    // Whitelist pour le statut
    $allowedStatuses = ['all', 'approved', 'pending', 'rejected'];
    $rawStatus = isset($_GET['status']) ? trim((string)$_GET['status']) : 'approved';
    $status = in_array($rawStatus, $allowedStatuses, true) ? $rawStatus : 'approved';
    
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
