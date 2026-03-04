-- Migration : ajout des nouvelles propriétés
-- Date : 2026-02-27

-- --------------------------------------------------------
-- Table `candidate`
-- --------------------------------------------------------

ALTER TABLE `candidate`
    ADD COLUMN `priority`            INT            NOT NULL DEFAULT 0            COMMENT 'Niveau de priorité du candidat (0 = aucune priorité)' AFTER `approved`,
    ADD COLUMN `seniority`           INT            NULL DEFAULT NULL              COMMENT 'Ancienneté du candidat en années' AFTER `priority`,
    ADD COLUMN `date_availability`   DATE           NULL DEFAULT NULL              COMMENT 'Date de disponibilité du candidat (YYYY-MM-DD)' AFTER `seniority`,
    ADD COLUMN `banned`              TINYINT(1)     NOT NULL DEFAULT 0             COMMENT 'Indique si le candidat est banni (0 = non, 1 = oui)' AFTER `date_availability`,
    ADD COLUMN `banlength`           INT            NULL DEFAULT NULL              COMMENT 'Durée du bannissement en jours (-1 = permanent, NULL = non banni)' AFTER `banned`,
    ADD COLUMN `ban_end_date`        DATE           NULL DEFAULT NULL              COMMENT 'Date de fin du bannissement (9999-12-31 = permanent)' AFTER `banlength`,
    ADD COLUMN `ban_reason`          TEXT           NULL DEFAULT NULL              COMMENT 'Raison du bannissement' AFTER `ban_end_date`,
    ADD COLUMN `start_date_priority` DATE           NULL DEFAULT NULL              COMMENT 'Date de début de la période de priorité (YYYY-MM-DD)' AFTER `ban_reason`,
    ADD COLUMN `end_date_priority`   DATE           NULL DEFAULT NULL              COMMENT 'Date de fin de la période de priorité (YYYY-MM-DD)' AFTER `start_date_priority`,
    ADD COLUMN `length_priority`     INT            NOT NULL DEFAULT 0             COMMENT 'Durée de la priorité en jours' AFTER `end_date_priority`;

-- --------------------------------------------------------
-- Table `company`
-- --------------------------------------------------------

ALTER TABLE `company`
    ADD COLUMN `banned`              TINYINT(1)     NOT NULL DEFAULT 0             COMMENT 'Indique si l\'entreprise est bannie (0 = non, 1 = oui)' AFTER `contact_id`,
    ADD COLUMN `banlength`           INT            NULL DEFAULT NULL              COMMENT 'Durée du bannissement en jours (-1 = permanent, NULL = non bannie)' AFTER `banned`,
    ADD COLUMN `ban_end_date`        DATE           NULL DEFAULT NULL              COMMENT 'Date de fin du bannissement (9999-12-31 = permanent)' AFTER `banlength`,
    ADD COLUMN `ban_reason`          TEXT           NULL DEFAULT NULL              COMMENT 'Raison du bannissement' AFTER `ban_end_date`,
    ADD COLUMN `date_creation`       DATE           NULL DEFAULT NULL              COMMENT 'Date de création de l\'entreprise (YYYY-MM-DD)' AFTER `ban_reason`;

-- --------------------------------------------------------
-- Table `job`
-- --------------------------------------------------------

ALTER TABLE `job`
    ADD COLUMN `priority`            INT            NOT NULL DEFAULT 0             COMMENT 'Niveau de priorité de l\'offre (0 = aucune priorité)' AFTER `approved`,
    ADD COLUMN `start_date_priority` DATE           NULL DEFAULT NULL              COMMENT 'Date de début de la période de priorité (YYYY-MM-DD)' AFTER `priority`,
    ADD COLUMN `end_date_priority`   DATE           NULL DEFAULT NULL              COMMENT 'Date de fin de la période de priorité (YYYY-MM-DD)' AFTER `start_date_priority`,
    ADD COLUMN `length_priority`     INT            NOT NULL DEFAULT 0             COMMENT 'Durée de la priorité en jours' AFTER `end_date_priority`;
