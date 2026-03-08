<?php
/**
 * Met à jour la séniorité d'un candidat
 * Accessible uniquement aux administrateurs
 */

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Vérifier que l'utilisateur est connecté et est administrateur
if (!isset($_SESSION['accountId']) || !isset($_SESSION['role'])) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Vous devez être connecté'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Vérifier les permissions (admin ou administrator)
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'administrator') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Accès refusé. Seuls les administrateurs peuvent modifier la séniorité.',
        'debug_role' => $_SESSION['role']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Récupérer les données JSON
$input = json_decode(file_get_contents('php://input'), true);

// Validation des données
if (!isset($input['candidate_id']) || empty($input['candidate_id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'ID du candidat manquant'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$candidateId = intval($input['candidate_id']);
$seniority = isset($input['seniority']) && $input['seniority'] !== '' && $input['seniority'] !== null ? intval($input['seniority']) : null;

// Valider le niveau de séniorité si fourni
$validSeniorities = [0, 1, 2, 3];
if ($seniority !== null && !in_array($seniority, $validSeniorities, true)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Niveau de séniorité invalide'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Connexion à la base de données
    require_once __DIR__ . '/connectionDatabase.php';
    $pdo = getDatabaseConnection();
    
    // Vérifier que le candidat existe
    $stmt = $pdo->prepare("SELECT id FROM candidate WHERE id = ?");
    $stmt->execute([$candidateId]);
    
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Candidat introuvable'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Mettre à jour la séniorité
    $stmt = $pdo->prepare("UPDATE candidate SET seniority = ? WHERE id = ?");
    $stmt->execute([$seniority, $candidateId]);
    
    // Mapper les noms français
    $seniorityNames = [
        0 => 'Apprenti',
        1 => 'Junior',
        2 => 'Confirmé',
        3 => 'Senior'
    ];
    
    $seniorityName = $seniority !== null ? $seniorityNames[$seniority] : 'Non spécifié';
    
    echo json_encode([
        'success' => true,
        'message' => 'Séniorité mise à jour avec succès',
        'seniority' => $seniority,
        'seniority_name' => $seniorityName
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
