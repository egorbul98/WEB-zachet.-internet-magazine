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
<body id='type_parametr_del'>
	<div id='divMessage'></div>
	<header><?php include('include/header.php');?></header>
	<main>
		<section id="left-section">
			<?php include('include/main-left-section.php');?>
		</section><!--
    --><section id="content">
			<h1>Изменение типа параметра категории</h1>
			<div id='divContent'>
				<div id='header'>
					<span id='spanCBSelectAll'><input type="checkbox" name='cbSelectAll' id='cbSelectAll'> Выделить все</span>
					<button type="button" id='btnDel'>Удалить</button>
				</div>
				<?php 
					$query=mysql_query("SELECT * FROM type_parametr");
					while($mas=mysql_fetch_array($query)){
						echo "<div><input type='checkbox' name='cbTPdel' class='cbTPdel' data-type_parametr_id='$mas[id]' data-type_parametr_name='$mas[name]'><a href='#' class='aTypeParametrDel' > ID: $mas[id]. $mas[name]</a></div>";
					}
				?>
			</div>
		</section>
	</main>
	<script src='scriptFunction.js'></script>
	<?php include('include/popup.php');?>
	<?php include('include/popup_parametr_add.php');?>
	<?php include('include/popup_log.php');?>
	<?php include('include/popup_discount_add.php');?>
	<!--    <script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>-->
	<script src='scriptErrors.js'></script>
	<script src='scriptForTypeParametr.js'></script>

</body>
</html>
