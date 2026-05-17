<?php
include 'conf.php';  
echo '<span class="input-group-addon">Тариф:</span>
<select name="tarif" class="form-control col-md-12">';
$query1 = mysql_query("SELECT * from produkti WHERE parent = '$_GET[id]'");
while($row1 = mysql_fetch_array($query1)) {
echo "<option value='".$row1['id']."'>".$row1['name']."</option>";
}
echo'</select>';
?>

