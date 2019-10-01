<?php
require('settings/connectDB.php');
require_once('settings/function.php');
$id = $_SESSION['user_id'];
$queryUser = mysql_query("SELECT * FROM user WHERE id='$id'");
$bol=true;//Проверка. Есть ли у пользователя заказы
if(mysql_num_rows($queryUser)>0){
	$masUser = mysql_fetch_array($queryUser);
	$queryClient =  mysql_query("SELECT * FROM client WHERE user_id='$id'");
	if(mysql_num_rows($queryClient)<=0){
		$bol = false;
	}
}else{
	$bol = false;
}

?>
			<div id='divContentOrder'>
			<h1>Список заказов</h1>
			<?php
				if(!$bol){//Если заказов у пользователя нет
					echo "У вас еще нет заказов";
				}else{
				?>
				<div id='header'>
					<div id='search'>
						От <input type="date" id='searchOrderFrom'>
						До <input type="date" id='searchOrderTo'>
						<input type='hidden' id='userEmail' value='<?php echo $masUser[email];?>'>
						<button type="button" id='btnSearchOrder'>Поиск по дате</button>
						<select name="selectStatusOrderSearch" id="selectStatusOrderSearch">
								<?php
									$queryStatus = mysql_query("SELECT * FROM orderstatus");
									while($mas = mysql_fetch_array($queryStatus)){
										echo "<option value='$mas[id]'>$mas[name]</option>";
									}
								?>

						</select> <button type="button" id='btnSearchOrderStatus'>Поиск по статусу</button>
					</div>
					<a href="user_orders.php">Обновить список</a>
				</div>
				<div id='list'>
					<?php 
						$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status`  
						FROM `order`, order_product, client, `orderstatus` 
						WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id 
						AND `order`.`status_id` = `orderstatus`.`id` 
						AND `client`.`email` = '$masUser[email]' GROUP BY `order`.`id`") or die(mysql_error());

						if(mysql_num_rows($queryOrder)>0){
							while($mas=mysql_fetch_array($queryOrder)){
								echo "<div><a href='#' class='aOrderDel'> ID: $mas[order_id]. Дата: $mas[date], $mas[time].</a> Статус заказа: $mas[status].<br>Клиент: $mas[name] $mas[lname] . Телефон: $mas[tel]. E-mail: $mas[email]. Адресс: $mas[address] <br> <button type='button' class='btnShowInfoOrder' >Подробнее</button> <button type='button' class='btnHideInfoOrder'>Скрыть информацию </button><br>"; 

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
						}
					?>
				</div>
				<?php
				}
				?>
			</div>
			
<script>

	$(document).on('input','.inputCount', function(){
		var par = $(this).parent().parent();
		var count = parseFloat($(this).val());
		var price = parseFloat(par.children('.tdPriceProduct').children('.spanPrice').text());
		par.children('.tdSum').children('.spanResult').text(count*price);

		updateTotalCost(par);
		function updateTotalCost(par){
			var tbody = par.parent('tbody');
			var trProduct = tbody.children('.trProduct');
//				var resultsCost = tbody.children('.trProduct').children('.tdSum').children('.spanResult');
			var resultsCost = trProduct.children('.tdSum').children('.spanResult');
			var sum = 0;
			for(var i = 0; i<resultsCost.length; i++){
				sum+=parseFloat($(resultsCost[i]).text());
			}
			tbody.children('.trSumPriceCart').children('.tdSumPriceCart').children('.spanSumPriceCart').text(sum);
			for(var i = 0; i<trProduct.length; i++){
				$(trProduct[i]).attr('data-cost',sum);
			}
		}
	});
	
	$('#btnSearchOrderStatus').click(function() {
			var status = $('#selectStatusOrderSearch').val();
			var email = $('#userEmail').val();
			$.ajax({
				url: 'obr_order.php',
				type: 'POST',
				data: {
					'searchOrderStatus': status,
					'email': email,
				},
				success: function(output) {
					$('#divContentOrder #list').html(output);
				},
				beforeSend:function(){
					$('#divContentOrder #list').html('<img src="image/load.gif" alt="load">');
				}
			});	
			
		});
	
	$(document).on('click','.btnShowInfoOrder', function(){
		var divInfoOrder = $(this).siblings('.divInfoOrder');
		divInfoOrder.show();
	});
	$(document).on('click','.btnHideInfoOrder', function(){
		var divInfoOrder = $(this).siblings('.divInfoOrder');
//			console.log(divInfoOrder);	
		divInfoOrder.hide();
	});

	$(document).on('click', '#btnSearchOrder', function() {
		var dateFrom = $('#search #searchOrderFrom').val();
		var dateTo = $('#search #searchOrderTo').val();
		var email = $('#userEmail').val();
		$.ajax({
			url: 'obr_order.php',
			type: 'POST',
			data: {
				'dateFrom': dateFrom,
				'dateTo': dateTo,
				'email':email,
			},
			success: function(output) {
				$('#divContentOrder #list').html(output);
			},
			beforeSend:function(){
				$('#divContentOrder #list').html('<img src="image/load.gif" alt="load">');
			}
		});
	});

</script>