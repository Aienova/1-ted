<?php
/**
 * Gestion des AIECoins
 * Cette fonction centralise toutes les modifications d'AIECoins
 * pour garantir la synchronisation entre la base de données et la session
 */

require_once __DIR__ . '/connectionDatabase.php';

/**
 * Mettre à jour le solde d'AIECoins d'un compte
 * @param int $accountId ID du compte
 * @param int $amount Montant à ajouter (positif) ou retirer (négatif)
 * @param string $reason Raison de la modification (pour les logs)
 * @return array ['success' => bool, 'newBalance' => int|null, 'message' => string]
 */
function updateAiecoins($accountId, $amount, $reason = '') {
    try {
        $pdo = getDatabaseConnection();
        
        // Commencer une transaction
        $pdo->beginTransaction();
        
        // Récupérer le solde actuel
        $sql = "SELECT aiecoins FROM account WHERE id = :accountId FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':accountId', $accountId, PDO::PARAM_INT);
        $stmt->execute();
        
        $account = $stmt->fetch();
        
        if (!$account) {
            $pdo->rollBack();
            return [
                'success' => false,
                'newBalance' => null,
                'message' => 'Compte introuvable'
            ];
        }
        
        $currentBalance = (int)$account['aiecoins'];
        $newBalance = $currentBalance + $amount;
        
        // Vérifier que le solde ne devient pas négatif
        if ($newBalance < 0) {
            $pdo->rollBack();
            return [
                'success' => false,
                'newBalance' => $currentBalance,
                'message' => 'Solde insuffisant'
            ];
        }
        
        // Mettre à jour le solde dans la base de données
        $updateSql = "UPDATE account SET aiecoins = :newBalance WHERE id = :accountId";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindValue(':newBalance', $newBalance, PDO::PARAM_INT);
        $updateStmt->bindValue(':accountId', $accountId, PDO::PARAM_INT);
        $updateStmt->execute();
        
        // Valider la transaction
        $pdo->commit();
        
        // Mettre à jour la session si c'est le compte connecté
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['accountId']) && $_SESSION['accountId'] == $accountId) {
            $_SESSION['aiecoins'] = $newBalance;
        }
        
        // Logger la transaction (optionnel - peut être amélioré avec une table de logs)
        error_log("AIECoins update: Account $accountId | Amount: $amount | New balance: $newBalance | Reason: $reason");
        
        return [
            'success' => true,
            'newBalance' => $newBalance,
            'previousBalance' => $currentBalance,
            'message' => 'Solde mis à jour avec succès'
        ];
        
    } catch (PDOException $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Erreur mise à jour AIECoins: " . $e->getMessage());
        return [
            'success' => false,
            'newBalance' => null,
            'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
        ];
    }
}

/**
 * Ajouter des AIECoins à un compte
 * @param int $accountId ID du compte
 * @param int $amount Montant à ajouter (doit être positif)
 * @param string $reason Raison de l'ajout
 * @return array
 */
function addAiecoins($accountId, $amount, $reason = '') {
    if ($amount <= 0) {
        return [
            'success' => false,
            'message' => 'Le montant doit être positif'
        ];
    }
    return updateAiecoins($accountId, $amount, $reason);
}

/**
 * Retirer des AIECoins à un compte
 * @param int $accountId ID du compte
 * @param int $amount Montant à retirer (doit être positif)
 * @param string $reason Raison du retrait
 * @return array
 */
function removeAiecoins($accountId, $amount, $reason = '') {
    if ($amount <= 0) {
        return [
            'success' => false,
            'message' => 'Le montant doit être positif'
        ];
    }
    return updateAiecoins($accountId, -$amount, $reason);
}

/**
 * Obtenir le solde actuel d'AIECoins
 * @param int $accountId ID du compte
 * @return array ['success' => bool, 'balance' => int|null]
 */
function getAiecoinsBalance($accountId) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT aiecoins FROM account WHERE id = :accountId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':accountId', $accountId, PDO::PARAM_INT);
        $stmt->execute();
        
        $account = $stmt->fetch();
        
        if (!$account) {
            return [
                'success' => false,
                'balance' => null,
                'message' => 'Compte introuvable'
            ];
        }
        
        return [
            'success' => true,
            'balance' => (int)$account['aiecoins']
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur récupération solde AIECoins: " . $e->getMessage());
        return [
            'success' => false,
            'balance' => null,
            'message' => 'Erreur lors de la récupération du solde'
        ];
    }
}

/**
 * Rafraîchir le solde dans la session depuis la base de données
 * @param int $accountId ID du compte
 * @return bool Succès ou échec
 */
function refreshAiecoinsInSession($accountId) {
    $result = getAiecoinsBalance($accountId);
    
    if ($result['success']) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['accountId']) && $_SESSION['accountId'] == $accountId) {
            $_SESSION['aiecoins'] = $result['balance'];
            return true;
        }
    }
    
    return false;
}

// Point d'entrée pour les requêtes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['action'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Action manquante'
        ]);
        exit;
    }
    
    // Vérifier que l'utilisateur est connecté
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['accountId'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Non authentifié'
        ]);
        exit;
    }
    
    switch ($input['action']) {
        case 'add':
            if (!isset($input['amount'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Montant manquant'
                ]);
                break;
            }
            
            $accountId = $input['accountId'] ?? $_SESSION['accountId'];
            $amount = (int)$input['amount'];
            $reason = $input['reason'] ?? 'Ajout manuel';
            
            $result = addAiecoins($accountId, $amount, $reason);
            echo json_encode($result);
            break;
            
        case 'remove':
            if (!isset($input['amount'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Montant manquant'
                ]);
                break;
            }
            
            $accountId = $input['accountId'] ?? $_SESSION['accountId'];
            $amount = (int)$input['amount'];
            $reason = $input['reason'] ?? 'Retrait manuel';
            
            $result = removeAiecoins($accountId, $amount, $reason);
            echo json_encode($result);
            break;
            
        case 'getBalance':
            $accountId = $input['accountId'] ?? $_SESSION['accountId'];
            $result = getAiecoinsBalance($accountId);
            echo json_encode($result);
            break;
            
        case 'refresh':
            $accountId = $_SESSION['accountId'];
            $success = refreshAiecoinsInSession($accountId);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'balance' => $_SESSION['aiecoins'],
                    'message' => 'Solde rafraîchi'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors du rafraîchissement'
                ]);
            }
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Action non reconnue'
            ]);
    }
    exit;
}
