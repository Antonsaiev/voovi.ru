<?php
	include './conf.php';
	require_once 'tarif_identity.php';
	
				if($_GET['tip'] == 't'){
				if($_GET['old'] == 1){
				voovi_tarif_archive_by_tarif_id($_GET['id']);
				}
				}if($_GET['tip'] == 'p'){
				$u = "UPDATE produkti SET `del`= '$_GET[old]'  WHERE id = $_GET[id]";
				mysql_query($u) or die(mysql_error($link));	
				}if($_GET['tip'] == 'u'){
				$u = "UPDATE uslugi SET `del`= '$_GET[old]'  WHERE id = $_GET[id]";
				mysql_query($u) or die(mysql_error($link));	
				}
				
	echo '<script type="text/javascript">
   document.location.href = "'.$_SERVER['HTTP_REFERER'].'";
</script>';
?>
