# Système de Séniorité des Candidats

## 📚 Vue d'ensemble

Ce système permet aux administrateurs de définir et afficher le niveau de séniorité de chaque candidat dans le backoffice. La séniorité est affichée sur la carte du candidat et peut être modifiée via une popup dédiée.

## 🎯 Niveaux de séniorité disponibles

| Niveau | Icône | Description | Expérience |
|--------|-------|-------------|------------|
| **Apprenti** | 🎓 | En formation ou stage | Formation initiale |
| **Junior** | 🌱 | Débutant dans le métier | 0-2 ans |
| **Confirmé** | 💼 | Niveau intermédiaire | 2-5 ans |
| **Senior** | ⭐ | Expérience avancée | 5-10 ans |
| **Expert** | 👑 | Expertise reconnue | 10+ ans |
| **Non spécifié** | ⚪ | Aucune séniorité définie | - |

## 📁 Fichiers créés

### Backend
- **`function/getSeniorities.php`** : API pour récupérer la liste des niveaux de séniorité
- **`function/updateCandidateSeniority.php`** : API pour mettre à jour la séniorité d'un candidat
  - Vérification des droits administrateur
  - Validation des données
  - Mise à jour en base de données

### Base de données
- **`sql/add_candidate_seniority.sql`** : Script SQL pour ajouter le champ `seniority` à la table `candidate`

### Frontend
- **Popup de sélection** : Interface pour choisir le niveau de séniorité
- **Badge de séniorité** : Affichage coloré dans la carte candidat
- **Bouton "Séniorité"** : Bouton dans les actions de chaque carte

## 🎨 Interface utilisateur

### Carte candidat
Le badge de séniorité s'affiche dans les informations du candidat, sous la localisation et l'âge :

```
📍 Paris, France
🎂 28 ans
💼 Confirmé         ← Badge de séniorité
```

### Popup de sélection
La popup permet de sélectionner parmi 6 options :
- Options avec icônes et descriptions
- Sélection par radio buttons
- Couleurs distinctives pour chaque niveau
- Boutons "Sauvegarder" et "Annuler"

## 🔧 Utilisation

### Pour un administrateur

1. **Afficher la séniorité** : Visible directement sur la carte du candidat
2. **Modifier la séniorité** :
   - Cliquer sur le bouton "📊 Séniorité"
   - Sélectionner le niveau approprié
   - Cliquer sur "💾 Sauvegarder"
3. **Retirer la séniorité** : Sélectionner "Non spécifié"

## 🗄️ Structure de la base de données

### Champ `seniority`
```sql
ALTER TABLE `candidate` 
ADD COLUMN `seniority` VARCHAR(50) NULL DEFAULT NULL;
```

**Valeurs possibles :**
- `'apprentice'` - Apprenti
- `'junior'` - Junior
- `'confirmed'` - Confirmé
- `'senior'` - Senior
- `'expert'` - Expert
- `NULL` - Non spécifié

## 🎨 Couleurs des badges

| Séniorité | Fond | Texte |
|-----------|------|-------|
| Apprenti | `#fef3c7` | `#92400e` |
| Junior | `#d1fae5` | `#065f46` |
| Confirmé | `#dbeafe` | `#1e40af` |
| Senior | `#fce7f3` | `#9f1239` |
| Expert | `#f3e8ff` | `#6b21a8` |

## 🔒 Sécurité

- **Authentification** : Session requise
- **Autorisation** : Réservé aux administrateurs (`role === 'administrator'`)
- **Validation** : Vérification des valeurs de séniorité
- **Sanitization** : Nettoyage des données d'entrée

## 📊 API

### GET `/function/getSeniorities.php`
Récupère la liste des niveaux de séniorité.

**Réponse :**
```json
{
  "success": true,
  "seniorities": [
    {
      "id": "junior",
      "name": "Junior",
      "fr_name": "Junior",
      "en_name": "Junior",
      "description": "0-2 ans d'expérience",
      "icon": "🌱",
      "color": "#d1fae5",
      "text_color": "#065f46"
    }
  ],
  "count": 5
}
```

### POST `/function/updateCandidateSeniority.php`
Met à jour la séniorité d'un candidat.

**Requête :**
```json
{
  "candidate_id": 123,
  "seniority": "senior"
}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Séniorité mise à jour avec succès",
  "seniority": "senior",
  "seniority_name": "Senior"
}
```

## 🧩 Intégration

### Dans candidates.php
```javascript
// Fonction pour obtenir le badge de séniorité
function getSeniorityBadge(seniority) {
    if (!seniority) return '';
    // ...
}

// Ouvrir le sélecteur
openSenioritySelector(candidateId, currentSeniority);

// Sauvegarder la séniorité
saveCandidateSeniority();
```

## ✅ Tests à effectuer

1. **Affichage** : Vérifier que le badge s'affiche correctement
2. **Ouverture popup** : Cliquer sur "Séniorité" ouvre la popup
3. **Sélection** : Chaque niveau de séniorité est sélectionnable
4. **Sauvegarde** : La séniorité est bien enregistrée
5. **Mise à jour** : La carte se rafraîchit avec la nouvelle séniorité
6. **Retrait** : Sélectionner "Non spécifié" retire la séniorité
7. **Droits** : Seuls les admins peuvent modifier

## 🚀 Évolutions possibles

- ✨ Filtrer les candidats par séniorité
- 📊 Statistiques sur la répartition des séniorités
- 🔍 Recherche avancée incluant la séniorité
- 📧 Mention de la séniorité dans les emails
- 🎨 Personnalisation des couleurs par séniorité
- 📱 Affichage adaptatif sur mobile

## 📝 Notes

- La séniorité est **optionnelle** (peut être NULL)
- Compatible avec le système de priorité existant
- Utilise le même style que la popup de priorité
- Rechargement automatique après modification
- Notifications de succès/erreur
