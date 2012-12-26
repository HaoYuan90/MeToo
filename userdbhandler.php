<?php
	require_once 'global_const.php';
	$facebook_id = $_POST['fb-id'];
	$facebook_name = $_POST['fb-username'];
	$facebook_email = $_POST['fb-email'];
	
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_set_charset('utf8',$conn); 
	mysql_select_db($DB_NAME) or die(mysql_error());
	$querycommand = "INSERT IGNORE INTO user_info (user_facebook_id,user_facebook_name, email) VALUES ('" . $facebook_id .  "', '" . $facebook_name . "', '" . $facebook_email .  "')";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	mysql_close($conn);
?>
			
