<?php
/**
 * Récupère tous les niveaux de séniorité disponibles
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    // Liste des niveaux de séniorité
    $seniorities = [
        [
            'id' => 0,
            'name' => 'Apprenti',
            'fr_name' => 'Apprenti',
            'en_name' => 'Apprentice',
            'description' => 'En formation ou stage',
            'icon' => '🎓',
            'color' => '#fef3c7',
            'text_color' => '#92400e'
        ],
        [
            'id' => 1,
            'name' => 'Junior',
            'fr_name' => 'Junior',
            'en_name' => 'Junior',
            'description' => '0-2 ans d\'expérience',
            'icon' => '🌱',
            'color' => '#d1fae5',
            'text_color' => '#065f46'
        ],
        [
            'id' => 2,
            'name' => 'Confirmé',
            'fr_name' => 'Confirmé',
            'en_name' => 'Confirmed',
            'description' => '2-5 ans d\'expérience',
            'icon' => '💼',
            'color' => '#dbeafe',
            'text_color' => '#1e40af'
        ],
        [
            'id' => 3,
            'name' => 'Senior',
            'fr_name' => 'Senior',
            'en_name' => 'Senior',
            'description' => '5+ ans d\'expérience',
            'icon' => '⭐',
            'color' => '#fce7f3',
            'text_color' => '#9f1239'
        ]
    ];
    
    echo json_encode([
        'success' => true,
        'seniorities' => $seniorities,
        'count' => count($seniorities)
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des niveaux de séniorité',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
