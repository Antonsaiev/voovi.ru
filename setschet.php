<?php
include 'conf.php';  
$pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult); 
$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf); 
$sch = "SELECT DISTINCT nomerschet,d,m,y,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,podpisant,groupi FROM schet WHERE del = '0' AND rand ='".$_GET['id']."' ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult); 
$kli = "SELECT * FROM ogrn WHERE id =".$_GET['kli'];
$kliresult = mysql_query($kli);
$klient = mysql_fetch_array($kliresult); 
$sav1 = "SELECT * FROM produkti WHERE id = '".$schet['produkt']."' ORDER BY id DESC";
$savresult1 = mysql_query($sav1);
$savoir1 = mysql_fetch_array($savresult1); 
$sav = "SELECT * FROM uslugi WHERE id = '".$savoir1['parent']."' ORDER BY id DESC";
$savresult = mysql_query($sav);
$savoir = mysql_fetch_array($savresult); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div style="margin-bottom: 25px;  font-size: 21px;  background: #26BB84;  text-align: center;  padding: 8px;  color: #fff;">
<?php echo $klient['naim']; ?> <b>(СЧЕТ<?php if($schet['oferta'] == 1){echo  '-ОФЕРТА';} echo ' № '.$schet['god'].$schet['filial'].$schet['otdel'].$pfsdhhgf['id'].$schet['nomerschet'];?>)</b>
</div>
<div class="container">
<div class="row">
<div class="col-md-6">


<p>
	<b>
		<span>
			Дата создания: 
			<i id="dateinfo">
				<?php echo $schet['d'].'.'.$schet['m'].'.'.$schet['y'];?>
			</i>
		</span>
	</b>

	
</p>
<br>
<table class="table  table-striped">
 <thead>
 <tr>
 <td><p><b><span>Наименование</span></b></p></td> 
 <td><p><b><span>Кол-во</span></b></p></td> 
 <td><p><b><span>Цена</span></b></p></td> 
 <td><p><b><span>Сумма</span></b></p></td> 
 </tr>
 </thead> 
<?php
$query = mysql_query("SELECT * FROM schet WHERE rand = '".$_GET['id']."' AND del = '0'");
while($row = mysql_fetch_array($query)) {
echo '<tr>';
echo '<td><span>';
$rpod = "SELECT * FROM tarif WHERE id =".$row['prod'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</span></p></td>';
echo "<td><p><span>";
echo $row['kvo'];
echo '</span></p></td>';
echo "<td><b>";
echo number_format($personrpod['price'], 2, '.', ' ');
echo '</b></td>';
echo "<td><p><span>";
 echo number_format($personrpod['price'] * $row['kvo'], 2, '.', ' ');
echo '</span></p></td></tr>';
}
?>
 <?php 
 if ($pavoir['goroddd'] > 0){
 echo "<tr>
<td><p><span>Выезд</span></p></td>
<td><p><span>"; 
 if($pavoir['goroddd'] == 450){
 echo "Пятигорск";
 } else if($pavoir['goroddd'] == 750){
 echo "КМВ";
 } else if($pavoir['goroddd'] == 1500){
 echo "Георгиевск";
 }else{
 echo $pavoir['goroddd'] / 23;
 }
 echo"</span></p>
 </td>
 <td>
 <p><span>"; 
 if($pavoir['goroddd'] == 450){
 echo number_format(450, 2, '.', ' ');
 } else if($pavoir['goroddd'] == 750){
 echo number_format(750, 2, '.', ' ');
 } else if($pavoir['goroddd'] == 1500){
 echo number_format(1500, 2, '.', ' ');
 }else{
 echo number_format(23, 2, '.', ' ');
 }
 echo"</span></p>
 </td>
 <td>
 <p><span>"; 
 $kvogorod = $pavoir['goroddd'] * $pavoir['kvogorod'];
 echo number_format($kvogorod, 2, '.', ' ');
 echo"</span></p>
 </td>
 </tr>
 ";
 }
 ?>
 <?php 
 if ($pavoir['skidka'] > 0){
 echo "
 <tr>
 <td >
 <p><b><span>Скидка:</span></b></p>
 </td>
 <td>
 <p><span>";echo $pavoir['skidka'];echo" %</span></p>
 </td>";
 echo '<td>';
$drgdfgdw = $pavoir['price'] / 100;
echo number_format($drgdfgdw, 2, '.', ' ');
 echo '</td>';

 echo '<td>';
  $drgdfgd = $pavoir['price'] / 100 * $pavoir['skidka'];
echo number_format($drgdfgd, 2, '.', ' ');
 echo'</td>
 </tr>
 ';
 }
 ?>
 <tr>
 <td >
 <p><b><span>Итого:</span></b></p>
 </td>
 <td>
 </td>
 <td>
 </td>
 <td>
 <p><span><?php echo number_format($pavoir['price'], 2, '.', ' ');?></span></p>
 </td>
 </tr>   
</table> 
<?
if($pavoir['kto']==$_GET['us'])
{
echo '<a onclick="addb()"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Изменить список продуктов</a>'; 
echo '<a onclick="editb()"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Изменить данные</a> ';
}
?>
<a onclick="contaiq()"><div id="contaiq"></div></a> 
<script>
function contaiq()
{
        $("#contaiq").empty();
		document.getElementById("contaiq").className = "";
}
</script>

 <br>  



<div id="konttakt" style="
    background: #eee;
    padding: 2px 10px;
    border: 1px solid #ddd;
">Контактное лицо:</div>
<table class="table">
<tr>
<td>
<?php 
$lis = "SELECT * FROM klient WHERE id =".$_GET['lico'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo '<q id="konactinfo">';
echo $personlis['fio'];
echo ' ';
echo $personlis['tel'];
echo ' ';
echo $personlis['email'];
echo '</q>';

echo '<select id="kontakti" name="kontakti" onchange="konTakti(this.value)" style="display: none;">';
echo '<option  value="0"></option>';
$query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".$_GET['kli']."' ORDER BY id DESC");
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
?></td><td  class="sexodrom" style='width:80px;'>
<a onclick="konttakt()" style="float: right;">Изменить <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
</td>
</tr>
</table>
<br>
<!------------------------------------------------------------------------------------- ----------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------- ----------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------- ----------------------------------------------------------------------------------->
<div style="border:3px solid <?php echo $schet['groupi'];?>">
<?php
if($_GET['gr'] > 0){
	$gr = "WHERE gr = '$_GET[gr]'";
	echo '
	<div style="
    background: #eee;
    padding: 2px 10px;
    border: 1px solid #ddd;
">Связанные счета</div>
	<table class="table tablehover rowclick" id="rowclick2">
<thead>
<tr>
<th style="width: 180px; text-align: center;">Название</th>
<th style="width: 90px; text-align: center;">Продукт</th>
<th >Комментарии</th>
<th style="width: 1px;">Открыть</th>
</tr>
</thead>';
}else{
	$gr = "WHERE gr = '99999999999999999999999999999999'";
}
$query = mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM schet ".$gr." ORDER BY id DESC");

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
echo '>';
echo '<td style="text-align: center;" ';
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
echo '<td id="komment'.$row['rand'].'" '; 
if ($row['doljen'] == 1){echo 'class="alert alert-danger" role="alert"';} 
echo '>'.$row['koment'];
echo '</td>';
echo '<td>';
echo "<a href='".VOOVI_MAIN_URL."/kartklient.php?id=".$row['idkli']."'><img style='width: 18px;' src='img/sav.png'></a>";
$idkli = "SELECT * FROM ogrn WHERE ogrn =".$row['ogrn'];
$ridkli = mysql_query($idkli);
$pidkli = mysql_fetch_array($ridkli);
$Qdelete = mysql_query("UPDATE `schet` SET `idkli` =  '".$pidkli['id']."' WHERE ogrn =".$row['ogrn']);
if($row['url'] == "0"){
}else{
echo "&nbsp;&nbsp;<a target='_blank' href='".$row['url']."'><img src='img/ks.png'></a>";
}
echo '</td></tr>';
}
?>
</table>
</div>
<!---------------------------------------------------------------------------------- ----------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------- ----------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------- ----------------------------------------------------------------------------------->
</div>
<div class="col-md-6">  <table class="table">
 <tr>
 <th style="padding: 1px 5px;" class="col-md-3">Идентификатор клиента:</th><th style="font-weight: 100; padding: 1px 5px;"><?php echo $klient['id']; ?></th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">ОГРН:</th><th style="font-weight: 100; padding: 1px 5px;">
 <?php echo $klient['ogrn']; ?>
 </th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">ИНН:</th><th style="font-weight: 100; padding: 1px 5px;"><a href="https://focus.kontur.ru/search?query=<?php echo $klient['inn']; ?>"><?php echo $klient['inn']; ?></a></th>
 </tr>
 <?php
 if(strlen($klient['inn']) == 10){
  echo'<tr>
 <th style="padding: 1px 5px;">КПП:</th><th style="font-weight: 100; padding: 1px 5px;">'.$klient['kpp'].'</th>
 </tr>';
 }
 ?>
 <tr>
 <th style="padding: 1px 5px;">Юридический адрес:</th><th style="font-weight: 100; padding: 1px 5px;"><?php echo $klient['uridadress'],"<br>"; ?></th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">Фактический адрес::</th><th style="font-weight: 100; padding: 1px 5px;"><?php echo $klient['fakadress'],"<br>"; ?></th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">Информация:</th><th style="font-weight: 100; padding: 1px 5px;"><?php echo nl2br($klient['primechan']),"<br>"; ?></th>
 </tr>
 </table> 
 
 <br>
 
<table id="tab"   class="table tabli" >
<thead>
<tr>
<th>Создал</th>
<th>Дата</th>
<th>Номер договора</th>
<th>Номер счета</th>
<th>Продукт</th>
<th>Оплата КС</th>
<th style="width:1px;"></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,rand,oplachenks,ogrn,idkli,d,m,y,lico,gr FROM schet WHERE del = '0' AND idkli = '".$klient['id']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
echo '<tr>';
$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$row['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);
echo '<td>';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'];
echo '</td>';
echo '<td>';
echo $row['d'].".".$row['m'].".".$row['y']; 
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$pfsdhhgf['id'],$row['nomerdog'];
echo '</td>';
echo '<td>';
echo $row['god'],$row['filial'],$row['otdel'],$pfsdhhgf['id'],$row['nomerschet']; 
echo '</td>';
echo '<td>';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td ';
if ($row['oplachenks'] == 1) { 
echo ' class="highlight_success"';
}
echo'>';
if($row['priceks'] > 0){
echo number_format($row['priceks'], 0, ' ', ' ')," руб.";
echo '<input type="checkbox" value="'.$row['rand'].'"';
if ($row['oplachenks'] == 1) { 
echo 'checked';
}
echo'>';
}else{
}
echo '</td>';
echo '<td class="sexodrom">';
echo '<a href="/setschet.php?id='.$row['rand'].'&p=0&kli='.$row['idkli'].'&lico='.$row['lico'].'&gr='.$row['gr'].'"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span></a>';
echo '</td></tr>'; 
}
?>
</table>
</div>
</div>
</div>
<script>
function konttakt(){
document.getElementById("kontakti").style.display="block";
}
function konTakti(str) {
if (str=="0") {
$.ajax({
type: "GET",
url: "pusya.php",
data: "lico="+str+"&rand=<?php echo $_GET['id']; ?>",
success: function(msg){
$("#konactinfo").html(msg);
document.getElementById("kontakti").style.display="none";
}});
} else {
$.ajax({
type: "GET",
url: "pusya.php",
data: "lico="+str+"&rand=<?php echo $_GET['id']; ?>",
success: function(msg){
$("#konactinfo").html(msg);
document.getElementById("kontakti").style.display="none";
}}
);
}
}



function addb()
{
 var c = document.getElementById("contaiq");
 var d = document.createElement("iframe");
 var t = document.createTextNode("11111");
 d.appendChild(t);
 c.appendChild(d);
	d.src = "/helloogrn.php?id=<?php echo $pfsdhhgf['id']; ?>&ogrn=<?php echo $klient['ogrn']; ?>&parent=3&rand=<?php echo $_GET['id']; ?>&kli=<?php echo $_GET['kli']; ?>";  
	d.width = document.documentElement.clientWidth  - document.documentElement.clientWidth / 6;
	d.height = document.documentElement.clientHeight;
	d.className = "iframestylediv";
	document.getElementById("contaiq").className = "contaidiv";
}

function editb()
{
 var c = document.getElementById("contaiq");
 var d = document.createElement("iframe");
 var t = document.createTextNode("11111");
 d.appendChild(t);
 c.appendChild(d);
	d.src = "/schetdataedit.php?id=<?php echo $pfsdhhgf['id']; ?>&ogrn=<?php echo $klient['ogrn']; ?>&parent=3&rand=<?php echo $_GET['id']; ?>";  
	d.width = document.documentElement.clientWidth  - document.documentElement.clientWidth / 2;
	d.height = document.documentElement.clientHeight;
	d.className = "iframestylediv";
	document.getElementById("contaiq").className = "contaidiv";
}
</script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
</body>
</html>