<?php
# подключаем конфиг
include '../conf.php';  
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<link href="../css/bootstrap.min.css" rel="stylesheet">
<!--<link rel="stylesheet" type="text/css" href="css/normalize.css" />
<link rel="stylesheet" type="text/css" href="css/demo.css" />
<link rel="stylesheet" type="text/css" href="css/component.css" /> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

		<!--<script src="js/jquery.ba-throttle-debounce.min.js"></script>
		<script src="js/jquery.stickyheader.js"></script>-->
</head>
<ul class="dropdown-menu" role="menu" style="
    display: block;
    top: 0;
    padding: 0;
    margin: 0;
    height: 600px;
    width: 400px;
    border: 0;
    box-shadow: none;
">
<?php
				
$query = mysql_query("SELECT * from urli ");
while($row = mysql_fetch_array($query)) {			
	$udos = mysql_query("SELECT * FROM users_access WHERE users = '".$userdata['users_id']."' AND uslugi = '".$userdata['inogrn']."'");
	while($udostup = mysql_fetch_array($udos)) {						
				echo '<li><a href="'.$row['url'].'"  target="_blank">';
				echo $row['name'];
				echo '</a></li>';
	}
}
				
				?>
				<li class="divider"></li>
				<li>

<a href="../newurli.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Новая ссылка</a></li>
</ul>