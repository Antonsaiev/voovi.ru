<?php
# подключаем конфиг
include 'conf.php';  
$lgenerac = "SELECT * FROM users WHERE users_id =".$_GET['komu'];
$rgenerac = mysql_query($lgenerac);
$pgenerac = mysql_fetch_array($rgenerac);
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
        <div class="qf b aml">
		<div style="padding: 0 10px 10px 0px; font-weight:bold;">
		<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> &nbsp; <?php 
		
if(!empty($_GET['komu'])){
	if($userdata['users_id'] == $_GET['komu']){
		echo 'Мои >';
	}else{
		$gen = $pgenerac['f_name'];
		echo mb_substr($gen,0,1,'UTF-8'),'. ';
		echo $pgenerac['l_name'];
	}
}else{
	echo 'Последние';
}
if(!empty($_GET['parent'])){
	$parent = "AND `parent`='".$_GET['parent']."'";
}
?>		</div>
            <div class="bs-example" data-example-id="list-group-custom-content"> 
				<div class="list-group">
<?php

if(!empty($_GET['komu'])){
	$komu = "AND `komu`='".$_GET['komu']."'";
}else{
	$komu = '';
}
if(!empty($_GET['parent'])){
	$parent = "AND `parent`='".$_GET['parent']."'";
}else{
	$parent = '';
}


$query = mysql_query("SELECT * from incident WHERE `id`!='0' $komu $parent ORDER BY dt DESC");
while($row = mysql_fetch_array($query)) {
	echo '<a onclick="includ'.$row['id'].'()" class="list-group-item   
	';
	if($row['status'] == 0){
		echo ' list-group-item-danger';
	}
	if($row['status'] == 1){
		echo ' list-group-item-warning';
	}
	if($row['status'] == 2){
		echo ' list-group-item-success';
	}
	echo '"
    
> 
	<h5 class="list-group-item-heading"style="margin-bottom: -2px;color: #666; ">'.$row['name'].'</h5> 
	<!--<p class="list-group-item-text">'.$row['text'].'</p>-->
	<p class="list-group-item-text" style="
    margin-left: -7px;
    margin-right: -3px;
	
    
    padding: 2px 5px;
    position: relative;
    bottom: -5px;
">';
	if($row['status'] == 0){
		echo '<span class="glyphicon glyphicon-import" aria-hidden="true"></span> Новый';
	}
	if($row['status'] == 1){
		echo '<span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Открыт';
	}
	if($row['status'] == 2){
		echo '<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Решен';
	}
	echo '; Ответственный ';

$lgenerac2 = "SELECT * FROM users WHERE users_id =".$row['komu'];
$rgenerac2 = mysql_query($lgenerac2);
$pgenerac2 = mysql_fetch_array($rgenerac2);
$gen2 = $pgenerac2['f_name'];
echo mb_substr($gen2,0,1,'UTF-8'),'. ';
echo $pgenerac2['l_name'];

echo '; №: '.$row['id'].' Дата: '.$row['date'].'</p> 
					</a> 
					
					
					
					
					
					
					';
					
					
					
					
					
					
}
?>
				</div> 
			</div>
        </div>
      