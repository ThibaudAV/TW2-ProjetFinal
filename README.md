

# BDD 

--
-- Structure de la table `albums`
--

```SQL
CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deezerID` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `artiste` varchar(100) NOT NULL,
  `coverURL` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;
```
-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

```SQL
CREATE TABLE IF NOT EXISTS `likes` (
  `nombre` int(11) NOT NULL,
  `id_track` int(11) NOT NULL,
  `id_playlist` int(11) NOT NULL,
  PRIMARY KEY (`id_track`,`id_playlist`),
  KEY `id_playlist` (`id_playlist`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```
-- --------------------------------------------------------

--
-- Structure de la table `playlists`
--

```SQL
CREATE TABLE IF NOT EXISTS `playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
```
-- --------------------------------------------------------

--
-- Structure de la table `tracks`
--

```SQL
CREATE TABLE IF NOT EXISTS `tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deezerID` int(10) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `preview` varchar(255) NOT NULL,
  `albumID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `albumID` (`albumID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;
```
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `likes`
--
```SQL
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_playlist`) REFERENCES `playlists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_3` FOREIGN KEY (`id_track`) REFERENCES `tracks` (`id`);
```
--
-- Contraintes pour la table `tracks`
--
```SQL
ALTER TABLE `tracks`
  ADD CONSTRAINT `tracks_ibfk_1` FOREIGN KEY (`albumID`) REFERENCES `albums` (`id`);
```