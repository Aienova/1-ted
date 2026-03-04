<?php
/**
 * Fonction pour envoyer un email de vérification à un utilisateur
 * Appelée après la création d'un compte
 * 
 * @param PDO $pdo Connexion à la base de données
 * @param int $accountId ID du compte créé
 * @param string $lang Langue de l'utilisateur (fr, en, es, de)
 * @return array ['success' => bool, 'message' => string]
 */

require_once __DIR__ . '/generateVerificationCode.php';
require_once __DIR__ . '/mailSystem.php';
require_once __DIR__ . '/connectionDatabase.php';

// Si appelé via HTTP POST, gérer la requête
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // Récupérer les données JSON
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data || !isset($data['account_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'account_id manquant'
            ]);
            exit;
        }
        
        $accountId = intval($data['account_id']);
        $lang = isset($data['lang']) ? $data['lang'] : 'fr';
        
        // Connexion à la base de données
        $pdo = getDatabaseConnection();
        
        // Envoyer l'email de vérification
        $result = sendVerificationEmail($pdo, $accountId, $lang);
        
        echo json_encode($result);
        exit;
        
    } catch (Exception $e) {
        error_log("Erreur HTTP sendVerificationEmail: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur serveur lors de l\'envoi de l\'email'
        ]);
        exit;
    }
}

function sendVerificationEmail($pdo, $accountId, $lang = 'fr') {
    try {
        // Récupérer les informations du compte
        $sql = "SELECT id, firstname, lastname, email FROM account WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $accountId, PDO::PARAM_INT);
        $stmt->execute();
        
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$account) {
            return [
                'success' => false,
                'message' => 'Compte introuvable'
            ];
        }
        
        // Créer le code de vérification
        $verificationData = createVerificationCode($pdo, $accountId);
        $verificationCode = $verificationData['code'];
        
        // Construire l'URL de vérification
        $baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $baseUrl .= $_SERVER['HTTP_HOST'];
        
        // Charger la configuration pour obtenir le bon baseUrl
        $envFile = __DIR__ . '/../local.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, 'BASE_URL=') === 0) {
                    $configBaseUrl = trim(substr($line, 9));
                    if (!empty($configBaseUrl)) {
                        $baseUrl = $configBaseUrl;
                    }
                    break;
                }
            }
        }
        
        $verificationUrl = "{$baseUrl}/{$lang}/user-account/{$verificationCode}/email-verification";
        
        // Messages multilingues
        $emailMessages = [
            'fr' => [
                'subject' => 'Vérifiez votre email - 1-TED',
                'greeting' => 'Bonjour ' . $account['firstname'] . ' ' . $account['lastname'],
                'welcome' => 'Bienvenue sur 1-TED !',
                'body' => 'Merci de vous être inscrit sur notre plateforme. Pour finaliser votre inscription et activer votre compte, veuillez vérifier votre adresse email en cliquant sur le bouton ci-dessous :',
                'button' => 'Vérifier mon email',
                'expiry' => '⚠️ Ce lien est valable pendant 10 minutes seulement.',
                'alternative' => 'Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :',
                'ignore' => 'Si vous n\'avez pas créé de compte sur 1-TED, vous pouvez ignorer cet email en toute sécurité.',
                'footer' => 'Cordialement,<br>L\'équipe 1-TED'
            ],
            'en' => [
                'subject' => 'Verify your email - 1-TED',
                'greeting' => 'Hello ' . $account['firstname'] . ' ' . $account['lastname'],
                'welcome' => 'Welcome to 1-TED!',
                'body' => 'Thank you for signing up on our platform. To finalize your registration and activate your account, please verify your email address by clicking the button below:',
                'button' => 'Verify my email',
                'expiry' => '⚠️ This link is valid for 10 minutes only.',
                'alternative' => 'If the button doesn\'t work, copy and paste this link into your browser:',
                'ignore' => 'If you didn\'t create an account on 1-TED, you can safely ignore this email.',
                'footer' => 'Best regards,<br>The 1-TED team'
            ],
            'es' => [
                'subject' => 'Verifique su correo electrónico - 1-TED',
                'greeting' => 'Hola ' . $account['firstname'] . ' ' . $account['lastname'],
                'welcome' => '¡Bienvenido a 1-TED!',
                'body' => 'Gracias por registrarse en nuestra plataforma. Para finalizar su registro y activar su cuenta, verifique su dirección de correo electrónico haciendo clic en el botón a continuación:',
                'button' => 'Verificar mi correo',
                'expiry' => '⚠️ Este enlace es válido solo por 10 minutos.',
                'alternative' => 'Si el botón no funciona, copie y pegue este enlace en su navegador:',
                'ignore' => 'Si no creó una cuenta en 1-TED, puede ignorar este correo electrónico de forma segura.',
                'footer' => 'Atentamente,<br>El equipo de 1-TED'
            ],
            'de' => [
                'subject' => 'Bestätigen Sie Ihre E-Mail - 1-TED',
                'greeting' => 'Hallo ' . $account['firstname'] . ' ' . $account['lastname'],
                'welcome' => 'Willkommen bei 1-TED!',
                'body' => 'Vielen Dank für Ihre Registrierung auf unserer Plattform. Um Ihre Registrierung abzuschließen und Ihr Konto zu aktivieren, bestätigen Sie bitte Ihre E-Mail-Adresse, indem Sie auf die Schaltfläche unten klicken:',
                'button' => 'E-Mail bestätigen',
                'expiry' => '⚠️ Dieser Link ist nur 10 Minuten gültig.',
                'alternative' => 'Wenn die Schaltfläche nicht funktioniert, kopieren Sie diesen Link und fügen Sie ihn in Ihren Browser ein:',
                'ignore' => 'Wenn Sie kein Konto bei 1-TED erstellt haben, können Sie diese E-Mail sicher ignorieren.',
                'footer' => 'Mit freundlichen Grüßen,<br>Das 1-TED-Team'
            ]
        ];
        
        $msg = $emailMessages[$lang] ?? $emailMessages['fr'];
        
        // Construire le contenu de l'email HTML
        $emailBody = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f4f4f4;
                }
                .container { 
                    max-width: 600px; 
                    margin: 20px auto; 
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                .header { 
                    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                    color: white; 
                    padding: 40px 20px; 
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: 600;
                }
                .content { 
                    padding: 40px 30px; 
                    background-color: #ffffff;
                }
                .content p {
                    margin: 15px 0;
                    color: #555;
                }
                .welcome {
                    font-size: 20px;
                    color: #2c3e50;
                    font-weight: 600;
                    margin-bottom: 20px;
                }
                .button-container {
                    text-align: center;
                    margin: 30px 0;
                }
                .button { 
                    display: inline-block; 
                    padding: 15px 40px; 
                    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                    color: white !important; 
                    text-decoration: none; 
                    border-radius: 5px;
                    font-weight: 600;
                    font-size: 16px;
                    box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
                    transition: transform 0.2s;
                }
                .button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 8px rgba(52, 152, 219, 0.4);
                }
                .expiry-warning {
                    background-color: #fff3cd;
                    border-left: 4px solid #ffc107;
                    padding: 12px 15px;
                    margin: 20px 0;
                    border-radius: 4px;
                    color: #856404;
                }
                .link-box {
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                    margin: 20px 0;
                    word-break: break-all;
                }
                .link { 
                    color: #3498db; 
                    text-decoration: none;
                    word-break: break-all;
                }
                .footer { 
                    text-align: center; 
                    padding: 30px 20px; 
                    background-color: #f8f9fa;
                    color: #7f8c8d; 
                    font-size: 13px;
                    border-top: 1px solid #e9ecef;
                }
                .divider {
                    height: 1px;
                    background-color: #e9ecef;
                    margin: 25px 0;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎯 1-TED</h1>
                </div>
                <div class='content'>
                    <p><strong>{$msg['greeting']},</strong></p>
                    <p class='welcome'>{$msg['welcome']}</p>
                    <p>{$msg['body']}</p>
                    
                    <div class='button-container'>
                        <a href='{$verificationUrl}' class='button'>✉️ {$msg['button']}</a>
                    </div>
                    
                    <div class='expiry-warning'>
                        {$msg['expiry']}
                    </div>
                    
                    <div class='divider'></div>
                    
                    <p><small><strong>{$msg['alternative']}</strong></small></p>
                    <div class='link-box'>
                        <a href='{$verificationUrl}' class='link'>{$verificationUrl}</a>
                    </div>
                    
                    <div class='divider'></div>
                    
                    <p><small style='color: #7f8c8d;'>{$msg['ignore']}</small></p>
                    
                    <p style='margin-top: 30px;'>{$msg['footer']}</p>
                </div>
                <div class='footer'>
                    <p style='margin: 5px 0;'>© " . date('Y') . " 1-TED - Tous droits réservés</p>
                    <p style='margin: 5px 0;'>Plateforme de recrutement et de gestion de talents</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Envoyer l'email
        $emailSent = sendEmail(
            $account['email'],
            $msg['subject'],
            $emailBody
        );
        
        if ($emailSent) {
            return [
                'success' => true,
                'message' => 'Email de vérification envoyé avec succès',
                'verification_code' => $verificationCode
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email de vérification'
            ];
        }
        
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi de l'email de vérification: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Une erreur est survenue lors de l\'envoi de l\'email'
        ];
    }
}
