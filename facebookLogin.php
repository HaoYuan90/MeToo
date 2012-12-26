<?php 
    require_once 'php-sdk/facebook.php';
    
    // Method to get the facebook cookie
    // The facebook cookie is created when you login with the Javascript SDK
    function get_facebook_cookie($app_id, $app_secret) {
        if (!array_key_exists('fbs_' . $app_id,$_COOKIE)) {
            return null;
        }
        $args = array();
        parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
        ksort($args);
        $payload = '';
        foreach ($args as $key => $value) {
            if ($key != 'sig') {
                $payload .= $key . '=' . $value;
            }
        }
        if (md5($payload . $app_secret) != $args['sig']) {
            return null;
        }
        return $args;
    }
    
    // The application parameters
    $fb_app_id = '152141811543304';
    $fb_secret = '78bb98991025f347bac7cc2a567c822b';
    
    // The url that contains the logout code
   $logout_url = 'http://ec2-50-17-68-237.compute-1.amazonaws.com/MobileCloud/facebookLogin.php';
    
    // The url that the user should be redirected to after logging out
    //$logout_redirect_url = 'http://ec2-50-17-68-237.compute-1.amazonaws.com/MobileCloud/facebookLogin.php';
    
    // Initialize the SDK
    $facebook = new Facebook(array(
                                   'appId' => $fb_app_id,
                                   'secret' => $fb_secret,
                                   ));
    
    // The start of our logout code.
    // First, check if there is a GET/POST parameter named "action"
    if (array_key_exists('action', $_GET)) {
        
        // If the action is to logout
        if ($_GET['action'] == "logout") {
            
            // Reset the global $_SESSION array which contains all the session variables
            // This array is used by the PHP SDK
            $_SESSION = array();
            
            // Kill the session cookies (if any)
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();			
                setcookie(session_name(), '', time() - 42000,
                          $params["path"], $params["domain"],
                          $params["secure"], $params["httponly"]
                          );
            }
            
            // Now we destroy the PHP session
            session_destroy();
            
            // Redirect the user to specified url
            echo "<script> top.location.href='" . $logout_redirect_url . "'</script>";
            
            // Stop PHP script execution.
            // Our job here is done
            exit();
        }
    }
    
    // Try to get the facebook cookie
    $fb_cookie = get_facebook_cookie($fb_app_id, $fb_secret);
    
    if ($fb_cookie) {
        // If the cookie is available, set the access token into the PHP SDK
        $facebook->setAccessToken($fb_cookie['access_token']);
    }
    
    // Get the user object
    $user = $facebook->getUser();
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <title>CS3216 Standalone Application - Using PHP and Javascript SDK</title>
        <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
    </head>
    
    <body>
        <div id="fb-root"></div>
        <script type="text/javascript">
            
            var logged_in = false;
            
            // Initialize the FB SDK
            FB.init({
                    appId  : '<?=$fb_app_id?>',
                    status : true, // check login status
                    cookie : true, // enable cookies to allow the server to access the session
                    xfbml  : true,  // parse XFBML
                    oauth  : false // we're not ready for OAuth 2.0
                    });
            
            // Register an event to refresh the current page when the user logs in
            FB.Event.subscribe('auth.login', function(response) {
                               window.location.reload(true);
                               });
            
            // Register an event to go to the logout url when the user logs out
            FB.Event.subscribe('auth.logout', function(response) {
                               window.location.href = '<?=$logout_url?>?action=logout';
                               });
            
            // Note that we are registering the above events instead of 
            // auth.sessionChange, like we did in standalone_js_app.php, 
            // because we're handling login and logout differently
            
            // XXX: Some things will change when OAuth is enabled completely. 
            // See: https://developers.facebook.com/blog/post/525
            // and: http://developers.facebook.com/docs/reference/javascript/FB.Event.subscribe/
            
            // Check the current login status
            FB.getLoginStatus(function(response) {
                              
                              if (response.session) {
                              // User is logged in
                              // Display the logout button
                              var logout_link = document.getElementById("fb_logout_link");
                              logout_link.style.display="inline";
                              logged_in = true;
                              } else {
                              // User is logged out
                              // Display the login button
                              var login_link = document.getElementById("fb_login_link");
                              login_link.style.display="inline";
                              }
                              
                              });
            
            function fb_login() {
                var fb_perms_required = ''; // Comma-separated list of required permissions
                FB.login(function(response) {
                         if (response.session) {
                         logged_in = true;
                         if (response.perms) {
                         // User is logged in and some permissions
                         // are granted
                         } else {
                         // User is logged in and no permissions 
                         // are granted			
                         }
                         } else {
                         // user is not logged in
                         logged_out = false;
                         }
                         }, {perms:fb_perms_required});
            }
            
            function fb_logout() {
                FB.logout(function(response) {
                          // Logged out
                          // Don't have to do anything, the page will automatically refresh
                          });
            }
            
            </script>
        
        <p>Hello World<br/></p>
        
        <p>
        User login/logout with the Javascript SDK alone:
        <a id="fb_login_link" style="display:none" href="javascript:fb_login();">Login</a>
        <a id="fb_logout_link" style="display:none" href="javascript:fb_logout();">Logout</a>
        <br/>
        </p>
        
        <?php if (!$user) { ?>
        <p>
        User login with the FB button:
        <fb:login-button length="long" background="light" size="medium"></fb:login-button>
            <br/>
            </p>
            <?php } // end if (!$user) ?>
            
            
            <?php
                // If the user is logged in
                if ($user) {
                    $user_details = $facebook->api('/me');
                    $user_name = $user_details['name'];
                ?>
            
            <p>
            My name is <?=$user_name?>. This is retrieved using the PHP SDK<br/>
            My name is <span id="name_area">loading...</span>. This is retrieved using the Javascript SDK
            </p>
            
            <script type="text/javascript">
                FB.api('/me', function(response) {
                       // Update the place where our name should go
                       var name_field = document.getElementById("name_area");
                       name_field.innerText = response.name;
                       });
                </script>
            <?php } // end if ($user) ?>
    </body>
</html>
