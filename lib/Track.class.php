<?php


class Track {
    
    var $ID;
    var $deezerID;
    var $titre;
    var $artist;
    var $album;
    var $album_cover;


    var $preview;


    public function __construct($deezerID, $titre, $preview) {

        $this->deezerID = $deezerID;
        $this->titre = $titre;
        $this->preview = $preview;

    } // constructor

    function init($ID, $deezerID, $titre, $preview, $album, $album_cover, $artist) {

        $this->ID = $ID;
        $this->deezerID = $deezerID;
        $this->titre = $titre;
        $this->preview = $preview;
        $this->album = $album;
        $this->album_cover = $album_cover;
        $this->artist = $artist;

    } // function
    
} // class