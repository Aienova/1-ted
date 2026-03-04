-- Table des taux de change pour les AIECoins
-- Définit combien d'AIECoins correspondent à 1 unité de chaque devise

CREATE TABLE IF NOT EXISTS `exchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) DEFAULT NULL COMMENT 'ID du secteur d\'activité (peut être NULL pour taux par défaut)',
  `country_id` int(11) DEFAULT NULL COMMENT 'ID du pays (peut être NULL pour taux par défaut)',
  `currency_code` varchar(3) NOT NULL COMMENT 'Code de la devise (EUR, USD, GBP, etc.)',
  `rate` int(11) NOT NULL COMMENT 'Nombre d\'AIECoins pour 1 unité de cette devise (toujours entier)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_exchange_activity` (`activity_id`),
  KEY `fk_exchange_country` (`country_id`),
  CONSTRAINT `fk_exchange_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_exchange_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des taux de change par défaut (sans pays/activité spécifique)
-- Taux de base : 1€ = 25 AIECoins
-- Les autres devises sont arrondies pour que les taux soient toujours en entiers

INSERT INTO `exchange` (`activity_id`, `country_id`, `currency_code`, `rate`) VALUES
-- Euro (devise de référence)
(NULL, NULL, 'EUR', 25),

-- Dollar américain (1 EUR ≈ 1.10 USD, arrondi à 23 AIECoins)
(NULL, NULL, 'USD', 23),

-- Livre sterling (1 EUR ≈ 0.85 GBP, arrondi à 30 AIECoins)
(NULL, NULL, 'GBP', 30),

-- Yen japonais (1 EUR ≈ 160 JPY, arrondi à 1 AIECoin)
(NULL, NULL, 'JPY', 1),

-- Dollar australien (1 EUR ≈ 1.65 AUD, arrondi à 15 AIECoins)
(NULL, NULL, 'AUD', 15),

-- Dollar canadien (1 EUR ≈ 1.50 CAD, arrondi à 17 AIECoins)
(NULL, NULL, 'CAD', 17),

-- Franc suisse (1 EUR ≈ 0.95 CHF, arrondi à 27 AIECoins)
(NULL, NULL, 'CHF', 27),

-- Yuan chinois (1 EUR ≈ 7.80 CNY, arrondi à 3 AIECoins)
(NULL, NULL, 'CNY', 3),

-- Couronne suédoise (1 EUR ≈ 11.50 SEK, arrondi à 2 AIECoins)
(NULL, NULL, 'SEK', 2),

-- Dollar néo-zélandais (1 EUR ≈ 1.80 NZD, arrondi à 14 AIECoins)
(NULL, NULL, 'NZD', 14);

-- Exemples de taux spécifiques par pays (à ajuster selon les besoins)
-- Exemple : Taux spécial pour la France (country_id = 1)
-- INSERT INTO `exchange` (`activity_id`, `country_id`, `currency_code`, `rate`) VALUES
-- (NULL, 1, 'EUR', 25.00);

-- Exemple : Taux spécial pour le secteur IT en France
-- INSERT INTO `exchange` (`activity_id`, `country_id`, `currency_code`, `rate`) VALUES
-- (5, 1, 'EUR', 23.00);
