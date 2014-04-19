<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;
 ?></title>
	<link id="MyCss" rel="stylesheet" type="text/css" href="media/css/<?php echo (ISSET($_COOKIE['changeCSS'])) ? $_COOKIE['changeCSS'] : 'style.css' ; ?>">
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>


    <script src="media/js/WebPlayerController.js"></script>
    <script src="media/js/WebPlayerClient.js"></script>

<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

		<script type="text/javascript" src="http://cdn-files.deezer.com/js/min/dz.js"></script>
    <script src="media/js/jquery.cookie.js"></script>


	<style type="text/css">
		.progressbarplay {
			cursor:pointer;
			overflow: hidden;
			height: 8px;
			margin-bottom: 8px;
			background-color: #B8B8B8;
/*			background-image: -moz-linear-gradient(top,whiteSmoke,#F9F9F9);
			background-image: -ms-linear-gradient(top,whiteSmoke,#F9F9F9);
			background-image: -webkit-gradient(linear,0 0,0 100%,from(whiteSmoke),to(#F9F9F9));
			background-image: -webkit-linear-gradient(top,whiteSmoke,#F9F9F9);
			background-image: -o-linear-gradient(top,whiteSmoke,#F9F9F9);
			background-image: linear-gradient(top,whiteSmoke,#F9F9F9);
			background-repeat: repeat-x;*/
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f5f5f5',endColorstr='#f9f9f9',GradientType=0);
			-webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
			-moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
			box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
			-webkit-border-radius: 6px;
			-moz-border-radius: 6px;
			border-radius: 6px;

		}
		.progressbarplay .bar {
			cursor:pointer;
			background: #4496C6;
			width: 0;
			height: 8px;
			color: white;
			font-size: 12px;
			text-align: center;
			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
			-webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
			-moz-box-shadow: inset 0 -1px 0 rgba(0,0,0,0.15);
			box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			-webkit-transition: width .6s ease;
			-moz-transition: width .6s ease;
			-ms-transition: width .6s ease;
			-o-transition: width .6s ease;
			transition: width .6s ease;

		}
	</style>

</head>
<body>
<div id="contenaire">
	
	<header>
		<div id="titre">
			<h1><i class="fa fa-music"></i> MyPlay</h1>
			<h2>Votre radio.</h2>
		</div>
		<div id="contenu">
			<div id="connexion">
			<?php if($user){ ?>
				Bonjour, <?php echo $user->username ?> <a href="logout">Déconnexion</a>
			<?php } else { ?>
				<form id="formConnexion" action="login" method="POST">
					<input type="text" name="username" placeholder="Nom d'utilisateur">
					<input type="password" name="password" placeholder="Mot de passe">
					<input type="submit" value="Connexion">
				</form>
			<?php } ?>
			
			</div>
			<nav>

						<ul>
						<?php 
						$select['index'] = '';
						$select['playlist'] = '';
						$select['admin'] = '';
						$select[$page] = 'select';
						?>
								<li><a href="accueil" class="<?php echo $select['index'] ?>">Accueil</a></li>
						<?php if( isset($user->role) and ($user->role != 'user' or $user->role != 'admin')){ ?>
								<li><a href="playlist" class="<?php echo $select['playlist'] ?>">Playlist</a></li>
						<?php } ?>
						<?php if( isset($user->role) and ($user->role == 'admin')){ ?>
								<li><a href="admin" class="<?php echo $select['admin'] ?>">Admin</a></li>
						<?php }  ?>
						</ul>
				</nav>
		</div>
	</header>
	<?php echo $body_content;
 ?>

<section id="footer">
	<a href="#" id="changeCSS" class="fa fa-repeat">Changer de CSS</a>
</section>
</div>


<script>
	
	$(document).ready(function() {



		// lorsque je soumets le formulaire
		$('#footer').on('click', '#changeCSS', function(event) {
			var changeCSS = $.cookie('changeCSS');
			if(changeCSS == 'style2.css') {
				$('link[id=MyCss]').attr('href','media/css/style.css');
				$.cookie('changeCSS', 'style.css');
			} else {
				$('link[id=MyCss]').attr('href','media/css/style2.css');
				$.cookie('changeCSS', 'style2.css');
			}
			/* Act on the event */
		});

		

		$('#formConnexion').on('submit', function() {
				var $this = $(this);
 
				// je récupère les valeurs
				var username = $('#username').val();
				var password = $('#password').val();
 
				// je vérifie une première fois pour ne pas lancer la requête HTTP
				// si je sais que mon PHP renverra une erreur
				if(username === '' || password === '') {
						alert('Les champs doivent êtres remplis');
				} else {
						// appel Ajax
						$.ajax({
								url: $this.attr('action'), // le nom du fichier indiqué dans le formulaire
								type: $this.attr('method'), // la méthode indiquée dans le formulaire (get ou post)
								data: $this.serialize(), // je sérialise les données (voir plus loin), ici les $_POST
								success: function(rep) { // je récupère la réponse du fichier PHP
									if(rep.type == 'success')
									{
										location.reload();
									}
									$('#infoBule').remove();
									$('body').append('<div id="infoBule" class="'+rep.type+'"><span class="header"><i class="fermer fa fa-times"></i> Notifications :</span>'+rep.msg+'</div>');

								}
						});
				}
				return false; // j'empêche le navigateur de soumettre lui-même le formulaire
		});
});
</script>
</body>
</html>