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
            <h1>Добавление скидки категории</h1>
			<div id='divContent'>
			
			<div id='list'>
			
				<?php
					$queryCategory = mysql_query("SELECT * FROM category");
					$categories = getCategoriesChilds();//0 - главные категории
					echo "<table id='table_list'>";
					echo "<tr><td colspan = 2><button type ='button' id='btnUpdateDiscount'>Сохранить</button></td></tr>";

					$i=0;
					echo "<tr>";
					while($masCat = mysql_fetch_array($queryCategory)){
						$i++;
						if($i>2){
							echo "<tr>";
							$i=1;
						}
						
						echo "<td>$masCat[name]</td>";
						echo "<td><select name='selectDiscount' data-category_id = '$masCat[id]' class='selectDiscount'>";
						echo "<option value='0'>Без скидки</option>";
						$queryDiscount=mysql_query("SELECT * FROM discount");
						$queryCategory_Discount=mysql_query("SELECT * FROM category_discount WHERE category_id='$masCat[id]'");
						if(mysql_num_rows($queryCategory_Discount)>0){
							$disc = mysql_fetch_array($queryCategory_Discount);	
						}else{
							$disc = null;
						}
						while($masDiscount = mysql_fetch_array($queryDiscount)){
							if(isset($disc)&&!empty($disc)&&$disc != null&&$disc['discount_id']==$masDiscount[id]){
								echo "<option value='$masDiscount[id]' selected>$masDiscount[discount]%</option>";
							}else{
								echo "<option value='$masDiscount[id]'>$masDiscount[discount]%</option>";
							}
							
						}
						echo "</select></td>";
						if($i>2){
							echo "</tr>";
						}
					}
					echo "</table>";
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
			var selectDiscount = $('.selectDiscount');
			var arrayDiscounts = {};
			var tempArray = {};
			for(var i = 0; i<selectDiscount.length; i++){
				tempArray = {
					'category_id':$(selectDiscount[i]).attr('data-category_id'),
					'discount':$(selectDiscount[i]).val(),
				}
				arrayDiscounts[i]=tempArray;
			}
			
			$.ajax({
				url: 'obr_discount.php',
				type: 'POST',
				data: {
					'arrayDiscounts':arrayDiscounts,
				},
				success: function(output) {
					showMsg($('#divMessage'),output)
				}
			});
			
			console.log(arrayDiscounts);
			
		});
	
	</script>
    
</body>
</html>