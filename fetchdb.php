<?php
require_once 'php-sdk/facebook.php';
require_once 'global_const.php';

	function offset2num($offset) { $n = (int)$offset; $m = $n%100; $h = ($n-$m)/100; return $h*3600+$m*60; }
	
	
	$status = 'new';
	$curtimestring = date("Y-m-d H:i:s");
	$curtimestring1 = time() + 12*60*60;
	$curtimestring = date("Y-m-d H:i:s", $curtimestring1);
	
	
			
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	$querycommand = "SELECT * FROM mistake_post";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	//echo "[";
	//$row = mysql_fetch_assoc( $result );
	//echo $jsondata.",";
	//echo json_encode($result);
	
	while ($row = mysql_fetch_assoc( $result ))
	{
		$jsondata = json_encode($row);
		$array[] = $row;	
		
	}
	echo json_encode($array);
	
	$id = mysql_insert_id();
	
	mysql_close($conn);
	
	
		
	//header( 'Location: user_info.php?id='.$facebook_id') ;
?>
			
