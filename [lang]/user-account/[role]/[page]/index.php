<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['token']) || !isset($_SESSION['role'])) {
    header('Location: ../../connection');
    exit;
}

// Récupérer les paramètres depuis l'URL
$urlRole = isset($_GET['role']) ? $_GET['role'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

// Vérifier que le rôle de l'URL correspond au rôle de la session
if ($_SESSION['role'] !== $urlRole) {
    header('Location: ../../connection');
    exit;
}

// Charger la configuration
$envFile = __DIR__ . '/../../../../local.env';
$baseUrl = 'http://1-ted.local/';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'BASE_URL=') === 0) {
            $baseUrl = trim(substr($line, 9));
            break;
        }
    }
}

// Mapper les rôles aux noms de fichiers JSON
$roleMapping = [
    'administrator' => 'admin',
    'candidate' => 'candidate',
    'recruiter' => 'recruiter',
    'company' => 'company'
];

$jsonFileName = isset($roleMapping[$urlRole]) ? $roleMapping[$urlRole] : 'candidate';
$jsonFile = __DIR__ . '/../../../../data/text/' . $lang . '/user-account/pages-' . $jsonFileName . '.json';

// Charger le fichier JSON
if (!file_exists($jsonFile)) {
    die("Configuration file not found: " . $jsonFile);
}

$jsonData = json_decode(file_get_contents($jsonFile), true);
if (!$jsonData || !isset($jsonData['pages'])) {
    die("Invalid JSON format");
}

// Trouver la page demandée
$currentPage = null;
foreach ($jsonData['pages'] as $p) {
    if ($p['name'] === $page) {
        $currentPage = $p;
        break;
    }
}

// Si la page n'existe pas, rediriger vers dashboard
if (!$currentPage) {
    header('Location: ' . $baseUrl . '/' . $lang . '/user-account/' . $urlRole . '/dashboard');
    exit;
}

// Récupérer les pages pour le menu (celles avec addOnMenu = true)
$menuPages = array_filter($jsonData['pages'], function($p) {
    return isset($p['addOnMenu']) && $p['addOnMenu'] === true;
});

// Informations de l'utilisateur connecté
$userName = isset($_SESSION['login']) ? $_SESSION['login'] : 'Utilisateur';
$userRole = ucfirst($_SESSION['role']);

// Mapper les rôles pour l'affichage en français
$roleDisplayNames = [
    'administrator' => 'Administrateur',
    'candidate' => 'Candidat',
    'recruiter' => 'Recruteur',
    'company' => 'Entreprise'
];
$userRole = isset($roleDisplayNames[$_SESSION['role']]) ? $roleDisplayNames[$_SESSION['role']] : ucfirst($_SESSION['role']);

// Titre et description de la page
$pageTitle = isset($currentPage['text-content']['title']) ? $currentPage['text-content']['title'] : $currentPage['label'];
$pageDescription = isset($currentPage['text-content']['description']) ? $currentPage['text-content']['description'] : '';

// Chemin du fragment
$fragmentPath = __DIR__ . '/fragment' . $currentPage['fragment'];
// Chemin du fragment
$fragmentPath = __DIR__ . '/fragment' . $currentPage['fragment'];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <title><?php echo htmlspecialchars($pageTitle); ?> - 1-TED</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/style.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/account-system.css">
        <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/style.css">
        <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/joboffer.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/editor.css">
        <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/list.css">
</head>
<body>
    <div class="user-space-container">
 
        <?php include __DIR__ . '/menu.php'; ?>
        <!-- Contenu principal -->
        <main class="user-space-content">
            <!-- Titre de la page -->
            <div class="user-space-header">
                <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
                <?php if ($pageDescription): ?>
                    <p class="description"><?php echo htmlspecialchars($pageDescription); ?></p>
                <?php endif; ?>
            </div>

            <!-- Fragment -->
            <div class="user-space-fragment">
                <?php 
                if (file_exists($fragmentPath)) {
                    include $fragmentPath;
                } else {
                    echo '<p class="error">Fragment introuvable : ' . htmlspecialchars($currentPage['fragment']) . '</p>';
                }
                ?>
            </div>
        </main>
    </div>
    
    <script src="<?php echo $baseUrl; ?>/script/popup.js"></script>
    <script src="<?php echo $baseUrl; ?>/script/config.js"></script>
<script src="<?php echo $baseUrl; ?>/script/editor.js"></script>
<script src="<?php echo $baseUrl; ?>/script/convertToHTML.js"></script>
<script src="<?php echo $baseUrl; ?>/script/register.js"></script>
<script src="<?php echo $baseUrl; ?>/script/save.js"></script>

    <script src="<?php echo $baseUrl; ?>/script/add-cv.js"></script>
    <script src="<?php echo $baseUrl; ?>/script/insertTestData.js"></script>

<script src="<?php echo $baseUrl; ?>/script/print.js"></script>
<script src="<?php echo $baseUrl; ?>/script/reset.js"></script>
<script src="<?php echo $baseUrl; ?>/script/classify.js"></script>
<script src="<?php echo $baseUrl; ?>/script/declassify.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/cv-title.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/personal-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/job-info.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/description.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/skills.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/experience.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/formation.js"></script>
<script src="<?php echo $baseUrl; ?>/script/forms/qrcode.js"></script>
    <script>
        const baseUrl = '<?php echo $baseUrl; ?>';
        const lang = '<?php echo $lang; ?>';
        
        // Gestion de la déconnexion
        document.getElementById('logoutBtn').addEventListener('click', async function(e) {
            e.preventDefault();
            const token = localStorage.getItem('authToken');
            
            try {
                const response = await fetch(`${baseUrl}/function/connectionToAccount.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'logout',
                        token: token
                    })
                });
                
                const result = await response.json();
                
                // Nettoyer le localStorage
                localStorage.removeItem('authToken');
                localStorage.removeItem('accountId');
                localStorage.removeItem('role');
                
                // Rediriger vers la page de connexion
                window.location.href = `${baseUrl}/${lang}/user-account/connection`;
            } catch (error) {
                console.error('Erreur lors de la déconnexion:', error);
                // Rediriger quand même
                window.location.href = `${baseUrl}/${lang}/user-account/connection`;
            }
        });
    </script>
</body>
</html>
