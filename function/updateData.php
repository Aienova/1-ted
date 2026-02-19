<?php

require_once __DIR__ . '/connectionDatabase.php';

// Désactiver l'affichage des erreurs PHP pour ne pas casser le JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Gérer les requêtes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Log pour debug
        error_log("UPDATE - Input reçu: " . print_r($input, true));
        
        if (isset($input['table']) && isset($input['id']) && isset($input['data'])) {
            $success = updateMultipleData($input['table'], $input['id'], $input['data']);
            
            if ($success) {
                error_log("UPDATE - Succès");
                echo json_encode(['success' => true]);
            } else {
                error_log("UPDATE - Échec");
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
            }
        } else {
            error_log("UPDATE - Paramètres manquants");
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        }
    } catch (Exception $e) {
        error_log("UPDATE - Exception: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

/**
 * Update data in a table by ID
 * @param string $table Table name
 * @param int $id Record ID to update
 * @param string $column Column name to update
 * @param mixed $value New value for the column
 * @return bool True on success, false on failure
 */
function updateData($table, $id, $column, $value) {
    try {
        // Get database connection
        $pdo = getDatabaseConnection();
        
        // Prepare SQL query
        $sql = "UPDATE $table SET $column = :value WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindValue(':value', $value);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        // Execute query
        $stmt->execute();
        
        // Return true if at least one row was affected
        return $stmt->rowCount() > 0;
        
    } catch (PDOException $e) {
        error_log("Update error in table $table for ID $id: " . $e->getMessage());
        return false;
    }
}

/**
 * Update multiple columns in a table by ID
 * @param string $table Table name
 * @param int $id Record ID to update
 * @param array $data Associative array of column => value
 * @return bool True on success, false on failure
 */
function updateMultipleData($table, $id, $data) {
    try {
        // Get database connection
        $pdo = getDatabaseConnection();
        
        // Retirer le champ 'id' du tableau data s'il existe (conflit avec WHERE id = :id)
        unset($data['id']);
        
        // Si aucune donnée à mettre à jour, retourner true
        if (empty($data)) {
            return true;
        }
        
        // Prepare SET clause with backticks for column names
        $setParts = [];
        $paramMapping = []; // Mapper les colonnes vers des noms de paramètres valides
        
        foreach ($data as $column => $value) {
            // Remplacer les tirets par des underscores pour les paramètres PDO
            $paramName = str_replace('-', '_', $column);
            $setParts[] = "`$column` = :$paramName";
            $paramMapping[$paramName] = $value;
        }
        $setClause = implode(', ', $setParts);
        
        // Prepare SQL query
        $sql = "UPDATE $table SET $setClause WHERE id = :id";
        error_log("SQL UPDATE: $sql");
        error_log("Data: " . print_r($data, true));
        error_log("ID: $id");
        
        $stmt = $pdo->prepare($sql);
        
        // Bind values using mapped parameter names
        foreach ($paramMapping as $paramName => $value) {
            $stmt->bindValue(":$paramName", $value);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        // Execute query
        $stmt->execute();
        
        // Retourner true même si aucune ligne n'a été modifiée (données identiques)
        // rowCount() == 0 n'est pas une erreur
        return true;
        
    } catch (PDOException $e) {
        error_log("Update multiple error in table $table for ID $id: " . $e->getMessage());
        throw new Exception("Erreur SQL: " . $e->getMessage());
    }
}
