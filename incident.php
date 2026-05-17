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
<html itemscope="" itemtype="http://schema.org/WebPage" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">


<script type="text/javascript">

	

// начать повторы с интервалом 2 сек
var timerId = setInterval(function() {
  $.ajax({
			type: "GET",
			url: "./incidentinc.php",
			data: "id=<?php 
			if(!empty($_GET['komu'])){
			echo '&komu='.$_GET['komu'];
			}
			?><?php 
			if(!empty($_GET['parent'])){
			echo '&parent='.$_GET['parent'];
			}
			?>",
			success: function(msg){
				var s = document.getElementById("include");
				s.innerHTML = msg;
			}
		});
}, 1000);

// через 5 сек остановить повторы
setTimeout(function() {
  clearInterval(timerId);
  alert( 'стоп' );
}, 999000);

</script>

</head>
<body>

<?php include 'header.php'; ?>

<div class="container" style="margin-top: 60px;">
<div class="row">


<link href="css/toolkit.css" rel="stylesheet">
<div class="by amt">
  <div class="gc">
    <div class="gn">
      <div class="qv rc sm sp">
        <div class="qw">
          <h5 class="ald"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Мои</h5>
          <div class="list-group" style="
    margin-left: 0px;
">
            <?php 
			$query = mysql_query("SELECT * from incident_group");
while($row = mysql_fetch_array($query)) {
	
					echo '<a class="list-group-item">
					
					
					

					
					
					
					
					
					';
					$cesult1 = mysql_query("SELECT count(*) from incident WHERE komu='".$userdata['users_id']."' AND parent='".$row['id']."' AND status='0'");
					$c1 = mysql_result($cesult1, 0);
					$result1 = mysql_query("SELECT count(*) from incident WHERE komu='".$userdata['users_id']."' AND parent='".$row['id']."' AND status='1'");
					$z1 = mysql_result($result1, 0);
					echo $row['name'];
if($c1 > 0){
					echo '<span class="badge badge-darck" style="
						background: #f2dede;
						color: #a94442;
						border: 1px solid #a94442;
						padding: 2px;
						margin: -2px;
						border-radius: 3px;
						min-width: 30px;
					">' . $c1 . '</span>';
}
if($z1 > 0){
					echo '<span class="badge badge-darck" style="
						background: #fcf8e3;
						color: #8a6d3b;
						border: 1px solid #8a6d3b;
						padding: 2px;
						margin: -2px;
						border-radius: 3px;
						min-width: 30px;
					">' . $z1 . '</span>';
}

					echo '<script type="text/javascript">
		$.ajax({
			type: "GET",
			url: "./incidentinc.php",
			data: "id=';
			
			echo '",
			success: function(msg){
				var s = document.getElementById("include");
				s.innerHTML = msg;
			}
		});
</script></a>';
  
  }
			?>
          </div>
        </div>
        <div class="qw">
          <h5 class="ald"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Категории</h5>
          <div class="list-group" style="
    margin-left: 0px;
">
 <?php 
			$query = mysql_query("SELECT * from incident_group");
while($row = mysql_fetch_array($query)) {
	
					echo '<a class="list-group-item">';
					$cesult1 = mysql_query("SELECT count(*) from incident WHERE parent='".$row['id']."' AND status='0'");
					$c1 = mysql_result($cesult1, 0);
					$result1 = mysql_query("SELECT count(*) from incident WHERE parent='".$row['id']."' AND status='1'");
					$z1 = mysql_result($result1, 0);
					echo $row['name'];
if($c1 > 0){
					echo '<span class="badge badge-darck" style="
						background: #f2dede;
						color: #a94442;
						border: 1px solid #a94442;
						padding: 2px;
						margin: -2px;
						border-radius: 3px;
						min-width: 30px;
					">' . $c1 . '</span>';
}
if($z1 > 0){
					echo '<span class="badge badge-darck" style="
						background: #fcf8e3;
						color: #8a6d3b;
						border: 1px solid #8a6d3b;
						padding: 2px;
						margin: -2px;
						border-radius: 3px;
						min-width: 30px;
					">' . $z1 . '</span>';
}

					echo '</a>';
  
  }
			?>
          </div>
        </div>
      </div>
    </div>
	
	
    <div class="hl">


      <div class="ca qo anx">
        <div class="qf b aml">
<div class="panel panel-default"> <div style="cursor: pointer;" class="panel-heading" role="tab" id="headingOne"  href="#collapseOne" role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="false" aria-controls="collapseOne" class="collapsed"> 
<h4 class="panel-title" style="
    color: #666;
    border: none;
"> 
<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Создать новый
</h4> 
</div> 
<div class="panel-collapse collapse" role="tabpanel" id="collapseOne" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;"> <div class="panel-body">

<div class="input-group input-group-sm">
  <span class="input-group-addon" id="sizing-addon3">Заголовок</span> 
  <input name="name" type="text" class="form-control" placeholder="" aria-describedby="sizing-addon3">
</div>
Создал(а): <?php echo $userdata['f_name']; ?> <?php echo $userdata['l_name']; ?>  <?php echo date('d.m.Y H:i'); ?><br> 
<div class="input-group input-group-sm">
  <span class="input-group-addon" id="sizing-addon3">Ответственный</span> 
  <select name="komu" class="form-control" aria-describedby="sizing-addon3">
            <?php 
			$query = mysql_query("SELECT * from users ");
while($row = mysql_fetch_array($query)) {
	
					echo '<option value="'.$row['users_id'].'">';
					echo $row['f_name'].' '.$row['l_name'];
					echo '</option>';
  
  }
			?>
</select>
  <span class="input-group-addon" id="sizing-addon3">Категория обращения</span> 
    <select name="parent" class="form-control" aria-describedby="sizing-addon3">
            <?php 
			$query = mysql_query("SELECT * from incident_group");
while($row = mysql_fetch_array($query)) {
	
					echo '<option value="'.$row['id'].'">';
					echo $row['name'];
					echo '</option>';
  
  }
			?>
</select>
  <!--<span class="input-group-addon" id="sizing-addon3">Статус</span> 
    <select name="status" class="form-control">
<option value="1">Открыт</option>
<option value="2">Решен</option>
</select>-->
</div> 
<div class="input-group">
<span class="input-group-addon">Описание:</span>
 <textarea name="text" class="form-control" rows="3"></textarea>
</div>

<input type="submit" name="add" value="Создать инцидент" class="btn btn-info">




<script> 
		function addincident() {
			$.ajax({
				type: "GET",
				url: "pusya.php",
				data: "tip=addincident&rand=",
				success: function(msg){
					
				}
			});
		}
	</script>


 </div> 
 
 
 </div> </div>
        </div>
      </div>
	  
<?php 
if(isset($_POST['add'])){
	
$add = "INSERT INTO `incident` (
`name`, 
`text`, 
`parent`, 
`status`, 
`date`, 
`dt`, 
`users_id`, 
`komu`
) VALUES (
'$_POST[name]',
'$_POST[text]',
'$_POST[parent]',
'0',
'".date('d.m.Y; H:i')."',
'".date('YmdHis')."',
'".$userdata['users_id']."',
'$_POST[komu]'
)";
mysql_query($add) or die(mysql_error($link));

$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.m.Y; H:i:s") ."','Новая задача','".$userdata['users_id']."')";
mysql_query($aktivn) or die(mysql_error($link));

echo '<div class="alert alert-success">
  <strong>Удачно!</strong> Инцидент успешно добавлен.
</div>';
}
?>
	  
	  
	  
      <div id="include" class="ca qo anx"></div>
	  
	  
	  
	  

	  
	  
	  
	  
	  <div></div>
	  
	  
    </div>
  </div>
  <div class="qv rc aok">
	<?php
	# подвал
	include 'footer.php';  
	?>
  </div>
</div>


</div>

</div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>
