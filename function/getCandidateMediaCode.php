<?php
/**
 * Récupère le media_code d'un candidat par son ID
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Vérifier que l'ID est fourni
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception('ID du candidat manquant');
    }
    
    $candidateId = intval($_GET['id']);
    
    // Connexion à la base de données
    $db = getDatabaseConnection();
    
    // Requête pour récupérer le media_code
    $query = "SELECT media_code FROM candidate WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $candidateId, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        throw new Exception('Candidat introuvable');
    }
    
    if (empty($result['media_code'])) {
        throw new Exception('Media code non défini pour ce candidat');
    }
    
    echo json_encode([
        'success' => true,
        'media_code' => $result['media_code']
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
