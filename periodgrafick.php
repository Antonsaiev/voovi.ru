<?
include 'conf.php';
$m = date(F);
$_monthsList = array(
"1"=>"Январь","2"=>"Февраль","3"=>"Март",
"4"=>"Апрель","5"=>"Май", "6"=>"Июнь",
"7"=>"Июль","8"=>"Август","9"=>"Сентябрь",
"10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь");
if($_GET['yg']==date('Y'))
	{
		$colmonf=date('n');
	}
	if($_GET['yg']!=date('Y'))
	{
		$colmonf=12;
	}
if($_GET['orgng']=="0")
{
    $allogrngrafick='';
}
else
{
    $allogrngrafick="uslugi.id='".$_GET['orgng']."' and";
}
	?> 
<div style="
width: 1000px;
    margin: 0 auto;
    margin-top: 20px;
    margin-bottom: 20px;
    height: 400px;
">
<canvas id="myCharti<?echo $_GET['yg'];?>" width="600" height="200"></canvas>
<?
	for ($m=1; $m <= $colmonf; $m++) { 
          if($m<10)
		  {
			  $month="0".$m;
		  }
		  else
		  {
			$month=$m;  
		  }
        if($_GET['orgng']=="12"){
            $statusgd="schet.status='1'  AND ";
            $statusnap="schet.status='2'  AND ";
            $statusotkl="schet.status='3'  AND ";
            $statusprov="schet.status='4'  AND ";
            $statuspos="schet.status='5'  AND ";
            $statuskkt="schet.status='6'  AND ";
            $statuskktk="schet.status='7'  AND ";
            $statusvie="schet.status='16'  AND ";
            $statusust="schet.status='17'  AND ";
            $statusvust="schet.status='18'  AND ";
            $statuspol="schet.status='19'  AND ";
            $statuspolo="schet.status='20'  AND ";
            $statusvoz="schet.status='23'  AND ";
            $statuscha="schet.status='21'  AND ";
            $statusnaa="schet.status='65'  AND ";
            $statusprou="schet.status='161'  AND ";
            $statuspere="schet.status='12354'  AND ";
        }
        if($_GET['orgng']=="24"){
            $statusgd="schet.status='44'  AND ";
            $statusnap="schet.status='45'  AND ";
            $statusotkl="schet.status='47'  AND ";
            $statusprov="schet.status='48'  AND ";
            $statuspos="schet.status='49'  AND ";
            $statuskkt="schet.status='50'  AND ";
            $statuskktk="schet.status='51'  AND ";
            $statusvie="schet.status='52'  AND ";
            $statusust="schet.status='53'  AND ";
            $statusvoz="schet.status='12356'  AND ";
            $statusnaa="schet.status='60'  AND ";
            $statuspere="schet.status='12355'  AND ";
        }
        if($_GET['orgng']=="22"){
            $statusgd="schet.status='35'  AND ";
            $statusnap="schet.status='36'  AND ";
            $statusotkl="schet.status='37'  AND ";
            $statusprov="schet.status='38'  AND ";
            $statuspos="schet.status='39'  AND ";
            $statuskkt="schet.status='40'  AND ";
            $statuskktk="schet.status='41'  AND ";
            $statusvie="schet.status='42'  AND ";
            $statusust="schet.status='43'  AND ";
            $statusnaa="schet.status='77'  AND ";
        }
		  $monlist[] = array($month);
		   $rm = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del='0'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and schet.otk!='1' and schet.cher!='1' group by schet.rand");
    $resm = mysql_num_rows($rm) ;
	$rim = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del='0'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and schet.akt='1'  group by schet.rand");
    $resim = mysql_num_rows($rim) ;
		  $rdol = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."'  AND schet.status=''AND schet.oplachenks!= '1' and schet.oplachen != '1'   AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resdol = mysql_num_rows($rdol) ;
		$rop = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' AND schet.oplachenks = '1' AND schet.status=''AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resop = mysql_num_rows($rop) ;
		$rgd = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' AND $statusgd schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resgd = mysql_num_rows($rgd) ;
		$rnap = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' AND $statusnap schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resnap = mysql_num_rows($rnap) ;
		$rotkl = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' AND $statusotkl schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resotkl = mysql_num_rows($rotkl) ;
		$rprov= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' AND $statusprov schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resprov = mysql_num_rows($rprov) ;
		$rpos = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statuspos schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $respos = mysql_num_rows($rpos) ;
		$rkkt= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statuskkt schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $reskkt = mysql_num_rows($rkkt) ;
		$rkktk= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statuskktk schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $reskktk = mysql_num_rows($rkktk) ;
		$rvie= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statusvie schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resvie = mysql_num_rows($rvie) ;
		$rust= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statusust schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resust = mysql_num_rows($rust) ;
		$rvust= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statusvust schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resvust = mysql_num_rows($rvust) ;
		$rpol= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statuspol schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $respol = mysql_num_rows($rpol) ;
		$rpolo= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statuspolo schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $respolo = mysql_num_rows($rpolo) ;
		$rvoz= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statusvoz schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resvoz = mysql_num_rows($rvoz) ;
		$rcha= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statuscha schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $rescha = mysql_num_rows($rcha) ;
		$rnaa= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statusnaa schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resnaa = mysql_num_rows($rnaa) ;
		$rprou= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statusprou schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $resprou = mysql_num_rows($rprou) ;
		$rpere= mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."' and  schet.del = '0' and $statuspere schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
        $respere = mysql_num_rows($rpere) ;
		$rcher = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."'  and schet.cher='1'and schet.otk='0' group by schet.rand");
    $rescher = mysql_num_rows($rcher) ;
    $rotk = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrngrafick schet.del!='1'and schet.y='".$_GET['yg']."' and schet.m='".$month."'  and schet.otk='1' and schet.cher='0'group by schet.rand");
    $resotk = mysql_num_rows($rotk) ;
	if($_GET['status']=="all"){
        if($_GET['orgng']=="12")
        {
            $summ=$resim+$rescher+$resotk+$resdol+$resop+$resgd+$resnap+$resotkl+$resprov+$respos+$reskkt+$reskktk+$resvie+$resust+$resvust+$respol+$respolo+$resvoz+$rescha+$resnaa+$resprou+$respere;
        }
        if($_GET['orgng']=="24")
        {
            $summ=$resim+$rescher+$resotk+$resdol+$resop+$resgd+$resnap+$resotkl+$resprov+$respos+$reskkt+$reskktk+$resvie+$resust+$resvoz+$resnaa+$respere;
        }
        if($_GET['orgng']=="22")
        {
            $summ=$resim+$rescher+$resotk+$resdol+$resop+$resgd+$resnap+$resotkl+$resprov+$respos+$reskkt+$reskktk+$resvie+$resust+$resnaa;
        }
	$all[]=array($summ.",");
	$colorgraf="#79D1CF";
	$stat="Всего счетов";
	}
	if($_GET['status']=="dol"){
	$all[]=array($resdol.",");
	$colorgraf="#78AFD8";
	$stat="Не оплачены";
	}
	if($_GET['status']=="opl"){
	$all[]=array($resop.",");
	$colorgraf="#FFF850";
	$stat="Оплата";
	}
	if($_GET['status']=="1" ||$_GET['status']=="44"||$_GET['status']=="35"){
	$all[]=array($resgd.",");
        if($rescher['status']=="1" ) {
            $stat="Ждем доки";
            $colorgraf="#FFF850";
        }
        if($rescher['status']=="44" ) {
            $stat = "оплата тс";
            $colorgraf="#FFF850";
        }
        if($rescher['status']=="35" ) {
            $stat = "заявка";
            $colorgraf="#FFF850";
        }
	}
	if($_GET['status']=="2" || $_GET['status']=="45"||$_GET['status']=="36"){
	$all[]=array($resnap.",");
        if($_GET['status']=="2" ) {
            $stat="На проверке";
            $colorgraf="#FFF850";
        }
        if($_GET['status']=="45" ) {
            $stat = "ждем ккт";
            $colorgraf="#E9C3FB";
        }
        if($_GET['status']=="36" ) {
            $stat = "регистрация и настройка";
            $colorgraf="#E9C3FB";
        }

	}
	if($_GET['status']=="3"|| $_GET['status']=="47"||$_GET['status']=="37"){
	$all[]=array($resotkl.",");
        if($_GET['status']=="3" ) {
            $stat="Отклонен";
            $colorgraf="#FFF850";
        }
        if($_GET['status']=="47" ) {
            $stat = "Товар получен";
            $colorgraf="#85D6D1";
        }
        if($_GET['status']=="37" ) {
            $stat = "ждем опись";
            $colorgraf="#85D6D1";
        }
	}
	if($_GET['status']=="4" || $_GET['status']=="48"||$_GET['status']=="38"){
	$all[]=array($resprov.",");
        if($_GET['status']=="4" ) {
            $stat="Проверен";
            $colorgraf="#FFF850";
        }
        if($_GET['status']=="48" ) {
            $stat = "Товар получен без фн";
            $colorgraf="#85D6D1";
        }
        if($_GET['status']=="38" ) {
            $stat = "опись принята";
            $colorgraf="#85D6D1";
        }

	}
	if($_GET['status']=="5"|| $_GET['status']=="49"||$_GET['status']=="39"){
	$all[]=array($respos.",");
        if($_GET['status']=="5" ) {
            $stat="поставк";
            $colorgraf="#E9C3FB";
        }
        if($_GET['status']=="49" ) {
            $stat = "на продлении";
            $colorgraf="#FFF850";
        }
        if($_GET['status']=="39" ) {
            $stat = "опсиь передана менеджер";
            $colorgraf="#FFF850";
        }


	}
	if($_GET['status']=="6"|| $_GET['status']=="50"||$_GET['status']=="40"){
	$all[]=array($reskkt.",");
        if($_GET['status']=="6" ) {
            $stat="ожидан.ккт";
            $colorgraf="#85D6D1";
        }
        if($_GET['status']=="50" ) {
            $stat = "устан.в офисе";
            $colorgraf="#FFB366";
        }
        if($_GET['status']=="40" ) {
            $stat = "отправить в гс1";
            $colorgraf="#FFB366";
        }

	}
	if($_GET['status']=="7"|| $_GET['status']=="51"||$_GET['status']=="41"){
	$all[]=array($reskktk.",");
        if($_GET['status']=="7" ) {
            $stat="ожидан ккт клиента";
            $colorgraf="#85D6D1";
        }
        if($_GET['status']=="51" ) {
            $stat = "выезд";
            $colorgraf="#FFB366";
        }
        if($_GET['status']=="41" ) {
            $stat = "ждем киз";
            $colorgraf="#FFB366";
        }


	}
	if($_GET['status']=="16"|| $_GET['status']=="52"||$_GET['status']=="42"){
	$all[]=array($reskktk.",");
        if($_GET['status']=="16" ) {
            $stat="выезд";
            $colorgraf="#FFF850";
        }
        if($_GET['status']=="52" ) {
            $stat = "выдали";
            $colorgraf="#FFF850";
        }
        if($_GET['status']=="42" ) {
            $stat = "маркировка киз без оборудовани";
            $colorgraf="#85D6D1";
        }
	}
	if($_GET['status']=="17"|| $_GET['status']=="43"||$_GET['status']=="53"){
	$all[]=array($reskktk.",");

        if($_GET['status']=="17" ) {
            $stat="устан.в офисе";
            $colorgraf="#FFB366";
        }
        if($_GET['status']=="53" ) {
            $stat = "ждем закрывающие документы";
            $colorgraf="#FFF850";
        }
        if($_GET['status']=="43" ) {
            $stat = "маркировка киз с оборудовани";
            $colorgraf="#85D6D1";
        }

	}
	if($_GET['status']=="18"){
	$all[]=array($reskktk.",");
	$colorgraf="#FFB366";
	$stat="выехали на установ";
	}
	if($_GET['status']=="19"){
	$all[]=array($reskktk.",");
	$colorgraf="#FFF850";
	$stat="полу-ние в лк";
	}
	if($_GET['status']=="20"){
	$all[]=array($reskktk.",");
	$colorgraf="#FFF850";
	$stat="получ-ие в офисе";
	}
	if($_GET['status']=="23"||$_GET['status']=="12356"){
	$all[]=array($resvoz.",");
	$colorgraf="#E76D74";
	$stat="возврат";
	}
	if($_GET['status']=="21"){
	$all[]=array($rescha.",");
	$colorgraf="#E9C3FB";
	$stat="частич-о на отгрузке";
	}
	if($_GET['status']=="65"|| $_GET['status']=="60"|| $_GET['status']=="77"){
	$all[]=array($resnaa.",");
	$colorgraf="#E9C3FB";
	$stat="на отгрузк";
	}
	if($_GET['status']=="161"){
	$all[]=array($resprou.",");
	$colorgraf="#FFB366";
	$stat="произв-я устанка";
	}
	if($_GET['status']=="atk"){
	$all[]=array($resim.",");
	$colorgraf="#90BEA3";
	$stat="отгр-ен";
	}
	if($_GET['status']=="12354"||$_GET['status']=="12355"){
	$all[]=array($respere.",");
	$colorgraf="#A0D7FF";
	$stat="переплата";
	}
	if($_GET['status']=="otk"){
	$all[]=array($rescher.",");
	$colorgraf="#FB9C9C";
	$stat="отказ";
	}
	if($_GET['status']=="cher"){
	$all[]=array($resotk.",");
	$colorgraf="#BC9B79";
	$stat="черн-ик";
	}
	?>

	<?} ?>
</div>	

	<script>
var chartData = {
    labels: [<?for($i = 1;$i<=count($monlist);$i++){
		if($i<10)
		  {
			  $month="0".$i.",";
		  }
		  else
		  {
			$month=$i.",";  
		  }
		  echo $month;
}?>],
        datasets: [
            {
				label:'<?echo $stat;?>',
                fillColor: "<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>,<?echo $colorgraf;?>",
                strokeColor: "<?echo $colorgraf;?>",
				backgroundColor: ['<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>','<?echo $colorgraf;?>'],
                data: [<?
				for($i=0;$i<count($all);$i++){
					$e=implode(",", $all[$i]);print_r($e);
					}
					
					?>]
            }
        ]
    };

var opt = {
    events: false,
    tooltips: {
        enabled: false
    },
    hover: {
        animationDuration: 0,
		enabled: false
    },
	scales: {
            yAxes: [{
                ticks: {
                    min: 0
                }
            }]
        },
    animation: {
        duration: 1,
        onComplete: function () {
            var chartInstance = this.chart,
                ctx = chartInstance.ctx;
            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';

            this.data.datasets.forEach(function (dataset, i) {
                var meta = chartInstance.controller.getDatasetMeta(i);
                meta.data.forEach(function (bar, index) {
                    var data = dataset.data[index];                            
                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                });
            });
        }
    }
};
 var ctx = document.getElementById("myCharti<?echo $_GET['yg'];?>"),
     myLineChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
           options: opt
     });
	
</script>