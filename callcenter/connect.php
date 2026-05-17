<?php
// PHP Call Center
// started May 2002
// http://www.sourceforge.net/projects/phpcc

$host="192.168.0.10";
$user="sql_user";
$pass="sql_user";
$db="phpcc";

mysql_connect($host,$user,$pass);
if(!mysql_select_db($db))
{
  echo "<font face=arial size=2>Failed to connect to DB.</font>";
  exit;
}

function AltConnect()
{
  $sql_alt=mysql_connect("localhost","sql_user","sql_user");
  mysql_select_db("export",$sql_alt);
  return $sql_alt;
}

?>
