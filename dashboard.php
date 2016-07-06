<?php
	session_start();

	if (!isset($_SESSION['USER_EMAIL'])) {
		http_response_code (401);
		echo('Unauthorized. (401). Please <a href="login.php">log in</a>, or see your web admin.');
		die();
	}
	
	//dashboard
	echo('dashboard!');
?>