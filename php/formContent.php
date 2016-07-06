<?php
	//echos the latest details as json.
	require('databaseTools.php');
	
	$fetchDate = validate((isset($_GET['from-date']) && !empty($_GET['from-date'])) ? $_GET['from-date'] : date('Y-m-d'));
	
	//get the latest update
	//find all events for the latest AND second latest update, order by time
	$pdo = getNewPDO();
	$rows = runQueryPrepared($pdo, 
			'SELECT * FROM (SELECT scheduledevents.EventID, scheduledevents.GroupName, scheduledevents.EventStart, scheduledevents.Metadata, scheduledevents.updateID, scheduledupdates.updateTime FROM scheduledevents INNER JOIN scheduledupdates ON scheduledupdates.updateID=scheduledevents.updateID WHERE 1 = IF(scheduledevents.EventStart < :fetchDate, 0, 1) AND scheduledevents.EventStart < DATE_ADD(:fetchDate, INTERVAL 2 DAY) ORDER BY scheduledupdates.updateTime desc, scheduledevents.EventStart asc LIMIT 2000) AS T1 ORDER BY T1.EventStart asc, T1.updateTime desc;',
			array(':fetchDate'=>$fetchDate));
	//reorder the resulted rows and build as proper json
	//reorder to have metadata>metadata>venue order, keeping existing order
	
	$keysUsed = array();
	$json = array();
	$venueNames = array();
	
	//find all the venue names and decode the json
	foreach ($rows as $key=>$row) {
		for ($x = 0; $x < 10; $x++) {
			if (isset($rows[$key][$x]))
				unset($rows[$key][$x]);
		}
		
		$rows[$key]['Metadata'] = json_decode($row['Metadata'], true);
		if (isset($rows[$key]['Metadata']['metadata']['venue']))
		$venueNames[$rows[$key]['Metadata']['metadata']['venue']] = $rows[$key]['Metadata']['metadata']['venue'];
	}
	sort($venueNames);
	
	//build final json
	while (!empty($rows)) {
		if (empty($venueNames)) {
			$temp = array_shift($rows);
			if (!isset($keysUsed[json_encode($temp['Metadata']['key'])])) {
				$keysUsed[json_encode($temp['Metadata']['key'])] = 1;
				array_push($json, $temp);
			}
		} else {
			//remove the first instance of the given venue name. If there is none, shift it.
			$firstVenue = $venueNames[0];
			$foundAny = false;
			foreach ($rows as $key=>$value) {
				if (isset($value['Metadata']['metadata']['venue']) && $value['Metadata']['metadata']['venue'] == $firstVenue) {
					$temp = $value;
					unset($rows[$key]);
					
					if (!isset($keysUsed[json_encode($temp['Metadata']['key'])])) {
						$keysUsed[json_encode($temp['Metadata']['key'])] = 1;
						array_push($json, $temp);
					}
					$foundAny = true;
					break;
				}
			}
			if (!$foundAny)
				array_shift($venueNames);
		}
	}
	
	//reorder this by groupname
	$jsonGrouped = array();
	foreach ($json as $key=>$value) {
		if (!isset($jsonGrouped[$value['GroupName']]))
			$jsonGrouped[$value['GroupName']] = array();
		array_push($jsonGrouped[$value['GroupName']], $value);
	}
	
	//echo the resulted json.
	echo(json_encode($jsonGrouped));
?>