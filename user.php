<?php
require_once 'php-sdk/facebook.php';
require_once 'global_const.php';
switch ( $_SERVER[ 'REQUEST_METHOD' ] ) {
case 'GET':
	
	function offset2num($offset) { $n = (int)$offset; $m = $n%100; $h = ($n-$m)/100; return $h*3600+$m*60; }
	
	
	$status = 'new';
	$curtimestring = date("Y-m-d H:i:s");
	$curtimestring1 = time() + 12*60*60;
	$curtimestring = date("Y-m-d H:i:s", $curtimestring1);
	
	$userid = $_GET['userid'];
	
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	//
	mysql_set_charset('utf8',$conn); 
	$querycommand = "SELECT * FROM user_info WHERE user_facebook_id='".$userid."'";
	
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	
	$row = mysql_fetch_assoc( $result );
	echo json_encode($row);
	
	
	
	
	mysql_close($conn);




break;
case 'POST':


break;
case 'PUT':
parse_str( file_get_contents( 'php://input' ) , $_PUT );
	
break;
case 'DELETE':
parse_str( file_get_contents( 'php://input' ) , $_DELETE );
	



break;}
?>