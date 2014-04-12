<?php

class ProposedTrack {

    var $proposalID;
    var $trackID;
    var $numberOfVotes;

    function init($proposalID, $trackID, $numberOfVotes) {

        $this->proposalID = $proposalID;
        $this->trackID = $trackID;
        $this->numberOfVotes = $numberOfVotes;

    } // function

} // class