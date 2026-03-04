<?php
/**
 * Supprimer le logo d'une entreprise
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
    // Récupérer les données JSON
    $jsonInput = file_get_contents('php://input');
    $data = json_decode($jsonInput, true);
    
    $mediaCode = $data['mediaCode'] ?? null;
    
    if (!$mediaCode) {
        echo json_encode([
            'success' => false,
            'error' => 'mediaCode requis'
        ]);
        exit;
    }
    
    // Vérifier que l'utilisateur est authentifié (optionnel mais recommandé)
    session_start();
    if (!isset($_SESSION['accountId'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Non authentifié'
        ]);
        exit;
    }
    
    // Construire le chemin du logo
    $logoPath = __DIR__ . '/../data/company/' . $mediaCode . '/logo.png';
    
    // Vérifier si le fichier existe
    if (!file_exists($logoPath)) {
        echo json_encode([
            'success' => true,
            'message' => 'Logo déjà supprimé ou inexistant'
        ]);
        exit;
    }
    
    // Supprimer le fichier
    if (unlink($logoPath)) {
        echo json_encode([
            'success' => true,
            'message' => 'Logo supprimé avec succès'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Impossible de supprimer le logo'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
