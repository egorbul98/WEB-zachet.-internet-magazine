<?php
include("settings/connectDB.php");
include_once("../settings/function.php");
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
	<link rel="stylesheet" href="../font-awesome-4.7.0/css/font-awesome.min.css">
	<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
</head>

<body id='category_del'>
<div id="divMessage"></div>
	<header><?php include('include/header.php');?></header>
	<main>
		<section id="left-section">
			<?php include('include/main-left-section.php');?>
		</section><!--
	 --><section id="content">
			<h1>Удаление категорий</h1>
			<?php   
			if(!isset($_GET['category_id'])){//То выводим список категорий
				$categories = getCategoriesChilds();//0 - главные категории
				echo "<table id='table_listCategories'>";
				echo "<caption>Выберите родительскую категорию товара</caption>";
				echo "<tr>";
				$i=0;
				foreach($categories as $cat){
					$i++;
					if($i>4){
						echo "<tr>";
						$i=1;
					}
					echo "<td>";
						echo "<li><a data-categoryId='$cat[id]' onclick='delCategory(this)'> <i class='fa fa-times 2px'></i> </a>$cat[name]</li>";
						if(isset($cat['child'])){
							getListCategoriesForAdmin($cat['child'], 'category_del.php');
						}
					echo "</td>";
					if($i>4){
						echo "</tr>";
					}
				}
				echo "</tr>";
				echo "</table>";
			}		
		?>
			
		</section>
	</main>
	
	<script src='scriptErrors.js'></script>
	<script src='scriptForCategory.js'></script>
	<script src='scriptFunction.js'></script>
	<?php include('include/popup.php');?>
	<?php include('include/popup_parametr_add.php');?>
	<?php include('include/popup_discount_add.php');?>
	<?php include('include/popup_log.php');?>
</body>
</html>
