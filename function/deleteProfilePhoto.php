<?php
/**
 * Suppression de la photo de profil du candidat
 */

session_start();
require_once 'connectionDatabase.php';

header('Content-Type: application/json');

// Vérifier que l'utilisateur est connecté en tant que candidat
if (!isset($_SESSION['accountId']) || $_SESSION['role'] !== 'candidate') {
    echo json_encode([
        'success' => false,
        'error' => 'Vous devez être connecté en tant que candidat'
    ]);
    exit;
}

try {
    $pdo = getDatabaseConnection();
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
    
    if (!$candidateId) {
        throw new Exception('ID candidat introuvable');
    }
    
    // Récupérer le media_code du candidat
    $stmt = $pdo->prepare("SELECT media_code FROM candidate WHERE id = ?");
    $stmt->execute([$candidateId]);
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$candidate || !$candidate['media_code']) {
        throw new Exception('Media code introuvable');
    }
    
    $mediaCode = $candidate['media_code'];
    $photoPath = __DIR__ . '/../data/candidates/media/' . $mediaCode . '/photo.jpg';
    
    // Supprimer le fichier s'il existe
    if (file_exists($photoPath)) {
        if (!unlink($photoPath)) {
            throw new Exception('Erreur lors de la suppression du fichier');
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Photo supprimée avec succès'
    ]);
    
} catch (Exception $e) {
    error_log("Erreur deleteProfilePhoto: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
