<?php
# подключаем конфиг
include 'conf.php';


		$q = "SELECT * FROM ogrn WHERE id =$_GET[id]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);





if($person['rand'] == 0){
 mysql_query("UPDATE ogrn SET `rand`='".rand(100000000,999999999).date('dmY')."' WHERE `id`=$_GET[id]");
}
$ogrnq = "SELECT * FROM ogrn";
		$ogrnresult = mysql_query($ogrnq);
		$ogrn = mysql_fetch_array($ogrnresult);

$qq = "SELECT * from tekkli WHERE idkli = '".$_GET['id']."'";
		$resultt = mysql_query($qq);
		$personn = mysql_fetch_array($resultt);

$qqq = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,priceks,kto,rand FROM schet WHERE del = '0' AND idkli = '".$_GET['id']."'");
$perq = mysql_fetch_array($qqq);



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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" ></script>
	<script type="text/javascript"src="js/script.js" defer></script>
<link rel="shortcut icon" href="/favicon.ico">

</head>
<body>
 <div class="tab1">
<table>
<thead>
<?php $r = mysql_query("SELECT count(id) FROM call_center"); $res = mysql_fetch_array($r) ; ?>
<tr>
			<th>Организация(<?php echo $res[0];?>)</th>
<th>ИНН</th>
<th>КПП</th>
			<th>Продукт</th>

			<th>Тип продления</th>
			<th>Дата продления</th>
			</tr>
</thead>
<tbody>
<?php $r = mysql_query("SELECT * FROM call_center inner join produkti on call_center.4=produkti.id inner join ogrn on call_center.9=ogrn.inn and call_center.10=ogrn.kpp and (call_center.otk!='1' or call_center.yes='21') order by naim  "); while($res = mysql_fetch_assoc($r))  : ?>
<tr value="<?php echo $res['id']?>">
			<td style="text-align:left;" value="<?php echo $res['9']?>"><?php echo $res['naim']?></td>
<td value="<?php echo $res['9']?>"><?php echo $res['9']?></td>
<td  value="<?php echo $res['10']?>"><?php echo $res['10']?></td>
			<td value="<?php echo $res['4']?>"><?php echo $res['name']?></td>
			<td value="<?php echo $res['6']?>"><?php echo $res['6']?></td>
			<td value="<?php echo substr($res['date'], 6, 2).'.'.substr($res['date'], 4, 2).'.'.substr($res['date'], 0, 4);?>"><?php echo substr($res['date'], 6, 2).'.'.substr($res['date'], 4, 2).'.'.substr($res['date'], 0, 4);?></td>
			</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

  </body>
  </html>