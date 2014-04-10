<?php

addAlbum();
getAlbums();

function getAlbums() {

    $url = "http://localhost/WebPlayer/albums";
    $request = curl_init($url);

    curl_setopt($request, CURLOPT_GET, true);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($request);
    $albums = json_decode($res);

    echoAlbums($albums);

}


function addAlbum() {

    $url = "http://localhost/WebPlayer/albums";
    $request = curl_init($url);

    // JSON object representing the new album
    $newAlbum = '{ "ID":5, "title":"The Resistance", "artist":"Muse", "coverURL":null }';

    curl_setopt($request, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($request, CURLOPT_HTTPHEADER, "Content-Type: application/json");
    curl_setopt($request, CURLOPT_POSTFIELDS, $newAlbum);

    curl_exec($request);

}



function echoAlbums($albums){

    foreach($albums as $album) {
        echo $album->ID . "<br/>";
        echo $album->title . "<br/>";
        echo $album->artist . "<br/>";
        echo $album->coverURL . "<br/>";

        echo "<br/>";
    }

}