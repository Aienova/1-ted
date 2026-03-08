<?php
/**
 * Mise à jour du niveau de priorité du candidat
 */

session_start();
require_once 'connectionDatabase.php';

header('Content-Type: application/json');

// Vérifier que l'utilisateur est connecté (candidat ou admin)
if (!isset($_SESSION['accountId']) || !isset($_SESSION['role'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Vous devez être connecté'
    ]);
    exit;
}

$role = $_SESSION['role'];

// Debug: Logger le rôle pour vérifier sa valeur
error_log("DEBUG updateCandidatePriority - Role: " . $role);

// Vérifier les permissions (admin ou administrator)
if ($role !== 'candidate' && $role !== 'admin' && $role !== 'administrator') {
    echo json_encode([
        'success' => false,
        'error' => 'Permissions insuffisantes',
        'debug_role' => $role
    ]);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['priority'])) {
        throw new Exception('Le niveau de priorité est requis');
    }
    
    $priority = intval($data['priority']);
    
    // Valider que la priorité est entre 0 et 4 (0 = aucune priorité)
    if ($priority < 0 || $priority > 4) {
        throw new Exception('Le niveau de priorité doit être entre 0 et 4');
    }
    
    $pdo = getDatabaseConnection();
    $candidateId = null;
    
    // Si c'est un admin, il peut spécifier l'ID du candidat
    if (($role === 'admin' || $role === 'administrator') && isset($data['candidate_id'])) {
        $candidateId = intval($data['candidate_id']);
    } 
    // Si c'est un candidat, récupérer son propre ID
    else if ($role === 'candidate') {
        $candidateId = $_SESSION['candidate_id'] ?? null;
        
        // Si pas dans la session, récupérer depuis la table account
        if (!$candidateId) {
            $stmt = $pdo->prepare("SELECT candidate_id FROM account WHERE id = ?");
            $stmt->execute([$_SESSION['accountId']]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($account && $account['candidate_id']) {
                $candidateId = $account['candidate_id'];
                $_SESSION['candidate_id'] = $candidateId;
            }
        }
    } else {
        throw new Exception('ID candidat manquant');
    }
    
    if (!$candidateId) {
        throw new Exception('ID candidat introuvable');
    }
    
    // Mettre à jour la priorité du candidat (utiliser NULL si priority = 0)
    $priorityValue = ($priority === 0) ? null : $priority;
    $stmt = $pdo->prepare("UPDATE candidate SET priority = ? WHERE id = ?");
    $stmt->execute([$priorityValue, $candidateId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Niveau de recherche mis à jour avec succès',
        'priority' => $priority,
        'candidate_id' => $candidateId
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
