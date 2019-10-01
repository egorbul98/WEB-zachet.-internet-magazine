<?php
require('settings/connectDB.php');
require_once('settings/function.php');

//$json_str = file_get_contents('php://input');
//$json_obj = json_decode($json_str, true);//# Получить объект
if(isset($_POST['products'])){
	$products = json_decode($_POST['products'], true);
	$cost = $_POST['cost'];
	$name = validValue($_POST['name']);
	$lname = validValue($_POST['lname']);
	$tel = validValue($_POST['tel']);
	$address = validValue($_POST['address']);
	$email = validValue($_POST['email']);	
	$date =  date("Y-m-d");
	$time = date('H:m:s');

	mysql_query("INSERT INTO client (name, lname, tel, address, email) VALUES ('$name','$lname','$tel','$address','$email')") or die(mysql_error());
	$client_id=mysql_insert_id();
	$queryUser = mysql_query("SELECT * FROM user WHERE email = '$email'");//Если клиентом является авторизованный пользователь
	if(mysql_num_rows($queryUser)){
		$masUser = mysql_fetch_array($queryUser);
		mysql_query("UPDATE client SET user_id='$masUser[id]' WHERE id='$client_id'") or die(mysql_error());
	}
	$queryOrder = mysql_query("INSERT INTO `order` (`client_id`, `date`, `time`, `cost`, `status_id`) VALUES ('$client_id','$date','$time','$cost', 0)") or die(mysql_error());
	$order_id=mysql_insert_id();
	foreach($products as $val){
		$queryOrderProduct = mysql_query("INSERT INTO order_product (order_id, product_id, product_price, count) VALUES ('$order_id', '$val[product_id]', '$val[product_price]', '$val[count]')") or die(mysql_error());
		mysql_query("UPDATE product SET `count`=`count`-$val[count] WHERE id = '$val[product_id]'") or die(mysql_error());
	}
	
	echo "Заказ успешно оформлен";
}
?>