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

$qestar = "SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,kolichschet,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr,gen,kvo,prod,kvogorod,goroddd,skidka,priceks,d,m,y,time,oferta,del FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."'";
$restar = mysql_query($qestar);
$pestar = mysql_fetch_array($restar);

$qestar2 = "SELECT * FROM schet WHERE rand ='".$_GET['rand']."'";
$restar2 = mysql_query($qestar2);
$pestar2 = mysql_fetch_array($restar2);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript"src="js/script.js" defer></script>
</head>
<body>
<table>
<thead class='avvozhead'>
<tr>
<th>№</th>
<th>Дата</th>
<th>ИНН</th>
<th>КПП</th>
<th style="
    width: 500px;
">Наименование</th>
<th>Продукт</th>
<th>Роль</th>
<th>Тип продаж</th>
<th>Выставил</th>
<th style="
    width: 200px;
">Агент</th>
<th style="
    width: 10%;
">№ счета</th>
<th>Сумма контур</th>
</tr>
</thead>
<tbody class="avvoz">
<? 
$a=0;
$y=date("y");
$m=date("m");
$dat=$y.$m;
$schetdob="";
$query = mysql_query("SELECT COUNT(av.schet) FROM av left join schet on schet.rand=av.rand where schet.del!='1' and schet.akt_date='".$_GET['date']."'") or die ("<br>Invalid query: " . mysql_error());
$kolschet=mysql_result($query, 0); 
$r=mysql_query("SELECT av.pagent,av.sagent,av.dagent,av.shtsert,av.chisrol,av.schet,av.os_prod,av.dob_usl,av.dob_prod,av.dobprodschet,av.summschet,av.kto,av.rand,schet.rand,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as orgname,av.os_prod,produkti.name,av.dob_prod,users.f_name,users.l_name,schet.agent,agent.name as agna,schet.akt,schet.akt_date from av inner join schet on schet.rand=av.rand inner join produkti on av.os_prod=produkti.id inner join users on schet.kto=users.users_id left join agent on schet.agent=agent.id WHERE schet.del='0' and schet.akt!='0' and schet.akt_date='".$_GET['date']."' group by schet.rand  ORDER BY av.id ASC");
   while($res = mysql_fetch_assoc($r)) :?>	  
    
<?
$schetdob=$res['schet'];
if($res['pagent']!=""&&$res['sagent']!=""&&$res['dagent']!="")
{
	$rolep="L-агент";
	$roles="S-агент";
	$ros=$res['sagent'];
	$roled="D-агент";
	$sum2=2;
	$tipp="Подключение";
}
if($res['pagent']==""&&$res['sagent']!=""&&$res['dagent']!="")
{
	$rolep="";
	$roles="S-агент";
	$ros=$res['sagent'];
	$roled="D-агент";
	$sum2=1;
	$tipp="Продление";
}
if($res['pagent']==""&&$res['sagent']!=""&&$res['dagent']=="")
{
	$rolep="";
	$roles="S-агент";
	$ros=$res['sagent'];
	$roled="";
	$sum2=1;
	$tipp="Продление";
}
if($res['pagent']==""&&$res['sagent']==""&&$res['dagent']!="")
{
	$rolep="";
	$roles="";
	$roled="D-агент";
	$k=1;
	$tipp="Удост.личн.";
}
if($res['pagent']!=""&&$res['sagent']!=""&&$res['dagent']=="")
{
    $rolep="L-агент";
	$roles="S-агент";
	$ros=$res['sagent'];
	$roled="";
	$sum2=2;
	$tipp="Подключение";
}
if($res['pagent']=="Без АВ"&&$res['sagent']=="Без АВ"&&$res['dagent']=="Без АВ")
{
    $rolep="Без АВ";
	$roles="Без АВ";
	$roled="Без АВ";
	$tipp="Без АВ";
	$sum2=2;
}
$a++;

?>
<? if($res['shtsert']!=0)
{
	$sum=$res['chisrol']+$res['shtsert']-1;
}
else
{
	$sum=$res['chisrol']+$res['shtsert'];
}
?>
<? $lag=0;
$sag=0;
$dag=0;
$la=0;
$sa=0;
$da=0;
$sche=0;
$sched=0;
$schetit=0;
$schetid=0;
$k=0;
$li=0;
++$k;
for($ch=0;$ch<$sum;$ch++)
{ ?>
<tr style="
    border: 1px solid #d3d3d3;
">
<td>
<?echo $a;?>

</td>
<td>
<?echo $res['d'].".".$res['m'].".".$res['y'];?>

</td>
<td>
<?echo $res['inn'];?>

</td>
<td>
<?echo $res['kpp'];?>

</td>
<td>
<?echo $res['orgname'];?>

</td>
<td>
<?echo $res['name'];?>
</td>
<td>

<?

if ($res['pagent']!="") {
	$roled="";
	for($lag;$lag<1;$lag++)
	{?>
    <p style="
    float: left;
    width: 100%;
"><?echo $rolep;$rolep="";break;?></p><?}}else{++$schetit;}?>
<?
if($schetit!=0){
	
if ($res['sagent']!="") {
	$rolep="";
	$roled="";
	for($sag;$sag<1;$sag++)
	{?>
    <p style="
    float: left;
    width: 100%;
"><?echo $roles;$roles="";$res['sagent']="";break;?></p><?}}else{++$schetid;}}?>
<?if($schetid!=0){
if ($res['dagent']!="") {
	$rolep="";
	$roled="D-агент";
	if ($res['dagent']=="Без АВ")
	{
		$roled="Без АВ";
	}
	for($dag;$dag<$res['shtsert'];$dag++)
	{?>
    <p style="
    float: left;
    width: 100%;
"><?echo $roled;break;?></p><?}}}?>

</td>
<td>
<? 

	for($cha=0;$cha<1;$cha++)
{
	if($roled=="D-агент")
{

	echo $tipp="Удост.личн.";
}
else
{
echo'<p>';
echo $tipp;
echo'</p>';
}
}
?>

</td>
<td>
<? 
echo'<p>';
echo mb_substr($res['f_name'],0,1,'UTF-8'),'. '.$res['l_name'];
echo'</p>';?>

</td>
<td>
<?
echo'<p>';
 echo $res['agna'];
echo'</p>';?>

</td>
<td>
<?if ($res['pagent']!="") {for($ch;$ch<1;$ch++)
{ echo $res['pagent'];break;}}else{++$schetit;}?>
<?  if($schetit!=0){echo $ros;$ros="";}else{++$schetid;}?>
<?if($roled!="")
{$li++;for ($o = 0 ; $o < 1 ; ++$o){
	if($schetid!=1)
	{
	echo $res['dagent']."K".($schetid-$li)."";
	}
	if($schetid==1)
	{
	echo $res['dagent']."K".($schetid)."";
	}
}}
?>

</td>

<td>

<?  
echo'<p>';
echo $res['summschet'];
echo'</p>';?>


<?if($roled==""){++$schetit;}else{$schetit=0;}if($res['pagent']==""){++$schetid;}else{$schetid=0;}
}$query = mysql_query("SELECT COUNT(schet) from av where dobprodschet!='0' and schet='".$res['schet']."'") or die ("<br>Invalid query: " . mysql_error());
	if (mysql_result($query, 0) == 1) {
		echo'</tr>';

		$redop=mysql_query("SELECT av.pagent,av.sagent,av.dagent ,av.schet,av.dob_prod,av.chisrol,av.shtsert,dobprod.dobprodnaim from av left join dobprod on av.dob_prod=dobprod.id where av.dob_prod!='' and av.schet='".$schetdob."'");
        while($resdopp = mysql_fetch_assoc($redop)){
         
			 if($resdopp['pagent']!=""&&$resdopp['sagent']!=""&&$resdopp['dagent']!="")
{
	$rolepd="L-агент";
	$rolesd="S-агент";
	$rosd=$resdopp['sagent'];
	$roledd="D-агент";
	$sum2d=2;
	$tippd="Подключение";
}
if($resdopp['pagent']==""&&$resdopp['sagent']!=""&&$resdopp['dagent']!="")
{
	$rolepd="";
	$rolesd="S-агент";
	$rosd=$resdopp['sagent'];
	$roledd="D-агент";
	$sum2d=1;
	$tippd="Продление";
}
if($resdopp['pagent']==""&&$resdopp['sagent']!=""&&$resdopp['dagent']=="")
{
	$rolepd="";
	$rolesd="S-агент";
	$rosd=$resdopp['sagent'];
	$roledd="";
	$sum2d=1;
	$tippd="Продление";
}
if($resdopp['pagent']==""&&$resdopp['sagent']==""&&$resdopp['dagent']!="")
{
	$rolepd="";
	$rolesd="";
	$roledd="D-агент";
	$kd=1;
	$tippd="Удост.личн.";
}
if($resdopp['pagent']!=""&&$resdopp['sagent']!=""&&$resdopp['dagent']=="")
{
    $rolepd="L-агент";
	$rolesd="S-агент";
	$rosd=$resdopp['sagent'];
	$roledd="";
	$sum2d=2;
	$tippd="Подключение";
}
if($resdopp['pagent']=="Без АВ"&&$resdopp['sagent']=="Без АВ"&&$resdopp['dagent']=="Без АВ")
{
    $rolepd="Без АВ";
	$rolesd="Без АВ";
	$roledd="Без АВ";
	$tippd="Без АВ";
	$sum2d=2;
}
if($resdopp['shtsert']!=0)
        {
	$sum2=$resdopp['chisrol']+$resdopp['shtsert']-1;
      }
       else
      {
	$sum2=$resdopp['chisrol']+$resdopp['shtsert'];
     }
	 for($cht=0;$cht<$sum2;$cht++)
       {
		echo'<tr style="
    border: 1px solid #d3d3d3;
">';
		echo'<td>';
		echo $a;
		echo'</td>';
		echo'<td>';
		echo $res['d'].".".$res['m'].".".$res['y'];;
		echo'</td>';
		echo'<td>';
		echo $res['inn'];
		echo'</td>';
		echo'<td>';
		echo $res['kpp'];
		echo'</td>';
		echo'<td>';
		echo $res['orgname'];
		echo'</td>';
		echo'<td>';
		echo "Доп.продукт ".$resdopp['dobprodnaim'];
		echo'</td>';
		echo'<td>';
	if ($resdopp['pagent']!="") {
	$roledd="";
	for($lag;$lag<1;$lag++)
	{
echo $rolepd;$rolepd="";break;}}else{++$schetitd;}
if($schetitd!=0){
	
if ($resdopp['sagent']!="") {
	$rolepd="";
	$roledd="";
	$rolesd="S-агент";
	for($sag;$sag<1;$sag++)
	{
   echo $rolesd;$roless="";$resdopp['sagent']="";break;}}else{++$schetidd;}}
if($schetidd!=1){
if ($resdopp['dagent']!="") {
	$rolepd="";
	$roledd="D-агент";
	if ($resdopp['dagent']=="Без АВ")
	{
		$roledd="Без АВ";
	}
	for($dag;$dag<$resdopp['shtsert'];$dag++)
	{echo $roledd;break;}}}
		echo'</td>';
		echo'<td>';
		echo "Доп. услуга";
		echo'</td>';
		echo'<td>';
        echo mb_substr($res['f_name'],0,1,'UTF-8'),'. '.$res['l_name'];
		echo'</td>';
		echo'<td>';
		echo $res['agna'];
		echo'</td>';
		echo'<td>';
       if ($resdopp['pagent']!="") {for($ch;$ch<1;$ch++)
{ echo $resdopp['pagent'];break;}}else{++$schetitd;}
if($schetitd!=0){echo $rosd;$rosd="";}else{++$schetidd;}
if($roledd!="")
{$li++;for ($o = 0 ; $o < 1 ; ++$o){
	if($schetidd!=1)
	{
	echo $res['dagent']."K".($schetidd-1)."";
	}
	if($schetidd==1)
	{
	echo $res['dagent']."K".($schetidd)."";
	}
}}

		echo'</td>';
		echo'<td>';
		echo $res['summschet'];
		echo'</td>';
		echo'</tr>';
		}
		}
		if($roledd==""){++$schetitd;}else{$schetitd=0;}if($resdopp['pagent']==""){++$schetidd;}else{$schetidd=0;}
    }
	else
	{
		echo'</tr>';
	}
?>

<?php endwhile;?>


</tbody>
</table>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js" async></script>
</body>
</html>