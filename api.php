<?php
	session_start();
	if (!isset($_SESSION['USER_EMAIL'])) {
		http_response_code (401);
		echo('Unauthorized. (401)');
		} else if ($_SESSION['USER_ADMIN'] == false) {
		http_response_code (403);
		echo('Forbidden. (403)');
		} else {
		require('php/connect.php');
		if (!empty($_POST['command'])) {
			echo(json_encode(send($_POST['command'])));
		} else if (!empty($_GET['command'])) {
			echo(json_encode(send($_GET['command'])));
		}
	}
?>