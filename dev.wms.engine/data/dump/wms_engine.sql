-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 22 mars 2021 à 17:36
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 7.3.25

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

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20201228210702', '2021-03-22 17:28:56', 4681);

-- --------------------------------------------------------

--
-- Structure de la table `wms_file`
--

CREATE TABLE `wms_file` (
  `id` int(11) NOT NULL,
  `fl_extension` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fl_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fl_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fl_nature` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wms_role`
--

CREATE TABLE `wms_role` (
  `id` int(11) NOT NULL,
  `rl_name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rl_description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `wms_role`
--

INSERT INTO `wms_role` (`id`, `rl_name`, `rl_description`) VALUES
(1, 'Superadmin', NULL),
(2, 'Utilisateur', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `wms_user`
--

CREATE TABLE `wms_user` (
  `id` int(11) NOT NULL,
  `wms_file_id` int(11) DEFAULT NULL,
  `wms_role_id` int(11) DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_firstname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usr_lastname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_date_create` datetime DEFAULT NULL,
  `usr_date_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `wms_user`
--

INSERT INTO `wms_user` (`id`, `wms_file_id`, `wms_role_id`, `email`, `usr_firstname`, `usr_lastname`, `enabled`, `roles`, `password`, `usr_date_create`, `usr_date_update`) VALUES
(1, NULL, 1, 'superadmin@moteur.mg', 'Moteur', 'ADMIN', 1, '[\"ROLE_SUPERADMIN\"]', '$2y$13$8Ecc9ZQaqxqet4TRXayv.uaymI6GmAe71kNx3.Ev472nf5.wdTMbG', '2021-03-22 17:35:48', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `wms_file`
--
ALTER TABLE `wms_file`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `wms_role`
--
ALTER TABLE `wms_role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `wms_user`
--
ALTER TABLE `wms_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B2F444E5E7927C74` (`email`),
  ADD KEY `IDX_B2F444E5D308C134` (`wms_file_id`),
  ADD KEY `IDX_B2F444E596C09AF4` (`wms_role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `wms_file`
--
ALTER TABLE `wms_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `wms_role`
--
ALTER TABLE `wms_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `wms_user`
--
ALTER TABLE `wms_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `wms_user`
--
ALTER TABLE `wms_user`
  ADD CONSTRAINT `FK_B2F444E596C09AF4` FOREIGN KEY (`wms_role_id`) REFERENCES `wms_role` (`id`),
  ADD CONSTRAINT `FK_B2F444E5D308C134` FOREIGN KEY (`wms_file_id`) REFERENCES `wms_file` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
