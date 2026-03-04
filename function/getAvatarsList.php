<?php
/**
 * Récupère la liste des avatars disponibles par genre
 */

header('Content-Type: application/json');

$gender = $_GET['gender'] ?? 'men';

// Valider le genre
if (!in_array($gender, ['men', 'women'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Genre invalide'
    ]);
    exit;
}

// Chemin vers le dossier des avatars
$avatarDir = __DIR__ . '/../media/image/icon/avatar/' . $gender . '/';

// Vérifier si le dossier existe
if (!is_dir($avatarDir)) {
    echo json_encode([
        'success' => false,
        'error' => 'Dossier d\'avatars introuvable'
    ]);
    exit;
}

// Récupérer la liste des fichiers
$files = scandir($avatarDir);
$avatars = [];

foreach ($files as $file) {
    // Ignorer les dossiers . et ..
    if ($file === '.' || $file === '..') {
        continue;
    }
    
    // Vérifier si c'est une image (png, jpg, jpeg, gif, webp)
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
        $avatars[] = '/media/image/icon/avatar/' . $gender . '/' . $file;
    }
}

// Trier les avatars par nom
sort($avatars);

echo json_encode([
    'success' => true,
    'avatars' => $avatars,
    'count' => count($avatars)
]);
