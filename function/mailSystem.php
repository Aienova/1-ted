<?php
/**
 * Système d'envoi d'emails
 * Utilise SMTP authentifié (configurable) avec fallback mail() si nécessaire
 */

require_once __DIR__ . '/mailConfig.php';

function encodeEmailSubject($subject) {
    return '=?UTF-8?B?' . base64_encode($subject) . '?=';
}

function buildEmailHeaders($config) {
    $fromName = $config['from_name'];
    $fromEmail = $config['from_email'];
    $replyToName = $config['reply_to_name'];
    $replyToEmail = $config['reply_to_email'];

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
    $headers .= "Reply-To: {$replyToName} <{$replyToEmail}>\r\n";
    $headers .= "Return-Path: {$fromEmail}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "Importance: Normal\r\n";

    return $headers;
}

function smtpReadResponse($socket) {
    $response = '';
    while (!feof($socket)) {
        $line = fgets($socket, 515);
        if ($line === false) {
            break;
        }
        $response .= $line;

        if (strlen($line) < 4) {
            break;
        }

        // Fin de réponse SMTP multiline: "250 "
        if ($line[3] === ' ') {
            break;
        }
    }
    return $response;
}

function smtpSendCommand($socket, $command, $expectedCodes) {
    if ($command !== null) {
        fwrite($socket, $command . "\r\n");
    }

    $response = smtpReadResponse($socket);
    $statusCode = (int)substr($response, 0, 3);

    if (!in_array($statusCode, (array)$expectedCodes, true)) {
        error_log("SMTP command failed. Command={$command}; Response={$response}");
        return false;
    }

    return $response;
}

function normalizeRecipients($to) {
    if (is_array($to)) {
        return array_values(array_filter(array_map('trim', $to), fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL)));
    }

    $parts = array_map('trim', explode(',', (string)$to));
    return array_values(array_filter($parts, fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL)));
}

function sendViaSmtp($to, $subject, $htmlMessage, $config) {
    $recipients = normalizeRecipients($to);
    if (empty($recipients)) {
        error_log('SMTP send aborted: no valid recipient');
        return false;
    }

    $host = $config['host'];
    $port = (int)$config['port'];
    $timeout = max(5, (int)$config['timeout']);
    $encryption = $config['encryption'];

    $remoteHost = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
    $socket = @stream_socket_client($remoteHost . ':' . $port, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);

    if (!$socket) {
        error_log("SMTP connection failed: {$errstr} ({$errno})");
        return false;
    }

    stream_set_timeout($socket, $timeout);

    try {
        if (!smtpSendCommand($socket, null, [220])) {
            fclose($socket);
            return false;
        }

        $hostname = $_SERVER['SERVER_NAME'] ?? 'localhost';
        if (!smtpSendCommand($socket, 'EHLO ' . $hostname, [250])) {
            fclose($socket);
            return false;
        }

        if ($encryption === 'tls') {
            if (!smtpSendCommand($socket, 'STARTTLS', [220])) {
                fclose($socket);
                return false;
            }

            $cryptoEnabled = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            if (!$cryptoEnabled) {
                error_log('SMTP STARTTLS failed: unable to enable TLS crypto');
                fclose($socket);
                return false;
            }

            if (!smtpSendCommand($socket, 'EHLO ' . $hostname, [250])) {
                fclose($socket);
                return false;
            }
        }

        if (!empty($config['auth'])) {
            if (!smtpSendCommand($socket, 'AUTH LOGIN', [334])) {
                fclose($socket);
                return false;
            }

            if (!smtpSendCommand($socket, base64_encode($config['username']), [334])) {
                fclose($socket);
                return false;
            }

            if (!smtpSendCommand($socket, base64_encode($config['password']), [235])) {
                fclose($socket);
                return false;
            }
        }

        $fromEmail = $config['from_email'];
        if (!smtpSendCommand($socket, 'MAIL FROM:<' . $fromEmail . '>', [250])) {
            fclose($socket);
            return false;
        }

        foreach ($recipients as $recipient) {
            if (!smtpSendCommand($socket, 'RCPT TO:<' . $recipient . '>', [250, 251])) {
                fclose($socket);
                return false;
            }
        }

        if (!smtpSendCommand($socket, 'DATA', [354])) {
            fclose($socket);
            return false;
        }

        $encodedSubject = encodeEmailSubject($subject);
        $headers = buildEmailHeaders($config);

        $toHeader = implode(', ', $recipients);
        $messageLines = preg_split('/\r\n|\r|\n/', $htmlMessage);
        $dotStuffedLines = array_map(function($line) {
            return (isset($line[0]) && $line[0] === '.') ? '.' . $line : $line;
        }, $messageLines);
        $dotStuffedBody = implode("\r\n", $dotStuffedLines);

        $rawMessage = "Date: " . date(DATE_RFC2822) . "\r\n";
        $rawMessage .= "To: {$toHeader}\r\n";
        $rawMessage .= "Subject: {$encodedSubject}\r\n";
        $rawMessage .= $headers . "\r\n";
        $rawMessage .= $dotStuffedBody . "\r\n.\r\n";

        fwrite($socket, $rawMessage);
        if (!smtpSendCommand($socket, null, [250])) {
            fclose($socket);
            return false;
        }

        smtpSendCommand($socket, 'QUIT', [221]);
        fclose($socket);
        return true;
    } catch (Throwable $e) {
        error_log('SMTP send exception: ' . $e->getMessage());
        if (is_resource($socket)) {
            fclose($socket);
        }
        return false;
    }
}

/**
 * Envoyer un email HTML brut (sans wrapper automatique)
 * Utilisé quand le message contient déjà un HTML complet
 * @param string $to Adresse email du destinataire
 * @param string $subject Sujet de l'email
 * @param string $htmlMessage Corps du message HTML complet
 * @param string $fromEmail Adresse email de l'expéditeur
 * @param string $fromName Nom de l'expéditeur
 * @return bool True si envoyé avec succès, false sinon
 */
function sendRawHtmlEmail($to, $subject, $htmlMessage, $fromEmail = 'no-reply@1-ted.com', $fromName = '1-TED', $replyToEmail = null, $replyToName = null) {
    $config = getResolvedMailConfig($fromEmail, $fromName, $replyToEmail, $replyToName);

    if (isSmtpMailConfigReady($config)) {
        return sendViaSmtp($to, $subject, $htmlMessage, $config);
    }

    // Fallback mail() si SMTP non configuré
    error_log('Mail fallback via mail(): SMTP config manquante ou incomplète');
    $headers = buildEmailHeaders($config);
    $encodedSubject = encodeEmailSubject($subject);
    return mail($to, $encodedSubject, $htmlMessage, $headers);
}

/**
 * Envoyer un email avec wrapper HTML automatique
 * @param string $to Adresse email du destinataire
 * @param string $subject Sujet de l'email
 * @param string $message Corps du message (HTML simple qui sera wrappé)
 * @param string $fromEmail Adresse email de l'expéditeur
 * @param string $fromName Nom de l'expéditeur
 * @return bool True si envoyé avec succès, false sinon
 */
function sendEmail($to, $subject, $message, $fromEmail = 'no-reply@1-ted.com', $fromName = '1-TED', $replyToEmail = null, $replyToName = null) {
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

    return sendRawHtmlEmail($to, $subject, $htmlMessage, $fromEmail, $fromName, $replyToEmail, $replyToName);
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
 * Envoyer un email de contact à l'équipe 1-TED et une confirmation à l'envoyeur
 * @param string $firstName Prénom de l'envoyeur
 * @param string $lastName Nom de l'envoyeur
 * @param string $email Adresse email de l'envoyeur
 * @param string $phone Téléphone de l'envoyeur (optionnel)
 * @param string $subject Sujet du message
 * @param string $userMessage Corps du message
 * @return bool True si les deux emails ont été envoyés avec succès
 */
function sendContactEmail($firstName, $lastName, $email, $phone, $subject, $userMessage) {
    $contactEmail = 'contact@1-ted.com';
    $fullName = htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName);
    $phoneDisplay = !empty($phone) ? htmlspecialchars($phone) : 'Non renseigné';

    // --- Email vers l'équipe 1-TED ---
    $teamSubject = "Nouveau message de contact - {$subject}";

    $teamMessage = "
        <h2>Nouveau message de contact</h2>
        <table style='width:100%; border-collapse: collapse;'>
            <tr>
                <td style='padding: 8px; font-weight: bold; width: 150px;'>Prénom :</td>
                <td style='padding: 8px;'>" . htmlspecialchars($firstName) . "</td>
            </tr>
            <tr style='background:#f0f0f0;'>
                <td style='padding: 8px; font-weight: bold;'>Nom :</td>
                <td style='padding: 8px;'>" . htmlspecialchars($lastName) . "</td>
            </tr>
            <tr>
                <td style='padding: 8px; font-weight: bold;'>Email :</td>
                <td style='padding: 8px;'><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></td>
            </tr>
            <tr style='background:#f0f0f0;'>
                <td style='padding: 8px; font-weight: bold;'>Téléphone :</td>
                <td style='padding: 8px;'>{$phoneDisplay}</td>
            </tr>
            <tr>
                <td style='padding: 8px; font-weight: bold;'>Sujet :</td>
                <td style='padding: 8px;'>" . htmlspecialchars($subject) . "</td>
            </tr>
        </table>
        <h3 style='margin-top: 20px;'>Message :</h3>
        <div style='background:#fff; border-left: 4px solid #3498db; padding: 15px; margin-top: 10px;'>
            " . nl2br(htmlspecialchars($userMessage)) . "
        </div>
        <p style='margin-top: 20px; color: #777; font-size: 12px;'>
            Pour répondre, utilisez l'adresse : <a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a>
        </p>
    ";

    // IMPORTANT: l'expéditeur doit être une adresse du domaine (DMARC/SPF),
    // et l'adresse utilisateur passe en Reply-To.
    $teamSent = sendEmail(
        $contactEmail,
        $teamSubject,
        $teamMessage,
        'contact@1-ted.com',
        '1-TED Contact',
        $email,
        $fullName
    );

    // --- Email de confirmation à l'envoyeur ---
    $confirmSubject = "Nous avons bien reçu votre message - 1-TED";

    $confirmMessage = "
        <h2>Votre message a bien été reçu !</h2>
        <p>Bonjour {$fullName},</p>
        <p>Nous avons bien reçu votre message concernant : <strong>" . htmlspecialchars($subject) . "</strong>.</p>
        <p>Notre équipe vous répondra dans les plus brefs délais.</p>
        <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
        <p style='color: #777; font-size: 13px;'><em>Récapitulatif de votre message :</em></p>
        <div style='background:#fff; border-left: 4px solid #3498db; padding: 15px; color: #555; font-size: 13px;'>
            " . nl2br(htmlspecialchars($userMessage)) . "
        </div>
        <p style='margin-top: 20px;'>Cordialement,<br>L'équipe 1-TED</p>
    ";

    $confirmSent = sendEmail($email, $confirmSubject, $confirmMessage);

    return $teamSent && $confirmSent;
}

/**
 * Envoyer un email de bienvenue après inscription
 * @param string $email Adresse email du destinataire
 * @param string $firstname Prénom de l'utilisateur
 * @param string $baseUrl URL de base de l'application
 * @return bool True si envoyé avec succès
 */
function sendWelcomeEmail($email, $firstname, $baseUrl) {
    $subject = "🎉 Bienvenue sur 1-TED, {$firstname} !";
    
    $message = "
        <h2 style='color: #2c3e50;'>Bienvenue sur 1-TED, {$firstname} !</h2>
        <p>Nous sommes ravis de vous compter parmi nous ! 🎉</p>
        <p>Votre compte a été créé avec succès. Vous faites maintenant partie de la communauté 1-TED.</p>
        
        <div style='background: #f0f8ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>
            <h3 style='color: #3498db; margin-top: 0;'>Prochaines étapes :</h3>
            <ol style='line-height: 1.8;'>
                <li>📧 <strong>Vérifiez votre email</strong> - Un email de vérification vous a été envoyé</li>
                <li>✅ Cliquez sur le lien de vérification pour activer votre compte</li>
                <li>🚀 Commencez à utiliser toutes les fonctionnalités de 1-TED</li>
            </ol>
        </div>
        
        <p style='margin-top: 30px;'>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
        <p>À très bientôt sur 1-TED !</p>
        <p style='margin-top: 20px;'>Cordialement,<br><strong>L'équipe 1-TED</strong></p>
    ";
    
    return sendEmail($email, $subject, $message);
}
