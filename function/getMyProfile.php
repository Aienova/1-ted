<?php
/**
 * Récupérer le profil de l'utilisateur connecté (candidat ou entreprise)
 * Cette fonction s'adapte automatiquement au rôle de l'utilisateur
 */

session_start();
require_once 'connectionDatabase.php';

header('Content-Type: application/json');

// Debug: Log toutes les variables de session
error_log("=== DEBUG getMyProfile - Session Start ===");
error_log("Session ID: " . session_id());
error_log("Session variables: " . print_r($_SESSION, true));
error_log("isset(\$_SESSION['accountId']): " . (isset($_SESSION['accountId']) ? 'YES' : 'NO'));
error_log("isset(\$_SESSION['role']): " . (isset($_SESSION['role']) ? 'YES' : 'NO'));

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['accountId']) || !isset($_SESSION['role'])) {
    error_log("❌ ÉCHEC: Session variables missing");
    echo json_encode([
        'success' => false,
        'error' => 'Vous devez être connecté',
        'debug' => [
            'session_id' => session_id(),
            'has_session_accountId' => isset($_SESSION['accountId']),
            'has_session_role' => isset($_SESSION['role']),
            'session_keys' => array_keys($_SESSION)
        ]
    ]);
    exit;
}

$role = $_SESSION['role'];

// Debug: Log les informations de session
error_log("DEBUG getMyProfile - Account ID: " . $_SESSION['accountId']);
error_log("DEBUG getMyProfile - Role: " . $role);
error_log("DEBUG getMyProfile - Candidate ID from session: " . ($_SESSION['candidate_id'] ?? 'NOT SET'));

try {
    $pdo = getDatabaseConnection();
    
    // Rediriger vers la fonction appropriée selon le rôle
    if ($role === 'candidate') {
        // Profil candidat
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
                c.priority,
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
        
        // Debug: Log les données récupérées
        error_log("DEBUG getMyProfile - Candidate ID: " . $candidateId);
        error_log("DEBUG getMyProfile - Candidate data: " . print_r($candidate, true));
        
        if (!$candidate) {
            throw new Exception('Candidat introuvable');
        }
        
        // Construire l'URL de la photo
        $photoUrl = null;
        if ($candidate['media_code']) {
            $photoPath = __DIR__ . '/../data/candidates/media/' . $candidate['media_code'] . '/photo.jpg';
            error_log("DEBUG getMyProfile - Photo path: " . $photoPath);
            error_log("DEBUG getMyProfile - Photo exists: " . (file_exists($photoPath) ? 'YES' : 'NO'));
            
            if (file_exists($photoPath)) {
                $photoUrl = '/data/candidates/media/' . $candidate['media_code'] . '/photo.jpg';
            }
        }
        
        $response = [
            'success' => true,
            'role' => 'candidate',
            'profile' => [
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
                'country' => $_SESSION['candidate_country'] ?? null,
                'priority' => $candidate['priority']
            ]
        ];
        
        error_log("DEBUG getMyProfile - Final response: " . json_encode($response));
        echo json_encode($response);
        
    } elseif ($role === 'company') {
        // Profil entreprise
        $companyId = $_SESSION['company_id'] ?? null;
        
        if (!$companyId) {
            throw new Exception('ID entreprise introuvable');
        }
        
        // Récupérer les informations de l'entreprise
        $stmt = $pdo->prepare("
            SELECT 
                c.*,
                a.email as account_email
            FROM company c
            LEFT JOIN account a ON a.company_id = c.id
            WHERE c.id = ?
        ");
        
        $stmt->execute([$companyId]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$company) {
            throw new Exception('Entreprise introuvable');
        }
        
        // Construire l'URL du logo
        $logoUrl = null;
        if ($company['media_code']) {
            $logoPath = __DIR__ . '/../data/company/' . $company['media_code'] . '/logo.png';
            if (file_exists($logoPath)) {
                $logoUrl = '/data/company/' . $company['media_code'] . '/logo.png';
            }
        }
        
        echo json_encode([
            'success' => true,
            'role' => 'company',
            'company' => [
                'id' => $company['id'],
                'name' => $company['name'],
                'siret' => $company['siret'],
                'address' => $company['address'],
                'email' => $company['email'],
                'phone' => $company['phone'],
                'website' => $company['website'],
                'description' => $company['description'],
                'contact_firstname' => $company['contact_firstname'],
                'contact_lastname' => $company['contact_lastname'],
                'media_code' => $company['media_code'],
                'logo_url' => $logoUrl,
                'account_email' => $company['account_email']
            ]
        ]);
        
    } else {
        throw new Exception('Rôle non reconnu');
    }
    
} catch (Exception $e) {
    error_log("Erreur getMyProfile: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
