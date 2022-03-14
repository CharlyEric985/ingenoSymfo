-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 13 mars 2022 à 22:54
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `wms_engine`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220313224941', '2022-03-13 22:49:55', 7110);

-- --------------------------------------------------------

--
-- Structure de la table `wms_code`
--

DROP TABLE IF EXISTS `wms_code`;
CREATE TABLE IF NOT EXISTS `wms_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `wms_code`
--

INSERT INTO `wms_code` (`id`, `code`) VALUES
(1, '101'),
(2, '501');

-- --------------------------------------------------------

--
-- Structure de la table `wms_dirigeant`
--

DROP TABLE IF EXISTS `wms_dirigeant`;
CREATE TABLE IF NOT EXISTS `wms_dirigeant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dirigeant_last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dirigeant_first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_91FB250E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `wms_dirigeant`
--

INSERT INTO `wms_dirigeant` (`id`, `dirigeant_last_name`, `dirigeant_first_name`, `email`, `sexe`) VALUES
(1, 'Test', 'P test', 'test@gmail.com', 'f');

-- --------------------------------------------------------

--
-- Structure de la table `wms_file`
--

DROP TABLE IF EXISTS `wms_file`;
CREATE TABLE IF NOT EXISTS `wms_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fl_extension` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fl_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fl_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fl_nature` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wms_role`
--

DROP TABLE IF EXISTS `wms_role`;
CREATE TABLE IF NOT EXISTS `wms_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rl_name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rl_description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wms_societe`
--

DROP TABLE IF EXISTS `wms_societe`;
CREATE TABLE IF NOT EXISTS `wms_societe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `societe_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `types` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `wms_societe`
--

INSERT INTO `wms_societe` (`id`, `societe_name`, `description`, `types`, `code_postal`, `ville`) VALUES
(1, 'Soc test', 'Desc test', 'SARL/SELARL', '1', '3');

-- --------------------------------------------------------

--
-- Structure de la table `wms_user`
--

DROP TABLE IF EXISTS `wms_user`;
CREATE TABLE IF NOT EXISTS `wms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wms_file_id` int(11) DEFAULT NULL,
  `wms_role_id` int(11) DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_firstname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_lastname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_date_create` datetime DEFAULT NULL,
  `usr_date_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B2F444E5E7927C74` (`email`),
  KEY `IDX_B2F444E5D308C134` (`wms_file_id`),
  KEY `IDX_B2F444E596C09AF4` (`wms_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wms_ville`
--

DROP TABLE IF EXISTS `wms_ville`;
CREATE TABLE IF NOT EXISTS `wms_ville` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_id` int(11) DEFAULT NULL,
  `nom_ville` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9C9C519227DAFE17` (`code_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `wms_ville`
--

INSERT INTO `wms_ville` (`id`, `code_id`, `nom_ville`) VALUES
(1, 1, 'Antananarivo'),
(2, 2, 'Toamasina'),
(3, 1, 'Tanjombato');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `wms_user`
--
ALTER TABLE `wms_user`
  ADD CONSTRAINT `FK_B2F444E596C09AF4` FOREIGN KEY (`wms_role_id`) REFERENCES `wms_role` (`id`),
  ADD CONSTRAINT `FK_B2F444E5D308C134` FOREIGN KEY (`wms_file_id`) REFERENCES `wms_file` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `wms_ville`
--
ALTER TABLE `wms_ville`
  ADD CONSTRAINT `FK_9C9C519227DAFE17` FOREIGN KEY (`code_id`) REFERENCES `wms_code` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
