<?php
require_once 'php-sdk/facebook.php';
require_once 'global_const.php';
// Initialize the SDK
// The application parameters
// Try to get the facebook cookie
// If the cookie is available, set the access token into the PHP SDK
$action = $_GET['action'];
if ($action=='logout')
{
	if (ini_get("session.use_cookies")) {
	 $params = session_get_cookie_params();
	 setcookie(session_name(), '', time() - 42000,
	 $params["path"], $params["domain"],
	 $params["secure"], $params["httponly"]
	 );
	 }
	echo "destroy session";
	 // Now we destroy the PHP session
	 session_destroy();
 }
else
{
try
{ 
$token = $_POST['token'];
echo $token;
$facebook->setAccessToken($token);

}
catch  (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

$user = $facebook->getUser();
echo $user;
}
?>

