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
		#list p{
			margin: 5px;
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
			<h1>Список скидок</h1>
			<div id='divContent'>
				<div id='list'>
					<?php 
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
<select name='selectDiscount' class='selectDiscount'>
	
	
</select>
	<script>
		$(document).on('click', '.btnDiscountDel', function() {
			var conf = confirm('Вы точно хотите удалить скидку? Скидка также будет удалена и у товаров.');
			if(conf){
				$.ajax({
					url: 'obr_discount.php',
					type: 'POST',
					data: {
						'body_name':'del',
						'id': $(this).attr('data-discount_id'),
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
		
	</script>

</body>

</html>