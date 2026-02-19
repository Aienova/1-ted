# Conversion CV en HTML Statique

## Fonctionnalité

À chaque sauvegarde ou création d'un CV, le système génère automatiquement une version HTML statique du CV pour consultation.

## Fichiers impliqués

### Scripts
- **`script/convertToHTML.js`** : Fonction principale de conversion
- **`script/save.js`** : Appel de la conversion après sauvegarde (mode UPDATE)
- **`script/add.js`** : Appel de la conversion après création (mode INSERT)

### Backend
- **`function/saveHTMLCV.php`** : Sauvegarde le fichier HTML sur le serveur

### Structure
- **`data/candidates/media/[media_code]/cv.html`** : Fichier HTML statique généré

## Processus de conversion

1. **Clonage du DOM** : Le conteneur `.cv-container` est cloné pour ne pas modifier l'original

2. **Nettoyage** :
   - Suppression des attributs `data-*` (table, id, column, value, classified, collection, editor, mode)
   - Suppression des classes d'édition (`dataToEdit`, `multipleDataToEdit`, `canEdit`)
   - Suppression du conteneur de boutons
   - Nettoyage des attributs style vides

3. **Génération HTML** :
   - Création d'un document HTML complet avec `<!DOCTYPE html>`
   - Inclusion des styles CSS (style.css, editor.css)
   - Ajout de styles spécifiques pour la version statique
   - Styles d'impression optimisés

4. **Sauvegarde** :
   - Envoi du HTML au serveur via `saveHTMLCV.php`
   - Création du dossier si nécessaire
   - Écriture du fichier `cv.html`

## Utilisation

### Après sauvegarde (mode UPDATE)
```javascript
// Dans save.js
await updateAllData(dataByTable, candidateId);
await convertCVToHTML(mediaCode); // ✅ Conversion automatique
```

### Après création (mode INSERT)
```javascript
// Dans add.js
const mediaCode = await insertNewCandidate(dataByTable);
await convertCVToHTML(mediaCode); // ✅ Conversion automatique
```

## Résultat

Le fichier généré est une version **statique** et **consultable** du CV, sans fonctionnalités d'édition :
- ✅ Affichage propre et professionnel
- ✅ Prêt pour l'impression
- ✅ Pas d'éditeur ni de boutons d'action
- ✅ Consultation pure

## Accès au CV statique

```
http://1-ted.local//data/candidates/media/[media_code]/cv.html
```

Exemple :
```
http://1-ted.local//data/candidates/media/8w2H1q0U5Z3m/cv.html
```
