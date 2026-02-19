<?php
/**
 * Test rapide pour vérifier les entreprises dans la DB
 */

require_once __DIR__ . '/connectionDatabase.php';

try {
    $pdo = getDatabaseConnection();
    
    // Compter les entreprises
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM company");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Nombre total d'entreprises : " . $count['total'] . "<br><br>";
    
    // Lister les 10 premières
    $stmt = $pdo->query("SELECT id, name, domain, country FROM company LIMIT 10");
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Liste des entreprises:</h3>";
    echo "<pre>";
    print_r($companies);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
