<?php
# подключаем конфиг
include 'conf.php';  

		$q = "SELECT * FROM `users` WHERE users_id =$_GET[id]";
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


$rpod2345 = "SELECT * FROM doljnost WHERE id = '".$person['tip']."'";
$result57657 = mysql_query($rpod2345);
$row134 = mysql_fetch_array($result57657);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/toolkit.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">
<?php
# левая колонка сайта
include 'left_sitebar.php';  
?>









<div class="by amt">
  <div class="gc">
    <div class="gn">
	  <div class="qv rc aog alu">
        <div class="qx" style="background-image: url(img/lux.jpg);"></div>
        <div class="qw dj">
		  <div class="aoh" style="background: url(img/<?php echo $person['img']; ?>); width: 100px;height: 100px;background-size: cover;margin: 0 auto;margin-top: -65px;"></div>
          <h5 class="qy">
            <a class="aku" href="profile/index.html"><?php echo $person['f_name']; ?>&nbsp<?php echo $person['l_name']; ?></a>
          </h5>  <p class="alu"><?php echo $row134['name']; ?></p>
          <ul class="aoi">
            <li class="aoj">
              <a href="#userModal" class="aku" data-toggle="modal">
                Рейтинг
                <h5 class="ali"><?php echo $person['raiting'];?></h5>
              </a>
            </li>
				
            <li class="aoj">
              <a href="#userModal" class="aku" data-toggle="modal">
                Место
                <h5 class="ali">1</h5>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="qv rc sm sp">
        <div class="qw">
          <h5 class="ald">Информация<?php if ($userdata['adm']==1 || intval($userdata['users_id']) === intval($person['users_id'])) { ?> <small>· <a href="reduser.php?id=<?php echo intval($person['users_id']); ?>">Изменить</a></small><?php } ?></h5>
          <ul class="eb tb">
		    <li><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1){?><div class="reg"><a href="dob_org.php">Изменить доступ для пользователя</a></div><?php }?>
            <li><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> Принят на работу <a href="#"><?php echo $person['prinyat'];?></a>
            <li><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Телефон <a href="#"><?php echo $person['tel'];?></a>
            <li><span class="glyphicon glyphicon-gift" aria-hidden="true"></span> День рождения <a href="#"><?php if ($person['dr'] == "0") {echo "Не указанно"; } else {echo $person['dr']; } i?></a>
            <li><span class="glyphicon glyphicon-baby-formula" aria-hidden="true"></span> Замечания <a href="#">2</a>
            <li><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Поощрения <a href="#">1</a>
          </ul>
        </div>
      </div>
    </div>
    <div class="gz">
      <div class="ca qo anx">
        <div class="qf b aml">
		<div style="padding: 0 10px 10px 0px; font-weight:bold;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Лучшие работники</div>
		<table class="table">
			<?php
				$query = mysql_query("SELECT * from users WHERE tip < 87  ORDER BY raiting DESC LIMIT 7");
				while($row = mysql_fetch_array($query)) {
					$query2 = mysql_query("SELECT count(*) from aktivn WHERE users = '".$row['users_id']."'");
					echo '<tr id="'.mysql_result($query2, 0).'">';
					echo '<td style="text-transform: inherit;">';
					echo '<a style="font-weight: bold;float: left;margin-left: 10px;margin-right: 10px;" href="profile.php?id=',$row['users_id'],'">',$row['f_name'] ," ", $row['l_name'],'</a>';
					echo '</td>';
					echo '<td style="text-transform: inherit;text-align: left;padding-left: 14px;padding-top: 5px;font-weight: bold;">';
					echo mysql_result($query2, 0); 
					echo '</td>';
					echo '</tr>';
				}
			?>
		</table>
		</div>
	  </div>
      <div class="ca qo anx">
        <div class="qf b aml">
		<div style="padding: 0 10px 10px 0px; font-weight:bold;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Воронка продаж</div>
			<div>
				<strong>Переговоры:</strong> Заявок 
				<?php 
				$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 1");
				echo mysql_result($result, 0); 
				?>
				<i class="icon-rouble"></i>
			</div>
			<div>
				<strong>Предоставленно демо:</strong> Заявок 
				<?php 
				$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 2");
				echo mysql_result($result, 0); 
				?>
				<i class="icon-rouble"></i>
			</div>
			<div>
				<strong>Принимают решение:</strong> Заявок 
				<?php 
				$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 3");
				echo mysql_result($result, 0); 
				?>
				<i class="icon-rouble"></i>
			</div>
			<div>
				<strong>Ждем оплату:</strong> Заявок: 
				<?php 
				$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 4");
				echo mysql_result($result, 0); 
				?>
			</div>
			<div>
				<strong>Отгруженно:</strong> Заявок 
				<?php 
				$result = mysql_query("SELECT count(*) from focus WHERE del = 0 AND a = 6");
				echo mysql_result($result, 0); 
				?>
				<i class="icon-rouble"></i>
			</div>
			<div>
				<strong>Расходы:</strong> Всего <?php 
				$result = mysql_query("SELECT SUM(rashod) from rashod WHERE users = '".$person['users_id']."' ");
				if (mysql_result($result, 0) == NULL) {
				echo 0;
				} else {
				echo $english_format_number = number_format(mysql_result($result, 0));
				}
				?><i class="icon-rouble"></i>  В этом месяце <?php 
				$result = mysql_query("SELECT SUM(rashod) from rashod WHERE users = '".$person['users_id']."' AND mr = '".date("m")."' AND gr = '".date("Y")."' ");
				if (mysql_result($result, 0) == NULL) {
				echo 0;
				} else {
				echo $english_format_number = number_format(mysql_result($result, 0));
				}
				?><i class="icon-rouble"></i>
			</div>
		</div>
      </div>
      <div class="ca qo anx">
        <div class="qf b aml">
		<div style="padding: 0 10px 10px 0px; font-weight:bold;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Статистика выполненных работ</div>
            <ul class="list-group">
			  <li class="list-group-item">
				<span class="badge-default"><?php $result = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet 
				WHERE generac='0' AND kto = '".$person['users_id']."' AND d ='".date("d")."' AND m ='".date("m")."' AND y ='".date("Y")."'");
				echo mysql_result($result, 0); ?></span>
				Счетов за сегодня
			  </li>
			  <li class="list-group-item">
				<span class="badge-default"><?php $result = mysql_query("SELECT count(DISTINCT nomerschet,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr) from schet 
				WHERE generac='0' AND kto = '".$person['users_id']."' AND m ='".date("m")."' AND y ='".date("Y")."'");
				echo mysql_result($result, 0); ?></span>
				Счетов в этом месяце
			  </li>
			  <li class="list-group-item">
				<span class="badge-default"><?php $result = mysql_query("SELECT SUM(kvo) from schet 
				WHERE generac = '".$person['users_id']."' AND gen = '1' AND gen_date ='".date("ym")."' AND akt_date ='".date("ym")."'"); 
				if(mysql_result($result, 0) >= 1){
					echo mysql_result($result, 0);
				}else{
					echo '0';
				} ?></span>
				Сгенерированно отгруженно
			  </li>
			  <li class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from ogrn WHERE d = '".date("d")."' AND m = '".date("m")."' AND y = '".date("Y")."' AND users = '".$person['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Новых организации сегодня
			  </li>
			  <li class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from ogrn WHERE m = '".date("m")."' AND y = '".date("Y")."' AND users = '".$person['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Новых организации за месяц
			  </li>
			  <li class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from napomin WHERE d = '".date("d")."' AND m = '".date("m")."' AND y = '".date("Y")."' AND usersyes = '".$person['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Задач выполненых сегодня
			  </li>
			  <li class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from napomin WHERE m = '".date("m")."' AND y = '".date("Y")."' AND usersyes = '".$person['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Задач выполненых за месяц
			  </li>
			  <li class="list-group-item">
				<span class="badge-default"><?php $result2 = mysql_query("SELECT count(*) from napomin WHERE prosrochen = '1' AND users = '".$person['users_id']."'");
				$koll2 = mysql_result($result2, 0); 
				echo $koll2;?></span>
				Просроченных напоминаний за все время
			  </li>
			</ul>
        </div>
      </div>
    </div>
    <div class="gn">
      <div class="alert pv alert-dismissible ss" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <a class="pr" href="profile/index.html">Важно!</a> Проверить клиентов на задолженность по документам.
      </div>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
