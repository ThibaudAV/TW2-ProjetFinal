<?php

require('lib/flight/Flight.php');
require("lib/WebPlaylistDB.class.php");
$_SERVER['HTTP_HOST'] = 'http://localhost/Cours/Tech_web2/TW2_Projet';



	// Flight::render('layout', array('title' => 'TW2 - Projet'));


Flight::route('/', function(){

	$db = new WebPlaylistDB();
	$playlists = $db->getPlaylists();
	$albums = $db->getAlbums();
    Flight::render('index', array( 'albums' => $albums,'playlists' => $playlists), 'body_content');

    Flight::render('layout', array('page' => 'index','title' => 'TW2 - Projet'));

});

Flight::route('/collection', function(){

	$db = new WebPlaylistDB();
	$albums = $db->getAlbums();
    Flight::render('collection', array( 'albums' => $albums), 'body_content');
    
    Flight::render('layout', array('page' => 'collection','title' => 'TW2 - Projet'));

});




//****************************************
//**********  REST services
//*****************************************
Flight::route('GET /albums', function(){

    $db = new WebPlaylistDB();
    $albums = $db->getAlbums();

    Flight::json($albums);
});


Flight::route('GET /albums/@id', function($id){

    $db = new WebPlaylistDB();
    $album = $db->getAlbum($id);
    echo "string";

    echo json_encode($album);

});


Flight::route('PUT /addTrack', function(){

    $request = Flight::request();
    $album = json_decode($request->body);
     // var_dump($album->deezerID);
    $db = new WebPlaylistDB();
    if($db->ifAlbumExist($album->deezerID))
        $msg = $db->updateAlbum($album);  // Updates an album
    else
        $msg = $db->addAlbum($album);  // Creates an album


    if($msg){
	    $return = $arrayName = array('type' => 'success','msg' => $msg );
	    echo json_encode($return);
    } else {
	    $return = $arrayName = array('type' => 'error' );
	    echo json_encode($return);
    }
});


Flight::route('DELETE /supprTrack', function(){

    $request = Flight::request();
    $track = json_decode($request->body);

    $db = new WebPlaylistDB();

    $msg = $db->removeTrack($track->ID);

    if($msg){
	    $return = $arrayName = array('type' => 'success','msg' => $msg );
	    echo json_encode($return);
    } else {
	    $return = $arrayName = array('type' => 'error','msg' => "La musique n'existe pas" );
	    echo json_encode($return);
    }

});

Flight::start();
