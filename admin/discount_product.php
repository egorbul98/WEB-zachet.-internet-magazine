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
    <style>
		#list li select{
			padding: 0;
		}
		#list .discountProduct{
			width: 150px;
		}
		#list #table_list{
			border-spacing: 10px;
		}
		#btnUpdateDiscount{
			margin: auto;
		}
	</style>
</head>
<body id='category_add'>
   <div id="divMessage"></div>
    <header><?php include('include/header.php');?></header>   
    <main>
       <section id="left-section">
            <?php include('include/main-left-section.php');?>
       </section><!--
    --><section id="content">
            <h1>Добавление скидки товару</h1>
			<div id='divContent'>
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
								getListCategoriesForAdmin($cat['child'],'discount_product.php');
							}else{
								echo "<a href='discount_product.php?category_id=$cat[id]&name=$cat[name]'>$cat[name]</a><br>";             
							}
						echo "</td>";
						if($i>4){
							echo "</tr>";
						}
					}
					echo "</tr>";
					echo "</table>";
                }else{
				?>
				
					<div id='header'>
						<div id='search'>
							<input type="search" id='searchProduct'>
							<button type="button" id='btnSearchProduct'>Поиск</button>
							<a href="discount_product.php?category_id=<?php echo $_GET['category_id']?>">Обновить список</a>
						</div>
						<span id='spanCBSelectAll'><input type="checkbox" name='cbSelectAll' id='cbSelectAll'> Выделить все</span>
						<button type="button" id='btnUpdateDiscount'>Изменить скидку у выбранных товаров на: </button>
						<select name="selectDiscountProduct" id="selectDiscountProduct" data-category_id='<?php echo $_GET['category_id'];?>'>
						<option value="0">Без скидки</option>
							<?php
								$queryDiscount=mysql_query("SELECT * FROM discount");
								while($mas = mysql_fetch_array($queryDiscount)){
									echo "<option value='$mas[id]'>$mas[discount]%</option>";
								}
							?>

						</select>
						
					</div>
					<div id='list'>
				<?php
					$queryProduct = mysql_query("SELECT * FROM product WHERE category_id = '$_GET[category_id]'");
					echo "<table id='table_list'>";
					$i=0;
					echo "<tr>";
					while($masProd = mysql_fetch_array($queryProduct)){
						$i++;
						if($i>2){
							echo "<tr>";
							$i=1;
						}
						echo "<td><input type='checkbox' class='cbProduct' data-product_id='$masProd[id]'> $masProd[name]</td>";
						
						$queryDiscount=mysql_query("SELECT discount.* FROM discount 
						INNER JOIN product_discount on product_discount.discount_id = discount.id 
						WHERE product_discount.product_id='$masProd[id]'");
						if(mysql_num_rows($queryDiscount)>0){
							$masD = mysql_fetch_array($queryDiscount);	
							echo "<td><input type='text' class='discountProduct' readonly data-product_id = '$masProd[id]' value='$masD[discount]%'></td>";
						}else{
							echo "<td><input type='text' class='discountProduct' readonly data-product_id = '$masProd[id]' value='Без скидки'></td>";
						}
						
						if($i>2){
							echo "</tr>";
						}
					}
					echo "</table>";
				}
				?>
				
				</div>
			</div>
     
      
	 </section>
       
    </main>
    
	
    <?php include('include/popup.php');?>
    <?php include('include/popup_parametr_add.php');?>
    <?php include('include/popup_discount_add.php');?>
    <?php include('include/popup_log.php');?>
    <script src="scriptFunction.js"></script>
    <script>
		
		$(document).on('click', '#btnUpdateDiscount', function(){
			var discount = $('#selectDiscountProduct').val();
			var category_id = $('#selectDiscountProduct').attr('data-category_id');
			var cb = $('.cbProduct');
			var arrayCB = Array();
			for (var i = 0; i < cb.length; i++) {
				if ($(cb[i]).prop('checked') == true) {
					arrayCB.push($(cb[i]).attr('data-product_id'));
				}
			}
			if(arrayCB.length==0){
				showMsg($('#divMessage'), 'Необходимо выбрать элементы для изменения скидки');
			}else{
				var conf = confirm("Вы точно хотите изменить скидку у товаров?");
				if(conf){
					$.ajax({
						url: 'obr_discount.php',
						type: 'POST',
						data: {
							'discount':discount,
							'products_id':arrayCB,
							'category_id':category_id
						},
						success: function(output) {
							$('#divContent #list').html(output);
						},
						beforeSend:function(){
							$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
						}
					});
				}
			}
		});
		
		
		
		$('#btnSearchProduct').click(function() {
			var search = $('#searchProduct').val();
			$.ajax({
				url: 'obr_discount.php',
				type: 'POST',
				data: {
					'search': search,
				},
				success: function(output) {
					$('#divContent #list').html(output);
				},
				beforeSend:function(){
					$('#divContent #list').html('<img src="../image/load.gif" alt="load">');
				}
			});	
			
		});
		
		$('#cbSelectAll').change(function() {
			if ($(this).prop('checked') == true) {
				$('.cbProduct').prop('checked', true);
			} else {
				$('.cbProduct').prop('checked', false);
			}
		});
		
		
	
	</script>
    
</body>
</html>
