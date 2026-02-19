# Bouton de Compte Utilisateur

## Description

Cette fonctionnalité permet d'afficher un bouton de compte personnalisé dans le menu de navigation lorsqu'un utilisateur est connecté. Le bouton "Connexion" du menu disparaît automatiquement et est remplacé par le bouton de compte.

## Fonctionnalités

### Affichage Conditionnel
- **Utilisateur non connecté** : Le menu affiche le lien "Connexion" (id=7, isAccountButton=true)
- **Utilisateur connecté** : Le lien "Connexion" est masqué et remplacé par le bouton de compte

### Informations Affichées
Le bouton de compte affiche :
- **Photo de profil** : Avatar de l'utilisateur (par défaut: `/media/image/icon/avatar/default.png`)
- **Login et Rôle** : Nom d'utilisateur et rôle (Admin, Candidat, Recruteur)
- **Crédits** : Nombre de crédits disponibles (actuellement 0 par défaut)

### Rôles Supportés
- `administrator` → Admin
- `candidate` → Candidat / Candidate
- `recruiter` → Recruteur / Recruiter

## Fichiers Modifiés

### 1. `[lang]/info.php`
- Démarrage de la session PHP
- Vérification de l'état de connexion (`$_SESSION['token']` et `$_SESSION['role']`)
- Filtrage du menu pour masquer les éléments avec `isAccountButton: true`

### 2. `[lang]/accountButton.php`
- Création du composant bouton de compte
- Affichage de la photo, login, rôle et crédits
- Lien vers la page de profil utilisateur

### 3. `[lang]/header.php`
- Inclusion du bouton de compte après le sélecteur de langue
- Le bouton s'affiche uniquement si l'utilisateur est connecté

### 4. `style/header.css`
- Styles pour le bouton de compte
- Design adaptatif (responsive)
- États hover et scrolled

### 5. `data/text/[lang]/menu.json`
- Ajout de la propriété `isAccountButton` à tous les éléments du menu
- Configuration de l'élément "Connexion" avec `isAccountButton: true`

## Détection de Connexion

La connexion utilisateur est détectée via les variables de session :
```php
$isUserConnected = isset($_SESSION['token']) && isset($_SESSION['role']);
```

Variables de session utilisées :
- `$_SESSION['token']` : Token d'authentification
- `$_SESSION['role']` : Rôle de l'utilisateur (admin/candidate/recruiter)
- `$_SESSION['login']` : Login/nom d'utilisateur
- `$_SESSION['accountId']` : ID du compte
- `$_SESSION['credits']` : Nombre de crédits (optionnel, par défaut 0)

## Style et Design

### Thème Clair / Sombre
Le bouton s'adapte automatiquement :
- **Header transparent** : Texte blanc, fond semi-transparent
- **Header scrolled** : Texte sombre, fond clair

### Responsive
- **Desktop** : Affichage complet (photo + texte)
- **Tablet** : Taille réduite
- **Mobile** : Photo uniquement

## Lien Vers Le Profil

Le bouton redirige vers la page de profil en fonction du rôle :
```php
$profileUrl = '/' . $lang . '/user-account/' . $_SESSION['role'] . '/profile';
```

Exemples :
- Admin : `/fr/user-account/administrator/profile`
- Candidat : `/fr/user-account/candidate/profile`
- Recruteur : `/fr/user-account/recruiter/profile`

## Améliorations Futures

1. **Système de crédits** : Ajouter une colonne `credits` dans la table `account`
2. **Photos de profil** : Permettre l'upload et le stockage de photos personnalisées
3. **Menu déroulant** : Ajouter un menu déroulant avec options (Profil, Paramètres, Déconnexion)
4. **Notifications** : Afficher un badge de notifications non lues
5. **Multi-langue** : Support complet pour toutes les langues du site

## Test

Pour tester la fonctionnalité :
1. Accéder à la page de connexion
2. Se connecter avec un compte (admin/candidate/recruiter)
3. Vérifier que le bouton "Connexion" a disparu
4. Vérifier que le bouton de compte s'affiche avec les bonnes informations
5. Cliquer sur le bouton pour accéder au profil
6. Se déconnecter et vérifier que le bouton "Connexion" réapparaît
