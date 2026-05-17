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

</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-10 ">
<?php
										if(isset($_POST['submit'])){
										$u = "INSERT INTO `rashod` (`dr`, `mr`, `gr`, `chr`, `mir`, `tipz`, `rashod`, `opis`, `users`) VALUES ('$_POST[dr]', '$_POST[mr]', '$_POST[gr]', '$_POST[chr]', '$_POST[mir]', '$_POST[tipz]', '$_POST[rashod]', '$_POST[opis]', '".$userdata['users_id']."')";
										
										$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('". date("d.t.Y; H:i:s") ."','Новый расход','".$userdata['users_id']."')";
										mysql_query($aktivn) or die(mysql_error($link));
										mysql_query($u) or die(mysql_error($link));	
										echo '<div class="alert alert-success"><strong>Удачно!</strong> Расходы успешно добавлены.</div>';

										}
									?>
							<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Новый расход:</h4></strong>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"><div style="margin-top: 6px;"></div>
			<span>Дата:</span>
								<select style="margin-bottom: 0px;"  type="text" name="dr"  />
								<option value="<?php echo date("d"); ?>"><?php echo date("d"); ?></option>
									<?php 
										$a = 1;
										$b = date("t");
										while($a <= $b) echo '<option>',$a++,'</option>';
									?>
								</select>
								

			<span >:</span>
			<select style="margin-bottom: 0px;"  type="text" name="mr"  />
									<option value="<?php echo date("m"); ?>">
									<?php 
									if (date("m") == "01") {
										echo "Январь"; 
									} if (date("m") == "02") {
										echo "Февраль"; 
									} if (date("m") == "03") {
										echo "Март"; 
									} if (date("m") == "04") {
										echo "Апрель"; 
									} if (date("m") == "05") {
										echo "Май"; 
									} if (date("m") == "06") {
										echo "Июнь"; 
									} if (date("m") == "07") {
										echo "Июль"; 
									} if (date("m") == "08") {
										echo "Август"; 
									} if (date("m") == "09") {
										echo "Сентябрь"; 
									} if (date("m") == "10") {
										echo "Октябрь"; 
									} if (date("m") == "11") {
										echo "Ноябрь"; 
									} if (date("m") == "12") {
										echo "Декабрь"; 
									}
									?>
									</option>
								  <option value="01">Январь</option>
								  <option value="02">Февраль</option>
								  <option value="03">Март</option>
								  <option value="04">Апрель</option>
								  <option value="05">Май</option>
								  <option value="06">Июнь</option>
								  <option value="07">Июль</option>
								  <option value="08">Август</option>
								  <option value="09">Сентябрь</option>
								  <option value="10">Октябрь</option>
								  <option value="11">Ноябрь</option>
								  <option value="12">Декабрь</option>
								</select>

			<span >:</span>
			<select style="margin-bottom: 0px;" type="text" name="gr"  />
								  <option value="2014">2014</option>
								  <option value="2015">2015</option>
								  <option value="2016">2016</option>
								  <option value="2017">2017</option>
								  <option value="2018">2018</option>
								  <option value="2019">2019</option>
								  <option value="2020">2020</option>
								  <option value="2021">2021</option>
								  <option value="2022">2022</option>
								  <option value="2023">2023</option>
								</select>

			<span >Время:</span>
			<select style="margin-bottom: 0px;"  type="text" name="chr"  />
									<option value="<?php echo date("H"); ?>"><?php echo date("H"); ?></option>
								  <option value="1">1</option>
								  <option value="2">2</option>
								  <option value="3">3</option>
								  <option value="4">4</option>
								  <option value="5">5</option>
								  <option value="6">6</option>
								  <option value="7">7</option>
								  <option value="8">8</option>
								  <option value="9">9</option>
								  <option value="10">10</option>
								  <option value="11">11</option>
								  <option value="12">12</option>
								  <option value="13">13</option>
								  <option value="14">14</option>
								  <option value="15">15</option>
								  <option value="16">16</option>
								  <option value="17">17</option>
								  <option value="18">18</option>
								  <option value="19">19</option>
								  <option value="20">20</option>
								  <option value="21">21</option>
								  <option value="22">22</option>
								  <option value="23">23</option>
								  <option value="24">24</option>
								</select>

			<span>:</span>
			<select style="margin-bottom: 0px;" type="text" name="mir"  />
								<option value="<?php echo date("i"); ?>"><?php echo date("i"); ?></option>
								<option value="00">00</option>
								<option value="05">05</option>
								<option value="10">10</option>
								  <option value="15">15</option>
								  <option value="20">20</option>
								  <option value="25">25</option>
								  <option value="30">30</option>
								  <option value="35">35</option>
								  <option value="40">40</option>
								  <option value="45">45</option>
								  <option value="50">50</option>
								  <option value="55">55</option>
								  
								</select>
								
								
								<br><div style="margin-top: 6px;"></div>
            
			
			
								
								<div class="input-group">
<span class="input-group-addon alert-danger">Сумма:</span>
<input class="col-md-12 form-control" type="text" name="rashod" value="<?php echo $person['rashod']; ?>"  >
</div><div style="margin-top: 6px;"></div>
								
							


			<div style="margin-top: 6px;"></div>
	<div class="input-group">
			<span class="input-group-addon">Описание:</span>
			 <textarea name="opis" value="<?php echo $person['opis']; ?>" class="form-control" rows="3"></textarea>
	</div>
	<br><input type="submit" name="submit" value="Добавить" id="submitSuggestion" class="btn btn-success" /><br>
	</form>
	
	<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Все расходы:</h4></strong>
	<table class="table">
	<thead>
		<tr>
		<th>Дата</th>
		<th>Время</th>
		<th>Cумма</th>
		<th>Описание</th>
		
		</tr>
		</thead>
					<?php
						$query = mysql_query("SELECT * from rashod WHERE users = '".intval($_COOKIE['id'])."' ORDER BY id DESC");	
							while($row = mysql_fetch_array($query)) {
							 
							if ($row['dr'] < date("d")) {
								echo '<tr title="Задача не выполненна в срок" class="alert alert-danger" style="border-left: 5px #D50000 solid;"><td>';
								} else { 
								 echo '<tr><td>';
								}
								
								echo $row['dr'],"&nbsp";
								echo $row['mr'],"&nbsp";
								echo $row['gr'];
								echo '</td>';
								echo '<td>';
								echo $row['chr'],":";
								echo $row['mir'];
								echo '</td>';
								echo '<td>';									
								echo $row['rashod'],"руб.";
								echo '</td>';
								echo '<td>';
								echo $row['opis'];
								echo '</td></tr>';
								
						}
					?>
				</table>
</div>
<?php
# левая колонка сайта
include 'left_sitebar.php';
?>
</div>
</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
