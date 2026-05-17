<?
include 'conf.php';
$numberdol=0;
$numberopl=0;
$numbergd=0;
$numbernap=0;
$numberotkl=0;
$numberprov=0;
$numberpos=0;
$numberkkt=0;
$numberkktk=0;
$numbervie=0;
$numberust=0;
$numbervust=0;
$numberpol=0;
$numberpolo=0;
$numbervoz=0;
$numbercha=0;
$numbernaa=0;
$numberpust=0;
$numberpere=0;
	$rcher = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status,schet.akt,schet.otk,schet.cher FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE uslugi.id='".$_GET['orgnn']."' and schet.del!='1'and schet.y='".$_GET['yn']."' and schet.m='".$_GET['mothn']."'  group by schet.rand order by schet.status asc");
while($rescher = mysql_fetch_assoc($rcher))  : 
if($rescher['akt']=="0"&&$rescher['cher']=="0"&&$rescher['otk']=="0"){

	if($rescher['status']==""&&$rescher['oplachenks']!="1")
{
	$numberdol++;
	$alldol[] = array($numberdol);
	$alldolldata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$alldolns[]=array($rescher['ns']." ");
	$alldolinn[]=array($rescher['inn']." ");
	$alldolkpp[]=array($rescher['kpp']." ");
	$alldologrn[]=array($rescher['ogrn']." ");
	$alldolname[]=array($rescher['name']." ");
	$alldolpriceks[]=array($rescher['priceks']." ");
	$alldolprice[]=array($rescher['price']." ");
	$alldolstatus="Не оплачен";
	$alldolkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
	$alldolrand[]=array($rescher['rand']);
	$alldolidkli[]=array($rescher['idkli']);
	$alldollico[]=array($rescher['lico']);
	$alldolgr[]=array($rescher['gr']);
	$alldolprodukti[]=array($rescher['produkt']);
	$alldolurl[]=array($rescher['url']);
	$ncolordol="#78AFD8";
}
	if($rescher['status']==""&&$rescher['oplachenks']=="1")
{
	$numberopl++;
	$allopl[] = array($numberopl);
	$allopldata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$alloplns[]=array($rescher['ns']." ");
	$alloplinn[]=array($rescher['inn']." ");
	$alloplkpp[]=array($rescher['kpp']." ");
	$alloplogrn[]=array($rescher['ogrn']." ");
	$alloplname[]=array($rescher['name']." ");
	$alloplpriceks[]=array($rescher['priceks']." ");
	$alloplprice[]=array($rescher['price']." ");
	$alloplstatus="Оплачен";
	$alloplkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
	$alloplrand[]=array($rescher['rand']);
	$alloplidkli[]=array($rescher['idkli']);
	$allopllico[]=array($rescher['lico']);
	$alloplgr[]=array($rescher['gr']);
	$alloplprodukti[]=array($rescher['produkt']);
	$alloplurl[]=array($rescher['url']);
	$ncoloropl="#FFF850";
}
	if($rescher['status']=="1"||$rescher['status']=="44"||$rescher['status']=="35")
{
	$numbergd++;
	$allgd[] = array($numbergd);
	$allgddata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allgdns[]=array($rescher['ns']." ");
	$allgdinn[]=array($rescher['inn']." ");
	$allgdkpp[]=array($rescher['kpp']." ");
	$allgdogrn[]=array($rescher['ogrn']." ");
	$allgdname[]=array($rescher['name']." ");
	$allgdpriceks[]=array($rescher['priceks']." ");
	$allgdprice[]=array($rescher['price']." ");

    if($rescher['status']=="1" ) {
        $allgdstatus="Ждем доки";
        $ncolorgd="#FFF850";
    }
    if($rescher['status']=="44" ) {
        $allgdstatus = "оплата тс";
        $ncolorgd="#FFF850";
    }
    if($rescher['status']=="35" ) {
        $allgdstatus = "заявка";
        $ncolorgd="#FFF850";
    }
	$allgdkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
		$allgdrand[]=array($rescher['rand']);
	$allgdidkli[]=array($rescher['idkli']);
	$allgdlico[]=array($rescher['lico']);
	$allgdgr[]=array($rescher['gr']);
	$allgdprodukti[]=array($rescher['produkt']);
	$allgdurl[]=array($rescher['url']);

}
	if($rescher['status']=="2" || $rescher['status']=="45"||$rescher['status']=="36")
{
	$numbernap++;
	$allnap[] = array($numbernap);
	$allnapdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allnapns[]=array($rescher['ns']." ");
	$allnapinn[]=array($rescher['inn']." ");
	$allnapkpp[]=array($rescher['kpp']." ");
	$allnapogrn[]=array($rescher['ogrn']." ");
	$allnapname[]=array($rescher['name']." ");
	$allnappriceks[]=array($rescher['priceks']." ");
	$allnapprice[]=array($rescher['price']." ");

    if($rescher['status']=="2" ) {
        $allnapstatus="На проверке";
        $ncolornap="#FFF850";
    }
    if($rescher['status']=="45" ) {
        $allnapstatus = "ждем ккт";
        $ncolornap="#FFF850";
    }
    if($rescher['status']=="36" ) {
        $allnapstatus = "регистрация и настройка";
        $ncolornap="#FFF850";
    }
	$allnapkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
		$allnaprand[]=array($rescher['rand']);
	$allnapidkli[]=array($rescher['idkli']);
	$allnaplico[]=array($rescher['lico']);
	$allnapgr[]=array($rescher['gr']);
	$allnapprodukti[]=array($rescher['produkt']);
	$allnapurl[]=array($rescher['url']);

}
	if($rescher['status']=="3" || $rescher['status']=="47"||$rescher['status']=="37")
{
	$numberotkl++;
	$allotkl[] = array($numberotkl);
	$allotkldata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allotklns[]=array($rescher['ns']." ");
	$allotklinn[]=array($rescher['inn']." ");
	$allotklkpp[]=array($rescher['kpp']." ");
	$allotklogrn[]=array($rescher['ogrn']." ");
	$allotklname[]=array($rescher['name']." ");
	$allotklpriceks[]=array($rescher['priceks']." ");
	$allotklprice[]=array($rescher['price']." ");

    if($rescher['status']=="3" ) {
        $allotklstatus="Отклонен";
        $ncolorotkl="#FFF850";
    }
    if($rescher['status']=="47" ) {
        $allotklstatus = "Товар получен";
        $ncolorotkl="#FFF850";
    }
    if($rescher['status']=="37" ) {
        $allotklstatus = "ждем опись";
        $ncolorotkl="#FFF850";
    }
	$allotklkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
		$allotklrand[]=array($rescher['rand']);
	$allotklidkli[]=array($rescher['idkli']);
	$allotkllico[]=array($rescher['lico']);
	$allotklgr[]=array($rescher['gr']);
	$allotklprodukti[]=array($rescher['produkt']);
	$allotklurl[]=array($rescher['url']);

}
	if($rescher['status']=="4" || $rescher['status']=="48"||$rescher['status']=="38")
{
	$numberprov++;
	$allprov[] = array($numberprov);
	$allprovdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allprovns[]=array($rescher['ns']." ");
	$allprovinn[]=array($rescher['inn']." ");
	$allprovkpp[]=array($rescher['kpp']." ");
	$allprovogrn[]=array($rescher['ogrn']." ");
	$allprovname[]=array($rescher['name']." ");
	$allprovpriceks[]=array($rescher['priceks']." ");
	$allprovprice[]=array($rescher['price']." ");

    if($rescher['status']=="4" ) {
        $allprovstatus="Проверен";
        $ncolorprov="#FFF850";
    }
    if($rescher['status']=="48" ) {
        $allprovstatus = "Товар получен без фн";
        $ncolorprov="#FFF850";
    }
    if($rescher['status']=="38" ) {
        $allprovstatus = "опись принята";
        $ncolorprov="#FFF850";
    }
	$allprovkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
		$allprovrand[]=array($rescher['rand']);
	$allprovidkli[]=array($rescher['idkli']);
	$allprovlico[]=array($rescher['lico']);
	$allprovgr[]=array($rescher['gr']);
	$allprovprodukti[]=array($rescher['produkt']);
	$allprovurl[]=array($rescher['url']);

}
	if($rescher['status']=="5"|| $rescher['status']=="49"||$rescher['status']=="39")
{
	$numberpos++;
	$allpos[] = array($numberpos);
	$allposdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allposns[]=array($rescher['ns']." ");
	$allposinn[]=array($rescher['inn']." ");
	$allposkpp[]=array($rescher['kpp']." ");
	$allposogrn[]=array($rescher['ogrn']." ");
	$allposname[]=array($rescher['name']." ");
	$allpospriceks[]=array($rescher['priceks']." ");
	$allposprice[]=array($rescher['price']." ");

    if($rescher['status']=="5" ) {
        $allposstatus="Поставка";
        $ncolorpos="#E9C3FB";
    }
    if($rescher['status']=="49" ) {
        $allposstatus = "на продлении";
        $ncolorpos="#E9C3FB";
    }
    if($rescher['status']=="39" ) {
        $allposstatus = "опсиь передана менеджер";
        $ncolorpos="#E9C3FB";
    }
	$allposkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allposrand[]=array($rescher['rand']);
	$allposidkli[]=array($rescher['idkli']);
	$allposlico[]=array($rescher['lico']);
	$allposgr[]=array($rescher['gr']);
	$allposprodukti[]=array($rescher['produkt']);
	$allposurl[]=array($rescher['url']);

}
	if($rescher['status']=="6"|| $rescher['status']=="50"||$rescher['status']=="40")
{
	$numberkkt++;
	$allkkt[] = array($numberkkt);
	$allkktdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allkktns[]=array($rescher['ns']." ");
	$allkktinn[]=array($rescher['inn']." ");
	$allkktkpp[]=array($rescher['kpp']." ");
	$allkktogrn[]=array($rescher['ogrn']." ");
	$allkktname[]=array($rescher['name']." ");
	$allkktpriceks[]=array($rescher['priceks']." ");
	$allkktprice[]=array($rescher['price']." ");
    if($rescher['status']=="6" ) {
        $allkktstatus="ожидан.ккт";
        $ncolorkkt="#85D6D1";
    }
    if($rescher['status']=="50" ) {
        $allkktstatus = "устан.в офисе";
        $ncolorkkt="#85D6D1";
    }
    if($rescher['status']=="40" ) {
        $allkktstatus = "отправить в гс1";
        $ncolorkkt="#85D6D1";
    }
	$allkktkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allkktrand[]=array($rescher['rand']);
	$allkktidkli[]=array($rescher['idkli']);
	$allkktlico[]=array($rescher['lico']);
	$allkktgr[]=array($rescher['gr']);
	$allkktprodukti[]=array($rescher['produkt']);
	$allkkturl[]=array($rescher['url']);

}
	if($rescher['status']=="7"|| $rescher['status']=="51"||$rescher['status']=="41")
{
	$numberkktk++;
	$allkktk[] = array($numberkktk);
	$allkktkdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allkktkns[]=array($rescher['ns']." ");
	$allkktkinn[]=array($rescher['inn']." ");
	$allkktkkpp[]=array($rescher['kpp']." ");
	$allkktkogrn[]=array($rescher['ogrn']." ");
	$allkktkname[]=array($rescher['name']." ");
	$allkktkpriceks[]=array($rescher['priceks']." ");
	$allkktkprice[]=array($rescher['price']." ");

    if($rescher['status']=="7" ) {
        $allkktkstatus="ожидан ккт клиента";
        $ncolorkktk="#85D6D1";
    }
    if($rescher['status']=="51" ) {
        $allkktkstatus = "выезд";
        $ncolorkktk="#85D6D1";
    }
    if($rescher['status']=="41" ) {
        $allkktkstatus = "ждем киз";
        $ncolorkktk="#85D6D1";
    }
	$allkktkkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allkktkrand[]=array($rescher['rand']);
	$allkktkidkli[]=array($rescher['idkli']);
	$allkktklico[]=array($rescher['lico']);
	$allkktkgr[]=array($rescher['gr']);
	$allkktkprodukti[]=array($rescher['produkt']);
	$allkktkurl[]=array($rescher['url']);

}
	if($rescher['status']=="16"|| $rescher['status']=="52"||$rescher['status']=="42")
{
	$numbervie++;
	$allvie[] = array($numbervie);
	$allviedata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allviens[]=array($rescher['ns']." ");
	$allvieinn[]=array($rescher['inn']." ");
	$allviekpp[]=array($rescher['kpp']." ");
	$allvieogrn[]=array($rescher['ogrn']." ");
	$allviename[]=array($rescher['name']." ");
	$allviepriceks[]=array($rescher['priceks']." ");
	$allvieprice[]=array($rescher['price']." ");

    if($rescher['status']=="16" ) {
        $allviestatus="выезд";
        $ncolorvie="#FFF850";
    }
    if($rescher['status']=="52" ) {
        $allviestatus = "выдали";
        $ncolorvie="#FFF850";
    }
    if($rescher['status']=="42" ) {
        $allviestatus = "маркировка киз без оборудовани";
        $ncolorvie="#FFF850";
    }
	$allviekto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allvierand[]=array($rescher['rand']);
	$allvieidkli[]=array($rescher['idkli']);
	$allvielico[]=array($rescher['lico']);
	$allviegr[]=array($rescher['gr']);
	$allvieprodukti[]=array($rescher['produkt']);
	$allvieurl[]=array($rescher['url']);

}
	if($rescher['status']=="17"|| $rescher['status']=="43"||$rescher['status']=="53")
{
	$numberust++;
	$allust[] = array($numberust);
	$allustdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allustns[]=array($rescher['ns']." ");
	$allustinn[]=array($rescher['inn']." ");
	$allustkpp[]=array($rescher['kpp']." ");
	$allustogrn[]=array($rescher['ogrn']." ");
	$allustname[]=array($rescher['name']." ");
	$allustpriceks[]=array($rescher['priceks']." ");
	$allustprice[]=array($rescher['price']." ");
    if($rescher['status']=="17" ) {
        $alluststatus="устан.в офисе";
        $ncolorust="#FFB366";
    }
    if($rescher['status']=="53" ) {
        $alluststatus = "ждем закрывающие документы";
        $ncolorust="#FFB366";
    }
    if($rescher['status']=="43" ) {
        $alluststatus = "маркировка киз с оборудовани";
        $ncolorust="#FFB366";
    }
	$allustkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allustrand[]=array($rescher['rand']);
	$allustidkli[]=array($rescher['idkli']);
	$allustlico[]=array($rescher['lico']);
	$allustgr[]=array($rescher['gr']);
	$allustprodukti[]=array($rescher['produkt']);
	$allusturl[]=array($rescher['url']);

}
	if($rescher['status']=="18")
{
	$numbervust++;
	$allvust[] = array($numbervust);
	$allvustdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allvustns[]=array($rescher['ns']." ");
	$allvustinn[]=array($rescher['inn']." ");
	$allvustkpp[]=array($rescher['kpp']." ");
	$allvustogrn[]=array($rescher['ogrn']." ");
	$allvustname[]=array($rescher['name']." ");
	$allvustpriceks[]=array($rescher['priceks']." ");
	$allvustprice[]=array($rescher['price']." ");
	$allvuststatus="выехали на установ";
	$allvustkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allvustrand[]=array($rescher['rand']);
	$allvustidkli[]=array($rescher['idkli']);
	$allvustlico[]=array($rescher['lico']);
	$allvustgr[]=array($rescher['gr']);
	$allvustprodukti[]=array($rescher['produkt']);
	$allvusturl[]=array($rescher['url']);
	$ncolorvust="#FFB366";
}
	if($rescher['status']=="19")
{
	$numberpol++;
	$allpol[] = array($numberpol);
	$allpoldata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allpolns[]=array($rescher['ns']." ");
	$allpolinn[]=array($rescher['inn']." ");
	$allpolkpp[]=array($rescher['kpp']." ");
	$allpologrn[]=array($rescher['ogrn']." ");
	$allpolname[]=array($rescher['name']." ");
	$allpolpriceks[]=array($rescher['priceks']." ");
	$allpolprice[]=array($rescher['price']." ");
	$allpolstatus="полу-ние в лк";
	$allpolkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allpolrand[]=array($rescher['rand']);
	$allpolidkli[]=array($rescher['idkli']);
	$allpollico[]=array($rescher['lico']);
	$allpolgr[]=array($rescher['gr']);
	$allpolprodukti[]=array($rescher['produkt']);
	$allpolurl[]=array($rescher['url']);
	$ncolorpol="#FFF850";
}
	if($rescher['status']=="20")
{
	$numberpolo++;
	$allpolo[] = array($numberpolo);
	$allpolodata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allpolons[]=array($rescher['ns']." ");
	$allpoloinn[]=array($rescher['inn']." ");
	$allpolokpp[]=array($rescher['kpp']." ");
	$allpoloogrn[]=array($rescher['ogrn']." ");
	$allpoloname[]=array($rescher['name']." ");
	$allpolopriceks[]=array($rescher['priceks']." ");
	$allpoloprice[]=array($rescher['price']." ");
	$allpolostatus="получ-ие в офисе";
	$allpolokto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allpolorand[]=array($rescher['rand']);
	$allpoloidkli[]=array($rescher['idkli']);
	$allpololico[]=array($rescher['lico']);
	$allpologr[]=array($rescher['gr']);
	$allpoloprodukti[]=array($rescher['produkt']);
	$allpolourl[]=array($rescher['url']);
	$ncolorpolo="#FFF850";
}
	if($rescher['status']=="23" ||$rescher['status']=="12356")
{
	$numbervoz++;
	$allvoz[] = array($numbervoz);
	$allvozdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allvozns[]=array($rescher['ns']." ");
	$allvozinn[]=array($rescher['inn']." ");
	$allvozkpp[]=array($rescher['kpp']." ");
	$allvozogrn[]=array($rescher['ogrn']." ");
	$allvozname[]=array($rescher['name']." ");
	$allvozpriceks[]=array($rescher['priceks']." ");
	$allvozprice[]=array($rescher['price']." ");
	$allvozstatus="возврат";
	$allvozkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allvozrand[]=array($rescher['rand']);
	$allvozidkli[]=array($rescher['idkli']);
	$allvozlico[]=array($rescher['lico']);
	$allvozgr[]=array($rescher['gr']);
	$allvozprodukti[]=array($rescher['produkt']);
	$allvozurl[]=array($rescher['url']);
	$ncolorvoz="#E76D74";
}
	if($rescher['status']=="21")
{
	$numbercha++;
	$allcha[] = array($numbercha);
	$allchadata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allchans[]=array($rescher['ns']." ");
	$allchainn[]=array($rescher['inn']." ");
	$allchakpp[]=array($rescher['kpp']." ");
	$allchaogrn[]=array($rescher['ogrn']." ");
	$allchaname[]=array($rescher['name']." ");
	$allchapriceks[]=array($rescher['priceks']." ");
	$allchaprice[]=array($rescher['price']." ");
	$allchastatus="частич-о на отгрузке";
	$allchakto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allcharand[]=array($rescher['rand']);
	$allchaidkli[]=array($rescher['idkli']);
	$allchalico[]=array($rescher['lico']);
	$allchagr[]=array($rescher['gr']);
	$allchaprodukti[]=array($rescher['produkt']);
	$allchaurl[]=array($rescher['url']);
	$ncolorcha="#E9C3FB";
}
	if($rescher['status']=="65" || $rescher['status']=="60"|| $rescher['status']=="77")
{
	$numbernaa++;
	$allnaa[] = array($numbernaa);
	$allnaadata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allnaans[]=array($rescher['ns']." ");
	$allnaainn[]=array($rescher['inn']." ");
	$allnaakpp[]=array($rescher['kpp']." ");
	$allnaaogrn[]=array($rescher['ogrn']." ");
	$allnaaname[]=array($rescher['name']." ");
	$allnaapriceks[]=array($rescher['priceks']." ");
	$allnaaprice[]=array($rescher['price']." ");
	$allnaastatus="на отгрузке";
	$allnaakto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allnaarand[]=array($rescher['rand']);
	$allnaaidkli[]=array($rescher['idkli']);
	$allnaalico[]=array($rescher['lico']);
	$allnaagr[]=array($rescher['gr']);
	$allnaaprodukti[]=array($rescher['produkt']);
	$allnaaurl[]=array($rescher['url']);
	$ncolornaa="#E9C3FB";
}
	if($rescher['status']=="161")
{
	$numberpust++;
	$allpust[] = array($numberpust);
	$allpustdata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allpustns[]=array($rescher['ns']." ");
	$allpustinn[]=array($rescher['inn']." ");
	$allpustkpp[]=array($rescher['kpp']." ");
	$allpustogrn[]=array($rescher['ogrn']." ");
	$allpustname[]=array($rescher['name']." ");
	$allpustpriceks[]=array($rescher['priceks']." ");
	$allpustprice[]=array($rescher['price']." ");
	$allpuststatus = "произв-я устан-ка";
	$allpustkto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allpustrand[]=array($rescher['rand']);
	$allpustidkli[]=array($rescher['idkli']);
	$allpustlico[]=array($rescher['lico']);
	$allpustgr[]=array($rescher['gr']);
	$allpustprodukti[]=array($rescher['produkt']);
	$allpusturl[]=array($rescher['url']);
	$ncolorpust="#FFB366";
}
	if($rescher['status']=="12354"||$rescher['status']=="12355")
{
	$numberpere++;
	$allpere[] = array($numberpere);
	$allperedata[]=array($rescher['d'].".".$rescher['m'].".".$rescher['y']);
	$allperens[]=array($rescher['ns']." ");
	$allpereinn[]=array($rescher['inn']." ");
	$allperekpp[]=array($rescher['kpp']." ");
	$allpereogrn[]=array($rescher['ogrn']." ");
	$allperename[]=array($rescher['name']." ");
	$allperepriceks[]=array($rescher['priceks']." ");
	$allpereprice[]=array($rescher['price']." ");
	$allperestatus="переплата";
	$allperekto[]=array( $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.');
    $allpererand[]=array($rescher['rand']);
	$allpereidkli[]=array($rescher['idkli']);
	$allperelico[]=array($rescher['lico']);
	$allperegr[]=array($rescher['gr']);
	$allpereprodukti[]=array($rescher['produkt']);
	$allpereurl[]=array($rescher['url']);
	$ncolorpere="#A0D7FF";
}
}
endwhile;
?>

<table id='neobs'>
<thead>
<tr>
<th>№</th>
<th>дата счета</th>
<th>№счета</th>
<th>инн</th>
<th>кпп</th>
<th style="
    width: 40%;
">наименование</th>
<th style="
    width: 15%;
">продукт</th>
<th>S</th>
<th>K</th>
<th style="
    width: 10%;
">Статус</th>
<th style="
    width: 7%;
">Выставил</th>
<th><img src="/img/qwerty.png"></th>
<th><img src="/img/tablsc.png"></th>
<th><img src="/img/ship.png"style="
    width: 20px;
"></th>
</tr>
</thead>
<tbody>
<?for($i=0;$i<count($alldol);$i++){?>
<tr>
<td><?$e=implode(" ", $alldol[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolordol;?>"><?$e=implode(" ", $alldolldata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolordol;?>"><?$e=implode(" ", $alldolns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $alldolinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $alldolkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $alldologrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $alldolname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolordol;?>"><?$e=implode(" ", $alldolprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolordol;?>"><?$e=implode(" ", $alldolpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolordol;?>"><?echo $alldolstatus;?></td>
<td><?$e=implode(" ", $alldolkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $alldolrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $alldologrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $alldolidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $alldollico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $alldolgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $alldolns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $alldolprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $alldolinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $alldolidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $alldolurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allopl);$i++){?>
<tr>
<td><?$e=implode(" ", $allopl[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncoloropl;?>"><?$e=implode(" ", $allopldata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncoloropl;?>"><?$e=implode(" ", $alloplns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $alloplinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $alloplkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $alloplogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $alloplname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncoloropl;?>"><?$e=implode(" ", $alloplprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncoloropl;?>"><?$e=implode(" ", $alloplpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncoloropl;?>"><?echo $alloplstatus;?></td>
<td><?$e=implode(" ", $alloplkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $alloplrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $alloplogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $alloplidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allopllico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $alloplgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $alloplns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $alloplprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $alloplinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $alloplidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $alloplurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allgd);$i++){?>
<tr>
<td><?$e=implode(" ", $allgd[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorgd;?>"><?$e=implode(" ", $allgddata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorgd;?>"><?$e=implode(" ", $allgdns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allgdinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allgdkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allgdogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allgdname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorgd;?>"><?$e=implode(" ", $allgdprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorgd;?>"><?$e=implode(" ", $allgdpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorgd;?>"><?echo $allgdstatus;?></td>
<td><?$e=implode(" ", $allgdkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allgdrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allgdogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allgdidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allgdlico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allgdgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allgdns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allgdprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allgdinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allgdidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allgdurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allnap);$i++){?>
<tr>
<td><?$e=implode(" ", $allnap[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornap;?>"><?$e=implode(" ", $allnapdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornap;?>"><?$e=implode(" ", $allnapns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allnapinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allnapkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allnapogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allnapname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornap;?>"><?$e=implode(" ", $allnapprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornap;?>"><?$e=implode(" ", $allnappriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornap;?>"><?echo $allnapstatus;?></td>
<td><?$e=implode(" ", $allnapkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allnaprand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allnapogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allnapidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allnaplico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allnapgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allnapns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allnapprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allnapinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allnapidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allnapurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allotkl);$i++){?>
<tr>
<td><?$e=implode(" ", $allotkl[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorotkl;?>"><?$e=implode(" ", $allotkldata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorotkl;?>"><?$e=implode(" ", $allotklns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allotklinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allotklkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allotklogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allotklname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorotkl;?>"><?$e=implode(" ", $allotklprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorotkl;?>"><?$e=implode(" ", $allotklpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorotkl;?>"><?echo $allotklstatus;?></td>
<td><?$e=implode(" ", $allotklkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allotklrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allotklogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allotklidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allotkllico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allotklgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allotklns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allotklprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allotklinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allotklidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allotklurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allprov);$i++){?>
<tr>
<td><?$e=implode(" ", $allprov[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorprov;?>"><?$e=implode(" ", $allprovdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorprov;?>"><?$e=implode(" ", $allprovns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allprovinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allprovkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allprovogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allprovname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorprov;?>"><?$e=implode(" ", $allprovprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorprov;?>"><?$e=implode(" ", $allprovpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorprov;?>"><?echo $allprovstatus;?></td>
<td><?$e=implode(" ", $allprovkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allprovrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allprovogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allprovidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allprovlico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allprovgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allprovns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allprovprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allprovinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allprovidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allprovurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allpos);$i++){?>
<tr>
<td><?$e=implode(" ", $allpos[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpos;?>"><?$e=implode(" ", $allposdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpos;?>"><?$e=implode(" ", $allposns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allposinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allposkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allposogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allposname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpos;?>"><?$e=implode(" ", $allposprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpos;?>"><?$e=implode(" ", $allpospriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpos;?>"><?echo $allposstatus;?></td>
<td><?$e=implode(" ", $allposkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allposrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allposogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allposidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allposlico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allposgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allposns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allposprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allposinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allposidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allposurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allkkt);$i++){?>
<tr>
<td><?$e=implode(" ", $allkkt[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkkt;?>"><?$e=implode(" ", $allkktdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkkt;?>"><?$e=implode(" ", $allkktns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allkktinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allkktkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allkktogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allkktname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkkt;?>"><?$e=implode(" ", $allkktprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkkt;?>"><?$e=implode(" ", $allkktpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkkt;?>"><?echo $allkktstatus;?></td>
<td><?$e=implode(" ", $allkktkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allkktrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allkktogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allkktidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allkktlico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allkktgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allkktns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allkktprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allkktinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allkktidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allkkturl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allkktk);$i++){?>
<tr>
<td><?$e=implode(" ", $allkktk[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkktk;?>"><?$e=implode(" ", $allkktkdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkktk;?>"><?$e=implode(" ", $allkktkns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allkktkinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allkktkkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allkktkogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allkktkname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkktk;?>"><?$e=implode(" ", $allkktkprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkktk;?>"><?$e=implode(" ", $allkktkpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorkktk;?>"><?echo $allkktkstatus;?></td>
<td><?$e=implode(" ", $allkktkkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allkktkrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allkktkogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allkktkidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allkktklico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allkktkgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allkktkns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allkktkprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allkktkinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allkktkidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allkktkurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allvie);$i++){?>
<tr>
<td><?$e=implode(" ", $allvie[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvie;?>"><?$e=implode(" ", $allviedata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvie;?>"><?$e=implode(" ", $allviens[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvieinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allviekpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvieogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allviename[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvie;?>"><?$e=implode(" ", $allvieprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvie;?>"><?$e=implode(" ", $allviepriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvie;?>"><?echo $allviestatus;?></td>
<td><?$e=implode(" ", $allviekto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allvierand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allvieogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allvieidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allvielico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allviegr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allviens[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allvieprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allvieinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allvieidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allvieurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allust);$i++){?>
<tr>
<td><?$e=implode(" ", $allust[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorust;?>"><?$e=implode(" ", $allustdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorust;?>"><?$e=implode(" ", $allustns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allustinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allustkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allustogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allustname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorust;?>"><?$e=implode(" ", $allustprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorust;?>"><?$e=implode(" ", $allustpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvust;?>"><?echo $alluststatus;?></td>
<td><?$e=implode(" ", $allustkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allustrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allustogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allustidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allustlico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allustgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allustns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allustprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allustinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allustidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allusturl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allvust);$i++){?>
<tr>
<td><?$e=implode(" ", $allvust[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvust;?>"><?$e=implode(" ", $allvustdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvust;?>"><?$e=implode(" ", $allvustns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvustinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvustkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvustogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvustname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvust;?>"><?$e=implode(" ", $allvustprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvust;?>"><?$e=implode(" ", $allvustpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvust;?>"><?echo $allvuststatus;?></td>
<td><?$e=implode(" ", $allvustkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allvustrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allvustogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allvustidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allvustlico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allvustgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allvustns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allvustprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allvustinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allvustidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allvusturl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allpol);$i++){?>
<tr>
<td><?$e=implode(" ", $allpol[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpol;?>"><?$e=implode(" ", $allpoldata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpol;?>"><?$e=implode(" ", $allpolns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpolinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpolkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpologrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpolname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpol;?>"><?$e=implode(" ", $allpolprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpol;?>"><?$e=implode(" ", $allpolpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpol;?>"><?echo $allpolstatus;?></td>
<td><?$e=implode(" ", $allpolkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allpolrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allpologrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allpolidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allpollico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allpolgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allpolns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allpolprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allpolinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allpolidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allpolurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allpolo);$i++){?>
<tr>
<td><?$e=implode(" ", $allpolo[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpolo;?>"><?$e=implode(" ", $allpolodata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpolo;?>"><?$e=implode(" ", $allpolons[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpoloinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpolokpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpoloogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpoloname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpolo;?>"><?$e=implode(" ", $allpoloprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpolo;?>"><?$e=implode(" ", $allpolopriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpolo;?>"><?echo $allpolostatus;?></td>
<td><?$e=implode(" ", $allpolokto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allpolorand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allpoloogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allpoloidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allpololico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allpologr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allpolons[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allpoloprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allpoloinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allpoloidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allpolourl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allvoz);$i++){?>
<tr>
<td><?$e=implode(" ", $allvoz[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvoz;?>"><?$e=implode(" ", $allvozdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvoz;?>"><?$e=implode(" ", $allvozns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvozinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvozkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvozogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allvozname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvoz;?>"><?$e=implode(" ", $allvozprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvoz;?>"><?$e=implode(" ", $allvozpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorvoz;?>"><?echo $allvozstatus;?></td>
<td><?$e=implode(" ", $allvozkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allvozrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allvozogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allvozidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allvozlico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allvozgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allvozns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allvozprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allvozinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allvozidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allvozurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allcha);$i++){?>
<tr>
<td><?$e=implode(" ", $allcha[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorcha;?>"><?$e=implode(" ", $allchadata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorcha;?>"><?$e=implode(" ", $allchans[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allchainn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allchakpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allchaogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allchaname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorcha;?>"><?$e=implode(" ", $allchaprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorcha;?>"><?$e=implode(" ", $allchapriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorcha;?>"><?echo $allchastatus;?></td>
<td><?$e=implode(" ", $allchakto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allcharand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allchaogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allchaidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allchalico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allchagr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allchans[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allchaprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allchainn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allchaidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allchaurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allnaa);$i++){?>
<tr>
<td><?$e=implode(" ", $allnaa[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornaa;?>"><?$e=implode(" ", $allnaadata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornaa;?>"><?$e=implode(" ", $allnaans[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allnaainn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allnaakpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allnaaogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allnaaname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornaa;?>"><?$e=implode(" ", $allnaaprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornaa;?>"><?$e=implode(" ", $allnaapriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolornaa;?>"><?echo $allnaastatus;?></td>
<td><?$e=implode(" ", $allnaakto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allnaarand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allnaaogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allnaaidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allnaalico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allnaagr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allnaans[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allnaaprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allnaainn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allnaaidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allnaaurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allpust);$i++){?>
<tr>
<td><?$e=implode(" ", $allpust[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpust;?>"><?$e=implode(" ", $allpustdata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpust;?>"><?$e=implode(" ", $allpustns[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpustinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpustkpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpustogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpustname[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpust;?>"><?$e=implode(" ", $allpustprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpust;?>"><?$e=implode(" ", $allpustpriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpust;?>"><?echo $allpuststatus;?></td>
<td><?$e=implode(" ", $allpustkto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allpustrand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allpustogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allpustidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allpustlico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allpustgr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allpustns[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allpustprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allpustinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allpustidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allpusturl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
<?for($i=0;$i<count($allpere);$i++){?>
<tr>
<td><?$e=implode(" ", $allpere[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpere;?>"><?$e=implode(" ", $allperedata[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpere;?>"><?$e=implode(" ", $allperens[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpereinn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allperekpp[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allpereogrn[$i]);print_r($e);?></td>
<td><?$e=implode(" ", $allperename[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpere;?>"><?$e=implode(" ", $allpereprice[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpere;?>"><?$e=implode(" ", $allperepriceks[$i]);print_r($e);?></td>
<td style="background-color:<?echo $ncolorpere;?>"><?echo $allperestatus;?></td>
<td><?$e=implode(" ", $allperekto[$i]);print_r($e);?></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<?$e=implode(" ", $allpererand[$i]);print_r($e);?>&p=0&ogrn=<?$e=implode(" ", $allpereogrn[$i]);print_r($e);?>&kli=<?$e=implode(" ", $allpereidkli[$i]);print_r($e);?>&lico=<?$e=implode(" ", $allperelico[$i]);print_r($e);?>&gr=<?$e=implode(" ", $allperegr[$i]);print_r($e);?>&nomerschet=<?$e=implode(" ", $allperens[$i]);print_r($e);?>&produkt=<?$e=implode(" ", $allpereprodukti[$i]);print_r($e);?>&inn=<?$e=implode(" ", $allpereinn[$i]);print_r($e);?>"><img src="/img/qwerty.png"></a></td>
<td><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<?$e=implode(" ", $allpereidkli[$i]);print_r($e);?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?$e=implode(" ", $allpereurl[$i]);print_r($e);?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>					
<?}?>
</tbody>