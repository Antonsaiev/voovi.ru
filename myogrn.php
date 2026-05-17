<?php
# подключаем конфиг
include 'conf.php';  
require_once 'schet_identity.php';
require_once 'tarif_identity.php';

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
$schetLoadingVisible = isset($_POST['submitsch']) || isset($_POST['submitsave']);
$schetLoadingTitle = isset($_POST['submitsave']) ? 'Сохраняем счет' : 'Создаем счет';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="/js/cdnjs.cloudflare.com_ajax_libs_decimal.js_9.0.0_decimal.min.js"></script>
<style type="text/css">
.schet-loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 99999;
    background: rgba(15, 23, 42, 0.55);
    text-align: center;
}
.schet-loading-overlay.is-visible {
    display: block;
}
.schet-loading-overlay:before {
    content: "";
    display: inline-block;
    height: 100%;
    vertical-align: middle;
}
.schet-loading-card {
    display: inline-block;
    width: 340px;
    max-width: 86%;
    padding: 28px 24px;
    vertical-align: middle;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 18px 54px rgba(0, 0, 0, 0.26);
    font-family: Arial, Helvetica, sans-serif;
}
.schet-loading-spinner {
    width: 56px;
    height: 56px;
    margin: 0 auto 18px;
    border: 4px solid #e7edf3;
    border-top-color: #26BB84;
    border-right-color: #3f77ae;
    border-radius: 50%;
    animation: schet-loading-spin 0.8s linear infinite;
}
.schet-loading-title {
    margin-bottom: 8px;
    color: #1f2937;
    font-size: 20px;
    font-weight: bold;
}
.schet-loading-text {
    color: #64748b;
    font-size: 14px;
    line-height: 1.45;
}
@keyframes schet-loading-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
</head>
<body>
<div id="schetLoadingOverlay" class="schet-loading-overlay<?php if ($schetLoadingVisible) { echo ' is-visible'; } ?>">
    <div class="schet-loading-card">
        <div class="schet-loading-spinner"></div>
        <div id="schetLoadingTitle" class="schet-loading-title"><?php echo $schetLoadingTitle; ?></div>
        <div class="schet-loading-text">Пожалуйста, подождите. Анкета откроется автоматически.</div>
    </div>
</div>
<?php
if ($schetLoadingVisible) {
    echo str_repeat(' ', 2048);
    @ob_flush();
    @flush();
}
?>
<?php
if(isset($_GET['rand']) || $_GET['head'] == 1){
echo '

<script src="/js/jquery-1.11.0.min.js"></script><script src="/js/jquery-live-compat.js?v=1"></script><div style="
margin-bottom: 25px;
  font-size: 21px;
  background: #26BB84;
  text-align: center;
  padding: 8px;
  color: #fff;
">Новый продукт для ';

		$q1 = "SELECT * FROM `ogrn` WHERE id =$_GET[id]";
		$result1 = mysql_query($q1);
		$person1 = mysql_fetch_array($result1);
		
		echo $person1['naim'].'</div>';
}else{
include 'header.php';  }

if(isset($_GET['rand']) || $_GET['head'] == 1){
echo '<div class="container" style="margin-top: 20px;">';
}else{
echo '<div class="container" style="margin-top: 60px;">';  }
?>
<div class="row">

<?php 

if(isset($_GET['rand'])){
echo '<div class="col-md-12">';
}else{
echo '<div class="col-md-8">';
}

?>

<div class="bs-example">
<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;"><a href="/newusluga.php?id=<?php echo $_GET['id']; ?>&ogrn=<?php echo $_GET['ogrn']; ?>&parent=<?php echo $_GET['tip'];

if(isset($_GET['rand'])){
echo '&rand='.$_GET['rand'];
}

 ?>"><< Назад</a> : Счет на "<?php
$id = (int)$_GET['parent']; // приводим к int
$person = mysql_fetch_assoc(mysql_query("SELECT * FROM `produkti` WHERE id = $id"));

// tip → uslugi
$tip    = isset($_GET['tip']) ? (int)$_GET['tip'] : 0;
$usluga = mysql_fetch_assoc(mysql_query("SELECT * FROM `uslugi` WHERE `id` = $tip"));

if ($usluga && $usluga['nds'] !== '' && $usluga['nds'] !== 'none' && $usluga['nds'] !== null) {
    $nds = "'" . mysql_real_escape_string($usluga['nds']) . "'";
} else {
    // в базе будет именно NULL
    $nds = "NULL";
}

echo $person['name'];
?>"</h4></strong>

<?php 
if(isset($_GET['rand'])){
$shetrand = $_GET['rand'];
}else{
$shetrand = '';
}
$sujmdfhsd = '';
$schetNs = '';

$qs = mysql_query("SELECT count(*) FROM schet WHERE idkli = '".$_GET['id']."' AND produkt = '".$_GET['parent']."'");
echo mysql_result($qs, 0); 
$qs = mysql_query("SELECT * FROM schet WHERE idkli = '".$_GET['id']."' ORDER BY id DESC LIMIT 1");
$ps = mysql_fetch_array($qs);
$zs = $ps['nomerschet'] + 1;

$qre = "SELECT * FROM `produkti` WHERE id =$_GET[parent]";
$resultre = mysql_query($qre);
$personre = mysql_fetch_array($resultre);
if(isset($_POST['submitsch'])){
	//if($_GET['parent']==319&&$_POST['kol_opis']==""||$_GET['parent']==400&&$_POST['kol_opis']=="" ||$_GET['parent']==391&&$_POST['kol_opis']==""&&$_GET['parent']==319)
    if (in_array($_GET['parent'], [319, 400, 391, 426, 428, 427]) && $_POST['kol_opis'] == "")
	{
		echo'<script>alert("Не введено количесвто описей")</script>';
	}
		else
		{
        $schetIdentity = voovi_allocate_schet_identity(date("y"), $userdata['users_id'], $userdata['otdel']);
        $shetrand = $schetIdentity['rand'];
        $sujmdfhsd = $schetIdentity['kolichschet'];
        $schetNs = $schetIdentity['ns'];
        $_POST['shetrand'] = $shetrand;
	    if($_GET['tip']==12 && $_POST['nomerschetks']!="")
	{
		/*echo'<script>alert('.$_POST['dobprod'].')</script>';*/
		if($_POST['av']==0)
		{
			$pagent=$_POST['nomerschetks']."L";
			$sagent=$_POST['nomerschetks'];
			$chisrol=2;
			if($_POST['sht']!="0"&&$_POST['sht']!="")
			{
				$dagent=$_POST['nomerschetks'];
				$shtsert=$_POST['sht'];
				$chisrol=3;
			}
			 $dop_yslygi = $_POST['dobusl'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
		for ($i=0; $i < $count; $i++)
        {
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		}
		}
		if($_POST['av']==1)
		{
			$sagent=$_POST['nomerschetks'];
			$chisrol=1;
			if($_POST['sht']!="0"&&$_POST['sht']!="")
			{
				$dagent=$_POST['nomerschetks'];
				$shtsert=$_POST['sht'];
				$chisrol=2;
			}
				 $dop_yslygi = $_POST['dobusl'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
		for ($i=0; $i < $count; $i++)
        {
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		}
		}
		if($_POST['av']==2)
		{
			if($_POST['sht']!="0"&&$_POST['sht']!="")
			{
				$dagent=$_POST['nomerschetks'];
				$shtsert=$_POST['sht'];
				$chisrol=1;
			}
			 $dop_yslygi = $_POST['dobusl'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
		for ($i=0; $i < $count; $i++)
        {
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		}
		}
		if($_POST['av']=="3")
		{
			$pagent="Без АВ";
			$sagent="Без АВ";
			$chisrol=2;
			if($_POST['sht']!="0"&&$_POST['sht']!="")
			{
				$dagent="Без АВ";
				$shtsert=$_POST['sht'];
				$chisrol=3;
			}
			 $dop_yslygi = $_POST['dobusl'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
		for ($i=0; $i < $count; $i++)
        {
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		}
		}
        if($_POST['av']=="4")
        {
            $pagent="Без АВ";
            $sagent="Без АВ";
            $chisrol=2;
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent="Без АВ";
                $shtsert=$_POST['sht'];
                $chisrol=3;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
		$dop_prod = $_POST['dobprod'];
        $count = count($dop_prod);
		$avdob = $_POST['avdob'];
		$shtsertdd=$_POST['shtpr'];
		if($count!=0)
        {
			for ($i=0; $i < $count; $i++)
        {
		if($avdob[$i]==0)
		{
			$dobprodschet=$_POST['nomerschetks']."dob";
			$pagentd=$_POST['nomerschetks']."L";
			$sagentd=$_POST['nomerschetks'];
			$chisroldob=2;
			if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
			{
				$dagentd=$_POST['nomerschetks'];
				$shtsertd=$shtsertdd[$i];
				$chisroldob=3;
			}
			 $dop_yslygi = $_POST['dobuslpr'];
			  //это количество элементов массива, для цикла.
		
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		    $pagentd="";
			$sagentd="";
			$chisroldob="";
		    $dagentd="";
		}
		
		if($avdob[$i]==1)
		{
			$dobprodschet=$_POST['nomerschetks']."dob";
			$sagentd=$_POST['nomerschetks'];
			$chisroldob=1;
			if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
			{
				$dagentd=$_POST['nomerschetks'];
				$shtsertd=$shtsertdd[$i];
				$chisroldob=2;
			}
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		    $pagentd="";
			$sagentd="";
			$chisroldob="";
		    $dagentd="";
		}

		if($avdob[$i]==2)
		{
			$chisroldob=0;
			if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
			{
				$dagentd=$_POST['nomerschetks'];
				$shtsertd=$shtsertdd[$i];
				$chisroldob=1;
			}
			$dobprodschet=$_POST['nomerschetks']."dob";
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		    $pagentd="";
			$sagentd="";
			$chisroldob="";
		    $dagentd="";
		}
		if($avdob[$i]=="3")
		{
			$dobprodschet=$_POST['nomerschetks']."dob";
			$pagentd="Без АВ";
			$sagentd="Без АВ";
			$chisroldob=2;
			if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
			{
				$dagentd="Без АВ";
				$shtsert=$shtsertdd[$i];
				$chisroldob=3;
			}
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		    $pagentd="";
			$sagentd="";
			$chisroldob="";
		    $dagentd="";
		}
        if($avdob[$i]=="4")
            {
                $dobprodschet=$_POST['nomerschetks']."dob";
                $pagentd="Без АВ";
                $sagentd="Без АВ";
                $chisroldob=2;
                if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                {
                    $dagentd="Без АВ";
                    $shtsert=$shtsertdd[$i];
                    $chisroldob=3;
                }
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
                $pagentd="";
                $sagentd="";
                $chisroldob="";
                $dagentd="";
            }
		}	
			
			
	    
       /* $dop_yslygi = $_POST['dobuslpr'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
				for ($i=0; $i < $count; $i++)
        {
					$azaza = "INSERT INTO `av` (
		
		
		`dob_usl`,
		`kto`
		) VALUES (
		
		'$dop_yslygi[$i]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
        }*/
		}
    }
	if($_POST['dog'] == 2){
		if(!empty($_POST['checkl']))
		{
			$checkl=1;
		}
		else
		{
			$checkl=0;
		}
		if($_GET['parent']==319||$_GET['parent']==400||$_GET['parent']==391)
		{
			$sos_opis=$_POST['sos_opis'];
			$kol_opis=$_POST['kol_opis'];
		}
		else
		{
			$sos_opis=0;
			$kol_opis=0;
		}
		if($_POST['nsold']!=""&&$_POST['nsold']!="0")
		{
			if($_POST['priceks']!=""&&$_POST['priceks']!="0")
			{
					$komenti = "UPDATE schet SET `prodlenks`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($linki));
			}
			if($_POST['aallsumm']!=""&&$_POST['aallsumm']!="0")
			{
			    if($_POST['skidka']=="100")
                {
                    $komentis = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
                    mysql_query($komentis) or die(mysql_error($link));
                }
			        $komenti = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($link));
			}
		}
		$agent=htmlspecialchars($_POST["agent"]);
		$ogrn = "SELECT * FROM ogrn WHERE inn ='".$_GET['inn']."' AND kpp ='".$_GET['kpp']."' ";
		$rogrn = mysql_query($ogrn);
		$pogrn = mysql_fetch_array($rogrn);
		$inn = $pogrn['inn'];
		$kpp = $pogrn['kpp'];
		$naim = $pogrn['naim'];
			$qrand = "SELECT sortir FROM `schet` ORDER BY sortir DESC LIMIT 1";
		$resultrand = mysql_query($qrand);
		$personrand = mysql_fetch_array($resultrand);		
		echo $personrand['sortir'];
		$var = $personrand['sortir'] + 1;
		$qs = "SELECT count(*) FROM schet WHERE idkli = '".$_GET['id']."' AND produkt = '".$_GET['parent']."'";
		$rs = mysql_query($qs);
		$ps = mysql_result($rs, 0);
		$zs = $ps['nomerchet']+ 1;
			$azaza = "INSERT INTO `schet` (
            `ns`,
			`shetold`,
				`idkli`,
			`sortir`,
			`otdel`,
			`filial`,
			`god`,
			`nomerdog`,
			`nomerschet`,
			`kolichschet`,
			`nomerschetks`,
			`kvo`,
			`prod`,
			`goroddd`,
			`prodlen`,
			`skidka`,
			`priceks`,
			`ogrn`,
			`name`,
			`inn`,
			`kpp`,
			`produkt`,
			`kto`,
			`data`,
			`d`,
			`m`,
			`y`,
			`time`,
			`rand`,
			`oferta`,
			`tell`,
			`url`,
			`agent`,
			`Lagent`,
			`sos_opis`,
			`kol_opis`,
		    `nds`
			) VALUES (
            '".$schetNs."',
			'$_POST[nsold]',
				'".$_GET['id']."',
			'".$var."',
			'".$userdata['otdel']."',
			'".$userdata['filial']."',
			'".date("y")."',
			'В КС',
			'В КС',
			'".$sujmdfhsd."',
			'$_POST[nomerschetks]',
			'".$_POST["input".$rowazaza['id']]."',
			'".$rowazaza['id']."',
			'$_POST[goroddd]',
			'0',
			'$_POST[skidka]',
			'$_POST[priceks]',
			'".$_GET['ogrn']."',
			'".$naim."',
			'".$_GET['inn']."',
			'".$_GET['kpp']."',
			'$_GET[parent]',
			'".$userdata['users_id']."',
			'".date("d.m.y в H:i")."',
			'".date("d")."',
			'".date("m")."',
			'".date("Y")."',
			'".date("H:i")."',
			'$_POST[shetrand]',
			'$_POST[dog]',
			'$_POST[tel]',
			'$_POST[url]',
			'$agent',
			'$checkl',
			'$_POST[sos_opis]',
			'$_POST[kol_opis]',
            $nds
		)";
		mysql_query($azaza) or die(mysql_error($link));
        $data_documents_str = "INSERT INTO `document_data` (
		`rand`,
		`d_bill`,
		`d_contract`,
		`d_specification`,
		`d_act`,
		`d_tn`
		) VALUES (
		'$_POST[shetrand]',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."'
		)";
        mysql_query($data_documents_str) or die(mysql_error($link));
        $messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
            . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
            . $_POST['dog'];
        error_log(date('Y-m-d H:i:s') . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
		echo '<script type="text/javascript">'; 
		echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";'; 
		echo '</script>';
	}
    else{
		$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`,`gmd`,`tip`) VALUES ('". date("d.m.Y; H:i:s") ."','Новый счет','".$userdata['users_id']."','".date("Ymd")."','')";
		mysql_query($aktivn) or die(mysql_error($link));

		$qe = "SELECT * FROM dogovor ORDER BY nomer DESC LIMIT 1";
		$re = mysql_query($qe);
		$pe = mysql_fetch_array($re);
		$ze = $pe['nomer']+ 1;

		$qrand = "SELECT sortir FROM `schet` ORDER BY sortir DESC LIMIT 1";
		$resultrand = mysql_query($qrand);
		$personrand = mysql_fetch_array($resultrand);
							
		echo $personrand['sortir'];
		$var = $personrand['sortir'] + 1;

		$ogrn = "SELECT * FROM ogrn WHERE id =".$_GET['id'];
		$rogrn = mysql_query($ogrn);
		$pogrn = mysql_fetch_array($rogrn);
		$inn = $pogrn['inn'];
		$kpp = $pogrn['kpp'];
		$naim = $pogrn['naim'];

		if($_POST['dog'] == 0){
		$u = "INSERT INTO `dogovor` (
		`god`,
		`filial`,
		`otdel`,
		`nomer`,
		`ogrn`
		) VALUES (
		'".date("Y")."',
		'".$userdata['filial']."',
		'".$userdata['otdel']."',
		'".$ze."',
		'".$_GET['id']."'
		)";
		mysql_query($u) or die(mysql_error($link));
		}
		$qazaza = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : ''));
		while($rowazaza = mysql_fetch_array($qazaza)) {
		if($_POST[$rowazaza['id']] == 1){
			
		if(!empty($_POST['checkl']))
		{
			$checkl=1;
		}
		else
		{
			$checkl=0;
		}
		if($_GET['parent']==319||$_GET['parent']==400||$_GET['parent']==391)
		{
			$sos_opis=$_POST['sos_opis'];
			$kol_opis=$_POST['kol_opis'];
		}
		else
		{
			$sos_opis=0;
			$kol_opis=0;
		}
		if($_POST['nsold']!=""&&$_POST['nsold']!="0")
		{
		if($_POST['priceks']!=""&&$_POST['priceks']!="0")
			{
					$koment = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($koment) or die(mysql_error($link));
			}
        if($_POST['aallsumm']!=""&&$_POST['aallsumm']!="0")
			{
                if($_POST['skidka']=="100")
                {
                    $komentis = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
                    mysql_query($komentis) or die(mysql_error($link));
                }
			$komenti = "UPDATE schet SET `prodlenks`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($link));
			}
		}
		$pkvo = $rowazaza;
        $agent=htmlspecialchars($_POST["agent"]);
			$azaza = "INSERT INTO `schet` (
            `ns`,
			`shetold`,
			`gen`,
		`idkli`,
		`sortir`,
		`otdel`,
		`filial`,
		`god`,
		`nomerdog`,
		`nomerschet`,
		`kolichschet`,
		`nomerschetks`,
		`kvo`,
		`prod`,
		`kvogorod`,
		`goroddd`,
		`prodlen`,
		`skidka`,
		`price`,
		`priceks`,
		`ogrn`,
		`name`,
		`inn`,
		`kpp`,
		`produkt`,
		`kto`,
		`data`,
		`d`,
		`m`,
		`y`,
		`time`,
		`rand`,
		`oferta`,
		`tell`,
		`url`,
		`agent`,
		`Lagent`,
		`sos_opis`,
		`kol_opis`,
		`nds`
			) VALUES (
            '".$schetNs."',
			'$_POST[nsold]',
			'".$pkvo['gen']."',
		'$_GET[id]',
		'".$var."',
		'".$userdata['otdel']."',
		'".$userdata['filial']."',
		'".date("y")."',
		'".$ze."',
		'".$zs."',
		'".$sujmdfhsd."',
		'$_POST[nomerschetks]',
		'".$_POST["input".$rowazaza['id']]."',
		'".$rowazaza['id']."',
		'$_POST[kvogorod]',
		'$_POST[goroddd]',
		'0',
		'$_POST[skidka]',
		'$_POST[aallsumm]',
		'$_POST[priceks]',
		'".$_GET['ogrn']."',
		'".$naim."',
		'".$inn."',
		'".$kpp."',
		'$_GET[parent]',
		'".$userdata['users_id']."',
		'".date("d.m.y в H:i")."',
		'".date("d")."',
		'".date("m")."',
		'".date("Y")."',
		'".date("H:i")."',
		'$_POST[shetrand]',
		'$_POST[dog]',
		'$_POST[tel]',
		'$_POST[url]',
		'$agent',
		'$checkl',
		'$_POST[sos_opis]',
        '$_POST[kol_opis]',
        $nds
		)";
		mysql_query($azaza) or die(mysql_error($link));


		}
		}

        $data_documents_str = "INSERT INTO `document_data` (
		`rand`,
		`d_bill`,
		`d_contract`,
		`d_specification`,
		`d_act`,
		`d_tn`
		) VALUES (
		'$_POST[shetrand]',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."'
		)";
        mysql_query($data_documents_str) or die(mysql_error($link));
        $messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
            . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
            . $_POST['dog'];
        error_log(date('Y-m-d H:i:s') . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
        echo '<script type="text/javascript">';
        echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";';
        echo '</script>';

		$mamba = voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : '');
		$rmamba = mysql_query($mamba);
		$pmamba = mysql_fetch_array($rmamba);
		$pkvo = $pmamba;
		$agent=htmlspecialchars($_POST["agent"]);
		if($_POST[$pmamba['id']] != 1){
		if(!empty($_POST['goroddd'])){
			if(!empty($_POST['checkl']))
		{
			$checkl=1;
		}
		    else
		{
			$checkl=0;
		}
			if($_GET['parent']==319||$_GET['parent']==400||$_GET['parent']==391)
		{
			$sos_opis=$_POST['sos_opis'];
			$kol_opis=$_POST['kol_opis'];
		}
		    else
		{
			$sos_opis=0;
			$kol_opis=0;
		}	
		if($_POST['nsold']!=""&&$_POST['nsold']!="0")
		{
			if($_POST['priceks']!=""&&$_POST['priceks']!="0")
			{
					$koment = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($koment) or die(mysql_error($link));
			}
			if($_POST['aallsumm']!=""&&$_POST['aallsumm']!="0")
			{
                if($_POST['skidka']=="100")
                {
                    $komentis = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
                    mysql_query($komentis) or die(mysql_error($link));
                }
			$komenti = "UPDATE schet SET `prodlenks`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($link));
			}
		}
		//------------------------------------------------------------------------
			$azazai = "INSERT INTO `schet` (
            `ns`,
			`shetold`,
			`gen`,
		`idkli`,
		`sortir`,
		`otdel`,
		`filial`,
		`god`,
		`nomerdog`,
		`nomerschet`,
		`kolichschet`,
		`nomerschetks`,
		`kvo`,
		`prod`,
		`kvogorod`,
		`goroddd`,
		`prodlen`,
		`skidka`,
		`price`,
		`priceks`,
		`ogrn`,
		`name`,
		`inn`,
		`kpp`,
		`produkt`,
		`kto`,
		`data`,
		`d`,
		`m`,
		`y`,
		`time`,
		`rand`,
		`oferta`,
		`tell`,
		`url`,
		`agent`,
		`Lagent`,
		`sos_opis`,
		`kol_opis`,
		`nds`
			) VALUES (
            '".$schetNs."',
			'$_POST[nsold]',
			'".$pkvo['gen']."',
		'$_GET[id]',
		'".$var."',
		'".$userdata['otdel']."',
		'".$userdata['filial']."',
		'".date("y")."',
		'".$ze."',
		'".$zs."',
		'".$sujmdfhsd."',
		'$_POST[nomerschetks]',
		'".$_POST["input".$rowazaza['id']]."',
		'".$rowazaza['id']."',
		'$_POST[kvogorod]',
		'$_POST[goroddd]',
		'0',
		'$_POST[skidka]',
		'$_POST[aallsumm]',
		'$_POST[priceks]',
		'".$_GET['ogrn']."',
		'".$naim."',
		'".$inn."',
		'".$kpp."',
		'$_GET[parent]',
		'".$userdata['users_id']."',
		'".date("d.m.y в H:i")."',
		'".date("d")."',
		'".date("m")."',
		'".date("Y")."',
		'".date("H:i")."',
		'$_POST[shetrand]',
		'$_POST[dog]',
		'$_POST[tel]',
		'$_POST[url]',
		'$agent',
		'$chekl',
		'$_POST[sos_opis]',
	    '$_POST[kol_opis]',
        $nds
		)";
		mysql_query($azazai) or die(mysql_error($link));

        $data_documents_str = "INSERT INTO `document_data` (
		`rand`,
		`d_bill`,
		`d_contract`,
		`d_specification`,
		`d_act`,
		`d_tn`
		) VALUES (
		'$_POST[shetrand]',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."',
		'".date("y-m-d")."'
		)";
        mysql_query($data_documents_str) or die(mysql_error($link));
            $messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
                . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
                . $_POST['dog'];
            error_log(date('Y-m-d H:i:s') . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
            echo '<script type="text/javascript">';
            echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";';
            echo '</script>';
		//---------------------------------------------------------------------------
		}
		}



	}
        if(isset($_POST['dobprod']))
        {
        $dop_yslygi = $_POST['dobprod'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
        }
		if($count != 0)
        {
				for ($i=0; $i < $count; $i++)
        {
					$azazal = "INSERT INTO `schet` (
		
		`dobprod`,
		`kto`
		) VALUES (
		'$dop_yslygi[$i]',
		'".$userdata['users_id']."',
		)";
		mysql_query($azazal) or die(mysql_error($link));
        }
        }
	}
}


if(isset($_POST['submitsave'])){

    $shetrand = $pestar['rand'];
    $sujmdfhsd = $pestar['kolichschet'];
    $schetNs = $pestar2['ns'];
    if ($schetNs == '' || $schetNs == '0') {
        $schetNs = $pestar['god'].$pestar['kto'].$pestar['otdel'].$pestar['kolichschet'];
    }
    $_POST['shetrand'] = $shetrand;

	$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`,`gmd`,`tip`) VALUES ('". date("d.m.Y; H:i:s") ."','Счет изменен','".$userdata['users_id']."','".date("Ymd")."','')";
mysql_query($aktivn) or die(mysql_error($link));

if($pestar2['del']=='1'){
	mysql_query("DELETE FROM `schet` WHERE  `del` =  '0' AND rand ='".$_GET['rand']."'");

}
if($pestar2['del']=='0'){
	mysql_query("UPDATE `schet` SET  `del` =  '1' WHERE rand ='".$_GET['rand']."'");
        mysql_query("DELETE FROM `av` WHERE   rand ='".$_GET['rand']."'");
}
if($_GET['tip']==12 && $_POST['nomerschetks']!="")
    {
        /*echo'<script>alert('.$_POST['dobprod'].')</script>';*/
        if($_POST['av']==0)
        {
            $pagent=$_POST['nomerschetks']."L";
            $sagent=$_POST['nomerschetks'];
            $chisrol=2;
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent=$_POST['nomerschetks'];
                $shtsert=$_POST['sht'];
                $chisrol=3;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
        if($_POST['av']==1)
        {
            $sagent=$_POST['nomerschetks'];
            $chisrol=1;
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent=$_POST['nomerschetks'];
                $shtsert=$_POST['sht'];
                $chisrol=2;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
        if($_POST['av']==2)
        {
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent=$_POST['nomerschetks'];
                $shtsert=$_POST['sht'];
                $chisrol=1;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
        if($_POST['av']=="3")
        {
            $pagent="Без АВ";
            $sagent="Без АВ";
            $chisrol=2;
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent="Без АВ";
                $shtsert=$_POST['sht'];
                $chisrol=3;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
        $dop_prod = $_POST['dobprod'];
        $count = count($dop_prod);
        $avdob = $_POST['avdob'];
        $shtsertdd=$_POST['shtpr'];
        if($count!=0)
        {
            for ($i=0; $i < $count; $i++)
            {
                if($avdob[$i]==0)
                {
                    $dobprodschet=$_POST['nomerschetks']."dob";
                    $pagentd=$_POST['nomerschetks']."L";
                    $sagentd=$_POST['nomerschetks'];
                    $chisroldob=2;
                    if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                    {
                        $dagentd=$_POST['nomerschetks'];
                        $shtsertd=$shtsertdd[$i];
                        $chisroldob=3;
                    }
                    $dop_yslygi = $_POST['dobuslpr'];
                    //это количество элементов массива, для цикла.

                    $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                    mysql_query($azaza) or die(mysql_error($link));
                    $pagentd="";
                    $sagentd="";
                    $chisroldob="";
                    $dagentd="";
                }
                if($avdob[$i]==1)
                {
                    $dobprodschet=$_POST['nomerschetks']."dob";
                    $sagentd=$_POST['nomerschetks'];
                    $chisroldob=1;
                    if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                    {
                        $dagentd=$_POST['nomerschetks'];
                        $shtsertd=$shtsertdd[$i];
                        $chisroldob=2;
                    }
                    $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                    mysql_query($azaza) or die(mysql_error($link));
                    $pagentd="";
                    $sagentd="";
                    $chisroldob="";
                    $dagentd="";
                }
                if($avdob[$i]==2)
                {
                    $chisroldob=0;
                    if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                    {
                        $dagentd=$_POST['nomerschetks'];
                        $shtsertd=$shtsertdd[$i];
                        $chisroldob=1;
                    }
                    $dobprodschet=$_POST['nomerschetks']."dob";
                    $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                    mysql_query($azaza) or die(mysql_error($link));
                    $pagentd="";
                    $sagentd="";
                    $chisroldob="";
                    $dagentd="";
                }
                if($avdob[$i]=="3")
                {
                    $dobprodschet=$_POST['nomerschetks']."dob";
                    $pagentd="Без АВ";
                    $sagentd="Без АВ";
                    $chisroldob=2;
                    if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                    {
                        $dagentd="Без АВ";
                        $shtsert=$shtsertdd[$i];
                        $chisroldob=3;
                    }
                    $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                    mysql_query($azaza) or die(mysql_error($link));
                    $pagentd="";
                    $sagentd="";
                    $chisroldob="";
                    $dagentd="";
                }

            }



            /* $dop_yslygi = $_POST['dobuslpr'];
             $count = count($dop_yslygi); //это количество элементов массива, для цикла.
                     for ($i=0; $i < $count; $i++)
             {
                         $azaza = "INSERT INTO `av` (


             `dob_usl`,
             `kto`
             ) VALUES (

             '$dop_yslygi[$i]',
             '".$userdata['users_id']."'
             )";
             mysql_query($azaza) or die(mysql_error($link));
             }*/
        }
    }


$ogrn = "SELECT * FROM ogrn WHERE id =".$_GET['id'];
$rogrn = mysql_query($ogrn);
$pogrn = mysql_fetch_array($rogrn);
$inn = $pogrn['inn'];
$kpp = $pogrn['kpp'];
$naim = $pogrn['naim'];
if($_POST['nsold']!=""&&$_POST['nsold']!="0")
		{
			if($_POST['priceks']!=""&&$_POST['priceks']!="0")
			{
					$koment = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($koment) or die(mysql_error($link));
			}
			if($_POST['aallsumm']!=""&&$_POST['aallsumm']!="0")
			{
			$komenti = "UPDATE schet SET `prodlenks`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($link));
			}
		}
$qazaza = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : ''));
while($rowazaza = mysql_fetch_array($qazaza)) {
if($_POST[$rowazaza['id']] == 1){
	$agent=htmlspecialchars($_POST["agent"]);
$pkvo = $rowazaza;
	$azaza = "INSERT INTO `schet` (
    `ns`,
	`shetold`,
	`gen`,
`idkli`,
`sortir`,
`otdel`,
`filial`,
`god`,
`nomerdog`,
`nomerschet`,
`kolichschet`,
`nomerschetks`,
`kvo`,
`prod`,
`kvogorod`,
`goroddd`,
`prodlen`,
`skidka`,
`price`,
`priceks`,
`ogrn`,
`name`,
`inn`,
`kpp`,
`produkt`,
`kto`,
`data`,
`koment`,
`d`,
`m`,
`y`,
`time`,
`rand`,
`oferta`,
`tell`,
`url`,
`agent`,
`nds`
	) VALUES (
    '".$schetNs."',
	'$_POST[nsold]',
	'".$pkvo['gen']."',
'$_GET[id]',
'".$var."',
'".$userdata['otdel']."',
'".$userdata['filial']."',
'".$pestar['god']."',
'".$pestar['nomerdog']."',
'".$pestar['nomerschet']."',
'".$pestar['kolichschet']."',
'$_POST[nomerschetks]',
'".$_POST["input".$rowazaza['id']]."',
'".$rowazaza['id']."',
'$_POST[kvogorod]',
'$_POST[goroddd]',
'0',
'$_POST[skidka]',
'$_POST[aallsumm]',
'$_POST[priceks]',
'".$_GET['ogrn']."',
'".$naim."',
'".$inn."',
'".$kpp."',
'$_GET[parent]',
'".$userdata['users_id']."',
'".$pestar['data']."',
'".$pestar['koment']."',
'".$pestar['d']."',
'".$pestar['m']."',
'".$pestar['y']."',
'".$pestar['time']."',
'$_POST[shetrand]',
'".$pestar['time']."',
'$_POST[tel]',
'$_POST[url]',
'$agent',
$nds
)";
mysql_query($azaza) or die(mysql_error($link));
}}
$data_documents_str = "INSERT INTO `document_data` (
    `rand`,
    `d_bill`,
    `d_contract`,
    `d_specification`,
    `d_act`,
    `d_tn`
    ) VALUES (
    '$_POST[shetrand]',
    '".date("y-m-d")."',
    '".date("y-m-d")."',
    '".date("y-m-d")."',
    '".date("y-m-d")."',
    '".date("y-m-d")."'
    )";
    $messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
        . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
        . $_POST['dog'];
    error_log(date('Y-m-d H:i:s') . ' ' . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
echo '<script type="text/javascript">';
echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";';
echo '</script>';


}

$depo = "SELECT * FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."'";
$rdepo = mysql_query($depo);
$pdepo = mysql_fetch_array($rdepo);

?>




<form method="post" id="schetForm">

<div>
<select  id="idprodkschet"class='form-control'>
<option value="2">Продление</option>
<option value="1">Новый</option>
</select>
<div id="oldns" style="display:none;width: 100%;float: left;"><div style="
    width: 23%;
    float: left;
"><span>Введите предыдущий № счета</span> <input id="nsold" name="nsold" type="text" value="<?echo $_GET['oldns']?>"/></div><div id="oldschet"></div></div>
</div>
<div id="prodkschet" style="display:none;">



<table id="tab" class="table">
<thead>
<tr>
<th style="width:1px;"></th>
<th style="padding: 5px;">Продукты </th>
  <th  style="width: 120px; padding: 5px;">Цена 1 шт.</th>
  <th  style="width: 10px; padding: 5px;">Кол-во</th>
  <th  style="width: 10px; padding: 5px;">Сумма</th>
</tr>
</thead>
<?php
$int = 1;
$query = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : ''));
while($row = mysql_fetch_array($query)) {
echo '<tr style="width:1px;"><td>
<input id="check'.$row['id'].'" name="'.$row['id'].'" type="checkbox" value="1" onclick="validate'.$row['id'].'()"';

if(isset($_GET['rand'])){
$dopschet = "SELECT * FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."' AND prod = '".$row['id']."'";
$rdopschet = mysql_query($dopschet);
$pdopschet = mysql_fetch_array($rdopschet);
if($pdopschet['prod'] == $row['id']){
echo 'checked';
}
}

echo'>';
echo '</td>';
echo '<td style="padding: 3px;">';
echo $row['name'];
echo '</td>';
echo '<td style="padding: 3px;">';
$vowels = array(" "," ");
$price = str_replace($vowels, "", $row['price']);
echo $price;

echo '</td>';
echo '<td style="width: 5px;">';
echo '<input id="input'.$row['id'].'" name="input'.$row['id'].'" type="text" value="';
if(isset($_GET['rand'])){
$dopschet = "SELECT * FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."' AND prod = '".$row['id']."'";
$rdopschet = mysql_query($dopschet);
$pdopschet = mysql_fetch_array($rdopschet);
if ($pdopschet['kvo'] > 0){
echo $pdopschet['kvo'];
}else{
echo '0';
}
}else{
echo '0';
}

echo'" style="width: 50px; height: 20px; color:#666;">';
echo '</td>';
echo '<td style="width: 50px;">';
echo '<p id="summ'.$row['id'].'" name="summ'.$row['id'].'">';
if(isset($_GET['rand'])){
$dopschet = "SELECT * FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."' AND prod = '".$row['id']."'";
$rdopschet = mysql_query($dopschet);
$pdopschet = mysql_fetch_array($rdopschet);

echo $pdopschet['kvo']*$price;

}else{
echo '0';
}
echo'</p>
<script >
$(function() {
$("input[id=input'.$row['id'].']").keyup(function input'.$row['id'].'(){
  if($("#input'.$row['id'].'").val() > 0){
	$("#check'.$row['id'].'").prop("checked", true);
  }else{
	$("#check'.$row['id'].'").attr("checked", false);
  }

price = '.$price.' * $("#input'.$row['id'].'").val();
document.getElementById("summ'.$row['id'].'").innerHTML = '.price.';
});

$( "#input'.$row['id'].'" ).click(function() {
  $( "#input'.$row['id'].'" ).keyup();
  if($("#input'.$row['id'].'").val() == 0){
	$("input[id=check'.$row['id'].']").removeAttr("checked");
  }if($("#input'.$row['id'].'").val() > 0){
	$("#check'.$row['id'].'").prop("checked", true);
  }
});
});
function validate'.$row['id'].'(){
if (check'.$row['id'].'.checked == 1){
$("#input'.$row['id'].'").val("1");
price = '.$price.' * $("#input'.$row['id'].'").val();
document.getElementById("summ'.$row['id'].'").innerHTML = '.price.';
} else {
$("#input'.$row['id'].'").val("0"); 
price = '.$price.' * $("#input'.$row['id'].'").val();
document.getElementById("summ'.$row['id'].'").innerHTML = '.price.';
}
}
</script>'; 
echo '</td></tr>';
}
?>
</table>

<table class="table ">
<tr  style="display:none"><td style="width:20px;">
</td>
<td style="padding: 5px;">
Выезд
</td>
<td style="width: 120px; padding: 5px;">
<select id="gorod" onchange="gorod()" type="text" name="gorod" style="font-size: 15px;">
<?php 
if($pdepo['goroddd'] > 0){
if(isset($_GET['rand'])){ echo '<option value="'.$pdepo['goroddd'].'">';
if($pdepo['goroddd'] == 450){
	echo 'Пятигорск';
}if($pdepo['goroddd'] == 750){
	echo 'КМВ';
}if($pdepo['goroddd'] == 1500){
	echo 'Георгиевск';
}if($pdepo['goroddd'] == 23){
	echo 'Километр';
}if($pdepo['goroddd'] == 162534){
	echo 'Связанная';
}
echo'</option>';
}
}
?>
<option value="0" style="background: #DDD;">Без выезда</option>
<option value="450">Пятигорск</option>
<option value="750">КМВ</option>
<option value="1500">Георгиевск</option>
<option value="23">Километр</option>
<option value="162534">Связанная</option>
</select>
</td>



<td style="width: 57px;">
<input id="kmgorod" type="text" value="" style="width: 50px; display: none;">
<input id="kvogorod" name="kvogorod" type="text" min="0" max="100" step="1" value="1" style="width: 50px;">
</td>
<td style="width: 5px;">
<p id="gorodd">0</p>
<input type="text" style="display: none;" name="goroddd" id="goroddd">
</td>
</tr>


<tr><td style="width:1px;">
</td>
<td style="padding: 5px;">
<strong style="font-size: 15px;">Итого:</strong>
</td>
<td style="font-size: 15px;width: 120px;">
Скидка %
</td>
<td  style="width: 58px; ">
<input id="skidka" name="skidka" type="text" value="0" style="width: 50px; color:#666;">
</td>
<td style="width: 50px;">
<p id="allsumm"></p>
<input type="text" style="display: none;" name="aallsumm" id="aallsumm">
</td></tr>
</table>

<table class="table">
<?php
if ($person['tel'] == 1) {
echo '
<tr><td style="width:150px;">
<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Номер телефона:
</td>
<td style="padding: 5px;">
<input id="tel" name="tel" class="form-control" type="text" value="';
if(isset($_GET['rand'])){ echo $pdepo['tell'];}
echo'" style="width:100%;">
</td>
</tr>';
}
?>
<?if ($_GET['parent']!=319){?>
<tr><td style="width:20px;">
<span class="glyphicon glyphicon-link" aria-hidden="true"></span> Ссылка
</td>
<td style="padding: 5px;">
<input id="url" name="url" class="form-control" type="text" value="<?php if(isset($_GET['rand'])){ echo $pdepo['url'];}?>" style="width:100%;">
</td>
</tr>
<tr><td style="width:1px;">
<span class="glyphicon glyphicon-ruble" aria-hidden="true"></span> Сумма:
</td>
<td  style="padding: 5px;">
<input type="text" class="form-control" name="priceks" id="priceks"  value="<?php if(isset($_GET['rand'])){ echo $pdepo['priceks'];}?>">
</td></tr>
<tr><td style="width:1px;">
<span class="glyphicon glyphicon-tag" aria-hidden="true"></span> Номер счета:
</td>
<td  style="padding: 5px;">
<input type="text" class="form-control" name="nomerschetks" id="nomerschetks"  value="<?php if(isset($_GET['rand'])){ echo $pdepo['nomerschetks'];}?>">
</td></tr>
<tr><td style="width:1px;">
<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Агент:
</td>
<td  style="padding: 5px;">
<select name="agent" class="form-control">
  <option value="0" selected>Выберите агента</option>   
  <?$r=mysql_query("SELECT * from agent ");
   while($res = mysql_fetch_assoc($r)) :?>	  
  <option value="<?php echo $res['id'];?>"><?php echo htmlspecialchars($res['name']);?></option>
    <?php endwhile; ?>
  </select>
</td></tr>
<?}?>

<?if (in_array($_GET['parent'], [319, 400, 391, 426, 428, 427, 434, 435])) {?>
<div style="
    padding-top: 20px;
    font-size: 12pt;
">
<b style="font-size: 15px;">Количество строк в описе:</b>
<input name="kol_opis" type="text" style="width:50px;" value=''>
    <br><b style="font-size: 15px;">Кто составил опись:</b>
    <select name="sos_opis" class="htext-add">
    <option  value='4113' selected>Коневец Татьяна</option>
         <option value="4113">Коневец Татьяна</option>;
</div>
<?}?>

</table>
<script >
setInterval(gorod, 500)
function gorod(){
var gorr = $("#gorod").val();
var kvogorod = $("#kvogorod").val();
var kmgorod = $("#kmgorod").val();



if (gorr == 23) {
$(function() {
$("input[id='kmgorod']").keyup(function kmgorod(){
document.getElementById("gorodd").innerHTML = gorr * $("#kmgorod").val()*$("#kvogorod").val() ;
$("#goroddd").val(gorr * $("#kmgorod").val());
});
});
document.getElementById("gorodd").innerHTML = gorr * kmgorod*$("#kvogorod").val() ;
$("#goroddd").val(gorr * kmgorod);
document.getElementById("kmgorod").style.display="block";
} else if (gorr == 162534) {
document.getElementById("gorodd").innerHTML = gorr - 162534;
$("#goroddd").val(gorr);
} else {
document.getElementById("gorodd").innerHTML = gorr*$("#kvogorod").val() ;
$("#goroddd").val(gorr);
document.getElementById("kmgorod").style.display="none";
}


}
setInterval(alsum, 500)
function alsum(){
    Decimal.config({ precision: 30 });
<?php $query3 = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : '')); while($row3 = mysql_fetch_array($query3)) { echo 'price'.$row3['id'].' = parseFloat(document.getElementById("summ'.$row3['id'].'").innerHTML);
'; } ?>
var allsumm = <?php $query1 = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : '')); while($row1 = mysql_fetch_array($query1)) { echo "price",$row1['id']," + "; } ?> parseFloat(document.getElementById("gorodd").innerHTML);
skidka = $("#skidka").val();
sskidka = (new Decimal(skidka)).dividedBy(100).times(new Decimal(allsumm));
if ($("#gorod").val() == 162534){
document.getElementById('allsumm').innerHTML = Decimal(allsumm).minus(Decimal(sskidka));
$("#aallsumm").val(Decimal(allsumm).minus(Decimal(sskidka)));
}else{
document.getElementById('allsumm').innerHTML = Decimal(allsumm).minus(Decimal(sskidka));
$("#aallsumm").val(Decimal(allsumm).minus(Decimal(sskidka)));
}
}
</script>
<?if ($_GET['tip']==12){?>
<div  class="av"id="agent">
<div class="form-control" style="height: auto;float: left;">
<label> Основной продукт&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 10pt;
    border: 1px solid #ddd; padding:5px;"><?php
$id = (int)$_GET['parent']; // приводим к int
$person = mysql_fetch_assoc(mysql_query("SELECT * FROM `produkti` WHERE id = $id"));
echo $person['name'];
?></span></label>
<div style="
    width: 25%;
    height: 50px;
    float: left;
">
<label style="
  width: 30%;
    text-align: left;
	padding-top:7px;
"> Тип основного продукта&nbsp;&nbsp;&nbsp;&nbsp;</label>
<select style="
    width: 53%;
    font-size: 15pt;
    margin-top: 7px;
    padding-top: 4px;
    padding-bottom: 4px;margin-left: 40px;"id="av" name="av">
<option value="-1"selected></option>
<option value="0">Новый</option>
<option value="1">Продление</option>
<option value="2">Удл.личности от контура</option>
<option value="3">Ускор.удл.личности</option>
<option value="4">Бесплатно</option>
</select>
</div>
<div style="
    width: 35%;
    height: auto;
    float: left;
	display:none;
"id="dobusl">
<label style="
  width: 50%;
    text-align: left;
	padding-top:7px;
">Укажите  какие услуги есть
 в счете основного продукта&nbsp;&nbsp;&nbsp;&nbsp;</label>
 <div style="
    width: 50%;
    font-size: 15pt;

    padding-top: 4px;
    padding-bottom: 4px;float: left;" id="dobusll">
<select style="
    width: 100%;
    font-size: 15pt;
    margin-top: 7px;
    padding-top: 4px;
    padding-bottom: 4px;"name="dobusl[]"id="dobuslll" >
	<option value="0"selected></option>
 <?$r=mysql_query("SELECT * from dobusl ");
   while($res = mysql_fetch_assoc($r)) :?>	  
  <option value="<?php echo $res['id'];?>"><?php echo htmlspecialchars($res['dobuslnaim']);?></option>
    <?php endwhile; ?>
</select>
</div>
</div>
<div style="
    
    width: 15%;
    height: 50px;
    float: left;
    margin-left: 0px;
    padding-left: 10px;
	display:none;

" id="shtsert">
<label style="
  
  width: 60%;
  text-align: left;
  padding-top:7px;

">Количество
выпусков эцп&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input class="form-control" style="width:50px;float: left;margin-top: 10px;font-size: 15pt;
    text-align: center;"id="sht" name="sht"></input>
</div>
</div>

<div class="form-control" style="height: auto;float: left;"id="dobp">
<div  style="
    height: auto;
    float: left;
    width: 100%;
"id="dobpp">
<div style="
    width: 25%;
    height: auto;
	float: left;
"id="dobpr">
<label style="
    width: 40%;
    padding-top: 5px;
">Добавить доп  продукт к основному продукту</label>
<div id="dobpp"> 
<select style="
    width: 53%;
    margin-left: 10px;
    margin-top: 8px;
    font-size: 15pt;
	padding-top: 4px;
    padding-bottom: 4px;
"name="dobprod[]"id="dobprod"class="dobprod">
<option value="0"selected></option>
<?$r=mysql_query("SELECT * from dobprod");
   while($res = mysql_fetch_assoc($r)) :?>	  
  <option value="<?php echo $res['id'];?>"><?php echo htmlspecialchars($res['dobprodnaim']);?></option>
    <?php endwhile; ?>
</select>
</div>
</div>
<div style="
    width: 25%;
    height: 50px;
    float: left;
	display:none;
"id="dobppr"class="dobppr">
<label style="
  
  width: 39%;
  text-align: left;
  padding-top:7px;

">Тип дополнительного продукта&nbsp;&nbsp;&nbsp;&nbsp;</label>
<select style="
  width: 53%;
    font-size: 15pt;
    margin-top: 7px;
    padding-top: 4px;
    padding-bottom: 4px;
    margin-left: 6px;"id="avdob" name="avdob[]" class="avdob">
<option value="-1"selected></option>
<option value="0">Новый</option>
<option value="1">Продление</option>
<option value="2">Удл.личности от контура</option>
<option value="3">Ускор.удл.личности</option>
<option value="4">Бесплатно</option>
</select>
</div>
<div style="
 width: 35%; height: auto; float: left; display: none;"id="dobuslpr">
<label style="
  width: 50%;
    text-align: left;
	padding-top:7px;
">Укажите  какие услуги есть
 в счете доп.продукта&nbsp;&nbsp;&nbsp;&nbsp;</label>
 <div style="
    width: 50%;
    font-size: 15pt;

    padding-top: 4px;
    padding-bottom: 4px;float: left;"id="dobuslprl">
<select style="
    width: 100%;
    font-size: 15pt;
    margin-top: 7px;
    padding-top: 4px;
    padding-bottom: 4px;"name="dobuslpr[]"id="dobusllpr">
	<option value="-1" selected></option>
 <?$r=mysql_query("SELECT * from dobusl ");
   while($res = mysql_fetch_assoc($r)) :?>	  
  <option value="<?php echo $res['id'];?>"><?php echo htmlspecialchars($res['dobuslnaim']);?></option>
    <?php endwhile; ?>
</select>
</div>
</div>
<div style="
    
    width: 15%;
    height: 50px;
    float: left;
    margin-left: 0px;
    padding-left: 10px;
	display:none;

" id="shtsertpr">
<label style="
  
  width: 60%;
  text-align: left;
  padding-top:7px;

">Количество
выпусков эцп&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input class="form-control" style="width:50px;float: left;margin-top: 10px;font-size: 15pt;
    text-align: center;"id="shtpr" name="shtpr[]" value="0"></input>
</div>
</div>
</div>
</div>
<?}?>
<?php 


 
//echo '<div>
//<br>
//<table class="table">
//<tr>
//<td colspan="2">
//<select id="dog" class="form-control" type="text" name="dog" style="font-size: 15px;">';
//if(!isset($_GET['rand'])){
//
//}else{
//	echo'<option value="'.$pdepo['oferta'].'" style="background: #ccc;">';
//	if($pdepo['oferta'] == 1){
//		echo 'Договор оферты';
//	}if($pdepo['oferta'] == 2){
//		echo 'Контур';
//	}if($pdepo['oferta'] == 0){
//		echo 'Новый договор';
//	}
//	echo'</option>';
//}
//echo'<option value="1">Договор оферты</option>';
///*<option value="2">Контур</option>*/
//echo'<option value="0">Новый договор</option>';
//$query1 = mysql_query("SELECT * from dogovor WHERE ogrn = $_GET[ogrn]");
//while($row1 = mysql_fetch_array($query1)) {
//echo "<option value='".$row1['god'].$row1['filial'].$row1['otdel'].$row1['nomer']."'>Договор №: ".$row1['god'].$row1['filial'].$row1['otdel'].$row1['nomer']."</option>";
//}
//if(isset($_GET['rand'])){
//}else{
//echo '<div class="col-md-4" style="padding-bottom: 10px;">';
//include 'docmyogrn.php';
//echo '</div>';
//}
//echo '</select>
//</td>
//</tr>
//</table>
//</div>';

if(!isset($_GET['rand'])){ 
echo '
<input type="text" style="display:none;" name="shetrand" id="shetrand" value="'.$shetrand.'"><br>
<input type="submit" name="submitsch" value="Создать" class="btn btn-success col-md-12" /><br>';
}else{
echo '
<input type="text" style="display:none;" name="shetrand" id="shetrand" value="'.$shetrand.'"><br>
<input type="submit" name="submitsave" value="Сохранить" class="btn btn-success col-md-12" /><br>';
}
?>


</form>
</div>


<!--<div>
<label> Основной продукт&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 10pt;
    border: 1px solid #ddd; padding:5px;"><?php
$id = (int)$_GET['parent']; // приводим к int
$person = mysql_fetch_assoc(mysql_query("SELECT * FROM `produkti` WHERE id = $id"));
echo $person['name'];
?></span></label>
</div>!-->


<script>
  var count = 0;
$(function() {
    count = $('input[type=checkbox]:checked').length;
    displayCount();

    $('input[type=checkbox]').bind('click' , function(e, a) {   
         if (this.checked) {
              count += a ? -1 : 1;
         } else {
              count += a ? 1 : -1;
         }
         displayCount();
    });
    $('#invert').click(function(e) {    
         $('input[type=checkbox]').trigger('click', true)
    });
	$('#deleteol').click(function() {    
        $('#count').text(count); 
		count = 0;
		document.getElementById("countdisplay").style.display="none";
    });
	
});
function displayCount() {
    $('#count').text(count); 
if(count == 0){
	document.getElementById("countdisplay").style.display="none";
	}else{
	document.getElementById("countdisplay").style.display="block";
	}
}
	

</script>
<script>
if(document.getElementById('idprodkschet').value=="1")
	{
		document.getElementById('prodkschet').style.display='block';
		document.getElementById('oldns').style.display='none';
	}
	if(document.getElementById('idprodkschet').value=="2")
	{
		document.getElementById('oldns').style.display='block';
		document.getElementById('prodkschet').style.display='none';
	}
$(document).ready(function(){
$( "#idprodkschet" ).change(function () {
	if(document.getElementById('idprodkschet').value=="1")
	{
		document.getElementById('prodkschet').style.display='block';
		document.getElementById('oldns').style.display='none';
	}
	if(document.getElementById('idprodkschet').value=="2")
	{
		document.getElementById('oldns').style.display='block';
		document.getElementById('prodkschet').style.display='none';
	}
	/*ogrn=document.getElementById('getOrg').value;
	tip=document.getElementById('tip').value;
	datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
			$.ajax({
				type: "GET",
				url: "tablschetog.php",
				data: "users=<?echo $_GET['id'];?>&ogrn="+ogrn+"&tip="+tip+"&datas="+datastart+"&dataf="+datafinish+"",
				success: function(html){
					 $("#tablschetog").html(html);
				}
			});*/
		});
    if (document.getElementById("nsold").value != '')
    {
        var ns = document.getElementById("nsold").value;


        $.ajax({
            type: "GET",
            url: "oldschet.php",
            data: "nsold="+ns+"&idkli=<?echo $_GET['id'];?>&parent=<?echo $_GET['parent'];?>",
            success: function(msg){
                var s = document.getElementById("oldschet");
                s.innerHTML = msg;
                if(s.innerHTML=="Данный номер счета не найден в системе")
                {
                    document.getElementById("prodkschet").style.display="none";
                    document.getElementById("oldschet").style.color="red";
                }
                if(s.innerHTML!="Данный номер счета не найден в системе")
                {
                    if(s.innerHTML!="Выбранный продукт не указан в счете №'"+ns+"'")
                    {
                        document.getElementById("prodkschet").style.display="block";
                    }
                }
                if(s.innerHTML=="Выбранный продукт не указан в счете №'"+ns+"'")
                {
                    document.getElementById("prodkschet").style.display="none";
                    document.getElementById("oldschet").style.color="red";
                }

            }
        })
    }
		 $("#nsold").keyup(function () {
            if (document.getElementById("nsold").value != '')
            {
                var ns = document.getElementById("nsold").value;


				$.ajax({
				type: "GET",
				url: "oldschet.php",
				data: "nsold="+ns+"&idkli=<?echo $_GET['id'];?>&parent=<?echo $_GET['parent'];?>",
				success: function(msg){
					 var s = document.getElementById("oldschet");
				s.innerHTML = msg;
				if(s.innerHTML=="Данный номер счета не найден в системе")
				{
					document.getElementById("prodkschet").style.display="none";
					document.getElementById("oldschet").style.color="red";
				}
				if(s.innerHTML!="Данный номер счета не найден в системе")
				{
					if(s.innerHTML!="Выбранный продукт не указан в счете №'"+ns+"'")
				    {
					document.getElementById("prodkschet").style.display="block";
				    }
				}
				if(s.innerHTML=="Выбранный продукт не указан в счете №'"+ns+"'")
				{
					document.getElementById("prodkschet").style.display="none";
					document.getElementById("oldschet").style.color="red";
				}
				
				}
			})
            }
            if (document.getElementById("nsold").value == '' )
            {
				var ns=document.getElementById("nsold").value;
				$.ajax({
				type: "GET",
				url: "oldschet.php",
				data: "nsold="+ns+"&idkli=<?echo $_GET['id'];?>&parent=<?echo $_GET['parent'];?>",
				success: function(msg){
					 var s = document.getElementById("oldschet");
				s.innerHTML = msg;
				document.getElementById("prodkschet").style.display="none";
				}
							
			})
            }
        });
});
if(<?echo $_GET['tip']?>=="12")
{
nomerschetks.oninput = function() {
    if(document.getElementById("nomerschetks").value!="")
	{
	document.getElementById("agent").style.display="block";
	}
	else
	{
		document.getElementById("agent").style.display="none";
	}
  };
  av.onchange=function(event)
  {
	  if(document.getElementById("av").value=="3")
	  {
          document.getElementById("dobusl").style.display="none";
          document.getElementById("shtsert").style.display="block";
	  }
      if(document.getElementById("av").value=="4")
      {
          document.getElementById("dobusl").style.display="none";
          document.getElementById("shtsert").style.display="none";
      }
	  if(document.getElementById("av").value=="-1")
	  {
		  document.getElementById("dobusl").style.display="none";
		  document.getElementById("shtsert").style.display="none";
	  }
	   if(document.getElementById("av").value=="0")
	  {
		  document.getElementById("dobusl").style.display="block";
		  document.getElementById("shtsert").style.display="block";
	  }
	   if(document.getElementById("av").value=="1")
	  {
		  document.getElementById("dobusl").style.display="block";
		  document.getElementById("shtsert").style.display="block";
	  }
	  if(document.getElementById("av").value=="2")
	  {
		  document.getElementById("dobusl").style.display="none";
		  document.getElementById("shtsert").style.display="block";
	  }
  }
  dobusl.onchange=function()
  {
	  if(document.getElementById("dobuslll").value!="0")
	  {
		  let div2=document.getElementById("dobuslll").cloneNode(true);
          document.getElementById("dobusll").appendChild(div2);
	  }
  }
    dobuslpr.onchange=function()
  {
	  if(document.getElementById("dobusllpr").value!="0")
	  {
		  let div2=document.getElementById("dobusllpr").cloneNode(true);
          document.getElementById("dobuslprl").appendChild(div2);
	  }
  }
 dobprod.oninput = function() {
    if(document.getElementById("dobprod").value!="-1")
	{
	document.getElementById("dobppr").style.display="block";
	 
	}
	else
	{
		document.getElementById("dobppr").style.display="none";
		 document.getElementById("dobuslpr").style.display="none";
		  document.getElementById("shtsertpr").style.display="none";
	}
  };
  $(document).ready(function(){
	 $('body').on("change", ".dobprod", function (e) {
		var div3=document.getElementById("dobpp").cloneNode(true);
  document.getElementById("dobp").append(div3);
 });  
});
  $(document).ready(function(){
     
	 $('body').on("change", ".avdob", function (e) {
		 var li = document.querySelectorAll('#avdob');
		 var dobuslpr = document.querySelectorAll('#dobuslpr');
		  var shtsertpr = document.querySelectorAll('#shtsertpr');
         for (index = 0; index < li.length; index++) {

      if(li[index].value=="3")
	  {
          dobuslpr[index].style.display="none";
          shtsertpr[index].style.display="block";
	  }
             if(li[index].value=="4")
             {
                 dobuslpr[index].style.display="none";
                 shtsertpr[index].style.display="none";
             }
	  if(li[index].value=="-1")
	  {
		   dobuslpr[index].style.display="none";
		  shtsertpr[index].style.display="none";
	  }
	   if(li[index].value=="0")
	  {
		  dobuslpr[index].style.display="block";
		  shtsertpr[index].style.display="block";
	  }
	   if(li[index].value=="1")
	  {
		   dobuslpr[index].style.display="block";
		  shtsertpr[index].style.display="block";
		  shtsertpr[index].value="0";
	  }
	  if(li[index].value=="2")
	  {
		   dobuslpr[index].style.display="none";
		  shtsertpr[index].style.display="block";
	  }
	  }
 }); 

});
}
</script>
<p id="countdisplay" style="">Выбранно: <b style="font-size:15px;" id="count"></b></p>

<?php
$messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
    . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
    . $_POST['dog'];
error_log(date('Y-m-d H:i:s') . ' ' . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
?>

<script src="/js/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
(function () {
    function onReady(fn) {
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            window.setTimeout(fn, 0);
        } else if (document.addEventListener) {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            window.attachEvent('onload', fn);
        }
    }

    onReady(function () {
        var form = document.getElementById('schetForm');
        var overlay = document.getElementById('schetLoadingOverlay');
        var title = document.getElementById('schetLoadingTitle');
        var submittedBy = '';

        if (!form || !overlay) {
            return;
        }

        var inputs = form.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type === 'submit') {
                inputs[i].onclick = function () {
                    submittedBy = this.name || '';
                };
            }
        }

        form.onsubmit = function () {
            if (form.getAttribute('data-loading') === '1') {
                return false;
            }

            if (form.checkValidity && !form.checkValidity()) {
                if (form.reportValidity) {
                    form.reportValidity();
                }
                return false;
            }

            form.setAttribute('data-loading', '1');
            if (!submittedBy && form.elements['submitsave']) {
                submittedBy = 'submitsave';
            }
            if (title) {
                title.innerHTML = submittedBy === 'submitsave' ? 'Сохраняем счет' : 'Создаем счет';
            }
            if (overlay.className.indexOf('is-visible') === -1) {
                overlay.className += ' is-visible';
            }

            return true;
        };
    });
}());
</script>
</body>
</html>
