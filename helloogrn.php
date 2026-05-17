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
if(isset($_GET['rand'])){
	
echo '<div style="
margin-bottom: 25px;
  font-size: 21px;
  background: #26BB84;
  text-align: center;
  padding: 8px;
  color: #fff;
">Новый продукт для ';

		$q1 = "SELECT * FROM `ogrn` WHERE id =$_GET[id]";
		$result1 = mysql_query($q1);
		$person1 = mysql_fetch_array($result1);
		
		echo $person1['naim'].'</div>';
}else{
include 'header.php';  }

if(isset($_GET['rand'])){
echo '<div class="container" style="margin-top: 20px;">';
}else{
echo '<div class="container" style="margin-top: 60px;">';  
}
?>
<div class="row">

<div class="col-md-12">

<div class="bs-example">
<?php
if(isset($_GET['rand'])){}else{
echo '<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Новый продукт для <a style="color: #3F77AE;" href="/kartklient.php?id='.$_GET['id'].'">';

		$q1 = "SELECT * FROM `ogrn` WHERE ogrn =$_GET[ogrn]";
		$result1 = mysql_query($q1);
		$person1 = mysql_fetch_array($result1);
		
		echo $person1['naim'];


  echo '</a></h4></strong>';  }
?>


<?php
					$qrand = "SELECT * FROM `schet` WHERE rand = ".$_GET['rand'];
					$resultrand = mysql_query($qrand);
					$personrand = mysql_fetch_array($resultrand);
					$qrands = "SELECT * FROM `tarif` WHERE id = ".$personrand['prod'];
					$resultrands = mysql_query($qrands);
					$personrands = mysql_fetch_array($resultrands);
				?>


<table class="table">
	<thead>
        <tr>
			<th style="padding: 5px;">Услуги</th>
			<th  style="width: 120px; padding: 5px;">Сайт</th>
			<th  style="width: 120px; padding: 5px;">Тарифов</th>
        </tr>
    </thead>
					<?php
					
						$num = 30;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM uslugi WHERE del = '0'");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;
					
					
					
					$query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."'");	
		while($row214 = mysql_fetch_array($query214)) {
						$query = mysql_query("SELECT * from uslugi  WHERE id = '".$row214['uslugi']."' AND del = '0' ORDER BY id DESC LIMIT $start, $num");	
							while($row = mysql_fetch_array($query)) {
							
								echo '<tr  style="font-size: 12px; ';
								if($personrands['parent'] == $row['id']){
									echo 'background:#E4F7E0;';
								}
								echo '"><td style="padding: 5px;"><a href="/newusluga.php?id='.$_GET['id'].'&parent='.$row['id'].'&ogrn='.$_GET['ogrn'].'&tip='.$_GET['parent'].'&kli='.$_GET['kli'];
								if(isset($_GET['rand'])){
								echo '&rand='.$_GET['rand'];
								}
								echo'">';
								echo $row['name'];
								echo '</a></td>';
								echo '<td style="padding: 5px;"><a href="'.$row['sites'].'">';
								echo $row['sites'];
								echo '</a></td>';  
								echo '<td style="padding: 5px;">';
								$result = mysql_query("SELECT count(*) from produkti WHERE parent = ".$row['id']);
								echo mysql_result($result, 0); 
								echo '</td></tr>';
								
						} 
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