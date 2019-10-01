<?php
include("settings/connectDB.php");
include_once("../settings/function.php");
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);//# Получить объект
if(isset($_GET['body_name'])){
if($_GET['body_name']=='category_add'){//Добавление категории
	$name = validValue($json_obj['name']);
	$parent_id = $json_obj['parent_id'];
	$parametrs = $json_obj['parametrs'];

	$query=mysql_query("INSERT INTO category (name, parent_id)
	VALUES ('$name', '$parent_id')") or die(mysql_error());
	$category_id=mysql_insert_id();
	foreach($parametrs as $type_parametr){
		$type_parametr = validValue($type_parametr);
		if(is_numeric($type_parametr)){
			$type_parametr_id = $type_parametr;
		}else{
			
			$query=mysql_query("SELECT * FROM type_parametr WHERE name='$type_parametr'");
			if(mysql_num_rows($query)<1){
				mysql_query("INSERT INTO type_parametr (name) VALUES ('$type_parametr')")or die(mysql_error());
				$type_parametr_id = mysql_insert_id();
			}else{
				$mas=mysql_fetch_array($query);
				$type_parametr_id = $mas['id'];
			}
		}
		$query=mysql_query("SELECT * FROM type_parametr_category WHERE type_parametr_id='$type_parametr_id' AND category_id='$category_id'");
		if(mysql_num_rows($query)<1){
			mysql_query("INSERT INTO type_parametr_category (type_parametr_id, category_id)
			VALUES ('$type_parametr_id', '$category_id')") or die(mysql_error());
		}
	}
	echo "Данные успешно добавлены.";
}else if($_GET['body_name']=='category_update'){//Обновление
	$name = validValue($json_obj['name']);
	$parent_id = $json_obj['parent_id'];
	$parametrs = $json_obj['parametrs'];
	$oldParametrs = $json_obj['oldParametrs'];
	$category_id = $json_obj['category_id'];
	if(mysql_query("UPDATE category SET name='$name', parent_id='$parent_id'
	WHERE id='$category_id'")){
		$i=0;
		foreach($parametrs as $type_parametr){
			$type_parametr = validValue($type_parametr);
			if(is_numeric($type_parametr)){
				$type_parametr_id = $type_parametr;
			}else{
				$query=mysql_query("SELECT * FROM type_parametr WHERE name='$type_parametr'");
				if(mysql_num_rows($query)<1){
					mysql_query("INSERT INTO type_parametr (name) VALUES ('$type_parametr')")or die(mysql_error());
					$type_parametr_id = mysql_insert_id();
				}else{
					$mas=mysql_fetch_array($query);
					$type_parametr_id = $mas['id'];
				}
			}
			if($oldParametrs[$i]=='NULL'){
				$query = mysql_query("SELECT * FROM type_parametr_category 
				WHERE type_parametr_id='$type_parametr_id' AND category_id='$category_id'")or die(mysql_error());
				if(mysql_num_rows($query)<1){
					mysql_query("INSERT INTO type_parametr_category (type_parametr_id, category_id)
					VALUES ('$type_parametr_id', '$category_id')") or die(mysql_error());
				}
			}
			mysql_query("UPDATE type_parametr_category SET type_parametr_id='$type_parametr_id' WHERE category_id='$category_id' AND type_parametr_id='$oldParametrs[$i]'");	
			$i++;
		}
		echo "Данные успешно обновлены";
	}
}else if($_GET['body_name']=='category_del'){
	if(isset($_GET['category_id'])&&!empty($_GET['category_id'])){
		$category_id=$_GET['category_id'];
		$childs_id=getCategoriesChildsId($category_id);
		foreach($childs_id as $id){
			mysql_query("DELETE FROM category WHERE id='$id'") or die(mysql_error());
			mysql_query("DELETE FROM type_parametr_category WHERE category_id='$id'") or die(mysql_error());
			mysql_query("DELETE FROM parametr_category WHERE category_id='$id'") or die(mysql_error());
		}
		mysql_query("DELETE FROM category WHERE id='$category_id'") or die(mysql_error());
		mysql_query("DELETE FROM type_parametr_category WHERE category_id='$category_id'") or die(mysql_error());
		mysql_query("DELETE FROM parametr_category WHERE category_id='$category_id'") or die(mysql_error());
		if($_GET['delProducts']==true){
			foreach($childs_id as $id){//Удаляем товары сначала для дочерних эелементов
				$query = mysql_query("SELECT id FROM product WHERE category_id='$id'");//сохраняем id товара, чтобы потом найти и удалить связанные с ним характерстики (параметры)
				mysql_query("DELETE FROM product WHERE category_id='$id'") or die(mysql_error());
				while($mas=mysql_fetch_array($query)){//Удаляем доп.характеристики, связанные с товаром
					mysql_query("DELETE FROM product_parametr WHERE product_id='$mas[id]'") or die(mysql_error());
				} 
			}
			$query = mysql_query("SELECT id FROM product WHERE category_id='$category_id'");//сохраняем id товара, чтобы потом найти и удалить связанные с ним характерстики (параметры)
			mysql_query("DELETE FROM product WHERE category_id='$category_id'") or die(mysql_error());
			while($mas=mysql_fetch_array($query)){//Удаляем доп.характеристики, связанные с товаром
				mysql_query("DELETE FROM product_parametr WHERE product_id='$mas[id]'") or die(mysql_error());
			} 
		}
		echo "Данные успешно удалены";
	}
}
}else if(isset($_GET['parametr_id_forDelete'])&&!empty($_GET['parametr_id_forDelete'])){//Удаление параметров
	$type_parametr_id =$_GET['parametr_id_forDelete'];
	$category_id =$_GET['category_id_forDelete'];
	mysql_query("DELETE FROM type_parametr_category
	WHERE type_parametr_id='$type_parametr_id' AND category_id='$category_id'")or die(mysql_error());
	echo "Параметр успешно удален";
}
?>
