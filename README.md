Projet LTW2
===========
####Mathilde Maltaire & Thibaud Avenier & Emmanuel Carré - WIC


###Instructions
* Pour PHP `>= 5.4` 
* Activer les extensions `php_pdo_mysql`  
* Ajouter la BDD  
* Modifier `lib/WebPlaylistDB.class.php` avec vos identifiants mysql
* Etre connecter a deezer

###Creation de la base de données
Le fichier /db/WebRadioDB.sql permet de créer et remplir la base de données



![alt tag](https://raw.githubusercontent.com/ThibaudAV/TW2-ProjetFinal/master/schemaDB_SQL.png)

```sql
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Structure de la table `playlists`
--

CREATE TABLE IF NOT EXISTS `playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Structure de la table `playlists_tracks`
--

CREATE TABLE IF NOT EXISTS `playlists_tracks` (
  `playlistID` int(11) NOT NULL,
  `trackID` int(11) NOT NULL,
  PRIMARY KEY (`playlistID`,`trackID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

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

```


###Ajouter des utilisateurs et un admin  
* admin : admin [admin]
* user1 : user1 [user]
* user2 : user2 [user]


```sql
INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin'),
(2, 'user1', 'user1', 'user'),
(3, 'user2', 'user2', 'user');
```








