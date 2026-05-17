<?php
# подключаем конфиг
include 'conf.php';# проверка авторизации
$q = "SELECT * FROM klient WHERE  id=(SELECT MAX(id) FROM klient)";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== &_COOKIE['id']))
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
<link href="blog.css" rel="stylesheet">
</head>
<body>
<?php
# шапка
include 'header.php';
?>
<div class="container" style="margin-top: 40px;"><div class="row">
<?php
?><div class="col-md-12" > 
<div class="row">
<?php
if(isset($_POST['submit'])){
$klient = "INSERT INTO `klient` (`idd`) VALUES ('$_POST[operac]')";
$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.m.Y; H:i:s") ."','Новая организация','".$userdata['users_id']."')";
mysql_query($aktivn) or die(mysql_error($link));
mysql_query($klient) or die(mysql_error($link));
echo '<div class="alert alert-success"><strong>Удачно!</strong> Новая организация успешно добавлена.</div>';
}?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div class="col-md-12">
<strong><h4 style="border-bottom: 1px #333 solid; font-weight: bold;">Новая организация: <?php echo $person['id'] + 1; ?></h4></strong>
<span id="kpp13" class="input-group-addon"></span>
<hr>
</div>
<hr>
<div class="col-md-6">

<div id="inn91" class=" input-group">
  <span class="input-group-addon">Введите ИНН:</span>
  <input class="form-control" name="inn" type="text" id="inn" value="" maxlength="12" required="" pattern="[0-9]{10,12}" style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px;">
  <span id="inn911" class="" style="z-index: 99; margin-top: -3px;"></span>
</div>
<div id="kpp12" class="input-group" style="display:none;">
<span class="input-group-addon">Введите КПП:</span>
<input class="form-control" name="kpp" type="text" id="kpp" value="" maxlength="9" required="" pattern="[0-9]{9}" style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px;"> 
<span id="kpp911" class="" style="z-index: 99; margin-top: -3px;"></span>
</div>
<div style="margin-top: 6px;"></div></div>
<div class="col-md-6">
</div>
<div style="margin-top: 6px;"></div>
<button type="button" name="submit" id="submitbox1"  class="btn btn-success" style="display:none;">Создать</button>
</form></div></div>
</div>
</div><br><br><br>
<script language="javascript">

$(function() {
$("input[id='inn']").keyup(function kpp13(){
number = $("input[id='inn']").val().length;
$("#kpp13").html("Количество введенных символов: "+number);
$("#inn911").load(" #inn911"); 
if (number==12){
document.getElementById("kpp12").style.display="none";
document.getElementById("submitbox1").style.display="";
document.getElementById("inn91").className = 'form-group has-success has-feedback input-group';
document.getElementById("inn911").className = 'glyphicon glyphicon-ok form-control-feedback';
}if (number==11){
document.getElementById("kpp12").style.display="none";
document.getElementById("submitbox1").style.display="none";
document.getElementById("inn91").className = 'form-group has-warning has-feedback input-group';
document.getElementById("inn911").className = 'glyphicon glyphicon-warning-sign form-control-feedback';
}if (number<10){
document.getElementById("kpp12").style.display="none";
document.getElementById("inn91").className = 'form-group has-warning has-feedback input-group';
document.getElementById("inn911").className = 'glyphicon glyphicon-warning-sign form-control-feedback';
document.getElementById("submitbox1").style.display="none";
}if (number==10){
document.getElementById("kpp12").style.display="";
document.getElementById("submitbox1").style.display="none";
document.getElementById("inn91").className = 'form-group has-success has-feedback input-group';
document.getElementById("inn911").className = 'glyphicon glyphicon-ok form-control-feedback';
}
});
});

$(function() {
$("input[id='kpp']").keyup(function kpp13(){
number = $("input[id='kpp']").val().length;
$("#kpp13").html("Количество введенных символов: "+number);
if (number==9){
document.getElementById("submitbox1").style.display="";
document.getElementById("kpp12").className = 'form-group has-success has-feedback input-group';
document.getElementById("kpp911").className = 'glyphicon glyphicon-ok form-control-feedback';
} else {
document.getElementById("submitbox1").style.display="none";
document.getElementById("kpp12").className = 'form-group has-warning has-feedback input-group';
document.getElementById("kpp911").className = 'glyphicon glyphicon-warning-sign form-control-feedback';
}
});
});




$(document).ready(function()
{
$("#submitbox1").click(function()
{if(document.getElementById('inn').value.length>=10){
var inn=$("#inn").val();
var kpp=$("#kpp").val();
$.post("innogrn.php",
					{
						inn:inn,
						kpp:kpp
					}
					,function(data){
					
					
					if(data=='yes'){
					
					document.location.href='/newogrn.php?id=<?php echo $person['id'] + 1; ?>&inn='+inn+'&kpp='+kpp+' ';
					
					}else{
					
					
					$("#kpp13").html('Организация существует: <a type="button"class="btn btn-default btn-xs" href="https://savoir.gmcrm.ru/kartklient.php?id='+data+'">Открыть карточку</a>');
					
					}
					
					
					
					
					});
}if(document.getElementById('inn').value.length < 10){
$("#kpp13").html("Неверный формат ИНН 10/12 символов");

}
});



});


</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>


