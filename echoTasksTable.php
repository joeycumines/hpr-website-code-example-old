<?php
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
	
	// Define the custom sort function
	function custom_sort($a,$b) {
		return $a['life']>$b['life'];
	}
?>