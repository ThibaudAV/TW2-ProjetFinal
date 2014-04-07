<?php


class Like {
    
    var $ID;
    var $nbLikes;
    var $track;

    function init($ID, $nbLikes, $track) {

        $this->ID = $ID;
        $this->nbLikes = $nbLikes;
        $this->track = $track;

    } // function

    
} // class