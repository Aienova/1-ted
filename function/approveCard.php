<?php
/**
 * Fonction pour approuver ou refuser un candidat ou une offre d'emploi
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Vérifier la méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }
    
    // Récupérer les données POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    $type = $data['type'] ?? ''; // 'candidate' ou 'job'
    $id = $data['id'] ?? 0;
    $approved = $data['approved'] ?? null; // 1 pour approuver, 0 pour refuser
    
    // Validation
    if (!in_array($type, ['candidate', 'job'])) {
        throw new Exception('Type invalide');
    }
    
    if (!$id || !is_numeric($id)) {
        throw new Exception('ID invalide');
    }
    
    if ($approved !== 0 && $approved !== 1) {
        throw new Exception('Statut d\'approbation invalide');
    }
    
    // Connexion à la base de données
    $db = getDatabaseConnection();
    
    // Préparer la requête selon le type
    $query = "UPDATE {$type} SET approved = :approved WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':approved', $approved, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    $success = $stmt->execute();
    
    if ($success) {
        $statusText = $approved === 1 ? 'approuvé' : 'refusé';
        $typeText = $type === 'candidate' ? 'Le candidat' : 'L\'offre d\'emploi';
        
        echo json_encode([
            'success' => true,
            'message' => "{$typeText} a été {$statusText} avec succès",
            'id' => $id,
            'approved' => $approved
        ]);
    } else {
        throw new Exception('Erreur lors de la mise à jour');
    }
    
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
?>
