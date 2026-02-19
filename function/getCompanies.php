<?php
/**
 * API pour récupérer la liste des entreprises
 * 
 * Paramètres:
 * - keyword: Recherche dans nom, domain, hq_location
 * - country: Filtrer par pays
 */

require_once __DIR__ . '/connectionDatabase.php';

header('Content-Type: application/json');

try {
    $pdo = getDatabaseConnection();
    
    // Récupérer les paramètres de recherche
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $country = isset($_GET['country']) ? trim($_GET['country']) : '';
    
    // Construire la requête SQL
    $sql = "SELECT 
                c.id,
                c.name,
                c.customer_number,
                c.nation_register_number,
                c.description,
                c.domain,
                c.capacity,
                c.hq_location,
                c.country,
                c.recruiting,
                c.contact_id,
                c.created_at,
                c.updated_at,
                cnt.first_name AS contact_first_name,
                cnt.last_name AS contact_last_name,
                cnt.email AS contact_email,
                cnt.phone AS contact_phone,
                COUNT(DISTINCT j.id) AS jobs_count,
                COUNT(DISTINCT a.id) AS accounts_count
            FROM company c
            LEFT JOIN contact cnt ON c.contact_id = cnt.id
            LEFT JOIN job j ON j.company_id = c.id
            LEFT JOIN account a ON a.company_id = c.id
            WHERE 1=1";
    
    // Ajouter les filtres
    $params = [];
    
    if (!empty($keyword)) {
        $sql .= " AND (c.name LIKE :keyword 
                   OR c.domain LIKE :keyword 
                   OR c.hq_location LIKE :keyword
                   OR c.description LIKE :keyword)";
        $params[':keyword'] = '%' . $keyword . '%';
    }
    
    if (!empty($country)) {
        $sql .= " AND c.country = :country";
        $params[':country'] = $country;
    }
    
    $sql .= " GROUP BY c.id
              ORDER BY c.created_at DESC";
    
    // Préparer et exécuter
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $companies = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Formater les données
        $companies[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'customer_number' => $row['customer_number'],
            'nation_register_number' => $row['nation_register_number'],
            'description' => $row['description'],
            'domain' => $row['domain'],
            'capacity' => $row['capacity'] ? (int)$row['capacity'] : null,
            'hq_location' => $row['hq_location'],
            'country' => $row['country'],
            'recruiting' => (bool)$row['recruiting'],
            'contact' => [
                'id' => $row['contact_id'] ? (int)$row['contact_id'] : null,
                'first_name' => $row['contact_first_name'],
                'last_name' => $row['contact_last_name'],
                'email' => $row['contact_email'],
                'phone' => $row['contact_phone']
            ],
            'jobs_count' => (int)$row['jobs_count'],
            'accounts_count' => (int)$row['accounts_count'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'companies' => $companies,
        'total' => count($companies)
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des entreprises: ' . $e->getMessage()
    ]);
}
