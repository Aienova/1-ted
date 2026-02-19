<?php
/**
 * Changer le mot de passe d'un compte
 */

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/getAccountForgetCode.php';

/**
 * Changer le mot de passe via forgetCode
 * @param string $forgetCode Code de réinitialisation
 * @param string $newPassword Nouveau mot de passe
 * @return array ['success' => bool, 'message' => string]
 */
function changePasswordWithCode($forgetCode, $newPassword) {
    try {
        // Récupérer le compte par forgetCode
        $account = getAccountByForgetCode($forgetCode);
        
        if (!$account) {
            return [
                'success' => false,
                'message' => 'Code de réinitialisation invalide ou expiré'
            ];
        }
        
        $pdo = getDatabaseConnection();
        
        // Hasher le nouveau mot de passe
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Mettre à jour le mot de passe et supprimer le forgetCode
        $sql = "UPDATE account 
                SET password_hash = :password_hash, forget_code = NULL 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);
        $stmt->bindValue(':id', $account->getId(), PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'success' => true,
            'message' => 'Mot de passe modifié avec succès'
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur changement mot de passe: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur lors du changement de mot de passe'
        ];
    }
}

/**
 * Changer le mot de passe d'un compte connecté
 * @param int $accountId ID du compte
 * @param string $currentPassword Mot de passe actuel
 * @param string $newPassword Nouveau mot de passe
 * @return array ['success' => bool, 'message' => string]
 */
function changePasswordWithCurrent($accountId, $currentPassword, $newPassword) {
    try {
        $pdo = getDatabaseConnection();
        
        // Récupérer le compte
        $sql = "SELECT * FROM account WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $accountId, PDO::PARAM_INT);
        $stmt->execute();
        
        $accountData = $stmt->fetch();
        
        if (!$accountData) {
            return [
                'success' => false,
                'message' => 'Compte non trouvé'
            ];
        }
        
        // Vérifier le mot de passe actuel
        if (!password_verify($currentPassword, $accountData['password_hash'])) {
            return [
                'success' => false,
                'message' => 'Mot de passe actuel incorrect'
            ];
        }
        
        // Hasher le nouveau mot de passe
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Mettre à jour le mot de passe
        $updateSql = "UPDATE account SET password_hash = :password_hash WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);
        $updateStmt->bindValue(':id', $accountId, PDO::PARAM_INT);
        $updateStmt->execute();
        
        return [
            'success' => true,
            'message' => 'Mot de passe modifié avec succès'
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur changement mot de passe: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur lors du changement de mot de passe'
        ];
    }
}

// Point d'entrée pour les requêtes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'reset':
                // Réinitialisation avec forgetCode
                if (isset($input['forgetCode']) && isset($input['newPassword'])) {
                    $result = changePasswordWithCode($input['forgetCode'], $input['newPassword']);
                    echo json_encode($result);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Code et nouveau mot de passe requis'
                    ]);
                }
                break;
                
            case 'change':
                // Changement avec mot de passe actuel
                if (isset($input['accountId']) && isset($input['currentPassword']) && isset($input['newPassword'])) {
                    $result = changePasswordWithCurrent(
                        $input['accountId'],
                        $input['currentPassword'],
                        $input['newPassword']
                    );
                    echo json_encode($result);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Données manquantes'
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
