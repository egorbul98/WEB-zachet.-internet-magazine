<?php
include("settings/connectDB.php");
include_once("../settings/function.php");
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);//# Получить объект
if(isset($_GET['body_name'])){
	if($_GET['body_name']=='product_add'){//Добавление товара
		$category_id=$json_obj['category_id']; 
		$name=validValue($json_obj['name']); 
		$price=validValue($json_obj['price']); 
		$count=validValue($json_obj['count']); 
		$description=validValue($json_obj['description']);
		$parametrsId = $json_obj['parametrsId'];
		$parametrsText = $json_obj['parametrsText'];
		$imageName = $json_obj['imageName'];
		if(is_numeric($price)&&is_numeric($count)){
			if(mysql_query("INSERT INTO product (category_id, name, price, count, description, product_image) VALUES ('$category_id', '$name', '$price', '$count', '$description', '$imageName')")){
				$product_id=mysql_insert_id();
				foreach($parametrsId as $parametr_id){
					mysql_query("INSERT INTO product_parametr (product_id, parametr_id) VALUES ('$product_id','$parametr_id')") or die(mysql_error());
                    $queryPC = mysql_query("SELECT * FROM parametr_category WHERE parametr_id='$parametr_id' AND  category_id='$category_id'");
                    if(mysql_num_rows($queryPC)<=0){
                        mysql_query("INSERT INTO parametr_category (parametr_id, category_id) VALUES ('$parametr_id','$category_id')") or die(mysql_error());
                    }
				}
				foreach($parametrsText as $type_parametr_id => $parametr_name){
					$parametr_name = validValue($parametr_name);
					$query=mysql_query("SELECT * FROM parametr
					WHERE name = '$parametr_name' AND type_parametr_id='$type_parametr_id'");
					if(mysql_num_rows($query)<1){//Если такой записи нет
						mysql_query("INSERT INTO parametr (type_parametr_id, name) VALUES ('$type_parametr_id','$parametr_name')") or die(mysql_error());
						$parametr_id = mysql_insert_id();
					}else{
						$mas=mysql_fetch_array($query);
						$parametr_id = $mas[id];
					}
					$queryPC = mysql_query("SELECT * FROM parametr_category WHERE parametr_id='$parametr_id' AND  category_id='$category_id'");
                    if(mysql_num_rows($queryPC)<=0){
                        mysql_query("INSERT INTO parametr_category (parametr_id, category_id) VALUES ('$parametr_id','$category_id')") or die(mysql_error());
                    }
					mysql_query("INSERT INTO product_parametr (product_id, parametr_id) VALUES ('$product_id','$parametr_id')") or die(mysql_error());
				}
				echo "Данные успешно добавлены.";
			}else{
				echo "Ошибка при добавлении. Попробуйте еще раз.";
			}
		}else{
			echo "Поля 'цена' и 'количество товара' необходимо заполнить числовыми значениями";
		}
	}else if($_GET['body_name']=='product_update'){
		$category_id=$json_obj['category_id']; 
		$name=validValue($json_obj['name']); 
		$price=validValue($json_obj['price']); 
		$count=validValue($json_obj['count']); 
		$description=validValue($json_obj['description']);
		$parametrsId = $json_obj['parametrsId'];
		$parametrsText = $json_obj['parametrsText'];
		$product_id=$json_obj['product_id'];
		$oldParametrs=$json_obj['oldParametrs'];
		$imageName = $json_obj['imageName'];
		if(empty($imageName)){
			mysql_query("UPDATE product SET category_id='$category_id', name='$name', price='$price', count='$count', description='$description' 
			WHERE id='$product_id'") or die(mysql_error());
		}else{
			mysql_query("UPDATE product SET category_id='$category_id', name='$name', price='$price', count='$count', description='$description', product_image = '$imageName' 
			WHERE id='$product_id'") or die(mysql_error());
		}
		foreach($parametrsId as $type_parametr_id=>$parametr_id){
			foreach($oldParametrs as $type_oldParametr_id=>$oldParametr_id){
				if($type_parametr_id==$type_oldParametr_id){
					mysql_query("UPDATE product_parametr SET parametr_id = '$parametr_id' WHERE product_id='$product_id' AND parametr_id='$oldParametr_id'") or die(mysql_error());
//				echo "здесь product_id=$product_id и oldParametrs=$oldParametrs[$i] новый = parametr_id = $parametr_id";
				}
				$queryPC = mysql_query("SELECT * FROM parametr_category WHERE parametr_id='$oldParametr_id' AND  category_id='$category_id'");
				if(mysql_num_rows($queryPC)>0){
					mysql_query("REPLACE INTO parametr_category (parametr_id, category_id) VALUES ('$parametr_id','$category_id')") or die(mysql_error());
				}else{
					$queryPC = mysql_query("SELECT * FROM parametr_category WHERE parametr_id='$parametr_id' AND  category_id='$category_id'");
					if(mysql_num_rows($queryPC)==0){
						mysql_query("INSERT INTO parametr_category (parametr_id, category_id) VALUES ('$parametr_id','$category_id')") or die(mysql_error());
					}
				}
//				$queryPC = mysql_query("SELECT * FROM parametr_category WHERE parametr_id='$oldParametr_id' AND  category_id='$category_id'");
//				if(mysql_num_rows($queryPC)<=0){
//					$queryPC2 = mysql_query("SELECT * FROM parametr_category WHERE parametr_id='$parametr_id' AND  category_id='$category_id'");
//					if(mysql_num_rows($queryPC2)<=0){
//						mysql_query("REPLACE INTO parametr_category (parametr_id, category_id) VALUES ('$parametr_id','$category_id')") or die(mysql_error());
//					}
//				}else{
//					mysql_query("UPDATE parametr_category SET parametr_id = '$parametr_id', category_id='$category_id' WHERE parametr_id='$oldParametr_id' AND category_id='$category_id'") or die(mysql_error());
//				}
        	}
		}
        foreach($parametrsText as $type_parametr_id=>$parametr_name){
			$parametr_name = validValue($parametr_name);
			$query = mysql_query("SELECT * FROM parametr WHERE name='$parametr_name' AND type_parametr_id='$type_parametr_id'") or die(mysql_error());
            if(mysql_num_rows($query)<1){
                mysql_query("INSERT INTO parametr (type_parametr_id, name) VALUES('$type_parametr', '$parametr_name')")or die(mysql_error());
                $parametr_id = mysql_insert_id();
            }else{
				$parametr = mysql_fetch_array($query);
				$parametr_id=$parametr['id'];
			}
			foreach($oldParametrs as $type_oldParametr_id=>$oldParametr_id){
				if($type_parametr_id==$type_oldParametr_id){
					mysql_query("UPDATE product_parametr SET parametr_id = '$parametr_id' WHERE product_id='$product_id' AND parametr_id='$oldParametr_id'") or die(mysql_error());
				}
				$queryPC = mysql_query("SELECT * FROM parametr_category WHERE parametr_id='$oldParametr_id' AND  category_id='$category_id'");
				if(mysql_num_rows($queryPC)>0){
					mysql_query("REPLACE INTO parametr_category (parametr_id, category_id) VALUES ('$parametr_id','$category_id')") or die(mysql_error());
				}else{
					$queryPC = mysql_query("SELECT * FROM parametr_category WHERE parametr_id='$parametr_id' AND  category_id='$category_id'");
					if(mysql_num_rows($queryPC)==0){
						mysql_query("INSERT INTO parametr_category (parametr_id, category_id) VALUES ('$parametr_id','$category_id')") or die(mysql_error());
					}
				}
			}
        }
		echo "Данные успешно изменены.";
		
	}else if($_GET['body_name']=='product_del'){
		if(isset($_GET['category_id'])){
			
		}else{
			$products_id = $json_obj['products_id'];
			foreach($products_id as $id){
				mysql_query("DELETE FROM product WHERE id='$id'") or die(mysql_error());
				mysql_query("DELETE FROM product_parametr WHERE product_id='$id'") or die(mysql_error());
			}
			echo "Данные успешно удалены.";	
		}
	}
	
}
?>
