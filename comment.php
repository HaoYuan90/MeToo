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
	
	
	$mistakeid = $_GET['mistakeid'];
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	
	$querycommand = "SELECT * FROM comment WHERE from_facebook_user_name is NULL";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$userfbid = $row['from_facebook_user_id'];
		$querycommand2 = "SELECT * FROM user_info WHERE user_facebook_id ='".$userfbid."'";
		$result2 = mysql_query($querycommand2, $conn) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
		$querycommand2 = "UPDATE comment SET from_facebook_user_name ='". $row2['user_facebook_name'] . "' WHERE from_facebook_user_id='".$userfbid."'";
		$result2 = mysql_query($querycommand2, $conn) or die(mysql_error());

	}
	
	$querycommand = "SELECT * FROM mistake_post WHERE id='".$mistakeid."'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	$row = mysql_fetch_array($result);
	if ($row['comment_count']==0) 
	{
		
		$servermessage = "[{\"comment_id\":\"-1\",\"mistake_post_id\":\"-1\",\"content\":\"No comment yet, be the first to comment\",\"from_facebook_user_id\":\"-1\",\"date\":\"-1\"}]";
		echo $servermessage;
		break;	
	}
	
	$querycommand = "SELECT * FROM comment WHERE mistake_post_id='".$mistakeid."'";
	
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

break;
case 'POST':
	function offset2num($offset) { $n = (int)$offset; $m = $n%100; $h = ($n-$m)/100; return $h*3600+$m*60; }
	
	
	$status = 'New';
	$curtimestring = date("Y-m-d H:i:s");
	$curtimestring1 = time() + 12*60*60;
	$curtimestring = date("Y-m-d H:i:s", $curtimestring1);
	
	
	$mistakeid = $_POST['mistakeid'];
	$content = $_POST['content'];
	$from_facebook_user_id = $_POST['author'];
	
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	$querycommand = "INSERT INTO comment (mistake_post_id, content, from_facebook_user_id, date) VALUES ('".$mistakeid."','".$content."','".$from_facebook_user_id."','".$curtimestring."')";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	//echo "[";
	//$row = mysql_fetch_assoc( $result );
	//echo $jsondata.",";
	//echo json_encode($result);
	$querycommand = "SELECT * FROM mistake_post WHERE id = '". $mistakeid . "'";	
	$result = mysql_query($querycommand, $conn) or die(mysql_error());

	$row = mysql_fetch_array($result);
	$comment_count = $row['comment_count'];
	
	$comment_count= $comment_count+1;
	//echo $commit_count;
	$querycommand = "UPDATE mistake_post SET comment_count='". $comment_count."'
WHERE id = '".$mistakeid."'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	
	
	// notify the post author that someone commented on his post
	$querycommand = "SELECT * FROM user_info WHERE user_facebook_id='".$from_facebook_user_id."'";
	//echo $querycommand. " $$$";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$authorname = $row['user_facebook_name'];
	
	$notificationcontent = $authorname. " commented on your post.";
	
	$querycommand = "SELECT * FROM mistake_post WHERE id='".$mistakeid."'";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$receiver = $row['from_facebook_userid'];
	if ($receiver!=$from_facebook_user_id)
	{
	$querycommand = "INSERT INTO notification (content, status, facebook_userid, date, mistake_id)
VALUES ('" . $notificationcontent .  "', '" . $status . "', '" . $receiver . "', '"  .  $curtimestring . "', '".   $mistakeid  ."')";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	}
	
	// notify other users that have commented on this post
		$querycommand = "SELECT * FROM comment WHERE mistake_post_id='".$mistakeid."' AND from_facebook_user_id!='".$from_facebook_user_id."' AND from_facebook_user_id!='".$receiver."'";
	
		//echo $querycommand. " $$$";
		$result = mysql_query($querycommand, $conn) or die(mysql_error());
		while ($row = mysql_fetch_assoc($result))
		{
			$commenter =  $row['from_facebook_user_id'];
	
			$notificationcontent = $authorname. " commented on a post you have commented.";
			$querycommand = "INSERT INTO notification (content, status, facebook_userid, date, mistake_id)
VALUES ('" . $notificationcontent .  "', '" . $status . "', '" . $commenter . "', '"  .  $curtimestring . "', '". $mistakeid  . "')";
			$result1 = mysql_query($querycommand, $conn) or die(mysql_error());
		
		}
	
	
	
	
	
	
	
	
	
	
	
	mysql_close($conn);








	
break;}
?>