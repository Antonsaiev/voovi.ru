<?php
	include 'conf.php'; 
	
mysql_query("DELETE FROM mail_to WHERE id = $_GET[id]") or die(mysql_error($link));
header("Location: ".$_SERVER['HTTP_REFERER']);
?>