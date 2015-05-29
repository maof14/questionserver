<?php

class CUser {
	
	// Members!!
	private $msgSocket; // as returned from socket_accept();
	private $username;
	
	// Methods!!
	public function __construct($msgSocket, $username) {
		$this->msgSocket = $msgSocket;
		$this->username = $username;
	}
	
	public function getUsername() {
		return $this->username;
	}

	public function getSocket() {
		return $this->msgSocket;
	}

	public function send($msg) {
		socket_write($this->msgSocket, $msg, strlen($msg));
	}
	
	public function leave() {
		socket_close($this->msgSocket);
	}
}