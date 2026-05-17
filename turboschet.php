<?php
include 'conf.php'; 

/*
$query = mysql_query("SELECT * FROM schet");
while($row = mysql_fetch_array($query)) {
$query2 = mysql_query("SELECT * FROM tarif WHERE name = '%скоренный%'");
while($row2 = mysql_fetch_array($query2)) {

$koment2 = "UPDATE schet SET `turbo`='".$row2['turbo']."' WHERE `id`='".$row['id']."' ";
mysql_query($koment2) or die(mysql_error($link));

}
}
*/

$query = mysql_query("SELECT * FROM schet");
while($row = mysql_fetch_array($query)) {
$query2 = mysql_query("SELECT * FROM tarif WHERE id = '".$row['prod']."'");
while($row2 = mysql_fetch_array($query2)) {
$koment2 = "UPDATE schet SET `turbo`='".$row2['turbo']."' WHERE `id`='".$row['id']."' ";
mysql_query($koment2) or die(mysql_error($link));
}
} 


$queryq = mysql_query("SELECT * FROM schet WHERE `otl3`!='0'");
while($rowq = mysql_fetch_array($queryq)) {
	$itog = date('Ymd') - $rowq['otl3'];
	if($itog >= 3){
		$koment2 = "UPDATE schet SET `otl3`='0' WHERE `id`='".$rowq['id']."' ";
		mysql_query($koment2) or die(mysql_error($link));
	}
}

?>