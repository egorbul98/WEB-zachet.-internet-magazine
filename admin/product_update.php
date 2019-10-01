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

<body id='product_update'>
   <div id='divMessage'></div>
    <header><?php include('include/header.php');?></header>
    <main>
        <section id="left-section">
            <?php include('include/main-left-section.php');?>
        </section><!--
     --><section id="content">
            <h1>Изменение товара</h1>
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
								getListCategoriesForAdmin($cat['child'],'product_update.php');
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
                }else if(isset($_GET['category_id'])){
                    if(isset($_GET['product_id'])){//Если выбрали товар для измения, то Вывести форму с полями для изменения товара
						$product_id=$_GET['product_id'];
						$category_id=$_GET['category_id'];
						$category_name=$_GET['category_name'];		
						$queryPr=mysql_query("SELECT * FROM product WHERE id='$product_id'");
						$product = mysql_fetch_array($queryPr);
					?>
                        <h2><a href="product_update.php?category_id=<?php echo $category_id;?>&name=<?php echo $category_name;?>">Категория '<?php echo $category_name;?>'</a></h2>
                        <form name='product_form' id='product_form' >
                           
                            <div id='mainParametrs'>
                               	<input type="hidden" id='product_id' value='<?php echo $product['id'];?>'> 
                                <p>Название: <input type="text" name="name" id='name' size='39' oninput="clearOfErrors(this, 'Необходимо заполнить поле \'Название\'')" value='<?php echo $product['name'];?>'></p>
                                <p>Цена: <input type="text" name="price" id='price' size='7' value='<?php echo $product['price'];?>'></p>
                                <p>Описание:</p><textarea name="description" id="description" cols="50" rows="7" oninput="clearOfErrors(this, 'Необходимо заполнить поле \'Описание\'')"><?php echo $product['description'];?></textarea>
                                <p>Количество товара в наличии: <input type="text" name="count" id='count' size='5' value='<?php echo $product['count'];?>'></p>
                                <p>Текущее изображение товара:</p>
                                <img src="../image/<?php if($product['product_image']!=NULL){echo 'products/'.$product['product_image'];}else{ echo "productFoto.jpg";} ?>" alt="Фото товара" id='product_form-img'>
                                <input type="file" name='imgFile' id='imgFile'><span id='showLoad'></span>
                                <button type="submit" name="submit">Сохранить данные</button>
                                <button type="reset" name="reset" onclick="clearAllErrors();">Очистить</button>
                            </div><!--
                         --><div id='additionalParametrs'>
                                <h1>Дополнительные параметры</h1>
                                <table id="table-parametrs">
                                    <?php
                                        if($category_id!=''){
											//Выводим типы параметров, связанные с данной категорией товаров
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
													$strQuery = "SELECT * FROM product_parametr 
													WHERE product_id='$product_id'";
													$masPr_P = getArraySqlSelect($strQuery);
                                                    while($masP=mysql_fetch_array($queryP)){
														$bool=true;//Если будет найдет option с select, то значение этой переменной поменяется на false
														for($i = 0; $i<count($masPr_P); $i++){
															if($masP['id']==$masPr_P[$i]['parametr_id']){
																echo "<option value='$masP[id]' selected>$masP[name]</option>";
																$bool=false;
																break;
															}
														}	
														if($bool){
															echo "<option value='$masP[id]'>$masP[name]</option>";
														}
                                                    }
                                                    ?>
                                            </select><br>
                                            <?php
                                            $queryP=mysql_query("SELECT parametr.name as name, parametr.id as id
                                            FROM parametr
                                            WHERE parametr.type_parametr_id = '$masTP[id]' GROUP BY name");
                                            $strQuery = "SELECT * FROM product_parametr 
                                            WHERE product_id='$product_id'";
                                            $masPr_P = getArraySqlSelect($strQuery);
											$bool=true;//Если будет найдет option с select, то значение этой переменной поменяется на false
                                            while($masP=mysql_fetch_array($queryP)){
                                                for($i = 0; $i<count($masPr_P); $i++){
                                                    if($masP['id']==$masPr_P[$i]['parametr_id']){
                                                        echo "<input type='hidden' class='oldParametr' data-typeParametrId='$masTP[id]' value='$masP[id]'>";
                                                        $bool=false;
                                                        break;
                                                    }
                                                }
												
                                            }
											if($bool){
												echo "<input type='hidden' class='oldParametr' data-typeParametrId='$masTP[id]' value=''>";
											}
                                            ?>
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
                        <script type="text/javascript" src="scriptForProduct.js"></script>
                        
                   <?php
                    }else{//ВЫводим список товаров
                        $category_id=$_GET['category_id'];
                        $category_name=$_GET['name'];
                        echo "<h2>Категория '$category_name'</h2>";
						echo "<div id='divContent'>";
                        $queryP=mysql_query("SELECT * FROM product WHERE category_id='$category_id'");
                        while($masP=mysql_fetch_array($queryP)){
                            echo "<p><a href='product_update.php?product_id=$masP[id]&category_id=$category_id&category_name=$category_name'>Код товара: $masP[id]; Название: $masP[name]; Цена: $masP[price]; Количество на складе: $masP[count]</a></p>";
                        }
						echo "</div>";
                    }
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
