<a <?php $result = mysql_query("SELECT count(*) from schet WHERE cher='1'");$class = mysql_result($result, 0);if ($class > 0) { echo 'style="background: #FFDEAD;" ';}?> href="/toha.php?cher=1" title="Отказались">
<span class='glyphicon glyphicon-ruble' aria-hidden="true"></span>
<span class="badge"><?php  $result = mysql_query("SELECT count(*) from schet WHERE cher='1'");echo mysql_result($result, 0); ?></span>
</a>