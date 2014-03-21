<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>TW2 - Projet</title>
	<link rel="stylesheet" type="text/css" href="media/css/style.css">


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
			<input type="text" id="search" name="search" onkeypress="search();"/>
			<ul id="results">
				<li>
					<img width="80" height="80" class="inline" id="cover_image" src="https://api.deezer.com/album/4491721/image" style="opacity: 1;">
					<span class="titre">Je N'veux Pas </span>
					<span class="album">1er Album</span>
				</li>

				<li>
					<img width="80" height="80" class="inline" id="cover_image" src="https://api.deezer.com/album/4491721/image" style="opacity: 1;">
					<span class="titre">Je N'veux Pas Rester Sage</span>
					<span class="album">1er Album</span>
				</li>
			</ul>
			
		</div>
		<div id="c2">
			<h2>Mon Catalogue</h2>
			
		</div>

	</section>



</div>
	<script>
		DZ.init({
			appId  : '122865',
			channelUrl : 'http://external.codecademy.com/channel.html',
		});

        function search(){
            DZ.api('/search?q='+$('#search').val(), function(json){
            	$('#results').text("Chargement ... !");
				for (var i=0, len = json.data.length; i<len ; i++)
				{
			      $('#results').append('<li>' + json.data[i].title + ' - ' + json.data[i].album.title + '</li>');
				}
			});
        }
	</script>
</body>
</html>