<?php
include 'conf.php';  

echo '<select name="produkti" onchange="produktiTus(this.value)" style="" class="form-control">';
echo '<option></option>';
$query32 = mysql_query("SELECT * FROM `produkti` WHERE `del` = '0' AND `parent`='".$_GET['status']."'");	
	while($row32 = mysql_fetch_array($query32)) {
    echo '<option  value="'.$row32['id'].'">';
	echo $row32['name'];
	echo "</option>";
  }
echo '<option  value="0"></option>';
echo '</select>';

?>