<?php
# подключаем конфиг
include 'conf.php';  

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
	<link href="blog.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
		<script>
			var auto_refresh = setInterval(
			function(){
				$('#load_div').fadeOut('slow').load().fadeIn("slow");
			}, 2000);
	</script>
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>


</head>
<body style="margin-top: 60px;">
<?php
# шапка
include 'header.php';  
?>
<div class="container ">
<div class="row">
<?php
# левая колонка сайта
include 'left_sitebar.php';  
?>
<div class="col-md-10">


<div style="font-size: 16px;padding-bottom: 10px;">
<strong>Заявка:</strong>

<?php
include 'conn.php';
$q = "SELECT * FROM iholmes WHERE id = $_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
echo $person['zayav'];
?>
</div>




<table class="table">
<?php


$search_name1= mysql_query("SELECT * FROM `gotovo` WHERE `zayavka` = $_GET[id]");
while ($row = mysql_fetch_assoc($search_name1)) {
$search_name2= mysql_query("SELECT * FROM `organizacii` WHERE `ogrn` = ".$row['ogrn']);
while ($row2 = mysql_fetch_assoc($search_name2)) {


echo '<tr style="font-size: 12px;"><td style="padding: 5px;margin: 0;vertical-align: middle;">';

echo '<a href="modholmes.php?id=',$row2['id'],'">',$row2['pn'],'</a>';


echo '</td>';
echo '<td style="padding:5px;"> Последнее обновление: ';
echo $row2['obnovl'];
echo '</td>';
echo '<td style="width: 1px;padding: 5px 10px;margin: 0;background: #E52E2E;vertical-align: middle;">';
echo '<a style="color: #fff;" title="Удалить из карточки заявки" href="./delzih.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-remove"></span></a>';
echo '</td></tr>';

}
}
?>
</table>
<form  method="post">
<input  type="submit" class="btn btn-success" value="Обработан" name="buttont">
</form>
<?php
if($_POST['buttont']== true){
include 'conn.php';
$q = "SELECT * FROM iholmes WHERE id = $_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
mysql_query("UPDATE iholmes SET status = '2' WHERE id = $_GET[id]"); 
}
 ?>
 
 
<!-- НАЧАЛО Авто подсказки при заполнении -->
<hr>
<table class="table">
<?php

$search_slovo = $_GET['bp'];
if ((isset($search_slovo))) {
$search_name = mysql_query("SELECT * FROM `organizacii` WHERE `serch` LIKE '%$search_slovo%'");
if (mysql_num_rows($search_name) != 0) {
while ($row = mysql_fetch_assoc($search_name)) {
echo '<tr style="font-size: 12px;"><td style="width: 1px;padding: 5px 10px;margin: 0;background: #3B984A;vertical-align: middle;">';
echo '<a style="color: #fff;" title="Добавить в карточку заявки" href="./addzih.php?id=' .$_GET['id']. '&bp=' .$row['ogrn']. '"><span class="glyphicon glyphicon-plus"></span></a>';
echo '</td>';
echo '<td style="padding: 5px;margin: 0;vertical-align: middle;">';
echo $row['pn'];
echo '</td>';
echo '<td style="padding: 5px;margin: 0;vertical-align: middle;">';
echo $row['ogrn'];
echo '</td></tr>';
}} else {
echo 'Не найдено<br>
<h4>Найти в Контур-Фокусе: <a href="https://focus.kontur.ru/search?query=' .$_GET['bp']. '" target="_blank" rel="external">' .$_GET['bp']. '</a></h4>
<a class="col-md-12" href="./oholmes.php" target="_blank">
<button type="button" style="margin-bottom: 7px; width: 100%; padding: 8px 3px;font-size: 12px;" class="btn btn-primary">Добавить организацию</button>
</a>

';
}} 
?>
</table>
<br>
<br>
<form action="" method="GET" name="form">
<div class="input-group">
<span class="input-group-addon">Быстрый поиск:</span>
<input name="bp" type="text" id="autocomplete" class="form-control">
</div>
<div class="input-group" style="display: none;">
<span class="input-group-addon">Индентификатор заявки:</span>
<input name="id" type="text" id="autocomplete" class="form-control" value="<?php echo $_GET['id']; ?>">
</div>
<input type="submit" class="btn btn-success" value="Поиск >>" style="margin: 5px;"><br><br>
</form>
<link href="podskazka/jquery-ui.css" rel="stylesheet">
<script src="podskazka/external/jquery/jquery.js"></script>
<script src="podskazka/jquery-ui.js"></script>
<script>
var availableTags = [
<?php
$query = mysql_query("SELECT * from organizacii");	
while($row = mysql_fetch_array($query)) {
echo "'";
echo $row['serch'];
echo "',";
}
?>
];
$( "#autocomplete" ).autocomplete({
	source: availableTags
});
</script>
<!-- КОНЕЦ Авто подсказки при заполнении -->	
	
	

</div>

</div></div>
			
			
			

<?php
include 'conn.php';
$q = "SELECT * FROM iholmes WHERE id = $_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
if ($person['status'] == 0){
mysql_query("UPDATE iholmes SET status = '1' WHERE id = $_GET[id]"); 
}
 ?>
<?php 
include 'conf.php';
$result21 = mysql_query("SELECT count(*) from aktivn WHERE  vzyal = $_GET[id]");
$vzyal = mysql_result($result21, 0); 
if($vzyal == 0){
$aktivn21 = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`,`vzyal`) VALUES ('". date("d.t.Y; H:i") ."','Начал обработку заявки iholmes','".$userdata['users_id']."','$_GET[id]')"; 
mysql_query($aktivn21) or die(mysql_error($link));
}
?>

</body>
</html>