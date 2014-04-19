<div id="dz-root"></div>

<section id="player">

<img id="cover" src="" >

	<div id="controlers">

<a href="#" style="" class="fa fa-play" onclick="webPlayer.startPlaying();"></a>
<!-- <i class="fa fa-pause"></i> -->

	</div>
 <div id="slider-range-min"></div>

	<h2>Cliquer sur play</h2>
	<p class="artiste"> Ou ajouter des musiques</p>
	<div id="progressbar" ></div>
	
        <div id="chrono">/</div>
        <table id="nextTracks"></table>
</section>

<section id="playlist">

<article id="proposedTracks" class="playlist">
	<h3>Liste des musiques proposées</h3>	
	<ul class="tracks"></ul>
</article>


</section>

<section id="catalogue">

<?php if($user) {?>
<nav class="center">
	<ul>
		<li><a class="catalogueCommun" href="#">Catalogue commun</a></li>
		<li><a class="mesPlaylists" href="#">Mes playlists</a></li>
	</ul>
</nav>
<hr>
<?php } ?>

<div id="catalogueCommun">
	<?php foreach ($db->getAlbums() as $album) { ?>
	<article class="album">
		<img src="<?php echo $album->coverURL ?>" width="80" height="80">
		<h3><?php echo $album->titre ?></h3>
		de <i><?php echo $album->artiste ?>	</i>
		<ul class="tracks">
		<?php foreach($album->tracks as $track ) { ?>
		<li class="track" ><?php echo $track->titre ?><i data-id="<?php echo $track->ID ?>" class="add fa fa-plus-square"></i></li>
		<?php } ?>
		</ul>
	</article>
	<?php } ?>
</div>

<?php if( isset($user->role) and ($user->role != 'user' or $user->role != 'admin')){?>
<div id="mesPlaylists">
	<select name="playlist" class="form-field" id="selectPlaylist">
	<?php foreach($db->getUserPlaylists($db->getUser()->ID) as $playlist ) {?>
	<option value="<?php echo $playlist->ID ?>"><?php echo $playlist->nom ?></option>
	<?php } ?>
	</select>
	<div id="playlists">
	</div>
</div>
<?php } ?>
</section>



<script>


// Init du lecteur Radio
	var webPlayer = null;



	$(document).ready(function(){


		webPlayer = new WebPlayerController();
		webPlayer.init('http://developers.deezer.com/examples/channel.php');

		$("#controlers input").attr('disabled', true);
		$("#slider_seek").click(function(evt,arg){
			var left = evt.offsetX;
			console.log(evt.offsetX, $(this).width(), evt.offsetX/$(this).width());
			DZ.player.seek((evt.offsetX/$(this).width()) * 100);
		});
		$("#mesPlaylists").hide();

		$("section#catalogue").on('click', '.mesPlaylists', function() {
			$("#catalogueCommun").hide();
			$("#mesPlaylists").show();
			majplaylists($( '#selectPlaylist' ).val());
		});
		$("section#catalogue").on('click', '.catalogueCommun', function() {
			$("#mesPlaylists").hide();
			$("#catalogueCommun").show();
		});

		$("section#catalogue").on('change ','#selectPlaylist',function(){
			majplaylists(this.value);
		});

		

		function majplaylists (playlistID) {
			$.ajax({
				type: "GET",
				url: "getPlaylist",
				contentType: 'application/json',
				data: {ID: playlistID},
				dataType: 'json',
			})
			.done(function( playlist ) {

				$('#playlists').html("");
				if(playlist.tracks)
				{
					var articleHTML = "";
					articleHTML += '<article class="playlist"><ul class="tracks">';

					for(key in playlist.tracks) {
						articleHTML += '<li class="track" >'+ playlist.tracks[key].titre+'<i data-id="'+ playlist.tracks[key].ID+'" class="add fa fa-plus-square"></i></li>';
					}
					articleHTML += "</ul></article>";

					$('#playlists').append(articleHTML);
				}

			});
		}


	});  


	// function event_listener_append() {
	// 	var pre = document.getElementById('event_listener');
	// 	var line = [];
	// 	for (var i = 0; i < arguments.length; i++) {
	// 		line.push(arguments[i]);
	// 	}
	// 	// pre.innerHTML += line.join(' ') + "\n";
	// }
	// function onPlayerLoaded() {
	// 	$("#controlers input").attr('disabled', false);
	// 	event_listener_append('player_loaded');
	// 	DZ.Event.subscribe('current_track', function(arg){
	// 		event_listener_append('current_track', arg.index, arg.track.title, arg.track.album.title);
	// 	});
	// 	DZ.Event.subscribe('player_position', function(arg){
	// 		event_listener_append('position', arg[0], arg[1]);
	// 		$("#slider_seek").find('.bar').css('width', (100*arg[0]/arg[1]) + '%');
	// 	});
	// }
	// DZ.init({
	// 	appId  : '8',
	// 	channelUrl : 'http://developers.deezer.com/examples/channel.php',
	// 	player : {
	// 		onload : onPlayerLoaded
	// 	}
	// });
</script>