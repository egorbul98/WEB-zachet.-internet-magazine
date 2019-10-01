<?php

require("settings/connectDB.php");

//foreach ($_FILES as $val){
//	foreach($val as $k=>$v){
//		echo "$k => $v<br>";
//	}
//}


$types = array('image/gif', 'image/png', 'image/jpeg', 'image/jpg');
$size = 1024000;
//$path = 'image/products/'.basename();
$path = '../image/products/';
$nameImg = $_FILES['image']['name'];
if (!in_array($_FILES['image']['type'], $types)){
	die('Данный файл не будет загружен, т.к. запрещённый тип файла.');
}
if ($_FILES['image']['size'] > $size){
	die('Данный файл не будет загружен, т.к. слишком большой размер.');
}
// Загрузка файла и вывод сообщения
 if (!@copy($_FILES['image']['tmp_name'], $path . $nameImg)){
	 echo 'Что-то пошло не так при загрузке файла';
 }
//else{
////	 mysql_query("INSERT INTO images (name, text) VALUES ('$nameImg', 'ewfewfewfewf')") or die(mysql_error());
//	 echo 'Загрузка удачна' ;
// }
?>