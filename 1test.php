<?php
include 'conf.php'; 


$query = mysql_query("SELECT * FROM schet");
while($row = mysql_fetch_array($query)) {
$oudosrpod = "SELECT * FROM ogrn WHERE inn = '".$row['inn']."' AND kpp = '".$row['kpp']."'";
	$oudosresultrpod = mysql_query($oudosrpod);
	$oudospersonrpod = mysql_fetch_array($oudosresultrpod);
	$koment3o = "UPDATE schet SET `name`='".$oudospersonrpod['naim']."' WHERE `inn`='".$row['inn']."' AND `kpp` = '".$row['kpp']."'";
	mysql_query($koment3o) or die(mysql_error($link));
}


?>