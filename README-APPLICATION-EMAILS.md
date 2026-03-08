# 📧 Système de Notifications Email pour les Candidatures

## Vue d'ensemble

Ce système gère l'envoi automatique d'emails de notification entre candidats et entreprises lors des différentes étapes du processus de candidature.

## Fichiers principaux

### `sendApplicationNotification.php`
Fonction centrale qui gère tous les types d'emails de candidature.

**Paramètres:**
- `$candidateId` : ID du candidat
- `$jobId` : ID de l'offre d'emploi
- `$isCompanyResponse` : `true` si c'est une réponse de l'entreprise, `false` si c'est le candidat qui agit
- `$companyValidation` : `true` si l'entreprise accepte la candidature, `false` si elle refuse
- `$candidateValidation` : `true` si le candidat valide (postule ou maintient sa candidature)
- `$isredflag` : `true` si c'est un signalement
- `$comment` : Commentaire optionnel

## Scénarios d'envoi d'emails

### 1. Candidat postule à une offre 📩
**Condition:** `isCompanyResponse = false`, `candidateValidation = true`, `isredflag = false`

**Emails envoyés:**
- **À l'entreprise:**
  - Sujet: "📩 Nouvelle candidature pour votre offre : [Titre]"
  - Contenu: 
    - Nom du candidat
    - Intitulé du poste du candidat
    - Ancienneté (Apprenti/Junior/Confirmé/Senior)
    - Date de disponibilité
    - Bouton vers l'espace entreprise pour consulter le CV

- **Au candidat:**
  - Sujet: "✅ Candidature envoyée : [Titre] chez [Entreprise]"
  - Contenu: Confirmation que l'entreprise a bien reçu sa candidature

**Intégration:**
```php
// Dans createApplication.php
sendApplicationNotification(
    $candidateId, 
    $jobId, 
    false,  // isCompanyResponse
    false,  // companyValidation (pas encore répondu)
    true,   // candidateValidation (candidat postule)
    false,  // isredflag
    ''      // comment
);
```

---

### 2. Entreprise accepte la candidature 🎉
**Condition:** `isCompanyResponse = true`, `companyValidation = true`, `isredflag = false`

**Email envoyé:**
- **Au candidat:**
  - Sujet: "🎉 Félicitations ! Votre candidature a été retenue par [Entreprise]"
  - Contenu:
    - Message de félicitations
    - L'équipe 1-TED est ravie de voir qu'il a trouvé la mission qui l'intéresse
    - Prochaines étapes: l'entreprise le contactera
    - Bouton vers son espace candidat

**Intégration:**
```php
// Dans updateApplicationStatus.php (status = 'validate')
sendApplicationNotification(
    $candidateId,
    $jobId,
    true,   // isCompanyResponse (entreprise répond)
    true,   // companyValidation (accepté)
    true,   // candidateValidation
    false,  // isredflag
    $comment ?? ''
);
```

---

### 3. Entreprise refuse la candidature ❌
**Condition:** `isCompanyResponse = true`, `companyValidation = false`, `isredflag = false`

**Email envoyé:**
- **Au candidat:**
  - Sujet: "Réponse à votre candidature : [Titre] chez [Entreprise]"
  - Contenu:
    - Notification du refus (formulé poliment)
    - Message de l'entreprise ($comment) si fourni
    - Encouragement à ne pas se décourager
    - Rappel que de nouvelles offres sont publiées régulièrement

**Intégration:**
```php
// Dans updateApplicationStatus.php (status = 'rejected')
sendApplicationNotification(
    $candidateId,
    $jobId,
    true,   // isCompanyResponse (entreprise répond)
    false,  // companyValidation (refusé)
    true,   // candidateValidation
    false,  // isredflag
    $comment ?? ''
);
```

---

### 4. Candidat retire sa candidature 🔙
**Condition:** `isCompanyResponse = false`, `candidateValidation = false`, `isredflag = false`

**Email envoyé:**
- **À l'entreprise:**
  - Sujet: "ℹ️ Retrait de candidature : [Titre]"
  - Contenu:
    - Notification que le candidat a retiré sa candidature
    - Message du candidat ($comment) si fourni
    - Note que la candidature a été automatiquement annulée

**Intégration:**
```php
// À créer dans un endpoint withdrawApplication.php
sendApplicationNotification(
    $candidateId,
    $jobId,
    false,  // isCompanyResponse (candidat agit)
    false,  // companyValidation
    false,  // candidateValidation (retire)
    false,  // isredflag
    $comment
);
```

---

### 5. Signalement (Red Flag) ⚠️
**Condition:** `isredflag = true`

**Email envoyé:**
- **À l'entreprise:**
  - Sujet: "⚠️ Signalement reçu concernant votre entreprise"
  - Contenu:
    - Notification qu'un candidat a signalé l'entreprise pour des raisons sérieuses
    - L'équipe 1-TED mène l'enquête
    - Reviendra vers l'entreprise pour en savoir plus

**Intégration:**
```php
// Dans sendRedFlagMail.php
sendApplicationNotification(
    $candidateId,
    $jobId,
    false, // isCompanyResponse
    false, // companyValidation
    false, // candidateValidation (retire)
    true,  // isredflag
    $reason
);
```

---

## Templates d'emails

Tous les emails utilisent un design HTML responsive avec:
- En-tête avec dégradé de couleur selon le type de message
- Mise en page moderne et professionnelle
- Compatibilité multi-appareils
- Boutons d'action clairs

### Couleurs selon le type:
- 🔵 **Nouvelle candidature:** Bleu (`#1e93e5`)
- 🟢 **Confirmation:** Vert (`#10b981`)
- 🟡 **Acceptation:** Orange/Or (`#f59e0b`)
- ⚫ **Refus:** Gris (`#6b7280`)
- 🔵 **Retrait:** Indigo (`#6366f1`)
- 🔴 **Signalement:** Rouge (`#ef4444`)

---

## Mapping des données

### Ancienneté (Seniority)
```php
0 => 'Apprenti'
1 => 'Junior'
2 => 'Confirmé'
3 => 'Senior'
```

### Domaine & Langue
- **Local:** `MODE_LOCAL = true` → `http://1-ted.local`
- **Production:** `MODE_LOCAL = false` → `https://fra-beta.1-ted.com`
- **Langue:** Détectée via `$_SESSION['lang']` ou `$_COOKIE['lang']`, défaut: `fr`

---

## Base de données

### Table `application`
- `candidate_id` : ID du candidat
- `job_id` : ID de l'offre
- `status` : pending, in-process, question, rejected, validate, cancel
- `recruiter_validation` : 0 (refusé), 1 (accepté), NULL (pas encore répondu)
- `candidate_validation` : 0 (retiré), 1 (validé)
- `comment` : Commentaire de l'entreprise ou du candidat

---

## Adresses email utilisées

- **Expéditeur candidatures:** `applications@1-ted.com` (Nom: "1-TED Candidatures")
- **Expéditeur support:** `support@1-ted.com` (Nom: "1-TED Support")

---

## Tests

Pour tester les emails, vous pouvez:
1. Créer une candidature via l'interface candidat
2. Accepter/Refuser une candidature via l'espace entreprise
3. Signaler une entreprise via le système Red Flag

Les emails de test seront envoyés aux adresses configurées dans la base de données.

---

## Logs

Les erreurs d'envoi d'email sont enregistrées dans le log PHP:
```php
error_log('Erreur envoi email pour application #' . $applicationId . ': ' . $error);
```

---

## Retours JSON

### createApplication.php
```json
{
  "success": true,
  "message": "Candidature envoyée avec succès",
  "application_id": 123,
  "emails_sent": true
}
```

### updateApplicationStatus.php
```json
{
  "success": true,
  "message": "Statut mis à jour avec succès",
  "emails_sent": true
}
```

---

## Prochaines améliorations possibles

1. **Traductions multilingues** des emails (actuellement en français uniquement)
2. **Endpoint withdrawApplication.php** pour permettre aux candidats de retirer leur candidature
3. **Personnalisation des templates** par entreprise
4. **Historique des emails** envoyés dans la base de données
5. **Templates pour autres langues** (EN, ES, DE)

---

## Support

Pour toute question ou problème concernant le système d'emails de candidature, contactez l'équipe technique 1-TED.
