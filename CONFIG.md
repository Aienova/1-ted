# Configuration du projet 1-TED

## 🔧 Système de configuration simplifié

Le projet utilise un système de configuration unique basé sur le fichier `local.env` avec une variable `MODE_LOCAL` qui détermine automatiquement tous les paramètres.

## 📝 Fichier de configuration : `local.env`

```env
# Mode de développement : true pour local, false pour production
MODE_LOCAL=true

# Configuration de la base de données locale
BASE_URL=
DB_HOST=localhost
DB_NAME=1ted
DB_USER=root
DB_PASSWORD=
DB_CHARSET=utf8mb4

# Configuration production (utilisé seulement si MODE_LOCAL=false)
PROD_BASE_URL=https://1-ted.com
PROD_DB_HOST=your_production_host
PROD_DB_NAME=ted_backoffice_prod
PROD_DB_USER=your_prod_user
PROD_DB_PASSWORD=your_prod_password
```

## 🖥️ Mode LOCAL (Développement)

**Quand :** `MODE_LOCAL=true`

**Configuration automatique :**
- Base URL : Valeur de `BASE_URL` depuis le fichier .env
- Base Path : Extrait automatiquement depuis `BASE_URL`
- Base de données : Utilise `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`

**Utilisation :**
Vous développez en local sur XAMPP/WAMP dans le dossier `htdocs/1-ted-backoffice`

## 🌐 Mode PRODUCTION

**Quand :** `MODE_LOCAL=false`

**Configuration automatique :**
- Base URL : Valeur de `PROD_BASE_URL` depuis le fichier .env
- Base Path : Extrait automatiquement depuis `PROD_BASE_URL`
- Base de données : Utilise `PROD_DB_HOST`, `PROD_DB_NAME`, `PROD_DB_USER`, `PROD_DB_PASSWORD`

**Utilisation :**
Déployé sur un serveur distant

## 🚀 Déploiement en production

### Étape 1 : Modifier `local.env`

```env
MODE_LOCAL=false

PROD_BASE_URL=https://votre-domaine.com
PROD_DB_HOST=serveur-sql.votre-hebergeur.com
PROD_DB_NAME=votre_base
PROD_DB_USER=votre_user
PROD_DB_PASSWORD=votre_password_securise
```

### Étape 2 : Générer le fichier .htaccess

Le fichier `.htaccess` doit être mis à jour pour correspondre à votre configuration :

```bash
php generate-htaccess.php
```

Ce script va :
- Lire votre fichier `local.env`
- Générer automatiquement le bon `RewriteBase` selon votre `BASE_URL` ou `PROD_BASE_URL`
- Créer une sauvegarde de l'ancien `.htaccess`

### Étape 3 : Upload sur le serveur

- Uploadez tous les fichiers à la racine de votre site web (ou dans le sous-dossier approprié)
- Le fichier `local.env` sera automatiquement lu
- Tous les chemins s'ajusteront automatiquement

### Étape 4 : C'est tout ! 🎉

Le système détecte automatiquement le mode et configure :
- Les URLs des assets (CSS, JS, images)
- Les chemins des fichiers PHP
- La connexion à la base de données
- Les redirections du moteur de recherche

## 🔐 Sécurité

**Important :** 
- Ne commitez JAMAIS `local.env` avec vos vrais identifiants de production
- Ajoutez `local.env` à votre `.gitignore`
- Sur le serveur, assurez-vous que `local.env` n'est pas accessible publiquement

## 📂 Structure des chemins

Les chemins sont automatiquement configurés selon votre `BASE_URL` ou `PROD_BASE_URL` :

### Exemple en LOCAL avec BASE_URL= :
- Site : ``
- Assets : `/style/...`
- API : `/function/...`

### Exemple en PRODUCTION avec PROD_BASE_URL=https://votre-domaine.com :
- Site : `https://votre-domaine.com`
- Assets : `https://votre-domaine.com/style/...`
- API : `https://votre-domaine.com/function/...`

### Exemple en PRODUCTION dans un sous-dossier PROD_BASE_URL=https://votre-domaine.com/ted :
- Site : `https://votre-domaine.com/ted`
- Assets : `https://votre-domaine.com/ted/style/...`
- API : `https://votre-domaine.com/ted/function/...`

## 🛠️ Variables disponibles

Dans tout le code PHP après `include 'info.php'` :
- `$baseUrl` : URL complète de base
- `$basePath` : Chemin relatif de base
- `PROJECT_ROOT` : Chemin absolu du projet sur le serveur

Dans tout le JavaScript :
- `window.APP_CONFIG.baseUrl` : URL complète de base
- `window.APP_CONFIG.basePath` : Chemin relatif de base
- `window.APP_CONFIG.lang` : Langue courante

## ✅ Avantages de ce système

- ✅ Un seul fichier de configuration
- ✅ Changement de mode en une seule variable
- ✅ Pas de code en dur
- ✅ Facile à déployer
- ✅ Configuration centralisée
- ✅ Pas de risque d'oublier de changer un fichier
