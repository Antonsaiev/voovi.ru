<?php
include 'conn.php'; 
mysql_query("UPDATE lico SET `del`= '1' WHERE id = $_GET[id]") or die(mysqli_error($link));
header("Location: ".$_SERVER['HTTP_REFERER']);
?>