<?php
# подключаем конфиг
include 'conf.php'; 
if($_GET['nsold']!="")
{	
$query = mysql_query("SELECT COUNT(ns) FROM schet WHERE ns='" . $_GET['nsold'] . "'") or die ("<br>Invalid query: " . mysql_error());
    if (mysql_result($query, 0) == 0) 
	{
      echo $err[] = "Данный номер счета не найден в системе";
    }
	else
	{
		$query = mysql_query("SELECT COUNT(ns) FROM schet WHERE ns='" . $_GET['nsold'] . "' and idkli='".$_GET['idkli']."' and produkt='".$_GET['parent']."'") or die ("<br>Invalid query: " . mysql_error());
    if (mysql_result($query, 0) == 0) 
	{
     echo $err[] = "Выбранный продукт не указан в счете №'".$_GET['nsold']."'";
    }
	else
	{
			/*$query = mysql_query("SELECT COUNT(id) FROM schetprodlen WHERE schetold='" . $_GET['nsold'] . "'") or die ("<br>Invalid query: " . mysql_error());
    if (mysql_result($query, 0) == 0) 
	{*/
		$y=date("Y");
        $m=date("m");
		$d=date("d");
        $dat=$y.$m.$d;
		$azaza = "INSERT INTO `schetprodlen` (
			`schetold`,
			`dateprod`
		) VALUES (
		'".$_GET['nsold']."',
		'$dat'
		)";
		mysql_query($azaza) or die(mysql_error($link));
	/*}*/
	}
	}
}
?>