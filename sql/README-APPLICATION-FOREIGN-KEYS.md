# Migration des clés étrangères pour la table Application

## 📋 Description

Cette migration ajoute des contraintes de clés étrangères (FOREIGN KEY) à la table `application` pour établir des relations formelles avec les tables `candidate` et `job`.

## 🎯 Objectif

Créer des jointures au niveau de la base de données pour garantir l'intégrité référentielle entre:
- `application.candidate_id` → `candidate.id`
- `application.job_id` → `job.id`

## 📦 Fichiers

- **add_foreign_keys_application.sql** - Script de migration pour ajouter les contraintes
- **rollback_foreign_keys_application.sql** - Script de rollback pour supprimer les contraintes
- **README-APPLICATION-FOREIGN-KEYS.md** - Ce fichier de documentation

## 🔧 Installation

### Option 1: Via phpMyAdmin
1. Ouvrez phpMyAdmin
2. Sélectionnez la base de données `1ted`
3. Allez dans l'onglet "SQL"
4. Copiez-collez le contenu de `add_foreign_keys_application.sql`
5. Cliquez sur "Exécuter"

### Option 2: Via ligne de commande MySQL
```bash
mysql -u root -p 1ted < sql/add_foreign_keys_application.sql
```

### Option 3: Via PowerShell (Windows avec XAMPP)
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -p 1ted -e "source C:\xampp\htdocs\1-ted-backoffice\sql\add_foreign_keys_application.sql"
```

## ⚙️ Comportement des contraintes

### ON DELETE CASCADE
Si un candidat ou un job est supprimé, toutes les applications associées **seront automatiquement supprimées**.

**Exemple:**
```sql
DELETE FROM candidate WHERE id = 5;
-- Toutes les applications avec candidate_id = 5 seront automatiquement supprimées
```

### ON UPDATE CASCADE
Si l'ID d'un candidat ou d'un job est modifié (rare), toutes les applications seront automatiquement mises à jour.

**Exemple:**
```sql
UPDATE candidate SET id = 999 WHERE id = 5;
-- Toutes les applications avec candidate_id = 5 seront mises à jour à candidate_id = 999
```

## 🔍 Vérification

Après avoir exécuté la migration, vérifiez que les contraintes sont bien créées:

```sql
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE 
    TABLE_NAME = 'application'
    AND CONSTRAINT_SCHEMA = '1ted'
    AND REFERENCED_TABLE_NAME IS NOT NULL;
```

**Résultat attendu:**
```
+----------------------------+-------------+--------------+-----------------------+-----------------------+
| CONSTRAINT_NAME            | TABLE_NAME  | COLUMN_NAME  | REFERENCED_TABLE_NAME | REFERENCED_COLUMN_NAME|
+----------------------------+-------------+--------------+-----------------------+-----------------------+
| fk_application_candidate   | application | candidate_id | candidate             | id                    |
| fk_application_job         | application | job_id       | job                   | id                    |
+----------------------------+-------------+--------------+-----------------------+-----------------------+
```

## ⚠️ Points d'attention

### Données orphelines
Le script inclut un nettoyage préventif des données orphelines (applications qui référencent des candidats ou jobs inexistants). **Ces données seront supprimées**.

Si vous voulez conserver ces données, commentez les lignes de DELETE dans le script:
```sql
-- DELETE FROM application 
-- WHERE candidate_id NOT IN (SELECT id FROM candidate);
-- 
-- DELETE FROM application 
-- WHERE job_id NOT IN (SELECT id FROM job);
```

### Impact sur les suppressions
Avec `ON DELETE CASCADE`, la suppression d'un candidat ou d'un job supprimera automatiquement toutes ses applications. Si vous préférez empêcher la suppression:

Utilisez `ON DELETE RESTRICT` à la place:
```sql
ALTER TABLE `application`
ADD CONSTRAINT `fk_application_candidate`
FOREIGN KEY (`candidate_id`) 
REFERENCES `candidate`(`id`)
ON DELETE RESTRICT  -- Au lieu de CASCADE
ON UPDATE CASCADE;
```

Avec `RESTRICT`, vous obtiendrez une erreur si vous essayez de supprimer un candidat/job qui a des applications.

## 🔄 Rollback

Si vous devez annuler la migration:
```bash
mysql -u root -p 1ted < sql/rollback_foreign_keys_application.sql
```

## 📊 Avantages

✅ **Intégrité référentielle** - Impossible d'avoir des applications pour des candidats/jobs inexistants
✅ **Cascade automatique** - Nettoyage automatique des applications quand on supprime un candidat/job
✅ **Performances** - Les index existants (`idx_candidate_id`, `idx_job_id`) optimisent les jointures
✅ **Cohérence ORM** - Correspond au modèle objet PHP avec `getCandidate()` et `getJob()`

## 📝 Notes

- Les index `idx_candidate_id` et `idx_job_id` existaient déjà et sont automatiquement utilisés par les contraintes
- La base de données utilise InnoDB, qui supporte les clés étrangères
- Le charset est `utf8mb4` avec collation `utf8mb4_unicode_ci`

## 🐛 Dépannage

**Erreur: Cannot add foreign key constraint**
- Vérifiez que les tables `candidate` et `job` existent
- Vérifiez que les colonnes `id` sont bien PRIMARY KEY dans ces tables
- Vérifiez qu'il n'y a pas de données orphelines (candidate_id ou job_id invalides)

**Pour diagnostiquer:**
```sql
-- Trouver les applications avec des candidate_id invalides
SELECT * FROM application 
WHERE candidate_id NOT IN (SELECT id FROM candidate);

-- Trouver les applications avec des job_id invalides
SELECT * FROM application 
WHERE job_id NOT IN (SELECT id FROM job);
```
