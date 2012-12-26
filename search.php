<?php
require_once 'php-sdk/facebook.php';
require_once 'global_const.php';
switch ( $_SERVER[ 'REQUEST_METHOD' ] ) {
case 'GET':
	function offset2num($offset) { $n = (int)$offset; $m = $n%100; $h = ($n-$m)/100; return $h*3600+$m*60; }
	
	
	
	$search = $_GET['search'];
	$searchtype = $_GET['type'];
	
	$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die                      ('Error connecting to mysql');
	mysql_select_db($DB_NAME) or die(mysql_error());
	
	if ($searchtype=='mistake') 	$querycommand = "SELECT * FROM mistake_post WHERE  MATCH(description) AGAINST ('".$search."') ORDER BY post_date DESC";
	else if ($searchtype=='user')   $querycommand = "SELECT * FROM user_info WHERE  MATCH(user_facebook_name) AGAINST ('".$search."' IN BOOLEAN MODE) ORDER BY user_facebook_id DESC";
	$result = mysql_query($querycommand, $conn) or die(mysql_error());
	
	//echo "[";
	//$row = mysql_fetch_assoc( $result );
	
	//echo json_encode($result);
	$flag = true;
	
	
		$array = null;
		while ($row = mysql_fetch_assoc( $result ))
		{
			$flag = false;
			$jsondata = json_encode($row);
			$array[] = $row;			
		}
		echo json_encode($array);
	
	if ($flag==true) echo "No results found.";
	
	
	mysql_close($conn);

break;
}
?>