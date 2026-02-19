<?php
// Désactiver l'affichage des erreurs pour éviter de polluer le JSON
@ini_set('display_errors', 0);
@error_reporting(E_ERROR | E_PARSE);

/**
 * Créer un nouveau compte utilisateur
 */

// Ne pas inclure les fichiers ici pour éviter les erreurs affichées
// Ils seront inclus dans la fonction si nécessaire

/**
 * Créer un compte utilisateur
 * @param array $data Données du compte
 * @return array ['success' => bool, 'accountId' => int|null, 'message' => string]
 */
function createAccount($data) {
    // Inclure les dépendances seulement quand nécessaire
    require_once __DIR__ . '/connectionDatabase.php';
    
    try {
        $pdo = getDatabaseConnection();
        
        // Validation des données
        if (empty($data['email']) || empty($data['password'])) {
            return [
                'success' => false,
                'accountId' => null,
                'message' => 'Email et mot de passe requis'
            ];
        }
        
        // Vérifier si l'email existe déjà
        $checkSql = "SELECT id FROM account WHERE email = :email";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            return [
                'success' => false,
                'accountId' => null,
                'message' => 'Un compte avec cet email existe déjà'
            ];
        }
        
        // Vérifier si le login existe déjà
        $login = $data['login'] ?? $data['email'];
        $checkLoginSql = "SELECT id FROM account WHERE login = :login";
        $checkLoginStmt = $pdo->prepare($checkLoginSql);
        $checkLoginStmt->bindValue(':login', $login, PDO::PARAM_STR);
        $checkLoginStmt->execute();
        
        if ($checkLoginStmt->fetch()) {
            return [
                'success' => false,
                'accountId' => null,
                'message' => 'Ce nom d\'utilisateur est déjà utilisé'
            ];
        }
        
        // Hasher le mot de passe
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Préparer les données (accepter both formats: firstname/first_name, telephone/phone)
        $firstname = $data['firstname'] ?? $data['first_name'] ?? null;
        $lastname = $data['lastname'] ?? $data['last_name'] ?? null;
        $telephone = $data['telephone'] ?? $data['phone'] ?? null;
        $companyName = $data['company_name'] ?? null;
        $companySiret = $data['company_siret'] ?? null;
        $activityId = $data['activity_id'] ?? null;
        $countryId = $data['country_id'] ?? null;
        $candidateId = $data['candidate_id'] ?? null;
        $companyId = $data['company_id'] ?? null;
        $role = $data['role'] ?? 'candidate'; // Par défaut: candidat
        
        // Insérer le compte
        $sql = "INSERT INTO account 
                (firstname, lastname, login, telephone, company_name, company_siret, 
                 activity_id, country_id, email, password_hash, role, candidate_id, company_id) 
                VALUES 
                (:firstname, :lastname, :login, :telephone, :company_name, :company_siret, 
                 :activity_id, :country_id, :email, :password_hash, :role, :candidate_id, :company_id)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->bindValue(':telephone', $telephone, PDO::PARAM_STR);
        $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
        $stmt->bindValue(':company_siret', $companySiret, PDO::PARAM_STR);
        $stmt->bindValue(':activity_id', $activityId, PDO::PARAM_INT);
        $stmt->bindValue(':country_id', $countryId, PDO::PARAM_INT);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->bindValue(':candidate_id', $candidateId, PDO::PARAM_INT);
        $stmt->bindValue(':company_id', $companyId, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $accountId = $pdo->lastInsertId();
        
        // Charger la configuration pour l'URL
        $envFile = __DIR__ . '/../local.env';
        $baseUrl = 'http://1-ted.local/';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, 'BASE_URL=') === 0) {
                    $baseUrl = trim(substr($line, 9));
                    break;
                }
            }
        }
        
        // Envoyer un email de bienvenue (ne pas bloquer si ça échoue)
        try {
            if (file_exists(__DIR__ . '/mailSystem.php')) {
                require_once __DIR__ . '/mailSystem.php';
                sendWelcomeEmail($data['email'], $firstname ?? 'Utilisateur', $baseUrl);
            }
        } catch (Exception $e) {
            // Log l'erreur mais ne pas bloquer la création du compte
            error_log("Erreur envoi email de bienvenue: " . $e->getMessage());
        }
        
        return [
            'success' => true,
            'accountId' => $accountId,
            'message' => 'Compte créé avec succès'
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur création compte: " . $e->getMessage());
        return [
            'success' => false,
            'accountId' => null,
            'message' => 'Erreur lors de la création du compte'
        ];
    }
}

// Point d'entrée pour les requêtes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyer tout buffer de sortie existant
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'accountId' => null,
                'message' => 'Données JSON invalides: ' . json_last_error_msg()
            ]);
            exit;
        }
        
        $result = createAccount($input);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Log l'erreur pour debug
        error_log("Erreur createAccount API: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        
        echo json_encode([
            'success' => false,
            'accountId' => null,
            'message' => 'Erreur serveur: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    } catch (Error $e) {
        // Capturer aussi les erreurs fatales
        error_log("Erreur fatale createAccount API: " . $e->getMessage());
        
        echo json_encode([
            'success' => false,
            'accountId' => null,
            'message' => 'Erreur serveur fatale'
        ], JSON_UNESCAPED_UNICODE);
    }
    
    exit;
}
