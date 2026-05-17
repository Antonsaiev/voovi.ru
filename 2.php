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
//Если форма отправлена
if(isset($_POST['submit'])) {

	//Проверка Поля ИМЯ
	if(trim($_POST['contactname']) == '') {
		$hasError = true;
	} else {
		$name = trim($_POST['contactname']);
	}

	//Проверка поля ТЕЛЕФОН
	if(trim($_POST['subject']) == '') {
		$hasError = true;
	} else {
		$subject = trim($_POST['subject']);
	}

	//Если ошибок нет, отправить email
	if(!isset($hasError)) {
		$emailTo = 'gmrxmax@yandex.ru'; //Место для email
		$body = "Имя: $name; \r\nТелефон: $subject;";
		$headers = 'From: GMCMS  <'.$emailTo.'>' . "\n\n" . 'Reply-To: ' . $email;

		mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">


<?php 
echo '<script>
var c=';
$query = mysql_query("SELECT DISTINCT 
ns,kolichschet,d,m,y,nomerschet,nomerschetks,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,goroddd,akt_date,otk,koment,oplachen,
oplachenks,priceks,doljen,gotov,akt,url,groupi,install,gr
FROM schet WHERE 
del = '0' AND turbo='1' AND akt='0' AND otk='0'");
while($row = mysql_fetch_array($query)) {
	$udosrpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$udosresultrpod = mysql_query($udosrpod);
	$udospersonrpod = mysql_fetch_array($udosresultrpod);
	$udos = mysql_query("SELECT * FROM users_access WHERE users = '".$userdata['users_id']."' AND uslugi = '".$udospersonrpod['parent']."'");
	while($udostup = mysql_fetch_array($udos)) {
			echo '1+';	
	}
}
echo '0;
string = numeral(c).format("0,0");
document.write(string);
</script>';
?>


</div>
</div><?php
# левая колонка сайта
include 'footer.php';  
?><br>
<br>
</div>







			
			
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>