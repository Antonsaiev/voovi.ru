<?php
  # Подключаем конфиг
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
  <div class="tab">
<table>
<thead>

<tr>
 <th>Наименование организации</th>
 <th>ИНН</th>
 <th>КПП</th>
 <th>ОГРН</th>
 <th>Телефон</th>
</tr>
</thead>
<tbody>
<?php $r = mysql_query("SELECT schet.id,schet.name,schet.inn,schet.kpp,schet.ogrn,schet.tell, klient.tel FROM schet inner join produkti on schet.produkt=produkti.id inner join uslugi on produkti.parent = uslugi.id inner join klient_ogrn on schet.idkli=klient_ogrn.idkli inner join klient on klient_ogrn.klient=klient.id and uslugi.id='22' group by schet.name"); while($res = mysql_fetch_assoc($r))  : ?>
<tr value="<?php echo $res['id']?>">
			<td><?php echo $res['name']?></td>
			<td><?php echo $res['inn']?></td>
			<td><?php echo $res['kpp']?></td>
			<td><?php echo $res['ogrn']?></td>
			<td><?php echo $res['tel']?></td>
			</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
  </body>
  </html>