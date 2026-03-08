# Système d'Envoi d'Emails d'Approbation/Refus

## Vue d'ensemble

Ce système envoie automatiquement des emails aux entreprises et candidats lorsque leurs offres d'emploi ou profils sont approuvés ou refusés par l'équipe d'administration.

## Fichiers créés/modifiés

### 1. **approvalEmailTemplates.php** (Nouveau)
Contient les templates HTML pour les emails :
- `getApprovalEmailTemplate()` : Template pour les emails d'approbation
- `getRejectionEmailTemplate()` : Template pour les emails de refus

Les templates sont responsive et incluent :
- Design moderne avec gradient
- Icônes visuelles (✓ pour approbation, ✗ pour refus)
- Boutons d'action pour consulter l'offre/profil
- Footer avec liens de contact

### 2. **sendApprovalResponse.php** (Modifié)
Fonction principale qui :
1. Récupère les informations de l'offre d'emploi ou du candidat
2. Obtient l'email du contact de l'entreprise (pour jobs) ou du candidat
3. Génère le template HTML approprié
4. Envoie l'email via le système SMTP configuré
5. Retourne le statut d'envoi

Fonctions principales :
- `sendApprovalResponse($approvalResponse, $requestType, $requestId)`
- `getJobInfoAndContactEmail($jobId)` : Récupère l'email du contact de l'entreprise
- `getCandidateInfoAndEmail($candidateId)` : Récupère l'email du candidat
- `getDomainAndLang()` : Récupère le domaine et la langue depuis la config

### 3. **approveCard.php** (Modifié)
Modifié pour appeler `sendApprovalResponse()` après la mise à jour en base de données.
Retourne maintenant dans la réponse JSON :
- `emailSent` : booléen indiquant si l'email a été envoyé
- `emailInfo` : détails sur l'envoi (email du destinataire, etc.)

### 4. **jobs.php** et **candidates.php** (Modifiés)
Modifiés pour afficher une notification enrichie indiquant :
- Si l'approbation/refus a réussi
- Si l'email a été envoyé avec succès
- L'adresse email du destinataire
- Un avertissement si l'email n'a pas pu être envoyé

## Fonctionnement

### Pour les Offres d'Emploi

1. **Approbation** : 
   - Email envoyé au contact de l'entreprise (table `contact` via `company.contact_id`)
   - Sujet : "✓ Votre offre d'emploi a été approuvé !"
   - Contenu : Félicitations + bouton vers `[domain]/[lang]/offers?id=[job_id]`

2. **Refus** :
   - Email envoyé au contact de l'entreprise
   - Sujet : "Notification concernant votre offre d'emploi"
   - Contenu : Message de regret + liens vers conditions d'utilisation et page contact

### Pour les Candidats

1. **Approbation** :
   - Email envoyé au candidat (table `identity` via `candidate.identity_id`)
   - Sujet : "✓ Votre profil candidat a été approuvé !"
   - Contenu : Félicitations + bouton vers `[domain]/[lang]/candidates?id=[candidate_id]`

2. **Refus** :
   - Email envoyé au candidat
   - Sujet : "Notification concernant votre profil candidat"
   - Contenu : Message de regret + liens vers conditions d'utilisation et page contact

## Structure des Emails

### Email d'Approbation
```
┌────────────────────────────────────┐
│ Header (fond vert gradient)      │
│   ✓ Félicitations !                │
└────────────────────────────────────┘
│ Bonjour [Nom],                     │
│                                     │
│ Votre [type] a été approuvé !      │
│                                     │
│ ┌─────────────────────────┐       │
│ │ 📋 Titre: [Titre]       │       │
│ └─────────────────────────┘       │
│                                     │
│     [🔍 Voir sur le site]          │
│                                     │
└────────────────────────────────────┘
│ Footer avec liens                  │
└────────────────────────────────────┘
```

### Email de Refus
```
┌────────────────────────────────────┐
│ Header (fond rouge gradient)      │
│   ✗ Notification importante        │
└────────────────────────────────────┘
│ Bonjour [Nom],                     │
│                                     │
│ Votre [type] a été rejeté          │
│                                     │
│ ┌─────────────────────────┐       │
│ │ 📋 Titre: [Titre]       │       │
│ └─────────────────────────┘       │
│                                     │
│ ⚠️ Raisons possibles :             │
│ • Non-respect des conditions       │
│ • Informations manquantes          │
│ • Standards de qualité             │
│                                     │
│ [📜 CGU]  [✉️ Contact]             │
│                                     │
└────────────────────────────────────┘
│ Footer avec liens                  │
└────────────────────────────────────┘
```

## Configuration Requise

### Variables d'Environnement (local.env)

Le système utilise automatiquement la configuration SMTP définie dans `local.env` :

```env
# Domaine
BASE_URL=http://1-ted.local          # En mode local
PROD_BASE_URL=https://1-ted.com      # En production

# SMTP
MAIL_TRANSPORT=smtp
MAIL_SMTP_HOST=smtp.lwspanel.com
MAIL_SMTP_PORT=587
MAIL_SMTP_ENCRYPTION=tls
MAIL_SMTP_AUTH=true

# Identifiants no-reply (utilisé pour l'envoi)
MAIL_NOREPLY_ADDRESS=no-reply@1-ted.com
MAIL_NOREPLY_NAME=1-TED
MAIL_NOREPLY_SMTP_USERNAME=no-reply@1-ted.com
MAIL_NOREPLY_SMTP_PASSWORD=***

# Contact (utilisé pour Reply-To)
MAIL_CONTACT_ADDRESS=contact@1-ted.com
MAIL_CONTACT_NAME=1-TED Contact
```

### Schéma de Base de Données

Le système nécessite les tables suivantes :

**Pour les Jobs :**
```sql
job (id, title, company_id, approved)
company (id, name, contact_id)
contact (id, first_name, last_name, email)
```

**Pour les Candidats :**
```sql
candidate (id, identity_id, approved)
identity (id, first_name, last_name, email, job_title)
```

## Utilisation Directe

La fonction peut être appelée directement :

```php
require_once 'function/sendApprovalResponse.php';

// Approuver une offre d'emploi
$result = sendApprovalResponse(1, 'job', 123);

// Refuser un candidat
$result = sendApprovalResponse(0, 'candidate', 456);

// $result contient:
// [
//   'approvalResponse' => 1/0,
//   'requestType' => 'job'/'candidate',
//   'requestId' => 123,
//   'emailSent' => true/false,
//   'recipientEmail' => 'email@example.com'
// ]
```

Ou via HTTP :
```
POST/GET /function/sendApprovalResponse.php
Params:
  - approvalResponse: 1 (approuver) ou 0 (refuser)
  - requestType: 'job' ou 'candidate'
  - requestId: ID de l'offre ou du candidat
```

## Gestion des Erreurs

Le système gère plusieurs cas d'erreur :

1. **Email de contact manquant** : Exception levée avec message explicite
2. **Échec d'envoi SMTP** : Logged dans error_log, retourne `emailSent: false`
3. **Paramètres invalides** : Exception levée avec validation stricte
4. **DB Connection error** : Exception PDO propagée

Les erreurs sont toujours loggées dans le error_log PHP et retournées dans la réponse JSON.

## Logs

Tous les événements sont loggés :
```php
// Succès
// Pas de log spécifique

// Échec
error_log("Échec de l'envoi de l'email d'approbation/refus pour job #123 à contact@example.com");
error_log("Erreur dans sendApprovalResponse: [message]");
```

## Tests

Pour tester le système :

1. **Via l'interface admin** :
   - Aller dans l'espace admin → Offres d'emploi ou Candidats
   - Approuver ou refuser une offre/candidat
   - Vérifier la notification affichée
   - Vérifier la réception de l'email

2. **Via HTTP direct** :
   ```bash
   curl -X POST http://1-ted.local/function/sendApprovalResponse.php \
     -d "approvalResponse=1&requestType=job&requestId=123"
   ```

3. **Via PHP** :
   ```php
   require_once 'function/sendApprovalResponse.php';
   $result = sendApprovalResponse(1, 'job', 123);
   var_dump($result);
   ```

## Personnalisation des Templates

Les templates peuvent être personnalisés en modifiant :
- `approvalEmailTemplates.php` : Structure HTML et styles
- Variables dynamiques disponibles :
  - `$type` : 'job' ou 'candidate'
  - `$id` : ID de l'élément
  - `$recipientName` : Nom du destinataire
  - `$itemTitle` : Titre de l'offre ou du profil
  - `$domain` : Domaine du site
  - `$lang` : Langue (fr, en, etc.)

## Support Multi-langue

Le système détecte automatiquement la langue :
1. Depuis `$_SESSION['lang']`
2. Depuis `$_COOKIE['lang']`
3. Par défaut : 'fr'

Les URLs générées utilisent cette langue : `[domain]/[lang]/offers?id=123`

## Notes Importantes

- Les emails sont envoyés **après** la mise à jour en base de données
- Si l'envoi échoue, l'approbation/refus reste enregistré
- Le système utilise `sendRawHtmlEmail()` car les templates sont déjà complets
- L'adresse no-reply est utilisée comme expéditeur
- L'adresse contact est utilisée pour Reply-To

## Évolutions Possibles

1. Ajouter des templates multi-langues
2. Personnaliser les raisons de refus
3. Ajouter des pièces jointes
4. Système de retry en cas d'échec
5. Queue d'envoi asynchrone pour de gros volumes
6. Templates dans la base de données pour édition admin
7. Statistiques d'ouverture des emails (tracking)

## Support

Pour toute question ou problème :
- Vérifier les logs PHP (error_log)
- Vérifier la configuration SMTP dans local.env
- Tester l'envoi d'email basique avec mailSystem.php
- Vérifier que les emails de contact sont bien renseignés en base
