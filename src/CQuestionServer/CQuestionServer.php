<?php 

class CQuestionServer {

	private $db;
	private $users = array();

	private $socket;
	private $adress = "localhost";
	private $port = 8105;

	/**
	 *
	 * Constructor.
	 * @param $database with database connection details.
	 * @return void.
	 */
	public function __construct($database) {
		// init database for questions access later.
		// $db = new CDatabase($database);
	}

	/**
	 *
	 * Function to initialize the server, creating the necessary objects.
	 * @return void.
	 *
	 */
	public function start() {
		// what to do here?
		if(!$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) {
			echo "Socket create failed, reason: " . socket_strerror(socket_last_error()) . "\n";
			return;
		}

		if(!socket_bind($this->socket, $this->adress, $this->port)) {
			echo "Socket bind failed, reason: " . socket_strerror(socket_last_error()) . "\n";
			return;
		}

		if(!socket_listen($this->socket, 5)) {
			echo "Socket listen failed, reason: " . socket_strerror(socket_last_error()) . "\n";
			return;
		}
		print "Server started.\n";
	}

	public function run() {
		print "Server is listening.\n";
		do {
			$read = array();
			$read[] = $this->socket;
			$sockArr = array();
			
			foreach($this->users as $u) {
				$sockArr[] = $u->getSocket();
			}
			
			$read = array_merge($read, $sockArr);
			
			$write = null;
			$except = null;
			
			if(socket_select($read, $write, $except, 5) < 1) {
				continue;
			}
			
			if(in_array($this->socket, $read)) {
				if(($msgSock = socket_accept($this->socket)) === false) {
					print "Socket accept fail, reason: " . socket_strerror(socket_last_error()) . "\n";
					break;
				}
				$welcomeMsg = "Welcome! Type in your username to get started.\n";
				socket_write($msgSock, $welcomeMsg, strlen($welcomeMsg));
				$username = socket_read($msgSock, 2048, PHP_NORMAL_READ);
				$this->users[] = new CUser($msgSock, $username);
				$lastUser = end($this->users);
				
				$msg = "\nVälkommen till PHP-sockets. \n" .
				"Ditt användarnamn är: {$lastUser->getUsername()}\n" .
				"För att sticka, skriv 'quit'. För att stänga ner servern, skriv 'shutdown'.\n";
				$lastUser->send($msg);
				foreach($this->users as $u) {
					if($u =! $lastUser) {
						$u->send("A new user is here: " . $lastUser->getUsername() . "\n");
					}
				}
			}
			
			// users = array av CUser. 
			foreach($this->users as $key => $client) {
				// sätta buf till null?
				if(in_array($client->getSocket(), $read)) {
					if(false === ($buf = socket_read($client->getSocket(), 2048, PHP_NORMAL_READ))) {
						print "Socket read fail, reason: " . socket_strerror(socket_last_error()) . "\n";
						break 2;
					}
					$username = $client->getUsername();
					if (!$buf = trim($buf)) {
						continue;
					}
					if ($buf == 'quit') {
						unset($this->users[$key]);
						$client->leave();
						break;
					}
					if ($buf == 'shutdown') {
						$client->leave();
						break 2;
					}
					// create broadcast str. $client->getUsername() does not work. Why?? 
					$broadcast = $username . " sa: " . $buf . "\n";
					
					foreach($this->users as $u) {
						if($u != $client) {
							// broadcast on success, to the users not writing the message. 
							socket_write($u->getSocket(), $broadcast, strlen($broadcast));
						}
					}
					// print message on server. 
					$date = date('H:m');
					print $date . ": " . $broadcast; 
				}
			}
		} while (true);
		
		// terminate if loops are broken. 
		$this->close();
	}
	
	/**
	 *
	 * Function to close the main socket resource.
	 * @return void. 
	 */
	private function close() {
		socket_close($this->socket);
		print "Socket closed. Program terminated.";
	}
}