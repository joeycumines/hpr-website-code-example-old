<?php
	//include this to access all database related generic methods
	$DB_CONNECTION_STRING = '';
	$DB_USERNAME='';
	$DB_PASSWORD='';
	
	function getNewPDO() {
		global $DB_CONNECTION_STRING, $DB_USERNAME, $DB_PASSWORD;
		$pdo = new PDO($DB_CONNECTION_STRING, $DB_USERNAME, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}
	
	function runUpdatePrepared($pdo, $queryString, $prepArray) {
		$sth = $pdo->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute($prepArray);
	}
	
	function runQueryPrepared($pdo, $queryString, $prepArray) {
		$sth = $pdo->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute($prepArray);
		return $sth->fetchAll();
	}
	
	function runQuery($pdo, $queryString) {
		$stmt = $pdo->query($queryString);
		return $stmt;
	}
	
	function validate($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		
		//empty string or variable results in null, for easy comparison
		if (empty($data))
		return null;
		
		return $data;
	}
	
	function validateUserPass($userName, $userPass) {
		try {
			$dbh = getNewPDO();
			$resultsRows = null;
			$sql = 'SELECT Email, Admin, ConfirmationString
			FROM members
			WHERE Email = :email AND PasswordHash = SHA2(CONCAT(:password, PasswordSalt), 256)';
			$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':email' => $userName, ':password' => $userPass));
			$resultsRows = $sth->fetchAll();
			foreach($resultsRows as $row)
			return $row;
			
			} catch (PDOException $e) {
		}
		
		return null;
	}
	
	function getAccessLevel($userName) {
		try {
			$dbh = getNewPDO();
			$resultsRows = null;
			$sql = 'SELECT AccessLevel
			FROM members
			WHERE Email = :email;';
			$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':email' => $userName));
			$resultsRows = $sth->fetchAll();
			foreach ($resultsRows as $row)
			return $row['AccessLevel'];
			
			} catch (PDOException $e) {
		}
		
		return 0;
	}
	
?>
