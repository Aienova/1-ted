-- ============================================================
-- Ajouter la colonne avatar_url à la table candidate
-- ============================================================
-- Date: 2026-03-03
-- Description: Ajoute un champ avatar_url pour stocker l'URL de l'avatar du candidat
-- ============================================================

USE `1ted`;

-- Ajouter la colonne avatar_url après media_code
ALTER TABLE `candidate` 
ADD COLUMN `avatar_url` VARCHAR(500) NULL DEFAULT NULL 
COMMENT 'URL de l\'avatar du candidat' 
AFTER `media_code`;

-- Vérification : Afficher la structure de la table
-- DESC `candidate`;

-- Note: Cette colonne est optionnelle (NULL) et permet de stocker une URL personnalisée
-- pour l'avatar du candidat au lieu d'utiliser le media_code pour générer l'avatar.
