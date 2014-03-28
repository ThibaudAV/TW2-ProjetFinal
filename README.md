

# BDD 

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
```
-- --------------------------------------------------------

--
-- Structure de la table `tracks`
--
```SQL
CREATE TABLE IF NOT EXISTS `tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `deezerID` int(11) NOT NULL,
  `preview` text NOT NULL,
  `album` varchar(255) NOT NULL,
  `album_cover` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;
```
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `likes`
--
```SQL
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_track`) REFERENCES `tracks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_playlist`) REFERENCES `playlists` (`id`) ON DELETE CASCADE;
```