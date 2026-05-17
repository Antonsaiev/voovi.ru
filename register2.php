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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title>Чат</title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<link href="/css/bootstrap.min.css" rel="stylesheet">

</head>
<body style="margin-top: 60px;">
<?php
# подключаем конфиг
include 'header.php';  
?>
<center>
<div class="alert alert-warning">Выбирите файл для загрузки:</div><br />
<form enctype="multipart/form-data" method="post">
<input type="file" name="userfile"/>
<input  type="submit" value="Загрузить" name="button"/>
</form>
<?php
//Выполняем код только при нажатии на кнопку
if($_POST['button']== true){
	
	if ((($_FILES["userfile"]["type"] == "image/gif")
|| ($_FILES["userfile"]["type"] == "image/jpeg")
|| ($_FILES["userfile"]["type"] == "image/png")
|| ($_FILES["userfile"]["type"] == "image/pjpeg"))
&& ($_FILES["userfile"]["size"] < 2000000))
  {
	
$sql = "UPDATE `users` SET `img`= '" . $_FILES['userfile']['name'] . "' WHERE `users_id`= '" . $userdata['users_id'] . "'";
$query = mysql_query($sql);
//Определяем директорию, куда будем загружать изображения
$dir = 'img/';
$file = $dir.basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $file)) {
    echo '<br><div class="alert alert-success"> Файл успешно загружен.</div>';
} else {
    echo "Произошла ошибка";
    exit;
}

  }else{
	  echo '<br><div class="alert alert-danger"> Си гэшхуэр умудэгу|'.$_FILES["userfile"]["type"].'|'.$_FILES["userfile"]["size"];
  }
}   
?>
</center>
</body>
</html>
