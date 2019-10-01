<?php
require("settings/connectDB.php");
require_once("../settings/function.php");

if(isset($_POST['body_name'])){
	if($_POST['body_name']=='del'){
		$orders_id = $_POST['order_id'];
		foreach($orders_id as $id){
			mysql_query("DELETE FROM `order` WHERE id='$id'") or die(mysql_error());
			mysql_query("DELETE FROM order_product WHERE order_id='$id'") or die(mysql_error());
		}
	}else if($_POST['body_name']=='update'){
		$parametrs = $_POST['arrayProducts'];
		$order_id = $_POST['order_id'];
		foreach($parametrs as $param){
			$count = $param['count'];
			$product_id = $param['product_id'];
			$product_price = $param['product_price'];
			$cost = $param['cost'];
			$old_count = $param['old_count'];
			$updateSklad = ($old_count-$count);
			$queryP = mysql_query("SELECT * FROM `product` WHERE id='$product_id'") or die(mysql_error());
			$masP = mysql_fetch_array($queryP);
			if($updateSklad<0){//Возврат на склад
				if($updateSklad>$masP['count']){
					$updateSklad = $masP['count']; //Если на складе больше нет товара, то берем всё что есть
				}
				mysql_query("UPDATE `product` SET `count` = `count` + '$updateSklad' 
				WHERE id='$product_id'") or die(mysql_error());
			}else{//Берем со склада
				
				mysql_query("UPDATE `product` SET `count` = `count` + '$updateSklad' 
				WHERE id='$product_id'") or die(mysql_error());
			}
			
			mysql_query("UPDATE `order` SET cost = '$cost' WHERE id='$order_id'") or die(mysql_error());
			mysql_query("UPDATE `order_product` SET `count` = '$count'
			WHERE order_id='$order_id' AND product_id='$product_id'") or die(mysql_error());	
					
		}
	}else if($_POST['body_name']=='updateOrderStatus'){
		$arrayOrder = $_POST['arrayOrder'];
		$status = $_POST['status'];
		foreach($arrayOrder as $order){
			if($order['old_status']!='Отклонен' && $status==4){
				foreach($order['products'] as $product){
					$count = $product['count'];
					$product_id = $product['product_id'];
					mysql_query("UPDATE product SET `count`=`count`+'$count' WHERE id='$product_id'") or die(mysql_error());
						
				}
			}else if($order['old_status']=='Отклонен' && $status!=4){//Восстанавливаем заказ после отклонения
				foreach($order['products'] as $product){
					$order_id = $order['order_id'];
					$count = $product['count'];
					$product_id = $product['product_id'];
					$product_price = $product['product_price'];
					$cost = $product['cost'];
					$old_count = $product['old_count'];
					
					$queryP = mysql_query("SELECT * FROM `product` WHERE id='$product_id'") or die(mysql_error());
					$masP = mysql_fetch_array($queryP);
					$updateSklad = ($masP['count']-$count);
					
					if($count>$masP['count']){//Если заказ больше, чем имеется товара на складе
						$count = $masP['count'];
					}
					mysql_query("UPDATE product SET `count`=`count`-'$count' WHERE id='$product_id'") or die(mysql_error());
					
//
//					mysql_query("UPDATE `order` SET cost = '$cost' WHERE id='$order_id'") or die(mysql_error());
//					mysql_query("UPDATE `order_product` SET `count` = '$count'
//					WHERE order_id='$order_id' AND product_id='$product_id'") or die(mysql_error());	

				}
			}
			mysql_query("UPDATE `order` SET status_id = '$status' WHERE id='$order[order_id]'") or die(mysql_error());	
		}
	}
	
	$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
	FROM `order`, order_product, client, `orderstatus`   
	WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id 
	AND `order`.`status_id` = `orderstatus`.`id` GROUP BY `order`.`id`") or die(mysql_error());
	
	
	if(mysql_num_rows($queryOrder)>0){
		while($mas=mysql_fetch_array($queryOrder)){
			echo "<div><input type='checkbox' name='cbOrderDel' class='cbOrderDel' data-order_id='$mas[order_id]' data-client_id='$mas[client_id]' data-old_status='$mas[status]'><a href='#' class='aOrderDel'> ID: $mas[order_id]. Дата: $mas[date], $mas[time].</a> Статус заказа: $mas[status].<br>Клиент: $mas[name] $mas[lname] . Телефон: $mas[tel]. E-mail: $mas[email]. Адресс: $mas[address] <br> <button type='button' class='btnShowInfoOrder' >Подробнее</button> <button type='button' class='btnHideInfoOrder'>Скрыть информацию </button><br>"; 

			$queryOP = mysql_query("SELECT `order_product`.`product_id` as `product_id`, `order_product`.`count` as `count`, `order_product`.`product_price` as `price`, `product`.`name` as `name`, `product`.`count` as `sklad`  
			FROM order_product, product 
			WHERE order_product.order_id = '$mas[order_id]' AND `order_product`.`product_id` = `product`.`id`") or die(mysql_error());
			$out = '<div class="divInfoOrder">';
			$out .= "<table>";
			$out .= "<tr>";
			$out .= "<td><p>Название товара</p></td>";
			$out .= "<td colspan='2'><p>Цена</p></td>";
			$out .= "<td class='tdCountProduct'><p>Количество</p></td>";
			$out .= "<td = colspan='3'><p>Сумма</p></td>";
			$out .= "</tr>";	
			while($masOP=mysql_fetch_array($queryOP)){//Информация о продуктах
				$out .= "<tr class='trProduct' data-cost='$mas[cost]' data-order_id='$mas[order_id]' data-product_id='$masOP[product_id]' data-product_price='$masOP[price]' data-product_old_count='$masOP[count]'>";
				$out .= "<td><a href='catalog.php?productId=$masOP[product_id]'>$masOP[name]</a></td>";

				$out .= "<td class='tdPriceProduct'><span class='spanPrice'>$masOP[price]</span> руб.</td>";
				$out .= "<td>*</td>";
				if($mas[status]=='Выполнен'){
					$out .= "<td class='tdCountProduct'><input type='number' readonly class='inputCount' min='0' max='$masOP[sklad]' value='$masOP[count]'></td>";
				}else{
					$out .= "<td class='tdCountProduct'><input type='number' class='inputCount' min='0' max='$masOP[sklad]' value='$masOP[count]'></td>";
				}
//				$out .= "<td class='tdCountProduct'><input type='number' class='inputCount' min='0' max='$masOP[sklad]' value='$masOP[count]'></td>";
				$out .= "<td>=</td>";
				$out .= "<td class='tdSum'><span class='spanResult'>".($masOP['price']*$masOP['count'])."</span> руб.</td>";
				$out .= "</tr>";
			}
			$out .= "<tr class='trSumPriceCart'><td class='tdSumPriceCart' data-cost='$mas[cost]' colspan='7'>Общая сумма: <span class='spanSumPriceCart'>$mas[cost]</span> руб.</td></tr>";
			$out .= "</table><button type='button' class='btnUpdateOrder' data-order_id='$mas[order_id]'>Сохранить данные</button>";
			
			$out .= "</div>";
			echo "$out</div>";
		}
	}
	
}else if(isset($_POST['dateFrom'])||isset($_POST['searchLogin'])||isset($_POST['searchOrderStatus'])){
	if(isset($_POST['dateFrom'])){
		$dateFrom = $_POST['dateFrom'];
		$dateTo = $_POST['dateTo'];
		$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
		FROM `order`, order_product, client, `orderstatus` 
		WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id AND `order`.`status_id` = `orderstatus`.`id` 
		AND `order`.`date` >= '$dateFrom' AND `order`.`date` <= '$dateTo' GROUP BY `order`.`id` ") or die(mysql_error());
	}else if(isset($_POST['searchLogin'])){
		$search = validValue($_POST['searchLogin']);
		$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
		FROM `order`, order_product, client, `orderstatus`  
		WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id AND `order`.`status_id` = `orderstatus`.`id` 
		AND `client`.`email` LIKE '%$search%' GROUP BY `order`.`id` ") or die(mysql_error());
	}else if(isset($_POST['searchOrderStatus'])){
		$status_id = $_POST['searchOrderStatus'];
		$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
		FROM `order`, order_product, client, `orderstatus`  
		WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id AND `order`.`status_id` = `orderstatus`.`id` 
		AND `order`.status_id = '$status_id' GROUP BY `order`.`id` ") or die(mysql_error());
	}
	
	if(mysql_num_rows($queryOrder)>0){
		while($mas=mysql_fetch_array($queryOrder)){
			echo "<div><input type='checkbox' name='cbOrderDel' class='cbOrderDel' data-order_id='$mas[order_id]' data-client_id='$mas[client_id]' data-old_status='$mas[status]'><a href='#' class='aOrderDel'> ID: $mas[order_id]. Дата: $mas[date], $mas[time].</a> Статус заказа: $mas[status].<br>Клиент: $mas[name] $mas[lname] . Телефон: $mas[tel]. E-mail: $mas[email]. Адресс: $mas[address] <br> <button type='button' class='btnShowInfoOrder' >Подробнее</button> <button type='button' class='btnHideInfoOrder'>Скрыть информацию </button><br>"; 

			$queryOP = mysql_query("SELECT `order_product`.`product_id` as `product_id`, `order_product`.`count` as `count`, `order_product`.`product_price` as `price`, `product`.`name` as `name`, `product`.`count` as `sklad`  
			FROM order_product, product 
			WHERE order_product.order_id = '$mas[order_id]' AND `order_product`.`product_id` = `product`.`id`") or die(mysql_error());
			$out = '<div class="divInfoOrder">';
			$out .= "<table>";
			$out .= "<tr>";
			$out .= "<td><p>Название товара</p></td>";
			$out .= "<td colspan='2'><p>Цена</p></td>";
			$out .= "<td class='tdCountProduct'><p>Количество</p></td>";
			$out .= "<td = colspan='3'><p>Сумма</p></td>";
			$out .= "</tr>";	
			while($masOP=mysql_fetch_array($queryOP)){//Информация о продуктах
				$out .= "<tr class='trProduct' data-cost='$mas[cost]' data-order_id='$mas[order_id]' data-product_id='$masOP[product_id]' data-product_price='$masOP[price]'>";
				$out .= "<td><a href='catalog.php?productId=$masOP[product_id]'>$masOP[name]</a></td>";

				$out .= "<td class='tdPriceProduct'><span class='spanPrice'>$masOP[price]</span> руб.</td>";
				$out .= "<td>*</td>";
				$out .= "<td class='tdCountProduct'><input type='number' class='inputCount' min='0' max='$masOP[sklad]' value='$masOP[count]'></td>";
				$out .= "<td>=</td>";
				$out .= "<td class='tdSum'><span class='spanResult'>".($masOP['price']*$masOP['count'])."</span> руб.</td>";
				$out .= "</tr>";
			}
			$out .= "<tr class='trSumPriceCart'><td class='tdSumPriceCart' data-cost='$mas[cost]' colspan='7'>Общая сумма: <span class='spanSumPriceCart'>$mas[cost]</span> руб.</td></tr>";
			$out .= "</table><button type='button' class='btnUpdateOrder' data-order_id='$mas[order_id]'>Сохранить данные</button>";
			
			$out .= "</div>";
			echo "$out</div>";
		}
	}else{
		echo "Поиск не дал результатов.";
	}
	
}



































//if(isset($_POST['body_name'])&&($_POST['body_name']=='del')){
//	$orders_id = $_POST['order_id'];
//	foreach($orders_id as $id){
//		mysql_query("DELETE FROM `order` WHERE id='$id'") or die(mysql_error());
//		mysql_query("DELETE FROM order_product WHERE order_id='$id'") or die(mysql_error());
//	}
//	echo "Данные успешно удалены";
//}else if(isset($_POST['body_name'])&&($_POST['body_name']=='update')){
//	$parametrs = $_POST['arrayProducts'];
//	$order_id = $_POST['order_id'];
//	foreach($parametrs as $param){
//		$count = $param['count'];
//		$product_id = $param['product_id'];
//		$product_price = $param['product_price'];
//		$cost = $param['cost'];
//		mysql_query("UPDATE `order` SET cost = '$cost' WHERE id='$order_id'") or die(mysql_error());
//		mysql_query("UPDATE `order_product` SET `count` = '$count'
//		WHERE order_id='$order_id' AND product_id='$product_id'") or die(mysql_error());		
//	}
//	echo "Данные успешно обновлены";
//	
//}else if(isset($_POST['dateFrom'])){
//	$dateFrom = $_POST['dateFrom'];
//	$dateTo = $_POST['dateTo'];
//	$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
//	FROM `order`, order_product, client, `orderstatus` 
//	WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id AND `order`.`status_id` = `orderstatus`.`id` 
//	AND `order`.`date` >= '$dateFrom' AND `order`.`date` <= '$dateTo' GROUP BY `order`.`id` ") or die(mysql_error());
//	
//	if(mysql_num_rows($queryOrder)>0){
//		while($mas=mysql_fetch_array($queryOrder)){
//			echo "<div><input type='checkbox' name='cbOrderDel' class='cbOrderDel' data-order_id='$mas[order_id]' data-client_id='$mas[client_id]'><a href='#' class='aOrderDel'> ID: $mas[order_id]. Дата: $mas[date], $mas[time].</a> Статус заказа: $mas[status].<br>Клиент: $mas[name] $mas[lname] . Телефон: $mas[tel]. E-mail: $mas[email]. Адресс: $mas[address] <br> <button type='button' class='btnShowInfoOrder' >Подробнее</button> <button type='button' class='btnHideInfoOrder'>Скрыть информацию </button><br>"; 
//
//			$queryOP = mysql_query("SELECT `order_product`.`product_id` as `product_id`, `order_product`.`count` as `count`, `order_product`.`product_price` as `price`, `product`.`name` as `name`, `product`.`count` as `sklad`  
//			FROM order_product, product 
//			WHERE order_product.order_id = '$mas[order_id]' AND `order_product`.`product_id` = `product`.`id`") or die(mysql_error());
//			$out = '<div class="divInfoOrder">';
//			$out .= "<table>";
//			$out .= "<tr>";
//			$out .= "<td><p>Название товара</p></td>";
//			$out .= "<td colspan='2'><p>Цена</p></td>";
//			$out .= "<td class='tdCountProduct'><p>Количество</p></td>";
//			$out .= "<td = colspan='3'><p>Сумма</p></td>";
//			$out .= "</tr>";	
//			while($masOP=mysql_fetch_array($queryOP)){//Информация о продуктах
//				$out .= "<tr class='trProduct' data-cost='$mas[cost]' data-order_id='$mas[order_id]' data-product_id='$masOP[product_id]' data-product_price='$masOP[price]'>";
//				$out .= "<td><a href='catalog.php?productId=$masOP[product_id]'>$masOP[name]</a></td>";
//
//				$out .= "<td class='tdPriceProduct'><span class='spanPrice'>$masOP[price]</span> руб.</td>";
//				$out .= "<td>*</td>";
//				$out .= "<td class='tdCountProduct'><input type='number' class='inputCount' min='0' max='$masOP[sklad]' value='$masOP[count]'></td>";
//				$out .= "<td>=</td>";
//				$out .= "<td class='tdSum'><span class='spanResult'>".($masOP['price']*$masOP['count'])."</span> руб.</td>";
//				$out .= "</tr>";
//			}
//			$out .= "<tr class='trSumPriceCart'><td class='tdSumPriceCart' data-cost='$mas[cost]' colspan='7'>Общая сумма: <span class='spanSumPriceCart'>$mas[cost]</span> руб.</td></tr>";
//			$out .= "</table><button type='button' class='btnUpdateOrder' data-order_id='$mas[order_id]'>Сохранить данные</button>";
//			$out .= "</div>";
//			echo "$out</div>";
//		}
//	}else{
//		echo "Поиск не дал результатов.";
//	}
//}else if(isset($_POST['searchLogin'])){
//	$search = validValue($_POST['searchLogin']);
//	$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
//	FROM `order`, order_product, client, `orderstatus`  
//	WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id AND `order`.`status_id` = `orderstatus`.`id` 
//	AND `client`.`email` LIKE '%$search%' GROUP BY `order`.`id` ") or die(mysql_error());
//	
//	if(mysql_num_rows($queryOrder)>0){
//		while($mas=mysql_fetch_array($queryOrder)){
//			echo "<div><input type='checkbox' name='cbOrderDel' class='cbOrderDel' data-order_id='$mas[order_id]' data-client_id='$mas[client_id]'><a href='#' class='aOrderDel'> ID: $mas[order_id]. Дата: $mas[date], $mas[time].</a> Статус заказа: $mas[status].<br>Клиент: $mas[name] $mas[lname] . Телефон: $mas[tel]. E-mail: $mas[email]. Адресс: $mas[address] <br> <button type='button' class='btnShowInfoOrder' >Подробнее</button> <button type='button' class='btnHideInfoOrder'>Скрыть информацию </button><br>"; 
//
//			$queryOP = mysql_query("SELECT `order_product`.`product_id` as `product_id`, `order_product`.`count` as `count`, `order_product`.`product_price` as `price`, `product`.`name` as `name`, `product`.`count` as `sklad`  
//			FROM order_product, product 
//			WHERE order_product.order_id = '$mas[order_id]' AND `order_product`.`product_id` = `product`.`id`") or die(mysql_error());
//			$out = '<div class="divInfoOrder">';
//			$out .= "<table>";
//			$out .= "<tr>";
//			$out .= "<td><p>Название товара</p></td>";
//			$out .= "<td colspan='2'><p>Цена</p></td>";
//			$out .= "<td class='tdCountProduct'><p>Количество</p></td>";
//			$out .= "<td = colspan='3'><p>Сумма</p></td>";
//			$out .= "</tr>";	
//			while($masOP=mysql_fetch_array($queryOP)){//Информация о продуктах
//				$out .= "<tr class='trProduct' data-cost='$mas[cost]' data-order_id='$mas[order_id]' data-product_id='$masOP[product_id]' data-product_price='$masOP[price]'>";
//				$out .= "<td><a href='catalog.php?productId=$masOP[product_id]'>$masOP[name]</a></td>";
//
//				$out .= "<td class='tdPriceProduct'><span class='spanPrice'>$masOP[price]</span> руб.</td>";
//				$out .= "<td>*</td>";
//				$out .= "<td class='tdCountProduct'><input type='number' class='inputCount' min='0' max='$masOP[sklad]' value='$masOP[count]'></td>";
//				$out .= "<td>=</td>";
//				$out .= "<td class='tdSum'><span class='spanResult'>".($masOP['price']*$masOP['count'])."</span> руб.</td>";
//				$out .= "</tr>";
//			}
//			$out .= "<tr class='trSumPriceCart'><td class='tdSumPriceCart' data-cost='$mas[cost]' colspan='7'>Общая сумма: <span class='spanSumPriceCart'>$mas[cost]</span> руб.</td></tr>";
//			$out .= "</table><button type='button' class='btnUpdateOrder' data-order_id='$mas[order_id]'>Сохранить данные</button>";
//			$out .= "</div>";
//			echo "$out</div>";
//		}
//	}else{
//		echo "Поиск не дал результатов.";
//	}
//}


?>