<?php 

class CGame {
	// members!! 

	private $players = array();
	private $db;

	// methods!! 
	public function __construct($database, $players) {
		// $this->db = new CDatabase($database); // can't use on Mac
		echo __METHOD__;
		$this->players = $players;
		echo $this->players[0]->getUsername() . " & " . $this->players[1]->getUsername() . " är inne och pgar nu.";
	}

	// skicka ut en fråga till en av användarna. Dvs använd $sql. 
	public function pushQuestion() {

	}

	// hantera svar som kommer från en användare. kolla om det är rätt och återge respons och sånt. 
	public function handleResponse() {

	}
}