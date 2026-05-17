<?php
# подключаем конфиг
require 'conf.php';  

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
<!DOCTYPE html>
<html itemscope="" itemtype="http://schema.org/WebPage" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container" style="margin-top: 60px;">
<div class="row">

<?php

?>


<?php
# левая колонка сайта
include 'left_sitebar.php';  
?>
<link href="css/toolkit.css" rel="stylesheet">
<div class="by amt">
  <div class="gc">
    <div class="gn">
	
      <div class="alert pv alert-dismissible ss" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <a class="pr" href="profile/index.html">Важно!</a> Проверить клиентов на задолженность по документам.
      </div>
      <div class="qv rc sm sp">
        <div class="qw">
          <h5 class="ald">Информация</h5>
          <ul class="eb tb">
            <li><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> Полученно заявок <a href="#"><?php 
mysql_close();
require 'mexopt_conf.php';  
$result = mysql_query("SELECT COUNT(*) FROM `data_sell` WHERE status = '1'");
	echo mysql_result($result, 0);
?></a>
            <li><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Взята в работу <a href="#"><?php 
$result = mysql_query("SELECT COUNT(*) FROM `data_sell` WHERE status = '2'");
	echo mysql_result($result, 0);
?></a>
            <li><span class="glyphicon glyphicon-gift" aria-hidden="true"></span> Выполненно заявок <a href="#"><?php 
$result = mysql_query("SELECT COUNT(*) FROM `data_sell` WHERE status = '3'");
	echo mysql_result($result, 0);
?></a>
            <li><span class="glyphicon glyphicon-baby-formula" aria-hidden="true"></span> Откланенные заявки <a href="#"><?php 
$result = mysql_query("SELECT COUNT(*) FROM `data_sell` WHERE status = '4'");
	echo mysql_result($result, 0);
	mysql_close();
require 'conf.php';  
?></a>
          </ul>
        </div>
      </div>
    </div>
	
	
    <div class="gz">

	  
      <!--<div class="ca qo anx">
        <div class="qf b aml">
		<div style="padding: 0 10px 10px 0px; font-weight:bold;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> &nbsp; Лучшие работники</div>
		<table class="table"  id="grid">
	<tbody>		
<?php
/* $query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."' GROUP BY users");	
while($row214 = mysql_fetch_array($query214)) {
$query32 = mysql_query("SELECT * from uslugi  WHERE del = '0' AND id = '".$row214['uslugi']."'");	
while($row32 = mysql_fetch_array($query32)) {
$query3214 = mysql_query("SELECT * from users_access  WHERE uslugi = '".$row32['id']."'");	
while($row3214 = mysql_fetch_array($query3214)) {
$queryw = mysql_query("SELECT * from users WHERE `users_id` = '".$row3214['users']."' AND `uvl` = '0' AND `tip` < '87' ORDER BY raiting DESC");
while($roww = mysql_fetch_array($queryw)) {
$query = mysql_query("SELECT * from users WHERE `users_id` = '".$roww['users_id']."' ORDER BY raiting DESC");
while($row = mysql_fetch_array($query)) {
	
					$query2 = mysql_query("SELECT count(*) from aktivn WHERE users = '".$row['users_id']."'");
					echo '<tr>';
					echo '<td style="text-transform: inherit;">';
					echo '<a style="font-weight: bold;float: left;margin-left: 5px;margin-right: 5px;" href="profile.php?id=',$row['users_id'],'">',$row['f_name'] ," ", $row['l_name'],'</a>';
					if ($row['dt']+1000 > date("YmdHis")+1) {
					echo '<span class="label label-success"><p class="mobile-none">Работает</p></span>';
					} else {
					echo '<span class="label label-primary"><p class="mobile-none">Был на работе: ',$row['pos_pos'],'</p></span>';
					}
					echo '</td>';
					echo '<td style="text-transform: inherit;text-align: left;padding-left: 14px;padding-top: 5px;font-weight: bold;">';
					echo mysql_result($query2, 0); 
					echo '</td>';
					echo '</tr>';
  
  }
  }
  }
  }
  } */
  ?></tbody>
		</table>
		</div>
	  </div>-->
	  
	  
     <!--- <div class="ca qo anx">
        <div class="qf b aml">
		<div style="padding: 0 10px 10px 0px; font-weight:bold;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Воронка продаж</div>
			<div>
				<strong>Переговоры:</strong> Заявок 
				<?php 
				//$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 1");
				//echo mysql_result($result, 0); 
				?>
				<i class="icon-rouble"></i>
			</div>
			<div>
				<strong>Предоставленно демо:</strong> Заявок 
				<?php 
				//$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 2");
				//echo mysql_result($result, 0); 
				?>
				<i class="icon-rouble"></i>
			</div>
			<div>
				<strong>Принимают решение:</strong> Заявок 
				<?php 
				//$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 3");
				//echo mysql_result($result, 0); 
				?>
				<i class="icon-rouble"></i>
			</div>
			<div>
				<strong>Ждем оплату:</strong> Заявок: 
				<?php 
				//$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 4");
				//echo mysql_result($result, 0); 
				?>
			</div>
			<div>
				<strong>Отгруженно:</strong> Заявок 
				<?php 
				//$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 6");
				//echo mysql_result($result, 0); 
				?>
				<i class="icon-rouble"></i>
			</div>
			<div>
				<strong>Расходы:</strong> Всего <?php 
				//$result = mysql_query("SELECT SUM(rashod) from rashod WHERE users = '".$userdata['users_id']."' ");
				//if (mysql_result($result, 0) == NULL) {
				//echo 0;
				//} else {
				//echo $english_format_number = number_format(mysql_result($result, 0));
				//}
				?><i class="icon-rouble"></i>  В этом месяце <?php 
				//$result = mysql_query("SELECT SUM(rashod) from rashod WHERE users = '".$userdata['users_id']."' AND mr = '".date("m")."' AND gr = '".date("Y")."' ");
				//if (mysql_result($result, 0) == NULL) {
				//echo 0;
				//} else {
				//echo $english_format_number = number_format(mysql_result($result, 0));
				//}
				?><i class="icon-rouble"></i>
			</div>
		</div>
      </div> --->
      <div class="ca qo anx">
        <div class="qf b aml">
		<div style="padding: 0 10px 10px 0px; font-weight:bold;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> &nbsp; Статистика выполненных работ</div>
            <div class="list-group">
			  <div class="list-group-item">
				<span class="badge-default"><?php $result = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet 
				WHERE generac='0' AND kto = '".$userdata['users_id']."' AND d ='".date("d")."' AND m ='".date("m")."' AND y ='".date("Y")."'");
				echo mysql_result($result, 0); ?></span>
				Счетов за сегодня
			  </div>
			  <div class="list-group-item">
				<span class="badge-default"><?php $result = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet 
				WHERE generac='0' AND kto = '".$userdata['users_id']."' AND m ='".date("m")."' AND y ='".date("Y")."'");
				echo mysql_result($result, 0); ?></span>
				Счетов в этом месяце
			  </div>
			  <div class="list-group-item">
				<span class="badge-default"><?php $result = mysql_query("SELECT SUM(kvo) from schet 
				WHERE generac = '".$userdata['users_id']."' AND gen = '1' AND gen_date ='".date("ym")."' AND akt_date ='".date("ym")."'"); 
				if(mysql_result($result, 0) >= 1){
					echo mysql_result($result, 0);
				}else{
					echo '0';
				} ?></span>
				Сгенерированно отгруженно
			  </div>
			  <div class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from ogrn WHERE d = '".date("d")."' AND m = '".date("m")."' AND y = '".date("Y")."' AND users = '".$userdata['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Новых организации сегодня
			  </div>
			  <div class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from ogrn WHERE m = '".date("m")."' AND y = '".date("Y")."' AND users = '".$userdata['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Новых организации за месяц
			  </div>
			  <div class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from napomin WHERE d = '".date("d")."' AND m = '".date("m")."' AND y = '".date("Y")."' AND usersyes = '".$userdata['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Задач выполненых сегодня
			  </div>
			  <div class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from napomin WHERE m = '".date("m")."' AND y = '".date("Y")."' AND usersyes = '".$userdata['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Задач выполненых за месяц
			  </div>
			  <div class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from napomin WHERE prosrochen = '1' AND users = '".$userdata['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Просроченных напоминаний за все время
			  </div>
			</div>
        </div>
      </div>
	  
	  
	  
	  
	  
	  
	  
	  
	  	<div class="ca qo anx">
		<div style="    font-weight: bold;
    position: relative;
    display: block;
    padding: 10px 20px;
    margin-bottom: -1px;
    background-color: #f5f8fa;
    border: 1px solid #d3e0e9;
    border-top-right-radius: 4px;
    border-top-left-radius: 4px;"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span> &nbsp; Новости о системе</div>
       
		
		
				<?php

$query = mysql_query("SELECT * from news ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
					echo '<div class="qf b aml">
					  <div class="qg">
						<div class="aoc">
						  <div class="qn">
							<small class="eg dp">';
							if($row['tip'] == 1){
								echo 'выпущен';
							}
							if($row['tip'] == 0){
								echo 'ожидается';
							}
							echo $row['date'].'</small>
							<b>'.$row['value'].'</b>
						  </div>
						  <p>
							'.$row['text'].'
						  </p>
						</div>
					  </div>
					</div>';
  }
  ?>
      </div>
	  
	  
	  
	  
	  
	  
	  
    </div>
    <div class="gn">
	  <div class="qv rc sm sp">
        <div class="qw">
          <?php include 'kalendar.php';?>
        </div>
		<?php
		$query = mysql_query("SELECT * from napomin WHERE yes = '0'  AND dmg <= '".date("Ymd")."'  AND users = '".$userdata['users_id']."' ORDER BY dmg LIMIT 2");
		while($row = mysql_fetch_array($query)) {
			echo '<div class="qz">'.$row['opis'].'</div>';
		}
		?>
		<div class="qz" style="padding: 10px 15px;font-weight: bold;color: #7CC58C;text-align: center;background-color: #F1FFF0;border-top: 1px solid #7CC58C;border-bottom-left-radius: 3px;">ПОКАЗАТЬ ВСЕ ЗАДАЧИ НА СЕГОДНЯ</div>
      </div>
    </div>
  </div>
  <div class="qv rc aok">
	<?php
	# подвал
	include 'footer.php';  
	?>
  </div>
</div>

<?php
# левая колонка сайта
include 'right_sitebar.php';  
?>


</div>

</div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>