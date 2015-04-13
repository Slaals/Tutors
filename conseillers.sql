-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 14 Mai 2014 à 21:40
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `conseillers`
--
CREATE DATABASE IF NOT EXISTS `conseillers` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `conseillers`;

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE IF NOT EXISTS `compte` (
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `id_statut` tinyint(4) NOT NULL,
  PRIMARY KEY (`login`),
  KEY `id_statut` (`id_statut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `compte`
--

INSERT INTO `compte` (`login`, `password`, `id_statut`) VALUES
('drh', '147de4c9d38de7fc9029aafbf0cc25a1', 2),
('respISI', 'bd86bced84fb3aef951fb07de8c533c7', 1),
('respMTE', 'bd86bced84fb3aef951fb07de8c533c7', 1),
('respSI', 'bd86bced84fb3aef951fb07de8c533c7', 1),
('respSIT', 'bd86bced84fb3aef951fb07de8c533c7', 1),
('respSM', 'bd86bced84fb3aef951fb07de8c533c7', 1),
('respSRT', 'bd86bced84fb3aef951fb07de8c533c7', 1),
('respTC', 'bd86bced84fb3aef951fb07de8c533c7', 1),
('scol', '0edc047e1c7b53cd3e0c7e05bd3cff91', 3);

-- --------------------------------------------------------

--
-- Structure de la table `conseiller`
--

CREATE TABLE IF NOT EXISTS `conseiller` (
  `id_enseignant_chercheur` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  PRIMARY KEY (`id_etudiant`),
  KEY `id_enseignant_chercheur` (`id_enseignant_chercheur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `conseiller`
--

INSERT INTO `conseiller` (`id_enseignant_chercheur`, `id_etudiant`) VALUES
(83, 4142),
(85, 4456),
(86, 45221),
(91, 1);

-- --------------------------------------------------------

--
-- Structure de la table `enseignant_chercheur`
--

CREATE TABLE IF NOT EXISTS `enseignant_chercheur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pole` tinyint(4) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `bureau` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pole` (`id_pole`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=98 ;

--
-- Contenu de la table `enseignant_chercheur`
--

INSERT INTO `enseignant_chercheur` (`id`, `id_pole`, `nom`, `prenom`, `bureau`) VALUES
(83, 1, 'LEMERCIER', 'Marc', 'T122'),
(85, 2, 'BIRREGAH', 'Babiga', 'H107'),
(86, 2, 'KENS', 'Osvald', 'H021'),
(87, 1, 'BENEL', 'Aurelien', 'T114'),
(88, 2, 'BEJON', 'Nathan', 'T121'),
(89, 1, 'MASSOT', 'Carl', 'T400'),
(90, 2, 'HASSARD', 'Emile', 'H121'),
(91, 1, 'ASOPOV', 'Arnold', 'T100'),
(92, 2, 'SIMON', 'Nicole', 'T050'),
(93, 1, 'NIKOS', 'Ulric', 'T312'),
(94, 2, 'JENVAL', 'Philippes', 'T120'),
(95, 3, 'MAUSIN', 'Philippe', 'T312-B'),
(96, 1, 'CORPEL', 'Alain', 'T114'),
(97, 2, 'PAUL', 'José', 'G154');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE IF NOT EXISTS `etudiant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_programme` tinyint(4) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `semestre` tinyint(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_programme` (`id_programme`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45224 ;

--
-- Contenu de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `id_programme`, `nom`, `prenom`, `semestre`) VALUES
(1, 5, 'VOL', 'Ross', 48),
(4142, 5, 'CHAUVIN', 'Romain', 12),
(4456, 1, 'MARTIN', 'Jean', 4),
(45221, 4, 'JOSSEL', 'Maris', 5);

-- --------------------------------------------------------

--
-- Structure de la table `habilitation`
--

CREATE TABLE IF NOT EXISTS `habilitation` (
  `id_enseignant_chercheur` int(11) NOT NULL,
  `id_programme` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_enseignant_chercheur`,`id_programme`),
  KEY `id_programme` (`id_programme`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `habilitation`
--

INSERT INTO `habilitation` (`id_enseignant_chercheur`, `id_programme`) VALUES
(83, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(86, 4),
(94, 4),
(83, 5),
(85, 5),
(86, 5),
(87, 5),
(88, 5),
(89, 5),
(90, 5),
(91, 5),
(92, 5),
(93, 5),
(94, 5),
(95, 5);

-- --------------------------------------------------------

--
-- Structure de la table `liste_pole`
--

CREATE TABLE IF NOT EXISTS `liste_pole` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `liste_pole`
--

INSERT INTO `liste_pole` (`id`, `libelle`) VALUES
(1, 'HETIC'),
(2, 'ROSAS'),
(3, 'P2MN'),
(4, 'SUEL');

-- --------------------------------------------------------

--
-- Structure de la table `liste_programme`
--

CREATE TABLE IF NOT EXISTS `liste_programme` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `liste_programme`
--

INSERT INTO `liste_programme` (`id`, `libelle`) VALUES
(1, 'TC'),
(2, 'ISI'),
(3, 'SRT'),
(4, 'MTE'),
(5, 'SI'),
(6, 'SIT'),
(7, 'SM');

-- --------------------------------------------------------

--
-- Structure de la table `liste_statut`
--

CREATE TABLE IF NOT EXISTS `liste_statut` (
  `id` tinyint(4) NOT NULL,
  `libelle` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `liste_statut`
--

INSERT INTO `liste_statut` (`id`, `libelle`) VALUES
(1, 'responsable_programme'),
(2, 'directeur_ressources_humaine'),
(3, 'service_scolarite');

-- --------------------------------------------------------

--
-- Structure de la table `resp_programme`
--

CREATE TABLE IF NOT EXISTS `resp_programme` (
  `identifiant` varchar(50) NOT NULL,
  `id_programme` tinyint(4) NOT NULL,
  PRIMARY KEY (`identifiant`),
  KEY `id_programme` (`id_programme`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `resp_programme`
--

INSERT INTO `resp_programme` (`identifiant`, `id_programme`) VALUES
('respTC', 1),
('respISI', 2),
('respSRT', 3),
('respMTE', 4),
('respSI', 5),
('respSIT', 6),
('respSM', 7);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `compte`
--
ALTER TABLE `compte`
  ADD CONSTRAINT `compte_ibfk_1` FOREIGN KEY (`id_statut`) REFERENCES `liste_statut` (`id`),
  ADD CONSTRAINT `compte_ibfk_2` FOREIGN KEY (`id_statut`) REFERENCES `liste_statut` (`id`);

--
-- Contraintes pour la table `conseiller`
--
ALTER TABLE `conseiller`
  ADD CONSTRAINT `conseiller_ibfk_2` FOREIGN KEY (`id_enseignant_chercheur`) REFERENCES `enseignant_chercheur` (`id`),
  ADD CONSTRAINT `conseiller_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id`);

--
-- Contraintes pour la table `enseignant_chercheur`
--
ALTER TABLE `enseignant_chercheur`
  ADD CONSTRAINT `enseignant_chercheur_ibfk_1` FOREIGN KEY (`id_pole`) REFERENCES `liste_pole` (`id`),
  ADD CONSTRAINT `enseignant_chercheur_ibfk_2` FOREIGN KEY (`id_pole`) REFERENCES `liste_pole` (`id`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`id_programme`) REFERENCES `liste_programme` (`id`),
  ADD CONSTRAINT `etudiant_ibfk_2` FOREIGN KEY (`id_programme`) REFERENCES `liste_programme` (`id`);

--
-- Contraintes pour la table `habilitation`
--
ALTER TABLE `habilitation`
  ADD CONSTRAINT `habilitation_ibfk_1` FOREIGN KEY (`id_enseignant_chercheur`) REFERENCES `enseignant_chercheur` (`id`),
  ADD CONSTRAINT `habilitation_ibfk_2` FOREIGN KEY (`id_programme`) REFERENCES `liste_programme` (`id`),
  ADD CONSTRAINT `habilitation_ibfk_3` FOREIGN KEY (`id_enseignant_chercheur`) REFERENCES `enseignant_chercheur` (`id`),
  ADD CONSTRAINT `habilitation_ibfk_4` FOREIGN KEY (`id_programme`) REFERENCES `liste_programme` (`id`);

--
-- Contraintes pour la table `resp_programme`
--
ALTER TABLE `resp_programme`
  ADD CONSTRAINT `resp_programme_ibfk_1` FOREIGN KEY (`identifiant`) REFERENCES `compte` (`login`),
  ADD CONSTRAINT `resp_programme_ibfk_2` FOREIGN KEY (`id_programme`) REFERENCES `liste_programme` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
