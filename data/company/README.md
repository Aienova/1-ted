# Company Media Files

Ce dossier contient les fichiers média des entreprises, organisés par `media_code`.

## Structure

```
/data/company/
  └── [media_code]/
      └── logo.png
```

## Détails

- **media_code** : Code unique de 12 caractères généré pour chaque entreprise
- **logo.png** : Logo de l'entreprise converti automatiquement en PNG (max 2MB)

## Exemples

```
/data/company/aB3cD4eF5gH6/logo.png
/data/company/xY9zA1bC2dE3/logo.png
```

## Gestion

- Upload : `/function/uploadImage.php` avec `entityType: 'company'` et `type: 'logo'`
- Suppression : `/function/deleteCompanyLogo.php`
- Les images sont automatiquement converties en PNG avec transparence
