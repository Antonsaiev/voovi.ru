<?php
	include './conf.php'; 
				$u = "UPDATE napomin SET `obsh`= '1'  WHERE id = $_GET[id]";
				mysql_query($u) or die(mysql_error($link));	
	header("Location:  " . $_SERVER['HTTP_REFERER'] . "");
?> 
