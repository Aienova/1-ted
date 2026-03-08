<?php
// Déconnecter automatiquement l'utilisateur s'il accède à la page de connexion
session_start();
if (isset($_SESSION['accountId'])) {
    // Détruire toutes les données de session
    $_SESSION = array();
    
    // Détruire le cookie de session si présent
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    
    // Détruire la session
    session_destroy();
}

// Récupérer la langue depuis l'URL
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';

// Charger la configuration
$envFile = __DIR__ . '/../../local.env';
$baseUrl = '';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'BASE_URL=') === 0) {
            $baseUrl = trim(substr($line, 9));
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connexion à votre compte 1-TED">
    <title>Connexion - 1-TED</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/account-system.css">
</head>
<body>
    <div class="account-container">
        <div class="account-card">
            <div class="account-header">
                <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>" class="back-home-link">← Retour à l'accueil</a></br>
                <img class="logo-connection" src="<?php echo $baseUrl; ?>/media/image/logo.png" alt="1-TED Logo" />
                <h2>Connexion</h2>
            </div>
            
            <form id="loginForm" class="account-form">
                <div class="form-group">
                    <label for="login">Émail ou nom d'utilisateur</label>
                    <input type="text" id="login" name="login" required autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                
                <div id="errorMessage" class="error-message" style="display: none;"></div>
                <div id="successMessage" class="success-message" style="display: none;"></div>
                
                <button type="submit" class="btn-primary">Se connecter</button>
                
                <div class="account-links">
                    <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/forget-password" class="link">Mot de passe oublié ?</a>
                    <span class="separator">|</span>
                    <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/registration" class="link">Créer un compte</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const baseUrl = '<?php echo $baseUrl; ?>';
        const lang = '<?php echo $lang; ?>';
    </script>
    <script src="<?php echo $baseUrl; ?>/script/account/connection.js"></script>
</body>
</html>