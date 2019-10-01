<?php
session_start();
include("settings/connectDB.php");
include_once("settings/function.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
</head>

<body id='catalog'>
   <div id='divMessage'></div>
   <script src='scriptFunction.js'></script>
    <header><?php include("include/header.php")?></header>
    <nav id='nav'>
        <?php include("include/navigationMenu.php")?>
    </nav>
    
    <main id='main'><!--
       --><section id="left-section">
            <?php include('include/main-left-section.php');?>
       </section><!--
       
    --><section id="catalog-content">
           <?php 
            if(isset($_POST['searh'])&&!empty($_POST['searh'])){
                echo "<h1>Результат поиска</h1>";
                $searh = validValue($_POST['searh']);
                $query = mysql_query("SELECT * FROM product WHERE name LIKE '%$searh%'") or die(mysql_error());
				?>
				<table id='table_product'>
				<tr>
					<?php
					$i=0;
					while($mas=mysql_fetch_array($query)){
					if($mas['product_image']==NULL){
						$pathImage = 'image/productFoto.jpg';
					}else{
						$pathImage = "image/products/$mas[product_image]";
					}
					$i++;
					if($i>4){
						echo "<tr>";
						$i=1;
					}
				?>
					<td>
						<div class="product">
							<div><a href="catalog.php?productId=<?php echo $mas['id'];?>"><img src="<?php echo $pathImage;?>" alt="Фото товара"></a></div>
							<h2><a href="catalog.php?productId=<?php echo $mas['id'];?>"><?php echo $mas['name'];?></a></h2>
							<span class="spanRedColor"><span class="spanBold"><?php echo $mas['price'];?></span> руб.</span>
							<button class="btnAddToCart" name="btnAddToCart" data-product_id='<?php echo $mas['id'];?>' data-product_count='<?php echo $mas['count'];?>'>Добавить в корзину</button>
						</div>
					</td>
					<?php
						if($i>4){
							echo "</tr>";
						}
					}
					while($i<4){
						echo "<td></td>";
						$i++;
					}
					?>
				</tr>
			</table>
				<?php
                while($mas=mysql_fetch_array($query)){
                    echo "<p><a href='catalog.php?productId=$mas[id]'>$mas[name]</a></p>";
                }
                
            }else{
                include('include/catalog-content.php');
            }
        ?>
       </section>
       </main>
    
     <footer id='footer'>
        <?php include('include/main-footer.php');?>
    </footer>
    
    <script src="scriptAnimation.js"></script>
    <?php include('include/popup_log.php');?>
    <script src="scriptMiniCart.js"></script>
   
</body>
</html>