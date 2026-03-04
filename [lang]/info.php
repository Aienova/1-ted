<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir le chemin racine du projet (répertoire parent de [lang])
define('PROJECT_ROOT', dirname(__DIR__));

// Charger les variables d'environnement
$envFile = PROJECT_ROOT . '/local.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Déterminer le mode (local ou production)
$isLocal = isset($_ENV['MODE_LOCAL']) && $_ENV['MODE_LOCAL'] === 'true';

// Configuration automatique selon le mode
if ($isLocal) {
    // Mode LOCAL - Utiliser BASE_URL depuis .env
    $baseUrl = isset($_ENV['BASE_URL']) ? $_ENV['BASE_URL'] : '';
    
    // Extraire le basePath depuis l'URL (enlever le protocole et le domaine)
    $parsedUrl = parse_url($baseUrl);
    $basePath = isset($parsedUrl['path']) ? rtrim($parsedUrl['path'], '/') : '';
    
    $dbConfig = [
        'host' => isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost',
        'name' => isset($_ENV['DB_NAME']) ? $_ENV['DB_NAME'] : '1ted',
        'user' => isset($_ENV['DB_USER']) ? $_ENV['DB_USER'] : 'root',
        'password' => isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : '',
        'charset' => isset($_ENV['DB_CHARSET']) ? $_ENV['DB_CHARSET'] : 'utf8mb4'
    ];
} else {
    // Mode PRODUCTION - Utiliser PROD_BASE_URL depuis .env
    $baseUrl = isset($_ENV['PROD_BASE_URL']) ? $_ENV['PROD_BASE_URL'] : 'https://1-ted.com';
    
    // En production, généralement à la racine ou extraire depuis l'URL
    $parsedUrl = parse_url($baseUrl);
    $basePath = isset($parsedUrl['path']) ? rtrim($parsedUrl['path'], '/') : '';
    
    $dbConfig = [
        'host' => isset($_ENV['PROD_DB_HOST']) ? $_ENV['PROD_DB_HOST'] : 'localhost',
        'name' => isset($_ENV['PROD_DB_NAME']) ? $_ENV['PROD_DB_NAME'] : 'ted_backoffice_prod',
        'user' => isset($_ENV['PROD_DB_USER']) ? $_ENV['PROD_DB_USER'] : 'root',
        'password' => isset($_ENV['PROD_DB_PASSWORD']) ? $_ENV['PROD_DB_PASSWORD'] : '',
        'charset' => isset($_ENV['DB_CHARSET']) ? $_ENV['DB_CHARSET'] : 'utf8mb4'
    ];
}

// Rendre les configs DB disponibles globalement
foreach ($dbConfig as $key => $value) {
    $_ENV['DB_' . strtoupper($key)] = $value;
}

// Récupérer la langue depuis l'URL
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

// Chemins absolus pour les fichiers JSON
$menuFile = PROJECT_ROOT . '/data/text/' . $lang . '/menu.json';
$searchFile = PROJECT_ROOT . '/data/text/' . $lang . '/search-engine.json';
$footerFile = PROJECT_ROOT . '/data/text/' . $lang . '/footer.json';
$pageFile = PROJECT_ROOT . '/data/text/' . $lang . '/page.json';

// Charger le menu
if (file_exists($menuFile)) {
    $menuJson = file_get_contents($menuFile);
    $menuItems = json_decode($menuJson, true);
} else {
    $menuItems = [];
}

// Vérifier si l'utilisateur est connecté
$isUserConnected = isset($_SESSION['token']) && isset($_SESSION['role']);

// Filtrer le menu pour masquer le bouton Connexion si l'utilisateur est connecté
if ($isUserConnected && is_array($menuItems)) {
    $menuItems = array_filter($menuItems, function($item) {
        return !isset($item['isAccountButton']) || $item['isAccountButton'] !== true;
    });
    // Réindexer le tableau après filtrage
    $menuItems = array_values($menuItems);
}

// ========================================
// FILTRAGE DES SOUS-MENUS SELON LE RÔLE
// ========================================
if ($isUserConnected && isset($_SESSION['role']) && is_array($menuItems)) {
    $role = $_SESSION['role'];
    
    foreach ($menuItems as &$item) {
        if (!empty($item['submenu'])) {
            // Filtrer les sous-menus pour les companies/recruiters
            // Masquer "Soumettre une candidature" car ils reçoivent des candidatures
            if ($role === 'company' || $role === 'recruiter') {
                $item['submenu'] = array_filter($item['submenu'], function($subitem) {
                    return $subitem['name'] !== 'submit-candidate';
                });
                $item['submenu'] = array_values($item['submenu']);
            }
            
            // Filtrer les sous-menus pour les candidats
            // Masquer "Soumettre une offre d'emploi" car ils postulent aux offres
            if ($role === 'candidate') {
                $item['submenu'] = array_filter($item['submenu'], function($subitem) {
                    return $subitem['name'] !== 'submit-offer';
                });
                $item['submenu'] = array_values($item['submenu']);
            }
        }
    }
    unset($item); // Libérer la référence
}

// Charger les données du moteur de recherche
if (file_exists($searchFile)) {
    $searchJson = file_get_contents($searchFile);
    $searchFields = json_decode($searchJson, true);
} else {
    $searchFields = [];
}

// Charger les données du footer
if (file_exists($footerFile)) {
    $footerJson = file_get_contents($footerFile);
    $footerData = json_decode($footerJson, true);
    $footerData = $footerData[0]; // Récupérer le premier élément
} else {
    $footerData = [];
}

// Charger les données des pages
if (file_exists($pageFile)) {
    $pageJson = file_get_contents($pageFile);
    $pagesData = json_decode($pageJson, true);
} else {
    $pagesData = [];
}

// Chemin vers le fichier languages.json
$languagesFile = PROJECT_ROOT . '/data/text/languages.json';

// Charger les langues disponibles
if (file_exists($languagesFile)) {
    $languagesJson = file_get_contents($languagesFile);
    $languages = json_decode($languagesJson, true);
} else {
    $languages = [];
}

// Récupérer le nom de la page depuis l'URL
$currentPageName = isset($_GET['page']) ? $_GET['page'] : 'home';

// ========================================
// SYSTÈME DE REROUTAGE SELON LE RÔLE
// ========================================
if ($isUserConnected && isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    
    // Reroutage pour les companies et recruiters
    if ($role === 'company' || $role === 'recruiter') {
        // submit-offer -> nos offres d'emploi
        if ($currentPageName === 'submit-offer') {
            header('Location: ' . $baseUrl . '/' . $lang . '/user-account/company/our-offers');
            exit;
        }
        // submit-candidate -> candidatures reçues
        if ($currentPageName === 'submit-candidate') {
            header('Location: ' . $baseUrl . '/' . $lang . '/user-account/company/applications');
            exit;
        }
    }
    
    // Reroutage pour les candidats
    if ($role === 'candidate') {
        // submit-offer -> mes candidatures
        if ($currentPageName === 'submit-offer') {
            header('Location: ' . $baseUrl . '/' . $lang . '/user-account/candidate/my-applications');
            exit;
        }
        // submit-candidate -> mon CV
        if ($currentPageName === 'submit-candidate') {
            header('Location: ' . $baseUrl . '/' . $lang . '/user-account/candidate/my-cv');
            exit;
        }
    }
}

// Trouver les données de la page courante
$currentPage = null;
foreach ($pagesData as $page) {
    if ($page['name'] === $currentPageName) {
        $currentPage = $page;
        break;
    }
}

// Si la page n'existe pas, utiliser la page d'accueil par défaut
if (!$currentPage) {
    foreach ($pagesData as $page) {
        if ($page['name'] === 'home') {
            $currentPage = $page;
            break;
        }
    }
}

// Extraire les informations de la page
$pageTitle = $currentPage ? $currentPage['title'] : '1TED';
$pageDescription = $currentPage ? $currentPage['description'] : '';
$pageContent = $currentPage ? $currentPage['content'] : null;
$pageFragment = $currentPage ? $currentPage['fragment'] : null;
$isSpecificPage = $currentPage ? $currentPage['specificPage'] : false;
$bannerTitle = $currentPage && isset($currentPage['bannerTitle']) ? $currentPage['bannerTitle'] : 'Trouvez votre talent ou votre mission';

    // Charger les textes depuis le fichier JSON selon la langue courante
    $textFile = PROJECT_ROOT . '/data/text/' . $lang . '/text.json';
    if (file_exists($textFile)) {
        $textData = json_decode(file_get_contents($textFile), true);
    } else {
        // Fallback vers le français si la langue n'est pas disponible
        $fallbackFile = PROJECT_ROOT . '/data/text/fr/text.json';
        $textData = file_exists($fallbackFile) ? json_decode(file_get_contents($fallbackFile), true) : [];
    }




?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <title><?php echo htmlspecialchars($pageTitle); ?> - 1TED</title>
    
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
    <!-- Désactivation du masquage pour les administrateurs -->
    <style>
        .classified {
            filter: none !important;
            opacity: 1 !important;
            color: inherit !important;
        }
    </style>
    <?php endif; ?>

    <!-- Variables globales JavaScript -->
    <script>
        window.APP_CONFIG = {
            baseUrl: '<?php echo $baseUrl; ?>',
            basePath: '<?php echo $basePath; ?>',
            lang: '<?php echo $lang; ?>',
            isAdmin: <?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator') ? 'true' : 'false'; ?>,
            isCandidate: <?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'candidate') ? 'true' : 'false'; ?>
        };
    </script>
    
    <link rel="stylesheet" href="/style/header.css">
    <link rel="stylesheet" href="/style/languageSwitcher.css">
    <link rel="stylesheet" href="/style/body.css">
    <link rel="stylesheet" href="/style/search.css">
    <link rel="stylesheet" href="/style/footer.css">
    <link rel="stylesheet" href="/style/content.css">
        <link rel="stylesheet" href="/style/style.css">

    <link rel="stylesheet" href="/style/editor.css">
    <link rel="stylesheet" href="/style/joboffer.css">
    <link rel="stylesheet" href="/style/list.css">
    <link rel="stylesheet" href="/style/viewer.css">
    <link rel="stylesheet" href="/style/GDPR.css">


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-1G3MHP0LP2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-1G3MHP0LP2');
</script>
</head>