<?php
/**
 * Upload de la photo de profil du candidat
 * Remplace le fichier photo.jpg dans /data/candidates/media/[media_code]/
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
    if (!isset($_FILES['photo'])) {
        throw new Exception('Aucune photo fournie');
    }
    
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
    $file = $_FILES['photo'];
    
    // Vérifier les erreurs d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erreur lors de l\'upload du fichier');
    }
    
    // Vérifier le type de fichier
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Type de fichier non autorisé. Utilisez JPG, PNG ou GIF');
    }
    
    // Vérifier la taille (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('Fichier trop volumineux (max 5 MB)');
    }
    
    // Créer le dossier si nécessaire
    $mediaDir = __DIR__ . '/../data/candidates/media/' . $mediaCode;
    if (!file_exists($mediaDir)) {
        mkdir($mediaDir, 0755, true);
    }
    
    // Chemin de destination (toujours photo.jpg)
    $destination = $mediaDir . '/photo.jpg';
    
    // Si c'est une image PNG ou GIF, la convertir en JPG
    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
        if ($mimeType === 'image/png') {
            $image = imagecreatefrompng($file['tmp_name']);
        } else {
            $image = imagecreatefromgif($file['tmp_name']);
        }
        
        // Créer une image JPG
        imagejpeg($image, $destination, 90);
        imagedestroy($image);
    } else {
        // Pour les JPG, copier directement
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Erreur lors de la sauvegarde du fichier');
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Photo de profil mise à jour',
        'photo_url' => '/data/candidates/media/' . $mediaCode . '/photo.jpg?t=' . time()
    ]);
    
} catch (Exception $e) {
    error_log("Erreur uploadProfilePhoto: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
