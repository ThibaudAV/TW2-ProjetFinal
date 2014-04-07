<?php

	include("lib/WebPlaylistDB.class.php");

	$db = new WebPlaylistDB();



?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>TW2 - Projet</title>
	<link rel="stylesheet" type="text/css" href="media/css/style.css">

<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

	<script src="http://cdn-files.deezer.com/js/min/dz.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
</head>
<body>
<div id="dz-root"></div>

<div id="contenaire">
	<header>
		<div id="titre">
			<h1><i class="fa fa-music"></i> MyPlay</h1>
			<h2>Créé vos playlists </h2>
		</div>
 
		<nav>
			<ul>
				<li><a href="index.php">Playlist</a></li>
				<li><a href="catalogue.php" class="select">Catalogue</a></li>
			</ul>
		</nav>
	</header>
	<section>
		<div id="c1">
			<h2>Ajouter des musique</h2>
			<input type="text" class="form-field" id="search" onkeyup="search();" placeholder="Rechercher" autocomplete="off" disabled/>
			<ul id="results">
				Taper votre recherche
			</ul>
			
		</div>
		<div id="c2">
			<h2>Mon Catalogue</h2>
			<ul id="catalogues">
			<?php
				$tracks = $db->getTracks();
				while( $track = array_pop($tracks) ) {
					// $response = json_decode(file_get_contents("http://api.deezer.com/track/".$track->deezerID));
					// echo $track->titre."<br>";
					// var_dump($response);
					echo "<li>";
						echo '<img width="80" height="80" class="inline" id="cover_image" src="'.$track->album_cover.'" style="opacity: 1;">';
						echo '<div data-id="'.$track->album_cover.'" class="miniPlayer" id="idMP_'.$track->id.'"> ';
						echo '</div><div class="addCatalogue">';
						echo '<span class="titre">'.$track->titre.'</span>';
						echo '<span class="album">'.$track->album.'</span> de ';
						echo '<span class="artist">'.$track->artist.'</span></div>';
						echo '<a href="#" data-id="'.$track->id.'" class="suppr fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-times fa-stack-1x fa-inverse"></i></a>';
					echo '</li>';

				
				}
			?>
				
			</ul>
		</div>

	</section>



</div>
<script>
		function initDivMP () {
			

			$( "div.miniPlayer" ).on({
				mouseenter: function() {
					var track = $( this ).attr("data-id");
					console.log("miniPlayer mouseenter");
					if(DZ.player.getCurrentTrack()){
						if(DZ.player.getCurrentTrack().id == track) {
							// $(this).addClass('on');
							if(DZ.player.isPlaying())
							{
								$(this).find("i.fa-play").removeClass('fa-play').addClass('fa-pause');
								// $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pause fa-stack-1x fa-inverse"></i></span>');
							} else {
								$(this).find("i.fa-pause").removeClass('fa-pause').addClass('fa-play');
								// $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>');
							}
						} else {
								// $(this).find("i.fa-play").removeClass('fa-pause').addClass('fa-play');
							$(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>');
						}
					} else {
								// $(this).find("i.fa-play").removeClass('fa-pause').addClass('fa-play');
						$(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>');
					}
				},mouseleave: function() {
					var track = $( this ).attr("data-id");
					console.log("miniPlayer mouseleave");
					if(DZ.player.getCurrentTrack()){
						if(DZ.player.getCurrentTrack().id == track) {
							if(DZ.player.isPlaying())
							{
								$(this).find("i.fa-pause").removeClass('fa-pause').addClass('fa-play');
								// $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>');
							} else {
								$(this).find("i.fa-play").removeClass('fa-play').addClass('fa-pause');
								
								// $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pause fa-stack-1x fa-inverse"></i></span>');
							}
						} else {
							$(this).html('');
						}
					} else {
						$(this).html('');
					}
				},'click': function(){
					var track = $( this ).attr("data-id");
					// playOnClick($( this ).attr("data-id"));
					console.log("miniPlayer click");

					if(DZ.player.getCurrentTrack()){
						if(DZ.player.getCurrentTrack().id == track) {
							if(DZ.player.isPlaying())
							{
								DZ.player.pause();
								$(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>');
							} else {
								DZ.player.play();
								$(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pause fa-stack-1x fa-inverse"></i></span>');
							}
						} else {
							$( "div.miniPlayer" ).html('');
							$('.miniPlayer').removeClass('on');
							$(this).addClass('on');
							DZ.player.playTracks([track]);
							$(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pause fa-stack-1x fa-inverse"></i></span>');
						}
					} else {
						$( "div.miniPlayer" ).html('');
						$('.miniPlayer').removeClass('on');
						$(this).addClass('on');
						DZ.player.playTracks([track]);
						$(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pause fa-stack-1x fa-inverse"></i></span>');
					}
				}
			});
				
			$( ".addCatalogue" ).click(function() {
					var track_id = $( this ).attr("data-id");

					DZ.api('/track/'+track_id, function(json){
							track_titre = json.title;
							track_preview = json.preview ;
							track_album = json.album.title;
							track_album_cover = json.album.cover;
							track_artist = json.artist.name;

						var jqxhr = $.post( "lib/trackAjax.php", { action: "add", id: track_id, titre: track_titre, preview: track_preview,album: track_album,album_cover:track_album_cover,artist:track_artist }, function(res) {

							if(res.type == 'success') {
								$('#catalogues').append('<li>' + 
								'<img width="80" height="80" class="inline" id="cover_image" src="'+json.album.cover+'" style="opacity: 1;">'+
								'<div data-id="'+json.id+'" class="miniPlayer" id="idMP_'+json.id+'"> '+
								''+
								'</div><div class="addCatalogue" data-id="'+json.id+'">' +
								'<span class="titre">'+json.title+'</span>' +
								'<span class="album">'+json.album.title+'</span> de ' +
								'<span class="artist">'+json.artist.name+'</span></div>'+
								'<a href="#" data-id="'+json.id+'" class="suppr fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-times fa-stack-1x fa-inverse"></i></a>'
								);
							}
							$('#infoBule').remove();
							$('body').append('<div id="infoBule" class="'+res.type+'"><span class="fermer"><i class="fa fa-times"></i></span>'+res.message+'</div>');
							

						})
						.fail(function() {
						});

					});

				});


		}
		initDivMP();

$(document).ready(function() {
		$(document).on('click','a.suppr',function(){
		// $( "a.suppr" ).click(function() {
			console.log('ze');
			var track_id = $( this ).attr("data-id");
			var jqxhr = $.post( "lib/trackAjax.php", { action: "suppr", id: track_id}, function(res) {
				$('a.suppr[data-id='+track_id+']').parent().remove();
							$('#infoBule').remove();
							$('body').append('<div id="infoBule" class="'+res.type+'"><span class="fermer"><i class="fa fa-times"></i></span>'+res.message+'</div>');
							
			})
			.fail(function() {
				alert( "error" );
			});

		});
		$(document).on('click','#infoBule span.fermer',function(){
		// $( "#infoBule span.fermer" ).click(function() {
			$(this).parent().remove();
		});
});


		function search(){
			var search = $('#search').val();
			DZ.api('/search?q='+search, function(json){
				$('#results').text("");
				for (var i=0, len = json.data.length; i<len ; i++)
				{
				  $('#results').append('<li>' + 
					'<img width="80" height="80" class="inline" id="cover_image" src="'+json.data[i].album.cover+'" style="opacity: 1;">'+
					'<div data-id="'+json.data[i].id+'" class="miniPlayer" id="idMP_'+json.data[i].id+'"> '+
					''+
					'</div><div class="addCatalogue" data-id="'+json.data[i].id+'">' +
					'<span class="titre">'+json.data[i].title+'</span>' +
					'<span class="album">'+json.data[i].album.title+'</span> de ' +
					'<span class="artist">'+json.data[i].artist.name+'</span></div>'
					);
				  DZ.player.pause();
				}
				initDivMP();
			});

		}

		DZ.init({
			appId  : '134001',
			channelUrl : 'channel.php',
			player : {
				onload : function(){
					$("input#search").attr('disabled', false);
				}
			}
		});
	</script>
</body>
</html>