<?php
/**
 * Récupère la liste de tous les secteurs d'activité
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    $db = getDatabaseConnection();
    
    // Récupérer toutes les activités triées par nom
    $query = "
        SELECT 
            id,
            name,
            fr_name,
            en_name,
            description
        FROM activity
        ORDER BY fr_name ASC
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'activities' => $activities,
        'count' => count($activities)
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
