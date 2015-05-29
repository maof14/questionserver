<?php 

class CQuestionServer {

	// Members!! 
	private $db;
	private $users = array();
	private $running;

	// socket properties. 
	private $socket;
	private $adress = "localhost";
	private $port = 8104;

	// Methods!! 
	public function __construct($database) {
		// $db = new CDatabase($database);
	}

	// setup the socket server. 
	public function start() {
		// what to do here?
		if(!$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) {
			echo "Socket create failed, reason: " . socket_strerror(socket_last_error() . "\n");
			die('die');
		}

		if(!socket_bind($this->socket, $this->adress, $this->port)) {
			echo "Socket bind failed, reason: " . socket_strerror(socket_last_error() . "\n");
			die('die');
		}

		if(!socket_listen($this->socket, 5)) {
			echo "Socket listen failed, reason: " . socket_strerror(socket_last_error() . "\n");
			die('die');
		}
	}

	// Run the server, allowing for connections to be made. 
	/* 
		Ok. Hur ska man göra här då. 
		Ne men om en ny connection är gjord. Så borde man skapa en instance av CUser. Och den borde få nån property. Typ 
	*/
	public function run() {
		$msg = "\nWelcome to the PHP Test Server. \n" .
		"To quit, type 'quit'. To shut down the server type 'shutdown'.\n";
		// kolla efter ny anslutning. Här fastnar den, och går till nästa do efter att den första användaren har anslutit. Hur kan man lösa det. 
		do {
			// sätt msgsock, och gå vidare till nästa loop. Måste alltså göras på annat sätt. Om ej fungerar - bryt första loopen. 
			if(!$msgsock = socket_accept($this->socket)) {
				echo "Socket accept failed, reason: " . socket_strerror(socket_last_error() . "\n");
				break; 
			} else {
				$username = socket_read($msgsock, 2048, PHP_NORMAL_READ);
				$this->users[] = new CUser($msgsock, $username);
			}
			do {
				// ny for each här, som går igenom alla users?
				if(!$buf = socket_read($msgsock, 2048, PHP_NORMAL_READ) {
					echo "Socket read failed, reason: " . socket_strerror(socket_last_error() . "\n");
					break 2; // break both do's. 
				}
				if(!$buf = trim($buf)) {
					continue;
				}
				if($buf == 'count') {
					print count($this->users) . " users online\n";
				}
				if($buf == 'quit') {
					break;
				}
				if($buf == 'shutdown') {
					socket_close($msgsock);
					break 2; // break both do's. 
				}
				$talkback = "PHP: You said: '$buf' \n";
				socket_write($msgsock, $talkback, strlen($talkback));
				echo $buf. "\n";
			} while (true);
			socket_close($msgsock);
		} while (true);
		socket_close($this->socket);
	}
}