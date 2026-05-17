<?php
$url_slash = substr_count($_SERVER['REQUEST_URI'],'/');
if($url_slash == 1){
	$url_sl = '';
}if($url_slash == 2){
	$url_sl = '../';
}if($url_slash == 3){
	$url_sl = '../../';
}

# подключаем конфиг
include $url_sl.'conf.php';  # проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
{ 
setcookie('id', '', time() - 60*24*30*12, '/'); 
setcookie('hash', '', time() - 60*24*30*12, '/');
setcookie('errors', '1', time() + 60*24*30*12, '/'); 
header('Location: '.$url_sl.'index.php'); exit();
} 
} 
else
{ 
  setcookie('errors', '2', time() + 60*24*30*12, '/');
  header('Location: '.$url_sl.'index.php'); exit();
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="<?php echo $url_sl; ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $url_sl; ?>css/toolkit.css" rel="stylesheet"> 
<body>
<?php
# шапка
include $url_sl.'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-12">
<ul>
<li><a href="setogrn.php">Настройки доступа к данным для организации</a></li>
</ul>

</div>





</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo $url_sl; ?>js/bootstrap.min.js"></script>
</body>
</html>
