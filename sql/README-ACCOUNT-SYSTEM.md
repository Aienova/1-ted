# Système de Comptes - Documentation

## Vue d'ensemble

Le système de comptes gère trois types d'utilisateurs :
- **Administrateur** (`role = 'administrator'`)
- **Candidats** (`role = 'candidate'`)  
- **Recruteurs/Entreprises** (`role = 'recruiter'`)

## Structure des Tables

### Table `account`
Stocke les informations de compte pour tous les utilisateurs.

**Champs principaux :**
- `id` : Identifiant unique
- `login` : Identifiant de connexion (UNIQUE)
- `email` : Adresse email (UNIQUE)
- `password_hash` : Mot de passe haché
- `role` : Type de compte (administrator/candidate/recruiter)
- `candidate_id` : ID du candidat associé (NULL pour admin et recruteurs)
- `company_id` : ID de l'entreprise associée (NULL pour admin et candidats)
- `firstname`, `lastname` : Nom et prénom
- `telephone` : Numéro de téléphone
- `company_name`, `company_siret`, `activity_sector` : Infos entreprise (pour recruteurs)
- `token` : Token de session
- `created_at` : Date de création
- `last_connection` : Dernière connexion

### Table `preference`
Stocke les préférences de recherche des utilisateurs.

**Champs :**
- `id` : Identifiant unique
- `account_id` : ID du compte associé
- `keywords` : Mots-clés de recherche
- `maximum_age` : Âge maximum recherché
- `minimum_experience` : Expérience minimale en années
- `minimum_salary`, `maximum_salary` : Fourchette salariale
- `locations` : Localisations préférées
- `suggestion_activated` : Active/désactive les suggestions automatiques

### Table `application`
Gère les candidatures des candidats aux offres d'emploi.

**Champs :**
- `id` : Identifiant unique
- `candidate_id` : ID du candidat
- `job_id` : ID de l'offre d'emploi
- `status` : Statut de la candidature (pending, accepted, rejected, etc.)
- `recruiter_validation` : Validation par le recruteur (boolean)
- `candidate_validation` : Validation par le candidat (boolean)
- `comment` : Commentaires

### Table `suggestion`
Stocke les suggestions de profils ou d'offres.

**Champs :**
- `id` : Identifiant unique
- `job_id` : ID de l'offre suggérée
- `candidate_id` : ID du candidat concerné
- `company_id` : ID de l'entreprise concernée

## Installation

### 1. Créer les tables

Exécutez le fichier SQL :
```bash
mysql -u root -p 1ted < sql/account_system.sql
```

Ou via phpMyAdmin : importez le fichier `account_system.sql`

### 2. Données de test

Le fichier SQL inclut :
- **1 compte administrateur** (ID=1)
  - Login: `admin`
  - Email: `admin@1ted.com`
  - Mot de passe: `password` (haché)

- **32 comptes candidats** (IDs 2-33)
  - Liés aux candidats existants (IDs 1, 3, 8-38)
  - Emails : `prenom.nom@email.com`
  - Logins : `prenom.nom`
  - Mot de passe : `password` (haché)

- **7 comptes recruteurs** (IDs 39-45)
  - Liés aux entreprises existantes (IDs 1-7)
  - Emails : `contact@entreprise.com`
  - Logins : basés sur le nom de l'entreprise
  - Mot de passe : `password` (haché)

- **5 préférences de recherche** (exemples pour certains comptes)

### 3. Mot de passe par défaut

⚠️ **IMPORTANT** : Le mot de passe par défaut est `password` pour TOUS les comptes de test.

Hash utilisé : `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`

**En production**, les utilisateurs DOIVENT définir leurs propres mots de passe sécurisés !

## Utilisation avec getData.php

Les nouvelles tables sont maintenant intégrées dans `getData.php`.

### Récupérer des comptes
```php
// Tous les comptes
$accounts = getData('account');

// Un compte par ID
$account = getData('account', 1);

// Compte d'un candidat spécifique
$accountsCandidate = getDataWhere('account', ['candidate_id' => 15]);

// Compte d'une entreprise spécifique
$accountsRecruiter = getDataWhere('account', ['company_id' => 1]);

// Tous les administrateurs
$admins = getDataWhere('account', ['role' => 'administrator']);
```

### Récupérer des préférences
```php
// Toutes les préférences
$preferences = getData('preference');

// Préférences d'un compte
$userPreferences = getDataWhere('preference', ['account_id' => 2]);
```

### Récupérer des candidatures
```php
// Toutes les candidatures
$applications = getData('application');

// Candidatures d'un candidat
$candidateApplications = getDataWhere('application', ['candidate_id' => 1]);

// Candidatures pour une offre
$jobApplications = getDataWhere('application', ['job_id' => 5]);

// Candidatures validées par le recruteur
$validatedApplications = getDataWhere('application', ['recruiter_validation' => 1]);
```

### Récupérer des suggestions
```php
// Toutes les suggestions
$suggestions = getData('suggestion');

// Suggestions pour un candidat
$candidateSuggestions = getDataWhere('suggestion', ['candidate_id' => 1]);

// Suggestions pour une entreprise
$companySuggestions = getDataWhere('suggestion', ['company_id' => 1]);
```

## API REST

Les tables sont accessibles via l'API HTTP GET :

```bash
# Récupérer tous les comptes
GET /function/getData.php?table=account

# Récupérer toutes les préférences
GET /function/getData.php?table=preference

# Récupérer toutes les candidatures
GET /function/getData.php?table=application

# Récupérer toutes les suggestions
GET /function/getData.php?table=suggestion
```

## Relations entre les tables

```
account (1) -----> (0..1) preference
   |
   |---- candidate_id ----> candidate
   |
   |---- company_id ------> company

application
   |---- candidate_id ----> candidate
   |---- job_id ----------> job

suggestion
   |---- candidate_id ----> candidate
   |---- job_id ----------> job
   |---- company_id ------> company
```

## Sécurité

### Contraintes UNIQUE
- `login` : Garantit l'unicité des identifiants
- `email` : Garantit l'unicité des emails

### Validation des rôles
Les rôles sont contrôlés par un ENUM :
- `administrator`
- `candidate`
- `recruiter`

### Hachage des mots de passe
Utilisez `password_hash()` et `password_verify()` de PHP :

```php
// Créer un hash
$hash = password_hash($password, PASSWORD_DEFAULT);

// Vérifier un mot de passe
if (password_verify($inputPassword, $storedHash)) {
    // Mot de passe correct
}
```

## Prochaines étapes

1. **Implémenter l'authentification**
   - Page de connexion
   - Gestion de session
   - Protection des pages

2. **Gérer les préférences utilisateur**
   - Interface de configuration
   - Sauvegarde automatique
   - Suggestions basées sur les préférences

3. **Système de candidatures**
   - Postuler à une offre
   - Suivi des candidatures
   - Notifications

4. **Système de suggestions**
   - Algorithme de matching
   - Notifications de suggestions
   - Accepter/refuser une suggestion

## Notes techniques

- Les mots de passe sont hachés avec `password_hash()` (bcrypt)
- Les timestamps sont gérés automatiquement par MySQL
- Les clés étrangères utilisent `ON DELETE CASCADE` pour la table `preference`
- Tous les champs sont en UTF-8 (charset: utf8mb4)
