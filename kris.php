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

<div class="container" style="margin-top: 20px;">
<div class="row">
<div class="col-md-12">
<div id="scrollArea" class="clusterize-scroll">
<br><h3 style="border-bottom: 1px #333 solid;"><a href="/leha.php?turbo=1">Текущие клиенты</a></h3> 








<div id="status"></div>
<?php 
$num = 99900;
$page = $_GET['page'];

if(isset($_GET['groupi'])){
if($_GET['groupi']==0){
$groupi="";
}else{
$groupi="gr != '$_GET[groupi]' AND";
}}else{
$groupi="inn != '$_GET[inn]' AND";
}
if(isset($_GET['akt'])){
$getakt="akt = '$_GET[akt]'";
}else{
$getakt="akt != '1'";
}

if(isset($_GET['akt_date'])){
$akt_date="AND akt_date = '$_GET[akt_date]'";
}else{
$akt_date="";
}
if(isset($_GET['gen_date'])){
$gen_date="AND gen_date = '$_GET[gen_date]'";
}else{
$gen_date="";
}
if (isset($_GET['oplachenks'])){
$oplachenks = "AND (oplachenks = '1' OR oplachen = '1') AND gotov = '0'  AND akt = '0'   AND doki = '$_GET[oplachenks]' ";
}
if (isset($_GET['oplachen'])){
$oplachen = "AND oplachenks = '1'";
}
if(isset($_GET['gotov'])){
$gotov="AND gotov = '$_GET[gotov]'";
}else{
$gotov="";
}
if($_GET['goroddd']== '1' ){
$goroddd="AND goroddd > '0'";
}
if($_GET['goroddd']== '0' ){
$goroddd="AND (goroddd = '0' OR goroddd = '')";
}
if(isset($_GET['neoplachen'])){
$neoplachen="AND oplachenks != '1' AND oplachen != '1' AND gotov = '0'  AND akt = '0'";
}else{
$neoplachen="";
}
if(isset($_GET['turbo'])){
$turbo="AND turbo = '$_GET[turbo]'";
}else{
$turbo="";
}
if(isset($_GET['postavka'])){
$postavka="AND nomerdog = '$_GET[postavka]'";
}else{
$postavka="";
}
if(isset($_GET['m'])){
$postavka="AND m = '$_GET[m]' AND y = '$_GET[y]'";
}else{
$postavka="";
}
if(isset($_GET['doljen'])){
$doljen="AND doljen = '$_GET[doljen]'";
}else{
$doljen="";
}
if($_GET['generac'] == '0'){
$generac="AND generac = '0'";
}if($_GET['generac'] == '1'){
$generac="AND generac != '0'"; 
}if(!isset($_GET['generac'])){
$generac="";
}if($_GET['generac'] > 9999){
$generac="AND generac = '$_GET[generac]'";
}
if(isset($_GET['otk'])){
$otk="AND otk = '$_GET[otk]'";
}else{
$otk="AND otk = '0'"; 
}
$result00 = mysql_query("SELECT COUNT(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,priceksisset,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) FROM schet WHERE del = '0' AND $getakt $dtakt");
$temp = mysql_fetch_array($result00);
$posts = $temp[0];
$total = (($posts - 1) / $num) + 1;
$total =  intval($total);
$page = intval($page);
if(empty($page) or $page < 0) $page = 1;
if($page > $total) $page = $total;
$start = $page * $num - $num;
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













<div class="col-md-6">

<div>
Агентское: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,priceksisset,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){
			if(!empty($pgenerac['m'])){
				if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
					echo $row['price'].'+';
				}
			}
		}
	}else{
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>












<div><?php
/* $query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM kvobop WHERE schet =".$row['rand'];
		$rgenerac = mysql_query($lgenerac);
		$pgenerac = mysql_fetch_array($rgenerac);
		if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
			
		}else{
			if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){
				if(!empty($pgenerac['m'])){
					if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
						//echo $row['price'].'+';
					}
				}
			}
		}
	}
} */
?>
Безнал этот: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date"$gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
		$rgenerac = mysql_query($lgenerac);
		$pgenerac = mysql_fetch_array($rgenerac);
		if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
			
		}else{
			if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){
				if(!empty($pgenerac['m'])){
					if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
						echo $row['price'].'+';
					}
				}
			}
		}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>
<div>
Безнал тот: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
		$rgenerac = mysql_query($lgenerac);
		$pgenerac = mysql_fetch_array($rgenerac);
		if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
			
		}else{
			if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){
				if(!empty($pgenerac['m'])){
					if(substr($_GET['akt_date'], 2, 2) != $pgenerac['m']){
						echo $row['price'].'+';
					}
				}
			}
		}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>



<div>
Старый офис: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
		$rgenerac = mysql_query($lgenerac);
		$pgenerac = mysql_fetch_array($rgenerac);
			if($pgenerac['tip'] == 9){
						echo $row['price'].'+';
			}
		
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>









<div>
Нал этот: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		
	}else{
		if($pgenerac['tip'] == 1 || $pgenerac['tip'] == 2){
			if(!empty($pgenerac['m'])){
				if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
					echo $row['price'].'+';
				}
			}
		}
	}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>
<div>
Нал тот: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		
	}else{
		if($pgenerac['tip'] == 1 || $pgenerac['tip'] == 2){
			if(!empty($pgenerac['m'])){
				if(substr($_GET['akt_date'], 2, 2) != $pgenerac['m']){
					echo $row['price'].'+';
				}
			}
		}
	}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>
<div>
Оплачен не отгружен: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND akt != '1' $turbo $gotov $goroddd $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		
	}else{
			if(substr($row['data'], 3, 2) != date('m')){
					echo $row['price'].'+';
			}
	}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>
<div>
Не оплачен не отгружен: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND akt != '1' $turbo $gotov $goroddd $gen_date $postavka $generac AND oplachenks != '1' $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		
	}else{
			if(substr($row['data'], 3, 2) != date('m')){
					echo $row['price'].'+';
			}
	}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>

  


</div>
</div>

















<div class="col-md-6">









<div><?php
/* $query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] != '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
		$rgenerac = mysql_query($lgenerac);
		$pgenerac = mysql_fetch_array($rgenerac);
		if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
			
		}else{
			if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){
				if(!empty($pgenerac['m'])){
					if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
						//echo $row['price'].'+';
					}
				}
			}
		}
	}
} */
?>
Безнал этот: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] == '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
		$rgenerac = mysql_query($lgenerac);
		$pgenerac = mysql_fetch_array($rgenerac);
		if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
			
		}else{
			if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){
				if(!empty($pgenerac['m'])){
					if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
						echo $row['price'].'+';
					}
				}
			}
		}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>
<div>
Безнал тот: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] == '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
		$rgenerac = mysql_query($lgenerac);
		$pgenerac = mysql_fetch_array($rgenerac);
		if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
			
		}else{
			if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){
				if(!empty($pgenerac['m'])){
					if(substr($_GET['akt_date'], 2, 2) != $pgenerac['m']){
						echo $row['price'].'+';
					}
				}
			}
		}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>









<div>
Нал этот: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] == '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		
	}else{
		if($pgenerac['tip'] == 1 || $pgenerac['tip'] == 2){
			if(!empty($pgenerac['m'])){
				if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
					echo $row['price'].'+';
				}
			}
		}
	}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>
<div>
Нал тот: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] == '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		
	}else{
		if($pgenerac['tip'] == 1 || $pgenerac['tip'] == 2){
			if(!empty($pgenerac['m'])){
				if(substr($_GET['akt_date'], 2, 2) != $pgenerac['m']){
					echo $row['price'].'+';
				}
			}
		}
	}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>

<div>
Оплачен не отгружен: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND akt != '1' $turbo $gotov $goroddd $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] == '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		
	}else{
			if(substr($row['data'], 3, 2) != date('m')){
					echo $row['price'].'+';
			}
	}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>


</div>
<div>
Не оплачен не отгружен: 
<script type="text/javascript">
var c=<?php
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND akt != '1' $turbo $gotov $goroddd $gen_date $postavka $generac AND oplachenks != '1' $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num");
while($row = mysql_fetch_array($query)) {
	$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$resultrpod = mysql_query($rpod);
	$personrpod = mysql_fetch_array($resultrpod);
	if($personrpod['parent'] == '24'){
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
	$rgenerac = mysql_query($lgenerac);
	$pgenerac = mysql_fetch_array($rgenerac);
	if($row['produkt'] == 148 ||$row['produkt'] == 149 ||$row['produkt'] == 150){
		
	}else{
			if(substr($row['data'], 3, 2) != date('m')){
					echo $row['price'].'+';
			}
	}
	}
}
?>0;
string = numeral(c).format("0,0");
document.write(string);
</script>

  


</div>
</div>















<table class="table tablehover rowclick" id="rowclick2">
<thead>
<tr>
<th><span class="glyphicon glyphicon-random" aria-hidden="true"></span></th>
<th>Дата</th>
<th>ИНН</th>
<th>КПП</th>
<th style="width: 250px;text-align: center;">Название</th>
<th style="text-align: center;">Продукт</th>
<th style="text-align: center;">Услуга</th>
<th style=" text-align: center;">Выставил</th>
<th>Исполнитель</th>
<th>Оплата S</th>
<th>Тип S</th>
<th>S</th>
<th></th>
<th>Безнал этот</th>
<th>Безнал тот</th>
<th>Нал этот</th>
<th>Нал тот</th>
<th >Номер счета кс</th>
<th>K</th>
</tr>
</thead>
<tbody id="contentArea" class="clusterize-content">
<?php $i = 1;
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,priceksisset,nomerschetks,nomerdog,data,produkt,price,priceks,kto,inn,kpp,idkli,otk,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC LIMIT $start, $num ");

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
echo '<td id="svyaz'.$row['gr'].'" >';
echo $i++;
echo '</td>';
$mess = substr($row['data'], 0, 9);
echo '<td>'.$mess.'</td>';
echo '<td >';
echo $row['inn'];
echo '</td>';
echo '<td >';
echo $row['kpp'];
echo '</td>';
echo '<td onclick="name'.$row['rand'].'()" style="text-align: center;" ';
if ($row['gotov'] > 0){  
echo 'class="alert alert-success" role="alert"';
}
echo '>';
echo $row['name'];
echo '</td>';
echo '<td style="text-align: center;"><div id="containame'.$row['rand'].'"></div>';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);

echo $personrpod['name'];
echo '</td>';
echo '<td id="proddlen'.$row['rand'].'" style="text-align: center;">';
echo '<div id="proleninfo'.$row['rand'].'">';
if($row['prodlen'] == 0){
echo 'Мы привели';
}if($row['prodlen'] == 2){
echo 'Сам пришел';
}if($row['prodlen'] == 1){
echo 'Продлен';
}
echo '</div>';
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
if ($pedfsbfdb >= 1){
echo "Генераций ".$pedfsbfdb;
}
$lgenerac = "SELECT * FROM users WHERE users_id =".$row['generac'];
$rgenerac = mysql_query($lgenerac);
$pgenerac = mysql_fetch_array($rgenerac);
echo '<div id="generacinfo'.$row['rand'].'">';
if($row['generac']!=546321564){
$gen = $pgenerac['f_name'];
echo mb_substr($gen,0,1,'UTF-8'),'. ';
echo $pgenerac['l_name'];
}else{
echo"Поставка";
}
echo ' </div>';
echo '<select id="generaci'.$row['rand'].'" name="generaci'.$row['rand'].'"   style="display:none;" >';
$query21 = mysql_query("SELECT * from users WHERE users_id < '7' ");
echo '<option value="0"></option>';
echo '<option value="546321564">Поставка</option>';
while($row21 = mysql_fetch_array($query21)) {
echo '<option  value="'.$row21['users_id'].'">',$row21['f_name']," ",$row21['l_name'],'</option>';
}
echo '<option value="0"></option>';
echo '</select>';

echo '</td>';


echo '<td style="width:1px;">'; 
		$lgenerac = "SELECT * FROM `kvobop` WHERE `schet` = '".$row['rand']."' ORDER BY id DESC";
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
}if($pgenerac['tip'] == 9){
echo 'Кассовый чек'; 
}
//echo $pgenerac['tip'];
echo '</td>';

echo '<td style="width:1px;">'; 
echo $row['price'];
echo '</td>';
echo '<td>';
if(!empty($pgenerac['m'])){
if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
	echo 'Этот месяц';
}else{
	echo 'Прошлый месяц';
}
}

echo '</td>';
echo '<td 

 ';

if($row['produkt'] == 148 || $row['produkt'] == 149 || $row['produkt'] == 150){
	echo 'style="background:red; color:#fff;"';
}else{
	echo 'id="beznalatot'.$row['rand'].'"';
}

echo ' 

>';

if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){
if(!empty($pgenerac['m'])){
if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
	echo $row['price'];
}else{
	echo '0';
}
}
}else{
	echo '0';
}





echo '</td>';
echo '<td>';

if($pgenerac['tip'] == 3 || $pgenerac['tip'] == 4){ 
if(!empty($pgenerac['m'])){
if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
	echo '0';
}else{
	echo $row['price'];
}
}
}else{
	echo '0';
}





echo '</td>';
echo '<td>';

if($pgenerac['tip'] == 1 || $pgenerac['tip'] == 2){
if(!empty($pgenerac['m'])){
if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
	echo $row['price'];
}else{
	echo '0';
}
}
}else{
	echo '0';
}





echo '</td>';
echo '<td>';

if($pgenerac['tip'] == 1 || $pgenerac['tip'] == 2){
if(!empty($pgenerac['m'])){
if(substr($_GET['akt_date'], 2, 2) == $pgenerac['m']){
	echo '0';
}else{
	echo $row['price'];
}
}
}else{
	echo '0';
}





echo '</td>';
echo '<td>';

echo $row['nomerschetks'];


echo '</td>';
echo '<td style="width:1px;">'; 
echo $row['priceks']; 
echo '</td>';
echo '<td style="width:1px;">'; 
echo $row['priceksisset']; 
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