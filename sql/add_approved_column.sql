-- Migration pour ajouter la colonne 'approved' aux tables candidate et job
-- Date: 2026-02-04

-- Ajouter la colonne 'approved' à la table 'candidate'
-- NULL = En attente, 1 = Approuvé, 0 = Refusé
ALTER TABLE `candidate` 
ADD COLUMN `approved` TINYINT(1) NULL DEFAULT NULL 
COMMENT 'NULL=En attente, 1=Approuvé, 0=Refusé' 
AFTER `media_code`;

-- Ajouter un index pour optimiser les requêtes par statut d'approbation
ALTER TABLE `candidate` 
ADD INDEX `idx_candidate_approved` (`approved`);

-- Ajouter la colonne 'approved' à la table 'job'
-- NULL = En attente, 1 = Approuvé, 0 = Refusé
ALTER TABLE `job` 
ADD COLUMN `approved` TINYINT(1) NULL DEFAULT NULL 
COMMENT 'NULL=En attente, 1=Approuvé, 0=Refusé' 
AFTER `open`;

-- Ajouter un index pour optimiser les requêtes par statut d'approbation
ALTER TABLE `job` 
ADD INDEX `idx_job_approved` (`approved`);

-- Afficher les colonnes des tables pour vérifier
SHOW COLUMNS FROM `candidate`;
SHOW COLUMNS FROM `job`;
