<section id="rchPlay">
	

	<h2>Créer une playlist</h2>
	<form method="POST" id="formAddPlaylist" action="addPlaylist">
	Nom de la playlist : <input type="text" name="nomplayliste" id="">
	<input type="submit" value="Ajouter">
	</form>
</section>

<section id="Pmusique">
	<div id="dz-root"></div>
<div id="c1">
	<h2>Ajouter des musiques</h2>
	<input type="text" class="form-field" id="search" onkeyup="search();" placeholder="Rechercher" autocomplete="off" />
	<h3>Rechercher dans les albums</h3>
	<div id="resultsAlbums"></div>
	<h3>Rechercher dans les tracks</h3>
	<div id="resultsAlbumsTracks"></div>

</div>
<div id="c2">
	<h2>Mes playlists</h2>


<select name="playlist" class="form-field" id="selectPlaylist">
	<?php foreach($db->getUserPlaylists($db->getUser()->ID) as $playlist ) {?>
	<option value="<?php echo $playlist->ID ?>"><?php echo $playlist->nom ?></option>
	<?php } ?>
</select>


	<div id="catalogues">
	</div>
</div>
</section>
<script>
function initDivMP () {
	
	
	$( ".addCatalogue" ).click(function() {
		var track_id = $( this ).attr("data-id");

		DZ.api('/track/'+track_id, function(json){
			track_titre = json.title;
			track_preview = json.preview ;
			track_album = json.album.title;
			track_album_cover = json.album.cover;
			track_artist = json.artist.name;

			$.ajax({
				type: "PUT",
				url: "addAlbum",
				contentType: 'application/json',
				data: JSON.stringify({ 
					deezerID: json.album.id,
					titre: json.album.title,
					artiste: json.artist.name,
					coverURL: json.album.cover,
					tracks : [{
						deezerID: track_id,
						titre: json.title, 
						preview: json.preview,
					}]
				}),
				dataType: 'json',
			})
			.done(function( rep ) {
				if(rep.type == 'success') {
					majCatalogues();
				}
				$('#infoBule').remove();
				$('body').append('<div id="infoBule" class="'+rep.type+'"><span class="header"><i class="fermer fa fa-times"></i> Notifications :</span>'+rep.msg+'</div>');
			});

		});

	});
}

initDivMP();

$(document).ready(function() {
	$(document).on('click','.add',function(){
		var track_id = $( this ).attr("data-id");
		var playlist_ID = $( '#selectPlaylist' ).val();
		$.ajax({
			type: "PUT",
			url: "addPlaylistTrack",
			contentType: 'application/json',
			data: JSON.stringify({ 
				trackID: track_id,
				playlistID: playlist_ID,
			}),
			dataType: 'json',
		})
		.done(function( rep ) {

			if(rep.type = 'success')
			{
				majCatalogues(playlist_ID);
			}
			$('#infoBule').remove();
			$('body').append('<div id="infoBule" class="'+rep.type+'"><span class="header"><i class="fermer fa fa-times"></i> Notifications :</span>'+rep.msg+'</div>');
		})
		.fail(function() {
			alert( "error" );
		});
	});
	$(document).on('click','.supprTrack',function(){
		var track_id = $( this ).attr("data-id"); 
		var playlist_ID = $( '#selectPlaylist' ).val();

			$.ajax({
				type: "DELETE",
				url: "supprPlaylistTrack",
				contentType: 'application/json',
				data: JSON.stringify({ 
					trackID: track_id,
					playlistID: playlist_ID,
				}),
				dataType: 'json',
			})
			.done(function( rep ) {
				if(rep.type = 'success')
				{
					$('.supprTrack[data-id='+track_id+']').parent().remove();
				}
				$('#infoBule').remove();
				$('body').append('<div id="infoBule" class="'+rep.type+'"><span class="header"><i class="fermer fa fa-times"></i> Notifications :</span>'+rep.msg+'</div>');
			})
			.fail(function() {
				alert( "error" );
			});
	});
	$(document).on('click','#infoBule span.fermer',function(){
		$(this).parent().remove();
	});
	$(document).on('change ','#selectPlaylist',function(){
		majCatalogues(this.value);
	});

});
majCatalogues($( '#selectPlaylist' ).val())

function majCatalogues (playlistID) {
	$.ajax({
			type: "GET",
			url: "getPlaylist",
			contentType: 'application/json',
			data: {ID: playlistID},
			dataType: 'json',
		})
		.done(function( playlist ) {

			$('#catalogues').html("");
				if(playlist.tracks)
				{
					var articleHTML = "";
					articleHTML += '<article class="playlist"><ul class="tracks">';

					for(key in playlist.tracks) {
						articleHTML += '<li class="track" >'+ playlist.tracks[key].titre+'<i data-id="'+ playlist.tracks[key].ID+'" class="supprTrack fa fa-minus-square"></i></li>';
					}
					articleHTML += "</ul></article>";

					$('#catalogues').append(articleHTML);
				}
			
		});
}

function search(){
	var _search = $('#search').val();


			$.ajax({
				type: "GET",
				url: "search",
				contentType: 'application/json',
				data: {search: _search},
				dataType: 'json',
			})
			.done(function( rep ) {
				console.log(rep);


				$('#resultsAlbums').text("");
				$('#resultsAlbumsTracks').text("");
				for(key in rep.albums) {
					var articleHTML = "";

					articleHTML += '<article class="album">'+
					'<img src="'+rep.albums[key].coverURL+'" width="80" height="80">'+
					'<h3>'+rep.albums[key].titre+'</h3>'+
					'de <i>'+rep.albums[key].artiste+'	</i>'+
					'<ul class="tracks">';

					tracks = rep.albums[key].tracks;
					for(key in tracks) {
						articleHTML += 	'<li class="track" >'+tracks[key].titre+'<i data-id="'+tracks[key].ID+'" class="add fa fa-plus-square"></i></li>';
					}
					articleHTML += '</ul></article>';
					$('#resultsAlbums').append(articleHTML);
				}
				for(key in rep.albumsTracks) {
					var articleHTML = "";

					articleHTML += '<article class="album">'+
					'<img src="'+rep.albumsTracks[key].coverURL+'" width="80" height="80">'+
					'<h3>'+rep.albumsTracks[key].titre+'</h3>'+
					'de <i>'+rep.albumsTracks[key].artiste+'	</i>'+
					'<ul class="tracks">';

					tracks = rep.albumsTracks[key].tracks;
					for(key in tracks) {
						articleHTML += 	'<li class="track" >'+tracks[key].titre+'<i data-id="'+tracks[key].ID+'" class="add fa fa-plus-square"></i></li>';
					}
					articleHTML += '</ul></article>';
					$('#resultsAlbumsTracks').append(articleHTML);
				}

			})
			.fail(function() {
				alert( "error" );
			});
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