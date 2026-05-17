<?
include 'conf.php';
if($_GET['tip']=="bron")
{

    if($_GET['message']=="bronyes")
    {
        $mes="1";
    }
    else
    {
        $mes="0";
    }
       $koment = "UPDATE schet SET `datebron`='".$_GET['datebron']."',`ktopis`='".$_GET['users']."',`messageb`='".$mes."' WHERE rand='".$_GET['rand']."'";
		mysql_query($koment) or die(mysql_error($link));
		$udosrpod = "SELECT DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg from schet where rand='".$_GET['rand']."'";
	$udosresultrpod = mysql_query($udosrpod);
	$udospersonrpod = mysql_fetch_array($udosresultrpod);
	$datebron=$udospersonrpod['date_reg'];
	echo $datebron;

}
if($_GET['tip']=="zvon")
{
    if($_GET['message']=="zvonyes")
    {
        $mes="1";
    }
    else
    {
        $mes="0";
    }
$koment = "UPDATE schet SET `datezvon`='".$_GET['datezvon']."',`timezvon`='".$_GET['timezvon']."',`ktopis`='".$_GET['users']."',`messagez`='".$mes."'  WHERE rand='".$_GET['rand']."'";
		mysql_query($koment) or die(mysql_error($link));
		$udosrpod = "SELECT DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon from schet where rand='".$_GET['rand']."'";
	$udosresultrpod = mysql_query($udosrpod);
	$udospersonrpod = mysql_fetch_array($udosresultrpod);
	$datebron=$udospersonrpod['date_zvon'];
	echo $datebron;

}
?>