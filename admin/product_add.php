<?php
include("settings/connectDB.php");
include_once("../settings/function.php");
session_start();
if(!isset($_SESSION['admin'])||($_SESSION['admin']!=1)){
	exit ('Вход на эту страницу доступен только администраторам!');
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="style.css">
	<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
</head>
<body id='product_add'>
<div id='divMessage'></div>    
	<header><?php include('include/header.php');?></header>
	<main>
		<section id="left-section">
			<?php include('include/main-left-section.php');?>
		</section><!--
     --><section id="content">
			<h1>Добавление товара</h1>
			<?php 
                if(!isset($_GET['category_id'])){//Показать таблицу категорий
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
                            if(isset($cat['child'])){
                                echo "<p class='cursorDefault'>$cat[name]</p>"; 
								getListCategoriesForAdmin($cat['child'],'product_add.php');
                            }else{
                                echo "<a href='product_add.php?category_id=$cat[id]&name=$cat[name]'>$cat[name]</a><br>";             
                            }
                        echo "</td>";
                        if($i>4){
                            echo "</tr>";
                        }
                    }
                    echo "</tr>";
                    echo "</table>";
                }else{ //Вывести форму с полями для заполнения данных
					$category_id=$_GET['category_id'];
                    $name=$_GET['name'];
					?>
                    <h2>Категория '<?php echo $name;?>'</h2>
					<form name='product_form' id='product_form' enctype="multipart/form-data">  
					<div id='mainParametrs'>
						<p>Название: <input type="text" name="name" id='name' size='39' oninput="clearOfErrors(this, 'Необходимо заполнить поле \'Название\'')"></p>
						<p>Цена: <input type="text" name="price" id='price' size='7'></p>
						<p>Описание:</p><textarea name="description" id="description" cols="50" rows="7" oninput="clearOfErrors(this, 'Необходимо заполнить поле \'Описание\'')"></textarea>
						<p>Количество товара в наличии: <input type="text" name="count" id='count' size='5'></p>
						<input type="file" name='imgFile' id='imgFile'><span id='showLoad'></span>
						<button type="submit" name="submit">Добавить в базу данных</button>
						<button type="reset" name="reset" onclick="clearAllErrors();">Очистить</button>
					</div><!--
				 --><div id='additionalParametrs'>
						<h1>Дополнительные параметры</h1>
						<table id="table-parametrs">
							<?php
							if($category_id!=''){
								$queryTP=mysql_query("SELECT type_parametr.name as name, type_parametr.id as id
								FROM type_parametr, type_parametr_category 
								WHERE type_parametr_category.category_id = '$category_id'
								AND type_parametr_category.type_parametr_id = type_parametr.id
								GROUP BY name");
								if(mysql_num_rows($queryTP)!=0){
									while($masTP=mysql_fetch_array($queryTP)){
							?>
							<tr class='tr-parametr'>
								<td><?php echo $masTP['name']?></td>
								<td>
									<select name='selectParametr' class='selectParametr' data-typeParametrId='<?php echo $masTP[id]?>' onchange="clearOfErrors(this,'Необходимо выбрать параметры');">
										<option value=''>Выберите значение</option>
										<?php
										$queryP=mysql_query("SELECT parametr.name as name, parametr.id as id
										FROM parametr
										WHERE parametr.type_parametr_id = '$masTP[id]' GROUP BY name");
										while($masP=mysql_fetch_array($queryP)){
											echo "<option value='$masP[id]'>$masP[name]</option>";
										}
										?>
									</select><br>
									<input type='checkbox' name='cbAddParametr' class='cbAddParametr' onchange='showAndHidenTextParametr(this)'>Свое значение
								</td>
								<td><input type='text' name='textParametr' class='textParametr' data-typeParametrId='<?php echo $masTP[id]?>' placeholder='Ввести своё значение' oninput="clearOfErrors(this,'Необходимо заполнить все поля параметров')"></td>
							</tr>
							<?php
							}
						}else{
							echo "<p>Нет дополнительных параметров для этой категории товаров</p>";
						}  
					}
					?>
						</table>
					</div>
					<input type='hidden' id='category_id' name='category_id' value='<?php echo $category_id;?>'>
					</form>
					<script src='scriptErrors.js'></script>
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
