<?php
/**
 * Endpoint AJAX - Envoi du formulaire de contact
 * Retourne du JSON : { "success": bool, "message": string }
 */
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/mailSystem.php';

// Démarrer la session pour le rate limiting
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Rate limiting : max 3 envois par tranche de 10 minutes par session
$rateLimitKey   = 'contact_submissions';
$rateLimitMax   = 6;
$rateLimitWindow = 1200; // 10 minutes en secondes

$now = time();
// Nettoyer les anciennes entrées hors fenêtre
if (isset($_SESSION[$rateLimitKey])) {
    $_SESSION[$rateLimitKey] = array_filter(
        $_SESSION[$rateLimitKey],
        fn($ts) => ($now - $ts) < $rateLimitWindow
    );
} else {
    $_SESSION[$rateLimitKey] = [];
}

if (count($_SESSION[$rateLimitKey]) >= $rateLimitMax) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Trop de demandes. Veuillez patienter quelques minutes avant de réessayer.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

/**
 * Sanitize un champ texte libre :
 * - retire tout HTML/PHP (strip_tags)
 * - supprime les caractères de contrôle invisibles (sauts de ligne, tabulations…)
 * - normalise les espaces multiples
 * - tronque à $maxLen caractères UTF-8
 */
function sanitizeText(string $value, int $maxLen = 255): string {
    $value = strip_tags($value);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value); // caractères de contrôle
    $value = preg_replace('/\s+/', ' ', $value);
    $value = trim($value);
    return mb_substr($value, 0, $maxLen, 'UTF-8');
}

/**
 * Sanitize un champ multiligne (message) :
 * - retire tout HTML/PHP
 * - supprime les caractères de contrôle invisibles mais conserve \n et \r
 * - tronque à $maxLen caractères UTF-8
 */
function sanitizeMultiline(string $value, int $maxLen = 5000): string {
    $value = strip_tags($value);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
    $value = trim($value);
    return mb_substr($value, 0, $maxLen, 'UTF-8');
}

// --- Sujets autorisés (whitelist) ---
$allowedSubjects = ['general', 'support', 'partnership', 'other'];

// --- Récupération et sanitisation des champs ---
$firstName = sanitizeText($_POST['firstName'] ?? '', 100);
$lastName  = sanitizeText($_POST['lastName']  ?? '', 100);
$phone     = sanitizeText($_POST['phone']     ?? '',  30);
$subject   = sanitizeText($_POST['subject']   ?? '', 50);
$message   = sanitizeMultiline($_POST['message'] ?? '', 5000);

// Email : sanitize puis valider le format + absence de sauts de ligne (injection header)
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
if (preg_match('/[\r\n]/', $email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide.']);
    exit;
}

// --- Validations ---
if (
    empty($firstName) || empty($lastName) ||
    empty($email)     || empty($subject)  ||
    empty($message)   || !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires avec des informations valides.']);
    exit;
}

if (!in_array($subject, $allowedSubjects, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Sujet invalide.']);
    exit;
}

$sent = sendContactEmail($firstName, $lastName, $email, $phone, $subject, $message);

if ($sent) {
    // Enregistrer la soumission dans le rate limiter
    $_SESSION[$rateLimitKey][] = $now;
    echo json_encode(['success' => true, 'message' => 'Votre message a bien été envoyé !']);
} else {
    echo json_encode(['success' => false, 'message' => "Une erreur est survenue lors de l'envoi. Veuillez réessayer."]);
}
