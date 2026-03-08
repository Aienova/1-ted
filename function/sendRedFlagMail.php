<?php
/**
 * Traitement des signalements d'entreprises (Red Flag)
 * Envoie un email à support@1-ted.com avec les détails du signalement
 */

session_start();
require_once 'connectionDatabase.php';
require_once 'mailSystem.php';

header('Content-Type: application/json');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['token']) || !isset($_SESSION['role'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Non autorisé. Veuillez vous connecter.'
    ]);
    exit;
}

// Vérifier que c'est bien un candidat
if ($_SESSION['role'] !== 'candidate') {
    echo json_encode([
        'success' => false,
        'error' => 'Vous devez être connecté en tant que candidat'
    ]);
    exit;
}

// Récupérer les données POST
$data = json_decode(file_get_contents('php://input'), true);

$applicationId = $data['application_id'] ?? null;
$subject = $data['subject'] ?? null;
$reason = $data['reason'] ?? null;

// Validation
if (!$applicationId || !$subject || !$reason) {
    echo json_encode([
        'success' => false,
        'error' => 'Données manquantes'
    ]);
    exit;
}

try {
    $pdo = getDatabaseConnection();
    
    // Récupérer le candidate_id depuis le token
    $stmtCandidateId = $pdo->prepare("
        SELECT candidate_id 
        FROM account 
        WHERE token = :token
    ");
    $stmtCandidateId->execute([':token' => $_SESSION['token']]);
    $resultCandidateId = $stmtCandidateId->fetch(PDO::FETCH_ASSOC);
    
    if (!$resultCandidateId || !$resultCandidateId['candidate_id']) {
        echo json_encode([
            'success' => false,
            'error' => 'Candidat introuvable'
        ]);
        exit;
    }
    
    $candidateId = $resultCandidateId['candidate_id'];
    
    // Récupérer les informations de la candidature et de l'entreprise
    $stmt = $pdo->prepare("
        SELECT 
            a.id as application_id,
            a.candidate_id,
            a.job_id,
            j.title as job_title,
            j.company_id,
            comp.name as company_name,
            comp.nation_register_number,
            comp.website as company_website,
            comp.hq_location as company_location,
            comp.domain as company_domain,
            cont.email as company_email,
            cont.phone as company_phone,
            cont.first_name as contact_firstname,
            cont.last_name as contact_lastname,
            acc.email as candidate_email,
            i.first_name as candidate_firstname,
            i.last_name as candidate_lastname
        FROM application a
        JOIN job j ON a.job_id = j.id
        JOIN company comp ON j.company_id = comp.id
        LEFT JOIN contact cont ON comp.contact_id = cont.id
        JOIN candidate cand ON a.candidate_id = cand.id
        JOIN identity i ON cand.identity_id = i.id
        JOIN account acc ON acc.candidate_id = cand.id
        WHERE a.id = ? AND a.candidate_id = ?
    ");
    
    $stmt->execute([$applicationId, $candidateId]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$application) {
        echo json_encode([
            'success' => false,
            'error' => 'Candidature introuvable'
        ]);
        exit;
    }
    
    // Préparer le contenu de l'email
    $emailMessage = "
        <h2 style='color: #dc2626;'>🚩 Nouveau signalement d'entreprise</h2>
        
        <div style='background: #fee2e2; padding: 15px; border-left: 4px solid #dc2626; margin: 20px 0;'>
            <h3 style='margin: 0 0 10px 0; color: #991b1b;'>Motif du signalement</h3>
            <p style='margin: 0; font-size: 1.1rem; font-weight: bold;'>{$subject}</p>
        </div>
        
        <h3>📝 Description du problème</h3>
        <div style='background: #f9fafb; padding: 15px; border: 1px solid #e5e7eb; border-radius: 5px;'>
            <p style='white-space: pre-line;'>" . htmlspecialchars($reason) . "</p>
        </div>
        
        <h3>👤 Signalé par</h3>
        <ul style='list-style: none; padding: 0;'>
            <li><strong>Nom:</strong> {$application['candidate_firstname']} {$application['candidate_lastname']}</li>
            <li><strong>Email:</strong> {$application['candidate_email']}</li>
        </ul>
        
        <h3>🏢 Entreprise signalée</h3>
        <ul style='list-style: none; padding: 0;'>
            <li><strong>ID Entreprise:</strong> #{$application['company_id']}</li>
            <li><strong>Nom:</strong> {$application['company_name']}</li>
            <li><strong>Numéro d'enregistrement national:</strong> {$application['nation_register_number']}</li>
            <li><strong>Domaine d'activité:</strong> {$application['company_domain']}</li>
            <li><strong>Localisation siège:</strong> {$application['company_location']}</li>
            <li><strong>Site web:</strong> {$application['company_website']}</li>
        </ul>
        
        <h3>📞 Contact entreprise</h3>
        <ul style='list-style: none; padding: 0;'>
            <li><strong>Nom du contact:</strong> {$application['contact_firstname']} {$application['contact_lastname']}</li>
            <li><strong>Email:</strong> {$application['company_email']}</li>
            <li><strong>Téléphone:</strong> {$application['company_phone']}</li>
        </ul>
        
        <h3>📋 Détails de la candidature</h3>
        <ul style='list-style: none; padding: 0;'>
            <li><strong>ID Candidature:</strong> #{$application['application_id']}</li>
            <li><strong>Offre d'emploi:</strong> {$application['job_title']}</li>
            <li><strong>ID Offre:</strong> #{$application['job_id']}</li>
        </ul>
        
        <div style='background: #fffbeb; padding: 15px; border-left: 4px solid #f59e0b; margin-top: 20px;'>
            <p style='margin: 0;'><strong>⚠️ Action requise:</strong> Ce signalement nécessite une vérification et un traitement dans les plus brefs délais.</p>
        </div>
    ";
    
    // Sauvegarder le signalement dans le champ comment de la candidature
    $commentContent = "[SIGNALEMENT: {$subject}]\n\n{$reason}";
    
    $stmt = $pdo->prepare("
        UPDATE application 
        SET status = 'redflag',
            candidate_validation = 0,
            comment = :comment,
            updated_at = NOW()
        WHERE id = :application_id
    ");
    $stmt->execute([
        ':comment' => $commentContent,
        ':application_id' => $applicationId
    ]);
    
    // Envoyer l'email à l'équipe 1-TED avec tous les détails du signalement
    $emailTeamSent = sendEmail(
        'redflag@1-ted.com',
        "🚩 Signalement entreprise: {$application['company_name']} - {$subject}",
        $emailMessage,
        'noreply@1-ted.com',
        '1-TED Platform'
    );
    
    // Envoyer un email de confirmation au candidat
    $confirmationMessage = "
        <h2 style='color: #16a34a;'>✅ Votre signalement a été reçu</h2>
        
        <p>Bonjour {$application['candidate_firstname']},</p>
        
        <p>Nous avons bien reçu votre signalement concernant l'entreprise <strong>{$application['company_name']}</strong>.</p>
        
        <div style='background: #f0fdf4; padding: 15px; border-left: 4px solid #16a34a; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0; color: #15803d;'>
                <strong>🔍 Enquête en cours</strong><br>
                Notre équipe va enquêter sur ce signalement et prendre les mesures appropriées si nécessaire.
            </p>
        </div>
        
        <div style='background: #eff6ff; padding: 15px; border-left: 4px solid #3b82f6; margin: 20px 0; border-radius: 4px;'>
            <p style='margin: 0; color: #1e40af;'>
                <strong>🔒 Votre anonymat est protégé</strong><br>
                L'entreprise ne sera pas informée de votre identité. Votre candidature a été annulée.
            </p>
        </div>
        
        <p>Merci de contribuer à maintenir la qualité de notre plateforme.</p>
        
        <p style='color: #777; font-size: 14px; margin-top: 30px;'>
            Cordialement,<br>
            L'équipe 1-TED
        </p>
    ";
    
    $emailCandidateSent = sendEmail(
        $application['candidate_email'],
        "✅ Votre signalement a été reçu - Enquête en cours",
        $confirmationMessage,
        'redflag@1-ted.com',
        '1-TED - Enquêtes'
    );
    
    echo json_encode([
        'success' => true,
        'message' => 'Signalement envoyé avec succès',
        'emails' => [
            'team' => $emailTeamSent,
            'candidate' => $emailCandidateSent
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Erreur sendRedFlagMail: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de l\'envoi du signalement: ' . $e->getMessage()
    ]);
}
