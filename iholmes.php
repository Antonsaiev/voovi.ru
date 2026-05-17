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
<div class="col-md-8">

	<table class="table table-striped">
	<thead style="
    font-size: 12px;
">
        <tr>
		  <th style="width: 10px;padding:4px;"><span class="glyphicon glyphicon-ok"></span></th>
		  <th style="padding:4px;">Запрос</th> 
		  <th style="padding:4px;">Статус</th>
		  <th style="width: 10px;padding:4px;"><i class="fa fa-file-excel-o"></i></th>
		  <th style="width: 10px;padding:4px;">Обработать</th>
        </tr> 
    </thead>
<?php

						
						include 'conn.php';
						$num = 30;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM iholmes");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;		
						
						$query = mysql_query("SELECT * from iholmes ORDER BY id DESC LIMIT $start, $num");	
							while($row = mysql_fetch_array($query)) {
								echo '<tr style="font-size: 12px;"><td style="
    width: 10px;padding:4px;
">';
								
								echo '</td>';
								echo '<td style="padding:4px;">';
								echo $row['zayav'];
								echo '</td>';
								echo '<td style="width: 100px;padding:4px;">';
								if ($row['status'] == 0){
								echo "Отправлено";
								}if ($row['status'] == 1){
								echo "Обрабатывается";
								}if ($row['status'] == 2){
								echo "Обработан";
								}if ($row['status'] == 3){
								echo "Не найденно";
								}
								echo '</td>';
								echo '<td style="width: 10px;padding:4px;">';
								echo '<a title="Выгрузка в EXCEL" href="./m_kli.php?id=' .$row['id']. '"><i class="fa fa-file-excel-o"></i></a>';
								echo '</td>';
								echo '<td style="width: 10px;padding:4px;font-weight: bold;">';
								echo '<a style="color: #3b5998;" title="Обработать" href="./obrholmes.php?id=' .$row['id']. '">Обработать</a>';
								echo '</td></tr>';
						}
					?>
				</table>
				
				
<?
// Проверяем нужны ли стрелки назад
if ($page != 1) $pervpage = '<a href=?page=1>Первая</a> | <a href=?page='. ($page - 1) .'>Предыдущая</a> | ';
// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = ' | <a href=?page='. ($page + 1) .'>Следующая</a> | <a href=?page=' .$total. '>Последняя</a>';

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 5 > 0) $page5left = ' <a href=?page='. ($page - 5) .'>'. ($page - 5) .'</a> | ';
if($page - 4 > 0) $page4left = ' <a href=?page='. ($page - 4) .'>'. ($page - 4) .'</a> | ';
if($page - 3 > 0) $page3left = ' <a href=?page='. ($page - 3) .'>'. ($page - 3) .'</a> | ';
if($page - 2 > 0) $page2left = ' <a href=?page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';
if($page - 1 > 0) $page1left = '<a href=?page='. ($page - 1) .'>'. ($page - 1) .'</a> | ';

if($page + 5 <= $total) $page5right = ' | <a href=?page='. ($page + 5) .'>'. ($page + 5) .'</a>';
if($page + 4 <= $total) $page4right = ' | <a href=?page='. ($page + 4) .'>'. ($page + 4) .'</a>';
if($page + 3 <= $total) $page3right = ' | <a href=?page='. ($page + 3) .'>'. ($page + 3) .'</a>';
if($page + 2 <= $total) $page2right = ' | <a href=?page='. ($page + 2) .'>'. ($page + 2) .'</a>';
if($page + 1 <= $total) $page1right = ' | <a href=?page='. ($page + 1) .'>'. ($page + 1) .'</a>';

// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
echo "</div>";
}
?>

				
</div>
<div class="col-md-2">
<a class="col-md-12" href="./allorg.php">
<button type="button" style="margin-bottom: 7px; width: 100%; padding: 8px 3px;font-size: 12px;" class="btn btn-primary"><span style="font-size: 12px;" class="glyphicon glyphicon-user"></span> Все ИП и Юр. лица</button>
</a>
</div>
</div></div>
			
			
			
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>