<a <?php $result1 = mysql_query("SELECT count(*) from focus WHERE a='0' AND del='0'");$z1 = mysql_result($result1, 0);if ($z1 > 0) { echo 'style="background: #C42D29; color: #fff;"';} ?> href="/zayav.php" title="Новые заявки">
<span class="glyphicon glyphicon-filter"></span>
<span class="badge"><?php include 'conf.php';$$z1 = mysql_result($result1, 0);echo $z1;?></span>
</a>