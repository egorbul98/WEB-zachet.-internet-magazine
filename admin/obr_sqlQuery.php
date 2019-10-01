<?php
include("settings/connectDB.php");
include_once("../settings/function.php");

$sql = ($_POST['sqlQuery']);
//$sql = mysql_real_escape_string(htmlspecialchars($_POST['sqlQuery']));
$query = mysql_query($sql) or die(mysql_error());
//$query = mysql_query($sql) or die(mysql_error());
if(gettype($query)=='boolean'){
	echo 'Запрос прошел успешно';
}else {
	echo '<table>';
	echo "<tr>";
	$i=0;
	$countFields = mysql_num_fields($query);
	while($nameAtr = mysql_field_name($query, $i++)){
//		foreach($nameAtr as $value){
		echo "<td>$nameAtr</td>";
		if($i==$countFields){
			break;
		}
	}
	echo "</tr>";
	
	while($mas=mysql_fetch_assoc($query)){
		echo "<tr>";
		foreach($mas as $key=>$value){
			echo "<td>$value</td>";
		}
		echo "</tr>";
		
	}
	echo "</table>";
	
}
//echo $sql.'<br><br>';
//echo stripslashes($sql).'<br><br>';
//echo htmlspecialchars($sql).'<br><br>';
?>
