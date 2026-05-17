<?php
# подключаем конфиг
include 'conf.php';  


$q = "SELECT * FROM focus WHERE id =$_GET[id]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);



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
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="blog.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
		<script>
			var auto_refresh = setInterval(
			function(){
				$('#load_div').fadeOut('slow').load().fadeIn("slow");
			}, 2000);
	</script>


	
	
	
	
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-10 ">
<?php


										if(isset($_POST['submit'])){
										$u = "INSERT INTO `sms` (`data`,`sms`,`klient`,`ot`) VALUES ('". date("d.t.Y; H:i:s") ."','$_POST[sms]','".$person['id']."','".$userdata['users_id']."')";
										$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.t.Y; H:i:s") ."','Новое сообщение','".$userdata['users_id']."')";
										mysql_query($aktivn) or die(mysql_error($link));
										mysql_query($u) or die(mysql_error($link));	
										echo '<div class="alert alert-success">
      <strong>Удачно!</strong> Новая задача успешно добавлена.
    </div>';
	$body=file_get_contents('http://sms.ru/sms/send?api_id=513439c3-5ece-a954-e5b2-31b36fe77cbf&to='.$userdata['tel'].'&text='.urlencode(iconv("utf-8","utf-8","$_POST[sms]")));
										}
										
									?>

								
						<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Новое сообщение:</h4></strong>
						
									
	
	<form method="POST"><div style="margin-top: 6px;"></div>
		
        Идентификатор клиента:  <?php  echo $person['id'],"<br>"; ?>
        Клиент: <?php  echo $person['name'],"<br>"; ?>
        Номер телефона: <?php  echo $person['tel']; ?>
        <br><br>
	<div class="input-group">
			<span class="input-group-addon">Сообщение:</span>
			 <textarea name="sms" value="<?php echo $person['sms']; ?>" class="form-control" rows="3">Здравствуйте <?php  echo $person['name']; ?> </textarea>
	</div>
	<br>
	<input type="submit" name="submit" value="Отправить" id="submitSuggestion" class="btn btn-success" style="float: right;"/><br>
	</form>
		

</div>


<?php
# левая колонка сайта
include 'left_sitebar.php';  
?>

</div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
