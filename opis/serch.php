<?
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'shoes');  
define ('DB_PASSWORD', 'ShoesOpt');
define ('DB_NAME', 'shoes');
mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8") or die ("<br>Invalid query: " . mysql_error()); 
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());
?>

<table class="table tablehover">

<?php

$search_slovo1 = $_GET['name'];



if ((isset($search_slovo1))) 
{
$search_name= mysql_query("SELECT * FROM `kind_of_shoes` WHERE name LIKE '%$search_slovo1%'  LIMIT 20");
if (mysql_num_rows($search_name) != 0) 
{
while ($row = mysql_fetch_assoc($search_name))
{


					
						
								echo '<div  class="col-md-12"  style="    font-size: 12px;
    padding: 4px 8px;
	height: 30px;
    background: #f3f3f3;
    margin-bottom: 2px;
    border-radius: 0;
    border: 0px solid #ccc;">';
								echo '<div style="width: 80%;float: right;">';
								
								echo '<a class="" href="./conter_ogrn.php?id_ogrn='.$_GET['id_ogrn'].'&id_kli=' .$row['id'].'" style="
    white-space: nowrap;
	    color: #3097d1;
">';
								echo $row['naim'];
								echo '</a>';
								echo '</div>';
								echo '<div style="    float: left; width: 20%;">';
								echo $row['inn'];
								if(!empty($row['kpp'])){
								echo '♦';
								}
								echo $row['kpp'];
								echo '</div>';
								echo '</div>';
								echo '</a>';
								echo '</ul>';
								echo '</div>';
						
				
				












}
} else {
echo "";
if(isset($_GET['inn'])) {
	echo "<a class='btn btn-info' href='/newogrn.php?id=186&inn=".$_GET['inn']."&kpp='>Создать организацию по ИНН: ".$_GET['inn']."</a>";
}
}
} 
?>
</table>









