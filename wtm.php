<?php
# настройки
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'gmrxmax');  
define ('DB_PASSWORD', '162534max');
define ('DB_NAME', 'pitmandb');
mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8") or die ("<br>Invalid query: " . mysql_error()); 
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());

# массив ошибок
$error[0] = 'Мы вас не знаем';
$error[1] = 'На сайт зашли с другого устройства';
$error[2] = 'Войдите на сайт';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">

</head>
<body>
<table class="table tablehover rowclick" id="rowclick2">
<?php
$i = 1; 
$query = mysql_query("SELECT * FROM wtm WHERE lagging = 'False' ORDER BY btc_revenue24 DESC LIMIT 1");
while($row = mysql_fetch_array($query)) {
echo '<tr>';
echo '<div class="tag'.$i++.'">';
		echo $row['tag'];
echo '</div>';
echo '</tr>';
} 
?>
</table>