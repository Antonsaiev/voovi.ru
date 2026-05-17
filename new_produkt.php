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
$showProductSite = false;
$showProductTel = false;
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
    <style>
        body {
            background: #f4f6f8;
        }

        .product-page {
            margin-top: 60px;
            margin-bottom: 40px;
        }

        .product-title {
            margin: 0 0 5px;
            font-size: 22px;
            font-weight: 700;
            color: #1f2933;
        }

        .product-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .product-nav {
            display: flex;
            gap: 8px;
            white-space: nowrap;
        }

        .product-nav .btn {
            min-height: 32px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.35;
        }

        .product-nav .glyphicon {
            margin-right: 4px;
        }

        .product-subtitle {
            color: #6b7280;
            font-size: 13px;
        }

        .product-panel {
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
            margin-bottom: 18px;
            padding: 18px;
        }

        .product-panel-title {
            margin: 0 0 12px;
            color: #2d3748;
            font-size: 15px;
            font-weight: 700;
        }

        .product-create-grid {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) 132px 140px;
            gap: 12px;
            align-items: end;
            max-width: 840px;
        }

        .product-create-description {
            display: none;
            margin-top: 12px;
        }

        .product-create-description textarea {
            min-height: 78px;
            resize: vertical;
        }

        .product-description-toggle,
        .product-add-button {
            min-height: 34px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.35;
        }

        .product-add-button {
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(22, 163, 74, 0.18);
        }

        .product-add-button .glyphicon {
            margin-right: 5px;
        }

        .product-list {
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        .product-list-head {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) 120px 220px;
            gap: 14px;
            align-items: center;
            padding: 11px 16px;
            background: #eef3f7;
            border-bottom: 1px solid #dbe2ea !important;
            color: #52616f;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .product-card {
            border-bottom: 1px solid #edf1f5;
        }

        .product-card:last-child {
            border-bottom: 0;
        }

        .product-card-main {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) 120px 220px;
            gap: 14px;
            align-items: center;
            padding: 15px 16px;
            transition: background 0.15s ease;
        }

        .product-list.has-site .product-list-head,
        .product-list.has-site .product-card-main {
            grid-template-columns: minmax(320px, 1fr) minmax(220px, 0.7fr) 120px 220px;
        }

        .product-list.has-tel .product-list-head,
        .product-list.has-tel .product-card-main {
            grid-template-columns: minmax(320px, 1fr) 120px 80px 220px;
        }

        .product-list.has-site.has-tel .product-list-head,
        .product-list.has-site.has-tel .product-card-main {
            grid-template-columns: minmax(320px, 1fr) minmax(220px, 0.7fr) 120px 80px 220px;
        }

        .product-card-main:hover {
            background: #fbfcfd;
        }

        .product-name {
            color: #1f2933;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.35;
            word-break: break-word;
        }

        .product-site {
            color: #2563a9;
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-count {
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

        .product-tel-cell {
            text-align: center;
        }

        .product-tel-cell input {
            margin: 0;
        }

        .product-actions {
            position: relative;
            white-space: nowrap;
            text-align: right;
        }

        .product-actions .btn {
            margin-left: 4px;
            min-height: 32px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.35;
        }

        .product-actions .glyphicon {
            margin-right: 4px;
        }

        .product-col-name {
            width: 64%;
        }

        .product-col-site {
            width: 25%;
        }

        .product-col-count {
            width: 12%;
        }

        .product-col-tel {
            width: 7%;
        }

        .product-col-actions {
            width: 24%;
            text-align: right;
        }

        .product-archive-popup {
            position: absolute;
            right: 14px;
            z-index: 20;
            margin-top: 8px;
            min-width: 220px;
            padding: 12px;
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14);
            text-align: center;
        }

        .product-archive-popup .btn {
            margin-top: 8px;
        }

        @media (max-width: 992px) {
            .product-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .product-create-grid {
                grid-template-columns: 1fr;
            }

            .product-list-head {
                display: none;
            }

            .product-card-main {
                grid-template-columns: 1fr 1fr;
                align-items: start;
            }

            .product-actions {
                text-align: left;
            }
        }

        @media (max-width: 768px) {
            .product-card-main {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container-fluid product-page">
<div class="row">

<div class="col-md-12">

<div class="product-shell">

<div class="product-header">
<div>
<h1 class="product-title">Продукты для <?php

		$q = "SELECT * FROM `uslugi` WHERE id =$_GET[parent]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);
		
		echo $person['name'];


  ?></h1>
<div class="product-subtitle">Активные продукты услуги</div>
</div>
<div class="product-nav">
<a class="btn btn-default btn-sm" href="new_usluga.php"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>Компании</a>
</div>
</div>



<?php
if(isset($_POST['submit'])){
$u = "INSERT INTO `produkti` (`name`,`value`,`parent`,`sites`) VALUES ('$_POST[name]','$_POST[value]','$_GET[parent]','$_POST[sites]')";
$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.t.Y; H:i:s") ."','Новый продукт','".$userdata['users_id']."')";
mysql_query($aktivn) or die(mysql_error($link));
mysql_query($u) or die(mysql_error($link));
echo '<div class="alert alert-success">
  <strong>Удачно!</strong> Новый продукт успешно добавлена.
</div>';
$body=file_get_contents("http://sms.ru/sms/send?api_id=513439c3-5ece-a954-e5b2-31b36fe77cbf&to=79097565645&text=".urlencode(iconv("utf-8","utf-8","Новый продукт: Манаджар: ".$userdata['users_id']."")));}
?>
<div class="product-panel">
<div class="product-panel-title">Добавить продукт</div>
<form method="post">
<div class="product-create-grid">
<div>
<label>Название продукта</label>
<input class="form-control" type="text" name="name" value="" required="">
</div>
<?php if ($showProductSite) : ?>
<div>
<label>Сайт продукта</label>
<input class="form-control" type="text" name="sites" value="" >
</div>
<?php else : ?>
<input type="hidden" name="sites" value="">
<?php endif; ?>
<button type="button" class="btn btn-default product-description-toggle js-product-description-toggle" data-target="#product-create-description">Описание</button>
<button type="submit" name="submit" id="submitSuggestion" class="btn btn-success product-add-button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Добавить</button>
</div>
<div id="product-create-description" class="product-create-description">
<label>Описание продукта</label>
<textarea name="value" class="form-control" rows="3"></textarea>
</div>
</form>
</div>



<div class="product-list tabli<?php if ($showProductSite) { echo ' has-site'; } ?><?php if ($showProductTel) { echo ' has-tel'; } ?>">
    <div class="product-list-head">
        <div>Продукт</div>
        <?php if ($showProductSite) : ?>
            <div>Сайт</div>
        <?php endif; ?>
        <div>Тарифов</div>
        <?php if ($showProductTel) : ?>
            <div>Тел.</div>
        <?php endif; ?>
        <div></div>
    </div>
					<?php

						$num = 999;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM produkti WHERE parent = '$_GET[parent]' AND del = '0'");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;		
					
						$query = mysql_query("SELECT * from produkti WHERE parent = '$_GET[parent]' AND del = '0' ORDER BY name LIMIT $start, $num");	
							while($row = mysql_fetch_array($query)) {
								echo '<div class="product-card"><div class="product-card-main"><div>';
								echo '<div class="product-name">'.$row['name'].'</div>';
								echo '</div>';
                                if ($showProductSite) {
								echo '<div><a class="product-site" href="http://'.$row['sites'].'">';
								echo $row['sites'];
								echo '</a></div>';
                                }
								echo '<div><span class="product-count">';
									$result = mysql_query("SELECT count(*) from tarif_parent WHERE product_id = '".$row['id']."' AND active = '1'");
								echo mysql_result($result, 0); 
								echo '</span></div>';
                                if ($showProductTel) {
								echo '<div class="product-tel-cell';if ($row['tel'] == 1) {echo ' highlight_success';}echo'">';
								echo '<input type="checkbox" value="'.$row['id'].'"'; if ($row['tel'] == 1) { echo 'checked';}echo'>';
								echo '</div>';
                                }
								echo '<div class="product-actions">';
								echo '<a class="btn btn-primary btn-sm" href="new_tarif.php?parent='.$row['id'].'">Тарифы</a>';
								echo '<button type="button" onclick="send_old'.$row['id'].'()" class="btn btn-default btn-sm" value="Отправить в архив"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>В архив</button>';
								echo '<div id="issetdiv'.$row['id'].'" class="contai product-archive-popup" style="display:none;">Вы точно хотите отправить в архив?<br>
								<a href="old_tarif.php?id='.$row['id'].'&old=1&tip=p" value="" class="btn btn-default  btn-xs">Да</a>
								<a href="old_tarif.php?id='.$row['id'].'&old=0&tip=p" value="" class="btn btn-primary  btn-xs">Нет</a></div>';
								echo '
								<script>
								function send_old'.$row['id'].'(){
									document.getElementById("issetdiv'.$row['id'].'").style.display="block";
								}
								</script>
								';
								echo '</div></div></div>';
						}
					?>
</div>
				 <script>
$(document).ready(function () {
    $(".js-product-description-toggle").click(function () {
        var button = $(this);
        var target = $(button.data("target"));

        target.slideToggle(160);
        button.toggleClass("btn-primary btn-default");
    });

    $(".tabli input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) {
            $(this).closest('.product-tel-cell').addClass("highlight_success");
			$.ajax({
			   type: "GET",
			   url: "pusya.php",
			   data: "id=1&tip=telprod&rand="+$(this).closest("input[type='checkbox']").val(),
			   success: function(msg){
			   }
			});
        } else {
            $(this).closest('.product-tel-cell').removeClass("highlight_success").addClass("highlights_row");
			$.ajax({
			   type: "GET",
			   url: "pusya.php",
			   data: "id=0&tip=telprod&rand="+$(this).closest("input[type='checkbox']").val(),
			   success: function(msg){
			   }
			});
        }
    });
});
 </script>
				
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
