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
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="blog.css" rel="stylesheet">
</head>
<body>
<?php
# шапка
include 'header.php';
?>
<div class="container" style="margin-top: 60px;">

<div class="row">


<?php


?>

<div class="col-md-12" >
	<div class="row">
						<div class="col-md-12">
						<?php




										if(isset($_POST['submit'])){
										$rand = (rand(1000000,100000000000));
										$klient = "INSERT INTO `klient` (`idd`) VALUES ('$_POST[operac]')";

										

										$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.m.Y; H:i:s") ."','Новый клиент','".$userdata['users_id']."')";
										mysql_query($aktivn) or die(mysql_error($link));
										mysql_query($klient) or die(mysql_error($link));


										echo '<div class="alert alert-success">
      <strong>Удачно!</strong> Новый клиент успешно добавлен.
    </div>';
										}





									?>
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
								<div class="col-md-12">
						<strong><h4 style="border-bottom: 1px #333 solid; font-weight: bold;">Новый клиент:</h4></strong>
								</div>
								<hr>
								<div class="col-md-6">
								<div class="input-group">
								<span class="input-group-addon">Фамилия:</span>
								<input class="col-md-12 form-control" type="text" name="fname" value="<?php echo $person['fname']; ?>"  />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon alert-danger">Имя:</span>
								<input class="col-md-12 form-control" type="text" name="lname" value="<?php echo $person['lname']; ?>"  required>
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Отчество:</span>
								<input class="col-md-12 form-control" type="text" name="mname" value="<?php echo $person['mname']; ?>"  />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon alert-danger">Телефон:</span>
								<input class="col-md-12 form-control" type="text" name="tel" value="<?php echo $person['tel']; ?>"  required>
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">День</span>
								<input class="col-md-12 form-control" value="<?php echo date("d"); ?>" type="text" name="dy" value="<?php echo $person['dy']; ?>"    />
								<span class="input-group-addon">Мес.</span>
								<input class="col-md-12 form-control" value="<?php echo date("m"); ?>" type="text" name="my" value="<?php echo $person['my']; ?>"    />
								<span class="input-group-addon">Год</span>
								<input class="col-md-12 form-control" value="<?php echo date("Y"); ?>" type="text" name="yy" value="<?php echo $person['yy']; ?>"    />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">E-mail:</span>
								<input class="col-md-12 form-control" type="text" name="email" value="<?php echo $person['email']; ?>"  />
								</div><div style="margin-top: 6px;"></div>

								</div>
								<div class="col-md-6">

								<div class="input-group">
								<span class="input-group-addon alert-danger">Статус:</span>
								<select class="col-md-12 form-control" type="text" name="status"  />
								<option value="1">Принимают решение</option>
								  <option value="2">Согласование договора</option>
								  <option value="3">Ожидание оплаты</option>
								  <option value="4">Оплачено</option>
								  <option value="5">Ждем документы</option>
								  <option value="6">Подпись документов</option>
								  <option value="7">Отгружено</option>
								  <option value="0">Должны документы</option>
								</select>
								</div>


								<div style="margin-top: 6px;"></div>
								<div class="input-group">

								<div class="checkbox">
								  <label>
									<input type="checkbox" name="contragent" value="1">
									Является контрагентом
								  </label><br>
								</div>

								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon" >Комментарий:</span>
								<textarea  class="form-control" rows="3" type="text" name="komm1" value="<?php echo $person['komm1']; ?>"></textarea>

								</div>
								<div style="margin-top: 6px;"></div>





								</div>









<div class="col-md-12 "></div>

									<div class="col-md-12">
										<div style="margin-top: 6px;"></div><input class="btn btn-success col-md-12" class="red_btn" type="submit" name="submit" value="Отправить" id="submitSuggestion" />
									</div><div style="margin-top: 6px;"></div>


														</form>



						</div>

		</div>






</div>
</div>
</div>

<br><br><br>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
