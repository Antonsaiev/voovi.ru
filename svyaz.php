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
<?php
//Если форма отправлена
if(isset($_POST['submit'])) {

	//Проверка Поля ИМЯ
	if(trim($_POST['contactname']) == '') {
		$hasError = true;
	} else {
		$name = trim($_POST['contactname']);
	}

	//Проверка поля ТЕЛЕФОН
	if(trim($_POST['subject']) == '') {
		$hasError = true;
	} else {
		$subject = trim($_POST['subject']);
	}

	//Если ошибок нет, отправить email
	if(!isset($hasError)) {
		$emailTo = 'gmrxmax@yandex.ru'; //Место для email
		$body = "Имя: $name; \r\nТелефон: $subject;";
		$headers = 'From: GMCMS  <'.$emailTo.'>' . "\n\n" . 'Reply-To: ' . $email;

		mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
	}
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
<div class="row">

<div class="col-md-6">

<div class="bs-example">
   
<h3 style="border-bottom: 1px #333 solid;">Выберите организацию для привязки</h3>




<form action="" method="GET" name="form">
<input type="text" name="id"  value="<?php echo $_GET['id']; ?>" style="display: none;" />
<input type="text" name="svyaz"  value="<?php echo $_GET['svyaz']; ?>" style="display: none;" />
Название: <input type="text" name="naim"  value="<?php echo $_GET['naim']; ?>" />  ИНН: <input type="text" name="inn"  value="<?php echo $_GET['inn']; ?>"  /> КПП: <input type="text" name="kpp"  value="<?php echo $_GET['kpp']; ?>"  /> ОГРН: <input type="text" name="ogrn" value="<?php echo $_GET['ogrn']; ?>"   />
<input type="submit" value="Поиск"><br><br>
</form>


<table class="table tablehover">

<?php

$search_slovo = $_GET['naim'];
$search_slovo1 = $_GET['inn'];
$search_slovo2 = $_GET['kpp'];
$search_slovo4 = $_GET['ogrn'];
$search_slovo3 = $_GET['email'];



if ((isset($search_slovo1))) {
		echo'<thead>
        <tr>
		<th>id</th>
		<th style="width: 350px;">Название</th>
		  <th style="width: 100px;">ИНН</th>
          <th style="width: 100px;">КПП</th>
          <th style="width: 100px;">ОГРН</th>
          <th style="width: 100px;"></th>
        </tr>
    </thead>';
$search_name= mysql_query("SELECT * FROM `ogrn` WHERE CONCAT(naim,' ',inn) LIKE '%$search_slovo1%' AND `naim` LIKE '%$search_slovo%'  AND `kpp` LIKE '%$search_slovo2%' AND `ogrn` LIKE '%$search_slovo4%' AND `email` LIKE '%$search_slovo3%' LIMIT 40");
if (mysql_num_rows($search_name) != 0) {
while ($row = mysql_fetch_assoc($search_name)) {



					
						
								echo '<tr  id="open'.$row['id'].'"  style="font-size: 12px;"><td style="width: 50px; text-align: center;"><strong>';
								echo $row['id'];
								echo '</strong></td>';
								echo '<td  style="width: 350px;">';
								echo $row['naim'];
								echo '<script>
$("#open'.$row['id'].'").live("dblclick", function() {
window.location.href="./kartklient.php?id='.$row['id'].'";
});
</script></td>';
								echo '<td style="width: 100px;">';
								echo $row['inn'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['kpp'];
								echo '</td>';
								echo '<td>';
								echo $row['ogrn'];
								echo '</td>';
								echo '<td  style="width: 1px;">';
								if($_GET['svyaz'] == $row['rand']){
								echo '<center>Основной</center>';
								}else{
								if($row['svyaz'] != $_GET['svyaz']){
								if($row['svyaz'] != 0){
								echo '<a  title="Перевязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=1"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Перевязать</a>';
								}else{
								echo '<a  title="Привязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=1"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Привязать</a>';
								}}else{
								echo '<a  title="Отвязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=0"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Отвезать</a>';
								}
								}
								echo '</td>';
								echo '</tr>';
						
				
				












}
} else {
echo " Ничего не найдено<br>";
if(isset($_GET['inn'])) {
	echo "<a href='".VOOVI_MAIN_URL."/newogrn.php?id=186&inn=".$_GET['inn']."&kpp='>Создать организацию по ИНН: ".$_GET['inn']."</a>";
}
}
} 
?>
</table>


<br><br>

<?
						$num = 35; 
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM ogrn");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;	

// Проверяем нужны ли стрелки назад
if ($page != 1) $pervpage = '<a href=?page=1>Первая</a> | <a href=?page='. ($page - 1) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>Предыдущая</a> | ';
// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = ' | <a href=?page='. ($page + 1) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>Следующая</a> | <a href=?page=' .$total.'&'.$_GET['id'].'&'.$_GET['svyaz']. '>Последняя</a>';

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 5 > 0) $page5left = ' <a href=?page='. ($page - 5) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page - 5) .'</a> | ';
if($page - 4 > 0) $page4left = ' <a href=?page='. ($page - 4) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page - 4) .'</a> | ';
if($page - 3 > 0) $page3left = ' <a href=?page='. ($page - 3) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page - 3) .'</a> | ';
if($page - 2 > 0) $page2left = ' <a href=?page='. ($page - 2) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page - 2) .'</a> | ';
if($page - 1 > 0) $page1left = '<a href=?page='. ($page - 1) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page - 1) .'</a> | ';

if($page + 5 <= $total) $page5right = ' | <a href=?page='. ($page + 5) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page + 5) .'</a>';
if($page + 4 <= $total) $page4right = ' | <a href=?page='. ($page + 4) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page + 4) .'</a>';
if($page + 3 <= $total) $page3right = ' | <a href=?page='. ($page + 3) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page + 3) .'</a>';
if($page + 2 <= $total) $page2right = ' | <a href=?page='. ($page + 2) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page + 2) .'</a>';
if($page + 1 <= $total) $page1right = ' | <a href=?page='. ($page + 1) .'&id='.$_GET['id'].'&svyaz='.$_GET['svyaz'].'>'. ($page + 1) .'</a>';

// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
echo "</div>";
}
?>





	<table class="table tablehover">
	<thead>
        <tr>
		<th>id</th>
		<th>Название</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th>ОГРН</th>
		  <th style="width: 100px;"></th>
        </tr>
    </thead>
					<?php
					
							
					
						$query = mysql_query("SELECT * from ogrn ORDER BY id DESC LIMIT $start, $num");	
							while($row = mysql_fetch_array($query)) {
								echo '<tr  id="open'.$row['id'].'"  style="font-size: 12px;"><td style="width: 50px; text-align: center;"><strong>';
								echo $row['id'];
								echo '</strong></td>';
								echo '<td style="">';
								echo $row['naim'];
								echo '<script>
$("#open'.$row['id'].'").live("dblclick", function() {
window.location.href="./kartklient.php?id='.$row['id'].'";
});
</script></td>';
								echo '<td style="width: 100px;">';
								echo $row['inn'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['kpp'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['ogrn'];
								echo '</td>';
								echo '<td  style="width: 1px;">';
								
								if($_GET['svyaz'] == $row['rand']){
								echo '<center>Основной</center>';
								}else{
								if($row['svyaz'] != $_GET['svyaz']){
								if($row['svyaz'] != 0){
								echo '<a  title="Перевязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=1"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Перевязать</a>';
								}else{
								echo '<a  title="Привязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=1"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Привязать</a>';
								}}else{
								echo '<a  title="Отвязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=0"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Отвезать</a>';
								}
								}
								
								echo '</td>';
								echo '</tr>';
						} 
					?>
				</table>
				
				
<?


// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
echo "</div>";
}
?>
<br>
</div>
</div>

<div class="col-md-6">

<div class="bs-example">
   
<h3 style="border-bottom: 1px #333 solid;">Cвязанные организации</h3>













<?

						$num = 35; 
						$pagee = $_GET['pagee'];
						$result00 = mysql_query("SELECT COUNT(*) FROM ogrn WHERE svyaz = $_GET[svyaz]");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$pagee = intval($pagee);
						if(empty($pagee) or $pagee < 0) $pagee = 1;
						if($pagee > $total) $pagee = $total;
						$start = $pagee * $num - $num;	

// Проверяем нужны ли стрелки назад
if ($pagee != 1) $pervpagee = '<a href=?pagee=1>Первая</a> | <a href=?pagee='. ($pagee - 1) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>Предыдущая</a> | ';
// Проверяем нужны ли стрелки вперед
if ($pagee != $total) $nextpagee = ' | <a href=?pagee='. ($pagee + 1) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>Следующая</a> | <a href=?pagee=' .$total.'&'.$_GET['id'].'&'.$_GET['svyaz']. '>Последняя</a>';

// Находим две ближайшие станицы с обоих краев, если они есть
if($pagee - 5 > 0) $pagee5left = ' <a href=?pagee='. ($pagee - 5) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee - 5) .'</a> | ';
if($pagee - 4 > 0) $pagee4left = ' <a href=?pagee='. ($pagee - 4) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee - 4) .'</a> | ';
if($pagee - 3 > 0) $pagee3left = ' <a href=?pagee='. ($pagee - 3) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee - 3) .'</a> | ';
if($pagee - 2 > 0) $pagee2left = ' <a href=?pagee='. ($pagee - 2) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee - 2) .'</a> | ';
if($pagee - 1 > 0) $pagee1left = '<a href=?pagee='. ($pagee - 1) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee - 1) .'</a> | ';

if($pagee + 5 <= $total) $pagee5right = ' | <a href=?pagee='. ($pagee + 5) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee + 5) .'</a>';
if($pagee + 4 <= $total) $pagee4right = ' | <a href=?pagee='. ($pagee + 4) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee + 4) .'</a>';
if($pagee + 3 <= $total) $pagee3right = ' | <a href=?pagee='. ($pagee + 3) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee + 3) .'</a>';
if($pagee + 2 <= $total) $pagee2right = ' | <a href=?pagee='. ($pagee + 2) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee + 2) .'</a>';
if($pagee + 1 <= $total) $pagee1right = ' | <a href=?pagee='. ($pagee + 1) .'&'.$_GET['id'].'&'.$_GET['svyaz'].'>'. ($pagee + 1) .'</a>';

// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpagee.$pagee5left.$pagee4left.$pagee3left.$pagee2left.$pagee1left.'<b>'.$pagee.'</b>'.$pagee1right.$pagee2right.$pagee3right.$pagee4right.$pagee5right.$nextpagee;
echo "</div>";
}
?>





	<table class="table tablehover">
	<thead>
        <tr>
		<th>id</th>
		<th>Название</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th>ОГРН</th><th style="width: 100px;"></th>
        </tr>
    </thead>
					<?php
					
							
					
						$query = mysql_query("SELECT * from ogrn WHERE svyaz = $_GET[svyaz] ORDER BY id DESC LIMIT $start, $num");	
							while($row = mysql_fetch_array($query)) {
								echo '<tr  id="open'.$row['id'].'"  style="font-size: 12px;"><td style="width: 50px; text-align: center;"><strong>';
								echo $row['id'];
								echo '</strong></td>';
								echo '<td style="">';
								echo $row['naim'];
								echo '<script>
$("#open'.$row['id'].'").live("dblclick", function() {
window.location.href="./kartklient.php?id='.$row['id'].'";
});
</script></td>';
								echo '<td style="width: 100px;">';
								echo $row['inn'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['kpp'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['ogrn'];
								echo '</td>';
								
								echo '<td  style="width: 1px;">';
								if($_GET['svyaz'] == $row['rand']){
								echo '<center>Основной</center>';
								}else{
								if($row['svyaz'] != $_GET['svyaz']){
								if($row['svyaz'] != 0){
								echo '<a  title="Перевязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=1"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Перевязать</a>';
								}else{
								echo '<a  title="Привязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=1"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Привязать</a>';
								}}else{
								echo '<a  title="Отвязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=0"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Отвезать</a>';
								}
								}
								echo '</td>';
								
								echo '</tr>';
						} 
					?>
				</table>
				
				
<?


// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpagee.$pagee5left.$pagee4left.$pagee3left.$pagee2left.$pagee1left.'<b>'.$pagee.'</b>'.$pagee1right.$pagee2right.$pagee3right.$pagee4right.$pagee5right.$nextpagee;
echo "</div>";
}
?>
<br>
</div>
</div>


</div><?php
# левая колонка сайта
include 'footer.php';  
?><br>
<br>
</div>







			
			
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>