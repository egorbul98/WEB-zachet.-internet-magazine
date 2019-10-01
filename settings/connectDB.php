<?php
$host="localhost";
$username="root";
$password="";
$nameDB="site";

$db=mysql_connect($host,$username,$password);
if($db){
    mysql_select_db($nameDB,$db);
    mysql_set_charset('utf8');
}else{
    echo "Ошибка при подключении к базе данных";
}

?>