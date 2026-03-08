<?php
/**
 * Met à jour le secteur d'activité (activity_id) d'une offre d'emploi
 */

session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Méthode non autorisée'
    ]);
    exit;
}

// Vérifier que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrator') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Accès refusé. Réservé aux administrateurs.'
    ]);
    exit;
}

try {
    // Lire les données JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['job_id']) || !isset($input['activity_id'])) {
        throw new Exception('Paramètres manquants: job_id et activity_id requis');
    }
    
    $jobId = (int)$input['job_id'];
    $activityId = $input['activity_id'] === null ? null : (int)$input['activity_id'];
    
    // Connexion à la base de données
    $db = getDatabaseConnection();
    
    // Vérifier que le job existe
    $checkQuery = "SELECT id FROM job WHERE id = :job_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':job_id', $jobId, PDO::PARAM_INT);
    $checkStmt->execute();
    
    if (!$checkStmt->fetch()) {
        throw new Exception('Offre d\'emploi introuvable');
    }
    
    // Vérifier que l'activité existe (si non null)
    if ($activityId !== null) {
        $activityCheckQuery = "SELECT id FROM activity WHERE id = :activity_id";
        $activityCheckStmt = $db->prepare($activityCheckQuery);
        $activityCheckStmt->bindValue(':activity_id', $activityId, PDO::PARAM_INT);
        $activityCheckStmt->execute();
        
        if (!$activityCheckStmt->fetch()) {
            throw new Exception('Secteur d\'activité introuvable');
        }
    }
    
    // Mettre à jour l'activity_id
    $updateQuery = "UPDATE job SET activity_id = :activity_id WHERE id = :job_id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindValue(':job_id', $jobId, PDO::PARAM_INT);
    
    if ($activityId === null) {
        $updateStmt->bindValue(':activity_id', null, PDO::PARAM_NULL);
    } else {
        $updateStmt->bindValue(':activity_id', $activityId, PDO::PARAM_INT);
    }
    
    $updateStmt->execute();
    
    // Récupérer les informations de l'activité mise à jour
    $activityName = 'Non spécifié';
    if ($activityId !== null) {
        $activityQuery = "SELECT fr_name FROM activity WHERE id = :activity_id";
        $activityStmt = $db->prepare($activityQuery);
        $activityStmt->bindValue(':activity_id', $activityId, PDO::PARAM_INT);
        $activityStmt->execute();
        $activityData = $activityStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($activityData) {
            $activityName = $activityData['fr_name'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Secteur d\'activité mis à jour avec succès',
        'activity_id' => $activityId,
        'activity_name' => $activityName
    ]);
    
} catch (PDOException $e) {
    error_log("Erreur updateJobActivity: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Erreur updateJobActivity: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
