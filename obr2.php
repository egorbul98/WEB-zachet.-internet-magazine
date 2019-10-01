<?php 
if ( isset( $_GET['id'] ) ) {
  // Здесь $id номер изображения
  $id = $_GET['id'];
  if ( $id > 0 ) {
    $query = mysql_query("SELECT * FROM `tempImg` WHERE `id`=1");
    // Выполняем запрос и получаем файл
    if ( mysql_num_rows( $query ) == 1 ) {
      $image = mysql_fetch_array($query);
      // Отсылаем браузеру заголовок, сообщающий о том, что сейчас будет передаваться файл изображения
     	header("Content-type: image/*");
      // И  передаем сам файл
      	echo $image['val'];
      // И  передаем сам файл
	}
    // Выполняем запрос и получаем файл
    
  }
}

