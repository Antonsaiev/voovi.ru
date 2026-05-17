<?php 
# подключаем конфиг
include 'conf.php';  
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));
    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']))
    {
        setcookie('id', null, time() - 60*24*30*12, "/", VOOVI_COOKIE_DOMAIN);
        setcookie('hash', null, time() - 60*24*30*12, "/", VOOVI_COOKIE_DOMAIN);
        setcookie('errors', '1', time() + 60*24*30*12, "/", VOOVI_COOKIE_DOMAIN);
        header('Location: index.php'); exit();
    }
} 
else 
{ 
    setcookie('errors', '2', time() + 60*24*30*12, "/", VOOVI_COOKIE_DOMAIN);
    header('Location: index.php'); exit();
}
mysql_query("UPDATE users SET users_hash='' WHERE users_id =".$userdata['users_id']);
echo '<script type="text/javascript">
   document.location.href = "exit.php";
</script>';
?>