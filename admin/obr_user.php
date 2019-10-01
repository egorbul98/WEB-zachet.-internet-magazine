<?php
include("settings/connectDB.php");
include_once("../settings/function.php");

if(isset($_GET['body_name'])){
	if($_GET['body_name']=='add'){
		$name = validValue($_POST['name']);
		$lname = validValue($_POST['lname']);
		$login = validValue($_POST['login']);
		$email = validValue($_POST['email']);
		$password = validValue($_POST['password']);
		$pol = $_POST['pol'];
		$admin = $_POST['admin'];
		
		$email_valiate = filter_var($email, FILTER_VALIDATE_EMAIL);
		if(checkLengthValue($name, 2, 25) && checkLengthValue($lname, 2, 25) && checkLengthValue($login, 2, 25) && checkLengthValue($password, 2, 16) && $email_valiate){
            $query = mysql_query("SELECT * FROM user WHERE login = '$login'");
            if(mysql_num_rows($query)>0){
                echo 'Пользователь с таким логином уже существует';
            }else{
                mysql_query("INSERT INTO user (name, lname, login, password, email, pol, admin) VALUES('$name','$lname','$login','$password','$email','$pol', '$admin')") or die(mysql_error());
                echo 'Регистрация прошла успешно';
            }
        }else{
            echo 'Неккоректно введены данные. Либо слишком много символов в полях.';
        }
	}else if($_GET['body_name']=='del'){
		if(!empty($_GET['users_id'])){
			$users_id = $_GET['users_id'];
			foreach($users_id as $id){
				mysql_query("DELETE FROM user WHERE id='$id'");
			}
			echo "Данные успешно удалены";
		}else{
			echo "Произошла ошибка при удалении";
		}
		
	}
}else if(isset($_GET['searchUser'])){
	$strSearchUser = validValue($_GET['searchUser']);
	$query = mysql_query("SELECT * FROM user WHERE name LIKE '%$strSearchUser%' OR lname LIKE '%$strSearchUser%' OR email LIKE '%$strSearchUser%' OR login LIKE '%$strSearchUser%'") or die(mysql_error());
	if(mysql_num_rows($query)>0){
		while($mas=mysql_fetch_array($query)){
			$admin='';
			if($mas['admin']=='1'){
				$admin='Администратор';
			}
			echo "<div class='divUser' data-user_id='$mas[id]' data-user_name='$mas[name]' data-user_admin='$mas[admin]'><input type='checkbox' name='cbUserDel' data-user_id='$mas[id]' class='cbUserDel'><a href='#' class='aUserDel' > ID: $mas[id]. $admin $mas[name] $mas[lname]. Логин: $mas[login]. E-mail: $mas[email]</a><br><button type='button' class='btnUserAdmin'>Сделать администратором / Удалить из администраторов</button></div>";
		}
	}else{
		echo "Поиск не дал результатов.";
	}
	
}else if(isset($_GET['user_admin'])){
	$id = $_GET['user_id'];
	$admin = $_GET['user_admin'];
	mysql_query("UPDATE user SET admin = '$admin' WHERE id='$id'") or die(mysql_error());
	$query = mysql_query("SELECT * FROM user WHERE id='$id'");
	$mas=mysql_fetch_array($query);
	$admin='';
	if($mas['admin']=='1'){
		$admin='Администратор';
	}
	echo "<div class='divUser' data-user_id='$mas[id]' data-user_name='$mas[name]' data-user_admin='$mas[admin]'><input type='checkbox' name='cbUserDel' class='cbUserDel'><a href='#' class='aUserDel' > ID: $mas[id]. $admin $mas[name] $mas[lname]. Логин: $mas[login]. E-mail: $mas[email]</a><br><button type='button' class='btnUserAdmin'>Сделать администратором / Удалить из администраторов</button></div>";
	
}

?>