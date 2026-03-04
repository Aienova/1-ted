<?php
// Récupérer la langue et le code de vérification depuis l'URL
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';
$verificationCode = isset($_GET['code']) ? $_GET['code'] : '';

// Charger la configuration
$envFile = __DIR__ . '/../../../local.env';
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

// Vérifier si le code est fourni
if (empty($verificationCode)) {
    header("Location: {$baseUrl}/{$lang}/user-account/connection");
    exit;
}

// Messages multilingues
$messages = [
    'fr' => [
        'title' => 'Vérification de votre email',
        'verifying' => 'Vérification en cours...',
        'success' => 'Votre email a été vérifié avec succès !',
        'expired' => 'Code de vérification expiré',
        'expired_message' => 'Votre code de vérification a expiré (validité: 10 minutes). Veuillez entrer votre email pour recevoir un nouveau code.',
        'invalid' => 'Code de vérification invalide',
        'invalid_message' => 'Le code de vérification est invalide ou a déjà été utilisé.',
        'email_label' => 'Adresse email',
        'send_new_code' => 'Recevoir un nouveau code',
        'redirect_message' => 'Vous allez être redirigé vers la page de connexion...',
        'back_home' => '← Retour à l\'accueil'
    ],
    'en' => [
        'title' => 'Email Verification',
        'verifying' => 'Verifying...',
        'success' => 'Your email has been successfully verified!',
        'expired' => 'Verification code expired',
        'expired_message' => 'Your verification code has expired (validity: 10 minutes). Please enter your email to receive a new code.',
        'invalid' => 'Invalid verification code',
        'invalid_message' => 'The verification code is invalid or has already been used.',
        'email_label' => 'Email address',
        'send_new_code' => 'Send new code',
        'redirect_message' => 'You will be redirected to the login page...',
        'back_home' => '← Back to home'
    ],
    'es' => [
        'title' => 'Verificación de su correo electrónico',
        'verifying' => 'Verificando...',
        'success' => '¡Su correo electrónico ha sido verificado con éxito!',
        'expired' => 'Código de verificación expirado',
        'expired_message' => 'Su código de verificación ha expirado (validez: 10 minutos). Por favor, ingrese su correo electrónico para recibir un nuevo código.',
        'invalid' => 'Código de verificación inválido',
        'invalid_message' => 'El código de verificación es inválido o ya ha sido utilizado.',
        'email_label' => 'Dirección de correo electrónico',
        'send_new_code' => 'Recibir nuevo código',
        'redirect_message' => 'Será redirigido a la página de inicio de sesión...',
        'back_home' => '← Volver al inicio'
    ],
    'de' => [
        'title' => 'E-Mail-Bestätigung',
        'verifying' => 'Überprüfung läuft...',
        'success' => 'Ihre E-Mail wurde erfolgreich bestätigt!',
        'expired' => 'Bestätigungscode abgelaufen',
        'expired_message' => 'Ihr Bestätigungscode ist abgelaufen (Gültigkeit: 10 Minuten). Bitte geben Sie Ihre E-Mail-Adresse ein, um einen neuen Code zu erhalten.',
        'invalid' => 'Ungültiger Bestätigungscode',
        'invalid_message' => 'Der Bestätigungscode ist ungültig oder wurde bereits verwendet.',
        'email_label' => 'E-Mail-Adresse',
        'send_new_code' => 'Neuen Code erhalten',
        'redirect_message' => 'Sie werden zur Anmeldeseite weitergeleitet...',
        'back_home' => '← Zurück zur Startseite'
    ]
];

$msg = $messages[$lang] ?? $messages['fr'];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $msg['title']; ?> - 1-TED">
    <title><?php echo $msg['title']; ?> - 1-TED</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/account-system.css">
    <style>
        .verification-status {
            text-align: center;
            padding: 30px 20px;
        }
        
        .verification-status .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .verification-status.success .icon {
            color: #27ae60;
        }
        
        .verification-status.error .icon {
            color: #e74c3c;
        }
        
        .verification-status.loading .icon {
            color: #3498db;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .verification-status h2 {
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .verification-status p {
            color: #7f8c8d;
            line-height: 1.6;
        }
        
        #resendForm {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="account-container">
        <div class="account-card">
            <div class="account-header">
                <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>" class="back-home-link"><?php echo $msg['back_home']; ?></a><br>
                <img class="logo-connection" src="<?php echo $baseUrl; ?>/media/image/logo.png" alt="1-TED Logo" />
                <h2><?php echo $msg['title']; ?></h2>
            </div>
            
            <!-- Zone de statut de vérification -->
            <div id="verificationStatus" class="verification-status loading">
                <div class="icon">⏳</div>
                <h2><?php echo $msg['verifying']; ?></h2>
            </div>
            
            <!-- Formulaire pour renvoyer un code (masqué par défaut) -->
            <form id="resendForm" class="account-form" style="display: none;">
                <div class="form-group">
                    <label for="email"><?php echo $msg['email_label']; ?></label>
                    <input type="email" id="email" name="email" required autocomplete="email">
                </div>
                
                <div id="errorMessage" class="error-message" style="display: none;"></div>
                <div id="successMessage" class="success-message" style="display: none;"></div>
                
                <button type="submit" class="btn-primary"><?php echo $msg['send_new_code']; ?></button>
            </form>
        </div>
    </div>
    
    <script>
        const baseUrl = '<?php echo $baseUrl; ?>';
        const lang = '<?php echo $lang; ?>';
        const verificationCode = '<?php echo $verificationCode; ?>';
        
        // Messages traduits
        const messages = <?php echo json_encode($msg); ?>;
        
        // Vérifier le code au chargement
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch(`${baseUrl}/function/getVerificationCode.php?code=${verificationCode}`);
                const data = await response.json();
                
                const statusDiv = document.getElementById('verificationStatus');
                const resendForm = document.getElementById('resendForm');
                
                if (data.success && data.verified) {
                    // Code valide et email vérifié
                    statusDiv.className = 'verification-status success';
                    statusDiv.innerHTML = `
                        <div class="icon">✅</div>
                        <h2>${messages.success}</h2>
                        <p>${messages.redirect_message}</p>
                    `;
                    
                    // Rediriger vers la page de connexion après 3 secondes
                    setTimeout(() => {
                        window.location.href = `${baseUrl}/${lang}/user-account/connection`;
                    }, 3000);
                    
                } else if (data.success && data.expired) {
                    // Code expiré
                    statusDiv.className = 'verification-status error';
                    statusDiv.innerHTML = `
                        <div class="icon">⏰</div>
                        <h2>${messages.expired}</h2>
                        <p>${messages.expired_message}</p>
                    `;
                    resendForm.style.display = 'block';
                    
                } else {
                    // Code invalide
                    statusDiv.className = 'verification-status error';
                    statusDiv.innerHTML = `
                        <div class="icon">❌</div>
                        <h2>${messages.invalid}</h2>
                        <p>${messages.invalid_message}</p>
                    `;
                }
                
            } catch (error) {
                console.error('Erreur lors de la vérification:', error);
                const statusDiv = document.getElementById('verificationStatus');
                statusDiv.className = 'verification-status error';
                statusDiv.innerHTML = `
                    <div class="icon">❌</div>
                    <h2>${messages.invalid}</h2>
                    <p>${messages.invalid_message}</p>
                `;
            }
        });
        
        // Gestion du formulaire de renvoi de code
        document.getElementById('resendForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const errorDiv = document.getElementById('errorMessage');
            const successDiv = document.getElementById('successMessage');
            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            // Réinitialiser les messages
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';
            
            // Désactiver le bouton
            submitBtn.disabled = true;
            submitBtn.textContent = messages.verifying;
            
            try {
                const response = await fetch(`${baseUrl}/function/resendVerificationCode.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    successDiv.textContent = data.message;
                    successDiv.style.display = 'block';
                    
                    // Rediriger après 2 secondes
                    setTimeout(() => {
                        window.location.href = `${baseUrl}/${lang}/user-account/connection`;
                    }, 2000);
                } else {
                    errorDiv.textContent = data.message || 'Une erreur est survenue';
                    errorDiv.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = messages.send_new_code;
                }
                
            } catch (error) {
                console.error('Erreur:', error);
                errorDiv.textContent = 'Une erreur est survenue lors de l\'envoi du code';
                errorDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = messages.send_new_code;
            }
        });
    </script>
</body>
</html>
