-- Ajouter la colonne priority à la table job
-- Niveaux de priorité :
-- 0 = Normal (par défaut)
-- 1 = Recherche Active
-- 2 = Importante
-- 3 = Urgente

ALTER TABLE `job` 
ADD COLUMN `priority` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Niveau de priorité de l''offre (0=Normal, 1=Recherche Active, 2=Importante, 3=Urgente)' 
AFTER `approved`;

-- Créer un index sur priority pour optimiser le tri
ALTER TABLE `job`
ADD INDEX `idx_priority` (`priority`);

-- Par défaut, toutes les offres existantes ont une priorité normale (0)
UPDATE `job` SET `priority` = 0 WHERE `priority` IS NULL;
