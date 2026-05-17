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
<a href="modholmes.php?id=<?php echo $_GET['id']; ?>"><input type="submit" class="btn btn-success" value="<< Назад" style="margin: 5px;"></a>
<strong>Виды деятельности:</strong>

<?php
include 'conn.php';
$q = "SELECT * FROM class_okved WHERE id = $_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
echo $person['zayav'];
?>
</div>




<table class="table">
<?php


$search_name1= mysql_query("SELECT * FROM `okved_ogrn` WHERE `ogrn` = $_GET[id]");
while ($row = mysql_fetch_assoc($search_name1)) {
$search_name2= mysql_query("SELECT * FROM `class_okved` WHERE `code` LIKE '".$row['code']."'");
while ($row2 = mysql_fetch_assoc($search_name2)) {
echo '<tr style="font-size: 12px;"><td style="padding: 5px;margin: 0;vertical-align: middle;">';
echo $row2['name'];
echo '</td>';
echo '<td style="padding:5px;">';
echo $row['code'];
echo '</td>';
echo '<td style="width: 1px;padding: 5px 10px;margin: 0;background: #E52E2E;vertical-align: middle;">';
echo '<a style="color: #fff;" title="Удалить из карточки заявки" href="./delokved.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-remove"></span></a>';
echo '</td></tr>';

}
}
?>
</table>

 
 
<!-- НАЧАЛО Авто подсказки при заполнении -->
<hr>
<table class="table">
<?php
include 'conn.php';
$search_slovo = $_GET['bp'];
if ((isset($search_slovo))) {
$search_name = mysql_query("SELECT * FROM `class_okved` WHERE `code` LIKE '%$search_slovo%'");
if (mysql_num_rows($search_name) != 0) {
while ($row = mysql_fetch_assoc($search_name)) {
echo '<tr style="font-size: 12px;"><td style="width: 1px;padding: 5px 10px;margin: 0;background: #3B984A;vertical-align: middle;">';
echo '<a style="color: #fff;" title="Добавить в карточку заявки" href="./addokved.php?id=' .$_GET['id']. '&bp=' .$row['code']. '"><span class="glyphicon glyphicon-plus"></span></a>';
echo '</td>';
echo '<td style="padding: 5px;margin: 0;vertical-align: middle;">';
echo $row['code'];
echo '</td>';
echo '<td style="padding: 5px;margin: 0;vertical-align: middle;">';
echo $row['name'];
echo '</td></tr>';
}} else {
echo 'Не найдено';
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
$query = mysql_query("SELECT * from class_okved");	
while($row = mysql_fetch_array($query)) {
echo "'";
echo $row['code'];
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