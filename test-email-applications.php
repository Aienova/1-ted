<?php
/**
 * Test d'envoi d'email depuis applications@1-ted.com
 */

require_once __DIR__ . '/function/mailSystem.php';
require_once __DIR__ . '/function/mailConfig.php';

echo "<h2>🧪 Test d'envoi d'email depuis applications@1-ted.com</h2>";

$to = 'aienovanothing2something@gmail.com';
$subject = 'Test email applications@1-ted.com';
$message = '<p>Ceci est un test d\'envoi depuis applications@1-ted.com</p>';

echo "<p><strong>Destinataire:</strong> $to</p>";
echo "<p><strong>Sujet:</strong> $subject</p>";

try {
    $result = sendEmail($to, $subject, $message, 'applications@1-ted.com', '1-TED Candidatures');
    
    if ($result) {
        echo "<p style='color: green;'>✅ <strong>Email envoyé avec succès !</strong></p>";
    } else {
        echo "<p style='color: red;'>❌ <strong>Échec de l'envoi d'email</strong></p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erreur:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<h3>Configuration SMTP pour applications@1-ted.com:</h3>";
$config = getResolvedMailConfig('applications@1-ted.com', '1-TED Candidatures');
echo "<pre>";
echo "Host: " . $config['host'] . "\n";
echo "Port: " . $config['port'] . "\n";
echo "Encryption: " . $config['encryption'] . "\n";
echo "Auth: " . ($config['auth'] ? 'true' : 'false') . "\n";
echo "Username: " . $config['username'] . "\n";
echo "Password: " . (empty($config['password']) ? '(vide)' : '***masqué***') . "\n";
echo "From Email: " . $config['from_email'] . "\n";
echo "From Name: " . $config['from_name'] . "\n";
echo "</pre>";
