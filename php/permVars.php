<?php
	require_once(DIR_PHP.'databaseTools.php');
	//this defines PERM_VARS //value is first value of a jsonarray
	$tempPerm = array();
	try {
		$dbh = getNewPDO();
		$resultsRows = null;
		$sql = 'SELECT *
			FROM permvars;';
		$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array());
		$resultsRows = $sth->fetchAll();
		foreach ($resultsRows as $row) {
			$vName = $row['id'];
			$vVal = null;
			try {
				$vVal = json_decode($row['value'], true)[0];
			} catch (Exception $e) {}
			if (!empty($vVal)) {
				$tempPerm[$vName] = $vVal;
			}
		}
		
	} catch (PDOException $e) {
	}
	$GLOBALS['PERM_VARS'] = $tempPerm;
	
	//function to update
	function updatePermVars() {
		try {
			$dbh = getNewPDO();
			//attempt to update all
			$sql = 'UPDATE permvars SET value = :fieldvalue WHERE id = :fieldname;';
			$sth = $dbh->prepare($sql);
			$sql2 = 'INSERT INTO permvars (id, value) VALUES (:fieldname, :fieldvalue);';
			$sth2 = $dbh->prepare($sql2);
			foreach ($GLOBALS['PERM_VARS'] as $key=>$value) {
				try {
					$sth2->execute(array(':fieldname' => $key, ':fieldvalue' => json_encode(array(0=>$value))));
				} catch (PDOException $e) {
					//attempt to update
					$sth->execute(array(':fieldname' => $key, ':fieldvalue' => json_encode(array(0=>$value))));
				}
			}
		} catch (PDOException $e) {
			echo($e->getMessage());
		}
	}
?>