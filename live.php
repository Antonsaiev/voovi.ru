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
	<link href="blog.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<script>
setInterval(function(){ 
$("#updatebox").load(" #updatebox"); 
}, 10000);
 </script>

</head>
<body>
<div id="updatebox" style="
<?php $result = mysql_query("SELECT count(*) from focus WHERE a=0 AND del=0");
				$sums = mysql_result($result, 0); 
				if ($sums == 0){
				echo 'background: #72AD73;';
				} else {
				echo 'background: #B11E1E;';
				}?>	
color: #FFF;
width: 100%;
position: absolute;
height: 100%;
text-align: center;
padding-top: 0;
"><span style="
font-size: 547px;
font-weight: bold;
">
			<?php $result = mysql_query("SELECT count(*) from focus WHERE a=0 AND del=0");
				echo mysql_result($result, 0); ?></span>
			<?php $result = mysql_query("SELECT count(*) from focus WHERE a=0 AND del=0");
				$sums = mysql_result($result, 0); 
				if ($sums > 0){
				echo '<audio src="1.mp3" autoplay loop></audio>';
				}
				
				?>	
	</div>			
	
				
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
