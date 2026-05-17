<?php
include '../conf.php';  


					
							
$query = mysql_query("SELECT * FROM schet");
while($row = mysql_fetch_array($query)) {
	$query2 = mysql_query("SELECT * FROM tarif WHERE `id` = '".$row['prod']."'");
	while($row2 = mysql_fetch_array($query2)) {
		$koment = "UPDATE schet SET `gen`='".$row2['gen']."' WHERE `id`='".$row['id']."' ";
		mysql_query($koment) or die(mysql_error($link)); // Генерация
		$koment2 = "UPDATE schet SET `turbo`='".$row2['turbo']."' WHERE `id`='".$row['id']."' ";
		mysql_query($koment2) or die(mysql_error($link)); // Ускоренный выпуск
	}
} 


$koment3 = "UPDATE users SET `o_name`='".date('YmdHis')."' WHERE `users_id`='1' ";
mysql_query($koment3) or die(mysql_error($link)); // Последнее выполнение CRON этой страницы
?>