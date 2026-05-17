<?php
# Подключаем конфиг 
include 'confmex.php';
$date = date('Ymd');
?> 
<html>
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery-1.11.0.min.js"></script>
   <script src="js/lightbox.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> 
	<script type="text/javascript" src="js/script.js"></script>
<link rel="shortcut icon" href="/favicon.ico">
</head>
 <body>
   <div class="modal-shadow"></div>
<div class="modal-window">
  <div class="close">X</div>
    <form class="contact_form" action="prod_lic.php" method="POST">
	<p>
	  <label for="login">Логин</label>
      <input type="text" name="login" id="login" placeholder="Ваш логин" required>
	</p>
    <!--<p>
	  <label for="old_date_lic">Старая дата лицензии</label>
      <input type="text" name="old_date_lic" id="old_date_lic" placeholder="Старая дата лицензии" required>
	</p>!-->
<p>
	  <label for="new_date_lic">Новая дата лицензии</label>
      <input type="text" name="new_date_lic" id="new_date_lic" placeholder="Новая дата лицензии" required>
	</p>
	<p>
	  <button>Продлить</button>
	</p>
	</form>
</div>
 <div class="tab">
<table>
<thead>
<?php $r = mysql_query("SELECT count(users_id) FROM users"); $res = mysql_fetch_array($r) ; ?>
<tr>
			<th>Пользователь(<?php echo $res[0];?>)</th>
			<th>Логин</th>
			<th>Почта</th>
			<th>ИНН</th>
			<th>Дата окончания лицензии</th>
			<th></th>
			</tr>
</thead>
<tbody>
<?php $r = mysql_query("SELECT * FROM users order by date_licen asc"); while($res = mysql_fetch_assoc($r))  : ?>
<tr value="<?php echo $res['users_id']?>">
			<td value="<?php echo $res['users_fio']?>"><?php echo $res['users_fio']?></td>
			<td value="<?php echo $res['users_login']?>"><?php echo $res['users_login']?></td>
			<td value="<?php echo $res['users_email']?>"><?php echo $res['users_email']?></td>
			<td value="<?php echo $res['inn']?>"><?php echo $res['inn']?></td>
			<td value="<?php echo $res['date_licen']?>"><?php echo $res['date_licen'];
            $ned=$res['date_licen']-$date;?></td>
			<?php
			if ($ned < 100 && $res['date_licen'] > $date) 
			{
			echo '<td style="background-color: #f6d008;" title="Срок лицензии истекает через месяц"><label class="lab" name='.$res['date_licen'].' id='.$res['users_login'].' onclick="f(this) ">Продлить</label></td>'; 
			}
			if ($ned >= 100 ) 
			{
			echo '<td style="background-color: #4eef0b;" title="Лицензия в норме"><label class="lab" name="'.$res['date_licen'].'" id="'.$res['users_login'].'" onclick="f(this)">Продлить</label></td> ';
			}
		    if ($res['date_licen'] < $date)
			{
			echo '<td style="background-color: #f80707;" title="Срок лицензии истек"><label class="lab" name="'.$res['date_licen'].'" id="'.$res['users_login'].'" onclick="f(this)">Продлить</label></td> ';
			}
			?>
			</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

  </body>
  </html>