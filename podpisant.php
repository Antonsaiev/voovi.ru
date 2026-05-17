<?php
include 'conf.php';  
$koment = "UPDATE schet SET `podpisant`='".$_GET['lico']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
echo '1';
?>