<?php


class User {
	
	var $ID;
	var $username;
	var $role;


	public function __construct($ID, $username, $role) {

		$this->ID = $ID;
		$this->username = $username;
		$this->role = $role;

	} // constructor
	public function isAdmin()
	{
		if($this->role == 'admin')
			return true;
		return false;
	}
	
	
} // class