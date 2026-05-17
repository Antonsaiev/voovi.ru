<?php
# подключаем конфиг
include 'conf.php';  


$q = "SELECT * FROM focus WHERE id =$_GET[id]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);



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
<link rel="shortcut icon" href="/favicon.ico">
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
		<script>
			var auto_refresh = setInterval(
			function(){
				$('#load_div').fadeOut('slow').load().fadeIn("slow");
			}, 2000);
	</script>


	
	<script src="//cdn.ckeditor.com/4.4.3/full/ckeditor.js"></script>
	
	
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-10 ">
<?php


										if(isset($_POST['submit'])){
										$u = "INSERT INTO `faq` (`vopros`,`otvet`) VALUES ('$_POST[vopros]','$_POST[otvet]')";
										mysql_query($u) or die(mysql_error($link));	
										echo '<div class="alert alert-success"><strong>Удачно!</strong></div>';
										}
										
									?>

								
						<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Новый вопрос - ответ:</h4></strong>

	<form method="POST"><div style="margin-top: 6px;"></div>
	<div class="input-group">
			<span class="input-group-addon">Вопрос:</span>
			 <input name="vopros" value="<?php echo $person['vopros']; ?>" class="form-control" rows="2"/>
			 </div>
			 <div style="margin-top: 10px;"></div>
			 
			 <div class="input-group">
			<span class="input-group-addon">Ответ:</span>
			 <textarea id="editor" name="otvet" value="<?php echo $person['otvet']; ?>" class="form-control" rows="5"></textarea>
			 <script >CKEDITOR.replace('editor');</script>
	</div>
	<br>
	<input type="submit" name="submit" value="Отправить" id="submitSuggestion" class="btn btn-success" style="float: right;"/><br>
	</form>
		
<br>
<br>
		<h3 style="border-bottom: 1px #333 solid; background: rgba(0, 0, 0, 0.21);
padding: 5px 15px; margin-top: -12px;">Вопросы - ответы.</h3>
<div class="panel-group" id="accordion">
					<?php
					
						$num = 10;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM faq");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;		
					
						$query = mysql_query("SELECT * from faq ORDER BY id DESC LIMIT $start, $num");	
							while($row = mysql_fetch_array($query)) {
								echo '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$row['id'].'">';
								echo $row['vopros'];
								echo '</a></h4></div><div id="collapse'.$row['id'].'" class="panel-collapse collapse"><div class="panel-body" style="color: #666;">';
								echo $row['otvet'];
								echo '</div></div></div>';
						} 
					?>
				</div>
				
				
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
echo "<div class=\"pstrnav\" style='
    padding: 10px;
    border-bottom-right-radius: 10px;
    border-top-left-radius: 10px;
    color: #000;
    background: rgba(255, 255, 255, 1);
'>";
echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
echo "</div>";
}
?>
		
		
		
</div>


<?php
# левая колонка сайта
include 'left_sitebar.php';  
?>

</div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
