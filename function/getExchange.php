<?php
/**
 * Gestion des taux de change AIECoins
 * Conversion entre AIECoins et différentes devises
 */

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/getData.php';

/**
 * Récupérer le taux de change pour une devise
 * @param string $currencyCode Code de la devise (EUR, USD, GBP, etc.)
 * @param int|null $countryId ID du pays (optionnel)
 * @param int|null $activityId ID de l'activité (optionnel)
 * @return array ['success' => bool, 'rate' => float|null, 'exchange' => object|null]
 */
function getExchangeRate($currencyCode, $countryId = null, $activityId = null) {
    try {
        $pdo = getDatabaseConnection();
        
        // Stratégie de récupération des taux (du plus spécifique au plus générique) :
        // 1. Taux spécifique : pays + activité + devise
        // 2. Taux par activité : activité + devise (pays = NULL)
        // 3. Taux par pays : pays + devise (activité = NULL)
        // 4. Taux par défaut : devise (pays = NULL, activité = NULL)
        
        $queries = [];
        
        // 1. Taux le plus spécifique (pays + activité)
        if ($countryId && $activityId) {
            $queries[] = [
                'sql' => "SELECT * FROM exchange 
                          WHERE currency_code = :currency_code 
                          AND country_id = :country_id 
                          AND activity_id = :activity_id 
                          LIMIT 1",
                'params' => [
                    ':currency_code' => $currencyCode,
                    ':country_id' => $countryId,
                    ':activity_id' => $activityId
                ]
            ];
        }
        
        // 2. Taux par activité uniquement
        if ($activityId) {
            $queries[] = [
                'sql' => "SELECT * FROM exchange 
                          WHERE currency_code = :currency_code 
                          AND country_id IS NULL 
                          AND activity_id = :activity_id 
                          LIMIT 1",
                'params' => [
                    ':currency_code' => $currencyCode,
                    ':activity_id' => $activityId
                ]
            ];
        }
        
        // 3. Taux par pays uniquement
        if ($countryId) {
            $queries[] = [
                'sql' => "SELECT * FROM exchange 
                          WHERE currency_code = :currency_code 
                          AND country_id = :country_id 
                          AND activity_id IS NULL 
                          LIMIT 1",
                'params' => [
                    ':currency_code' => $currencyCode,
                    ':country_id' => $countryId
                ]
            ];
        }
        
        // 4. Taux par défaut
        $queries[] = [
            'sql' => "SELECT * FROM exchange 
                      WHERE currency_code = :currency_code 
                      AND country_id IS NULL 
                      AND activity_id IS NULL 
                      LIMIT 1",
            'params' => [
                ':currency_code' => $currencyCode
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
                // Convertir en objet Exchange
                $exchange = mapToObject('exchange', $result);
                
                return [
                    'success' => true,
                    'rate' => (int)$result['rate'],
                    'currencyCode' => $result['currency_code'],
                    'exchange' => $exchange
                ];
            }
        }
        
        // Aucun taux trouvé
        return [
            'success' => false,
            'rate' => null,
            'currencyCode' => $currencyCode,
            'exchange' => null,
            'message' => 'Aucun taux de change trouvé pour cette devise'
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur récupération taux de change: " . $e->getMessage());
        return [
            'success' => false,
            'rate' => null,
            'exchange' => null,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

/**
 * Convertir AIECoins en devise
 * @param int $aiecoins Montant en AIECoins
 * @param string $currencyCode Code de la devise cible
 * @param int|null $countryId ID du pays (optionnel)
 * @param int|null $activityId ID de l'activité (optionnel)
 * @return array ['success' => bool, 'amount' => float|null, 'currency' => string]
 */
function convertAiecoinsToMoney($aiecoins, $currencyCode, $countryId = null, $activityId = null) {
    try {
        $rateResult = getExchangeRate($currencyCode, $countryId, $activityId);
        
        if (!$rateResult['success']) {
            return [
                'success' => false,
                'amount' => null,
                'currency' => $currencyCode,
                'message' => $rateResult['message'] ?? 'Taux introuvable'
            ];
        }
        
        // Calcul : AIECoins / taux = montant en devise
        // Exemple : 100 AIECoins / 25 (taux EUR) = 4€
        $amount = $aiecoins / $rateResult['rate'];
        
        return [
            'success' => true,
            'amount' => round($amount, 2),
            'currency' => $currencyCode,
            'rate' => $rateResult['rate'],
            'aiecoins' => $aiecoins
        ];
        
    } catch (Exception $e) {
        error_log("Erreur conversion AIECoins vers devise: " . $e->getMessage());
        return [
            'success' => false,
            'amount' => null,
            'currency' => $currencyCode,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

/**
 * Convertir une devise en AIECoins
 * @param float $amount Montant en devise
 * @param string $currencyCode Code de la devise source
 * @param int|null $countryId ID du pays (optionnel)
 * @param int|null $activityId ID de l'activité (optionnel)
 * @return array ['success' => bool, 'aiecoins' => int|null]
 */
function convertMoneyToAiecoins($amount, $currencyCode, $countryId = null, $activityId = null) {
    try {
        $rateResult = getExchangeRate($currencyCode, $countryId, $activityId);
        
        if (!$rateResult['success']) {
            return [
                'success' => false,
                'aiecoins' => null,
                'message' => $rateResult['message'] ?? 'Taux introuvable'
            ];
        }
        
        // Calcul : montant * taux = AIECoins
        // Exemple : 4€ * 25 (taux EUR) = 100 AIECoins
        $aiecoins = $amount * $rateResult['rate'];
        
        return [
            'success' => true,
            'aiecoins' => (int)round($aiecoins),
            'amount' => $amount,
            'currency' => $currencyCode,
            'rate' => $rateResult['rate']
        ];
        
    } catch (Exception $e) {
        error_log("Erreur conversion devise vers AIECoins: " . $e->getMessage());
        return [
            'success' => false,
            'aiecoins' => null,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

/**
 * Récupérer tous les taux de change disponibles
 * @return array Liste des taux de change par devise
 */
function getAllExchangeRates() {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT DISTINCT currency_code, rate 
                FROM exchange 
                WHERE country_id IS NULL AND activity_id IS NULL
                ORDER BY currency_code";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $rates = [];
        while ($row = $stmt->fetch()) {
            $rates[$row['currency_code']] = (int)$row['rate'];
        }
        
        return [
            'success' => true,
            'rates' => $rates
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur récupération tous les taux: " . $e->getMessage());
        return [
            'success' => false,
            'rates' => [],
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

/**
 * Acheter des AIECoins avec de l'argent réel
 * @param int $accountId ID du compte
 * @param float $amount Montant payé
 * @param string $currencyCode Code de la devise
 * @param int|null $countryId ID du pays (optionnel)
 * @param int|null $activityId ID de l'activité (optionnel)
 * @return array ['success' => bool, 'aiecoins' => int, 'newBalance' => int|null]
 */
function purchaseAiecoins($accountId, $amount, $currencyCode, $countryId = null, $activityId = null) {
    try {
        require_once __DIR__ . '/updateAiecoins.php';
        
        // Convertir le montant en AIECoins
        $conversionResult = convertMoneyToAiecoins($amount, $currencyCode, $countryId, $activityId);
        
        if (!$conversionResult['success']) {
            return [
                'success' => false,
                'message' => $conversionResult['message'] ?? 'Erreur de conversion'
            ];
        }
        
        $aiecoinsToAdd = $conversionResult['aiecoins'];
        
        // Ajouter les AIECoins au compte
        $addResult = addAiecoins(
            $accountId, 
            $aiecoinsToAdd, 
            "Achat de {$aiecoinsToAdd} AIECoins pour {$amount} {$currencyCode}"
        );
        
        if (!$addResult['success']) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'ajout des AIECoins: ' . $addResult['message']
            ];
        }
        
        return [
            'success' => true,
            'aiecoins' => $aiecoinsToAdd,
            'newBalance' => $addResult['newBalance'],
            'amount' => $amount,
            'currency' => $currencyCode,
            'rate' => $conversionResult['rate'],
            'message' => "Achat réussi : +{$aiecoinsToAdd} AIECoins"
        ];
        
    } catch (Exception $e) {
        error_log("Erreur achat AIECoins: " . $e->getMessage());
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
        case 'getRate':
            if (!isset($input['currencyCode'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Code devise manquant'
                ]);
                break;
            }
            
            $result = getExchangeRate(
                $input['currencyCode'],
                $input['countryId'] ?? null,
                $input['activityId'] ?? null
            );
            
            echo json_encode($result);
            break;
            
        case 'convertToMoney':
            if (!isset($input['aiecoins']) || !isset($input['currencyCode'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Paramètres manquants (aiecoins, currencyCode)'
                ]);
                break;
            }
            
            $result = convertAiecoinsToMoney(
                $input['aiecoins'],
                $input['currencyCode'],
                $input['countryId'] ?? null,
                $input['activityId'] ?? null
            );
            
            echo json_encode($result);
            break;
            
        case 'convertToAiecoins':
            if (!isset($input['amount']) || !isset($input['currencyCode'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Paramètres manquants (amount, currencyCode)'
                ]);
                break;
            }
            
            $result = convertMoneyToAiecoins(
                $input['amount'],
                $input['currencyCode'],
                $input['countryId'] ?? null,
                $input['activityId'] ?? null
            );
            
            echo json_encode($result);
            break;
            
        case 'getAllRates':
            $result = getAllExchangeRates();
            echo json_encode($result);
            break;
            
        case 'purchase':
            if (!isset($input['accountId']) || !isset($input['amount']) || !isset($input['currencyCode'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Paramètres manquants (accountId, amount, currencyCode)'
                ]);
                break;
            }
            
            $result = purchaseAiecoins(
                $input['accountId'],
                $input['amount'],
                $input['currencyCode'],
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
