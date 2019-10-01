<?php
include_once('settings/function.php');
include_once('settings/connectDB.php');

if(!isset($_GET['productId'])){
    $id=0;
    if(isset($_GET['id'])&&$_GET['id']!=''){ //Если не передан id категории, то по умолчанию будет вывод вех категорий
        $id=$_GET['id'];
    }
    $CategoriesChilds=getCategoriesChilds($id);
    echo "<table id='table_listCategories'>";
    if(isset($_GET['name'])){
        $name = $_GET['name'];
        echo "<th colspan='3'>$name</th>";
    }else{
        echo "<th colspan='3'>Каталог</th>";
    }
    echo "<tr>";
            $i=0;
            foreach($CategoriesChilds as $cat){
            $i++;
            if($i>3){
                echo "<tr>";
                $i=1;
            }
            echo "<td>";
                echo "<a href='catalog.php?id=$cat[id]&name=$cat[name]'>$cat[name]</a><br>";
                if(isset($cat['child'])){
                    getListCategories($cat['child']);              
                }
            echo "</td>";
            if($i>3){
                echo "</tr>";
            }
        }
    echo "</tr>";
    echo "</table>";
    
    
//    rec($CategoriesChilds);
    $query=mysql_query("SELECT * FROM product WHERE category_id='$id'");    
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
                <div class="product"><!---------------------------------------СПИСОК ТОВАРОВ -->
                    <div><a href="catalog.php?productId=<?php echo $mas['id'];?>&category_id=<?php echo $id;?>"><img src="<?php echo $pathImage;?>" alt="Фото товара"></a></div>
                    <h2><a href="catalog.php?productId=<?php echo $mas['id'];?>&category_id=<?php echo $id;?>"><?php echo $mas['name'];?></a></h2>
                    <?php
        				
						$queryDiscount=mysql_query("SELECT discount.* FROM discount 
						INNER JOIN product_discount on product_discount.discount_id = discount.id 
						WHERE product_discount.product_id='$mas[id]'");
						$queryDiscountCategory=mysql_query("SELECT discount.* FROM discount 
						INNER JOIN category_discount on category_discount.discount_id = discount.id 
						WHERE category_discount.category_id='$id'");
						if(mysql_num_rows($queryDiscount)>0){
							$discount = mysql_fetch_array($queryDiscount);
							$price = ($mas['price'] - ($mas['price']/100)*$discount['discount']);
                            $price = (ceil((int)$price/10))*10;
							echo "<span class='spanOldPriceThrough'><span class='spanOldPrice'>$mas[price]</span><span>руб.</span></span><span class='discount'>-$discount[discount]%</span>";
						}else{
							if(mysql_num_rows($queryDiscountCategory)>0){
								$discount = mysql_fetch_array($queryDiscountCategory);
								$price = ($mas['price'] - ($mas['price']/100)*$discount['discount']);
								echo "<span class='spanOldPriceThrough'><span class='spanOldPrice'>$mas[price]</span><span>руб.</span></span><span class='discount'>-$discount[discount]%</span>";
                                $price = (ceil((int)$price/10))*10;
							}else{
								$price = $mas['price'];
							}
//                            $number = 7056;
//                            $result = ceil((int)$price/100)*100;
							
						}
					?>
                    <span class="spanRedColor"><span class="spanBold"><?php echo $price;?></span> руб.</span>
                    <button class="btnAddToCart" name="btnAddToCart" data-product_id='<?php echo $mas['id'];?>' data-product_count='<?php echo $mas['count'];?>'>Добавить в корзину </button>
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

}else if(isset($_GET['productId'])&&$_GET['productId']!=''){ //выводим данные одного товара
    $productId=$_GET['productId'];
    $queryProduct=mysql_query("SELECT * FROM product WHERE id='$productId'");
    if(mysql_num_rows($queryProduct)!=0){
        $product=mysql_fetch_array($queryProduct);
		if($product['product_image']==NULL){
			$pathImage = 'image/productFoto.jpg';
		}else{
			$pathImage = "image/products/$product[product_image]";
		}
		
		
		
?>
        <div id="product-content"> <!---------------------------------------ТОВАР -->
            <div>
                <section id="product-image">
                    <img src="<?php echo $pathImage;?>" alt="Фото товара" id='product-info-img'>
                </section><!--
             --><section id="product-info">
                    <div id="product-info-buy">
                        <div id="head">
                            <h1><?php echo $product['name'];?></h1>
                            <p><a href="catalog.php?id=<?php echo $product['category_id'];?>">[Перейти в каталог этих товаров]</a></p>
                        </div>
                        <div id='bottom'>
                        	<?php
								$id=$_GET['category_id'];
								$queryDiscount=mysql_query("SELECT discount.* FROM discount 
								INNER JOIN product_discount on product_discount.discount_id = discount.id 
								WHERE product_discount.product_id='$product[id]'");
								$queryDiscountCategory=mysql_query("SELECT discount.* FROM discount 
								INNER JOIN category_discount on category_discount.discount_id = discount.id 
								WHERE category_discount.category_id='$id'");
								if(mysql_num_rows($queryDiscount)>0){
									$discount = mysql_fetch_array($queryDiscount);
									$price = ($product['price'] - ($product['price']/100)*$discount['discount']);
									$price = (ceil((int)$price/10))*10;
									echo "<div id='divOldPrice'><span id='spanOldPriceThrough'><span id='spanOldPrice'>$product[price]</span><span>руб.</span></span><span id='discount'>-$discount[discount]%</span></div>";
								}else{
									if(mysql_num_rows($queryDiscountCategory)>0){
										$discount = mysql_fetch_array($queryDiscountCategory);
										$price = ($product['price'] - ($product['price']/100)*$discount['discount']);
                                        $price = (ceil((int)$price/10))*10;
										echo "<div id='divOldPrice'><span id='spanOldPriceThrough'><span id='spanOldPrice'>$product[price]</span><span>руб.</span></span><span id='discount'>-$discount[discount]%</span></div>";
                                        
									}else{
										$price = $product['price'];
									}
									
									
								}
							?>
                        	
                        	<div id='main-info'>
                        		<span class="spanRedColor"><sup>ЦЕНА</sup> <span id="spanPrice" class="spanBold"><?php echo $price;?></span> руб.</span>
								<button class="btnAddToCart" name='btnAddToCart' data-product_id='<?php echo $product['id'];?>' data-product_count='<?php echo $product['count'];?>'>Добавить в корзину</button><br>
		<!--                        <button id="btnBuy">Купить в один клик</button>-->
								<p>Код товара: <span class="spanBold"><?php echo $product['id'];?></span></p>
								<p>Количество на складе: <span class="spanBold"><?php echo $product['count'];?></span></p>
                        	</div>
                        </div>
                    </div>
                </section>
            </div>
            <div id="product-info-description">
                <p><?php echo $product['description'];?> руб.</p>
            </div>
            <div id='blockParametrs'>
                <h2>Характеристики</h2>
                <table id='table-parametrs'>
                    <?php
                $queryParametr=mysql_query("SELECT parametr.name as Pname, type_parametr.name as TPname 
                FROM product_parametr, parametr, type_parametr 
                WHERE product_parametr.product_id = '$product[id]' 
                AND parametr.id = product_parametr.parametr_id 
                AND parametr.type_parametr_id = type_parametr.id");
                if(mysql_num_rows($queryParametr)!=0){
                    while($parametr=mysql_fetch_array($queryParametr)){
                        echo "<tr>";
                        echo "<td class='parametrType'>$parametr[TPname]:</td>";
                        echo "<td class='parametrValue'>$parametr[Pname]</td>";
                        echo "</tr>";
                    }
                }
            ?>
                </table>
            </div>
        </div>
<?php       
    }
}
?>
<script src="scriptFunction.js"></script>
<script>
	$(document).on('click','.btnAddToCart', function(){
		var id = $(this).attr('data-product_id');
		var count = $(this).attr('data-product_count');
		if(cart[id]>=count||count==0){
			showMsg('#divMessage','На складе больше нет товаров');
		}else{
			if(cart[id]==undefined){
				cart[id]=1;
			}else{
				cart[id]++;
			}
			console.log(cart);
			localStorage.setItem('cart',JSON.stringify(cart));
			showMiniCart();
		}
		
	});
	
	
	
</script>

