<?php

include("WebPlaylistDB.class.php");

$db = new WebPlaylistDB();

if (isset($_POST['action']) && $_POST['action'] == "add")
{

	if(isset($_POST['id']) && isset($_POST['titre']) && isset($_POST['preview']) && isset($_POST['album']) && isset($_POST['album_cover']) && isset($_POST['artist']) )
	{

		$id = $_POST['id'];
		$titre = $_POST['titre'];
		$preview = $_POST['preview'];
		$album = $_POST['album'];
		$album_cover = $_POST['album_cover'];
		$artist = $_POST['artist'];
		$rep = $db->addTrack($id,$titre,$preview,$album, $album_cover, $artist);

		if($rep == "true") {
	        header('Content-Type: application/json');
	        die(json_encode(array('type'=>'success', 'message' => 'La musique a bien été ajoutée','id'=> $id)));
		} else {
	        header('Content-Type: application/json');
	        die(json_encode(array('type'=>'error','message' => 'Erreur : '.$rep)));
		}



	} else {
		header('HTTP/1.1 500 Internal Server Booboo');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('type'=>'error','message' => 'Erreur : ')));
	}
} elseif (isset($_POST['action']) && $_POST['action'] == "suppr") {

	if(isset($_POST['id']) && $_POST['id'] != '') 
	{
		$db->removeTrack($_POST['id']);
	    header('Content-Type: application/json');
	    die(json_encode(array('type'=>'success', 'message' => 'La musique a bien été supprimé')));
	}

}






