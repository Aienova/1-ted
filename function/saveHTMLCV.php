<?php
/**
 * Script pour sauvegarder le CV en HTML statique
 * Reçoit le code média et le contenu HTML, puis crée le fichier cv.html
 */

header('Content-Type: application/json');

try {
    // Récupérer les données JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data || !isset($data['mediaCode']) || !isset($data['htmlContent'])) {
        throw new Exception('Données manquantes: mediaCode et htmlContent requis');
    }
    
    $mediaCode = $data['mediaCode'];
    $htmlContent = $data['htmlContent'];
    
    // Valider le mediaCode (12 caractères alphanumériques)
    if (!preg_match('/^[a-zA-Z0-9]{12}$/', $mediaCode)) {
        throw new Exception('Format de mediaCode invalide');
    }
    
    // Construire le chemin du dossier media
    $mediaDir = __DIR__ . '/../data/candidates/media/' . $mediaCode;
    
    // Vérifier si le dossier existe, sinon le créer
    if (!is_dir($mediaDir)) {
        if (!mkdir($mediaDir, 0755, true)) {
            throw new Exception('Impossible de créer le dossier media: ' . $mediaDir);
        }
    }
    
    // Chemin du fichier HTML
    $htmlFilePath = $mediaDir . '/cv.html';
    
    // Écrire le contenu HTML dans le fichier
    $bytesWritten = file_put_contents($htmlFilePath, $htmlContent);
    
    if ($bytesWritten === false) {
        throw new Exception('Erreur lors de l\'écriture du fichier HTML');
    }
    
    // Succès
    echo json_encode([
        'success' => true,
        'filePath' => $htmlFilePath,
        'mediaCode' => $mediaCode,
        'fileSize' => $bytesWritten
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
