<?php
/**
 * Connexion à un compte utilisateur
 */

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/getData.php';

/**
 * Générer un token de session unique
 * @return string Token de 64 caractères
 */
function generateSessionToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Connecter un utilisateur
 * @param string $login Login ou email
 * @param string $password Mot de passe
 * @return array ['success' => bool, 'account' => Account|null, 'token' => string|null, 'message' => string]
 */
function connectionToAccount($login, $password) {
    try {
        $pdo = getDatabaseConnection();
        
        // Chercher le compte par login ou email
        $sql = "SELECT * FROM account WHERE login = :login OR email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->bindValue(':email', $login, PDO::PARAM_STR);
        $stmt->execute();
        
        $accountData = $stmt->fetch();
        
        if (!$accountData) {
            return [
                'success' => false,
                'account' => null,
                'request' => $sql,
                'token' => null,
                'message' => 'Identifiants inconnus'
            ];
        }
        
        // Vérifier le mot de passe
        if (!password_verify($password, $accountData['password_hash'])) {
            return [
                'success' => false,
                'account' => null,
                'request' => $sql,
                'token' => null,
                'message' => 'Mot de passe incorrects'
            ];
        }
        
        // Générer un token de session
        $token = generateSessionToken();
        
        // Mettre à jour le token et la date de connexion
        $updateSql = "UPDATE account 
                      SET token = :token, last_connection = NOW() 
                      WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindValue(':token', $token, PDO::PARAM_STR);
        $updateStmt->bindValue(':id', $accountData['id'], PDO::PARAM_INT);
        $updateStmt->execute();
        
        return [
            'success' => true,
            'account' => null, // On n'envoie pas l'objet complet
            'token' => $token,
            'accountId' => $accountData['id'],
            'role' => $accountData['role'],
            'firstname' => $accountData['firstname'] ?? '',
            'lastname' => $accountData['lastname'] ?? '',
            'aiecoins' => $accountData['aiecoins'] ?? 0,
            'message' => 'Connexion réussie'
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur connexion compte: " . $e->getMessage());
        return [
            'success' => false,
            'account' => null,
            'token' => null,
            'message' => 'Erreur lors de la connexion: ' . $e->getMessage()
        ];
    } catch (Exception $e) {
        error_log("Erreur générale connexion: " . $e->getMessage());
        return [
            'success' => false,
            'account' => null,
            'token' => null,
            'message' => 'Erreur lors de la connexion: ' . $e->getMessage()
        ];
    }
}

/**
 * Vérifier un token de session
 * @param string $token Token à vérifier
 * @return array ['valid' => bool, 'account' => Account|null]
 */
function verifySessionToken($token) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT * FROM account WHERE token = :token";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        
        $accountData = $stmt->fetch();
        
        if (!$accountData) {
            return [
                'valid' => false,
                'account' => null
            ];
        }
        
        $account = mapToObject('account', $accountData);
        
        return [
            'valid' => true,
            'account' => $account
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur vérification token: " . $e->getMessage());
        return [
            'valid' => false,
            'account' => null
        ];
    }
}

/**
 * Déconnecter un utilisateur (supprimer le token)
 * @param string $token Token de session
 * @return bool True si déconnexion réussie
 */
function logout($token) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "UPDATE account SET token = NULL WHERE token = :token";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        
        return true;
        
    } catch (PDOException $e) {
        error_log("Erreur déconnexion: " . $e->getMessage());
        return false;
    }
}

// Point d'entrée pour les requêtes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'login':
                if (isset($input['login']) && isset($input['password'])) {
                    $result = connectionToAccount($input['login'], $input['password']);
                    
                    // Ne pas renvoyer l'objet Account complet, juste les infos nécessaires
                    if ($result['success']) {
                        unset($result['account']);
                        
                        // Stocker le token dans une session PHP
                        session_start();
                        $_SESSION['token'] = $result['token'];
                        $_SESSION['accountId'] = $result['accountId'];
                        $_SESSION['role'] = $result['role'];
                        $_SESSION['firstname'] = $result['firstname'];
                        $_SESSION['lastname'] = $result['lastname'];
                        $_SESSION['aiecoins'] = $result['aiecoins'];
                        $_SESSION['login'] = $input['login']; // Stocker le login utilisé
                    }
                    
                    echo json_encode($result);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Login et mot de passe requis'
                    ]);
                }
                break;
                
            case 'verify':
                if (isset($input['token'])) {
                    $result = verifySessionToken($input['token']);
                    echo json_encode($result);
                } else {
                    echo json_encode([
                        'valid' => false,
                        'message' => 'Token requis'
                    ]);
                }
                break;
                
            case 'logout':
                if (isset($input['token'])) {
                    $success = logout($input['token']);
                    
                    // Détruire la session PHP
                    session_start();
                    session_destroy();
                    
                    echo json_encode([
                        'success' => $success,
                        'message' => $success ? 'Déconnexion réussie' : 'Erreur lors de la déconnexion'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Token requis'
                    ]);
                }
                break;
                
            default:
                echo json_encode([
                    'success' => false,
                    'message' => 'Action non reconnue'
                ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Action manquante'
        ]);
    }
    exit;
}
