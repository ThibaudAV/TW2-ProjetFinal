<?php


include("Playlist.class.php");
include("Album.class.php");
include("Track.class.php");
include("Like.class.php");


class WebPlaylistDB {

    const DB_SERVER   = "localhost";
    const DB_USER     = "root";
    const DB_PASSWORD = "aout91";
    const DB_NAME     = "WebPlaylistDB";
    
    var $db;

    public function __construct() {

        // $this->db = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB_NAME)
        //     or die("MySQL connection failure: " . $this->db->connect_error);;

        try
        {
            $this->db = new PDO('mysql:host='.self::DB_SERVER.';dbname='.self::DB_NAME, self::DB_USER, self::DB_PASSWORD);
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }


    } // constructor
    
    /* **********************************
    *  *********** Track
    *  **********************************/

    public function ifTrackExist($deezerID)
    {
        $req = $this->db->prepare("SELECT * from tracks where deezerID = :deezerID");
        $req->bindParam(":deezerID", $deezerID);
        $req->execute();
        $track=$req->fetch(PDO::FETCH_OBJ);

        if ($req->errorInfo()[1]) {
            var_dump($req->errorInfo());
            return false;
        }

        if($req->rowCount() > 0)
            return $track->id;
        
        return false;
    }

    public function getTracks() {
        $tracks = array();

        $reponse = $this->db->query("SELECT * FROM tracks");
        while ($row = $reponse->fetch())
        {
            $track = new Track( null, null, null);
            $track->init($row['id'], $row['deezerID'], $row['titre'], $row['preview']);
            array_push($tracks, $track);
        }
        return $tracks;
    }

    public function getTrack($ID) {

        $reponse=$this->db->prepare("SELECT * FROM tracks WHERE id = :id"); // on prépare notre requête
        $reponse->execute(array( 'id' => $ID ));
        $track=$reponse->fetch(PDO::FETCH_OBJ);

        return $track;
    }
    public function getAlbumTracks($albumID) {

        $tracks = array();
        $reponse = $this->db->query("SELECT * FROM albums as album, tracks as track WHERE album.id = track.albumID AND album.id = ".$albumID);
        while ($row = $reponse->fetch())
        {
            $track = new Track(null, null, null);
            $track->init( $row['id'], $row['deezerID'], $row['titre'], $row['preview'] );
            array_push($tracks, $track);
        }
        return $tracks;

    } // function

    public function addAlbumTrack($albumID,$track) {

        $req = $this->db->prepare('INSERT INTO tracks(deezerID, titre, preview, albumID) VALUES(:deezerID, :titre, :preview, :albumID)');
        $req->execute(array(
            'deezerID' => $track->deezerID,
            'titre' => $track->titre,
            'preview' => $track->preview,
            'albumID' => $albumID
            ));
        if ($req->errorInfo()[1]) {
            var_dump($req->errorInfo());
            return false;
        } else {
            return "La musique \"".$track->titre."\" a été ajouté";            
        }

    } // function

    public function removeTrack($id)
    {
        if ($this->db->exec("DELETE FROM tracks WHERE id = '".$id."'")) {
            return "La musique a bien été supprimé.";
        }

        return false;
    }
    public function removeAlbumTrack($albumID) {

        if ($this->db->exec("DELETE FROM tracks WHERE albumID = '".$albumID."'")) {
            return true;
        }
        return false;

    } // function

    /* **********************************
    *  *********** Albums
    *  **********************************/
    
    public function ifAlbumExist($deezerID)
    {
        $req = $this->db->prepare("SELECT * from albums where deezerID = :deezerID");
        $req->bindParam(":deezerID", $deezerID);
        $req->execute();
        $album=$req->fetch(PDO::FETCH_OBJ);

        if ($req->errorInfo()[1]) {
            var_dump($req->errorInfo());
            return false;
        }

        if($req->rowCount() > 0)
            return $album->id;
        
        return false;
    }

    public function getAlbums() {

        $albums = array();

        $reponse = $this->db->query("SELECT * FROM albums");
        while ($row = $reponse->fetch())
        {
            $album = new Album();
            $album->init( $row['id'], $row['deezerID'], $row['titre'], $row['artiste'], $row['coverURL'] );
            $album->tracks = $this->getAlbumTracks($album->ID);
            array_push($albums, $album);
        }
       return $albums;
    } // function

    public function addAlbum($album)
    {

        $req = $this->db->prepare('INSERT INTO albums(deezerID, titre, artiste, coverURL) VALUES(:deezerID, :titre, :artiste, :coverURL)');
        $req->execute(array(
            'deezerID' => $album->deezerID,
            'titre' => $album->titre,
            'artiste' => $album->artiste,
            'coverURL' => $album->coverURL
            ));
        if ($req->errorInfo()[1]) {
            var_dump($req->errorInfo());
            return false;
        }
        $return = "<ul>";
        $return = "<li>L'album \"".$album->titre."\" a été créer</li>";

        if($album->tracks) // si il y a des musique a ajouter 
        {
            foreach ($album->tracks as $track) {
                $return .= "<li>".$this->addAlbumTrack($this->db->lastInsertId(), $track)."</li>";
            }
        }
        $return .= "</ul>";
        return $return;   
    }

    public function removeAlbum($id)
    {   
        if ($this->db->exec("DELETE FROM albums WHERE id = '".$id."'")) {
            $this->removeAlbumTrack($id);
            return "L'album a bien été supprimé.";
        }
        return false;
    }

    function updateAlbum($album) { 
        $req = $this->db->prepare('UPDATE albums SET titre = :titre, artiste = :artiste, coverURL = :coverURL WHERE deezerID = :deezerID');
        $req->execute(array(
            'deezerID' => $album->deezerID,
            'titre' => $album->titre,
            'artiste' => $album->artiste,
            'coverURL' => $album->coverURL
            ));
        if ($req->errorInfo()[1]) {
            var_dump($req->errorInfo());
            return false;
        }
        $return = "<ul>";
        $return = "<li>L'album \"".$album->titre."\" a été mis à jour</li>";
        if($album->tracks) // si il y a des musique a ajouter 
        {
            foreach ($album->tracks as $track) {
                if(!$this->ifTrackExist($track->deezerID)) {
                    // on récupére l'id de notre albums
                    $return .= "<li>".$this->addAlbumTrack($this->ifTrackExist($track->deezerID), $track)."</li>";
                }
            }
        }
        $return .= "</ul>";
        return $return;

    } // function

    /* **********************************
    *  *********** Playlists
    *  **********************************/

    public function getPlaylists()
    {
        $playlists = array();

        $reponse = $this->db->query("SELECT * FROM playlists");
        while ($row = $reponse->fetch())
        {
            $playlist = new Playlist( null, null);
            $playlist->init($row['id'], $row['nom']);
            array_push($playlists, $playlist);
        }
        return $playlists;
    }
/*





    

    function getAlbumTracks($albumID) {

        $tracks = array();

        $sql = "SELECT SQL_NO_CACHE track.ID, track.DeezerID, track.Title, track.Preview " .
               "FROM   Albums as album, Tracks as track " .
               "WHERE  album.ID = track.albumID AND " .
               "       album.ID = {$albumID}; ";

        $result = $this->db->query($sql);

        while( $row = $result->fetch_array() ) {

            $track = new Track(null, null, null);
            $track->init( $row['ID'], $row['DeezerID'], $row['Title'], $row['Preview'] );

            array_push($tracks, $track);

        } // while

        return $tracks;

    } // function

    function populate() {

        $album = new Album();
        $album->artist = "Daft Punk";
        $album->title = "TRON Legacy: Reconfigured (2011)";
        $album->coverURL = "https://api.deezer.com/album/936927/image";
        $album->tracks = array(
            new Track(67238728, 'Give Life Back to Music', 'http://cdn-preview-5.deezer.com/stream/5ee6a64ea769922abeea0549a5c91cbc-5.mp3'),
            new Track(67238729, 'The Game of Love', 'http://cdn-preview-2.deezer.com/stream/298178023291c60d0dfb5421b1c9c47f-1.mp3'),
            new Track(67238730, 'Giorgio by Moroder', 'http://cdn-preview-c.deezer.com/stream/cf3eae62911ee8f925bbfa79ed2432f2-5.mp3'),
            new Track(67238731, 'Within', 'http://cdn-preview-6.deezer.com/stream/6e9d7bfe83dd80afe37a5e8843f447f5-5.mp3'),
            new Track(67238732, 'Instant Crush', 'http://cdn-preview-0.deezer.com/stream/0f766b2312d338fd1295df91c869c4e5-5.mp3'),
            new Track(67238733, 'Lose Yourself to Dance', 'http://cdn-preview-e.deezer.com/stream/ea91cf77f4cd12ddf499465a8f94c272-5.mp3'),
            new Track(67238734, 'Touch', 'http://cdn-preview-8.deezer.com/stream/82c8d9b85e09fce8a624508890b99567-1.mp3'),
            new Track(67238735, 'Get Lucky', 'http://cdn-preview-7.deezer.com/stream/783280be6a0859be3f7050408050242e-1.mp3'),
            new Track(67238736, 'Beyond', 'http://cdn-preview-d.deezer.com/stream/d84e8400c43a3b8c548ca5ce85f3db3c-1.mp3'),
            new Track(67238737, 'Motherboard', 'http://cdn-preview-e.deezer.com/stream/ebab4779e9585c7ebfe830b074be9253-5.mp3'),
            new Track(67238738, 'Fragments of Time', 'http://cdn-preview-8.deezer.com/stream/870e7e146329d5abbf161b6606a0cf8b-1.mp3'),
            new Track(67238739, "Doin'' it Right", 'http://cdn-preview-d.deezer.com/stream/def3a5534d10660a450d7bc3df2dafac-5.mp3'),
            new Track(67238740, 'Contact', 'http://cdn-preview-b.deezer.com/stream/bd484d548a804ba44c7676092f169eca-1.mp3')
        );

        // Use AddAlbum HERE

    } // function


    function getAlbum() { } // function

    function addAlbum() { } // function

    function updateAlbum() { } // function

    function removeAlbum() { } // function


    function getTrack() { } // function

    function addTrack() { } // function

    function removeTrack() { } // function

    function updateTrack() { } // function
    */
    
} // class