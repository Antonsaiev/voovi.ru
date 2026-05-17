<?php
# подключаем конфиг
include './conf.php';  

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
    <link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../blog.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/mojo.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
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
include '../header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-12">

<div class="bs-example">
   
<h3 style="border-bottom: 1px #333 solid;">Текущие клиенты</h3>




<form action="" method="GET" name="form">
Название: <input type="text" name="naimen"   />  ИНН: <input type="text" name="inn"   /> КПП: <input type="text" name="kpp"   /> E-mail: <input type="text" name="e-mail"   />


<input type="submit" value="Поиск"><br><br>
</form>


<table class="table table-striped">
	<thead>
        <tr>
		  <th>Дата</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th>Название</th>
		  <th>Продукт</th>
		  <th>Состояние</th>
		  <th>Комментарии</th>
		  <th>Контакты</th>
          <th>Продл./Новый</th>
		  <th>Генерация</th>
        </tr>
    </thead>
<?php

$search_slovo = $_GET['naimen'];
$search_slovo1 = $_GET['inn'];
$search_slovo2 = $_GET['kpp'];
$search_slovo3 = $_GET['e-mail'];



if ((isset($search_slovo))) {
$search_name= mysql_query("SELECT * FROM `tekkli` WHERE `naimen` LIKE '%$search_slovo%' AND `inn` LIKE '%$search_slovo1%' AND `kpp` LIKE '%$search_slovo2%' AND `e-mail` LIKE '%$search_slovo3%'");
if (mysql_num_rows($search_name) != 0) {
while ($row = mysql_fetch_assoc($search_name)) {



					
						
								echo '<tr style="font-size: 12px;"><td style="width: 300px;">';
								echo $row['naimen'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['inn'];
								echo '</td>';
								echo '<td style="width: 100px;">';
								echo $row['kpp'];
								echo '</td>';
								echo '<td>';
								echo $row['e-mail'];
								echo '</td>';
								echo '<td  style="width: 17px;">';
								echo '<a  title="Новое напоминание" href="./napomni.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-time"></span></a>';
								echo '</td>';
								echo '<td style="width: 17px;">';
								echo '<a title="Карточка клиента" href="./kartklient.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-user"></span></a>';
								echo '</td>';
								echo '<td style="width: 17px;">';
								echo '<a title="Редактировать" href="./m_kli.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-pencil"></span></a>';
								echo '</td></tr>';
						
				
				












}
} else {
echo " Ничего не найдено";
}
} 
?>
</table>






<br><br>






















<?php 

	$id = $_POST['id'];
	
	if(isset($_POST['deletemarked']))
	{
		if (empty($id) || $id == 0)
		{
			echo 'Ошибка';
		}
		else
		{
			
			$impid = implode(", ",$id);
			$Qdelete = mysql_query("DELETE FROM tekkli WHERE id IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete))
			{
				echo "Успешно удалено";
			}
		}
	}

?>








<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
<input type="submit" name="deletemarked" value="Удалить" /> - <input type="reset" value="Убрать выделения" />
<div id="status"></div>

	<table class="table table-striped">
	<thead>
        <tr>
		  <th></th>
		  <th>Дата</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th>Название</th>
		  <th>Продукт</th>
		  <th>Состояние</th>
		  <th>Комментарии</th>
		  <th>Контакты</th>
          <th>Продл./Новый</th>
		  <th>Генерация</th>
        </tr>
    </thead>
					<?php
					
						$num = 15;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM tekkli");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;		
					
						
							$query = "SELECT * FROM tekkli ORDER BY id DESC LIMIT $start, $num";    
							$result = mysql_query($query) or die ('Query couldn\'t be executed');  
							while ($row = mysql_fetch_assoc($result)) { 
							echo '<tr style="font-size: 11px;">
							<td style="width: 5px;">';
								echo '<input type="checkbox" name="id[]" value="'.$row['id'].'"">';
								echo '</td>';
								echo '<td style="width: 30px;" data-idname="data" class="editable" id="'.$row['id'].'">';
								echo $row['data'];
								echo '</td>';
								echo '<td data-idname="inn" class="editable" id="'.$row['id'].'">';
								echo $row['inn'];
								echo '</td>';
								echo '<td data-idname="kpp" class="editable" id="'.$row['id'].'">';
								echo $row['kpp'];
								echo '</td>';
								echo '<td style="width: 200px;" data-idname="value" class="editable" id="'.$row['id'].'">';
								echo $row['value'];
								echo '</td>';
								echo '<td style="width: 100px;" class="editable" data-idname="produkt" id="'.$row['id'].'">';
								echo $row['produkt'];
								echo '</td>';
								echo '<td style="width: 90px;" data-idname="sostoyanie" class="editable" id="'.$row['id'].'">';
								echo $row['sostoyanie'];
								echo '</td>';
								echo '<td style="width: 200px;" data-idname="komm" class="editable" id="'.$row['id'].'">';
								echo $row['komm'];
								echo '</td>';
								echo '<td style="width: 150px;" data-idname="kontakti" class="editable" id="'.$row['id'].'">';
								echo $row['kontakti'];
								echo '</td>';
								echo '<td style="width: 100px;" data-idname="prod" class="editable" id="'.$row['id'].'">';
								echo $row['prod'];
								echo '</td>';
								echo '<td style="width: 70px;" data-idname="salectable" class="editable" id="'.$row['id'].'">';
								echo $row['genkluch'];
								echo '</td></tr>';
							}
					?>
				</table>
				
				</form>
				
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
<br><br><br>
</div>
</div>


</div>
</div>


 





			
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>