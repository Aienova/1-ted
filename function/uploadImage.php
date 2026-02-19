<?php
/**
 * Upload d'images pour les photos de profil et QR codes
 * Stocke les images dans /data/candidates/media/[media_code]/
 */

header('Content-Type: application/json');

// Vérifier la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Méthode non autorisée'
    ]);
    exit;
}

try {
    // Récupérer le media_code
    $mediaCode = $_POST['mediaCode'] ?? null;
    
    if (!$mediaCode) {
        echo json_encode([
            'success' => false,
            'error' => 'mediaCode requis'
        ]);
        exit;
    }
    
    // Créer le dossier du candidat si nécessaire
    $mediaDir = __DIR__ . '/../data/candidates/media/' . $mediaCode;
    if (!file_exists($mediaDir)) {
        if (!mkdir($mediaDir, 0755, true)) {
            echo json_encode([
                'success' => false,
                'error' => 'Impossible de créer le dossier media'
            ]);
            exit;
        }
    }
    
    $uploadedFiles = [];
    
    // Uploader la photo si présente
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoPath = $mediaDir . '/photo.jpg';
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $uploadedFiles['photo'] = '../candidates/media/' . $mediaCode . '/photo.jpg';
        } else {
            throw new Exception('Impossible d\'uploader la photo');
        }
    }
    
    // Uploader le QR code si présent
    if (isset($_FILES['qrcode']) && $_FILES['qrcode']['error'] === UPLOAD_ERR_OK) {
        $qrcodePath = $mediaDir . '/qr-code.png';
        if (move_uploaded_file($_FILES['qrcode']['tmp_name'], $qrcodePath)) {
            $uploadedFiles['qrcode'] = '../candidates/media/' . $mediaCode . '/qr-code.png';
        } else {
            throw new Exception('Impossible d\'uploader le QR code');
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Dossier créé et images uploadées',
        'mediaCode' => $mediaCode,
        'files' => $uploadedFiles
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
