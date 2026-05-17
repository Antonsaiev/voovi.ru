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
}?>
<!DOCTYPE html>
<html lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body ><?php
# шапка
include 'header.php';?>
<div class="container " style="margin-top: 60px;">
<div class="row">
<div class="col-md-12" style="margin-bottom: 15px;"><?php
$query = mysql_query("SELECT * from focus WHERE del = 0 AND a != 7 AND id='".$_GET['id']."' ORDER BY a");
while($row = mysql_fetch_array($query)) {

   echo '<a class="btn btn-default"  title="В избранные" href="./zayavstar.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-star"></span> В избранные</a>';echo '<a class="btn btn-default"  title="Новое действие" href="./zayaviz.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-earphone"></span> Новый звонок</a>';echo '<a class="btn btn-default"  title="Написать сообщение" href="./new_sms.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-envelope"></span> Написать сообщение</a>';echo '<a class="btn btn-default"  title="Новое напоминание" href="./napomni.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-time"></span> Новое напоминание</a>';echo '<a class="btn btn-default"  title="Редактировать" href="./openz.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-pencil"></span> Редактировать</a>';echo'
</div>

<div class="col-md-3">
<table class="table">
  <tr><td><strong>Организация:</strong> </td><td>'.$row['nameogrn'].'</td></tr>
  <tr><td><strong>ИНН:</strong> </td><td>';
  echo ' ',$row['inn'],' <a style="font-weight: bold; color: #28A125;" href="https://focus.kontur.ru/search?query=',$row['inn'],'" target="_blank">открыть в Контур Фокус</a>';
  echo'

  <tr><td><strong>ФИО:</strong> </td><td>'.$row['name'].'</td></tr>

  <tr><td><strong>Телефон:</strong> </td><td>'.$row['tel'].'</td></tr>

<tr><td><strong>Статус:</strong> </td><td>';
if ($row['a'] == 0) {
echo "Новая заявка";
} if ($row['a'] == 1) {
echo "Перезвонить";
} if ($row['a'] == 11) {
echo "Не дозвонились";
} if ($row['a'] == 2) {
echo "Предоставлено демо";
} if ($row['a'] == 3) {
echo "Принимает решение";
} if ($row['a'] == 4) {
echo "Ждем оплату";
} if ($row['a'] == 5) {
echo "Ждем акт";
} if ($row['a'] == 6) {
echo "Отгружено";
} if ($row['a'] == 7) {
echo "Отказ";
} if ($row['a'] == 8) {
echo "Пока нет";
} if ($row['a'] == 9) {
echo "Продлить";
}
echo'</td></tr><tr><td><strong>E-почта:</strong> </td><td>'.$row['email'];
echo'</td></tr><tr><td><strong>Комментарий:</strong> </td><td>'.$row['komm'];
if($userdata['users_id'] > 0){
echo'</td></tr><tr><td><strong>Откуда заявка: </strong> </td><td>';
if ($row['kto'] == 0){
echo 'Заявка с сайта';
} if ($row['kto'] > 0){
$query22 = mysql_query("SELECT * from users WHERE users_id = '".$row['kto']."'");
while($row22 = mysql_fetch_array($query22)) {
echo $row22['f_name']," ",$row22['l_name'];
}
}
}
echo '</td></tr>
<tr><td><strong>Дата получения заявки:</strong> </td><td>'.$row['data'].'</td></tr>
  <tr><td><strong>Тариф:</strong> </td><td>';
  if ($row['tip'] == 4) {
echo "Демо 48 часов";
} if ($row['tip'] == 1) {
echo "Стандарт";
} if ($row['tip'] == 2) {
echo "Бизнес";
} if ($row['tip'] == 3) {
echo "Премиум";
} if ($row['tip'] == 5) {
echo "Демо 24 часа";
}
}
?>
  </td></tr></table>
  </div>
  <div class="col-md-9 row">
  <div class="col-md-12">
<h4 style="border-bottom: 1px #333 solid;margin-top: 0;">Напоминания:</h4>
<table class="table">
<thead>
<tr>
<th>Дата/Время</th>
<th>Задача</th>
<th>Описание</th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-share"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-ok-circle"></span></th>
</tr>
</thead>
<?php

$query = mysql_query("SELECT * from napomin WHERE produrl = '".$_GET['id']."' AND yes = '0'  ORDER BY dmg");
while($row = mysql_fetch_array($query)) {
$dmg = $row['gr'].$row['mr'].$row['dr'];
$dmg2 = date("Ymd");
if ($dmg < $dmg2) {
echo '<tr id="'.$dmg.'/'.$dmg2.'" title="Задача не выполненна в срок" class="alert alert-danger" style="border-left: 5px #D50000 solid;"><td>';
} else { 
 echo '<tr id="'.$dmg.'/'.$dmg2.'"><td>';
}echo $row['dr'],".";
echo $row['mr'],".";
echo $row['gr'];
echo ' ';
echo $row['chr'],":";
echo $row['mir'];
echo '</td>';
echo '<td>';
if ($row['tipz'] == 'zv') {
echo "Звонок: ",$row['tel'];
} if ($row['tipz'] == 'vs') { 
echo "Выезд к клиенту: ";
  if ($row['gor'] == '1') { 
echo "Пятигорск";
} if ($row['gor'] == '2') { 
echo "Ставрополь";
} if ($row['gor'] == '3') { 
echo "Ессентуки";
} if ($row['gor'] == '4') { 
echo "Мин-Воды";
} if ($row['gor'] == '5') { 
echo "Юца";
} if ($row['gor'] == '6') { 
echo "Кисловодск";
} if ($row['gor'] == '7') { 
echo "ст. Ессентукская";
} if ($row['gor'] == '8') { 
echo "Иноземцево";
} if ($row['gor'] == '9') { 
echo "Лермонтов";
} if ($row['gor'] == '10') { 
echo "Георгиевск";
} if ($row['gor'] == '11') { 
echo "Железноводск";
} if ($row['gor'] == '12') { 
echo "Горячеводск";
} if ($row['gor'] == '13') { 
echo "Суворовская";
} if ($row['gor'] == '14') { 
echo "Черкеск";
} if ($row['gor'] == '15') { 
echo "КБР";
}
echo " ",$row['mestvs'];
} if ($row['tipz'] == 'vsv') { 
echo "Встреча в офисе";
}
echo '</td>';
echo '<td>';
echo $row['opis'];
echo '</td>';
echo '<td>';
echo '<a style="width: 1px;" title="Отложить" href="./otlojitnapomin.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-share"></span></a>';
echo '</td>';
echo '<td>';

if ($dmg < $dmg2) {
echo '<a style="color: #CE0101; width: 1px;" title="Выполненно" href="./eess.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-ok-circle"></span></a>';
} else {
echo '<a style="width: 1px;"  title="Выполненно" href="./eess.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-ok-circle"></span></a>';
}

echo '</td></tr>';}

?>
</table>
</div>

  <div class="col-md-12 ">
  <h4 style="border-bottom: 1px #333 solid;margin-top: 0;">Архив звонков:</h4>
  <table class="table">
  <?php
$querys2 = mysql_query("SELECT * from bignapomin WHERE zayav = '".$_GET['id']."' ORDER BY id DESC");
while($rows2 = mysql_fetch_array($querys2)) {echo'<tr>';
echo'<th style="min-width: 110px;">';
echo $rows2['kogda']; 
echo'</th>';
echo'<th style="min-width: 80px;">';
$q = "SELECT * FROM users WHERE users_id =".$rows2['kto'];
$result = mysql_query($q);
$person = mysql_fetch_array($result); 
$gen = $person['f_name'];
echo mb_substr($gen,0,1,'UTF-8');
echo '. ';
echo $person['l_name'];
echo'</th>';
echo'<th>';
echo $rows2['otchet']; 
echo'</th>';
echo'<th style="min-width: 120px;">';
echo $rows2['tip']; 
echo'</th>';
echo'</tr>';}
echo'</table>';

?>
</div>



</div>
</div>

<div class="col-md-12">
<?php
# подвал
include 'footer.php';  
?>
<br>
</div>

</div></div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>