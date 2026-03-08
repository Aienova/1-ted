<?php
/**
 * Crée un contact avec les informations du compte et le lie à l'entreprise
 * Cette fonction est appelée lors de la création d'un compte entreprise dans registerPopup
 * 
 * @param int $companyId - L'ID de l'entreprise
 * @param array $accountData - Les données du compte (first_name, last_name, email, phone)
 * @return array ['success' => bool, 'contactId' => int|null, 'message' => string]
 */

header('Content-Type: application/json');
@ini_set('display_errors', 0);
@error_reporting(E_ERROR | E_PARSE);

require_once __DIR__ . '/connectionDatabase.php';

function linkAccountContact($companyId, $accountData) {
    try {
        $pdo = getDatabaseConnection();
        
        // Activer l'autocommit pour être sûr que les changements sont persistés
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        
        // Log pour déboguer
        error_log("linkAccountContact appelé avec companyId: $companyId");
        
        // Validation des données
        if (empty($companyId)) {
            return [
                'success' => false,
                'contactId' => null,
                'message' => 'ID de l\'entreprise requis'
            ];
        }
        
        if (empty($accountData['first_name']) || empty($accountData['last_name'])) {
            return [
                'success' => false,
                'contactId' => null,
                'message' => 'Prénom et nom requis'
            ];
        }
        
        if (empty($accountData['email']) && empty($accountData['phone'])) {
            return [
                'success' => false,
                'contactId' => null,
                'message' => 'Email ou téléphone requis'
            ];
        }
        
        // Créer le contact
        $sql = "INSERT INTO contact (first_name, last_name, email, phone) 
                VALUES (:first_name, :last_name, :email, :phone)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':first_name', $accountData['first_name'], PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $accountData['last_name'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $accountData['email'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $accountData['phone'] ?? null, PDO::PARAM_STR);
        
        if (!$stmt->execute()) {
            throw new Exception('Impossible de créer le contact');
        }
        
        $contactId = $pdo->lastInsertId();
        
        // Vérifier que l'entreprise existe
        $checkSql = "SELECT id FROM company WHERE id = :company_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindValue(':company_id', $companyId, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if (!$checkStmt->fetch()) {
            throw new Exception("Entreprise avec l'ID $companyId non trouvée");
        }
        
        // Lier le contact à l'entreprise
        $updateSql = "UPDATE company SET contact_id = :contact_id WHERE id = :company_id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindValue(':contact_id', $contactId, PDO::PARAM_INT);
        $updateStmt->bindValue(':company_id', $companyId, PDO::PARAM_INT);
        
        if (!$updateStmt->execute()) {
            throw new Exception('Impossible de lier le contact à l\'entreprise');
        }
        
        // Vérifier que la mise à jour a bien affecté une ligne
        if ($updateStmt->rowCount() === 0) {
            throw new Exception("Aucune ligne n'a été mise à jour. Company ID: $companyId, Contact ID: $contactId");
        }
        
        // Vérifier que le contact_id a bien été enregistré
        $verifySql = "SELECT contact_id FROM company WHERE id = :company_id";
        $verifyStmt = $pdo->prepare($verifySql);
        $verifyStmt->bindValue(':company_id', $companyId, PDO::PARAM_INT);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->fetch();
        
        if (!$verifyResult || $verifyResult['contact_id'] != $contactId) {
            throw new Exception("Vérification échouée - contact_id non enregistré. Attendu: $contactId, Obtenu: " . ($verifyResult['contact_id'] ?? 'NULL'));
        }
        
        error_log("Contact $contactId lié avec succès à la company $companyId");
        
        return [
            'success' => true,
            'contactId' => $contactId,
            'companyId' => $companyId,
            'rowsAffected' => $updateStmt->rowCount(),
            'message' => 'Contact créé et lié à l\'entreprise avec succès'
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'contactId' => null,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

// Si appelé directement via HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonInput = file_get_contents('php://input');
    $data = json_decode($jsonInput, true);
    
    $companyId = $data['company_id'] ?? null;
    $accountData = [
        'first_name' => $data['first_name'] ?? null,
        'last_name' => $data['last_name'] ?? null,
        'email' => $data['email'] ?? null,
        'phone' => $data['phone'] ?? null
    ];
    
    $result = linkAccountContact($companyId, $accountData);
    echo json_encode($result);
    exit;
}
