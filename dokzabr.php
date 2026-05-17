<?php
include 'conf.php'; 
if($_GET['id']==1){
$koment = "INSERT INTO `dokstampzabr` (`doki`, `idkli`, `status`, `schet`) VALUES ('$_GET[doki]', '$_GET[idkli]', '2', '$_GET[schet]')";
mysql_query($koment) or die(mysql_error($link));
echo "Создал";
}if($_GET['id']==0){
$koment = "DELETE FROM `dokstampzabr` WHERE `doki` = '$_GET[doki]' AND `idkli` = '$_GET[idkli]' AND `status` = '2' AND `schet` = '$_GET[schet]'";
mysql_query($koment) or die(mysql_error($link));
echo "Удалил";
}
?>