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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<!--<link rel="stylesheet" type="text/css" href="css/normalize.css" />
<link rel="stylesheet" type="text/css" href="css/demo.css" />
<link rel="stylesheet" type="text/css" href="css/component.css" /> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

		<!--<script src="js/jquery.ba-throttle-debounce.min.js"></script>
		<script src="js/jquery.stickyheader.js"></script>-->
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

 

<?php
$qrand = "SELECT * FROM `schet` ORDER BY sortir DESC";
					$resultrand = mysql_query($qrand);
					$personrand = mysql_fetch_array($resultrand);
					

$var = $personrand['sortir'] + 1;

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
		$Qdelete = mysql_query("UPDATE `schet` SET `gr` =  '".rand(0,255).rand(0,255).rand(200,255)."', `groupi` =  'rgb(".rand(0,255).", ".rand(0,255).", ".rand(200,255).")', `sortir` =  '".$var."'  WHERE rand IN (".$impid.")") or die ("error in delete");
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
			$Qdelete = mysql_query("UPDATE `schet` SET `doljen` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
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
			$Qdelete = mysql_query("UPDATE `schet` SET `gotov` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
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
			$Qdelete = mysql_query("UPDATE `schet` SET `oplachenks` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
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
			$Qdelete = mysql_query("UPDATE `schet` SET `akt` =  '1',`akt_date` =  '".date('ym')."', `sortir` =  '".$var."', `status`='22' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['akt'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `akt` =  '0',`akt_date` =  '', `status`='' WHERE rand IN (".$impid.")") or die ("error in delete");
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
			$Qdelete = mysql_query("UPDATE `schet` SET `otk` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
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
<p>

<p>
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
$oplachen = "AND oplachenks = '1' AND status = ''";
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
$neoplachen="AND oplachenks != '1' AND oplachen != '1' AND gotov = '0' AND akt = '0'";
}else{
$neoplachen="";
}
if(isset($_GET['turbo'])){
$turbo="AND turbo = '$_GET[turbo]'";
}else{
$turbo="";
}
if(isset($_GET['status'])){
$status="AND status = '$_GET[status]'";
}else{
$status="";
}
if(isset($_GET['moy'])){
$moy="AND kto = '".$userdata['users_id']."'";
}else{
$moy="";
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
$result00 = mysql_query("SELECT COUNT(DISTINCT nomerschet,ogrn,prodlen,status,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) FROM schet WHERE del = '0' AND $getakt $dtakt");
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
<th style="width: 1px;">№</th>
<th style="width: 1px;">Дата</th>
<th style="width: 1px;">ИНН</th>
<th style="width: 1px;">КПП</th>
<th style="width: 180px; text-align: center;">Название</th>
<th style="width: 90px; text-align: center;">Продукт</th>
<th >Комментарии</th>
<th style="width: 1px;"><span class="glyphicon glyphicon-star"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-ruble" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-open-file" aria-hidden="true"></span></th>
<th style="width: 8px; text-align: center;">Контур</span></th>
<th style="width: 8px; text-align: center;">S</th>
<th style="width: 80px; text-align: center;">Услуга</th>
<th style="width: 80px; text-align: center;">Выставил</th>
<th style="width: 1px;">Исполнитель</th>
<th style="width: 1px;">Открыть</th>
</tr>
</thead>
<tbody id="contentArea" class="clusterize-content">
<?php $iz = 1;
$query = mysql_query("SELECT DISTINCT 

ns,
kolichschet,
d,
m,
y,
nomerschet,
nomerschetks,
ogrn,
status,
prodlen,
generac,
name,
lico,
rand,
otdel,
filial,
sortir,
god,
nomerdog,
data,
produkt,
install,
akt_date,
price,
kto,
inn,
kpp,
idkli,
goroddd,
otk,
koment,
oplachen,
oplachenks,
priceks,
doljen,
gotov,
akt,
url, 
groupi,
gr 

FROM schet 
WHERE del = '0' AND $groupi $getakt $turbo $gotov $status $goroddd $akt_date $gen_date $postavka $generac $oplachenks $oplachen $neoplachen $doljen $otk $moy  ORDER BY sortir LIMIT $start, $num ");
echo'<script type="text/javascript">
var c=';
while($row = mysql_fetch_array($query)) {



if (!empty($row['price'])){
echo $row['goroddd'];
}else{
echo '0';
}

echo'+';
}
echo'0;
string = numeral(c).format("0,0");
document.write(string);
</script>';
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
</body>
</html>