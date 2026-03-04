<?php
/**
 * Met à jour les informations du profil de l'entreprise connectée
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';

try {
    // Démarrer la session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Vérifier l'authentification
    if (!isset($_SESSION['accountId']) || !isset($_SESSION['role'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Non authentifié'
        ]);
        exit;
    }
    
    // Accepter à la fois les rôles 'recruiter' et 'company'
    if ($_SESSION['role'] !== 'recruiter' && $_SESSION['role'] !== 'company') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Accès réservé aux entreprises et recruteurs'
        ]);
        exit;
    }
    
    $db = getDatabaseConnection();
    
    // Récupérer l'ID de l'entreprise
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
    
    // Récupérer les données JSON du corps de la requête
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Données invalides'
        ]);
        exit;
    }
    
    $db->beginTransaction();
    
    try {
        // 1. Mettre à jour les informations de l'entreprise
        if (isset($data['company_info'])) {
            $info = $data['company_info'];
            
            $updateFields = [];
            $params = [':company_id' => $companyId];
            
            if (isset($info['name'])) {
                $updateFields[] = "name = :name";
                $params[':name'] = $info['name'];
            }
            if (isset($info['nation_register_number'])) {
                $updateFields[] = "nation_register_number = :nation_register_number";
                $params[':nation_register_number'] = $info['nation_register_number'];
            }
            if (isset($info['domain'])) {
                $updateFields[] = "domain = :domain";
                $params[':domain'] = $info['domain'];
            }
            // Size - colonne à ajouter dans la base de données
            // if (isset($info['size'])) {
            //     $updateFields[] = "size = :size";
            //     $params[':size'] = $info['size'];
            // }
            if (isset($info['website'])) {
                $updateFields[] = "website = :website";
                $params[':website'] = $info['website'];
            }
            if (isset($info['hq_location'])) {
                $updateFields[] = "hq_location = :hq_location";
                $params[':hq_location'] = $info['hq_location'];
            }
            if (isset($info['description'])) {
                $updateFields[] = "description = :description";
                $params[':description'] = $info['description'];
            }
            
            if (!empty($updateFields)) {
                $sql = "UPDATE company SET " . implode(', ', $updateFields) . " WHERE id = :company_id";
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            }
        }
        
        // 2. Mettre à jour les informations de contact
        if (isset($data['contact'])) {
            $contact = $data['contact'];
            
            // Récupérer l'ID du contact existant
            $stmt = $db->prepare("SELECT contact_id FROM company WHERE id = :company_id");
            $stmt->execute([':company_id' => $companyId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $contactId = $result['contact_id'] ?? null;
            
            if ($contactId) {
                // Mettre à jour le contact existant
                $updateFields = [];
                $params = [':contact_id' => $contactId];
                
                if (isset($contact['first_name'])) {
                    $updateFields[] = "first_name = :first_name";
                    $params[':first_name'] = $contact['first_name'];
                }
                if (isset($contact['last_name'])) {
                    $updateFields[] = "last_name = :last_name";
                    $params[':last_name'] = $contact['last_name'];
                }
                if (isset($contact['email'])) {
                    $updateFields[] = "email = :email";
                    $params[':email'] = $contact['email'];
                }
                if (isset($contact['phone'])) {
                    $updateFields[] = "phone = :phone";
                    $params[':phone'] = $contact['phone'];
                }
                // Position - colonne à ajouter dans la table contact
                // if (isset($contact['position'])) {
                //     $updateFields[] = "position = :position";
                //     $params[':position'] = $contact['position'];
                // }
                
                if (!empty($updateFields)) {
                    $sql = "UPDATE contact SET " . implode(', ', $updateFields) . " WHERE id = :contact_id";
                    $stmt = $db->prepare($sql);
                    $stmt->execute($params);
                }
            } else {
                // Créer un nouveau contact (sans position pour l'instant)
                $stmt = $db->prepare("
                    INSERT INTO contact (first_name, last_name, email, phone)
                    VALUES (:first_name, :last_name, :email, :phone)
                ");
                $stmt->execute([
                    ':first_name' => $contact['first_name'] ?? '',
                    ':last_name' => $contact['last_name'] ?? '',
                    ':email' => $contact['email'] ?? '',
                    ':phone' => $contact['phone'] ?? ''
                ]);
                
                $contactId = $db->lastInsertId();
                
                // Associer le contact à l'entreprise
                $stmt = $db->prepare("UPDATE company SET contact_id = :contact_id WHERE id = :company_id");
                $stmt->execute([
                    ':contact_id' => $contactId,
                    ':company_id' => $companyId
                ]);
            }
        }
        
        // 3. Mettre à jour les réseaux sociaux
        // TODO: Ajouter une colonne social_media (JSON) dans la table company
        // if (isset($data['social'])) {
        //     $social = $data['social'];
        //     $socialJson = json_encode($social);
        //     $stmt = $db->prepare("UPDATE company SET social_media = :social WHERE id = :company_id");
        //     $stmt->execute([
        //         ':social' => $socialJson,
        //         ':company_id' => $companyId
        //     ]);
        // }
        
        // 4. Mettre à jour les avantages (stockés en JSON)
        // TODO: Ajouter une colonne benefits (JSON) dans la table company
        // if (isset($data['benefits'])) {
        //     $benefits = $data['benefits'];
        //     $benefitsJson = json_encode($benefits);
        //     $stmt = $db->prepare("UPDATE company SET benefits = :benefits WHERE id = :company_id");
        //     $stmt->execute([
        //         ':benefits' => $benefitsJson,
        //         ':company_id' => $companyId
        //     ]);
        // }
        
        $db->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Profil mis à jour avec succès'
        ]);
        
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
