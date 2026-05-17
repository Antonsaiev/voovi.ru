<?php
# подключаем конфиг
include 'conf.php';
$q = "SELECT * FROM focus WHERE id =$_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
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
# шапка
include 'header.php';
?>
<div class="container" style="margin-top: 60px;">
<div class="row"><div class="col-md-10 "><a href="zayav.php" style="margin-bottom: 10px;"><input style="margin-bottom: 10px;" type="submit" value="<< Назад" class="btn btn-success"></a><form action="" method="post" style="margin: 0;">
<div class="input-group" style="margin-bottom: 10px;"><span class="input-group-addon">Продукт:</span>

<?php
echo '<select name="produkt" id="produkt" onchange="proDukt(this.value)" class="form-control col-md-12">
';
echo "<option></option>
";
$query1 = mysql_query("SELECT * from uslugi");
while($row1 = mysql_fetch_array($query1)) {
echo "<option value='".$row1['id']."'>".$row1['name']."</option>
";
}
echo '</select>';
?>

</div>
<div class="input-group" id="tarif" style="margin-bottom: 10px;">

</div>

<script>
function proDukt(str) {
$.ajax({
type: "GET",
url: "tarifselect.php", 
data: "id="+str,
success: function(msg){
document.getElementById('tarif').innerHTML=(msg);
}
});
}
</script>
<input class="form-control col-md-12" name="nameTip" placeholder="ФИО" style="margin-bottom: 10px;" />
<div style="margin-top: 6px;"></div>
<input class="form-control col-md-12" name="telTip"placeholder="Телефон" style="margin-bottom: 10px;" />
<div style="margin-top: 6px;"></div>
<input class="form-control col-md-12" name="emailTip" placeholder="Email" style="margin-bottom: 10px;" />
<div style="margin-top: 6px;"></div>
<input class="form-control col-md-12" name="innTorgan"placeholder="organ" style="margin-bottom: 10px;" value="<?php echo $_GET['name']; ?>"/>
<input class="form-control col-md-12" name="innTinn"placeholder="ИНН" style="margin-bottom: 10px;" value="<?php echo $_GET['inn']; ?>"/>
<input class="form-control col-md-12" name="innTkpp"placeholder="КПП" style="margin-bottom: 10px;" value="<?php echo $_GET['kpp']; ?>"/>
<div style="margin-top: 6px;"></div><input type="submit" name="submitTip" value="Создать заявку" class="btn btn-warning col-md-12">
</form>
<?php 
if(isset($_POST['submitTip'])){
 $uDemo = "INSERT INTO `focus` (`produkt`,`data`,`dt`,`name`,`tel`,`email`,`inn`,`tip`,`kto`,`nameogrn`,`kpp`) VALUES ('$_POST[produkt]','".date('d.m.Y в H:i')."','".date('dmY')."','$_POST[nameTip]','$_POST[telTip]','$_POST[emailTip]','$_POST[innTinn]','$_POST[tarif]','".$userdata['users_id']."','$_POST[innTorgan]','$_POST[innTkpp]')";
mysql_query($uDemo) or die(mysql_error($link));
echo '<script type="text/javascript">
   document.location.href = "zayav.php";
</script>';
}
?>
</div>
<?php
# левая колонка сайта
include 'left_sitebar.php';
?></div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>