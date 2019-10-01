<?php
require("settings/connectDB.php");
//var_dump($_FILES['image']['type']);
echo $_FILES['image']['type'];
$types = array('image/gif', 'image/png', 'image/jpeg');
$size = 1024000;
//$path = 'image/products/'.basename();
$path = 'image/products/';
$nameImg = $_FILES['image']['name'];
if (!in_array($_FILES['image']['type'], $types)){
	die('Запрещённый тип файла. <a href="?">Попробовать другой файл?</a>');
}
if ($_FILES['image']['size'] > $size){
	die('Слишком большой размер файла. <a href="?">Попробовать другой файл?</a>');
}
// Загрузка файла и вывод сообщения
 if (!@copy($_FILES['image']['tmp_name'], $path . $nameImg))
 echo 'Что-то пошло не так';
 else
// echo 'Загрузка удачна <a href="' . $path . $_FILES['image']['name'] . '">Посмотреть</a> ' ;'
	 mysql_query("INSERT INTO images (name, text) VALUES ('$nameImg', 'ewfewfewfewf')") or die(mysql_error());
echo 'Загрузка удачна' ;

 





// Проверяем пришел ли файл
//if( !empty( $_FILES['image']['name'] ) ) {
//  // Проверяем, что при загрузке не произошло ошибок
//  if ( $_FILES['image']['error'] == 0 ) {
//    // Если файл загружен успешно, то проверяем - графический ли он
//    if( substr($_FILES['image']['type'], 0, 5)=='image' ) {
//      // Читаем содержимое файла
//      $image = file_get_contents( $_FILES['image']['tmp_name'] );
//      // Экранируем специальные символы в содержимом файла
//      $image = mysql_escape_string( $image );
//      // Формируем запрос на добавление файла в базу данных
//      $query=mysql_query("INSERT INTO `tempImg` (`val`) VALUES('$image')");
//      // После чего остается только выполнить данный запрос к базе данных
//    }
//  }
//
//
//}
?>