<?php
/**
 * Gestion des prix AIECoins (AIECourt)
 * Récupère le prix d'une action en fonction du contexte (pays, activité, rôle)
 */

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/getData.php';

/**
 * Récupérer le prix d'une action en AIECoins
 * @param int $actionId ID de l'action
 * @param string $role Rôle (candidate, recruiter, administrator)
 * @param int|null $countryId ID du pays (optionnel)
 * @param int|null $activityId ID de l'activité (optionnel)
 * @return array ['success' => bool, 'price' => int|null, 'aiecourt' => object|null]
 */
function getAiecoinsPrice($actionId, $role, $countryId = null, $activityId = null) {
    try {
        $pdo = getDatabaseConnection();
        
        // Stratégie de récupération des prix (du plus spécifique au plus générique) :
        // 1. Prix spécifique : pays + activité + action + rôle
        // 2. Prix par activité : activité + action + rôle (pays = NULL)
        // 3. Prix par pays : pays + action + rôle (activité = NULL)
        // 4. Prix par défaut : action + rôle (pays = NULL, activité = NULL)
        
        $queries = [];
        
        // 1. Prix le plus spécifique (pays + activité)
        if ($countryId && $activityId) {
            $queries[] = [
                'sql' => "SELECT * FROM aiecourt 
                          WHERE action_id = :action_id 
                          AND role = :role 
                          AND country_id = :country_id 
                          AND activity_id = :activity_id 
                          LIMIT 1",
                'params' => [
                    ':action_id' => $actionId,
                    ':role' => $role,
                    ':country_id' => $countryId,
                    ':activity_id' => $activityId
                ]
            ];
        }
        
        // 2. Prix par activité uniquement
        if ($activityId) {
            $queries[] = [
                'sql' => "SELECT * FROM aiecourt 
                          WHERE action_id = :action_id 
                          AND role = :role 
                          AND country_id IS NULL 
                          AND activity_id = :activity_id 
                          LIMIT 1",
                'params' => [
                    ':action_id' => $actionId,
                    ':role' => $role,
                    ':activity_id' => $activityId
                ]
            ];
        }
        
        // 3. Prix par pays uniquement
        if ($countryId) {
            $queries[] = [
                'sql' => "SELECT * FROM aiecourt 
                          WHERE action_id = :action_id 
                          AND role = :role 
                          AND country_id = :country_id 
                          AND activity_id IS NULL 
                          LIMIT 1",
                'params' => [
                    ':action_id' => $actionId,
                    ':role' => $role,
                    ':country_id' => $countryId
                ]
            ];
        }
        
        // 4. Prix par défaut
        $queries[] = [
            'sql' => "SELECT * FROM aiecourt 
                      WHERE action_id = :action_id 
                      AND role = :role 
                      AND country_id IS NULL 
                      AND activity_id IS NULL 
                      LIMIT 1",
            'params' => [
                ':action_id' => $actionId,
                ':role' => $role
            ]
        ];
        
        // Exécuter les requêtes dans l'ordre (du plus spécifique au plus générique)
        foreach ($queries as $query) {
            $stmt = $pdo->prepare($query['sql']);
            foreach ($query['params'] as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            $stmt->execute();
            
            $result = $stmt->fetch();
            
            if ($result) {
                // Convertir en objet AIECourt
                $aiecourt = mapToObject('aiecourt', $result);
                
                return [
                    'success' => true,
                    'price' => (int)$result['price'],
                    'aiecourt' => $aiecourt
                ];
            }
        }
        
        // Aucun prix trouvé
        return [
            'success' => false,
            'price' => null,
            'aiecourt' => null,
            'message' => 'Aucun prix trouvé pour cette action'
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur récupération prix AIECoins: " . $e->getMessage());
        return [
            'success' => false,
            'price' => null,
            'aiecourt' => null,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

/**
 * Vérifier si un utilisateur a assez d'AIECoins pour une action
 * @param int $accountId ID du compte
 * @param int $actionId ID de l'action
 * @param string $role Rôle
 * @param int|null $countryId ID du pays (optionnel)
 * @param int|null $activityId ID de l'activité (optionnel)
 * @return array ['success' => bool, 'canAfford' => bool, 'price' => int, 'balance' => int]
 */
function canAffordAction($accountId, $actionId, $role, $countryId = null, $activityId = null) {
    try {
        require_once __DIR__ . '/updateAiecoins.php';
        
        // Récupérer le prix
        $priceResult = getAiecoinsPrice($actionId, $role, $countryId, $activityId);
        
        if (!$priceResult['success']) {
            return [
                'success' => false,
                'canAfford' => false,
                'price' => null,
                'balance' => null,
                'message' => $priceResult['message'] ?? 'Prix introuvable'
            ];
        }
        
        // Récupérer le solde
        $balanceResult = getAiecoinsBalance($accountId);
        
        if (!$balanceResult['success']) {
            return [
                'success' => false,
                'canAfford' => false,
                'price' => $priceResult['price'],
                'balance' => null,
                'message' => 'Impossible de récupérer le solde'
            ];
        }
        
        $canAfford = $balanceResult['balance'] >= $priceResult['price'];
        
        return [
            'success' => true,
            'canAfford' => $canAfford,
            'price' => $priceResult['price'],
            'balance' => $balanceResult['balance']
        ];
        
    } catch (Exception $e) {
        error_log("Erreur vérification moyens: " . $e->getMessage());
        return [
            'success' => false,
            'canAfford' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

/**
 * Effectuer une action payante en AIECoins
 * @param int $accountId ID du compte
 * @param int $actionId ID de l'action
 * @param string $role Rôle
 * @param int|null $countryId ID du pays (optionnel)
 * @param int|null $activityId ID de l'activité (optionnel)
 * @return array ['success' => bool, 'newBalance' => int|null, 'price' => int|null]
 */
function performPaidAction($accountId, $actionId, $role, $countryId = null, $activityId = null) {
    try {
        require_once __DIR__ . '/updateAiecoins.php';
        
        // Vérifier si l'utilisateur peut se permettre l'action
        $affordResult = canAffordAction($accountId, $actionId, $role, $countryId, $activityId);
        
        if (!$affordResult['success']) {
            return [
                'success' => false,
                'message' => $affordResult['message'] ?? 'Erreur lors de la vérification'
            ];
        }
        
        if (!$affordResult['canAfford']) {
            return [
                'success' => false,
                'message' => 'Solde insuffisant',
                'price' => $affordResult['price'],
                'balance' => $affordResult['balance']
            ];
        }
        
        // Récupérer le nom de l'action pour le log
        $action = getDataById('action', $actionId, true);
        $actionName = $action ? $action->name : "Action ID $actionId";
        
        // Débiter le compte
        $debitResult = removeAiecoins(
            $accountId, 
            $affordResult['price'], 
            $actionName
        );
        
        if (!$debitResult['success']) {
            return [
                'success' => false,
                'message' => 'Erreur lors du débit: ' . $debitResult['message']
            ];
        }
        
        return [
            'success' => true,
            'newBalance' => $debitResult['newBalance'],
            'price' => $affordResult['price'],
            'message' => 'Action effectuée avec succès'
        ];
        
    } catch (Exception $e) {
        error_log("Erreur action payante: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
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
    
    switch ($input['action']) {
        case 'getPrice':
            if (!isset($input['actionId']) || !isset($input['role'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Paramètres manquants (actionId, role)'
                ]);
                break;
            }
            
            $result = getAiecoinsPrice(
                $input['actionId'],
                $input['role'],
                $input['countryId'] ?? null,
                $input['activityId'] ?? null
            );
            
            echo json_encode($result);
            break;
            
        case 'canAfford':
            if (!isset($input['accountId']) || !isset($input['actionId']) || !isset($input['role'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Paramètres manquants (accountId, actionId, role)'
                ]);
                break;
            }
            
            $result = canAffordAction(
                $input['accountId'],
                $input['actionId'],
                $input['role'],
                $input['countryId'] ?? null,
                $input['activityId'] ?? null
            );
            
            echo json_encode($result);
            break;
            
        case 'perform':
            if (!isset($input['accountId']) || !isset($input['actionId']) || !isset($input['role'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Paramètres manquants (accountId, actionId, role)'
                ]);
                break;
            }
            
            $result = performPaidAction(
                $input['accountId'],
                $input['actionId'],
                $input['role'],
                $input['countryId'] ?? null,
                $input['activityId'] ?? null
            );
            
            echo json_encode($result);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Action non reconnue'
            ]);
    }
    exit;
}
