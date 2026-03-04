<?php
/**
 * Récupérer le profil complet du candidat connecté
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
    $pdo = getDatabaseConnection();
    
    // Récupérer l'ID du candidat depuis la session ou depuis l'account
    $candidateId = $_SESSION['candidate_id'] ?? null;
    
    // Si pas dans la session, récupérer depuis la table account
    if (!$candidateId) {
        $stmt = $pdo->prepare("SELECT candidate_id FROM account WHERE id = ?");
        $stmt->execute([$_SESSION['accountId']]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($account && $account['candidate_id']) {
            $candidateId = $account['candidate_id'];
            $_SESSION['candidate_id'] = $candidateId; // Sauvegarder dans la session
        }
    }
    
    if (!$candidateId) {
        throw new Exception('ID candidat introuvable dans la session et la base de données');
    }
    
    // Récupérer les informations du candidat
    $stmt = $pdo->prepare("
        SELECT 
            c.id,
            c.identity_id,
            c.country_id,
            c.media_code,
            i.first_name,
            i.last_name,
            i.birthdate,
            i.phone,
            i.email as identity_email,
            i.job_title,
            a.email as account_email,
            a.id as account_id
        FROM candidate c
        LEFT JOIN identity i ON c.identity_id = i.id
        LEFT JOIN account a ON a.candidate_id = c.id
        WHERE c.id = ?
    ");
    
    $stmt->execute([$candidateId]);
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$candidate) {
        throw new Exception('Candidat introuvable');
    }
    
    // Construire l'URL de la photo
    $photoUrl = null;
    if ($candidate['media_code']) {
        $photoPath = __DIR__ . '/../data/candidates/media/' . $candidate['media_code'] . '/photo.jpg';
        if (file_exists($photoPath)) {
            $photoUrl = '/data/candidates/media/' . $candidate['media_code'] . '/photo.jpg';
        }
    }
    
    // Structurer la réponse
    $profile = [
        'account' => [
            'id' => $candidate['account_id'],
            'email' => $candidate['account_email']
        ],
        'identity' => [
            'first_name' => $candidate['first_name'],
            'last_name' => $candidate['last_name'],
            'phone' => $candidate['phone'],
            'birth_date' => $candidate['birthdate'],
            'job_title' => $candidate['job_title'],
            'email' => $candidate['identity_email']
        ],
        'photo_url' => $photoUrl,
        'media_code' => $candidate['media_code'],
        'country_id' => $candidate['country_id'],
        'country' => $_SESSION['candidate_country'] ?? null
    ];
    
    echo json_encode([
        'success' => true,
        'profile' => $profile
    ]);
    
} catch (Exception $e) {
    error_log("Erreur getCandidateProfile: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
