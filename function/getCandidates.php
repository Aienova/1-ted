<?php
/**
 * Récupère la liste de tous les candidats avec leurs informations de base
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
    
    // Requête pour récupérer tous les candidats avec leurs identités et demandes
    $query = "
        SELECT 
            c.id,
            c.media_code,
            c.approved,
            i.first_name,
            i.last_name,
            i.job_title,
            i.location,
            i.birthdate,
            i.email,
            d.contract_type,
            d.salary
        FROM candidate c
        LEFT JOIN identity i ON c.identity_id = i.id
        LEFT JOIN demand d ON c.demand_id = d.id
        WHERE 1=1
    ";
    
    // Ajouter les filtres de recherche si présents
    $params = [];
    
    // Filtre par statut d'approbation
    if ($status === 'approved') {
        $query .= " AND c.approved = 1";
    } elseif ($status === 'pending') {
        $query .= " AND c.approved IS NULL";
    } elseif ($status === 'rejected') {
        $query .= " AND c.approved = 0";
    }
    // Si 'all', on ne filtre pas
    
    if (!empty($keyword)) {
        $keywordParam = '%' . $keyword . '%';
        $query .= " AND (i.job_title LIKE :keyword1 OR i.first_name LIKE :keyword2 OR i.last_name LIKE :keyword3)";
        $params[':keyword1'] = $keywordParam;
        $params[':keyword2'] = $keywordParam;
        $params[':keyword3'] = $keywordParam;
    }
    
    if (!empty($location)) {
        $query .= " AND i.location LIKE :location";
        $params[':location'] = '%' . $location . '%';
    }
    
    $query .= " ORDER BY c.id DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculer l'âge pour chaque candidat
    foreach ($candidates as &$candidate) {
        if ($candidate['birthdate']) {
            $birthDate = new DateTime($candidate['birthdate']);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
            $candidate['age'] = $age;
        } else {
            $candidate['age'] = null;
        }
        
        // Construire l'URL de la photo
        if ($candidate['media_code']) {
            $candidate['photo_url'] = 'data/candidates/media/' . $candidate['media_code'] . '/photo.jpg';
        } else {
            $candidate['photo_url'] = 'data/candidates/media/default/photo.jpg';
        }
    }
    
    echo json_encode([
        'success' => true,
        'candidates' => $candidates,
        'count' => count($candidates)
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
