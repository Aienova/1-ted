<?php
/**
 * Gestion des emails de notification pour les candidatures
 * Gère tous les types d'emails entre candidats et entreprises
 */

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/mailSystem.php';
require_once __DIR__ . '/mailConfig.php';

/**
 * Envoie les emails de notification selon le contexte de la candidature
 * 
 * @param int $candidateId ID du candidat
 * @param int $jobId ID de l'offre d'emploi
 * @param bool $isCompanyResponse True si c'est une réponse de l'entreprise
 * @param bool $companyValidation True si l'entreprise valide la candidature
 * @param bool $candidateValidation True si le candidat valide sa candidature
 * @param bool $isredflag True si c'est un signalement
 * @param string $comment Commentaire optionnel
 * @return array Résultat de l'envoi
 */
function sendApplicationNotification($candidateId, $jobId, $isCompanyResponse, $companyValidation, $candidateValidation, $isredflag, $comment = '') {
    try {
        error_log("📧 [sendApplicationNotification] Début - candidateId=$candidateId, jobId=$jobId");
        
        $pdo = getDatabaseConnection();
        
        // Récupérer les informations du candidat
        $candidateStmt = $pdo->prepare("
            SELECT 
                c.id as candidate_id,
                c.seniority,
                c.date_availability as availability_date,
                i.first_name as candidate_firstname,
                i.last_name as candidate_lastname,
                i.job_title as candidate_job_title,
                acc.email as candidate_email
            FROM candidate c
            JOIN identity i ON c.identity_id = i.id
            JOIN account acc ON acc.candidate_id = c.id
            WHERE c.id = ?
        ");
        
        $candidateStmt->execute([$candidateId]);
        $candidateData = $candidateStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$candidateData) {
            error_log("❌ [sendApplicationNotification] Candidat $candidateId introuvable");
            return [
                'success' => false,
                'error' => 'Candidat introuvable'
            ];
        }
        
        // Récupérer les informations du job et de l'entreprise
        $jobStmt = $pdo->prepare("
            SELECT 
                j.id as job_id,
                j.title as job_title,
                j.company_id,
                comp.name as company_name,
                cont.email as company_email,
                cont.first_name as contact_firstname,
                cont.last_name as contact_lastname
            FROM job j
            JOIN company comp ON j.company_id = comp.id
            LEFT JOIN contact cont ON comp.contact_id = cont.id
            WHERE j.id = ?
        ");
        
        $jobStmt->execute([$jobId]);
        $jobData = $jobStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$jobData) {
            error_log("❌ [sendApplicationNotification] Job $jobId introuvable");
            return [
                'success' => false,
                'error' => 'Offre d\'emploi introuvable'
            ];
        }
        
        // Fusionner les données
        $data = array_merge($candidateData, $jobData);
        
        // Déterminer le domaine et la langue
        $envMap = mailGetEnvMap();
        $isLocal = $envMap['MODE_LOCAL'] === 'true';
        $domain = $isLocal ? 'https://fra-beta.1-ted.com' : 'https://fra-beta.1-ted.com';
        $lang = $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'fr';
        
        // Mapper seniority ID vers nom
        $seniorityNames = [
            0 => 'Apprenti',
            1 => 'Junior',
            2 => 'Confirmé',
            3 => 'Senior'
        ];
        $seniorityName = $data['seniority'] !== null ? $seniorityNames[$data['seniority']] : 'Non spécifié';
        
        // Formater la date de disponibilité
        $availabilityDate = $data['availability_date'] ? date('d/m/Y', strtotime($data['availability_date'])) : 'Non spécifiée';
        
        $emailsSent = [];
        
        // CAS 1: Candidat postule à une offre (isCompanyResponse = false, candidateValidation = true)
        if (!$isCompanyResponse && $candidateValidation && !$isredflag) {
            error_log("📝 [sendApplicationNotification] CAS 1: Candidat postule");
            
            // Email à l'entreprise
            $companyEmail = sendNewApplicationToCompany(
                $data['company_email'],
                $data['contact_firstname'],
                $data['contact_lastname'],
                $data['candidate_firstname'],
                $data['candidate_lastname'],
                $data['candidate_job_title'],
                $seniorityName,
                $availabilityDate,
                $data['job_title'],
                $data['company_name'],
                $domain,
                $lang
            );
            $emailsSent['company'] = $companyEmail;
            error_log("🏢 [sendApplicationNotification] Email entreprise: " . ($companyEmail ? '✅ envoyé' : '❌ échoué') . " à {$data['company_email']}");
            
            // Email de confirmation au candidat
            $candidateEmail = sendApplicationConfirmationToCandidate(
                $data['candidate_email'],
                $data['candidate_firstname'],
                $data['company_name'],
                $data['job_title']
            );
            $emailsSent['candidate'] = $candidateEmail;
            error_log("👤 [sendApplicationNotification] Email candidat: " . ($candidateEmail ? '✅ envoyé' : '❌ échoué') . " à {$data['candidate_email']}");
        }
        
        // CAS 2: Entreprise accepte la candidature (isCompanyResponse = true, companyValidation = true)
        if ($isCompanyResponse && $companyValidation && !$isredflag) {
            $candidateEmail = sendAcceptanceToCandidate(
                $data['candidate_email'],
                $data['candidate_firstname'],
                $data['company_name'],
                $data['job_title'],
                $domain,
                $lang
            );
            $emailsSent['candidate'] = $candidateEmail;
            
            // Notifier l'équipe 1-TED du recrutement
            $teamEmail = sendRecruitmentNotificationToTeam(
                $data['candidate_firstname'],
                $data['candidate_lastname'],
                $data['candidate_job_title'],
                $data['company_name'],
                $data['job_title']
            );
            $emailsSent['team'] = $teamEmail;
            error_log("👥 [sendApplicationNotification] Email équipe 1-TED: " . ($teamEmail ? '✅ envoyé' : '❌ échoué') . " à applications@1-ted.com");
        }
        
        // CAS 3: Entreprise refuse la candidature (isCompanyResponse = true, companyValidation = false)
        if ($isCompanyResponse && !$companyValidation && !$isredflag) {
            error_log("📝 [sendApplicationNotification] CAS 3: Entreprise refuse la candidature");
            $candidateEmail = sendRejectionToCandidate(
                $data['candidate_email'],
                $data['candidate_firstname'],
                $data['company_name'],
                $data['job_title'],
                $comment
            );
            $emailsSent['candidate'] = $candidateEmail;
            error_log("👤 [sendApplicationNotification] Email refus candidat: " . ($candidateEmail ? '✅ envoyé' : '❌ échoué') . " à {$data['candidate_email']}");
        }
        
        // CAS 4: Candidat retire sa candidature (isCompanyResponse = false, candidateValidation = false)
        // Note: Pour les signalements (isredflag = true), on utilise le CAS 5 avec un message différent
        if (!$isCompanyResponse && !$candidateValidation && !$isredflag) {
            error_log("📧 [sendApplicationNotification] CAS 4: Retrait candidature");
            $companyEmail = sendWithdrawalToCompany(
                $data['company_email'],
                $data['contact_firstname'],
                $data['contact_lastname'],
                $data['candidate_firstname'],
                $data['candidate_lastname'],
                $data['job_title'],
                $comment
            );
            $emailsSent['company'] = $companyEmail;
            error_log("🏢 [sendApplicationNotification] Email retrait entreprise: " . ($companyEmail ? '✅ envoyé' : '❌ échoué') . " à {$data['company_email']}");
        }
        
        // CAS 5: Signalement (isredflag = true)
        if ($isredflag) {
            error_log("📧 [sendApplicationNotification] CAS 5: Signalement - Envoi email entreprise");
            error_log("   Email entreprise: " . ($data['company_email'] ?? 'NULL'));
            error_log("   Contact: {$data['contact_firstname']} {$data['contact_lastname']}");
            error_log("   Entreprise: {$data['company_name']}");
            
            if (empty($data['company_email'])) {
                error_log("❌ [sendApplicationNotification] ERREUR: Email entreprise vide ou NULL");
                $emailsSent['company'] = false;
                $emailsSent['error'] = 'Email entreprise introuvable';
            } else {
                $companyEmail = sendRedFlagNotificationToCompany(
                    $data['company_email'],
                    $data['contact_firstname'],
                    $data['contact_lastname'],
                    $data['company_name']
                );
                $emailsSent['company'] = $companyEmail;
                error_log("🏢 [sendApplicationNotification] Email signalement entreprise: " . ($companyEmail ? '✅ envoyé' : '❌ échoué') . " à {$data['company_email']}");
            }
        }
        
        return [
            'success' => true,
            'emails_sent' => $emailsSent
        ];
        
    } catch (Exception $e) {
        error_log("Erreur sendApplicationNotification: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

// ==================== TEMPLATES D'EMAILS ====================

/**
 * Email à l'entreprise: nouvelle candidature reçue
 */
function sendNewApplicationToCompany($companyEmail, $contactFirstname, $contactLastname, $candidateFirstname, $candidateLastname, $candidateJobTitle, $seniority, $availability, $jobTitle, $companyName, $domain, $lang) {
    $contactName = trim($contactFirstname . ' ' . $contactLastname);
    if (empty($contactName)) {
        $contactName = 'Cher recruteur';
    }
    
    $subject = "📩 Nouvelle candidature pour votre offre : $jobTitle";
    
    $message = "
        <h2 style='color: #1e93e5;'>📩 Nouvelle Candidature</h2>
        
        <p>Bonjour $contactName,</p>
        
        <p>Vous avez un nouveau candidat qui a postulé à votre offre d'emploi <strong>$jobTitle</strong> chez <strong>$companyName</strong>.</p>
        
        <div style='background: #f8f9fa; border-left: 4px solid #1e93e5; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <h3 style='color: #1e93e5; margin-top: 0;'>👤 Informations générales</h3>
            <ul style='list-style: none; padding: 0;'>
                <li><strong>Nom :</strong> $candidateFirstname $candidateLastname</li>
                <li><strong>Intitulé du poste :</strong> $candidateJobTitle</li>
                <li><strong>Ancienneté :</strong> $seniority</li>
                <li><strong>Disponibilité :</strong> $availability</li>
            </ul>
        </div>
        
        <p>Veuillez vous connecter sur votre espace entreprise pour consulter son CV complet et procéder à la suite de sa candidature.</p>
        
        <div style='text-align: center; margin: 30px 0;'>
            <a href='{$domain}/{$lang}/user-account/company/applications' style='display: inline-block; padding: 15px 30px; background: #3498db; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;'>
                🔍 Consulter la candidature
            </a>
        </div>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            L'équipe 1-TED vous accompagne dans votre recrutement.
        </p>
    ";
    
    return sendEmail($companyEmail, $subject, $message, 'applications@1-ted.com', '1-TED Candidatures');
}

/**
 * Email au candidat: confirmation d'envoi de candidature
 */
function sendApplicationConfirmationToCandidate($candidateEmail, $candidateFirstname, $companyName, $jobTitle) {
    $subject = "✅ Candidature envoyée : $jobTitle chez $companyName";
    
    // Récupérer le domaine et la langue
    $envMap = mailGetEnvMap();
    $isLocal = $envMap['MODE_LOCAL'] === 'true';
    $domain = $isLocal ? 'https://fra-beta.1-ted.com' : 'https://fra-beta.1-ted.com';
    $lang = $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'fr';
    
    $message = "
        <h2 style='color: #10b981;'>✅ Candidature Confirmée</h2>
        
        <p>Bonjour $candidateFirstname,</p>
        
        <p>L'entreprise <strong>$companyName</strong> a bien reçu votre candidature pour le poste de <strong>$jobTitle</strong>.</p>
        
        <div style='background: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0;'><strong>📧 Prochaine étape :</strong> L'entreprise examinera votre profil et reviendra vers vous d'ici peu avec sa réponse.</p>
        </div>
        
        <p>Vous pouvez suivre le processus de votre demande dans l'espace candidat :</p>
        
        <div style='text-align: center; margin: 30px 0;'>
            <a href='{$domain}/{$lang}/user-account/candidate/my-applications' style='display: inline-block; padding: 15px 30px; background: #10b981; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;'>
                📊 Suivre votre candidature
            </a>
        </div>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            Bonne chance ! 🍀<br>
            L'équipe 1-TED
        </p>
    ";
    
    return sendEmail($candidateEmail, $subject, $message, 'applications@1-ted.com', '1-TED Candidatures');
}

/**
 * Email au candidat: félicitations, candidature acceptée
 */
function sendAcceptanceToCandidate($candidateEmail, $candidateFirstname, $companyName, $jobTitle, $domain, $lang) {
    $subject = "🎉 Félicitations ! Votre candidature a été retenue par $companyName";
    
    $message = "
        <h2 style='color: #f59e0b;'>🎉 Félicitations !</h2>
        
        <p>Bonjour $candidateFirstname,</p>
        
        <p>Excellente nouvelle ! La société <strong>$companyName</strong> a retenu votre candidature pour le poste de <strong>$jobTitle</strong>.</p>
        
        <div style='background: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0; font-size: 16px;'>
                <strong>🌟 Nous sommes ravis de voir que vous avez trouvé la mission qui vous intéresse !</strong>
            </p>
        </div>
        
        <p>L'entreprise vous contactera très prochainement pour discuter des prochaines étapes et organiser un entretien.</p>
        
        <div style='text-align: center; margin: 30px 0;'>
            <a href='{$domain}/{$lang}/user-account/candidate/my-applications' style='display: inline-block; padding: 15px 30px; background: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;'>
                📊 Voir mes candidatures
            </a>
        </div>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            Toute l'équipe 1-TED vous félicite et vous souhaite une belle réussite ! 🚀
        </p>
    ";
    
    return sendEmail($candidateEmail, $subject, $message, 'applications@1-ted.com', '1-TED Candidatures');
}

/**
 * Email au candidat: refus de candidature
 */
function sendRejectionToCandidate($candidateEmail, $candidateFirstname, $companyName, $jobTitle, $comment) {
    error_log("📧 [sendRejectionToCandidate] Envoi email de refus à $candidateEmail pour $companyName / $jobTitle");
    error_log("📧 [sendRejectionToCandidate] Commentaire: " . ($comment ? substr($comment, 0, 50) . '...' : 'vide'));
    
    // Récupérer le domaine et la langue
    $envMap = mailGetEnvMap();
    $isLocal = $envMap['MODE_LOCAL'] === 'true';
    $domain = $isLocal ? 'https://fra-beta.1-ted.com' : 'https://fra-beta.1-ted.com';
    $lang = $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'fr';
    
    $subject = "Réponse à votre candidature : $jobTitle chez $companyName";
    
    // Gérer le commentaire avec message par défaut si vide
    $commentHtml = '';
    if (!empty(trim($comment))) {
        $commentHtml = "
        <div style='background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 20px; margin: 20px 0; border-radius: 8px;'>
            <p style='margin: 0 0 10px 0; color: #1e40af;'><strong>💬 Retour de l'entreprise :</strong></p>
            <p style='margin: 0; color: #1e3a8a; font-style: italic; line-height: 1.6;'>" . nl2br(htmlspecialchars($comment)) . "</p>
        </div>
        ";
    } else {
        $commentHtml = "
        <div style='background: #f9fafb; border-left: 4px solid #9ca3af; padding: 20px; margin: 20px 0; border-radius: 8px;'>
            <p style='margin: 0; color: #6b7280; font-style: italic;'>💬 Aucune précision sur leur refus.</p>
        </div>
        ";
    }
    
    $message = "
        <h2 style='color: #ef4444;'>📩 Réponse à votre candidature</h2>
        
        <p>Bonjour $candidateFirstname,</p>
        
        <p>Nous vous remercions de l'intérêt que vous avez porté au poste de <strong>$jobTitle</strong> chez <strong>$companyName</strong>.</p>
        
        <div style='background: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; margin: 20px 0; border-radius: 8px;'>
            <p style='margin: 0; color: #991b1b;'>
                Malheureusement, après étude de votre profil, l'entreprise a décidé de ne pas donner suite à votre candidature pour ce poste.
            </p>
        </div>
        
        $commentHtml
        
        <div style='background: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 8px;'>
            <p style='margin: 0;'>
                <strong style='color: #15803d;'>💪 Ne vous découragez pas !</strong><br>
                <span style='color: #166534;'>De nouvelles opportunités sont publiées régulièrement sur notre plateforme. Continuez à postuler, votre prochaine opportunité vous attend !</span>
            </p>
        </div>
        
        <div style='text-align: center; margin: 30px 0;'>
            <a href='$domain/$lang/offers' 
               style='display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 1.1rem;'>
                🔍 Rechercher une autre offre
            </a>
        </div>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            💡 <strong>Conseil :</strong> Continuez à enrichir votre profil et à postuler aux offres qui correspondent à vos compétences.<br>
            L'équipe 1-TED vous accompagne dans votre recherche d'emploi. 🍀
        </p>
    ";
    
    error_log("📧 [sendRejectionToCandidate] Appel à sendEmail vers $candidateEmail avec sujet: $subject");
    $result = sendEmail($candidateEmail, $subject, $message, 'applications@1-ted.com', '1-TED Candidatures');
    error_log("📧 [sendRejectionToCandidate] Résultat sendEmail: " . ($result ? 'true' : 'false'));
    
    return $result;
}

/**
 * Email à l'entreprise: candidat retire sa candidature
 */
function sendWithdrawalToCompany($companyEmail, $contactFirstname, $contactLastname, $candidateFirstname, $candidateLastname, $jobTitle, $comment) {
    $contactName = trim($contactFirstname . ' ' . $contactLastname);
    if (empty($contactName)) {
        $contactName = 'Cher recruteur';
    }
    
    $subject = "ℹ️ Retrait de candidature : $jobTitle";
    
    $commentHtml = '';
    if (!empty($comment)) {
        $commentHtml = "
        <div style='background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 20px 0;'>
            <p style='margin: 0;'><strong>Message du candidat :</strong></p>
            <p style='margin: 10px 0 0 0; font-style: italic;'>" . nl2br(htmlspecialchars($comment)) . "</p>
        </div>
        ";
    }
    
    $message = "
        <h2 style='color: #6366f1;'>ℹ️ Retrait de Candidature</h2>
        
        <p>Bonjour $contactName,</p>
        
        <p>Nous vous informons que <strong>$candidateFirstname $candidateLastname</strong> a décidé de retirer sa candidature pour l'offre <strong>$jobTitle</strong>.</p>
        
        $commentHtml
        
        <div style='background: #eef2ff; border-left: 4px solid #6366f1; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0;'>
                Cette candidature a été automatiquement annulée dans votre espace entreprise.
            </p>
        </div>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            L'équipe 1-TED
        </p>
    ";
    
    return sendEmail($companyEmail, $subject, $message, 'applications@1-ted.com', '1-TED Candidatures');
}

/**
 * Email à l'entreprise: signalement (red flag)
 * Message standard sans détails pour préserver l'intégrité de l'enquête
 */
function sendRedFlagNotificationToCompany($companyEmail, $contactFirstname, $contactLastname, $companyName) {
    $contactName = trim($contactFirstname . ' ' . $contactLastname);
    if (empty($contactName)) {
        $contactName = 'Cher partenaire';
    }
    
    $subject = "⚠️ Signalement concernant vos méthodes de recrutement";
    
    $message = "
        <h2 style='color: #ef4444;'>⚠️ Signalement</h2>
        
        <p>Bonjour $contactName,</p>
        
        <p>Un signalement a été lancé à l'encontre de vos méthodes de recrutement selon un candidat.</p>
        
        <div style='background: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0; color: #991b1b;'>
                <strong>🔍 Enquête en cours</strong><br>
                L'équipe 1-TED enquêtera sur cet incident et reviendra vers vous dans les plus brefs délais.
            </p>
        </div>
        
        <p>Nous prenons ce type de signalement très au sérieux et nous nous engageons à traiter cette situation de manière équitable et transparente.</p>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            Cordialement,<br>
            L'équipe 1-TED
        </p>
    ";
    
    return sendEmail($companyEmail, $subject, $message, 'redflag@1-ted.com', '1-TED - Enquêtes');
}

/**
 * Email au candidat: confirmation de réception du signalement
 */
function sendRedFlagConfirmationToCandidate($candidateEmail, $candidateFirstname, $companyName, $jobTitle) {
    $subject = "✅ Votre signalement a été reçu - Enquête en cours";
    
    $message = "
        <h2 style='color: #f59e0b;'>✅ Signalement reçu</h2>
        
        <p>Bonjour $candidateFirstname,</p>
        
        <p>Nous avons bien reçu votre signalement concernant l'entreprise <strong>$companyName</strong> pour l'offre <strong>$jobTitle</strong>.</p>
        
        <div style='background: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0;'>
                <strong>🔍 Enquête en cours</strong><br>
                L'équipe 1-TED prend votre signalement très au sérieux. Nous allons mener une enquête approfondie et reviendrons vers vous dans les plus brefs délais.
            </p>
        </div>
        
        <div style='background: #eef2ff; border-left: 4px solid #6366f1; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0;'>
                <strong>ℹ️ Votre candidature</strong><br>
                Votre candidature pour cette offre a été automatiquement annulée. Vous ne recevrez plus de notifications concernant cette candidature.
            </p>
        </div>
        
        <p>Nous vous remercions d'avoir porté cette situation à notre attention. Votre démarche contribue à maintenir la qualité et l'intégrité de notre plateforme.</p>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            Cordialement,<br>
            L'équipe 1-TED - Service Enquêtes
        </p>
    ";
    
    return sendEmail($candidateEmail, $subject, $message, 'redflag@1-ted.com', '1-TED - Enquêtes');
}

/**
 * Email à l'équipe 1-TED: notification de recrutement réussi
 */
function sendRecruitmentNotificationToTeam($candidateFirstname, $candidateLastname, $candidateJobTitle, $companyName, $jobTitle) {
    $subject = "🎉 Recrutement réussi via 1-TED : $companyName recrute $candidateFirstname $candidateLastname";
    
    $message = "
        <h2 style='color: #10b981;'>🎉 Nouveau Recrutement !</h2>
        
        <p>Bonjour l'équipe 1-TED,</p>
        
        <p>Excellente nouvelle ! Un recrutement vient d'être finalisé via notre plateforme :</p>
        
        <div style='background: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <h3 style='color: #15803d; margin-top: 0;'>👤 Candidat recruté</h3>
            <ul style='list-style: none; padding: 0;'>
                <li><strong>Nom :</strong> $candidateFirstname $candidateLastname</li>
                <li><strong>Profession :</strong> $candidateJobTitle</li>
            </ul>
        </div>
        
        <div style='background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <h3 style='color: #1e40af; margin-top: 0;'>🏢 Entreprise</h3>
            <ul style='list-style: none; padding: 0;'>
                <li><strong>Nom :</strong> $companyName</li>
                <li><strong>Poste :</strong> $jobTitle</li>
            </ul>
        </div>
        
        <div style='background: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0; color: #92400e;'>
                <strong>✨ Félicitations !</strong><br>
                Un nouveau succès pour la plateforme 1-TED. Cette entreprise a trouvé son talent grâce à nos services.
            </p>
        </div>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            📊 Pensez à mettre à jour vos statistiques de placements !<br>
            Notification automatique - Plateforme 1-TED
        </p>
    ";
    
    return sendEmail('applications@1-ted.com', $subject, $message, 'noreply@1-ted.com', '1-TED Platform');
}