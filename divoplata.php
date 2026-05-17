<?php
# подключаем конфиг
include 'conf.php';

$pav = "SELECT * FROM schet WHERE rand = $_GET[rand] ORDER BY id DESC";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);





$sch = "SELECT DISTINCT nomerschet,kolichschet,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,podpisant,d,m,y FROM schet WHERE del = '0' AND rand ='$_GET[rand]'  ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult);


$sav1 = "SELECT * FROM produkti WHERE id = '".$schet['produkt']."' ORDER BY id DESC";
$savresult1 = mysql_query($sav1);
$savoir1 = mysql_fetch_array($savresult1);

$sav = "SELECT * FROM uslugi WHERE id = '".$savoir1['parent']."' ORDER BY id DESC";
$savresult = mysql_query($sav);
$savoir = mysql_fetch_array($savresult);





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
</head>
<body>
<?php
//888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888
if(isset($_POST['submitqqq'.$pavoir['rand']])){

$result542353 = mysql_query("SELECT count(*) from kvobop WHERE schet = ".$pavoir['rand']);
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
'".$pavoir['rand']."',
'".$userdata['users_id']."',
'".$pavoir['god'].$pavoir['filial'].$pavoir['otdel'].$pfsdhhgf['id'].$pavoir['nomerschet']."',
'".$class413241324."',
'".$person['ogrn']."',
'".$pavoir['price']."',
'".$personrpod['name']."',
'$_POST[summa]'
)";
mysql_query($u) or die(mysql_error($link));

if($_POST['tipopll'] == 1 || $_POST['tipopll'] == 2){
	$query = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'");
	while($row = mysql_fetch_array($query)) {
		$srpod = "SELECT * FROM tarif WHERE id =".$row['prod'];
		$sresultrpod = mysql_query($srpod);
		$spersonrpod = mysql_fetch_array($sresultrpod);
		//echo $pavoir['rand'].'<br>';
		$us = "INSERT INTO `chec` (
		`rand`,
		`name`,
		`summ`,
		`skidka`,
		`kol`,
		`date`,
		`users_id`,
		`users_fl`,
		`nalog`,
		`nds`,
`sn`,
`kkt`,
`port`,
`obsumm`
		) VALUES (
		'".$pavoir['rand']."',
		'".$spersonrpod['name']."',
		'".$spersonrpod['price']."',
		'".$row['skidka']."',
		'".$row['kvo']."',
		'".$row['data']."',
		'".$userdata['users_id']."',
		'".$userdata['f_name'].' '.$userdata['l_name']."',
		'0',
		'0',
'".$savoir['sn']."',
'".$savoir['kkt']."',
'".$savoir['port']."',
'$_POST[summa]'
		)";
		mysql_query($us) or die(mysql_error($link));
		/*
		echo $spersonrpod['name'].'<br>';
		echo $schet['produkt'].'<br>';
		echo $savoir1['parent'].'<br>';
		echo $savoir['kkt'].'<br>';
		echo $savoir['port'].'<br>';*/
	}

}

}


if($_GET['id']==0){
echo '<div>
<div>
<div>
<div class="modal-header">

<h4 class="modal-title" id="myModalLabel">Квитанции по счету: '.$pavoir['god'].$pavoir['filial'].$pavoir['otdel'].$pavoir['idkli'].$pavoir['nomerschet'].'</h4>
</div>
<div class="modal-body">
';

$query56362 = mysql_query("SELECT * FROM kvobop WHERE schet = '".$pavoir['rand']."'");
while($row12342 = mysql_fetch_array($query56362)) {
    $query56362222 = mysql_query("SELECT * FROM kvobop_tip WHERE id = ".$row12342['tip']."");
    $row123422222 = mysql_fetch_array($query56362222);
    echo $row123422222['name_tip'];

//if($row12342['tip'] == 1){
//echo'Наличные';
//}if($row12342['tip'] == 2){
//echo'Наличные (частично)';
//}if($row12342['tip'] == 3){
//echo'Безналичные';
//}if($row12342['tip'] == 4){
//echo'Безналичные (частично)';
//}if($row12342['tip'] == 5){
//echo'Гарантийное письмо';
//}if($row12342['tip'] == 6){
//echo'Платежное поручение';
//}if($row12342['tip'] == 7){
//echo'Служебное письмо';
//}if($row12342['tip'] == 8){
//echo'Квитанция';
//}
echo'<br>';
if($row12342['tip'] == 1 || $row12342['tip'] == 3){
echo number_format($row12342['polnsumma'], 0, ' ', ' ')," руб. (оплаченно)<br>";
echo " Дата заявления2: ",$row12342['d'],".",$row12342['m'],".",$row12342['y'],"г. <br>";
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
//    <option value="1">Наличные</option>
//<option value="2">Наличные (частично)</option>
//<option value="5">Гарантийное письмо</option><option value="6">Платежное поручение</option><option value="7">Служебное письмо</option><option value="8">Квитанция</option>
echo'
  </div>
</div>
  </div>
</div>';
}if($_GET['id']==1){

echo '<div>
  <div>
<div>
  <div class="modal-header">

<h4 class="modal-title" id="myModalLabel">Оплата счета: '.$pavoir['god'].$pavoir['filial'].$pavoir['otdel'].$pavoir['idkli'].$pavoir['nomerschet'].'</h4>
  </div>
  <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
  <div class="modal-body">
';
echo '
<div class="input-group">
<span class="input-group-addon">Тип оплаты: </span>
<select id="tipopll'.$pavoir['rand'].'" name="tipopll" onclick="tipopl'.$pavoir['rand'].'()" class="form-control">
<option value="3">Безналичные</option>
<option value="4">Безналичные (частично)</option>
<option value="5">Наличные (карта Иван Федорович)</option>
<option value="6">Наличные (карта Майя Исмаиловна)</option>
<option value="7">Наличные (карта Лейла Исмаиловна)</option>
<option value="8">Наличные (карта Алексей Иванович)</option>
<option value="9">Касса № 4</option>
</select>
</div>
<script type="text/javascript">
function tipopl'.$pavoir['rand'].'()
{
if ($("#tipopll'.$pavoir['rand'].' option:selected").val() == "1" || 
$("#tipopll'.$pavoir['rand'].' option:selected").val() == "3"){
document.getElementById("dobfile'.$pavoir['rand'].'").style.display="none";
document.getElementById("dataoff'.$pavoir['rand'].'").style.display="none";
}
	if ($("#tipopll'.$pavoir['rand'].' option:selected").val() == "3" || $("#tipopll'.$pavoir['rand'].' option:selected").val() == "4")
	{
		document.getElementById("submitnal").value = "Создать квитанцию";
	}else{
		document.getElementById("submitnal").value = "Создать квитанцию";  //и напечатать чек
	}
	if ($("#tipopll'.$pavoir['rand'].' option:selected").val() == "2" || $("#tipopll'.$pavoir['rand'].' option:selected").val() == "4")
	{
	document.getElementById("dobfile'.$pavoir['rand'].'").style.display="none";
	document.getElementById("dataoff'.$pavoir['rand'].'").style.display="none";
	document.getElementById("summa'.$pavoir['rand'].'").style.display="";
	document.getElementById("submitnal").val="123";
	}else{
		document.getElementById("summa'.$pavoir['rand'].'").style.display="none";
	}
	} </script>';
echo '<div style="margin-top: 6px;"></div>';
echo '<div id="none-summa'.$pavoir['rand'].'" class="input-group" style="display:none;">
<span class="input-group-addon">Сумма оплаты: </span>
<input type="text" name="summa" class="col-md-12  form-control" value="'.$pavoir['price'].'">
</div>';
echo '<div style="margin-top: 6px;"></div>';
echo '<div id="dataon'.$pavoir['rand'].'" class="input-group" >
<span class="input-group-addon">Дата заявления: </span>
<span class="input-group-addon"> День</span>
<select class="col-md-12 form-control"  type="text" name="dy"  />
<option value="'.date("d").'">'.date("d").'</option><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option>';
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
echo'</option><option value="01">Январь</option><option value="02">Февраль</option><option value="03">Март</option><option value="04">Апрель</option><option value="05">Май</option><option value="06">Июнь</option><option value="07">Июль</option><option value="08">Август</option><option value="09">Сентябрь</option><option value="10">Октябрь</option><option value="11">Ноябрь</option><option value="12">Декабрь</option></select>';
echo '<span class="input-group-addon">Год</span>
<select class="col-md-12 form-control" type="text" name="yy"  />';
for($i=date('Y')-1; $i<=date('Y')+4; $i++){
  if(date('Y') == $i){
    echo '<option selected value="'.$i.'">'.$i.'</option>';
  }else{
    echo '<option value="'.$i.'">'.$i.'</option>';
  }
}
echo '
</select>
</div>';
echo '<div style="margin-top: 6px;"></div>';




echo '<div id="none-dataoff'.$pavoir['rand'].'" class="input-group"  style="display:none;">
<span class="input-group-addon">Дата завершения: </span>
<span class="input-group-addon"> День</span>
<select class="col-md-12 form-control"  type="text" name="do"  />
<option value="'.date("d").'">'.date("d").'</option>
<option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option>';
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
echo'</option><option value="01">Январь</option><option value="02">Февраль</option><option value="03">Март</option><option value="04">Апрель</option><option value="05">Май</option><option value="06">Июнь</option><option value="07">Июль</option><option value="08">Август</option><option value="09">Сентябрь</option><option value="10">Октябрь</option><option value="11">Ноябрь</option><option value="12">Декабрь</option>
</select>';
echo '<span class="input-group-addon">Год</span>
<select class="col-md-12 form-control" type="text" name="yo"  />
';
for($i=date('Y')-1; $i<=date('Y')+4; $i++){
  if(date('Y') == $i){
    echo '<option selected value="'.$i.'">'.$i.'</option>';
  }else{
    echo '<option value="'.$i.'">'.$i.'</option>';
  }
}
echo '
</select>
</div>';
echo '<div style="margin-top: 6px;"></div>';
echo '<div id="none-dataoff2'.$pavoir['rand'].'" class="input-group" style="display:none;">
<span class="input-group-addon">Дата завершения: </span>
<span class="input-group-addon"> +7 дней к дате заявления</span>
</div>';
echo '<div style="margin-top: 6px;"></div>';
echo '<div id="none-dobfile'.$pavoir['rand'].'" class="input-group" style="display:none;">
<span class="input-group-addon">Добавить файл: </span>
<input name="dobfile'.$pavoir['rand'].'" type="file" class="col-md-12 form-control" style="padding-top: 3px;">
</div>';
echo '<div style="margin-top: 6px;"></div>';
echo '<div id="komm'.$pavoir['rand'].'" class="input-group" >
<span class="input-group-addon">Комментарий: </span>
<textarea name="komm" value="" class="form-control" rows="5"></textarea></div>';

echo'
  </div>
  <div class="modal-footer">
<input type="submit" name="submitqqq'.$pavoir['rand'].'" id="submitnal" class="btn btn-primary col-md-12"  value="Создать квитанцию"/>
  </div>
  </form>

</div>
  </div>
</div>';
}
//888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
