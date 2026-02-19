# Mise à jour - Système Country et Activity

## 📋 Vue d'ensemble

Cette mise à jour ajoute la gestion des pays (Country) et des secteurs d'activité (Activity) à l'ensemble du système. Au lieu d'utiliser des chaînes de texte, toutes les entités utilisent désormais des IDs pour référencer pays et activités.

## 🗂️ Nouvelles tables créées

### 1. Table `country`
- **29 pays** : Tous les pays de l'UE + Royaume-Uni + Suisse
- Noms multilingues (FR, EN, DE, ES, IT, NL, PT)
- Code ISO, devise, langue par défaut
- **Fichier SQL** : [country_activity.sql](country_activity.sql)

### 2. Table `activity`  
- **30 secteurs d'activité** complets
- Noms multilingues pour chaque secteur
- Descriptions détaillées
- **Fichier SQL** : [country_activity.sql](country_activity.sql)

## 📊 Tables modifiées

### 1. Table `account`
**Changements :**
- `activity_sector` (varchar) → `activity_id` (int)
- Ajout de `country_id` (int)

**Fichier** : [account_system.sql](account_system.sql) (mis à jour)

### 2. Table `candidate`
**Changements :**
- Ajout de `country_id` (int)
- Ajout de `activity_id` (int)

### 3. Table `job`
**Changements :**
- Ajout de `activity_id` (int)  
- Ajout de `country_id` (int)

### 4. Table `company`
**Changements :**
- `country` (varchar) → `country_id` (int)

**Fichier de migration** : [update_tables_country_activity.sql](update_tables_country_activity.sql)

## 🔧 Modifications du code

### Entités PHP mises à jour

| Entité | Propriétés ajoutées/modifiées | Fichier |
|--------|------------------------------|---------|
| **Account** | `$activity` (ID), `$country` (ID) | [entity/other/account.php](../entity/other/account.php) |
| **Candidate** | `$activity` (ID), `$country` (ID) | [entity/candidate/candidate.php](../entity/candidate/candidate.php) |
| **Job** | `$activity` (ID), `$country` (ID) | [entity/job/job.php](../entity/job/job.php) |
| **Company** | `$country` (ID) | [entity/job/company.php](../entity/job/company.php) |
| **Country** | *Nouvelle entité* | [entity/other/country.php](../entity/other/country.php) |
| **Activity** | *Nouvelle entité* | [entity/other/activity.php](../entity/other/activity.php) |

### getData.php
**Modifications** :
- ✅ Ajout des `require_once` pour Country et Activity
- ✅ Mapping complet pour `country` et `activity`
- ✅ Mise à jour du mapping de `account` (utilise `activity_id`, `country_id`)
- ✅ Mise à jour du mapping de `candidate` (utilise `country_id`, `activity_id`)
- ✅ Mise à jour du mapping de `job` (utilise `activity_id`, `country_id`)
- ✅ Mise à jour du mapping de `company` (utilise `country_id`)
- ✅ Ajout dans `$allowedTables` : `'country'`, `'activity'`

## 🚀 Installation

### Étape 1 : Créer les tables Country et Activity

```bash
mysql -u root -p 1ted < sql/country_activity.sql
```

### Étape 2 : Mettre à jour le système de comptes

```bash
mysql -u root -p 1ted < sql/account_system.sql
```

Cette commande va créer/recréer la table `account` avec les bons champs.

### Étape 3 : Mettre à jour les tables existantes

```bash
mysql -u root -p 1ted < sql/update_tables_country_activity.sql
```

Cette commande ajoute les colonnes `country_id` et `activity_id` aux tables `candidate`, `job`, et `company`.

### Étape 4 : Mettre à jour les données existantes (optionnel)

Par défaut, les pays et activités seront NULL pour les données existantes. Vous pouvez exécuter des UPDATE pour les définir :

```sql
-- Exemple : Mettre tous les candidats en France
UPDATE `candidate` SET `country_id` = 1 WHERE `country_id` IS NULL;

-- Exemple : Mettre toutes les entreprises en France  
UPDATE `company` SET `country_id` = 1 WHERE `country_id` IS NULL;
```

## 📖 Utilisation

### Récupérer les pays

```php
// Tous les pays
$countries = getData('country');

// Un pays par ID
$france = getData('country', 1); // France

// Afficher le nom en français
echo $france->getFrName(); // "France"
```

### Récupérer les activités

```php
// Toutes les activités
$activities = getData('activity');

// Une activité par ID
$it = getData('activity', 1); // Informatique et Technologies

// Afficher le nom en anglais
echo $it->getEnName(); // "IT and Technology"
```

### Utiliser avec les comptes

```php
// Récupérer un compte
$account = getData('account', 2);

// Récupérer son activité
$activityId = $account->getActivity();
if ($activityId) {
    $activity = getData('activity', $activityId);
    echo $activity->getName(); // "Informatique et Technologies"
}

// Récupérer son pays
$countryId = $account->getCountry();
if ($countryId) {
    $country = getData('country', $countryId);
    echo $country->getCode(); // "FR"
    echo $country->getCurrencySymbol(); // "€"
}
```

### Utiliser avec les candidats

```php
// Récupérer un candidat
$candidate = getData('candidate', 1);

// Son pays et son activité
$countryId = $candidate->getCountry();
$activityId = $candidate->getActivity();

if ($countryId) {
    $country = getData('country', $countryId);
    echo "Pays : " . $country->getName();
}
```

### Utiliser avec les jobs

```php
// Récupérer une offre d'emploi
$job = getData('job', 5);

// Activité et pays de l'offre
$activityId = $job->getActivity();
$countryId = $job->getCountry();
```

## 🗺️ Liste des pays (IDs)

| ID | Pays | Code |
|----|------|------|
| 1 | France | FR |
| 2 | Allemagne | DE |
| 3 | Italie | IT |
| 4 | Espagne | ES |
| 5 | Pays-Bas | NL |
| 6 | Belgique | BE |
| 7 | Portugal | PT |
| 8 | Autriche | AT |
| 28 | Royaume-Uni | GB |
| 29 | Suisse | CH |

**Note** : Voir le fichier [country_activity.sql](country_activity.sql) pour la liste complète.

## 🏢 Liste des activités (IDs)

| ID | Activité |
|----|----------|
| 1 | Informatique et Technologies |
| 2 | BTP et Construction |
| 3 | Santé et Médical |
| 4 | Commerce et Vente |
| 5 | Finance et Banque |
| 6 | Marketing et Communication |
| 7 | Ressources Humaines |
| 8 | Industrie et Production |
| 9 | Logistique et Transport |
| 10 | Éducation et Formation |
| ... | ... |

**Note** : Voir le fichier [country_activity.sql](country_activity.sql) pour la liste complète (30 activités).

## 🔑 Mapping des activités pour les comptes de test

Les comptes créés dans [account_system.sql](account_system.sql) utilisent les IDs suivants :

| Activité (texte original) | ID | Nom dans la table |
|---------------------------|-----|-------------------|
| Informatique | 1 | Informatique et Technologies |
| Commerce | 4 | Commerce et Vente |
| Finance | 5 | Finance et Banque |
| Marketing | 6 | Marketing et Communication |
| RH | 7 | Ressources Humaines |
| Design | 20 | Design et Créativité |
| Production | 8 | Industrie et Production |
| Logistique | 9 | Logistique et Transport |
| BTP | 2 | BTP et Construction |

Tous les comptes sont par défaut en France (`country_id = 1`).

## 🔍 API REST

Les nouvelles tables sont accessibles via l'API HTTP GET :

```bash
# Récupérer tous les pays
GET /function/getData.php?table=country

# Récupérer toutes les activités
GET /function/getData.php?table=activity
```

## ⚠️ Notes importantes

1. **Migration des données existantes** : Les données existantes dans `candidate`, `job`, et `company` auront leurs champs `country_id` et `activity_id` à NULL par défaut. Pensez à les mettre à jour.

2. **Compatibilité** : Si vous avez du code qui utilise encore les anciens champs textuels, il faudra le mettre à jour pour utiliser les IDs.

3. **Clés étrangères** : Pour l'instant, il n'y a pas de contraintes de clés étrangères. Vous pouvez les ajouter si nécessaire.

## 📝 Checklist après installation

- [ ] Tables `country` et `activity` créées
- [ ] Table `account` mise à jour avec `activity_id` et `country_id`
- [ ] Tables `candidate`, `job`, `company` mises à jour
- [ ] getData.php mis à jour et fonctionnel
- [ ] Données existantes migrées (optionnel)
- [ ] Tests effectués sur les entités Account, Candidate, Job, Company

## 🆘 En cas de problème

**Erreur "Unknown column 'activity_id'"**
→ Vous n'avez pas exécuté les migrations SQL. Exécutez les fichiers dans l'ordre indiqué.

**Les getters/setters ne fonctionnent pas**
→ Vérifiez que les entités PHP ont bien les propriétés `$activity` et `$country`.

**Les mappings retournent NULL**
→ Vérifiez que les colonnes dans la base de données s'appellent bien `activity_id` et `country_id`.

## ✨ Avantages de cette mise à jour

- ✅ **Multilingue** : Tous les noms sont traduits en 7 langues
- ✅ **Cohérence** : Plus de texte libre, uniquement des IDs
- ✅ **Normalisation** : Respect des bonnes pratiques de base de données
- ✅ **Facilité de maintenance** : Une seule source de vérité pour chaque pays/activité
- ✅ **Extensibilité** : Facile d'ajouter de nouveaux pays ou activités
- ✅ **Performance** : Les jointures sur des IDs sont plus rapides que sur du texte
