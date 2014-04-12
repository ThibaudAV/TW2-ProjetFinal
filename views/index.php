<div id="dz-root"></div>

<section id="player">

<img id="cover" src="https://api.deezer.com/album/7518196/image" >

	<div id="controlers">

<a href="#" style="" class="fa fa-play" onclick="webPlayer.startPlaying();"></a>
<!-- <i class="fa fa-pause"></i> -->

	</div>
	<h2>Dans ma paranoïa</h2>
	<p class="artiste">Sexion d'Assaut</p>
		<div id="slider_seek" class="progressbarplay" style="">
		<div class="bar" style="width: 0%;"></div>
	</div>
	
        <table id="proposedTracks"></table>
</section>

<section id="playlist">

<article class="playlist">
	<h3>Blalzkadsd</h3>	
	<ul class="tracks">
	<li class="track" >qsdcqsdf qsdfqsd <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdfqsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdcqsdf qsdfqsd <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdfqsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdcqsdf qsdfqsd lirem qsdf qsd  qsd  qsssqdd qsd<i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdfqsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdcqsdf qsdfqsd <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdfqsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdcqsdf qsdfqsd <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdfqsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	<li class="track" >qsdf qsdf <i class="vote fa fa-thumbs-o-up"></i> <i class="vote fa fa-thumbs-o-down"></i></li>
	</ul>
</article>



	<?php if( isset($user->role) and ($user->role != 'user' or $user->role != 'admin')){
			foreach ($db->getUserPlaylists($user->ID) as $playlist) {
			?>
			<h2><?php echo $playlist->nom; ?></h2>

			<?php
		}
	}
	?>
</section>

<section>

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
		<li class="track" ><?php echo $track->titre ?><i class="add fa fa-plus-square"></i></li>
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

	window.onload = function() {
		webPlayer = new WebPlayerController('<?php echo $_SERVER['HTTP_HOST'];?>');
		webPlayer.init('http://developers.deezer.com/examples/channel.php');
	}






	$(document).ready(function(){
		$("#controlers input").attr('disabled', true);
		$("#slider_seek").click(function(evt,arg){
			var left = evt.offsetX;
			console.log(evt.offsetX, $(this).width(), evt.offsetX/$(this).width());
			DZ.player.seek((evt.offsetX/$(this).width()) * 100);
		});
		$("#mesPlaylists").hide();

		$(document).on('click', '.mesPlaylists', function() {
			$("#catalogueCommun").hide();
			$("#mesPlaylists").show();
			majplaylists($( '#selectPlaylist' ).val());
		});
		$(document).on('click', '.catalogueCommun', function() {
			$("#mesPlaylists").hide();
			$("#catalogueCommun").show();
		});

		$(document).on('change ','#selectPlaylist',function(){
			majplaylists(this.value);
		});


		function majplaylists (playlistID) {
			$.ajax({
				type: "GET",
				url: "<?php echo $_SERVER['HTTP_HOST'];?>/getPlaylist",
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