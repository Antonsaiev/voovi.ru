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

<div class="container" style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">
<div id="scrollArea" class="clusterize-scroll">
<br><h3 style="border-bottom: 1px #333 solid;"><a href="/leha.php?turbo=1">Текущие клиенты</a></h3> 

<div class="div_macintosh"> 
<?php

$result0 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) FROM schet WHERE del = '0' AND akt='1'");
echo "<a onclick='barak()' class='macintosh btn  btn-xs'>Отгруженные: ".mysql_result($result0, 0)."</a>"; 
$result4 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND gotov='1' AND akt != '1' AND generac != '0'");
echo "<a href='/leha.php?gotov=1&generac=1' class='macintosh btn  btn-xs'> <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Готов к отгрузке: ".mysql_result($result4, 0)."</a>"; 
$result4 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND gotov='1' AND akt != '1' AND generac = '0' AND goroddd > '0'");
echo "<a href='/leha.php?gotov=1&generac=0&goroddd=1' class='macintosh btn  btn-xs'> <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Выезды: ".mysql_result($result4, 0)."</a>"; 
$result1 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND gotov='1' AND akt != '1' AND generac = '0'  AND (goroddd = '0' OR goroddd = '')");
echo "<a href='/leha.php?gotov=1&generac=0&goroddd=0' class='macintosh btn  btn-xs'> <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Готовы к выпуску: ".mysql_result($result1, 0)."</a>"; 
$result55 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND (oplachenks = '1' OR oplachen = '1') AND generac = '546321564' AND akt = '0'");
echo "<a href='/leha.php?generac=546321564&oplachen=1' class='macintosh btn  btn-xs'> <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Поставка: ".mysql_result($result55, 0)."</a>"; 
$result5 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND (oplachenks = '1' OR oplachen = '1') AND gotov = '0'  AND akt = '0'   AND doki = '1'");
echo "<a href='/leha.php?oplachenks=1' class='macintosh btn  btn-xs'> <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> На проверке: ".mysql_result($result5, 0)."</a>"; 
$result2 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND (oplachenks = '1' OR oplachen = '1') AND gotov = '0'  AND akt = '0'   AND doki = '0'");
echo "<a href='/leha.php?oplachenks=0' class='macintosh btn  btn-xs'> <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Оплачен нет доков: ".mysql_result($result2, 0)."</a>"; 
$result3 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND AND oplachen != '1' AND gotov = '0'  AND akt = '0'  AND doki = '0'");
echo "<a href='/leha.php?neoplachen=0' class='macintosh btn  btn-xs'> <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Не оплаченые: ".mysql_result($result3, 0)."</a>"; 
$result4 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND doljen='1'");
echo "<a href='/doljen.php' class='macintoshred btn  btn-xs'> <span class='glyphicon glyphicon-share-alt' aria-hidden='true'></span> Должники: ".mysql_result($result4, 0)."</a>"; 
$result6 = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet WHERE del = '0' AND otk='1'");
echo "<a href='/leha.php?otk=1' class='macintoshgavno btn  btn-xs'> <span class='glyphicon glyphicon-alert' aria-hidden='true'></span> Отказники: ".mysql_result($result6, 0)."</a>"; 

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
echo 'window.location.href="/leha.php?akt=1&akt_date='.$y.''.$m.'";'; 
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

if(isset($_POST['otk'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['otk'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `otk` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['otk'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `otk` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

include 'serchleha.php'; 
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
<?php if($userdata['otvetstven']==1){
echo '
<input  class="btn btn-warning btn-xs" type="submit" name="oplachen" value="Оплата" style="margin-bottom: 5px;" /> - 
<input  class="btn btn-successs btn-xs" type="submit" name="proveren" value="Проверенно" style="margin-bottom: 5px;" /> - 
<input class="btn btn-success btn-xs" type="submit" name="akt" value="Акт отгружен" style="margin-bottom: 5px;" /> - 
';}
?>
<input class="btn btn-gavno btn-xs" type="submit" name="otk" value="Отказались" style="margin-bottom: 5px;" /> - 
<input class="btn btn-default btn-xs" type="reset" id="deleteol" value="Убрать отмеченые" style="margin-bottom: 5px;" /> - 
<?php if($userdata['otvetstven']==1){echo '<input onclick=\'return confirm("Для удаления счета нажмите OK!");\' class="btn btn-primary btn-xs" type="submit" name="deletemarked" value="Удалить" style="margin-bottom: 5px;" />';}?>
<p id="countdisplay" style="color:#fff; display:none;">Выбранно: <b style="font-size:15px;" id="count"></b></p>
</div>
<br>
<br>
<div id="status"></div>
<?php 
$num = 9999999900;
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
$result00 = mysql_query("SELECT COUNT(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr,agent) FROM schet WHERE del = '0' AND $getakt $dtakt");
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
<th>Дата</th>
<th>ИНН</th>
<th>КПП</th>
<th>№Счета</th>
<th style="width: 250px;text-align: center;">Название</th>
<th style=" text-align: center;">Выставил</th>
<th style=" text-align: center;">Агент</th>
<th>Исполнитель</th>
<th>Оплата S</th>
<th>Тип S</th>
<th>ID Тарифа</th>
<th>Тариф</th>
<th>S</th>
</tr>
</thead>
<tbody id="contentArea" class="clusterize-content">
<?php $i = 1;
$query = mysql_query("SELECT * FROM schet WHERE del = '0' AND $groupi $getakt $turbo $gotov $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk ORDER BY id DESC ");
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
	echo '<td style="text-align: center;">';
	$ktolgeneraci = "SELECT * FROM agent WHERE id =".$row['agent'];
	$ktorgeneraci = mysql_query($ktolgeneraci);
	$ktopgeneraci = mysql_fetch_array($ktorgeneraci);
	$kto = $ktopgenerac['name'];
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
	if($pgenerac['tip'] == 1)
	{
	echo 'Наличные'; 
	}
	if($pgenerac['tip'] == 2
    ) {
        echo 'Наличные (частично)';
    }
    if($pgenerac['tip'] == 10)
    {
        echo 'Наличные возврат';
	}
	if($pgenerac['tip'] == 3)
	{
	echo 'Безналичные физ.лицо';
	}
	if($pgenerac['tip'] == 4)
	{
        echo 'Безналичные физ.лицо (частично)';
    }
    if($pgenerac['tip'] == 12)
    {
        echo 'Безналичные возврат карта';
    }
        if($pgenerac['tip'] == 11)
        {
            echo 'Безналичные счет';
        }
        if($pgenerac['tip'] == 13)
        {
            echo 'Возврат Безналичные счет';
        }
	if($pgenerac['tip'] == 5)
	{
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
