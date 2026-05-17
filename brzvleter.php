<?
include 'conf.php';
$nextday=date("Y-m-d");
/*$date = new DateTime($nextday);
$date->modify('-2 day');
$day=$date->format('Y-m-d');*/
$qazaza = mysql_query("SELECT schet.produkt,schet.datebron,schet.oplachenks,schet.datezvon,schet.timezvon,schet.date_op,schet.ogrn,schet.idkli,schet.lico,schet.rand,schet.gr,schet.inn,schet.kpp,schet.ns,schet.name as naim,schet.nomerschet,produkti.name,users.f_name,users.l_name,users.o_name FROM `schet` inner join produkti on schet.produkt=produkti.id inner join users on users.users_id=schet.ktopis WHERE (schet.datebron!='0000-00-00' or schet.datezvon!='0000-00-00') and schet.cher='0' and schet.messageb!='0' and schet.messagez!='0' group by schet.ns");
while($rowazaza = mysql_fetch_array($qazaza)) {

	if($rowazaza['datebron']!="0000-00-00"&&$rowazaza['date_op']=="0000-00-00")
	{
	$date2 = new DateTime($rowazaza['datebron']);
    $date2->modify('-3 day');
    $day2=$date2->format('Y-m-d');
	if($nextday==$day2)
	{
		$to = "infosavoir@ya.ru";
$subject = "Бронь". $rowazaza['f_name'].' '.$rowazaza['l_name'].' '.$rowazaza['o_name'].' ';
$charset = "utf-8";
$headerss ="Content-type: text/html; charset=$charset\r\n";
$headerss.="MIME-Version: 1.0\r\n";
$headerss.="Date: ".date('D, d M Y h:i:s O')."\r\n";
$msg = "Вы ставили напоминание в таблице счетов  по брони"."
<html>
<head>
</head>
<body>
    <p><a href='".VOOVI_MAIN_URL."/kartklient.php?id=".$rowazaza['idkli']."'>"." Организация ".$rowazaza['naim']." Инн ".$rowazaza['inn']." КПП ".$rowazaza['kpp']." № Счета ".$rowazaza['ns']." Продукт ".$rowazaza['name']."</a></p>
</body>
</html>
";
mail($to, $subject, $msg, $headerss);
	}
	}
	if($rowazaza['datezvon']!="0000-00-00"&&$rowazaza['date_op']=="0000-00-00")
	{
	$date2 = new DateTime($rowazaza['datezvon']);
    $day2=$date2->format('Y-m-d');
	if($nextday==$day2)
	{
		$to = "infosavoir@ya.ru";
$subject = "Звонок ".$rowazaza['f_name'].' '.$rowazaza['l_name'].' '.$rowazaza['o_name'].' '.$rowazaza['timezvon'];
$charset = "utf-8";
$headerss ="Content-type: text/html; charset=$charset\r\n";
$headerss.="MIME-Version: 1.0\r\n";
$headerss.="Date: ".date('D, d M Y h:i:s O')."\r\n";
$msg = "Вы ставили напоминание в таблице счетов  по звонку"."
<html>
<head>
</head>
<body>
    <p><a href='".VOOVI_MAIN_URL."/kartklient.php?id=".$rowazaza['idkli']."'>"." Организация ".$rowazaza['naim']." Инн ".$rowazaza['inn']." КПП ".$rowazaza['kpp']." № Счета ".$rowazaza['ns']." Продукт ".$rowazaza['name']."</a></p>
</body>
</html>
";
mail($to, $subject, $msg, $headerss);
	}
	}
}
?>