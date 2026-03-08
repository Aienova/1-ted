<?php
/**
 * Générer un code de réinitialisation de mot de passe unique
 * Même méthode que pour le mediaCode du candidat
 * @return string Code unique de 12 caractères
 */
function generateForgetCode() {
    // Extraire tous les chiffres de la date/heure de création (YmdHis)
    $timestamp = date('YmdHis'); // Ex: 20260215143052
    $digits = str_split($timestamp); // Tableau de chiffres
    
    // Générer des lettres aléatoires (majuscules et minuscules)
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomLetters = [];
    for ($i = 0; $i < 6; $i++) {
        $randomLetters[] = $letters[rand(0, strlen($letters) - 1)];
    }
    
    // Sélectionner 6 chiffres aléatoires du timestamp
    shuffle($digits);
    $selectedDigits = array_slice($digits, 0, 6);
    
    // Mélanger les lettres et les chiffres
    $characters = array_merge($randomLetters, $selectedDigits);
    shuffle($characters);
    
    // Prendre les 12 premiers caractères pour garantir la longueur
    $forgetCode = implode('', array_slice($characters, 0, 12));
    
    return $forgetCode;
}

/**
 * Récupérer un compte par son forgetCode
 * @param string $forgetCode Code de réinitialisation
 * @return Account|null Objet Account ou null si non trouvé
 */
function getAccountByForgetCode($forgetCode) {
    require_once __DIR__ . '/connectionDatabase.php';
    require_once __DIR__ . '/getData.php';
    
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT * FROM account WHERE forget_code = :forget_code";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':forget_code', $forgetCode, PDO::PARAM_STR);
        $stmt->execute();
        
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        // Convertir en objet Account
        return mapToObject('account', $data);
        
    } catch (PDOException $e) {
        error_log("Erreur récupération compte par forgetCode: " . $e->getMessage());
        return null;
    }
}

/**
 * Générer et enregistrer un forgetCode pour un compte
 * @param string $email Email du compte
 * @return array ['success' => bool, 'forgetCode' => string|null, 'message' => string]
 */
function generateAndSaveForgetCode($email) {
    require_once __DIR__ . '/connectionDatabase.php';
    require_once __DIR__ . '/getData.php';
    
    try {
        $pdo = getDatabaseConnection();
        
        // Vérifier que le compte existe
        $sql = "SELECT * FROM account WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $account = $stmt->fetch();
        
        if (!$account) {
            return [
                'success' => false,
                'forgetCode' => null,
                'message' => 'Aucun compte trouvé avec cet email'
            ];
        }
        
        // Générer un nouveau forgetCode
        $forgetCode = generateForgetCode();
        
        // Enregistrer le forgetCode dans la base de données
        $updateSql = "UPDATE account SET forget_code = :forget_code WHERE email = :email";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindValue(':forget_code', $forgetCode, PDO::PARAM_STR);
        $updateStmt->bindValue(':email', $email, PDO::PARAM_STR);
        $updateStmt->execute();
        
        return [
            'success' => true,
            'forgetCode' => $forgetCode,
            'message' => 'Code de réinitialisation généré avec succès'
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur génération forgetCode: " . $e->getMessage());
        return [
            'success' => false,
            'forgetCode' => null,
            'message' => 'Erreur lors de la génération du code'
        ];
    }
}

// Point d'entrée pour les requêtes AJAX
// Vérifier que ce fichier est appelé directement (pas inclus par un autre script)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && basename($_SERVER['SCRIPT_FILENAME']) === 'getAccountForgetCode.php') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['email'])) {
        require_once __DIR__ . '/mailSystem.php';
        
        // Charger la configuration
        $envFile = __DIR__ . '/../local.env';
        $baseUrl = '';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, 'BASE_URL=') === 0) {
                    $baseUrl = trim(substr($line, 9));
                    break;
                }
            }
        }
        
        $result = generateAndSaveForgetCode($input['email']);
        
        if ($result['success']) {
            // Envoyer l'email
            $emailSent = sendPasswordResetEmail($input['email'], $result['forgetCode'], $baseUrl);
            
            echo json_encode([
                'success' => true,
                'message' => 'Un email de réinitialisation a été envoyé',
                'emailSent' => $emailSent
            ]);
        } else {
            echo json_encode($result);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Email manquant'
        ]);
    }
    exit;
}
