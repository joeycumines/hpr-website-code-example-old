<?php
	//code for post back in the registration.
	//This php script will echo the register form and required scripts
	//looks in GET for prefilled information.
	
	//it also will post back to itself, and complete the registration process
	//check post data
	$doEcho = true;
	$errorMessage = null;
	require('php/pageRedirect.php');
	require('php/template.php');
	require('php/databaseTools.php');
	
	$register = validate(isset($_POST["register"]) ? $_POST["register"] : false);
	$email = validate(isset($_POST["email"]) ? $_POST["email"] : null);
	$password = validate(isset($_POST["password"]) ? $_POST["password"] : null);
	$tAndC = validate(isset($_POST["t_and_c"]) ? $_POST["t_and_c"] : false);
	$password_confirmation = validate(isset($_POST["password_confirmation"]) ? $_POST["password_confirmation"] : null);
	
	$validEntries = true;
	if ($register) {
		if (empty($email)) {
			$errorMessage = 'You need to enter a email address.';
			$validEntries = false;
		} else if (empty($password)) {
			$errorMessage = 'You need to enter a password.';
			$validEntries = false;
		} else if (strlen($password) < 6) { 
			$errorMessage = 'Your password must be at least 6 characters long.';
			$validEntries = false;
		} else if ($password != $password_confirmation) {
			$errorMessage = 'Your passwords did not match.';
			$validEntries = false;
		} else if (!$tAndC) {
			$errorMessage = 'You need to accept our terms and conditions to register.';
			$validEntries = false;
		}
	}
	
	if ($register && $validEntries ) {
		//register the user then redirect to user dashboard, signing in as we go.
		//successfully verified, try to add to database
		//generate new salt
		$salt = uniqid(); //new salt
		try {
			$dbh = getNewPDO();
			$sql = 'INSERT
					INTO members (Email, PasswordHash, PasswordSalt)
					VALUES (:email, SHA2(CONCAT(:password, :passwordsalt), 256), :passwordsalt);';
			$sth = $dbh->prepare($sql, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			$succeeded = $sth->execute(array(':email' => $email, ':password' => $password, ':passwordsalt' => $salt));
			if ($succeeded) {
				//success, lets redirect to login status
				$doEcho = false;
				$_SESSION['loggedIn'] = true;
				$_SESSION['userName'] = $email;
				echoRedirectPage('index.php');
			} else {
				$errorMessage = 'We connected but didn\'t make it to registration! Try again?';
				//this will continue echoing the registration form.
			}
		} catch (PDOException $e) {
			if ($e->getCode() == 23000) {
				$errorMessage = 'You are already registered! Try to login.';
			} else
				$errorMessage = 'We experienced an error registering your account, try again?';
			//this will continue echoing the registration form.
		}
	}	
	
?>

<!DOCTYPE html>
<html lang="en">
	<?php echoHead('Registration', '<link href="css/register.css" rel="stylesheet">');?>
	<body>
		<?php
			echoNav('');
			if ($doEcho) {
				$errorBox = '';
				//if we tried to register, echo the error message as well as the data
				if (!empty($errorMessage)) {
					$errorBox = '
					<br>
					<div class="alert alert-danger" role="alert">
						<strong>Oops! </strong>'.$errorMessage.'
					</div>
';
				}
				//echo the form itself
				echo('
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
'.$errorBox.'
					<form role="form" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="POST" style="background: rgba(10,10,10,0.7); padding: 20px;">
						<input type="hidden" name="register" value="true">
						<h2>Sign Up <small>Exclusive ratings are just a step away!</small></h2>
						<hr class="gradientgraph">
						<div class="form-group">
							<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" tabindex="1">
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<div class="form-group">
									<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="2">
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<div class="form-group">
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-lg" placeholder="Confirm Password" tabindex="3">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-4 col-sm-3 col-md-3">
								<span class="button-checkbox">
									<button type="button" class="btn" data-color="info" tabindex="4">I Agree</button>
									<input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
								</span>
							</div>
							<div class="col-xs-8 col-sm-9 col-md-9">
								 By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a> set out by this site, including our Cookie Use.
							</div>
						</div>
						
						<hr class="gradientgraph">
						<div class="row">
							<div class="col-xs-12 col-md-6"><input type="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="4"></div>
							<div class="col-xs-12 col-md-6"><a href="login.php" class="btn btn-success btn-block btn-lg">Log In</a></div>
						</div>
					</form>
				</div>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
							<h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
						</div>
						<div class="modal-body">
');
				require('termsAndConditions.html');
				echo('
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
');
				echoFooter('');
				//echo js
				echoJS();
				echo('<script src="js/register.js"></script>');
			}
		?>
	</body>
</html>