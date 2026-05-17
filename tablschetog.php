<?
include 'conf.php'; 
$manfss=date("m", strtotime($_GET['datas']));
$yearss=date("Y", strtotime($_GET['datas']));
$days=date("d", strtotime($_GET['datas']));
$manff=date("m", strtotime($_GET['dataf']));
$yearf=date("Y", strtotime($_GET['dataf']));
$dayf=date("d", strtotime($_GET['dataf']));
$ds= $yearss."-".$manfss."-".$days;
$df= $yearf."-".$manff."-".$dayf;
if($_GET['tip']=="1")
{
$tipschet="Новый";
} 
if($_GET['tip']=="2")
{
$tipschet="Продления";
}
$manf=date("m", strtotime($_GET['datas']));
$year=date("Y", strtotime($_GET['datas']));
$manfs=date("m", strtotime($_GET['dataf']));
$years=date("Y", strtotime($_GET['dataf']));
session_start();
$_SESSION['ogrn']=$_GET['ogrn'];
$_SESSION['ds']=$ds;
$_SESSION['df']=$df;
$_SESSION['tip']=$_GET['tip'];
session_write_close();
setcookie('ogrn',$_GET['ogrn'],time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
setcookie('tip',$_GET['tip'],time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
setcookie('datas',$_GET['datas'],time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
setcookie('dataf',$_GET['dataf'],time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
setcookie('manfs',$manfs,time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
if($_GET['ogrn']=="0")
{
	$uslugiogrn="";
}
if($_GET['ogrn']!="0")
{
	$uslugiogrn="uslugi.id='".$_GET['ogrn']."' and";
}
$dataproms=$ds;
$datapromf=$df;

if($_GET['tip']=="2")
{
	$r = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.del!='1'and  schet.tipprod!='' and schet.tipprod!='Нет'and schet.shetold='' and schet.prodlens='0' and schet.prodlenks='0'and schet.cher='0'and schet.otk='0' and (schet.dataprod BETWEEN'$ds' and '$df' or schet.datasert BETWEEN '$ds'and '$df') group by schet.rand");
    $res = mysql_num_rows($r) ;
	
}

if($_GET['tip']=="1")
{
	$r = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.del!='1'and schet.tipprod=''and schet.cher='0' and (schet.dataprod BETWEEN'$ds' and '$df' or schet.datasert BETWEEN '$ds'and '$df')group by schet.ns");
    $res = mysql_num_rows($r) ;
}
if($_GET['tip']=="1")
{
	$ri = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id  WHERE $uslugiogrn schet.del!='1'AND schet.oplachenks!= '1' and schet.oplachen != '1' and schet.shetold=''  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resi = mysql_num_rows($ri) ;
}
if($_GET['tip']=="2")
{
	$ri = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id  WHERE $uslugiogrn schet.del!='1'AND schet.oplachenks!= '1' and schet.oplachen != '1' and schet.shetold!='' AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resi = mysql_num_rows($ri) ;
}
if($_GET['tip']=="1")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '' or schet.status = '1'or schet.status = '2'or schet.status = '3'or schet.status = '4'or schet.status = '6'or schet.status = '7'or schet.status = '16'or schet.status = '19'or schet.status = '20')";
	}
	if($_GET['ogrn']=="22")
	{
		$status="(schet.status = '' or schet.status = '35'or schet.status = '37'or schet.status = '38'or schet.status = '39'or schet.status = '40'or schet.status = '41'or schet.status = '42'or schet.status = '43')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '' or schet.status = '44'or schet.status = '45'or schet.status = '47'or schet.status = '48'or schet.status = '49')";
	}
	$ro = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0' and $status AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $reso = mysql_num_rows($ro) ;
}
if($_GET['tip']=="2")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '' or schet.status = '1'or schet.status = '2'or schet.status = '3'or schet.status = '4'or schet.status = '6'or schet.status = '7'or schet.status = '16'or schet.status = '19'or schet.status = '20')";
	}
	if($_GET['ogrn']=="22")
	{
		$status="(schet.status = '' or schet.status = '35'or schet.status = '37'or schet.status = '38'or schet.status = '39'or schet.status = '40'or schet.status = '41'or schet.status = '42'or schet.status = '43')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '' or schet.status = '44'or schet.status = '45'or schet.status = '47'or schet.status = '48'or schet.status = '49')";
	}
	$ro = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' and $status AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $reso = mysql_num_rows($ro) ;
}
if($_GET['tip']=="1")
{
	$rpos = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0' and schet.status = '5' AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $respos = mysql_num_rows($rpos) ;
}
if($_GET['tip']=="2")
{
	$rpos = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' and schet.status = '5' AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $respos = mysql_num_rows($rpos) ;
}
if($_GET['tip']=="1")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '17' or schet.status='18' or schet.status='161')";
	}
	if($_GET['ogrn']=="22")
	{
		$status="(schet.status = '36')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '50' or schet.status = '51')";
	}
	$ryv = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0' and  $status AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resyv = mysql_num_rows($ryv) ;
}
if($_GET['tip']=="2")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '17' or schet.status='18' or schet.status='161')";
	}
	if($_GET['ogrn']=="22")
	{
		$status="(schet.status = '36')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '50' or schet.status = '51')";
	}
	$ryv = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' and $status AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resyv = mysql_num_rows($ryv) ;
}
if($_GET['tip']=="1")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '17' or schet.status='18' or schet.status='161')";
	}
	if($_GET['ogrn']=="22")
	{
		$status="(schet.status = '77')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '52' or schet.status = '53'or schet.status = '60')";
	}
	$rna = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0' and  $status AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resna = mysql_num_rows($rna) ;
}
if($_GET['tip']=="2")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '21' or schet.status='65')";
	}
	if($_GET['ogrn']=="22")
	{
		$status="(schet.status = '77')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '52' or schet.status = '53'or schet.status = '60')";
	}
	$rna = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' and $status AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resna = mysql_num_rows($rna) ;
}
if($_GET['tip']=="1")
{
	$ratk = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0'   AND schet.akt = '1'  AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resatk = mysql_num_rows($ratk) ;
}
if($_GET['tip']=="2")
{
	$ratk = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' AND schet.akt = '1'  AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resatk = mysql_num_rows($ratk) ;
}
if($_GET['tip']=="1")
{
	$rotk = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0'   AND schet.otk = '0'  AND schet.cher = '1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resotk = mysql_num_rows($rotk) ;
}
if($_GET['tip']=="2")
{
	$rotk = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' AND schet.otk = '0'  AND schet.cher = '1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resotk = mysql_num_rows($rotk) ;
}
if($_GET['tip']=="1")
{
	$rcher = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0'   AND schet.otk = '1'  AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $rescher = mysql_num_rows($rcher) ;
}
if($_GET['tip']=="2")
{
	$rcher = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' AND schet.otk = '1'  AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $rescher = mysql_num_rows($rcher) ;
}
if($_GET['tip']=="1")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '23')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '12356')";
	}
	$rvoz = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0' and $status AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resvoz = mysql_num_rows($rvoz) ;
}
if($_GET['tip']=="2")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '23')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '12356')";
	}
	$rvoz = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' and $status  AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resvoz = mysql_num_rows($rvoz) ;
}
if($_GET['tip']=="1")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '12354')";
	}
	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '12355')";
	}
	$rpere = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold='' and schet.del = '0' and $status   AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $respere = mysql_num_rows($rpere) ;
}
if($_GET['tip']=="2")
{
	if($_GET['ogrn']!="22"&&$_GET['ogrn']!="24")
	{
		$status="(schet.status = '12354')";
	}

	if($_GET['ogrn']=="24")
	{
		$status="(schet.status = '12355')";
	}
	$rpere = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.shetold!='' and schet.del = '0' and $status  AND schet.oplachenks = '1'AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $respere = mysql_num_rows($rpere) ;
}
if($_GET['tip']=="1")
{

    $rallschet= mysql_query("SELECT schet.ns, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.del='0' and schet.shetold='' and schet.akt!='1' and schet.cher!='1' and schet.otk!='1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resallschet = mysql_num_rows($rallschet) ;
}
if($_GET['tip']=="2")
{

    $rallschet= mysql_query("SELECT schet.ns, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.del='0' and schet.shetold!='' and schet.akt!='1' and schet.cher!='1' and schet.otk!='1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns");
    $resallschet = mysql_num_rows($rallschet) ;
}
?>

<div class="tipschet" id="tipp">
<p><?php echo $res;?></p>
<p>шт.</p>
<p><?echo $tipschet;?></p>
</div>
<div class="tipschet"id="tips">
<p><?php echo $resi;?></p>
<p>шт.</p>
<p>Счета</p>
</div>
<div class="tipschet"id="tipo">
<p><?php echo $reso;?></p>
<p>шт.</p>
<p>Оплата+работа</p>
</div>
<div class="tipschet"id="tippos">
<p><?php echo $respos;?></p>
<p>шт.</p>
<p>Поставки</p>
</div>
<div class="tipschet"id="tipyv">
<p><?php echo $resyv;?></p>
<p>шт.</p>
<p>Установки +выезды</p>
</div>
<div class="tipschet"id="tipna">
<p><?php echo $resna;?></p>
<p>шт.</p>
<p>На отгрузке</p>
</div>
<div class="tipschet"id="tipatk">
<p><?php echo $resatk;?></p>
<p>шт.</p>
<p>Отгруженые</p>
</div>
<div class="tipschet"id="tipot">
<p><?php echo $resotk;?></p>
<p>шт.</p>
<p>Отказ</p>
</div>
<div class="tipschet"id="tipcher">
<p><?php echo $rescher;?></p>
<p>шт.</p>
<p>Черновик</p>
</div>
<div class="tipschet"id="tipvoz">
<p><?php echo $resvoz;?></p>
<p>шт.</p>
<p>Возврат</p>
</div>
<div class="tipschet" id="tippere">
<p><?php echo $respere;?></p>
<p>шт.</p>
<p>Переплата</p>
</div>
<div class="tipschet" id="tipallschet" style="margin-left: 50px;border: 1px solid #d3d3d3;">
    <p><?php echo $resallschet+$res?></p>
    <p>шт.</p>
    <p>Все счета</p>
</div>
<script>
 $('#tipp').click(function(){  
 document.getElementById('modal-shadowkube').style.display="block";
 document.getElementById('kube').style.display="block";
                document.getElementById('tipp').style.backgroundColor="#C1BBBB";
				document.getElementById('tipatk').style.backgroundColor="white";
				document.getElementById('tipna').style.backgroundColor="white";
				document.getElementById('tipyv').style.backgroundColor="white";
										document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
				document.getElementById('tips').style.backgroundColor="white";
				document.getElementById('tipo').style.backgroundColor="white";
				document.getElementById('tippos').style.backgroundColor="white";
				document.getElementById('tipot').style.backgroundColor="white";
				document.getElementById('tipot').style.color="#666";
				document.getElementById('tipatk').style.color="#666";
				document.getElementById('tipna').style.color="#666";
				document.getElementById('tipp').style.color="white";
				document.getElementById('tips').style.color="#666";
				document.getElementById('tipyv').style.color="#666";
				document.getElementById('tipcher').style.backgroundColor="white";
			document.getElementById('tipcher').style.color="#666";
     document.getElementById('tipallschet').style.backgroundColor="white";
     document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=prod&na=<?echo $manf;?>&nay=<?echo $year;?>&naf=<?echo $manfs;?>&nayf=<?echo $years;?>&ds=<?echo $ds;?>&df=<?echo $df;?>&ms=<?echo $ds;?>&mf=<?echo $df;?>&ogr=<?echo $_GET['ogrn'];?>&tipi=<?echo $_GET['tip'];?>&id=<?echo $_GET['users'];?>",
                    success: function(html){  
                        $("#tablosn").html(html);
document.getElementById('modal-shadowkube').style.display="none";	
 document.getElementById('kube').style.display="none";					
                    }  
                });  
            }); 
$('#tips').click(function(){  
document.getElementById('modal-shadowkube').style.display="block";
 document.getElementById('kube').style.display="block";
document.getElementById('tips').style.backgroundColor="#C1E5FB";
document.getElementById('tipatk').style.backgroundColor="white";
document.getElementById('tipna').style.backgroundColor="white";
						document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
document.getElementById('tipyv').style.backgroundColor="white";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tippos').style.backgroundColor="white";
document.getElementById('tipot').style.backgroundColor="white";
document.getElementById('tipot').style.color="#666";
document.getElementById('tipatk').style.color="#666";
document.getElementById('tipna').style.color="#666";
document.getElementById('tips').style.color="#666";
document.getElementById('tipyv').style.color="#666";
document.getElementById('tipp').style.color="#666";
document.getElementById('tipcher').style.backgroundColor="white";
    document.getElementById('tipallschet').style.backgroundColor="white";
    document.getElementById('tipallschet').style.color="#666";
			document.getElementById('tipcher').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=schet&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html);  
						document.getElementById('modal-shadowkube').style.display="none";	
						document.getElementById('kube').style.display="none";	
                    }  
                });  
            });
			$('#tipo').click(function(){  
			document.getElementById('modal-shadowkube').style.display="block";
			 document.getElementById('kube').style.display="block";
document.getElementById('tipo').style.backgroundColor="#FFF850";
document.getElementById('tipatk').style.backgroundColor="white";
document.getElementById('tipna').style.backgroundColor="white";
document.getElementById('tipyv').style.backgroundColor="white";
						document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
document.getElementById('tippos').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tipot').style.backgroundColor="white";
document.getElementById('tipot').style.color="#666";
document.getElementById('tipatk').style.color="#666";
document.getElementById('tipna').style.color="#666";
document.getElementById('tipyv').style.color="#666";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
document.getElementById('tipcher').style.backgroundColor="white";
			document.getElementById('tipcher').style.color="#666";
                document.getElementById('tipallschet').style.backgroundColor="white";
                document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=oplachen&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html);  
						document.getElementById('modal-shadowkube').style.display="none";	
						document.getElementById('kube').style.display="none";	
                    }  
                });  
            });
			$('#tippos').click(function(){  
			document.getElementById('modal-shadowkube').style.display="block";
			 document.getElementById('kube').style.display="block";
			document.getElementById('tippos').style.backgroundColor="#E9C3FB";
			document.getElementById('tipna').style.backgroundColor="white";
			document.getElementById('tipatk').style.backgroundColor="white";
			document.getElementById('tipyv').style.backgroundColor="white";
			document.getElementById('tipot').style.backgroundColor="white";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
									document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tipot').style.color="#666";
document.getElementById('tipatk').style.color="#666";
document.getElementById('tipna').style.color="#666";
document.getElementById('tipyv').style.color="#666";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
document.getElementById('tipcher').style.backgroundColor="white";
			document.getElementById('tipcher').style.color="#666";
                document.getElementById('tipallschet').style.backgroundColor="white";
                document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=postavka&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html); 
document.getElementById('modal-shadowkube').style.display="none";	
document.getElementById('kube').style.display="none";							
                    }  
                });  
            });
						$('#tipyv').click(function(){  
						document.getElementById('modal-shadowkube').style.display="block";
						 document.getElementById('kube').style.display="block";
						document.getElementById('tipna').style.backgroundColor="white";
						document.getElementById('tipatk').style.backgroundColor="white";
			document.getElementById('tipyv').style.backgroundColor="#FFB366";
			document.getElementById('tippos').style.backgroundColor="white";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
									document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipot').style.backgroundColor="white";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tipatk').style.color="#666";
document.getElementById('tipna').style.color="#666";
document.getElementById('tipyv').style.color="white";
document.getElementById('tipot').style.color="#666";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
document.getElementById('tipcher').style.backgroundColor="white";
			document.getElementById('tipcher').style.color="#666";
                            document.getElementById('tipallschet').style.backgroundColor="white";
                            document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=ystanovka&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html);  
						document.getElementById('modal-shadowkube').style.display="none";	
						document.getElementById('kube').style.display="none";	
                    }  
                });  
            });
			$('#tipna').click(function(){  
			document.getElementById('modal-shadowkube').style.display="block";
			 document.getElementById('kube').style.display="block";
			document.getElementById('tipna').style.backgroundColor="#85D6D1";
			document.getElementById('tipatk').style.backgroundColor="white";
									document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
			document.getElementById('tipyv').style.backgroundColor="white";
			document.getElementById('tippos').style.backgroundColor="white";
			document.getElementById('tipot').style.backgroundColor="white";
			document.getElementById('tipyv').style.color="#666";
			document.getElementById('tipatk').style.color="#666";
			document.getElementById('tipna').style.color="white";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tipot').style.color="#666";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
document.getElementById('tipcher').style.backgroundColor="white";
			document.getElementById('tipcher').style.color="#666";
                document.getElementById('tipallschet').style.backgroundColor="white";
                document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=naatk&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html); 
document.getElementById('modal-shadowkube').style.display="none";	
document.getElementById('kube').style.display="none";							
                    }  
                });  
            });
			$('#tipatk').click(function(){  
			document.getElementById('modal-shadowkube').style.display="block";
			 document.getElementById('kube').style.display="block";
			document.getElementById('tipna').style.backgroundColor="white";
			document.getElementById('tipatk').style.backgroundColor="#85D6A7";
									document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
			document.getElementById('tipyv').style.backgroundColor="white";
			document.getElementById('tippos').style.backgroundColor="white";
			document.getElementById('tipot').style.backgroundColor="white";
			document.getElementById('tipna').style.color="#666";
			document.getElementById('tipyv').style.color="#666";
			document.getElementById('tipot').style.color="#666";
			document.getElementById('tipatk').style.color="white";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
document.getElementById('tipcher').style.backgroundColor="white";
                document.getElementById('tipallschet').style.backgroundColor="white";
                document.getElementById('tipallschet').style.color="#666";
			document.getElementById('tipcher').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=atk&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html);  
						document.getElementById('modal-shadowkube').style.display="none";	
						document.getElementById('kube').style.display="none";	
                    }  
                });  
            });
				$('#tipot').click(function(){ 
document.getElementById('modal-shadowkube').style.display="block";	
 document.getElementById('kube').style.display="block";			
				document.getElementById('tipot').style.backgroundColor="#FB9C9C";
			document.getElementById('tipna').style.backgroundColor="white";
									document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
			document.getElementById('tipatk').style.backgroundColor="white";
			document.getElementById('tipyv').style.backgroundColor="white";
			document.getElementById('tippos').style.backgroundColor="white";
			document.getElementById('tipna').style.color="#666";
			document.getElementById('tipyv').style.color="#666";
			document.getElementById('tipot').style.color="white";
			document.getElementById('tipatk').style.color="#666";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
document.getElementById('tipcher').style.backgroundColor="white";
			document.getElementById('tipcher').style.color="#666";
                    document.getElementById('tipallschet').style.backgroundColor="white";
                    document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=otk&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html); 
document.getElementById('modal-shadowkube').style.display="none";	
document.getElementById('kube').style.display="none";							
                    }  
                });  
            });
			$('#tipcher').click(function(){ 
document.getElementById('modal-shadowkube').style.display="block";
 document.getElementById('kube').style.display="block";			
			document.getElementById('tipcher').style.backgroundColor="#BC9B79";
			document.getElementById('tipcher').style.color="white";
									document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
				document.getElementById('tipot').style.backgroundColor="white";
				document.getElementById('tipot').style.color="#666";
			document.getElementById('tipna').style.backgroundColor="white";
			document.getElementById('tipatk').style.backgroundColor="white";
			document.getElementById('tipyv').style.backgroundColor="white";
			document.getElementById('tippos').style.backgroundColor="white";
			document.getElementById('tipna').style.color="#666";
			document.getElementById('tipyv').style.color="#666";
			document.getElementById('tipatk').style.color="#666";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
                document.getElementById('tipallschet').style.backgroundColor="white";
                document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=cher&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html);  
						document.getElementById('modal-shadowkube').style.display="none";	
						document.getElementById('kube').style.display="none";	
                    }  
                });  
            });
			$('#tipvoz').click(function(){  
			document.getElementById('modal-shadowkube').style.display="block";
			 document.getElementById('kube').style.display="block";
			document.getElementById('tipvoz').style.backgroundColor="#E45A51";
			document.getElementById('tipvoz').style.color="white";
			document.getElementById('tipcher').style.backgroundColor="white";
			document.getElementById('tipcher').style.color="#666";
				document.getElementById('tipot').style.backgroundColor="white";
				document.getElementById('tipot').style.color="#666";
			document.getElementById('tippere').style.backgroundColor="white";
			document.getElementById('tippere').style.color="#666";
			document.getElementById('tipna').style.backgroundColor="white";
			document.getElementById('tipatk').style.backgroundColor="white";
			document.getElementById('tipyv').style.backgroundColor="white";
			document.getElementById('tippos').style.backgroundColor="white";
			document.getElementById('tipna').style.color="#666";
			document.getElementById('tipyv').style.color="#666";
			document.getElementById('tipatk').style.color="#666";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
                document.getElementById('tipallschet').style.backgroundColor="white";
                document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=vozvrat&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html); 
document.getElementById('modal-shadowkube').style.display="none";		
document.getElementById('kube').style.display="none";						
                    }  
                });  
            });
			$('#tippere').click(function(){  
			document.getElementById('modal-shadowkube').style.display="block";
			 document.getElementById('kube').style.display="block";
			document.getElementById('tippere').style.backgroundColor="#DCF541";
			document.getElementById('tippere').style.color="white";
			document.getElementById('tipvoz').style.backgroundColor="white";
			document.getElementById('tipvoz').style.color="#666";
			document.getElementById('tipcher').style.backgroundColor="white";
			document.getElementById('tipcher').style.color="#666";
				document.getElementById('tipot').style.backgroundColor="white";
				document.getElementById('tipot').style.color="#666";
			document.getElementById('tipna').style.backgroundColor="white";
			document.getElementById('tipatk').style.backgroundColor="white";
			document.getElementById('tipyv').style.backgroundColor="white";
			document.getElementById('tippos').style.backgroundColor="white";
			document.getElementById('tipna').style.color="#666";
			document.getElementById('tipyv').style.color="#666";
			document.getElementById('tipatk').style.color="#666";
document.getElementById('tipo').style.backgroundColor="white";
document.getElementById('tips').style.backgroundColor="white";
document.getElementById('tips').style.color="#666";
document.getElementById('tipp').style.backgroundColor="white";
document.getElementById('tipp').style.color="#666";
                document.getElementById('tipallschet').style.backgroundColor="white";
                document.getElementById('tipallschet').style.color="#666";
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=pereplata&ms=<?echo $manfss;?>&na=<?echo $manf;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
                    success: function(html){  
                        $("#tablosn").html(html);  
						document.getElementById('modal-shadowkube').style.display="none";	
						document.getElementById('kube').style.display="none";	
                    }  
                });  
            });
 $('#tipallschet').click(function(){
     document.getElementById('modal-shadowkube').style.display="block";
     document.getElementById('kube').style.display="block";
     document.getElementById('tipallschet').style.backgroundColor="#DCF541";
     document.getElementById('tipallschet').style.color="white";
     document.getElementById('tippere').style.backgroundColor="white";
     document.getElementById('tippere').style.color="#666";
     document.getElementById('tipvoz').style.backgroundColor="white";
     document.getElementById('tipvoz').style.color="#666";
     document.getElementById('tipcher').style.backgroundColor="white";
     document.getElementById('tipcher').style.color="#666";
     document.getElementById('tipot').style.backgroundColor="white";
     document.getElementById('tipot').style.color="#666";
     document.getElementById('tipna').style.backgroundColor="white";
     document.getElementById('tipatk').style.backgroundColor="white";
     document.getElementById('tipyv').style.backgroundColor="white";
     document.getElementById('tippos').style.backgroundColor="white";
     document.getElementById('tipna').style.color="#666";
     document.getElementById('tipyv').style.color="#666";
     document.getElementById('tipatk').style.color="#666";
     document.getElementById('tipo').style.backgroundColor="white";
     document.getElementById('tips').style.backgroundColor="white";
     document.getElementById('tips').style.color="#666";
     document.getElementById('tipp').style.backgroundColor="white";
     document.getElementById('tipp').style.color="#666";
     $.ajax({
         url: "tablschetosn.php",
         cache: false,
         data: "tip=schetall&ms=<?echo $manfss;?>&na=<?echo $manf;?>&nay=<?echo $year;?>&naf=<?echo $manfs;?>&nayf=<?echo $years;?>&mf=<?echo $manff;?>&ys=<?echo $yearss;?>&yf=<?echo $yearf;?>&ogr=<?echo $_GET['ogrn'];?>&id=<?echo $_GET['users'];?>&tipi=<?echo $_GET['tip'];?>&ds=<?echo $ds;?>&df=<?echo $df;?>&dayys=<?echo $days;?>&dayyf=<?echo $dayf;?>",
         success: function(html){
             $("#tablosn").html(html);
             document.getElementById('modal-shadowkube').style.display="none";
             document.getElementById('kube').style.display="none";
         }
     });
 });
</script>
