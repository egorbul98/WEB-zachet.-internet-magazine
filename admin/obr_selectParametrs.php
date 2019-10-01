<?php
include("settings/connectDB.php");
include_once("../settings/function.php");

if(isset($_GET['body_name']) && isset($_GET['selectTypeParametr']) && $_GET['selectTypeParametr'] == true){
	$body_name=$_GET['body_name'];
	echo "<td>";
	echo "<button class='btnDelParametr' type='button' onclick='btnDelParametr(this)'>Удалить</button>";
	if($body_name=='category_update'){
		echo "<input type='hidden' class='oldParametr' value=''>";
	}
	echo "</td>";
	echo "<td>";
	echo "<select name='selectParametr' class='selectParametr' onchange='clearOfErrors(this, \"Необходимо выбрать параметры\");'><option value=''>Выберите тип параметра</option>";
	$query=mysql_query("SELECT * FROM type_parametr");
	while($mas=mysql_fetch_array($query)){
		echo "<option value='$mas[id]' >$mas[name]</option>";
	}
	echo "</select><br>";
	echo "<input type='checkbox' name='cbAddParametr' class='cbAddParametr' onchange='showAndHidenTextParametr(this)'> Добавить свой параметр";
	echo "</td>";
	echo "<td><input type='text' name='textParametr' class='textParametr' placeholder='Введите своё значение' oninput='clearOfErrors(this, \"Необходимо заполнить все поля параметров\")'></td>";

//    $id=$_GET['id'];
//    if($id!=''){
//        $queryTP=mysql_query("SELECT type_parametr.name as name, type_parametr.id as id
//        FROM type_parametr, type_parametr_category 
//        WHERE type_parametr_category.category_id = '$id'
//        AND type_parametr_category.type_parametr_id = type_parametr.id
//        GROUP BY name");
//        if(mysql_num_rows($queryTP)!=0){
//            while($masTP=mysql_fetch_array($queryTP)){
//            echo "<tr>";
//            echo "<td>".$masTP['name']."</td>";
//            echo "<td><select name='select$masTP[id]' id='$masTP[id]' class='selectParametr'>";
//            $queryP=mysql_query("SELECT parametr.name as name, parametr.id as id
//            FROM parametr, parametr_category
//            WHERE parametr.type_parametr_id = '$masTP[id]'
//            AND parametr_category.category_id = '$id'
//            GROUP BY name");
//            while($masP=mysql_fetch_array($queryP)){
//                echo "<option value='$masP[id]'>$masP[name]</option>";
//            }
//            echo "</select></td>";
//            echo "<td><input type='text' name='inputAccessory' placeholder='Ввести своё значение'></td>";
//            echo "</tr>";
//            }
//        }else{
//            echo "Нет дополнительных параметров для этой категории товаров";
//        }  
}
?>
