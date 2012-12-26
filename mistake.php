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
	
	$action = $_GET['action'];
	if (($action=="0") || ($action=="20") || ($action=="commit"))$mistakeid = $_GET['mistakeid'];
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	//
	mysql_set_charset('utf8',$conn); 

	if ($action=="0") $querycommand = "SELECT * FROM mistake_post WHERE id='".$mistakeid."'";
	else if ($action=="20") $querycommand = "SELECT * FROM mistake_post WHERE id<'".$mistakeid."' ORDER BY id DESC";
	else if ($action=="user")
	{
		$userid = $_GET['userid'];
		$querycommand = "SELECT * FROM mistake_post WHERE from_facebook_userid='".$userid."'";	
	}
	else if ($action=="like")
	{
		$querycommand = "SELECT * FROM mistake_post WHERE id = '". $mistakeid . "'";
		$result = mysql_query($querycommand, $conn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$like_count = $row['like_count'];
	
		$like_count= $like_count+1;
		//echo $commit_count;
		$querycommand = "UPDATE mistake_post SET like_count='". $like_count."'
WHERE id = '".$mistakeid."'";
		$result = mysql_query($querycommand, $conn) or die(mysql_error());
		$querycommand = "SELECT * FROM mistake_post WHERE id = '". $mistakeid . "'";
	}
	else if ($action=="commit") $querycommand = "SELECT * FROM mistake_post WHERE id = '". $mistakeid . "'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	$num_rows = mysql_num_rows($result);
	if ($num_rows== NULL)
	{
		// do sth
		echo "[{\"id\":\"-1\",\"from_facebook_userid\":\"-1\",\"description\":\"You have no mistake yet :-) Why don't create one? \",\"status\":\"-1\",\"post_date\":\"-1\",\"type\":\"-1\",\"like_count\":\"-1\",\"comment_count\":\"-1\",\"commit_count\":\"-1\"}]";
		break;
	}
	
	
	
	//echo "[";
	//$row = mysql_fetch_assoc( $result );
	//echo $jsondata.",";
	//echo json_encode($result);
	$postcount=0;
	while ($row = mysql_fetch_assoc( $result ))
	{
		$jsondata = json_encode($row);
		$array[] = $row;	
		$postcount++;		
	}
	if ($action!="commit") echo json_encode($array);
	
	if ($action=="commit")
	{
		$querycommand = "SELECT * FROM commit_time WHERE mistake_id='".$mistakeid."'";
		$result = mysql_query($querycommand, $conn) or die(mysql_error());
		$array1 = null;
		while ($row = mysql_fetch_assoc( $result ))
		{
			$jsondata = json_encode($row);
			$array1[] = $row;				
		}
		if ($array1!=null) echo json_encode($array1);
	}
	
	
	
	
	mysql_close($conn);




break;
case 'POST':

	function offset2num($offset) { $n = (int)$offset; $m = $n%100; $h = ($n-$m)/100; return $h*3600+$m*60; }
	$description = mysql_real_escape_string(trim($_POST['message']));
	$description = $_POST['message'];
	$facebook_id = $_POST['facebook_id'];
	echo "<script type=\"text/javascript\"> alert('".$facebook_id."')</scr". "ipt>";
	$status = 'new';
	$curtimestring = date("Y-m-d H:i:s");
	$curtimestring1 = time() + 12*60*60;
	$curtimestring = date("Y-m-d H:i:s", $curtimestring1);
	
	$post_on_wall = $_POST['post_to_wall'];
	$type = "mistake";
			
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	mysql_set_charset('utf8',$conn); 

	$querycommand = "INSERT INTO mistake_post (from_facebook_userid,description,type,status,post_date)
VALUES ('" . $facebook_id .  "', '" . $description . "', '" . $type . "', '" . $status . "', '"  .  $curtimestring . "')";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());

	$id = mysql_insert_id();
	
	mysql_close($conn);
	
	
		
	
	$post_to_wall = $_POST['post_to_wall'];
	if ($post_to_wall=="1")
	{
		$user = $facebook->getUser();
		echo 'USER :'.$user;
		$params = array(
			'message' => $description,
			'link' => $INDEX_URL.$id,
			'name' => 'You too?',
			'caption' => 'New mistake',
			'description' => 'See more of my mistakes here :-)',
		);
		$post_id = $facebook->api("/".$user. "/feed", "POST", $params);
	}			
		
	//header( 'Location: user_info.php?id='.$facebook_id') ;
break;
case 'PUT':
parse_str( file_get_contents( 'php://input' ) , $_PUT );
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	mysql_set_charset('utf8',$conn); 

	$mistakeid = $_GET['mistakeid'];
	//echo $mistakeid;
	$querycommand = "SELECT * FROM mistake_post WHERE id = '". $mistakeid . "'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$commit_count = $row['commit_count'];
	
	$commit_count= $commit_count+1;
	//echo $commit_count;
	$querycommand = "UPDATE mistake_post SET commit_count='". $commit_count."'
WHERE id = '".$mistakeid."'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	
	
	$curtimestring = date("Y-m-d H:i:s");
	$curtimestring1 = time() + 12*60*60;
	$curtimestring = date("Y-m-d H:i:s", $curtimestring1);
	
	$querycommand = "INSERT INTO commit_time VALUES ('".$mistakeid."','".$curtimestring."')";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	mysql_close($conn);
break;
case 'DELETE':
parse_str( file_get_contents( 'php://input' ) , $_DELETE );
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	$mistakeid = $_GET['mistakeid'];
	//echo $mistakeid;
	$querycommand = "DELETE FROM mistake_post WHERE id = '". $mistakeid . "'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	mysql_close($conn);



break;}
?>