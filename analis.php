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

<div class="col-md-12">
<?php
if(isset($_POST['submit'])){
$u = "INSERT INTO `analis` (`m`, `g`, `t`, `s`, `o`) VALUES ('$_POST[m]', '$_POST[g]', '$_POST[t]', '$_POST[s]', '$_POST[o]')";
mysql_query($u) or die(mysql_error($link));
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<div class="input-group">
<span class="input-group-addon alert-danger">Месяц:</span>
<select style="margin-bottom: 0px;" class="form-control"   name="m"  />
  <option value="1">Январь</option>
  <option value="2">Февраль</option>
  <option value="3">Март</option>
  <option value="4">Апрель</option>
  <option value="5">Май</option>
  <option value="6">Июнь</option>
  <option value="7">Июль</option>
  <option value="8">Август</option>
  <option value="9">Сентябрь</option>
  <option value="10">Октябрь</option>
  <option value="11">Ноябрь</option>
  <option value="12">Декабрь</option>
</select>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Год:</span>
<select style="margin-bottom: 0px;" class="form-control"   name="g"  />
  <option value="12">2012</option>
  <option value="13">2013</option>
  <option value="14">2014</option>
  <option value="15">2015</option>
  <option value="16">2016</option>
  <option value="17">2017</option>
  <option value="18">2018</option>
  <option value="19">2019</option>
  <option value="20">2020</option>
  <option value="21">2021</option>
  <option value="22">2022</option>
  <option value="23">2023</option>
</select>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Тип:</span>
<select style="margin-bottom: 0px;"  class="form-control"   name="t"  />
  <option value="1">Савоир</option>
  <option value="2">Контур</option>
  <option value="3">Сертум</option>
  <option value="4">Алкоголь</option>
</select>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Сумма:</span>
<input class="col-md-12 form-control"  type="text" name="s" value=""  >
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon alert-danger">Отдел:</span>
<select style="margin-bottom: 0px;" class="form-control"  name="o"  />
  <option value="1">ЭЦП</option>
  <option value="2">Бух Отчет</option>
  <option value="3">Ведение Б</option>
</select>
</div>
<div style="margin-top: 6px;"></div>


<input type="submit" name="submit" value="Добавить" id="submitSuggestion" class="btn btn-success" /><br>
</form>

<br>
<table class="table table-striped">
<thead>
<tr>
<th>Месяц</th>
<th>Год</th>
<th>Тип</th>
<th>Сумма</th>
<th>Отдел</th>
</tr>
</thead>
<?php
					
						$num = 16;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM analis");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;		
					
						$query = mysql_query("SELECT * from analis ORDER BY id DESC LIMIT $start, $num");	
							while($row = mysql_fetch_array($query)) {
								echo '<tr><td>';
								if ($row['m'] == 1) {
								echo "Январь";
								}if ($row['m'] == 2) {
								echo "Февраль";
								}if ($row['m'] == 3) {
								echo "Март";
								}if ($row['m'] == 4) {
								echo "Апрель";
								}if ($row['m'] == 5) {
								echo "Май";
								}if ($row['m'] == 6) {
								echo "Июнь";
								}if ($row['m'] == 7) {
								echo "Июль";
								}if ($row['m'] == 8) {
								echo "Август";
								}if ($row['m'] == 9) {
								echo "Сентябрь";
								}if ($row['m'] == 10) {
								echo "Октябрь";
								}if ($row['m'] == 11) {
								echo "Ноябрь";
								}if ($row['m'] == 12) {
								echo "Декабрь";
								}
								echo '</td>';
								echo '<td>';
								echo "20",$row['g'];
								echo '</td>';
								echo '<td>';
								if ($row['t'] == 1) {
								echo "SAVOIR";
								}if ($row['t'] == 2) {
								echo "Контур";
								}if ($row['t'] == 3) {
								echo "Сертум";
								}if ($row['t'] == 4) {
								echo "Центр.Информ";
								}
								echo '</td>';
								echo '<td>';
								echo $row['s'];
								echo '</td>';
								echo '<td>';
								if ($row['o'] == 1) {
								echo "ЭЦП";
								}if ($row['o'] == 2) {
								echo "Бух Отчет";
								}if ($row['o'] == 3) {
								echo "Ведение Б";
								}
								echo '</td></tr>';
							}
							
							
					?>
				</table>
<?
// Проверяем нужны ли стрелки назад
if ($page != 1) $pervpage = '<a href=?page=1>Первая</a> | <a href=?page='. ($page - 1) .'>Предыдущая</a> | ';
// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = ' | <a href=?page='. ($page + 1) .'>Следующая</a> | <a href=?page=' .$total. '>Последняя</a>';

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 5 > 0) $page5left = ' <a href=?page='. ($page - 5) .'>'. ($page - 5) .'</a> | ';
if($page - 4 > 0) $page4left = ' <a href=?page='. ($page - 4) .'>'. ($page - 4) .'</a> | ';
if($page - 3 > 0) $page3left = ' <a href=?page='. ($page - 3) .'>'. ($page - 3) .'</a> | ';
if($page - 2 > 0) $page2left = ' <a href=?page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';
if($page - 1 > 0) $page1left = '<a href=?page='. ($page - 1) .'>'. ($page - 1) .'</a> | '; 

if($page + 5 <= $total) $page5right = ' | <a href=?page='. ($page + 5) .'>'. ($page + 5) .'</a>';
if($page + 4 <= $total) $page4right = ' | <a href=?page='. ($page + 4) .'>'. ($page + 4) .'</a>';
if($page + 3 <= $total) $page3right = ' | <a href=?page='. ($page + 3) .'>'. ($page + 3) .'</a>';
if($page + 2 <= $total) $page2right = ' | <a href=?page='. ($page + 2) .'>'. ($page + 2) .'</a>';
if($page + 1 <= $total) $page1right = ' | <a href=?page='. ($page + 1) .'>'. ($page + 1) .'</a>';

// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
echo "</div>";
}
?>


	 
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
	
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Месяц', 'SAVOIR (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=14 AND o = 1 AND t=1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Контур (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=14 AND o = 1 AND t=2");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Сертум (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=14 AND o = 1 AND t=3");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Алкоголь (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=14 AND o = 1 AND t=4");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)'],
<?php 
$query1 = mysql_query("SELECT * from analis WHERE g = 14 AND o = 1 GROUP BY m ORDER BY m");	
while($row1 = mysql_fetch_array($query1)) {


echo "['",$row1['m'],"', ";
$query2 = mysql_query("SELECT * from analis WHERE g = 14 AND o = 1 AND m = ".$row1['m']." ");
while($row2 = mysql_fetch_array($query2)) {
if ($row2['t'] == 1) {
echo $row2['s'],",";
}else{
echo "0";
}
if ($row2['t'] == 2) {
echo $row2['s'],",";
}else{
echo "0";
}
if ($row2['t'] == 3) {
echo $row2['s'],",";
}else{
echo "0";
}
if ($row2['t'] == 4) {
echo $row2['s'],",";
}else{
echo "0";
}
}
echo "],
";
}
?>
        ]);

        var options = {
          title: '2014 (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=14 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 
          hAxis: {title: 'Месяц',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_14'));
        chart.draw(data, options);
      }
    </script>
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Месяц', 'SAVOIR (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND t=1 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Контур (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND t=2 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Сертум (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND t=3 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Алкоголь (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND t=4 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)'],
<?php 
$query1 = mysql_query("SELECT * from analis WHERE g = 13 AND o = 1 GROUP BY m ORDER BY m");	
while($row1 = mysql_fetch_array($query1)) {


echo "['",$row1['m'],"', ";
$query2 = mysql_query("SELECT * from analis WHERE g = 13 AND o = 1 AND m = ".$row1['m']." ");	
while($row2 = mysql_fetch_array($query2)) {
if ($row2['t'] == 1) {
echo $row2['s'],",";
}else{
echo 0;
}
if ($row2['t'] == 2) {
echo $row2['s'],",";
}else{
echo 0;
}
if ($row2['t'] == 3) {
echo $row2['s'],",";
}else{
echo 0;
}
if ($row2['t'] == 4) {
echo $row2['s'],",";
}else{
echo 0;
}
}
echo "],
";
}
?>
        ]);

        var options = {
          title: '2013 (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 
          hAxis: {title: 'Месяц',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_13'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Месяц', 'SAVOIR (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=12 AND t=1 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Контур (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=12 AND t=2 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Сертум (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=12 AND t=3 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Алкоголь (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=12 AND t=4 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)'],
<?php 
$query1 = mysql_query("SELECT * from analis WHERE g = 12 AND o = 1 GROUP BY m ORDER BY m");	
while($row1 = mysql_fetch_array($query1)) {


echo "['",$row1['m'],"', ";
$query2 = mysql_query("SELECT * from analis WHERE g = 12 AND o = 1 AND m = ".$row1['m']." ");
while($row2 = mysql_fetch_array($query2)) {
if ($row2['t'] == 1) {
echo $row2['s'],",";
}
if ($row2['t'] == 2) {
echo $row2['s'],",";
}
if ($row2['t'] == 3) {
echo $row2['s'],",";
}
if ($row2['t'] == 4) {
echo $row2['s'],",";
}
}
echo "],
";
}
?>
        ]);

        var options = {
          title: '2012 (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=12 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 
          hAxis: {title: 'Месяц',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_12'));
        chart.draw(data, options);
      }
    </script>
	
	
	    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Месяц', 'SAVOIR (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND t=1 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Контур (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND t=2 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Сертум (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND t=3 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 'Алкоголь (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND t=4 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)'],
<?php 
$query1 = mysql_query("SELECT * from analis WHERE g = 13 AND o = 1 GROUP BY m ");	
while($row1 = mysql_fetch_array($query1)) {


echo "['",$row1['m'],"', ";
$query2 = mysql_query("SELECT * from analis WHERE g = 13 AND o = 1 AND m = ".$row1['m']." ");
while($row2 = mysql_fetch_array($query2)) {
if ($row2['t'] == 1) {
echo $row2['s'],",";
}else{
echo"0";
}
if ($row2['t'] == 2) {
echo $row2['s'],",";
}else{
echo"0";
}
if ($row2['t'] == 3) {
echo $row2['s'],",";
}else{
echo"0";
}
if ($row2['t'] == 4) {
echo $row2['s'],",";
}else{
echo"0";
}
}
echo "],
";
}
?>
        ]);

        var options = {
          title: '2013 (<?php 
		
		$savoir = mysql_query("SELECT sum(s) from analis WHERE g=13 AND o = 1");
		echo number_format(mysql_result($savoir, 0)); 
		
		?>)', 
          hAxis: {title: 'Месяц',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_13'));
        chart.draw(data, options);
      }
    </script>
	

	
	<div id="chart_12" class="col-md-6" style="height: 300px;"></div>	
	<div id="chart_13" class="col-md-6" style="height: 300px;"></div>
	<div id="chart_14" class="col-md-6" style="height: 300px;"></div>
	








</div>

</div>
</div>
</body>
</html>