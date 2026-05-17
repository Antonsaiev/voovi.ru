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

<script>
var auto_refresh = setInterval(
function(){
$('#load_div').fadeOut('slow').load().fadeIn("slow");
}, 2000);
</script>
</head>
<body >
<?php
# шапка
include 'header.php';?>
<div class="container " style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">

<div style="">
<span>
<input type="checkbox" id="remember" name="remember"  onclick="validate()" >Показать избранные
</span>
<a href="new_zayav.php"><input type="submit" name="submit" value="Новая заявка на Контур-Фокус" id="submitSuggestion" class="btn btn-info btn-xs"/></a>
</div>

<br>
<br><script language="javascript">
function validate(){
if (remember.checked == 1){
document.getElementById("izbran").style.display="none";
document.getElementById("izbranie").style.display="";
}else{
document.getElementById("izbran").style.display="";
document.getElementById("izbranie").style.display="none";
}
}
</script>
<div id="izbran">
<table class="table" style="
    font-size: 11px;
">
<thead>
<tr>
<th  style="text-align: center;width: 120px;">Дата</th>
<th  style="text-align: center;width: 60px;">Продукт</th>
<th  style="text-align: center;">Организация</th>
<th style="text-align: center; width: 100px;">ИНН</th>
<th  style="text-align: center; width: 250px;">ФИО</th>
<th style="text-align: center;">Телефон</th>
<th style="text-align: center; width: 100px;">Откуда заявка</th>
<th style="text-align: center;">Статус</th>
<th style="text-align: center;"><span class="glyphicon glyphicon-folder-open"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-star"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-earphone"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-envelope"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-time"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-pencil"></span></th>
<?php
if($userdata['tip'] == 4 ){
echo '<th style="text-align: center;"><span class="glyphicon glyphicon-trash"></span></th>';
}
?>
</tr>
</thead>
<?php
include 'conf.php';

$num = 100;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) from focus WHERE del = 0");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						


$query = mysql_query("SELECT * from focus WHERE del = 0  ORDER BY id DESC LIMIT $num");
while($row = mysql_fetch_array($query)) {
echo '<tr id="open'.$row['id'].'" title="'.$row['komm'].'" style="cursor: pointer;';
if ($row['a'] == 0) {
echo "background: #FF4B4B;color: #FFF;";
} if ($row['a'] == 1) {
echo "";
} if ($row['a'] == 2) {
echo "background: #F8FFA2;color: #333;";
} if ($row['a'] == 3) {
echo "background: #FFDD86;color: #333;";
} if ($row['a'] == 4) {
echo "background: #FFB5B5;color: #333;";
} if ($row['a'] == 5) {
echo "background: #F6BFFF;color: #333;";
} if ($row['a'] == 6) {
echo "background: #B0FFB4;color:#333;";
} if ($row['a'] == 7) {
echo "background: #DFDFDF;color: #333;";
} if ($row['a'] == 8) {
echo "background: #FFB689;color: #333;";
} if ($row['a'] == 9) {
echo "background: #F09595;color: #333;";
}
echo '">';
echo '<script>
$("#open'.$row['id'].'").live("dblclick", function() {
window.location.href="./zayavpage.php?id='.$row['id'].'";
});
</script>';
echo '<td  style="text-align: center;">';
echo $row['data'];
echo '</td>';
echo '<td  style="text-align: center;">';

$qprodukt = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$rprodukt = mysql_query($qprodukt);
$pprodukt = mysql_fetch_array($rprodukt); 
echo $pprodukt['name'];
echo '</td>';
echo '<td  style="text-align: center;">';
echo $row['nameogrn'];
echo '</td>';
echo '<td style="text-align: center;  width: 100px;">';
echo '<a href="https://focus.kontur.ru/search?query=',$row['inn'],'" target="_blank">',$row['inn'],'</a>';
echo '</td>';
echo '<td style="text-align: center;" >';
echo $row['name'];
echo '</td>';
echo '<td style="text-align: center;">';

$vowels = array("+", "(", ")", " ", "-", ",", ";", "а", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы", "Ь", "Э", "Ю", "Я");
				echo str_replace($vowels, "", $row['tel']);
				$str = str_replace($vowels, "", $row['tel']);
				$str2 = mb_strlen($str,'UTF-8')."\n";
				echo '<br>';
				if($str2 == 11){
					$str00 = substr($str, 0, 1);
					$str01 = substr($str, 1, 2);
					$str02 = substr($str, 1, 3);
					$str03 = substr($str, 4, 10);
					$str04 = mysql_fetch_array(mysql_query("SELECT * FROM `mtt_codes` WHERE `def` ='".$str02."' AND `from` <= '".$str03."' AND `to` >= '".$str03."'"));
					$str05 = mysql_fetch_array(mysql_query("SELECT * FROM `mtt_regions` WHERE `id` ='".$str04['region']."'"));
					$s = $str05['tip'];
					$i = date("i");
					$H = date("H") + $str05['zch'];
					echo $str05['name'],".  Время: ",$H,":",$i;
					
				}
echo '</td>';


echo '<td style="text-align: center;">';
if($row['kto']>0){
$q = "SELECT * FROM users WHERE users_id =".$row['kto'];
$result = mysql_query($q);
$person = mysql_fetch_array($result); 
$gen = $person['f_name'];
echo mb_substr($gen,0,1,'UTF-8');
echo '. ';
echo $person['l_name'];
}else{
echo "Заявка с сайта";
}
echo '</td>';


echo '<td style="text-align: center;">';
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
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Открыть" href="./zayavpage.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-folder-open"></span></a>';
echo '</td>';
echo '<td style="text-align: center;">';
$result = mysql_query("SELECT count(*) FROM focusstar WHERE users = '".$userdata['users_id']."' AND zayav =".$row['id']);
$class = mysql_result($result, 0);
if($class == 0){
echo '<a title="В избранные" href="./zayavstar.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-star"></span></a>';
}else{
echo '<a title="В избранные" href="./zayavstardel.php?users='.$userdata['users_id'].'&id='.$row['id']. '"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span></a>';
}
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Новое действие" href="./zayaviz.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-earphone"></span></a>';
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Написать сообщение" href="./new_sms.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-envelope"></span></a>';
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Новое напоминание" href="./napomni.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-time"></span></a>';
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Редактировать" href="./openz.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-pencil"></span></a>';
echo '</td>';
if($userdata['tip'] == 4 ){
echo '<td style="text-align: center;">';
echo '<a title="Удалить" href="./delz.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-trash"></span></a>';
echo '</td>';
}
echo '</tr>';
} 
?>
</table><?
// Проверяем нужны ли стрелки назад
if ($page != 1) $pervpage = '<a href=?page=1>Первая</a> | <a href=?page='. ($page - 1) .'>Предыдущая</a> | ';
// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = ' | <a href=?page='. ($page + 1) .'>Следующая</a> | <a href=?page=' .$total. '>Последняя</a>';

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 5 > 0) $page5left = ' <a href=?page='. ($page - 5) .'>'. ($page - 5) .'</a> | ';
if($page - 4 > 0) $page4left = ' <a href=?page='. ($page - 4) .'>'. ($page - 4) .'</a> | ';
if($page - 3 > 0) $page3left = ' <a href=?page='. ($page - 3) .'>'. ($page - 3) .'</a> | ';
if($page - 2 > 0) $page2left = ' <a href=?page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';
if($page - 1 > 0) $page1left = '<a href=?page='. ($page - 1) .'>'. ($page - 1) .'</a> | ';

if($page + 5 <= $total) $page5right = ' | <a href=?page='. ($page + 5) .'>'. ($page + 5) .'</a>';
if($page + 4 <= $total) $page4right = ' | <a href=?page='. ($page + 4) .'>'. ($page + 4) .'</a>';
if($page + 3 <= $total) $page3right = ' | <a href=?page='. ($page + 3) .'>'. ($page + 3) .'</a>';
if($page + 2 <= $total) $page2right = ' | <a href=?page='. ($page + 2) .'>'. ($page + 2) .'</a>';
if($page + 1 <= $total) $page1right = ' | <a href=?page='. ($page + 1) .'>'. ($page + 1) .'</a>';

// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
echo "</div>";
}
?>
<br></div>


<div id="izbranie" style="display:none;">
<table class="table">
<thead>
<tr>
<th  style="text-align: center;width: 120px;">Дата</th>
<th  style="text-align: center;">ФИО</th>
<th style="text-align: center;">Телефон</th>
<th style="text-align: center;">ИНН</th>
<th style="text-align: center;">Статус</th>
<th style="text-align: center;"><span class="glyphicon glyphicon-folder-open"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-star"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-earphone"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-envelope"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-time"></span></th>
<th style="text-align: center;"><span class="glyphicon glyphicon-pencil"></span></th>
<?php if($userdata['tip'] == 4 ){echo '<th style="text-align: center;"><span class="glyphicon glyphicon-trash"></span></th>';}?>
</tr>
</thead>
<?php
include 'conf.php';
$queryqw = mysql_query("SELECT * from focusstar WHERE users = '".$userdata['users_id']."' ORDER BY id");
while($rowqw = mysql_fetch_array($queryqw)) {
$query = mysql_query("SELECT * from focus WHERE del = 0 AND id = '".$rowqw['zayav']."' ORDER BY a");
while($row = mysql_fetch_array($query)) {
echo '<tr  data-toggle="modal" data-target="#myModal'.$row['id'].'"  title="'.$row['komm'].'" style="cursor: pointer;';
if ($row['a'] == 0) {
echo "background: #C5F2FF;color: #333;";
} if ($row['a'] == 1) {
echo "";
} if ($row['a'] == 2) {
echo "background: #F8FFA2;color: #333;";
} if ($row['a'] == 3) {
echo "background: #FFDD86;color: #333;";
} if ($row['a'] == 4) {
echo "background: #FFB5B5;color: #333;";
} if ($row['a'] == 5) {
echo "background: #F6BFFF;color: #333;";
} if ($row['a'] == 6) {
echo "background: #B0FFB4;color: #333;";
} if ($row['a'] == 7) {
echo "background: #DFDFDF;color: #333;";
} if ($row['a'] == 8) {
echo "background: #FFB689;color: #333;";
} if ($row['a'] == 9) {
echo "background: #F09595;color: #333;";
}
echo '"><td  style="text-align: center;">';
echo $row['data'];
echo '</td>';echo '<td  style="text-align: center;">';
echo $row['name'];
echo '</td>';
echo '<td style="text-align: center;">';
echo $row['tel'];
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a href="https://focus.kontur.ru/search?query=',$row['inn'],'" target="_blank">',$row['inn'],'</a>';
echo '</td>';
echo '<td style="text-align: center;">';
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
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Открыть" href="./zayavpage.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-folder-open"></span></a>';
echo '</td>';
echo '<td style="text-align: center;">';
$result = mysql_query("SELECT count(*) FROM focusstar WHERE users = '".$userdata['users_id']."' AND zayav =".$row['id']);
$class = mysql_result($result, 0);
if($class == 0){
echo '<a title="В избранные" href="./zayavstar.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-star"></span></a>';
}else{
echo '<a title="В избранные" href="./zayavstardel.php?users='.$userdata['users_id'].'&id='.$row['id']. '"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span></a>';
}
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Новое действие" href="./zayaviz.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-earphone"></span></a>';
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Написать сообщение" href="./new_sms.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-envelope"></span></a>';
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Новое напоминание" href="./napomni.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-time"></span></a>';
echo '</td>';
echo '<td style="text-align: center;">';
echo '<a title="Редактировать" href="./openz.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-pencil"></span></a>';
echo '</td>';
if($userdata['tip'] == 4 ){
echo '<td style="text-align: center;">';
echo '<a title="Удалить" href="./delz.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-trash"></span></a>';
echo '</td>';
}
echo '</tr>';
} 
} 
?>
</table>
<br>

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