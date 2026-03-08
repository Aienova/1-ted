<?php
/**
 * Templates d'emails pour l'approbation/refus de candidats et d'offres d'emploi
 */

/**
 * Génère le template HTML pour un email d'approbation
 * 
 * @param string $type 'job' ou 'candidate'
 * @param int $id ID de l'offre d'emploi ou du candidat
 * @param string $recipientName Nom du destinataire
 * @param string $itemTitle Titre de l'offre d'emploi ou nom complet du candidat
 * @param string $domain Domaine du site (ex: https://1-ted.com)
 * @param string $lang Langue (ex: fr, en)
 * @return string HTML de l'email
 */
function getApprovalEmailTemplate($type, $id, $recipientName, $itemTitle, $domain, $lang) {
    $isJob = ($type === 'job');
    
    // Construire l'URL de visualisation
    if ($isJob) {
        $viewUrl = "{$domain}/{$lang}/offers?id={$id}";
        $itemTypeText = "votre offre d'emploi";
        $itemTypeLong = "l'offre d'emploi";
    } else {
        $viewUrl = "{$domain}/{$lang}/candidates?id={$id}";
        $itemTypeText = "votre profil de candidat";
        $itemTypeLong = "le profil candidat";
    }
    
    return "
    <!DOCTYPE html>
    <html lang='fr'>
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
                background-color: #f5f5f5;
            }
            .container {
                max-width: 600px;
                margin: 30px auto;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            .header {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 40px 30px;
                text-align: center;
            }
            .icon-success {
                width: 80px;
                height: 80px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                font-size: 48px;
            }
            .header h1 {
                margin: 0;
                font-size: 28px;
                font-weight: 700;
            }
            .content {
                padding: 40px 30px;
            }
            .greeting {
                font-size: 18px;
                color: #1f2937;
                margin-bottom: 20px;
            }
            .message {
                font-size: 16px;
                color: #4b5563;
                line-height: 1.8;
                margin-bottom: 30px;
            }
            .item-title {
                font-weight: 600;
                color: #10b981;
            }
            .btn-container {
                text-align: center;
                margin: 35px 0;
            }
            .btn {
                display: inline-block;
                padding: 16px 40px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                font-size: 16px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }
            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(102, 126, 234, 0.6);
            }
            .info-box {
                background: #f0fdf4;
                border-left: 4px solid #10b981;
                padding: 20px;
                margin: 25px 0;
                border-radius: 6px;
            }
            .info-box p {
                margin: 0;
                color: #065f46;
                font-size: 14px;
            }
            .footer {
                background: #f9fafb;
                padding: 30px;
                text-align: center;
                border-top: 1px solid #e5e7eb;
            }
            .footer p {
                margin: 5px 0;
                color: #6b7280;
                font-size: 14px;
            }
            .footer a {
                color: #667eea;
                text-decoration: none;
            }
            .logo-text {
                font-size: 20px;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='icon-success'>✓</div>
                <h1>Félicitations !</h1>
            </div>
            
            <div class='content'>
                <p class='greeting'>Bonjour {$recipientName},</p>
                
                <p class='message'>
                    Nous avons le plaisir de vous informer que <span class='item-title'>{$itemTypeText}</span> 
                    a été <strong>approuvé</strong> par notre équipe 1-TED !
                </p>
                
                <div class='info-box'>
                    <p><strong>📋 Titre :</strong> {$itemTitle}</p>
                </div>
                
                <p class='message'>
                    {$itemTypeLong} est maintenant visible sur notre plateforme et accessible à tous nos utilisateurs.
                    " . ($isJob ? "Les candidats peuvent désormais postuler à cette offre." : "Les recruteurs peuvent désormais consulter ce profil.") . "
                </p>
                
                <div class='btn-container'>
                    <a href='{$viewUrl}' class='btn'>
                        🔍 Voir sur le site
                    </a>
                </div>
                
                <p class='message'>
                    Merci de votre confiance et de votre collaboration avec 1-TED.
                </p>
            </div>
            
            <div class='footer'>
                <p class='logo-text'>1-TED</p>
                <p>Votre plateforme de recrutement</p>
                <p><a href='{$domain}/{$lang}/contact'>Contactez-nous</a> | <a href='{$domain}/{$lang}'>Visiter notre site</a></p>
                <p style='margin-top: 20px; font-size: 12px;'>
                    Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
}

/**
 * Génère le template HTML pour un email de refus
 * 
 * @param string $type 'job' ou 'candidate'
 * @param int $id ID de l'offre d'emploi ou du candidat
 * @param string $recipientName Nom du destinataire
 * @param string $itemTitle Titre de l'offre d'emploi ou nom complet du candidat
 * @param string $domain Domaine du site (ex: https://1-ted.com)
 * @param string $lang Langue (ex: fr, en)
 * @return string HTML de l'email
 */
function getRejectionEmailTemplate($type, $id, $recipientName, $itemTitle, $domain, $lang) {
    $isJob = ($type === 'job');
    
    // URL pour les conditions d'utilisation et la page contact
    $termsUrl = "{$domain}/{$lang}/terms";
    $contactUrl = "{$domain}/{$lang}/contact";
    
    if ($isJob) {
        $itemTypeText = "votre offre d'emploi";
        $itemTypeLong = "L'offre d'emploi";
    } else {
        $itemTypeText = "votre profil de candidat";
        $itemTypeLong = "Le profil candidat";
    }
    
    return "
    <!DOCTYPE html>
    <html lang='fr'>
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
                background-color: #f5f5f5;
            }
            .container {
                max-width: 600px;
                margin: 30px auto;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            .header {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                padding: 40px 30px;
                text-align: center;
            }
            .icon-error {
                width: 80px;
                height: 80px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                font-size: 48px;
            }
            .header h1 {
                margin: 0;
                font-size: 28px;
                font-weight: 700;
            }
            .content {
                padding: 40px 30px;
            }
            .greeting {
                font-size: 18px;
                color: #1f2937;
                margin-bottom: 20px;
            }
            .message {
                font-size: 16px;
                color: #4b5563;
                line-height: 1.8;
                margin-bottom: 30px;
            }
            .item-title {
                font-weight: 600;
                color: #ef4444;
            }
            .btn-container {
                text-align: center;
                margin: 30px 0;
                display: flex;
                gap: 15px;
                justify-content: center;
            }
            .btn {
                display: inline-block;
                padding: 14px 30px;
                color: white!important;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                font-size: 15px;
                transition: all 0.3s ease;
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(102, 126, 234, 0.6);
            }
            .btn-secondary {
                background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
                box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
            }
            .btn-secondary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(107, 114, 128, 0.6);
            }
            .info-box {
                background: #fef2f2;
                border-left: 4px solid #ef4444;
                padding: 20px;
                margin: 25px 0;
                border-radius: 6px;
            }
            .info-box p {
                margin: 0;
                color: #991b1b;
                font-size: 14px;
            }
            .warning-box {
                background: #fffbeb;
                border-left: 4px solid #f59e0b;
                padding: 20px;
                margin: 25px 0;
                border-radius: 6px;
            }
            .warning-box p {
                margin: 0;
                color: #92400e;
                font-size: 14px;
                line-height: 1.6;
            }
            .footer {
                background: #f9fafb;
                padding: 30px;
                text-align: center;
                border-top: 1px solid #e5e7eb;
            }
            .footer p {
                margin: 5px 0;
                color: #6b7280;
                font-size: 14px;
            }
            .footer a {
                color: #667eea;
                text-decoration: none;
            }
            .logo-text {
                font-size: 20px;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='icon-error'>✗</div>
                <h1>Notification importante</h1>
            </div>
            
            <div class='content'>
                <p class='greeting'>Bonjour {$recipientName},</p>
                
                <p class='message'>
                    Nous avons le regret de vous annoncer que <span class='item-title'>{$itemTypeText}</span> 
                    a été <strong>rejeté</strong> par notre équipe de modération.
                </p>
                
                <div class='info-box'>
                    <p><strong>📋 Titre :</strong> {$itemTitle}</p>
                </div>
                
                <div class='warning-box'>
                    <p>
                        <strong>⚠️ Raisons possibles :</strong><br><br>
                        • Le contenu ne respecte pas nos conditions d'utilisation<br>
                        • Des informations sont manquantes ou incorrectes<br>
                        • Le contenu ne correspond pas aux standards de qualité requis
                    </p>
                </div>
                
                <p class='message'>
                    Nous vous invitons à :
                </p>
                
                <ul style='color: #4b5563; font-size: 16px; line-height: 1.8;'>
                    <li>Relire attentivement nos conditions d'utilisation</li>
                    <li>Vérifier que toutes les informations fournies sont complètes et exactes</li>
                    <li>Nous contacter si vous avez des questions ou besoin de clarifications</li>
                </ul>
                
                <div class='btn-container'>
                    <a href='{$termsUrl}' class='btn btn-secondary'>
                        📜 Conditions d'utilisation
                    </a>
                    <a href='{$contactUrl}' class='btn btn-primary'>
                        ✉️ Nous contacter
                    </a>
                </div>
                
                <p class='message'>
                    Notre équipe reste à votre disposition pour toute question.
                </p>
            </div>
            
            <div class='footer'>
                <p class='logo-text'>1-TED</p>
                <p>Votre plateforme de recrutement</p>
                <p><a href='{$contactUrl}'>Contactez-nous</a> | <a href='{$domain}/{$lang}'>Visiter notre site</a></p>
                <p style='margin-top: 20px; font-size: 12px;'>
                    Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>
