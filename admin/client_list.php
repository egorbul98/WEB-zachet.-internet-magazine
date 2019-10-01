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
	
	<style>
		#tableClients {
			display: block;
			left: 0;
			text-align: left;
			border-spacing: 10px;
		}
		
		#list form {
			margin: 0;
			text-align: left;
		}
	
	</style>
</head>

<body>
	<div id="divMessage"></div>
	<header><?php include('include/header.php');?></header>
	<main>
		<section id="left-section">
			<?php include('include/main-left-section.php');?>
		</section><!--
    --><section id="content">
			<h1>Список клиентов</h1>
			<div id='divContent'>
				<div id='header'>
					<div id='search'>
						<input type="search" id='searchClient'>
						<button type="button" id='btnSearchClient'>Поиск</button>
						<button type="button" id='btnShowClientReg'>Показать зарегистрированных на сайте</button>
						<button type="button" id='btnShowAll'>Показать всех</button>
						<a href="client_list.php">Обновить список</a>
					</div>
					<span id='spanCBSelectAll'><input type="checkbox" name='cbSelectAll' id='cbSelectAll'> Выделить все</span>
					<button type="button" id='btnDel'>Удалить</button>
				</div>
				<div id='list'>
				
					<?php 
//					while($mas=mysql_fetch_array($query)){
//						echo "<form action='order_list.php' method='post'><div class='divClient' data-client_id='$mas[id]' data-client_name='$mas[name]' data-user_id='$mas[user_id]'><input type='checkbox' name='cbClientDel' class='cbClientDel' data-client_id='$mas[id]'><a href='#' class='aClientDel' > ID: $mas[id]. $mas[name] $mas[lname]. Телефон: $mas[tel]. E-mail: $mas[email]</a><input type='hidden' name='email' value='$mas[email]'><button type='submit' class='btnShowOrders' css='padding:0px;'>Показать заказы клиента</button></div></form>";
//					}
					
					
					$query=mysql_query("SELECT * FROM client");
					
					
					echo "<table id='tableClients'>";
					while($mas=mysql_fetch_array($query)){
						echo "<form action='order_list.php' method='post'>";
						echo "<div class='divClient' data-client_id='$mas[id]' data-client_name='$mas[name]' data-user_id='$mas[user_id]'>";
						
						echo "<tr>";
						echo "<td><input type='checkbox' name='cbClientDel' class='cbClientDel' data-client_id='$mas[id]'><a href='#' class='aClientDel' > ID: $mas[id]. $mas[name] $mas[lname]. Телефон: $mas[tel]. E-mail: $mas[email]</a><input type='hidden' name='email' value='$mas[email]'></td><td><button type='submit' class='btnShowOrders' css='padding:0px;'>Показать заказы клиента</button></td>";
						
						echo "</tr>";
						
						echo "</div>";
					echo "</form>";
					}
					echo "</table>";
					
					
					
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
		$('#cbSelectAll').change(function() {
			if ($(this).prop('checked') == true) {
				$('.cbClientDel').prop('checked', true);
			} else {
				$('.cbClientDel').prop('checked', false);
			}
		});

		$('.aClientDel').click(function() {
			var cb = $(this).siblings('.cbClientDel');
			if (cb.prop('checked') == true) {
				cb.prop('checked', false);
			} else {
				cb.prop('checked', true);
			}
		});
		
		$(document).on('click', '.btnShowOrders', function() {
			
			
			
		});
		
		$('#btnDel').click(function() {
			var cb = $('.cbClientDel');
			var arrayCB = Array();
			for (var i = 0; i < cb.length; i++) {
				if ($(cb[i]).prop('checked') == true) {
					arrayCB.push($(cb[i]).attr('data-client_id'));
				}
			}
			if(arrayCB.length==0){
				showMsg($('#divMessage'), 'Необходимо выбрать элементы для удаления');
			}else{
				$.ajax({
					url: 'obr_client.php',
					type: 'POST',
					data: {
						'client_id': arrayCB,
						'body_name': 'del'
					},
					success: function(output) {
						showMsg($('#divMessage'), output);
					}
				});	
			}

			
		});
		
		$(document).on('click', '#btnSearchClient', function() {
			var search = $('#search #searchClient').val();
			$.ajax({
				url: 'obr_client.php',
				type: 'POST',
				data: {
					'searchClient': search,
				},
				success: function(output) {
					$('#divContent #list').html(output);
				}
			});
		});
		
		

		$(document).on('click', '#btnShowAll', function() {
			var client = $('.divClient');
			for (var i = 0; i < client.length; i++) {
				$(client[i]).show();
			}
		});

		$(document).on('click', '#btnShowClientReg', function() {
			var client = $('.divClient');
			for (var i = 0; i < client.length; i++) {
				if ($(client[i]).attr('data-user_id') == '') {
					$(client[i]).hide();
				}
			}
		});

	</script>

</body>

</html>