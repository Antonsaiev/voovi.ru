<a style="background: color: #fff;" href="#"  data-toggle="dropdown" role="button" aria-expanded="false" title="Мои дела">
<span class="glyphicon glyphicon-briefcase"></span>
<span class="badgee">


<?php 
echo '<script type="text/javascript">
var c=';
$query = mysql_query("SELECT DISTINCT 
ns,kolichschet,d,m,y,nomerschet,nomerschetks,ogrn,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,goroddd,akt_date,otk,koment,oplachen,
oplachenks,priceks,doljen,gotov,akt,url,groupi,install,gr
FROM schet WHERE 
del = '0' AND akt!='1' AND otk != '1'");
while($row = mysql_fetch_array($query)) {

	$udosrpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$udosresultrpod = mysql_query($udosrpod);
	$udospersonrpod = mysql_fetch_array($udosresultrpod);
	
if($userdata['inogrn'] != 89097565645){
	if($udospersonrpod['parent'] == $userdata['inogrn']) {
		echo '1+';
	}
}else{
	$udos = mysql_query("SELECT * FROM users_access WHERE users = '".$userdata['users_id']."' AND uslugi = '".$udospersonrpod['parent']."'");
	while($udostup = mysql_fetch_array($udos)) {
		echo '1+';
	}
}
	 
	
}
echo '0;
document.write(c);
</script>';
?>
</span>
</a>

				<ul class="dropdown-menu" role="menu">
				<li><a href="/sizbran.php">Избранные счета</a></li>
				<li><a href="/toha.php?moy=1">Мои счета</a></li>
				<li><a href="/toha.php">Все счета</a></li> 
				</ul>