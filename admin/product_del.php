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
	<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
</head>

<body id='product_del'>
<div id='divMessage'></div>
	<header><?php include('include/header.php');?></header>
	<main>
		<section id="left-section">
			<?php include('include/main-left-section.php');?>
		</section><!--
     --><section id="content">
			<h1>Удаление товара</h1>
			<?php 
                if(!isset($_GET['category_id'])){//Показать таблицу категорий
                    $categories = getCategoriesChilds();//0 - главные категории
                    echo "<table id='table_listCategories'>";
                    echo "<caption>Выберите категорию, в которой будут изменятся товары</caption>";
                    echo "<tr>";
                    $i=0;
                    foreach($categories as $cat){
                        $i++;
                        if($i>4){
                            echo "<tr>";
                            $i=1;
                        }
                        echo "<td>";
                            if(isset($cat['child'])){
                                echo "<p>$cat[name]</p>"; 
								getListCategoriesForAdmin($cat['child'],'product_del.php');
                            }else{
                                echo "<a href='product_update.php?category_id=$cat[id]&name=$cat[name]'>$cat[name]</a><br>";             
                            }
                        echo "</td>";
                        if($i>4){
                            echo "</tr>";
                        }
                    }
                    echo "</tr>";
                    echo "</table>";
                }else if(isset($_GET['category_id'])){//ВЫводим список товаров
					$category_id=$_GET['category_id'];
					$category_name=$_GET['name'];
					echo "<h2>Категория '$category_name'</h2>";
					echo "<div id='divContent'>";
					echo "<div id='header'><span id='spanCBSelectAll'><input type='checkbox' name='cbSelectAll' id='cbSelectAll'> Выделить все</span> <button type='button' id='btnDelProduct'>Удалить выбранные товары</button></div>";
//					echo "<button type='button' id='btnDelProduct'>Удалить выбранные товары</button>";
					echo "<input type='hidden' id='category_id' value='$category_id'>";
					$queryP=mysql_query("SELECT * FROM product WHERE category_id='$category_id'");
					while($masP=mysql_fetch_array($queryP)){
						echo "<p><input type='checkbox' name='cbDelProduct' class='cbDelProduct' value='$masP[id]'> Код товара: $masP[id]; Название: $masP[name]; Цена: $masP[price]</p>";
					}	
					echo "</div>";
				?>
				<div id='divListProduct'></div>
				<script src="scriptErrors.js"></script>
				<script src="scriptForProduct.js"></script>
				
			<?php
                }
				?>

		</section>
	</main>
	<script src='scriptFunction.js'></script>
	<?php include('include/popup.php');?>
	<?php include('include/popup_parametr_add.php');?>
	<?php include('include/popup_discount_add.php');?>
	<?php include('include/popup_log.php');?>
</body>

</html>
