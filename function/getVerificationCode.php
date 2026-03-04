<?php
/**
 * Endpoint pour vérifier un code de vérification d'email
 * Accessible via: /function/getVerificationCode.php?code=XXXXXXXXXXXX
 * 
 * Retourne:
 * - success: true/false
 * - verified: true si le compte a été vérifié avec succès
 * - expired: true si le code a expiré
 * - message: message d'erreur ou de succès
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Récupérer le code depuis l'URL
    $code = isset($_GET['code']) ? trim($_GET['code']) : '';
    
    if (empty($code)) {
        echo json_encode([
            'success' => false,
            'message' => 'Code de vérification manquant'
        ]);
        exit;
    }
    
    // Valider le format du code (12 caractères alphanumériques)
    if (!preg_match('/^[a-zA-Z0-9]{12}$/', $code)) {
        echo json_encode([
            'success' => false,
            'message' => 'Format de code invalide'
        ]);
        exit;
    }
    
    // Connexion à la base de données
    $pdo = getDatabaseConnection();
    
    // Rechercher le compte avec ce code de vérification
    $sql = "SELECT id, email, verified, verification_expire_date 
            FROM account 
            WHERE verification_code = :code";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$account) {
        echo json_encode([
            'success' => false,
            'message' => 'Code de vérification invalide ou déjà utilisé'
        ]);
        exit;
    }
    
    // Vérifier si l'email est déjà vérifié
    if ($account['verified'] == 1) {
        echo json_encode([
            'success' => true,
            'verified' => true,
            'message' => 'Email déjà vérifié'
        ]);
        exit;
    }
    
    // Vérifier si le code a expiré
    $now = new DateTime();
    $expireDate = new DateTime($account['verification_expire_date']);
    
    if ($now > $expireDate) {
        echo json_encode([
            'success' => true,
            'expired' => true,
            'message' => 'Le code de vérification a expiré (validité: 10 minutes)'
        ]);
        exit;
    }
    
    // Code valide et non expiré : marquer le compte comme vérifié
    $updateSql = "UPDATE account 
                  SET verified = 1, 
                      verification_code = NULL, 
                      verification_expire_date = NULL 
                  WHERE id = :id";
    
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindValue(':id', $account['id'], PDO::PARAM_INT);
    $updateStmt->execute();
    
    // Succès
    echo json_encode([
        'success' => true,
        'verified' => true,
        'message' => 'Email vérifié avec succès',
        'email' => $account['email']
    ]);
    
} catch (PDOException $e) {
    error_log("Erreur lors de la vérification du code: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur lors de la vérification'
    ]);
} catch (Exception $e) {
    error_log("Erreur: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Une erreur est survenue'
    ]);
}
