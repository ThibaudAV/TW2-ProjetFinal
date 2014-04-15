<section>
	<div id="dz-root"></div>
<div id="c1">
	<h2>Ajouter des musique</h2>
	<input type="text" class="form-field" id="search" onkeyup="search();" placeholder="Rechercher" autocomplete="off" disabled/>
	<ul id="results">
		Taper votre recherche
	</ul>

</div>
<div id="c2">
	<h2>Catalogue commun</h2>
	<div id="catalogues">
		<?php  foreach($albums as $album ) {?>
		<article class="album">
			<img src="<?php echo $album->coverURL ?>" width="80" height="80">
			<h3><?php echo $album->titre ?><i data-id="<?php echo $album->ID ?>" class="supprAlbum fa fa-minus-square"></i></h3>
			de <i><?php echo $album->artiste ?>	</i>
			<ul class="tracks">
			<?php foreach($album->tracks as $track ) { ?>
			<li class="track" ><?php echo $track->titre ?><i data-id="<?php echo $track->ID ?>" class="supprTrack fa fa-minus-square"></i></li>
			<?php } ?>
			</ul>
		</article>
		<?php } ?>
	</div>
	<!-- 
	<ul id="catalogues">
		<?php /*
			 // var_dump($albums);
		while( $album = array_pop($albums) ) {
			// var_dump($album->tracks);
			 foreach($album->tracks as $track ) {
				echo "<li>";
				echo '<img width="80" height="80" class="inline" id="cover_image" src="'.$album->coverURL.'" style="opacity: 1;">';
				echo '<div data-id="'.$track->deezerID.'" class="miniPlayer" id="idMP_'.$track->ID.'"> ';
				echo '</div><div class="addCatalogue">';
				echo '<span class="titre">'.$track->titre.'</span>';
				echo '<span class="album">'.$album->titre.'</span> de ';
				echo '<span class="artist">'.$album->artiste.'</span></div>';
				echo '<a href="#" data-id="'.$track->ID.'" class="suppr fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-times fa-stack-1x fa-inverse"></i></a>';
				echo '</li>';
			 }
		}*/
		?>

	</ul> -->
</div>
</section>
<script>
function initDivMP () {
	
	$( "div.miniPlayer" ).on({
		mouseenter: function() {
			var track = $( this ).attr("data-id");
			// console.log("miniPlayer mouseenter");
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
			// console.log("miniPlayer mouseleave");
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
			// console.log("miniPlayer click");
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


			$.ajax({
				type: "PUT",
				url: "<?php echo $_SERVER['HTTP_HOST'];?>/addAlbum",
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
					/*
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
*/
				}
				$('#infoBule').remove();
				$('body').append('<div id="infoBule" class="'+rep.type+'"><span class="header"><i class="fermer fa fa-times"></i> Notifications :</span>'+rep.msg+'</div>');
			});

/*
			var jqxhr = $.post( "<?php echo $_SERVER['HTTP_HOST'];?>/addTrack", { 
				action: "add", id: track_id, titre: track_titre, preview: track_preview,album: track_album,album_cover:track_album_cover,artist:track_artist }, function(res) {

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

			});*/

		});

	});
}


initDivMP();

$(document).ready(function() {
	$(document).on('click','.supprTrack',function(){
		var track_id = $( this ).attr("data-id");

		$.ajax({
			type: "DELETE",
			url: "<?php echo $_SERVER['HTTP_HOST'];?>/supprTrack",
		    contentType: 'application/json',
		    data: JSON.stringify({ 
		    	ID: track_id,
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
	$(document).on('click','.supprAlbum',function(){
		var track_id = $( this ).attr("data-id");
			if (confirm("Ete vous sur de vouloir supprimer l'album ?")) { 

				$.ajax({
					type: "DELETE",
					url: "<?php echo $_SERVER['HTTP_HOST'];?>/supprAlbum",
				    contentType: 'application/json',
				    data: JSON.stringify({ 
				    	ID: track_id,
				    }),
				    dataType: 'json',
				})
				.done(function( rep ) {
					if(rep.type = 'success')
					{
						$('.supprAlbum[data-id='+track_id+']').parent().parent().remove();
					}
					$('#infoBule').remove();
					$('body').append('<div id="infoBule" class="'+rep.type+'"><span class="header"><i class="fermer fa fa-times"></i> Notifications :</span>'+rep.msg+'</div>');
				})
				.fail(function() {
					alert( "error" );
				});
	       }
	});
	$(document).on('click','#infoBule span.fermer',function(){
		// $( "#infoBule span.fermer" ).click(function() {
		$(this).parent().remove();
	});
});

function majCatalogues () {
	$.ajax({
		type: "GET",
		url: "<?php echo $_SERVER['HTTP_HOST'];?>/getAlbums",
		contentType: 'application/json',
		dataType: 'json',
	})
	.done(function( albums ) {
		$('#catalogues').html("");
		for(key in albums) {
		var articleHTML = "";

			articleHTML += '<article class="album">'+
				'<img src="'+albums[key].coverURL+'" width="80" height="80">'+
				'<h3>'+albums[key].titre+'<i data-id="'+albums[key].ID+'" class="supprAlbum fa fa-minus-square"></i></h3>'+
				'de <i>'+albums[key].artiste+'	</i>'+
				'<ul class="tracks">';

			tracks = albums[key].tracks;
			for(key in tracks) {
				articleHTML += 	'<li class="track" >'+tracks[key].titre+'<i data-id="'+tracks[key].ID+'" class="supprTrack fa fa-minus-square"></i></li>';
			}
			articleHTML += '</ul></article>';
			$('#catalogues').append(articleHTML);
		}



			console.log(albums[key]);
		
		
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