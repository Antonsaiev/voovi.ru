<a <?php $result = mysql_query("SELECT count(*) from schet WHERE oplachen!='1' and oplachenks!='1' AND gotov = '0' AND akt = '0'  AND otk = '0'");$class = mysql_result($result, 0);if ($class > 0) { echo 'style="" ';}?> href="/toha.php?neoplachen=0" title="Неоплаченые счета">
<span class='glyphicon glyphicon-ruble' aria-hidden="true"></span>
<span class="badge"><?php  $result = mysql_query("SELECT count(*) from schet WHERE oplachen!='1' and oplachenks!='1' AND gotov = '0' AND akt = '0'  AND otk = '0'");echo mysql_result($result, 0); ?></span>
</a>