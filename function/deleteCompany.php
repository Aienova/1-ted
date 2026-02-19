<?php
/**
 * Supprimer une entreprise et toutes ses données associées
 * 
 * Cette fonction supprime en cascade:
 * 1. Toutes les offres d'emploi de l'entreprise (table job)
 * 2. Tous les comptes associés à l'entreprise (table account)
 * 3. Les contacts de l'entreprise (table contact)
 * 4. L'entreprise elle-même (table company)
 */

require_once __DIR__ . '/connectionDatabase.php';

header('Content-Type: application/json');

// Vérifier la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Méthode non autorisée'
    ]);
    exit;
}

// Récupérer l'ID de l'entreprise
$companyId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($companyId <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'ID d\'entreprise invalide'
    ]);
    exit;
}

try {
    $pdo = getDatabaseConnection();
    
    // Démarrer une transaction pour garantir l'intégrité
    $pdo->beginTransaction();
    
    // 1. Récupérer le contact_id avant de supprimer l'entreprise
    $stmt = $pdo->prepare("SELECT contact_id FROM company WHERE id = :id");
    $stmt->execute([':id' => $companyId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Entreprise non trouvée'
        ]);
        exit;
    }
    
    $contactId = $company['contact_id'];
    
    // 2. Supprimer toutes les offres d'emploi de l'entreprise
    $stmt = $pdo->prepare("DELETE FROM job WHERE company_id = :company_id");
    $stmt->execute([':company_id' => $companyId]);
    $jobsDeleted = $stmt->rowCount();
    
    // 3. Supprimer tous les comptes associés à l'entreprise
    // Les préférences, applications et suggestions seront supprimées automatiquement
    // grâce aux contraintes FOREIGN KEY ON DELETE CASCADE
    $stmt = $pdo->prepare("DELETE FROM account WHERE company_id = :company_id");
    $stmt->execute([':company_id' => $companyId]);
    $accountsDeleted = $stmt->rowCount();
    
    // 4. Supprimer l'entreprise
    $stmt = $pdo->prepare("DELETE FROM company WHERE id = :id");
    $stmt->execute([':id' => $companyId]);
    
    // 5. Supprimer le contact si présent
    if ($contactId) {
        $stmt = $pdo->prepare("DELETE FROM contact WHERE id = :id");
        $stmt->execute([':id' => $contactId]);
    }
    
    // Valider la transaction
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Entreprise supprimée avec succès',
        'deleted' => [
            'jobs' => $jobsDeleted,
            'accounts' => $accountsDeleted,
            'contact' => $contactId ? true : false
        ]
    ]);
    
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la suppression: ' . $e->getMessage()
    ]);
}
