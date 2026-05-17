<?
include 'conf.php';

setcookie('ogrnl',$_GET['ogrnl'],time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
setcookie('usersi',$_GET['users'],time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
?>
<?
$summmon=$_GET['mon']+1;
$df = new DateTime('first day of this month');
$dl = new DateTime('last day of this month');
$fist=$df->format('Y-'.$summmon.'-0j');
$last=$dl->format('Y-'.$summmon.'-j');
$year=date("Y");
if($_GET['ogrnl']=="0")
{
    $uslugiogrn="";
}
if($_GET['ogrnl']!="0")
{
    $uslugiogrn="uslugi.id='".$_GET['ogrnl']."' and";
}
$r = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.del!='1'and  schet.tipprod!=''and (schet.price!='' or schet.price!='0') and  schet.tipprod!='Нет' and schet.cher='0' and schet.kto='".$_GET['users']."' and (schet.dataprod BETWEEN'$fist' and '$last' or schet.datasert BETWEEN '$fist'and '$last') group by schet.ns");
$res = mysql_num_rows($r) ;
?>
<?
/*
$ri = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id  WHERE $uslugiogrn schet.del!='1' and schet.shetold!='' and (schet.price!='' or schet.price!='0')and schet.kto='".$_GET['users']."'  and (  schet.m BETWEEN'".$_GET['mon']."' and '".$_GET['mon']."' and schet.y BETWEEN '$year'and '$year')group by schet.ns");
$resi = mysql_num_rows($ri) ;
if($_GET['ogrnl']!="22"&&$_GET['ogrnl']!="24")
{
    $status="(schet.status = '' or schet.status = '1'or schet.status = '2'or schet.status = '3'or schet.status = '4'or schet.status = '6'or schet.status = '7'or schet.status = '16'or schet.status = '19'or schet.status = '20' or schet.status = '17' or schet.status='18' or schet.status='161' or schet.status = '21' or schet.status='65')";
}
if($_GET['ogrnl']=="22")
{
    $status="(schet.status = '' or schet.status = '35'or schet.status = '37'or schet.status = '38'or schet.status = '39'or schet.status = '40'or schet.status = '41'or schet.status = '42'or schet.status = '43' or schet.status = '36' or schet.status = '77' )";
}
if($_GET['ogrnl']=="24")
{
    $status="(schet.status = '' or schet.status = '44'or schet.status = '45'or schet.status = '47'or schet.status = '48'or schet.status = '49' or schet.status = '50' or schet.status = '51' or schet.status = '52' or schet.status = '53'or schet.status = '60' )";
}
$ro = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id  WHERE $uslugiogrn schet.del='0'AND $status and (schet.price!='' or schet.price!='0') AND schet.oplachenks = '1' and schet.shetold!='' and schet.kto='".$_GET['users']."' and (  schet.m BETWEEN'".$_GET['mon']."' and '".$_GET['mon']."' and schet.y BETWEEN '$year'and '$year')group by schet.ns");
$reso = mysql_num_rows($ro) ;
$rotk = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id  WHERE $uslugiogrn schet.del='0'AND $status and (schet.price!='' or schet.price!='0') AND schet.otk = '0' and schet.cher='1' and schet.shetold!='' and schet.kto='".$_GET['users']."' and (  schet.m BETWEEN'".$_GET['mon']."' and '".$_GET['mon']."' and schet.y BETWEEN '$year'and '$year')group by schet.ns");
$resotk = mysql_num_rows($rotk) ;
/*$ri = mysql_query("SELECT schet.ns,COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id  WHERE $uslugiogrn schet.del!='1'AND schet.oplachenks!= '1' and schet.oplachen != '1' and schet.shetold!='' and schet.kto='".$_GET['users']."'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (  schet.m BETWEEN'".$_GET['mon']."' and '".$_GET['mon']."' and schet.y BETWEEN '$year'and '$year')group by schet.ns");
$resi = mysql_num_rows($ri) ;
?>
<?$konotks=0;$konotkks=0; $rgrafis= mysql_query("SELECT Sum(schet.price) as kolsum,Sum(schet.priceks) as kolsumks FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.del!='1' and  schet.tipprod!=''and  schet.tipprod!='Нет' and schet.kto='".$_GET['users']."' and schet.cher='0' and (schet.dataprod BETWEEN'$fist' and '$last' or schet.datasert BETWEEN '$fist'and '$last') group by schet.ns");
while($resgrafis  = mysql_fetch_assoc($rgrafis ))  : $konotks+=$resgrafis['kolsum'];$konotkks+=$resgrafis['kolsumks'];?>
<?endwhile;?>
<!--<div class="row col-xs-12" style="padding-left: 0px;margin-top: 30px;">
<? echo $konotks;?>
<? echo $konotkks;*/?>
       <!-- <div class="col-xs-2" style="padding-left: 0px;width: 10%;margin-left: 30px;">
            <h3 class="text-secondari" >Продление счетов <p>SAVOIR</p></h3>
        </div>
        <div class="col-xs-10" style="padding-left: 0px;height: 60px;margin-left: 30px;width: 85%;padding-right: 0px;">
        <div class="col-xs-9" style="padding-left: 0px;height: 60px;border-right: 1px solid white;background: #C1BBBB;text-align: center;">
            <div style="width: <?$prod=($resi*100)/$res;if(round($prod, 1)>100){$prodc="100"."%";echo $prodc;}else{echo round($prod, 1)."%";}?>;height: 60px;float: left;position: absolute;background: #85D6A7;"title="Выставлено <? echo $resi;?>"></div>
            <div style="width: <?$prodm=($resotk*100)/$res;echo round($prodm, 1)."%";?>;height: 60px;float: left;position: absolute;background: #FB9C9C;"title="Отказов <? echo $resotk;?>"></div>
            <div style="width: <?$prodi=($reso*100)/$res;echo round($prodi, 1)."%";?>;height: 60px;float: left;position: absolute;background: #FFF850;"title="В работе <? echo $reso;?>"></div>
            <div class="col-xs-11" style="padding-left: 0px;height: 60px;border-right: 1px solid white;width: 88%;">
                <div class="col-xs-9" style="padding-left: 0px;height: 60px;border-right: 1px solid white;text-align: center;    width: 62%;">
                    <span class="list-inline-item">-10%</span>
                </div>
                <div class="col-xs-1" style="padding-left: 0px;height: 60px;border-right: 1px solid white;text-align: center;    width: 12.9%;">
                    <span class="list-inline-item">-7%</span>
                </div>
                <div class="col-xs-1" style="padding-left: 0px;height: 60px;border-right: 1px solid white;text-align: center;    width: 12.9%;">
                    <span class="list-inline-item">-6%</span>
                </div>
                <div class="col-xs-1" style="padding-left: 0px;height: 60px;text-align: center;padding-left: 50px">
                    <span class="list-inline-item">-3%</span>
                </div>
            </div>
            <span class="list-inline-item">3%</span>

        </div>
            <div class="col-xs-3" style="padding-left: 0px;background: #E9C3FB;">
                <?if(round($prod, 1)>100){$prodper=round($prod, 1)-100;} ?>
                <div style="width:<?echo $prodper."%";?>;height: 60px;float: left;position: absolute;background: #A0D7FF;"title="Перевыполнено <? echo $resi-$res;?>"> </div>
            <div class="col-xs-3" style="padding-left: 0px;height: 60px;border-right: 1px solid white;text-align: center;    width: 35%;">
                <span class="list-inline-item">4%</span>
            </div>
            <div class="col-xs-3" style="padding-left: 0px;height: 60px;border-right: 1px solid white;text-align: center;    width: 35%;">
                <span class="list-inline-item">5%</span>
            </div>
            <div class="col-xs-3" style="padding-left: 30px;height: 60px;text-align: center;">
                <span class="list-inline-item">6%</span>
            </div>
            </div>
        </div>
</div>!-->
