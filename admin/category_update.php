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

<body id='category_update'>
<div id="divMessage"></div>
	<header><?php include('include/header.php');?></header>
	<main>
		<section id="left-section">
			<?php include('include/main-left-section.php');?>
		</section><!--
	 --><section id="content">
			<h1>Изменение категории</h1>
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
						echo "<a href='category_update.php?category_id=$cat[id]&name=$cat[name]&parent_id=$cat[parent_id]'>$cat[name]</a><br>";
						if(isset($cat['child'])){
							getListCategoriesForAdmin($cat['child'], 'category_update.php');
						}
					echo "</td>";
					if($i>4){
						echo "</tr>";
					}
				}
				echo "</tr>";
				echo "</table>";
			}else{//Выводим поля для измения категории
					$id=$_GET['category_id'];
					$parent_id=$_GET['parent_id'];
					$name=$_GET['name'];			
		?>
			<form id='category_form' name="category_form">
				<input type="hidden" name="category_id" id='category_id' value="<?php echo $id;?>">
				<p>Название: <input type="text" name="name" id='name' value="<?php echo $name;?>"></p>
				<p>Родительская категория:</p>
				<select name="parent_id" id='parent_id'>
					<option value='0'>Главная категория</option>
					<?php
						$queryCategories=mysql_query("SELECT * FROM category");
						while($mas=mysql_fetch_array($queryCategories)){
							if($mas[id]==$parent_id){
								echo "<option value='$mas[id]' selected>$mas[name]</option>";
							}else{
								echo "<option value='$mas[id]' >$mas[name]</option>";
							}	
						}
					?>
				</select>
				<p>Добавить типы характеристик(параметров), которые будут у товаров данной категории:</p>
				<button id='btnAddParametr' type="button">Добавить еще характеристику</button>
				<table id="table-parametrsCategory">
					<?php
					$queryTP=mysql_query("SELECT type_parametr.name as name,  type_parametr.id FROM type_parametr, type_parametr_category
					WHERE type_parametr_category.category_id='$id'
					AND type_parametr_category.type_parametr_id = type_parametr.id");
					while($masTP=mysql_fetch_array($queryTP)){						
				?>
					<tr class='tr-parametr'>
						<td>
							<button class='btnDelParametr' type="button" onclick="btnDelParametr(this)" data-idCategory='<?php echo $id;?>'>Удалить</button>
							<?php
							$query=mysql_query("SELECT * FROM type_parametr");
							$bool=false;
							while($mas=mysql_fetch_array($query)){
								if($mas[id]==$masTP[id]){
									echo "<input type='hidden' class='oldParametr' value='$mas[id]'>";
									$bool = true;
									break;
								}
							}
							if(!$bool){
								echo "<input type='hidden' class='oldParametr' value=''>";
							}
						?>	
						</td>
						<td>
							<select name="selectParametr" class='selectParametr' onchange="clearOfErrors(this,'Необходимо выбрать параметры');">
								<option value=''>Выберите тип параметра</option>
								<?php
									$query=mysql_query("SELECT * FROM type_parametr");
									while($mas=mysql_fetch_array($query)){
										if($mas[id]==$masTP[id]){
											echo "<option value='$mas[id]' selected >$mas[name]</option>";
										}else{
											echo "<option value='$mas[id]' >$mas[name]</option>";
										}
									}
								?>
							</select><br>
							<input type="checkbox" name='cbAddParametr' class='cbAddParametr' onchange="showAndHidenTextParametr(this)"> Добавить свой параметр
						</td>
						<td><input type="text" name="textParametr" class='textParametr' placeholder="Введите своё значение" oninput="clearOfErrors(this,'Необходимо заполнить все поля параметров')"></td>
					</tr>
					<?php
					}
				?>
				</table>
				<button type="submit" name="submit">Изменить данные</button>
				<button type="reset" name="reset" onclick="clearAllErrors();">Очистить</button>
				
			</form>
			
			<script src='scriptErrors.js'></script>
			<script src='scriptForCategory.js'></script>	
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
