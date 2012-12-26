
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php
            require_once 'php-sdk/facebook.php';
            require_once 'global_const.php';
            // Initialize the SDK
            // The application parameters.
            
            $user = $facebook->getUser();
            $mistakeid = $_GET['mistakeid'];
            $likeURL = "http://ec2-50-17-68-237.compute-1.amazonaws.com/MobileCloud/viewmistake/".$mistakeid;
            ?>

        <title>new mistake</title>	
        <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
        <script type="text/javascript" src="jquery-1.6.2.min.js"></script>
        <script src="touch/sencha-touch.js" type="text/javascript"></script>
        <script src="models.js" type="text/javascript"></script>
        <link href="touch/resources/css/sencha-touch.css" rel="stylesheet" type="text/css" />
        <script src="actions/replyAction.js" type="text/javascript"></script>
        <script src="actions/commitAction.js" type="text/javascript"></script>

        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js"></script>
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
          		  
		<script type="text/javascript">
            var mistakeid;
            var user = <?php echo $user ?>;
            mistakeid = <?php echo $mistakeid ?>;
		</script>

        <script src="viewMistake/components.js" type="text/javascript"></script>
        <script src="viewMistake/setup.js" type="text/javascript"></script>
		<style type="text/css">
				.fb_edge_widget_with_comment span.fb_edge_comment_widget iframe.fb_ltr {display: none !important;}
		</style>
    </head>
    <body>
        <div id="fb-login-button"><fb:like align="right" href=<?php echo $likeURL; ?> layout="button_count"></fb:like></div> 
        <div id="fb-root"></div> 
<script type = "text/javascript">
//initialising FB SDK
var userID;

FB.init({appId: '152141811543304', status: true, cookie: true, xfbml: true});

FB.getLoginStatus(function(response) {
                  if (response.session) {
                  userID = response.session.uid;
                  //set up online/offline mode
                  } else {
                  // no user session available, someone you dont know
                  }
                  });
</script>

        

        <script type="text/javascript"> 
        window.fbAsyncInit = function() {
            FB.init({appId: '152141811543304', status: true, cookie: true,xfbml: true});
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
		<script type="text/javascript"> 
        FB.Event.subscribe('edge.create',
    function(response) {
		var str = response;
		var i = str.lastIndexOf("/");
		var mistakeid = str.slice(i+1, str.length);
			
		
		 FB.getLoginStatus(function(response) {
                    if (response.session) {
                        // logged in and connected user, someone you know
						var clickerid = response.session.uid;
						var clickername;						
							
						$.ajax({
									type: "GET",
									url: "userinfo/" + clickerid,
									//data: "mistakeid=" + mistakeid + "&content=" + content + "&receiver=" + mistakeauthor;
									success: function (response) {
										var userobject = $.parseJSON(response);
										clickername = userobject.user_facebook_name;									
									$.ajax({
											type: "GET",
											url: "mistake/" + mistakeid,
											success: function (response) {
												var mistakeobject = $.parseJSON(response);
												var mistakeauthor = mistakeobject[0].from_facebook_userid;
												var content = clickername + " liked your mistake.";
												if (mistakeauthor!=clickerid)
												{
													$.ajax({
															type: "POST",
															url: "notification/",
															data: "mistakeid=" + mistakeid + "&content=" + content + "&receiver=" + mistakeauthor,
															success: function (response) {
															
																console.log(response);
															
													},
													error: function (hqXHR, textStatus, errorThrown) {
														}												
													});					
												}
								},
								error: function (hqXHR, textStatus, errorThrown) {
									//alert("fail2 " + hqXHR + " " + textStatus + " " + errorThrown);
								}					
							});			
						
										
										
										
										
										console.log(response);
											
									},
									error: function (hqXHR, textStatus, errorThrown) {
										
									}					
						});			


							
												
												

											
                    }
					else 
					{
							
					}
                });
            
			
			
			
			//alert(str + " " + response);
        //alert('You liked the URL: ' + response);
    }
);

        </script> 
    </body>
</html>
