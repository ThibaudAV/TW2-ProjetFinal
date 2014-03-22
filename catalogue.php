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
			<h1>MyPlay</h1>
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
			<input type="text" class="form-field" id="search" onkeyup="search();" placeholder="Rechercher" autocomplete="off"/>
			<ul id="results">
				<li onmouseover="">
					<img width="80" height="80" class="inline" id="cover_image" src="https://api.deezer.com/album/4491721/image" style="opacity: 1;">
					<div class="miniPlayer">
						<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>
					</div>
					<span class="titre">Je N'veux Pas </span>
					<span class="album">1er Album</span>

				</li>

				<li onclick="">
					<img width="80" height="80" class="inline" id="cover_image" src="https://api.deezer.com/album/4491721/image" style="opacity: 1;">
					<span class="titre">Je N'veux Pas Rester Sage</span>
					<span class="album">1er Album</span>
					de
				</li>
			</ul>
			
		</div>
		<div id="c2">
			<h2>Mon Catalogue</h2>
			
		</div>

	</section>



</div>
	<script>

		function initDivMP () {
			$( "div.miniPlayer.on" ).on({
				   mouseenter: function() {
					  $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-pause fa-stack-1x fa-inverse"></i></span>');
				  }, mouseleave: function() {
					  $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>');
				  }
				});
				$( "div.miniPlayer:not('.on')" ).on({
				   mouseenter: function() {
					  $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>');
				  }, mouseleave: function() {
					  $(this).html('');
				  }
				});
		}

        function search(){
        	var search = $('#search').val();
            DZ.api('/search?q='+search, function(json){
            	$('#results').text("");
				for (var i=0, len = json.data.length; i<len ; i++)
				{
			      $('#results').append('<li>' + 
			      	'<img width="80" height="80" class="inline" id="cover_image" src="'+json.data[i].album.cover+'" style="opacity: 1;">'+
			      	'<div onclick="playOnClick('+json.data[i].id+'); return false;" class="miniPlayer" id="idMP_'+json.data[i].id+'"> '+
			      	''+
			      	'</div><div class="addCatalogue">' +
			      	'<span class="titre">'+json.data[i].title+'</span>' +
			      	'<span class="album">'+json.data[i].album.title+'</span> de ' +
			      	'<span class="artist">'+json.data[i].artist.name+'</span></div>'
					);
			      DZ.player.pause();
			      initDivMP();
				}
			});

        }
        function playOnClick (track) {
        	var newPlay=false;
        	if(DZ.player.getCurrentTrack()){
		        if(DZ.player.getCurrentTrack().id == track) {
		        	console.log(DZ.player.isPlaying());
		        	if(DZ.player.isPlaying())
		        	{
		        		DZ.player.pause();
		        	}
		        	else 
		        	{
		        		DZ.player.play();
		        	}
		        } else {
		        	newPlay=true;
		        }
        	} else {
        		newPlay=true;
        	}

			if (newPlay) {
	        		DZ.player.playTracks([track]);

	        	$('.miniPlayer').html('');
	        	$('.miniPlayer').removeClass('on');

	        	$('#idMP_'+track).addClass('on');
	        	$('#idMP_'+track).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-play fa-stack-1x fa-inverse"></i></span>');
	    
	        	initDivMP();
			};

        }

		DZ.init({
			appId  : '134001',
			channelUrl : 'channel.php',
			player : {
				onload : function(){

				}
			}
		});
	</script>
</body>
</html>