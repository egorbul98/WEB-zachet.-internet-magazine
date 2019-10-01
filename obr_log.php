<?php
include("settings/connectDB.php");
include_once("settings/function.php");

session_start();

if(isset($_POST['pol'])){
    echo "----нач-----";
    if(isset($_POST['update'])){
        $name = validValue($_POST['name']);
        $lname = validValue($_POST['lname']);
        $login = validValue($_POST['login']);
        $email = validValue($_POST['email']);
        $passwordNew = validValue($_POST['passwordNew']);
        $passwordOld = validValue($_POST['passwordOld']);
        $user_id = $_POST['user_id'];
        
        if($passwordNew!=''&&$passwordOld!=''){
            mysql_query("UPDATE user SET name, lname, login, email, pol VALUES('$name','$lname','$login','$email','$pol') WHERE id= '$user_id'");
            echo "Успешно изменено";
        }else{
            $queryUser = mysql_query("SELECT * FROM user WHERE login = '$login' AND password = '$passwordOld'");
            if(mysql_num_rows($queryUser)>0){
                 mysql_query("UPDATE user SET name, lname, login, password, email,  pol  VALUES('$name','$lname','$login','$passwordNew','$email','$pol') WHERE id= '$user_id'");
                echo "Успешно изменено";
            }else{
                echo "Неверный пароль";
            }
        }
        
    }else{
    $name = validValue($_POST['name']);
    $lname = validValue($_POST['lname']);
    $login = validValue($_POST['login']);
    $email = validValue($_POST['email']);
    $password = validValue($_POST['password']);
//    $password = crypt($password, PASSWORD_DEFAULT);
//    $password = crypt($password);
    $pol = validValue($_POST['pol']);
    if(!empty($name)&&!empty($lname)&&!empty($login)&&!empty($email)&&!empty($password)){
        $email_valiate = filter_var($email, FILTER_VALIDATE_EMAIL);
        if(checkLengthValue($name, 2, 25) && checkLengthValue($lname, 2, 25) && checkLengthValue($login, 2, 25) && checkLengthValue($password, 2, 100) && $email_valiate){
            $query = mysql_query("SELECT * FROM user WHERE login = '$login'");
            if(mysql_num_rows($query)>0){
                echo 'Пользователь с таким логином уже существует';
            }else{
                mysql_query("INSERT INTO user (name, lname, login, password, email, pol, admin) VALUES('$name','$lname','$login','$password','$email','$pol', '0')");
				$id= mysql_insert_id();
				mysql_query("UPDATE client SET user_id = '$id' WHERE email='$email'") or die(mysql_error());
                echo 'Регистрация прошла успешно';
            }
        }else{
            echo 'Неккоректно введены данные. Либо слишком много символов в полях.';
        }
    }
    }
}else if(isset($_GET['log'])&&$_GET['log']=='logout'){
	unset($_SESSION['user_name']);
	unset($_SESSION['admin']);
	unset($_SESSION['user_id']);
	echo 'Обновить';
}else{
    $login = validValue($_POST['login']);
    $password = validValue($_POST['password']);
    if(!empty($login)&&!empty($password)){
        if(checkLengthValue($login, 2, 25) && checkLengthValue($password, 2, 16)){
            $query = mysql_query("SELECT * FROM user WHERE login = '$login'");
            if(mysql_num_rows($query)>0){
				$mas = mysql_fetch_array($query);
//                $mas['password']==$password
//				if(password_verify($password,$mas['password']))
                if($mas['password']==$password){
					echo 'Вы авторизовались';
					$_SESSION['user_name']=$mas['name'];
					$_SESSION['admin']=$mas['admin'];
					$_SESSION['user_id']=$mas['id'];
				}else{
					echo 'Неверный логин или пароль';
				}
            }else{
                echo 'Пользователя с таким логином не существует';
            }
        }
	}
}
?>
