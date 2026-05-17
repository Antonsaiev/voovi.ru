<h3 style="border-bottom: 1px #333 solid; margin-top: 0;">Мои организации</h3>

<table class="table">
	<thead>
        <tr>
		<th>id</th>
		<th>Название</th>
		  <th>ИНН</th>
          <th>КПП</th>
		  <th style="width: 80px;"><span class="glyphicon glyphicon-user"></span> Открыть</th>
        </tr>
    </thead>
<?php
$query2 = mysql_query("SELECT * from klient WHERE tel = '".$userdata['users_login']."' ");	
while($row2 = mysql_fetch_array($query2)) {
$query2 = mysql_query("SELECT * from klient_ogrn WHERE klient = '".$row2['id']."' ");	
while($row2 = mysql_fetch_array($query2)) {
$query2 = mysql_query("SELECT * from ogrn WHERE ogrn = '".$row2['ogrn']."' ");	
while($row = mysql_fetch_array($query2)) {
echo '<tr style="font-size: 12px;"><td style="width: 50px; text-align: center;"><strong>';
echo $row['id'];
echo '</strong></td>';
echo '<td style="">';
echo $row['naim'];
echo '</td>';
echo '<td style="width: 100px;">';
echo $row['inn'];
echo '</td>';
echo '<td style="width: 100px;">';
echo $row['kpp'];
echo '</td>';
echo '<td>';
echo '<a title="Карточка организации" href="./kartklients.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-user"></span> Открыть</a>';
echo '</td></tr>';
}
}
}
?>
</table>