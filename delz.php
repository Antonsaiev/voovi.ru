<?php
	include 'conf.php'; 
	
	mysql_query("UPDATE focus SET `del`= '1' WHERE id = $_GET[id]") or die(mysqli_error($link));

	header("Location: ./zayav.php");
?>
