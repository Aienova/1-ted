<?php
/**
 * Upload d'images pour les photos de profil et QR codes
 * Stocke les images dans /data/candidates/media/[media_code]/
 * Supporte deux modes: FormData (multipart/form-data) et JSON (application/json)
 */

// Commencer le buffer de sortie pour capturer les erreurs
ob_start();

// Désactiver l'affichage des erreurs pour éviter de corrompre le JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Nettoyer le buffer et définir le header
ob_clean();
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
    // Détecter le type de contenu
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $isJson = strpos($contentType, 'application/json') !== false;
    
    $mediaCode = null;
    $uploadedFiles = [];
    
    if ($isJson) {
        // ========== MODE JSON (depuis editor avec base64) ==========
        $jsonInput = file_get_contents('php://input');
        $data = json_decode($jsonInput, true);
        
        $mediaCode = $data['mediaCode'] ?? null;
        $type = $data['type'] ?? null; // 'photo', 'qrcode' ou 'company_logo'
        $imageData = $data['imageData'] ?? null;
        $entityType = $data['entityType'] ?? 'candidate'; // 'candidate' ou 'company'
        
        if (!$mediaCode) {
            echo json_encode([
                'success' => false,
                'error' => 'mediaCode requis'
            ]);
            exit;
        }
        
        // Créer le dossier selon le type d'entité
        if ($entityType === 'company') {
            $mediaDir = __DIR__ . '/../data/company/media/' . $mediaCode;
        } else {
            $mediaDir = __DIR__ . '/../data/candidates/media/' . $mediaCode;
        }
        
        if (!file_exists($mediaDir)) {
            if (!mkdir($mediaDir, 0755, true)) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Impossible de créer le dossier media'
                ]);
                exit;
            }
        }
        
        // Uploader l'image depuis base64
        if ($imageData && $type) {
            // Extraire les données base64 (enlever le préfixe data:image/...;base64,)
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageType = $matches[1];
                $base64Data = substr($imageData, strpos($imageData, ',') + 1);
                $decodedData = base64_decode($base64Data);
                
                if ($decodedData === false) {
                    throw new Exception('Impossible de décoder l\'image base64');
                }
                
                // Traitement selon le type
                if ($entityType === 'company' && $type === 'logo') {
                    // Toujours sauvegarder en .png pour les logos (format standardisé)
                    $filePath = $mediaDir . '/logo.png';
                    $urlPath = '/data/company/media/' . $mediaCode . '/logo.png';
                    
                    if (file_put_contents($filePath, $decodedData) === false) {
                        throw new Exception('Impossible d\'écrire le fichier logo');
                    }
                    
                    $uploadedFiles['logo'] = $urlPath;
                    
                } elseif ($type === 'photo') {
                    $filePath = $mediaDir . '/photo.jpg';
                    $urlPath = '/data/candidates/media/' . $mediaCode . '/photo.jpg';
                    
                    if (file_put_contents($filePath, $decodedData) === false) {
                        throw new Exception('Impossible d\'écrire le fichier image');
                    }
                    
                    $uploadedFiles[$type] = $urlPath;
                    
                } elseif ($type === 'qrcode') {
                    $filePath = $mediaDir . '/qr-code.png';
                    $urlPath = '/data/candidates/media/' . $mediaCode . '/qr-code.png';
                    
                    if (file_put_contents($filePath, $decodedData) === false) {
                        throw new Exception('Impossible d\'écrire le fichier image');
                    }
                    
                    $uploadedFiles[$type] = $urlPath;
                    
                } else {
                    throw new Exception('Type d\'image non reconnu: ' . $type);
                }
            } else {
                throw new Exception('Format base64 invalide');
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Image uploadée avec succès',
            'url' => $uploadedFiles[$type] ?? ($uploadedFiles['logo'] ?? null),
            'mediaCode' => $mediaCode
        ]);
        
    } else {
        // ========== MODE FORMDATA (depuis ancien système) ==========
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
        
        // Uploader la photo si présente
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $mediaDir . '/photo.jpg';
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                $uploadedFiles['photo'] = '/data/candidates/media/' . $mediaCode . '/photo.jpg';
            } else {
                throw new Exception('Impossible d\'uploader la photo');
            }
        }
        
        // Uploader le QR code si présent
        if (isset($_FILES['qrcode']) && $_FILES['qrcode']['error'] === UPLOAD_ERR_OK) {
            $qrcodePath = $mediaDir . '/qr-code.png';
            if (move_uploaded_file($_FILES['qrcode']['tmp_name'], $qrcodePath)) {
                $uploadedFiles['qrcode'] = '/data/candidates/media/' . $mediaCode . '/qr-code.png';
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
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
