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

<div class="container" style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">
<div id="scrollArea" class="clusterize-scroll">
<br><h3 style="border-bottom: 1px #333 solid;"><a href="/toha.php?turbo=1">Текущие клиенты</a></h3> 

<div class="div_macintosh"> 
<?php

$result0 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) FROM schet WHERE del = '0' AND akt='1'");
echo "<a onclick='barak()' class='macintosh btn  btn-xs'>Отгруженные: ".mysql_result($result0, 0)."</a>"; 







$result4 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND gotov='1' AND akt != '1' AND generac != '0'");
echo " <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> <a href='/toha.php?gotov=1&generac=1' class='macintosh btn  btn-xs'>Готов к отгрузке: ".mysql_result($result4, 0)."</a> "; 


  
$result4 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND gotov='1' AND akt != '1' AND generac = '0' AND goroddd > '0'");
echo " <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> <a href='/toha.php?gotov=1&generac=0&goroddd=1' class='macintosh btn  btn-xs'>Выезды: ".mysql_result($result4, 0)."</a> "; 


$result1 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND gotov='1' AND akt != '1' AND generac = '0'  AND (goroddd = '0' OR goroddd = '')");
echo " <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> <a href='/toha.php?gotov=1&generac=0&goroddd=0' class='macintosh btn  btn-xs'>Готовы к выпуску: ".mysql_result($result1, 0)."</a>"; 


$result55 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND (oplachenks = '1' OR oplachen = '1') AND generac = '546321564' AND akt = '0'");
echo " <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> <a href='/toha.php?generac=546321564&oplachen=1' class='macintosh btn  btn-xs'>Поставка: ".mysql_result($result55, 0)."</a>"; 



$result5 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND (oplachenks = '1' OR oplachen = '1') AND gotov = '0'  AND akt = '0'   AND doki = '1'");
echo " <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> <a href='/toha.php?oplachenks=1' class='macintosh btn  btn-xs'>На проверке: ".mysql_result($result5, 0)."</a>"; 

$result2 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND (oplachenks = '1' OR oplachen = '1') AND gotov = '0'  AND akt = '0'   AND doki = '0'");
echo " <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> <a href='/toha.php?oplachenks=0' class='macintosh btn  btn-xs'>Оплачен нет доков: ".mysql_result($result2, 0)."</a>"; 
$result3 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND AND oplachen != '1' AND gotov = '0'  AND akt = '0'  AND doki = '0'");
echo " <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> <a href='/toha.php?neoplachen=0' class='macintosh btn  btn-xs'>Не оплаченые: ".mysql_result($result3, 0)."</a>"; 
$result4 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND doljen='1'");
echo " <span class='glyphicon glyphicon-share-alt' aria-hidden='true'></span> <a href='/toha.php?doljen=1&akt=0' class='macintoshred btn  btn-xs'>Должники: ".mysql_result($result4, 0)."</a>"; 

?>

</div>




<script type="text/javascript">
function barak()
{
    var barakc = document.getElementById("barakobama");
    var barakd = document.createElement("form");
    var baraki = document.createElement("input");
    var baraki2 = document.createElement("input");
    var baraki3 = document.createElement("input");
    barakd.appendChild(baraki);
	baraki.type = 'month'; 
	baraki.name = 'akt_date'; 
    barakd.appendChild(baraki2);
	baraki2.type = 'submit'; 
	baraki2.name = 'aktdate'; 
	baraki2.value = 'ОК'; 
    barakd.appendChild(baraki3);
	baraki3.type = 'submit'; 
	baraki3.id = 'close'; 
	baraki3.value = 'Отменить'; 
    barakc.appendChild(barakd);
	barakd.src = "/abcd.php";
	barakd.width = "300px";
	barakd.height = "200px";
	barakd.className = "iframestyle_300";
	barakd.method = "post";
	document.getElementById("barakobama").className = "contai";
}
$(document).ready(function(){
    $("#close").click(function(){
        $("#barakobama").empty();
		document.getElementById("barakobama").className = "";
		
    }); 
});
</script>
<div id="barakobama"></div>
<?php
if (isset($_POST['aktdate'])){
$y = mb_substr(strstr($_POST['akt_date'],"2"),2,2,'UTF-8'); 
$m = mb_substr(strstr($_POST['akt_date'],"-"),1,2,'UTF-8');
echo '<script type="text/javascript">'; 
echo 'window.location.href="/toha.php?akt=1&akt_date='.$y.''.$m.'";'; 
echo '</script>';
}



$id = $_POST['id'];
if(isset($_POST['deletemarked'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$Qdelete = mysql_query("DELETE FROM schet WHERE del = '0' AND rand IN (".$impid.")") or die ("error in delete");
		if(isset($Qdelete))
		{
			echo "Успешно удален счет";
		}
	}
}

if(isset($_POST['ingroup'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$Qdelete = mysql_query("UPDATE `schet` SET `gr` =  '".rand(0,255).rand(0,255).rand(200,255)."', `groupi` =  'rgb(".rand(0,255).", ".rand(0,255).", ".rand(200,255).")'  WHERE rand IN (".$impid.")") or die ("error in delete");
		if(isset($Qdelete))
		{
			echo "Успешно  ".$person."";
		}
	}
}

if(isset($_POST['ungroup'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$Qdelete = mysql_query("UPDATE `schet` SET `gr` =  '0', `groupi` =  '' WHERE rand IN (".$impid.")") or die ("error in delete");
		if(isset($Qdelete))
		{
			echo "Успешно  ".$person."";
		}
	}
}

if(isset($_POST['doljen'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['doljen'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljen` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['doljen'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljen` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['proveren'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['gotov'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `gotov` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['gotov'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `gotov` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['oplachen'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['oplachenks'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `oplachenks` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['oplachenks'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `oplachenks` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['akt'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['akt'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `akt` =  '1',`akt_date` =  '".date('ym')."' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['akt'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `akt` =  '0',`akt_date` =  '' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

include 'serchtoha.php'; 
?>
<script>
  var h_hght = 40; // высота шапки 
  var h_mrg = 40; // отступ когда шапка уже не видна
  $(function(){
   $(window).scroll(function(){
  var top = $(this).scrollTop();
  var elem = $('#top_nav');
  if (top+h_mrg < h_hght) {
   elem.css('top', (h_hght-top));
  } else {
   elem.css('top', h_mrg);
  }
});
  });
  
  
  var count = 0;
$(function() {
    count = $('input[type=checkbox]:checked').length;
    displayCount();

    $('input[type=checkbox]').bind('click' , function(e, a) {   
         if (this.checked) {
              count += a ? -1 : 1;
         } else {
              count += a ? 1 : -1;
         }
         displayCount();
    });
    $('#invert').click(function(e) {    
         $('input[type=checkbox]').trigger('click', true)
    });
	$('#deleteol').click(function() {    
        $('#count').text(count); 
		count = 0;
		document.getElementById("countdisplay").style.display="none";
    });
	
});
function displayCount() {
    $('#count').text(count); 
if(count == 0){
	document.getElementById("countdisplay").style.display="none";
	}else{
	document.getElementById("countdisplay").style.display="block";
	}
}
	

</script>



<div id="top_nav" style="padding:8px 0px 3px 8px ;background: rgb(51, 51, 51);">
<input class="btn btn-info btn-xs" type="submit" name="ingroup" value="Сгруппировать" style="margin-bottom: 5px; border-bottom-right-radius: 0; border-top-right-radius: 0;" /> 
<input class="btn btn-info btn-xs" type="submit" name="ungroup" value="Разгруппировать" style="margin-bottom: 5px; border-bottom-left-radius: 0; border-top-left-radius: 0;" /> - 
<input  class="btn btn-danger btn-xs" type="submit" name="doljen" value="Должен" style="margin-bottom: 5px;" /> - 
<input  class="btn btn-warning btn-xs" type="submit" name="oplachen" value="Оплата" style="margin-bottom: 5px;" /> - 
<input  class="btn btn-successs btn-xs" type="submit" name="proveren" value="Проверенно" style="margin-bottom: 5px;" /> - 
<input class="btn btn-success btn-xs" type="submit" name="akt" value="Акт отгружен" style="margin-bottom: 5px;" /> - 
<input class="btn btn-default btn-xs" type="reset" id="deleteol" value="Убрать отмеченые" style="margin-bottom: 5px;" /> - 
<input onclick='return confirm("Для удаления счета нажмите OK!");' class="btn btn-primary btn-xs" type="submit" name="deletemarked" value="Удалить" style="margin-bottom: 5px;" /> 
<p id="countdisplay" style="color:#fff; display:none;">Выбранно: <b style="font-size:15px;" id="count"></b></p>
</div>
<br>
<br>
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


$result00 = mysql_query("SELECT COUNT(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) FROM schet WHERE del = '0' AND $getakt $dtakt");
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
<table class="table tablehover rowclick" id="rowclick2">
<thead>
<tr>
<th style="width: 1px;"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></th>
<th style="width: 1px;">Дата</th>
<th style="width: 1px;">ИНН</th>
<th style="width: 1px;">КПП</th>
<th style="width: 180px; text-align: center;">Название</th>
<th style="width: 90px; text-align: center;">Продукт</th>
<th >Комментарии</th>
<!--<th style="width: 200px;">Контакты</th>-->
<th style="width: 1px;"><span class="glyphicon glyphicon-ruble" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-open-file" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
<th style="width: 60px; text-align: center;">Услуга</th>
<th style="width: 80px; text-align: center;">Выставил</th>
<th style="width: 1px;">Исполнитель</th>
<th style="width: 1px;">Открыть</th>
</tr>
</thead>
<tbody id="contentArea" class="clusterize-content">
<?php $i = 1;
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr,d,m,y FROM schet WHERE m = '02' AND y = '2015' ORDER BY id DESC LIMIT $start, $num ");

while($row = mysql_fetch_array($query)) {
echo '<tr for="raz'.$row['rand'].'" ';
if ($row['akt'] == 1){
echo 'class="alert alert-success" role="alert"';
} if ($row['oplachenks'] == 1 || $row['oplachen'] == 1){
echo 'class="alert alert-warning" role="alert"';
}

$qdsafesd = mysql_query("SELECT SUM(turbo) FROM schet WHERE del = '0' AND turbo = '1' AND rand ='".$row['rand']."' GROUP BY rand");
$pedfsbfedb = mysql_result($qdsafesd, 0);
if ($pedfsbfedb >= 1){
echo ' style="border: 2px solid red;"';
}
echo '><td style="width: 10px;text-align: center;cursor: pointer;"> ';
echo '<input type="checkbox" name="id[]" id="raz'.$row['rand'].'"  value="'.$row['rand'].'">';
echo ' </td>';
echo '<td id="svyaz'.$row['gr'].'" style="width: 1px;text-align: center; background: '.$row['groupi'].';">';

echo $i++.'
<script type="text/javascript">$("#svyaz'.$row['gr'].'").live("dblclick", function() {document.location.href = "/toha.php?name=&inn=&kpp=&groupi='.$row['gr'].'";});</script>
';
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
echo '<td style="text-align: center;"><div id="containame'.$row['rand'].'"></div>';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);

echo $personrpod['name'];
echo '</td>';

echo '<td id="komment'.$row['rand'].'" '; 
$rpissetkomment = "SELECT * FROM schetoldkomment WHERE schet ='".$row['rand']."' ORDER BY id DESC";
$reissetkomment = mysql_query($rpissetkomment);
$peissetkomment = mysql_fetch_array($reissetkomment);
			$issetkomment = mysql_query("SELECT COUNT(*) FROM schetoldkomment WHERE schet ='".$row['rand']."'");
			$yesisset = mysql_result($issetkomment, 0);
if ($row['doljen'] == 1){echo 'class="alert alert-danger" role="alert"';} 
echo '><div id="refresh'.$row['rand'].'" style="text-align: left;" >';
if($yesisset > 0){echo $peissetkomment['komment'];}else{echo $row['koment'];}
echo'';
$qdsafsd = mysql_query("SELECT SUM(turbo) FROM schet WHERE del = '0' AND turbo = '1' AND rand ='".$row['rand']."' GROUP BY rand");
$pedfsbfdb = mysql_result($qdsafsd, 0);
if ($pedfsbfdb >= 1){
echo '<img src="/upload/image/acceleratedbig.png">';
}
echo'</div><div style="display:none;" id="display'.$row['rand'].'">
<textarea rows="5" name="editor'.$row['rand'].'" id="editor'.$row['rand'].'" >';
if($yesisset > 0){echo $peissetkomment['komment'];}else{echo $row['koment'];}
echo'</textarea>
		<input class="btn btn-success" name="submit'.$row['rand'].'" type="submit" value="Сохранить" >
		<div id="otmenit'.$row['rand'].'" class="btn btn-primary" value="Отменить" >Отменить</div>
	</div>
<script  type="text/javascript">
$( "#komment'.$row['rand'].'" ).dblclick(function() {
document.getElementById("display'.$row['rand'].'").style.display="block";
var ckeditor'.$row['rand'].' = CKEDITOR.replace( "editor'.$row['rand'].'" ).config.toolbarGroups = [
{ name: "tools" },
{ name: "others" },
{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ]},
{ name: "colors" }
];
});
$("#otmenit'.$row['rand'].'").click(function(){document.getElementById("display'.$row['rand'].'").style.display="none";});
</script>
';
if(isset($_POST['submit'.$row['rand']])){ 
$koment = "UPDATE schet SET `koment`='".$_POST["editor".$row['rand']]." (".$userdata['f_name']." ".$userdata['l_name'].")' WHERE rand ='".$row['rand']."' ";
mysql_query($koment) or die(mysql_error($link));
echo'<script type="text/javascript">document.location.href = "'.$_SERVER['REQUEST_URI'].'";</script>';
$oldkomment = "INSERT INTO `schetoldkomment` (`schet`,`komment`,`kto`,`data`) VALUES ('".$row['rand']."','".$_POST["editor".$row['rand']]."','".$userdata['users_id']."','".date("d.m.Y; H:i")."')";
mysql_query($oldkomment) or die(mysql_error($linkoldkoment));
$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('".date("d.m.Y; H:i:s")."','Изменен коментарий счета','".$userdata['users_id']."')";
mysql_query($aktivn) or die(mysql_error($link));
}
echo '</td>';/*
echo '<td id="konttakt'.$row['rand'].'">';

$lis = "SELECT * FROM klient WHERE id =".$row['lico'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo '<div id="konactinfo'.$row['rand'].'">';
echo $personlis['fio'];
echo ' ';
echo $personlis['tel'];
echo ' ';
echo $personlis['email'];
echo '</div>';

echo '<select id="kontakti'.$row['rand'].'" name="kontakti'.$row['rand'].'" onchange="konTakti'.$row['rand'].'(this.value)" style="display: none;">';
echo '<option  value="0"></option>';
$query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".$row['idkli']."' ORDER BY id DESC");
while($row2 = mysql_fetch_array($query2)) {
$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");
while($row3 = mysql_fetch_array($query3)) {

echo '<option  value="'.$row3['id'].'">';
echo $row3['fio']," (",$row3['dol'],":",$row3['tel'],")";
echo '</option>';
}
}
echo '<option  value="0"></option>';
echo '</select>';
echo '<script>
$("#konttakt'.$row['rand'].'").live("dblclick", function() {document.getElementById("kontakti'.$row['rand'].'").style.display="block";});function konTakti'.$row['rand'].'(str) {if (str=="0") {$.ajax({type: "GET",url: "pusya.php",data: "lico="+str+"&rand='.$row['rand'].'",success: function(msg){document.getElementById("kontakti'.$row['rand'].'").style.display="none";setTimeout(function() {$("#konactinfo'.$row['rand'].'").load(" #konactinfo'.$row['rand'].'");}, 1000);}});} else {$.ajax({type: "GET",url: "pusya.php",data: "lico="+str+"&rand='.$row['rand'].'",success: function(msg){document.getElementById("kontakti'.$row['rand'].'").style.display="none";setTimeout(function() {$("#konactinfo'.$row['rand'].'").load(" #konactinfo'.$row['rand'].'");}, 1000);}});}}
</script>';
echo '</td>';*/
if ($row['nomerdog']=="В КС"){
echo '<td style="text-align: center;font-size: 12px;padding: 0;">';
echo '</td>';
}else{
$rpod2345 = "SELECT * FROM kvobop WHERE schet = '".$row['rand']."'";
$result57657 = mysql_query($rpod2345);
$row134 = mysql_fetch_array($result57657);
$query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row134['schet']);
$person426 = mysql_result($query544, 0);
if ($person426 <= $row['price'] && $person426 != 0) { 
echo '<td style="text-align: center;font-size: 12px;padding: 0;background:green;text-align:center;color:#fff;">';
}else{
echo '<td style="text-align: center;font-size: 12px;padding: 0;">';
}
if ($person426 != $row['price'] && $person426 > 0 && $person426 < $row['price'] && $person426 != 0) {
echo '<a id="rable'.$row['rand'].'" onclick="ruble'.$row['rand'].'()"><span class="glyphicon glyphicon-ruble"></span></a>';
}
if ($person426 >= $row['price'] && $person426 != $row['price']) {
echo '<a onclick="open'.$row['rand'].'()"><span class="glyphicon glyphicon-eye-open"></span></a>';
}
if ($person426 <= $row['price'] && $person426 != 0) { 
echo '<a onclick="open'.$row['rand'].'()"><span class="glyphicon glyphicon-eye-open"></span></a>';
}else{
echo '<a id="rable'.$row['rand'].'" onclick="ruble'.$row['rand'].'()"><span class="glyphicon glyphicon-ruble"></span></a>';
}
echo '<script type="text/javascript">
function ruble'.$row['rand'].'()
{
    var rublec = document.getElementById("rublei'.$row['rand'].'");
    var rubled = document.createElement("iframe");
    var rublet = document.createTextNode("11111");
    rubled.appendChild(rublet);
    rublec.appendChild(rubled);
	rubled.src = "/divoplata.php?id=1&rand='.$row['rand'].'";
	rubled.width = "900px";
	rubled.height = document.documentElement.clientHeight - 100;
	rubled.className = "iframestyle";
	document.getElementById("rublei'.$row['rand'].'").className = "contai";
}
function open'.$row['rand'].'()
{
    var rublec = document.getElementById("rublei'.$row['rand'].'");
    var rubled = document.createElement("iframe");
    var rublet = document.createTextNode("11111");
    rubled.appendChild(rublet);
    rublec.appendChild(rubled);
	rubled.src = "/divoplata.php?id=0&rand='.$row['rand'].'";
	rubled.width = "900px";
	rubled.height = document.documentElement.clientHeight - 100;
	rubled.className = "iframestyle";
	document.getElementById("rublei'.$row['rand'].'").className = "contai";
}
$(document).ready(function(){
    $("#rublei'.$row['rand'].'").click(function(){
        $("#rublei'.$row['rand'].'").empty();
		document.getElementById("rublei'.$row['rand'].'").className = "";
		
    }); 
});
</script>
<div id="rublei'.$row['rand'].'"></div>';

echo '</td>';
}
if (strlen($row['inn']) == 12){
$tipf = 1;
} else {
$tipf = 2;
}
$re1f = mysql_query("SELECT count(*) from proddoc WHERE produkt = '$row[produkt]'  AND tip = '".$tipf."'");
$cl1f = mysql_result($re1f, 0);
$re2f = mysql_query("SELECT count(*) from proddoc WHERE produkt = '$row[produkt]'  AND tip = '3'");
$cl2f = mysql_result($re2f, 0);
$cl3f = $cl2f + $cl1f;
$redf = mysql_query("SELECT count(*) from dokstamp WHERE ogrn ='".$row['idkli']."' AND schet = '".$row['rand']."'");
$cladf = mysql_result($redf, 0);
echo '<td style="text-align: center;font-size: 14px;
padding: 0;';
if($cl3f == $cladf){
echo "background:green; color:#fff;";
mysql_query("UPDATE schet SET doki='1' WHERE rand='".$row['rand']."'");
}
echo'">';


echo '<a  onclick="doc'.$row['rand'].'()"><span class="glyphicon glyphicon-open-file"></span></a>
<script type="text/javascript">
function doc'.$row['rand'].'()
{
    var docc = document.getElementById("doccontai'.$row['rand'].'");
    var docd = document.createElement("iframe");
    var doct = document.createTextNode("11111");
    docd.appendChild(doct);
    docc.appendChild(docd);
	docd.src = "/rabotasdoc.php?id='.$row['idkli'].'&schet='.$row['rand'].'&parent='.$row['produkt'].'&inn='.$row['inn'].'&head=0";
	docd.width = "900px";
	docd.height = document.documentElement.clientHeight - 100;
	docd.className = "iframestyle";
	document.getElementById("doccontai'.$row['rand'].'").className = "contai";
}
$(document).ready(function(){
    $("#doccontai'.$row['rand'].'").click(function(){
        $("#doccontai'.$row['rand'].'").empty();
		document.getElementById("doccontai'.$row['rand'].'").className = "";
    });
});
</script>
<div id="doccontai'.$row['rand'].'"></div>
';
echo '</td>';
echo '<td>';
echo '<a  onclick="add'.$row['rand'].'()"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
<script type="text/javascript">
function add'.$row['rand'].'()
{
    var c = document.getElementById("contai'.$row['rand'].'");
    var d = document.createElement("iframe");
    var t = document.createTextNode("11111");
    d.appendChild(t);
    c.appendChild(d);
	d.src = "'.VOOVI_MAIN_URL.'/setschet.php?id='.$row['rand'].'&p=0&kli='.$row['idkli'].'";
	d.width = "900px";
	d.height = document.documentElement.clientHeight - 100;
	d.className = "iframestyle";
	document.getElementById("contai'.$row['rand'].'").className = "contai";
}
$(document).ready(function(){
    $("#contai'.$row['rand'].'").click(function(){
        $("#contai'.$row['rand'].'").empty();
		document.getElementById("contai'.$row['rand'].'").className = "";
    });
});


</script>
<div id="contai'.$row['rand'].'"></div>
';
echo '</td>';
echo '<td id="proddlen'.$row['rand'].'" style="text-align: center;">';
echo '<div id="proleninfo'.$row['rand'].'">';
if($row['prodlen'] == 0){
echo 'Новая';
}else{
echo 'Продлен';
}
echo '</div>';
echo '<select id="prodlen'.$row['rand'].'" name="prodlen'.$row['rand'].'" onchange="proDlen'.$row['rand'].'(this.value)" style="display: none;">';
echo '<option  value=""></option>';
echo '<option  value="0">Новая</option>';
echo '<option  value="1">Продление</option>';
echo '<option  value=""></option>';
echo '</select>';
echo '<script>
$("#proddlen'.$row['rand'].'").live("dblclick", function() {document.getElementById("prodlen'.$row['rand'].'").style.display="block";});
function proDlen'.$row['rand'].'(str) {
if (str=="0") {
$.ajax({
type: "GET",
url: "prodlen.php", 
data: "prodlen="+str+"&rand='.$row['rand'].'",
success: function(msg){
document.getElementById("prodlen'.$row['rand'].'").style.display="none";
setTimeout(function() {$("#proleninfo'.$row['rand'].'").load(" #proleninfo'.$row['rand'].'");}, 1000);}}); 
} else {
$.ajax({type: "GET",url: "prodlen.php",data: "prodlen="+str+"&rand='.$row['rand'].'",success: function(msg){document.getElementById("prodlen'.$row['rand'].'").style.display="none";setTimeout(function() {$("#proleninfo'.$row['rand'].'").load(" #proleninfo'.$row['rand'].'");}, 1000);}});}}
</script>';
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
echo '<select id="generaci'.$row['rand'].'" name="generaci'.$row['rand'].'" onchange="generaciTakti'.$row['rand'].'(this.value)"  style="display:none;" >';
$query21 = mysql_query("SELECT * from users WHERE users_id < '7' ");
echo '<option value="0"></option>';
echo '<option value="546321564">Поставка</option>';
while($row21 = mysql_fetch_array($query21)) {
echo '<option  value="'.$row21['users_id'].'">',$row21['f_name']," ",$row21['l_name'],'</option>';
}
echo '<option value="0"></option>';
echo '</select>';

echo '<script>
$("#generac'.$row['rand'].'").live("dblclick", function() {
document.getElementById("generaci'.$row['rand'].'").style.display="block";
});
function generaciTakti'.$row['rand'].'(str) {if (str=="0") {
$.ajax({   
type: "GET",   
url: "mari.php",   
data: "lico="+str+"&rand='.$row['rand'].'",   
success: function(msg){ 
document.getElementById("generaci'.$row['rand'].'").style.display="none"; 
setTimeout(function() {
$("#generacinfo'.$row['rand'].'").load(" #generacinfo'.$row['rand'].'");}, 1000);   
}});} else {
$.ajax({   
type: "GET",   
url: "mari.php",   
data: "lico="+str+"&rand='.$row['rand'].'",   
success: function(msg){ 
document.getElementById("generaci'.$row['rand'].'").style.display="none"; 
setTimeout(function() {
$("#generacinfo'.$row['rand'].'").load(" #generacinfo'.$row['rand'].'");
}, 1000);   
}});}}
</script>';
echo '</td>';
echo '<td>';
echo "<a href='".VOOVI_MAIN_URL."/kartklient.php?id=".$row['idkli']."'><img style='width: 18px;' src='img/sav.png'></a>";
$idkli = "SELECT * FROM ogrn WHERE inn ='".$row['inn']."' AND kpp ='".$row['kpp']."'";
$ridkli = mysql_query($idkli);
$pidkli = mysql_fetch_array($ridkli);
$Qdelete = mysql_query("UPDATE `schet` SET `idkli` =  '".$pidkli['id']."' WHERE inn ='".$row['inn']."' AND kpp ='".$row['kpp']."' ");
if($row['url'] == "0"){
}else{
echo "&nbsp;&nbsp;<a target='_blank' href='".$row['url']."'><img src='img/ks.png'></a>";
}
echo '</td></tr>';
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
