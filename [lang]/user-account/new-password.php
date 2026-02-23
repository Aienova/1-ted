<?php
// Récupérer la langue et le code depuis l'URL
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';
$forgetCode = isset($_GET['code']) ? $_GET['code'] : '';

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

// Vérifier que le forgetCode est valide
if (empty($forgetCode) || strlen($forgetCode) != 12) {
    header("Location: {$baseUrl}/{$lang}/user-account/forget-password");
    exit;
}

// Vérifier que le code existe dans la base de données
require_once __DIR__ . '/../../function/getAccountForgetCode.php';
$account = getAccountByForgetCode($forgetCode);

if (!$account) {
    $errorMessage = "Ce lien de réinitialisation est invalide ou a expiré.";
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nouveau mot de passe - 1-TED">
    <title>Nouveau mot de passe - 1-TED</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/account-system.css">
</head>
<body>
    <div class="account-container">
        <div class="account-card">
            <div class="account-header">
                <h1>1-TED</h1>
                <h2>Nouveau mot de passe</h2>
                <p class="subtitle">Choisissez un nouveau mot de passe sécurisé</p>
            </div>
            
            <?php if (isset($errorMessage)): ?>
                <div class="error-message" style="display: block;">
                    <?php echo $errorMessage; ?><br>
                    <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/forget-password" class="link">
                        Demander un nouveau lien
                    </a>
                </div>
            <?php else: ?>
                <form id="newPasswordForm" class="account-form">
                    <input type="hidden" id="forgetCode" name="forgetCode" value="<?php echo htmlspecialchars($forgetCode); ?>">
                    
                    <div class="form-group">
                        <label for="newPassword">Nouveau mot de passe *</label>
                        <input type="password" id="newPassword" name="newPassword" required autocomplete="new-password" minlength="8">
                        <small>Minimum 8 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">Confirmer le mot de passe *</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required autocomplete="new-password">
                    </div>
                    
                    <div id="errorMessage" class="error-message" style="display: none;"></div>
                    <div id="successMessage" class="success-message" style="display: none;"></div>
                    
                    <button type="submit" class="btn-primary">Confirmer</button>
                    
                    <div class="account-links">
                        <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/connection" class="link">Retour à la connexion</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        const baseUrl = '<?php echo $baseUrl; ?>';
        const lang = '<?php echo $lang; ?>';
    </script>
    <script src="<?php echo $baseUrl; ?>/script/account/new-password.js"></script>
</body>
</html>
