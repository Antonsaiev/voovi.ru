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

<table class="table tablehover tabli">
	<thead>
        <tr>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
        </tr>
    </thead>
<?php
$query = mysql_query("SELECT * from users WHERE tip != '88' ORDER BY users_id");	
while($row = mysql_fetch_array($query)) {
		$result = mysql_query("SELECT COUNT(*) FROM `users_access` WHERE users = $row[users_id] AND uslugi = $_GET[id]");
		$class = mysql_result($result, 0);
	echo '<tr  id="open'.$row['users_id'].'"  style="font-size: 12px;"';
	if($class == 1){
		echo 'class="alert alert-success"';
	}else{
		echo 'class="alert alert-warning"';
	}
	echo'>';
	echo '<td style="">';
	echo $row['users_id'];
	echo '</td>';
	echo '<td style="">';
			echo $row['f_name'].' '; 
			echo $row['l_name'];
	echo '</td>';
	echo '<td style="">';
		
	echo '</td>';
	echo '<td style="">';
		if (mysql_result(mysql_query("SELECT count(*) from users_access WHERE users = $row[users_id] AND uslugi = $_GET[id]"), 0) > 0) {
			echo '<td style="width: 20px;" id="kta'.$row['users_id'].'" class="danger">';
			echo '<span onclick="ktpa'.$row['users_id'].'()" id="ktpa'.$row['users_id'].'" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
			<script>
			function ktpa'.$row['users_id'].'(){
				$.ajax({
					type: "GET",
					url: "/pusya.php",
					data: "tip=users_access2&users='.$row['users_id'].'&uslugi='.$_GET['id'].'",
					success: function(){
						document.getElementById("ktpa'.$row['users_id'].'").className = "glyphicon glyphicon-plus-sign";
						document.getElementById("kta'.$row['users_id'].'").className = "success";
						if($("#kta'.$row['users_id'].'").hasClass("success") == true){
							document.getElementById("st'.$row['users_id'].'").className = "warning";
						}else{
							if($("#kt'.$row['users_id'].'").hasClass("success") == true || $("#ktt'.$row['users_id'].'").hasClass("success") == true){
								document.getElementById("st'.$row['users_id'].'").className = "success";
							}
						}
			}});}</script>';
			echo '</td>';
		}else{
			echo '<td style="width: 20px;" id="kta'.$row['users_id'].'" class="success">';  
			echo '<span onclick="ktpa'.$row['users_id'].'()" id="ktpa'.$row['users_id'].'" class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
			<script>
			function ktpa'.$row['users_id'].'(){
				$.ajax({type: "GET",
				url: "/pusya.php",
				data: "tip=users_access&users='.$row['users_id'].'&uslugi='.$_GET['id'].'",
				success: function(){
					document.getElementById("ktpa'.$row['users_id'].'").className = "glyphicon glyphicon-trash";
					document.getElementById("kta'.$row['users_id'].'").className = "danger";
					if($("#kta'.$row['users_id'].'").hasClass("success") == true){
						document.getElementById("st'.$row['users_id'].'").className = "warning";
					}else{
						if($("#kt'.$row['users_id'].'").hasClass("danger") == true && $("#ktt'.$row['users_id'].'").hasClass("danger") == true){
							document.getElementById("st'.$row['users_id'].'").className = "danger";
						}
					}
			}});}</script>';
			echo '</td>';
		}
	echo '</td>';
	echo '</tr>';

}
?>
</table>	



</div>





</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo $url_sl; ?>js/bootstrap.min.js"></script>
</body>
</html>
