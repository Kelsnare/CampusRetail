<?php
include_once("scripts/checkLoginStatus.php");
if($user_ok)
	header("location: user.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>OnlineCampus Retail</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/index.js"></script>
	<script type="text/javascript">
	$(function(){
		//$("#testBtn > button").click(fill_content);
		locate();
		$(window).resize(locate);
	});
	</script>
</head>
<body>
	<div id="body">
	<div id="header">
		<span id='logoText'>Online Campus Retail</span>
	</div>
	<div id="content">
		<!--<div id='testBtn'><button style="padding: 5px; font-family: verdana,ubuntu;">Refresh User List</button></div>
		<div id='userList'></div>-->
		<div id = "signin">
			<form onsubmit='return false;'>
				<span id='signInWebmail'><input type="text" placeholder="Enter your webmail id">@iitg.ernet.in</span><br><br>
				<span id='signInPass'><input type='password' placeholder='Password'></span><br><br>
				<button id="signInBtn" onclick="login();">Enter</button><br><br>
				<span id='loginStatus' style="color: white"></span>
			</form><br><br>
			<span id='signup'><a href='signup.php'>Sign Up</a></span> | <span id='forgotPass'><a href='forgotPass.php'>Forgot password</a></span>
		</div>

	</div>
	<div id="footer"></div>
	</div>
</body>
</html>