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
        error_log("INSERT - Input reçu: " . print_r($input, true));
        
        if (isset($input['table']) && isset($input['data'])) {
            $id = insertData($input['table'], $input['data']);
            
            if ($id) {
                error_log("INSERT - Succès, ID: $id");
                echo json_encode(['success' => true, 'id' => $id]);
            } else {
                error_log("INSERT - Échec");
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'insertion']);
            }
        } else {
            error_log("INSERT - Paramètres manquants");
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        }
    } catch (Exception $e) {
        error_log("INSERT - Exception: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

/**
 * Insert data into a table
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @return int|false Last inserted ID or false on failure
 */
function insertData($table, $data) {
    try {
        // Get database connection
        $pdo = getDatabaseConnection();
        
        // Prepare column names and placeholders
        $columns = array_keys($data);
        
        // Créer des noms de paramètres valides (remplacer - par _)
        $paramNames = array_map(function($col) { return str_replace('-', '_', $col); }, $columns);
        $placeholders = ':' . implode(', :', $paramNames);
        
        // Prepare SQL query with backticks for column names
        $columnsListEscaped = implode(', ', array_map(function($col) { return "`$col`"; }, $columns));
        $sql = "INSERT INTO $table ($columnsListEscaped) VALUES ($placeholders)";
        error_log("SQL INSERT: $sql");
        error_log("Data: " . print_r($data, true));
        
        $stmt = $pdo->prepare($sql);
        
        // Bind values using mapped parameter names
        $i = 0;
        foreach ($data as $column => $value) {
            $paramName = $paramNames[$i];
            $stmt->bindValue(":$paramName", $value);
            $i++;
        }
        
        // Execute query
        $stmt->execute();
        
        // Return last inserted ID
        return $pdo->lastInsertId();
        
    } catch (PDOException $e) {
        error_log("Insert error in table $table: " . $e->getMessage());
        throw new Exception("Erreur SQL: " . $e->getMessage());
    }
}
