<?php
include 'conf.php'; 
if($_GET['id']==1){
$koment = "UPDATE schet SET `oplachenks`='1' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
echo "Оплата принята";
}if($_GET['id']==0){
$koment = "UPDATE schet SET `oplachenks`='0' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
echo "Оплата удалена";
}
?>