<?php
# подключаем конфиг
include 'conf.php';  


$q = "SELECT * FROM ogrn WHERE id =$_GET[id]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);

$qq = "SELECT * from tekkli WHERE inn = '".$person['inn']."' AND kpp = '".$person['kpp']."'";
		$resultt = mysql_query($qq);
		$personn = mysql_fetch_array($resultt);



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
<link rel="shortcut icon" href="/favicon.ico">

</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">
    <div class="col-md-12">
<?php
if(isset($_POST['nksubmit'])){
$q2 = "SELECT * FROM klient WHERE id=(SELECT MAX(id) FROM klient)";
$result2 = mysql_query($q2);
$person2 = mysql_fetch_array($result2);
$lico = "INSERT INTO `klient`(`fio`, `dol`, `tel`, `email`, `pol`) VALUES ('$_POST[fio]', '$_POST[dol]', '$_POST[tel]', '$_POST[email]', '$_POST[pol]')";
mysql_query($lico) or die(mysql_error($links));
$url2 = $person2['id'] + 1;
$ogrnlico = "INSERT INTO `klient_ogrn`(`ogrn`, `klient`)  VALUES ('".$person['ogrn']."', '".$url2."')";
mysql_query($ogrnlico) or die(mysql_error($links));
}
?>
    
<div class="col-md-2">


<div style="margin-bottom: 6px; padding: 0; " class="btn-group col-md-12 ">
  <button type="button" class="btn btn-success dropdown-toggle col-md-12" data-toggle="dropdown" aria-expanded="false">
   <span class="glyphicon glyphicon-plus"></span> Выставить счет <span class="caret"></span>
  </button>
  <ul class='dropdown-menu' role='menu'>
  <?php
		$query32 = mysql_query("SELECT * from uslugi ORDER BY name");	
		while($row32 = mysql_fetch_array($query32)) {

    echo "<li>";
	echo "<a href='./newusluga.php?id=".$_GET['id']."&ogrn=".$person['ogrn']."&parent=".$row32['id']."'>";
	echo $row32['name'];
	echo "</a>";
	echo "</li>";

  }
  ?>
  </ul>
</div> 

<div style="margin-bottom: 6px; padding: 0; " class="btn-group col-md-12 "  role="group">
        <button class="btn btn-danger dropdown-toggle col-md-10" type="button" data-toggle="dropdown" aria-expanded="true">
          <span class="glyphicon glyphicon-earphone"></span> Новый звонок <span class="caret"></span>
        </button>
        <button class="btn btn-danger col-md-2" value="История звонков" type="button">
          <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Изходящий звонок</a></li>
          <li><a href="#">Входящий звонок</a></li>
		  <li class="divider"></li>
		  <li><a href="#">История звоноков</a></li>
        </ul>
      </div>
	  
<div style="margin-bottom: 6px; padding: 0; " class="btn-group col-md-12 ">
        <button class="btn btn-warning dropdown-toggle col-md-12" type="button" data-toggle="dropdown" aria-expanded="true">
          <span class="glyphicon glyphicon-envelope"></span> Написать <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Написать СМС</a></li>
          <li><a href="#">Написать E-mail</a></li>
		  <li class="divider"></li>
		  <li><a href="#">История писем</a></li>
        </ul>
      </div>
	  

<button style="margin-bottom: 6px;" type="button" class="btn btn-primary col-md-12" data-toggle="modal" data-target=".bs-example-modal-lg">
<span class="icon-user4"></span> Новый контакт</button>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="
    width: 600px;
    margin: 30px auto;
">
    <div class="modal-content">
<form action="" method="post">
<div class="col-md-12">
<div style="background: #ddd; padding: 6px; padding-top: 0;margin-top: 10px;border-radius: 5px;"><strong><h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background: #5D75A8;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;padding-bottom: 4px;"><span class="icon-user4"></span> Контактное лицо</h4></strong><div class="input-group">
<span class="input-group-addon">ФИО:</span>
<input class="form-control" name="fio" type="text" id="fio" value="" style="box-sizing: border-box; border-color: rgb(204, 204, 204); border-bottom-right-radius: 4px; border-top-right-radius: 4px; padding-left: 7px;"> 
</div>
<div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Должность:</span> 
<input id="dol" type="text" name="dol"class="form-control" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Телефон:</span> 
<input id="tel" class="form-control" type="text" name="tel" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">E-mail:</span>
<input id="email" class="form-control" type="text"name="email" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<div id="fullname-gender">
<label class="sgt-granular_label" style="font-size: 13px; font-weight: normal;">
<input type="radio" name="pol" id="fullname-gender-male" value="1" class="inline">
Мужской
</label>
<label class="sgt-granular_label" style="
font-size: 13px;
font-weight: normal;
">
<input type="radio" name="pol" id="fullname-gender-female" value="2" class="inline">
Женский
</label>
</div>
</div>
</div>
</div>

<input type="submit" name="nksubmit" value="Зарегестрировать" class="btn btn-primary" role="button" style="
    margin-left: 5px;
    margin-top: -5px;
    padding-top: 10px;
    border: none;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
">

</form>
    </div>
  </div>
</div>

</div>


	 <div class="col-md-5">
	 <h3 style="border-bottom: 3px rgba(255, 255, 255, 0.5) solid;
margin-top: 0;
background: #5CB8A3;
padding: 3px;
font-size: 19px;
color: #fff;">Карточка организации <a href="/kartklientred.php?id=<?php  echo $person['id']; ?>&inn=<?php  echo $person['inn']; ?>" style="
    font-size: 15px;
    float: right;
    margin-top: 2px;
"> <span class="glyphicon glyphicon-cog" style="font-size: 18px; margin-top: -2px; padding-right: 2px;"></span></a></h3>
	 <table class="table">
		<tr>
		<th  style="padding: 1px 5px;" class="col-md-3">Идентификатор клиента:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['id']; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px;">Название:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['naim']; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px;">ОГРН:</th><th style="font-weight: 100; padding: 1px 5px;">
		<?php  echo $person['ogrn']; ?>
		</th>
		</tr>
		<tr>
		<th style="padding: 1px 5px;">ИНН:</th><th style="font-weight: 100; padding: 1px 5px;"><a href="https://focus.kontur.ru/search?query=<?php  echo $person['inn']; ?>"><?php  echo $person['inn']; ?></a></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px;">КПП:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['kpp']; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px;">Действие на оснований:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['naosnovanii']; ?></th>
		</tr>
		<tr>
        <th  style="padding: 1px 5px;">Юридический адрес:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['uridadress'],"<br>"; ?></th>
		</tr>
		<tr>
        <th  style="padding: 1px 5px;">Фактический адрес::</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['fakadress'],"<br>"; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px;">Информация:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo nl2br($person['primechan']),"<br>"; ?></th>
		</tr>
		</table>
		
		

        </div>
        
	 <div class="col-md-5">

			<h3 style="border-bottom: 3px rgba(255, 255, 255, 0.5) solid;
margin-top: 0;
background: #5CB8A3;
padding: 3px;
font-size: 19px;
color: #fff;">Банк</h3>
	 <table class="table">
		<tr>
		<th style="padding: 1px 5px;" class="col-md-3">Бик:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['bik']; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px;">Банк:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['bank'],"<br>"; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px;">Адрес:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['city']," ",$person['adress'],"<br>"; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px;">р/счет:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['r_schet'],"<br>"; ?></th>
		</tr>
		<tr>
		<th  style="padding: 1px 5px;">к/счет:</th><th style="font-weight: 100; padding: 1px 5px;"><?php  echo $person['k_schet'],"<br>"; ?></th>
		</tr>
		</table>
		
		<h3 style="border-bottom: 3px rgba(255, 255, 255, 0.5) solid;
margin-top: 10px;
background: #5CB8A3;
padding: 3px;
font-size: 19px;
color: #fff;">Контакты</h3>
		<table class="table">
		<?php
		$query2 = mysql_query("SELECT * from klient_ogrn WHERE ogrn = '".$person['ogrn']."' ORDER BY id DESC");	
		while($row2 = mysql_fetch_array($query2)) {
		$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");	
		while($row3 = mysql_fetch_array($query3)) {
		echo '<tr>';
		echo '<td>';
		echo 'ФИО: ',$row3['fio'];
		echo '</td>';
		echo '<td>';
		echo 'Должность: ',$row3['dol'];
		echo '</td>';
		echo '<td>';
		echo 'Телефон: ',$row3['tel'];
		echo '</td>';
		echo '<td>';
		echo 'E-mail: ',$row3['email'];
		echo '</td>';
		echo '<td>';
		echo '<li role="presentation" class="dropdown">
        <a id="drop4" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
          <span class="glyphicon glyphicon-earphone"></span>
          <span class="caret"></span>
        </a>
        <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4" style="right: 0;left: inherit;">
          <li role="presentation"><a role="menuitem" tabindex="-1" href="">Позвонить</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="">Принял звонок</a></li>
          <li role="presentation" class="divider"></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href=""><span style="color: red;" class="glyphicon glyphicon-stop" aria-hidden="true"></span> Не дозвонился</a></li>
        </ul>
      </li>';
		echo '</td>';
		echo '<td>';
		echo '<li role="presentation" class="dropdown">
        <a id="drop4" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
          <span class="glyphicon glyphicon-envelope"></span>
          <span class="caret"></span>
        </a>
        <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4" style="right: 0;left: inherit;">
          <li role="presentation"><a role="menuitem" tabindex="-1" href="">Отправить СМС</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="">Отправить E-mail</a></li>
          <li role="presentation" class="divider"></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href=""><span style="color: red;" class="glyphicon glyphicon-stop" aria-hidden="true"></span> Не дошло</a></li>
        </ul>
      </li>';
		echo '</td>';
		echo '</tr>';
		}
		}
		?>
		</table>
	
      </div>  
        
        <div class="col-md-12" style="margin-top: 30px;">
        	<table class="table">
	<thead>
        <tr>
		<th></th>
		  <th class="jazist">Дата</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th class="jazist">Название</th>
		  <th class="jazist">Продукт</th>
		  <th class="jazist">Комментарии</th>
		  <th>Контакты</th>
          <th>Продл./Новый</th>
		  <th class="jazist">Генерация</th>
        </tr>
    </thead>
					<?php
						$query = mysql_query("SELECT DISTINCT nomerschet,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment FROM schet WHERE ogrn = '".$person['ogrn']."' ORDER BY id DESC");	
						
							while($row = mysql_fetch_array($query)) {
echo '<tr for="raz'.$row['rand'].'" style="font-size: 11px; background: #'.$row['color'].';';
if ($row['color'] == '5CB85C' || $row['color'] == 'F0AD4E' || $row['color'] == 'D9534F') {
	echo ' color: #fff;';
}
echo '"><td  style="width: 10px;text-align: center;cursor: pointer;background: ';

if ($row['group'] == NULL) {
	echo '#fff';
} else {
	echo $row['group'];
}

echo ';"> ';
echo '';
echo ' </td>';
echo '<td >';
echo $row['data'];
echo '</td>';
echo '<td >';
echo $row['inn'];
echo '</td>';
echo '<td >';
echo $row['kpp'];
echo '</td>';
echo '<td >';
echo $row['name'];
echo '</td>';
echo '<td >';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td id="komment'.$row['id'].'">
<div id="refresh" style="text-align: left; padding: 4px;">'.$row['koment'].'</div>
<form style="display:none;" id="display'.$row['rand'].'" action="/kartklient.php?id='.$_GET['id'].'" method="post">';
echo '<textarea rows="5" name="editor'.$row['rand'].'" id="editor'.$row['rand'].'" >'.$row['koment'].'</textarea>
<input class="btn btn-success" name="submit'.$row['rand'].'" type="submit" value="Сохранить" >
<input id="otmenit'.$row['rand'].'" class="btn btn-primary" type="submit" value="Отменить" >
</form>
<script>
CKEDITOR.replace( "editor'.$row['rand'].'" );

$("#komment'.$row['id'].'").live("dblclick", function() {

document.getElementById("display'.$row['rand'].'").style.display="block";

});
$("#otmenit'.$row['rand'].'").click(
function(){
document.getElementById("display'.$row['rand'].'").style.display="none";
});

</script>
';
if(isset($_POST['submit'.$row['rand']])){ 
$koment = "UPDATE schet SET `koment`='".$_POST["editor".$row['rand']]."' WHERE rand ='".$row['rand']."' ";
mysql_query($koment) or die(mysql_error($link));
}
echo '</td>';
echo '<td id="konttakt'.$row['rand'].'">';

$lis = "SELECT * FROM klient WHERE id =".$row['lico'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo '<div id="konactinfo'.$row['rand'].'">';
echo $personlis['fio'];
echo '';
echo $personlis['tel'];
echo '';
echo $personlis['email'];
echo '</div>';

echo '<select id="kontakti'.$row['rand'].'" name="kontakti'.$row['rand'].'" onchange="konTakti'.$row['rand'].'(this.value)" style="display: none;">';
$query2 = mysql_query("SELECT * from klient_ogrn WHERE ogrn = '".$person['ogrn']."' ORDER BY id DESC");	
while($row2 = mysql_fetch_array($query2)) {
$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");	
while($row3 = mysql_fetch_array($query3)) {
echo '<option  value="0"></option>';
echo '<option  value="'.$row3['id'].'">',$row3['fio']," (",$row3['dol'],":",$row3['tel'],")",'</option>';
}}
echo '</select>';

echo '<script> 
$("#konttakt'.$row['rand'].'").live("dblclick", function() {
document.getElementById("kontakti'.$row['rand'].'").style.display="block";
});
function konTakti'.$row['rand'].'(str) {
if (str=="0") {
$.ajax({
   type: "GET",
   url: "pusya.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("kontakti'.$row['rand'].'").style.display="none";
	setTimeout(function() {
	$("#konactinfo'.$row['rand'].'").load("#konactinfo'.$row['rand'].'");
	}, 1000);
   }
});
} else {
$.ajax({
   type: "GET",
   url: "pusya.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("kontakti'.$row['rand'].'").style.display="none";
	setTimeout(function() {
	$("#konactinfo'.$row['rand'].'").load("#konactinfo'.$row['rand'].'");
	}, 1000);
   }
});
}}
</script>';
echo '</td>';
echo '<td>';
if($row['prodlen'] == 0){
echo 'Новая';
}else{
echo 'Продлен';
}
echo '</td>';
echo '<td id="generac'.$row['rand'].'">';

$lgenerac = "SELECT * FROM users WHERE users_id =".$row['generac'];
$rgenerac = mysql_query($lgenerac);
$pgenerac = mysql_fetch_array($rgenerac);
echo '<div id="generacinfo'.$row['rand'].'">';
echo $pgenerac['f_name'];
echo ' ';
echo $pgenerac['l_name'];
echo '</div>';

echo '<select id="generaci'.$row['rand'].'" name="generaci'.$row['rand'].'" onchange="generaciTakti'.$row['rand'].'(this.value)"  style="display:none;" >';

echo '<option  value="0"></option>';
$query21 = mysql_query("SELECT * from users WHERE users_id < '7' ");	
while($row21 = mysql_fetch_array($query21)) {
echo '<option  value="'.$row21['users_id'].'">',$row21['f_name']," ",$row21['l_name'],'</option>';
}
echo '</select>';
echo '<script>


$("#generac'.$row['rand'].'").live("dblclick", function() {

document.getElementById("generaci'.$row['rand'].'").style.display="block";

});


 
function generaciTakti'.$row['rand'].'(str) {
if (str=="0") {
$.ajax({
   type: "GET",
   url: "mari.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("generaci'.$row['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#generacinfo'.$row['rand'].'").load(" #generacinfo'.$row['rand'].'");
	}, 1000);
   }
});
} else {
$.ajax({
   type: "GET",
   url: "mari.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("generaci'.$row['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#generacinfo'.$row['rand'].'").load(" #generacinfo'.$row['rand'].'");
	}, 1000);
   }
});
}
}

</script>';
echo '</td></tr>';

							
						} 
					?>
				</table>
        
        </div>
<div class="col-md-12" style="margin-top: 20px;">
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li class="active"><a style="font-weight: bold;" href="#scheta" role="tab" data-toggle="tab">Cчета</a></li>
  <li><a style="font-weight: bold;" href="#dogov" role="tab" data-toggle="tab">Договоры</a></li>
  <li><a style="font-weight: bold;" href="#akti" role="tab" data-toggle="tab">Акты</a></li>
  <li><a style="font-weight: bold;" href="#cpecif" role="tab" data-toggle="tab">Спецификации</a></li>
  <li><a style="font-weight: bold;" href="#kvoo" role="tab" data-toggle="tab">Квитанции об оплате</a></li>
  <li><a style="font-weight: bold;" href="#kakl" role="tab" data-toggle="tab">Карты клиента</a></li>
</ul>

<!-- Tab panes -->

</div><div class="col-md-12" style="margin-top: 10px;float: left;">
   <div class="tab-content">

  <div class="tab-pane active" id="scheta">
  
  

<table id="tab" class="table">
<thead>
<tr>
<th>Создал</th>
<th>Дата</th>
<th>Номер договора</th>
<th>Номер счета</th>
<th>Продукт</th>
<th>Сумма</th>
<th>Оплата</th>
<th><span class="icon-print"></span> Работа с документами</th>
<th><span class="icon-print"></span> Печать</th>
<th><span class="glyphicon glyphicon-eye-open"></span> Открыть</th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,kto,rand FROM schet WHERE ogrn = '".$person['ogrn']."'");
while($row = mysql_fetch_array($query)) {
echo '<tr><td>';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'];
echo '</td>';
echo '<td>';
echo $row['data'];
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$row['nomerschet']; 
echo '</td>';
echo '<td>';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td>';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td style="width: 170px; padding: 2px;">';


			$rpod2345 = "SELECT * FROM kvobop WHERE schet = '".$row['rand']."'";
			$result57657 = mysql_query($rpod2345);
			$row134 = mysql_fetch_array($result57657);

			$query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row134['schet']);
			$person426 = mysql_result($query544, 0);
			echo number_format($person426, 0, ' ', ' ')," руб. ";
if ($person426 != $row['price'] && $person426 > 0 && $person426 < $row['price'] && $person426 != 0) {
echo ' <span class="icon-wrench" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$row['rand'].'"></span> ';
}if ($person426 <= $row['price'] && $person426 != 0) { 
echo ' <span class="glyphicon glyphicon-eye-open" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$row['rand'].'aa"></span> ';
}else{
echo ' <span class="icon-wrench" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$row['rand'].'"></span> ';
}if ($person426 >= $row['price'] && $person426 != $row['price']) {
echo ' <span class="glyphicon glyphicon-eye-open" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$row['rand'].'aa"></span> ';
}
 
echo '</td>';
echo '<td style="';

$q123 = mysql_query("SELECT count(*) FROM dokstamp WHERE schet ='".$row['rand']."' AND status='1'");
$person123 = mysql_result($q123, 0);
if($person123['status'] > 0){
echo "background-color: rgb(240, 76, 76); color: #fff;";
}
if($person123['status'] == 0){
echo "background-color: rgb(100, 192, 74); color: #fff;";
}
echo ' cursor: pointer;">';
echo '<a  href="rabotasdoc.php?id='.$_GET['id'].'&schet='.$row['rand'].'"><span class="icon-print"></span> Работа с документами</a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowss'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span> Печать</a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindows'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span> Открыть</a>';
echo '


<script type="text/javascript">
  function openImageWindowss'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/uliya.php?id='.$row['rand'].'&p=1",src,"width=" + width + ",height=" + height);
  }
</script>

<script type="text/javascript">
  function openImageWindows'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/uliya.php?id='.$row['rand'].'&p=0",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
}
?>
</table>
  
  
<?php
$query = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,kto,rand FROM schet WHERE ogrn = '".$person['ogrn']."'");
while($row = mysql_fetch_array($query)) {


echo '<div class="modal fade" id="myModal'.$row['rand'].'aa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 700px;  margin: 30px auto;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Счет: '.$row['god'].$row['filial'].$row['otdel'].$row['nomerschet'].'</h4>
      </div>
      <div class="modal-body">
        ';

$query56362 = mysql_query("SELECT * FROM kvobop WHERE schet = '".$row['rand']."'");
while($row12342 = mysql_fetch_array($query56362)) {
if($row12342['tip'] == 1 || $row12342['tip'] == 3){
echo number_format($row12342['polnsumma'], 0, ' ', ' ')," руб. (оплаченно)<br>";
}else{
$query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row12342['schet']);
$person426 = mysql_result($query544, 0);
echo "  Оплаченно: <strong>",number_format($row12342['summa'], 0, ' ', ' ')," руб. </strong> ";
if($person426 == $row12342['polnsumma']){
echo "(оплаченно ",number_format($row12342['polnsumma'], 0, ' ', ' '),"руб.)<br>";
}else{
$zcsdcs = $row12342['polnsumma']-$person426;
if($row12342['polnsumma'] < $person426){
$fhfhtjyf = abs($zcsdcs);
echo "(здача: ",number_format($fhfhtjyf, 0, ' ', ' ')," руб. из ",number_format($row12342['polnsumma'], 0, ' ', ' '),"руб.) ";
}else{
echo "(остаток: ",number_format($zcsdcs, 0, ' ', ' ')," руб. из ",number_format($row12342['polnsumma'], 0, ' ', ' '),"руб.) ";
}
}
echo " Дата заявления: ",$row12342['d'],".",$row12342['m'],".",$row12342['y'],"г. <br>";
if($row12342['tip'] != 5 || $row12342['tip'] != 6 || $row12342['tip'] != 7 || $row12342['tip'] != 8){
echo " Дата завершения: ",$row12342['dz'],".",$row12342['mz'],".",$row12342['yz'],"г. <br>";
}
if($row12342['tip'] == 6 || $row12342['tip'] == 8){
$ewfedee = $row12342['d'] + 7;
if($ewfedee > date("j")){
$dsfgrea54 = $row12342['m'] + 1;
echo $ewfedee - date("j"),".";
if($dsfgrea54 > 12){
$rwerwetrf43 = $row12342['y'] + 1;
echo "1.",$rwerwetrf43;
}
}

echo " Дата завершения: ",$row12342['d'],".",$row12342['m'],".",$row12342['y'],"г. <br>";
}
}
}

		echo'
      </div>
    </div>
  </div>
</div>';


echo '<div class="modal fade" id="myModal'.$row['rand'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 700px;  margin: 30px auto;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Счет: '.$row['god'].$row['filial'].$row['otdel'].$row['nomerschet'].'</h4>
      </div>
	  <form  enctype="multipart/form-data"  method="post">
      <div class="modal-body">
        ';
		echo '
		
		
		<div class="input-group">
		<span class="input-group-addon">Тип оплаты: </span>
		<select id="tipopll'.$row['rand'].'" name="tipopll" onclick="tipopl'.$row['rand'].'()" class="form-control">
			
			<option value="1">Наличные</option>
			<option value="2">Наличные (частично)</option>
			<option value="3">Безналичные</option>
			<option value="4">Безналичные (частично)</option>
			<option value="5">Гарантийное письмо</option>
			<option value="6">Платежное поручение</option>
			<option value="7">Служебное письмо</option>
			<option value="8">Квитанция</option>
		</select></div> 
			<script type="text/javascript">
			function tipopl'.$row['rand'].'(){
			if ($("#tipopll'.$row['rand'].' option:selected").val() == "1" || $("#tipopll'.$row['rand'].' option:selected").val() == "3"){
			document.getElementById("dobfile'.$row['rand'].'").style.display="none";
			document.getElementById("dataoff'.$row['rand'].'").style.display="none";
			}if ($("#tipopll'.$row['rand'].' option:selected").val() == "2" || $("#tipopll'.$row['rand'].' option:selected").val() == "4"){
			document.getElementById("dobfile'.$row['rand'].'").style.display="none";
			document.getElementById("dataoff'.$row['rand'].'").style.display="none";
			document.getElementById("summa'.$row['rand'].'").style.display="";
			}else{
			document.getElementById("summa'.$row['rand'].'").style.display="none";
			}
			
			if ($("#tipopll'.$row['rand'].' option:selected").val() == "5" || $("#tipopll'.$row['rand'].' option:selected").val() == "7"){
			document.getElementById("dobfile'.$row['rand'].'").style.display="";
			document.getElementById("dataoff'.$row['rand'].'").style.display="";
			}if ($("#tipopll'.$row['rand'].' option:selected").val() == "6" || $("#tipopll'.$row['rand'].' option:selected").val() == "8"){
			document.getElementById("dobfile'.$row['rand'].'").style.display="";
			document.getElementById("dataoff'.$row['rand'].'").style.display="none";
			document.getElementById("dataoff2'.$row['rand'].'").style.display="";
			}else{
			document.getElementById("dataoff2'.$row['rand'].'").style.display="none";
			}
			} 
			</script>'; 
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="summa'.$row['rand'].'" class="input-group" style="display:none;"> 
		<span class="input-group-addon">Сумма оплаты: </span>
		<input type="text" name="summa" class="col-md-12  form-control" value="'.$row['price'].'">
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="dataon'.$row['rand'].'" class="input-group" >
		<span class="input-group-addon">Дата заявления: </span>
		<span class="input-group-addon"> День</span>
		<select class="col-md-12 form-control"  type="text" name="dy"  />
		<option value="'.date("d").'">'.date("d").'</option>
		<option>01</option>
		<option>02</option>
		<option>03</option>
		<option>04</option>
		<option>05</option>
		<option>06</option>
		<option>07</option>
		<option>08</option>
		<option>09</option>';
		$a = 10;
		$b = date("t");
		while($a <= $b) echo '<option>',$a++,'</option>';
		echo'</select>
		<span class="input-group-addon">Мес.</span>
		<select class="col-md-12 form-control"  type="text" name="my"  />
		<option value="'.date("m").'">';

		if (date("m") == "01") {
		echo "Январь"; 
		} if (date("m") == "02") {
		echo "Февраль"; 
		} if (date("m") == "03") {
		echo "Март"; 
		} if (date("m") == "04") {
		echo "Апрель"; 
		} if (date("m") == "05") {
		echo "Май"; 
		} if (date("m") == "06") {
		echo "Июнь"; 
		} if (date("m") == "07") {
		echo "Июль"; 
		} if (date("m") == "08") {
		echo "Август"; 
		} if (date("m") == "09") {
		echo "Сентябрь"; 
		} if (date("m") == "10") {
		echo "Октябрь"; 
		} if (date("m") == "11") {
		echo "Ноябрь"; 
		} if (date("m") == "12") {
		echo "Декабрь"; 
		}
		echo'</option>
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
		</select>';
		echo '<span class="input-group-addon">Год</span>
		<select class="col-md-12 form-control" type="text" name="yy"  />
		  <option value="2014">2014</option>
		  <option value="2015">2015</option>
		  <option value="2016">2016</option>
		  <option value="2017">2017</option>
		  <option value="2018">2018</option>
		  <option value="2019">2019</option>
		  <option value="2020">2020</option>
		  <option value="2021">2021</option>
		  <option value="2022">2022</option>
		  <option value="2023">2023</option>
		</select>
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		
		
		
		
		echo '<div id="dataoff'.$row['rand'].'" class="input-group"  style="display:none;">
		<span class="input-group-addon">Дата завершения: </span>
		<span class="input-group-addon"> День</span>
		<select class="col-md-12 form-control"  type="text" name="do"  />
		<option value="'.date("d").'">'.date("d").'</option>
		<option>01</option>
		<option>02</option>
		<option>03</option>
		<option>04</option>
		<option>05</option>
		<option>06</option>
		<option>07</option>
		<option>08</option>
		<option>09</option>';
		$a = 10;
		$b = date("t");
		while($a <= $b) echo '<option>',$a++,'</option>';
		echo'</select>
		<span class="input-group-addon">Мес.</span>
		<select class="col-md-12 form-control"  type="text" name="mo"  />
		<option value="'.date("m").'">';

		if (date("m") == "01") {
		echo "Январь"; 
		} if (date("m") == "02") {
		echo "Февраль"; 
		} if (date("m") == "03") {
		echo "Март"; 
		} if (date("m") == "04") {
		echo "Апрель"; 
		} if (date("m") == "05") {
		echo "Май"; 
		} if (date("m") == "06") {
		echo "Июнь"; 
		} if (date("m") == "07") {
		echo "Июль"; 
		} if (date("m") == "08") {
		echo "Август"; 
		} if (date("m") == "09") {
		echo "Сентябрь"; 
		} if (date("m") == "10") {
		echo "Октябрь"; 
		} if (date("m") == "11") {
		echo "Ноябрь"; 
		} if (date("m") == "12") {
		echo "Декабрь"; 
		}
		echo'</option>
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
		</select>';
		echo '<span class="input-group-addon">Год</span>
		<select class="col-md-12 form-control" type="text" name="yo"  />
		  <option value="2014">2014</option>
		  <option value="2015">2015</option>
		  <option value="2016">2016</option>
		  <option value="2017">2017</option>
		  <option value="2018">2018</option>
		  <option value="2019">2019</option>
		  <option value="2020">2020</option>
		  <option value="2021">2021</option>
		  <option value="2022">2022</option>
		  <option value="2023">2023</option>
		</select>
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="dataoff2'.$row['rand'].'" class="input-group" style="display:none;">
		<span class="input-group-addon">Дата завершения: </span>
		<span class="input-group-addon"> +7 дней к дате заявления</span>
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="dobfile'.$row['rand'].'" class="input-group" style="display:none;">
		<span class="input-group-addon">Добавить файл: </span>
		<input name="dobfile'.$row['rand'].'" type="file" class="col-md-12 form-control" style="padding-top: 3px;">
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="komm'.$row['rand'].'" class="input-group" >
		<span class="input-group-addon">Комментарий: </span>
		<textarea name="komm" value="" class="form-control" rows="5"></textarea></div>';
		
		echo'
      </div>
      <div class="modal-footer">
        <input type="submit" name="submit'.$row['rand'].'" class="btn btn-primary col-md-12" value="Создать квитанцию"/>
      </div>
	  </form>
	  
    </div>
  </div>
</div>';

if(isset($_POST['submit'.$row['rand']])){

$dir537 = 'scheta/garant/'.$row['rand'].'/';
	if($_FILES['dobfile'.$row['rand']]["size"] > 1024*10*1024){
		echo "Размер файла превышает 10 мегабайт";
		exit;
	}else{
		if(file_exists($dir537)){
			if(is_dir($dir537)){
			$file = $dir537.basename($_FILES['dobfile'.$row['rand']]['name']);
				if (move_uploaded_file($_FILES['dobfile'.$row['rand']]['tmp_name'], $file)) {
					echo '<br><div class="alert alert-success"> Файл успешно загружен.</div>';
				} else {
					echo "Произошла ошибка";    
				}
			}
		}else{
		$new_dir537 = mkdir ($dir537, 0777);   
			if($new_dir537){
				echo 'Каталог успешно создан';
			}else{
				$cont4653 .= 'Ошибка создания каталога';
				return $cont4653;
			}
		}
	}
	
$result542353 = mysql_query("SELECT count(*) from kvobop WHERE schet = ".$row['rand']);
$class413241324 = mysql_result($result542353, 0) + 1;
$u = "INSERT INTO `kvobop`(
`tip`, 
`dataon`, 
`dataoff`, 
`dz`, 
`mz`, 
`yz`, 
`d`, 
`m`, 
`y`, 
`file`, 
`komm`, 
`schet`, 
`kto`, 
`nschet`, 
`nkvit`, 
`ogrn`, 
`polnsumma`, 
`produkt`, 
`summa`
) VALUES (
'$_POST[tipopll]', 
'$_POST[yy]$_POST[my]$_POST[dy]', 
'$_POST[yo]$_POST[mo]$_POST[do]', 
'$_POST[do]', 
'$_POST[mo]', 
'$_POST[yo]', 
'$_POST[dy]', 
'$_POST[my]', 
'$_POST[yy]', 
'$_POST[dobfile]', 
'$_POST[komm]', 
'".$row['rand']."', 
'".$userdata['users_id']."', 
'".$row['god'].$row['filial'].$row['otdel'].$row['nomerschet']."', 
'".$class413241324."', 
'".$person['ogrn']."', 
'".$row['price']."', 
'".$personrpod['name']."', 
'$_POST[summa]'
)";
mysql_query($u) or die(mysql_error($link));
}
}
?>
  

  
  </div>
  <div class="tab-pane" id="dogov">
   
<table id="tab" class="table" style="font-size: 14px;">
<tr>
<td>
Договор оферты:
</td>
<td style="width: 80px;">
<span class="icon-print"></span> Печать
</td>
<td style="width: 80px;">
<a onclick = "openImageWindowoff(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span> Открыть</a>
<script type="text/javascript">
  function openImageWindowoff(src) {
    var width = 700;
    var height = 800;
    window.open("/dogovora.php?id=<?php echo $person['id'];?>",src,"width=" + width + ",height=" + height);
  }
</script>
</td></tr>
</table>
</br>
  
<table id="tab" class="table">
<thead>
<tr>
<th>Создал</th>
<th>Дата</th>
<th>Номер договора</th>
<th>Продукт</th>
<th>Подписант</th>
<th style="width: 80px;"><span class="icon-print"></span> Печать</th>
<th style="width: 80px;"><span class="glyphicon glyphicon-eye-open"></span> Открыть</th>
</tr>
</thead>




<?php
$query = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,kto,rand,podpisant FROM schet WHERE ogrn = '".$person['ogrn']."'");
while($row = mysql_fetch_array($query)) {
echo '<tr><td>';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'];
echo '</td>';
echo '<td>';
echo $row['data'];
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td>';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';


echo '<td id="konttaktpod'.$row['rand'].'">';
$lis = "SELECT * FROM klient WHERE id =".$row['podpisant'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo '<div id="konactinfos'.$row['rand'].'">';
echo $personlis['fio'];
echo ' (';
echo $personlis['tel'];
echo ') ';
echo $personlis['email'];
echo '</div>';

echo '<select id="kontaktipod'.$row['rand'].'" name="kontaktipod'.$row['rand'].'" onchange="konTakti'.$row['rand'].'(this.value)" style="display: none;">';
$query2 = mysql_query("SELECT * from klient_ogrn WHERE ogrn = '".$person['ogrn']."' ORDER BY id DESC");	
while($row2 = mysql_fetch_array($query2)) {
$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");	
while($row3 = mysql_fetch_array($query3)) {
echo '<option  value="0"></option>';
echo '<option  value="'.$row3['id'].'">',$row3['fio']," (",$row3['dol'],":",$row3['tel'],")",'</option>';
}
}
echo '</select>';
echo '<script> 
$("#konttaktpod'.$row['rand'].'").live("dblclick", function() {
document.getElementById("kontaktipod'.$row['rand'].'").style.display="block";
}); 
function konTakti'.$row['rand'].'(str) {
if (str=="0") {
$.ajax({
   type: "GET",
   url: "podpisant.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("kontaktipod'.$row['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#konactinfos'.$row['rand'].'").load(" #konactinfos'.$row['rand'].'");
	}, 1000);
   }
});
} else {
$.ajax({
   type: "GET",
   url: "podpisant.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("kontaktipod'.$row['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#konactinfos'.$row['rand'].'").load(" #konactinfos'.$row['rand'].'");
	}, 1000);
   }
});
}
}
</script>';
echo '</td>';


echo '<td>';
echo '<span class="icon-print"></span> Печать';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowd'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span> Открыть</a>';
echo '


<script type="text/javascript">
  function openImageWindowd'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/dogovor.php?id='.$row['rand'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
}
?>
</table>
  
  
  
  
  </div>
  <div class="tab-pane" id="akti">
  
  
  
  
  
<table id="tab" class="table">
<thead>
<tr>
<th>Создал</th>
<th>Дата</th>
<th>Номер договора</th>
<th>Номер счета</th>
<th>Продукт</th>
<th>Сумма</th>
<th>Оплата</th>
<th><span class="icon-print"></span> Печать</th>
<th><span class="glyphicon glyphicon-eye-open"></span> Открыть</th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,kto,rand FROM schet WHERE ogrn = '".$person['ogrn']."'");
while($row = mysql_fetch_array($query)) {
echo '<tr><td>';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'];
echo '</td>';
echo '<td>';
echo $row['data'];
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$row['nomerschet']; 
echo '</td>';
echo '<td>';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td>';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<span class="icon-print"></span> Печать';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowa'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span> Открыть</a>';
echo '


<script type="text/javascript">
  function openImageWindowa'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/vika.php?id='.$row['rand'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
}
?>
</table>
  
  
  
  
  </div>
  <div class="tab-pane" id="cpecif">
  
  
  
  
  
  
  
<table id="tab" class="table">
<thead>
<tr>
<th>Создал</th>
<th>Дата</th>
<th>Номер договора</th>
<th>Номер счета</th>
<th>Продукт</th>
<th>Сумма</th>
<th>Оплата</th>
<th><span class="icon-print"></span> Печать</th>
<th><span class="glyphicon glyphicon-eye-open"></span> Открыть</th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,kto,rand FROM schet WHERE ogrn = '".$person['ogrn']."'");
while($row = mysql_fetch_array($query)) {
echo '<tr><td>';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'];
echo '</td>';
echo '<td>';
echo $row['data'];
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$row['nomerschet']; 
echo '</td>';
echo '<td>';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td>';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<span class="icon-print"></span> Печать';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowsp'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span> Открыть</a>';
echo '


<script type="text/javascript">
  function openImageWindowsp'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/specifik.php?id='.$row['rand'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
}
?>
</table>
  
  
  
  
  
  
  
  </div>
  <div class="tab-pane" id="kvoo">
  
  
  
  <table id="tab" class="table">
<thead>
<tr>
<th>Создал</th>
<th>Дата</th>
<th>Продукт</th>
<th>Номер счета</th>
<th>Номер квитанции</th>
<th>Тип оплаты</th>
<th>Сумма</th>
<th><span class="icon-print"></span> Печать</th>
<th><span class="glyphicon glyphicon-eye-open"></span> Открыть</th>
</tr>
</thead>
<?php

$query = mysql_query("SELECT * FROM kvobop WHERE ogrn = '".$person['ogrn']."'");
while($row = mysql_fetch_array($query)) {
echo '<tr><td>';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'];
echo '</td>';
echo '<td>';
echo $row['d'],'.',$row['m'],'.',$row['y'],' ';
echo '</td>';
echo '<td>';
echo $row['produkt'];
echo '</td>';
echo '<td>';
echo $row['nschet'];
echo '</td>';
echo '<td>';
echo $row['nschet']."_".$row['nkvit'];
echo '</td>';
echo '<td>';
if($row['tip'] == 1){
echo "Наличные"; 
}if($row['tip'] == 2){
echo "Наличные (частично)"; 
}if($row['tip'] == 3){
echo "Безналичные"; 
}if($row['tip'] == 4){
echo "Безналичные (частично)"; 
}if($row['tip'] == 5){
echo "Гарантийное письмо"; 
}if($row['tip'] == 6){
echo "Платежное поручение"; 
}if($row['tip'] == 7){
echo "Служебное письмо"; 
}if($row['tip'] == 8){
echo "Квитанция"; 
}
echo '</td>';
echo '<td>';
if($row['tip'] == 1 || $row['tip'] == 3){
echo number_format($row['polnsumma'], 0, ' ', ' ')," руб. (оплаченно)";
}else{
$query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row['schet']);
$person426 = mysql_result($query544, 0);
echo number_format($row['summa'], 0, ' ', ' ')," руб. ";
if($person426 == $row['polnsumma']){
echo "(оплаченно ",number_format($row['polnsumma'], 0, ' ', ' '),"руб.)";
}else{
$zcsdcs = $row['polnsumma']-$person426;
echo "(остаток: ",number_format($zcsdcs, 0, ' ', ' ')," руб. из ",number_format($row['polnsumma'], 0, ' ', ' '),"руб.)";
}
}
echo '</td>';
echo '<td>';
echo '<span class="icon-print"></span> Печать';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowsp'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span> Открыть</a>';
echo '
<script type="text/javascript">
  function openImageWindowsp'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/specifik.php?id='.$row['rand'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
}
?>
</table>
  
  
  
  </div>
  <div class="tab-pane" id="kakl">Карты клиента</div>
</div>   </div>  


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
<br>
        <br>
        <br>
        <br>
		
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>

