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

$lis = "SELECT * FROM napomin WHERE id =".$_GET['id'];
$resultlis = mysql_query($lis);
$person = mysql_fetch_array($resultlis);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="blog.css" rel="stylesheet"></head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-12">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Отложить выполнение задачи на:</h4></strong>
<div style="margin-top: 6px;"></div>
<span>Дата:</span>
<select style="margin-bottom: 0px;"  type="text" name="dr"  />
<option value="<?php echo date("d"); ?>"><?php echo date("d"); ?></option>
<option>01</option>
<option>02</option>
<option>03</option>
<option>04</option>
<option>05</option>
<option>06</option>
<option>07</option>
<option>08</option>
<option>09</option>
<?php 
$a = 10;
$b = date("t");
while($a <= $b) echo '<option>',$a++,'</option>';
?>
</select>
<span >:</span>
<select style="margin-bottom: 0px;"  type="text" name="mr"  />
<option value="<?php echo date("m"); ?>">
<?php 
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
?>
</option>
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
</select><span >:</span>
<select style="margin-bottom: 0px;" type="text" name="gr"  />
  <option value="2016">2016</option>
  <option value="2017">2017</option>
  <option value="2018">2018</option>
  <option value="2019">2019</option>
  <option value="2020">2020</option>
  <option value="2021">2021</option>
  <option value="2022">2022</option>
  <option value="2023">2023</option>
</select><span >Время:</span>
<select style="margin-bottom: 0px;"  type="text" name="chr"  />
<option value="<?php echo date("H"); ?>"><?php echo date("H"); ?></option>
  <option value="01">01</option>
  <option value="02">02</option>
  <option value="03">03</option>
  <option value="04">04</option>
  <option value="05">05</option>
  <option value="06">06</option>
  <option value="07">07</option>
  <option value="08">08</option>
  <option value="09">09</option>
  <option value="10">10</option>
  <option value="11">11</option>
  <option value="12">12</option>
  <option value="13">13</option>
  <option value="14">14</option>
  <option value="15">15</option>
  <option value="16">16</option>
  <option value="17">17</option>
  <option value="18">18</option>
  <option value="19">19</option>
  <option value="20">20</option>
  <option value="21">21</option>
  <option value="22">22</option>
  <option value="23">23</option>
  <option value="24">24</option>
</select><span>:</span>
<select style="margin-bottom: 0px;" type="text" name="mir"  />
<option value="<?php echo date("i"); ?>"><?php echo date("i"); ?></option>
<option value="00">00</option>
<option value="05">05</option>
<option value="10">10</option>
  <option value="15">15</option>
  <option value="20">20</option>
  <option value="25">25</option>
  <option value="30">30</option>
  <option value="35">35</option>
  <option value="40">40</option>
  <option value="45">45</option>
  <option value="50">50</option>
  <option value="55">55</option>
</select>
<div style="margin-top: 6px;"></div>
Отдать: <select type="text" name="users" style="font-size: 15px;" />
<option value="<?php echo $userdata['users_id']; ?>"><?php echo $userdata['f_name']," ",$userdata['l_name']; ?></option>
<?php
$querysas = mysql_query("SELECT * from users WHERE users_id != '".$userdata['users_id']."' AND tip != '88'");
while($rowsas = mysql_fetch_array($querysas)) {
echo "<option value='".$rowsas['users_id']."'>";
echo $rowsas['f_name']," ",$rowsas['l_name'];
echo "</option>";
}?>

</select>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Изменить:</span>
 <textarea name="opis" class="form-control" rows="30"><?php echo $person['opis']; ?></textarea>
</div>
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" /><br>
<input type="submit" name="submit" value="Добавить" id="submitSuggestion" class="btn btn-success" /><br>
</form>
<?php
if(isset($_POST['submit'])){
$u = "UPDATE `napomin` SET `dr`='$_POST[dr]',`mr`='$_POST[mr]',`gr`='$_POST[gr]', `dmg`='$_POST[gr]$_POST[mr]$_POST[dr]',`chr`='$_POST[chr]',`mir`='$_POST[mir]',`opis`='$_POST[opis]',`users`='$_POST[users]' WHERE id=$_POST[id]";
mysql_query($u) or die(mysql_error($link));
echo '<script type="text/javascript">'; 
echo 'window.location.href="/napomin.php";'; 
echo '</script>';

}
?>
      

</div>


</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
