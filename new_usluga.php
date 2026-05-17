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
    <style>
        body {
            background: #f4f6f8;
        }

        .service-page {
            margin-top: 60px;
            margin-bottom: 40px;
        }

        .service-title {
            margin: 0 0 5px;
            color: #1f2933;
            font-size: 22px;
            font-weight: 700;
        }

        .service-subtitle {
            margin-bottom: 18px;
            color: #6b7280;
            font-size: 13px;
        }

        .service-panel {
            max-width: 840px;
            margin-bottom: 18px;
            padding: 18px;
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }

        .service-panel-title {
            margin: 0 0 12px;
            color: #2d3748;
            font-size: 15px;
            font-weight: 700;
        }

        .service-create-grid {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) 140px;
            gap: 12px;
            align-items: end;
        }

        .service-add-button {
            min-height: 34px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
            box-shadow: 0 6px 14px rgba(22, 163, 74, 0.18);
        }

        .service-add-button .glyphicon {
            margin-right: 5px;
        }

        .service-list {
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        .service-list-head,
        .service-card-main {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) 120px 240px;
            gap: 14px;
            align-items: center;
        }

        .service-list-head {
            padding: 11px 16px;
            background: #eef3f7;
            border-bottom: 1px solid #dbe2ea;
            color: #52616f;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .service-card {
            border-bottom: 1px solid #edf1f5;
        }

        .service-card:last-child {
            border-bottom: 0;
        }

        .service-card.is-archived {
            background: #fff7f7;
        }

        .service-card-main {
            padding: 15px 16px;
            transition: background 0.15s ease;
        }

        .service-card-main:hover {
            background: #fbfcfd;
        }

        .service-card.is-archived .service-card-main:hover {
            background: #fff1f1;
        }

        .service-name {
            color: #1f2933;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.35;
            word-break: break-word;
        }

        .service-archived-label {
            display: inline-block;
            margin-top: 5px;
            padding: 3px 7px;
            border-radius: 4px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 12px;
            font-weight: 700;
        }

        .service-count {
            display: inline-block;
            min-width: 42px;
            padding: 4px 9px;
            border-radius: 4px;
            background: #eef7f4;
            color: #0f766e;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
        }

        .service-actions {
            position: relative;
            white-space: nowrap;
            text-align: right;
        }

        .service-actions .btn {
            min-height: 32px;
            margin-left: 4px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.35;
        }

        .service-actions .glyphicon {
            margin-right: 4px;
        }

        .service-archive-popup {
            position: absolute;
            right: 0;
            z-index: 20;
            min-width: 240px;
            margin-top: 8px;
            padding: 12px;
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14);
            text-align: center;
            white-space: normal;
        }

        .service-archive-popup .btn {
            margin-top: 8px;
        }

        @media (max-width: 992px) {
            .service-create-grid,
            .service-card-main {
                grid-template-columns: 1fr;
            }

            .service-list-head {
                display: none;
            }

            .service-actions {
                text-align: left;
            }
        }
    </style>
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container-fluid service-page">
<div class="row">

<div class="col-md-12">

<div class="service-shell">

<h1 class="service-title">Компании</h1>
<div class="service-subtitle">Доступные компании и их продукты</div>





<?php
if(isset($_POST['submit'])){

$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.m.Y; H:i:s") ."','Новая компания','".$userdata['users_id']."')";
mysql_query($aktivn) or die(mysql_error($link));

$u = "INSERT INTO `uslugi` (`name`) VALUES ('$_POST[name]')";
mysql_query($u) or die(mysql_error($link));

echo '<div class="alert alert-success">
  <strong>Удачно!</strong> Новая компания успешно добавлена.
</div>';
$body=file_get_contents("http://sms.ru/sms/send?api_id=513439c3-5ece-a954-e5b2-31b36fe77cbf&to=79097565645&text=".urlencode(iconv("utf-8","utf-8","Новая компания: Манаджар: ".$userdata['users_id']."")));}


if($userdata['users_id'] == 1){
echo '
<div class="service-panel">
<div class="service-panel-title">Добавить компанию</div>
<form action="'.$_SERVER['PHP_SELF'].'" method="post">
<div class="service-create-grid">
<div>
<label>Название компании</label>
<input class="col-md-12 form-control" type="text" name="name" value="" required="">
</div>
<button type="submit" name="submit" id="submitSuggestion" class="btn btn-success service-add-button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Добавить</button>
</div>
</form>
</div>';
}

?>

<br>
















	<div class="service-list">
    <div class="service-list-head">
        <div>Компания</div>
        <div>Продуктов</div>
        <div></div>
    </div>
					<?php
					
						$num = 30;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM uslugi ");
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
							echo '<div class="service-card';
								if($row['del'] == 1){
									echo ' is-archived';
								}
								echo '"><div class="service-card-main"><div>';
                                echo '<div class="service-name">';
								echo $row['name'];
                                echo '</div>';
								if($row['del'] == 1){
									echo '<span class="service-archived-label">Архив</span>';
								}
								echo '</div>';
								echo '<div><span class="service-count">';
								$result = mysql_query("SELECT count(*) from produkti WHERE parent = '".$row['id']."' AND del = '0'");
								echo mysql_result($result, 0);
								echo '</span></div>';
								echo '<div class="service-actions">';
								if($row['del'] == 0){
								echo '<a class="btn btn-primary btn-sm" href="new_produkt.php?parent='.$row['id'].'">Продукты</a>';
								}
								
								
								if($row['del'] == 0){
								echo '<button type="button" onclick="send_old'.$row['id'].'()" class="btn btn-default btn-sm" value="Отправить в архив"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>В архив</button>';
								echo '<div id="issetdiv'.$row['id'].'" class="contai service-archive-popup" style="display:none;">Вы точно хотите отправить в архив?<br>
								<a href="old_tarif.php?id='.$row['id'].'&old=1&tip=u" value="" class="btn btn-default  btn-xs">Да</a>
								<a href="old_tarif.php?id='.$row['id'].'&old=0&tip=u" value="" class="btn btn-primary  btn-xs">Нет</a></div>';
								echo '<script>function send_old'.$row['id'].'(){document.getElementById("issetdiv'.$row['id'].'").style.display="block";}</script>';
								}else{
								echo '<button type="button" onclick="send_old'.$row['id'].'()" class="btn btn-default btn-sm" value="Восстановить из архива"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>Восстановить</button>';
								echo '<div id="issetdiv'.$row['id'].'" class="contai service-archive-popup" style="display:none;">Вы хотите восстановить организацию из архива?<br>
								<a href="old_tarif.php?id='.$row['id'].'&old=0&tip=u" value="" class="btn btn-default  btn-xs">Да</a>
								<a href="old_tarif.php?id='.$row['id'].'&old=1&tip=u" value="" class="btn btn-primary  btn-xs">Нет</a></div>';
								echo '<script>function send_old'.$row['id'].'(){document.getElementById("issetdiv'.$row['id'].'").style.display="block";}</script>';
								}
								
								echo '</div></div></div>';
						} 
						} 
					
					
		$query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."'");	
		while($row214 = mysql_fetch_array($query214)) {
						$query = mysql_query("SELECT * from uslugi  WHERE id = '".$row214['uslugi']."' AND del = '1' ORDER BY id DESC LIMIT $start, $num");	
							while($row = mysql_fetch_array($query)) {
							echo '<div class="service-card';
								if($row['del'] == 1){
									echo ' is-archived';
								}
								echo '"><div class="service-card-main"><div>';
                                echo '<div class="service-name">';
								echo $row['name'];
                                echo '</div>';
								if($row['del'] == 1){
									echo '<span class="service-archived-label">Архив</span>';
								}
								echo '</div>';
								echo '<div><span class="service-count">';
								$result = mysql_query("SELECT count(*) from produkti WHERE parent = '".$row['id']."' AND del = '0'");
								echo mysql_result($result, 0);
								echo '</span></div>';
								echo '<div class="service-actions">';
								if($row['del'] == 0){
								echo '<a class="btn btn-primary btn-sm" href="new_produkt.php?parent='.$row['id'].'">Продукты</a>';
								}
								
								
								if($row['del'] == 0){
								echo '<button type="button" onclick="send_old'.$row['id'].'()" class="btn btn-default btn-sm" value="Отправить в архив"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>В архив</button>';
								echo '<div id="issetdiv'.$row['id'].'" class="contai service-archive-popup" style="display:none;">Вы точно хотите отправить в архив?<br>
								<a href="old_tarif.php?id='.$row['id'].'&old=1&tip=u" value="" class="btn btn-default  btn-xs">Да</a>
								<a href="old_tarif.php?id='.$row['id'].'&old=0&tip=u" value="" class="btn btn-primary  btn-xs">Нет</a></div>';
								echo '<script>function send_old'.$row['id'].'(){document.getElementById("issetdiv'.$row['id'].'").style.display="block";}</script>';
								}else{
								echo '<button type="button" onclick="send_old'.$row['id'].'()" class="btn btn-default btn-sm" value="Восстановить из архива"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>Восстановить</button>';
								echo '<div id="issetdiv'.$row['id'].'" class="contai service-archive-popup" style="display:none;">Вы хотите восстановить организацию из архива?<br>
								<a href="old_tarif.php?id='.$row['id'].'&old=0&tip=u" value="" class="btn btn-default  btn-xs">Да</a>
								<a href="old_tarif.php?id='.$row['id'].'&old=1&tip=u" value="" class="btn btn-primary  btn-xs">Нет</a></div>';
								echo '<script>function send_old'.$row['id'].'(){document.getElementById("issetdiv'.$row['id'].'").style.display="block";}</script>';
								}
								
								echo '</div></div></div>';
						} 
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







			
			
    <script src="/js/jquery-1.11.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
