<?php
/**
 * Script pour générer le fichier .htaccess selon la configuration local.env
 * Exécutez ce script après avoir modifié local.env pour mettre à jour .htaccess
 * 
 * Usage: php generate-htaccess.php
 */

// Charger les variables d'environnement
$envFile = __DIR__ . '/local.env';
if (!file_exists($envFile)) {
    die("❌ Erreur: Le fichier local.env n'existe pas.\n");
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Déterminer le mode et le basePath
$isLocal = isset($_ENV['MODE_LOCAL']) && $_ENV['MODE_LOCAL'] === 'true';

if ($isLocal) {
    $baseUrl = isset($_ENV['BASE_URL']) ? $_ENV['BASE_URL'] : '';
} else {
    $baseUrl = isset($_ENV['PROD_BASE_URL']) ? $_ENV['PROD_BASE_URL'] : '';
}

// Extraire le basePath depuis l'URL
$parsedUrl = parse_url($baseUrl);
$basePath = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

// S'assurer que le basePath se termine par /
if (!empty($basePath) && substr($basePath, -1) !== '/') {
    $basePath .= '/';
}

// Si basePath est vide, utiliser /
if (empty($basePath)) {
    $basePath = '/';
}

echo "Mode: " . ($isLocal ? "LOCAL" : "PRODUCTION") . "\n";
echo "Base URL: " . $baseUrl . "\n";
echo "Base Path: " . $basePath . "\n\n";

// Contenu du fichier .htaccess
$htaccessContent = <<<HTACCESS
# Enable URL Rewriting
RewriteEngine On

# Set base directory
# Auto-généré depuis local.env - Mode: {$_ENV['MODE_LOCAL']}
RewriteBase {$basePath}

# Redirection de la racine vers la langue par défaut (fr)
RewriteRule ^$ fr [R=301,L]

# Routes multi-langues
# Route pour la page d'accueil: /fr, /en, etc. -> /[lang]/index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([a-z]{2})$ [lang]/index.php?lang=\$1 [L,QSA]

# Route pour les pages: /fr/contact, /en/about, etc. -> /[lang]/[page]/index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([a-z]{2})/([^/]+)$ [lang]/[page]/index.php?lang=\$1&page=\$2 [L,QSA]

# Routes pour le système de comptes utilisateurs
# Route pour vérification d'email avec verificationCode: /fr/user-account/[code]/email-verification
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([a-z]{2})/user-account/([a-zA-Z0-9]{12})/email-verification$ [lang]/user-account/[verificationCode]/email-verification.php?lang=\$1&code=\$2 [L,QSA]

# Route pour nouveau mot de passe avec forgetCode: /fr/user-account/[code]/new-password
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([a-z]{2})/user-account/([a-zA-Z0-9]{12})/new-password$ [lang]/user-account/new-password.php?lang=\$1&code=\$2 [L,QSA]

# Route pour les pages des espaces utilisateurs par rôle: /fr/user-account/administrator/dashboard
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([a-z]{2})/user-account/(administrator|candidate|recruiter|company)/([^/]+)$ [lang]/user-account/[role]/[page]/index.php?lang=\$1&role=\$2&page=\$3 [L,QSA]

# Route pour les pages user-account: /fr/user-account/connection, etc.
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([a-z]{2})/user-account/([^/]+)$ [lang]/user-account/\$2.php?lang=\$1 [L,QSA]

# Rewrite rule for candidate URLs
# Route pour ajouter un nouveau candidat: /data/candidates/add
RewriteRule ^data/candidates/add$ data/candidates/id/index.php?id=add [L,QSA]

# Route pour ajouter une nouvelle offre d'emploi: /data/jobs/add
RewriteRule ^data/jobs/add$ data/jobs/id/index.php?id=add [L,QSA]

# Example: /data/candidates/2 -> /data/candidates/id/index.php?id=2
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^data/candidates/([0-9]+)$ data/candidates/id/index.php?id=\$1 [L,QSA]
RewriteRule ^data/jobs/([0-9]+)$ data/jobs/id/index.php?id=\$1 [L,QSA]

# Optional: Remove trailing slashes
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)/$ /\$1 [R=301,L]

HTACCESS;

// Écrire le fichier .htaccess
$htaccessFile = __DIR__ . '/.htaccess';

// Sauvegarder l'ancien .htaccess s'il existe
if (file_exists($htaccessFile)) {
    $backupFile = __DIR__ . '/.htaccess.backup.' . date('Y-m-d_H-i-s');
    copy($htaccessFile, $backupFile);
    echo "✅ Ancien .htaccess sauvegardé dans: " . basename($backupFile) . "\n";
}

// Écrire le nouveau .htaccess
file_put_contents($htaccessFile, $htaccessContent);

echo "✅ Fichier .htaccess généré avec succès!\n";
echo "📝 RewriteBase configuré à: {$basePath}\n";
