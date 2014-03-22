<?php


class Track {
    
    var $ID;
    var $deezerID;
    var $title;
    var $preview;

    public function __construct($deezerID, $title, $preview) {

        $this->deezerID = $deezerID;
        $this->title = $title;
        $this->preview = $preview;

    } // constructor

    function init($ID, $deezerID, $title, $preview) {

        $this->ID = $ID;
        $this->deezerID = $deezerID;
        $this->title = $title;
        $this->preview = $preview;

    } // function
    
} // class