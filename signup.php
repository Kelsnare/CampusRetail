<?php
include_once("scripts/checkLoginStatus.php");
//session_start();
// If user is logged in, header them away
if(isset($_SESSION["username"]) && $user_ok){

  header("location: scripts/message.php?msg=User is already logged in. Please signout first");
  mysqli_close($db_conx);
  exit();
}
?><?php
if(isset($_POST["u"])){
  
  $u = mysqli_real_escape_string($db_conx, $_POST['u']);
  $u = strtolower($u);
  $p = $_POST['p'];
  $fname = preg_replace('#[^a-z]#i', '', $_POST['fname']);
  $lname = "";
  $lname .= preg_replace('#[^a-z]#i', '', $_POST['lname']);

  $addr = preg_replace('#[^a-z0-9 -:]#i', '', $_POST['addr']);
  $phone = mysqli_real_escape_string($db_conx,$_POST['phone']);
  
  $gender = $_POST['gender'];

  // GET USER IP ADDRESS
  $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
  
  // DUPLICATE DATA CHECKS FOR WEBMAIL
  
  $sql = "SELECT id FROM users WHERE webmail='$u' LIMIT 1";
  $query = mysqli_query($db_conx, $sql); 
  $u_check = mysqli_num_rows($query);
  
  // FORM DATA ERROR HANDLING
  if($u == "" || $p == "" || $fname == "" || $addr == "" || $phone == "" || $gender == ""){
    echo "The form submission is missing values.";
    mysqli_close($db_conx);
    exit();
  } else if ($u_check > 0){ 
        echo "The webmail you entered is already taken";
        mysqli_close($db_conx);
        exit();
  }else {
  // END FORM DATA ERROR HANDLING
      // Begin Insertion of data into the database
    // Hash the password
    $p_hash = crypt($p);
  
    $sql = "INSERT INTO users (webmail, password, ip, signup_date, last_login)       
            VALUES('$u','$p_hash','$ip',now(), now())";
    $query = mysqli_query($db_conx, $sql);
    if(!$query){
      echo "ERROR";
      mysqli_close($db_conx);
      exit();
    }
    $uid = mysqli_insert_id($db_conx);
    //set user type
    $sql = "INSERT INTO user_details (id,phone,first_name,last_name,address,gender) VALUES ($uid, '$phone', '$fname', '$lname', '$addr', '$gender')";
    $query = mysqli_query($db_conx, $sql);
    if(!$query){
      echo "ERROR";
      mysqli_close($db_conx);
      exit();
    }

    $sql = "UPDATE user_details SET gender = '$gender' WHERE id=$uid";
    $query = mysqli_query($db_conx, $sql);
    if(!$query){
      echo "ERROR";
      mysqli_close($db_conx);
      exit();
    }

    // Email the user their activation link
    $to = "$u";              
    $from = "no-reply@aceretail.com";
    $subject = 'Online Campus Retail Account Activation';
    $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Online Campus Retail Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://127.0.0.1/"><span style="font-size: 20pt">Online Campus Retail</span></a></div><div style="padding:24px; font-size:17px;">Hello ,<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://127.0.0.1:8080/DEV/WEB/aceretail/activation.php?id='.$uid.'&u='.$u.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$u.'</b></div></body></html>';
    $headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
    mail($to, $subject, $message, $headers);

    echo "signup_success";
    mysqli_close($db_conx);
    exit();
  }
  mysqli_close($db_conx);
  exit();
}
?>


<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Sign Up Form</title>
  <link rel="stylesheet" href="css/signup_style.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
  <script type="text/javascript">
    var webmailError = true,passError = true, fNameError = true, phoneError = true,addrError = true;
    $(function(){
      $("#webmailText").keyup(function(){
        var typed = $(this).val();
        if((typed.indexOf("@iitg.ernet.in")) == -1){
          webmailError = true;          
        }
        else
          webmailError = false;
      });

      $("#pass").keyup(function(){
        var typed = $(this).val();
        if(typed.length < 6){
          passError = true;          
        }
        else
          passError = false;

        if($("#rePass").val() != ""){
          if(typed != $("#rePass").val())
            passError = true;
          else
            passError = false;
        }
      });

      $("#rePass").keyup(function(){
        var pass = $("#pass").val();
        var typed = $(this).val();
        if(pass.length < 6 || typed != pass){
          passError = true;        
        }
        else
          passError = false;
      });

      $("#firstName").keyup(function(){
        var typed = $(this).val();
        if(typed < 2){
          fNameError = true;
        }else
          fNameError = false;
      });

      $("#phoneNum").keyup(function(){
        var typed = $(this).val();
        if(!isNumber(typed) || typed.length != 10)
          phoneError = true;
        else
          phoneError = false;
      });

      $("#address").keyup(function(){
        var typed = $(this).val();
        if(typed < 5){
          addrError = true;
        }else
          addrError = false;
      });

    });

    function signup(){
      //alert("In signup");
      if(validateEntries() ){
        sData = {
          'u' : $("#webmailText").val(),
          'p' : $("#pass").val(),
          'fname' : $("#firstName").val(),
          'lname' : $("#lastName").val(),
          'addr' : $("#address").val(),
          'phone' : $("#phoneNum").val(),
          'gender' : $("input[name=sex]:checked").val()
        };
        //alert(sData.gender);
        $.ajax({
          type: "POST",
          url: "signup.php",
          data: sData,
          async: false,
          success: function(response){
            if(response.indexOf("signup_success") > -1){
              $('body').html("<p style='font-size:16pt;padding: 20px; background-color:rgba(0,0,0,0.8);color: white;margin:10px;z-index:10'>OK, check your email inbox and spam mail box at <u>"+sData.u+"</u> in a moment to complete the sign up process by activating your account. Unless activated your account is unusable.<br>You are being redirected...</p>");
              
              setTimeout(function(){window.location = 'index.php';},6000);

            }else{
              alert(response);
            }
          }
        });
      }else{
        alert("There is error in filling form");
        //alert("Error" + webmailError+ " "+ passError + " "+ fNameError + " "+ phoneError + " " + addrError);
      }
    }

    function validateEntries(){
      if(webmailError|| passError|| fNameError|| phoneError|| addrError)
        return false;
      else
        return true;
    }
    function isNumber(n) {
    
      if(n.match(/^\d+$/))
        return true;
      else
        return false;
    }

  </script>
</head>
<body>
<div id="body"></div>
  <form class="sign-up" onsubmit="return false;">
    <h1 class="sign-up-title">Sign up in seconds</h1>
	<input type="text" class="sign-up-input" placeholder="Web Mail" autofocus id='webmailText'>
	<input type="password" class="sign-up-input" placeholder="Choose a password" id='pass'>
	<input type="password" class="sign-up-input" placeholder="Retype your password" id="rePass">
    <input type="text" class="sign-up-input" placeholder="What's your First Name?" autofocus id='firstName'>
	<input type="text" class="sign-up-input" placeholder="What's your Last Name?" autofocus id="lastName">
	<input type="text" class="sign-up-input" placeholder="Address" id="address">
	<input type="text" class="sign-up-input" placeholder="Enter your Phone Number" id="phoneNum">
    <div class="register-switch">
      <input type="radio" name="sex" value="m" id="sex_m" class="register-switch-input" checked>
      <label for="sex_m" class="register-switch-label">Male</label>
      <input type="radio" name="sex" value="f" id="sex_f" class="register-switch-input">
      <label for="sex_f" class="register-switch-label">Female</label>
    </div>
	
    <input type="submit" value="Sign me up!" class="sign-up-button" onclick='signup();'>
  </form>
</body>
</html>
