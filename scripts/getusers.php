<?php
	include_once("db_conx.php");

	$sql = "SELECT webmail FROM users;";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}
	while($row = mysqli_fetch_array($query)){
		echo $row[0]."<br>";
	}

	mysqli_close($db_conx);

?>