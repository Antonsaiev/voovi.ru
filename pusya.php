<?php
include 'conf.php';
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



if($_GET['tip'] == 'konttakt'){
if(isset($_GET['lico'])){
$koment = "UPDATE schet SET `lico`='".$_GET['lico']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
$lis = "SELECT * FROM klient WHERE id =".$_GET['lico'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);

echo '{';
echo '"fio"';
echo ':"';
echo $personlis['fio'];
echo '", "tel"';
echo ':"';
echo $personlis['tel'];
echo '", "dol"';
echo ':"';
echo $personlis['dol'];
echo '", "email"';
echo ':"';
echo $personlis['email'];
echo '"}';

setcookie("fio", $personlis['fio'], time()+60*60*24*30); 
setcookie("tel", $personlis['tel'], time()+60*60*24*30); 
setcookie("email", $personlis['email'], time()+60*60*24*30); 

}
}




if($_GET['tip'] == 'bl'){
	$lis = "SELECT * FROM ogrn WHERE id =".$_GET['ogrn'];
	$resultlis = mysql_query($lis);
	$personlis = mysql_fetch_array($resultlis);
	if($personlis['bl'] == 0){
		$koment = "UPDATE ogrn SET `bl`='1' WHERE id='".$_GET['ogrn']."'";
		mysql_query($koment) or die(mysql_error($link));
		echo '1';
	}else{
		$koment = "UPDATE ogrn SET `bl`='0' WHERE id='".$_GET['ogrn']."'";
		mysql_query($koment) or die(mysql_error($link));
		echo '0';
	}
}


if($_GET['tip'] == 'delcoll'){
$koment = "DELETE FROM `call_center` WHERE `id`='".$_GET['id']."'";
mysql_query($koment) or die(mysql_error($link));
}

if($_GET['tip'] == 'agentich'){
$koment = "UPDATE schet SET `agent`='".$_GET['tel']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}
if($_GET['tip'] == 'sos_opis'){
    $koment = "UPDATE schet SET `sos_opis`='".$_GET['tel']."' WHERE rand='".$_GET['rand']."'";
    mysql_query($koment) or die(mysql_error($link));
    $komenti = "UPDATE schet SET `kol_opis`='".$_GET['kol']."' WHERE rand='".$_GET['rand']."'";
    mysql_query($komenti) or die(mysql_error($link));
}



if($_GET['tip'] == 'tellich'){
$koment = "UPDATE schet SET `tell`='".$_GET['tel']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}

if($_GET['tip'] == 'otl3'){


$qrand = "SELECT * FROM `schet` ORDER BY sortir DESC";
					$resultrand = mysql_query($qrand);
					$personrand = mysql_fetch_array($resultrand);
					
echo $personrand['sortir'];
$var = $personrand['sortir'] + 1;
	
	
	
$koment = "UPDATE schet SET `otl3`='".date('Ymd')."', `sortir`='".$var."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}

if($_GET['tip'] == 'tipprod'){
$koment = "UPDATE schet SET `tipprod`='".$_GET['tel']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}

if($_GET['tip'] == 'issetprod'){
$koment = "UPDATE schet SET `issetprod`='1' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}

if($_GET['tip'] == 'issetprodno'){
$koment = "UPDATE schet SET `issetprod`='0' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}

if($_GET['tip'] == 'dataprod'){

$koment = "UPDATE schet SET `dataprod`='".$_GET['tel']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
    $pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[rand]";
    $pavresult = mysql_query($pav);
    $pavoir = mysql_fetch_array($pavresult);
    $pavs = mysql_query("SELECT COUNT(*) as coun FROM kol_prodlen WHERE randkol = '".$_GET['rand']."'");
    $res =  mysql_fetch_array($pavs) ;
    if($res['coun']==0) {
        $komentu = "INSERT INTO `kol_prodlen`(`ns`, `kol`,`tip`,`date_prodleni`, `randkol`,`status`) VALUES ('" .$pavoir['ns']. "','1','Сертификат','" . $pavoir['dataprod']. "','" . $_GET['rand'] . "','schet')";
        mysql_query($komentu) or die(mysql_error($link));
    }
    else
    {
        $koments = "UPDATE kol_prodlen SET `date_prodleni`='".$_GET['tel']."' WHERE randkol='".$_GET['rand']."' and status='schet'";
        mysql_query($koments) or die(mysql_error($link));
    }

}
if($_GET['tip'] == 'datasert'){

$koment = "UPDATE schet SET `datasert`='".$_GET['tel']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
    $pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[rand]";
    $pavresult = mysql_query($pav);
    $pavoir = mysql_fetch_array($pavresult);
    $pavs = mysql_query("SELECT COUNT(*) as coun FROM kol_prodlen WHERE randkol = '".$_GET['rand']."'");
    $res =  mysql_fetch_array($pavs) ;
    if($res['coun']==0) {
        $komentu = "INSERT INTO `kol_prodlen`(`ns`, `kol`,`tip`,`date_prodleni`, `randkol`,`status`) VALUES ('" .$pavoir['ns']. "','1','Поставка','" .  $pavoir['dataprod'] . "','" . $_GET['rand'] . "','schet')";
        mysql_query($komentu) or die(mysql_error($link));
    }
    else
    {
        $koments = "UPDATE kol_prodlen SET `date_prodleni`='".$_GET['tel']."' WHERE randkol='".$_GET['rand']."' and status='schet'";
        mysql_query($koments) or die(mysql_error($link));
    }
}
if($_GET['tip'] == 'datacar'){

$koment = "UPDATE schet SET `datacar`='".$_GET['tel']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));

 

$qrand = "SELECT * FROM `schet` ORDER BY sortir DESC";
					$resultrand = mysql_query($qrand);
					$personrand = mysql_fetch_array($resultrand);
					
echo $personrand['sortir'];
$var = $personrand['sortir'] + 1;

$komentq = "UPDATE `schet` SET `sortir` =  '".$var."' WHERE rand='".$_GET['rand']."'";
mysql_query($komentq) or die(mysql_error($linkq));

}

if($_GET['tip'] == 'fadress'){
if(isset($_GET['vlad'])){
$koment = "UPDATE schet SET `fadress`='".$_GET['vlad']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
echo $_GET['vlad'];
}
}

if($_GET['tip'] == 'vladelec'){
if(isset($_GET['vlad'])){
$koment = "UPDATE schet SET `vladelec`='".$_GET['vlad']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
$lis = "SELECT * FROM klient WHERE id =".$_GET['vlad'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);

echo $personlis['fio'];
echo ' ';
echo $personlis['tel'];
echo ' ';
echo $personlis['email'];
}
}

if($_GET['tip'] == 'scheton'){
$koment = "UPDATE schet SET `scheton`='".$_GET['scheton']."' WHERE id='".$_GET['id']."'";
mysql_query($koment) or die(mysql_error($link));
}

if($_GET['tip'] == '2doc'){
$koment = "INSERT INTO `dokstamp` (`doki`, `ogrn`, `status`, `schet`) VALUES ('".$_GET['prod']."', '".$_GET['kli']."', '".$_GET['doc']."', '".$_GET['rand']."');";
mysql_query($koment) or die(mysql_error($link));
}else{
$koment = "DELETE FROM `dokstamp` WHERE `doki`='".$_GET['prod']."' AND `ogrn`='".$_GET['kli']."' AND `status`='".$_GET['doc']."' AND `schet`='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}

if($_GET['tip'] == 'ogrnus'){
$koment = "UPDATE users SET `inogrn`='".$_GET['rand']."' WHERE `users_id`='".$userdata['users_id']."'";
mysql_query($koment) or die(mysql_error($link));
} 

if($_GET['tip'] == 'telprod'){
	if($_GET['id']==1){mysql_query("UPDATE produkti SET `tel`='1' WHERE id='".$_GET['rand']."'") or die(mysql_error($link));}
	if($_GET['id']==0){mysql_query("UPDATE produkti SET `tel`='0' WHERE id='".$_GET['rand']."'") or die(mysql_error($link));}	
}

if($_GET['tip'] == 'genprod'){
	$tarifVersion = voovi_tarif_set_gen_by_tarif_id($_GET['rand'], $_GET['id']==1 ? 1 : 0);
	echo $tarifVersion['tarif_id'];
}

if($_GET['tip'] == 'prodlenacp'){
$koment = "UPDATE schet SET `prodlen_acp`='".$_GET['vlad']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}
if($_GET['tip'] == 'regkoment'){
$koment = "UPDATE schet SET `regkoment`='".$_GET['vlad']."' WHERE id='".$_GET['idprod']."'";
mysql_query($koment) or die(mysql_error($link));
}
if($_GET['tip'] == 'startacp'){
$koment = "UPDATE schet SET `start_acp`='".$_GET['vlad']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}
if($_GET['tip'] == 'endacp'){
$koment = "UPDATE schet SET `end_acp`='".$_GET['vlad']."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
}
if($_GET['tip'] == 'ktoprodu'){
$aaqiq = "SELECT * FROM produkti WHERE id ='".$_GET['id']."'";
$aaqresiult = mysql_query($aaqiq);
$aaqoigrn = mysql_fetch_array($aaqresiult);

$result = mysql_query("SELECT COUNT(*) FROM ogtrnprod WHERE prod ='".$_GET['prod']."' AND idkli ='".$_GET['idkli']."'");
$class = mysql_result($result, 0);
	
if ($class != 0) {
$aktivn = "UPDATE `ogtrnprod` SET `kto`='".$_GET['lico']."' WHERE prod ='".$_GET['prod']."' AND idkli ='".$_GET['idkli']."'";
mysql_query($aktivn) or die(mysql_error($link));
$aaqiqq = "SELECT * FROM users WHERE users_id ='".$_GET['lico']."'";
$aaqresiultq = mysql_query($aaqiqq);
$aaqoigrnq = mysql_fetch_array($aaqresiultq);
echo $aaqoigrnq['f_name']," ",$aaqoigrnq['l_name'];
}
}

if($_GET['tip'] == 'addkontakt'){
$q2 = "SELECT * FROM klient WHERE id=(SELECT MAX(id) FROM klient)";
$result2 = mysql_query($q2);
$person2 = mysql_fetch_array($result2);
$lico = "INSERT INTO `klient`(`fio`, `dol`, `tel`, `email`, `pol`) VALUES ('$_GET[fio]', '$_GET[dol]', '$_GET[tel]', '$_GET[email]', '$_GET[pol]')";
mysql_query($lico) or die(mysql_error($links));
$url2 = $person2['id'] + 1;
$ogrnlico = "INSERT INTO `klient_ogrn`(`idkli`, `klient`)  VALUES ('".$_GET['kli']."', '".$url2."')";
mysql_query($ogrnlico) or die(mysql_error($links));
}

if($_GET['tip'] == 'regtkoment'){
$koment = "UPDATE dokstampzabr SET `text`='".$_GET['vlad']."' WHERE doki = '".$_GET['doki']."' AND schet = '".$_GET['schet']."' AND idkli =".$_GET['idkli'];
mysql_query($koment) or die(mysql_error($link));
}

if(isset($_GET['date_schet'])){
$y = strstr($_GET['date_schet'],"-", true);
$m = mb_substr(strstr($_GET['date_schet'],"-"),1,2,'UTF-8');
$d = mb_substr(strstr($_GET['date_schet'],"-"),4,2,'UTF-8');
$koment = "UPDATE schet SET `d`='".$d."',`m`='".$m."',`y`='".$y."' WHERE rand='".$_GET['rand']."'";
mysql_query($koment) or die(mysql_error($link));
$lis = "SELECT * FROM schet WHERE rand =".$_GET['rand'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo $d.".".$m.".".$y;
}

?>
