<?php

include 'conn.php'; 



		$q = "SELECT * FROM `lico` WHERE inn = '".$_POST['inn']."'";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);







    //это переменная содержит имена существующих пользователей
	
      $existing_users=array($person['inn']);     
	  $inn=$_POST['inn'];
      //проверка существует ли пользователь в массиве $existing_users
      if (in_array($inn, $existing_users))
      {
      //юзер недоступен
      echo $person['name'];
      } 
      else
      {
      //доступен
      echo "yes";
      }
	  
	  
      ?>