<?php
# подключаем конфиг
include 'conf.php';  # проверка авторизации
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
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-12 ">
<?php
if(isset($_POST['submit'])){
$u = "INSERT INTO `napomin` (`dr`, `mr`, `gr`, `dmg`, `gor`, `chr`, `mir`, `tipz`, `tel`, `mestvs`, `opis`, `users`) VALUES ('$_POST[dr]', '$_POST[mr]', '$_POST[gr]', '$_POST[gr]$_POST[mr]$_POST[dr]', '$_POST[gor]', '$_POST[chr]', '$_POST[mir]', '$_POST[tipz]', '$_POST[tel]', '$_POST[mestvs]', '$_POST[ckeditor]', '$_POST[users]')";

$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.m.Y; H:i:s") ."','Новая задача','".$userdata['users_id']."')";
mysql_query($aktivn) or die(mysql_error($link));
mysql_query($u) or die(mysql_error($link));
echo '<div class="alert alert-success">
  <strong>Удачно!</strong> Новая задача успешно добавлена.
</div>';
$body=file_get_contents("http://sms.ru/sms/send?api_id=513439c3-5ece-a954-e5b2-31b36fe77cbf&to=79097565645&text=".urlencode(iconv("utf-8","utf-8","Новая задача: Манаджар: ".$userdata['users_id']."")));}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;"><a href="/kartklient.php?id=<?php echo $_GET['id']; ?>"><< Счет на "<?php
$q = "SELECT * FROM `ogrn` WHERE ogrn =$_GET[ogrn]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
echo $person['naim'];
?>"</a> <div id="clickme" class="btn btn-success btn-xs">
  Добавить почту...
</div></h4> 


<script>


      $("#clickme").toggle(function() {
        // Отображаем скрытый блок 
        $("#result").fadeIn(); // fadeIn - плавное появление
        return false; // не производить переход по ссылке
      },  
      function() {
        // Скрываем блок 
        $("#result").fadeOut(); // fadeOut - плавное исчезновение 
        return false; // не производить переход по ссылке
      }); // end of toggle() 


</script></strong>


<div id="result" style="display:none;">
<form action="" method=post> 
<div class="input-group"> 
              <span class="input-group-addon">Тип отправки</span> 
              <select type="text" name="tipz"class="form-control" />
<option value="0"></option>
<option value="1">Входящая почта</option>
  <option value="2">Исходящая почта</option>
</select> 
</div>
<div class="input-group"> 
              <span class="input-group-addon">Коментарий</span> 
              <textarea rows="10" name="mess" cols="30"></textarea> 
</div> 
<div class="input-group"> 
			  <script  type="text/javascript">  
var ckeditor = CKEDITOR.replace( "mess" );
AjexFileManager.init({returnTo: "mess", editor: ckeditor});
</script>
              <span class="input-group-addon">Файл</span>
              <input class="form-control" name="attachfile" type="file" size="28"> 
</div> 
<div> 
              <input class="btn btn-success" type="submit" value="Отправить" name="submit">
</div> 
</form> <br>
 </div>
<br>
<div class="bs-example bs-example-tabs">
<div>
<div class="col-md-6" id="">
<li class="active"><a href="#yesterday" role="tab" data-toggle="tab">Входящая почта</a></li>
<table class="table" style="
border: 2px solid #8BC08B;
">
<thead>
<tr>
<th>Дата/Время</th>
<th>Описание</th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT * from napomin WHERE yes = '0'  AND dmg <= '".date("Ymd")."'  AND users = '".$userdata['users_id']."' ORDER BY dmg");
while($row = mysql_fetch_array($query)) {
echo '<tr>';
echo '<td>';
echo '</td>';
echo '<td>';
echo '</td>';
echo '</tr>';
}
?>
</table>
</div>
<div class="col-md-6" id="">
<li><a href="#home" role="tab" data-toggle="tab">Исходящая почта</a></li>
<table class="table" style="
border: 2px solid #8BC08B;
">
<thead>
<tr>
<th>Дата/Время</th>
<th>Описание</th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT * from napomin WHERE yes = '0'  AND dmg <= '".date("Ymd")."'  AND users = '".$userdata['users_id']."' ORDER BY dmg");
while($row = mysql_fetch_array($query)) {
echo '<tr>';
echo '<td>';
echo '</td>';
echo '<td>';
echo '</td>';
echo '</tr>';
}
?>
</table>
  </div>

</div>       
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
</body>
</html>
