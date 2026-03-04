<?php
/**
 * Fonction pour générer un code de vérification d'email unique
 * Utilise la même logique que mediaCode
 * 
 * @return string Code de vérification de 12 caractères alphanumériques
 */
function generateVerificationCode() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    
    for ($i = 0; $i < 12; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $code;
}

/**
 * Fonction pour générer un code de vérification unique (non utilisé dans la BDD)
 * 
 * @param PDO $pdo Connexion à la base de données
 * @return string Code de vérification unique
 */
function generateUniqueVerificationCode($pdo) {
    $maxAttempts = 10;
    $attempts = 0;
    
    while ($attempts < $maxAttempts) {
        $code = generateVerificationCode();
        
        // Vérifier si le code existe déjà
        $sql = "SELECT COUNT(*) as count FROM account WHERE verification_code = :code";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':code', $code, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            return $code;
        }
        
        $attempts++;
    }
    
    throw new Exception('Impossible de générer un code de vérification unique');
}

/**
 * Fonction pour créer et enregistrer un code de vérification pour un compte
 * 
 * @param PDO $pdo Connexion à la base de données
 * @param int $accountId ID du compte
 * @return array ['code' => string, 'expire_date' => string]
 */
function createVerificationCode($pdo, $accountId) {
    // Générer un code unique
    $code = generateUniqueVerificationCode($pdo);
    
    // Calculer la date d'expiration (maintenant + 10 minutes)
    $now = new DateTime();
    $expireDate = clone $now;
    $expireDate->modify('+10 minutes');
    
    // Mettre à jour le compte avec le nouveau code
    $sql = "UPDATE account 
            SET verification_code = :code, 
                verification_expire_date = :expire_date,
                verified = 0
            WHERE id = :account_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':code', $code, PDO::PARAM_STR);
    $stmt->bindValue(':expire_date', $expireDate->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt->bindValue(':account_id', $accountId, PDO::PARAM_INT);
    $stmt->execute();
    
    return [
        'code' => $code,
        'expire_date' => $expireDate->format('Y-m-d H:i:s')
    ];
}

/**
 * Fonction pour créer et enregistrer un code de vérification pour un email
 * 
 * @param PDO $pdo Connexion à la base de données
 * @param string $email Email du compte
 * @return array ['success' => bool, 'code' => string, 'expire_date' => string, 'message' => string]
 */
function createVerificationCodeByEmail($pdo, $email) {
    // Rechercher le compte par email
    $sql = "SELECT id FROM account WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$account) {
        return [
            'success' => false,
            'message' => 'Aucun compte trouvé avec cet email'
        ];
    }
    
    // Créer le code de vérification
    $verificationData = createVerificationCode($pdo, $account['id']);
    
    return [
        'success' => true,
        'code' => $verificationData['code'],
        'expire_date' => $verificationData['expire_date'],
        'message' => 'Code de vérification créé avec succès'
    ];
}
