<?php

require_once __DIR__ . '/connectionDatabase.php';

/**
 * Delete data from a table by ID
 * @param string $table Table name
 * @param int $id Record ID to delete
 * @return bool True on success, false on failure
 */
function deleteData($table, $id) {
    try {
        // Get database connection
        $pdo = getDatabaseConnection();
        
        // Prepare SQL query
        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // Bind ID parameter
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        // Execute query
        $stmt->execute();
        
        // Return true if at least one row was affected
        return $stmt->rowCount() > 0;
        
    } catch (PDOException $e) {
        error_log("Delete error in table $table for ID $id: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete a candidate and all related data (cascade delete)
 * @param int $candidateId Candidate ID to delete
 * @return array ['success' => bool, 'message' => string]
 */
function deleteCandidate($candidateId) {
    try {
        $pdo = getDatabaseConnection();
        $pdo->beginTransaction();
        
        // 1. Get candidate data to find related IDs
        $candidateQuery = "SELECT * FROM candidate WHERE id = :id";
        $candidateStmt = $pdo->prepare($candidateQuery);
        $candidateStmt->bindValue(':id', $candidateId, PDO::PARAM_INT);
        $candidateStmt->execute();
        $candidate = $candidateStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$candidate) {
            $pdo->rollBack();
            return ['success' => false, 'message' => 'Candidat introuvable'];
        }
        
        // 2. Delete skills
        if (!empty($candidate['skill_ids'])) {
            $skillIds = json_decode($candidate['skill_ids'], true);
            if (is_array($skillIds)) {
                foreach ($skillIds as $skillId) {
                    deleteData('skill', $skillId);
                }
            }
        }
        
        // 3. Delete experiences and their missions
        if (!empty($candidate['experience_ids'])) {
            $expIds = json_decode($candidate['experience_ids'], true);
            if (is_array($expIds)) {
                foreach ($expIds as $expId) {
                    // Get missions for this experience
                    $expQuery = "SELECT missions FROM experience WHERE id = :id";
                    $expStmt = $pdo->prepare($expQuery);
                    $expStmt->bindValue(':id', $expId, PDO::PARAM_INT);
                    $expStmt->execute();
                    $exp = $expStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($exp && !empty($exp['missions'])) {
                        $missionIds = json_decode($exp['missions'], true);
                        if (is_array($missionIds)) {
                            foreach ($missionIds as $missionId) {
                                deleteData('mission', $missionId);
                            }
                        }
                    }
                    
                    deleteData('experience', $expId);
                }
            }
        }
        
        // 4. Delete formations and their knowledges
        if (!empty($candidate['formation_ids'])) {
            $formIds = json_decode($candidate['formation_ids'], true);
            if (is_array($formIds)) {
                foreach ($formIds as $formId) {
                    // Get knowledges for this formation
                    $formQuery = "SELECT knowledge FROM formation WHERE id = :id";
                    $formStmt = $pdo->prepare($formQuery);
                    $formStmt->bindValue(':id', $formId, PDO::PARAM_INT);
                    $formStmt->execute();
                    $form = $formStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($form && !empty($form['knowledge'])) {
                        $knowledgeIds = json_decode($form['knowledge'], true);
                        if (is_array($knowledgeIds)) {
                            foreach ($knowledgeIds as $knowledgeId) {
                                deleteData('knowledge', $knowledgeId);
                            }
                        }
                    }
                    
                    deleteData('formation', $formId);
                }
            }
        }
        
        // 5. Delete personalities
        if (!empty($candidate['personality_ids'])) {
            $persIds = json_decode($candidate['personality_ids'], true);
            if (is_array($persIds)) {
                foreach ($persIds as $persId) {
                    deleteData('personality', $persId);
                }
            }
        }
        
        // 6. Delete identity (languages and hobbies)
        if (!empty($candidate['identity_id'])) {
            $identityQuery = "SELECT * FROM identity WHERE id = :id";
            $identityStmt = $pdo->prepare($identityQuery);
            $identityStmt->bindValue(':id', $candidate['identity_id'], PDO::PARAM_INT);
            $identityStmt->execute();
            $identity = $identityStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($identity) {
                // Delete languages
                if (!empty($identity['language_ids'])) {
                    $langIds = json_decode($identity['language_ids'], true);
                    if (is_array($langIds)) {
                        foreach ($langIds as $langId) {
                            deleteData('language', $langId);
                        }
                    }
                }
                
                // Delete hobbies
                if (!empty($identity['hobbie_ids'])) {
                    $hobbieIds = json_decode($identity['hobbie_ids'], true);
                    if (is_array($hobbieIds)) {
                        foreach ($hobbieIds as $hobbieId) {
                            deleteData('hobbie', $hobbieId);
                        }
                    }
                }
                
                deleteData('identity', $candidate['identity_id']);
            }
        }
        
        // 7. Delete demand
        if (!empty($candidate['demand_id'])) {
            deleteData('demand', $candidate['demand_id']);
        }
        
        // 8. Delete media folder if exists
        if (!empty($candidate['media_code'])) {
            $mediaPath = __DIR__ . '/../data/candidates/media/' . $candidate['media_code'];
            if (is_dir($mediaPath)) {
                // Delete all files in the directory
                $files = glob($mediaPath . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                // Delete the directory
                rmdir($mediaPath);
            }
        }
        
        // 9. Finally, delete the candidate
        deleteData('candidate', $candidateId);
        
        $pdo->commit();
        
        return ['success' => true, 'message' => 'Candidat et toutes ses données supprimés avec succès'];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error deleting candidate $candidateId: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erreur lors de la suppression: ' . $e->getMessage()];
    }
}

// Point d'entrée pour les requêtes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['table']) || !isset($input['id'])) {
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        exit;
    }
    
    // If deleting a candidate, use cascade delete
    if ($input['table'] === 'candidate') {
        $result = deleteCandidate($input['id']);
        echo json_encode($result);
    } else {
        $success = deleteData($input['table'], $input['id']);
        echo json_encode(['success' => $success, 'message' => $success ? 'Supprimé avec succès' : 'Échec de la suppression']);
    }
    
    exit;
}
