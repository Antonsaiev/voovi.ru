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
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-12">

<div class="bs-example">
   
<h3 style="border-bottom: 1px #333 solid;">Отправка уведомлений</h3>






























<?
						$num = 100; 
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM mail_to WHERE `email` LIKE  '%@%' OR `e_mail` LIKE  '%@%'");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;	

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





	<table class="table tablehover">
	<thead>
        <tr>
		<th>id</th>
		<th>Название</th>
		  <th>Email-1</th>
		  <th>Email-2</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th>Полученна</th>
		  <th>Статус</th>
		  <th><span class="glyphicon glyphicon-trash"></span></th>
        </tr>
    </thead>
					<?php
					
							
					
						$query = mysql_query("SELECT * from mail_to WHERE `email` LIKE  '%@%' OR `e_mail` LIKE  '%@%' ORDER BY id DESC LIMIT $start, $num");	
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
								echo '<td style="width: 120px;">';
								echo $row['email'];
								echo '</td>';
								echo '<td style="width: 120px;">';
								echo $row['e_mail'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['inn'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['kpp'];
								echo '</td>';
								echo '<td>';
								echo $row['dat_fns'];
								echo '</td>';
								echo '<td>';
								if($row['status'] == 0){
								echo '<a title="Отправить" class="btn btn-success btn-xs" 
									href="./todecmail.php?id='.$row['id'].'&naim='.$row['naim'].'&d='.substr($row['dat_fns'], 0, 2).'&m='.substr($row['dat_fns'], 3, 2).'&y='.substr($row['dat_fns'], 6, 4).'&email='.$row['email'].'&email2='.$row['e_mail'].'&picmo='.$row['picmo'].'&fns='.$row['fns'].'"
								>Отправить <span class="glyphicon glyphicon-time"></span></a>';
								}else{
									echo 'Отправленно: '.$row['dat_sav'];
								}
								echo '</td>';
								echo '<td>';
								echo '<a href="deltodecmail.php?id='.$row['id'].'"><span class="glyphicon glyphicon-trash"></span></a>';
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
<a href="import_mail/export.php"> export the database table </a>

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