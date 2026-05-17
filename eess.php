<?php
	include './conf.php'; 
	
				
				$u = "UPDATE napomin SET `yes`= '1',`d`= '".date('d')."',`m`= '".date('m')."',`y`= '".date('Y')."',`usersyes`= '".$_COOKIE['id']."'  WHERE id = $_GET[id]";
				mysql_query($u) or die(mysql_error($link));	
	header("Location:  " . $_SERVER['HTTP_REFERER'] . "");
?>
