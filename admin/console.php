<?php
	if (!LOGGED_IN || ACCESS_LEVEL != 99) {
			http_response_code (401);
			echo('Unauthorized. (401)');
	} else {
		require(DIR_PHP.'dashboardTemplate.php');
		require(DIR_PHP.'command.php');
		$anchors = array();
		//load the dashboard object
		$page = getUserDashboard($anchors, '', 'Admin Console', 'Manage the website.');
		$content = '
					<h1 class="page-header">Console Tool</h1>
					<form method="POST" onsubmit="return runCommand(\'commandRows\', this);">
						<input name="command" type="text" class="form-control"><br>
						<button class="btn btn-primary form-control" type="submit">SEND</button>
					</form>
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody id="commandRows">
							</tbody>
						</table>
					</div>
					<h2>Commands</h2>
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody>
';
		//load the help rows
		$jHelp = json_decode(getCommand('help'), true);
		foreach($jHelp as $com) {
			$content .= '
								<tr><td>'.$com.'</td></tr>
';
		}
		$content .= '
							</tbody>
						</table>
					</div>
';
		$page->appendBody($page->getMainPageW($content, null));
		$page->appendScript('
		<script src="'.URL_BASE.'javascript/admin/console.js"></script>
');
		$page->echoPage();
	}
?>