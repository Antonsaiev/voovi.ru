<?php

include 'conf.php';
$q = "SELECT * FROM `klient` WHERE inn = '".$_POST['inn']."' AND kpp = '".$_POST['kpp']."'";
		$result2 = mysql_query($q);
		$person = mysql_fetch_array($result2);
		
		$result = mysql_query("SELECT count(*) from `klient` WHERE inn = '".$_POST['inn']."' AND kpp = '".$_POST['kpp']."'");
		$ras = mysql_result($result, 0); 
		
    //это переменная содержит имена существующих пользователей
	if($ras == 0){echo 'yes';
	} else {
	echo $person['id'];
	}
      
	  
      ?>