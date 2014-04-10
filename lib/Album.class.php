<?php


class Album {
    
    var $ID;
    var $deezerID;
    var $titre;
    var $artiste;
    var $coverURL;
    var $tracks = array();

    function init($ID, $deezerID, $titre, $artiste, $coverURL) {

        $this->ID = $ID;
        $this->deezerID = $deezerID;
        $this->titre = $titre;
        $this->artiste = $artiste;
        $this->coverURL = $coverURL;

    } // function


    // public function __get($attr)
    // {
    //     if(isset($this->$attr)) return $this->$attr;
    //     else throw new Exception('Unknow attribute '.$attr);
    // }
    
    // public function __set($attr,$value)
    // {
    //     if(isset($this->$attr)) $this->$attr = $value;
    //     else throw new Exception('Unknow attribute '.$attr);
    // }

    
} // class