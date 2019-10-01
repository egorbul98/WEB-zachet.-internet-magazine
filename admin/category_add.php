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
<body id='category_add'>
   <div id="divMessage"></div>
    <header><?php include('include/header.php');?></header>   
    <main>
       <section id="left-section">
            <?php include('include/main-left-section.php');?>
       </section><!--
    --><section id="content">
            <h1>Добавление категории</h1>
			<form id='category_form' name="category_form">
			<p>Название: <input type="text" name="name" id='name'></p>
			<p>Родительская категория:</p>
			<select name="parent_id" id='parent_id' required>
				<option value='0'>Главная категория</option>
				<?php
					$queryCategories=mysql_query("SELECT * FROM category");
					while($mas=mysql_fetch_array($queryCategories)){
						echo "<option value='$mas[id]' >$mas[name]</option>";
					}
				?>
			</select> 
			<p>Добавить типы характеристик(параметров), которые будут у товаров данной категории:</p>
			<button id='btnAddParametr' type="button">Добавить характеристику</button>
<!--			<button id='btnAddParametrBottom' type="button">Добавить характеристику вниз</button>-->
			<table id="table-parametrsCategory">
				<tr class='tr-parametr'>
				   <td>
					<button class='btnDelParametr' type="button" onclick="btnDelParametr(this)">Удалить</button>
				   </td>
					<td>
						<select name="selectParametr" class='selectParametr' onchange="clearOfErrors(this,'Необходимо выбрать параметры');">
							<option value=''>Выберите тип параметра</option>
							<?php
								$query=mysql_query("SELECT * FROM type_parametr");
								while($mas=mysql_fetch_array($query)){
									echo "<option value='$mas[id]' >$mas[name]</option>";
								}
							?>
						</select><br>
						<input type="checkbox" name='cbAddParametr' class='cbAddParametr' onchange="showAndHidenTextParametr(this)"> Добавить свой параметр
					</td>
					<td><input type="text" name="textParametr" class='textParametr' placeholder="Введите своё значение" oninput="clearOfErrors(this,'Необходимо заполнить все поля параметров')"></td>
				</tr>
			</table>

			<button type="submit" name="submit">Добавить в базу данных</button>
			<button type="reset" name="reset" onclick="clearAllErrors();">Очистить</button>
			</form>
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