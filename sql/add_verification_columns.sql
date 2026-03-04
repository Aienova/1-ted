-- Script pour ajouter les colonnes de vérification d'email à la table account
-- Exécutez ce script dans phpMyAdmin ou via la ligne de commande MySQL

USE `c2248065c_1-ted_france`;

-- Ajouter la colonne verification_code
ALTER TABLE `account` 
ADD COLUMN `verification_code` varchar(12) DEFAULT NULL COMMENT 'Code de vérification email (12 caractères)' AFTER `forget_code`;

-- Ajouter la colonne verification_expire_date
ALTER TABLE `account` 
ADD COLUMN `verification_expire_date` datetime DEFAULT NULL COMMENT 'Date d\'expiration du code de vérification' AFTER `verification_code`;

-- Ajouter la colonne verified
ALTER TABLE `account` 
ADD COLUMN `verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indique si l\'email a été vérifié' AFTER `verification_expire_date`;

-- Vérifier que les colonnes ont été ajoutées
DESCRIBE `account`;
