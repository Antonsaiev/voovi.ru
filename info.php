<!-- Конфигурация -->
<?php include 'conf.php'; ?>
<!-- Индентификация -->
<?php 
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
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

<?php
if($_GET['tip']=='histprod'){
	$tip = "SELECT * FROM ogtrnprod WHERE prod ='".$_GET['prod']."' AND idkli ='".$_GET['idkli']."'  ORDER BY id DESC";
}

	echo '<link href="css/bootstrap.min.css" rel="stylesheet"><table class="table table-striped">';
	$query = mysql_query($tip);	
	while($row = mysql_fetch_array($query)) {
	echo '<tr>';
	echo '<td>';
	echo ''.$row['date'].'';
	echo '</td>';
	echo '<td>';
	echo ''.$row['text'].'';
	echo '</td>';
	
	
	if($_GET['tip']=='histprod'){
	echo '<td>';
		if($row['redaktor'] > '0'){
		$q = "SELECT * FROM users WHERE users_id ='".$row['redaktor']."'";
		$res = mysql_query($q);
		$igrn = mysql_fetch_array($res);
		echo ''.$igrn['f_name']." ".$igrn['l_name'].'';
		}
	echo '</td>';
	}
		
	echo '</tr>';
	}
	echo '</table>';

?>