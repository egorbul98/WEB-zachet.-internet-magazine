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

<body>
	<div id="divMessage"></div>
	<header><?php include('include/header.php');?></header>
	<main>
		<section id="left-section">
			<?php include('include/main-left-section.php');?>
		</section><!--
    --><section id="content">
			<h1>Список пользователей</h1>
			<div id='divContent'>
				<div id='header'>
					<div id='search'>
						<input type="search" id='searchUser'>
						<button type="button" id='btnSearchUser'>Поиск</button>
						<button type="button" id='btnShowAdmin'>Показать администраторов</button>
						<button type="button" id='btnShowAll'>Показать всех</button>
						<a href="user_list.php">Отменить поиск и обновить список</a>
					</div>
					<span id='spanCBSelectAll'><input type="checkbox" name='cbSelectAll' id='cbSelectAll'> Выделить все</span>
					<button type="button" id='btnDel'>Удалить</button>
				</div>
				<div id='list'>
					<?php 
					$query=mysql_query("SELECT * FROM user");
					while($mas=mysql_fetch_array($query)){
						$admin='';
						if($mas['admin']=='1'){
							$admin='Администратор';
						}
						echo "<div class='divUser' data-user_id='$mas[id]' data-user_name='$mas[name]' data-user_admin='$mas[admin]'><input type='checkbox' name='cbUserDel' class='cbUserDel' data-user_id='$mas[id]'><a href='#' class='aUserDel' > ID: $mas[id]. $admin $mas[name] $mas[lname]. Логин: $mas[login]. E-mail: $mas[email]</a><br><button type='button' class='btnUserAdmin'>Сделать администратором / Удалить из администраторов</button></div>";
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
		$('#cbSelectAll').change(function() {
			if ($(this).prop('checked') == true) {
				$('.cbUserDel').prop('checked', true);
			} else {
				$('.cbUserDel').prop('checked', false);
			}
		});

		$('.aUserDel').click(function() {
			var cb = $(this).siblings('.cbUserDel');
			if (cb.prop('checked') == true) {
				cb.prop('checked', false);
			} else {
				cb.prop('checked', true);
			}
		});

		$('#btnDel').click(function() {
			var cb = $('.cbUserDel');
			console.log(cb);
			var arrayCB = Array();
			for (var i = 0; i < cb.length; i++) {
				if ($(cb[i]).prop('checked') == true) {
					arrayCB.push($(cb[i]).attr('data-user_id'));
					alert($(cb[i]).attr('data-user_id'));
				}
			}
			if(arrayCB.length==0){
				showMsg($('#divMessage'), 'Необходимо выбрать элементы для удаления');
			}else{
				$.ajax({
					url: 'obr_user.php',
					type: 'GET',
					data: {
						'users_id': arrayCB,
						'body_name': 'del'
					},
					success: function(output) {
						showMsg($('#divMessage'), output);
					}
				});	
			}

			
		});

		$(document).on('click', '.btnUserAdmin', function() {
			var parent = $(this).parent();
			var id = parent.attr('data-user_id');
			var admin = parent.attr('data-user_admin');
			if (admin == 0) {
				admin = 1;
			} else {
				admin = 0;
			}
			$.ajax({
				url: 'obr_user.php',
				type: 'GET',
				data: {
					'user_id': id,
					'user_admin': admin,
				},
				success: function(output) {
					$(parent).html(output);
				}
			});
		});
		$(document).on('click', '#btnSearchUser', function() {
			//ПОиск пользователей
			var search = $('#search #searchUser').val();
			$.ajax({
				url: 'obr_user.php',
				type: 'GET',
				data: {
					'searchUser': search,
				},
				success: function(output) {
					$('#divContent #list').html(output);
				}
			});
		});

		$(document).on('click', '#btnShowAll', function() {
			var user = $('.divUser');
			for (var i = 0; i < user.length; i++) {
				$(user[i]).show();
			}
		});

		$(document).on('click', '#btnShowAdmin', function() {
			var user = $('.divUser');
			for (var i = 0; i < user.length; i++) {
				if ($(user[i]).attr('data-user_admin') != '1') {
					$(user[i]).hide();
				}
			}
		});

	</script>

</body>

</html>
