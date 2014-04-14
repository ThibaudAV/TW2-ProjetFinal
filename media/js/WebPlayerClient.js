

////////////////////////////////////////////////////////////////////////////////////
//
// This class encapsulates ALL the logic used for interacting with the application server-side
// using AJAX
//
////////////////////////////////////////////////////////////////////////////////////


function WebPlayerClient(HTTP_HOST) {


    this.getCurrentTrack = function() {

        var currentTrack = null;
        $.ajax({
            url:  HTTP_HOST+"/radio/currentTrack",
            type: "GET",
            async: false,
            success: function(response) {
                currentTrack = JSON.parse(response);
            },
            error: function(xhr) {
                console.log("Error when calling WebPlayerServer.getCurrentTrack()");
            }
        });

        return currentTrack;

    } // function


    this.getProposedTracks = function() {

        var proposedTracks = new Array();

        $.ajax({
            url:  HTTP_HOST+"/radio/proposedTracks",
            type: "GET",
            async: false,
            success: function(response) {
                proposedTracks = JSON.parse(response);
            },
            error: function(xhr) {
                console.log("Error when calling WebPlayerServer.getProposedTracks()");
            }
        });

        return proposedTracks;

    } // function


    this.getNextTrack = function(previousTrackProposalID) {

        var nextTrack = null;

        $.ajax({
            url:  HTTP_HOST+"/radio/nextTrack",
            type: "POST",
            data: { "previousTrackProposalID" : previousTrackProposalID },
            async: false,
            success: function(response) {
                nextTrack = JSON.parse(response);
            },
            error: function(xhr) {
                console.log("Error when calling WebPlayerServer.getNextTrack()");
            }
        });


        return nextTrack;

    } // function


    this.proposeTrack = function(trackID) {

        var proposedTrack = { trackID : trackID };

        $.ajax({
            url:  HTTP_HOST+"/radio/proposedTracks",
            type: "PUT",
            contentType: "application/json",
            data: JSON.stringify(proposedTrack),
            async: false,
            success: function(response) {
                console.log("Proposed Song: " + trackID );
            },
            error: function(xhr) {
                console.log("Error when calling WebPlayerServer.proposeTrack()");
            }
        });


    } // function


    this.addProposedTrackVote = function(_vote,_proposalID) {

        var proposedTrack = { proposalID : _proposalID, vote : _vote };

        $.ajax({
            url:  HTTP_HOST+"/radio/addProposedTrackVote",
            type: "PUT",
            contentType: "application/json",
            data: JSON.stringify(proposedTrack),
            async: false,
            success: function(response) {
                console.log("Vote "+_vote+" Song: " + _proposalID );
            },
            error: function(xhr) {
                console.log("Error when calling WebPlayerServer.proposeTrack()");
            }
        });


    } // function


} // function
