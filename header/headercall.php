<a <?php $result = mysql_query("SELECT count(*) from call_center WHERE yes = '0'");$class = mysql_result($result, 0);if ($class > 0) { echo 'style="color: #fff;"';}?> href="/call_cent.php" title="Обзвон холодных клиентов">
<span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>
<span class="badgeeee"><?php  $result = mysql_query("SELECT count(*) from call_center WHERE  yes = '0'");echo mysql_result($result, 0); ?></span>
</a>