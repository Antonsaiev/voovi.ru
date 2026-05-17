<?php
include 'conf.php';  
if($_GET['go'] == '1'){
$koment = "UPDATE ogrn SET `svyaz`='".$_GET['svyaz']."' WHERE id='".$_GET['id']."'";
mysql_query($koment) or die(mysql_error($link));
header("Location: ".$_SERVER['HTTP_REFERER']);
}else{
$koment = "UPDATE ogrn SET `svyaz`='0' WHERE id='".$_GET['id']."'";
mysql_query($koment) or die(mysql_error($link));
header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>