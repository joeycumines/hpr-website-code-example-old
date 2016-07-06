<?php
	session_start();
	if (!isset($_SESSION['USER_EMAIL'])) {
		http_response_code (401);
		echo('Unauthorized. (401)');
	} else if ($_SESSION['USER_ADMIN'] == false) {
		http_response_code (403);
		echo('Forbidden. (403)');
	} else {
		require('php/template.php');
		echo('
<!DOCTYPE html>
<html lang="en">
'.echoHead('ADMIN TOOLS', '', true).'
'.echoNav('', true).'
	<body>
		<div class="container-fluid" style="background-color: rgba(0,0,0,0.9);">
			<h1 class="page-header">Admin Dashboard</h1>
			<h2 class="sub-header">Running Commands</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>UUID</th>
							<th>Command</th>
							<th>Life (seconds)</th>
						</tr>
					</thead>
					<tbody id="commandRows">');
		require('php/connect.php');
		//lets echo all the running commands
		$returned=send("lt");
		//sort returned
		 usort($returned, "custom_sort");

		foreach($returned as $rw) {
			$uuid = $rw['uuid'];
			$command = $rw['command'];
			$life = $rw['life'];
			echo("
									<tr>
										<td>$uuid</td>
										<td>$command</td>
										<td>$life</td>
									</tr>
			");
		}
		echo('
					</tbody>
				</table>
			</div>
			<h1 class="page-header">Console Tool</h1>
			<form method="POST" onsubmit="return runCommand(\'commandHistory\', this);">
				<input name="command" type="text" class="form-control"><br>
				<button class="btn btn-primary form-control" type="submit">SEND</button>
			</form>
			<div class="table-responsive">
				<table class="table table-striped">
					<tbody id="commandHistory">
					</tbody>
				</table>
			</div>
			<h2>Commands</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<tbody>');
		$jHelp = send('help');
		foreach($jHelp as $com) {
			echo("\n".'<tr><td>'.$com.'</td></tr>');
		}
		echo('
					</tbody>
				</table>
			</div>
'.echoFooter('<script src="js/loadRowsCommands.js"></script><script src="js/console.js"></script>', true).'
		</div>
'.echoJS(true).'
	</body>
</html>');

	}
	
	// Define the custom sort function
	function custom_sort($a,$b) {
		return $a['life']>$b['life'];
	}
?>