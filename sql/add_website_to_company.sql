-- Ajouter la colonne website à la table company
ALTER TABLE `company` 
ADD COLUMN `website` VARCHAR(255) DEFAULT NULL COMMENT 'Site web de l\'entreprise' 
AFTER `recruiting`;
