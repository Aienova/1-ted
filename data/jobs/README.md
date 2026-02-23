# Système de gestion des offres d'emploi

## 📋 Vue d'ensemble

Système complet d'édition et de gestion des offres d'emploi, similaire à l'éditeur de CV. Permet de créer, modifier et afficher des offres d'emploi de manière interactive.

## 🗄️ Structure de la base de données

### Tables créées

1. **contact** - Informations de contact
   - id, first_name, last_name, email, phone

2. **company** - Informations sur les entreprises
   - id, name, customer_number, nation_register_number, description, domain, capacity, hq_location, country, recruiting, contact_id

3. **requirement** - Exigences pour les postes
   - id, description, degree, experience_years

4. **job** - Offres d'emploi principales
   - id, title, description, location, salary, contract_type, start_date, length, company_id, work_condition, need_vehicle, contact_id, requirement_id, open

### Installation de la base de données

```sql
-- Exécuter le fichier SQL
SOURCE sql/job.sql;
```

## 📁 Fichiers créés

### Backend (PHP)

- **sql/job.sql** - Schéma de base de données
- **data/jobs/job-offer.php** - Template d'affichage d'offre
- **data/jobs/id/index.php** - Page individuelle d'offre
- **data/jobs/index.html** - Liste de toutes les offres
- **function/getData.php** - Mise à jour avec les entités job

### Frontend (JavaScript)

Formulaires d'édition dans `script/forms/` :
- **job-title.js** - Titre et entreprise
- **job-basic-info.js** - Informations de base (localisation, salaire, contrat, etc.)
- **job-description.js** - Description du poste
- **job-conditions.js** - Conditions de travail
- **job-requirements.js** - Profil recherché
- **job-company-info.js** - À propos de l'entreprise
- **job-contact.js** - Informations de contact

### Styles (CSS)

- **style/joboffer.css** - Styles pour les offres d'emploi

## 🚀 Utilisation

### Afficher la liste des offres

Accéder à : `/data/jobs/`

### Afficher une offre spécifique

Accéder à : `/data/jobs/id/?id=1`

### Éditer une offre

1. Cliquer sur n'importe quelle zone éditable (marquée par l'icône ✏️ au survol)
2. Une popup s'ouvre avec le formulaire d'édition
3. Modifier les informations
4. Cliquer sur "Enregistrer"

### Zones éditables disponibles

- **En-tête** : Titre du poste et nom de l'entreprise
- **Informations de base** : Localisation, salaire, type de contrat, date de début, durée, véhicule
- **Description du poste** : Missions et responsabilités
- **Conditions de travail** : Horaires, télétravail, environnement
- **Profil recherché** : Niveau d'études, années d'expérience, compétences
- **À propos de l'entreprise** : Nom, secteur, effectif, siège social, description
- **Contact** : Prénom, nom, email, téléphone

## 🔧 Fonctionnalités

### Boutons d'action

- **Sauvegarder l'offre** : Enregistre toutes les modifications
- **Réinitialiser** : Annule les modifications non sauvegardées
- **Imprimer** : Imprime l'offre d'emploi

### Système d'édition

Le système utilise le même principe que l'éditeur de CV :

1. **Attributs data-*** : Chaque élément éditable contient des attributs pour identifier la table et la colonne
2. **EditorManager** : Gère l'ouverture des popups et l'affichage des formulaires
3. **FormEditors** : Collection de formulaires spécialisés pour chaque section
4. **getData.php** : API pour récupérer et mettre à jour les données

## 📊 Exemple de données

Le fichier SQL inclut des exemples de données pour tester le système :

- 2 contacts
- 2 entreprises (TechCorp, BuildPro)
- 2 exigences
- 2 offres d'emploi (Développeur Full Stack, Chef de Projet BTP)

## 🎨 Personnalisation

### Modifier les couleurs

Éditer `style/joboffer.css` :
```css
.job-header {
    background: linear-gradient(135deg, #1e93e5 0%, #4b97a2 100%);
}
```

### Ajouter des champs

1. Ajouter la colonne dans la base de données
2. Mettre à jour l'entité PHP correspondante
3. Ajouter le champ dans le template `job-offer.php`
4. Ajouter le champ dans le formulaire JavaScript approprié

## 🔗 Intégration avec le système existant

Le système s'intègre parfaitement avec :

- **editor.js** : Gestionnaire d'édition existant
- **save.js** : Système de sauvegarde
- **print.js** : Fonctionnalité d'impression
- **getData.php** : API de données étendue

## 📝 Notes importantes

1. Les ID des entités liées (company_id, contact_id, requirement_id) doivent exister dans leurs tables respectives
2. Le système utilise le même principe de `data-table`, `data-column`, `data-id` que l'éditeur de CV
3. Les formulaires sont chargés dynamiquement via `window.FormEditors`
4. L'attribut `data-classified` est conservé pour cohérence mais n'est pas utilisé pour les offres d'emploi

## 🐛 Dépannage

### Les offres ne s'affichent pas

- Vérifier que la base de données job est créée
- Vérifier les données dans la table `job`
- Consulter la console JavaScript pour les erreurs

### L'édition ne fonctionne pas

- Vérifier que tous les scripts JS sont chargés
- Vérifier que `EditorManager` est initialisé
- Consulter la console pour les erreurs

### Les données ne se sauvegardent pas

- Vérifier les permissions de la base de données
- Vérifier que `updateData.php` existe et fonctionne
- Consulter les logs PHP pour les erreurs
