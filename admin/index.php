<?php
include('settings/connectDB.php');
include('../settings/function.php');
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
			<h1>Административная зона</h1>
			<div id='divContent'>
				<div id='formSQLQuery'>
					<p>Выполнить SQL-запрос к базе данных:</p>
					<textarea name="sqlQuery" id="sqlQuery"></textarea>
					<button id='btnSubmit'>Отправить</button>
					<button id='btnQuerySelect'>SELECT * FROM</button>
					<button id='btnQueryInsert'>INSERT INTO</button>
					<button id='btnQueryUpdate'>UPDATE</button>
					<button id='btnQueryDelete'>DELETE FROM</button>
					<button id='btnReset'>Очистить</button>
				</div>
				<div id='resultSQLQuery'></div>
			</div>
		</section>
	</main>
	<script src="scriptErrors.js"></script>
	<script src='scriptFunction.js'></script>
	<?php include('include/popup.php');?>
	<?php include('include/popup_parametr_add.php');?>
	<?php include('include/popup_discount_add.php');?>
	<?php include('include/popup_log.php');?>
	<script>
		$('#btnSubmit').click(function(){
			var conf = true;
			if($.trim($('#sqlQuery').val()).length>0){
				if(($('#sqlQuery').val()).indexOf("DELETE") != -1 ||($('#sqlQuery').val()).indexOf("DROP") != -1){
					 conf = confirm("Вы точно хотите произвести удаление?"); 
			   	}	
				if(conf){
					$.ajax({
						url:'obr_sqlQuery.php',
						type:'POST',
						data:{
							'sqlQuery':$('#sqlQuery').val()
						},
						success: function(output){
							$('#resultSQLQuery').html(output);
						},
						beforeSend:function(){
							$('#resultSQLQuery').html('<img src="../image/load.gif" alt="load">');
						}

					});
				}
			}else{
				var msg = 'Необходимо заполнить поле для запросов';
				showMsg($('#divMessage'),msg);
			}
		});
		
		$('#btnReset').click(function(){
			$('#sqlQuery').val('');
		});
		$('#btnQuerySelect').click(function(){
			$('#sqlQuery').val($('#sqlQuery').val()+'SELECT * FROM table ');
		});
		$('#btnQueryInsert').click(function(){
			$('#sqlQuery').val($('#sqlQuery').val()+"INSERT INTO table (atr) VALUES ('value') ");
		});
		$('#btnQueryUpdate').click(function(){
			$('#sqlQuery').val($('#sqlQuery').val()+"UPDATE table SET atr='value' WHERE ");
		});
		$('#btnQueryDelete').click(function(){
			$('#sqlQuery').val($('#sqlQuery').val()+'DELETE FROM table WHERE ');
		});
		
	</script>
	
	
</body>

</html>
