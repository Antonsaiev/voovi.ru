<?php
# Подключаем конфиг
  include 'conf.php';
  if(isset($_POST['submit']))
  {

  

  # Функция для генерации случайной строки
  function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
  }
  # Если есть куки с ошибкой то выводим их в переменную и удаляем куки
  if (isset($_COOKIE['errors'])){
      $errors = $_COOKIE['errors'];
      setcookie('errors', '', time() - 60*24*30*12, "/", VOOVI_COOKIE_DOMAIN);
  } 
	  
	  
	  
	  
	  
    # Вытаскиваем из БД запись, у которой логин равняеться введенному
    $data = mysql_fetch_assoc(mysql_query("SELECT users_id, users_password FROM `users` WHERE `del_users` = '0' AND `users_login`='".mysql_real_escape_string($_POST['login'])."' LIMIT 1"));

    # Соавниваем пароли
    if($data['users_password'] === md5(md5($_POST['password'])))
    {
      # Генерируем случайное число и шифруем его
      $hash = md5(generateCode(10));

      # Записываем в БД новый хеш авторизации и IP
      mysql_query("UPDATE users SET users_hash='".$hash."' WHERE users_id='".$data['users_id']."'") or die("MySQL Error: " . mysql_error());

      # Ставим куки
      setcookie("id", $data['users_id'], time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
      setcookie("hash", $hash, time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);

      # Переадресовываем браузер на страницу проверки нашего скрипта
      header("Location: check.php"); exit();
    }
    else
    {
      print "<div class='form-signin-error'>Попробуйте ещё раз</div>";
    }
  }
  # проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

	   if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
    { 
        setcookie('id', '', time() - 60*24*30*12, '/'); 
        setcookie('hash', '', time() - 60*24*30*12, '/');
    setcookie('errors', '1', time() + 60*24*30*12, '/'); 
    } 
	else 
    { 
     setcookie('errors', '2', time() + 60*24*30*12, '/');
     header('Location: check.php'); exit();
    }
}
?>
<html>
<head>
	<title>VooVi System -1</title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body class="cont-sing-in" style="background: url(img/lux.jpg); background-size: cover;">
  <div class="container" >
      <form method="POST" class="form-signin jumbotron" style="padding: 15px;padding: 12px;position: absolute;width: 300px;margin-left: -150px;left: 50%;top: 50%;color: #428BCA;margin-top: -80px;border: 2px solid rgba(255, 255, 255, 0.54);background: rgba(255, 255, 255, 0.69);border-radius: 7px;box-shadow: 1px 1px 10px #428BCA;">
        <h3 style="font-size: 17px;font-weight: bold;font-family: verdana;text-align: center;margin: 0;margin-bottom: 15px;">VooVi System</h3>
        <input  name="login" class="form-control col-md-12" placeholder="Ваш логин" style="margin-bottom: 6px;  border-radius: 2px;">
        <input  type="password" name="password" class="form-control col-md-12" placeholder="Ваш пароль" style="margin-bottom: 6px;  border-radius: 2px;">
        <button class="btn btn-primary col-md-12" name="submit" type="submit">Войти</button>
		<br>
		<?php
		  # Проверяем наличие в куках номера ошибки
		  if (isset($errors)) {print '<div class="form-signin-error">'.$error[$errors].'</div>';}
		?>
      </form>
    </div>
  </body>
  </html>
