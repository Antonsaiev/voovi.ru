<?php
include 'conf.php';  
$koment = "UPDATE schet SET `prodlen`='".$_GET['prodlen']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
echo '1';
?>