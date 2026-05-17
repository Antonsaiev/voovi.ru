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
$query = mysql_query("SELECT * FROM users WHERE tip != '88'");
while($row = mysql_fetch_array($query)) {
	echo '<div class="col-md-4 col-border-bg ">';
	echo '<div style="padding: 1px 0px;font-size: 19px;color: #428bca; ">'.$row['f_name'].' '.$row['l_name'].'</div>';
	echo '<div style="padding: 2px 5px;background: rgba(92, 184, 118, 0.61);color: #fff;">';
	$rpod2345 = "SELECT * FROM doljnost WHERE id = '".$row['tip']."'";
	$result57657 = mysql_query($rpod2345);
	$row134 = mysql_fetch_array($result57657);
	echo $row134['name'],'</div>';
	echo '<table class="table tablehover">';
	echo '<tr>';
	echo '<td class="chmoshnik">Выставил счетов в этом месяце '; 
	$kto = mysql_query("SELECT count(DISTINCT rand) from schet WHERE kto = '".$row['users_id']."' AND m ='".$_GET['m']."' AND y ='".$_GET['y']."'");
	echo '</td>';
	echo '<td>';
	echo mysql_result($kto, 0);
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="chmoshnik">Генераций в этом месяце SAVOIR'; 
	$generac = mysql_query("SELECT SUM(kvo) from schet WHERE generac = '".$row['users_id']."' AND gen_date ='".$_GET['y'].$_GET['m']."' AND gen='1'");
	echo '</td>';
	echo '<td>';
	$hgkvv = mysql_result($generac, 0);
	echo (INT)$hgkvv;
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="chmoshnik">Сгенерированные отгруженные '; 
	$sgenerac = mysql_query("SELECT SUM(kvo) from schet WHERE generac = '".$row['users_id']."' AND akt_date ='".$_GET['y'].$_GET['m']."'  AND akt='1' AND gen='1'");
	echo '</td>';
	echo '<td>';
	$reggrt = mysql_result($sgenerac, 0);
	echo (INT)$reggrt;
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="chmoshnik">Продление поставки '; 
	$pgenerac = mysql_query("SELECT count(DISTINCT rand,kto,generac), SUM(kvo) from schet WHERE generac = '546321564' AND m ='".$_GET['m']."' AND y ='".$_GET['y']."' AND akt='1'");
	echo '</td>';
	echo '<td>';
	echo mysql_result($pgenerac, 0);
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="chmoshnik">Новых счетов '; 
	$result2 = mysql_query("SELECT count(DISTINCT d,m,y,rand,kto) from schet WHERE d = '".$_GET['d']."' AND m = '".$_GET['m']."' AND y = '".$_GET['y']."' AND kto = '".$row['users_id']."'");
	$koll2 = mysql_result($result2, 0); 
	echo '</td>';
	echo '<td>';
	echo $koll2;
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';
}
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