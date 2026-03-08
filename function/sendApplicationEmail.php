<?php
/**
 * Envoie les emails de notification de candidature
 * - Email à l'entreprise
 * - Email de confirmation au candidat
 */

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/mailSystem.php';

/**
 * Envoie les notifications par email pour une nouvelle candidature
 * @param int $applicationId ID de la candidature
 * @return array Résultat de l'envoi
 */
function sendApplicationNotifications($applicationId) {
    try {
        $db = getDatabaseConnection();
        
        // Récupérer les informations complètes de la candidature
        $stmt = $db->prepare("
            SELECT 
                a.id as application_id,
                a.candidate_id,
                a.job_id,
                a.status,
                j.title as job_title,
                j.location as job_location,
                j.contract_type as job_contract_type,
                j.company_id,
                i.first_name,
                i.last_name,
                i.job_title as candidate_job_title,
                i.language_ids as languages,
                i.hobbie_ids as hobbies,
                c.avatar_url,
                c.media_code,
                c.skill_ids,
                acc.email as candidate_email,
                comp.name as company_name,
                cont.email as company_email
            FROM application a
            JOIN job j ON a.job_id = j.id
            JOIN candidate c ON a.candidate_id = c.id
            JOIN identity i ON c.identity_id = i.id
            JOIN account acc ON acc.candidate_id = c.id
            JOIN company comp ON j.company_id = comp.id
            LEFT JOIN contact cont ON comp.contact_id = cont.id
            WHERE a.id = :application_id
        ");
        
        $stmt->execute([':application_id' => $applicationId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return [
                'success' => false,
                'error' => 'Candidature introuvable'
            ];
        }
        
        // --- EMAIL À L'ENTREPRISE ---
        $companyEmailResult = sendCompanyNotification($data);
        
        // --- EMAIL AU CANDIDAT ---
        $candidateEmailResult = sendCandidateConfirmation($data);
        
        return [
            'success' => true,
            'company_email_sent' => $companyEmailResult,
            'candidate_email_sent' => $candidateEmailResult
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Erreur lors de l\'envoi des emails: ' . $e->getMessage()
        ];
    }
}

/**
 * Envoie l'email de notification à l'entreprise
 * @param array $data Données de la candidature
 * @return bool Succès de l'envoi
 */
function sendCompanyNotification($data) {
    // Préparer l'avatar URL (ou défaut si pas d'avatar)
    $avatarUrl = $data['avatar_url'] ?: 'https://1-ted.com/data/candidates/media/default/photo.jpg';
    
    // Récupérer les compétences depuis skill_ids
    $skills = '';
    if (!empty($data['skill_ids'])) {
        $skillIds = json_decode($data['skill_ids'], true);
        if (is_array($skillIds) && count($skillIds) > 0) {
            try {
                $db = getDatabaseConnection();
                $placeholders = implode(',', array_fill(0, count($skillIds), '?'));
                $stmt = $db->prepare("SELECT title FROM skill WHERE id IN ($placeholders)");
                $stmt->execute($skillIds);
                $skillTitles = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $skills = implode(', ', $skillTitles);
            } catch (Exception $e) {
                // Si erreur, on continue sans les skills
            }
        }
    }
    
    // Préparer les langues
    $languages = '';
    if (!empty($data['languages'])) {
        $languageIds = json_decode($data['languages'], true);
        if (is_array($languageIds) && count($languageIds) > 0) {
            try {
                $db = getDatabaseConnection();
                $placeholders = implode(',', array_fill(0, count($languageIds), '?'));
                $stmt = $db->prepare("SELECT language_name FROM language WHERE id IN ($placeholders)");
                $stmt->execute($languageIds);
                $languageNames = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $languages = implode(', ', $languageNames);
            } catch (Exception $e) {
                // Si erreur, on continue sans les langues
            }
        }
    }
    
    // Préparer les hobbies
    $hobbies = '';
    if (!empty($data['hobbies'])) {
        $hobbieIds = json_decode($data['hobbies'], true);
        if (is_array($hobbieIds) && count($hobbieIds) > 0) {
            try {
                $db = getDatabaseConnection();
                $placeholders = implode(',', array_fill(0, count($hobbieIds), '?'));
                $stmt = $db->prepare("SELECT title FROM hobbie WHERE id IN ($placeholders)");
                $stmt->execute($hobbieIds);
                $hobbieTitles = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $hobbies = implode(', ', $hobbieTitles);
            } catch (Exception $e) {
                // Si erreur, on continue sans les hobbies
            }
        }
    }
    
    // Construire le message
    $subject = "📩 Nouvelle candidature pour votre offre : " . $data['job_title'];
    
    $message = "
        <h2 style='color: #2c3e50;'>Nouvelle candidature reçue !</h2>
        
        <p>Bonjour,</p>
        
        <p>Un profil a postulé à votre offre d'emploi <strong>{$data['job_title']}</strong>.</p>
        
        <div style='background: #ecf0f1; padding: 20px; border-radius: 8px; margin: 20px 0;'>
            <h3 style='color: #34495e; margin-top: 0;'>👤 Informations du candidat</h3>
            
            <div style='text-align: center; margin: 20px 0;'>
                <img src='{$avatarUrl}' alt='Avatar' style='width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #3498db;'>
            </div>
            
            <table style='width: 100%; margin-top: 15px;'>
                <tr>
                    <td style='padding: 8px 0;'><strong>Profession :</strong></td>
                    <td style='padding: 8px 0;'>{$data['candidate_job_title']}</td>
                </tr>
                " . (!empty($skills) ? "
                <tr>
                    <td style='padding: 8px 0; vertical-align: top;'><strong>Compétences :</strong></td>
                    <td style='padding: 8px 0;'>{$skills}</td>
                </tr>
                " : "") . "
                " . (!empty($languages) ? "
                <tr>
                    <td style='padding: 8px 0; vertical-align: top;'><strong>Langues :</strong></td>
                    <td style='padding: 8px 0;'>{$languages}</td>
                </tr>
                " : "") . "
                " . (!empty($hobbies) ? "
                <tr>
                    <td style='padding: 8px 0; vertical-align: top;'><strong>Centres d'intérêt :</strong></td>
                    <td style='padding: 8px 0;'>{$hobbies}</td>
                </tr>
                " : "") . "
            </table>
            
            <div style='background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-top: 20px; border-radius: 4px;'>
                <p style='margin: 0; color: #856404;'><strong>ℹ️ Note :</strong> Pour des raisons de confidentialité, les coordonnées personnelles (nom, téléphone, email, adresse) ne sont pas affichées ici.</p>
            </div>
        </div>
        
        <div style='background: #e8f5e9; padding: 20px; border-radius: 8px; margin: 20px 0;'>
            <h3 style='color: #2e7d32; margin-top: 0;'>💼 Offre concernée</h3>
            <p style='margin: 5px 0;'><strong>Poste :</strong> {$data['job_title']}</p>
            " . (!empty($data['job_location']) ? "<p style='margin: 5px 0;'><strong>Localisation :</strong> {$data['job_location']}</p>" : "") . "
            " . (!empty($data['job_contract_type']) ? "<p style='margin: 5px 0;'><strong>Type de contrat :</strong> {$data['job_contract_type']}</p>" : "") . "
        </div>
        
        <div style='text-align: center; margin: 30px 0;'>
            <a href='https://1-ted.com/fr/user-account/company/applications' 
               style='display: inline-block; padding: 15px 40px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                🔍 Voir le profil complet et gérer la candidature
            </a>
        </div>
        
        <p style='color: #7f8c8d; font-size: 14px; margin-top: 30px;'>
            <strong>💡 Prochaines étapes :</strong><br>
            Connectez-vous à votre espace entreprise pour consulter le CV complet du candidat et gérer cette candidature.
        </p>
    ";
    
    return sendEmail(
        $data['company_email'],
        $subject,
        $message,
        'applications@1-ted.com',
        '1-TED Candidatures'
    );
}

/**
 * Envoie l'email de confirmation au candidat
 * @param array $data Données de la candidature
 * @return bool Succès de l'envoi
 */
function sendCandidateConfirmation($data) {
    $subject = "✅ Confirmation de candidature : " . $data['job_title'];
    
    $message = "
        <h2 style='color: #27ae60;'>Candidature envoyée avec succès !</h2>
        
        <p>Bonjour,</p>
        
        <p>Votre candidature pour l'offre <strong>{$data['job_title']}</strong> chez <strong>{$data['company_name']}</strong> a bien été transmise.</p>
        
        <div style='background: #e8f5e9; padding: 20px; border-radius: 8px; margin: 20px 0;'>
            <h3 style='color: #2e7d32; margin-top: 0;'>💼 Récapitulatif de votre candidature</h3>
            <p style='margin: 5px 0;'><strong>Poste :</strong> {$data['job_title']}</p>
            <p style='margin: 5px 0;'><strong>Entreprise :</strong> {$data['company_name']}</p>
            " . (!empty($data['job_location']) ? "<p style='margin: 5px 0;'><strong>Localisation :</strong> {$data['job_location']}</p>" : "") . "
            " . (!empty($data['job_contract_type']) ? "<p style='margin: 5px 0;'><strong>Type de contrat :</strong> {$data['job_contract_type']}</p>" : "") . "
            <p style='margin: 5px 0;'><strong>Statut :</strong> <span style='color: #f39c12;'>En attente de réponse</span></p>
        </div>
        
        <div style='background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0; color: #856404;'><strong>⏳ Que se passe-t-il maintenant ?</strong></p>
            <ul style='margin: 10px 0; padding-left: 20px; color: #856404;'>
                <li>L'entreprise a reçu votre profil et va l'examiner</li>
                <li>Vous recevrez un email si l'entreprise souhaite vous contacter</li>
                <li>Vous pouvez suivre l'évolution de votre candidature depuis votre espace personnel</li>
            </ul>
        </div>
        
        <div style='text-align: center; margin: 30px 0;'>
            <a href='https://1-ted.com/fr/user-account/candidate/my-applications' 
               style='display: inline-block; padding: 15px 40px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                📊 Suivre mes candidatures
            </a>
        </div>
        
        <p style='color: #7f8c8d; font-size: 14px;'>
            <strong>💡 Conseil :</strong> Pendant cette période, nous vous recommandons de continuer à postuler à d'autres offres qui correspondent à votre profil.
        </p>
        
        <p>Nous vous souhaitons bonne chance ! 🍀</p>
    ";
    
    return sendEmail(
        $data['candidate_email'],
        $subject,
        $message,
        'applications@1-ted.com',
        '1-TED Candidatures'
    );
}

// Si appelé directement via POST (pour tests ou usage externe)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['application_id'])) {
    header('Content-Type: application/json');
    $result = sendApplicationNotifications($_POST['application_id']);
    echo json_encode($result);
}
