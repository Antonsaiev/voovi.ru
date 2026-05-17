<?php
# подключаем конфиг
include 'conf.php';  

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
    { 
        setcookie('id', '', time() - 60*24*30*12, '/'); 
        setcookie('hash', '', time() - 60*24*30*12, '/');
    setcookie('errors', '1', time() + 60*24*30*12, '/'); 
    header('Location: index.php'); exit();
    } 
} 
else 
{ 
  setcookie('errors', '2', time() + 60*24*30*12, '/');
  header('Location: index.php'); exit();
}


	

?>
<?php 
# получение данных со страницы
$login=$userdata['users_login'];
$pass=$userdata['users_password'];
$old_pass = htmlspecialchars(md5(md5(trim($_POST["old_pass"]))));
$new_pass = htmlspecialchars(md5(md5(trim($_POST["new_pass"]))));
$confirm_pass=htmlspecialchars(md5(md5(trim($_POST["confirm_pass"]))));



/* Ваш адрес и тема сообщения */
$address = "menafis64@gmail.com";
$sub = "Сообщение с сайта voovi";

/* Формат письма */
$mes = "Сообщение с сайта voovi.\n
Для логина $login пароль был успешно изменен";
$from = "Reply-To: voovi.ru \r\n";
# проверка данных
if($pass===$old_pass)
{
	if($new_pass===$confirm_pass)
	{
		# смена пароля
		$sql=mysql_query("UPDATE `users` SET users_password='$new_pass' WHERE users_login='".$userdata['users_login']."'");
	    echo '<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
        <body>Пароль успешно изменен</body>';
		setcookie('id', '', time() - 60*24*30*12, '/'); 
        setcookie('hash', '', time() - 60*24*30*12, '/');
    setcookie('errors', '1', time() + 60*24*30*12, '/'); 
    header('Refresh: 2; URL=' . VOOVI_MAIN_URL . '/index.php'); exit();
	}
	else
    {
	header('Refresh: 5; URL=' . VOOVI_MAIN_URL . '/check.php');
	echo '<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
    <body>Новый пароль и потверждение пароля не совпадают, повторите попытку</body>';
    }
}
else
{
	header('Refresh: 5; URL=' . VOOVI_MAIN_URL . '/check.php');
	echo '<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
    <body>Вы неверно указали старый пароль</body>';
}

 ?>