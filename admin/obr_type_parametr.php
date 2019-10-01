<?php
include("settings/connectDB.php");
include_once("../settings/function.php");
if($_GET['body_name']=='add'){
	$name = validValue($_GET['name']);
	mysql_query("INSERT INTO type_parametr (name) VALUES ('$name')") or die(mysql_error());
	echo "Данные успешно добавлены";
}else if($_GET['body_name']=='update'){
	$id=$_GET['id'];
	$name = validValue($_GET['name']);
	mysql_query("UPDATE type_parametr SET name='$name' WHERE id='$id'") or die(mysql_error());
	echo "Данные успешно обновлены";
}else if($_GET['body_name']=='del'){
	$type_parametrs_id = $_GET['type_parametr_id'];
	foreach($type_parametrs_id as $id){
		mysql_query("DELETE FROM type_parametr WHERE id='$id'") or die(mysql_error());
		mysql_query("DELETE FROM type_parametr_category WHERE type_parametr_id='$id'") or die(mysql_error());
	}
	
	echo "Данные успешно удалены";
}






?>