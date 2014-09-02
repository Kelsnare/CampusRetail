<?php
include_once("scripts/checkLoginStatus.php");
$webmail = "";
$load = "";
//Check if user is logged in......................................................................................
if(!$user_ok || $log_username == ""){
	echo "Please login before continuing...<br><br><a href='http://127.0.0.1:8080/DEV/WEB/aceretail/'>Log In</a>";
	mysqli_close($db_conx);
	exit();
}

$webmail = $log_username;

if(isset($_GET['load'])){
	$load = $_GET['load'];
}
?>
<?php

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Online Campus Retail : <?php echo $webmail;?></title>
		<link rel="stylesheet" type="text/css" href="css/user.css">
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="js/user.js"></script>
		<script type="text/javascript">
			var load= "";
			var category = "all"
			var user = "";
			var uid = 0;
			<?php
				if($load != "")
					echo "load = '$load';";
				echo "user = '$log_username';";
				echo "uid = $log_id;";
			?>
			$(function(){
				locate();
				$(window).resize(locate);
				$("#left").load("scripts/userServ.php?categories=all");

				if(load == "sell"){
					//$("#rightContent").load("scripts/userServ.php?fillSell="+category+"&uid="+uid);
					fillSell();
				}else if(load == "bought"){

				}else if(load == "sold"){

				}else{
					//$("#rightContent").load("scripts/userServ.php?fillBuy="+category);
					fillBuy();
					load = "buy";
				}

				$(document).on("click", ".userHeaderOp",function(){
					var id = $(this).attr("id");
					userHeaderOpClick(id);
				});
				$(document).on("click", ".category", function(){
					var id = $(this).attr("id");
					categoryClick(id);
				});

				$(document).on("click", "#addProduct", addProduct);
				$(document).on("click", "#pOrders", pOrders);
				$(document).on("click", "#pOrdersBuy", pOrdersBuy);
				$(document).on("click", ".deleteProd", function(){
					var pname = $(this).attr("name");
					deleteProd(pname);
				});
				$(document).on("click", ".product > img", function(){
					var name = $(this).parent().attr("name");
					var desc = $(this).parent().attr("title");
					var img = $(this).attr("src");
					var id = $(this).attr('id');
					id = id.split("_");
					id = id[id.length-1];
					productClick(name, desc, img,id);
				});

				$(document).on("click", ".changePic", function(){
					var id= $(this).attr('id');
					//alert()
					changePicture(id);
				});

				$(document).on("click", ".cancelOrderBuy", function(){
					var oid = $(this).attr("id");
					oid = oid.split("_");
					oid = oid[1];
					//alert(oid);
					cancelOrderBuy(oid);
				});

			});
		</script>
	</head>
	<body>
		<div id='body'></div>
		<div id='header'><?php include_once("scripts/userHeader.php") ?></div>

		<div id="content">
			<div id='left'></div>
			<div id="right">
				<div id="userHeader">
					<span class='userHeaderOpS' id='buy'>Buy</span>
					<span class='userHeaderOp' id='sell'>Sell</span>
					<!--<span class='userHeaderOp' id='bought'>Bought</span>
					<span class='userHeaderOp' id='sold'>Sold</span>-->
				</div>
				<div id='contentOp'></div>
				<div id="rightContent"></div>
			</div>
		</div>
		
		<div id='footer'></div>

	</body>
</html>