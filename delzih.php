<?php
	include 'conn.php'; 
	
mysql_query("DELETE FROM gotovo WHERE id = $_GET[id]") or die(mysql_error($link));
header("Location: ".$_SERVER['HTTP_REFERER']);
?>