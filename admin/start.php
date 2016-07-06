<?php

	if (!LOGGED_IN || ACCESS_LEVEL != 99) {
		http_response_code (401);
		echo('Unauthorized. (401)');
	} else {
		require(DIR_PHP.'command.php');
		//set the callback
		getCommand('callbacktimer 1 '.URL_PHP.'admin/mainloop.php');
		//redirect to dashboard
		echoRedirectPage(URL_CONTENT . 'admin/dashboard.php');
	}

?>