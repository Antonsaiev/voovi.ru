<?php
include 'conf.php';  
$koment = "UPDATE schet SET `kto`='".$_GET['lico']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
$fsdhhgf = "SELECT * FROM users WHERE users_id =".$_GET['lico'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);
echo $pfsdhhgf['f_name'].' '.$pfsdhhgf['l_name'];
?>