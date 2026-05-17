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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-8 ">
<?php
if(isset($_POST['submit'])){
$u = "INSERT INTO `napomin` (`dr`, `mr`, `gr`, `dmg`, `gor`, `chr`, `mir`, `tipz`, `tel`, `mestvs`, `opis`, `users`) VALUES ('$_POST[dr]', '$_POST[mr]', '$_POST[gr]', '$_POST[gr]$_POST[mr]$_POST[dr]', '$_POST[gor]', '$_POST[chr]', '$_POST[mir]', '$_POST[tipz]', '$_POST[tel]', '$_POST[mestvs]', '$_POST[opis]', '$_POST[users]')";
$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.t.Y; H:i:s") ."','Новая задача','".$userdata['users_id']."')";
mysql_query($aktivn) or die(mysql_error($link));
mysql_query($u) or die(mysql_error($link));
echo '<div class="alert alert-success">
  <strong>Удачно!</strong> Новая задача успешно добавлена.
</div>';

$body=file_get_contents("http://sms.ru/sms/send?api_id=513439c3-5ece-a954-e5b2-31b36fe77cbf&to=79097565645&text=".urlencode(iconv("utf-8","utf-8","Новая задача: Манаджар: ".$userdata['users_id']."")));
		echo '<script> 
		window.location.href="'.$_SERVER['REQUEST_URI'].'";
		</script>';
}
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<strong><h4 style="margin-top: 0px; font-weight: bold; border-bottom: 1px #333 solid;">Новая задача для:
<select type="text" name="users" style="font-size: 15px;" />
<option value="<?php echo $userdata['users_id']; ?>"><?php echo $userdata['f_name']," ",$userdata['l_name']; ?></option>
<?php
$querysas = mysql_query("SELECT * from users WHERE users_id != ".$userdata['users_id']);
while($rowsas = mysql_fetch_array($querysas)) {
echo "<option value='".$rowsas['users_id']."'>";
echo $rowsas['f_name']," ",$rowsas['l_name'];
echo "</option>";
}?>

</select></h4></strong>
<div style="margin-top: 6px;"></div>
<div style="
    background: rgba(51, 51, 51, 0.11);
    width: 226px;
    height: 18px;
    position: absolute;
"></div>
<fieldset style="
    float: left;
    margin-right: 10px;
" >
<span>Дата:</span>
<select id="disabledSelect" style="margin-bottom: 0px;"  type="text" name="dr"  />
<option value="<?php echo $_GET['day']; ?>"><?php echo $_GET['day']; ?></option>
<option>01</option>
<option>02</option>
<option>03</option>
<option>04</option>
<option>05</option>
<option>06</option>
<option>07</option>
<option>08</option>
<option>09</option>
<?php 
$a = 10;
$b = date("t");
while($a <= $b) echo '<option>',$a++,'</option>';
?>
</select>
<span >:</span>
<select id="disabledSelect" style="margin-bottom: 0px;"  type="text" name="mr"  />
<option value="<?php echo $_GET['m']; ?>">
<?php 
if ($_GET['m'] == "01") {
echo "Январь"; 
} if ($_GET['m'] == "02") {
echo "Февраль"; 
} if ($_GET['m'] == "03") {
echo "Март"; 
} if ($_GET['m'] == "04") {
echo "Апрель"; 
} if ($_GET['m'] == "05") {
echo "Май"; 
} if ($_GET['m'] == "06") {
echo "Июнь"; 
} if ($_GET['m'] == "07") {
echo "Июль"; 
} if ($_GET['m'] == "08") {
echo "Август"; 
} if ($_GET['m'] == "09") {
echo "Сентябрь"; 
} if ($_GET['m'] == "10") {
echo "Октябрь"; 
} if ($_GET['m'] == "11") {
echo "Ноябрь"; 
} if ($_GET['m'] == "12") {
echo "Декабрь"; 
}
?>
</option>
  <option value="01">Январь</option>
  <option value="02">Февраль</option>
  <option value="03">Март</option>
  <option value="04">Апрель</option>
  <option value="05">Май</option>
  <option value="06">Июнь</option>
  <option value="07">Июль</option>
  <option value="08">Август</option>
  <option value="09">Сентябрь</option>
  <option value="10">Октябрь</option>
  <option value="11">Ноябрь</option>
  <option value="12">Декабрь</option>
</select><span >:</span>
<select id="disabledSelect" style="margin-bottom: 0px;" type="text" name="gr"  />
  <option value="<?php echo $_GET['yers']; ?>"><?php echo $_GET['yers']; ?></option>
  <option value="2015">2015</option>
  <option value="2016">2016</option>
  <option value="2017">2017</option>
  <option value="2018">2018</option>
  <option value="2019">2019</option>
  <option value="2020">2020</option>
  <option value="2021">2021</option>
  <option value="2022">2022</option>
  <option value="2023">2023</option>
</select></fieldset><span >Время:</span>
<select style="margin-bottom: 0px;"  type="text" name="chr"  />
<option value="<?php echo date("H"); ?>"><?php echo date("H"); ?></option>
  <option value="01">01</option>
  <option value="02">02</option>
  <option value="03">03</option>
  <option value="04">04</option>
  <option value="05">05</option>
  <option value="06">06</option>
  <option value="07">07</option>
  <option value="08">08</option>
  <option value="09">09</option>
  <option value="10">10</option>
  <option value="11">11</option>
  <option value="12">12</option>
  <option value="13">13</option>
  <option value="14">14</option>
  <option value="15">15</option>
  <option value="16">16</option>
  <option value="17">17</option>
  <option value="18">18</option>
  <option value="19">19</option>
  <option value="20">20</option>
  <option value="21">21</option>
  <option value="22">22</option>
  <option value="23">23</option>
  <option value="24">24</option>
</select><span>:</span>
<select style="margin-bottom: 0px;" type="text" name="mir"  />
<option value="<?php echo date("i"); ?>"><?php echo date("i"); ?></option>
<option value="00">00</option>
<option value="05">05</option>
<option value="10">10</option>
  <option value="15">15</option>
  <option value="20">20</option>
  <option value="25">25</option>
  <option value="30">30</option>
  <option value="35">35</option>
  <option value="40">40</option>
  <option value="45">45</option>
  <option value="50">50</option>
  <option value="55">55</option>
</select>
<br><div style="margin-top: 6px;"></div><span>Тип задачи:</span>
<select type="text" name="tipz" id="otherFieldOption" />
<option></option>
<option value="zv">Звонок</option>
  <option value="vs">Выезд к клиенту</option>
<option value="vsv">Встреча в офисе</option>
</select>
<script>
$(document).ready(function() {
  $.viewInput = {
'0' : $([]),
//Это имя DIV вокруг невидимого поля
'zv' : $('#zv'),
'vs' : $('#vs'),
  };$('#otherFieldOption').change(function() {
// Прячет это поле, если выбрана другая опция
$.each($.viewInput, function() { this.hide(); });
// Показывает поле при выборе необходимой опции
$.viewInput[$(this).val()].show();
  });});
</script><div id="zv" style="display:none;">
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Телефон:</span>
<input class="col-md-12 form-control" type="text" name="tel" value="<?php echo $person['tel']; ?>"  >
</div><div style="margin-top: 6px;"></div>
</div>
<div id="vs" style="display:none;">
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Город:</span>
<select style="margin-bottom: 0px;" class="col-md-12 form-control" type="gor" name="gor"  />
<option value="1">Пятигорск</option>
<option value="2">Ставрополь</option>
<option value="3">Ессентуки</option>
<option value="4">Мин-Воды</option>
<option value="5">Юца</option>
<option value="6">Кисловодск</option>
<option value="7">ст. Ессентукская</option>
<option value="8">Иноземцево</option>
<option value="9">Лермонтов</option>
<option value="10">Георгиевск</option>
<option value="11">Железноводск</option>
<option value="12">Горячеводск</option>
<option value="13">Суворовская</option>
<option value="14">Черкеск</option>
<option value="15">КБР</option>
</select>
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Адрес:</span>
<input class="col-md-12 form-control" type="text" name="mestvs" value="<?php echo $person['mestvs']; ?>"  >
</div><div style="margin-top: 6px;"></div>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Описание:</span>
 <textarea name="opis" value="<?php echo $person['opis']; ?>" class="form-control" rows="3"></textarea>
</div>
<br>
<input type="submit" name="submit" value="Добавить" id="submitSuggestion" class="btn btn-success" /><br>
</form><br>
<br>
<div class="bs-example bs-example-tabs">
<ul id="myTab" class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#yesterday" role="tab" data-toggle="tab">Задачи на <?php echo $_GET['day']." ";?><?php 
if ($_GET['m'] == "01") {
echo "Январь"; 
} if ($_GET['m'] == "02") {
echo "Февраль"; 
} if ($_GET['m'] == "03") {
echo "Март"; 
} if ($_GET['m'] == "04") {
echo "Апрель"; 
} if ($_GET['m'] == "05") {
echo "Май"; 
} if ($_GET['m'] == "06") {
echo "Июнь"; 
} if ($_GET['m'] == "07") {
echo "Июль"; 
} if ($_GET['m'] == "08") {
echo "Август"; 
} if ($_GET['m'] == "09") {
echo "Сентябрь"; 
} if ($_GET['m'] == "10") {
echo "Октябрь"; 
} if ($_GET['m'] == "11") {
echo "Ноябрь"; 
} if ($_GET['m'] == "12") {
echo "Декабрь"; 
}
?><?php echo " ".$_GET['yers']; ?></a></li>
  <li><a href="#home" role="tab" data-toggle="tab">Все задачи</a></li>
  <li><a href="#profile" role="tab" data-toggle="tab">Общие задачи</a></li>
</ul>
<div id="myTabContent" class="tab-content">
<div class="tab-pane fade active in" id="yesterday">
<table class="table" style="
border: 2px solid #8BC08B;
">
<thead>
<tr>
<th>Дата/Время</th>
<th>Задача</th>
<th>Описание</th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-share"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-folder-close"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-ok-circle"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT * from napomin WHERE yes = '0'  AND dmg = '".$_GET['id']."'  AND users = '".$userdata['users_id']."' ORDER BY dmg");
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
echo '<a style="width: 1px;" title="Сделать общим" href="./eessobsh.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-eye-open"></span></a>';
echo '</td>';echo '<td>';
if($row['produrl'] > 0){
echo '<a style="width: 1px;" title="Открыть" href=".'.$row['tip'].$row['produrl'].'"><span class="glyphicon glyphicon-folder-open"></span></a>';
}else{
echo '<span class="glyphicon glyphicon-folder-close"></span>';
}
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
<div class="tab-pane fade" id="home">
<table class="table" style="
border: 2px solid #8BC08B;
">
<thead>
<tr>
<th>Дата/Время</th>
<th>Задача</th>
<th>Описание</th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-share"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-folder-close"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-ok-circle"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT * from napomin WHERE yes = '0' AND users = '".$userdata['users_id']."' ORDER BY dmg");
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
echo '<a style="width: 1px;" title="Сделать общим" href="./eessobsh.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-eye-open"></span></a>';
echo '</td>';echo '<td>';
if($row['produrl'] > 0){
echo '<a style="width: 1px;" title="Открыть" href=".'.$row['tip'].$row['produrl'].'"><span class="glyphicon glyphicon-folder-open"></span></a>';
}else{
echo '<span class="glyphicon glyphicon-folder-close"></span>';
}
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
<div class="tab-pane fade" id="profile">
<table class="table" style="
border: 2px solid #8BC08B;
">
<thead>
<tr>
<th>Дата/Время</th>
<th>Задача</th>
<th>Описание</th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-share"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-folder-close"></span></th>
<th style="width: 1px;" ><span class="glyphicon glyphicon-ok-circle"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT * from napomin WHERE yes = '0' AND obsh = '1' ORDER BY dmg");
while($row = mysql_fetch_array($query)) {
$dmg = $row['gr'].$row['mr'].$row['dr'];
$dmg2 = date("Ymd");
if ($dmg < $dmg2) {
echo '<tr title="Задача не выполненна в срок" class="alert alert-danger" style="border-left: 5px #D50000 solid;"><td>';
} else { 
 echo '<tr><td>';
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
echo '<a style="width: 1px;"  title="Отложить" href="./otlojitnapomin.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-share"></span></a>';
echo '</td>';echo '<td>';
if($row['produrl'] > 0){
echo '<a style="width: 1px;" title="Открыть" href=".'.$row['tip'].$row['produrl'].'"><span class="glyphicon glyphicon-folder-open"></span></a>';
}else{
echo '<span class="glyphicon glyphicon-folder-close"></span>';
}
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
</div>       
  </div>
</div>
<div class="col-md-4">
<?php
# подключаем колендарь
include 'kalendar.php';
?>
</div>


<div class="col-md-12">
<?php
# подвал
include 'footer.php';  
?>
<br>
</div>

</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
