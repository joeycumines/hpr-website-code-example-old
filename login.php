<?php
	//start the session
	session_start();
	
	require('php/databaseTools.php');
	require('php/pageRedirect.php');
	$error = '';
	
	//perform login, if we posted back to ourself
	$userName = validate(isset($_POST["userName"]) ? $_POST["userName"] : null);
	$userPass = validate(isset($_POST["userPass"]) ? $_POST["userPass"] : null);
	
	if (!empty($userName) && !empty($userPass)) {
		$user = null;
		try {
			$user = validateUserPass($userName, $userPass);
		} catch (PDOException $e) {
			$user = null;
			$error = 'Error with database. ';
			//$error = $e->getMessage();
		}
		if (empty($user) || !isset($user['Email']) || empty($user['Email'])) {
			$error .= 'Invalid user name or password!';
		} else {
			//set our session vars
			$_SESSION['USER_EMAIL'] = $user['Email'];
			$_SESSION['USER_CONFIRMATION'] = $user['ConfirmationString'];
			$_SESSION['USER_ADMIN'] = $user['Admin'];
			
			echo('Successfully Logged In!');
			//go to the dashboard
			echoRedirectPage('dashboard.php');
			die();
			
		}
	}
	
	$errorBox = '';
	if (!empty($error)) {
		$errorBox = '
			<div class="alert alert-danger" role="alert">
				<strong>Oops! </strong>'.$error.'
			</div>
';
	}
	
	require('php/template.php');

echo('

<!DOCTYPE html>
<html lang="en">
'.echoHead('Login', "\t\t<link href=\"css/login.css\" rel=\"stylesheet\">", true).'

	<body>

		<div class="container">
'.$errorBox.'
			<form class="form-signin" method="POST" action="login.php">
				<h2 class="form-signin-heading">Log In<small><a href="register.php">&nbsp;(REGISTER)</a></small></h2>
				<label for="inputEmail" class="sr-only">User Name</label>
				<input name="userName" type="email" id="inputEmail" class="form-control" placeholder="your email address" required autofocus>
				<label for="inputPassword" class="sr-only">Password</label>
				<input name="userPass" type="password" id="inputPassword" class="form-control" placeholder="your password" required>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Log In</button>
			</form>

		</div> <!-- /container -->

	</body>
</html>

');

?>