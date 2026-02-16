-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : lun. 16 fév. 2026 à 14:55
-- Version du serveur : 11.3.2-MariaDB
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `batipilot_api`
--

-- --------------------------------------------------------

--
-- Structure de la table `chantier`
--

DROP TABLE IF EXISTS `chantier`;
CREATE TABLE IF NOT EXISTS `chantier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adresse` varchar(120) DEFAULT NULL,
  `copos` varchar(5) DEFAULT NULL,
  `ville` varchar(120) DEFAULT NULL,
  `date_debut_prevue` date DEFAULT NULL,
  `date_demarrage` date DEFAULT NULL,
  `date_reception` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `surface_plancher` double DEFAULT NULL,
  `surface_habitable` double DEFAULT NULL,
  `distance_depot` int(11) DEFAULT NULL,
  `temps_trajet` int(11) DEFAULT NULL,
  `coefficient` double DEFAULT NULL,
  `alerte` varchar(255) DEFAULT NULL,
  `archive` smallint(6) NOT NULL,
  `equipe_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_636F27F66D861B89` (`equipe_id`),
  KEY `IDX_636F27F619EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chantier`
--

INSERT INTO `chantier` (`id`, `adresse`, `copos`, `ville`, `date_debut_prevue`, `date_demarrage`, `date_reception`, `date_fin`, `surface_plancher`, `surface_habitable`, `distance_depot`, `temps_trajet`, `coefficient`, `alerte`, `archive`, `equipe_id`, `client_id`) VALUES
(1, 'rue des lilas', '14000', 'Caen', '2025-01-04', '2025-01-04', '2025-11-15', NULL, NULL, NULL, NULL, NULL, 1.42, NULL, 0, 1, 1),
(2, 'rue des coquelicots', '14123', 'Ifs', '2025-01-15', '2025-01-20', '2025-10-30', NULL, NULL, NULL, NULL, NULL, 1.15, NULL, 0, 2, 2),
(3, 'rue de Suède', '14680', 'Bretteville sur Laize', '2026-12-01', '2026-01-04', NULL, NULL, NULL, NULL, NULL, NULL, 1.42, NULL, 0, 1, 3),
(4, 'rue de Norvège', '14680', 'Gouvix', '2025-11-30', '2026-01-15', NULL, NULL, NULL, NULL, NULL, NULL, 1.15, NULL, 0, 2, 4),
(5, 'rue de la mer', '14500', 'Bernières sur mer', '2026-07-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.42, NULL, 0, 1, 5),
(6, 'rue de l\'océan', '14600', 'Deauville', '2026-06-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.15, NULL, 0, 2, 6);

-- --------------------------------------------------------

--
-- Structure de la table `chantier_etape`
--

DROP TABLE IF EXISTS `chantier_etape`;
CREATE TABLE IF NOT EXISTS `chantier_etape` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `val_boolean` tinyint(4) DEFAULT NULL,
  `val_integer` int(11) DEFAULT NULL,
  `val_float` double DEFAULT NULL,
  `val_text` varchar(255) DEFAULT NULL,
  `val_date` date DEFAULT NULL,
  `val_date_heure` datetime DEFAULT NULL,
  `chantier_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3B99027DD0C0049D` (`chantier_id`),
  KEY `IDX_3B99027D4A8CA2AD` (`etape_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chantier_poste`
--

DROP TABLE IF EXISTS `chantier_poste`;
CREATE TABLE IF NOT EXISTS `chantier_poste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `montant_ht` double DEFAULT NULL,
  `montant_ttc` double DEFAULT NULL,
  `montant_fournitures` double DEFAULT NULL,
  `nb_jours_travailles` double DEFAULT NULL,
  `montant_prestataire` double DEFAULT NULL,
  `nom_prestataire` varchar(120) DEFAULT NULL,
  `chantier_id` int(11) NOT NULL,
  `poste_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6F4F780BD0C0049D` (`chantier_id`),
  KEY `IDX_6F4F780BA0905086` (`poste_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chantier_poste`
--

INSERT INTO `chantier_poste` (`id`, `montant_ht`, `montant_ttc`, `montant_fournitures`, `nb_jours_travailles`, `montant_prestataire`, `nom_prestataire`, `chantier_id`, `poste_id`) VALUES
(1, 0, 0, 0, 0, NULL, NULL, 1, 1),
(2, 5225.23, 6250, 856.23, 2.5, 0, NULL, 1, 2),
(3, 1115.3, 1338.36, 355.2, 4.5, 975, 'Entreprise Martin', 1, 3),
(4, 1340, 1608, 385.5, 5, 1100.75, 'Société Dupont', 1, 4),
(5, 1210.6, 1452.72, 362.8, 5.5, 1010.4, 'Groupe Moreau', 1, 5),
(6, 1425.25, 1710.3, 402, 6, 1205.5, 'SARL Petit', 1, 6),
(7, 1260, 1512, 375.25, 5, 1075.6, 'Entreprise Richard', 1, 7),
(8, 1385.4, 1662.48, 392.5, 5.5, 1150.8, 'Construction Bernard', 1, 8),
(9, 1460.8, 1752.96, 425.75, 6, 1250.25, 'Bâtiments Roux', 1, 9),
(10, 1575.6, 1890.72, 455, 7, 1360.9, 'SARL Gautier', 1, 10),
(11, 1105.5, 1326.6, 325.8, 5.5, 1010.4, 'Entreprise Lefevre', 2, 1),
(12, 1230.75, 1476.9, 345.6, 6, 1120.2, 'Bâtiment Petit', 2, 2),
(13, 1315.4, 1578.48, 370.5, 4.5, 1205.75, 'SARL Roux', 2, 3),
(14, 1420.25, 1704.3, 390.75, 5, 1290.5, 'Construction Durand', 2, 4),
(15, 1510.6, 1812.72, 410, 6.5, 1365.2, 'Entreprise Martin', 2, 5),
(16, 1625.8, 1950.96, 430.25, 7, 1470.5, 'Groupe Dupont', 2, 6),
(17, 1275.25, 1530.3, 380.5, 5, 1105.75, 'SARL Bernard', 2, 7),
(18, 1395.6, 1674.72, 395.8, 5.5, 1175.6, 'Bâtiment Moreau', 2, 8),
(19, 1485.9, 1783.08, 420, 6, 1275.25, 'Construction Richard', 2, 9),
(20, 1580.5, 1896.6, 450.25, 7, 1375.8, 'Entreprise Gautier', 2, 10),
(21, 1120.75, 1344.9, 330.5, 5.5, 1020.25, 'SARL Lefevre', 3, 1),
(22, 2563, 1500.48, 350.75, 6, 1130.6, 'Bâtiment Petit', 3, 2),
(23, 5500.25, 1602.36, 375.2, 4.5, 1210, 'Entreprise Roux', 3, 3),
(24, 1440, 1728, 395.5, 5, 1305.75, 'Construction Durand', 3, 4),
(25, 1530.6, 1836.72, 415.8, 6.5, 1380.4, 'Groupe Martin', 3, 5),
(26, 1645.25, 1974.3, 435, 7, 1490.5, 'SARL Dupont', 3, 6),
(27, 1290, 1548, 385.25, 5, 1115.6, 'Bâtiments Bernard', 3, 7),
(28, 1410.4, 1692.48, 400.5, 5.5, 1185.8, 'Construction Moreau', 3, 8),
(29, 1500.8, 1800.96, 425.75, 6, 1285.25, 'Entreprise Richard', 3, 9),
(30, 1605.6, 1926.72, 455, 7, 1385.9, 'Bâtiment Gautier', 3, 10),
(31, 1150.5, 1380.6, 335.8, 5, 1030.4, 'Construction Lefevre', 4, 1),
(32, 1275.75, 1530.9, 355.6, 6.5, 1140.2, 'SARL Petit', 4, 2),
(33, 1355.4, 1626.48, 380.5, 4.5, 1225.75, 'Bâtiment Roux', 4, 3),
(34, 1460.25, 1752.3, 400.75, 5, 1310.5, 'Entreprise Durand', 4, 4),
(35, 1550.6, 1860.72, 420, 6, 1390.2, 'Groupe Martin', 4, 5),
(36, 1665.8, 1998.96, 440.25, 7, 1500.5, 'SARL Dupont', 4, 6),
(37, 1315.25, 1578.3, 390.5, 5, 1125.75, 'Construction Bernard', 4, 7),
(38, 1435.6, 1722.72, 405.8, 5.5, 1195.6, 'Bâtiment Moreau', 4, 8),
(39, 1525.9, 1830.08, 430, 6, 1295.25, 'Entreprise Richard', 4, 9),
(40, 1620.5, 1944.6, 460.25, 7, 1395.8, 'Construction Gautier', 4, 10),
(41, 1160.75, 1392.9, 340.5, 5.5, 1040.25, 'SARL Lefevre', 5, 1),
(42, 1285.4, 1542.48, 360.75, 6, 1150.6, 'Bâtiment Petit', 5, 2),
(43, 1365.3, 1638.36, 385.2, 4.5, 1230, 'Entreprise Roux', 5, 3),
(44, 1470, 1764, 405.5, 5, 1325.75, 'Construction Durand', 5, 4),
(45, 1560.6, 1872.72, 425.8, 6.5, 1400.4, 'Groupe Martin', 5, 5),
(46, 1675.25, 2004.3, 445, 7, 1510.5, 'SARL Dupont', 5, 6),
(47, 1320, 1584, 395.25, 5, 1135.6, 'Bâtiments Bernard', 5, 7),
(48, 1440.4, 1728.48, 410.5, 5.5, 1205.8, 'Construction Moreau', 5, 8),
(49, 1530.8, 1836.96, 435.75, 6, 1305.25, 'Entreprise Richard', 5, 9),
(50, 1635.6, 1962.72, 465, 7, 1405.9, 'Bâtiment Gautier', 5, 10),
(51, 1170.5, 1404.6, 345.8, 5, 1050.4, 'Construction Lefevre', 6, 1),
(52, 1295.75, 1554.9, 365.6, 6.5, 1160.2, 'SARL Petit', 6, 2),
(53, 1375.4, 1650.48, 390.5, 4.5, 1245.75, 'Bâtiment Roux', 6, 3),
(54, 1480.25, 1776.3, 410.75, 5, 1330.5, 'Entreprise Durand', 6, 4),
(55, 1570.6, 1884.72, 430, 6, 1410.2, 'Groupe Martin', 6, 5),
(56, 1685.8, 2018.96, 450.25, 7, 1520.5, 'SARL Dupont', 6, 6),
(57, 1335.25, 1602.3, 400.5, 5, 1145.75, 'Construction Bernard', 6, 7),
(58, 1455.6, 1746.72, 415.8, 5.5, 1215.6, 'Bâtiment Moreau', 6, 8),
(59, 1550.9, 1860.08, 440, 6, 1315.25, 'Entreprise Richard', 6, 9),
(60, 1650.5, 1980.6, 470.25, 7, 1415.8, 'Construction Gautier', 6, 10);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(80) NOT NULL,
  `prenom` varchar(80) DEFAULT NULL,
  `telephone` varchar(14) DEFAULT NULL,
  `mail` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `telephone`, `mail`) VALUES
(1, 'Floranger', 'Denise', NULL, NULL),
(2, 'Rosier', 'Marthe', NULL, NULL),
(3, 'M. Lepays', 'Luc', '02020202', 'luc.lepays@gmail.com'),
(4, 'Mme Eloigne', 'Linda', '06080900405', 'linda.eloigne@gmail.com'),
(5, 'Homard', 'Karima', '0506080405', 'karima.homard@gmail.com'),
(6, 'Poisson', 'Carine', '0808090808', 'carine.poisson@free.fr');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260210172600', '2026-02-10 17:26:11', 223);

-- --------------------------------------------------------

--
-- Structure de la table `equipe`
--

DROP TABLE IF EXISTS `equipe`;
CREATE TABLE IF NOT EXISTS `equipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(120) NOT NULL,
  `coefficient` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `equipe`
--

INSERT INTO `equipe` (`id`, `nom`, `coefficient`) VALUES
(1, 'Equipe charpente traditionnelle', 1.42),
(2, 'Equipe charpente fermette', 1.15);

-- --------------------------------------------------------

--
-- Structure de la table `etape`
--

DROP TABLE IF EXISTS `etape`;
CREATE TABLE IF NOT EXISTS `etape` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(80) DEFAULT NULL,
  `archive` smallint(6) NOT NULL,
  `etape_format_id` int(11) DEFAULT NULL,
  `poste_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_285F75DDFB3A43EA` (`etape_format_id`),
  KEY `IDX_285F75DDA0905086` (`poste_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etape_format`
--

DROP TABLE IF EXISTS `etape_format`;
CREATE TABLE IF NOT EXISTS `etape_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etape_format`
--

INSERT INTO `etape_format` (`id`, `libelle`) VALUES
(1, 'oui ou non'),
(2, 'nombre entier'),
(3, 'nombre décimal'),
(4, 'texte'),
(5, 'date'),
(6, 'date et heure');

-- --------------------------------------------------------

--
-- Structure de la table `poste`
--

DROP TABLE IF EXISTS `poste`;
CREATE TABLE IF NOT EXISTS `poste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(80) NOT NULL,
  `tva` double DEFAULT NULL,
  `equipe` smallint(6) DEFAULT NULL,
  `prestataire` smallint(6) DEFAULT NULL,
  `ordre` smallint(6) DEFAULT NULL,
  `archive` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `poste`
--

INSERT INTO `poste` (`id`, `libelle`, `tva`, `equipe`, `prestataire`, `ordre`, `archive`) VALUES
(1, 'pré-chantier', 1, 0, 0, 1, 0),
(2, 'charpente', 5.5, 1, 0, 2, 0),
(3, 'couverture', 5.5, 1, 0, NULL, 0),
(4, 'Maçonnerie', NULL, 0, 1, NULL, 0),
(5, 'Escalier et balustrade', 5.5, 0, 1, NULL, 0),
(6, 'Platrerie et isolation', 5.5, 1, 0, NULL, 0),
(7, 'Platrerie et cloison', 5.5, 1, 0, NULL, 0),
(8, 'Electricité et chauffage', 5.5, 0, 1, NULL, 0),
(9, 'Plomberie', 5.5, 0, 1, NULL, 0),
(10, 'Revêtement', 10, 0, 1, NULL, 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chantier`
--
ALTER TABLE `chantier`
  ADD CONSTRAINT `FK_636F27F619EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_636F27F66D861B89` FOREIGN KEY (`equipe_id`) REFERENCES `equipe` (`id`);

--
-- Contraintes pour la table `chantier_etape`
--
ALTER TABLE `chantier_etape`
  ADD CONSTRAINT `FK_3B99027D4A8CA2AD` FOREIGN KEY (`etape_id`) REFERENCES `etape` (`id`),
  ADD CONSTRAINT `FK_3B99027DD0C0049D` FOREIGN KEY (`chantier_id`) REFERENCES `chantier` (`id`);

--
-- Contraintes pour la table `chantier_poste`
--
ALTER TABLE `chantier_poste`
  ADD CONSTRAINT `FK_6F4F780BA0905086` FOREIGN KEY (`poste_id`) REFERENCES `poste` (`id`),
  ADD CONSTRAINT `FK_6F4F780BD0C0049D` FOREIGN KEY (`chantier_id`) REFERENCES `chantier` (`id`);

--
-- Contraintes pour la table `etape`
--
ALTER TABLE `etape`
  ADD CONSTRAINT `FK_285F75DDA0905086` FOREIGN KEY (`poste_id`) REFERENCES `poste` (`id`),
  ADD CONSTRAINT `FK_285F75DDFB3A43EA` FOREIGN KEY (`etape_format_id`) REFERENCES `etape_format` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
