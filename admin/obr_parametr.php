<?php
include("settings/connectDB.php");
include_once("../settings/function.php");
//include_once("../settings/function.php");
if($_GET['body_name']=='add'){
	$name = validValue($_GET['name']);
	$type_parametr_id = $_GET['type_parametr_id'];
	mysql_query("INSERT INTO parametr (name, type_parametr_id) VALUES ('$name','$type_parametr_id')") or die(mysql_error());
	echo "Данные успешно добавлены";
}else if($_GET['body_name']=='update'){
	$id=$_GET['id'];
	$type_parametr_id=$_GET['type_parametr_id'];
	$name = validValue($_GET['name']);
	mysql_query("UPDATE parametr SET name='$name', type_parametr_id='$type_parametr_id' WHERE id='$id'") or die(mysql_error());
	echo "Данные успешно обновлены";
}else if($_GET['body_name']=='del'){
	$parametrs = $_GET['parametrs'];
	if(!empty($parametrs)){
		foreach($parametrs as $param){
			mysql_query("DELETE FROM parametr WHERE id='$param[id]' AND type_parametr_id='$param[type_parametr_id]'") or die(mysql_error());
			mysql_query("DELETE FROM product_parametr WHERE parametr_id='$param[id]'") or die(mysql_error());
		}
		echo "Данные успешно удалены";
	}
	
	
	
}






?>