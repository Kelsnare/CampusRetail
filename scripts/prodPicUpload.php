<?php
include_once("checkLoginStatus.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php 
if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != ""){
	$fileName = $_FILES["avatar"]["name"];
    $fileTmpLoc = $_FILES["avatar"]["tmp_name"];
	$fileType = $_FILES["avatar"]["type"];
	$fileSize = $_FILES["avatar"]["size"];
	$fileErrorMsg = $_FILES["avatar"]["error"];
	$kaboom = explode(".", $fileName);
	$fileExt = end($kaboom);
	list($width, $height) = getimagesize($fileTmpLoc);
	if($width < 10 || $height < 10){
		header("location: message.php?msg=ERROR: That image has no dimensions");
        exit();	
	}
	$db_file_name = rand(100000000000,999999999999).".".$fileExt;
	if($fileSize > 1048576) {
		header("location: message.php?msg=ERROR: Your image file was larger than 1mb");
		exit();	
	} else if (!preg_match("/\.(gif|jpg|png|jpeg)$/i", $fileName) ) {
		header("location: message.php?msg=ERROR: Your image file was not jpg, gif or png type");
		exit();
	} else if ($fileErrorMsg == 1) {
		header("location: message.php?msg=ERROR: An unknown error occurred");
		exit();
	}

	if(!isset($_GET['id'])){
		echo "Error updating database";
		exit();
	}
	$pid = $_GET['id'];

	$sql = "SELECT name FROM all_images WHERE pid ='$pid' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$avatar = $row[0];
	if($avatar != ""){
		$picurl = "../$avatar"; 
	    if (file_exists($picurl)) { unlink($picurl); }
	    $sql = "DELETE FROM all_images WHERE pid='$pid'";
	    $query = mysqli_query($db_conx,$sql);
	}

	$moveResult = move_uploaded_file($fileTmpLoc, "../productimages/$db_file_name");
	if ($moveResult != true) {
		header("location: message.php?msg=ERROR: File upload failed");
		exit();
	}
	include_once("imageResize.php");
	$target_file = "../productimages/$db_file_name";
	$resized_file = "../productimages/$db_file_name";
	$wmax = 500;
	$hmax = 500;
	img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
	
	$target_file = "../productimages/$db_file_name";
	$cropped_file = "../productimages/$db_file_name";
	img_crop($target_file, $cropped_file, $fileExt);
	
	
	
	$sql = "INSERT INTO all_images (name,pid) VALUES ('productimages/$db_file_name', $pid)";
	$query = mysqli_query($db_conx, $sql);
	mysqli_close($db_conx);
	header("location: ../user.php?load=sell");
	exit();
}
?>