<?php


function addFilterCondition($where, $add) //Для фильтра товаров
{
    if ($where) {
        $where .= " OR $add";
    } else $where = $add;
    return $where;
}


function validValue($value = "") {
    $value = trim($value);//удаляем пробелы
    $value = stripslashes($value);//удаляем экранированные символы
    $value = strip_tags($value);//удаляем HTML и PHP теги
//    $value = htmlspecialchars($value);//преобразуем специальные символы в HTML-сущности ('&' преобразуется в '&amp;' и т.д.)
    $value = mysql_real_escape_string($value);//преобразует символы с особым значением в SQL-запросах MySQL в экранированные символы
    return $value;
}

function checkLengthValue($value = "", $min, $max) { //Проверка длины строки
    $result = (strlen($value) < $min || strlen($value) > $max);
    return !$result;
}


function getCategoriesChildsId($idCategory=0, &$result=array()){
    include("settings/connectDB.php");
    $query=mysql_query("SELECT * FROM category WHERE parent_id='$idCategory'");
    while($mas=mysql_fetch_array($query)){
        $result[]=$mas['id'];
		getCategoriesChildsId($mas['id'],&$result);
    }
    return $result;
}

function getListCategoriesForAdmin($categories, $url){//Получение списка подкатегорий, причем категории, находящиеся на последнем уровне вложенности, являются ссылками на страницу добавления товара
	include_once("settings/connectDB.php");
    foreach($categories as $cat){       
        echo "<ul>";
            if(isset($cat['child'])){
				if($url=="product_add.php"||$url=="product_del.php"||$url=="discount_product.php"){
					echo "<li class='cursorDefault'>$cat[name]";
				}else if($url=="product_update.php"){
                    echo "<li class='cursorDefault'>$cat[name]";
//					<a href='$url?category_id=$cat[id]&name=$cat[name]&parent_id=$cat[parent_id]'>$cat[name]</a>";
                }else if($url=="category_update.php"){
					echo "<li><a href='$url?category_id=$cat[id]&name=$cat[name]&parent_id=$cat[parent_id]'>$cat[name]</a>";
				}else if($url=="category_del.php"){
					echo "<li><a data-categoryId='$cat[id]' onclick='delCategory(this)'> <i class='fa fa-times 2px'></i> </a>$cat[name]";
				}else if($url=="category_discount.php"){
					
					$queryDiscount=mysql_query("SELECT * FROM discount");
					echo "<li data-categoryId='$cat[id]' class='cursorDefault'> $cat[name] ";
					echo "<select name='selectDiscount' class='selectDiscount'>";
					echo "<option value='0'>Без скидки</option>";
					while($mas = mysql_fetch_array($queryDiscount)){
						echo "<option value='$mas[id]'>$mas[discount]%</option>";
					}
					echo "</select>";
					echo "</li>";
				}
                getListCategoriesForAdmin($cat['child'], $url);
                echo "</li>";
            }else{
				if($url=="category_del.php"){
					echo "<li><a data-categoryId='$cat[id]' onclick='delCategory(this)'> <i class='fa fa-times 2px'></i> </a>$cat[name]</li>";
				}else if($url=="category_discount.php"){
					$queryDiscount=mysql_query("SELECT * FROM discount");
					echo "<li data-categoryId='$cat[id]' class='cursorDefault'> $cat[name] ";
					echo "<select name='selectDiscount' class='selectDiscount'>";
					echo "<option value='0'>Без скидки</option>";
					while($mas = mysql_fetch_array($queryDiscount)){
						echo "<option value='$mas[id]'>$mas[discount]%</option>";
					}
					echo "</select>";
					echo "</li>";
					
				}else{
                	echo "<li><a href='$url?category_id=$cat[id]&name=$cat[name]&parent_id=$cat[parent_id]'>$cat[name]</a></li>";
				}
            }
        echo "</ul>";
    }     
}

//Функция получения категории с ее дочерними категориями ввиде массива для каталога товаров 
function getCategoriesChilds($idCategory=0){
    $result=array();
    include("settings/connectDB.php");
    $query=mysql_query("SELECT * FROM category WHERE parent_id='$idCategory'");
    while($mas=mysql_fetch_array($query)){
//        $result[$mas['id']]=array(
//            'id'=>$mas['id'],
//            'parent_id'=>$mas['parent_id'],
//            'name'=>$mas['name'],
//        );
        $result[$mas['id']]['id']=$mas['id'];
        $result[$mas['id']]['parent_id']=$mas['parent_id'];
        $result[$mas['id']]['name']=$mas['name'];
        $childs= getCategoriesChilds($mas['id']);
        if(!empty($childs)){
            $result[$mas['id']]['child']=$childs;
        }
    }
    return $result;
}

function getListCategories($categories){//Функция вывода подкатегорий ввиде списка ссылок в случае, если была нажата какая-либо главная категория
        foreach($categories as $cat){       
            echo "<ul>";
                echo "<li><a href='catalog.php?id=$cat[id]&name=$cat[name]'>$cat[name]</a>";
                if(isset($cat['child'])){
                    getListCategories($cat['child']);
                    echo "</li>";
                }else{
                    echo "</li>";
                }
            echo "</ul>";
        }     
}

//Функция вывода категорий каталога для навигационной меню
function vivodMenu($arr){ 
    echo "<ul>";
    foreach($arr as $a){   
        echo "<li><a href='catalog.php?id=$a[id]&name=$a[name]'>$a[name]</a>";
        if(isset($a['childs'])){
            vivodMenu($a['childs']);
        }
        echo "</li>";
    }
    echo "</ul>";
}

function vivodCategoriesForAdmin($arr){
    echo "<ul>";
    foreach($arr as $a){   
        echo "<li><a href='category_update.php?id=$a[id]'>$a[name]</a>";
        if(isset($a['childs'])){
            vivodCategoriesForAdmin($a['childs']);
        }
        echo "</li>";
    }
    echo "</ul>";
}
function vivodCategoriesForAdminCheckBox($arr){
    echo "<ul>";
    foreach($arr as $a){   
        echo "<li><input type='checkbox' class='checkbox' name='cbID[]' oninput='funOnInput()' value='$a[id]'>$a[name]";
        if(isset($a['childs'])){
            vivodCategoriesForAdminCheckBox($a['childs']);
        }
        echo "</li>";
    }
    echo "</ul>";
}
function printArr($arr){
    echo "<pre>".print_r($arr,true)."</pre>";
}

function getArrayOfCategories(){
    include("settings/connectDB.php");
    $query=mysql_query("SELECT * FROM category");
    $tree=array();
    while($mas=mysql_fetch_array($query)){
        $tree[$mas['id']]=$mas;
    }
    return $tree;
}

function getArraySqlSelect($str){
    include("settings/connectDB.php");
    $query=mysql_query($str);
    $tree=array();
    while($mas=mysql_fetch_array($query)){
        $tree[]=$mas;
    }
    return $tree;
}



function transformTreeOfCategories(&$tree, $arr){
    if($tree==NULL){
        foreach($arr as $id=>&$a){
            if($a['parent_id']==0){
                $tree[$id]=&$a;
            }
        }   
    }
    foreach($tree as $t){
       foreach($arr as $id=>&$a){
            if($t['id']==$a['parent_id']){
                $tree[$a['parent_id']]['childs'][$id]=&$a;
            }
        } 
    }
    foreach($tree as $t){
        if(isset($t['childs'])){
            transformTreeOfCategories($t['childs'],$arr);
        }
    }       
}


?>