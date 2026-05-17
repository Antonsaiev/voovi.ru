<form action="" method="GET" name="form" style="
position: fixed;
top: 45px;
z-index: 10;
right: 95px;
background: #424242;
color: #FFF;
border-radius: 4px;
height: 28px;
padding: 1px 8px;
padding-left: 1px;
border: 1px solid #545454;
">

<input type="text" id="texts" name="inn" style="
    border: 2px solid #868686;
    background: #666666;
	border-top-left-radius: 3px;
    border-bottom-left-radius: 3px;
	width: 200px;" placeholder="Введите Название или ИНН">
<input type="submit" value="Поиск" style="
    background: #333;
    margin-right: -7px;
    padding: 4px;
    border: 1px solid #666;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
"><br><br>
</form>
<div id="details">
</div>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
<table class="table rowclick" id="rowclick2">

<?php
$name1 = $_GET['name'];
$inn1 = $_GET['inn'];
$groupi1 = $_GET['groupi'];
if (isset($inn1)) {
$search_name= mysql_query("SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr FROM `schet` WHERE CONCAT(inn,' ',name) LIKE '%$inn1%' AND `gr` LIKE '%$groupi1%' AND akt = '1'");
if (mysql_num_rows($search_name) != 0) {
echo'	<thead>
<tr>
<th style="width: 1px;"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></th>
<th style="width: 1px;">Дата</th>
<th style="width: 1px;">ИНН</th>
<th style="width: 1px;">КПП</th>
<th style="width: 180px; text-align: center;">Название</th>
<th style="width: 100px; text-align: center;">Продукт</th>
<th >Комментарии</th>
<th style="width: 180px;">Контакты</th>
<th style="width: 10px;">Оплата</th>
<th style="width: 60px; text-align: center;">Услуга</th>
<th style="width: 1px;">Исполнитель</th>
<th style="width: 1px;">Открыть</th>
</tr>
    </thead>';
while ($roww = mysql_fetch_assoc($search_name)) {
echo '<tr for="raz'.$roww['rand'].'" style="font-size: 11px;" ';
if ($roww['akt'] == 1){
echo 'class="alert alert-success" role="alert"';
} if ($roww['oplachenks'] == 1 || $roww['oplachen'] == 1){
echo 'class="alert alert-warning" role="alert"';
}
echo '><td style="width: 10px;text-align: center;cursor: pointer;"> ';
echo '<input type="checkbox" name="id[]" id="raz'.$roww['rand'].'"  value="'.$roww['rand'].'">';
echo ' </td>';
echo '<td id="svyaz'.$roww['gr'].'" style="width: 1px;text-align: center; background: '.$roww['groupi'].';">';


echo '
<script type="text/javascript">
	$("#svyaz'.$roww['gr'].'").live("dblclick", function() {
		document.location.href = "/toha.php?name=&inn=&kpp=&groupi='.$roww['gr'].'";
	});
</script>
';
echo '</td>';
$mess = substr($roww['data'], 0, 9);
echo '<td>'.$mess.'</td>';
echo '<td >';
echo $roww['inn'];
echo '</td>';
echo '<td >';
echo $roww['kpp'];
echo '</td>';
echo '<td style="text-align: center;" ';
if ($roww['gotov'] > 0){  
echo 'class="alert alert-success" role="alert"';
}
echo '>';
echo $roww['name'];
echo '</td>';
echo '<td style="text-align: center;">';
$rpod = "SELECT * FROM produkti WHERE id =".$roww['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="-moz-user-select: -moz-none;  -o-user-select: none;  -khtml-user-select: none;  -webkit-user-select: none;  user-select: none;" id="komment'.$roww['rand'].'" '; 
if ($roww['doljen'] == 1){echo 'class="alert alert-danger" role="alert"';} 
echo '><div id="refresh'.$roww['rand'].'" style="text-align: left;" >'.$roww['koment'].'</div>
<div style="display:none;" id="display'.$roww['rand'].'">';
echo '<textarea rows="5" name="editor'.$roww['rand'].'" id="editor'.$roww['rand'].'" >'.$roww['koment'].'</textarea>
<input class="btn btn-success" name="submitqad'.$roww['rand'].'" type="submit" value="Сохранить" >
<div id="otmenit'.$row['rand'].'" class="btn btn-primary" value="Отменить" >Отменить</div>
</div>
<script  type="text/javascript">
$( "#komment'.$row['rand'].'" ).dblclick(function() {document.getElementById("display'.$row['rand'].'").style.display="block";
var ckeditor'.$roww['rand'].' = CKEDITOR.replace( "editor'.$roww['rand'].'" ).config.toolbarGroups = [
    { name: "tools" },
    { name: "others" },
    { name: "basicstyles", groups: [ "basicstyles", "cleanup" ] },
    { name: "colors" }
];
AjexFileManager.init({returnTo: "ckeditor", editor: ckeditor'.$roww['rand'].'});
});
$("#otmenit'.$roww['rand'].'").click(
function(){
document.getElementById("display'.$roww['rand'].'").style.display="none";
});

</script>
';

echo '</td>';
echo '<td id="konttakt'.$roww['rand'].'">';

$lis = "SELECT * FROM klient WHERE id =".$roww['lico'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo '<div id="konactinfoq'.$roww['rand'].'">';
echo $personlis['fio'];
echo ' ';
echo $personlis['tel'];
echo ' ';
echo $personlis['email'];
echo '</div>';

echo '<select id="kontakti'.$roww['rand'].'" name="kontakti'.$roww['rand'].'" onchange="konTakti'.$roww['rand'].'(this.value)" style="display: none;">';
echo '<option  value="0"></option>';
$query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".$roww['idkli']."' ORDER BY id DESC");	
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
$("#konttakt'.$roww['rand'].'").live("dblclick", function() {
document.getElementById("kontakti'.$roww['rand'].'").style.display="block";
});
function konTakti'.$roww['rand'].'(str) {
if (str=="0") {
$.ajax({
   type: "GET",
   url: "pusya.php",
   data: "lico="+str+"&rand='.$roww['rand'].'",
   success: function(msg){
	 document.getElementById("kontakti'.$roww['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#konactinfoq'.$roww['rand'].'").load(" #konactinfoq'.$roww['rand'].'");
	}, 1000);
   }
});
} else {
$.ajax({
   type: "GET",
   url: "pusya.php",
   data: "lico="+str+"&rand='.$roww['rand'].'",
   success: function(msg){
	 document.getElementById("kontakti'.$roww['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#konactinfoq'.$roww['rand'].'").load(" #konactinfoq'.$roww['rand'].'");
	}, 1000);
   }
});
}
}

</script>';
echo '</td>';


echo '<td style="width: 10px; padding: 2px;">';
if ($roww['nomerdog']=="В КС"){}else{

			$rpod2345 = "SELECT * FROM kvobop WHERE schet = '".$roww['rand']."'";
			$result57657 = mysql_query($rpod2345);
			$row134 = mysql_fetch_array($result57657);

			$query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row134['schet']);
			$person426 = mysql_result($query544, 0);
if ($person426 != $roww['price'] && $person426 > 0 && $person426 < $roww['price'] && $person426 != 0) {
echo ' <span class="icon-wrench" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$roww['rand'].'"></span> ';
}if ($person426 <= $roww['price'] && $person426 != 0) { 
echo ' <span class="glyphicon glyphicon-eye-open" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$roww['rand'].'aa"></span> ';
}else{
echo ' <span class="icon-wrench" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$roww['rand'].'"></span> ';
}if ($person426 >= $roww['price'] && $person426 != $roww['price']) {
echo ' <span class="glyphicon glyphicon-eye-open" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$roww['rand'].'aa"></span> ';
}
 }
echo '</td>';


echo '<td id="proddlen'.$roww['rand'].'" style="text-align: center;">';
echo '<div id="proleninfoq'.$roww['rand'].'">';
if($roww['prodlen'] == 0){
echo 'Новая';
}else{
echo 'Продлен';
}
echo '</div>';
echo '<select id="prodlen'.$roww['rand'].'" name="prodlen'.$roww['rand'].'" onchange="proDlen'.$roww['rand'].'(this.value)" style="display: none;">';
echo '<option  value=""></option>';
echo '<option  value="0">Новая</option>';
echo '<option  value="1">Продление</option>';
echo '<option  value=""></option>';
echo '</select>';
echo '<script> 


$("#proddlen'.$roww['rand'].'").live("dblclick", function() {

document.getElementById("prodlen'.$roww['rand'].'").style.display="block";

});


 
function proDlen'.$roww['rand'].'(str) {
if (str=="0") {
$.ajax({
   type: "GET",
   url: "prodlen.php",
   data: "prodlen="+str+"&rand='.$roww['rand'].'",
   success: function(msg){
	 document.getElementById("prodlen'.$roww['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#proleninfoq'.$roww['rand'].'").load(" #proleninfoq'.$roww['rand'].'");
	}, 1000);
   }
}); 
} else {
$.ajax({
   type: "GET",
   url: "prodlen.php",
   data: "prodlen="+str+"&rand='.$roww['rand'].'",
   success: function(msg){
	 document.getElementById("prodlen'.$roww['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#proleninfoq'.$roww['rand'].'").load(" #proleninfoq'.$roww['rand'].'");
	}, 1000);
   }
});
}
}

</script>';

echo '</td>';
echo '<td id="generac'.$roww['rand'].'">';





$qdsafsd = mysql_query("SELECT SUM(kvo) FROM schet WHERE gen = '1' AND rand ='".$roww['rand']."' GROUP BY rand");
$pedfsbfdb = mysql_result($qdsafsd, 0);
echo (int)$pedfsbfdb;



$lgenerac = "SELECT * FROM users WHERE users_id =".$roww['generac'];
$rgenerac = mysql_query($lgenerac);
$pgenerac = mysql_fetch_array($rgenerac);
echo '<div id="generacinfoq'.$roww['rand'].'">';
if($roww['generac']!=546321564){
	$gen = $pgenerac['f_name'];
	echo mb_substr($gen,0,1,'UTF-8'),'. ';
	echo $pgenerac['l_name'];
}else{
	echo"Готов";
}
echo ' </div>';
echo '<select id="generaci'.$roww['rand'].'" name="generaci'.$roww['rand'].'" onchange="generaciTakti'.$roww['rand'].'(this.value)"  style="display:none;" >';
$query21 = mysql_query("SELECT * from users WHERE users_id < '7' ");	
echo '<option value="0"></option>';
echo '<option value="546321564">Готов к отгрузке</option>';
while($row21 = mysql_fetch_array($query21)) {
echo '<option  value="'.$row21['users_id'].'">',$row21['f_name']," ",$row21['l_name'],'</option>';
}
echo '<option value="0"></option>';
echo '</select>';

echo '<script>
 

$("#generac'.$roww['rand'].'").live("dblclick", function() {
document.getElementById("generaci'.$roww['rand'].'").style.display="block";
});

function generaciTakti'.$roww['rand'].'(str) {
if (str=="0") {
$.ajax({
   type: "GET",
   url: "mari.php",
   data: "lico="+str+"&rand='.$roww['rand'].'",
   success: function(msg){
	 document.getElementById("generaci'.$roww['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#generacinfoq'.$roww['rand'].'").load(" #generacinfoq'.$roww['rand'].'");
	}, 1000);
	
   }
});
} else {
$.ajax({
   type: "GET",
   url: "mari.php",
   data: "lico="+str+"&rand='.$roww['rand'].'",
   success: function(msg){
	 document.getElementById("generaci'.$roww['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#generacinfoq'.$roww['rand'].'").load(" #generacinfoq'.$roww['rand'].'");
	}, 1000);
   }
});
}
}

</script>';
echo '</td>';
echo '<td>';
echo "<a href='".VOOVI_MAIN_URL."/kartklient.php?id=".$roww['idkli']."'>Открыть</a>";
$idkli = "SELECT * FROM ogrn WHERE ogrn =".$roww['ogrn'];
$ridkli = mysql_query($idkli);
$pidkli = mysql_fetch_array($ridkli);
$Qdelete = mysql_query("UPDATE `schet` SET `idkli` =  '".$pidkli['id']."' WHERE ogrn =".$roww['ogrn']);


if($roww['url'] == "0"){

}else{
	echo "<br><a target='_blank' href='".$roww['url']."'>(В_КС)</a>";
}
echo '</td></tr>';



}
} else {
echo "Ничего не найдено";
}
} 
?>
</table>
