

function WebPlayerController() {

	this.webPlayerServer = new WebPlayerClient();
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
					_this.onPlayerLoaded();
					_this.startPlaying();
					_this.updatePlaylist();
					// _this.updatePlayer();

				} // function

			} // object

		});


	} // function



	this.onPlayerLoaded = function() {
		// this.event_listener_append('player_loaded');
		_this = this;
	 	var nextProposedTrack = false;

	 	DZ.Event.subscribe('player_position', function(arg){
			// this.event_listener_append('position', arg[0], arg[1]);
			// $("#slider_seek .bar").css('width', (100*arg[0]/arg[1]) + '%');
			var pourcentage = (100*arg[0]/arg[1]);
			$( "#progressbar" ).progressbar( "value", pourcentage);
			var current_sec = Math.round(arg[0]) % 60;
			current_sec = (current_sec < 10) ? "0" + current_sec : current_sec;
			var current_min = Math.floor(Math.round(arg[0]) / 60);
			var total_sec = arg[1] % 60;
			total_sec = (total_sec < 10) ? "0" + total_sec : total_sec;
			var total_min = Math.floor(arg[1] / 60);
			$("#chrono").text(current_min + "\"" + current_sec + " / " + total_min + "\"" + total_sec);

			// console.log(Math.round(pourcentage));
			if(Math.round(pourcentage)>=95 && nextProposedTrack == false) 
			{
				_this.addPlayNextProposedTrack();
				nextProposedTrack = true;
			} else if (Math.round(pourcentage)<=5 && nextProposedTrack == true) 
			{
				nextProposedTrack = false;
			}

		});

		DZ.Event.subscribe('current_track', function(track, evt_name){
			_this.updatePlayer();
			console.log('current_track');
		});


		$( "#progressbar" ).progressbar({
			value: false
		});
		$("#slider-range-min").slider({
			orientation: "vertical",
			range: "min",
			value: 80,
			min: 0,
			max: 100,
			slide: function(event, ui) {
				DZ.player.setVolume(ui.value);
			}
		});
		$("section#catalogue").on('click','.add',function(){
			var trackID = $( this ).attr("data-id");
			_this.addProposedTrack(trackID);
		});


		$("article#proposedTracks").on('click','.vote',function(){
			var vote = $( this ).attr("data-vote");
			var proposalID = $( this ).attr("data-ID");
			_this.addProposedTrackVote(vote , proposalID);
		});
		// on actualise toutes les 5s
		setInterval(function() {
			_this.updatePlaylist();
			// console.log('coucou');
		}, 5000);
	}

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

	this.addPlayNextProposedTrack = function() {
		var currentTrackProposalID = this.currentTrack.proposalID;
		var nextTrack = this.webPlayerServer.getNextTrack( currentTrackProposalID );

		if(nextTrack != null) {
		var track = this.getTrackInformation(nextTrack.trackID);

			this.isPlaying = true;
			DZ.player.addToQueue([track.deezerID]);
			this.currentTrack = nextTrack;
			console.log('addPlayNextProposedTrack :'+nextTrack);
			this.updatePlaylist();

		} else {
			this.isPlaying = false;
			// DZ.player.pause();
			// <<<------ Insert a Wait HERE
		}
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
		console.log(proposedTrack);

		// this.updatePlayer();

	} // function


	this.updatePlayer = function() {
		var ct = this.currentTrack

		if(ct){
			var trackAlbumInformation = this.getTrackAlbumInformation(ct.trackID);
			var trackInformation = this.getTrackInformation(ct.trackID);


			console.log(trackAlbumInformation);
			$('#player h2').html(trackInformation.titre);
			$('#player p.artiste').html(trackAlbumInformation.artiste);
			$('#player img#cover').attr('src', trackAlbumInformation.coverURL);
		} else {


			$('#player p.artiste').html("");
			$('#player h2').html("Ajouter des musiques a la radio");
		}



	}
	this.updatePlaylist = function() {
		var proposedTracks = this.webPlayerServer.getProposedTracks();

		console.log('updatePlaylist');
		$('#proposedTracks ul.tracks').html("");


		for(key in proposedTracks) {

			var trackInfo = this.getTrackInformation(proposedTracks[key].trackID);

			$('#proposedTracks ul.tracks').append('<li class="track" id="'+proposedTracks[key].trackID+'" >'+
				trackInfo.titre+
				'<i data-ID="'+proposedTracks[key].proposalID+'" data-vote="-1" class="vote fa fa-thumbs-o-down"></i>'+
				'<i data-ID="'+proposedTracks[key].proposalID+'" data-vote="1" class="vote fa fa-thumbs-o-up">'+proposedTracks[key].numberOfVotes+'</i> </li>');
		}

/*

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
*/
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

	this.getTrackAlbumInformation = function(trackID) {

		var trackAlbumInformation = null;

		$.ajax({
			url:  "getTrackAlbum/" + trackID,
			type: "GET",
			async: false,
			success: function(response) {
				trackAlbumInformation = JSON.parse(response);
			},
			error: function(xhr) {
				console.log("Error when calling WebPlayerDB.getTrack()");
			}
		});

		return trackAlbumInformation;

	} // function


	this.addProposedTrack = function(trackID) {

		this.webPlayerServer.proposeTrack(trackID);
		this.updatePlaylist();

	} // function

	this.addProposedTrackVote = function( _vote, _proposalID) {

		this.webPlayerServer.addProposedTrackVote(_vote , _proposalID);
		this.updatePlaylist();

	} // function
} // function
