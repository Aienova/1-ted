# Système de Visualisation de CV

## 📋 Description

Ce système permet d'afficher une liste de candidats avec un viewer de CV dynamique. La liste est scrollable indépendamment et affiche les CV en HTML statique dans un panneau de droite.

## 🎯 Architecture

### Layout
```
┌─────────────────────────────────────────────┐
│  Liste Candidats     │      Viewer CV       │
│  (35% - scroll)      │    (65% - scroll)    │
│                      │                      │
│  ┌──────────────┐    │                      │
│  │ Carte 1      │    │                      │
│  ├──────────────┤    │     [CV HTML]        │
│  │ Carte 2      │◄───┼─ Chargement          │
│  ├──────────────┤    │    dynamique         │
│  │ Carte 3      │    │                      │
│  └──────────────┘    │                      │
└─────────────────────────────────────────────┘
```

## 📁 Fichiers

### Frontend
- **`[lang]/[page]/fragment/list-candidates.php`** - Page principale avec layout
- **`[lang]/[page]/fragment/cv-viewer.php`** - Container viewer
- **`script/viewer.js`** - Logique de chargement dynamique
- **`style/viewer.css`** - Styles du layout et viewer
- **`style/list.css`** - Styles des cartes candidats
- **`style/style.css`** - Styles du CV

### Backend
- **`function/getCandidates.php`** - API liste tous les candidats
- **`function/getCandidateMediaCode.php`** - API récupère media_code par ID
- **`data/candidates/media/[media_code]/cv.html`** - CV statique généré

## 🔄 Flux de données

1. **Chargement initial**
   ```
   Page → getCandidates.php → Affichage cartes
   ```

2. **Clic sur "Voir son CV"**
   ```
   Carte → getCandidateMediaCode.php → media_code
   → Fetch cv.html → Affichage dans viewer
   ```

## 💻 Utilisation

### Intégrer la liste dans une page

```php
<?php include 'fragment/list-candidates.php'; ?>
```

### Structure d'une carte candidat

```html
<div class="candidate-card" data-candidate-id="123">
    <div class="candidate-card-header">
        <img src="..." class="candidate-photo">
        <div class="candidate-card-info">
            <h3>Nom Prénom</h3>
            <p class="position">Développeur</p>
        </div>
    </div>
    <div class="candidate-card-details">
        <p>📍 Paris</p>
        <p>🎂 28 ans</p>
        <p>💼 CDI</p>
    </div>
    <button class="btn-view-cv">Voir son CV</button>
</div>
```

## 🎨 Styles importants

### Classes CSS principales
- `.candidates-section` - Container principal
- `.candidates-list-column` - Colonne gauche (liste)
- `.cv-viewer-column` - Colonne droite (viewer)
- `.candidate-card` - Carte candidat
- `.candidate-card.active` - Carte sélectionnée
- `#viewer` - Container du CV

### États du viewer
- `.empty` - État vide (message d'accueil)
- `.loader` - Chargement en cours
- `.error-state` - Erreur d'affichage

## 🔧 Fonctions JavaScript

### Principales
- `loadCandidatesList()` - Charge la liste depuis l'API
- `loadCandidateCV(id, card)` - Charge un CV spécifique
- `getMediaCodeByID(id)` - Récupère le media_code
- `fetchCVHTML(mediaCode)` - Récupère le HTML du CV
- `displayCV(viewer, html)` - Affiche le CV
- `markCardAsActive(card)` - Marque la carte active

### Utilitaires
- `showLoader(viewer)` - Affiche le loader
- `showError(viewer, message)` - Affiche une erreur
- `initializeViewer()` - Initialise l'état vide

## 📱 Responsive

- **Desktop** : Layout 2 colonnes (35% / 65%)
- **Tablet/Mobile** : Layout vertical empilé
  - Liste : max-height 400px
  - Viewer : min-height 600px

## 🔗 API Endpoints

### GET /function/getCandidates.php
Récupère tous les candidats

**Response:**
```json
{
    "success": true,
    "candidates": [
        {
            "id": 1,
            "media_code": "8w2H1q0U5Z3m",
            "first_name": "John",
            "last_name": "Doe",
            "job_title": "Développeur",
            "location": "Paris",
            "age": 28,
            "photo_url": "..."
        }
    ],
    "count": 1
}
```

### GET /function/getCandidateMediaCode.php?id=1
Récupère le media_code d'un candidat

**Response:**
```json
{
    "success": true,
    "media_code": "8w2H1q0U5Z3m"
}
```

## ⚠️ Notes importantes

1. **CV Statique requis** : Le fichier `cv.html` doit être généré via `convertToHTML.js` lors de la sauvegarde
2. **Media code obligatoire** : Chaque candidat doit avoir un `media_code` valide
3. **Scroll indépendant** : Les colonnes scrollent séparément
4. **Cache busting** : Utiliser `?t=${timestamp}` pour les images si nécessaire

## 🐛 Débogage

### Problèmes courants

**CV ne s'affiche pas**
- Vérifier que `cv.html` existe dans le dossier media_code
- Vérifier les permissions du fichier
- Ouvrir la console pour voir les erreurs

**Liste vide**
- Vérifier la connexion à la base de données
- Vérifier que des candidats existent
- Vérifier les logs PHP

**Styles cassés**
- Vérifier que tous les CSS sont inclus dans `info.php`
- Vérifier l'ordre de chargement des CSS
