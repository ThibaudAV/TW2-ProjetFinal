<?php


require_once("WebPlaylistDB.class.php");
require_once(__DIR__ . "/../models/ProposedTrack.class.php");


class WebPlayerServer {

    var $db;

    public function __construct() {

        $this->db = new WebPlaylistDB();

    } // constructor


    //////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  Public methods
    //
    //////////////////////////////////////////////////////////////////////////////////////////////


    public function getCurrentTrack() {

        $proposedTracks = $this->db->getProposedTracks("Playing");
        $currentTrack = null;

        if( count($proposedTracks) > 0)
            $currentTrack = array_pop($proposedTracks);

        return $currentTrack;

    } // function


    public function getProposedTracks() {

        return $this->db->getProposedTracks("Proposed");

    } // function


    public function getNextTrack($previousTrackProposalID) {

        $nextTrack = null;

        ///
        // Case 1: The WebPlayer is not playing a track but there is a "proposed track" waiting
        ///
        if( !$this->isPlayingTrack() && $this->hasTrackToPlay() ) {
            $this->playNextProposedTrack();
            $nextTrack = $this->getCurrentTrack();
        }

        ///
        // Case 2: The WebPlayer is playing a track. Determine whether the WebPlayer has to stop the "current track"
        ///
        else {

            $currentTrack = $this->getCurrentTrack();

            ///
            // Case 2.1: First time this user asks for the “next track"... there is no need to stop the "current track"
            ///
            if($previousTrackProposalID == null)
                $nextTrack = $currentTrack;

            // Case 2.2: This user is the "first user" that ends listening the "current track". Play "next track" (if possible)
            elseif($previousTrackProposalID == $currentTrack->proposalID) {

                $this->stopPlayingCurrentTrack();

                if( $this->hasTrackToPlay() )
                    $this->playNextProposedTrack();

                $nextTrack = $this->getCurrentTrack();

            // Case 2.3: This user is listening a previous song. Synchronize with "first user"
            } else {
                $nextTrack = $this->getCurrentTrack();

            }
        }

        return $nextTrack;

    } // function


    public function proposeTrack($trackID) {
        $this->db->addProposedTrack($trackID);
    } // function


    public function discardProposedTracks() {
        $this->db->deleteProposedTracks();
    } // function

    public function addTrackVote($vote,$proposalID) {
        if($vote >= 1){
            $this->db->addTrackVote(1,$proposalID);
        } else {
            $this->db->addTrackVote(-1,$proposalID);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  Private methods (i.e., only accessible to instances of the WebPlayerServer class)
    //
    //////////////////////////////////////////////////////////////////////////////////////////////

    /***
     *
     * Determines whether there is a track in the player' playlist or not (i.e. whether
     * there is a "track" marked as "Proposed" in the database.
     *
     * @return bool
     */
    private function hasTrackToPlay() {

        $proposedTracks = $this->getProposedTracks();

        if( count($proposedTracks) == 0 ) {
            return false;
        }

        return true;

    } // function

    /***
     *
     * Determines whether the WebPlayer is playing a track or not (i.e. whether
     * there is a "track" marked as "Playing" in the database.
     *
     * @return bool
     */
    private function isPlayingTrack() {

        $currentTrack = $this->getCurrentTrack();

        if($currentTrack == null) {
            return false;
        }

        return true;

    } // function

    /***
     *
     * Plays the next track of the playlist (i.e., marks the next "proposed track"
     * in the playlist as currently "Playing").
     *
     */
    private function playNextProposedTrack() {

        $proposedTracks = $this->getProposedTracks();
        $nextTrack = array_values($proposedTracks)[0];

        $this->db->updateProposedTrackWithStatus($nextTrack->proposalID, "Playing");

    } // function

    /***
     *
     * Stops the track that is being played (i.e., it (i) obtains the "track" marked as "Playing"
     * from the database and (ii) marks it as "Played").
     */
    private function stopPlayingCurrentTrack() {

        $currentTrack = $this->getCurrentTrack();
        $this->db->updateProposedTrackWithStatus($currentTrack->proposalID, "Played");

    } // function


} // class

