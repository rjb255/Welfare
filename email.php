<?php
	// CHANGE WHEN MIGRATING
	$welfareEmail = "rjb255@cam.ac.uk";		//i.e. email of the welfare officers
	require("/public/home/rjb255/fwrk1/sensitive.php"); //passwords and stuff for the database
	require("/public/home/rjb255/widgets/gym/1welfare/scrambler.php");
	
	$prequel = "rjb255-welfareID";
	//////////////////////////////////////////////////////
	$header = "";
	$message = "";
	$f = "";
	$pos = 0;
	$emailPattern = '[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,6}(?!.*[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,6})/';
	while($f = fgets(STDIN)){
		
		switch ($pos){
			case 0:
				if (preg_match("/^From: /", $f)){
					if (preg_match("/" . $emailPattern, $f, $fromOrig)) { $pos++; };
				}
				break;
			case 1:
				if (preg_match("/^To: /", $f)){
					if (preg_match("/" . $emailPattern, $f, $toOrig)) { $pos++; }
				}
				break;
			case 2:
				if (preg_match("/^Subject: /", $f)){
					$subject = substr($f,9);
					$pos++;
				}
				break;
			case 3:
				if ($f == "\n"){
					$pos++;			
				}
				if (preg_match("/^Content-Type: /", $f)){
					
					$header = str_replace(array("\n",";"),"",$f) . "\r\n";
					
					// while (preg_match("/;\n$/",$f)){
						// $f = fgets(STDIN);
						// $header .= $f;
					// }
				}
				break;
			case 4:
				if (!preg_match("/^To: .*$emailPattern", $f)){
					$message .= $f;
				}
				break;
		}
	}
	
	
	preg_match("/" . $prequel . "[0-9]+@srcf.net/", $toOrig[0], $matches);
	$from = $matches[0];
	$to = "";
	$temp = find($matches[0]);
	$x = 0;
	
	if (strpos($fromOrig[0], $welfareEmail) !== FALSE) {
		$to = $temp;
		$x = 1;
	} else if (strpos($fromOrig[0], $temp) !== FALSE) {
		$to = $welfareEmail;
	} else {
		mail($fromOrig[0],"RE: " . $subject,"Your email address does not match this email adress in our records.
		 Please use the same email address which you gave us.\nThank you\nThe Welfare Team", "From: " . $from);
		 exit();
	}
	$header .= "From: $from" . "\r\n";
	mail($to,$subject,$message,$header);
	if ($x == 0){
		mail($fromOrig[0],"RE: " . $subject,"
		Thank you for your email. We will be in contact soon. If you have any issues, please do not hesitate to reply to this email.
		","From: " . $from . "\r\n");
	}
?>