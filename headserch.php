<?php
# подключаем конфиг
include 'conf.php';  

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
    { 
        setcookie('id', '', time() - 60*24*30*12, '/'); 
        setcookie('hash', '', time() - 60*24*30*12, '/');
    setcookie('errors', '1', time() + 60*24*30*12, '/'); 
    header('Location: index.php'); exit();
    } 
} 
else 
{ 
  setcookie('errors', '2', time() + 60*24*30*12, '/');
  header('Location: index.php'); exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<table class="table tablehover">

<?php

$search_slovo = $_GET['naim'];
$search_slovo1 = $_GET['inn'];
$search_slovo2 = $_GET['kpp'];
$search_slovo4 = $_GET['ogrn'];
$search_slovo3 = $_GET['email'];
$search_slovo4 = $_GET['ns'];
$search_slovo5 = $_GET['nsinn'];
$search_slovo6 = $_GET['vns'];
$search_slovo7 = $_GET['vnsinn'];

if ((isset($search_slovo1))) {
$search_name= mysql_query("SELECT * FROM `ogrn` WHERE CONCAT(naim,' ',inn) LIKE '%$search_slovo1%' AND `naim` LIKE '%$search_slovo%'  AND `kpp` LIKE '%$search_slovo2%' AND `ogrn` LIKE '%$search_slovo4%' AND `email` LIKE '%$search_slovo3%' LIMIT 20");
if (mysql_num_rows($search_name) != 0) {
while ($row = mysql_fetch_assoc($search_name)) {



					
						
								echo '<div  class="col-md-12"  style="    font-size: 12px;
    padding: 4px 8px;
    background: #f3f3f3;
    margin-bottom: 2px;
    border-radius: 0;
    border: 0px solid #ccc;">';
								echo '<div class="">';
								echo '<a class="" href="./kartklient.php?id='.$row['id'].'" style="
    white-space: nowrap;
	    color: #3097d1;
">';
								echo $row['naim'];
								echo '</a>';
								echo '</div>';
								echo '<div class="left">';
								echo $row['inn'];
								if(!empty($row['kpp'])){
								echo '♦';
								}
								echo $row['kpp'];
								echo '</div>';
								echo '<div class="left">';
								echo '<a  style="color: #3097d1;" title="Новое напоминание" href="./napomni.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-time"></span></a>';
								echo '</div>';
								echo '<a  title="Карточка клиента" href="./kartklient.php?id=' .$row['id']. '">';
								echo '<div  class="btn-primary" style="
    float: right;
    padding: 11px;
    margin: 0;
    width: 33px;
    line-height: 23px;
    background: #3b5998;
    height: 41px;
    margin-top: -18px;
    margin-right: -9px;
    margin-bottom: -10px;
    color: #fff;
	position: relative;
">';
								echo '<span class="glyphicon glyphicon-share" aria-hidden="true"></span>';
								echo '</div>';
								echo '</a>';
								echo '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="
    float: right;
    position: relative;
    margin-top: -18px;
    height: 41px;
    right: 0;
    border: none;
    margin-bottom: -5px;
">
   <span class="glyphicon glyphicon-plus"></span>
  </button>
  <ul class="dropdown-menu" role="menu">';

		$query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."'");	
		while($row214 = mysql_fetch_array($query214)) {
		$query32 = mysql_query("SELECT * from uslugi  WHERE del = '0' AND go = '1' AND id = '".$row214['uslugi']."' ORDER BY name ");	
		while($row32 = mysql_fetch_array($query32)) {

    echo "<li>";
	echo '<a   onclick="countRabbits('.$row['id'].''.$row32['id'].')">';
	echo $row32['name'];
	echo "</a>";
	echo "</li>";
  }
  }


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
if ((isset($search_slovo4))) {
$search_name= mysql_query("SELECT * FROM `schet` WHERE ns LIKE '%$search_slovo4%' and idkli='".$_GET["idkli"]."' LIMIT 1");
if (mysql_num_rows($search_name) != 0) {
while ($row = mysql_fetch_assoc($search_name)) {
    echo'<div style="width: 400px; padding: 10px;">';
    echo '<span style="float: left;margin-right: 10px;">'.$row["ns"].'</span>';
    echo '<span style="float: left;margin-right: 10px;">'.$row["inn"].'</span>';
    echo '<span style="float: left;margin-right: 10px;">'.$row["kpp"].'</span>';
    echo '<span style="float: left;margin-right: 10px;">'.$row["name"].'</span>';
    echo'<div >';
    echo '<button id="schet'.$_GET["id"].'" value="'.$row["ns"].'" type="button" style="float: left;margin-bottom: 10px; margin-right: 10px;position: relative;left: 35%;margin-top: 20px;">Привязать</button>';
    echo'</div>';
    echo'</div>';
}
}
}
if ((isset($search_slovo5))) {
    $search_name= mysql_query("SELECT * FROM `ogrn` WHERE CONCAT(naim,' ',inn) LIKE '%$search_slovo5%' LIMIT 4");
    if (mysql_num_rows($search_name) != 0) {
        while ($row = mysql_fetch_assoc($search_name)) {
            echo'<div style="width: 400px; padding: 10px;">';
            echo '<span style="float: left;margin-right: 10px;">'.$row["inn"].'</span>';
            echo '<span style="float: left;margin-right: 10px;">'.$row["kpp"].'</span>';
            echo '<span style="float: left;margin-right: 10px;">'.$row["naim"].'</span>';
            echo'<div >';
            echo '<button id="schet'.$_GET["id"].'" value="'.$row["id"].'" type="button" style="float: left;margin-bottom: 10px; margin-right: 10px;position: relative;right: 45%;margin-top: 30px;">Привязать</button>';
            echo'</div>';
            echo'</div>';
        }
    }
}
if ((isset($search_slovo6))) {
    if($_GET["innv"]!="")
    {
      $datainn='and inn='.$_GET["innv"].'';
    }
    else
    {
        $datainn='';
    }
    $search_name= mysql_query("SELECT * FROM `schet` WHERE ns LIKE '%$search_slovo6%' $datainn LIMIT 4");
    if (mysql_num_rows($search_name) != 0) {
        while ($row = mysql_fetch_assoc($search_name)) {
            echo'<div style="width: 400px;padding: 10px;height: 50px;float: left;">';
            echo '<span style="float: left;margin-right: 10px;">'.$row["ns"].'</span>';
            echo '<span style="float: left;margin-right: 10px;">'.$row["inn"].'</span>';
            echo '<span style="float: left;margin-right: 10px;">'.$row["kpp"].'</span>';
            echo '<span style="float: left;margin-right: 10px;">'.$row["name"].'</span>';
            echo'<div >';
            echo '<button id="schetnsv" value="'.$row["ns"].$row["idkli"].'" type="button">Привязать</button>';
            echo'</div>';
            echo'</div>';
        }
    }
}
if ((isset($search_slovo7))) {
    if($_GET["vnsid"]!="")
    {
        $dataid='and id='.$_GET["vnsid"].'';
    }
    else
    {
        $dataid='';
    }
    $search_name= mysql_query("SELECT * FROM `ogrn` WHERE CONCAT(naim,' ',inn) LIKE '%$search_slovo7%' $dataid LIMIT 4");
    if (mysql_num_rows($search_name) != 0) {
        while ($row = mysql_fetch_assoc($search_name)) {
            echo'<div style="width: 100%;padding: 10px;height: 50px;float: left;">';
            echo '<span style="float: left;margin-right: 10px;">'.$row["inn"].'</span>';
            echo '<span style="float: left;margin-right: 10px;">'.$row["kpp"].'</span>';
            echo '<span style="float: left;margin-right: 10px;"id="naim">'.$row["naim"].'</span>';
            echo'<div >';
            echo '<button id="schetinn" value="'.$row["inn"].'" type="button">Привязать</button>';
            echo'</div>';
            echo'</div>';
        }
    }
}
?>

</table>












			
			
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>
</html>