-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 22 Avril 2014 à 11:08
-- Version du serveur: 5.5.34-0ubuntu0.13.04.1
-- Version de PHP: 5.4.9-4ubuntu2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `WebRadioDB`
--

-- --------------------------------------------------------

--
-- Structure de la table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deezerID` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `artiste` varchar(100) NOT NULL,
  `coverURL` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `albums`
--

INSERT INTO `albums` (`id`, `deezerID`, `titre`, `artiste`, `coverURL`) VALUES
(1, 531349, 'Rodrigo Y Gabriela', 'Rodrigo y Gabriela', 'https://api.deezer.com/album/531349/image'),
(2, 6168389, 'Nothing But the Beat Ultimate', 'David Guetta', 'https://api.deezer.com/album/6168389/image'),
(3, 7112602, 'Flume: Deluxe Edition', 'Disclosure feat. Eliza Doolittle', 'https://api.deezer.com/album/7112602/image');

-- --------------------------------------------------------

--
-- Structure de la table `playlists`
--

CREATE TABLE IF NOT EXISTS `playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `playlists`
--

INSERT INTO `playlists` (`id`, `nom`, `userID`) VALUES
(1, 'admin', 1),
(2, 'electro', 2),
(3, 'guitare', 2);

-- --------------------------------------------------------

--
-- Structure de la table `playlists_tracks`
--

CREATE TABLE IF NOT EXISTS `playlists_tracks` (
  `playlistID` int(11) NOT NULL,
  `trackID` int(11) NOT NULL,
  PRIMARY KEY (`playlistID`,`trackID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `playlists_tracks`
--

INSERT INTO `playlists_tracks` (`playlistID`, `trackID`) VALUES
(1, 1),
(1, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7);

-- --------------------------------------------------------

--
-- Structure de la table `proposed_tracks`
--

CREATE TABLE IF NOT EXISTS `proposed_tracks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `trackID` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `status` enum('Proposed','Playing','Played') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `proposed_tracks`
--

INSERT INTO `proposed_tracks` (`ID`, `trackID`, `votes`, `status`) VALUES
(1, 1, 9, 'Proposed'),
(2, 11, 3, 'Proposed'),
(3, 4, 2, 'Proposed'),
(4, 8, 0, 'Proposed'),
(5, 6, -2, 'Proposed');

-- --------------------------------------------------------

--
-- Structure de la table `tracks`
--

CREATE TABLE IF NOT EXISTS `tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deezerID` int(10) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `preview` varchar(255) NOT NULL,
  `albumID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `albumID` (`albumID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `tracks`
--

INSERT INTO `tracks` (`id`, `deezerID`, `titre`, `preview`, `albumID`) VALUES
(1, 5835722, 'Tamacun', 'http://cdn-preview-c.deezer.com/stream/c9ba0c4f7cef38795d33fec61e01bf67-4.mp3', 1),
(2, 5835723, 'Diablo Rojo', 'http://cdn-preview-5.deezer.com/stream/5bd6fc2cb7a8251ced44baa300605b4e-4.mp3', 1),
(3, 5835727, 'Stairway To Heaven', 'http://cdn-preview-7.deezer.com/stream/7b6fefcf7b571b7124d6a97b4311a4da-4.mp3', 1),
(4, 5835724, 'Vikingman', 'http://cdn-preview-0.deezer.com/stream/013982415610b2527245a60bf2a9feb3-4.mp3', 1),
(5, 5835728, 'Orion', 'http://cdn-preview-4.deezer.com/stream/4203fe3d5f440c0579d4d35d054bad57-4.mp3', 1),
(6, 5835726, 'Ixtapa', 'http://cdn-preview-a.deezer.com/stream/aefd94da80c1daa512d894cf7fc0e2ec-4.mp3', 1),
(7, 5835725, 'Satori', 'http://cdn-preview-a.deezer.com/stream/a1debd087273bbca01dd7a6b6981c4ba-4.mp3', 1),
(8, 62847144, 'She Wolf (Falling to Pieces) [feat. Sia]', 'http://cdn-preview-a.deezer.com/stream/a319b6d9db783e2a480661a7e477af1f-2.mp3', 2),
(9, 62847147, 'Play Hard (feat. Ne-Yo & Akon)', 'http://cdn-preview-9.deezer.com/stream/92567dfb15c9520392eebd3f42b0c281-2.mp3', 2),
(10, 62847142, 'Titanium (feat. Sia)', 'http://cdn-preview-9.deezer.com/stream/9e7dcdbd3cc9895f605a721fe3afd95c-2.mp3', 2),
(11, 72363411, 'You & Me (Flume Remix)', 'http://cdn-preview-4.deezer.com/stream/49aab56a106d54dbf1ccc4aa88587200-1.mp3', 3),
(12, 72363388, 'Holdin On', 'http://cdn-preview-c.deezer.com/stream/cee8f7c8109d03f762ae8385462e6293-0.mp3', 3),
(13, 72363387, 'Sintra', 'http://cdn-preview-3.deezer.com/stream/3340f8bb4d017929f1347d44ff8da9dc-0.mp3', 3),
(14, 72363390, 'Sleepless', 'http://cdn-preview-0.deezer.com/stream/0830a79d06f3e5c1c5739295eca2573c-0.mp3', 3);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin'),
(2, 'user1', 'user1', 'user'),
(3, 'user2', 'user2', 'user');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
