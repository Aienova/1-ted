<?php
// Test simple pour voir si le fichier est accessible
header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Le fichier est accessible',
    'gd_available' => extension_loaded('gd'),
    'php_version' => phpversion()
]);
