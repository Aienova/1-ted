<?php
/**
 * Récupère les informations complètes de l'entreprise connectée
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
    
    // Récupérer les informations complètes de l'entreprise
    $stmt = $db->prepare("
        SELECT 
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
            cnt.phone AS contact_phone
        FROM company c
        LEFT JOIN contact cnt ON c.contact_id = cnt.id
        WHERE c.id = :company_id
    ");
    
    $stmt->execute([':company_id' => $companyId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company) {
        echo json_encode([
            'success' => false,
            'error' => 'Entreprise non trouvée'
        ]);
        exit;
    }
    
    // Les réseaux sociaux ne sont pas encore stockés dans la base
    // TODO: Ajouter une colonne social_media (JSON) ou social_id dans la table company
    $social = null;
    
    // Les avantages ne sont pas encore stockés dans la base
    // TODO: Ajouter une colonne benefits (JSON) dans la table company
    $benefits = null;
    
    // Compter les offres d'emploi et candidatures
    $stmtStats = $db->prepare("
        SELECT 
            COUNT(DISTINCT j.id) as jobs_count,
            SUM(CASE WHEN j.open = 1 THEN 1 ELSE 0 END) as open_jobs,
            COUNT(DISTINCT a.id) as applications_count
        FROM job j
        LEFT JOIN application a ON a.job_id = j.id
        WHERE j.company_id = :company_id
    ");
    $stmtStats->execute([':company_id' => $companyId]);
    $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);
    
    // Formater la réponse
    $response = [
        'success' => true,
        'company' => [
            'id' => (int)$company['id'],
            'name' => $company['name'],
            'customer_number' => $company['customer_number'],
            'nation_register_number' => $company['nation_register_number'],
            'siret' => null, // Colonne à ajouter dans la base
            'description' => $company['description'],
            'domain' => $company['domain'],
            'capacity' => $company['capacity'] ? (int)$company['capacity'] : null,
            'size' => null, // Colonne à ajouter dans la base
            'hq_location' => $company['hq_location'],
            'country' => $company['country'],
            'recruiting' => (bool)$company['recruiting'],
            'website' => null, // Colonne à ajouter dans la base
            'logo_url' => null, // Colonne à ajouter dans la base
            'contact' => [
                'id' => $company['contact_id'] ? (int)$company['contact_id'] : null,
                'first_name' => $company['contact_first_name'],
                'last_name' => $company['contact_last_name'],
                'email' => $company['contact_email'],
                'phone' => $company['contact_phone'],
                'position' => null // Colonne à ajouter dans la table contact
            ],
            'social' => $social ?: [
                'linkedin' => null,
                'twitter' => null,
                'facebook' => null,
                'instagram' => null
            ],
            'benefits' => $benefits ?: [
                'remote' => false,
                'flexible' => false,
                'health' => false,
                'training' => false,
                'bonus' => false,
                'restaurant' => false,
                'transport' => false,
                'events' => false
            ],
            'stats' => [
                'jobs_count' => (int)($stats['jobs_count'] ?? 0),
                'open_jobs' => (int)($stats['open_jobs'] ?? 0),
                'applications_count' => (int)($stats['applications_count'] ?? 0)
            ],
            'created_at' => $company['created_at'],
            'updated_at' => $company['updated_at']
        ]
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
