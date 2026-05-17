<?php
# настройки
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'voovi.ru');  
define ('DB_PASSWORD', 'voovi.ru');
define ('DB_NAME', 'voovi');

# Домены и URL (централизовано)
if (!defined('VOOVI_MAIN_HOST')) {
    define('VOOVI_MAIN_HOST', getenv('VOOVI_MAIN_HOST') ?: 'voovi.ru');
}
if (!defined('VOOVI_MAIN_URL')) {
    define('VOOVI_MAIN_URL', (getenv('VOOVI_MAIN_SCHEME') ?: 'https').'://'.VOOVI_MAIN_HOST);
}
if (!defined('VOOVI_DOC_HOST')) {
    define('VOOVI_DOC_HOST', getenv('VOOVI_DOC_HOST') ?: 's.voovi.ru');
}
if (!defined('VOOVI_DOC_URL')) {
    define('VOOVI_DOC_URL', (getenv('VOOVI_DOC_SCHEME') ?: 'https').'://'.VOOVI_DOC_HOST);
}
if (!defined('VOOVI_COOKIE_DOMAIN')) {
    $vooviCookieDomain = getenv('VOOVI_COOKIE_DOMAIN');
    define('VOOVI_COOKIE_DOMAIN', $vooviCookieDomain !== false ? $vooviCookieDomain : '.voovi.ru');
}

mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8") or die ("<br>Invalid query: " . mysql_error()); 
mysql_query("SET SESSION sql_mode = ''") or die ("<br>Invalid query: " . mysql_error());
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());

# массив ошибок
$error[0] = 'Мы вас не знаем';
$error[1] = 'На сайт зашли с другого устройства';
$error[2] = 'Войдите на сайт';



	if(isset($_REQUEST) && count($_REQUEST)>0){
		$data="";
		foreach($_REQUEST as $key=>$val){
			if(is_string($val) && strlen($val)>2000 )
			$val=substr($val,0,2000);
			$data.=$key."=".$val."\n";
		}
		//$fp=fopen(__DIR__ ."/AAAlog-post.txt","a");
		
		$fps = date("Y-m-d H:i:s")." ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['REQUEST_METHOD']." ".$_SERVER['SCRIPT_FILENAME']."\n".$data."---------------------------\n";
		
		//fwrite($fp,date("Y-m-d H:i:s")." ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['REQUEST_METHOD']." ".$_SERVER['SCRIPT_FILENAME']."\n".$data."---------------------------\n");
		mysql_query("INSERT INTO `voovi`.`aaa_log` (`row`,`users`) VALUES ('".$fps."','".$_COOKIE['id']."');");
		fclose($fp);
		$data="";
		reset($_REQUEST);
	}

?>
