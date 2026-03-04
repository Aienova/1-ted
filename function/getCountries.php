<?php
/**
 * Récupérer la liste de tous les pays
 * Retourne les pays triés par ordre alphabétique
 */

require_once 'connectionDatabase.php';

header('Content-Type: application/json');

try {
    $pdo = getDatabaseConnection();
    
    // Récupérer la langue depuis les paramètres GET (par défaut: français)
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';
    
    // Mapper la langue au champ approprié
    $nameColumn = 'fr_name'; // Par défaut français
    $validLangs = ['fr', 'en', 'de', 'es', 'it', 'nl', 'pt'];
    
    if (in_array($lang, $validLangs)) {
        $nameColumn = $lang . '_name';
    }
    
    // Récupérer tous les pays triés par ordre alphabétique
    $stmt = $pdo->prepare("
        SELECT 
            id,
            name,
            code,
            fr_name,
            en_name,
            de_name,
            es_name,
            it_name,
            nl_name,
            pt_name,
            flag_url,
            currency_symbol
        FROM country
        ORDER BY " . $nameColumn . " ASC
    ");
    
    $stmt->execute();
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater la réponse pour inclure le nom localisé
    $formattedCountries = array_map(function($country) use ($nameColumn) {
        return [
            'id' => $country['id'],
            'name' => $country[$nameColumn] ?? $country['name'],
            'code' => $country['code'],
            'flag_url' => $country['flag_url'],
            'currency_symbol' => $country['currency_symbol'],
            'translations' => [
                'fr' => $country['fr_name'],
                'en' => $country['en_name'],
                'de' => $country['de_name'],
                'es' => $country['es_name'],
                'it' => $country['it_name'],
                'nl' => $country['nl_name'],
                'pt' => $country['pt_name']
            ]
        ];
    }, $countries);
    
    echo json_encode([
        'success' => true,
        'countries' => $formattedCountries,
        'total' => count($formattedCountries)
    ]);
    
} catch (Exception $e) {
    error_log("Erreur getCountries: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
