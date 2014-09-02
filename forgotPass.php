<?php
include_once("scripts/checkLoginStatus.php");
if($user_ok){
	echo "<span style='font-family: calibri;'>Log out first and revisit this url<br><a href='user.php'>Go to your page</a></span> ";
	mysqli_close($db_conx);
	exit();
}
?><?php

	if(isset($_POST['w'])){
		$webmail = $_POST['w']."@iitg.ernet.in";
		$webmail = mysqli_real_escape_string($db_conx, $webmail);
		$sql = "SELECT id FROM users WHERE webmail = '$webmail' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		if(!$query){
			echo "QUERY Error!!";
			mysqli_close($db_conx);
			exit();
		}

		if(mysqli_num_rows($query) > 0){
			$rand = rand(100000,999999);
			$sql = "UPDATE users SET temp_code = $rand WHERE webmail = '$webmail'";
			$query = mysqli_query($db_conx, $sql);
			if(!$query){
				mysqli_close($db_conx);
				echo "Query Error";
				exit();
			}

			$to = $webmail."@iitg.ernet.in";              
		    $from = "no-reply@aceretail.com";
		    $subject = 'Online Campus Retail Account Activation';
		    $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Online Campus Retail Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://127.0.0.1/DEV/WEB/aceretail/"><span style="font-size: 20pt">Online Campus Retail</span></a></div><div style="padding:24px; font-size:17px;">We received a forgot password request for your account.Please ignore if you did not initiate such request.<br><br>Your random code is '.$rand.'</div></body></html>';
		    $headers = "From: $from\n";
		    $headers .= "MIME-Version: 1.0\n";
		    $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		    mail($to, $subject, $message, $headers);
		    echo "success";
		}else{
			echo "This user does not exist!!";
		}
		mysqli_close($db_conx);
		exit();
	}else if(isset($_POST['code'])){
		$code = (int)($_POST['code']);
		$webmail = $_POST['web']."@iitg.ernet.in";
		$webmail = mysqli_real_escape_string($db_conx, $webmail);
		$sql = "SELECT id FROM users WHERE webmail = '$webmail' AND temp_code = $code LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		if(!$query){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();
		}

		if(mysqli_num_rows($query) > 0){		

			echo "success";
		}else{
			echo "Incorrect code!!<br><a href='index.php'>Go Back</a>";
		}
		$sql = "UPDATE users SET temp_code=NULL WHERE webmail = '$webmail'";
		$query = mysqli_query($db_conx, $sql);
		if(!$query){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();
		}
		mysqli_close($db_conx);
		exit();
	}else if(isset($_POST['pass'])){
		$pass = $_POST['pass'];
		$webmail = $_POST['web']."@iitg.ernet.in";
		$webmail = mysqli_real_escape_string($db_conx, $webmail);
		$phash = crypt($pass);

		$sql = "UPDATE users SET password = '$phash' WHERE webmail = '$webmail'";
		$query = mysqli_query($db_conx, $sql);
		if(!$query){
			mysqli_close($db_conx);
			echo "ERROR";
			exit();
		}

		echo "success";
		mysqli_close($db_conx);
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password : Online Campus Retail</title>
	<style type="text/css">
		@font-face{
			font-family: "logobloqo2";
			src: url('css/fonts/Logobloqo2.ttf');
		}
		body{
			margin: 0 auto 0 auto;
			padding: 0;
		}
		#header{
			position: absolute;
			font-family: "logobloqo2";
			background-color: black;
			top: 0;
			left: 0;
			color: white;
			padding: 10px;
			width: 100%;
		}
		#logoText{
			font-size: 25pt;
			cursor: pointer;
		}
		#content{
			margin-top: 70px;
			margin-left: 20px;
			font-family: "calibri";
		}
		#status{
			color: red;
		}
	</style>
	<script type="text/javascript" src='js/jquery-1.10.2.min.js'></script>
	<script type="text/javascript">
		var webmail_id='';
		<?php
			
		?>

		function gotWebmail(){
			var webmail_elem = document.getElementById("askWebmail_input");
			var id = webmail_elem.value;
			webmail_id = id;			 
			if(id == ""){
				
				document.getElementById("status").innerHTML = "The Webmail Id Field above cannot be left blank";
			}else{

				$.ajax({
					type: "POST",
					url : "forgotPass.php",
					data : {'w': id},
					async: false,
					success : function(response){
						//alert("In here");
						if(response.indexOf('success') > -1){
							fill = "<div id='secretCode'>Enter Code : <input id='codeText' size = '40' type='text'><br><br> <button id='checkCode' onclick='checkCode();'>Continue</button><br><br><span id='status'></span></div>";
							document.getElementById('content').innerHTML = fill;
						}else{
							//alert(response);
						}
					}
				});
				
			}			
		}

		function changePass(){
			var newPass = document.getElementById("newPass").value;
			var rePass = document.getElementById("reNewPass").value;

			if(newPass.length < 6)
				document.getElementById("status").innerHTML = "Password should be at least 6 characters long";
			else if(newPass != rePass)
				document.getElementById("status").innerHTML = "Passwords don't match";
			else{
				document.getElementById("status").innerHTML = "";

				$.ajax({
					type: "POST",
					url : "forgotPass.php",
					data : {"pass": newPass, "web" : webmail_id},
					async: false,
					success: function(response){
						if(response.indexOf("success") > -1){
							var fill = "DONE!!<br><a href='index.php'>GO BACK</a>";
							document.getElementById("content").innerHTML = fill;
						}else{
							document.getElementById('content').innerHTML = "Password Reset Failed<br><a href='index.php'>GO BACK</a>";
						}
					}	
				});
			}
		}

		function checkCode(){
			var code = document.getElementById('codeText').value;
			if(code == ""){
				document.getElementById('status').innerHTML = "Code field above cannot be blank";
			}else{
				document.getElementById('status').innerHTML = "";

				$.ajax({
					type: "POST",
					url: "forgotPass.php",
					data: {"code" : code, "web" : webmail_id},
					async: false,
					success: function(response){
						if(response.indexOf("success") > -1){
							var fill = "<span id='newPassword'>New Password : <input type='password' placeholder='New Password' id='newPass' size='40'></span><br><br><span id='rePassword'>Re-Enter Password : <input type='password' placeholder='Re-enter Password' id='reNewPass' size='40'></span><br><br><button id='changePass' onclick='changePass();'>Change Password</button><span id='status'></span>";
							document.getElementById("content").innerHTML = fill;
						}else{
							if(response.indexOf("incorrect") > -1){
								document.getElementById('content').innerHTML = "Incorrect Code<br><a href='index.php'>GO BACK</a>";
							}
						}
					}
				});
			}
		}

		function ajaxRequest(){
			var xmlhttp;
			if(window.XMLHttpRequest){
				xmlhttp = new XMLHttpRequest();
			}else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			return xmlhttp;
		}
	</script>
</head>

<body>
	<div id='header'><span id='logoText' onclick='window.location = "index.php";'>Online Campus Retail</span></div>
	<div id='content'>
		<span id='askWebmail'>Webmail Id : <input type='text' size="40" placeholder='Webmail Id' id='askWebmail_input' onfocus="document.getElementById('status').innerHTML = '';">@iitg.ernet.in</span>
		<br><br>
		<button id='gotWebmail' onclick='gotWebmail()' autofocus="true">Continue</button>
		<br> <br><span id='status'></span> 
	</div>	
</body>
</html>