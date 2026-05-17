<?php
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'voovi.ru');  
define ('DB_PASSWORD', 'voovi.ru');
define ('DB_NAME', 'voovi');
$link = mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8") or die ("<br>Invalid query: " . mysql_error()); 
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());

$queryq = mysql_query("SELECT * FROM schet");
while($row = mysql_fetch_array($queryq)) {
		$dateschet = "UPDATE schet SET `date_schet`='".$row['y'].'-'.$row['m'].'-'.$row['d']."' WHERE `id`='".$row['id']."' ";
		mysql_query($dateschet) or die(mysql_error($link));
}

mysql_close($link);
?>