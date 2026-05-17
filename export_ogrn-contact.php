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
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<style>
	.iframestyledivaaa {
		background: #fff;
		float:right;
		border: 0px solid #333;
		box-shadow: 0px 0px 30px #333;
	}
	</style>
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">
<div class="bs-example">
<h3 style="border-bottom: 1px #333 solid;">Все клиенты</h3>
<?
	echo '<table class="table tablehover">
	<thead>
        <tr>
		<th></th>
		<th>Название</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th>ОГРН</th>
          <th></th>
		  ';
        echo ' </tr></thead>';
					$saas = 1;		
						$query = mysql_query("SELECT * from ogrn");	
							while($row = mysql_fetch_array($query)) {
								echo '<tr  id="open'.$row['id'].'"  style="font-size: 12px;"><td>';
								echo $saas++;
								echo '</td>';
								echo '<td>';
								echo $row['naim'];
								echo '</div></td>';
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
									
	
				$a = 1;
		$query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".$row['id']."' ORDER BY id DESC");	
		while($row2 = mysql_fetch_array($query2)) {
		$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' AND `tel`!='' ORDER BY id DESC");	
		while($row3 = mysql_fetch_array($query3)) {
		echo '<div class="alert alert-info">';
		echo '<div>';
		if(!empty($row3['tel'])){
		echo '';
		$vowels = array("+", "(", ")", " ", "-");
		echo str_replace($vowels, "", "$row3[tel]").'| ';
		}
		if(!empty($row3['fio'])){
		echo '<b>ФИО:</b> ',$row3['fio'];
		}
		if(!empty($row3['dol'])){
		echo ' / <b>Должность:</b> ',$row3['dol'];
		}
		
		if(!empty($row3['email'])){
		echo ' / <b>E-mail:</b> ',$row3['email'];
		}
		echo '</div>';
		echo '</div>';
		}
		}
		
								echo '</td>';
								echo '<td>';
									
	


$queryq = mysql_query("SELECT * from produkti WHERE id = '272' AND kartkli = '1' AND del = '0'");	
while($rowq = mysql_fetch_array($queryq)) {
	if($rowq['parent'] == $userdata['inogrn']) {
		$aqiq = "SELECT * FROM ogtrnprod WHERE prod ='".$rowq['id']."' AND idkli ='".$row['id']."' ORDER BY id DESC";
		$aqresiult = mysql_query($aqiq);
		$aqoigrn = mysql_fetch_array($aqresiult);
		if($aqoigrn['redaktor'] > '0'){
			echo ' <b style="font-size: 12px;">';
				echo ' <b style="width: 160px;">'.$rowq['name'].' / </b>';
				echo ' <b style="width: 180px;">';
					if(!empty($aqoigrn['kto'])){
						echo $aqoigrn['date']." | ";
					}
				echo ' </b>';
				echo ' <b>';
					echo $aqoigrn['text'];
					$wusersaqiq = "SELECT * FROM users WHERE users_id ='".$aqoigrn['redaktor']."'";
					$wusersaqresiult = mysql_query($wusersaqiq);
					$wusersaqoigrn = mysql_fetch_array($wusersaqresiult);
					echo ' <i> ('.$wusersaqoigrn['f_name']." ".$wusersaqoigrn['l_name'].')</i>';
					$resulte = mysql_query("SELECT COUNT(*) FROM ogtrnprod WHERE prod ='".$rowq['id']."' AND idkli ='".$_GET['id']."'");
					$classe = mysql_result($resulte, 0);
					if($classe > '1'){
						echo '<span onclick="prod'.$rowq['id'].'()" style="float:right;" class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>';
					}
				echo ' </b>';
				echo ' <b>';
					echo $aqoigrn['kol'];
				echo ' </b>';
			echo ' </b>';
		}
	}
}
				
		
								echo '</td>';
								echo '</tr>';
						}
				echo '</table>';
?>
<br>
</div>
</div>
</div>
</div>	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>