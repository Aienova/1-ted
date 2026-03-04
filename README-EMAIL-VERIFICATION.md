# Système de Vérification d'Email - 1-TED

## 📋 Vue d'ensemble

Le système de vérification d'email permet de confirmer l'adresse email des utilisateurs lors de leur inscription. Un code de vérification est envoyé par email et expire après 10 minutes.

## 🗄️ Structure de la Base de Données

### Nouvelles colonnes dans la table `account`

```sql
verification_code VARCHAR(12) DEFAULT NULL
verification_expire_date DATETIME DEFAULT NULL
verified TINYINT(1) NOT NULL DEFAULT 0
```

Pour ajouter ces colonnes à votre base de données existante, exécutez le script :
```bash
sql/add_verification_columns.sql
```

## 🔧 Fichiers Créés

### 1. Page de Vérification
- **Fichier** : `[lang]/user-account/[verificationCode]/email-verification.php`
- **Route** : `/fr/user-account/{CODE}/email-verification`
- **Description** : Page de vérification qui valide le code et affiche le statut

### 2. Fonctions Backend

#### getVerificationCode.php
- **Path** : `function/getVerificationCode.php`
- **Méthode** : GET
- **Paramètres** : `?code=XXXXXXXXXXXX`
- **Retour** :
  ```json
  {
    "success": true,
    "verified": true,
    "message": "Email vérifié avec succès"
  }
  ```

#### generateVerificationCode.php
- **Path** : `function/generateVerificationCode.php`
- **Description** : Fonctions utilitaires pour générer des codes uniques
- **Fonctions** :
  - `generateVerificationCode()` : Génère un code de 12 caractères
  - `generateUniqueVerificationCode($pdo)` : Génère un code unique
  - `createVerificationCode($pdo, $accountId)` : Crée et enregistre un code
  - `createVerificationCodeByEmail($pdo, $email)` : Crée un code par email

#### resendVerificationCode.php
- **Path** : `function/resendVerificationCode.php`
- **Méthode** : POST
- **Body** :
  ```json
  {
    "email": "user@example.com",
    "lang": "fr"
  }
  ```
- **Retour** :
  ```json
  {
    "success": true,
    "message": "Un nouveau code de vérification a été envoyé"
  }
  ```

## 📝 Routes .htaccess

```apache
# Route pour vérification d'email
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([a-z]{2})/user-account/([a-zA-Z0-9]{12})/email-verification$ [lang]/user-account/[verificationCode]/email-verification.php?lang=$1&code=$2 [L,QSA]
```

## 🚀 Utilisation

### 1. Lors de l'inscription

Après la création d'un compte, appelez :

```php
require_once __DIR__ . '/function/generateVerificationCode.php';
require_once __DIR__ . '/function/resendVerificationCode.php';

// Créer le code de vérification
$verificationData = createVerificationCode($pdo, $accountId);

// Envoyer l'email (utiliser resendVerificationCode ou mailSystem)
// L'utilisateur recevra un lien : /fr/user-account/{CODE}/email-verification
```

### 2. Validation du code

L'utilisateur clique sur le lien dans son email :
- **Lien** : `https://votre-domaine.com/fr/user-account/AbC123XyZ789/email-verification`
- La page charge et appelle automatiquement `getVerificationCode.php`
- Si valide : `verified = 1` dans la BDD et redirection vers la connexion
- Si expiré : formulaire pour renvoyer un nouveau code

### 3. Renvoyer un code

Si le code a expiré, l'utilisateur peut :
1. Entrer son email dans le formulaire
2. Un nouveau code est généré (validité : 10 minutes)
3. Un nouvel email est envoyé

## ⏰ Durée de Validité

Le code de vérification expire **10 minutes** après sa création :
```php
$expireDate = (new DateTime())->modify('+10 minutes');
```

## 🎨 Design

La page utilise le même CSS que le système de connexion :
- **Fichier CSS** : `style/account-system.css`
- **Design** : Cohérent avec les pages de connexion/inscription

## 🌐 Multilingue

Le système supporte les langues suivantes :
- Français (fr)
- Anglais (en)
- Espagnol (es)
- Allemand (de)

Les messages sont traduits dans la page `email-verification.php` et dans les emails envoyés.

## 📧 Email de Vérification

L'email contient :
- Un bouton cliquable pour vérifier
- Le lien complet (si le bouton ne fonctionne pas)
- Information sur l'expiration (10 minutes)
- Design HTML responsive

## 🔒 Sécurité

- ✅ Codes de 12 caractères alphanumériques (comme `media_code`)
- ✅ Vérification de l'unicité du code dans la BDD
- ✅ Expiration automatique après 10 minutes
- ✅ Code supprimé après vérification réussie
- ✅ Validation du format du code (regex)

## 🧪 Tests

### Tester la génération de code
```php
require_once 'function/generateVerificationCode.php';
$code = generateVerificationCode();
echo "Code généré : " . $code; // Ex: 1Ab2Cd3Ef4Gh
```

### Tester la vérification
1. Créer un compte test
2. Vérifier dans la BDD que `verification_code` et `verification_expire_date` sont remplis
3. Accéder à : `/fr/user-account/{CODE}/email-verification`
4. Vérifier que `verified = 1` après validation

### Tester l'expiration
1. Créer un code
2. Modifier manuellement `verification_expire_date` à une date passée
3. Accéder au lien de vérification
4. Vérifier que le message "Code expiré" s'affiche

## 📊 États Possibles

| État | Description | Action |
|------|-------------|--------|
| ✅ Vérifié | Code valide et non expiré | Compte marqué comme vérifié, redirection |
| ⏰ Expiré | Code dépassé les 10 minutes | Formulaire pour renvoyer un nouveau code |
| ❌ Invalide | Code inconnu ou déjà utilisé | Message d'erreur |

## 🔄 Workflow Complet

```
1. Utilisateur s'inscrit
   ↓
2. Compte créé avec verified = 0
   ↓
3. Code de vérification généré (12 chars)
   ↓
4. verification_expire_date = now + 10 min
   ↓
5. Email envoyé avec lien de vérification
   ↓
6. Utilisateur clique sur le lien
   ↓
7a. Code valide → verified = 1, code supprimé, redirection
7b. Code expiré → Formulaire pour renvoyer un code
7c. Code invalide → Message d'erreur
```

## 📝 Notes Importantes

- ⚠️ Les comptes non vérifiés (`verified = 0`) peuvent avoir des restrictions
- 💡 Vous pouvez ajouter une vérification dans `connectionToAccount.php` pour bloquer les connexions si `verified = 0`
- 🔄 Le système peut être étendu pour renvoyer automatiquement un code après X tentatives

## 🛠️ Maintenance

### Nettoyer les codes expirés
```sql
UPDATE account 
SET verification_code = NULL, 
    verification_expire_date = NULL 
WHERE verification_expire_date < NOW() 
  AND verified = 0;
```

### Statistiques
```sql
-- Comptes vérifiés
SELECT COUNT(*) FROM account WHERE verified = 1;

-- Comptes en attente de vérification
SELECT COUNT(*) FROM account WHERE verified = 0;

-- Codes expirés non utilisés
SELECT COUNT(*) FROM account 
WHERE verification_expire_date < NOW() 
  AND verified = 0;
```
