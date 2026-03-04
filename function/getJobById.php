<?php
/**
 * Récupère une seule offre d'emploi par son ID
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Vérifier que l'ID est fourni
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception('ID de l\'offre manquant');
    }
    
    $jobId = (int)$_GET['id'];
    
    // Vérifier si on doit filtrer par statut d'approbation (par défaut: oui)
    $checkApproved = isset($_GET['checkApproved']) ? $_GET['checkApproved'] === '1' : true;
    
    // Connexion à la base de données
    $db = getDatabaseConnection();
    
    // Construire la clause WHERE en fonction du filtre d'approbation
    $whereClause = "j.id = :id AND j.open = 1";
    if ($checkApproved) {
        $whereClause .= " AND j.approved = 1";
    }
    
    // Requête pour récupérer l'offre avec les informations de l'entreprise
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
            j.approved,
            j.activity_id,
            j.country_id,
            c.name as company_name,
            c.description as company_description,
            a.name as activity_name,
            a.fr_name as activity_fr_name,
            co.name as country_name,
            co.code as country_code,
            co.fr_name as country_fr_name
        FROM job j
        LEFT JOIN company c ON j.company_id = c.id
        LEFT JOIN activity a ON j.activity_id = a.id
        LEFT JOIN country co ON j.country_id = co.id
        WHERE $whereClause
    ";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $jobId, PDO::PARAM_INT);
    $stmt->execute();
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$job) {
        throw new Exception('Offre d\'emploi introuvable ou non disponible');
    }
    
    echo json_encode([
        'success' => true,
        'job' => $job
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
