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

	if($_GET['tip']=='1'){
	mysql_query("INSERT INTO `schetizbran`(`kto`, `schet`) VALUES ('".$userdata['users_id']."','$_GET[id]')") or die(mysqli_error($link));
	}
	if($_GET['tip']=='2'){
	mysql_query("DELETE FROM `schetizbran` WHERE kto = '$_GET[users]' AND schet = '$_GET[id]'") or die(mysqli_error($link));
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
?>