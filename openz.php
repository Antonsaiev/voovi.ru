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
<link href="https://dadata.ru/static/css/lib/suggestions-4.7.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php
# шапка
include 'header.php';
?>
<div class="container" style="margin-top: 60px;">
<div class="row">
<?php
# левая колонка сайта
include 'left_sitebar.php';
?>

<div class="col-md-8">
<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Обновление данных:</h4></strong>
<div class="input-group" style="padding: 0 5px;">
<span class="input-group-addon"style="background: #fff;font-size: 20px;padding: 0 10px;"><span class="icon-search"></span></span>
<input class="form-control" id="party" name="party" value="<?php echo $person['inn']; ?>" type="text" style="box-sizing: border-box; padding-left: 7px; padding-right: 7px;height: 36px;border-color: #ccc;border-bottom-right-radius: 4px;border-top-right-radius: 4px;font-size: 18px;"> 
</div><div style="margin-top: 6px;"></div>
<form method="POST"><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Дата:</span>
<input class="form-control" type="text" name="data" value="<?php echo $person['data']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">ФИО:</span>
<input class="form-control" type="text" name="name" value="<?php echo $person['name']; ?>"  />
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Телефон:</span>
<input class="form-control onlynumbers" type="text" name="tel" value="<?php echo $person['tel']; ?>"  required>
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ИНН:</span>
<input class="form-control" name="inn" type="text" value="<?php echo $person['inn']; ?>"  />
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Название организации:</span>
<input class="form-control" name="nameogrn"  id="nameogrn" type="text" value='<?php echo $person['nameogrn']; ?>'  />
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ФИО руководителя:</span>
<input class="form-control" name="fioruk" type="text" id="fioruk" value="<?php echo $person['fioruk']; ?>"> 
</div>
<div style="margin-top: 6px;"></div>

<div class="input-group">
<span class="input-group-addon">Юридический адрес:</span>
<input id="bik" class="form-control" name="uridadress" type="text" id="uridadress" value="<?php echo $person['uridadress']; ?>"> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Фактический адрес:</span>
<input id="city" class="form-control" name="fakadress" type="text" id="fakadress" value="<?php echo $person['fakadress']; ?>"> 
</div>
<div style="margin-top: 6px;"></div>



<div class="input-group">
<span class="input-group-addon ">Email:</span>
<input class="form-control" type="text" name="email" value="<?php echo $person['email']; ?>"  />
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Статус:</span>
 <select class="form-control" type="text" name="a" />
<option style="background: #ccc;" value="<?php echo $person['a']; ?>">
<?php 
if ($person['a'] == 0) {
echo "Новая заявка";
} if ($person['a'] == 1) {
echo "Перезвонить";
} if ($person['a'] == 11) {
echo "Не дозвонились";
} if ($person['a'] == 2) {
echo "Предоставлено демо";
} if ($person['a'] == 3) {
echo "Принимает решение";
} if ($person['a'] == 4) {
echo "Ждем оплату";
} if ($person['a'] == 5) {
echo "Ждем акт";
} if ($person['a'] == 6) {
echo "Отгруженно";
} if ($person['a'] == 7) {
echo "Отказ";
} if ($person['a'] == 8) {
echo "Пока нет";
} if ($person['a'] == 9) {
echo "Продлить";
}
?>
</option>
<option value="0">Новая заявка</option>
<option value="1">Перезвонить</option>
<option value="11">Не дозвонились</option>
<option value="2">Предоставлено демо</option>
<option value="3">Принимает решение</option>
<option value="4">Ждем оплату</option>
<option value="5">Ждем акт</option>
<option value="6">Отгруженно</option>
<option value="7">Отказ</option>
<option value="8">Пока нет</option>
<option value="9">Продлить</option>
</select>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Регион:</span>
 <select class="form-control" type="text" name="reg" />
<option style="background: #ccc;" value="<?php echo $person['reg']; ?>">
<?php 
$qwe = "SELECT * FROM oblast WHERE id =".$person['reg'];
$resultwe = mysql_query($qwe);
$personwe = mysql_fetch_array($resultwe);
$i = date("i") + $personwe['min'];
$H = date("H") + $personwe['ch'];
$ii = $i - 60;
if ($i >= 60) {
echo $personwe['name'],".  Время: ",$H + 1,":";
if ($ii == 1) {
echo "01";
} if ($ii == 2) {
echo "02";
} if ($ii == 3) {
echo "03";
} if ($ii == 4) {
echo "04";
} if ($ii == 5) {
echo "05";
} if ($ii == 6) {
echo "06";
} if ($ii == 7) {
echo "07";
} if ($ii == 8) {
echo "08";
} if ($ii == 9) {
echo "09";
} if ($ii == 0) {
echo "00";
}  if ($ii > 9) {
echo $i - 60;
}
} else {
echo $personwe['name'],".  Время: ",$H,":",$i;
}
?>
</option>
<?php 
$queryasd = mysql_query("SELECT * from oblast ORDER BY name");
while($rowsa = mysql_fetch_array($queryasd)) {
echo '<option value="'.$rowsa['id'].'">'.$rowsa['name'].'</option>';
}
?>
</select>
</div><div style="margin-top: 6px;"></div>
<div class="input-group" style="margin-bottom: 6px;"><span class="input-group-addon alert-danger">Продукт:</span>
<select name="produkt" class="form-control col-md-12">
<option value="6">Фокус</option>
<option value="17">Торги</option>
<option value="5">Экстерн</option>
<option value="10">Персональные данные</option>
<option value="9">Закупки</option>
</select>
</div>
<div class="input-group">
<span class="input-group-addon alert-danger">Тариф:</span>
 <select class="form-control" type="text" name="tip" />
<option style="background: #ccc;" value="<?php echo $person['tip']; ?>"><?php 
if ($person['tip'] == 4) {
echo "Демо 48 часов";
} if ($person['tip'] == 1) {
echo "Стандарт";
} if ($person['tip'] == 2) {
echo "Бизнес";
} if ($person['tip'] == 3) {
echo "Премиум";
} if ($person['tip'] == 5) {
echo "Демо (Самостоятельные 24 часа)";
}
?></option>
<option value="1">Стандарт</option>
<option value="2">Бизнес</option>
<option value="3">Премиум</option>
<option value="4">Демо 48 часов</option>
<option value="5">Демо (Самостоятельные 24 часа)</option>
</select>
</div><div style="margin-top: 6px;"></div>
<textarea name="komm" value="<?php echo $person['komm']; ?>" class="form-control" rows="5"><?php  echo $person['komm']; ?></textarea>
<div style="margin-top: 6px;"></div>
<input type="submit" name="submit" value="Сохранить" id="submitSuggestion" class="btn btn-success" style="float: right;"/><br>
</form>
<?php
if(isset($_POST['submit'])){
$u = "UPDATE focus SET 
`data`='$_POST[data]',
`name`='$_POST[name]',
`tel`='$_POST[tel]',
`inn`='$_POST[inn]',
`email`='$_POST[email]',
`a`='$_POST[a]',
`reg`='$_POST[reg]',
`tip`='$_POST[tip]',
`nameogrn`='$_POST[nameogrn]',
`fioruk`='$_POST[fioruk]',
`uridadress`='$_POST[uridadress]',
`fakadress`='$_POST[fakadress]',
`komm`='$_POST[komm]'
WHERE id = $_GET[id]";
$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.t.Y; H:i:s") ."','Изменения в `Заказ с сайта`','".$userdata['users_id']."')";
mysql_query($aktivn) or die(mysql_error($link));
mysql_query($u) or die(mysql_error($link));
echo '<div class="alert alert-success"><strong>Удачно!</strong> Обновления успешно сохранены.</div>';
echo '<script type="text/javascript">'; 
echo 'window.location.href="./zayav.php";'; 
echo '</script>'; 
}
?>
</div>
<div class="col-md-4">
<?php 

require('DadataClient.php');
use Dadata\DadataClient as DadataClient;

$url = 'https://dadata.ru/api/v2/clean';
$token = '136cfc9e5c3737d7c68bbba1867e6de1af8f8b90';
$secret = '23653495be7be652df361473c73d18532ebd6b68';
$dadata = new DadataClient($url, $token, $secret);

$data = '{ "structure": [ "PHONE" ], "data": [ [ "'.$person['tel'].'" ] ] }';


echo "Мое:\n" . $dadata->clean($data) . "\n";

?>
</div>
<link href="https://dadata.ru/static/css/lib/suggestions-4.8.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!--[if lt IE 10]>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.1/jquery.xdomainrequest.min.js"></script>
<![endif]-->
<script type="text/javascript" src="https://dadata.ru/static/js/lib/jquery.suggestions-4.8.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
$("input[id='bik']").keyup(function(){
$.ajax({
dataType: 'json',
url: 'https://dadata.ru/api/v2/detectAddressByIp?token=ef58966fecde08a48042dd431b6213c7e4d7294f&ip='+$("input[id='bik']").val(),
success: function(jsondata){
$('#city').val(jsondata.location.data.city.replace(/&quot;/g,'"'));
}
});
});
});


$("#party").suggestions({
serviceUrl: "https://dadata.ru/api/v2",
token: "136cfc9e5c3737d7c68bbba1867e6de1af8f8b90",
type: "PARTY",
count: 5,
onSelect: showSelected
});

function join(arr /*, separator */) {
var separator = arguments.length > 1 ? arguments[1] : ", ";
return arr.filter(function(n){return n}).join(separator);
}
function showSelected(suggestion) {
var party = suggestion.data;
 
$("#nameogrn").val(
join([suggestion.value], " ")
);

$("#uridadress").val(
join([party.address && party.address.value || ""], " ")
);

$("#fioruk").val(
join([party.management.name || ""], " ")
);

}
	
	
$(function(){

var obj = $('.onlynumbers');
obj.bind('keyup', function(){
this.value = this.value.replace (/[A-Za-z - ) ( + -]/, '');

});
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</div>
</body>
</html>