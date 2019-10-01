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
<body id='type_parametr_update'>
   <div id='divMessage'></div>
    <header><?php include('include/header.php');?></header>   
    <main>
       <section id="left-section">
            <?php include('include/main-left-section.php');?>
       </section><!--
    --><section id="content">
    <h1>Изменение типа параметра категории</h1>
    <div id='divContent'>
    	<?php 
			$query=mysql_query("SELECT * FROM type_parametr");
			while($mas=mysql_fetch_array($query)){
				echo "<p><a href='#' class='aTypeParametrUpdate' data-type_parametr_id='$mas[id]' data-type_parametr_name='$mas[name]'>ID: $mas[id]. $mas[name]</a></p>";
			}
		?>
    </div>
       </section>
    </main>
	<script src='scriptFunction.js'></script>
    <?php include('include/popup.php');?>
    <?php include('include/popup_parametr_add.php');?>
    <?php include('include/popup_type_parametr_update.php');?>
    <?php include('include/popup_discount_add.php');?>
    <?php include('include/popup_log.php');?>
	<script src='scriptErrors.js'></script>
    <script src='scriptForTypeParametr.js'></script>
    
</body>
</html>