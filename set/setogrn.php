<?php
$url_slash = substr_count($_SERVER['REQUEST_URI'],'/');
if($url_slash == 1){
	$url_sl = '';
}if($url_slash == 2){
	$url_sl = '../';
}if($url_slash == 3){
	$url_sl = '../../';
}

# подключаем конфиг
include $url_sl.'conf.php';  # проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
{ 
setcookie('id', '', time() - 60*24*30*12, '/'); 
setcookie('hash', '', time() - 60*24*30*12, '/');
setcookie('errors', '1', time() + 60*24*30*12, '/'); 
header('Location: '.$url_sl.'index.php'); exit();
} 
} 
else
{ 
  setcookie('errors', '2', time() + 60*24*30*12, '/');
  header('Location: '.$url_sl.'index.php'); exit();
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="<?php echo $url_sl; ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $url_sl; ?>css/toolkit.css" rel="stylesheet"> 
<body>
<?php
# шапка
include $url_sl.'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">





<table class="table tablehover">
	<thead>
        <tr>
		<th>Название</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th>ОГРН</th>
		  <th style="width: 1px;"><span class="glyphicon glyphicon-user"></span></th>
		  <th style="width: 1px;"><span class="glyphicon glyphicon-file"></span></th>
		  <th style="width: 1px;"><span class="glyphicon glyphicon-pencil"></span></th>
		  <?php
			if($userdata['otvetstven']==1){
			echo '<th style="width: 1px;"><span class="glyphicon glyphicon-trash"></span></th>';
			}
		  ?>
        </tr>
    </thead>
<?php
$query = mysql_query("SELECT * from uslugi ORDER BY id DESC");	
while($row = mysql_fetch_array($query)) {
echo '<tr  id="open'.$row['id'].'"  style="font-size: 12px;">';
echo '<td style="">';
echo $row['name'];
echo '<script>
$("#open'.$row['id'].'").live("dblclick", function() {
window.location.href="./kartklient.php?id='.$row['id'].'";
});
</script></td>';
echo '<td style="width: 100px;">';
echo $row['inn'];
echo '</td>';
echo '<td style="width: 100px;">';
echo $row['kpp'];
echo '</td>';
echo '<td style="width: 100px;">';
echo $row['ogrn'];
echo '</td>';
echo '<td>';
echo '<a title="Сотрудники" href="./setuser.php?id='.$row['id'].'"><span class="glyphicon glyphicon-user"></span></a>';
echo '</td>';
echo '<td>';
echo '<a title="Документы" href="./doc/index.php?id='.$row['id'].'&inn='.$row['inn'].'"><span class="glyphicon glyphicon-file"></span></a>';
echo '</td>';
echo '<td>';
echo '<a title="Редактировать" href="seton.php?id='.$row['id'].'&inn='.$row['inn'].'"><span class="glyphicon glyphicon-pencil"></span></a>';
echo '</td>';
if($userdata['otvetstven']==1){
echo '<td>';
echo '<a title="Удалить без возврата" href="./m_del.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-trash"></span></a>';
echo '</td>';
}
echo '</tr>';
}
?>
</table>		

<br>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo $url_sl; ?>js/bootstrap.min.js"></script>
</body>
</html>
