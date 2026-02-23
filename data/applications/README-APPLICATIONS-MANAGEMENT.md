# Gestion des Candidatures - Documentation

## Vue d'ensemble

L'interface de gestion des candidatures permet de visualiser et gérer toutes les candidatures des candidats aux offres d'emploi.

## Fichiers créés

### 1. `function/getApplications.php`
API qui récupère toutes les candidatures avec les informations associées :
- **Candidat** : nom, prénom, email, téléphone, photo, media_code
- **Job** : intitulé, media_code
- **Société** : nom, contact (email, téléphone)
- **Recruteur** : email, téléphone (via table account)

**Paramètres GET** :
- `keyword` : Recherche par nom candidat, poste, ou société
- `status` : Filtre par statut (all, pending, in-process, question, rejected, validate)

**Retour JSON** :
```json
{
  "success": true,
  "applications": [...],
  "count": 10
}
```

### 2. `[lang]/user-account/[role]/[page]/fragment/admin/applications.php`
Interface de gestion des candidatures avec :
- **Liste des candidatures** avec layout en colonnes :
  - Gauche : Photo + Nom prénom du candidat
  - Centre : Icône de statut
  - Centre-droite : Intitulé du poste + Nom de la société
  - Droite : Boutons d'action

- **Filtres** :
  - Recherche par mot-clé (nom, prénom, poste, société)
  - Filtre par statut

- **Actions** :
  - 👁️ Voir le CV : Ouvre le CV dans un nouvel onglet
  - 📄 Voir l'offre : Ouvre l'offre d'emploi dans un nouvel onglet
  - 📞 Contact : Ouvre une popup avec les coordonnées

### 3. `style/account-system.css` (mis à jour)
Ajout des styles pour :
- `.applications-container` : Container principal
- `.application-item` : Layout grid des items
- `.status-icon` : Icônes de statut avec couleurs
- `.btn-action` : Boutons d'action colorés

## Statuts des candidatures

| Statut | Icône | Couleur | Signification |
|--------|-------|---------|---------------|
| `pending` | ⏳ | Orange | En attente de traitement |
| `in-process` | 👁️ | Bleu | En cours d'examen |
| `question` | ❓ | Violet | Examen / Entretien programmé |
| `rejected` | ❌ | Rouge | Candidature rejetée |
| `validate` | ✅ | Vert | Candidat embauché |

## Fonctionnalités

### 1. Affichage des candidatures
- Layout responsive avec grille adaptative
- Photos des candidats (fallback sur image par défaut si manquante)
- Tooltip au survol des icônes de statut
- Effet hover sur les items

### 2. Filtres avec debounce
- Recherche temps réel avec délai de 500ms
- Filtre par statut instantané
- Message "Aucune candidature trouvée" si résultats vides

### 3. Actions sur les candidatures

#### Voir le CV
- Ouvre le fichier `data/candidates/media/{mediaCode}/cv.html` dans un nouvel onglet
- Bouton désactivé si le CV n'existe pas

#### Voir l'offre d'emploi
- Ouvre le fichier `data/jobs/media/{mediaCode}/job.html` dans un nouvel onglet
- Bouton désactivé si l'offre n'existe pas

#### Contact
- Popup avec 2 sections :
  - **Candidat** : Email (mailto:) + Téléphone (tel:)
  - **Recruteur/Société** : Email (mailto:) + Téléphone (tel:)
- Liens cliquables pour envoyer email ou appeler directement
- Affichage du commentaire si présent

## Base de données

### Requête principale
```sql
SELECT 
    a.id, a.status, a.comment,
    c.id, c.media_code,
    i.first_name, i.last_name, i.email, i.phone,
    j.id, j.job_title, j.media_code,
    co.name,
    cont.email, cont.phone,
    acc.email, acc.phone
FROM application a
LEFT JOIN candidate c ON a.candidate_id = c.id
LEFT JOIN identity i ON c.identity_id = i.id
LEFT JOIN job j ON a.job_id = j.id
LEFT JOIN company co ON j.company_id = co.id
LEFT JOIN contact cont ON co.contact_id = cont.id
LEFT JOIN account acc ON acc.company_id = co.id AND acc.role = 'recruiter'
WHERE 1=1
ORDER BY a.id DESC
```

### Relations utilisées
- `application` → `candidate` (FK: candidate_id)
- `application` → `job` (FK: job_id)
- `candidate` → `identity` (FK: identity_id)
- `job` → `company` (FK: company_id)
- `company` → `contact` (FK: contact_id)
- `account` → `company` (FK: company_id)

## Utilisation

### Accéder à la page
```
/[lang]/user-account/[role]/[page]/fragment/admin/applications.php
```

### Variables PHP requises
La page attend la variable `$baseUrl` définie par le parent :
```php
$baseUrl = '/1-ted-backoffice';
include 'fragment/admin/applications.php';
```

### JavaScript
Les fonctions JavaScript utilisées :
- `loadApplications()` : Charge les candidatures depuis l'API
- `displayApplications(apps)` : Affiche la liste
- `getStatusInfo(status)` : Retourne icône + label pour un statut
- `viewCV(url)` : Ouvre le CV dans nouvel onglet
- `viewJob(url)` : Ouvre l'offre dans nouvel onglet
- `showContactPopup(id)` : Affiche popup de contact

### Système de popup
Utilise le système de popup générique du projet :
- `popup.js` : Gestion des popups
- `blocks/popup.php` : Template de popup
- Popup ID : `popup-contact` (taille medium)

## Responsive

### Desktop (> 1024px)
- Layout grid : `280px | 80px | 1fr | 180px`
- 4 colonnes visibles

### Tablet (768px - 1024px)
- Layout grid : `1fr`
- Colonnes empilées verticalement
- Actions en ligne horizontale

### Mobile (< 768px)
- Filtres en colonne unique
- Actions en colonne verticale
- Photos réduites

## Améliorations possibles

1. **Tri des colonnes** : Ajouter tri par nom, date, statut
2. **Pagination** : Limiter à 20 résultats par page
3. **Export CSV** : Exporter la liste des candidatures
4. **Modification du statut** : Dropdown pour changer le statut directement
5. **Notes/Commentaires** : Ajouter des commentaires sur les candidatures
6. **Notifications** : Alerter le candidat/recruteur lors de changement de statut
7. **Historique** : Tracer les changements de statut avec dates
8. **Statistiques** : Dashboard avec répartition par statut

## Dépendances

- `function/connectionDatabase.php` : Connexion à la base
- `script/popup.js` : Système de popups
- `[lang]/user-account/[role]/[page]/fragment/admin/blocks/popup.php` : Template popup
- `style/account-system.css` : Styles globaux

## Notes techniques

1. **Débounce** : 500ms sur le champ de recherche pour éviter trop de requêtes
2. **Fallback images** : Image par défaut si photo candidat manquante
3. **Null safety** : Vérification de l'existence des données avant affichage
4. **Liens mailto/tel** : Fonctionnent sur tous les navigateurs modernes
5. **Override CSS** : Le body est surchargé pour éviter le gradient de connexion

## Sécurité

⚠️ **À implémenter** :
- Vérification des permissions utilisateur (admin uniquement)
- Protection CSRF sur les actions
- Validation des IDs en entrée
- Échappement HTML des données utilisateur
- Rate limiting sur l'API
