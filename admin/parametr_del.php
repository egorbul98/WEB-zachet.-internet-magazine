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
<body id='parametr_del'>
   <div id='divMessage'></div>
    <header><?php include('include/header.php');?></header>   
    <main>
       <section id="left-section">
            <?php include('include/main-left-section.php');?>
       </section><!--
    --><section id="content">
		<h1>Удаление значения у параметра</h1>
		<div id='divContent'>
			<div id='header'>
				<span id='spanCBSelectAll'><input type="checkbox" name='cbSelectAll' id='cbSelectAll'> Выделить все</span>
				<button type="button" id='btnDel'>Удалить</button>
			</div>
				<?php 
					$query=mysql_query("SELECT parametr.name as Pname, parametr.id as Pid, parametr.type_parametr_id as Ptype_parametr_id, type_parametr.name as TPname, type_parametr.id as TPid FROM parametr, type_parametr WHERE parametr.type_parametr_id = type_parametr.id");
					while($mas=mysql_fetch_array($query)){
						echo "<div><input type='checkbox' class='cbPdel' data-parametr_id='$mas[Pid]' data-parametr_name='$mas[Pname]' data-type_parametr_id='$mas[Ptype_parametr_id]' data-type_parametr_name='$mas[TPname]'><a href='#' class='aParametrDel'> ID: $mas[Pid]. Тип параметра: $mas[TPname].  Значение: $mas[Pname]</a></div>";
					}
				?>
		</div>
       </section>
    </main>
    <script src='scriptErrors.js'></script>
    <script src='scriptFunction.js'></script>
    <?php include('include/popup.php');?>
    <?php include('include/popup_parametr_add.php');?>
    <?php include('include/popup_discount_add.php');?>
    <?php include('include/popup_log.php');?>
<!--	<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>-->
	<script src='scriptForParametr.js'></script>

    
</body>
</html>