<?php
# настройки
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'mex');  
define ('DB_PASSWORD', 'mex162534');
define ('DB_NAME', 'mex');
mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8mb4") or die ("<br>Invalid query: " . mysql_error()); 
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());

# массив ошибок
$error[0] = 'Мы вас не знаем';
$error[1] = 'На сайт зашли с другого устройства';
$error[2] = 'Войдите на сайт';
?>