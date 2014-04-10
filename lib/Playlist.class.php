<?php


class Playlist {
    
    var $ID;
    var $nom;
    var $userID;
    var $tracks;

    function __construct($ID, $nom, $userID) {

        $this->ID = $ID;
        $this->nom = $nom;
        $this->userID = $userID;

    } // function


    
} // class