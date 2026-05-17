<?php
# подключаем конфиг
include '../conf.php';# проверка авторизации
$q = "SELECT * FROM uslugi WHERE id=$_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']))
{
setcookie('id', '', time() - 60*24*30*12, '/');
setcookie('hash', '', time() - 60*24*30*12, '/');
setcookie('errors', '1', time() + 60*24*30*12, '/');
header('Location: ../index.php'); exit();
}
}
else
{
setcookie('errors', '2', time() + 60*24*30*12, '/');
header('Location: ../index.php'); exit();
}
?>
<html>
<head> 
<meta charset="utf-8">
<meta http-equiv="Content-Style-Type" content="text/css" />
<link href="../css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<?php
# шапка
include '../header.php';
?>
<div class="container" style="margin-top: 40px;"><div class="row"><?php
if(isset($_POST['submit'])){
$ogrn = "UPDATE `uslugi` SET 
`inn`='$_POST[inn]',
`kpp`='$_POST[kpp]',
`full_name`='$_POST[naim]',
`ogrn`='$_POST[ogrn]',
`uridadress`='$_POST[uridadress]',
`adres`='$_POST[fakadress]',
`r_schet`='$_POST[r_schet]',
`bank`='$_POST[bank]',
`k_schet`='$_POST[k_schet]',
`bik`='$_POST[bik]',
`city`='$_POST[city]',
`adress`='$_POST[adress]',
`primechan`='$_POST[info]'
WHERE id = $_GET[id]";
mysql_query($ogrn) or die(mysql_error($links));
echo '<script type="text/javascript">'; 
echo 'window.location.href="'.$_SERVER['REQUEST_URI'].'";'; 
echo '</script>';
}
?>
<form action="" method="post">
<div class="col-md-12">
<strong><h4 style="border-bottom: 1px #333 solid; font-weight: bold;margin-top: 25px;">Новая организация: <?php echo $person['id']; ?></h4></strong>
</div>
<div class="input-group" style="padding: 0 5px;">
<span class="input-group-addon"style="background: #fff;font-size: 20px;padding: 0 10px;"><span class="icon-search"></span></span>
<input class="form-control" id="party" name="party" value="<?php echo $_GET['inn']; ?>" type="text" style="box-sizing: border-box; padding-left: 7px; padding-right: 7px;height: 36px;border-color: #ccc;border-bottom-right-radius: 4px;border-top-right-radius: 4px;font-size: 18px;">
</div><div style="margin-top: 6px;"></div>
<div class="col-md-6" style="margin-top: 10px;">
<div style="background: #ddd; padding: 6px; padding-top: 0;border-radius: 5px;">
<strong>
<h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background:#5bc0de;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;"><span class="icon-globe"></span> Данные организации</h4></strong>
<div class="input-group">
<span class="input-group-addon">Наименование:</span>
<input class="form-control" name="naim" type="text" id="naim" value='<?php echo $person['full_name']; ?>'>
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ИНН:</span>
<input class="form-control" name="inn" type="text" id="inn" value='<?php echo $person['inn']; ?>' > 
</div><div style="margin-top: 6px;"></div>
<div id="kpp12" class="input-group">
<span class="input-group-addon">КПП:</span>
<input class="form-control" name="kpp" type="text" id="kpp" value='<?php echo $person['kpp']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ОГРН:</span>
<input class="form-control" name="ogrn" type="text" id="ogrn" value='<?php echo $person['ogrn']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Юридический адрес:</span>
<input class="form-control" name="uridadress" type="text" id="uridadress" value='<?php echo $person['uridadress']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Фактический адрес:</span>
<input class="form-control" name="fakadress" type="text" id="fakadress" value='<?php echo $person['adres']; ?>'> 
</div>
</div>
<br>
<div style="background: #ddd; padding: 6px; padding-top: 0;border-radius: 5px;">
<strong><h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background: #5FC964;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;"><span class="glyphicon glyphicon-usd"></span> Банк</h4></strong>
<div class="input-group">
<span class="input-group-addon">Расчетный счет:</span>
<input class="form-control" name="r_schet" type="text" id="r_schet" value='<?php echo $person['r_schet']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">БИК:</span> 
<input id="bik" type="text" size="8"name="bik"class="form-control" value='<?php echo $person['bik']; ?>'/>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Банк:</span> 
<input id="bank" class="form-control" type="text" size="64" name="bank"  value='<?php echo $person['bank']; ?>'/>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Город:</span> 
<input id="city" class="form-control" type="text" size="64" name="city"  value='<?php echo $person['city']; ?>'/>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Адрес:</span> 
<input id="adress" class="form-control" type="text" size="64" name="adress"  value='<?php echo $person['adress']; ?>'/>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Корр./Лиц. cчет:</span> 
<input id="k_schet" class="form-control" type="text" size="20" name="k_schet"  value='<?php echo $person['k_schet']; ?>'/>
</div>
</div>
</div>
<div class="col-md-6" style="margin-top: 10px;">

<table class="table" >
<tr>
<td style="width: 1px;"><input type="checkbox" name="schet" id="schet" <?php
if($person['schet'] == 1){
	echo 'checked';
}
?>>

</td><td>Разрешить создание счета</td>
</tr>
</table>
</div>

<div class="col-md-12" style=" margin-top: 0px; margin-bottom: 10px;">
<br>
<input type="submit" name="submit" value="Редактировать" class="btn btn-primary" role="button"/>
</div>
</div>
</div>
</form>
<link href="https://dadata.ru/static/css/lib/suggestions-4.8.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="https://dadata.ru/static/js/lib/jquery.suggestions-4.8.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("input[id='bik']").keyup(function(){
$.ajax({
dataType: 'json',
url: 'https://htmlweb.ru/service/api.php?bic='+$("input[id='bik']").val()+'&json',
success: function(jsondata){$('#bank').val(jsondata.name.replace(/&quot;/g,'"')); 
$('#city').val(jsondata.city.replace(/&quot;/g,'"'));
$('#adress').val(jsondata.adress.replace(/&quot;/g,'"'));
$('#k_schet').val(jsondata.ks.replace(/&quot;/g,'"'));
}
});
});
});

$("#fio").suggestions({
serviceUrl: "https://dadata.ru/api/v2",
        token: "b955dbc625a2b4895757691d955ff2e983a46f1c",
type: "NAME",
/* Вызывается, когда пользователь выбирает одну из подсказок */
onSelect: function (suggestion) {
var data = suggestion.data;
 
if (data.gender == 'MALE'){
$("#fullname-gender-male").attr('checked',true)
}if (data.gender == 'FEMALE') {
$("#fullname-gender-female").attr('checked',true)
}if (data.gender == 'UNKNOWN') {
}
}
});

$("#party").suggestions({
serviceUrl: "https://dadata.ru/api/v2",
        token: "b955dbc625a2b4895757691d955ff2e983a46f1c",
type: "PARTY",
count: 7,
onSelect: showSelected
});

function join(arr /*, separator */) {
var separator = arguments.length > 1 ? arguments[1] : ", ";
return arr.filter(function(n){return n}).join(separator);
}function showSelected(suggestion) {
var party = suggestion.data;
 
$("#tip").val(
join([party.opf.full + ' "' + party.name.full+'"'], " ")
);

if(suggestion.data.opf.short == null){
	$("#naim").val(suggestion.value);
}else{
	$("#naim").val(suggestion.data.opf.short+' "'+suggestion.data.name.full+'"');
}
$("#uridadress").val(
join([party.address && party.address.value || ""], " ")
);
$("#ogrn").val(
join([party.ogrn], " ")
);
$("#kpp").val(
join([party.kpp], " ")
);
}

$("input[id='inn']").ready(function kpp13(){
number = $("input[id='inn']").val().length;
if (number==12){
document.getElementById("kpp12").style.display="none";
}else{
document.getElementById("kpp12").style.display="";
}
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
