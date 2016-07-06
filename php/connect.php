<?php
	//this is only going to be included by other classes
	require_once('vendor/autoload.php');
	
	/**
	* Sends a command to the API, string returned.
	*/
	function send($command) {
		//$factory = new \Socket\Raw\Factory();
		//$socket = $factory->createClient('localhost:55551');
		//echo 'Connected to ' . $socket->getPeerName() . PHP_EOL;
		// send simple request to remote side
		//$socket->write("helloworld\n");
		// receive and dump response
		//var_dump($socket->read(8192));
		//$socket->close();
		$factory = new \Socket\Raw\Factory();
		$socket = $factory->createClient('localhost:55551');
		$socket->write($command."\n");
		$result = $socket->read(8192);
		$socket->close();
		return json_decode($result, true);
	}
?>