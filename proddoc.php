<?php
include 'conf.php';  
if($_GET['id']==1){
$koment = "INSERT INTO `proddoc` (`produkt`, `document`,tip) VALUES ('".$_GET['produkt']."', '".$_GET['document']."', '".$_GET['tip']."')";
mysql_query($koment) or die(mysql_error($link));
echo "Создал";
}if($_GET['id']==0){
$koment = "DELETE FROM `proddoc` WHERE `produkt` = '".$_GET['produkt']."' AND `document` = '".$_GET['document']."' AND `tip` = '".$_GET['tip']."'";
mysql_query($koment) or die(mysql_error($link));
echo "Удалил";
}
?>