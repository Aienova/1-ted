# Guide d'Intégration - Système de Vérification d'Email

## 📝 Comment intégrer le système dans votre flux d'inscription

### Étape 1 : Exécuter le script SQL

Avant toute chose, ajoutez les colonnes nécessaires à la table `account` :

```bash
# Via phpMyAdmin ou ligne de commande MySQL
mysql -u root -p 1ted < sql/add_verification_columns.sql
```

### Étape 2 : Modifier createAccount.php

Dans votre fichier `function/createAccount.php`, après la création du compte, ajoutez l'envoi de l'email de vérification :

```php
<?php
// Après la création du compte avec succès
require_once __DIR__ . '/sendVerificationEmail.php';

// Envoyer l'email de vérification
$lang = isset($_POST['lang']) ? $_POST['lang'] : 'fr';
$emailResult = sendVerificationEmail($pdo, $newAccountId, $lang);

if (!$emailResult['success']) {
    error_log("Erreur envoi email de vérification : " . $emailResult['message']);
    // Note: Le compte est créé même si l'email échoue
    // L'utilisateur pourra demander un renvoi du code
}
```

### Étape 3 : Exemple complet dans createAccount.php

```php
<?php
/**
 * Endpoint pour créer un nouveau compte utilisateur
 */

header('Content-Type: application/json');

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/sendVerificationEmail.php';

try {
    // Validation et création du compte (votre code existant)
    // ...
    
    // Insérer le compte dans la BDD
    $sql = "INSERT INTO account (firstname, lastname, email, password_hash, role, ...) 
            VALUES (:firstname, :lastname, :email, :password_hash, :role, ...)";
    
    $stmt = $pdo->prepare($sql);
    // Bind des paramètres...
    $stmt->execute();
    
    $newAccountId = $pdo->lastInsertId();
    
    // ✅ NOUVEAU : Envoyer l'email de vérification
    $lang = isset($_POST['lang']) ? $_POST['lang'] : 'fr';
    $verificationResult = sendVerificationEmail($pdo, $newAccountId, $lang);
    
    // Réponse de succès
    echo json_encode([
        'success' => true,
        'message' => 'Compte créé avec succès. Veuillez vérifier votre email.',
        'account_id' => $newAccountId,
        'email_sent' => $verificationResult['success']
    ]);
    
} catch (Exception $e) {
    error_log("Erreur création compte: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la création du compte'
    ]);
}
```

### Étape 4 : Mettre à jour le formulaire d'inscription

Dans votre script JavaScript d'inscription (`script/account/registration.js`), ajoutez un message après la création du compte :

```javascript
// Après réception de la réponse de création de compte
if (data.success) {
    successDiv.textContent = 'Compte créé ! Vérifiez votre email pour activer votre compte.';
    successDiv.style.display = 'block';
    
    // Optionnel: rediriger vers une page de confirmation
    setTimeout(() => {
        window.location.href = `${baseUrl}/${lang}/user-account/connection`;
    }, 3000);
}
```

### Étape 5 (Optionnel) : Bloquer la connexion des comptes non vérifiés

Dans `function/connectionToAccount.php`, ajoutez une vérification :

```php
<?php
// Après avoir trouvé et validé le compte
if ($account && password_verify($password, $account['password_hash'])) {
    
    // ✅ NOUVEAU : Vérifier si l'email est vérifié
    if ($account['verified'] != 1) {
        echo json_encode([
            'success' => false,
            'message' => 'Veuillez vérifier votre email avant de vous connecter.',
            'code' => 'EMAIL_NOT_VERIFIED'
        ]);
        exit;
    }
    
    // Connexion réussie (votre code existant)
    // ...
}
```

## 🧪 Tests

### 1. Test de création de compte

```bash
# POST vers createAccount.php
curl -X POST http://localhost/function/createAccount.php \
  -H "Content-Type: application/json" \
  -d '{
    "firstname": "Jean",
    "lastname": "Test",
    "email": "jean.test@example.com",
    "password": "MotDePasse123!",
    "lang": "fr"
  }'
```

### 2. Vérifier dans la BDD

```sql
SELECT id, email, verification_code, verification_expire_date, verified 
FROM account 
WHERE email = 'jean.test@example.com';
```

Vous devriez voir :
- `verification_code` : Code de 12 caractères (ex: `1Ab2Cd3Ef4Gh`)
- `verification_expire_date` : Date actuelle + 10 minutes
- `verified` : 0

### 3. Test de la page de vérification

Accédez à l'URL générée :
```
http://localhost/fr/user-account/{CODE}/email-verification
```

### 4. Test de l'expiration

```sql
-- Forcer l'expiration du code
UPDATE account 
SET verification_expire_date = DATE_SUB(NOW(), INTERVAL 1 HOUR) 
WHERE email = 'jean.test@example.com';
```

Puis accédez à nouveau à la page de vérification. Vous devriez voir le formulaire pour renvoyer un code.

### 5. Test du renvoi de code

```bash
# POST vers resendVerificationCode.php
curl -X POST http://localhost/function/resendVerificationCode.php \
  -H "Content-Type: application/json" \
  -d '{
    "email": "jean.test@example.com",
    "lang": "fr"
  }'
```

## 📊 Monitoring

### Statistiques des vérifications

```sql
-- Taux de vérification
SELECT 
    COUNT(*) as total_comptes,
    SUM(verified) as comptes_verifies,
    (SUM(verified) / COUNT(*)) * 100 as taux_verification
FROM account;

-- Codes expirés non utilisés
SELECT COUNT(*) as codes_expires
FROM account 
WHERE verification_expire_date < NOW() 
  AND verified = 0
  AND verification_code IS NOT NULL;
```

### Nettoyage périodique

Créez un cron job pour nettoyer les codes expirés :

```sql
-- À exécuter quotidiennement
UPDATE account 
SET verification_code = NULL, 
    verification_expire_date = NULL 
WHERE verification_expire_date < NOW() 
  AND verified = 0;
```

## 🎨 Personnalisation

### Changer la durée de validité

Dans `function/generateVerificationCode.php`, ligne ~63 :

```php
// Changer de 10 minutes à 30 minutes par exemple
$expireDate->modify('+30 minutes');
```

### Personnaliser l'email

Dans `function/sendVerificationEmail.php`, modifiez le tableau `$emailMessages` pour changer les textes.

### Ajouter une langue

Ajoutez votre langue dans les tableaux de messages dans :
- `[lang]/user-account/[verificationCode]/email-verification.php`
- `function/sendVerificationEmail.php`
- `function/resendVerificationCode.php`

## 🔒 Sécurité - Bonnes Pratiques

1. **Rate Limiting** : Limitez le nombre de demandes de renvoi de code par email (ex: max 3 par heure)

```php
// Dans resendVerificationCode.php
$sql = "SELECT COUNT(*) as resend_count 
        FROM audit_log 
        WHERE email = :email 
          AND action = 'resend_verification' 
          AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
// Si resend_count > 3, bloquer
```

2. **Logs** : Enregistrez toutes les tentatives de vérification

```php
// Créer une table audit_log pour tracer les actions
INSERT INTO audit_log (email, action, ip_address, created_at) 
VALUES (:email, 'email_verification', :ip, NOW());
```

3. **Email valide** : Validez que l'email existe réellement avant d'envoyer

```php
// Utiliser un service comme mailboxlayer.com ou abstract.com
```

## 🚨 Dépannage

### L'email n'est pas envoyé

1. Vérifiez la configuration SMTP dans `function/mailSystem.php`
2. Vérifiez les logs PHP : `error_log`
3. Testez avec un email personnel au lieu d'un temporaire

### Le code est toujours expiré

1. Vérifiez le fuseau horaire de MySQL : `SELECT NOW();`
2. Vérifiez le fuseau horaire de PHP : `echo date('Y-m-d H:i:s');`
3. Configurez dans `php.ini` : `date.timezone = "Europe/Paris"`

### Le lien ne fonctionne pas

1. Vérifiez le fichier `.htaccess`
2. Activez `mod_rewrite` dans Apache
3. Testez la route directement : `/[lang]/user-account/[verificationCode]/email-verification.php?lang=fr&code=XXX`

## 📚 Ressources

- [README-EMAIL-VERIFICATION.md](README-EMAIL-VERIFICATION.md) - Documentation complète
- [sql/add_verification_columns.sql](sql/add_verification_columns.sql) - Script SQL
- [function/sendVerificationEmail.php](function/sendVerificationEmail.php) - Fonction principale

## 💡 Améliorations Futures

1. **Notification SMS** : En plus de l'email, envoyer un SMS
2. **Double authentification** : Email + SMS obligatoire
3. **Vérification progressive** : Permettre la connexion mais limiter les actions
4. **Dashboard admin** : Vue des comptes non vérifiés
5. **Auto-suppression** : Supprimer les comptes non vérifiés après 7 jours
