<?php
require('settings/connectDB.php');
require_once('settings/function.php');

if(isset($_POST['dateFrom'])||isset($_POST['searchOrderStatus'])){
	if(isset($_POST['dateFrom'])){
		$dateFrom = $_POST['dateFrom'];
		$dateTo = $_POST['dateTo'];
		$email = $_POST['email'];

		$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
		FROM `order`, order_product, client, `orderstatus` 
		WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id
		AND `client`.`email` = '$email' AND `order`.`status_id` = `orderstatus`.`id` 
		AND `order`.`date` >= '$dateFrom' AND `order`.`date` <= '$dateTo' 
		GROUP BY `order`.`id`") or die(mysql_error());
	}else if(isset($_POST['searchOrderStatus'])){
		$email = $_POST['email'];
		$status_id = $_POST['searchOrderStatus'];

		$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
		FROM `order`, order_product, client, `orderstatus` 
		WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id
		AND `client`.`email` = '$email' AND `order`.`status_id` = `orderstatus`.`id` 
		AND `order`.status_id = '$status_id' 
		GROUP BY `order`.`id`") or die(mysql_error());
	}
	
	
	if(mysql_num_rows($queryOrder)>0){
		while($mas=mysql_fetch_array($queryOrder)){
			echo "<div><a href='#' class='aOrderDel'> ID: $mas[order_id]. Дата: $mas[date], $mas[time].</a>  Статус заказа: $mas[status].<br>Клиент: $mas[name] $mas[lname] . Телефон: $mas[tel]. E-mail: $mas[email]. Адресс: $mas[address] <br> <button type='button' class='btnShowInfoOrder' >Подробнее</button> <button type='button' class='btnHideInfoOrder'>Скрыть информацию </button><br>"; 
			
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
				$out .= "<td class='tdCountProduct'><input type='text' readonly class='inputCount' value='$masOP[count]'></td>";
				$out .= "<td>=</td>";
				$out .= "<td class='tdSum'><span class='spanResult'>".($masOP['price']*$masOP['count'])."</span> руб.</td>";
				$out .= "</tr>";
			}
			$out .= "<tr class='trSumPriceCart'><td class='tdSumPriceCart' data-cost='$mas[cost]' colspan='7'>Общая сумма: <span class='spanSumPriceCart'>$mas[cost]</span> руб.</td></tr>";
			$out .= "</table>";
			$out .= "</div>";
			echo "$out</div>";
		}
	}else{
		echo "Поиск не дал результатов.";
	}
}

?>