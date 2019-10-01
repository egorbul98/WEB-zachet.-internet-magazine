<?php
require("settings/connectDB.php");
require_once("../settings/function.php");


if(isset($_POST['body_name'])&&($_POST['body_name']=='del')){
	$clients_id = $_POST['client_id'];
	foreach($clients_id as $id){
		mysql_query("DELETE FROM client WHERE id='$id'") or die(mysql_error());
	}
	echo "Данные успешно удалены";
}else if(isset($_POST['searchClient'])){
	$searchClient = validValue($_POST['searchClient']);
	$query = mysql_query("SELECT * FROM client WHERE name LIKE '%$searchClient%' OR lname LIKE '%$searchClient%' OR email LIKE '%$searchClient%'") or die(mysql_error());
	if(mysql_num_rows($query)>0){
		while($mas=mysql_fetch_array($query)){
			echo "<div class='divClient' data-client_id='$mas[id]' data-client_name='$mas[name]' data-user_id='$mas[user_id]'><input type='checkbox' name='cbClientDel' class='cbClientDel' data-client_id='$mas[id]'><a href='#' class='aClientDel' > ID: $mas[id]. $mas[name] $mas[lname]. Телефон: $mas[tel]. E-mail: $mas[email]</a><button type='button' class='btnShowOrders' css='padding:0px;'>Показать заказы клиента</button></div>";
		}
	}else{
		echo "Поиск не дал результатов.";
	}
}





?>