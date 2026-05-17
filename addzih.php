<?php
	include 'conn.php'; 
	mysql_query("INSERT INTO `user40727_iholmes`.`gotovo` (`zayavka`,`ogrn`) VALUES ('$_GET[id]','$_GET[bp]')") or die(mysqli_error($link));
	header("Location: ".$_SERVER['HTTP_REFERER']); 
?>