<?php
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
    <meta name="description" content="Mot de passe oublié - 1-TED">
    <title>Mot de passe oublié - 1-TED</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/account-system.css">
</head>
<body>
    <div class="account-container">
        <div class="account-card">
            <div class="account-header">
                <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>" class="back-home-link">← Retour à l'accueil</a></br>
                <img class="logo-connection" src="<?php echo $baseUrl; ?>/media/image/logo.png" alt="1-TED Logo" />
                <h2>Mot de passe oublié</h2>
                <p class="subtitle">Entrez votre email pour recevoir un lien de réinitialisation</p>
            </div>

            
            
            <form id="forgetPasswordForm" class="account-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="email">
                </div>
                
                <div id="errorMessage" class="error-message" style="display: none;"></div>
                <div id="successMessage" class="success-message" style="display: none;"></div>
                
                <button type="submit" class="btn-primary">Envoyer le lien</button>
                
                <div class="account-links">
                    <a href="/<?php echo $lang; ?>/user-account/connection" class="link">Retour à la connexion</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const baseUrl = '';
        const lang = '<?php echo $lang; ?>';
    </script>
    <script src="/script/account/forget-password.js"></script>
</body>
</html>