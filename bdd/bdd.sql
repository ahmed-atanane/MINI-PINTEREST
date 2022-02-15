-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 07 mai 2021 à 21:48
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
-- Base de données : `root`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `catId` int(11) NOT NULL AUTO_INCREMENT,
  `nomCat` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`catId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`catId`, `nomCat`) VALUES
(1, 'Anime'),
(2, 'Nature'),
(3, 'Astronomie');

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `photoId` int(11) NOT NULL AUTO_INCREMENT,
  `nomFich` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `catId` int(11) DEFAULT NULL,
  `usrId` int(11) DEFAULT NULL,
  `state` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`photoId`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`photoId`, `nomFich`, `description`, `catId`, `usrId`, `state`) VALUES
(19, 'DSC1.png', 'Texture de neige', 2, 2, 1),
(20, 'DSC20.png', 'Koro Sensei', 1, 2, 1),
(21, 'DSC21.png', 'Une map de mini golf.', 2, 2, 1),
(22, 'DSC22.png', 'Une autre map de golf.', 3, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(10) UNSIGNED NOT NULL DEFAULT '2',
  `pseudo` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `etat` varchar(255) NOT NULL,
  `connectedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `roleId`, `pseudo`, `mdp`, `etat`, `connectedOn`) VALUES
(2, 2, 'sonia', '1234', 'disconnected', '2021-05-07 21:33:15'),
(12, 2, 'ahmed', '1234', 'disconnected', NULL),
(11, 1, 'admin2', '1234', 'disconnected', '2021-05-07 19:54:36'),
(10, 1, 'newadmin', '1234', 'disconnected', '2021-05-07 20:15:23');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
