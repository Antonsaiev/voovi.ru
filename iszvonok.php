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
										`komm1`,
                                        `komma`

										) VALUES (

										'" . $rand . "',
										'$_POST[contragent]',
										'1',
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
										'$_POST[komm1]',
                                        '$_POST[komma]'


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

										$aktivn = "INSERT INTO `user40727_test`.`aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.m.Y; H:i:s") ."','Исходящий звонок','".$userdata['users_id']."')";
										mysql_query($aktivn) or die(mysql_error($link));
										mysql_query($klient) or die(mysql_error($link));
										mysql_query($obekt) or die(mysql_error($link));


										echo '<div class="alert alert-success">
      <strong>Удачно!</strong> Новый звонок успешно добавлен.
    </div>';
										}





									?>
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
								<div class="col-md-12">
						<strong><h4 style="border-bottom: 1px #333 solid; font-weight: bold;">Клиент:</h4></strong>
								</div>
								<hr>
								<div class="col-md-5">
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
								<div class="col-md-5">

								<div class="input-group">
								<span class="input-group-addon alert-danger">Статус:</span>
								<select class="col-md-12 form-control" type="text" name="status"  />
								<option value="1">Первичный контакт</option>
								  <option value="2">Переговоры</option>
								  <option value="3">Принимают решение</option>
								  <option value="4">Согласование договора</option>
								  <option value="0">В архив (без слелки)</option>
								</select>
								</div>




								<div style="margin-top: 6px;"></div>
<script>
$(document).ready(function() {
  $.viewInput = {
    '0' : $([]),
	//Это имя DIV вокруг невидимого поля
	'ag1' : $('#zadanie'),
	'ag2' : $('#zadanie'),
	'ag3' : $('#zadanie'),
	'ag4' : $('#zadanie'),
	'ag5' : $('#zadanie'),
  };

$('#agent').change(function() {
    // Прячет это поле, если выбрана другая опция
    $.each($.viewInput, function() { this.hide(); });
	// Показывает поле при выборе необходимой опции
    $.viewInput[$(this).val()].show();
  });

});
</script>


								<select style="margin-bottom: 6px;" class="col-md-12 form-control" type="text" name="agent"  id="agent" />
								  <option style="background: #ccc;" >Агент:</option>


					<?php
						$query = mysql_query("SELECT * from users WHERE tip = 2 ");
							while($row = mysql_fetch_array($query)) {
								echo '<option value="ag',$row['l_name'],'">';
								echo $row['f_name'];
								echo '&nbsp';
								echo $row['l_name'];
								echo '</option>';
						}
					?>



								</select>

<div id="zadanie">
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Задание агенту:</span>
<select class="col-md-12 form-control" type="text" name="zadanie"  />
<option value="0">Заключить договор</option>
<option value="1">Встреча с клиентом</option>
<option value="2">Опись объекта</option>
</select>

</div>
                            
                            <div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon alert-danger" >Комментарий агенту:</span>
								<textarea  class="form-control" rows="3" type="text" name="komma" value="<?php echo $person['komma']; ?>"></textarea>

								</div>
<div style="margin-bottom: 6px;"></div>
</div>



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
								<div class="col-md-6 ">
								<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Объект:</h4></strong>

								<div class="input-group">
								<span class="input-group-addon">Операция:</span>
								<select style="margin-bottom: 0px;" class="col-md-12 form-control" type="text" name="operac"  />
								<option style="background: #ccc;" value="<?php echo $person['operac']; ?>"></option>
								<option value="1">Аренда</option>
								  <option value="2">Купля</option>
								  <option value="3">Продажа</option>
								  <option value="4">Арендовать</option>
								</select>
								</div>


								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Тип недвижимости:</span>
								<select style="margin-bottom: 0px;" class="col-md-12 form-control" type="text" name="tnedv"  />
								  <option style="background: #ccc;" value="<?php echo $person['tnedv']; ?>"></option>
								  <option value="1">Комната</option>
								  <option value="2">Квартира</option>
								  <option value="3">Новостройка</option>
								  <option value="4">Коммерческая</option>
								  <option value="5">Таунхаус</option>
								  <option value="6">Дом</option>
								  <option value="7">Дача</option>
								  <option value="8">Коттедж</option>
								  <option value="9">Земельный участок</option>
								  <option value="10">Земля (коммерческая)</option>
								  <option value="11">Гараж</option>
								  <option value="12">Стоянка</option>
								</select>
								</div>


								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Комисия:</span>
								<input class="col-md-12 form-control" type="text" name="komis" value="<?php echo $person['komis']; ?>"   />
								<span class="input-group-addon">%</span>
								</div>

								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Кол. комнат:</span>
								<input class="col-md-12 form-control" type="text" name="kkomn" value="<?php echo $person['kkomn']; ?>"   />
								</div>

								<div style="margin-top: 6px;"></div>
								<div class="input-group">

								<span class="input-group-addon">Сумма сделки:</span>
								<script type="text/javascript">
								function proverka(input) {
									var value = input.value;
									var rep = /[-\.;":'a-zA-Zа-яА-Я ]/;
									if (rep.test(value)) {
										value = value.replace(rep, '');
										input.value = value;
									}
									
									
								}
								</script>
								<input class="col-md-12 form-control" type="text" onkeyup="return proverka(this);" name="ssdelk" value="<?php echo $person['ssdelk']; ?>"   />
								<span class="input-group-addon">Руб.</span>
								</div>

								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Более 3 лет в собственности:</span>
								<select class="col-md-12 form-control" type="text" name="b3l"  />
								  <option  value="<?php echo $person['b3l']; ?>" style="background: #ccc;"></option>
								  <option value="1">Да</option>
								  <option value="0">Нет</option>
								</select>
								</div>

								<div style="margin-top: 6px;"></div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Ключи от объекта:</span>
								<select class="col-md-12 form-control" type="text" name="koo"  />
								  <option value="<?php echo $person['koo']; ?>" style="background: #ccc;"></option>
								  <option value="1">У нас</option>
								  <option value="2">У контрагента</option>
								  <option value="3">У собственника</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Когда возможен показ:</span>
								<input class="col-md-12 form-control" type="text" name="kvp" value="<?php echo $person['kvp']; ?>"   />
								</div>


								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Комментарий:</span>
								<textarea class="form-control" rows="3" type="text" name="komm2" value="<?php echo $person['komm2']; ?>"></textarea>
								</div>



								</div>



								<div class="col-md-6">
						<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Расположения:</h4></strong>

								<div class="input-group">
								<span class="input-group-addon">Область:</span>
								<input class="col-md-12 form-control" type="text" name="oblast" value="<?php echo $person['oblast']; ?>"   />
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Район области:</span>
								<input class="col-md-12 form-control" type="text" name="roblast" value="<?php echo $person['roblast']; ?>"   />
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Нас. пункт:</span>
								<input class="col-md-12 form-control" type="text" name="npunkt" value="<?php echo $person['npunkt']; ?>"   />
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Район города:</span>
								<input class="col-md-12 form-control" type="text" name="rgoroda" value="<?php echo $person['rgoroda']; ?>"   />
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Удаленнность от гор.:</span>
								<input class="col-md-12 form-control" type="text" name="uog" value="<?php echo $person['uog']; ?>"   />
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Улица:</span>
								<input class="col-md-12 form-control" type="text" name="ulica" value="<?php echo $person['ulica']; ?>"   />
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Дом №:</span>
								<input class="col-md-12 form-control" type="text" name="dom" value="<?php echo $person['dom']; ?>"   />
								<span class="input-group-addon">Дробь:</span>
								<input class="col-md-12 form-control" type="text" name="drob" value="<?php echo $person['drob']; ?>"   />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Корп.:</span>
								<input class="col-md-12 form-control" type="text" name="korpus" value="<?php echo $person['korpus']; ?>"   />
								<span class="input-group-addon">Стр.:</span>
								<input class="col-md-12 form-control" type="text" name="stroe" value="<?php echo $person['stroe']; ?>"   />
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Кв.:</span>
								<input class="col-md-12 form-control" type="text" name="kvartir" value="<?php echo $person['kvartir']; ?>"   />
								<span class="input-group-addon">Подъезд:</span>
								<input class="col-md-12 form-control" type="text" name="podez" value="<?php echo $person['podez']; ?>"   />
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Этаж:</span>
								<input class="col-md-12 form-control" type="text" name="ataj" value="<?php echo $person['ataj']; ?>"   />
								<span class="input-group-addon">Этажность:</span>
								<input class="col-md-12 form-control" type="text" name="atajn" value="<?php echo $person['atajn']; ?>"   />
								</div>





								</div>


								<div class="col-md-12">
						<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Площадь:</h4></strong>
						</div>
						<div class="col-md-5">
								<div class="input-group">
								<span class="input-group-addon">Общая:</span>
								<input class="col-md-12 form-control" type="text" name="opl" value="<?php echo $person['opl']; ?>"   />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Кухня:</span>
								<input class="col-md-12 form-control" type="text" name="kpl" value="<?php echo $person['kpl']; ?>"   />
								</div>
						</div>
						<div class="col-md-5">
								<div class="input-group">
								<span class="input-group-addon">Жилая:</span>
								<input class="col-md-12 form-control" type="text" name="jpl" value="<?php echo $person['jpl']; ?>"   />
								</div><div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">По комнатам:</span>
								<input class="col-md-12 form-control" type="text" name="pkpl" value="<?php echo $person['pkpl']; ?>"   />
								</div>
						</div>







								<div class="col-md-5">
						<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Параметры:</h4></strong>
						<div class="input-group">
								<span class="input-group-addon">Тип:</span>
								<select  class="col-md-12 form-control" type="text" name="tippor" />
								<option value="<?php echo $person['tippor']; ?>" style="background: #ccc;"><?php echo $person['tippor']; ?></option>
								  <option value="1">Элитная</option>
								  <option value="2">Апартаменты</option>
								  <option value="3">Пентхаус</option>
								  <option value="4">Общежитие</option>
								  <option value="5">Малосемейка</option>
								  <option value="6">Хрущевка</option>
								  <option value="7">Сталинка</option>
								  <option value="8">Жилое помещение</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Материал стен:</span>
								<select  class="col-md-12 form-control" type="text" name="msp"   />
								  <option value="<?php echo $person['msp']; ?>" style="background: #ccc;"><?php echo $person['msp']; ?></option>
								  <option value="1">Кирпич</option>
								  <option value="2">Железобетон</option>
								  <option value="3">Панель</option>
								  <option value="4">Монолит</option>
								  <option value="5">Монолит-кирпич</option>
								  <option value="6">Брус</option>
								  <option value="7">Дерево</option>
								  <option value="8">Блок</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Серия дома:</span>
								<select  class="col-md-12 form-control" type="text" name="sdp" />
								  <option value="<?php echo $person['sdp']; ?>"  style="background: #ccc;"><?php echo $person['sdp']; ?></option>
								  <option value="1">И-155</option>
								  <option value="2">Индивид проект</option>
								  <option value="3">КОПЭ</option>
								   <option value="4">КОПЭ-М-ПАРУС</option>
								   <option value="5">П-3М</option>
								   <option value="6">С-222</option>
								   <option value="7">П-46М</option>
								   <option value="8">ГМС-1</option>
								   <option value="9">П-44Т</option>
								   <option value="10">П-44ТМ</option>
								   <option value="11">П-44К</option>
								   <option value="12">ГМС-3</option>
								   <option value="13">И-1723</option>
								   <option value="14">ИП-46С</option>
								   <option value="15">ГМС-2001</option>
								   <option value="16">П-111</option>
								   <option value="17">П-55М</option>
								   <option value="18">П-30</option>
								   <option value="19">ТМ-25</option>
								   <option value="20">Юбилейный</option>
								   <option value="21">ПД-4</option>
								   <option value="22">И-1724</option>
								   <option value="23">П-55</option>
								   <option value="24">РД-90</option>
								   <option value="25">II-49</option>
								   <option value="26">II-68</option>
								   <option value="27">1-515/1</option>
								   <option value="28">II-18</option>
								   <option value="29">П-111М</option>
								   <option value="30">П-111МО</option>
								   <option value="31">С-111М</option>
								   <option value="32">П-46</option>
								   <option value="33">П-44М</option>
								   <option value="34">П-44</option>
								   <option value="35">П-3</option>
								   <option value="36">И-1279</option>
								   <option value="37">Бекерон</option>
								   <option value="38">Призма</option>
								   <option value="39">Колос</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Высота потолка:</span>
								<input class="col-md-12 form-control" type="text" name="vpp" value="<?php echo $person['vpp']; ?>"   />
								</div>

								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Год постойки:</span>
								<input class="col-md-12 form-control" type="text" name="gpp" value="<?php echo $person['gpp']; ?>"   />
								<span class="input-group-addon">Квартал:</span>
								<input class="col-md-12 form-control" type="text" name="kpar" value="<?php echo $person['kpar']; ?>"   />
								</div>

								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Ремонт:</span>
								<select  class="col-md-12 form-control" type="text" name="remont" />
								  <option  value="<?php echo $person['remont']; ?>"  style="background: #ccc;"><?php echo $person['remont']; ?></option>
								   <option value="1">Косметический</option>
								   <option value="2">Евростандарт</option>
								   <option value="3">Под отделки</option>
								   <option value="4">Авторский</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Окна:</span>
								<select  class="col-md-12 form-control" type="text" name="okna"  />
								  <option value="<?php echo $person['okna']; ?>" style="background: #ccc;"><?php echo $person['okna']; ?></option>
								   <option value="1">Деревянные</option>
								   <option value="2">Пластиковые</option>
								   <option value="3">Евроокна</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Вид из окна:</span>
								<select  class="col-md-12 form-control" type="text" name="vio"  />
								  <option value="<?php echo $person['vio']; ?>" style="background: #ccc;"><?php echo $person['vio']; ?></option>
								   <option value="1">На улицу</option>
								   <option value="2">Во двор</option>
								   <option value="3">На разные стороны</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">С/У:</span>
								<select  class="col-md-12 form-control" type="text" name="su"  />
								  <option value="<?php echo $person['su']; ?>" style="background: #ccc;"><?php echo $person['su']; ?></option>
								   <option value="1">Совместнный</option>
								   <option value="2">Раздельный</option>
								   <option value="3">2</option>
								   <option value="4">3</option>
								   <option value="5">4</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Л/Б:</span>
								<select  class="col-md-12 form-control" type="text" name="lb"   />
								  <option value="<?php echo $person['lb']; ?>" style="background: #ccc;"><?php echo $person['lb']; ?></option>
								   <option value="1">Балкон</option>
								   <option value="2">Балкон застекленный</option>
								   <option value="3">Балкон и Лоджия</option>
								   <option value="4">Балкон и Лоджия застеклены</option>
								   <option value="5">Лоджия</option>
								   <option value="6">Лоджия застеклены</option>
								</select>
								</div>
								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Пол:</span>
								<input class="col-md-12 form-control" type="text" name="pol" value="<?php echo $person['pol']; ?>"   />
								</div>

								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Двери:</span>
								<input class="col-md-12 form-control" type="text" name="dvery" value="<?php echo $person['dvery']; ?>"   />
								</div>

								<div style="margin-top: 6px;"></div>
								<div class="input-group">
								<span class="input-group-addon">Батарея:</span>
								<input class="col-md-12 form-control" type="text" name="batar" value="<?php echo $person['batar']; ?>"   />
								</div>


								</div>
								<div class="col-md-5">
						<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Дополнительно:</h4></strong>

								<div class="checkbox">
								  <label>
									<input type="checkbox" name="ugkv" value="1">
									Угловая квартира
								  </label><br>
								  <label>
									<input type="checkbox" name="spka" value="1">
									Спутниковое/Кабельное ТВ
								  </label><br>
								  <label>
									<input type="checkbox" name="ohte" value="1">
									Охраняемая территория
								  </label><br>
								  <label>
									<input type="checkbox" name="ogte" value="1">
									Огороженная территория
								  </label><br>
								  <label>
									<input type="checkbox" name="muod" value="1">
									Мусоропровод
								  </label><br>
								  <label>
									<input type="checkbox" name="gas" value="1">
									Газ
								  </label><br>
								  <label>
									<input type="checkbox" name="avot" value="1">
									Автономное отопление
								  </label><br>
								  <label>
									<input type="checkbox" name="dolu" value="1">
									Доступно людям с физ недостаткам
								  </label><br>
								  <label>
									<input type="checkbox" name="inet" value="1">
									Интернет
								  </label><br>
								  <label>
									<input type="checkbox" name="teon" value="1">
									Телефон
								  </label><br>
								  <label>
									<input type="checkbox" name="lift" value="1">
									Лифт
								  </label><br>
								  <label>
									<input type="checkbox" name="grli" value="1">
									Грузовой лифт
								  </label><br>
								  <label>
									<input type="checkbox" name="vaer" value="1">
									Вахтер
								  </label>

								</div>




								</div>







									<div class="col-md-12">
										<div style="margin-top: 6px;"></div><input class="btn btn-success col-md-12" href="javascript: void(0);" onclick="$('#ord1frm .error').hide(); var name = $('#ord1frm input[name=first_name]').val(); var contact = $('#ord1frm input[name=email_telefon]').val(); if(contact!=''&&contact!='8 913-3218-2555 MAIL@MAIL.ru'){$('#ord1frm').submit();}else{$('#ord1frm .error').show();}" class="red_btn" type="submit" name="submit" value="Отправить" id="submitSuggestion" />
									</div><div style="margin-top: 6px;"></div>


														</form>



						</div>







						<div class="col-md-4">
<div class="col-md-12">
<input type="checkbox" id="raz" class="del"/><label for="raz" class="del">Скрипт исходящего звонка</label><div>


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
