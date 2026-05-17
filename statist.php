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
<div class="container" style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">


<?php 
$queryw = mysql_query("SELECT * FROM produkti WHERE parent = '".$_GET['id']."' AND del='0'");
			while($roww = mysql_fetch_array($queryw)) {
				echo '<table class="table tablehover" style="padding: 10px;">';
				echo '<tr>';
				echo '<td>';
				
				
				
				echo '<div class="div">';
				echo '<table class="table tablehover">';
				echo '<thead>
				<tr>
				<th style="">'.$roww['name'].'</th>
				<th style="width: 200px;">2015</th>
				<th style="width: 200px;">2016</th>
				<th style="width: 200px;">2017</th>
				<th style="width: 200px;">2018</th>
				</tr>
				</thead>';
				
				$queryww = mysql_query("SELECT * FROM tarif WHERE parent = '".$roww['id']."' ");
				while($rowww = mysql_fetch_array($queryww)) {
					
					if($rowww['del'] != '0'){
						echo '<tr style="background:red;">';
					}else{
						echo '<tr style="">';
					}
					echo '<td>';
					echo ''.$rowww['name'].'';
					echo '</td>';
					
						echo '<td style="">';
					
					
					
					
					
					
				$result = mysql_query("SELECT count(*) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2015'");
				echo mysql_result($result, 0);
					
				$result = mysql_query("SELECT SUM(price) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2015'");
				echo '('.mysql_result($result, 0).')';
					
					
					
					echo '</td>';
					echo '<td style="">';	
					
					
				$result = mysql_query("SELECT count(*) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2016'");
				echo mysql_result($result, 0);
				
					
				$price = mysql_query("SELECT SUM(price) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2016'");
				$priceks = mysql_query("SELECT SUM(priceks) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2016'");
				echo '('.mysql_result($price, 0).'/'.mysql_result($priceks, 0).')';
				
					
					
					echo '</td>';
					echo '<td style="">';	
					
					
					
					
				$result = mysql_query("SELECT count(*) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2017'");
				echo mysql_result($result, 0);	
					
				$result = mysql_query("SELECT SUM(price) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2017'");
				echo '('.mysql_result($result, 0).')';
					
					
					echo '</td>';
					echo '<td style="">';	
					
					
					
					
				$result = mysql_query("SELECT count(*) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2018'");
				echo mysql_result($result, 0);
					
				$result = mysql_query("SELECT SUM(price) from schet WHERE del = '0' AND akt='1' AND prod='".$rowww['id']."' AND y='2018'");
				echo '('.mysql_result($result, 0).')';
					
					
					
					echo '</td>';
					
					echo '</tr>';
				}

				echo '</table>';
				echo '</div>';
					
					
					
				echo '</td>';
				echo '</tr>';
				echo '</table>';
			}
?>


</div>
</div><?php
# левая колонка сайта
include 'footer.php';  
?><br>
<br>
</div>







			
			
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>