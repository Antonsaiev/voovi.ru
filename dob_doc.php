<?php
# Подключаем конфиг 
include 'conf.php'; 


# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']) ) 
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
<!DOCTYPE html>
<html itemscope="" itemtype="http://schema.org/WebPage" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
<?php 
$id_doc=htmlspecialchars($_POST["dob"]);
session_start();
$id_schet=$_SESSION['a'];
session_start();
$id_kli=$_SESSION['s'];
$id_status="0";
$id_origin="0";
$id_coopi="0";
echo $id_doc;
echo $id_schet;
echo $id_kli;

$sql=mysql_query("INSERT INTO dokstamp(doki,ogrn,status,schet,origin,coopi) values($id_doc,$id_kli,$id_status,$id_schet,$id_origin,$id_coopi)");
echo '<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
        <body>Документ добавлен</body>';
    header("Location: ".$_SERVER['HTTP_REFERER']);


?>
</body>
</html>