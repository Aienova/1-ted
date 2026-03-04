<?php
/**
 * Récupère les IDs de l'entreprise et du contact de l'utilisateur connecté
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Démarrer la session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Vérifier l'authentification
    if (!isset($_SESSION['accountId'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Non authentifié'
        ]);
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer l'ID de l'entreprise depuis la session ou la base de données
    if (isset($_SESSION['company_id'])) {
        $companyId = $_SESSION['company_id'];
    } else {
        $stmt = $db->prepare("SELECT company_id FROM account WHERE id = :account_id");
        $stmt->execute([':account_id' => $_SESSION['accountId']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $companyId = $result['company_id'] ?? null;
        
        if ($companyId) {
            $_SESSION['company_id'] = $companyId;
        }
    }
    
    if (!$companyId) {
        echo json_encode([
            'success' => false,
            'error' => 'Aucune entreprise associée à ce compte'
        ]);
        exit;
    }
    
    // Récupérer le contact_id et le nom de l'entreprise
    $stmt = $db->prepare("SELECT contact_id, name FROM company WHERE id = :company_id");
    $stmt->execute([':company_id' => $companyId]);
    $companyData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$companyData) {
        echo json_encode([
            'success' => false,
            'error' => 'Entreprise non trouvée'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'company_id' => (int)$companyId,
        'contact_id' => $companyData['contact_id'] ? (int)$companyData['contact_id'] : null,
        'company_name' => $companyData['name'] ?? 'Entreprise'
    ]);
    
} catch (PDOException $e) {
    error_log("Erreur getMyCompanyIds: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Erreur getMyCompanyIds: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur: ' . $e->getMessage()
    ]);
}
