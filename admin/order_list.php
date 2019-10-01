<?php
include("settings/connectDB.php");
session_start();
if(!isset($_SESSION['admin'])||($_SESSION['admin']!=1)){
	exit ('Вход на эту страницу доступен только администраторам!');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="style.css">
	<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
</head>
<style>
	.divInfoOrder{
		display: none;
	}	
	
	
</style>
<body>
	<div id="divMessage"></div>
	<header><?php include('include/header.php');?></header>
	<main>
		<section id="left-section">
			<?php include('include/main-left-section.php');?>
		</section><!--
    --><section id="content">
			<h1>Список заказов</h1>
			<div id='divContent'>
				<div id='header'>
					<div id='search'>
						От <input type="date" id='searchOrderFrom'>
						До <input type="date" id='searchOrderTo'>
						<button type="button" id='btnSearchOrder'>Поиск по дате</button>
						<?php
							if(isset($_POST['email'])&&!empty($_POST['email'])){
								$email = $_POST['email'];
							}
						?>
						<input type="search" id='searchOrderLogin' placeholder="Поиск по email" value='<?php echo $email;?>'>
						<button type="button" id='btnSearchOrderLogin'>Поиск</button><br>
						<div id='searchStatus'>
							
	<!--						<button type="button" id='btnShowClientReg'>Показать заказы авторизованных пользователей</button>-->
	<!--						<button type="button" id='btnShowAll'>Показать всех</button>-->
							<select name="selectStatusOrderSearch" id="selectStatusOrderSearch">
								<?php
									$queryStatus = mysql_query("SELECT * FROM orderstatus");
									while($mas = mysql_fetch_array($queryStatus)){
										echo "<option value='$mas[id]'>$mas[name]</option>";
									}
								?>

							</select> <button type="button" id='btnSearchOrderStatus'>Поиск по статусу</button>
						</div>
						
					</div>
					<span id='spanCBSelectAll'><input type="checkbox" name='cbSelectAll' id='cbSelectAll'> Выделить все</span>
					<button type="button" id='btnDel'>Удалить</button>
					<button type="button" id='btnUpdateOrderStatus'>Изменить статус заказа на: </button>
					<select name="selectStatusUpdate" id="selectStatusUpdate">
							<?php
								$queryStatus = mysql_query("SELECT * FROM orderstatus");
								while($mas = mysql_fetch_array($queryStatus)){
									echo "<option value='$mas[id]'>$mas[name]</option>";
								}
							?>
							
						</select>
					<a href="order_list.php">Обновить список</a>
				</div>
				<div id='list'>
					<?php 
						$queryOrder = mysql_query("SELECT `order`.`id` as `order_id`, `order`.`cost` as `cost`, `order`.`date` as `date`, `order`.`time` as `time`, `client`.`id` as `client_id`, `client`.`name` as `name`, `client`.`lname` as `lname`, `client`.`tel` as `tel`, `client`.`email` as `email`, `client`.`address` as `address`, orderstatus.name as `status` 
						FROM `order`, order_product, client, `orderstatus`   
						WHERE `order`.id = order_product.order_id AND `order`.client_id = client.id 
						AND `order`.`status_id` = `orderstatus`.`id` GROUP BY `order`.`id`") or die(mysql_error());

						while($mas=mysql_fetch_array($queryOrder)){
							echo "<div><input type='checkbox' name='cbOrderDel' class='cbOrderDel' data-order_id='$mas[order_id]' data-client_id='$mas[client_id]' data-old_status='$mas[status]'><a href='#' class='aOrderDel'> ID: $mas[order_id]. Дата: $mas[date], $mas[time].</a> Статус заказа: $mas[status]. <br>Клиент: $mas[name] $mas[lname] . Телефон: $mas[tel]. E-mail: $mas[email]. Адресс: $mas[address] <br> <button type='button' class='btnShowInfoOrder' >Подробнее</button> <button type='button' class='btnHideInfoOrder'>Скрыть информацию </button><br>"; 
							
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
//								$out .= "<td class='tdCountProduct'><input type='number' class='inputCount' min='0' max='$masOP[sklad]' value='$masOP[count]'></td>";
								$out .= "<td>=</td>";
								$out .= "<td class='tdSum'><span class='spanResult'>".($masOP['price']*$masOP['count'])."</span> руб.</td>";
								$out .= "</tr>";
							}
							$out .= "<tr class='trSumPriceCart'><td class='tdSumPriceCart' data-cost='$mas[cost]' colspan='7'>Общая сумма: <span class='spanSumPriceCart'>$mas[cost]</span> руб.</td></tr>";
							$out .= "</table><button type='button' class='btnUpdateOrder' data-order_id='$mas[order_id]'>Сохранить данные</button> ";
//							if($mas[status]=='Отклонен'){
//								$out .= "<button type='button' class='btnRecover'>Восстановить заявку после отклонения</button>";
//							}
							$out .= "</div>";
							echo "$out</div>";
						}
					?>
				</div>
			</div>
		</section>
		
	</main>

	<script src='scriptErrors.js'></script>
	<script src='scriptFunction.js'></script>
	<?php include('include/popup.php');?>
	<?php include('include/popup_parametr_add.php');?>
	<?php include('include/popup_discount_add.php');?>
	<?php include('include/popup_log.php');?>

	<script>
		$(document).ready(function(){
			if($('#searchOrderLogin').val()!=''){
				$('#btnSearchOrderLogin').trigger('click');
			}
		});
		
		$('#btnSearchOrderStatus').click(function() {
			var status = $('#selectStatusOrderSearch').val();
			$.ajax({
				url: 'obr_order.php',
				type: 'POST',
				data: {
					'searchOrderStatus': status,
				},
				success: function(output) {
					$('#divContent #list').html(output);
				},
				beforeSend:function(){
					$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
				}
			});	
			
		});
		
		$('#btnUpdateOrderStatus').click(function() {//-----------------------------------Обновление статуса
			var status = $('#selectStatusUpdate').val();
			var cb = $('.cbOrderDel');
			var arrayCB = {};
			var arrayProducts = {};
			for (var i = 0; i < cb.length; i++) {
				if ($(cb[i]).prop('checked') == true) {
					var tr = $(cb[i]).parent().children('.divInfoOrder').children('table').children().children('.trProduct');
					for(var j = 0; j<tr.length; j++){
						arrayProducts[j]={
							'count':$(tr[j]).children('.tdCountProduct').children('.inputCount').val(),
							'product_id': $(tr[j]).attr('data-product_id'),
							'product_price': $(tr[j]).attr('data-product_price'),
							'cost': $(tr[j]).attr('data-cost'),
							'old_count': $(tr[j]).attr('data-product_old_count'),
						}
					}
					arrayCB[i]={
						'order_id': $(cb[i]).attr('data-order_id'),
						'products': arrayProducts,
						'old_status': $(cb[i]).attr('data-old_status'),
					}
					arrayProducts = {};
				}
			}
			if(arrayCB.length==0){
				showMsg($('#divMessage'), 'Необходимо выбрать элементы для изменения статуса заказа');
			}else{
				var conf = confirm("Вы точно хотите изменить статус заказа?");
				console.log(arrayCB);
				if(conf){
					$.ajax({
						url: 'obr_order.php',
						type: 'POST',
						data: {
							'arrayOrder': arrayCB,
							'status': status,
							'body_name': 'updateOrderStatus'
						},
						success: function(output) {
							$('#divContent #list').html(output);
						},
						beforeSend:function(){
							$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
						}
					});	
				}
			}

			
		});
		
		
		$(document).on('click','.btnRecover', function(){
			var par = $(this).parent('.divInfoOrder');
			var tr = par.children('table').children().children('.trProduct');	
			var arrayProducts={};
			var order_id = $(tr[0]).attr('data-order_id');
			for(var i = 0; i<tr.length; i++){
				arrayProducts[i]={
					'count':$(tr[i]).children('.tdCountProduct').children('.inputCount').val(),
					'product_id': $(tr[i]).attr('data-product_id'),
					'product_price': $(tr[i]).attr('data-product_price'),
					'cost': $(tr[i]).attr('data-cost'),
					'old_count': $(tr[i]).attr('data-product_old_count'),
				}
			}
			console.log(arrayProducts);
			$.ajax({
					url: 'obr_order.php',
					type: 'POST',
					data: {
						'arrayProducts': arrayProducts,
						'body_name': 'update',
						'order_id': order_id
					},
					success: function(output) {
						$('#divContent #list').html(output);
					},
					beforeSend:function(){
						$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
					}
			});
			
		});
		$(document).on('click','.btnUpdateOrder', function(){
			var par = $(this).parent('.divInfoOrder');
			var tr = par.children('table').children().children('.trProduct');	
			var arrayProducts={};
			var order_id = $(tr[0]).attr('data-order_id');
			for(var i = 0; i<tr.length; i++){
				arrayProducts[i]={
					'count':$(tr[i]).children('.tdCountProduct').children('.inputCount').val(),
					'product_id': $(tr[i]).attr('data-product_id'),
					'product_price': $(tr[i]).attr('data-product_price'),
					'cost': $(tr[i]).attr('data-cost'),
					'old_count': $(tr[i]).attr('data-product_old_count'),
				}
			}
			console.log(arrayProducts);
			$.ajax({
					url: 'obr_order.php',
					type: 'POST',
					data: {
						'arrayProducts': arrayProducts,
						'body_name': 'update',
						'order_id': order_id
					},
					success: function(output) {
						$('#divContent #list').html(output);
					},
					beforeSend:function(){
						$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
					}
			});
			
		});
		
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
		
		$(document).on('click','.btnShowInfoOrder', function(){
			var divInfoOrder = $(this).siblings('.divInfoOrder');
//			console.log(divInfoOrder);	
			divInfoOrder.show();
		});
		$(document).on('click','.btnHideInfoOrder', function(){
			var divInfoOrder = $(this).siblings('.divInfoOrder');
//			console.log(divInfoOrder);	
			divInfoOrder.hide();
		});
		
		
		
		$('#cbSelectAll').change(function() {
			if ($(this).prop('checked') == true) {
				$('.cbOrderDel').prop('checked', true);
			} else {
				$('.cbOrderDel').prop('checked', false);
			}
		});

		$(document).on('click','.aOrderDel', function(){
			var cb = $(this).siblings('.cbOrderDel');
			if (cb.prop('checked') == true) {
				cb.prop('checked', false);
			} else {
				cb.prop('checked', true);
			}
		});
//		$('.aOrderDel').click(function() {
//			
//		});
		
		$('#btnDel').click(function() {
			var cb = $('.cbOrderDel');
			var arrayCB = Array();
			for (var i = 0; i < cb.length; i++) {
				if ($(cb[i]).prop('checked') == true) {
					arrayCB.push($(cb[i]).attr('data-order_id'));
				}
			}
			if(arrayCB.length==0){
				showMsg($('#divMessage'), 'Необходимо выбрать элементы для удаления');
			}else{
				$.ajax({
					url: 'obr_order.php',
					type: 'POST',
					data: {
						'order_id': arrayCB,
						'body_name': 'del'
					},
					success: function(output) {
						$('#divContent #list').html(output);
					},
					beforeSend:function(){
						$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
					}
				});	
			}

			
		});
		
		
		$(document).on('click', '#btnSearchOrder', function() {
			var dateFrom = $('#search #searchOrderFrom').val();
			var dateTo = $('#search #searchOrderTo').val();
			$.ajax({
				url: 'obr_order.php',
				type: 'POST',
				data: {
					'dateFrom': dateFrom,
					'dateTo': dateTo
				},
				success: function(output) {
					$('#divContent #list').html(output);
				},
				beforeSend:function(){
					$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
				}
			});
		});
		$(document).on('click', '#btnSearchOrderLogin', function() {
			var searchLogin = $('#search #searchOrderLogin').val();
			$.ajax({
				url: 'obr_order.php',
				type: 'POST',
				data: {
					'searchLogin': searchLogin,
				},
				success: function(output) {
					$('#divContent #list').html(output);
				},
				beforeSend:function(){
					$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
				}
			});
		});
		
		

//		$(document).on('click', '#btnShowAll', function() {
//			var client = $('.divClient');
//			for (var i = 0; i < client.length; i++) {
//				$(client[i]).show();
//			}
//		});

//		$(document).on('click', '#btnShowClientReg', function() {
//			var client = $('.divClient');
//			for (var i = 0; i < client.length; i++) {
//				if ($(client[i]).attr('data-user_id') == '') {
//					$(client[i]).hide();
//				}
//			}
//		});

	</script>

</body>

</html>