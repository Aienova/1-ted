<?php

session_start();

require_once __DIR__ . '/connectionDatabase.php';

/**
 * Crée une nouvelle offre d'emploi pour une entreprise connectée
 * Gère automatiquement les company_id et contact_id depuis la session
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // Vérifier que l'utilisateur est connecté en tant qu'entreprise
        if (!isset($_SESSION['accountId']) || !isset($_SESSION['role']) || 
            ($_SESSION['role'] !== 'company' && $_SESSION['role'] !== 'recruiter')) {
            echo json_encode(['success' => false, 'message' => 'Vous devez être connecté en tant qu\'entreprise']);
            exit;
        }
        
        $pdo = getDatabaseConnection();
        
        // Récupérer le company_id et contact_id depuis le compte
        $accountQuery = "SELECT company_id FROM account WHERE id = :account_id";
        $accountStmt = $pdo->prepare($accountQuery);
        $accountStmt->bindValue(':account_id', $_SESSION['accountId'], PDO::PARAM_INT);
        $accountStmt->execute();
        $accountData = $accountStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$accountData || !$accountData['company_id']) {
            echo json_encode(['success' => false, 'message' => 'Aucune entreprise associée à votre compte']);
            exit;
        }
        
        $companyId = $accountData['company_id'];
        
        // Récupérer le contact_id depuis company
        $companyQuery = "SELECT contact_id FROM company WHERE id = :company_id";
        $companyStmt = $pdo->prepare($companyQuery);
        $companyStmt->bindValue(':company_id', $companyId, PDO::PARAM_INT);
        $companyStmt->execute();
        $companyData = $companyStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$companyData || !$companyData['contact_id']) {
            echo json_encode(['success' => false, 'message' => 'Aucun contact associé à votre entreprise']);
            exit;
        }
        
        $contactId = $companyData['contact_id'];
        
        // Récupérer les données de la requête
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            exit;
        }
        
        $pdo->beginTransaction();
        
        $requirementId = null;
        
        // ÉTAPE 1: Insérer requirement si présent
        if (isset($input['requirement']) && !empty($input['requirement'])) {
            $requirementData = [];
            
            foreach ($input['requirement'] as $column => $value) {
                if ($value !== null && $value !== '' && $value !== '--') {
                    $requirementData[$column] = $value;
                }
            }
            
            if (!empty($requirementData)) {
                $reqColumns = array_keys($requirementData);
                $reqPlaceholders = array_map(fn($col) => ":$col", $reqColumns);
                
                $reqSql = "INSERT INTO requirement (" . implode(', ', $reqColumns) . ") 
                          VALUES (" . implode(', ', $reqPlaceholders) . ")";
                
                $reqStmt = $pdo->prepare($reqSql);
                
                foreach ($requirementData as $column => $value) {
                    $reqStmt->bindValue(":$column", $value);
                }
                
                $reqStmt->execute();
                $requirementId = $pdo->lastInsertId();
            }
        }
        
        // ÉTAPE 2: Insérer job
        if (!isset($input['job']) || empty($input['job'])) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Les informations du poste sont requises']);
            exit;
        }
        
        $jobData = [
            'company_id' => $companyId,
            'contact_id' => $contactId,
            'requirement_id' => $requirementId,
            'open' => 1,
            'approved' => null
        ];
        
        foreach ($input['job'] as $column => $value) {
            if ($column === 'need_vehicle') {
                $jobData[$column] = $value ? 1 : 0;
            } elseif ($value !== null && $value !== '' && $value !== '--') {
                $jobData[$column] = $value;
            }
        }
        
        // Validation: au moins le titre est requis
        if (empty($jobData['title'])) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Le titre du poste est requis']);
            exit;
        }
        
        $jobColumns = array_keys($jobData);
        $jobPlaceholders = array_map(fn($col) => ":$col", $jobColumns);
        
        $jobSql = "INSERT INTO job (" . implode(', ', $jobColumns) . ") 
                   VALUES (" . implode(', ', $jobPlaceholders) . ")";
        
        $jobStmt = $pdo->prepare($jobSql);
        
        foreach ($jobData as $column => $value) {
            $jobStmt->bindValue(":$column", $value);
        }
        
        $jobStmt->execute();
        $jobId = $pdo->lastInsertId();
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Offre d\'emploi créée avec succès',
            'job_id' => $jobId,
            'company_id' => $companyId,
            'contact_id' => $contactId,
            'requirement_id' => $requirementId
        ]);
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Erreur création offre d'emploi: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la création: ' . $e->getMessage()
        ]);
    }
    
    exit;
}
