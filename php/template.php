<?php
	/**
		Echo templated html.
	*/
	function echoHead($title, $addHTML, $doReturn = false) {
		$result = '
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="Joey">
		<link rel="icon" href="resource/favicon.ico">
		
		<title>'.$title.' - High Performance Ratings</title>
		
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		
		<!-- Custom styles for this template -->
		<link href="css/template.css" rel="stylesheet">
		
'.$addHTML.'
		
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>';
		
		if ($doReturn) {
			return $result;
		} else {
			echo($result);
		}
	}
	
	function echoNav($addHTML, $doReturn = false) {
		$result = '
		<nav class="navbar navbar-inverse navbar-fixed-top ">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!--<a class="navbar-brand" href="#">Project name</a>-->
					<div>
						<a href="home.php"><img src="resource/hprlogo.png" class="hpr-logo" alt="navbar logo"></a>
					</div>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#" class="">Link</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="#" class="">Action</a></li>
								<li><a href="#" class="">Another action</a></li>
								<li><a href="#" class="">Something else here</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#" class="">Separated link</a></li>
							</ul>
						</li>
					</ul>
					<!--
						<form class="navbar-form navbar-right">
						<div class="form-group">
						<input type="text" placeholder="Email" class="form-control">
						</div>
						<div class="form-group">
						<input type="password" placeholder="Password" class="form-control">
						</div>
						<button type="submit" class="btn btn-success">Sign in</button>
						</form>
					-->
'.$addHTML.'
				</div><!--/.navbar-collapse -->
			</div>
		</nav>';
		
		if ($doReturn) {
			return $result;
		} else {
			echo($result);
		}
	}
	
	function echoFooter($addHTML, $doReturn = false) {
		$result = '
		<div class="container">
			<hr>
			<footer>
				<p>&copy; Joseph Cumines 2015</p>
			</footer>
		</div>
'.$addHTML;
		
		if ($doReturn) {
			return $result;
		} else {
			echo($result);
		}
	}
	
	function echoJS($doReturn = false) {
		$result = '
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
';
		if ($doReturn) {
			return $result;
		} else {
			echo($result);
		}
	}
?>