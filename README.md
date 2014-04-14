Projet LTW2
===========

###User
admin : admin
user1 : user1
user2 : user2



# Base de données: `WebRadioDB`

![alt tag](https://raw.githubusercontent.com/ThibaudAV/TW2-ProjetFinal/master/schemaDB_SQL.png)

-- Structure de la table `albums`

```SQL
CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deezerID` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `artiste` varchar(100) NOT NULL,
  `coverURL` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;
```
-- --------------------------------------------------------

-- Structure de la table `playlists`
```SQL
CREATE TABLE IF NOT EXISTS `playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
```
-- --------------------------------------------------------

-- Structure de la table `playlists_tracks`
```SQL
CREATE TABLE IF NOT EXISTS `playlists_tracks` (
  `playlistID` int(11) NOT NULL,
  `trackID` int(11) NOT NULL,
  PRIMARY KEY (`playlistID`,`trackID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```
-- --------------------------------------------------------

-- Structure de la table `proposed_tracks`
```SQL
CREATE TABLE IF NOT EXISTS `proposed_tracks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `trackID` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `status` enum('Proposed','Playing','Played') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
```
-- --------------------------------------------------------

-- Structure de la table `tracks`
```SQL
CREATE TABLE IF NOT EXISTS `tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deezerID` int(10) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `preview` varchar(255) NOT NULL,
  `albumID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `albumID` (`albumID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;
```
-- --------------------------------------------------------

-- Structure de la table `users`
```SQL
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin'),
(2, 'user1', 'user1', 'user'),
(3, 'user2', 'user2', 'user');

```
