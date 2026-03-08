<?php
/**
 * Envoie un email de notification d'approbation ou de refus
 * pour les offres d'emploi et les candidats
 */

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/mailSystem.php';
require_once __DIR__ . '/approvalEmailTemplates.php';
require_once __DIR__ . '/mailConfig.php';

/**
 * Récupère la configuration du domaine et de la langue
 */
function getDomainAndLang() {
    // Récupérer le domaine depuis local.env
    $envMap = mailGetEnvMap();
    
    // Vérifier si on est en mode local ou production
    $isLocal = isset($envMap['MODE_LOCAL']) && $envMap['MODE_LOCAL'] === 'true';
    
    if ($isLocal) {
        $domain = $envMap['BASE_URL'] ?? 'https://fra-beta.1-ted.com';
    } else {
        $domain = $envMap['PROD_BASE_URL'] ?? 'https://fra-beta.1-ted.com';
    }
    
    // Détecter la langue (par défaut: fr)
    // Vous pouvez améliorer cette détection selon votre logique
    $lang = 'fr';
    if (isset($_SESSION['lang'])) {
        $lang = $_SESSION['lang'];
    } elseif (isset($_COOKIE['lang'])) {
        $lang = $_COOKIE['lang'];
    }
    
    return ['domain' => $domain, 'lang' => $lang];
}

/**
 * Récupère les informations d'une offre d'emploi et l'email de contact de l'entreprise
 */
function getJobInfoAndContactEmail($jobId) {
    $db = getDatabaseConnection();
    
    $query = "
        SELECT 
            j.id,
            j.title,
            j.company_id,
            c.name as company_name,
            c.contact_id,
            cnt.first_name as contact_first_name,
            cnt.last_name as contact_last_name,
            cnt.email as contact_email
        FROM job j
        LEFT JOIN company c ON j.company_id = c.id
        LEFT JOIN contact cnt ON c.contact_id = cnt.id
        WHERE j.id = :jobId
    ";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result || !$result['contact_email']) {
        throw new Exception("Impossible de récupérer l'email de contact pour l'offre d'emploi #" . $jobId);
    }
    
    return $result;
}

/**
 * Récupère les informations d'un candidat et son email
 */
function getCandidateInfoAndEmail($candidateId) {
    $db = getDatabaseConnection();
    
    $query = "
        SELECT 
            c.id,
            i.first_name,
            i.last_name,
            i.email as candidate_email,
            i.job_title
        FROM candidate c
        LEFT JOIN identity i ON c.identity_id = i.id
        WHERE c.id = :candidateId
    ";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':candidateId', $candidateId, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result || !$result['candidate_email']) {
        throw new Exception("Impossible de récupérer l'email du candidat #" . $candidateId);
    }
    
    return $result;
}

/**
 * Envoie l'email d'approbation ou de refus
 * 
 * @param int $approvalResponse 1 pour approuvé, 0 pour refusé
 * @param string $requestType 'job' ou 'candidate'
 * @param int $requestId ID de l'offre d'emploi ou du candidat
 * @return array Réponse avec le statut d'envoi
 */
function sendApprovalResponse($approvalResponse, $requestType, $requestId) {
    try {
        // Validation des paramètres
        if (!in_array($requestType, ['job', 'candidate'])) {
            throw new Exception("Type de requête invalide: {$requestType}");
        }
        
        if (!in_array($approvalResponse, [0, 1], true)) {
            throw new Exception("Réponse d'approbation invalide: {$approvalResponse}");
        }
        
        // Récupérer le domaine et la langue
        $config = getDomainAndLang();
        $domain = $config['domain'];
        $lang = $config['lang'];
        
        // Récupérer les informations selon le type
        if ($requestType === 'job') {
            $info = getJobInfoAndContactEmail($requestId);
            $recipientEmail = $info['contact_email'];
            $recipientName = trim($info['contact_first_name'] . ' ' . $info['contact_last_name']);
            $itemTitle = $info['title'];
        } else { // candidate
            $info = getCandidateInfoAndEmail($requestId);
            $recipientEmail = $info['candidate_email'];
            $recipientName = trim($info['first_name'] . ' ' . $info['last_name']);
            $itemTitle = $info['job_title'] ?? 'Profil candidat';
        }
        
        // Générer le template HTML
        if ($approvalResponse === 1) {
            // Email d'approbation
            $subject = "✓ Votre " . ($requestType === 'job' ? "offre d'emploi" : "profil candidat") . " a été approuvé !";
            $htmlMessage = getApprovalEmailTemplate(
                $requestType,
                $requestId,
                $recipientName,
                $itemTitle,
                $domain,
                $lang
            );
        } else {
            // Email de refus
            $subject = "Notification concernant votre " . ($requestType === 'job' ? "offre d'emploi" : "profil candidat");
            $htmlMessage = getRejectionEmailTemplate(
                $requestType,
                $requestId,
                $recipientName,
                $itemTitle,
                $domain,
                $lang
            );
        }
        
        // Envoyer l'email via sendRawHtmlEmail (car on a déjà un HTML complet)
        $emailSent = sendRawHtmlEmail(
            $recipientEmail,
            $subject,
            $htmlMessage,
            'no-reply@1-ted.com',
            '1-TED',
            'contact@1-ted.com',
            '1-TED Contact'
        );
        
        // Construire la réponse
        $response = [
            "approvalResponse" => $approvalResponse,
            "requestType" => $requestType,
            "requestId" => $requestId,
            "emailSent" => $emailSent,
            "recipientEmail" => $recipientEmail
        ];
        
        if (!$emailSent) {
            error_log("Échec de l'envoi de l'email d'approbation/refus pour {$requestType} #{$requestId} à {$recipientEmail}");
            $response['emailError'] = "L'email n'a pas pu être envoyé";
        }
        
        return $response;
        
    } catch (Exception $e) {
        error_log("Erreur dans sendApprovalResponse: " . $e->getMessage());
        return [
            "approvalResponse" => $approvalResponse,
            "requestType" => $requestType,
            "requestId" => $requestId,
            "emailSent" => false,
            "error" => $e->getMessage()
        ];
    }
}

// Si appelé directement (pas en include), traiter la requête
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/json');
    
    // Récupérer les paramètres (POST ou GET)
    $approvalResponse = isset($_POST['approvalResponse']) ? (int)$_POST['approvalResponse'] : (isset($_GET['approvalResponse']) ? (int)$_GET['approvalResponse'] : null);
    $requestType = isset($_POST['requestType']) ? $_POST['requestType'] : (isset($_GET['requestType']) ? $_GET['requestType'] : null);
    $requestId = isset($_POST['requestId']) ? (int)$_POST['requestId'] : (isset($_GET['requestId']) ? (int)$_GET['requestId'] : null);
    
    if ($approvalResponse === null || !$requestType || !$requestId) {
        echo json_encode([
            'success' => false,
            'error' => 'Paramètres manquants: approvalResponse, requestType, ou requestId'
        ]);
        exit;
    }
    
    $result = sendApprovalResponse($approvalResponse, $requestType, $requestId);
    echo json_encode($result);
}