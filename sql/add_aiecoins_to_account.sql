-- Migration : Ajout de la colonne aiecoins à la table account
-- Date : 2026-02-26
--
-- aiecoins : crédits entiers permettant de consulter les informations des CV

ALTER TABLE `account`
  ADD COLUMN `aiecoins` int(11) NOT NULL DEFAULT 0 COMMENT 'Crédits pour consulter les CV' AFTER `forget_code`;
