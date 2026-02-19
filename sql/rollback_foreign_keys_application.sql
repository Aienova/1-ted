-- Rollback: Supprimer les contraintes de clés étrangères de la table application
-- Date: 2026-02-16
-- Description: Supprime les relations FOREIGN KEY ajoutées à la table application

-- Supprimer la contrainte pour candidate_id
ALTER TABLE `application`
DROP FOREIGN KEY `fk_application_candidate`;

-- Supprimer la contrainte pour job_id
ALTER TABLE `application`
DROP FOREIGN KEY `fk_application_job`;

-- Vérification: les contraintes devraient être supprimées
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE 
    TABLE_NAME = 'application'
    AND CONSTRAINT_SCHEMA = '1ted'
    AND REFERENCED_TABLE_NAME IS NOT NULL;
