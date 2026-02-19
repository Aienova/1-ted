-- ============================================================
-- AJOUT DE LA COLONNE forget_code À LA TABLE ACCOUNT
-- ============================================================
-- Cette colonne stocke le code de réinitialisation du mot de passe
-- ============================================================

-- Vérifier si la colonne existe déjà avant de l'ajouter
SET @dbname = DATABASE();
SET @tablename = "account";
SET @columnname = "forget_code";

SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname)) > 0,
  "SELECT 'La colonne forget_code existe déjà' AS message",
  CONCAT("ALTER TABLE `", @tablename, "` ADD COLUMN `", @columnname, "` varchar(255) DEFAULT NULL AFTER `token`, ADD KEY `idx_forget_code` (`", @columnname, "`)")
));

PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================================
-- FIN DU SCRIPT
-- ============================================================
