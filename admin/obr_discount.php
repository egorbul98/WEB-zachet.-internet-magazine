<?php
require("settings/connectDB.php");
require_once("../settings/function.php");


if(isset($_POST['body_name'])){
	if($_POST['body_name']=='add'){
		$discount = validValue($_POST['discount']);
		if(is_numeric($discount)){
			$query = mysql_query("SELECT * FROM `discount` WHERE `discount` = $discount");
			if(mysql_num_rows($query)>0){
				echo "Такая скидка уже существует";
			}else{
				mysql_query("INSERT INTO discount (discount) VALUES ('$discount')") or die(mysql_error());
				echo "Cкидка успешно добавлена";
			}

		}else{
			echo "Скидка принимает только числовые значения";
		}
	}else if($_POST['body_name']=='del'){
		$id = $_POST['id'];
		mysql_query("DELETE FROM discount WHERE id='$id'") or die(mysql_error());
		$query=mysql_query("SELECT * FROM discount");
		if(mysql_num_rows($query)>0){
			echo "<table>";
			while($mas=mysql_fetch_array($query)){
				echo "<tr>";
				echo "<td><p>Скидка $mas[discount]%. </td><td><button type='button' class='btnDiscountDel' data-discount_id='$mas[id]'>Удалить</button></p></td>";
				echo "</tr>";
			}
			echo "</table>";
		}else{
			echo "<p>Скидок пока нет</p>";
		}
	}
}else if(isset($_POST['arrayDiscounts'])){
	$arrayDiscounts = $_POST['arrayDiscounts'];
	foreach($arrayDiscounts as $arr){
		$category_id = $arr['category_id'];
		if($arr['discount']==0){
			$discount = NULL;
		}else{
			$discount = $arr['discount'];
		}
		$query = mysql_query("SELECT * FROM `category_discount` WHERE `category_id` = '$category_id'");
		if(mysql_num_rows($query)>0){
			if($discount==NULL){
				mysql_query("DELETE FROM `category_discount` WHERE `category_id` = '$category_id'") or die(mysql_error());
			}else{
				mysql_query("UPDATE `category_discount` SET discount_id = '$discount' WHERE `category_id` = '$category_id'") or die(mysql_error());
			}
		}else{
			if($discount!=NULL){
				mysql_query("INSERT INTO `category_discount` (category_id, discount_id) VALUES ('$category_id', '$discount')") or die(mysql_error());
			}
		}
	}
	echo "Данные успешно обновлены";
}else if(isset($_POST['search'])){
	$search = validValue($_POST['search']);
	
	$query = mysql_query("SELECT * FROM product WHERE name LIKE '%$search%' OR name LIKE '$search%' GROUP BY id ") or die(mysql_error());
	if(mysql_num_rows($query)>0){
		echo "<table id='table_list'>";
		$i=0;
		echo "<tr>";
		while($masProd = mysql_fetch_array($query)){
			$i++;
			if($i>2){
				echo "<tr>";
				$i=1;
			}
			echo "<td><input type='checkbox' class='cbProduct' data-product_id='$masProd[id]'> $masProd[name]</td>";

			$queryDiscount=mysql_query("SELECT discount.* FROM discount 
			INNER JOIN product_discount on product_discount.discount_id = discount.id 
			WHERE product_discount.product_id='$masProd[id]'");
			if(mysql_num_rows($queryDiscount)>0){
				$masD = mysql_fetch_array($queryDiscount);	
				echo "<td><input type='text' class='discountProduct' readonly data-product_id = '$masProd[id]' value='$masD[discount]%'></td>";
			}else{
				echo "<td><input type='text' class='discountProduct' readonly data-product_id = '$masProd[id]' value='Без скидки'></td>";
			}

			if($i>2){
				echo "</tr>";
			}
		}
		echo "</table>";
	}else{
		echo 'Поиск не дал результатов';
	}
	
}else if(isset($_POST['discount'])){
	$discount_id = $_POST['discount'];
	$products_id = $_POST['products_id'];
	$category_id = $_POST['category_id'];
	foreach($products_id as $product_id){
		$query = mysql_query("SELECT * FROM `product_discount` WHERE `product_id` = '$product_id'");
		if(mysql_num_rows($query)>0){
			if($discount_id==0){
				mysql_query("DELETE FROM `product_discount` WHERE `product_id` = '$product_id'") or die(mysql_error());
			}else{
				mysql_query("UPDATE `product_discount` SET discount_id = '$discount_id' WHERE `product_id` = '$product_id'") or die(mysql_error());
			}
		}else{
			if($discount_id!=0){
				mysql_query("INSERT INTO `product_discount` (product_id, discount_id) VALUES ('$product_id', '$discount_id')") or die(mysql_error());
			}
		}
	}
	
	
	$query = mysql_query("SELECT * FROM product WHERE category_id='$category_id'") or die(mysql_error());
	if(mysql_num_rows($query)>0){
		echo "<table id='table_list'>";
		$i=0;
		echo "<tr>";
		while($masProd = mysql_fetch_array($query)){
			$i++;
			if($i>2){
				echo "<tr>";
				$i=1;
			}
			echo "<td><input type='checkbox' class='cbProduct' data-product_id='$masProd[id]'> $masProd[name]</td>";

			$queryDiscount=mysql_query("SELECT discount.* FROM discount 
			INNER JOIN product_discount on product_discount.discount_id = discount.id 
			WHERE product_discount.product_id='$masProd[id]'");
			if(mysql_num_rows($queryDiscount)>0){
				$masD = mysql_fetch_array($queryDiscount);	
				echo "<td><input type='text' class='discountProduct' readonly data-product_id = '$masProd[id]' value='$masD[discount]'></td>";
			}else{
				echo "<td><input type='text' class='discountProduct' readonly data-product_id = '$masProd[id]' value='Без скидки'></td>";
			}

			if($i>2){
				echo "</tr>";
			}
		}
		echo "</table>";
	}else{
		echo 'Поиск не дал результатов';
	}
}




?>