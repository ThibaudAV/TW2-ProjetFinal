<?php


class Track {
	
	var $ID;
	var $deezerID;
	var $titre;
	var $preview;


	public function __construct($deezerID, $titre, $preview) {

		$this->deezerID = $deezerID;
		$this->titre = $titre;
		$this->preview = $preview;

	} // constructor

	function init($ID, $deezerID, $titre, $preview) {

		$this->ID = $ID;
		$this->deezerID = $deezerID;
		$this->titre = $titre;
		$this->preview = $preview;

	} // function
	
} // class