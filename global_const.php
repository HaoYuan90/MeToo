<?php
require_once 'php-sdk/facebook.php';
$FB_APP_ID = '152141811543304';
$FB_SECRET = '78bb98991025f347bac7cc2a567c822b';
$facebook = new Facebook(array(
	'appId' => $FB_APP_ID,
	'secret' => $FB_SECRET,
	'cookie' => true,
	));

$INDEX_URL = 'http://ec2-50-17-68-237.compute-1.amazonaws.com/MobileCloud/';	
$SESSION_TIMEOUT = 42000;
//
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'improve';
$DB_NAME ="MobileCloud";
?>