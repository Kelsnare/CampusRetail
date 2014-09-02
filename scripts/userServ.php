<?php
include_once("db_conx.php");

if(isset($_GET['categories'])){

	$sql = "SELECT c_name,c_desc FROM categories";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}
	echo "<div id='all' title='Show products from all categories' class='categoryS'>All</div>";
	while($row = mysqli_fetch_array($query)){
		echo "<div id='$row[0]' title='$row[1]' class='category'>$row[0]</div>";
	}
}else if(isset($_GET['fillBuy'])){
	$c = $_GET['fillBuy'];
	$uid = $_GET['uid'];
	$sql = "";
	if($c == "all"){
		$sql = "SELECT * FROM product WHERE ordered = 0 AND sold = 0 AND id NOT IN (SELECT pid FROM seller WHERE uid = $uid) ORDER BY id DESC";
	}else{
		$sql = "SELECT * FROM product WHERE id IN(SELECT pid FROM hascategory WHERE cid = (SELECT id FROM categories WHERE c_name = '$c' LIMIT 1) AND ordered = 0 AND sold = 0 AND id NOT IN (SELECT pid FROM seller WHERE uid = $uid))";
	}
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	while($row = mysqli_fetch_array($query)){
		$pid = $row[0];
		$pcost = $row[1];
		$pname = $row[2];
		$pdesc = $row[3];
		$sql = "SELECT name FROM all_images WHERE pid = $pid LIMIT 1";
		$res = mysqli_query($db_conx, $sql);
		if(!$res){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();		
		}

		$num_images = mysqli_num_rows($res);
		$img_name= "";
		$img = "<img src='productimages/defaultProd.png' style='height: 100px' id='".$pname."_".$pid."'>";
		if($num_images > 0){
			$result = mysqli_fetch_row($res);
			$img = "<img src='$result[0]' style='height: 100px' id='".$pname."_".$pid."'>";
			$img_name = $result[0];			
		}

		$sql = "SELECT avg(rate) FROM reviews WHERE pid = $pid";
		$res = mysqli_query($db_conx, $sql);
		if(!$res){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();		
		}

		$rate = "None";
		$result = mysqli_fetch_row($res);

		if($result[0]){
			
			$rate = "$result[0]";			
		}

		echo "<div name='$pname' title='$pdesc' class='product'>$img<br><span class='productName'>$pname</span><br><b>Rs. $pcost</b><br>Rating : <b>$rate</b></div>";
	}
}else if(isset($_GET['fillSell'])){
	$c = $_GET['fillSell'];
	$id = $_GET['uid'];
	$sql = "";
	if($c == "all"){
		$sql = "SELECT * FROM product WHERE id IN (SELECT pid FROM seller WHERE uid = $id) ORDER BY id DESC";
	}else{
		$sql = "SELECT * FROM product WHERE id IN (SELECT pid FROM hascategory WHERE cid = (SELECT id FROM categories WHERE c_name = '$c' LIMIT 1)) AND id IN (SELECT pid FROM seller WHERE uid = $id) ORDER BY id DESC";
	}
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	while($row = mysqli_fetch_array($query)){
		$pid = $row[0];
		$pcost = $row[1];
		$pname = $row[2];
		$pdesc = $row[3];
		$ordered = $row[4];
		$sold = $row[5];
		$sql = "SELECT name FROM all_images WHERE pid = $pid LIMIT 1";
		$res = mysqli_query($db_conx, $sql);
		if(!$res){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();		
		}

		$num_images = mysqli_num_rows($res);
		$img = "<img src='productimages/defaultProd.png' style='height: 100px' id='".$pname."_".$pid."'>";
		if($num_images > 0){
			$result = mysqli_fetch_row($res);
			$img = "<img src='$result[0]' style='height: 100px' id='".$pname."_".$pid."'>";			
		}

		$sql = "SELECT avg(rate) FROM reviews WHERE pid = $pid";
		$res = mysqli_query($db_conx, $sql);
		if(!$res){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();		
		}

		$rate = "None";
		$result = mysqli_fetch_row($res);

		if($result[0]){
			
			$rate = "$result[0]";			
		}
		if($sold == 1)
			echo "<div name='$pname' title='$pdesc' class='product'>$img<br><span class='productName'>$pname</span><br><b>Rs. <span class='prodCost'>$pcost</span></b><br>Rating : <b><span class='prodRate'>$rate</span></b></div>";
		else
			echo "<div name='$pname' title='$pdesc' class='product'>$img<br><span class='productName'>$pname</span><br><b>Rs. <span class='prodCost'>$pcost</span></b><br>Rating : <b><span class='prodRate'>$rate</span></b><br><b><span class='changePic' id='$pid'>Change picture</span></b><br><span class='deleteProd' name='$pname'><b>Delete</b></span></div>";
	}
}else if(isset($_GET['getCategories'])){
	$sql = "SELECT c_name FROM categories";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}
	$str = "<select>";
	while($row = mysqli_fetch_array($query)){
		$str .= "<option value='$row[0]'>$row[0]</option>";
	}
	$str .= "</select>";
	echo $str;
}else if(isset($_POST['saveProduct'])) {
	//$pname = preg_replace('#[^a-b0-9 :.]#i', '', $_POST['saveProduct']);
	$pname = mysqli_real_escape_string($db_conx, $_POST['saveProduct']);
	$pname = htmlentities($pname);
	$pcost = preg_replace('#[^0-9.]#', '', $_POST['pcost']);
	$pcost = (float)$pcost;
	$pdesc = mysqli_real_escape_string($db_conx, $_POST['pdesc']);
	$pdesc = htmlentities($pdesc);
	str_replace("\n", '<br>', $pdesc);
	$cat = $_POST['cat'];
	$user = $_POST['u'];

	$sql = "INSERT INTO product(p_name,p_cost,p_desc) VALUES ('$pname', $pcost, '$pdesc')";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}
	$pid = mysqli_insert_id($db_conx);

	$sql = "INSERT INTO seller(uid,pid) VALUES ((SELECT id FROM users WHERE webmail = '$user' LIMIT 1), $pid)";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	
	$sql = "INSERT INTO hascategory(cid,pid) VALUES ((SELECT id FROM categories WHERE c_name = '$cat' LIMIT 1), $pid)";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}
	echo "success.".$pid;
}else if(isset($_GET['getProdInfo'])){
	$id = $_GET['getProdInfo'];
	$sql = "SELECT p_cost,ordered,sold,p_desc FROM product WHERE id = '$id' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	$row = mysqli_fetch_row($query);
	$obj = array();
	
	$obj['cost'] = $row[0];
	$obj['ordered'] = $row[1];
	$obj['sold'] = $row[2];
	$obj['desc'] = $row[3];
	//$obj['pid'] = $id;

	$sql = "SELECT avg(rate) FROM reviews WHERE pid =".$id;
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();	
	}
	$row = mysqli_fetch_row($query);
	if($row[0])
		$obj['rate'] = $row[0];
	else
		$obj['rate'] = 0;

	echo json_encode($obj);
}else if(isset($_POST['placeOrder'])){
	$pid = $_POST['placeOrder'];
	$pcost = $_POST['cost'];
	$user = $_POST['u'];

	$sql = "SELECT address FROM user_details WHERE id = (SELECT id FROM users WHERE webmail = '$user' LIMIT 1)";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}
	$row = mysqli_fetch_row($query);
	$addr = $row[0];
	$delv_date = strtotime('+2 days');

	$sql = "INSERT INTO orders(delv_date,delv_addr,o_date,p_amt,net_charge) VALUES (DATE_ADD(CURDATE(), INTERVAL 2 DAY), '$addr', now(), 1, $pcost)";
	$query = mysqli_query($db_conx,$sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	$oid = mysqli_insert_id($db_conx);
	$sql = 'INSERT INTO hasprod(oid, pid) VALUES ('.$oid.', '.$pid.')';
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	$sql = 'INSERT INTO transactions(uid, oid) VALUES ((SELECT id FROM users WHERE webmail = "'.$user.'" LIMIT 1), '.$oid.')';
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	$sql = "UPDATE product SET ordered = 1 WHERE id = $pid";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	echo "success";

}else if(isset($_POST['setSold'])){
	$pid = $_POST['setSold'];
	$cost = $_POST['cost'];

	$sql = "INSERT INTO payment(datetime, amount,oid) VALUES (now(), $cost, (SELECT oid FROM hasprod WHERE pid = $pid LIMIT 1))";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	$sql = "UPDATE product SET sold = 1 WHERE id = $pid";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	echo "success";	
}else if(isset($_POST['cancelOrder'])){
	$pid = $_POST['cancelOrder'];
	$cost = $_POST['cost'];

	$sql = 'DELETE FROM orders WHERE id = (SELECT oid FROM hasprod WHERE pid = '.$pid.' LIMIT 1)';
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	$sql = "UPDATE product SET ordered = 0 WHERE id = $pid";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	echo "success";
}else if(isset($_GET['pOrders'])){
	$user = $_GET['pOrders'];
	$sql = "SELECT id,p_name FROM product WHERE ordered = 1 AND sold = 0 AND id IN (SELECT pid FROM seller WHERE uid = (SELECT id FROM users WHERE webmail = '$user' LIMIT 1))";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}
	$str = "";
	while($row = mysqli_fetch_array($query)){
		$pid =$row[0];
		$pname = $row[1];

		$sql = "SELECT id,o_date,delv_addr,net_charge FROM orders WHERE id = (SELECT oid FROM hasprod WHERE pid = $pid LIMIT 1)";
		$res = mysqli_query($db_conx, $sql);
		if(!$res){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();		
		}
		$result = mysqli_fetch_row($res);
		$orderId = $result[0];
		$oDate = $result[1];
		$oAddr = $result[2];
		$nCharge = $result[3];

		$sql = "SELECT phone,first_name,last_name FROM user_details WHERE id = (SELECT uid FROM transactions WHERE oid = $orderId)";
		$res = mysqli_query($db_conx, $sql);
		if(!$res){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();		
		}
		$result = mysqli_fetch_row($res);
		$phone = $result[0];
		$first_name = $result[1];
		$last_name = $result[2];

		$str .= "<div class='prodOrder'><span class='oid'>Order Id : <b>$orderId</b></span><br><span class='pOrderName'><b>$pname</b></span><br><span class='oDate'>Ordered On : <b>$oDate</b></span><br><span class='oAddr'>Delivery Address : <b>$oAddr</b></span><br><span class='oCharge'>Net Charge : <b>$nCharge</b></span><br><span class='oPhone'>Phone : <b>$phone</b></span><br><span class='oTo'>To : <b>$first_name $last_name</b></span></div>"; 
	}

	echo $str;
}else if(isset($_GET['pOrdersBuy'])){
	$user = $_GET['pOrdersBuy'];

	$sql = "SELECT id,o_date,delv_addr,net_charge,delv_date FROM orders WHERE id IN (SELECT oid FROM transactions WHERE uid = (SELECT id FROM users WHERE webmail = '$user' LIMIT 1))";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}
	$str = "";
	$orderId;
	while($row = mysqli_fetch_array($query)){
		$orderId = $row[0];
		$oDate = $row[1];
		$oAddr = $row[2];
		$nCharge = $row[3];
		$delvDate = $row[4];

		$sql = "SELECT p_name,id FROM product WHERE id=(SELECT pid FROM hasprod WHERE oid = $orderId LIMIT 1) AND ordered = 1 AND sold = 0";
		$res = mysqli_query($db_conx, $sql);
		if(!$res){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();		
		}

		$num = mysqli_num_rows($res);
		if($num > 0){
			$result = mysqli_fetch_row($res);
			$pname = $result[0];
			$pid = $result[1];

			$sql = "SELECT phone,first_name,last_name FROM user_details WHERE id=(SELECT uid FROM seller WHERE pid = $pid LIMIT 1)";
			$res = mysqli_query($db_conx, $sql);
			if(!$res){
				echo "ERROR";
				mysqli_close($db_conx);
				exit();		
			}
			$result = mysqli_fetch_row($res);
			$phone = $result[0];
			$first_name = $result[1];
			$last_name = $result[2];

			$str .= "<div class='prodOrderBuy'><span class='oid'>Order Id : <b>$orderId</b></span><span class='cancelOrderBuy' id='cOrderBuy_".$orderId."' style='margin-left: 20px'><b>Cancel Order</b></span><br><span class='pOrderName'><b>$pname</b></span><br><span class='oDate'>Ordered On : <b>$oDate</b></span><br><span class='dDate'>Delivery By : <b>$delvDate</b></span><br><span class='oAddr'>Delivery Address : <b>$oAddr</b></span><br><span class='oCharge'>Net Charge : <b>$nCharge</b></span><br><span class='oPhone'>Phone : <b>$phone</b></span><br><span class='oFrom'>From : <b>$first_name $last_name</b></span></div>";		
		}
	}

	echo $str;
}else if(isset($_POST['deleteProd'])){
	$pname = $_POST['deleteProd'];
	$user = $_POST['u'];
	$sql = "SELECT oid FROM hasprod WHERE pid = (SELECT id FROM product WHERE p_name = '$pname' LIMIT 1) AND pid IN (SELECT pid FROM seller WHERE uid = (SELECT id FROM users WHERE webmail = '$user' LIMIT 1))";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	$num = mysqli_num_rows($query);
	if($num > 0){
		$row = mysqli_fetch_row($query);
		$oid = $row[0];
		$sql = "DELETE FROM orders WHERE id = $oid";
		$res = mysqli_query($db_conx, $sql);
		if(!$res){
			echo "ERROR";
			mysqli_close($db_conx);
			exit();
		}	

		
	}

	$sql = "DELETE FROM product WHERE id IN (SELECT pid FROM seller WHERE uid = (SELECT id FROM users WHERE webmail = '$user' LIMIT 1)) AND p_name = '$pname'";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	echo "success";
}else if(isset($_POST['saveDesc'])){
	$pid = $_POST['saveDesc'];
	$pdesc = mysqli_real_escape_string($db_conx, $_POST['data']);
	$pdesc = htmlentities($pdesc);
	nl2br($pdesc);
	str_replace("\n", '<br>', $pdesc);	

	$sql = "UPDATE product SET p_desc = '$pdesc' WHERE id=$pid";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		mysqli_close($db_conx);
		echo "ERROR";
		exit();
	}

	echo "success";
}else if(isset($_POST['cancelOrderBuy'])){
	$oid = $_POST['cancelOrderBuy'];

	$sql = "UPDATE product SET ordered = 0 WHERE id = (SELECT pid FROM hasprod WHERE oid = $oid LIMIT 1)";
	$query = mysqli_query($db_conx, $sql);

	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();
	}

	$sql = "DELETE FROM orders WHERE id = $oid";
	$query = mysqli_query($db_conx, $sql);
	if(!$query){
		echo "ERROR";
		mysqli_close($db_conx);
		exit();	
	}

	echo "success";
}



mysqli_close($db_conx);
?>