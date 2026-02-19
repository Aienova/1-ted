<?php
/**
 * Script pour sauvegarder l'offre d'emploi en HTML statique
 * Reçoit l'ID de l'offre et le contenu HTML, puis crée le fichier job.html
 */

header('Content-Type: application/json');

try {
    // Récupérer les données JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data || !isset($data['jobId']) || !isset($data['htmlContent'])) {
        throw new Exception('Données manquantes: jobId et htmlContent requis');
    }
    
    $jobId = $data['jobId'];
    $htmlContent = $data['htmlContent'];
    
    // Valider le jobId (nombre entier)
    if (!is_numeric($jobId)) {
        throw new Exception('Format de jobId invalide');
    }
    
    // Construire le chemin du dossier de l'offre
    $jobDir = __DIR__ . '/../data/jobs/id/' . $jobId;
    
    // Vérifier si le dossier existe, sinon le créer
    if (!is_dir($jobDir)) {
        if (!mkdir($jobDir, 0755, true)) {
            throw new Exception('Impossible de créer le dossier de l\'offre: ' . $jobDir);
        }
    }
    
    // Chemin du fichier HTML
    $htmlFilePath = $jobDir . '/job.html';
    
    // Écrire le contenu HTML dans le fichier
    $bytesWritten = file_put_contents($htmlFilePath, $htmlContent);
    
    if ($bytesWritten === false) {
        throw new Exception('Erreur lors de l\'écriture du fichier HTML');
    }
    
    // Succès
    echo json_encode([
        'success' => true,
        'filePath' => $htmlFilePath,
        'jobId' => $jobId,
        'fileSize' => $bytesWritten
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
