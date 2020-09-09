<?php
/////////////////////////////////////////////////////////////////////////////////
	require_once("/public/home/rjb255/fwrk1/sensitive.php");
/////////////////////////////////////////////////////////////////////////////////


	$table = "emailScram";
	$conn = new mysqli(servername(), username(), password(), dbname());
	$query = "SELECT 1 FROM `$table` LIMIT 1";
	$val = $conn -> query($query);
	if ($val === FALSE){
		$query = "CREATE TABLE `emailScram` ( `ID` INT NOT NULL AUTO_INCREMENT , `dummy_email` TEXT NOT NULL , `email` TEXT NOT NULL , `lastMailStamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`ID`)) ENGINE = InnoDB";
		$conn -> query($query);	
	}
	$conn ->close();

	function scrambler($email){
		
		$table = "emailScram";
		$conn = new mysqli(servername(), username(), password(), dbname());
		
		//Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$sql = "SELECT MAX(`ID`) FROM `$table`";
		
		$r = $conn->query($sql);
		$f = $r -> fetch_row();
		$scram = $f[0] + 1;
		$scramEmail = "rjb255-welfareID" . $scram . "@srcf.net"; // Change for CUOGCS
		
		$email = bin2hex($email);
		
		$stmt = $conn->prepare("INSERT INTO `$table` (`ID`, `dummy_email`, `email`) VALUES ('$scram','$scramEmail', ? )");
		$stmt->bind_param("s", $semail);
		$semail = $email;
		$stmt->execute();
		
		$conn->close();
		return $scramEmail;
	}
	
	function find($scramEmail){
		$table = "emailScram";
		$conn = new mysqli(servername(), username(), password(), dbname());
		
		//Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$stmt = $conn->prepare("SELECT `email` FROM `$table` WHERE `dummy_email` = (?)");
		$stmt->bind_param("s", $email);
		$email = $scramEmail;
		
		$stmt->execute();
		$r = $stmt -> get_result();
		$f = $r -> fetch_row();
		
		$conn->close();
		
		$f[0] = hex2bin($f[0]);
		
		return $f[0];
	}
	
?>