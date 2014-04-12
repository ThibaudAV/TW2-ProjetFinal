

function WebPlayerController(HTTP_HOST) {

    this.webPlayerServer = new WebPlayerClient(HTTP_HOST);
    this.appID = "112661";

    this.currentTrack = null;
    this.isPlaying = false;

    



    this.init = function(_channelUrl) {

        _this = this;

        DZ.init({
            appId  : this.appID,
            channelUrl : _channelUrl,
            player: {
                // container: 'player',
                // width : 800,
                // height : 300,
                onload : function(){

                    //_this.startPlaying();
                    //_this.updatePlaylist();

                } // function

            } // object

        });

    } // function


    this.startPlaying = function() {

        var currentTrack = this.webPlayerServer.getCurrentTrack();

        if(currentTrack != null) {
            this.playProposedTrack(currentTrack);

        } else {

            var proposedTracks = this.webPlayerServer.getProposedTracks();

            if(proposedTracks.length != 0) {
                var nextTrack = this.webPlayerServer.getNextTrack();
                this.playProposedTrack(nextTrack);

            } else {

                this.currentTrack = null;
                this.isPlaying = false;
                // <<<------ Insert a Wait HERE

            } // else

        } // else

    } // function


    this.playNextProposedTrack = function() {

        var currentTrackProposalID = this.currentTrack.proposalID;
        var nextTrack = this.webPlayerServer.getNextTrack( currentTrackProposalID );

        if(nextTrack != null) {
            this.playProposedTrack(nextTrack);

        } else {
            this.isPlaying = false;
            DZ.player.pause();
            // <<<------ Insert a Wait HERE
        }

    } // function


    this.playProposedTrack = function(proposedTrack) {

        var track = this.getTrackInformation(proposedTrack.trackID);

        DZ.player.playTracks( [ track.deezerID ] );

        this.isPlaying = true;
        this.currentTrack = proposedTrack;

    } // function


    this.updatePlaylist = function() {

        var proposedTracks = this.webPlayerServer.getProposedTracks();

        var table = document.createElement('table');
        table.id = "proposedTracks";

        for(var i=0; i < proposedTracks.length; i++) {

            var proposedTrack = proposedTracks[i];
            var trackInfo = this.getTrackInformation(proposedTrack.trackID);

            var row  = document.createElement('tr');
            var col1 = document.createElement('td');
            var col2 = document.createElement('td');
            var col3 = document.createElement('td');

            var txt1 = document.createTextNode(proposedTrack.trackID);
            var txt2 = document.createTextNode(trackInfo.title);
            var txt3 = document.createTextNode(trackInfo.deezerID);

            col1.appendChild(txt1);
            col2.appendChild(txt2);
            col3.appendChild(txt3);

            row.appendChild(col1);
            row.appendChild(col2);
            row.appendChild(col3);

            table.appendChild(row);

        } // for

        var playlist = document.getElementById("player");
        var tracks = document.getElementById("proposedTracks");

        playlist.replaceChild(table, tracks);

    } // function


    this.getTrackInformation = function(trackID) {

        var trackInformation = null;

        $.ajax({
            url:  "getTrack/" + trackID,
            type: "GET",
            async: false,
            success: function(response) {
                trackInformation = JSON.parse(response);
            },
            error: function(xhr) {
                console.log("Error when calling WebPlayerDB.getTrack()");
            }
        });

        return trackInformation;

    } // function


} // function
