-- Migration : Ajout des clés étrangères entre job, country et activity
-- Date : 2026-02-26
--
-- Cette migration ajoute les contraintes FK manquantes sur la table `job` :
--   - job.activity_id  → activity.id
--   - job.country_id   → country.id

-- -------------------------------------------------------
-- Vérification que les index existent déjà (idx_activity_id et idx_country_id)
-- Si la table a été créée avec job.sql ils sont déjà présents.
-- -------------------------------------------------------

ALTER TABLE `job`
  ADD CONSTRAINT `fk_job_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_job_country`  FOREIGN KEY (`country_id`)  REFERENCES `country`  (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
