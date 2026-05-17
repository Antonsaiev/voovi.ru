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

/* if($userdata['users_id'] != 4){
	echo '<script language="JavaScript"> 
  window.location.href = "/"
</script>';
exit;
} */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<!--<link rel="stylesheet" type="text/css" href="css/normalize.css" />
<link rel="stylesheet" type="text/css" href="css/demo.css" />
<link rel="stylesheet" type="text/css" href="css/component.css" /> -->
</head>
<body>
<?php
# шапка
include 'header.php';
?>

<div class="container" style="margin-top: 80px;">
<div class="row">
<div class="col-md-12">
<div id="scrollArea" class="clusterize-scroll">




<form action="" method="GET" name="form">

Отгружен: <input type="text" name="akt"  value="<?php echo $_GET['akt']; ?>" />  
Услуга: <input type="text" name="produkt"  value="<?php echo $_GET['produkt']; ?>" />  
Продукт: <input type="text" name="prod"  value="<?php echo $_GET['prod']; ?>" />  


<input type="submit" value="Поиск"><br><br>
</form>

<div id="barakobama"></div>
<?php
if (isset($_POST['aktdate'])){
$y = mb_substr(strstr($_POST['akt_date'],"2"),2,2,'UTF-8'); 
$m = mb_substr(strstr($_POST['akt_date'],"-"),1,2,'UTF-8');
echo '<script type="text/javascript">'; 
echo 'window.location.href="/leha.php?akt=1&akt_date='.$y.''.$m.'";'; 
echo '</script>';
}
?>

<?php 
if(isset($_GET['akt'])){
$getakt="akt LIKE '%$_GET[akt]%'";
}else{
$getakt="akt != '1'";
}
if(isset($_GET['akt_date'])){
$akt_date="AND akt_date LIKE '%$_GET[akt_date]%'";
}else{
$akt_date="";
}
if(isset($_GET['produkt'])){
$produkt="AND produkt LIKE '%$_GET[produkt]%'";
}else{
$produkt="";
}
if(isset($_GET['prod'])){
$prod="AND prod LIKE '%$_GET[prod]%'";
}else{
$prod="";
}
if(isset($_GET['y'])){
$y="AND y LIKE '%$_GET[y]%'";
}else{
$y="";
}
?>
<table class="table tablehover rowclick" id="rowclick2">
<thead>
<tr>
<th>Дата</th>
<th>ИНН</th>
<th>КПП</th>
<th>№Счета</th>
<th style="width: 250px;text-align: center;">Название</th>
<th style=" text-align: center;">Выставил</th>
<th>Исполнитель</th>
<th>Оплата S</th>
<th>Тип S</th>
<th>ID Продукта</th>
<th>Продукт</th>
<th>S</th>
<th>Отгружен</th>
</tr>
</thead>
<tbody id="contentArea" class="clusterize-content">
<?php
$query = mysql_query("SELECT * FROM schet WHERE del = '0' AND $getakt $prod $produkt $akt_date $y ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {

echo '<tr for="raz'.$row['rand'].'" ';
if ($row['akt'] == 1){
echo 'class="alert alert-success" role="alert"';
} if ($row['otk'] == 1){
echo 'class="alert alert-gavno" role="alert"';
} if ($row['oplachenks'] == 1 || $row['oplachen'] == 1){
echo 'class="alert alert-warning" role="alert"';
}

$qdsafesd = mysql_query("SELECT SUM(turbo) FROM schet WHERE del = '0' AND turbo = '1' AND rand ='".$row['rand']."' GROUP BY rand");
$pedfsbfedb = mysql_result($qdsafesd, 0);

if ($pedfsbfedb >= 1){
echo ' style="border: 2px solid red;"';
}

echo '>';
echo '';
$mess = substr($row['data'], 0, 9);
echo '<td>'.$mess.'</td>';
echo '<td >';
echo $row['inn'];
echo '</td>';
echo '<td >';
echo $row['kpp'];
echo '</td>';
echo '<td >';
if($row['nomerschet'] != 'В КС'){
	echo '<span class="glyphicon glyphicon-bookmark" aria-hidden="true"  style="display: initial;"></span>',$row['god'],$row['kto'],$row['otdel'],$row['kolichschet'].'<br>';
	if($row['ns']=='0'){
		mysql_query("UPDATE schet SET ns='".$row['god'].$row['kto'].$row['otdel'].$row['kolichschet']."' WHERE rand =".$row['rand']);
	}
}else{
	
}
echo '</td>';
echo '<td onclick="name'.$row['rand'].'()" style="text-align: center;" ';
if ($row['gotov'] > 0){  
echo 'class="alert alert-success" role="alert"';
}
echo '>';
echo $row['name'];



echo '<script type="text/javascript">
function name'.$row['rand'].'()
{
    var c = document.getElementById("containame'.$row['rand'].'");
    var d = document.createElement("iframe");
    var t = document.createTextNode("11111");
    d.appendChild(t);
    c.appendChild(d);
	d.src = "'.VOOVI_MAIN_URL.'/setschet.php?id='.$row['rand'].'&p=0&kli='.$row['idkli'].'&lico='.$row['lico'].'&gr='.$row['gr'].'";
	d.width = document.documentElement.clientWidth - document.documentElement.clientWidth / 15;
	d.height = document.documentElement.clientHeight+20;
	d.className = "iframestylediv";
	d.Name = "f2";
	document.getElementById("containame'.$row['rand'].'").className = "contaidiv";
	d.style.minWidth = "992px";
}
$(document).ready(function(){
    $("#containame'.$row['rand'].'").click(function(){
        $("#containame'.$row['rand'].'").empty();
		document.getElementById("containame'.$row['rand'].'").className = "";
    });
});


</script>
';


echo '</td>';


echo '<td id="proddlen'.$row['rand'].'" style="text-align: center;">';

$ktolgenerac = "SELECT * FROM users WHERE users_id =".$row['kto'];
$ktorgenerac = mysql_query($ktolgenerac);
$ktopgenerac = mysql_fetch_array($ktorgenerac);
$kto = $ktopgenerac['f_name'];
echo mb_substr($kto,0,1,'UTF-8'),'. ';
echo $ktopgenerac['l_name'];
echo '</td>';
echo '<td id="generac'.$row['rand'].'">';
$qdsafsd = mysql_query("SELECT SUM(kvo) FROM schet WHERE del = '0' AND gen = '1' AND rand ='".$row['rand']."'  GROUP BY rand");
$pedfsbfdb = mysql_result($qdsafsd, 0);

$lgenerac = "SELECT * FROM users WHERE users_id =".$row['generac'];
$rgenerac = mysql_query($lgenerac);
$pgenerac = mysql_fetch_array($rgenerac);
echo '';
if($row['generac']!=546321564){
$gen = $pgenerac['f_name'];
echo mb_substr($gen,0,1,'UTF-8'),'. ';
echo $pgenerac['l_name'];
}else{
echo"Поставка";
}
echo '';
echo '</td>';


echo '<td style="width:1px;">'; 
$lgenerac = "SELECT * FROM kvobop WHERE schet =".$row['rand'];
$rgenerac = mysql_query($lgenerac);
$pgenerac = mysql_fetch_array($rgenerac);
echo $pgenerac['d'].'.'.$pgenerac['m'].'.'.$pgenerac['y'].'<br>'; 
echo '</td>'; 
echo '<td>'; 
if($pgenerac['tip'] == 1){
echo 'Наличные'; 
}if($pgenerac['tip'] == 2){
echo 'Наличные (частично)'; 
}if($pgenerac['tip'] == 3){
echo 'Безналичные'; 
}if($pgenerac['tip'] == 4){
echo 'Безналичные (частично)'; 
}if($pgenerac['tip'] == 5){
echo 'Гарантийное письмо'; 
}
echo '</td>';

echo '<td>'; 

$rpod = "SELECT * FROM tarif WHERE id =".$row['prod'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);

echo $row['prod'];
echo '</td>';

echo '<td>'; 
echo $personrpod['name'];
echo '</td>';

echo '<td style="width:100px;">'; 
$summ = number_format($personrpod['price'] * $row['kvo'], 0, '.', '');
$skidka = ($summ / 100) * $row['skidka'];
echo $summ - $skidka;
echo '</td>';

echo '<td>'; 
echo $row['akt_date'];
echo '</td>';
echo '</tr>';
}
?>
</tbody>
</table>


</form>
<?
// Вывод меню если страниц больше одной
if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
echo "</div>";
}
?>


</div>
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
		<!--<script src="js/jquery.ba-throttle-debounce.min.js"></script>
		<script src="js/jquery.stickyheader.js"></script>-->
</body>
</html>
