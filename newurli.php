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
<link href="blog.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico"><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<script>
var auto_refresh = setInterval(
function(){
$('#load_div').fadeOut('slow').load().fadeIn("slow");
}, 2000);
</script>
</head>
<body>
<?php
# шапка
include 'header.php';
?>

<div class="container" style="margin-top: 60px;">
<div class="row"><div class="col-md-12">
<?php 
if(isset($_POST['submitTip'])){
 $uDemo = "INSERT INTO `urli` (`name`,`url`,`text`,`access`) VALUES ('$_POST[name]','$_POST[url]','$_POST[text]','$_POST[ogrn]')";
mysql_query($uDemo) or die(mysql_error($link));
echo '<div class="alert alert-success" role="alert">
      <strong>Ссылка успешно созданна</strong> '.$_POST['name'].'
    </div><br>';
}
?>
<form action="" method="post" style="margin: 0;">
<strong><h4 style="margin-top: 0px; font-weight: bold; border-bottom: 1px #333 solid;">Новая ссылка</h4></strong>
<div class="input-group">
<span class="input-group-addon">Организация:</span>
<select name="ogrn" class="form-control"  style="display: block;">
<?php
		$query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."'");	
		while($row214 = mysql_fetch_array($query214)) {
		$query32 = mysql_query("SELECT * from uslugi  WHERE del = '0' AND id = '".$row214['uslugi']."' ORDER BY name ");	
		while($row32 = mysql_fetch_array($query32)) {

    echo "<option value='".$row32['id']."'>";
	echo $row32['name'];
	echo "</option>";
  }
  }
  ?>
</select>
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Краткое название:</span>
<input class="form-control col-md-12" name="name"/>
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Ссылка: с http(s)://</span>
<input class="form-control col-md-12" name="url"/>
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Описание:</span>
 <textarea name="text" class="form-control" rows="3"></textarea>
</div><div style="margin-top: 6px;"></div>
<input type="submit" name="submitTip" value="Создать ссылку" class="btn btn-warning "/>
</form>

<table class="table" style="border: 2px solid #8BC08B; margin-top: 10px;">
<thead>
<tr>
<th>Краткое название</th>
<th>Ссылка</th>
<th>Описание</th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT * from urli ");
while($row = mysql_fetch_array($query)) {			
	$udos = mysql_query("SELECT * FROM users_access WHERE users = '".$userdata['users_id']."' AND uslugi = '".$row['access']."'");
	while($udostup = mysql_fetch_array($udos)) {	
echo '<tr><td>';
echo $row['name'];
echo '</td>';
echo '<td>';
echo $row['url'];
echo '</td>';
echo '<td>';
echo $row['text'];
echo '</td></tr>';}}
?>
</table>
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