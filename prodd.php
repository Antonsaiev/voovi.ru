<?php
include 'conf.php'; 
if($_GET['id']==1){
$koment = "INSERT INTO `dokstamp` (`doki`, `ogrn`, `status`, `schet`) VALUES ('$_GET[doki]', '$_GET[ogrn]', '0', '$_GET[schet]')";
mysql_query($koment) or die(mysql_error($link));
echo "Создал";
}if($_GET['id']==0){
$koment = "DELETE FROM `dokstamp` WHERE `doki` = '$_GET[doki]' AND `ogrn` = '$_GET[ogrn]' AND `status` = '0' AND `schet` = '$_GET[schet]'";
mysql_query($koment) or die(mysql_error($link));
echo "Удалил";
}if($_GET['id']==3){
$koment = "INSERT INTO `dokstamp` (`doki`, `ogrn`, `status`, `schet`) VALUES ('$_GET[doki]', '$_GET[ogrn]', '0', '$_GET[schet]')";
mysql_query($koment) or die(mysql_error($link));
echo "Не нужен";
$koment2 = "DELETE FROM `dokstamp` WHERE `doki` = '$_GET[doki]' AND `ogrn` = '$_GET[ogrn]' AND `status` = '0' AND `schet` = '$_GET[schet]'";
mysql_query($koment2) or die(mysql_error($link2));
echo "Удалил";
}if($_GET['id']==4){
$koment = "UPDATE `dokstamp` SET `status`='0' WHERE `doki` = '$_GET[doki]' AND `ogrn` = '$_GET[ogrn]' AND `status` = '0' AND `schet` = '$_GET[schet]'";
mysql_query($koment) or die(mysql_error($link));
echo "Нужен";
}
?>