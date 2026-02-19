-- Migration pour ajouter les contraintes de clés étrangères à la table application
-- Date: 2026-02-16
-- Description: Ajoute les relations FOREIGN KEY entre application → candidate et application → job

-- Note: Cette migration suppose que les tables candidate et job existent déjà
-- et que toutes les données existantes dans application respectent l'intégrité référentielle

-- Étape 1: Vérifier et nettoyer les données orphelines (optionnel mais recommandé)
-- Supprimer les applications avec des candidate_id qui n'existent pas dans la table candidate
DELETE FROM application 
WHERE candidate_id NOT IN (SELECT id FROM candidate);

-- Supprimer les applications avec des job_id qui n'existent pas dans la table job
DELETE FROM application 
WHERE job_id NOT IN (SELECT id FROM job);

-- Étape 2: Ajouter les contraintes de clés étrangères

-- Contrainte pour candidate_id
ALTER TABLE `application`
ADD CONSTRAINT `fk_application_candidate`
FOREIGN KEY (`candidate_id`) 
REFERENCES `candidate`(`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Contrainte pour job_id
ALTER TABLE `application`
ADD CONSTRAINT `fk_application_job`
FOREIGN KEY (`job_id`) 
REFERENCES `job`(`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Vérification: afficher les contraintes ajoutées (à exécuter manuellement si besoin)
-- Cette requête est commentée car elle peut causer des problèmes de permissions lors de l'import
-- Pour vérifier manuellement après l'import, exécutez:
/*
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
*/
