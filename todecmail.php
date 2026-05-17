<?php
# подключаем конфиг
include 'conf.php';  # проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
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

$q = "SELECT * FROM mail_to WHERE id =$_GET[id]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);

?>

<? 
		$end = $_GET['d'] + 6;
		if($end <= date('t')){
			
			$dsfdsfds = $_GET['m'];
			if($dsfdsfds > 12){
				$mes = 1;
			}else{
				$mes = $dsfdsfds;
			}
			
			$den = $end;
		}else{
			$dsfdsfds = $_GET['m'] + 1;
			if($dsfdsfds > 12){
				$mes = 1;
			}else{
				$mes = $dsfdsfds;
			}
			$den = 6 - (date('t') - $_GET['d']);
		}

		if (!empty($_GET['email'])){
		$subject = 'Уведомление от ФНС';
		$email = 'infosavoir@yandex.ru';
		$emailTo = $_GET['email']; //Место для email
$body = "Для: ".$_GET['naim']."  \r\nВ ответ на отправленые Вами декларации поступило требование. Во избежание блокировки Ваших банковских счетов, Вам необходимо подписать квитанцию о получении в ИЦ SAVOIR до ".$den.'.'.$mes.'.'.$_GET['y']." \r\nДата получения из ФНС: ".$_GET['dat_fns']."  \r\nАдрес: г.Пятигорск ул.Февральская 3а \r\nТел: 8(8793) 33-27-99 \r\nEmail: infosavoir@yandex.ru ";
		$headers = 'From:ИЦ "SAVOIR" <'.$email.'>' . "\n\n";
		mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
		echo 'Email-1';
		}
		if (!empty($_GET['email2'])){
		$subject = 'Уведомление от ФНС';
		$email = 'infosavoir@yandex.ru';
		$emailTo = $_GET['email2']; //Место для email
$body = "Для: ".$_GET['naim']."  \r\nВ ответ на отправленые Вами декларации поступило требование. Во избежание блокировки Ваших банковских счетов, Вам необходимо подписать квитанцию о получении в ИЦ SAVOIR до ".$den.'.'.$mes.'.'.$_GET['y']." \r\nДата получения из ФНС: ".$_GET['dat_fns']."  \r\nАдрес: г.Пятигорск ул.Февральская 3а \r\nТел: 8(8793) 33-27-99 \r\nEmail: infosavoir@yandex.ru ";
		$headers = 'From:ИЦ "SAVOIR" <'.$email.'>' . "\n\n";
		mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
		echo 'Email-2';
		}
		mysql_query("UPDATE mail_to SET `status`= '1',`dat_sav`= '".date('d.m.Y H:i:s')."' WHERE id = $_GET[id]") or die(mysqli_error($link));
		echo '<script>window.location.href="./dec_mail.php?yes=1";</script>';
?> 
