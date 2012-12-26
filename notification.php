<?php
require_once 'php-sdk/facebook.php';
require_once 'global_const.php';


switch ( $_SERVER[ 'REQUEST_METHOD' ] ) {
case 'GET':
	function offset2num($offset) { $n = (int)$offset; $m = $n%100; $h = ($n-$m)/100; return $h*3600+$m*60; }
	
	
	
	
	$action = $_GET['action'];
	//if (($action=="0") || ($action=="20"))$mistakeid = $_GET['mistakeid'];
	
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	mysql_set_charset('utf8',$conn); 
	//
	$userid = $_GET['userid'];
	if ($action=="new") $querycommand = "SELECT * FROM notification WHERE facebook_userid='".$userid."' AND status='New' ORDER BY date DESC";
	else if ($action=="all") $querycommand = "SELECT * FROM notification WHERE facebook_userid ='".$userid."' ORDER BY date DESC";
	
	
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	$num_rows = mysql_num_rows($result);
	if ($num_rows== NULL)
	{
		// do sth
		echo "[{\"id\":\"-1\",\"content\":\"You have no notification yet.\",\"status\":\"Checked\",\"facebook_userid\":\"-1\",\"date\":\"-1\",\"mistake_id\":\"-1\"}]";
		break;
	}
	
	
	//echo "[";
	//$row = mysql_fetch_assoc( $result );
	//echo $jsondata.",";
	//echo json_encode($result);
	//$postcount=0;
	$array = null;
	while ($row = mysql_fetch_assoc( $result ))
	{
		$jsondata = json_encode($row);
		$array[] = $row;	
		//$postcount++;
		//if ($postcount>20) break;
	}
	if ($array!=null) echo json_encode($array);
	else 
	{
		$array = array("message"=> "No new notification");
		echo json_encode($array);
	
	}
	
	$querycommand = "UPDATE notification  SET status='Checked' WHERE status='New' AND facebook_userid='".$userid."'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());

	
	
	
	mysql_close($conn);




break;
case 'POST':

	function offset2num($offset) { $n = (int)$offset; $m = $n%100; $h = ($n-$m)/100; return $h*3600+$m*60; }
	
	
	$status = 'New';
	$curtimestring = date("Y-m-d H:i:s");
	$curtimestring1 = time() + 12*60*60;
	$curtimestring = date("Y-m-d H:i:s", $curtimestring1);
	
	$content = $_POST['content'];
	$facebook_userid = $_POST['receiver'];
	$mistakeid = $_POST['mistakeid'];
			
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	mysql_set_charset('utf8',$conn); 

	$querycommand = "INSERT INTO notification (content, status, facebook_userid, date, mistake_id)
VALUES ('" . $content .  "', '" . $status . "', '" . $facebook_userid . "', '"  .  $curtimestring . "', '" . $mistakeid ."')";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());

	$id = mysql_insert_id();
	
	mysql_close($conn);
	
		
	
break;
case 'PUT':
parse_str( file_get_contents( 'php://input' ) , $_PUT );
	


	$curtimestring = date("Y-m-d H:i:s");
	$curtimestring1 = time() + 12*60*60;
	$curtimestring = date("Y-m-d H:i:s", $curtimestring1);
	
	
	$facebook_userid = $_GET['userid'];
	
			
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	$querycommand = "UPDATE notification  SET status='Checked' WHERE status='New' AND facebook_userid='".$facebook_userid."'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());

	
	
	mysql_close($conn);


break;}
?>