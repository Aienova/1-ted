<?php
/**
 * Récupère les détails d'une offre d'emploi
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    $jobId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if (!$jobId) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'ID de l\'offre manquant'
        ]);
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer les détails du job
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
            
            co.name as company_name,
            co.domain,
            co.hq_location as company_location
            
        FROM job j
        LEFT JOIN company co ON j.company_id = co.id
        WHERE j.id = :id AND j.open = 1
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $jobId]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$job) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Offre d\'emploi introuvable ou fermée'
        ]);
        exit;
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
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
