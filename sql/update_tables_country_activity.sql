-- Mise à jour des tables pour utiliser country_id et activity_id
-- À exécuter APRÈS avoir créé les tables country et activity

-- --------------------------------------------------------
-- Mise à jour de la table `candidate`
-- --------------------------------------------------------

-- Ajouter les colonnes country_id et activity_id
ALTER TABLE `candidate` 
ADD COLUMN `country_id` int(11) DEFAULT NULL AFTER `identity_id`,
ADD COLUMN `activity_id` int(11) DEFAULT NULL AFTER `country_id`,
ADD KEY `idx_country_id` (`country_id`),
ADD KEY `idx_activity_id` (`activity_id`);

-- --------------------------------------------------------
-- Mise à jour de la table `job`
-- --------------------------------------------------------

-- Ajouter les colonnes activity_id et country_id
ALTER TABLE `job` 
ADD COLUMN `activity_id` int(11) DEFAULT NULL AFTER `location`,
ADD COLUMN `country_id` int(11) DEFAULT NULL AFTER `activity_id`,
ADD KEY `idx_activity_id` (`activity_id`),
ADD KEY `idx_country_id` (`country_id`);

-- --------------------------------------------------------
-- Mise à jour de la table `company`
-- --------------------------------------------------------

-- Ajouter la colonne country_id si elle n'existe pas déjà
-- (vérifiez d'abord si la colonne 'country' existe comme texte)
ALTER TABLE `company` 
MODIFY COLUMN `country` int(11) DEFAULT NULL;

-- Si la colonne s'appelle autrement, utilisez:
-- ALTER TABLE `company` 
-- ADD COLUMN `country_id` int(11) DEFAULT NULL AFTER `hq_location`,
-- ADD KEY `idx_country_id` (`country_id`);

-- Ou si vous avez une colonne 'country' en varchar, renommez-la:
-- ALTER TABLE `company` 
-- CHANGE COLUMN `country` `country_id` int(11) DEFAULT NULL;

-- --------------------------------------------------------
-- Exemples de mise à jour des données existantes
-- --------------------------------------------------------

-- Mettre tous les candidats français (exemple)
-- UPDATE `candidate` SET `country_id` = 1 WHERE `country_id` IS NULL;

-- Mettre tous les jobs en France (exemple)
-- UPDATE `job` SET `country_id` = 1 WHERE `country_id` IS NULL;

-- Mettre toutes les entreprises en France (exemple)
-- UPDATE `company` SET `country_id` = 1 WHERE `country_id` IS NULL OR `country_id` = '';

-- Note: country_id = 1 correspond à la France dans la table country
