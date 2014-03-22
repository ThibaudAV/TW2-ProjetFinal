<?php


include("Playlist.class.php");
include("Catalogue.class.php");
include("Track.class.php");


class WebPlaylistDB {

    const DB_SERVER   = "localhost";
    const DB_USER     = "root";
    const DB_PASSWORD = "root";
    const DB_NAME     = "WebPlayerDB";
    
    var $db;

    public function __construct() {

        $this->db = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB_NAME)
            or die("MySQL connection failure: " . $this->db->connect_error);;

    } // constructor
    


    function getCatalogue() {

    }










    function getAlbums() {

        $albums = array();

        $sql = "SELECT SQL_NO_CACHE * " .
               "FROM   Albums; ";

        $result = $this->db->query($sql);

        while( $row = $result->fetch_array() ) {

            $album = new Album();
            $album->init( $row['ID'], $row['Title'], $row['Artist'], $row['CoverURL'] );
            $album->tracks = $this->getAlbumTracks($album->ID);

            array_push($albums, $album);

        } // while

       return $albums;
       
    } // function

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
    
    
} // class