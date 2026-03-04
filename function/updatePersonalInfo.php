<?php
/**
 * Mise à jour des informations personnelles du candidat
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
    $data = json_decode(file_get_contents('php://input'), true);
    
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
    
    // Récupérer l'identity_id du candidat
    $stmt = $pdo->prepare("SELECT identity_id FROM candidate WHERE id = ?");
    $stmt->execute([$candidateId]);
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$candidate || !$candidate['identity_id']) {
        throw new Exception('Identité du candidat introuvable');
    }
    
    $identityId = $candidate['identity_id'];
    
    // Préparer les données pour la mise à jour
    $firstName = $data['first_name'] ?? null;
    $lastName = $data['last_name'] ?? null;
    $phone = $data['phone'] ?? null;
    $birthDate = $data['birth_date'] ?? null;
    $countryId = $data['country'] ?? null;
    
    if (!$firstName || !$lastName) {
        throw new Exception('Le prénom et le nom sont obligatoires');
    }
    
    // Mise à jour de la table identity
    $stmt = $pdo->prepare("
        UPDATE identity 
        SET 
            first_name = ?,
            last_name = ?,
            phone = ?,
            birthdate = ?
        WHERE id = ?
    ");
    
    $stmt->execute([
        $firstName,
        $lastName,
        $phone,
        $birthDate ?: null,
        $identityId
    ]);
    
    // Mise à jour du pays dans la table candidate
    if ($countryId !== null) {
        $stmt = $pdo->prepare("UPDATE candidate SET country_id = ? WHERE id = ?");
        $stmt->execute([$countryId ?: null, $candidateId]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Informations personnelles mises à jour'
    ]);
    
} catch (Exception $e) {
    error_log("Erreur updatePersonalInfo: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
