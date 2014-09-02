<?php
	require_once "constants.php";
	$db_conx = mysqli_connect(IP,USER,PASSWORD,DATABASE);

	if(mysqli_connect_errno()){
		echo "ERROR";
		exit();
	}

	//echo "Connection successfull!!";

?>