<?php
	$datetime = new DateTime; // current time = server time
	$otherTZ  = new DateTimeZone('Australia/Sydney');
	$datetime->setTimezone($otherTZ); // calculates with new TZ now
	echo($datetime->format('Y-m-d H:i'));
?>