<?php
require_once 'php-sdk/facebook.php';
require_once 'global_const.php';
// Initialize the SDK
// The application parameters

$user = $facebook->getUser();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" manifest=cache.manifest>
    <head>
	<meta name="apple-mobile-web-app-capable" content="yes" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Me,Too!</title>
		<div id="fb-root"></div>
		<script type="text/javascript" src="jquery-1.6.2.min.js"></script>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<link rel="apple-touch-icon" sizes="72x72" href="images/icon.jpg" />
<link rel="apple-touch-startup-image" href="images/splash.png">
<script>
  FB.init({
    appId  : '152141811543304',
    status : true, // check login status
    cookie : true, // enable cookies to allow the server to access the session
    xfbml  : true, // parse XFBML
		oauth  : false // we're not ready for OAuth 2.0

  });
</script>
    </head>
    <body  style='background-color:black;background-image:url("images/concept.png");background-repeat: no-repeat;'>
        <div id="fb-root"></div>
        <script type="text/javascript">
		
			if ( !navigator.onLine ) {
				window.location = "appPage.html";
					
			}
			
			
			
			
			
		 FB.init({appId: '152141811543304', status: true, cookie: true, xfbml: true});
		
		 
            window.fbAsyncInit = function() {
                FB.init({appId: '152141811543304', status: true, cookie: true, xfbml: true});

                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
					
						var token = response.session.access_token;				
						var getid = response.session.uid;
						
						
						FB.api('/me', function(response) {						
							
							
							// send user information to server
							$.ajax({
								type: "POST",
								url: "user/",
								data: "fb-username=" + response.name + "&fb-email=" + response.email + "&fb-id=" + getid,
								 success: function (data) {
								},
								error: function (hqXHR, textStatus, errorThrown) {
									//alert("fail0 " + hqXHR + " " + textStatus + " " + errorThrown);
								}					
							});			

						
					
						$.ajax({
								type: "POST",
								url: "user/token",
								data : "token=" + token,
								 success: function (response) {
									//alert(response);
									console.log(response);
									//alert($("#newmistake").serialize());
								},
								error: function (hqXHR, textStatus, errorThrown) {
									//alert("fail1 " + hqXHR + " " + textStatus + " " + errorThrown);
								}					
							});			
					window.location = "appPage.html";
					
                });
				});
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
					$.ajax({
								type: "GET",
								url: "user/logout",
								
								 success: function (data) {
								},
								error: function (hqXHR, textStatus, errorThrown) {
									//alert("fail0");
								}					
							});			

                });
				
	
				
                FB.getLoginStatus(function(response) {
                    if (response.session) {
                        // logged in and connected user, someone you know
												var token = response.session.access_token;				

						$.ajax({
								type: "POST",
								url: "user/token",
								data : "token=" + token,
								 success: function (response) {
									//alert(response);
									console.log(response);
									//alert($("#newmistake").serialize());
									window.location="appPage.html";
								},
								error: function (hqXHR, textStatus, errorThrown) {
									//alert("fail2 " + hqXHR + " " + textStatus + " " + errorThrown);
								}					
							});			
						
											
                    }
					else 
					{
							
					}
                });
            
			};
			
			
			
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());

           
            
        </script>

<div style = "position:absolute; top:450px; left:45%;"><fb:login-button autologoutlink="true" perms="email,status_update,publish_stream, offline_access"></fb:login-button></div>
        
        

 

</html>