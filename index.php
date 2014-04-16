<?php
session_start();

require('lib/flight/Flight.php');
require("lib/WebPlaylistDB.class.php");
require("lib/WebPlayerServer.class.php");


	// Flight::render('layout', array('title' => 'TW2 - Projet'));





Flight::route('/', function(){

	$db = new WebPlaylistDB();
    $user = $db->getUser();
    Flight::render('index', array( 'db' => $db,'user'=>$user), 'body_content');

    Flight::render('layout', array('page' => 'index','title' => 'TW2 - Projet','user'=>$user));

});

Flight::route('/playlist', function(){

    $db = new WebPlaylistDB();
    $user = $db->getUser();
    if( !isset($user->role) or ($user->role != 'user' and $user->role != 'admin')){
        Flight::redirect($_SERVER['HTTP_HOST'].'/');
        exit;
     } 
    Flight::render('playlist', array( 'db' => $db), 'body_content');
    
    Flight::render('layout', array('page' => 'playlist','title' => 'TW2 - Projet','user'=>$user));

});

Flight::route('/admin', function(){

    $db = new WebPlaylistDB();
    $user = $db->getUser();
    if(isset($user->role) and ($user->role == 'admin')){
        Flight::render('admin', array( 'user' => $user), 'body_content');
        
        Flight::render('layout', array('page' => 'admin','title' => 'TW2 - Projet','user'=>$user));
    }else {
        Flight::redirect($_SERVER['HTTP_HOST'].'/');
    }
});


Flight::route('/admin/users', function(){


    $db = new WebPlaylistDB();
    $user = $db->getUser();
    $users = $db->getUsers();
    if(isset($user->role) and ( $user->role == 'admin')){
        Flight::render('users', array( 'users' => $users), 'body_content');
    
        Flight::render('layout', array('page' => 'admin','title' => 'TW2 - Projet','user'=>$user));
    }else {
        Flight::redirect($_SERVER['HTTP_HOST'].'/');
    }

});

Flight::route('/admin/collection', function(){

    $db = new WebPlaylistDB();
    $albums = $db->getAlbums();
    $user = $db->getUser();
    if(isset($user->role) and ( $user->role == 'admin')){
        Flight::render('collection', array( 'albums' => $albums), 'body_content');
        
        Flight::render('layout', array('page' => 'collection','title' => 'TW2 - Projet','user'=>$user));
    }else {
        Flight::redirect($_SERVER['HTTP_HOST'].'/');
    }
});

Flight::route('POST /addPlaylist', function(){

    $request = Flight::request();

    $db = new WebPlaylistDB();

    $rep = $db->setUserPlaylists($db->getUser()->ID,$request->data->nomplayliste);
    Flight::redirect($_SERVER['HTTP_HOST'].'/playlist');
});


Flight::route('/logout', function(){
    $_SESSION['WP_Login_UN'] = '';
    $_SESSION['WP_Login_PW'] = '';
    Flight::redirect($_SERVER['HTTP_HOST'].'/');

});

//****************************************
//**********  REST services
//*****************************************


Flight::route('POST /login', function(){

    $request = Flight::request();

    $db = new WebPlaylistDB();

    $rep = $db->login($request->data->username,$request->data->password);

    if($rep){
        $return = $arrayName = array('type' => 'success' );
        echo json_encode($return);
    } else {
        $return = $arrayName = array('type' => 'error','msg' => "Identifiant invalide." );
        echo json_encode($return);
    }
    Flight::json($return);

});


Flight::route('GET /getalbums', function(){

    $db = new WebPlaylistDB();
    $albums = $db->getAlbums();

    Flight::json($albums);
});

Flight::route('GET /getPlaylist', function(){
    $request = Flight::request();
    $db = new WebPlaylistDB();
    $playlist = $db->getPlaylist($request->query->ID);

    Flight::json($playlist);
});
Flight::route('GET /search', function(){
    $request = Flight::request();

    $db = new WebPlaylistDB();
    $search = $db->search($request->query->search);

    Flight::json($search);
});

Flight::route('GET /albums/@id', function($id){
    $request = Flight::request();

    $db = new WebPlaylistDB();
    $album = $db->getAlbum($id);

    echo json_encode($album);

});
Flight::route('GET /getTrack/@id', function($id){
    $request = Flight::request();

    $db = new WebPlaylistDB();
    $track = $db->getTrack($id);

    echo json_encode($track);

});
Flight::route('GET /getTrackAlbum/@id', function($id){
    $request = Flight::request();

    $db = new WebPlaylistDB();
    $album = $db->getTrackAlbum($id);

    echo json_encode($album);

});
Flight::route('PUT /addPlaylistTrack', function(){
    $request = Flight::request();
    $rep = json_decode($request->body);
    $db = new WebPlaylistDB();
    $msg = $db->addPlaylistTrack($rep->playlistID,$rep->trackID);

    if($msg){
        $return = $arrayName = array('type' => 'success','msg' => $msg );
        echo json_encode($return);
    } else {
        $return = $arrayName = array('type' => 'error' );
        echo json_encode($return);
    }
});

Flight::route('DELETE /supprPlaylistTrack', function(){
    $request = Flight::request();
    $rep = json_decode($request->body);
    $db = new WebPlaylistDB();
    $msg = $db->supprPlaylistTrack($rep->playlistID,$rep->trackID);

    if($msg){
        $return = $arrayName = array('type' => 'success','msg' => $msg );
        echo json_encode($return);
    } else {
        $return = $arrayName = array('type' => 'error' );
        echo json_encode($return);
    }
});

Flight::route('PUT /addAlbum', function(){
    $msg ="";
    $request = Flight::request();
    $album = json_decode($request->body);
     // var_dump($album->deezerID);
    $db = new WebPlaylistDB();
    $ifAlbumExist = $db->ifAlbumExist($album->deezerID);
    if($ifAlbumExist) 
    {
        if($album->tracks) // si il y a des musique a ajouter 
        {
            foreach ($album->tracks as $track) {
                if(!$db->ifTrackExist($track->deezerID)) {
                    $msg = $db->addAlbumTrack($ifAlbumExist,$track);  // Updates an album
                } else {
                    $msg = "La musique est déjà dans la collection.";
                }
            }
        }
    } else {

        $msg = $db->addAlbum($album);  // Creates an album
    }


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
    $track   = json_decode($request->body);

    $db = new WebPlaylistDB();

    $msg = $db->removeTrack($track->ID);

    if($msg){
	    $return = $arrayName = array('type' => 'success','msg' => $msg );
	    echo json_encode($return);
    } else {
	    $return = $arrayName = array('type' => 'error','msg' => "La musique n'existe pas." );
	    echo json_encode($return);
    }

});
Flight::route('DELETE /supprAlbum', function(){

    $request = Flight::request();
    $album = json_decode($request->body);

    $db = new WebPlaylistDB();

    $msg = $db->removeAlbum($album->ID);

    if($msg){
        $return = $arrayName = array('type' => 'success','msg' => $msg );
        echo json_encode($return);
    } else {
        $return = $arrayName = array('type' => 'error','msg' => "L'album n'existe pas." );
        echo json_encode($return);
    }

});


//****************************************
//**********  REST services API radio
//*****************************************


Flight::route('GET /radio/currentTrack', function(){

    $webPlayerServer = new WebPlayerServer();
    $currentTrack = $webPlayerServer->getCurrentTrack();

    echo json_encode($currentTrack);

});


Flight::route('GET|PUT /radio/proposedTracks', function(){

    $request = Flight::request();
    $webPlayerServer = new WebPlayerServer();

    if($request->method == "GET") {
        $proposedTracks = $webPlayerServer->getProposedTracks();
        echo json_encode($proposedTracks);
    }

    if($request->method == "PUT") {
        $proposedTrack = json_decode($request->body);
        $webPlayerServer->proposeTrack($proposedTrack->trackID);
    }

});


Flight::route('POST /radio/nextTrack', function(){

    $request = Flight::request();
    $previousTrackProposalID = $request->data["previousTrackProposalID"];

    $webPlayerServer = new WebPlayerServer();
    $nextTrack = $webPlayerServer->getNextTrack( $previousTrackProposalID );

    echo json_encode($nextTrack);

});

Flight::route('PUT /radio/addProposedTrackVote', function(){

    $request = Flight::request();
    $webPlayerServer = new WebPlayerServer();

    $proposedTrack = json_decode($request->body);
    $webPlayerServer->addTrackVote($proposedTrack->vote,$proposedTrack->proposalID);


});

Flight::start();
