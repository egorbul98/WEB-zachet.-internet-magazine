<?php
require("settings/connectDB.php");//Получение товаров в корзине

if(isset($_POST['cart'])){
	$cart = $_POST['cart'];
	$arrayOutput = array();
    $sum=0;
	foreach($cart as $id=>$count){
		$query=mysql_query("SELECT * FROM product WHERE id='$id'") or die(mysql_error());
		$mas=mysql_fetch_array($query);
		if($mas['count']<$count){
			$count = $mas['count'];
		}
		$queryDiscount=mysql_query("SELECT discount.* FROM discount 
		INNER JOIN product_discount on product_discount.discount_id = discount.id 
		WHERE product_discount.product_id='$mas[id]'");
		$queryDiscountCategory=mysql_query("SELECT discount.* FROM discount 
		INNER JOIN category_discount on category_discount.discount_id = discount.id 
		WHERE category_discount.category_id='$mas[category_id]'");
		if(mysql_num_rows($queryDiscount)>0){
			$discount = mysql_fetch_array($queryDiscount);
			$price = ($mas['price'] - ($mas['price']/100)*$discount['discount']);
		}else{
			if(mysql_num_rows($queryDiscountCategory)>0){
				$discount = mysql_fetch_array($queryDiscountCategory);
				$price = ($mas['price'] - ($mas['price']/100)*$discount['discount']);
			}else{
				$price = $mas['price'];
			}
		}
		
            $sum+=($price*$count);
			$arrayOutput[$mas['id']]['id']= $mas['id'];
			$arrayOutput[$mas['id']]['name']= $mas['name'];
			$arrayOutput[$mas['id']]['price']= $price;
			$arrayOutput[$mas['id']]['count']= $count;
			$arrayOutput[$mas['id']]['sklad']= $mas['count'];;
			$arrayOutput[$mas['id']]['sum']= ($price*$count);
	}
	echo json_encode($arrayOutput);
}





?>
