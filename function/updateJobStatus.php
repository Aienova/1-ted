<?php

session_start();

require_once __DIR__ . '/connectionDatabase.php';

/**
 * Met à jour le statut d'une offre d'emploi (ouvert/fermé)
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['job_id']) || !isset($input['open'])) {
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        exit;
    }
    
    try {
        $pdo = getDatabaseConnection();
        
        // Récupérer le company_id depuis la session si connecté
        $companyId = null;
        if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'company') {
            $accountQuery = "SELECT company_id FROM account WHERE id = :account_id";
            $accountStmt = $pdo->prepare($accountQuery);
            $accountStmt->bindValue(':account_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $accountStmt->execute();
            $account = $accountStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($account && !empty($account['company_id'])) {
                $companyId = $account['company_id'];
            }
        }
        
        // Vérifier que l'entreprise est propriétaire de cette offre
        if ($companyId !== null) {
            $checkQuery = "SELECT company_id FROM job WHERE id = :job_id";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->bindValue(':job_id', $input['job_id'], PDO::PARAM_INT);
            $checkStmt->execute();
            $job = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$job) {
                echo json_encode(['success' => false, 'message' => 'Offre introuvable']);
                exit;
            }
            
            if ($job['company_id'] != $companyId) {
                echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas autorisé à modifier cette offre']);
                exit;
            }
        }
        
        // Mettre à jour le statut
        $updateQuery = "UPDATE job SET open = :open WHERE id = :job_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindValue(':open', $input['open'] ? 1 : 0, PDO::PARAM_INT);
        $updateStmt->bindValue(':job_id', $input['job_id'], PDO::PARAM_INT);
        $updateStmt->execute();
        
        $status = $input['open'] ? 'ouverte' : 'fermée';
        echo json_encode([
            'success' => true, 
            'message' => "Offre d'emploi $status avec succès"
        ]);
        
    } catch (Exception $e) {
        error_log("Error updating job status: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
        ]);
    }
    
    exit;
}
