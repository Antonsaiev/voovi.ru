<?php
include 'conf.php'; 
 mysql_query("UPDATE users SET `inform`='".time()."' WHERE `users_id`='".$_GET['users_id']."'");
?>