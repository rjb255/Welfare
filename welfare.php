<?php
	
	// CHANGE THE FOLLOWING PARTS WHEN IMPLEMENTING
	$email = "rjb255@srcf.net";
	$subject = "Welfare Testing";
	///////////////////////////////////////////////
		
	require("scrambler.php");
	
	$error = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$anon = clean($_POST['anon']);
		$name = ($anon == "on") ? "" : clean($_POST['name']);
		$offi = clean($_POST['Officer']);
		$emai = clean($_POST['email']);
		$mess = clean($_POST['message']);
		$_POST = array();
		if (empty($mess) or $mess == ""){
			$error .= "Please Enter a Message\n";
		}
		if (!($emai == "") && !filter_var($email, FILTER_VALIDATE_EMAIL)){
			$error .= "The entered email is not valid. NB email may be left blank.\n";
		}
		if ($error == ""){
			if ($anon == "on" && $emai != ""){
				$emai = scrambler($emai);
			}			
			mail($email, $subject,
				"Recipient: " 	. $offi .
				"\nAnonymity: " . $anon . 
				"\nName: " 		. $name . 
				"\nMessage: "	. $mess,
				"From: " . $emai . "\r\n");

		}
		echo $error;
		exit();
		
	}
	
	function clean($input){
		$input = trim($input);
		$input = htmlspecialchars($input);
		return $input;
	}
?>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>

<style>
	#welfareFormGym .wide{
		width: 300px;
	}
	#welfareFormGym{
		width:300px;
	}
	
	
</style>




<form id = "welfareFormGym">
	<label> Name: </label><br>
	<input class = "wide" type = "text" name = "name"></input><br><br>
	<label> Email: </label><br>
	<input class = "wide" type = "email" name = "email"></input><br><br>
	<label> Intended Officer: </label><br>
	<select class = "wide" name = "Officer">
		<option value = "B">Both</option>
		<option value = "M&NB">Male and Non-Binary</option>
		<option value = "F&NB">Female and Non-Binary</option>
	</select><br><br>
	<label> Anonymous Submission (*): </label>
	<input type = "checkbox" checked name = "anon"></input><br><br>
	<label>Message:</label><br>
	<textarea class = "wide" rows = "10" name = "message"></textarea><br><br>
	<input type = "submit" name = "SubmitButton" value = "Submit" id = "WelfareSubmit"></input>
	<p> * <i>(Anonymous by default. Your name will not be stored, and your email will be hidden from us)</i>
	<p style = "color:red;" id = "welfareError"></p>
</form>

<script>
	$('#WelfareSubmit').click(function(e){
		
		e.preventDefault();

		$.post({
			url: window.location.href.match(/^[^(\?|#)]+/)[0],
			data: $('#welfareFormGym').serialize(),
			success:function(data){
				
				//whatever you wanna do after the form is successfully submitted
				if (data == ""){
					$("#welfareFormGym").trigger("reset");
					window.alert("You have successfully submitted your issue.\nWe will do our best to help.");
				}
				$("#welfareError").html(data);
				
				
			},
			dataType: "html"
		});
	});
</script>