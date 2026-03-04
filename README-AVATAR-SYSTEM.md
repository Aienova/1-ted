# Système d'assignation d'avatar pour les candidats

## Fonctionnalités

L'admin peut désormais assigner un avatar personnalisé à chaque candidat depuis le panneau d'administration.

## Fichiers créés/modifiés

### 1. Backend (API)

- **function/assignAvatar.php** : API pour mettre à jour l'avatar_url d'un candidat
  - POST avec `candidateId` et `avatarUrl`
  - Met à jour la colonne `avatar_url` dans la table `candidate`

- **function/getAvatarsList.php** : API pour récupérer la liste des avatars disponibles
  - GET avec paramètre `gender` (men ou women)
  - Scanne le dossier `media/image/icon/avatar/[gender]/`
  - Retourne un tableau d'URLs d'avatars

### 2. Frontend (JavaScript)

- **script/forms/select-avatar.js** : Logique de la popup de sélection d'avatar
  - `openAvatarSelector(candidateId, currentAvatarUrl)` : Ouvre la popup
  - `loadAvatarsByGender(gender)` : Charge les avatars selon le genre
  - `selectAvatar(avatarUrl)` : Sélectionne un avatar
  - `saveAvatar()` : Sauvegarde l'avatar via l'API
  - `closeAvatarSelector()` : Ferme la popup

### 3. Interface Admin

- **[lang]/user-account/[role]/[page]/fragment/admin/candidates.php** :
  - Bouton "🎭 Avatar" ajouté dans les actions de chaque carte candidat
  - Popup HTML complète avec :
    - Sélection du genre (👨 Homme / 👩 Femme)
    - Aperçu de l'avatar sélectionné
    - Galerie d'avatars (miniatures circulaires)
    - Boutons Sauvegarder / Annuler
  - Styles CSS complets pour la popup

## Utilisation

1. L'admin clique sur le bouton **"🎭 Avatar"** sur une carte candidat
2. Une popup s'ouvre avec l'avatar actuel ou l'avatar par défaut
3. L'admin sélectionne le genre de l'avatar (Homme ou Femme)
4. La galerie se charge avec tous les avatars disponibles
5. L'admin clique sur un avatar pour le sélectionner
6. L'aperçu se met à jour en temps réel
7. L'admin clique sur **"💾 Sauvegarder"**
8. L'avatar est assigné au candidat via l'API
9. La liste des candidats se recharge automatiquement

## Structure des dossiers

```
media/image/icon/avatar/
├── default.png          # Avatar par défaut si avatar_url est null
├── men/                 # Avatars masculins
│   ├── 1.png
│   ├── 2.png
│   └── ...
└── women/               # Avatars féminins
    ├── 1.png
    ├── 2.png
    └── ...
```

## Base de données

La colonne `avatar_url` (VARCHAR 500) existe déjà dans la table `candidate`.
Elle stocke l'URL complète de l'avatar sélectionné, par exemple :
- `/media/image/icon/avatar/women/4.png`
- `/media/image/icon/avatar/men/12.png`

## Affichage

- **Pour les admins** : La vraie photo via `media_code` est affichée
- **Pour les visiteurs** : L'`avatar_url` ou l'image par défaut est affichée
- **Si avatar_url est NULL** : `/media/image/icon/avatar/default.png` est utilisé

## Sécurité

- Seuls les admins peuvent assigner des avatars
- Les avatars sont stockés localement (pas d'upload)
- L'API valide les paramètres avant mise à jour
- Les chemins d'avatars sont contrôlés (men/women uniquement)
