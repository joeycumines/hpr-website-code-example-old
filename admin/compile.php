<?php
	if (!LOGGED_IN || ACCESS_LEVEL != 99) {
		http_response_code (401);
		echo('Unauthorized. (401)');
	} else {
		require(DIR_PHP.'dashboardTemplate.php');
		
		$anchors = array();
		
		//load the update content
		$statusBox = '';
		$start = validate(isset($_GET['start']) ? $_GET['start'] : null);
		$end = validate(isset($_GET['end']) ? $_GET['end'] : null);
		$ttl = validate(isset($_GET['ttl']) ? $_GET['ttl'] : null);
		$ttlUnit = validate(isset($_GET['ttlUnit']) ? $_GET['ttlUnit'] : null);
		if (!empty($start) && !empty($end) && !empty($ttl) && !empty($ttlUnit)) {
			require(DIR_PHP.'command.php');
			//Compile in the background
			$secs = 0;
			if ($ttlUnit == 'm') {
				$secs += $ttl * 60.0;
			} else if ($ttlUnit == 'd') {
				$secs += $ttl * 86400.0;
			} else {
				$secs += $ttl * 1.0;
			}
				
			
			$statusBox = '
			<div class="alert alert-info" role="alert">
				<strong>COMPILING! </strong> '.getCommand('resultcompile '.$secs.' '.$start.' '.$end).'
			</div>
';
		}
		
		//load the dashboard object
		$page = getUserDashboard($anchors, '', 'Compile Results', 'Take results from multiple sources and upload.');
		$content = $statusBox.'
					<h1 class="page-header">Compile Results</h1>
					<div id="compiler">
						<form action="'.URL_CONTENT.'admin/compile.php" method="GET" onsubmit="return compile(\'compiler\', this);">
							<fieldset>
								<legend>Enter the date range (inclusive) and time it can take.</legend>
								Enter the start date in format year-month-day<br>
								<input type="text" name="start" ><br><br>
								Enter the end date in format year-month-day<br>
								<input type="text" name="end" ><br><br>
								Enter the time to live (per day)<br>
								<input type="number" name="ttl" value="10"><br>
								In units of 
								<select name="ttlUnit">
									<option value="s">Seconds</option>
									<option value="m" selected>Minutes</option>
									<option value="d">Days</option>
								</select><br><br>
								<input type="checkbox" name="backC" value="true"> Run in background<br><br>
								<input type="submit" value="Compile" class="btn btn-primary">
							</fieldset>
						</form>
					</div>
					
';
		$page->appendBody($page->getMainPageW($content, null));
		
		$page->appendScript('
		<script src="'.URL_BASE.'javascript/admin/compile.js"></script>
');
		
		$page->echoPage();
	}
?>