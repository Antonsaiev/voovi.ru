<?
include 'conf.php';
require_once 'schet_identity.php';
if (!isset($_GET['rand']) || $_GET['rand'] === '') {
    exit;
}
if($_GET['tipschet']=="prod") {
    $delset="`del`='0',`prodlens`='1',`prodlenks`='1'";
}
else{
    $delset="`del`='0'";
}
$datet= date("Y-m-d");
$sql = mysql_query("UPDATE schet SET $delset,`dateotkaz` ='".$datet."',`ktootkaz` ='".$_GET['kto']."' WHERE `rand` = '" . $_GET['rand'] . "'");
$newSchetIdentity = null;

$q = "SELECT * FROM `schet` WHERE rand ='".$_GET['rand']."'";
$result = mysql_query($q);
while($person  = mysql_fetch_assoc($result ))  {
/*$person = mysql_fetch_array($result);*/
if($person['akt']=="1") {
    if ($newSchetIdentity === null) {
        $newSchetIdentity = voovi_allocate_schet_identity(date("Y"), $person['kto'], $person['otdel']);
    }
    $kols = $newSchetIdentity['kolichschet'];
    $shetrand = $newSchetIdentity['rand'];
    if ($_GET['tipschet'] == "prod") {
        $datetim = date("d.m.y в H:i");
        $dated = date("d");
        $datem = date("m");
        $datey = date("Y");
    } else {
        $datetim = $person['data'];
        $dated = $person['d'];
        $datem = $person['m'];
        $datey = "20" . $person['y'];
    }

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
			`oplachenks`,
			`kvo`,
			`prod`,
			`goroddd`,
			`prodlen`,
			`skidka`,
			`priceks`,
			`price`,
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
			`cher`,
			`rand`,
			`koment`,
			`lico`,
			`vladelec`,
			`oferta`,
			`prichotk`,
			`ktootkaz`,
			`dateotkaz`,
			`url`
			) VALUES (
			'" . $newSchetIdentity['ns'] . "',
		'" . $person['ns'] . "',
		'" . $person['idkli'] . "',
		'" . $person['sortir'] . "',
		'" . $person['otdel'] . "',
		'" . $person['filial'] . "',
		'" . date("Y") . "',
		'" . $person['nomerdog'] . "',
		'" . $person['nomerschet'] . "',
		'" . $kols . "',
		'" . $person['nomerschetks'] . "',
		'0',
		'" . $person['kvo'] . "',
		'" . $person['prod'] . "',
		'" . $person['goroddd'] . "',
		'" . $person['prodlen'] . "',
		'" . $person['skidka'] . "',
		'" . $person['priceks'] . "',
		'" . $person['price'] . "',
		'" . $person['ogrn'] . "',
		'" . $person['name'] . "',
		'" . $person['inn'] . "',
		'" . $person['kpp'] . "',
		'" . $person['produkt'] . "',
		'" . $person['kto'] . "',
		'" . $datetim . "',
			'" . $dated . "',
			'" . $datem . "',
			'" . $datey . "',
			'" . $person['time'] . "',
			'1',
			'" . $shetrand . "',
			'Отказ от счета " . $person['ns'] . "',
			'" . $person['lico'] . "',
			'" . $person['vladelec'] . "',
			'" . $person['oferta'] . "',
			'" . $_GET['tipotkaz'] . "',
			'" . $_GET['kto'] . "',
			'" . date("Y-m-d") . "',
			'" . $person['url'] . "'
		)";
        mysql_query($azaza) or die(mysql_error($link));
    }

if($person['akt']=="0")
{
    $sqlli = mysql_query("UPDATE schet SET `cher`='1', `prichotk`='" . $_GET['tipotkaz'] . "' WHERE `rand` = '" . $_GET['rand'] . "'");
}
}
?>
