<?php

require_once 'php-sdk/facebook.php';
require_once 'global_const.php';
// Initialize the SDK
// The application parameters

	
$user = $facebook->getUser();
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>new mistake</title>
		
    
	
	<script type="text/javascript" src="jquery-1.6.2.min.js"></script><script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>

           <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js"></script>
           <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
           <script src="dateTime/jquery.ui.datetimepicker.min.js" type="text/javascript"></script>
           <script type="text/javascript">
              $(function() {
                 $('#expiryDate').datetimepicker();
             });
          </script> 
	<script src="ga.js" language="javascript" type="text/javascript"></script>		  
    </head>
	<body>
	
	<div id="fb-root"></div>
	<script type="text/javascript">

	// add place holder for the textarea
	$(document).ready(function(){

    $(".watermark").each(function(){
       $(this).val($(this).attr('placeholder'));
	     $(this).css('color', '#676767');
	   
    });

    $(".watermark").focus(function(){
		
        var placeholder = $(this).attr('placeholder');
        var current_value = $(this).val();
        $(this).css('color', '#192750');
        if(current_value == placeholder) {
            $(this).val('');
        }

    });

    $(".watermark").blur(function(){

        var placeholder = $(this).attr('placeholder');
        var current_value = $(this).val();

        if(current_value == '') {
            $(this).val(placeholder);
            $(this).css('color', '#676767');
        }

    });
})

	//// confirm and validate input
	
	function PutIntoDB()
	{
		
		alert("yes");
		$.ajax({
								type: "POST",
								url: "dbhandler.php",
								data: $("#newmistake").serialize(),
								 success: function (data) {
									alert($("#newmistake").serialize());
								},
								error: function (hqXHR, textStatus, errorThrown) {
								}					
							});			
	
	}
	
	
		window.fbAsyncInit = function() {
                FB.init({appId: '261624167189277', status: true, cookie: true, xfbml: true});

                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
					
					document.getElementById('newpostform').style.visibility="visible";
					var getid = response.session.uid;
					document.getElementById('facebook_id').value = getid;
					document.getElementById('profilepic').src = "http://graph.facebook.com/" + getid + "/picture";
					document.getElementById('profilepic').style.visibility="visible";
					document.getElementById('my_page').style.display="inline";
                    //login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
					document.getElementById('newpostform').style.visibility="hidden";
					document.getElementById('profilepic').style.visibility="hidden";
					document.getElementById('my_page').style.display="none";
                    //logout();
                });
				
	
				
                FB.getLoginStatus(function(response) {
                    if (response.session) {
                        // logged in and connected user, someone you know
						var getid = response.session.uid;
                        document.getElementById('facebook_id').value = getid;
						//alert(response.session.access_token);
						document.getElementById('newpostform').style.visibility="visible";
						

					document.getElementById('profilepic').src = "http://graph.facebook.com/" + getid + "/picture";
					document.getElementById('profilepic').style.visibility="visible";
					
						var accesstoken = response.session.access_token;
						//var getid = response.session.uid;
						
						var dataJSON = { "action" : "post_token",
										 "security": "SECURITY_TOKEN",
										 "contents": accesstoken,
										 "id": getid
										 };						
											
                    }
					else 
					{
						document.getElementById('newpostform').style.visibility="hidden";
						document.getElementById('profilepic').style.visibility="hidden";
						document.getElementById('my_page').style.display="none";
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
	
	<div class="top"></div>
	<center>
	
		
		
		
 
		
		
		
		
	<div id="fb-root" style="display:inline;"></div>
	<fb:login-button autologoutlink="true" perms="email,user_birthday,status_update,publish_stream,user_about_me,offline_access,read_stream"></fb:login-button>
	<div class="main">
		<script type="text/javascript">
		function submithandler()
		{
			//window.location = "search.php";
			
		}
		</script>
		
		<div class="content">
		<form id="newmistake" name="input" action="" >
					
			<textarea id="message" style="font-family: Arial" rows="1" cols="60" name="message" class ="watermark" placeholder="Today I have...">
			</textarea><br/>
			    
			
			
			
			<input type="hidden" id="facebook_id" name="facebook_id"/>

				
			
			<input type="checkbox" id="post_to_wall" name="post_to_wall" value="1" checked="checked">Post this on my wall too<br>

			<input type="button" value="Commit" onclick="PutIntoDB()" />
		</form> 
		
		
		</div>	
		</div>
		</center>
		
	</body>
</html>