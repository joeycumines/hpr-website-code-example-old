<?php
	
	if (isset($_GET['id'])) {
		//echo the competitors for an event
		require('databaseTools.php');
		$pdo = getNewPDO();
		$rows = runQueryPrepared($pdo, 'SELECT * FROM scheduledcompetitors WHERE EventID = :id;', array(':id'=>validate($_GET['id'])));
		foreach ($rows as $key=>$value) {
			$rows[$key]['Metadata'] = json_decode($rows[$key]['Metadata'], true);
			for ($x = 0; $x < 10; $x++) {
				if (isset($rows[$key][$x]))
				unset($rows[$key][$x]);
			}
		}
		echo(json_encode($rows));
	}
?>