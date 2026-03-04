<?php
/**
 * Traitement des signalements d'entreprises (Red Flag)
 * Envoie un email à redflag@1-ted.com avec les détails du signalement
 */

session_start();
require_once 'connectionDatabase.php';
require_once 'mailSystem.php';

header('Content-Type: application/json');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'candidate') {
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
    
    // Récupérer les informations de la candidature et de l'entreprise
    $stmt = $pdo->prepare("
        SELECT 
            a.id as application_id,
            a.candidate_id,
            a.job_id,
            j.title as job_title,
            j.company_id,
            c.name as company_name,
            c.siret,
            c.email as company_email,
            c.phone as company_phone,
            c.address as company_address,
            c.contact_firstname,
            c.contact_lastname,
            cand.email as candidate_email,
            cand.first_name as candidate_firstname,
            cand.last_name as candidate_lastname
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        JOIN company c ON j.company_id = c.id
        JOIN candidate cand ON a.candidate_id = cand.id
        WHERE a.id = ? AND a.candidate_id = ?
    ");
    
    $stmt->execute([$applicationId, $_SESSION['id']]);
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
            <li><strong>SIRET:</strong> {$application['siret']}</li>
            <li><strong>Email:</strong> {$application['company_email']}</li>
            <li><strong>Téléphone:</strong> {$application['company_phone']}</li>
            <li><strong>Adresse:</strong> {$application['company_address']}</li>
            <li><strong>Contact:</strong> {$application['contact_firstname']} {$application['contact_lastname']}</li>
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
    
    // Envoyer l'email à l'équipe Red Flag
    $emailSent = sendEmail(
        'redflag@1-ted.com',
        "🚩 Signalement entreprise: {$application['company_name']} - {$subject}",
        $emailMessage,
        'noreply@1-ted.com',
        '1-TED Platform'
    );
    
    if (!$emailSent) {
        throw new Exception("Erreur lors de l'envoi de l'email");
    }
    
    // Mettre à jour le statut de la candidature en "cancel"
    $stmt = $pdo->prepare("UPDATE applications SET status = 'cancel' WHERE id = ?");
    $stmt->execute([$applicationId]);
    
    // Enregistrer le signalement dans une table (optionnel - à créer si nécessaire)
    // $stmt = $pdo->prepare("INSERT INTO red_flags (application_id, company_id, subject, reason, created_at) VALUES (?, ?, ?, ?, NOW())");
    // $stmt->execute([$applicationId, $application['company_id'], $subject, $reason]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Signalement envoyé avec succès'
    ]);
    
} catch (Exception $e) {
    error_log("Erreur sendRedFlagMail: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de l\'envoi du signalement: ' . $e->getMessage()
    ]);
}
