<?php
/**
 * Endpoint pour renvoyer un code de vérification d'email
 * Accessible via POST: /function/resendVerificationCode.php
 * 
 * Body JSON:
 * {
 *   "email": "user@example.com"
 * }
 * 
 * Retourne:
 * - success: true/false
 * - message: message de succès ou d'erreur
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/generateVerificationCode.php';
require_once __DIR__ . '/mailSystem.php';

try {
    // Vérifier que c'est une requête POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Méthode non autorisée'
        ]);
        exit;
    }
    
    // Récupérer les données JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data || !isset($data['email'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Email manquant'
        ]);
        exit;
    }
    
    $email = trim($data['email']);
    
    // Valider le format de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Format d\'email invalide'
        ]);
        exit;
    }
    
    // Connexion à la base de données
    $pdo = getDatabaseConnection();
    
    // Créer un nouveau code de vérification
    $result = createVerificationCodeByEmail($pdo, $email);
    
    if (!$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
        exit;
    }
    
    $verificationCode = $result['code'];
    
    // Récupérer les informations du compte pour l'email
    $sql = "SELECT id, firstname, lastname, email FROM account WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$account) {
        echo json_encode([
            'success' => false,
            'message' => 'Compte introuvable'
        ]);
        exit;
    }
    
    // Déterminer la langue (par défaut: fr)
    $lang = isset($data['lang']) ? $data['lang'] : 'fr';
    
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
            'subject' => 'Vérification de votre email - 1-TED',
            'greeting' => 'Bonjour ' . $account['firstname'] . ' ' . $account['lastname'],
            'body' => 'Merci de vous être inscrit sur 1-TED. Pour finaliser votre inscription, veuillez vérifier votre adresse email en cliquant sur le lien ci-dessous :',
            'button' => 'Vérifier mon email',
            'expiry' => 'Ce lien est valable pendant 10 minutes.',
            'alternative' => 'Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :',
            'ignore' => 'Si vous n\'avez pas créé de compte sur 1-TED, vous pouvez ignorer cet email.',
            'footer' => 'Cordialement,<br>L\'équipe 1-TED'
        ],
        'en' => [
            'subject' => 'Email Verification - 1-TED',
            'greeting' => 'Hello ' . $account['firstname'] . ' ' . $account['lastname'],
            'body' => 'Thank you for signing up on 1-TED. To finalize your registration, please verify your email address by clicking the link below:',
            'button' => 'Verify my email',
            'expiry' => 'This link is valid for 10 minutes.',
            'alternative' => 'If the button doesn\'t work, copy and paste this link into your browser:',
            'ignore' => 'If you didn\'t create an account on 1-TED, you can ignore this email.',
            'footer' => 'Best regards,<br>The 1-TED team'
        ]
    ];
    
    $msg = $emailMessages[$lang] ?? $emailMessages['fr'];
    
    // Construire le contenu de l'email HTML
    $emailBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #3498db; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
            .content { background-color: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
            .button { display: inline-block; padding: 12px 30px; background-color: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { text-align: center; padding: 20px; color: #7f8c8d; font-size: 12px; }
            .link { word-break: break-all; color: #3498db; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>1-TED</h1>
            </div>
            <div class='content'>
                <p><strong>{$msg['greeting']},</strong></p>
                <p>{$msg['body']}</p>
                <p style='text-align: center;'>
                    <a href='{$verificationUrl}' class='button'>{$msg['button']}</a>
                </p>
                <p><small>{$msg['expiry']}</small></p>
                <hr>
                <p><small>{$msg['alternative']}</small></p>
                <p class='link'><small>{$verificationUrl}</small></p>
                <hr>
                <p><small>{$msg['ignore']}</small></p>
                <p>{$msg['footer']}</p>
            </div>
            <div class='footer'>
                <p>© " . date('Y') . " 1-TED - Tous droits réservés</p>
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
        echo json_encode([
            'success' => true,
            'message' => 'Un nouveau code de vérification a été envoyé à votre email'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de l\'envoi de l\'email'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Erreur lors du renvoi du code: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur lors du renvoi du code'
    ]);
} catch (Exception $e) {
    error_log("Erreur: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Une erreur est survenue'
    ]);
}
