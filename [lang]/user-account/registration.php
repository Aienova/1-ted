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

// Charger les pays et activités pour les sélecteurs
require_once __DIR__ . '/../../function/getData.php';
$countries = getData('country', null, true);
$activities = getData('activity', null, true);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inscription - 1-TED">
    <title>Inscription - 1-TED</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/account-system.css">
</head>
<body>
    <div class="account-container">
        <div class="account-card account-card-large">
            <div class="account-header">
                <h1>1-TED</h1>
                <h2>Créer un compte</h2>
            </div>
            
            <form id="registrationForm" class="account-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">Prénom</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastname">Nom</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required autocomplete="email">
                </div>
                
                <div class="form-group">
                    <label for="login">Nom d'utilisateur *</label>
                    <input type="text" id="login" name="login" required autocomplete="username">
                    <small>Utilisé pour vous connecter</small>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Mot de passe *</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password" minlength="8">
                        <small>Minimum 8 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">Confirmer *</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required autocomplete="new-password">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" autocomplete="tel">
                </div>
                
                <div class="form-group">
                    <label for="role">Type de compte *</label>
                    <select id="role" name="role" required>
                        <option value="candidate">Candidat</option>
                        <option value="company">Entreprise</option>
                    </select>
                </div>
                
                <div id="companyFields" style="display: none;">
                    <div class="form-group">
                        <label for="company_name">Nom de l'entreprise</label>
                        <input type="text" id="company_name" name="company_name">
                    </div>
                    
                    <div class="form-group">
                        <label for="company_siret">SIRET</label>
                        <input type="text" id="company_siret" name="company_siret" maxlength="14">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="country_id">Pays</label>
                        <select id="country_id" name="country_id">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?php echo $country->getId(); ?>">
                                    <?php echo htmlspecialchars($country->getFrName()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="activity_id">Secteur d'activité</label>
                        <select id="activity_id" name="activity_id">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($activities as $activity): ?>
                                <option value="<?php echo $activity->getId(); ?>">
                                    <?php echo htmlspecialchars($activity->getFrName()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div id="errorMessage" class="error-message" style="display: none;"></div>
                <div id="successMessage" class="success-message" style="display: none;"></div>
                
                <button type="submit" class="btn-primary">Créer mon compte</button>
                
                <div class="account-links">
                    <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>/user-account/connection" class="link">Déjà un compte ? Se connecter</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const baseUrl = '<?php echo $baseUrl; ?>';
        const lang = '<?php echo $lang; ?>';
    </script>
    <script src="<?php echo $baseUrl; ?>/script/account/registration.js"></script>
</body>
</html>