-- Ajouter la colonne media_code à la table company
-- Ce code média unique permet d'identifier les ressources médias associées à l'entreprise

ALTER TABLE `company` 
ADD COLUMN `media_code` VARCHAR(12) DEFAULT NULL COMMENT 'Code unique pour identifier les médias de l''entreprise (12 caractères)' 
AFTER `website`;

-- Créer un index sur media_code pour optimiser les recherches
ALTER TABLE `company`
ADD INDEX `idx_media_code` (`media_code`);

-- Générer des media_code uniques pour les entreprises existantes
UPDATE `company` 
SET `media_code` = CONCAT(
    SUBSTRING(MD5(CONCAT(id, name, UNIX_TIMESTAMP())), 1, 6),
    LPAD(id, 3, '0'),
    SUBSTRING(MD5(RAND()), 1, 3)
)
WHERE `media_code` IS NULL;
