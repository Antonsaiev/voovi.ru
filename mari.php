<?php
include 'conf.php';  
$koment = "UPDATE schet SET `generac`='".$_GET['lico']."', `gen_date`='".date('y').date('m')."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
echo '1';
?>