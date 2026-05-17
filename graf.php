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

if(isset($_GET['y'])){
$y="AND y = '$_GET[y]'";
}else{
$y=""; 
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

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);


      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
         ['Month', 'SAVOIR'],
         <?php
$query = mysql_query("SELECT DISTINCT ns,kolichschet,d,m,y,nomerschet,nomerschetks,ogrn,status,prodlen,generac,lico,otdel,filial,god,nomerdog,data,produkt,install,akt_date,price,kto,
inn,kpp,idkli,goroddd,otk,koment,oplachen,oplachenks,priceks,doljen,gotov,akt,url,groupi,gr  
from schet WHERE del = '0' AND akt = '1' $y GROUP BY m ORDER BY m");	 
while($row = mysql_fetch_array($query)) {
echo "['".$row['y']."/".$row['m']."', ";
$savoir = mysql_query("SELECT SUM(price) FROM schet WHERE m='".$row['m']."' AND y='".$_GET['y']."' AND akt = '1' GROUP BY rand");
echo mysql_result($savoir, 0)."],"; 
}
?>
      ]);

    var options = {
      title : 'Доход за <?php echo $_GET['y']; ?>г.',
      vAxis: {title: 'Сумма'},
      hAxis: {title: 'Месяц'},
      seriesType: 'bars',
      series: {5: {type: 'line'}}
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
    </script>
<div id="chart_div" style="width: 900px; height: 500px;"></div> 
<br>

<script>

 var summ = <?php
$query = mysql_query("SELECT * FROM schet WHERE m='03' AND y='".$_GET['y']."' AND akt = '1'  GROUP BY rand");	 
while($row = mysql_fetch_array($query)) {
echo "".$row['price']."+";
}
/* $savoirq = mysql_query("SELECT SUM(price) FROM schet WHERE m='01' AND y='".$_GET['y']."' AND akt = '1'  GROUP BY rand");
echo mysql_result($savoirq, 0);   */
?>0;
document.write(summ);
</script>
   
 
</div>

</div>
</div>
</body>
</html>