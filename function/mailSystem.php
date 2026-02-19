<?php
/**
 * Système d'envoi d'emails
 * Utilise la fonction mail() de PHP
 */

/**
 * Envoyer un email
 * @param string $to Adresse email du destinataire
 * @param string $subject Sujet de l'email
 * @param string $message Corps du message (HTML)
 * @param string $fromEmail Adresse email de l'expéditeur
 * @param string $fromName Nom de l'expéditeur
 * @return bool True si envoyé avec succès, false sinon
 */
function sendEmail($to, $subject, $message, $fromEmail = 'noreply@1-ted.com', $fromName = '1-TED') {
    // Headers pour l'email HTML
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
    $headers .= "Reply-To: {$fromEmail}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Wrapper HTML pour le message
    $htmlMessage = "
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #2c3e50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
            .footer { background: #34495e; color: #bdc3c7; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 5px 5px; }
            .button { display: inline-block; padding: 12px 30px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .button:hover { background: #2980b9; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>1-TED</h1>
            </div>
            <div class='content'>
                {$message}
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " 1-TED. Tous droits réservés.</p>
                <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Envoyer l'email
    return mail($to, $subject, $htmlMessage, $headers);
}

/**
 * Envoyer un email de réinitialisation de mot de passe
 * @param string $email Adresse email du destinataire
 * @param string $forgetCode Code de réinitialisation
 * @param string $baseUrl URL de base de l'application
 * @return bool True si envoyé avec succès
 */
function sendPasswordResetEmail($email, $forgetCode, $baseUrl) {
    $resetLink = "{$baseUrl}/fr/user-account/{$forgetCode}/new-password";
    
    $subject = "Réinitialisation de votre mot de passe - 1-TED";
    
    $message = "
        <h2>Réinitialisation de mot de passe</h2>
        <p>Bonjour,</p>
        <p>Vous avez demandé la réinitialisation de votre mot de passe sur 1-TED.</p>
        <p>Pour créer un nouveau mot de passe, cliquez sur le lien ci-dessous :</p>
        <p style='text-align: center;'>
            <a href='{$resetLink}' class='button'>Réinitialiser mon mot de passe</a>
        </p>
        <p>Ou copiez ce lien dans votre navigateur :</p>
        <p style='word-break: break-all;'><a href='{$resetLink}'>{$resetLink}</a></p>
        <p><strong>Ce lien est valable pendant 24 heures.</strong></p>
        <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
        <p>Cordialement,<br>L'équipe 1-TED</p>
    ";
    
    return sendEmail($email, $subject, $message);
}

/**
 * Envoyer un email de confirmation d'inscription
 * @param string $email Adresse email du destinataire
 * @param string $firstname Prénom de l'utilisateur
 * @param string $baseUrl URL de base de l'application
 * @return bool True si envoyé avec succès
 */
function sendWelcomeEmail($email, $firstname, $baseUrl) {
    $loginLink = "{$baseUrl}/fr/user-account/connection";
    
    $subject = "Bienvenue sur 1-TED !";
    
    $message = "
        <h2>Bienvenue {$firstname} !</h2>
        <p>Votre compte 1-TED a été créé avec succès.</p>
        <p>Vous pouvez dès maintenant vous connecter à votre espace personnel :</p>
        <p style='text-align: center;'>
            <a href='{$loginLink}' class='button'>Se connecter</a>
        </p>
        <p>Merci de nous faire confiance pour votre recherche d'emploi ou de candidats.</p>
        <p>Cordialement,<br>L'équipe 1-TED</p>
    ";
    
    return sendEmail($email, $subject, $message);
}
