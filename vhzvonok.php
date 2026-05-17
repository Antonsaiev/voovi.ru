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
						<div class="col-md-8">
						<?php




										if(isset($_POST['submit'])){
										$rand = (rand(1000000,100000000000));
										$klient = "INSERT INTO `user40727_test`.`klient` (

										`idd`,
										`contragent`,
										`tipzv`,
										`dr`,
										`mr`,
										`gr`,
										`man`,
										`status`,
										`operac`,
										`fname`,
										`lname`,
										`mname`,
										`tel`,
										`dy`,
										`my`,
										`yy`,
										`email`,
										`agent`,
										`ouznal`,
										`komm1`

										) VALUES (

										'" . $rand . "',
										'$_POST[contragent]',
										'2',
										'" . date("d") . "',
										'" . date("m") . "',
										'" . date("Y") . "',
										'" . $userdata['users_id'] . "',
										'$_POST[status]',
										'$_POST[operac]',
										'$_POST[fname]',
										'$_POST[lname]',
										'$_POST[mname]',
										'$_POST[tel]',
										'$_POST[dy]',
										'$_POST[my]',
										'$_POST[yy]',
										'$_POST[email]',
										'$_POST[agent]',
										'$_POST[ouznal]',
										'$_POST[komm1]'


										)";

										$obekt = "INSERT INTO `user40727_test`.`obekt` (
										`man`,
										`agent`,
										`dr`,
										`mr`,
										`gr`,
										`idd`,
										`operac`,
										`tnedv`,
										`status`,
										`komis`,
										`kkomn`,
										`ssdelk`,
										`b3l`,
										`koo`,
										`kvp`,
										`komm2`,

										`oblast`,
										`roblast`,
										`npunkt`,
										`rgoroda`,
										`uog`,
										`ulica`,
										`dom`,
										`drob`,
										`korpus`,
										`stroe`,
										`kvartir`,
										`podez`,
										`ataj`,
										`atajn`,

										`opl`,
										`kpl`,
										`jpl`,
										`pkpl`,
										`tippor`,
										`msp`,
										`sdp`,
										`vpp`,
										`gpp`,
										`kpar`,
										`remont`,
										`okna`,
										`vio`,
										`su`,
										`lb`,
										`pol`,
										`dvery`,
										`batar`,

										`ugkv`,
										`spka`,
										`ohte`,
										`ogte`,
										`muod`,
										`gas`,
										`avot`,
										`dolu`,
										`inet`,
										`teon`,
										`lift`,
										`grli`,
										`vaer`

										) VALUES (
										'" . $userdata['users_id'] . "',
										'$_POST[agent]',
										'" . date("d") . "',
										'" . date("m") . "',
										'" . date("Y") . "',
										'" . $rand . "',
										'$_POST[operac]',
										'$_POST[tnedv]',
										'$_POST[status]',
										'$_POST[komis]',
										'$_POST[kkomn]',
										'$_POST[ssdelk]',
										'$_POST[b3l]',
										'$_POST[koo]',
										'$_POST[kvp]',
										'$_POST[komm2]',

										'$_POST[oblast]',
										'$_POST[roblast]',
										'$_POST[npunkt]',
										'$_POST[rgoroda]',
										'$_POST[uog]',
										'$_POST[ulica]',
										'$_POST[dom]',
										'$_POST[drob]',
										'$_POST[korpus]',
										'$_POST[stroe]',
										'$_POST[kvartir]',
										'$_POST[podez]',
										'$_POST[ataj]',
										'$_POST[atajn]',

										'$_POST[opl]',
										'$_POST[kpl]',
										'$_POST[jpl]',
										'$_POST[pkpl]',
										'$_POST[tippor]',
										'$_POST[msp]',
										'$_POST[sdp]',
										'$_POST[vpp]',
										'$_POST[gpp]',
										'$_POST[kpar]',
										'$_POST[remont]',
										'$_POST[okna]',
										'$_POST[vio]',
										'$_POST[su]',
										'$_POST[lb]',
										'$_POST[pol]',
										'$_POST[dvery]',
										'$_POST[batar]',

										'$_POST[ugkv]',
										'$_POST[spka]',
										'$_POST[ohte]',
										'$_POST[ogte]',
										'$_POST[muod]',
										'$_POST[gas]',
										'$_POST[avot]',
										'$_POST[dolu]',
										'$_POST[inet]',
										'$_POST[teon]',
										'$_POST[lift]',
										'$_POST[grli]',
										'$_POST[vaer]'

										)";
										
										
										$aktivn = "INSERT INTO `user40727_test`.`aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.m.Y; H:i:s") ."','Входящий звонок','".$userdata['users_id']."')";
										mysql_query($aktivn) or die(mysql_error($link));


										mysql_query($klient) or die(mysql_error($link));
										mysql_query($obekt) or die(mysql_error($link));


										echo '<div class="alert alert-success">
      <strong>Удачно!</strong> Новый звонок успешно добавлен.
    </div>';
										}





									?>
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

								
								<div class="col-md-12" style="margin-top: -5px;">


<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="scheta">
  
  <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist" style="background: #E6E6E6; border-bottom: 6px solid #8BC08B;">
  <li class="active"><a style="font-weight: bold;" href="#scheta" role="tab" data-toggle="tab">Контактная информация</a></li>
  <li><a style="font-weight: bold;" href="#dogov" role="tab" data-toggle="tab">Сервис</a></li>
  <li><a style="font-weight: bold;" href="#akti" role="tab" data-toggle="tab">Дополнительно</a></li>
  <li><a style="font-weight: bold;" href="#cpecif" role="tab" data-toggle="tab">Статус</a></li>
  <li><a style="font-weight: bold;" href="#kvoo" role="tab" data-toggle="tab">Квитанции </a></li>
  <li><a style="font-weight: bold;" href="#kakl" role="tab" data-toggle="tab">Карты </a></li>
</ul>


<div style="margin-top: 6px;"></div>
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
								<span class="input-group-addon ">ИНН:</span>
								<input class="col-md-12 form-control" type="text" name="tel" value="<?php echo $person['tel']; ?>"  />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon ">КПП:</span>
								<input class="col-md-12 form-control" type="text" name="tel" value="<?php echo $person['tel']; ?>"  />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon ">Название организации:</span>
								<input class="col-md-12 form-control" type="text" name="tel" value="<?php echo $person['tel']; ?>"  />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">E-mail:</span>
								<input class="col-md-12 form-control" type="text" name="email" value="<?php echo $person['email']; ?>"  />
								</div><div style="margin-top: 6px;"></div>
  
<a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
right: 6px;
border-radius: 2px;" href="#dogov" role="tab" data-toggle="tab">Далее</a>
  
  </div>
  
  
  
  <div class="tab-pane" id="dogov">

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist" style="background: #E6E6E6; border-bottom: 6px solid #8BC08B;">
  <li><a style="font-weight: bold;" href="#scheta" role="tab" data-toggle="tab">Контактная информация</a></li>
  <li class="active"><a style="font-weight: bold;" href="#dogov" role="tab" data-toggle="tab">Сервис</a></li>
  <li><a style="font-weight: bold;" href="#akti" role="tab" data-toggle="tab">Дополнительно</a></li>
  <li><a style="font-weight: bold;" href="#cpecif" role="tab" data-toggle="tab">Статус</a></li>
  <li><a style="font-weight: bold;" href="#kvoo" role="tab" data-toggle="tab">Квитанции </a></li>
  <li><a style="font-weight: bold;" href="#kakl" role="tab" data-toggle="tab">Карты </a></li>
</ul>
								
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Сервис:</span>
  <select class="col-md-12 form-control" type="text" onchange="vahtaChange(this)"  id="agent" />
								<option></option>
								<option value="kontur">Контур</option>
								  <option value="torgi">Торги</option>
								  <option value="savoir">Услуги SAVOIR</option>
								  <option value="veb">ВЭБ</option>
								  <option value="centrinfo">Центринформ</option>
								</select>
								</div>
<div style="margin-top: 6px;"></div>


<label id="kontur" style="display:none">
<div class="input-group">
<span class="input-group-addon alert-danger">Контур:</span>
  <select class="col-md-12 form-control" type="text"  onchange="konturChange(this)" id="agent" />
								<option></option>
								  <option value="kontur1">Алкоголь.Контур</option>
								  <option value="kontur2">Контур-Норматив</option>
								  <option value="kontur3">Контур-Экстен</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>						
</label>
<label id="kontur1" style="display:none">
								<div class="input-group">
<span class="input-group-addon alert-danger">Тип организации:</span>
  <select class="col-md-12 form-control" type="text"  id="agent" />
								<option></option>
								  <option value="alkkontip">ИП</option>
								  <option value="alkkonturli">Юр. лица</option>
								</select>
								</div>
<div style="margin-top: 6px;"></div>
									<div class="input-group">
<span class="input-group-addon alert-danger">Является обонентом Контур-Экстерн:</span>
  <select class="col-md-12 form-control" type="text"  id="agent" onchange="alkkontChange(this)" />
								<option></option>
								  <option value="alkkontyes">Да</option>
								  <option value="alkkontno">Нет</option>
								</select>
								</div>
</label>


<!-- ------------------------------------------------------- Является обонентом Контур-Экстерн: ДА ----------------------------------------------------------------- -->

<div  id="alkkontyes" style="display:none">

<div class="col-md-3"   style="border: 2px solid #fff;">

<div id="Sum1">
<style>
#fon1:checked ~ .fon1 {
  background: rgb(200, 218, 189);
}
</style>
<div style="margin: 0 -5px;padding: 3px 5px;height: 40px;padding-left: 32px;background: #8BC08B;color: #fff;font-size: 12px;font-weight: bold;">
Отчетновть в РАР для ОБ и ПКД</div>
<input type="radio" name="tri" value="6700" id="fon1" style="position: absolute;top: 10px;left: 10px;">
<div class="fon1" style="font-weight: normal;padding: 0 5px;height: 150px;margin: 0 -5px;border: 2px solid #8BC08B;">


  <input type="checkbox" value="300"/>Крипто про лицензия<br>
  <input type="checkbox" value="800"/>Токен<br>
  <output id="rezultat1" style="position: absolute;bottom: 0;padding: 4px 10px;font-size: 15px;color: #fff;margin: 0 -7px;font-weight: bold;width: 100%;background: #8BC08B;">Сумма: 0 руб</output>
</div>

<script>
var s1 = document.getElementById('Sum1'),
    d1 = s1.querySelectorAll('input[type="checkbox"][type="radio"]:not([value]), input[type="checkbox"][type="radio"][value=""]');
  for (var i = 0; i < d1.length; i++) // чтобы не было написано NaN, убираем в disabled пункты, где не прописаны значения
    d1[i].disabled = true;
s1.onchange = function() { // начало работы функции сложения
  var n = s1.querySelectorAll('[type="checkbox"],[type="radio"]'),
      itog1 = 0;
  for(var j=0; j<n.length; j++)
    n[j].checked ? itog1 += parseFloat(n[j].value) : itog1;
    document.getElementById('rezultat1').innerHTML = 'Сумма: ' + itog1 + ' руб';
}



</script>


</div>
</div>

<div class="col-md-3"   style="border: 2px solid #fff;">

<div id="Sum2">
<style>
#fon2:checked ~ .fon2 {
  background: rgb(200, 218, 189);
}
</style>
<div style="margin: 0 -5px;padding: 3px 5px;height: 40px;padding-left: 32px;background: #8BC08B;color: #fff;font-size: 12px;font-weight: bold;">
Отчетновть в РАР</div>
<input type="radio" name="tri" value="3800" id="fon2" style="position: absolute;top: 10px;left: 10px;">
<div class="fon2" style="font-weight: normal;padding: 0 5px;height: 150px;margin: 0 -5px;border: 2px solid #8BC08B;">


  <input type="checkbox" value="300"/>Крипто про лицензия<br>
  <input type="checkbox" value="800"/>Токен<br>
  <output id="rezultat2" style="position: absolute;bottom: 0;padding: 4px 10px;font-size: 15px;color: #fff;margin: 0 -7px;font-weight: bold;width: 100%;background: #8BC08B;">Сумма: 0 руб</output>
</div>

<script>
var s2 = document.getElementById('Sum2'),
    d2 = s2.querySelectorAll('input[type="checkbox"]:not([value]), input[type="checkbox"][value=""]');
  for (var i = 0; i < d2.length; i++) // чтобы не было написано NaN, убираем в disabled пункты, где не прописаны значения
    d2[i].disabled = true;
s2.onchange = function() { // начало работы функции сложения
  var n = s2.querySelectorAll('[type="checkbox"],[type="radio"]'),
      itog2 = 0;
  for(var j=0; j<n.length; j++)
    n[j].checked ? itog2 += parseFloat(n[j].value) : itog2;
    document.getElementById('rezultat2').innerHTML = 'Сумма: ' + itog2 + ' руб';
}



</script>


</div>
</div>

<div class="col-md-3"   style="border: 2px solid #fff;">

<div id="Sum3">
<style>
#fon3:checked ~ .fon3 {
  background: rgb(200, 218, 189);
}
</style>
<div style="margin: 0 -5px;padding: 3px 5px;height: 40px;padding-left: 32px;background: #8BC08B;color: #fff;font-size: 12px;font-weight: bold;">
Отчетновть в РАР через уполномоченное лицо</div>
<input type="radio"  name="tri"  value="2500" id="fon3" style="position: absolute;top: 10px;left: 10px;">
<div class="fon3" style="font-weight: normal;padding: 0 5px;height: 150px;margin: 0 -5px;border: 2px solid #8BC08B;">

  <input type="checkbox" value="800" />Токен<br>
  <output id="rezultat3" style="position: absolute;bottom: 0;padding: 4px 10px;font-size: 15px;color: #fff;margin: 0 -7px;font-weight: bold;width: 100%;background: #8BC08B;">Сумма: 0 руб</output>
</div>

<script>
var s3 = document.getElementById('Sum3'),
    d3 = s3.querySelectorAll('input[type="checkbox"]:not([value]), input[type="checkbox"][value=""]');
  for (var i = 0; i < d3.length; i++) // чтобы не было написано NaN, убираем в disabled пункты, где не прописаны значения
    d3[i].disabled = true;
s3.onchange = function() { // начало работы функции сложения
  var n = s3.querySelectorAll('[type="checkbox"],[type="radio"]'),
      itog3 = 0;
  for(var j=0; j<n.length; j++)
    n[j].checked ? itog3 += parseFloat(n[j].value) : itog3;
    document.getElementById('rezultat3').innerHTML = 'Сумма: ' + itog3 + ' руб';
}



</script>


</div>
</div>












<div class="col-md-3"   style="border: 2px solid #fff;">

<div id="Sum4">
<style>
#fon4:checked ~ .fon4 {
  background: rgb(200, 218, 189);
}
</style>
<div style="margin: 0 -5px;padding: 3px 5px;height: 40px;padding-left: 32px;background: #8BC08B;color: #fff;font-size: 12px;font-weight: bold;">
Тарифные модификации РАР</div>
<input type="radio"  name="tri"  value="0" id="fon4" style="position: absolute;top: 10px;left: 10px;">
<div class="fon4" style="font-weight: normal;padding: 0 5px;height: 150px;margin: 0 -5px;border: 2px solid #8BC08B;">

Тарифный модификатор:
<select class="col-md-12 " type="text"  id="suchka" />
<option value="0"></option>
<option id="r1" value="2500">РАР</option>
<option id="r2" value="4000">РАР ОБ</option>
<option id="r3" value="4000">РАР для ПКД</option>
</select>
Тарифный план:<br>
<select class="col-md-12 " type="text"  onchange="mpristChange(this)" />
<option value="0"></option>
<option value="0">Модификатор приобретен с тарифом</option>
<option value="123123" >Модификатор приобретен с течением срока</option>
</select>
<p  id="123123" style="display:none">
Количество месяцев:
<input style="width: 100%;" id="slon" class="col-md-12" type="text" name="tel" value="0"  />
</p>
  <output id="rezultat4" style="position: absolute;bottom: 0;padding: 4px 10px;font-size: 15px;color: #fff;margin: 0 -7px;font-weight: bold;width: 100%;background: #8BC08B;">Сумма: 0 руб</output>
</div>

<script>
var s4 = document.getElementById('Sum4'),
    d4 = s4.querySelectorAll('input[type="checkbox"]:not([value]), input[type="checkbox"][value=""]');
  for (var i = 0; i < d4.length; i++) // чтобы не было написано NaN, убираем в disabled пункты, где не прописаны значения
    d4[i].disabled = true;
s4.onchange = function() { // начало работы функции сложения
  var n = s4.querySelectorAll('[type="checkbox"],[type="radio"]'),
      itog4 = 0;
  for(var j=0; j<n.length; j++)
    n[j].checked ? itog4 += parseFloat(n[j].value) : itog4;
	totalInt = parseInt(rezultat4.innerHTML.innerHTML);
    document.getElementById('rezultat4').innerHTML = 'Сумма: ' + parseInt(itog4 + parseInt(suchka.value) - parseInt(slon.value * suchka.value / 12)) + ' руб';

}
</script>
</div>
</div>
</div>


<!-- -------------------------------------------------------- Является обонентом Контур-Экстерн: НЕТ ------------------------------------------------------- -->

<div  id="alkkontno" style="display:none">

<div class="col-md-4"   style="border: 2px solid #fff;">

<div id="Sum5">
<style>
#fon5:checked ~ .fon5 {
  background: rgb(200, 218, 189);
}
</style>
<div style="margin: 0 -5px;padding: 3px 5px;height: 40px;padding-left: 32px;background: #8BC08B;color: #fff;font-size: 12px;font-weight: bold;">
Отчетновть в РАР для ОБ и ПКД</div>
<input type="radio"  name="tri" value="6700" id="fon5" style="position: absolute;top: 10px;left: 10px;">
<div class="fon5" style="font-weight: normal;padding: 0 5px;height: 150px;margin: 0 -5px;border: 2px solid #8BC08B;">


  <input type="checkbox" value="300"/>Крипто про лицензия<br>
  <input type="checkbox" value="800"/>Токен<br>
  <output id="rezultat5" style="position: absolute;bottom: 0;padding: 4px 10px;font-size: 15px;color: #fff;margin: 0 -7px;font-weight: bold;width: 100%;background: #8BC08B;">Сумма: 0 руб</output>
</div>

<script>
var s5 = document.getElementById('Sum5'),
    d5 = s5.querySelectorAll('input[type="checkbox"]:not([value]), input[type="checkbox"][value=""]');
  for (var i = 0; i < d5.length; i++) // чтобы не было написано NaN, убираем в disabled пункты, где не прописаны значения
    d5[i].disabled = true;
s5.onchange = function() { // начало работы функции сложения
  var n = s5.querySelectorAll('[type="checkbox"],[type="radio"]'),
      itog5 = 0;
  for(var j=0; j<n.length; j++)
    n[j].checked ? itog5 += parseFloat(n[j].value) : itog5;
    document.getElementById('rezultat5').innerHTML = 'Сумма: ' + itog5 + ' руб';
}



</script>


</div>
</div>

<div class="col-md-4"   style="border: 2px solid #fff;">

<div id="Sum6">
<style>
#fon6:checked ~ .fon6 {
  background: rgb(200, 218, 189);
}
</style>
<div style="margin: 0 -5px;padding: 3px 5px;height: 40px;padding-left: 32px;background: #8BC08B;color: #fff;font-size: 12px;font-weight: bold;">
Отчетновть в РАР</div>
<input type="radio"  name="tri" value="0" id="fon6" style="position: absolute;top: 10px;left: 10px;">
<div class="fon6" style="font-weight: normal;padding: 0 5px;height: 150px;margin: 0 -5px;border: 2px solid #8BC08B;">


  <input type="checkbox" value="300"/>Крипто про лицензия<br>
  Тарифный план:<br>
<select class="col-md-12 " type="text"  id="suchka3" />
<option value="0"></option>
<option value="3800">Для неабонентов КЭ</option>
<option value="2500">Для абонентов КЭ</option>
<option value="4000">Для абонентов КЭ ОБ</option>
</select>
  <input type="checkbox" value="800"/>Токен<br>
  <output id="rezultat6" style="position: absolute;bottom: 0;padding: 4px 10px;font-size: 15px;color: #fff;margin: 0 -7px;font-weight: bold;width: 100%;background: #8BC08B;">Сумма: 0 руб</output>
</div>

<script>
var s6 = document.getElementById('Sum6'),
    d6 = s6.querySelectorAll('input[type="checkbox"]:not([value]), input[type="checkbox"][value=""]');
  for (var i = 0; i < d6.length; i++) // чтобы не было написано NaN, убираем в disabled пункты, где не прописаны значения
    d6[i].disabled = true;
s6.onchange = function() { // начало работы функции сложения
  var n = s6.querySelectorAll('[type="checkbox"],[type="radio"]'),
      itog6 = 0;
  for(var j=0; j<n.length; j++)
    n[j].checked ? itog6 += parseFloat(n[j].value) : itog6;
    document.getElementById('rezultat6').innerHTML = 'Сумма: ' +  parseInt(itog6 + parseInt(suchka3.value)) +  ' руб';
}



</script>


</div>
</div>

<div class="col-md-4"   style="border: 2px solid #fff;">

<div id="Sum7">
<style>
#fon7:checked ~ .fon7 {
  background: rgb(200, 218, 189);
}
</style>
<div style="margin: 0 -5px;padding: 3px 5px;height: 40px;padding-left: 32px;background: #8BC08B;color: #fff;font-size: 12px;font-weight: bold;">
Отчетновть в РАР через уполномоченное лицо</div>
<input type="radio"  name="tri" value="2500" id="fon7" style="position: absolute;top: 10px;left: 10px;">
<div class="fon7" style="font-weight: normal;padding: 0 5px;height: 150px;margin: 0 -5px;border: 2px solid #8BC08B;">

  <input type="checkbox" value="800" />Токен<br>
  <output id="rezultat7" style="position: absolute;bottom: 0;padding: 4px 10px;font-size: 15px;color: #fff;margin: 0 -7px;font-weight: bold;width: 100%;background: #8BC08B;">Сумма: 0 руб</output>
</div>

<script>
var s7 = document.getElementById('Sum7'),
    d7 = s7.querySelectorAll('input[type="checkbox"]:not([value]), input[type="checkbox"][value=""]');
  for (var i = 0; i < d7.length; i++) // чтобы не было написано NaN, убираем в disabled пункты, где не прописаны значения
    d7[i].disabled = true;
s7.onchange = function() { // начало работы функции сложения
  var n = s7.querySelectorAll('[type="checkbox"],[type="radio"]'),
      itog7 = 0;
  for(var j=0; j<n.length; j++)
    n[j].checked ? itog7 += parseFloat(n[j].value) : itog7;
    document.getElementById('rezultat7').innerHTML = 'Сумма: ' + itog7 + ' руб';
}
</script>
</div>
</div>
</div>





<!-- ------------------------------------------------------------------------------------------------------------------------ -->



<label id="torgi" style="display:none">
<div class="input-group">
<span class="input-group-addon alert-danger">Торги:</span>
  <select class="col-md-12 form-control" type="text"  id="agent" />
								<option></option>
								  <option value="torgi1">Сертум</option>
								  <option value="torgi2">КЭП</option>
								  <option value="torgi3">Услуги УЦ</option>
								</select>
								</div>
</label>
<label id="savoir" style="display:none">
<div class="input-group">
<span class="input-group-addon alert-danger">Услуги SAVOIR:</span>
  <select class="col-md-12 form-control" type="text"   id="agent" />
								<option></option>
								  <option value="sav1">Электронные торги</option>
								  <option value="sav2">Контур-Экстерн</option>
								  <option value="sav3">Центринформ</option>
								  <option value="sav4">Диадок</option>
								  <option value="sav5">Росреестр</option>
								  <option value="sav6">ЕИАС мониторинг</option>
								  <option value="sav7">Программное обеспечение</option>
								  <option value="sav8">Дополнительные услуги</option>
								</select>
								</div>
</label>
<label id="veb" style="display:none">
<div class="input-group">
<span class="input-group-addon alert-danger">ВЭБ:</span>
  <select class="col-md-12 form-control" type="text" id="agent" />
								<option></option>
								  <option value="veb1">Бугалтерия контур</option>
								  <option value="veb2">Диадок</option>
								  <option value="veb3">Контур-Фокус</option>
								</select>
								</div>
</label>


<SCRIPT LANGUAGE="JavaScript">
<!--
 function vahtaChange(combo){

   if(combo.value == 'kontur'){
     document.getElementById('kontur').style.display = 'block';
   }else{
   document.getElementById('kontur').style.display = 'none';
   }


   if(combo.value == 'torgi'){
     document.getElementById('torgi').style.display = 'block';
   }else{
   document.getElementById('torgi').style.display = 'none';
   }


   if(combo.value == 'savoir'){
     document.getElementById('savoir').style.display = 'block';
   }else{
   document.getElementById('savoir').style.display = 'none';
   }


   if(combo.value == 'veb'){
     document.getElementById('veb').style.display = 'block';
   }else{
   document.getElementById('veb').style.display = 'none';
   }


   if(combo.value == 'centrinfo'){
     document.getElementById('centrinfo').style.display = 'block';
   }else{
   document.getElementById('centrinfo').style.display = 'none';
   }

 }
 function konturChange(combo){

   if(combo.value == 'kontur1'){
     document.getElementById('kontur1').style.display = 'block';
   }else{
   document.getElementById('kontur1').style.display = 'none';
   }

 }
  function alkkontChange(combo){

   if(combo.value == 'alkkontyes'){
     document.getElementById('alkkontyes').style.display = 'block';
   }else{
   document.getElementById('alkkontyes').style.display = 'none';
   }
   
   if(combo.value == 'alkkontno'){
     document.getElementById('alkkontno').style.display = 'block';
   }else{
   document.getElementById('alkkontno').style.display = 'none';
   }
   
 }
 
  function mpristChange(combo){

   if(combo.value == '123123'){
     document.getElementById('123123').style.display = 'block';
   }else{
   document.getElementById('123123').style.display = 'none';
   }
   
   
 }
//-->
</SCRIPT>

<div class="col-md-12" style="
    padding: 0;
">
<a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
border-radius: 2px;" href="#scheta" role="tab" data-toggle="tab">Назад</a><a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
right: 6px;
border-radius: 2px;" href="#akti" role="tab" data-toggle="tab">Далее</a>
  
  </div>  </div>
  <div class="tab-pane" id="akti">
  
  <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist" style="background: #E6E6E6; border-bottom: 6px solid #8BC08B;">
  <li><a style="font-weight: bold;" href="#scheta" role="tab" data-toggle="tab">Контактная информация</a></li>
  <li><a style="font-weight: bold;" href="#dogov" role="tab" data-toggle="tab">Сервис</a></li>
  <li class="active"><a style="font-weight: bold;" href="#akti" role="tab" data-toggle="tab">Дополнительно</a></li>
  <li><a style="font-weight: bold;" href="#cpecif" role="tab" data-toggle="tab">Статус</a></li>
  <li><a style="font-weight: bold;" href="#kvoo" role="tab" data-toggle="tab">Квитанции </a></li>
  <li><a style="font-weight: bold;" href="#kakl" role="tab" data-toggle="tab">Карты </a></li>
</ul>
  
  
  
  <table class="table table-striped " style="
    font-size: 11px;
">
  <tr>
  <th><input type="checkbox" /></th>
  <th><p style="font-size: 15px;margin: 0;">Выпуск сертификата</p>Оформление документов, генерация закрытого ключа</th>
  <th style="font-size: 18px;">500<i class="icon-rouble"></i></th>
  </tr>
  <tr>
  <th><input type="checkbox" /></th>
  <th><p style="font-size: 15px;margin: 0;">Установка программного обеспечения на одном рабочем месте</p>Установка ПО, КриптоПро, настройка браузера для работы в системе Контур Экстерн</th>
  <th style="font-size: 18px;">1560<i class="icon-rouble"></i></th>
  </tr>
  <tr>
  <th><input type="checkbox" /></th>
  <th><p style="font-size: 15px;margin: 0;">Установка сертификата</p>Установка сертификата при продлении, дополнительного сертификата, сертификата ФСС на корректно настроеное рабочее место</th>
  <th style="font-size: 18px;">700<i class="icon-rouble"></i></th>
  </tr>
  <tr>
  <th><input type="checkbox" /></th>
  <th><p style="font-size: 15px;margin: 0;">Установка Крипто Про</p></th>
  <th style="font-size: 18px;">700<i class="icon-rouble"></i></th>
  </tr>
  <tr>
  <th><input type="checkbox" /></th>
  <th><p style="font-size: 15px;margin: 0;">Консультация</p></th>
  <th style="font-size: 18px;">800<i class="icon-rouble"></i></th>
  </tr>
  </table>
  
  
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
								<span class="input-group-addon" >Комментарий:</span>
								<textarea  class="form-control" rows="9" type="text" name="komm1" value="<?php echo $person['komm1']; ?>"></textarea>

								</div>
								<div style="margin-top: 6px;"></div>
								
								
								
								<a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
border-radius: 2px;" href="#dogov" role="tab" data-toggle="tab">Назад</a><a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
right: 6px;
border-radius: 2px;" href="#cpecif" role="tab" data-toggle="tab">Далее</a>
								
								</div>
  <div class="tab-pane" id="cpecif">  <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist" style="background: #E6E6E6; border-bottom: 6px solid #8BC08B;">
  <li><a style="font-weight: bold;" href="#scheta" role="tab" data-toggle="tab">Контактная информация</a></li>
  <li><a style="font-weight: bold;" href="#dogov" role="tab" data-toggle="tab">Сервис</a></li>
  <li><a style="font-weight: bold;" href="#akti" role="tab" data-toggle="tab">Дополнительно</a></li>
  <li class="active"><a style="font-weight: bold;" href="#cpecif" role="tab" data-toggle="tab">Статус</a></li>
  <li><a style="font-weight: bold;" href="#kvoo" role="tab" data-toggle="tab">Квитанции </a></li>
  <li><a style="font-weight: bold;" href="#kakl" role="tab" data-toggle="tab">Карты </a></li>
</ul>


<a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
border-radius: 2px;" href="#akti" role="tab" data-toggle="tab">Назад</a><a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
right: 6px;
border-radius: 2px;" href="#kvoo" role="tab" data-toggle="tab">Далее</a>

</div>
  <div class="tab-pane" id="kvoo">  <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist" style="background: #E6E6E6; border-bottom: 6px solid #8BC08B;">
  <li><a style="font-weight: bold;" href="#scheta" role="tab" data-toggle="tab">Контактная информация</a></li>
  <li><a style="font-weight: bold;" href="#dogov" role="tab" data-toggle="tab">Сервис</a></li>
  <li><a style="font-weight: bold;" href="#akti" role="tab" data-toggle="tab">Дополнительно</a></li>
  <li><a style="font-weight: bold;" href="#cpecif" role="tab" data-toggle="tab">Статус</a></li>
  <li class="active"><a style="font-weight: bold;" href="#kvoo" role="tab" data-toggle="tab">Квитанции </a></li>
  <li><a style="font-weight: bold;" href="#kakl" role="tab" data-toggle="tab">Карты </a></li>
</ul>

<a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
border-radius: 2px;" href="#cpecif" role="tab" data-toggle="tab">Назад</a><a style="font-weight: bold;
background: #3b5998;
color: #fff;
padding: 7px 30px;
position: absolute;
right: 6px;
border-radius: 2px;" href="#kakl" role="tab" data-toggle="tab">Далее</a>


</div>
  <div class="tab-pane" id="kakl">
    <!-- Nav tabs -->

	<ul class="nav nav-tabs" role="tablist" style="background: #E6E6E6; border-bottom: 6px solid #8BC08B;">
  <li><a style="font-weight: bold;" href="#scheta" role="tab" data-toggle="tab">Контактная информация</a></li>
  <li><a style="font-weight: bold;" href="#dogov" role="tab" data-toggle="tab">Сервис</a></li>
  <li><a style="font-weight: bold;" href="#akti" role="tab" data-toggle="tab">Дополнительно</a></li>
  <li><a style="font-weight: bold;" href="#cpecif" role="tab" data-toggle="tab">Статус</a></li>
  <li><a style="font-weight: bold;" href="#kvoo" role="tab" data-toggle="tab">Квитанции </a></li>
  <li  class="active"><a style="font-weight: bold;" href="#kakl" role="tab" data-toggle="tab">Карты </a></li>
</ul>
  
  
  
<div class="col-md-12" style="
    padding: 0;
"><a style="font-weight: bold;
background: #3b5998;
color: #fff;
position: absolute;
top: 5px;
padding: 7px 30px;
border-radius: 2px;" href="#kvoo" role="tab" data-toggle="tab">Назад</a>
										<div style="margin-top: 6px;"></div><input class="btn btn-success" href="javascript: void(0);" onclick="$('#ord1frm .error').hide(); var name = $('#ord1frm input[name=first_name]').val(); var contact = $('#ord1frm input[name=email_telefon]').val(); if(contact!=''&&contact!='8 913-3218-2555 MAIL@MAIL.ru'){$('#ord1frm').submit();}else{$('#ord1frm .error').show();}" class="red_btn" type="submit" name="submit" value="Отправить" style="
    float: right;
    font-weight: bold;    color: #fff;    
    border: none;
    padding: 7px 30px;  
    border-radius: 2px;
" />
									</div>
									
									
									</div>
</div>
</div>
								
								
								









									


														</form>



						</div>







						<div class="col-md-4">
<div class="col-md-12">
<input type="checkbox" id="raz" class="del"/><label for="raz" class="del">Скрипт входящего звонка</label><div>


- Добрый день!<br>
- Меня зовут…Аскер…..агентство Гарант недвижимость<br>
- Скажите пожалуйста вам удобно сейчас говорить? А как к вам обращаться?<br>
-  Скажите пожалуйста, вы заявляли ваше объявление о продаже квартиры оно еще актуально?<br>
- Да<br>
-  Хорошо, Я как понимаю, я разговариваю с хозяином квартиры?<br>
-  Да<br>
 - Я думаю, процесс продажи существенно ускорится, если воспользоваться новыми бесплатными услугами, которая может предложить наша компания. <br>
- Это поможет сделать заметным вашу квартиру в интернете, мы разместим информацию о вашей квартире на всех спец. позициях и ведущих сайтах недвижимости, мы бесплатно сделаем для вас планировки 3д формата, организуем профессиональную фотосъемку по вашей квартире, также полное юридическое сопровождение вашей сделки. <br>
Михаил. Для того чтобы мы более подробно могли обсудить наше дальнейшее сотрудничество я мог бы к вам  подъехать в удобное для вас время. <br>
Да подъезжай в<br>
Отлично Михаил в семь часов я обязательно к вам подъеду, до встречи. <br>


</div>
</div><div style="margin-top: 6px;"></div>
<div class="col-md-12">
<input type="checkbox" id="raz2" class="del2"/><label for="raz2" class="del2">Скрипт работы с типовыми возражениями</label>
<div>
<div class="col-md-12">
<input type="checkbox" id="raz3" class="del3"/><label for="raz3" class="del3">- Я попытаюсь продать квартиру самостоятельно</label>
<div>

Самостоятельная продажа квартиры может обернуться для Вас печальными последствиями. При оформлении документации и участии в денежных операциях Вам необходимо знать все тонкости совершаемой сделки. Иначе есть риск остаться и без денег, и без квартиры. При работе с профессиональным агентством недвижимости Вы застрахованы от мошеннических сделок, экономите порядка 100 часов своего личного времени и получаете более крупную сумму за проданную квартиру.<br>
<strong>Следствие - Вы можете потерять деньги и свою квартиру</strong>



</div>
</div>
<div class="col-md-12">
<input type="checkbox" id="raz4" class="del4"/><label for="raz4" class="del4">- Ну продавайте, я уже сказал другим агентствам кто первый продаст, тот и получит вознаграждение</label>
<div>

Обращение к нескольким агентствам недвижимости  не поможет продать Вашу квартиру быстрее, Вы совершаете большую ошибку. На самом деле Вы лишь создаёте искусственную конкуренцию, так как каждое агентство вносит Вашу квартиру в общую для всех риэлторов базу. Из-за конкуренции между агентствами потенциальный покупатель обычно теряет в цене 3-5%. Чтобы продать квартиру быстро и по максимальной цене, необходимо заключать эксклюзивный договор с одним агентством недвижимости.<br>
<strong>Следствие - Вы продаете квартиру дольше и дешевле</strong>



</div>
</div>
<div class="col-md-12">
<input type="checkbox" id="raz5" class="del5"/><label for="raz5" class="del5">- А другое агентство оценило мою квартиру дороже</label>
<div>

В 90% случаев при предварительной оценке квартиры агенты завышают цену для того, чтобы заполучить клиента. Через месяц безрезультатной работы агентство убеждает Вас понизить цену, оправдывая это слишком высокой стоимостью на рынке недвижимости. Уже привыкнув к родному агентству, Вы соглашаетесь на снижение цены.<br>
<strong>Следствие - Вы теряете время и деньги</strong>


</div>
</div>
<div class="col-md-12">
<input type="checkbox" id="raz6" class="del6"/><label for="raz6" class="del6">- Нет, я хочу сотрудничать только с крупным агентством</label>
<div>

Самое распространенное заблуждение. Размер агентства недвижимости не оказывает влияния на качество оказанных услуг, которое зависит исключительно от риэлтора, его компетентности и профессионализма. В крупном агентстве высок шанс начать работать со стажёром. Действительно же профи занимают руководящие должности и не работают непосредственно с клиентом.<br>
<strong>Следствие - Вы не получаете индивидуального подхода, который Вам обещали</strong>


</div>
</div>
<div class="col-md-12">
<input type="checkbox" id="raz7" class="del7"/><label for="raz7" class="del7">- Нет, я не хочу подписывать договор</label>
<div>

<strong>Хорошо, но скорее всего квартира будет продаваться дольше.</strong><br>
<strong>- Почему это?</strong>
 А поставьте себя на место риэлтора: зачем ему тратить силы, время, деньги на рекламу, пытаясь побыстрее продать вашу квартиру, если у него нет никаких гарантий от вас? Ведь он знает, что одновременно с ним продажей занимается еще кто-то, комиссионные же с покупателя получит только один, все прочие останутся ни с чем. Вот и получается, что фактически вашей квартирой не занимается никто.

Другое дело, когда есть договор: теперь вы - реальный клиент, обслужив которого, агентство получит реальную выгоду. И чем быстрее квартира будет продана, тем лучше не только вам, но и риэлтору.


</div>
</div>

</div>
</div>
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
