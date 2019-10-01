<?php //Получение товаров полсе фильтрации
require("settings/connectDB.php");
require_once("settings/function.php");

if(isset($_POST['from'])&&isset($_POST['to'])&&isset($_POST['category_id'])){
	$from = $_POST['from'];
	$to = ($_POST['to']+1);
	$category_id = $_POST['category_id'];
	$filterArray = $_POST['filterArray'];
	$id=0;
	
	$where='';
	$count = 0;
	if(!empty($filterArray)){ //Формируем where для sql запроссаа
		foreach($filterArray as $id=>$arr){
			foreach($arr as $value){
				if(gettype($value)=='integer'){
					$str = " parametr.name = ".$value;
				}else{
					$str = " parametr.name = '".$value."'";
				}
				$where=addFilterCondition($where, $str);
			}
			$count++;
		}
		$where.=' AND';
	}
		if($where==''){
			if($category_id!=0){
//				$query=mysql_query("SELECT * FROM product WHERE category_id='$category_id' AND price>='$from' AND price<='$to'") or die(mysql_error());
				$query=mysql_query("SELECT * FROM product WHERE category_id='$category_id' AND price BETWEEN '$from' AND '$to'") or die(mysql_error());
			}else{
//				$query=mysql_query("SELECT * FROM product WHERE price>='$from' AND price<='$to'") or die(mysql_error());
				$query=mysql_query("SELECT * FROM product WHERE price BETWEEN '$from' AND '$to'") or die(mysql_error());
			}
			 
		}else{
			if($category_id!=0){
			$query=mysql_query("SELECT product.*
			FROM `product`
			inner join `product_parametr` on product_parametr .`product_id` = `product`.`id`  
			inner join `parametr` on `parametr`.`id` = product_parametr .parametr_id
			inner join `type_parametr` on `type_parametr`.`id` = parametr.type_parametr_id
			WHERE $where product.category_id = '$category_id' AND product.price>='$from' AND product.price<='$to' GROUP BY product.id 
			HAVING count(*)= $count") or die(mysql_error());
			}else{
				$query=mysql_query("SELECT product.*
				FROM `product`
				inner join `product_parametr` on product_parametr .`product_id` = `product`.`id`  
				inner join `parametr` on `parametr`.`id` = product_parametr .parametr_id
				inner join `type_parametr` on `type_parametr`.`id` = parametr.type_parametr_id
				WHERE $where product.price>='$from' AND product.price<='$to' GROUP BY product.id 
				HAVING count(*)= $count") or die(mysql_error());
			}
		}
		
		

		if(mysql_num_rows($query)>0){
			echo "<tr>";
			$i=0;
			while($mas=mysql_fetch_array($query)){
				if($mas['product_image']==NULL){
					$pathImage = 'image/productFoto.jpg';
				}else{
					$pathImage = "image/products/$mas[product_image]";
				}
				$i++;
				if($i>4){
					echo "<tr>";
					$i=1;
				}
				echo "<td><div class='product'><div><a href='catalog.php?productId=$mas[id]'><img src='$pathImage' alt='Фото товара'></a></div><h2><a href='catalog.php?productId=$mas[id]'>$mas[name]</a></h2><span class='spanRedColor'><span class='spanBold'>$mas[price]</span> руб.</span><button class='btnAddToCart' name='btnAddToCart' data-product_id='$mas[id]'>Добавить в корзину</button></div></td>";
				if($i>4){
					echo "</tr>";
				}
			}
			while($i<4){
				echo "<td></td>";
				$i++;
			}
		echo "</tr>";
		}else{
			echo "Поиск не дал результатов";
		}
		
	
	
}
?>