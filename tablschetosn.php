<?
include 'conf.php';
if (!function_exists('schet_tip_text')) {
    function schet_tip_from_tipprod($row) {
        $tip = isset($row['tipprod']) ? trim($row['tipprod']) : '';

        if ($tip == '' || $tip == 'Нет') {
            return '';
        }
        if ($tip == 'Сер/Пос') {
            return 'Сертификат';
        }

        return $tip;
    }

    function schet_tip_fallback_text($row) {
        if (isset($_GET['tip']) && $_GET['tip'] == 'postavka') {
            return 'Поставка';
        }
        if (isset($row['status']) && $row['status'] == '5') {
            return 'Поставка';
        }
        if (isset($row['postprod']) && $row['postprod'] == '1') {
            return 'Поставка';
        }
        if (isset($row['generac']) && $row['generac'] == '546321564') {
            return 'Поставка';
        }
        if (isset($row['ust_sert']) && $row['ust_sert'] == '1') {
            return 'Сертификат';
        }

        return '';
    }

    function schet_tip_load($where) {
        static $cache = array();
        if (isset($cache[$where])) {
            return $cache[$where];
        }

        $q = mysql_query("SELECT ns,tipprod,status,dataprod,datasert,prodlenks,prodlens,postprod,ust_sert,shetold,generac,produkt FROM schet WHERE " . $where . " LIMIT 1");
        if ($q) {
            $cache[$where] = mysql_fetch_assoc($q);
            return $cache[$where];
        }
        $cache[$where] = false;
        return false;
    }

    function schet_tip_load_old($shetold, $produkt) {
        static $cache = array();
        if ($shetold == '') {
            return false;
        }

        $cacheKey = $shetold . '|' . $produkt;
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }

        $shetold = mysql_real_escape_string($shetold);
        $where = "ns='" . $shetold . "'";
        if ($produkt != '') {
            $where .= " AND produkt='" . mysql_real_escape_string($produkt) . "'";
        }

        $old = schet_tip_load($where . " AND tipprod!='' AND tipprod!='Нет'");
        if ($old) {
            $cache[$cacheKey] = $old;
            return $cache[$cacheKey];
        }

        $where = "ns LIKE '" . $shetold . "%'";
        if ($produkt != '') {
            $where .= " AND produkt='" . mysql_real_escape_string($produkt) . "'";
        }

        $old = schet_tip_load($where . " AND tipprod!='' AND tipprod!='Нет'");
        if ($old) {
            $cache[$cacheKey] = $old;
            return $cache[$cacheKey];
        }

        $cache[$cacheKey] = schet_tip_load("ns LIKE '" . $shetold . "%' AND tipprod!='' AND tipprod!='Нет'");
        return $cache[$cacheKey];
    }

    function schet_tip_text($row) {
        $text = schet_tip_from_tipprod($row);
        if ($text != '') {
            return $text;
        }

        $full = false;
        if (!empty($row['id'])) {
            $full = schet_tip_load("id='" . mysql_real_escape_string($row['id']) . "'");
        }
        if (!$full && !empty($row['rand'])) {
            $full = schet_tip_load("rand='" . mysql_real_escape_string($row['rand']) . "'");
        }
        if ($full) {
            $row = array_merge($row, $full);
            $text = schet_tip_from_tipprod($row);
            if ($text != '') {
                return $text;
            }
        }

        if (!empty($row['shetold'])) {
            $old = schet_tip_load_old($row['shetold'], isset($row['produkt']) ? $row['produkt'] : '');
            if ($old) {
                $text = schet_tip_from_tipprod($old);
                if ($text != '') {
                    return $text;
                }
            }
        }

        return schet_tip_fallback_text($row);
    }
}
if($_GET['tip']=="prod")
{
	$tipsh="продления";
}
if($_GET['tip']=="schet")
{
	$tipsh="счета";
}
$h=0;
$dataproms=$_GET['ys']."-".$_GET['ms']."-".$_GET['dayys'];
$datapromf=$_GET['yf']."-".$_GET['mf']."-".$_GET['dayyf'];
$dss=$_SESSION['ds'];
$dff=$_SESSION['df'];
$tips=$_COOKIE['tip'];
$m=date('m');
?>
<? if($_GET['tip']=="otk"){?>

        <?if($_GET['ogr']=="0")
    {
        $uslugiogrn="";
    }
    if($_GET['ogr']!="0")
    {
        $uslugiogrn="uslugi.id='".$_GET['ogr']."' and";
    }?>
        <? if($_GET['tipi']=="2"){?>
    <div class="by amt" style="float: left;
 width: 100%;
 padding-left: 0px;
     margin-bottom: 50px;
">
        <div class="by amt" style="float: left;
 width: 30%;padding-left: 0px;
">
        <table style="width: 70%;float: left;">
            <thead>
            <tr style="height: 37.78px;">
                <th style="
    width: 90%;
">Тип отказа</th>
                <th>шт</th>
                <th>%</th>
            </tr>
            </thead>
            <tbody>
            <?php $konotk=0; $rgrafi= mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id  left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn   schet.prichotk!='0'and schet.shetold!='' and schet.cher='1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'   group by schet.prichotk");
            while($resgrafi  = mysql_fetch_assoc($rgrafi ))  : $konotk+=$resgrafi['coun']; ?>
            <?endwhile;/*SELECT Count(schet.prichotk) as coun,prichotk.value,schet.rand,schet.kto,prichotk.col,schet.prichotk from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id  left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn   schet.prichotk!='0'and schet.shetold!='' and schet.cher='1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.prichotk*/?>
    <?php  $rgraf = mysql_query("SELECT prichotk.value,prichotk.id,prichotk.col from prichotk ");
    while($resgraf  = mysql_fetch_assoc($rgraf ))  :
        $allvalue[]=array($resgraf['coun'].",");
        $allcol[]=array("'".$resgraf['col']."',");
        ?>
    <tr>
        <td style="
    font-size: 10pt;
"><?echo $resgraf['value'];?></td>
       <td><?$valuotk = mysql_fetch_assoc(mysql_query("SELECT Count(schet.prichotk) as coun FROM schet left join produkti on schet.produkt=produkti.id  left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn   schet.shetold!='' and schet.cher='1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  and schet.del='0' and  schet.prichotk = '".$resgraf['id']."'"));echo $valuotk['coun'];$allvalue[]=array($valuotk['coun']." ");?></td>
        <td><?$proc=($valuotk['coun']*100)/$konotk;echo round($proc, 1);?></td>
    </tr>
    <?endwhile;?>
            </tbody>
        </table>
              <div style="

            position: relative;
            float: left;
            left: 419px;
            height: 150px;
            width: 70%;
            bottom: 282px;
            ">
                <label style="
    width: 98%;
    text-align: center;
    font-size: 12pt;
    position: relative;
   bottom: 631px;
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #f2f2f2;
    z-index: 999;
    border: 1px solid #d3d3d3;
    height: 39px;
">График</label>
                <canvas id="densityCharti" width="60" height="125" style="position: relative;right: 3px;
    bottom: 665px;height: auto;"></canvas>
            </div>
            <script>


                var chartData = {
                    labels: [<?
                        for($i=1;$i<count($allvalue);$i++){
                            $e=implode(" ", $allvalue[$i]);print_r($e);}?>],
                    datasets: [
                        {
                            label:'',

                            backgroundColor: [<?
                                for($i=0;$i<count($allcol);$i++){
                                    $e=implode(",", $allcol[$i]);print_r($e);}?>],
                            data: [<?
                                for($i=1;$i<count($allvalue);$i++){
                                    $e=implode(",", $allvalue[$i]);print_r($e);}?>]
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
                                min:999999
                            }
                        }]
                    },
                };
                var ctx = document.getElementById("densityCharti"),
                    myLineChart = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: chartData,
                        options: opt
                    });
            </script>
        </div>
           <div class="by amt" style="float: left;
 width: 30%;padding-left: 0px;    margin-left: 250px;
">
            <table style="
    width: 30%;
    float: left;
">
                <thead>
                <tr style="height: 37.78px;">
                    <th>С.руб</th>
                    <th>%</th>
                </tr>
                </thead>
                <tbody>
                <?php $konotks=0; $rgrafis= mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value,Sum(schet.price) as kolsum from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn  schet.prichotk!='0' and schet.shetold!='' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'   group by schet.prichotk");
                while($resgrafis  = mysql_fetch_assoc($rgrafis ))  : $konotks+=$resgrafis['kolsum'];?>
                <?endwhile;?>
                <?php  $rgrafs = mysql_query("SELECT prichotk.value,prichotk.id,prichotk.col from prichotk");
                while($resgrafs  = mysql_fetch_assoc($rgrafs )):
                     $allcols[]=array("'".$resgrafs['col']."',"); ?>
                <tr>
                    <td><?$valuotks = mysql_fetch_assoc(mysql_query("SELECT Sum(schet.price) as kolsum FROM schet left join produkti on schet.produkt=produkti.id  left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn   schet.shetold!='' and schet.cher='1' and schet.price!=''and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  and schet.del='0' and  schet.prichotk = '".$resgrafs['id']."'"));if($valuotks['kolsum']==''){$kols="0";echo $kols;}else{ $kols=$valuotks['kolsum'];echo$kols;}$allvalues[]=array($kols.",");?></td>
                    <td><?$procs=($valuotks['kolsum']*100)/$konotks;echo round($procs, 1);?></td>
                </tr>
                <?endwhile?>
                </tbody>
            </table>
            <div style="
            position: relative;
            height: 150px;width: 70%;top: 631px;
    float: left;">
                <label style="
    width: 98%;
    text-align: center;
    font-size: 12pt;
    position: relative;
   bottom: 631px;
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #f2f2f2;
    z-index: 999;
    border: 1px solid #d3d3d3;
    height: 39px;
">График</label>
                <canvas id="densityChart" width="60" height="125" style="position: relative;right: 3px;
    bottom: 665px;"></canvas>
            </div>
            <script>


                var chartData = {
                    labels: [<?
                        for($i=0;$i<count($allvalues);$i++){
                            $e=implode(",", $allvalues[$i]);print_r($e);}?>],
                    datasets: [
                        {
                            label:'<?echo $konotks;?>',

                            backgroundColor: [<?
                                for($i=0;$i<count($allcols);$i++){
                                    $e=implode(",", $allcols[$i]);print_r($e);}?>],
                            data: [<?
                                for($i=0;$i<count($allvalues);$i++){
                                    $e=implode(",", $allvalues[$i]);print_r($e);}?>]
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
                                min: 999999
                            }
                        }]
                    },
                };
                var ctx = document.getElementById("densityChart"),
                    myLineChart = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: chartData,
                        options: opt
                    });
            </script>
        </div>
         <div class="by amt" style="float: left;
  width: 20%;padding-left: 0px;
 ">
             <table    style="width: 30%;
                       float: left;">
                 <thead>
                 <tr style="height: 37.78px;">
                     <th>К.руб</th>
                     <th>%</th>
                 </tr>
                 </thead>
                 <tbody>
                 <?php $konotkks=0; $rgrafiks= mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value,Sum(schet.priceks) as kolsumka from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn  schet.prichotk!='0' and schet.shetold!='' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'   group by schet.prichotk");
                 while($resgrafiks  = mysql_fetch_assoc($rgrafiks))  : $konotkks+=$resgrafiks['kolsumka'];?>
                 <?endwhile;?>
                 <?php  $rgrafks = mysql_query("SELECT prichotk.value,prichotk.id,prichotk.col from prichotk");
                 while($resgrafks  = mysql_fetch_assoc($rgrafks)):
                     $allcolks[]=array("'".$resgrafks['col']."',"); ?>
                     <tr>
                         <td><?$valuotksum= mysql_fetch_assoc(mysql_query("SELECT Sum(schet.priceks) as kolsumka FROM schet left join produkti on schet.produkt=produkti.id  left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn   schet.shetold!='' and schet.cher='1' and schet.price!=''and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  and schet.del='0' and  schet.prichotk = '".$resgrafks['id']."'"));if($valuotksum['kolsumka']==''){$kols="0";echo $kols;}else{ $kols=$valuotksum['kolsumka'];echo$kols;}$allvalueks[]=array($kols.",");?></td>
                         <td><?$procks=($valuotksum['kolsumka']*100)/$konotkks;echo round($procks, 1);?></td>
                     </tr>
                 <?endwhile?>
                 </tbody>
             </table>
             <div style="
            position: relative;
    left: 118px;
    height: 250px;
    width: 100%;
    bottom: 282px;
    float: left;">
                 <label style="
    width: 98%;
    text-align: center;
    font-size: 12pt;
    position: relative;
   bottom: 631px;
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #f2f2f2;
    z-index: 999;
    border: 1px solid #d3d3d3;
    height: 39px;
">График</label>
                 <canvas id="densityChartis" width="100" height="221" style="position: relative;right: 3px;
    bottom: 665px;"></canvas>
             </div>
             <script>


                 var chartDatai = {
                     labels: [<?
                         for($i=0;$i<count($allvalueks);$i++){
                             $e=implode(",", $allvalueks[$i]);print_r($e);}?>],
                     datasets: [
                         {
                             label:'',

                             backgroundColor: [<?
                                 for($i=0;$i<count($allcolks);$i++){
                                     $e=implode(",", $allcolks[$i]);print_r($e);}?>],
                             data: [<?
                                 for($i=0;$i<count($allvalueks);$i++){
                                     $e=implode(",", $allvalueks[$i]);print_r($e);}?>]
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
                                 min: 999999
                             }
                         }]
                     },
                 };
                 var ctx = document.getElementById("densityChartis"),
                     myLineChart = new Chart(ctx, {
                         type: 'horizontalBar',
                         data: chartDatai,
                         options: opt
                     });
             </script>
         </div>
    </div>
            <?}?>
    <? if($_GET['tipi']=="1") {?>
       <!-- <div class="by amt" style="float: left;
 width: 100%;
 padding-left: 0px;
     margin-bottom: 50px;
">
            <div class="by amt" style="float: left;
 width: 30%;padding-left: 0px;
">
                <table style="width: 70%;float: left;">
                    <thead>
                    <tr style="height: 37.78px;">
                        <th style="
    width: 90%;
">Тип отказа</th>
                        <th>шт</th>
                        <th>%</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $konotk=0; $rgrafi= mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id  left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn   schet.prichotk!='0'and schet.shetold='' and schet.cher='1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'   group by schet.prichotk");
                    while($resgrafi  = mysql_fetch_assoc($rgrafi ))  : $konotk+=$resgrafi['coun'];?>
                    <?endwhile;?>
                    <?php  $rgraf = mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value,schet.rand,schet.kto,prichotk.col,schet.prichotk from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id  left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn   schet.prichotk!='0'and schet.shetold='' and schet.cher='1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.prichotk ");
                    while($resgraf  = mysql_fetch_assoc($rgraf ))  :
                        $allvalue[]=array($resgraf['coun'].",");
                        $allcol[]=array("'".$resgraf['col']."',");
                        ?>
                        <tr>
                            <td style="
    font-size: 10pt;
"><?echo $resgraf['value'];?></td>
                            <td><?echo $resgraf['coun'];?></td>
                            <td><?$proc=($resgraf['coun']*100)/$konotk;echo round($proc, 1);?></td>
                        </tr>
                    <?endwhile;?>
                    </tbody>
                </table>
                <div style="
            position: relative;
            left: 419px;
            height: 150px;width: 70%;top: 551px;">
                    <label style="
    width: 98%;
    text-align: center;
    font-size: 12pt;
    position: relative;
   bottom: 631px;
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #f2f2f2;
    z-index: 999;
    border: 1px solid #d3d3d3;
    height: 39px;
">График</label>
                    <canvas id="densityCharti" width="60" height="15" style="position: relative;right: 3px;
    bottom: 665px;"></canvas>
                </div>
                <script>


                    var chartData = {
                        labels: [<?
                            for($i=0;$i<count($allvalue);$i++){
                                $e=implode(",", $allvalue[$i]);print_r($e);}?>],
                        datasets: [
                            {
                                label:'<?echo $konotk;?>',

                                backgroundColor: [<?
                                    for($i=0;$i<count($allcol);$i++){
                                        $e=implode(",", $allcol[$i]);print_r($e);}?>],
                                data: [<?
                                    for($i=0;$i<count($allvalue);$i++){
                                        $e=implode(",", $allvalue[$i]);print_r($e);}?>]
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
                    };
                    var ctx = document.getElementById("densityCharti"),
                        myLineChart = new Chart(ctx, {
                            type: 'horizontalBar',
                            data: chartData,
                            options: opt
                        });
                </script>
            </div>
            <div class="by amt" style="float: left;
 width: 30%;padding-left: 0px;    margin-left: 250px;
">
                <table style="
    width: 30%;
    float: left;
">
                    <thead>
                    <tr style="height: 37.78px;">
                        <th>С.руб</th>
                        <th>%</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $konotks=0; $rgrafis= mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value,schet.price from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn  schet.prichotk!='0' and schet.shetold='' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'   group by schet.prichotk");
                    while($resgrafis  = mysql_fetch_assoc($rgrafis ))  : $konotks+=$resgrafis['price'];?>
                    <?endwhile;?>
                    <?php  $rgrafs = mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value,schet.rand,schet.kto,Sum(schet.price) as kolsum,prichotk.col from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn  schet.prichotk!='0' and schet.shetold='' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.prichotk ");
                    while($resgrafs  = mysql_fetch_assoc($rgrafs )):
                        $allvalues[]=array($resgrafs['kolsum'].",");
                        $allcols[]=array("'".$resgrafs['col']."',"); ?>
                        <tr>
                            <td><?echo $resgrafs['kolsum'];?></td>
                            <td><?$procs=($resgrafs['kolsum']*100)/$konotks;echo round($procs, 1);?></td>
                        </tr>
                    <?endwhile?>
                    </tbody>
                </table>
                <div style="
            position: relative;
            left: 179px;
            height: 150px;width: 70%;top: 91px;">
                    <label style="
    width: 98%;
    text-align: center;
    font-size: 12pt;
    position: relative;
   bottom: 631px;
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #f2f2f2;
    z-index: 999;
    border: 1px solid #d3d3d3;
    height: 39px;
">График</label>
                    <canvas id="densityChart" width="60" height="80" style="position: relative;right: 3px;
    bottom: 665px;"></canvas>
                </div>
                <script>


                    var chartData = {
                        labels: [<?
                            for($i=0;$i<count($allvalues);$i++){
                                $e=implode(",", $allvalues[$i]);print_r($e);}?>],
                        datasets: [
                            {
                                label:'<?echo $konotks;?>',

                                backgroundColor: [<?
                                    for($i=0;$i<count($allcols);$i++){
                                        $e=implode(",", $allcols[$i]);print_r($e);}?>],
                                data: [<?
                                    for($i=0;$i<count($allvalues);$i++){
                                        $e=implode(",", $allvalues[$i]);print_r($e);}?>]
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
                                    min: 999999
                                }
                            }]
                        },
                    };
                    var ctx = document.getElementById("densityChart"),
                        myLineChart = new Chart(ctx, {
                            type: 'horizontalBar',
                            data: chartData,
                            options: opt
                        });
                </script>
            </div>
            <div class="by amt" style="float: left;
  width: 20%;padding-left: 0px;
 ">
                <table    style="width: 30%;
                       float: left;">
                    <thead>
                    <tr style="height: 37.78px;">
                        <th>К.руб</th>
                        <th>%</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $konotkks=0; $rgrafiks= mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value,schet.priceks from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn  schet.prichotk!='0' and schet.shetold='' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'   group by schet.prichotk");
                    while($resgrafiks  = mysql_fetch_assoc($rgrafiks))  : $konotkks+=$resgrafiks['priceks'];?>
                    <?endwhile;?>
                    <?php  $rgrafks = mysql_query("SELECT Count(schet.prichotk) as coun,prichotk.value,schet.rand,schet.kto,SUM(schet.priceks) as kolsumka,prichotk.col,schet.ns from schet left join prichotk on schet.prichotk=prichotk.id left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $uslugiogrn schet.prichotk!='0' and schet.shetold='' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.prichotk ");
                    while($resgrafks  = mysql_fetch_assoc($rgrafks)):
                        $allvalueks[]=array($resgrafks['kolsumka'].",");
                        $allcolks[]=array("'".$resgrafks['col']."',"); ?>
                        <tr>
                            <td id="<?echo $resgrafks['ns'];?>"><?echo $resgrafks['kolsumka'];?></td>
                            <td><?$procks=($resgrafks['kolsumka']*100)/$konotkks;echo round($procks, 1);?></td>
                        </tr>
                    <?endwhile?>
                    </tbody>
                </table>
                <div style="
            position: relative;
            left: 118px;
            height: 250px;width: 100%;top: 91px;">
                    <label style="
    width: 98%;
    text-align: center;
    font-size: 12pt;
    position: relative;
   bottom: 631px;
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #f2f2f2;
    z-index: 999;
    border: 1px solid #d3d3d3;
    height: 39px;
">График</label>
                    <canvas id="densityChartis" width="100" height="141" style="position: relative;right: 3px;
    bottom: 665px;"></canvas>
                </div>
                <script>


                    var chartDatai = {
                        labels: [<?
                            for($i=0;$i<count($allvalueks);$i++){
                                $e=implode(",", $allvalueks[$i]);print_r($e);}?>],
                        datasets: [
                            {
                                label:'',

                                backgroundColor: [<?
                                    for($i=0;$i<count($allcolks);$i++){
                                        $e=implode(",", $allcolks[$i]);print_r($e);}?>],
                                data: [<?
                                    for($i=0;$i<count($allvalueks);$i++){
                                        $e=implode(",", $allvalueks[$i]);print_r($e);}?>]
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
                                    min: 999999
                                }
                            }]
                        },
                    };
                    var ctx = document.getElementById("densityChartis"),
                        myLineChart = new Chart(ctx, {
                            type: 'horizontalBar',
                            data: chartDatai,
                            options: opt
                        });
                </script>
            </div>
        </div>!-->
<?}?>
<?}?>

<style>
.schet-table-scroll {
    width: 100%;
    overflow-x: auto;
    overflow-y: visible;
    cursor: grab;
    -webkit-overflow-scrolling: touch;
}
.schet-table-scroll.is-dragging {
    cursor: grabbing;
    user-select: none;
}
.schet-table {
    width: max-content !important;
    min-width: max-content !important;
    max-width: none;
    border-collapse: collapse;
    table-layout: auto;
}
.schet-table th,
.schet-table td {
    width: auto !important;
    padding: 3px 5px;
    font-size: 11px;
    line-height: 1.15;
    white-space: nowrap;
    vertical-align: middle;
}
.schet-table th[style*="width"],
.schet-table td[style*="width"] {
    width: auto !important;
    min-width: 0;
    max-width: none;
}
.schet-table td[style*="width: 30%"] {
    width: auto !important;
    min-width: 0;
    max-width: none;
    white-space: nowrap;
}
.schet-table p {
    margin: 1px 0;
}
.schet-table ul {
    margin: 0;
    padding: 0;
    list-style: none;
}
.schet-table img {
    max-width: 18px;
    height: auto;
}
</style>
<div class="schet-table-scroll">
<table id="myTable1" class="tablesorter schet-table">
<thead style="
    background: white;
">
<tr>
<th>№</th>
<?if($_GET['tip']=="prod"&&$_GET['tipi']=="2")
{?>
<th>Старый № счета</th>
<?}?>
    <?if($_GET['tip']=="prod"&&$_GET['tipi']=="2")
    {?>
        <th>Продлений</th>
    <?}?>
<th>дата счета</th>
<th>№ счета</th>
<th>инн</th>
<th>кпп</th>
<th>наименование</th>
<th>продукт</th>
<th>тип</th>
<th>К</th>
<th>С</th>
<th>продление</th>
<th>статус</th>
<th>менеджер</th>
<th style="
    width: 6%;
">контроль бронь</th>
<th style="
    width: 6%;
">дата звонка</th>
<th><img src="/img/icons8.png"></th>
<th><img src="/img/qwerty.png"></th>
<th><img src="/img/tablsc.png"></th>
<th><img src="/img/ship.png"style="
    width: 20px;
"></th>
    
</tr>
</thead>
<tbody  >
<?if($_GET['ogr']=="0")
{
	$uslugiogrn="";
}
if($_GET['ogr']!="0")
{
	$uslugiogrn="uslugi.id='".$_GET['ogr']."' and";
}

    $dataproms=$_GET['ys']."-".$_GET['ms']."-".$_GET['dayys'];
    $datapromf=$_GET['yf']."-".$_GET['mf']."-".$_GET['dayyf'];

if($_GET['tip']=="prod")
{
if ($_GET['tipi']=="2"){?>
<?php
$r = mysql_query("SELECT schet.id,schet.idkli,schet.lico,schet.gr,schet.url,schet.ogrn as orgh,schet.ns,schet.rand,schet.kto,schet.datebron,schet.datezvon,schet.nomerschetks,schet.inn,DATE_FORMAT(schet.dataprod,'%m') as date_po,DATE_FORMAT(schet.dataprod,'%Y') as date_poy,DATE_FORMAT(schet.datasert,'%m') as date_se,DATE_FORMAT(schet.datasert,'%Y') as date_sey,DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,schet.dataprod,schet.datasert,users.f_name,users.l_name,users.o_name,schet.prodlenks,schet.prodlens,schet.produkt FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id  WHERE $uslugiogrn schet.del!='1'and  schet.tipprod!='' and  (schet.shetold!='' or schet.shetold='') and schet.tipprod!='Нет' and schet.prodlens='0'and schet.prodlenks='0'and schet.cher='0' and schet.otk='0' and (schet.dataprod BETWEEN'".$_GET['ds']."' and '".$_GET['df']."' or schet.datasert BETWEEN'".$_GET['ds']."' and '".$_GET['df']."') group by schet.rand");
while($res = mysql_fetch_assoc($r))  : 
if( $res['tipprod']!='Сер/Пос'){?>
<tr value="<?php echo $res['id'];?>"class="schetprodlenie">
<?$h++;?>
<td><?echo $h;?></td>
<td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
<td></td>
<td style="background:#C1BBBB;">Ожидаем</td>
<td style="background:#C1BBBB;">Ожидаем</td>
<td><?echo $res['inn'];?></td>
<td><?echo $res['kpp'];?></td>
<td style="width: 30%;"><?echo $res['ogrn'];?></td>
<td><?echo $res['name'];?></td>
<td><?echo schet_tip_text($res);?></td>
<td><?echo $res['priceks'];?></td>
<td><?echo $res['price'];?></td>
<td style="width: 4%;"><?if($res['dataprod']!=''){echo $res['dataprod'];}if($res['datasert']!=''){echo '<p>';echo $res['datasert'];}echo'</p>';?></td>
<td style="width: 7%;background:#C1BBBB;">не продлено</td>
<td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
<td>
<ul class="schetkal"><?if($res['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $res['rand'];?>"id="schetkal<?echo $res['rand'];?>"><?if($res['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($res['datebron']));
	$monvis=date('m', strtotime($res['datebron']));
	$yesrvis=date('Y', strtotime($res['datebron']));
    $dss= $dayvis.".".$monvis.".".$yesrvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $res['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $res['rand'];?>' id="datebr<?echo $res['rand'];?>" type="date"value="<?if($res['datebron']!="0000-00-00"){echo $res['datebron'];}?>"> 
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $res['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
	/*<?if($res['date_reg']!="00.00.0000"){?>
	document.getElementById('datebr<?echo $res['rand'];?>').valueAsDate='<?echo $res['date_reg'];?>';<?}?>*/
	<?if($res['date_reg']!="00.00.0000"){?>
	<?}?>
    $('#schetkal<?echo $res['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $res['rand'];?>').show();
    $('.modal-window<?echo $res['rand'];?>').show();
	$('#kalbr<?echo $res['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $res['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $res['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $res['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $res['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $res['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $res['rand'];?>').hide();
	  $('#kalbr<?echo $res['rand'];?>').hide();
    $('.modal-window<?echo $res['rand'];?>').hide();
	document.getElementById("scales<?echo $res['rand'];?>").checked=false;
    });
});
</script>

<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $res['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $res['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvon"><?if($res['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvon<?echo $res['rand'];?>"><?if($res['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($res['datezvon']));
	$monvis=date('m', strtotime($res['datezvon']));
	$yesrvis=date('Y', strtotime($res['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowz<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowz<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalz<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidz<?echo $res['rand'];?>">Перезвонить</label>
<input class='form-control datez<?echo $res['rand'];?>' id="datez<?echo $res['rand'];?>" type="date"value="<?if($res['datezvon']!="0000-00-00"){echo $res['datezvon'];}?>">
<textarea class='form-control timez<?echo $res['rand'];?>' id="timez<?echo $res['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalez<?echo $res['rand'];?>" name="scalez" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalez">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvon<?echo $res['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowz<?echo $res['rand'];?>').show();
    $('.modal-windowz<?echo $res['rand'];?>').show();
	$('#kalz<?echo $res['rand'];?>').show();
    });
 
    $('.modal-shadowz<?echo $res['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalez<?echo $res['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datez<?echo $res['rand'];?>")[0].value;
		timezvon=document.getElementById("timez<?echo $res['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&message="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $res['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvon<?echo $res['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowz<?echo $res['rand'];?>').hide();
	  $('#kalz<?echo $res['rand'];?>').hide();
    $('.modal-windowz<?echo $res['rand'];?>').hide();
	document.getElementById("scalez<?echo $res['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvon<?echo $res['rand'];?>').innerHTML;
document.getElementById('schetzvon<?echo $res['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td value="<?echo $res['rand'];?>"id="movschet<?echo $res['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $res['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $res['rand'];?>"onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $res['idkli'];?>&parent=<?echo $res['produkt'];?>&ogrn=<?echo $res['orgh'];?>&inn=<?echo $res['inn'];?>&kpp=<?echo $res['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $res['ns']?>')">Выставить счет</label>
                <label class="otkaz" id="otkaz<?echo $res['rand'];?>">Отказать</label>
                </div>
            <div id="prich<?echo $res['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 250px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $res['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                <script>
                    $(function() {
                        $('#otkazp<?echo $res['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                            document.getElementById('modal-shadowkube').style.display = "block";
                            document.getElementById('kube').style.display = "block";
                            var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                            $('.modal-shadowm<?echo $res['rand'];?>').hide();
                            $('#kalm<?echo $res['rand'];?>').hide();
                            $('.modal-windowm<?echo $res['rand'];?>').hide();
                            $('#prich<?echo $res['rand'];?>').hide();
                            $.ajax({
                                type: "GET",
                                url: "otkazschet.php",
                                data: "tipotkaz=" + n + "&rand=<?echo $res['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=prod",
                                success: function (html) {
                                    $.ajax({
                                        url: "tablschetosn.php",
                                        cache: false,
                                        data: "tip=prod&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&tipi=<?echo $_GET['tipi'];?>&id=<?echo $_GET['id'];?>",
                                        success: function (html) {
                                            $("#tablosn").html(html);
                                            document.getElementById('modal-shadowkube').style.display = "none";
                                            document.getElementById('kube').style.display = "none";
                                        }
                                    });
                                }
                            });
                        });
                    });
                </script>
                <?endwhile?>
            </div>
            </div>

        </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').show();
                $('.modal-windowm<?echo $res['rand'];?>').show();
                $('#kalm<?echo $res['rand'];?>').show();
            });
            $('#otkaz<?echo $res['rand'];?>').click(function () {

                $('#prich<?echo $res['rand'];?>').show();

            });
            $('#vist<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').hide();
                $('#kalm<?echo $res['rand'];?>').hide();
                $('.modal-windowm<?echo $res['rand'];?>').hide();
                $('#prich<?echo $res['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').hide();
                $('#kalm<?echo $res['rand'];?>').hide();
                $('.modal-windowm<?echo $res['rand'];?>').hide();
                $('#prich<?echo $res['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $res['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $res['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $res['ogrn']);?>&kli=<? echo $res['idkli'];?>&lico=<? echo $res['lico'];?>&gr=<? echo $res['gr'];?>&nomerschet=<? echo $res['ns'];?>&produkt=<? echo $res['produkt'];?>&inn=<? echo $res['inn'];?>"><img src="/img/qwerty.png"></a></td>
  <td value="<?echo $res['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $res['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td value="<?echo $res['rand'];?>"><a target="_blank" href=<?echo $res['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
    <?/*
    $query33 = mysql_query("SELECT COUNT(*) as count from kol_prodlen WHERE ns='" . $res['ns'] . "' and prod='0' and otkaz='0'");
    $resuy = mysql_fetch_array($query33) ;
    if($resuy['count']!=0)
    {
        $hp=0;
        $roue = mysql_query("SELECT * from kol_prodlen WHERE ns='" . $res['ns'] . "' and prod='0' and otkaz='0'");
        while($oue = mysql_fetch_assoc($roue))  {
        ?>
    <tr>
        <td><?$hp++;echo $h.".".$hp;?></td>
        <td style="background:#C1BBBB;"><?echo $oue['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
        <td><?echo $oue['kol'];?></td>
        <td style="background:#C1BBBB;">Ожидаем</td>
        <td style="background:#C1BBBB;">Ожидаем</td>
        <td><?echo $res['inn'];?></td>
        <td><?echo $res['kpp'];?></td>
        <td style="width: 30%;"><?echo $res['ogrn'];?></td>
        <td><?echo $res['name'];?></td>
        <td><?echo $oue['tip'];?></td>
        <td></td>
        <td></td>
        <td><?echo $oue['date_prodleni'];?></td>
    </tr>
<?}}*/}
if( $res['tipprod']=='Сер/Пос'&& stristr($res['date_se'], $_GET['naf'])==true&& stristr($res['date_sey'], $_GET['nay'])==true&&$res['prodlenks']=='0'){?>
<tr value="<?php echo $res['id'];?>"class="schetprodlenie">
<?$h++;?>
<td><?echo $h;?></td>
<td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
    <td></td>
<td style="background:#C1BBBB;">Ожидаем</td>
<td style="background:#C1BBBB;">Ожидаем</td>
<td><?echo $res['inn'];?></td>
<td><?echo $res['kpp'];?></td>
<td style="width: 30%;"><?echo $res['ogrn'];?></td>
<td><?echo $res['name'];?></td>
<td>Cертификат</td>
<td><?echo $res['priceks'];?></td>
<td><?echo $res['price'];?></td>
<td style="width: 4%;"><?echo $res['datasert'];?></td>
<td style="width: 7%;background:#C1BBBB;">не продлено</td>
<td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
<td>
<ul class="schetkals"><?if($res['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkals<?echo $res['rand'];?>"id="schetkals<?echo $res['rand'];?>"><?if($res['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($res['datebron']));
	$monvis=date('m', strtotime($res['datebron']));
	$yesrvis=date('Y', strtotime($res['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadows<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windows<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbrs<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbrs<?echo $res['rand'];?>">Добавить бронь</label>
<input class='form-control datebrs<?echo $res['rand'];?>' id="datebrs<?echo $res['rand'];?>" type="date"value="<?if($res['datebron']!="0000-00-00"){echo $res['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scaless<?echo $res['rand'];?>" name="scaless" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scaless">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkals<?echo $res['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadows<?echo $res['rand'];?>').show();
    $('.modal-windows<?echo $res['rand'];?>').show();
	$('#kalbrs<?echo $res['rand'];?>').show();
    });
 
    $('.modal-shadows<?echo $res['rand'];?>').click(function () {
		if(sost=="bron")
		{
			if(document.getElementById("scaless<?echo $res['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebrs<?echo $res['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&massage="+message+"&datebron="+datebroni+"&rand=<?echo $res['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkals<?echo $res['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadows<?echo $res['rand'];?>').hide();
	  $('#kalbrs<?echo $res['rand'];?>').hide();
    $('.modal-windows<?echo $res['rand'];?>').hide();
	document.getElementById("scaless<?echo $res['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkals<?echo $res['rand'];?>').innerHTML;
document.getElementById('schetkals<?echo $res['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($res['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $res['rand'];?>"><?if($res['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($res['datezvon']));
	$monvis=date('m', strtotime($res['datezvon']));
	$yesrvis=date('Y', strtotime($res['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $res['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $res['rand'];?>' id="datezs<?echo $res['rand'];?>" type="date"value="<?if($res['datezvon']!="0000-00-00"){echo $res['datezvon'];}?>">
<textarea class='form-control timezs<?echo $res['rand'];?>' id="timezs<?echo $res['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $res['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $res['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $res['rand'];?>').show();
    $('.modal-windowzs<?echo $res['rand'];?>').show();
	$('#kalzs<?echo $res['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $res['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $res['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $res['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $res['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&message="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $res['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $res['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $res['rand'];?>').hide();
	  $('#kalzs<?echo $res['rand'];?>').hide();
    $('.modal-windowzs<?echo $res['rand'];?>').hide();
	document.getElementById("scalezs<?echo $res['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $res['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $res['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $res['rand'];?>"id="movschet<?echo $res['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $res['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $res['rand'];?>"onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $res['idkli'];?>&parent=<?echo $res['produkt'];?>&ogrn=<?echo $res['orgh'];?>&inn=<?echo $res['inn'];?>&kpp=<?echo $res['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $res['ns']?>')">Выставить счет</label>
                <label class="otkaz" id="otkaz<?echo $res['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $res['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 250px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $res['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $res['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $res['rand'];?>').hide();
                                $('#kalm<?echo $res['rand'];?>').hide();
                                $('.modal-windowm<?echo $res['rand'];?>').hide();
                                $('#prich<?echo $res['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $res['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=prod",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=prod&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&tipi=<?echo $_GET['tipi'];?>&id=<?echo $_GET['id'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').show();
                $('.modal-windowm<?echo $res['rand'];?>').show();
                $('#kalm<?echo $res['rand'];?>').show();
            });
            $('#otkaz<?echo $res['rand'];?>').click(function () {

                $('#prich<?echo $res['rand'];?>').show();

            });
            $('#vist<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').hide();
                $('#kalm<?echo $res['rand'];?>').hide();
                $('.modal-windowm<?echo $res['rand'];?>').hide();
                $('#prich<?echo $res['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').hide();
                $('#kalm<?echo $res['rand'];?>').hide();
                $('.modal-windowm<?echo $res['rand'];?>').hide();
                $('#prich<?echo $res['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $res['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $res['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $res['ogrn']);?>&kli=<? echo $res['idkli'];?>&lico=<? echo $res['lico'];?>&gr=<? echo $res['gr'];?>&nomerschet=<? echo $res['ns'];?>&produkt=<? echo $res['produkt'];?>&inn=<? echo $res['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $res['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $res['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $res['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
    <!--
   <tr>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
   </tr>!-->
<?}if( $res['tipprod']=='Сер/Пос'&& stristr($res['date_po'], $_GET['na'])==true&& stristr($res['date_poy'], $_GET['nay'])==true||$res['tipprod']=='Сер/Пос'&& stristr($res['date_po'], $_GET['naf'])==true&& stristr($res['date_poy'], $_GET['nayf'])==true){?>
<?$h++;?>
<tr value="<?php echo $res['id'];?>"class="schetprodlenie">
<td><?echo $h;?></td>
<td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
    <td></td>
<td style="background:#C1BBBB;">Ожидаем</td>
<td style="background:#C1BBBB;">Ожидаем</td>
<td><?echo $res['inn'];?></td>
<td><?echo $res['kpp'];?></td>
<td style="width: 30%;"><?echo $res['ogrn'];?></td>
<td><?echo $res['name'];?></td>
<td>Поставка</td>
<td><?echo $res['priceks'];?></td>
<td><?echo $res['price'];?></td>
<td style="width: 4%;"><?echo $res['dataprod'];?></td>
<td style="width: 7%;background:#C1BBBB;">не продлено</td>
<td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
<td>
<ul class="schetkalp"><?if($res['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkalp<?echo $res['rand'];?>"id="schetkalp<?echo $res['rand'];?>"><?if($res['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($res['datebron']));
	$monvis=date('m', strtotime($res['datebron']));
	$yesrvis=date('Y', strtotime($res['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowp<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowp<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbrp<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbrp<?echo $res['rand'];?>">Добавить бронь</label>
<input class='form-control datebrp<?echo $res['rand'];?>' id="datebrp<?echo $res['rand'];?>" type="date"value="<?if($res['datebron']!="0000-00-00"){echo $res['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalesp<?echo $res['rand'];?>" name="scalesp" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalesp">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkalp<?echo $res['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadowp<?echo $res['rand'];?>').show();
    $('.modal-windowp<?echo $res['rand'];?>').show();
	$('#kalbrp<?echo $res['rand'];?>').show();
    });
 
    $('.modal-shadowp<?echo $res['rand'];?>').click(function () {
		if(sost=="bron")
		{
				if(document.getElementById("scaless<?echo $res['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebrp<?echo $res['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&massage="+massage+"&datebron="+datebroni+"&rand=<?echo $res['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkalp<?echo $res['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowp<?echo $res['rand'];?>').hide();
	  $('#kalbrp<?echo $res['rand'];?>').hide();
    $('.modal-windowp<?echo $res['rand'];?>').hide();
	document.getElementById("scaless<?echo $res['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkalp<?echo $res['rand'];?>').innerHTML;
document.getElementById('schetkalp<?echo $res['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvonp"><?if($res['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvonp<?echo $res['rand'];?>"><?if($res['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($res['datezvon']));
	$monvis=date('m', strtotime($res['datezvon']));
	$yesrvis=date('Y', strtotime($res['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzp<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzp<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzp<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzp<?echo $res['rand'];?>">Перезвонить</label>
<input class='form-control datezp<?echo $res['rand'];?>' id="datezp<?echo $res['rand'];?>" type="date"value="<?if($res['datezvon']!="0000-00-00"){echo $res['datezvon'];}?>">
<textarea class='form-control timezp<?echo $res['rand'];?>' id="timezp<?echo $res['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezp<?echo $res['rand'];?>" name="scaleszp" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezp">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvonp<?echo $res['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzp<?echo $res['rand'];?>').show();
    $('.modal-windowzp<?echo $res['rand'];?>').show();
	$('#kalzp<?echo $res['rand'];?>').show();
    });
 
    $('.modal-shadowzp<?echo $res['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezp<?echo $res['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezp<?echo $res['rand'];?>")[0].value;
		timezvon=document.getElementById("timezp<?echo $res['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $res['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvonp<?echo $res['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzp<?echo $res['rand'];?>').hide();
	  $('#kalzp<?echo $res['rand'];?>').hide();
    $('.modal-windowzp<?echo $res['rand'];?>').hide();
	document.getElementById("scalezp<?echo $res['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvonp<?echo $res['rand'];?>').innerHTML;
document.getElementById('schetzvonp<?echo $res['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $res['rand'];?>"id="movschetp<?echo $res['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowmp<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowmp<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalmp<?echo $res['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vistp<?echo $res['rand'];?>"onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $res['idkli'];?>&parent=<?echo $res['produkt'];?>&ogrn=<?echo $res['orgh'];?>&inn=<?echo $res['inn'];?>&kpp=<?echo $res['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $res['ns']?>')">Выставить счет</label>
                <label class="otkaz" id="otkazppp<?echo $res['rand'];?>">Отказать</label>
            </div>
            <div id="prichp<?echo $res['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $res['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $res['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowmp<?echo $res['rand'];?>').hide();
                                $('#kalmp<?echo $res['rand'];?>').hide();
                                $('.modal-windowmp<?echo $res['rand'];?>').hide();
                                $('#prichp<?echo $res['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $res['rand'];?>&kto=<? echo $res['id'];?>&tipschet=prod",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=prod&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&tipi=<?echo $_GET['tipi'];?>&id=<?echo $_GET['id'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschetp<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowmp<?echo $res['rand'];?>').show();
                $('.modal-windowmp<?echo $res['rand'];?>').show();
                $('#kalmp<?echo $res['rand'];?>').show();
            });
            $('#otkazppp<?echo $res['rand'];?>').click(function () {

                $('#prichp<?echo $res['rand'];?>').show();

            });
            $('#vistp<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowmp<?echo $res['rand'];?>').hide();
                $('#kalmp<?echo $res['rand'];?>').hide();
                $('.modal-windowmp<?echo $res['rand'];?>').hide();
                $('#prichp<?echo $res['rand'];?>').hide();

            });
            $('.modal-shadowmp<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowmp<?echo $res['rand'];?>').hide();
                $('#kalmp<?echo $res['rand'];?>').hide();
                $('.modal-windowmp<?echo $res['rand'];?>').hide();
                $('#prichp<?echo $res['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $res['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $res['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $res['ogrn']);?>&kli=<? echo $res['idkli'];?>&lico=<? echo $res['lico'];?>&gr=<? echo $res['gr'];?>&nomerschet=<? echo $res['ns'];?>&produkt=<? echo $res['produkt'];?>&inn=<? echo $res['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $res['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $res['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $res['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
    <!--
   <tr>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
   </tr>!-->
<?} endwhile; }?>
<?if($_GET['tipi']=="1")
{?>
<?php $r = mysql_query("SELECT schet.id,schet.idkli,schet.lico,schet.ogrn as orgh,schet.gr,schet.url,schet.produkt,schet.ns,schet.rand,schet.kto,schet.datebron,schet.datezvon,schet.shetold,DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.nomerschetks,schet.inn,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join schetprodlen on schet.shetold=schetprodlen.schetold left join users on schet.kto=users.users_id where $uslugiogrn schet.del!='1' and schet.tipprod='' and schet.prodlens='0'and schet.prodlenks='0' and schet.cher='0' and (schet.dataprod BETWEEN'".$_GET['ms']."' and '".$_GET['mf']."' or schet.datasert BETWEEN'".$_GET['ms']."' and '".$_GET['mf']."')group by schet.ns");
while($res = mysql_fetch_assoc($r))  : ?>
<tr value="<?php echo $res['id'];?>"class="schetprodlenie">
<?$h++;?>
<td><?echo $h;?></td>
<td style="
    width: 4%;background:#C1BBBB;
"><?echo $res['prodschet'];?></td>
<td style="background:#C1BBBB;"><?echo $res['shetold'];?></td>
<td><?echo $res['inn'];?></td>
<td><?echo $res['kpp'];?></td>
<td style="width: 30%;"><?echo $res['ogrn'];?></td>
<td><?echo $res['name'];?></td>
<td><?echo schet_tip_text($res);?></td>
<td><?echo $res['price'];?></td>
<td><?echo $res['priceks'];?></td>
<td style="width: 4%;"> <?echo $res['prodschet'];?></td>
<td style="width: 5%;">продлено</td>
<td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
<td>
<ul class="schetkal"><?if($res['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $res['rand'];?>"id="schetkal<?echo $res['rand'];?>"><?if($res['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($res['datebron']));
	$monvis=date('m', strtotime($res['datebron']));
	$yesrvis=date('Y', strtotime($res['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $res['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $res['rand'];?>' id="datebr<?echo $res['rand'];?>" type="date"value="<?if($res['datebron']!="0000-00-00"){echo $res['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $res['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $res['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $res['rand'];?>').show();
    $('.modal-window<?echo $res['rand'];?>').show();
	$('#kalbr<?echo $res['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $res['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $res['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $res['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $res['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $res['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $res['rand'];?>').hide();
	  $('#kalbr<?echo $res['rand'];?>').hide();
    $('.modal-window<?echo $res['rand'];?>').hide();
	document.getElementById("scales<?echo $res['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $res['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $res['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvon"><?if($res['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvon<?echo $res['rand'];?>"><?if($res['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($res['datezvon']));
	$monvis=date('m', strtotime($res['datezvon']));
	$yesrvis=date('Y', strtotime($res['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowz<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowz<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalz<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidz<?echo $res['rand'];?>">Перезвонить</label>
<input class='form-control datez<?echo $res['rand'];?>' id="datez<?echo $res['rand'];?>" type="date"value="<?if($res['datezvon']!="0000-00-00"){echo $res['datezvon'];}?>">
<textarea class='form-control timez<?echo $res['rand'];?>' id="timez<?echo $res['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalez<?echo $res['rand'];?>" name="scalesz" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalez">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvon<?echo $res['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowz<?echo $res['rand'];?>').show();
    $('.modal-windowz<?echo $res['rand'];?>').show();
	$('#kalz<?echo $res['rand'];?>').show();
    });
 
    $('.modal-shadowz<?echo $res['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalez<?echo $res['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datez<?echo $res['rand'];?>")[0].value;
		timezvon=document.getElementById("timez<?echo $res['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&message="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $res['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvon<?echo $res['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowz<?echo $res['rand'];?>').hide();
	  $('#kalz<?echo $res['rand'];?>').hide();
    $('.modal-windowz<?echo $res['rand'];?>').hide();
	document.getElementById("scalez<?echo $res['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvon<?echo $res['rand'];?>').innerHTML;
document.getElementById('schetzvon<?echo $res['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $res['rand'];?>"id="movschet<?echo $res['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $res['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $res['rand'];?>"onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $res['idkli'];?>&parent=<?echo $res['produkt'];?>&ogrn=<?echo $res['orgh'];?>&inn=<?echo $res['inn'];?>&kpp=<?echo $res['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $res['ns']?>')">Выставить счет</label>
                <label class="otkaz" id="otkaz<?echo $res['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $res['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 250px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $res['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $res['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $res['rand'];?>').hide();
                                $('#kalm<?echo $res['rand'];?>').hide();
                                $('.modal-windowm<?echo $res['rand'];?>').hide();
                                $('#prich<?echo $res['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $res['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=prod",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=prod&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&tipi=<?echo $_GET['tipi'];?>&id=<?echo $_GET['id'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').show();
                $('.modal-windowm<?echo $res['rand'];?>').show();
                $('#kalm<?echo $res['rand'];?>').show();
            });
            $('#otkaz<?echo $res['rand'];?>').click(function () {

                $('#prich<?echo $res['rand'];?>').show();

            });
            $('#vist<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').hide();
                $('#kalm<?echo $res['rand'];?>').hide();
                $('.modal-windowm<?echo $res['rand'];?>').hide();
                $('#prich<?echo $res['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $res['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $res['rand'];?>').hide();
                $('#kalm<?echo $res['rand'];?>').hide();
                $('.modal-windowm<?echo $res['rand'];?>').hide();
                $('#prich<?echo $res['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $res['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $res['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $res['ogrn']);?>&kli=<? echo $res['idkli'];?>&lico=<? echo $res['lico'];?>&gr=<? echo $res['gr'];?>&nomerschet=<? echo $res['ns'];?>&produkt=<? echo $res['produkt'];?>&inn=<? echo $res['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $res['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $res['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $res['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
<?if( $res['tipprod']=='Сер/Пос')
{
	echo'</tr>';
	echo'<tr class="schetprodlenie">';
	echo'<td>';
	echo $h;
	echo'</td>';
	echo'<td style="background:#C1BBBB;">';
	echo $res['prodschet'];
	echo'</td>';
	echo'<td style="background:#C1BBBB;">';
	echo $res['shetold'];
	echo'</td>';
	echo'<td>';
	echo $res['inn'];
	echo'</td>';
	echo'<td>';
	echo $res['kpp'];
	echo'</td>';
	echo'<td>';
	echo $res['ogrn'];
	echo'</td>';
	echo'<td>';
	echo $res['name'];
	echo'</td>';
	echo'<td>';
	echo "Поставка";
	echo'</td>';
	echo'<td>';
	echo $res['price'];
	echo'</td>';
	echo'<td>';
	echo $res['priceks'];
	echo'</td>';
	echo'<td>';
	echo $res['dataprod'];
	echo'</td>';
	echo'<td>';
	echo $res['prodschet'];
	echo'</td>';
	echo'<td>';
	echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';
	echo'</td>';
	echo'<td>';
	echo '<input class=form-control secondDate id="datefinish" type="date">';
	echo'</td>';
	echo'<td>';
	echo '<input class=form-control secondDate id="datefinish" type="date">';
	echo'</td>';
	echo'<td value="';echo $res['rand'];echo'">';
	echo'<img src="/img/qwerty.png">';
	echo'</td>';
	echo'<td value="';echo $res['rand'];echo'">';
	echo'<img src="/img/tablsc.png">';
	echo'</td>';
	echo'</tr>';
}else{?>
</tr>
        <!--
   <tr>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
   </tr>!-->
<?}?>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="schet"){
if ($_GET['tipi']=="1"){?>
<?php $rs = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.idkli,DATE_FORMAT(schet.datasert,'%d.%m.%Y') as sert,DATE_FORMAT(schet.dataprod,'%d.%m.%Y') as prod,schet.gr,schet.ogrn,schet.lico,schet.nomerschetks,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,schet.produkt,users.f_name,users.l_name,users.o_name,kvobop.tip as yes FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.del!='1'AND schet.oplachenks!= '1' and schet.oplachen != '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($ress = mysql_fetch_assoc($rs))  : ?>
<tr value="<?php echo $ress['id'];?> "class="schetdol">
<?$h++;
if($ress['yes']=="11"||$ress['yes']=="1")
{
	$color="background:#FFF850;";
}
else
{
	$color="background:#C1E5FB;";
}
?>

<td><?echo $h;?></td>
<td style="background:#C1E5FB;" id="datasch<?echo $ress['rand'];?>"><?php
$dayvis=$ress['d'];
	$monvis=$ress['m'];
	$yesrvis=$ress['y'];
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;?></td>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('datasch<?echo $ress['rand'];?>').innerHTML;
document.getElementById('datasch<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td style="background:#C1E5FB;"><?php echo $ress['ns'];?><?if($ress['nomerschetks']!=''){?><p><?php echo $ress['nomerschetks'];?></p><?}?></td>
<td><?echo $ress['inn'];?></td>
<td><?echo $ress['kpp'];?></td>
<td style="width: 30%;"><?echo $ress['ogrn'];?></td>
<td><?echo $ress['name'];?></td>
<td><?echo schet_tip_text($ress);?></td>
<td style="background:#C1E5FB;"><?echo $ress['priceks'];?></td>
<td style="<?echo $color;?>"><?echo $ress['price'];?></td>
    <td><?$userdatas = mysql_fetch_assoc(mysql_query("SELECT DATE_FORMAT(schet.datasert,'%d.%m.%Y') as sert,DATE_FORMAT(schet.dataprod,'%d.%m.%Y') as prod,dataprod,prodlens,prodlenks,datasert FROM schet WHERE ns = '".$ress['shetold']."' "));
        if($userdatas['dataprod']!=""&&$userdatas['datasert']=="") {echo $userdatas['prod'];}if($userdatas['datasert']!=""&&$userdatas['dataprod']=="") {echo $userdatas['sert'];}if($userdatas['dataprod']!=""&&$userdatas['datasert']!=""){if($userdatas['prodlens']=="1"){echo $userdatas['prod'];}if($userdatas['prodlenks']=="1"){?><br><?echo $userdatas['sert'];}}if($userdatas['dataprod']==""&&$userdatas['datasert']==""){echo "Нет продления";}?></td>
<td style="width: 6%;background:#C1E5FB;">Счет выставлен</td>
<td style="width: 7%;"><?echo $ress['f_name'].' '.mb_substr($ress['l_name'],0,1,'UTF-8'),'. '.mb_substr($ress['o_name'],0,1,'UTF-8').'.';?></td>
<td style="width: 8%;"><ul class="schetkal"><?if($ress['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $ress['rand'];?>"id="schetkal<?echo $ress['rand'];?>"><?if($ress['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($ress['datebron']));
	$monvis=date('m', strtotime($ress['datebron']));
	$yesrvis=date('Y', strtotime($ress['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>

</td>
   <div class="modal-shadow<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $ress['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $ress['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $ress['rand'];?>' id="datebr<?echo $ress['rand'];?>" type="date"value="<?if($ress['datebron']!="0000-00-00"){echo $ress['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $ress['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $ress['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $ress['rand'];?>').show();
    $('.modal-window<?echo $ress['rand'];?>').show();
	$('#kalbr<?echo $ress['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $ress['rand'];?>').click(function () {
        if (sost == "bron") {
            if (document.getElementById("scales<?echo $ress['rand'];?>").checked == true) {
                var message = "bronyes";
            }
            datebroni = document.getElementsByClassName("datebr<?echo $ress['rand'];?>")[0].value;
            $.ajax({
                url: "bron.php",
                cache: false,
                data: "tip=bron&message=" + message + "&datebron=" + datebroni + "&rand=<?echo $ress['rand'];?>&users=<?echo $_GET['id'];?>",
                success: function (msg) {
                    var s = document.getElementById("schetkal<?echo $ress['rand'];?>");
                    s.innerHTML = msg;
                }
            });
        }
      $('.modal-shadow<?echo $ress['rand'];?>').hide();
	  $('#kalbr<?echo $ress['rand'];?>').hide();
    $('.modal-window<?echo $ress['rand'];?>').hide();
	document.getElementById("scales<?echo $ress['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $ress['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td style="width: 8%;">
<ul class="schetzvons"><?if($ress['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $ress['rand'];?>"><?if($ress['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($ress['datezvon']));
	$monvis=date('m', strtotime($ress['datezvon']));
	$yesrvis=date('Y', strtotime($ress['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $ress['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $ress['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $ress['rand'];?>' id="datezs<?echo $ress['rand'];?>" type="date"value="<?if($ress['datezvon']!="0000-00-00"){echo $ress['datezvon'];}?>">
<textarea class='form-control timezs<?echo $ress['rand'];?>' id="timezs<?echo $ress['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $ress['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $ress['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $ress['rand'];?>').show();
    $('.modal-windowzs<?echo $ress['rand'];?>').show();
	$('#kalz<?echo $ress['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $ress['rand'];?>').click(function () {
		if(sost=="zvon") {
            if (document.getElementById("scalezs<?echo $ress['rand'];?>").checked == true) {
                var message = "zvonyes";
            }
            datezvon = document.getElementsByClassName("datezs<?echo $ress['rand'];?>")[0].value;
            timezvon = document.getElementById("timezs<?echo $ress['rand'];?>").value;
                $.ajax({
                    url: "bron.php",
                    cache: false,
                    data: "tip=zvon&massage=" + message + "&datezvon=" + datezvon + "&timezvon=" + timezvon + "&rand=<?echo $ress['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function (msg) {
                        var s = document.getElementById("schetzvons<?echo $ress['rand'];?>");
                        s.innerHTML = msg;
                    }
                });
        }
      $('.modal-shadowzs<?echo $ress['rand'];?>').hide();
	  $('#kalzs<?echo $ress['rand'];?>').hide();
    $('.modal-windowzs<?echo $ress['rand'];?>').hide();
	document.getElementById("scalezs<?echo $ress['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $ress['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $ress['rand'];?>"id="movschet<?echo $ress['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $ress['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $ress['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $ress['idkli'];?>&parent=<?echo $ress['produkt'];?>&ogrn=<?echo $ress['orgh'];?>&inn=<?echo $ress['inn'];?>&kpp=<?echo $ress['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $ress['ns']?>')">Продлить</label>

                <label class="otkaz" id="otkaz<?echo $ress['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $ress['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 250px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $ress['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $ress['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $ress['rand'];?>').hide();
                                $('#kalm<?echo $ress['rand'];?>').hide();
                                $('.modal-windowm<?echo $ress['rand'];?>').hide();
                                $('#prich<?echo $ress['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $ress['rand'];?>&kto=<? echo $_GET['id'];?>&tip=<?echo $_GET['tip'];?>&tipschet=schet",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=schet&ms=<?echo $_GET['ms'];?>&na=<?echo $_GET['na'];?>&mf=<?echo $_GET['mf'];?>&ys=<?echo $_GET['ys'];?>&yf=<?echo $_GET['yf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $ress['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $ress['rand'];?>').show();
                $('.modal-windowm<?echo $ress['rand'];?>').show();
                $('#kalm<?echo $ress['rand'];?>').show();
            });
            $('#otkaz<?echo $ress['rand'];?>').click(function () {

                $('#prich<?echo $ress['rand'];?>').show();

            });
            $('#vist<?echo $ress['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $ress['rand'];?>').hide();
                $('#kalm<?echo $ress['rand'];?>').hide();
                $('.modal-windowm<?echo $ress['rand'];?>').hide();
                $('#prich<?echo $ress['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $ress['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $ress['rand'];?>').hide();
                $('#kalm<?echo $ress['rand'];?>').hide();
                $('.modal-windowm<?echo $ress['rand'];?>').hide();
                $('#prich<?echo $ress['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $ress['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $ress['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $ress['ogrn']);?>&kli=<? echo $ress['idkli'];?>&lico=<? echo $ress['lico'];?>&gr=<? echo $ress['gr'];?>&nomerschet=<? echo $ress['ns'];?>&produkt=<? echo $ress['produkt'];?>&inn=<? echo $ress['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $ress['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $ress['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $ress['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>

<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{?>
<?php $rs = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.idkli,schet.gr,schet.ogrn,schet.url,schet.lico,schet.datasert,dataprod,schet.shetold,schet.oplachenks,schet.oplachen,schet.nomerschetks,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg,schet.produkt,DATE_FORMAT(schet.datebron,'%d') as date_regd,DATE_FORMAT(schet.datebron,'%m') as date_regm,DATE_FORMAT(schet.datebron,'%Y') as date_regy,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.del!='1'AND schet.oplachenks!= '1' and schet.oplachen != '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' group by schet.ns ");
while($ress = mysql_fetch_assoc($rs))  : ?>
<tr value="<?php echo $ress['id'];?> "class="schetdol">
<?$h++;
if($ress['yes']=="11"||$ress['yes']=="1")
{
	$color="background:#FFF850;";
}
else
{
	$color="background:#C1E5FB;";
}
?>

<td><?echo $h;?></td>
<td style="background:#C1E5FB;" id="datasch<?echo $ress['rand'];?>"><?php
$dayvis=$ress['d'];
	$monvis=$ress['m'];
	$yesrvis=$ress['y'];
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;?></td>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('datasch<?echo $ress['rand'];?>').innerHTML;
document.getElementById('datasch<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td style="background:#C1E5FB;"><?php echo $ress['ns'];?><?if($ress['nomerschetks']!=''){?><p><?php echo $ress['nomerschetks'];?></p><?}?></td>
<td><?echo $ress['inn'];?></td>
<td><?echo $ress['kpp'];?></td>
<td style="width: 30%;"><?echo $ress['ogrn'];?></td>
<td><?echo $ress['name'];?></td>
<td><?echo schet_tip_text($ress);?></td>
<td style="background:#C1E5FB;"><?echo $ress['priceks'];?></td>
<td style="<?echo $color;?>"><?echo $ress['price'];?></td>
<td ><p id="datpr<?echo $ress['rand'];?>"><?$userdatas = mysql_fetch_assoc(mysql_query("SELECT DATE_FORMAT(schet.datasert,'%Y.%m.%d') as sert,DATE_FORMAT(schet.dataprod,'%Y.%m.%d') as prod,dataprod,prodlens,prodlenks,datasert FROM schet WHERE ns = '".$ress['shetold']."' "));
   if($userdatas['dataprod']!=""&&$userdatas['datasert']=="") {echo $userdatas['prod'];}if($userdatas['datasert']!=""&&$userdatas['dataprod']=="") {echo $userdatas['sert'];}if($userdatas['dataprod']!=""&&$userdatas['datasert']!=""){if($userdatas['prodlens']=="1"){echo $userdatas['prod'];}if($userdatas['prodlenks']=="1"){?><br><?echo $userdatas['sert'];}}if($userdatas['dataprod']==""&&$userdatas['datasert']==""){echo "Нет продления";}?></p></td>
   <script> $(function() {
    $("#myTable1").tablesorter();
    });
    var dat=document.getElementById('datpr<?echo $ress['rand'];?>').innerHTML;

    document.getElementById('datpr<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');</script>

    <td style="width: 6%;background:#C1E5FB;">Счет выставлен</td>
<td style="width: 7%;"><?echo $ress['f_name'].' '.mb_substr($ress['l_name'],0,1,'UTF-8'),'. '.mb_substr($ress['o_name'],0,1,'UTF-8').'.';?></td>
<td style="width: 8%;">
<ul class="schetkal"><?if($ress['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $ress['rand'];?>"id="schetkal<?echo $ress['rand'];?>"><?if($ress['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($ress['datebron']));
	$monvis=date('m', strtotime($ress['datebron']));
	$yesrvis=date('Y', strtotime($ress['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>

</td>
<div class="modal-shadow<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $ress['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $ress['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $ress['rand'];?>' id="datebr<?echo $ress['rand'];?>" type="date"value="<?if($ress['datebron']!="0000-00-00"){echo $ress['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $ress['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $ress['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $ress['rand'];?>').show();
    $('.modal-window<?echo $ress['rand'];?>').show();
	$('#kalbr<?echo $ress['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $ress['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $ress['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $ress['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $ress['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $ress['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $ress['rand'];?>').hide();
	  $('#kalbr<?echo $ress['rand'];?>').hide();
    $('.modal-window<?echo $ress['rand'];?>').hide();
	document.getElementById("scales<?echo $ress['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $ress['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td style="width: 8%;">
<ul class="schetzvons"><?if($ress['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $ress['rand'];?>"><?if($ress['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($ress['datezvon']));
	$monvis=date('m', strtotime($ress['datezvon']));
	$yesrvis=date('Y', strtotime($ress['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>

</td>
   <div class="modal-shadowzs<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $ress['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $ress['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $ress['rand'];?>' id="datezs<?echo $ress['rand'];?>" type="date"value="<?if($ress['datezvon']!="0000-00-00"){echo $ress['datezvon'];}?>">
<textarea class='form-control timezs<?echo $ress['rand'];?>' id="timezs<?echo $ress['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $ress['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $ress['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $ress['rand'];?>').show();
    $('.modal-windowzs<?echo $ress['rand'];?>').show();
	$('#kalzs<?echo $ress['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $ress['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $ress['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $ress['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $ress['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $ress['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $ress['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $ress['rand'];?>').hide();
	  $('#kalzs<?echo $ress['rand'];?>').hide();
    $('.modal-windowzs<?echo $ress['rand'];?>').hide();
	document.getElementById("scalezs<?echo $ress['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $ress['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
</td>
    <td value="<?echo $ress['rand'];?>"id="movschet<?echo $ress['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $ress['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $ress['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $ress['idkli'];?>&parent=<?echo $ress['produkt'];?>&ogrn=<?echo $ress['orgh'];?>&inn=<?echo $ress['inn'];?>&kpp=<?echo $ress['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $ress['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $ress['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $ress['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 250px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $ress['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $ress['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $ress['rand'];?>').hide();
                                $('#kalm<?echo $ress['rand'];?>').hide();
                                $('.modal-windowm<?echo $ress['rand'];?>').hide();
                                $('#prich<?echo $ress['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $ress['rand'];?>&tip=<?echo $_GET['tip'];?>&kto=<? echo $_GET['id'];?>&tipschet=schet",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=schet&ms=<?echo $_GET['ms'];?>&na=<?echo $_GET['na'];?>&mf=<?echo $_GET['mf'];?>&ys=<?echo $_GET['ys'];?>&yf=<?echo $_GET['yf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $ress['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $ress['rand'];?>').show();
                $('.modal-windowm<?echo $ress['rand'];?>').show();
                $('#kalm<?echo $ress['rand'];?>').show();
            });
            $('#otkaz<?echo $ress['rand'];?>').click(function () {

                $('#prich<?echo $ress['rand'];?>').show();

            });
            $('#vist<?echo $ress['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $ress['rand'];?>').hide();
                $('#kalm<?echo $ress['rand'];?>').hide();
                $('.modal-windowm<?echo $ress['rand'];?>').hide();
                $('#prich<?echo $ress['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $ress['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $ress['rand'];?>').hide();
                $('#kalm<?echo $ress['rand'];?>').hide();
                $('.modal-windowm<?echo $ress['rand'];?>').hide();
                $('#prich<?echo $ress['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $ress['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $ress['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $ress['ogrn']);?>&kli=<? echo $ress['idkli'];?>&lico=<? echo $ress['lico'];?>&gr=<? echo $ress['gr'];?>&nomerschet=<? echo $ress['ns'];?>&produkt=<? echo $ress['produkt'];?>&inn=<? echo $ress['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $ress['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $ress['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $ress['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>


<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="oplachen"){
if ($_GET['tipi']=="1"){
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '' or schet.status = '1'or schet.status = '2'or schet.status = '3'or schet.status = '4'or schet.status = '6'or schet.status = '7'or schet.status = '16'or schet.status = '19'or schet.status = '20')";
	}
	if($_GET['ogr']=="22")
	{
		$status="(schet.status = '' or schet.status = '35'or schet.status = '37'or schet.status = '38'or schet.status = '39'or schet.status = '40'or schet.status = '41'or schet.status = '42'or schet.status = '43')";
	}
	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '' or schet.status = '44'or schet.status = '45'or schet.status = '47'or schet.status = '48'or schet.status = '49')";
	}?>

<?php $ro = mysql_query("SELECT schet.id,schet.ns,schet.ogrn as orgh,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.idkli,schet.url,schet.gr,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.ogrn,schet.lico,schet.oplachen,schet.nomerschetks,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($reso = mysql_fetch_assoc($ro))  : ?>
<tr value="<?php echo $reso['id'];?> "class="schetopl">
<?$h++;
if($reso['yes']=="11"||$reso['yes']=="1")
{
	$color="background:#FFF850;"."color:#666;";
}
else
{
	$color="background:#FFF850;";
}
?>

<td><?echo $h;?></td>
<td style="background:#FFF850;"><?php echo $reso['d'].".".$reso['m'].".".$reso['y'];?></td>
<td style="background:#FFF850;"><?php echo $reso['ns'];?><?if($reso['nomerschetks']!=''){?><p><?php echo $reso['nomerschetks'];?></p><?}?></td>
<td><?echo $reso['inn'];?></td>
<td><?echo $reso['kpp'];?></td>
<td style="width: 30%;"><?echo $reso['ogrn'];?></td>
<td><?echo $reso['name'];?></td>
<td><?echo schet_tip_text($reso);?></td>
<td style="background:#FFF850;"><?echo $reso['priceks'];?></td>
<td style="<?echo $color;?>"><?echo $reso['price'];?></td>
<td><?echo $reso['dataprod'];?></td>
<td style="width: 6%;background:#FFF850;">
<?if ($reso['status']==''){
	echo"в работе";
}
if ($reso['status']=='1'){
	echo"Ждем документы";
}
if ($reso['status']=='2'){
	echo"На проверке";
}
if ($reso['status']=='3'){
	echo"Отклонен";
}
if ($reso['status']=='4'){
	echo"Проверен";
}
if ($reso['status']=='6'){
	echo"Ожидание кассы";
}
if ($reso['status']=='7'){
	echo"Ожидание кассы клиента";
}
if ($reso['status']=='16'){
	echo"Выезд";
}
if ($reso['status']=='19'){
	echo"Получение в лич.каб.";
}
if ($reso['status']=='20'){
	echo"Получение в офисе";
}
if ($reso['status']=='35'){
	echo"Заявка";
}
if ($reso['status']=='37'){
	echo"Ждем опись";
}
if ($reso['status']=='38'){
	echo"Опись принята";
}
if ($reso['status']=='39'){
	echo"Опись передана менеджеру";
}
if ($reso['status']=='40'){
	echo"Отправить в ГС1";
}
if ($reso['status']=='41'){
	echo"Ждем КИЗ";
}
if ($reso['status']=='42'){
	echo"Маркировка КИЗ без оборудования";
}
if ($reso['status']=='43'){
	echo"Маркировка КИЗ с оборудования";
}
if ($reso['status']=='44'){
	echo"Оплачен в ТС";
}
if ($reso['status']=='45'){
	echo"Ждем ККТ";
}
if ($reso['status']=='47'){
	echo"Товар получен";
}
if ($reso['status']=='48'){
	echo"Товар получен без ФН";
}
if ($reso['status']=='49'){
	echo"На продлении";
}
?>

</td>
<td style="width: 7%;"><?echo $reso['f_name'].' '.mb_substr($reso['l_name'],0,1,'UTF-8'),'. '.mb_substr($reso['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($reso['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $reso['rand'];?>"id="schetkal<?echo $reso['rand'];?>"><?if($reso['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($reso['datebron']));
	$monvis=date('m', strtotime($reso['datebron']));
	$yesrvis=date('Y', strtotime($reso['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $reso['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $reso['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $reso['rand'];?>' id="datebr<?echo $reso['rand'];?>" type="date"value="<?if($reso['datebron']!="0000-00-00"){echo $reso['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $reso['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $reso['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $reso['rand'];?>').show();
    $('.modal-window<?echo $reso['rand'];?>').show();
	$('#kalbr<?echo $reso['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $reso['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $reso['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $reso['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $reso['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $reso['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $reso['rand'];?>').hide();
	  $('#kalbr<?echo $reso['rand'];?>').hide();
    $('.modal-window<?echo $reso['rand'];?>').hide();
	document.getElementById("scales<?echo $reso['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $reso['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $reso['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($reso['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $reso['rand'];?>"><?if($reso['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($reso['datezvon']));
	$monvis=date('m', strtotime($reso['datezvon']));
	$yesrvis=date('Y', strtotime($reso['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $reso['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $reso['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $reso['rand'];?>' id="datezs<?echo $reso['rand'];?>" type="date"value="<?if($reso['datezvon']!="0000-00-00"){echo $reso['datezvon'];}?>">
<textarea class='form-control timezs<?echo $reso['rand'];?>' id="timezs<?echo $reso['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $reso['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $reso['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $reso['rand'];?>').show();
    $('.modal-windowzs<?echo $reso['rand'];?>').show();
	$('#kalz<?echo $reso['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $reso['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $reso['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $reso['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $reso['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $reso['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $reso['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $reso['rand'];?>').hide();
	  $('#kalzs<?echo $reso['rand'];?>').hide();
    $('.modal-windowzs<?echo $reso['rand'];?>').hide();
	document.getElementById("scalezs<?echo $reso['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $reso['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $reso['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $reso['rand'];?>"id="movschet<?echo $reso['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $reso['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $reso['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $reso['idkli'];?>&parent=<?echo $reso['produkt'];?>&ogrn=<?echo $reso['orgh'];?>&inn=<?echo $reso['inn'];?>&kpp=<?echo $reso['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $reso['ns']?>')">Продлить</label>

                <label class="otkaz" id="otkaz<?echo $reso['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $reso['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $reso['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $reso['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $reso['rand'];?>').hide();
                                $('#kalm<?echo $reso['rand'];?>').hide();
                                $('.modal-windowm<?echo $reso['rand'];?>').hide();
                                $('#prich<?echo $reso['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $reso['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=oplachen",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=oplachen&ms=<?echo $_GET['ms'];?>&na=<?echo $_GET['na'];?>&mf=<?echo $_GET['mf'];?>&ys=<?echo $_GET['ys'];?>&yf=<?echo $_GET['yf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $reso['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $reso['rand'];?>').show();
                $('.modal-windowm<?echo $reso['rand'];?>').show();
                $('#kalm<?echo $reso['rand'];?>').show();
            });
            $('#otkaz<?echo $reso['rand'];?>').click(function () {

                $('#prich<?echo $reso['rand'];?>').show();

            });
            $('#vist<?echo $reso['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $reso['rand'];?>').hide();
                $('#kalm<?echo $reso['rand'];?>').hide();
                $('.modal-windowm<?echo $reso['rand'];?>').hide();
                $('#prich<?echo $reso['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $reso['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $reso['rand'];?>').hide();
                $('#kalm<?echo $reso['rand'];?>').hide();
                $('.modal-windowm<?echo $reso['rand'];?>').hide();
                $('#prich<?echo $reso['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $reso['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $reso['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $reso['ogrn']);?>&kli=<? echo $reso['idkli'];?>&lico=<? echo $reso['lico'];?>&gr=<? echo $reso['gr'];?>&nomerschet=<? echo $reso['ns'];?>&produkt=<? echo $reso['produkt'];?>&inn=<? echo $reso['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $reso['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $reso['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $reso['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '' or schet.status = '1'or schet.status = '2'or schet.status = '3'or schet.status = '4'or schet.status = '6'or schet.status = '7'or schet.status = '16'or schet.status = '19'or schet.status = '20')";
	}
	if($_GET['ogr']=="22")
	{
		$status="(schet.status = '' or schet.status = '35'or schet.status = '37'or schet.status = '38'or schet.status = '39'or schet.status = '40'or schet.status = '41'or schet.status = '42'or schet.status = '43')";
	}
	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '' or schet.status = '44'or schet.status = '45'or schet.status = '47'or schet.status = '48'or schet.status = '49')";
	}?>
<?php $ro = mysql_query("SELECT schet.id,schet.ns,schet.ogrn as orgh,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($reso = mysql_fetch_assoc($ro))  : ?>
<tr value="<?php echo $reso['id'];?> "class="schetopl">
<?$h++;
if($reso['yes']=="11"||$reso['yes']=="1")
{
	$color="background:#FFF850;"."color:#666;";
}
else
{
	$color="background:#FFF850;";
}
?>

<td><?echo $h;?></td>
<td style="background:#FFF850;"><?php echo $reso['d'].".".$reso['m'].".".$reso['y'];?></td>
<td style="background:#FFF850;"><?php echo $reso['ns'];?><?if($reso['nomerschetks']!=''){?><p><?php echo $reso['nomerschetks'];?></p><?}?></td>
<td><?echo $reso['inn'];?></td>
<td><?echo $reso['kpp'];?></td>
<td style="width: 30%;"><?echo $reso['ogrn'];?></td>
<td><?echo $reso['name'];?></td>
<td><?echo schet_tip_text($reso);?></td>
<td style="background:#FFF850;"><?echo $reso['priceks'];?></td>
<td style="<?echo $color;?>"><?echo $reso['price'];?></td>
<td><?echo $reso['dataprod'];?></td>
<td style="width: 6%;background:#FFF850;"><?if ($reso['status']==''){
	echo"в работе";
}
if ($reso['status']=='1'){
	echo"Ждем документы";
}
if ($reso['status']=='2'){
	echo"На проверке";
}
if ($reso['status']=='3'){
	echo"Отклонен";
}
if ($reso['status']=='4'){
	echo"Проверен";
}
if ($reso['status']=='6'){
	echo"Ожидание кассы";
}
if ($reso['status']=='7'){
	echo"Ожидание кассы клиента";
}
if ($reso['status']=='16'){
	echo"Выезд";
}
if ($reso['status']=='19'){
	echo"Получение в лич.каб.";
}
if ($reso['status']=='20'){
	echo"Получение в офисе";
}
if ($reso['status']=='35'){
	echo"Заявка";
}
if ($reso['status']=='37'){
	echo"Ждем опись";
}
if ($reso['status']=='38'){
	echo"Опись принята";
}
if ($reso['status']=='39'){
	echo"Опись передана менеджеру";
}
if ($reso['status']=='40'){
	echo"Отправить в ГС1";
}
if ($reso['status']=='41'){
	echo"Ждем КИЗ";
}
if ($reso['status']=='42'){
	echo"Маркировка КИЗ без оборудования";
}
if ($reso['status']=='43'){
	echo"Маркировка КИЗ с оборудования";
}
if ($reso['status']=='44'){
	echo"Оплачен в ТС";
}
if ($reso['status']=='45'){
	echo"Ждем ККТ";
}
if ($reso['status']=='47'){
	echo"Товар получен";
}
if ($reso['status']=='48'){
	echo"Товар получен без ФН";
}
if ($reso['status']=='49'){
	echo"На продлении";
}
?></td>
<td style="width: 7%;"><?echo $reso['f_name'].' '.mb_substr($reso['l_name'],0,1,'UTF-8'),'. '.mb_substr($reso['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($reso['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $reso['rand'];?>"id="schetkal<?echo $reso['rand'];?>"><?if($reso['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($reso['datebron']));
	$monvis=date('m', strtotime($reso['datebron']));
	$yesrvis=date('Y', strtotime($reso['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $reso['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $reso['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $reso['rand'];?>' id="datebr<?echo $reso['rand'];?>" type="date"value="<?if($reso['datebron']!="0000-00-00"){echo $reso['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $reso['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $reso['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $reso['rand'];?>').show();
    $('.modal-window<?echo $reso['rand'];?>').show();
	$('#kalbr<?echo $reso['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $reso['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $reso['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $reso['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $reso['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $reso['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $reso['rand'];?>').hide();
	  $('#kalbr<?echo $reso['rand'];?>').hide();
    $('.modal-window<?echo $reso['rand'];?>').hide();
	document.getElementById("scales<?echo $reso['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $reso['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $reso['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($reso['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $reso['rand'];?>"><?if($reso['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($reso['datezvon']));
	$monvis=date('m', strtotime($reso['datezvon']));
	$yesrvis=date('Y', strtotime($reso['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $reso['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $reso['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $reso['rand'];?>' id="datezs<?echo $reso['rand'];?>" type="date"value="<?if($reso['datezvon']!="0000-00-00"){echo $reso['datezvon'];}?>">
<textarea class='form-control timezs<?echo $reso['rand'];?>' id="timezs<?echo $reso['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $reso['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $reso['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $reso['rand'];?>').show();
    $('.modal-windowzs<?echo $reso['rand'];?>').show();
	$('#kalz<?echo $reso['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $reso['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $reso['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $reso['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $reso['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $reso['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $reso['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $reso['rand'];?>').hide();
	  $('#kalzs<?echo $reso['rand'];?>').hide();
    $('.modal-windowzs<?echo $reso['rand'];?>').hide();
	document.getElementById("scalezs<?echo $reso['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $reso['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $reso['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $reso['rand'];?>"id="movschet<?echo $reso['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $reso['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $reso['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $reso['idkli'];?>&parent=<?echo $reso['produkt'];?>&ogrn=<?echo $reso['orgh'];?>&inn=<?echo $reso['inn'];?>&kpp=<?echo $reso['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $reso['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $reso['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $reso['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $reso['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $reso['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $reso['rand'];?>').hide();
                                $('#kalm<?echo $reso['rand'];?>').hide();
                                $('.modal-windowm<?echo $reso['rand'];?>').hide();
                                $('#prich<?echo $reso['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $reso['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=oplachen",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=oplachen&ms=<?echo $_GET['ms'];?>&na=<?echo $_GET['na'];?>&mf=<?echo $_GET['mf'];?>&ys=<?echo $_GET['ys'];?>&yf=<?echo $_GET['yf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $reso['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $reso['rand'];?>').show();
                $('.modal-windowm<?echo $reso['rand'];?>').show();
                $('#kalm<?echo $reso['rand'];?>').show();
            });
            $('#otkaz<?echo $reso['rand'];?>').click(function () {

                $('#prich<?echo $reso['rand'];?>').show();

            });
            $('#vist<?echo $reso['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $reso['rand'];?>').hide();
                $('#kalm<?echo $reso['rand'];?>').hide();
                $('.modal-windowm<?echo $reso['rand'];?>').hide();
                $('#prich<?echo $reso['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $reso['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $reso['rand'];?>').hide();
                $('#kalm<?echo $reso['rand'];?>').hide();
                $('.modal-windowm<?echo $reso['rand'];?>').hide();
                $('#prich<?echo $reso['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $reso['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $reso['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $reso['ogrn']);?>&kli=<? echo $reso['idkli'];?>&lico=<? echo $reso['lico'];?>&gr=<? echo $reso['gr'];?>&nomerschet=<? echo $reso['ns'];?>&produkt=<? echo $reso['produkt'];?>&inn=<? echo $reso['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $reso['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $reso['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $reso['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="postavka"){
if ($_GET['tipi']=="1"){?>
<?php $rpos = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.ogrn as orgh,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.status = '5'and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($respos = mysql_fetch_assoc($rpos))  : ?>
<tr value="<?php echo $respos['id'];?> "class="schetpos">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#E9C3FB;"><?php echo $respos['d'].".".$respos['m'].".".$respos['y'];?></td>
<td style="background:#E9C3FB;"><?php echo $respos['ns'];?><?if($respos['nomerschetks']!=''){?><p><?php echo $respos['nomerschetks'];?></p><?}?></td>
<td><?echo $respos['inn'];?></td>
<td><?echo $respos['kpp'];?></td>
<td style="width: 30%;"><?echo $respos['ogrn'];?></td>
<td><?echo $respos['name'];?></td>
<td><?echo schet_tip_text($respos);?></td>
<td style="background:#E9C3FB;"><?echo $respos['priceks'];?></td>
<td style="background:#E9C3FB;"><?echo $respos['price'];?></td>
<td><?echo $respos['dataprod'];?></td>
<td style="width: 6%;background:#E9C3FB;">Поставка</td>
<td style="width: 7%;"><?echo $respos['f_name'].' '.mb_substr($respos['l_name'],0,1,'UTF-8'),'. '.mb_substr($respos['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($respos['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $respos['rand'];?>"id="schetkal<?echo $respos['rand'];?>"><?if($respos['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($respos['datebron']));
	$monvis=date('m', strtotime($respos['datebron']));
	$yesrvis=date('Y', strtotime($respos['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $respos['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $respos['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $respos['rand'];?>' id="datebr<?echo $respos['rand'];?>" type="date"value="<?if($respos['datebron']!="0000-00-00"){echo $respos['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $respos['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $respos['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $respos['rand'];?>').show();
    $('.modal-window<?echo $respos['rand'];?>').show();
	$('#kalbr<?echo $respos['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $respos['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $respos['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $respos['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $respos['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $respos['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $respos['rand'];?>').hide();
	  $('#kalbr<?echo $respos['rand'];?>').hide();
    $('.modal-window<?echo $respos['rand'];?>').hide();
	document.getElementById("scales<?echo $respos['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $respos['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $respos['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($respos['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $respos['rand'];?>"><?if($respos['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($respos['datezvon']));
	$monvis=date('m', strtotime($respos['datezvon']));
	$yesrvis=date('Y', strtotime($respos['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $respos['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $respos['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $respos['rand'];?>' id="datezs<?echo $respos['rand'];?>" type="date"value="<?if($respos['datezvon']!="0000-00-00"){echo $respos['datezvon'];}?>">
<textarea class='form-control timezs<?echo $respos['rand'];?>' id="timezs<?echo $respos['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $respos['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $respos['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $respos['rand'];?>').show();
    $('.modal-windowzs<?echo $respos['rand'];?>').show();
	$('#kalz<?echo $respos['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $respos['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $respos['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $respos['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $respos['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $respos['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $respos['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $respos['rand'];?>').hide();
	  $('#kalzs<?echo $respos['rand'];?>').hide();
    $('.modal-windowzs<?echo $respos['rand'];?>').hide();
	document.getElementById("scalezs<?echo $respos['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $respos['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $respos['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $respos['rand'];?>"id="movschet<?echo $respos['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $respos['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $respos['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $respos['idkli'];?>&parent=<?echo $respos['produkt'];?>&ogrn=<?echo $respos['orgh'];?>&inn=<?echo $respos['inn'];?>&kpp=<?echo $respos['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $respos['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $respos['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $respos['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $respos['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $respos['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $respos['rand'];?>').hide();
                                $('#kalm<?echo $respos['rand'];?>').hide();
                                $('.modal-windowm<?echo $respos['rand'];?>').hide();
                                $('#prich<?echo $respos['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $respos['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=postavka",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=postavka&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $respos['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respos['rand'];?>').show();
                $('.modal-windowm<?echo $respos['rand'];?>').show();
                $('#kalm<?echo $respos['rand'];?>').show();
            });
            $('#otkaz<?echo $respos['rand'];?>').click(function () {

                $('#prich<?echo $respos['rand'];?>').show();

            });
            $('#vist<?echo $respos['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respos['rand'];?>').hide();
                $('#kalm<?echo $respos['rand'];?>').hide();
                $('.modal-windowm<?echo $respos['rand'];?>').hide();
                $('#prich<?echo $respos['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $respos['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respos['rand'];?>').hide();
                $('#kalm<?echo $respos['rand'];?>').hide();
                $('.modal-windowm<?echo $respos['rand'];?>').hide();
                $('#prich<?echo $respos['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $respos['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $respos['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $respos['ogrn']);?>&kli=<? echo $respos['idkli'];?>&lico=<? echo $respos['lico'];?>&gr=<? echo $respos['gr'];?>&nomerschet=<? echo $respos['ns'];?>&produkt=<? echo $respos['produkt'];?>&inn=<? echo $respos['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $respos['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $respos['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $respos['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{?>
<?php $rpos = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.ogrn as orgh,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.status = '5'and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($respos = mysql_fetch_assoc($rpos))  : ?>
<tr value="<?php echo $respos['id'];?> "class="schetpos">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#E9C3FB;"><?php echo $respos['d'].".".$respos['m'].".".$respos['y'];?></td>
<td style="background:#E9C3FB;"><?php echo $respos['ns'];?><?if($respos['nomerschetks']!=''){?><p><?php echo $respos['nomerschetks'];?></p><?}?></td>
<td><?echo $respos['inn'];?></td>
<td><?echo $respos['kpp'];?></td>
<td style="width: 30%;"><?echo $respos['ogrn'];?></td>
<td><?echo $respos['name'];?></td>
<td><?echo schet_tip_text($respos);?></td>
<td style="background:#E9C3FB;"><?echo $respos['priceks'];?></td>
<td style="background:#E9C3FB;"><?echo $respos['price'];?></td>
<td><?echo $respos['dataprod'];?></td>
<td style="width: 6%;background:#E9C3FB;">Поставка</td>
<td style="width: 7%;"><?echo $respos['f_name'].' '.mb_substr($respos['l_name'],0,1,'UTF-8'),'. '.mb_substr($respos['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($respos['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $respos['rand'];?>"id="schetkal<?echo $respos['rand'];?>"><?if($respos['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($respos['datebron']));
	$monvis=date('m', strtotime($respos['datebron']));
	$yesrvis=date('Y', strtotime($respos['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $respos['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $respos['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $respos['rand'];?>' id="datebr<?echo $respos['rand'];?>" type="date"value="<?if($respos['datebron']!="0000-00-00"){echo $respos['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $respos['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $respos['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $respos['rand'];?>').show();
    $('.modal-window<?echo $respos['rand'];?>').show();
	$('#kalbr<?echo $respos['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $respos['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $respos['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $respos['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $respos['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $respos['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $respos['rand'];?>').hide();
	  $('#kalbr<?echo $respos['rand'];?>').hide();
    $('.modal-window<?echo $respos['rand'];?>').hide();
	document.getElementById("scales<?echo $respos['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $respos['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $respos['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($respos['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $respos['rand'];?>"><?if($respos['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($respos['datezvon']));
	$monvis=date('m', strtotime($respos['datezvon']));
	$yesrvis=date('Y', strtotime($respos['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $respos['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $respos['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $respos['rand'];?>' id="datezs<?echo $respos['rand'];?>" type="date"value="<?if($respos['datezvon']!="0000-00-00"){echo $respos['datezvon'];}?>">
<textarea class='form-control timezs<?echo $respos['rand'];?>' id="timezs<?echo $respos['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $respos['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $respos['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $respos['rand'];?>').show();
    $('.modal-windowzs<?echo $respos['rand'];?>').show();
	$('#kalz<?echo $respos['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $respos['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $respos['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $respos['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $respos['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $respos['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $respos['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $respos['rand'];?>').hide();
	  $('#kalzs<?echo $respos['rand'];?>').hide();
    $('.modal-windowzs<?echo $respos['rand'];?>').hide();
	document.getElementById("scalezs<?echo $respos['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $respos['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $respos['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $respos['rand'];?>"id="movschet<?echo $respos['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $respos['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $respos['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $respos['idkli'];?>&parent=<?echo $respos['produkt'];?>&ogrn=<?echo $respos['orgh'];?>&inn=<?echo $respos['inn'];?>&kpp=<?echo $respos['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $respos['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $respos['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $respos['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $respos['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $respos['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $respos['rand'];?>').hide();
                                $('#kalm<?echo $respos['rand'];?>').hide();
                                $('.modal-windowm<?echo $respos['rand'];?>').hide();
                                $('#prich<?echo $respos['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $respos['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=postavka",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=postavka&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $respos['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respos['rand'];?>').show();
                $('.modal-windowm<?echo $respos['rand'];?>').show();
                $('#kalm<?echo $respos['rand'];?>').show();
            });
            $('#otkaz<?echo $respos['rand'];?>').click(function () {

                $('#prich<?echo $respos['rand'];?>').show();

            });
            $('#vist<?echo $respos['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respos['rand'];?>').hide();
                $('#kalm<?echo $respos['rand'];?>').hide();
                $('.modal-windowm<?echo $respos['rand'];?>').hide();
                $('#prich<?echo $respos['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $respos['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respos['rand'];?>').hide();
                $('#kalm<?echo $respos['rand'];?>').hide();
                $('.modal-windowm<?echo $respos['rand'];?>').hide();
                $('#prich<?echo $respos['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $respos['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $respos['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $respos['ogrn']);?>&kli=<? echo $respos['idkli'];?>&lico=<? echo $respos['lico'];?>&gr=<? echo $respos['gr'];?>&nomerschet=<? echo $respos['ns'];?>&produkt=<? echo $respos['produkt'];?>&inn=<? echo $respos['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $respos['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $respos['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $respos['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
}?>
<?if($_GET['tip']=="ystanovka"){
if ($_GET['tipi']=="1"){
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '17' or schet.status='18' or schet.status='161')";
	}
	if($_GET['ogr']=="22")
	{
		$status="(schet.status = '36')";
	}
	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '50' or schet.status = '51')";
	}?>
<?php $ryv = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.ogrn as orgh,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0'and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resyv = mysql_fetch_assoc($ryv))  : ?>
<tr value="<?php echo $resyv['id'];?> "class="schetyv">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#FFB366;"><?php echo $resyv['d'].".".$resyv['m'].".".$resyv['y'];?></td>
<td style="background:#FFB366;"><?php echo $resyv['ns'];?><?if($resyv['nomerschetks']!=''){?><p><?php echo $resyv['nomerschetks'];?></p><?}?></td>
<td><?echo $resyv['inn'];?></td>
<td><?echo $resyv['kpp'];?></td>
<td style="width: 30%;"><?echo $resyv['ogrn'];?></td>
<td><?echo $resyv['name'];?></td>
<td><?echo schet_tip_text($resyv);?></td>
<td style="background:#FFB366;"><?echo $resyv['priceks'];?></td>
<td style="background:#FFB366;"><?echo $resyv['price'];?></td>
<td><?echo $resyv['dataprod'];?></td>
<td style="width: 6%;background:#FFB366;"><?if ($resyv['status']=='17'){
	echo"Установка";
}
if ($resyv['status']=='18'){
	echo"Выезд";
}
if ($resyv['status']=='161'){
	echo"Произв.устан.";
}
if ($resyv['status']=='36'){
	echo"Регистрация + Настройка";
}
if ($resyv['status']=='50'){
	echo"Установка в офисе";
}
if ($resyv['status']=='51'){
	echo"Выезд";
}
?></td>
<td style="width: 7%;"><?echo $resyv['f_name'].' '.mb_substr($resyv['l_name'],0,1,'UTF-8'),'. '.mb_substr($resyv['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resyv['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $respos['rand'];?>"id="schetkal<?echo $resyv['rand'];?>"><?if($resyv['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resyv['datebron']));
	$monvis=date('m', strtotime($resyv['datebron']));
	$yesrvis=date('Y', strtotime($resyv['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resyv['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resyv['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resyv['rand'];?>' id="datebr<?echo $resyv['rand'];?>" type="date"value="<?if($resyv['datebron']!="0000-00-00"){echo $resyv['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resyv['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resyv['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resyv['rand'];?>').show();
    $('.modal-window<?echo $resyv['rand'];?>').show();
	$('#kalbr<?echo $resyv['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resyv['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resyv['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resyv['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resyv['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resyv['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resyv['rand'];?>').hide();
	  $('#kalbr<?echo $resyv['rand'];?>').hide();
    $('.modal-window<?echo $resyv['rand'];?>').hide();
	document.getElementById("scales<?echo $resyv['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resyv['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resyv['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resyv['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resyv['rand'];?>"><?if($resyv['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resyv['datezvon']));
	$monvis=date('m', strtotime($resyv['datezvon']));
	$yesrvis=date('Y', strtotime($resyv['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resyv['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resyv['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resyv['rand'];?>' id="datezs<?echo $resyv['rand'];?>" type="date"value="<?if($resyv['datezvon']!="0000-00-00"){echo $resyv['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resyv['rand'];?>' id="timezs<?echo $resyv['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resyv['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resyv['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resyv['rand'];?>').show();
    $('.modal-windowzs<?echo $resyv['rand'];?>').show();
	$('#kalz<?echo $resyv['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resyv['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resyv['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resyv['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resyv['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resyv['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resyv['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resyv['rand'];?>').hide();
	  $('#kalzs<?echo $resyv['rand'];?>').hide();
    $('.modal-windowzs<?echo $resyv['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resyv['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resyv['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resyv['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resyv['rand'];?>"id="movschet<?echo $resyv['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resyv['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resyv['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resyv['idkli'];?>&parent=<?echo $resyv['produkt'];?>&ogrn=<?echo $resyv['orgh'];?>&inn=<?echo $resyv['inn'];?>&kpp=<?echo $resyv['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resyv['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resyv['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resyv['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resyv['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resyv['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resyv['rand'];?>').hide();
                                $('#kalm<?echo $resyv['rand'];?>').hide();
                                $('.modal-windowm<?echo $resyv['rand'];?>').hide();
                                $('#prich<?echo $resyv['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resyv['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=ystanovka",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=ystanovka&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&users=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resyv['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resyv['rand'];?>').show();
                $('.modal-windowm<?echo $resyv['rand'];?>').show();
                $('#kalm<?echo $resyv['rand'];?>').show();
            });
            $('#otkaz<?echo $resyv['rand'];?>').click(function () {

                $('#prich<?echo $resyv['rand'];?>').show();

            });
            $('#vist<?echo $resyv['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resyv['rand'];?>').hide();
                $('#kalm<?echo $resyv['rand'];?>').hide();
                $('.modal-windowm<?echo $resyv['rand'];?>').hide();
                $('#prich<?echo $resyv['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resyv['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resyv['rand'];?>').hide();
                $('#kalm<?echo $resyv['rand'];?>').hide();
                $('.modal-windowm<?echo $resyv['rand'];?>').hide();
                $('#prich<?echo $resyv['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resyv['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resyv['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resyv['ogrn']);?>&kli=<? echo $resyv['idkli'];?>&lico=<? echo $resyv['lico'];?>&gr=<? echo $resyv['gr'];?>&nomerschet=<? echo $resyv['ns'];?>&produkt=<? echo $resyv['produkt'];?>&inn=<? echo $resyv['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resyv['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resyv['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resyv['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '17' or schet.status='18' or schet.status='161')";
	}
	if($_GET['ogr']=="22")
	{
		$status="(schet.status = '36')";
	}
	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '50' or schet.status = '51')";
	}?>
<?php $ryv = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resyv = mysql_fetch_assoc($ryv))  : ?>
<tr value="<?php echo $resyv['id'];?> "class="schetyv">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#FFB366;"><?php echo $resyv['d'].".".$resyv['m'].".".$resyv['y'];?></td>
<td style="background:#FFB366;"><?php echo $resyv['ns'];?><?if($resyv['nomerschetks']!=''){?><p><?php echo $resyv['nomerschetks'];?></p><?}?></td>
<td><?echo $resyv['inn'];?></td>
<td><?echo $resyv['kpp'];?></td>
<td style="width: 30%;"><?echo $resyv['ogrn'];?></td>
<td><?echo $resyv['name'];?></td>
<td><?echo schet_tip_text($resyv);?></td>
<td style="background:#FFB366;"><?echo $resyv['priceks'];?></td>
<td style="background:#FFB366;"><?echo $resyv['price'];?></td>
<td><?echo $resyv['dataprod'];?></td>
<td style="width: 6%;background:#FFB366;"><?if ($resyv['status']=='17'){
	echo"Установка";
}
if ($resyv['status']=='18'){
	echo"Выезд";
}
if ($resyv['status']=='161'){
	echo"Произв.устан.";
}
if ($resyv['status']=='36'){
	echo"Регистрация + Настройка";
}
if ($resyv['status']=='50'){
	echo"Установка в офисе";
}
if ($resyv['status']=='51'){
	echo"Выезд";
}
?></td>
<td style="width: 7%;"><?echo $resyv['f_name'].' '.mb_substr($resyv['l_name'],0,1,'UTF-8'),'. '.mb_substr($resyv['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resyv['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resyv['rand'];?>"id="schetkal<?echo $resyv['rand'];?>"><?if($resyv['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resyv['datebron']));
	$monvis=date('m', strtotime($resyv['datebron']));
	$yesrvis=date('Y', strtotime($resyv['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resyv['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resyv['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resyv['rand'];?>' id="datebr<?echo $resyv['rand'];?>" type="date"value="<?if($resyv['datebron']!="0000-00-00"){echo $resyv['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resyv['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resyv['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resyv['rand'];?>').show();
    $('.modal-window<?echo $resyv['rand'];?>').show();
	$('#kalbr<?echo $resyv['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resyv['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resyv['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resyv['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resyv['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resyv['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resyv['rand'];?>').hide();
	  $('#kalbr<?echo $resyv['rand'];?>').hide();
    $('.modal-window<?echo $resyv['rand'];?>').hide();
	document.getElementById("scales<?echo $resyv['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resyv['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resyv['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resyv['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resyv['rand'];?>"><?if($resyv['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resyv['datezvon']));
	$monvis=date('m', strtotime($resyv['datezvon']));
	$yesrvis=date('Y', strtotime($resyv['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resyv['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resyv['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resyv['rand'];?>' id="datezs<?echo $resyv['rand'];?>" type="date"value="<?if($resyv['datezvon']!="0000-00-00"){echo $resyv['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resyv['rand'];?>' id="timezs<?echo $resyv['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resyv['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resyv['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resyv['rand'];?>').show();
    $('.modal-windowzs<?echo $resyv['rand'];?>').show();
	$('#kalz<?echo $resyv['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resyv['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resyv['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resyv['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resyv['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resyv['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resyv['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resyv['rand'];?>').hide();
	  $('#kalzs<?echo $resyv['rand'];?>').hide();
    $('.modal-windowzs<?echo $resyv['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resyv['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resyv['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resyv['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resyv['rand'];?>"id="movschet<?echo $resyv['$resyv'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resyv['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resyv['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resyv['idkli'];?>&parent=<?echo $resyv['produkt'];?>&ogrn=<?echo $resyv['orgh'];?>&inn=<?echo $resyv['inn'];?>&kpp=<?echo $resyv['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resyv['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resyv['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resyv['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resyv['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resyv['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resyv['rand'];?>').hide();
                                $('#kalm<?echo $resyv['rand'];?>').hide();
                                $('.modal-windowm<?echo $resyv['rand'];?>').hide();
                                $('#prich<?echo $resyv['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resyv['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=ystanovka",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=ystanovka&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resyv['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resyv['rand'];?>').show();
                $('.modal-windowm<?echo $resyv['rand'];?>').show();
                $('#kalm<?echo $resyv['rand'];?>').show();
            });
            $('#otkaz<?echo $resyv['rand'];?>').click(function () {

                $('#prich<?echo $resyv['rand'];?>').show();

            });
            $('#vist<?echo $resyv['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resyv['rand'];?>').hide();
                $('#kalm<?echo $resyv['rand'];?>').hide();
                $('.modal-windowm<?echo $resyv['rand'];?>').hide();
                $('#prich<?echo $resyv['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resyv['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resyv['rand'];?>').hide();
                $('#kalm<?echo $resyv['rand'];?>').hide();
                $('.modal-windowm<?echo $resyv['rand'];?>').hide();
                $('#prich<?echo $resyv['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resyv['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resyv['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resyv['ogrn']);?>&kli=<? echo $resyv['idkli'];?>&lico=<? echo $resyv['lico'];?>&gr=<? echo $resyv['gr'];?>&nomerschet=<? echo $resyv['ns'];?>&produkt=<? echo $resyv['produkt'];?>&inn=<? echo $resyv['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resyv['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resyv['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resyv['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="naatk"){
if ($_GET['tipi']=="1"){
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '21' or schet.status='65')";
	}
	if($_GET['ogr']=="22")
	{
		$status="(schet.status = '77')";
	}
	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '52' or schet.status = '53'or schet.status = '60')";
	}?>
<?php $rna = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.oplachenks,schet.url,schet.oplachen,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.nomerschetks,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resrna = mysql_fetch_assoc($rna))  : ?>
<tr value="<?php echo $resrna['id'];?> "class="schetna">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#85D6D1;"><?php echo $resrna['d'].".".$resrna['m'].".".$resrna['y'];?></td>
<td style="background:#85D6D1;"><?php echo $resrna['ns'];?><?if($resrna['nomerschetks']!=''){?><p><?php echo $resrna['nomerschetks'];?></p><?}?></td>
<td><?echo $resrna['inn'];?></td>
<td><?echo $resrna['kpp'];?></td>
<td style="width: 30%;"><?echo $resrna['ogrn'];?></td>
<td><?echo $resrna['name'];?></td>
<td><?echo schet_tip_text($resrna);?></td>
<td style="background:#85D6D1;"><?echo $resrna['priceks'];?></td>
<td style="background:#85D6D1;"><?echo $resrna['price'];?></td>
<td><?echo $resrna['dataprod'];?></td>
<td style="width: 6%;background:#85D6D1;"><?
if ($resrna['status']=='21'){
	echo"Частично на отгрузке";
}
if ($resrna['status']=='65'){
	echo"На отгрузке.";
}
if ($resrna['status']=='77'){
	echo"На отгрузке.";
}
if ($resrna['status']=='52'){
	echo"Выдали";
}
if ($resrna['status']=='53'){
	echo"Жем закрывающие документы";
}
if ($resrna['status']=='60'){
	echo"На отгрузке.";
}
?></td>
<td style="width: 7%;"><?echo $resrna['f_name'].' '.mb_substr($resrna['l_name'],0,1,'UTF-8'),'. '.mb_substr($resrna['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resrna['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resrna['rand'];?>"id="schetkal<?echo $resrna['rand'];?>"><?if($resrna['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resrna['datebron']));
	$monvis=date('m', strtotime($resrna['datebron']));
	$yesrvis=date('Y', strtotime($resrna['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resrna['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resrna['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resrna['rand'];?>' id="datebr<?echo $resrna['rand'];?>" type="date"value="<?if($resrna['datebron']!="0000-00-00"){echo $resrna['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resrna['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resrna['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resrna['rand'];?>').show();
    $('.modal-window<?echo $resrna['rand'];?>').show();
	$('#kalbr<?echo $resrna['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resrna['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resrna['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resrna['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resrna['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resrna['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resrna['rand'];?>').hide();
	  $('#kalbr<?echo $resrna['rand'];?>').hide();
    $('.modal-window<?echo $resrna['rand'];?>').hide();
	document.getElementById("scales<?echo $resrna['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resrna['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resrna['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resrna['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resrna['rand'];?>"><?if($resrna['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resrna['datezvon']));
	$monvis=date('m', strtotime($resrna['datezvon']));
	$yesrvis=date('Y', strtotime($resrna['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resrna['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resrna['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resrna['rand'];?>' id="datezs<?echo $resrna['rand'];?>" type="date"value="<?if($resrna['datezvon']!="0000-00-00"){echo $resrna['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resrna['rand'];?>' id="timezs<?echo $resrna['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resrna['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resrna['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resrna['rand'];?>').show();
    $('.modal-windowzs<?echo $resrna['rand'];?>').show();
	$('#kalz<?echo $resrna['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resrna['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resrna['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resrna['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resrna['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resrna['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resrna['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resrna['rand'];?>').hide();
	  $('#kalzs<?echo $resrna['rand'];?>').hide();
    $('.modal-windowzs<?echo $resrna['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resrna['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resrna['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resrna['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resrna['rand'];?>"id="movschet<?echo $resrna['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resrna['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resrna['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resrna['idkli'];?>&parent=<?echo $resrna['produkt'];?>&ogrn=<?echo $resrna['orgh'];?>&inn=<?echo $resrna['inn'];?>&kpp=<?echo $resrna['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resrna['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resrna['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resrna['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resrna['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resrna['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resrna['rand'];?>').hide();
                                $('#kalm<?echo $resrna['rand'];?>').hide();
                                $('.modal-windowm<?echo $resrna['rand'];?>').hide();
                                $('#prich<?echo $resrna['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resrna['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=naatk",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=naatk&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resrna['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resrna['rand'];?>').show();
                $('.modal-windowm<?echo $resrna['rand'];?>').show();
                $('#kalm<?echo $resrna['rand'];?>').show();
            });
            $('#otkaz<?echo $resrna['rand'];?>').click(function () {

                $('#prich<?echo $resrna['rand'];?>').show();

            });
            $('#vist<?echo $resrna['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resrna['rand'];?>').hide();
                $('#kalm<?echo $resrna['rand'];?>').hide();
                $('.modal-windowm<?echo $resrna['rand'];?>').hide();
                $('#prich<?echo $resrna['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resrna['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resrna['rand'];?>').hide();
                $('#kalm<?echo $resrna['rand'];?>').hide();
                $('.modal-windowm<?echo $resrna['rand'];?>').hide();
                $('#prich<?echo $resrna['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resrna['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resrna['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resrna['ogrn']);?>&kli=<? echo $resrna['idkli'];?>&lico=<? echo $resrna['lico'];?>&gr=<? echo $resrna['gr'];?>&nomerschet=<? echo $resrna['ns'];?>&produkt=<? echo $resrna['produkt'];?>&inn=<? echo $resrna['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resrna['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resrna['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resrna['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2"){
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '21' or schet.status='65')";
	}
	if($_GET['ogr']=="22")
	{
		$status="(schet.status = '77')";
	}
	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '52' or schet.status = '53'or schet.status = '60')";
	}?>
<?php $rna = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.url,schet.oplachenks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.oplachen,schet.nomerschetks,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resrna = mysql_fetch_assoc($rna))  : ?>
<tr value="<?php echo $resrna['id'];?> "class="schetna">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#85D6D1;"><?php echo $resrna['d'].".".$resrna['m'].".".$resrna['y'];?></td>
<td style="background:#85D6D1;"><?php echo $resrna['ns'];?><?if($resrna['nomerschetks']!=''){?><p><?php echo $resrna['nomerschetks'];?></p><?}?></td>
<td><?echo $resrna['inn'];?></td>
<td><?echo $resrna['kpp'];?></td>
<td style="width: 30%;"><?echo $resrna['ogrn'];?></td>
<td><?echo $resrna['name'];?></td>
<td><?echo schet_tip_text($resrna);?></td>
<td style="background:#85D6D1;"><?echo $resrna['priceks'];?></td>
<td style="background:#85D6D1;"><?echo $resrna['price'];?></td>
<td><?echo $resrna['dataprod'];?></td>
<td style="width: 6%;background:#85D6D1;"><?
if ($resrna['status']=='21'){
	echo"Частично на отгрузке";
}
if ($resrna['status']=='65'){
	echo"На отгрузке.";
}
if ($resrna['status']=='77'){
	echo"На отгрузке.";
}
if ($resrna['status']=='52'){
	echo"Выдали";
}
if ($resrna['status']=='53'){
	echo"Жем закрывающие документы";
}
if ($resrna['status']=='60'){
	echo"На отгрузке.";
}
?></td>
<td style="width: 7%;"><?echo $resrna['f_name'].' '.mb_substr($resrna['l_name'],0,1,'UTF-8'),'. '.mb_substr($resrna['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resrna['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resrna['rand'];?>"id="schetkal<?echo $resrna['rand'];?>"><?if($resrna['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resrna['datebron']));
	$monvis=date('m', strtotime($resrna['datebron']));
	$yesrvis=date('Y', strtotime($resrna['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resrna['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resrna['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resrna['rand'];?>' id="datebr<?echo $resrna['rand'];?>" type="date"value="<?if($resrna['datebron']!="0000-00-00"){echo $resrna['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resrna['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resrna['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resrna['rand'];?>').show();
    $('.modal-window<?echo $resrna['rand'];?>').show();
	$('#kalbr<?echo $resrna['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resrna['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resrna['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resrna['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resrna['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resrna['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resrna['rand'];?>').hide();
	  $('#kalbr<?echo $resrna['rand'];?>').hide();
    $('.modal-window<?echo $resrna['rand'];?>').hide();
	document.getElementById("scales<?echo $resrna['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resrna['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resrna['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resrna['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resrna['rand'];?>"><?if($resrna['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resrna['datezvon']));
	$monvis=date('m', strtotime($resrna['datezvon']));
	$yesrvis=date('Y', strtotime($resrna['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resrna['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resrna['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resrna['rand'];?>' id="datezs<?echo $resrna['rand'];?>" type="date"value="<?if($resrna['datezvon']!="0000-00-00"){echo $resrna['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resrna['rand'];?>' id="timezs<?echo $resrna['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resrna['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resrna['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resrna['rand'];?>').show();
    $('.modal-windowzs<?echo $resrna['rand'];?>').show();
	$('#kalz<?echo $resrna['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resrna['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resrna['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resrna['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resrna['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resrna['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resrna['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resrna['rand'];?>').hide();
	  $('#kalzs<?echo $resrna['rand'];?>').hide();
    $('.modal-windowzs<?echo $resrna['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resrna['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resrna['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resrna['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resrna['rand'];?>"id="movschet<?echo $resrna['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resrna['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resrna['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resrna['idkli'];?>&parent=<?echo $resrna['produkt'];?>&ogrn=<?echo $resrna['orgh'];?>&inn=<?echo $resrna['inn'];?>&kpp=<?echo $resrna['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resrna['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resrna['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resrna['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resrna['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resrna['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resrna['rand'];?>').hide();
                                $('#kalm<?echo $resrna['rand'];?>').hide();
                                $('.modal-windowm<?echo $resrna['rand'];?>').hide();
                                $('#prich<?echo $resrna['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resrna['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=naatk",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=naatk&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resrna['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resrna['rand'];?>').show();
                $('.modal-windowm<?echo $resrna['rand'];?>').show();
                $('#kalm<?echo $resrna['rand'];?>').show();
            });
            $('#otkaz<?echo $resrna['rand'];?>').click(function () {

                $('#prich<?echo $resrna['rand'];?>').show();

            });
            $('#vist<?echo $resrna['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resrna['rand'];?>').hide();
                $('#kalm<?echo $resrna['rand'];?>').hide();
                $('.modal-windowm<?echo $resrna['rand'];?>').hide();
                $('#prich<?echo $resrna['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resrna['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resrna['rand'];?>').hide();
                $('#kalm<?echo $resrna['rand'];?>').hide();
                $('.modal-windowm<?echo $resrna['rand'];?>').hide();
                $('#prich<?echo $resrna['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resrna['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resrna['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resrna['ogrn']);?>&kli=<? echo $resrna['idkli'];?>&lico=<? echo $resrna['lico'];?>&gr=<? echo $resrna['gr'];?>&nomerschet=<? echo $resrna['ns'];?>&produkt=<? echo $resrna['produkt'];?>&inn=<? echo $resrna['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resrna['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resrna['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resrna['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="atk"){
if ($_GET['tipi']=="1"){?>
<?php $ratk = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.del='0' AND schet.akt = '1' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resatk = mysql_fetch_assoc($ratk))  : ?>
<tr value="<?php echo $resatk['id'];?> "class="schetatk">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#85D6A7;"><?php echo $resatk['d'].".".$resatk['m'].".".$resatk['y'];?></td>
<td style="background:#85D6A7;"><?php echo $resatk['ns'];?><?if($resatk['nomerschetks']!=''){?><p><?php echo $resatk['nomerschetks'];?></p><?}?></td>
<td><?echo $resatk['inn'];?></td>
<td><?echo $resatk['kpp'];?></td>
<td style="width: 30%;"><?echo $resatk['ogrn'];?></td>
<td><?echo $resatk['name'];?></td>
<td><?echo schet_tip_text($resatk);?></td>
<td style="background:#85D6A7;"><?echo $resatk['priceks'];?></td>
<td style="background:#85D6A7;"><?echo $resatk['price'];?></td>
<td><?echo $resatk['dataprod'];?></td>
<td style="width: 6%;background:#85D6A7;">Отгружен</td>
<td style="width: 7%;"><?echo $resatk['f_name'].' '.mb_substr($resatk['l_name'],0,1,'UTF-8'),'. '.mb_substr($resatk['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resatk['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resatk['rand'];?>"id="schetkal<?echo $resatk['rand'];?>"><?if($resatk['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resatk['datebron']));
	$monvis=date('m', strtotime($resatk['datebron']));
	$yesrvis=date('Y', strtotime($resatk['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resatk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resatk['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resatk['rand'];?>' id="datebr<?echo $resatk['rand'];?>" type="date"value="<?if($resatk['datebron']!="0000-00-00"){echo $resatk['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resatk['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resatk['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resatk['rand'];?>').show();
    $('.modal-window<?echo $resatk['rand'];?>').show();
	$('#kalbr<?echo $resatk['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resatk['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resatk['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resatk['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resatk['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resatk['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resatk['rand'];?>').hide();
	  $('#kalbr<?echo $resatk['rand'];?>').hide();
    $('.modal-window<?echo $resatk['rand'];?>').hide();
	document.getElementById("scales<?echo $resatk['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resatk['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resatk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resatk['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resatk['rand'];?>"><?if($resatk['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resatk['datezvon']));
	$monvis=date('m', strtotime($resatk['datezvon']));
	$yesrvis=date('Y', strtotime($resatk['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resatk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resatk['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resatk['rand'];?>' id="datezs<?echo $resatk['rand'];?>" type="date"value="<?if($resatk['datezvon']!="0000-00-00"){echo $resatk['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resatk['rand'];?>' id="timezs<?echo $resatk['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resatk['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resatk['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resatk['rand'];?>').show();
    $('.modal-windowzs<?echo $resatk['rand'];?>').show();
	$('#kalz<?echo $resatk['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resatk['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resatk['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resatk['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resatk['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resatk['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resatk['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resatk['rand'];?>').hide();
	  $('#kalzs<?echo $resatk['rand'];?>').hide();
    $('.modal-windowzs<?echo $resatk['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resatk['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resatk['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resatk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resatk['rand'];?>"id="movschet<?echo $resatk['$resyv'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resatk['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resatk['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resatk['idkli'];?>&parent=<?echo $resatk['produkt'];?>&ogrn=<?echo $resatk['orgh'];?>&inn=<?echo $resatk['inn'];?>&kpp=<?echo $resatk['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resatk['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resatk['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resatk['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resatk['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resatk['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resatk['rand'];?>').hide();
                                $('#kalm<?echo $resatk['rand'];?>').hide();
                                $('.modal-windowm<?echo $resatk['rand'];?>').hide();
                                $('#prich<?echo $resatk['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resatk['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=atk",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=atk&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resatk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resatk['rand'];?>').show();
                $('.modal-windowm<?echo $resatk['rand'];?>').show();
                $('#kalm<?echo $resatk['rand'];?>').show();
            });
            $('#otkaz<?echo $resatk['rand'];?>').click(function () {

                $('#prich<?echo $resatk['rand'];?>').show();

            });
            $('#vist<?echo $resatk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resatk['rand'];?>').hide();
                $('#kalm<?echo $resatk['rand'];?>').hide();
                $('.modal-windowm<?echo $resatk['rand'];?>').hide();
                $('#prich<?echo $resatk['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resatk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resatk['rand'];?>').hide();
                $('#kalm<?echo $resatk['rand'];?>').hide();
                $('.modal-windowm<?echo $resatk['rand'];?>').hide();
                $('#prich<?echo $resatk['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resatk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resatk['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resatk['ogrn']);?>&kli=<? echo $resatk['idkli'];?>&lico=<? echo $resatk['lico'];?>&gr=<? echo $resatk['gr'];?>&nomerschet=<? echo $resatk['ns'];?>&produkt=<? echo $resatk['produkt'];?>&inn=<? echo $resatk['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resatk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resatk['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resatk['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{?>
<?php $ratk = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.del='0' AND schet.akt = '1'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resatk = mysql_fetch_assoc($ratk))  : ?>
<tr value="<?php echo $resatk['id'];?> "class="schetatk">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#85D6A7;"><?php echo $resatk['d'].".".$resatk['m'].".".$resatk['y'];?></td>
<td style="background:#85D6A7;"><?php echo $resatk['ns'];?><?if($resatk['nomerschetks']!=''){?><p><?php echo $resatk['nomerschetks'];?></p><?}?></td>
<td><?echo $resatk['inn'];?></td>
<td><?echo $resatk['kpp'];?></td>
<td style="width: 30%;"><?echo $resatk['ogrn'];?></td>
<td><?echo $resatk['name'];?></td>
<td><?echo schet_tip_text($resatk);?></td>
<td style="background:#85D6A7;"><?echo $resatk['priceks'];?></td>
<td style="background:#85D6A7;"><?echo $resatk['price'];?></td>
<td><?echo $resatk['dataprod'];?></td>
<td style="width: 6%;background:#85D6A7;">Отгружен</td>
<td style="width: 7%;"><?echo $resatk['f_name'].' '.mb_substr($resatk['l_name'],0,1,'UTF-8'),'. '.mb_substr($resatk['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resatk['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resatk['rand'];?>"id="schetkal<?echo $resatk['rand'];?>"><?if($resatk['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resatk['datebron']));
	$monvis=date('m', strtotime($resatk['datebron']));
	$yesrvis=date('Y', strtotime($resatk['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resatk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resatk['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resatk['rand'];?>' id="datebr<?echo $resatk['rand'];?>" type="date"value="<?if($resatk['datebron']!="0000-00-00"){echo $resatk['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resatk['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resatk['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resatk['rand'];?>').show();
    $('.modal-window<?echo $resatk['rand'];?>').show();
	$('#kalbr<?echo $resatk['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resatk['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resatk['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resatk['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resatk['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resatk['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resatk['rand'];?>').hide();
	  $('#kalbr<?echo $resatk['rand'];?>').hide();
    $('.modal-window<?echo $resatk['rand'];?>').hide();
	document.getElementById("scales<?echo $resatk['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resatk['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resatk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resatk['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resatk['rand'];?>"><?if($resatk['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resatk['datezvon']));
	$monvis=date('m', strtotime($resatk['datezvon']));
	$yesrvis=date('Y', strtotime($resatk['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resatk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resatk['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resatk['rand'];?>' id="datezs<?echo $resatk['rand'];?>" type="date"value="<?if($resatk['datezvon']!="0000-00-00"){echo $resatk['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resatk['rand'];?>' id="timezs<?echo $resatk['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resatk['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resatk['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resatk['rand'];?>').show();
    $('.modal-windowzs<?echo $resatk['rand'];?>').show();
	$('#kalz<?echo $resatk['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resatk['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resatk['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resatk['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resatk['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resatk['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resatk['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resatk['rand'];?>').hide();
	  $('#kalzs<?echo $resatk['rand'];?>').hide();
    $('.modal-windowzs<?echo $resatk['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resatk['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resatk['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resatk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resatk['rand'];?>"id="movschet<?echo $resatk['$resyv'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resatk['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resatk['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resatk['idkli'];?>&parent=<?echo $resatk['produkt'];?>&ogrn=<?echo $resatk['orgh'];?>&inn=<?echo $resatk['inn'];?>&kpp=<?echo $resatk['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resatk['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resatk['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resatk['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resatk['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resatk['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resatk['rand'];?>').hide();
                                $('#kalm<?echo $resatk['rand'];?>').hide();
                                $('.modal-windowm<?echo $resatk['rand'];?>').hide();
                                $('#prich<?echo $resatk['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resatk['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=atk",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=atk&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resatk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resatk['rand'];?>').show();
                $('.modal-windowm<?echo $resatk['rand'];?>').show();
                $('#kalm<?echo $resatk['rand'];?>').show();
            });
            $('#otkaz<?echo $resatk['rand'];?>').click(function () {

                $('#prich<?echo $resatk['rand'];?>').show();

            });
            $('#vist<?echo $resatk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resatk['rand'];?>').hide();
                $('#kalm<?echo $resatk['rand'];?>').hide();
                $('.modal-windowm<?echo $resatk['rand'];?>').hide();
                $('#prich<?echo $resatk['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resatk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resatk['rand'];?>').hide();
                $('#kalm<?echo $resatk['rand'];?>').hide();
                $('.modal-windowm<?echo $resatk['rand'];?>').hide();
                $('#prich<?echo $resatk['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resatk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resatk['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resatk['ogrn']);?>&kli=<? echo $resatk['idkli'];?>&lico=<? echo $resatk['lico'];?>&gr=<? echo $resatk['gr'];?>&nomerschet=<? echo $resatk['ns'];?>&produkt=<? echo $resatk['produkt'];?>&inn=<? echo $resatk['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resatk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resatk['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resatk['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="otk"){
if ($_GET['tipi']=="1"){?>
<?php $rotk = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status,prichotk.value as avl FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet left join prichotk on schet.prichotk=prichotk.id WHERE  schet.shetold='' and $uslugiogrn schet.del='0' AND schet.otk = '0' AND schet.cher = '1' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resotk = mysql_fetch_assoc($rotk))  : ?>
<tr value="<?php echo $resotk['id'];?> "class="schetotk">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#FB9C9C;"><?php echo $resotk['d'].".".$resotk['m'].".".$resotk['y'];?></td>
<td style="background:#FB9C9C;"><?php echo $resotk['ns'];?><?if($resotk['nomerschetks']!=''){?><p><?php echo $resotk['nomerschetks'];?></p><?}?></td>
<td><?echo $resotk['inn'];?></td>
<td><?echo $resotk['kpp'];?></td>
<td style="width: 30%;"><?echo $resotk['ogrn'];?></td>
<td><?echo $resotk['name'];?></td>
<td><?echo schet_tip_text($resotk);?></td>
<td style="background:#FB9C9C;"><?echo $resotk['priceks'];?></td>
<td style="background:#FB9C9C;"><?echo round($resotk['price'], 1);?></td>
<td><?echo $resotk['dataprod'];?></td>
<td style="width: 6%;background:#FB9C9C;"><?echo $resotk['avl'];?></td>
<td style="width: 7%;"><?echo $resotk['f_name'].' '.mb_substr($resotk['l_name'],0,1,'UTF-8'),'. '.mb_substr($resotk['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resotk['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resotk['rand'];?>"id="schetkal<?echo $resotk['rand'];?>"><?if($resotk['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resotk['datebron']));
	$monvis=date('m', strtotime($resotk['datebron']));
	$yesrvis=date('Y', strtotime($resotk['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resotk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resotk['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resotk['rand'];?>' id="datebr<?echo $resotk['rand'];?>" type="date"value="<?if($resotk['datebron']!="0000-00-00"){echo $resotk['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resotk['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resotk['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resotk['rand'];?>').show();
    $('.modal-window<?echo $resotk['rand'];?>').show();
	$('#kalbr<?echo $resotk['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resotk['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resotk['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resotk['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resotk['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resotk['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resotk['rand'];?>').hide();
	  $('#kalbr<?echo $resotk['rand'];?>').hide();
    $('.modal-window<?echo $resotk['rand'];?>').hide();
	document.getElementById("scales<?echo $resotk['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resotk['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resotk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resotk['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resotk['rand'];?>"><?if($resotk['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resotk['datezvon']));
	$monvis=date('m', strtotime($resotk['datezvon']));
	$yesrvis=date('Y', strtotime($resotk['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resotk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resotk['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resotk['rand'];?>' id="datezs<?echo $resotk['rand'];?>" type="date"value="<?if($resotk['datezvon']!="0000-00-00"){echo $resotk['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resotk['rand'];?>' id="timezs<?echo $resotk['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resotk['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resotk['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resotk['rand'];?>').show();
    $('.modal-windowzs<?echo $resotk['rand'];?>').show();
	$('#kalz<?echo $resotk['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resotk['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resotk['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resotk['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resotk['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resotk['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resotk['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resotk['rand'];?>').hide();
	  $('#kalzs<?echo $resotk['rand'];?>').hide();
    $('.modal-windowzs<?echo $resotk['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resotk['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resotk['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resotk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resotk['rand'];?>"id="movschet<?echo $resotk['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resotk['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resotk['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resotk['idkli'];?>&parent=<?echo $resotk['produkt'];?>&ogrn=<?echo $resotk['orgh'];?>&inn=<?echo $resotk['inn'];?>&kpp=<?echo $resotk['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resotk['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resotk['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resotk['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resotk['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resotk['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resotk['rand'];?>').hide();
                                $('#kalm<?echo $resotk['rand'];?>').hide();
                                $('.modal-windowm<?echo $resotk['rand'];?>').hide();
                                $('#prich<?echo $resotk['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resotk['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=otk",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=otk&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resotk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resotk['rand'];?>').show();
                $('.modal-windowm<?echo $resotk['rand'];?>').show();
                $('#kalm<?echo $resotk['rand'];?>').show();
            });
            $('#otkaz<?echo $resotk['rand'];?>').click(function () {

                $('#prich<?echo $resotk['rand'];?>').show();

            });
            $('#vist<?echo $resotk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resotk['rand'];?>').hide();
                $('#kalm<?echo $resotk['rand'];?>').hide();
                $('.modal-windowm<?echo $resotk['rand'];?>').hide();
                $('#prich<?echo $resotk['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resotk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resotk['rand'];?>').hide();
                $('#kalm<?echo $resotk['rand'];?>').hide();
                $('.modal-windowm<?echo $resotk['rand'];?>').hide();
                $('#prich<?echo $resotk['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resotk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resotk['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resatk['ogrn']);?>&kli=<? echo $resotk['idkli'];?>&lico=<? echo $resotk['lico'];?>&gr=<? echo $resotk['gr'];?>&nomerschet=<? echo $resotk['ns'];?>&produkt=<? echo $resotk['produkt'];?>&inn=<? echo $resotk['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resotk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resotk['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resotk['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{?>

<?php $rotk = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.ogrn as orgh,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status,prichotk.value as avl FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet left join prichotk on schet.prichotk=prichotk.id WHERE  schet.shetold!='' and $uslugiogrn schet.del='0'  AND schet.otk = '0' AND schet.cher = '1' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resotk = mysql_fetch_assoc($rotk))  : ?>
<tr value="<?php echo $resotk['id'];?> "class="schetotk">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#FB9C9C;"><?php echo $resotk['d'].".".$resotk['m'].".".$resotk['y'];?></td>
<td style="background:#FB9C9C;"><?php echo $resotk['ns'];?><?if($resotk['nomerschetks']!=''){?><p><?php echo $resotk['nomerschetks'];?></p><?}?></td>
<td><?echo $resotk['inn'];?></td>
<td><?echo $resotk['kpp'];?></td>
<td style="width: 30%;"><?echo $resotk['ogrn'];?></td>
<td><?echo $resotk['name'];?></td>
<td><?echo schet_tip_text($resotk);?></td>
<td style="background:#FB9C9C;"><?echo $resotk['priceks'];?></td>
<td style="background:#FB9C9C;"><?echo round($resotk['price'], 1);?></td>
<td><?echo $resotk['dataprod'];?></td>
<td style="width: 13%;background:#FB9C9C;"><?echo $resotk['avl'];?></td>
<td style="width: 7%;"><?echo $resotk['f_name'].' '.mb_substr($resotk['l_name'],0,1,'UTF-8'),'. '.mb_substr($resotk['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resotk['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resotk['rand'];?>"id="schetkal<?echo $resotk['rand'];?>"><?if($resotk['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resotk['datebron']));
	$monvis=date('m', strtotime($resotk['datebron']));
	$yesrvis=date('Y', strtotime($resotk['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resotk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resotk['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resotk['rand'];?>' id="datebr<?echo $resotk['rand'];?>" type="date"value="<?if($resotk['datebron']!="0000-00-00"){echo $resotk['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resotk['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resotk['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resotk['rand'];?>').show();
    $('.modal-window<?echo $resotk['rand'];?>').show();
	$('#kalbr<?echo $resotk['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resotk['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resotk['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resotk['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resotk['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resotk['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resotk['rand'];?>').hide();
	  $('#kalbr<?echo $resotk['rand'];?>').hide();
    $('.modal-window<?echo $resotk['rand'];?>').hide();
	document.getElementById("scales<?echo $resotk['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resotk['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resotk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resotk['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resotk['rand'];?>"><?if($resotk['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resotk['datezvon']));
	$monvis=date('m', strtotime($resotk['datezvon']));
	$yesrvis=date('Y', strtotime($resotk['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resotk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resotk['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resotk['rand'];?>' id="datezs<?echo $resotk['rand'];?>" type="date"value="<?if($resotk['datezvon']!="0000-00-00"){echo $resotk['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resotk['rand'];?>' id="timezs<?echo $resotk['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resotk['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resotk['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resotk['rand'];?>').show();
    $('.modal-windowzs<?echo $resotk['rand'];?>').show();
	$('#kalz<?echo $resotk['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resotk['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resotk['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resotk['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resotk['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resotk['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resotk['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resotk['rand'];?>').hide();
	  $('#kalzs<?echo $resotk['rand'];?>').hide();
    $('.modal-windowzs<?echo $resotk['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resotk['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resotk['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resotk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resotk['rand'];?>"id="movschet<?echo $resotk['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resotk['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resotk['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resotk['idkli'];?>&parent=<?echo $resotk['produkt'];?>&ogrn=<?echo $resotk['orgh'];?>&inn=<?echo $resotk['inn'];?>&kpp=<?echo $resotk['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resotk['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resotk['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resotk['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resotk['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resotk['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resotk['rand'];?>').hide();
                                $('#kalm<?echo $resotk['rand'];?>').hide();
                                $('.modal-windowm<?echo $resotk['rand'];?>').hide();
                                $('#prich<?echo $resotk['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resotk['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=otk",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=otk&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resotk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resotk['rand'];?>').show();
                $('.modal-windowm<?echo $resotk['rand'];?>').show();
                $('#kalm<?echo $resotk['rand'];?>').show();
            });
            $('#otkaz<?echo $resotk['rand'];?>').click(function () {

                $('#prich<?echo $resotk['rand'];?>').show();

            });
            $('#vist<?echo $resotk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resotk['rand'];?>').hide();
                $('#kalm<?echo $resotk['rand'];?>').hide();
                $('.modal-windowm<?echo $resotk['rand'];?>').hide();
                $('#prich<?echo $resotk['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resotk['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resotk['rand'];?>').hide();
                $('#kalm<?echo $resotk['rand'];?>').hide();
                $('.modal-windowm<?echo $resotk['rand'];?>').hide();
                $('#prich<?echo $resotk['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resotk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resotk['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resotk['ogrn']);?>&kli=<? echo $resotk['idkli'];?>&lico=<? echo $resotk['lico'];?>&gr=<? echo $resotk['gr'];?>&nomerschet=<? echo $resotk['ns'];?>&produkt=<? echo $resotk['produkt'];?>&inn=<? echo $resotk['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resotk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resotk['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resotk['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="cher"){
if ($_GET['tipi']=="1"){?>
<?php $rcher = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.ogrn as orgh,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.del='0' AND schet.otk = '1' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($rescher = mysql_fetch_assoc($rcher))  : ?>
<tr value="<?php echo $rescher['id'];?> "class="schetcher">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#BC9B79;"><?php echo $rescher['d'].".".$rescher['m'].".".$rescher['y'];?></td>
<td style="background:#BC9B79;"><?php echo $rescher['ns'];?><?if($rescher['nomerschetks']!=''){?><p><?php echo $rescher['nomerschetks'];?></p><?}?></td>
<td><?echo $rescher['inn'];?></td>
<td><?echo $rescher['kpp'];?></td>
<td style="width: 30%;"><?echo $rescher['ogrn'];?></td>
<td><?echo $rescher['name'];?></td>
<td><?echo schet_tip_text($rescher);?></td>
<td style="background:#BC9B79;"><?echo $rescher['priceks'];?></td>
<td style="background:#BC9B79;"><?echo $rescher['price'];?></td>
<td><?echo $rescher['dataprod'];?></td>
<td style="width: 6%;background:#BC9B79;">Черновик</td>
<td style="width: 7%;"><?echo $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($rescher['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $rescher['rand'];?>"id="schetkal<?echo $rescher['rand'];?>"><?if($rescher['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($rescher['datebron']));
	$monvis=date('m', strtotime($rescher['datebron']));
	$yesrvis=date('Y', strtotime($rescher['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $rescher['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $rescher['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $rescher['rand'];?>' id="datebr<?echo $rescher['rand'];?>" type="date"value="<?if($rescher['datebron']!="0000-00-00"){echo $rescher['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $rescher['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $rescher['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $rescher['rand'];?>').show();
    $('.modal-window<?echo $rescher['rand'];?>').show();
	$('#kalbr<?echo $rescher['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $rescher['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $rescher['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $rescher['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $rescher['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $rescher['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $rescher['rand'];?>').hide();
	  $('#kalbr<?echo $rescher['rand'];?>').hide();
    $('.modal-window<?echo $rescher['rand'];?>').hide();
	document.getElementById("scales<?echo $rescher['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $rescher['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $rescher['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($rescher['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $rescher['rand'];?>"><?if($rescher['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($rescher['datezvon']));
	$monvis=date('m', strtotime($rescher['datezvon']));
	$yesrvis=date('Y', strtotime($rescher['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $rescher['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $rescher['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $rescher['rand'];?>' id="datezs<?echo $rescher['rand'];?>" type="date"value="<?if($rescher['datezvon']!="0000-00-00"){echo $rescher['datezvon'];}?>">
<textarea class='form-control timezs<?echo $rescher['rand'];?>' id="timezs<?echo $rescher['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $rescher['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $rescher['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $rescher['rand'];?>').show();
    $('.modal-windowzs<?echo $rescher['rand'];?>').show();
	$('#kalz<?echo $rescher['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $rescher['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $rescher['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $rescher['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $rescher['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $rescher['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $rescher['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $rescher['rand'];?>').hide();
	  $('#kalzs<?echo $rescher['rand'];?>').hide();
    $('.modal-windowzs<?echo $rescher['rand'];?>').hide();
	document.getElementById("scalezs<?echo $rescher['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $rescher['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $rescher['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $rescher['rand'];?>"id="movschet<?echo $rescher['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $rescher['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $rescher['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $rescher['idkli'];?>&parent=<?echo $rescher['produkt'];?>&ogrn=<?echo $rescher['orgh'];?>&inn=<?echo $rescher['inn'];?>&kpp=<?echo $rescher['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $rescher['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $rescher['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $rescher['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $rescher['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $rescher['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $rescher['rand'];?>').hide();
                                $('#kalm<?echo $rescher['rand'];?>').hide();
                                $('.modal-windowm<?echo $rescher['rand'];?>').hide();
                                $('#prich<?echo $rescher['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $rescher['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=cher",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=cher&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $rescher['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $rescher['rand'];?>').show();
                $('.modal-windowm<?echo $rescher['rand'];?>').show();
                $('#kalm<?echo $rescher['rand'];?>').show();
            });
            $('#otkaz<?echo $rescher['rand'];?>').click(function () {

                $('#prich<?echo $rescher['rand'];?>').show();

            });
            $('#vist<?echo $rescher['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $rescher['rand'];?>').hide();
                $('#kalm<?echo $rescher['rand'];?>').hide();
                $('.modal-windowm<?echo $rescher['rand'];?>').hide();
                $('#prich<?echo $rescher['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $rescher['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $rescher['rand'];?>').hide();
                $('#kalm<?echo $rescher['rand'];?>').hide();
                $('.modal-windowm<?echo $rescher['rand'];?>').hide();
                $('#prich<?echo $rescher['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $rescher['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $rescher['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $rescher['ogrn']);?>&kli=<? echo $rescher['idkli'];?>&lico=<? echo $rescher['lico'];?>&gr=<? echo $rescher['gr'];?>&nomerschet=<? echo $rescher['ns'];?>&produkt=<? echo $rescher['produkt'];?>&inn=<? echo $rescher['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $rescher['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $rescher['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $rescher['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{?>
<?php $rcher = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.del='0'   AND schet.otk = '1' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($rescher = mysql_fetch_assoc($rcher))  : ?>
<tr value="<?php echo $rescher['id'];?> "class="schetcher">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#BC9B79;"><?php echo $rescher['d'].".".$rescher['m'].".".$rescher['y'];?></td>
<td style="background:#BC9B79;"><?php echo $rescher['ns'];?><?if($rescher['nomerschetks']!=''){?><p><?php echo $rescher['nomerschetks'];?></p><?}?></td>
<td><?echo $rescher['inn'];?></td>
<td><?echo $rescher['kpp'];?></td>
<td style="width: 30%;"><?echo $rescher['ogrn'];?></td>
<td><?echo $rescher['name'];?></td>
<td><?echo schet_tip_text($rescher);?></td>
<td style="background:#BC9B79;"><?echo $rescher['priceks'];?></td>
<td style="background:#BC9B79;"><?echo $rescher['price'];?></td>
<td><?echo $rescher['dataprod'];?></td>
<td style="width: 6%;background:#BC9B79;">Черновик</td>
<td style="width: 7%;"><?echo $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($rescher['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $rescher['rand'];?>"id="schetkal<?echo $rescher['rand'];?>"><?if($rescher['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($rescher['datebron']));
	$monvis=date('m', strtotime($rescher['datebron']));
	$yesrvis=date('Y', strtotime($rescher['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $rescher['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $rescher['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $rescher['rand'];?>' id="datebr<?echo $rescher['rand'];?>" type="date"value="<?if($rescher['datebron']!="0000-00-00"){echo $rescher['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $rescher['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $rescher['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $rescher['rand'];?>').show();
    $('.modal-window<?echo $rescher['rand'];?>').show();
	$('#kalbr<?echo $rescher['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $rescher['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $rescher['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $rescher['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $rescher['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $rescher['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $rescher['rand'];?>').hide();
	  $('#kalbr<?echo $rescher['rand'];?>').hide();
    $('.modal-window<?echo $rescher['rand'];?>').hide();
	document.getElementById("scales<?echo $rescher['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $rescher['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $rescher['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($rescher['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $rescher['rand'];?>"><?if($rescher['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($rescher['datezvon']));
	$monvis=date('m', strtotime($rescher['datezvon']));
	$yesrvis=date('Y', strtotime($rescher['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $rescher['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $rescher['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $rescher['rand'];?>' id="datezs<?echo $rescher['rand'];?>" type="date"value="<?if($rescher['datezvon']!="0000-00-00"){echo $rescher['datezvon'];}?>">
<textarea class='form-control timezs<?echo $rescher['rand'];?>' id="timezs<?echo $rescher['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $rescher['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $rescher['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $rescher['rand'];?>').show();
    $('.modal-windowzs<?echo $rescher['rand'];?>').show();
	$('#kalz<?echo $rescher['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $rescher['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $rescher['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $rescher['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $rescher['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $rescher['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $rescher['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $rescher['rand'];?>').hide();
	  $('#kalzs<?echo $rescher['rand'];?>').hide();
    $('.modal-windowzs<?echo $rescher['rand'];?>').hide();
	document.getElementById("scalezs<?echo $rescher['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $rescher['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $rescher['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $rescher['rand'];?>"id="movschet<?echo $rescher['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $rescher['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $rescher['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $rescher['idkli'];?>&parent=<?echo $rescher['produkt'];?>&ogrn=<?echo $rescher['orgh'];?>&inn=<?echo $rescher['inn'];?>&kpp=<?echo $rescher['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $rescher['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $rescher['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $rescher['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $rescher['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $rescher['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $rescher['rand'];?>').hide();
                                $('#kalm<?echo $rescher['rand'];?>').hide();
                                $('.modal-windowm<?echo $rescher['rand'];?>').hide();
                                $('#prich<?echo $rescher['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $rescher['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=cher",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=cher&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $rescher['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $rescher['rand'];?>').show();
                $('.modal-windowm<?echo $rescher['rand'];?>').show();
                $('#kalm<?echo $rescher['rand'];?>').show();
            });
            $('#otkaz<?echo $rescher['rand'];?>').click(function () {

                $('#prich<?echo $rescher['rand'];?>').show();

            });
            $('#vist<?echo $rescher['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $rescher['rand'];?>').hide();
                $('#kalm<?echo $rescher['rand'];?>').hide();
                $('.modal-windowm<?echo $rescher['rand'];?>').hide();
                $('#prich<?echo $rescher['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $rescher['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $rescher['rand'];?>').hide();
                $('#kalm<?echo $rescher['rand'];?>').hide();
                $('.modal-windowm<?echo $rescher['rand'];?>').hide();
                $('#prich<?echo $rescher['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $rescher['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $rescher['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $rescher['ogrn']);?>&kli=<? echo $rescher['idkli'];?>&lico=<? echo $rescher['lico'];?>&gr=<? echo $rescher['gr'];?>&nomerschet=<? echo $rescher['ns'];?>&produkt=<? echo $rescher['produkt'];?>&inn=<? echo $rescher['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $rescher['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $rescher['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $rescher['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
</tr>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="vozvrat"){
if ($_GET['tipi']=="1"){
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '23')";
	}
	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '12356')";
	}?>
<?php $rvoz = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resvoz = mysql_fetch_assoc($rvoz))  : ?>
<tr value="<?php echo $resvoz['id'];?> "class="schetvoz">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#E45A51;"><?php echo $resvoz['d'].".".$resvoz['m'].".".$resvoz['y'];?></td>
<td style="background:#E45A51;"><?php echo $resvoz['ns'];?><?if($resvoz['nomerschetks']!=''){?><p><?php echo $resvoz['nomerschetks'];?></p><?}?></td>
<td><?echo $resvoz['inn'];?></td>
<td><?echo $resvoz['kpp'];?></td>
<td style="width: 30%;"><?echo $resvoz['ogrn'];?></td>
<td><?echo $resvoz['name'];?></td>
<td><?echo schet_tip_text($resvoz);?></td>
<td style="background:#E45A51;"><?echo $resvoz['priceks'];?></td>
<td style="background:#E45A51;"><?echo $resvoz['price'];?></td>
<td><?echo $resvoz['dataprod'];?></td>
<td style="width: 6%;background:#E45A51;">Возврат</td>
<td style="width: 7%;"><?echo $resvoz['f_name'].' '.mb_substr($resvoz['l_name'],0,1,'UTF-8'),'. '.mb_substr($resvoz['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resvoz['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resvoz['rand'];?>"id="schetkal<?echo $resvoz['rand'];?>"><?if($resvoz['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resvoz['datebron']));
	$monvis=date('m', strtotime($resvoz['datebron']));
	$yesrvis=date('Y', strtotime($resvoz['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resvoz['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resvoz['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resvoz['rand'];?>' id="datebr<?echo $resvoz['rand'];?>" type="date"value="<?if($resvoz['datebron']!="0000-00-00"){echo $resvoz['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resvoz['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resvoz['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resvoz['rand'];?>').show();
    $('.modal-window<?echo $resvoz['rand'];?>').show();
	$('#kalbr<?echo $resvoz['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resvoz['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resvoz['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resvoz['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resvoz['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resvoz['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resvoz['rand'];?>').hide();
	  $('#kalbr<?echo $resvoz['rand'];?>').hide();
    $('.modal-window<?echo $resvoz['rand'];?>').hide();
	document.getElementById("scales<?echo $resvoz['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resvoz['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resvoz['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resvoz['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resvoz['rand'];?>"><?if($resvoz['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resvoz['datezvon']));
	$monvis=date('m', strtotime($resvoz['datezvon']));
	$yesrvis=date('Y', strtotime($resvoz['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resvoz['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resvoz['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resvoz['rand'];?>' id="datezs<?echo $resvoz['rand'];?>" type="date"value="<?if($resvoz['datezvon']!="0000-00-00"){echo $resvoz['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resvoz['rand'];?>' id="timezs<?echo $resvoz['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resvoz['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resvoz['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resvoz['rand'];?>').show();
    $('.modal-windowzs<?echo $resvoz['rand'];?>').show();
	$('#kalz<?echo $resvoz['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resvoz['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resvoz['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resvoz['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resvoz['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resvoz['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resvoz['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resvoz['rand'];?>').hide();
	  $('#kalzs<?echo $resvoz['rand'];?>').hide();
    $('.modal-windowzs<?echo $resvoz['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resvoz['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resvoz['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resvoz['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resvoz['rand'];?>"id="movschet<?echo $resvoz['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resvoz['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resvoz['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resvoz['idkli'];?>&parent=<?echo $resvoz['produkt'];?>&ogrn=<?echo $resvoz['orgh'];?>&inn=<?echo $resvoz['inn'];?>&kpp=<?echo $resvoz['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resvoz['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resvoz['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resvoz['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resvoz['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resvoz['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resvoz['rand'];?>').hide();
                                $('#kalm<?echo $resvoz['rand'];?>').hide();
                                $('.modal-windowm<?echo $resvoz['rand'];?>').hide();
                                $('#prich<?echo $resvoz['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resvoz['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=vozvrat",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=vozvrat&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resvoz['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resvoz['rand'];?>').show();
                $('.modal-windowm<?echo $resvoz['rand'];?>').show();
                $('#kalm<?echo $resvoz['rand'];?>').show();
            });
            $('#otkaz<?echo $resvoz['rand'];?>').click(function () {

                $('#prich<?echo $resvoz['rand'];?>').show();

            });
            $('#vist<?echo $resvoz['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resvoz['rand'];?>').hide();
                $('#kalm<?echo $resvoz['rand'];?>').hide();
                $('.modal-windowm<?echo $resvoz['rand'];?>').hide();
                $('#prich<?echo $resvoz['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resvoz['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resvoz['rand'];?>').hide();
                $('#kalm<?echo $resvoz['rand'];?>').hide();
                $('.modal-windowm<?echo $resvoz['rand'];?>').hide();
                $('#prich<?echo $resvoz['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resvoz['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resvoz['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resvoz['ogrn']);?>&kli=<? echo $resvoz['idkli'];?>&lico=<? echo $resvoz['lico'];?>&gr=<? echo $resvoz['gr'];?>&nomerschet=<? echo $resvoz['ns'];?>&produkt=<? echo $resvoz['produkt'];?>&inn=<? echo $resvoz['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resvoz['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resvoz['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resvoz['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '23')";
	}
	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '12356')";
	}?>
<?php $rvoz = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($resvoz = mysql_fetch_assoc($rvoz))  : ?>
<tr value="<?php echo $resvoz['id'];?> "class="schetvoz">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#E45A51;"><?php echo $resvoz['d'].".".$resvoz['m'].".".$resvoz['y'];?></td>
<td style="background:#E45A51;"><?php echo $resvoz['ns'];?><?if($resvoz['nomerschetks']!=''){?><p><?php echo $resvoz['nomerschetks'];?></p><?}?></td>
<td><?echo $resvoz['inn'];?></td>
<td><?echo $resvoz['kpp'];?></td>
<td style="width: 30%;"><?echo $resvoz['ogrn'];?></td>
<td><?echo $resvoz['name'];?></td>
<td><?echo schet_tip_text($resvoz);?></td>
<td style="background:#E45A51;"><?echo $resvoz['priceks'];?></td>
<td style="background:#E45A51;"><?echo $resvoz['price'];?></td>
<td><?echo $resvoz['dataprod'];?></td>
<td style="width: 6%;background:#E45A51;">Возврат</td>
<td style="width: 7%;"><?echo $resvoz['f_name'].' '.mb_substr($resvoz['l_name'],0,1,'UTF-8'),'. '.mb_substr($resvoz['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($resvoz['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $resvoz['rand'];?>"id="schetkal<?echo $resvoz['rand'];?>"><?if($resvoz['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($resvoz['datebron']));
	$monvis=date('m', strtotime($resvoz['datebron']));
	$yesrvis=date('Y', strtotime($resvoz['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $resvoz['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $resvoz['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $resvoz['rand'];?>' id="datebr<?echo $resvoz['rand'];?>" type="date"value="<?if($resvoz['datebron']!="0000-00-00"){echo $resvoz['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $resvoz['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $resvoz['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $resvoz['rand'];?>').show();
    $('.modal-window<?echo $resvoz['rand'];?>').show();
	$('#kalbr<?echo $resvoz['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $resvoz['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $resvoz['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $resvoz['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resvoz['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $resvoz['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $resvoz['rand'];?>').hide();
	  $('#kalbr<?echo $resvoz['rand'];?>').hide();
    $('.modal-window<?echo $resvoz['rand'];?>').hide();
	document.getElementById("scales<?echo $resvoz['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $resvoz['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $resvoz['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($resvoz['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $resvoz['rand'];?>"><?if($resvoz['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resvoz['datezvon']));
	$monvis=date('m', strtotime($resvoz['datezvon']));
	$yesrvis=date('Y', strtotime($resvoz['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $resvoz['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $resvoz['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $resvoz['rand'];?>' id="datezs<?echo $resvoz['rand'];?>" type="date"value="<?if($resvoz['datezvon']!="0000-00-00"){echo $resvoz['datezvon'];}?>">
<textarea class='form-control timezs<?echo $resvoz['rand'];?>' id="timezs<?echo $resvoz['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $resvoz['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $resvoz['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $resvoz['rand'];?>').show();
    $('.modal-windowzs<?echo $resvoz['rand'];?>').show();
	$('#kalz<?echo $resvoz['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $resvoz['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $resvoz['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $resvoz['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $resvoz['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resvoz['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $resvoz['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $resvoz['rand'];?>').hide();
	  $('#kalzs<?echo $resvoz['rand'];?>').hide();
    $('.modal-windowzs<?echo $resvoz['rand'];?>').hide();
	document.getElementById("scalezs<?echo $resvoz['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $resvoz['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $resvoz['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $resvoz['rand'];?>"id="movschet<?echo $resvoz['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resvoz['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resvoz['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resvoz['idkli'];?>&parent=<?echo $resvoz['produkt'];?>&ogrn=<?echo $resvoz['orgh'];?>&inn=<?echo $resvoz['inn'];?>&kpp=<?echo $resvoz['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resvoz['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resvoz['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resvoz['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resvoz['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resvoz['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resvoz['rand'];?>').hide();
                                $('#kalm<?echo $resvoz['rand'];?>').hide();
                                $('.modal-windowm<?echo $resvoz['rand'];?>').hide();
                                $('#prich<?echo $resvoz['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resvoz['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=vozvrat",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=vozvrat&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resvoz['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resvoz['rand'];?>').show();
                $('.modal-windowm<?echo $resvoz['rand'];?>').show();
                $('#kalm<?echo $resvoz['rand'];?>').show();
            });
            $('#otkaz<?echo $resvoz['rand'];?>').click(function () {

                $('#prich<?echo $resvoz['rand'];?>').show();

            });
            $('#vist<?echo $resvoz['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resvoz['rand'];?>').hide();
                $('#kalm<?echo $resvoz['rand'];?>').hide();
                $('.modal-windowm<?echo $resvoz['rand'];?>').hide();
                $('#prich<?echo $resvoz['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resvoz['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resvoz['rand'];?>').hide();
                $('#kalm<?echo $resvoz['rand'];?>').hide();
                $('.modal-windowm<?echo $resvoz['rand'];?>').hide();
                $('#prich<?echo $resvoz['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $resvoz['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resvoz['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resvoz['ogrn']);?>&kli=<? echo $resvoz['idkli'];?>&lico=<? echo $resvoz['lico'];?>&gr=<? echo $resvoz['gr'];?>&nomerschet=<? echo $resvoz['ns'];?>&produkt=<? echo $resvoz['produkt'];?>&inn=<? echo $resvoz['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $resvoz['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resvoz['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $resvoz['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="pereplata"){
if ($_GET['tipi']=="1"){
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '12354')";
	}

	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '12355')";
	}?>
<?php $rpere = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.ogrn as orgh,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($respere = mysql_fetch_assoc($rpere))  : ?>
<tr value="<?php echo $respere['id'];?> "class="schetpere">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#DCF541;"><?php echo $respere['d'].".".$respere['m'].".".$respere['y'];?></td>
<td style="background:#DCF541;"><?php echo $respere['ns'];?><?if($respere['nomerschetks']!=''){?><p><?php echo $respere['nomerschetks'];?></p><?}?></td>
<td><?echo $respere['inn'];?></td>
<td><?echo $respere['kpp'];?></td>
<td style="width: 30%;"><?echo $respere['ogrn'];?></td>
<td><?echo $respere['name'];?></td>
<td><?echo schet_tip_text($respere);?></td>
<td style="background:#DCF541;"><?echo $respere['priceks'];?></td>
<td style="background:#DCF541;"><?echo $respere['price'];?></td>
<td><?echo $respere['dataprod'];?></td>
<td style="width: 6%;background:#DCF541;">Переплата</td>
<td style="width: 7%;"><?echo $respere['f_name'].' '.mb_substr($respere['l_name'],0,1,'UTF-8'),'. '.mb_substr($respere['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($respere['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $respere['rand'];?>"id="schetkal<?echo $respere['rand'];?>"><?if($respere['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($respere['datebron']));
	$monvis=date('m', strtotime($respere['datebron']));
	$yesrvis=date('Y', strtotime($respere['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $respere['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $respere['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $respere['rand'];?>' id="datebr<?echo $respere['rand'];?>" type="date"value="<?if($respere['datebron']!="0000-00-00"){echo $respere['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $respere['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $respere['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $respere['rand'];?>').show();
    $('.modal-window<?echo $respere['rand'];?>').show();
	$('#kalbr<?echo $respere['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $respere['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $respere['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $respere['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $respere['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $respere['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $respere['rand'];?>').hide();
	  $('#kalbr<?echo $respere['rand'];?>').hide();
    $('.modal-window<?echo $respere['rand'];?>').hide();
	document.getElementById("scales<?echo $respere['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $respere['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $respere['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($respere['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $respere['rand'];?>"><?if($respere['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($respere['datezvon']));
	$monvis=date('m', strtotime($respere['datezvon']));
	$yesrvis=date('Y', strtotime($respere['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $respere['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $respere['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $respere['rand'];?>' id="datezs<?echo $respere['rand'];?>" type="date"value="<?if($respere['datezvon']!="0000-00-00"){echo $respere['datezvon'];}?>">
<textarea class='form-control timezs<?echo $respere['rand'];?>' id="timezs<?echo $respere['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $respere['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $respere['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $respere['rand'];?>').show();
    $('.modal-windowzs<?echo $respere['rand'];?>').show();
	$('#kalz<?echo $respere['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $respere['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $respere['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $respere['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $respere['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $respere['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $respere['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $respere['rand'];?>').hide();
	  $('#kalzs<?echo $respere['rand'];?>').hide();
    $('.modal-windowzs<?echo $respere['rand'];?>').hide();
	document.getElementById("scalezs<?echo $respere['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $respere['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $respere['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $respere['rand'];?>"id="movschet<?echo $respere['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $respere['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $respere['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $respere['idkli'];?>&parent=<?echo $respere['produkt'];?>&ogrn=<?echo $respere['orgh'];?>&inn=<?echo $respere['inn'];?>&kpp=<?echo $respere['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $respere['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $respere['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $respere['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $respere['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $respere['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $respere['rand'];?>').hide();
                                $('#kalm<?echo $respere['rand'];?>').hide();
                                $('.modal-windowm<?echo $respere['rand'];?>').hide();
                                $('#prich<?echo $respere['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $respere['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=pereplata",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $respere['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respere['rand'];?>').show();
                $('.modal-windowm<?echo $respere['rand'];?>').show();
                $('#kalm<?echo $respere['rand'];?>').show();
            });
            $('#otkaz<?echo $respere['rand'];?>').click(function () {

                $('#prich<?echo $respere['rand'];?>').show();

            });
            $('#vist<?echo $respere['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respere['rand'];?>').hide();
                $('#kalm<?echo $respere['rand'];?>').hide();
                $('.modal-windowm<?echo $respere['rand'];?>').hide();
                $('#prich<?echo $respere['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $respere['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respere['rand'];?>').hide();
                $('#kalm<?echo $respere['rand'];?>').hide();
                $('.modal-windowm<?echo $respere['rand'];?>').hide();
                $('#prich<?echo $respere['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $respere['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $respere['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $respere['ogrn']);?>&kli=<? echo $respere['idkli'];?>&lico=<? echo $respere['lico'];?>&gr=<? echo $respere['gr'];?>&nomerschet=<? echo $respere['ns'];?>&produkt=<? echo $respere['produkt'];?>&inn=<? echo $respere['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $respere['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $respere['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $respere['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}
if ($_GET['tipi']=="2")
{
	if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
	{
		$status="(schet.status = '12354')";
	}

	if($_GET['ogr']=="24")
	{
		$status="(schet.status = '12355')";
	}?>
<?php $rpere = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.ogrn as orgh,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.prodlenks='0' or schet.prodlen='0') and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
while($respere = mysql_fetch_assoc($rpere))  : ?>
<tr value="<?php echo $respere['id'];?> "class="schetpere">
<?$h++;
?>
<td><?echo $h;?></td>
<td style="background:#DCF541;"><?php echo $respere['d'].".".$respere['m'].".".$respere['y'];?></td>
<td style="background:#DCF541;"><?php echo $respere['ns'];?><?if($respere['nomerschetks']!=''){?><p><?php echo $respere['nomerschetks'];?></p><?}?></td>
<td><?echo $respere['inn'];?></td>
<td><?echo $respere['kpp'];?></td>
<td style="width: 30%;"><?echo $respere['ogrn'];?></td>
<td><?echo $respere['name'];?></td>
<td><?echo schet_tip_text($respere);?></td>
<td style="background:#DCF541;"><?echo $respere['priceks'];?></td>
<td style="background:#DCF541;"><?echo $respere['price'];?></td>
<td><?echo $respere['dataprod'];?></td>
<td style="width: 6%;background:#DCF541;">Переплата</td>
<td style="width: 7%;"><?echo $respere['f_name'].' '.mb_substr($respere['l_name'],0,1,'UTF-8'),'. '.mb_substr($respere['o_name'],0,1,'UTF-8').'.';?></td>
<td><ul class="schetkal"><?if($respere['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
<li name="schetkal<?echo $respere['rand'];?>"id="schetkal<?echo $respere['rand'];?>"><?if($respere['datebron']!="0000-00-00"){?><?
$dayvis=date('d', strtotime($respere['datebron']));
	$monvis=date('m', strtotime($respere['datebron']));
	$yesrvis=date('Y', strtotime($respere['datebron']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadow<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-window<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalbr<?echo $respere['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="bron" id="vidbr<?echo $respere['rand'];?>">Добавить бронь</label>
<input class='form-control datebr<?echo $respere['rand'];?>' id="datebr<?echo $respere['rand'];?>" type="date"value="<?if($respere['datebron']!="0000-00-00"){echo $respere['datebron'];}?>">
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scales<?echo $respere['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
	var sost="";
$(function(){
    $('#schetkal<?echo $respere['rand'];?>').click(function () {
		sost="bron";
      $('.modal-shadow<?echo $respere['rand'];?>').show();
    $('.modal-window<?echo $respere['rand'];?>').show();
	$('#kalbr<?echo $respere['rand'];?>').show();
    });
 
    $('.modal-shadow<?echo $respere['rand'];?>').click(function () {
		if(sost=="bron")
		{
        if(document.getElementById("scales<?echo $respere['rand'];?>").checked==true)
			{
				var message="bronyes";
			}
		datebroni=document.getElementsByClassName("datebr<?echo $respere['rand'];?>")[0].value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $respere['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetkal<?echo $respere['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadow<?echo $respere['rand'];?>').hide();
	  $('#kalbr<?echo $respere['rand'];?>').hide();
    $('.modal-window<?echo $respere['rand'];?>').hide();
	document.getElementById("scales<?echo $respere['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetkal<?echo $respere['rand'];?>').innerHTML;
document.getElementById('schetkal<?echo $respere['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
<td>
<ul class="schetzvons"><?if($respere['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
<li id="schetzvons<?echo $respere['rand'];?>"><?if($respere['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($respere['datezvon']));
	$monvis=date('m', strtotime($respere['datezvon']));
	$yesrvis=date('Y', strtotime($respere['datezvon']));
    $dss= $yesrvis.".".$monvis.".".$dayvis;
echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
</ul>
</td>
   <div class="modal-shadowzs<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
<div class="modal-windowzs<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
    <form class="contact_form" method="POST">
	<div id="kalzs<?echo $respere['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
<label class="zvon" id="vidzs<?echo $respere['rand'];?>">Перезвонить</label>
<input class='form-control datezs<?echo $respere['rand'];?>' id="datezs<?echo $respere['rand'];?>" type="date"value="<?if($respere['datezvon']!="0000-00-00"){echo $respere['datezvon'];}?>">
<textarea class='form-control timezs<?echo $respere['rand'];?>' id="timezs<?echo $respere['rand'];?>" type="time"></textarea>
<div style="padding-bottom: 10px;padding-top: 10px;">
<input class="check" id="scalezs<?echo $respere['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
<label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
</div>
</div>
	</form>
</div>
<script>
$(function(){
    $('#schetzvons<?echo $respere['rand'];?>').click(function () {
		sost="zvon";
      $('.modal-shadowzs<?echo $respere['rand'];?>').show();
    $('.modal-windowzs<?echo $respere['rand'];?>').show();
	$('#kalz<?echo $respere['rand'];?>').show();
    });
 
    $('.modal-shadowzs<?echo $respere['rand'];?>').click(function () {
		if(sost=="zvon")
		{
			if(document.getElementById("scalezs<?echo $respere['rand'];?>").checked==true)
			{
				var message="zvonyes";
			}
		datezvon=document.getElementsByClassName("datezs<?echo $respere['rand'];?>")[0].value;
		timezvon=document.getElementById("timezs<?echo $respere['rand'];?>").value;
		$.ajax({  
                    url: "bron.php",  
                    cache: false,  
					data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $respere['rand'];?>&users=<?echo $_GET['id'];?>",
                    success: function(msg){  
					 var s = document.getElementById("schetzvons<?echo $respere['rand'];?>");
				     s.innerHTML = msg;
                    }  
                });
	    }
      $('.modal-shadowzs<?echo $respere['rand'];?>').hide();
	  $('#kalzs<?echo $respere['rand'];?>').hide();
    $('.modal-windowzs<?echo $respere['rand'];?>').hide();
	document.getElementById("scalezs<?echo $respere['rand'];?>").checked=false;
    });
});
</script>
<script>
$(function() {
  $("#myTable1").tablesorter();
});
var dat=document.getElementById('schetzvons<?echo $respere['rand'];?>').innerHTML;
document.getElementById('schetzvons<?echo $respere['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
</script>
    <td value="<?echo $respere['rand'];?>"id="movschet<?echo $respere['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $respere['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $respere['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $respere['idkli'];?>&parent=<?echo $respere['produkt'];?>&ogrn=<?echo $respere['orgh'];?>&inn=<?echo $respere['inn'];?>&kpp=<?echo $respere['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $respere['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $respere['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $respere['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $respere['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $respere['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $respere['rand'];?>').hide();
                                $('#kalm<?echo $respere['rand'];?>').hide();
                                $('.modal-windowm<?echo $respere['rand'];?>').hide();
                                $('#prich<?echo $respere['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $respere['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=pereplata",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $respere['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respere['rand'];?>').show();
                $('.modal-windowm<?echo $respere['rand'];?>').show();
                $('#kalm<?echo $respere['rand'];?>').show();
            });
            $('#otkaz<?echo $respere['rand'];?>').click(function () {

                $('#prich<?echo $respere['rand'];?>').show();

            });
            $('#vist<?echo $respere['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respere['rand'];?>').hide();
                $('#kalm<?echo $respere['rand'];?>').hide();
                $('.modal-windowm<?echo $respere['rand'];?>').hide();
                $('#prich<?echo $respere['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $respere['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $respere['rand'];?>').hide();
                $('#kalm<?echo $respere['rand'];?>').hide();
                $('.modal-windowm<?echo $respere['rand'];?>').hide();
                $('#prich<?echo $respere['rand'];?>').hide();
            });
        });

    </script>
<td value="<?echo $respere['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $respere['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $respere['ogrn']);?>&kli=<? echo $respere['idkli'];?>&lico=<? echo $respere['lico'];?>&gr=<? echo $respere['gr'];?>&nomerschet=<? echo $respere['ns'];?>&produkt=<? echo $respere['produkt'];?>&inn=<? echo $respere['inn'];?>"><img src="/img/qwerty.png"></a></td>
<td value="<?echo $respere['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $respere['idkli'];?>"><img src="/img/tablsc.png"></a></td>
<td><a target="_blank" href=<?echo $respere['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
<?php endwhile; ?>
<?}}?>
<?if($_GET['tip']=="schetall"){
if ($_GET['tipi']=="1") {
?>
    <?php $rschetall = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.ogrn as orgh,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn  schet.del='0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."'  group by schet.ns");
    while($resschetall = mysql_fetch_assoc($rschetall))  :
        if( $resschetall['oplachenks']!="1")
        {
            $color="background:#C1E5FB;";
            $statussi="Счет выставлен";
        }
        if($resschetall['oplachenks'] =="1")
        {

            if ($resschetall['status']==''){
                $statussi="в работе";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='1'){
                $statussi="Ждем документы";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='2'){
                $statussi="На проверке";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='3'){
                $statussi="Отклонен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='4'){
                $statussi="Проверен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='6'){
                $statussi="Ожидание кассы";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='7'){
                $statussi="Ожидание кассы клиента";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='16'){
                $statussi="Выезд";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='19'){
                $statussi="Получение в лич.каб.";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='20'){
                $statussi="Получение в офисе";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='35'){
                $statussi="Заявка";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='37'){
                $statussi="Ждем опись";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='38'){
                $statussi="Опись принята";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='39'){
                $statussi="Опись передана менеджеру";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='40'){
                $statussi="Отправить в ГС1";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='41'){
                $statussi="Ждем КИЗ";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='42'){
                $statussi="Маркировка КИЗ без оборудования";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='43'){
                $statussi="Маркировка КИЗ с оборудования";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='44'){
                $statussi="Оплачен в ТС";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='45'){
                $statussi="Ждем ККТ";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='47'){
                $statussi="Товар получен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='48'){
                $statussi="Товар получен без ФН";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='49'){
                $statussi="На продлении";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='5'){
                $statussi="Поставка";
                $color="background:#E9C3FB;";
            }
            if ($resschetall['status']=='17'){
                $statussi="Установка";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='18'){
                $statussi="Выезд";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='161'){
                $statussi="Произв.устан.";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='36'){
                $statussi="Регистрация + Настройка";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='50'){
                $statussi="Установка в офисе";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='51'){
                $statussi="Выезд";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='21'){
                $statussi="Частично на отгрузке";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='65'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='77'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='52'){
                $statussi="Выдали";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='53'){
                $statussi="Жем закрывающие документы";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='60'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['akt']=='1' && $resrna['cher']=='0'){
                $statussi="Отгруженые";
                $color="background:#85D6A7;";
            }
            if ($resschetall['otk']=='0' && $resrna['cher']=='1'){
                $statussi="Отказ";
                $color="background:#FB9C9C;";
            }
            if ($resschetall['otk']=='1' && $resrna['cher']=='0'){
                $statussi="Черновик";
                $color="background:#BC9B79;";
            }
            if ($resschetall['status']=='23' || $resrna['status']=='12356'){
                $statussi="Возврат";
                $color="background:#E45A51;";
            }
            if ($resschetall['status']=='12354' || $resrna['status']=='12355'){
                $statussi="Переплата";
                $color="background:#DCF541;";
            }

        }
        ?>

        <tr value="<?php echo $resschetall['id'];?> "class="schetpere">
            <?$h++;

            ?>
            <td><?echo $h;?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['d'].".".$resschetall['m'].".".$resschetall['y'];?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['ns'];?><?if($resschetall['nomerschetks']!=''){?><p><?php echo $resschetall['nomerschetks'];?></p><?}?></td>
            <td><?echo $resschetall['inn'];?></td>
            <td><?echo $resschetall['kpp'];?></td>
            <td style="width: 30%;"><?echo $resschetall['ogrn'];?></td>
            <td><?echo $resschetall['name'];?></td>
            <td><?echo schet_tip_text($resschetall);?></td>
            <td style="<? echo $color;?>"><?echo $resschetall['priceks'];?></td>
            <td style="<? echo $color;?>"><?echo $resschetall['price'];?></td>
            <td><?echo $resschetall['dataprod'];?></td>
            <td style="width: 6%;<? echo $color;?>"><?echo $statussi;?></td>
            <td style="width: 7%;"><?echo $resschetall['f_name'].' '.mb_substr($resschetall['l_name'],0,1,'UTF-8'),'. '.mb_substr($resschetall['o_name'],0,1,'UTF-8').'.';?></td>
            <td><ul class="schetkal"><?if($resschetall['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                    <li name="schetkal<?echo $resschetall['rand'];?>"id="schetkal<?echo $resschetall['rand'];?>"><?if($resschetall['datebron']!="0000-00-00"){?><?
                            $dayvis=date('d', strtotime($resschetall['datebron']));
                            $monvis=date('m', strtotime($resschetall['datebron']));
                            $yesrvis=date('Y', strtotime($resschetall['datebron']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadow<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-window<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalbr<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="bron" id="vidbr<?echo $resschetall['rand'];?>">Добавить бронь</label>
                        <input class='form-control datebr<?echo $resschetall['rand'];?>' id="datebr<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datebron']!="0000-00-00"){echo $resschetall['datebron'];}?>">
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scales<?echo $resschetall['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                var sost="";
                $(function(){
                    $('#schetkal<?echo $resschetall['rand'];?>').click(function () {
                        sost="bron";
                        $('.modal-shadow<?echo $resschetall['rand'];?>').show();
                        $('.modal-window<?echo $resschetall['rand'];?>').show();
                        $('#kalbr<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadow<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="bron")
                        {
                            if(document.getElementById("scales<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="bronyes";
                            }
                            datebroni=document.getElementsByClassName("datebr<?echo $resschetall['rand'];?>")[0].value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetkal<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadow<?echo $resschetall['rand'];?>').hide();
                        $('#kalbr<?echo $resschetall['rand'];?>').hide();
                        $('.modal-window<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scales<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td>
                <ul class="schetzvons"><?if($resschetall['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                    <li id="schetzvons<?echo $resschetall['rand'];?>"><?if($resschetall['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resschetall['datezvon']));
                            $monvis=date('m', strtotime($resschetall['datezvon']));
                            $yesrvis=date('Y', strtotime($resschetall['datezvon']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadowzs<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowzs<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalzs<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="zvon" id="vidzs<?echo $resschetall['rand'];?>">Перезвонить</label>
                        <input class='form-control datezs<?echo $resschetall['rand'];?>' id="datezs<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datezvon']!="0000-00-00"){echo $resschetall['datezvon'];}?>">
                        <textarea class='form-control timezs<?echo $resschetall['rand'];?>' id="timezs<?echo $resschetall['rand'];?>" type="time"></textarea>
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scalezs<?echo $resschetall['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                $(function(){
                    $('#schetzvons<?echo $resschetall['rand'];?>').click(function () {
                        sost="zvon";
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').show();
                        $('#kalz<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="zvon")
                        {
                            if(document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="zvonyes";
                            }
                            datezvon=document.getElementsByClassName("datezs<?echo $resschetall['rand'];?>")[0].value;
                            timezvon=document.getElementById("timezs<?echo $resschetall['rand'];?>").value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetzvons<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').hide();
                        $('#kalzs<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td value="<?echo $resschetall['rand'];?>"id="movschet<?echo $resschetall['rand'];?>"><img src="/img/icons8.png"></td>
            <div class="modal-shadowm<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowm<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalm<?echo $resschetall['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resschetall['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resschetall['idkli'];?>&parent=<?echo $resschetall['produkt'];?>&ogrn=<?echo $resschetall['orgh'];?>&inn=<?echo $resschetall['inn'];?>&kpp=<?echo $resschetall['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resschetall['ns']?>')">Продлить</label>
                        <label class="otkaz" id="otkaz<?echo $resschetall['rand'];?>">Отказать</label>
                    </div>
                    <div id="prich<?echo $resschetall['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                        <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                        while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                            <label class="otkaz" id="otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                            <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                            <script>
                                $(function() {
                                    $('#otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                        document.getElementById('modal-shadowkube').style.display = "block";
                                        document.getElementById('kube').style.display = "block";
                                        var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                                        $('#prich<?echo $resschetall['rand'];?>').hide();
                                        $.ajax({
                                            type: "GET",
                                            url: "otkazschet.php",
                                            data: "tipotkaz=" + n + "&rand=<?echo $resschetall['rand'];?>&kto=<? echo $_GET['id'];?>&tipschet=pereplata",
                                            success: function (html) {
                                                $.ajax({
                                                    url: "tablschetosn.php",
                                                    cache: false,
                                                    data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                                    success: function (html) {
                                                        $("#tablosn").html(html);
                                                        document.getElementById('modal-shadowkube').style.display = "none";
                                                        document.getElementById('kube').style.display = "none";
                                                    }
                                                });
                                            }
                                        });
                                    });
                                });
                            </script>
                        <?endwhile?>
                    </div>
            </div>

            </form>


            </div>
            <script>
                $(function(){
                    $('#movschet<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').show();
                        $('#kalm<?echo $resschetall['rand'];?>').show();
                    });
                    $('#otkaz<?echo $resschetall['rand'];?>').click(function () {

                        $('#prich<?echo $resschetall['rand'];?>').show();

                    });
                    $('#vist<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();

                    });
                    $('.modal-shadowm<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();
                    });
                });

            </script>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resschetall['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resschetall['ogrn']);?>&kli=<? echo $resschetall['idkli'];?>&lico=<? echo $resschetall['lico'];?>&gr=<? echo $resschetall['gr'];?>&nomerschet=<? echo $resschetall['ns'];?>&produkt=<? echo $resschetall['produkt'];?>&inn=<? echo $resschetall['inn'];?>"><img src="/img/qwerty.png"></a></td>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resschetall['idkli'];?>"><img src="/img/tablsc.png"></a></td>
            <td><a target="_blank" href=<?echo $resschetall['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
        </tr>
    <?php endwhile; }?>
<?if ($_GET['tipi']=="2")
{?>
    <?php $rschetall = mysql_query("SELECT schet.id,schet.ns,schet.akt,schet.cher,schet.otk,DATE_FORMAT(schet.datasert,'%Y.%m.%d') as sert,DATE_FORMAT(schet.dataprod,'%Y.%m.%d') as prod,schet.rand,schet.kto,schet.prodlenks,schet.prodlens,schet.shetold,schet.url,schet.ogrn as orgh,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,DATE_FORMAT(schet.dataprod,'%m') as date_po,DATE_FORMAT(schet.dataprod,'%Y') as date_poy,DATE_FORMAT(schet.datasert,'%m') as date_se,DATE_FORMAT(schet.datasert,'%Y') as date_sey,schet.dataprod,schet.datasert,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE   $uslugiogrn  schet.del='0' and (schet.shetold!='' and schet.cher!='1' and schet.otk!='1' and schet.akt!='1' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$dataproms."' and '".$datapromf."' or schet.tipprod!=''and schet.tipprod!='Нет'and schet.shetold='' and schet.prodlens='0'and schet.prodlenks='0'and schet.cher='0' and schet.otk='0' and (schet.dataprod BETWEEN'".$_GET['ds']."' and '".$_GET['df']."' or schet.datasert BETWEEN'".$_GET['ds']."' and '".$_GET['df']."'))  group by schet.ns");
    while($resschetall = mysql_fetch_assoc($rschetall))  :
        if( $resschetall['oplachenks']!="1")
        {
            $color="background:#C1E5FB;";
            $statussi="Счет выставлен";
        }
        if($resschetall['oplachenks'] =="1")
        {

            if ($resschetall['status']==''){
                $statussi="в работе";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='1'){
                $statussi="Ждем документы";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='2'){
                $statussi="На проверке";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='3'){
                $statussi="Отклонен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='4'){
                $statussi="Проверен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='6'){
                $statussi="Ожидание кассы";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='7'){
                $statussi="Ожидание кассы клиента";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='16'){
                $statussi="Выезд";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='19'){
                $statussi="Получение в лич.каб.";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='20'){
                $statussi="Получение в офисе";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='35'){
                $statussi="Заявка";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='37'){
                $statussi="Ждем опись";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='38'){
                $statussi="Опись принята";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='39'){
                $statussi="Опись передана менеджеру";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='40'){
                $statussi="Отправить в ГС1";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='41'){
                $statussi="Ждем КИЗ";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='42'){
                $statussi="Маркировка КИЗ без оборудования";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='43'){
                $statussi="Маркировка КИЗ с оборудования";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='44'){
                $statussi="Оплачен в ТС";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='45'){
                $statussi="Ждем ККТ";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='47'){
                $statussi="Товар получен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='48'){
                $statussi="Товар получен без ФН";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='49'){
                $statussi="На продлении";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='5'){
                $statussi="Поставка";
                $color="background:#E9C3FB;";
            }
            if ($resschetall['status']=='17'){
                $statussi="Установка";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='18'){
                $statussi="Выезд";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='161'){
                $statussi="Произв.устан.";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='36'){
                $statussi="Регистрация + Настройка";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='50'){
                $statussi="Установка в офисе";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='51'){
                $statussi="Выезд";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='21'){
                $statussi="Частично на отгрузке";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='65'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='77'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='52'){
                $statussi="Выдали";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='53'){
                $statussi="Жем закрывающие документы";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='60'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='23' || $resrna['status']=='12356'){
                $statussi="Возврат";
                $color="background:#E45A51;";
            }
            if ($resschetall['status']=='12354' || $resrna['status']=='12355'){
                $statussi="Переплата";
                $color="background:#DCF541;";
            }

        }
        if ($resschetall['shetold']==''){
            $statussi="Не продлено";
            $color="background:#C1BBBB;";
        }
        if ($resschetall['akt']=='1' && $resschetall['cher']=='0' && $resschetall['otk']=='0' &&$resschetall['shetold']!='')
        {
            $statussi="Отгружен";
            $color="background:#85D6A7;";
        }
        if ($resschetall['otk']=='0' && $resschetall['cher']=='1'){
            $statussi="Отказ";
            $color="background:#FB9C9C;";
        }
        if ($resschetall['otk']=='1' && $resschetall['cher']=='0'){
            $statussi="Черновик";
            $color="background:#BC9B79;";
        }
        if( $resschetall['tipprod']!='Сер/Пос'){?>
        <tr value="<?php echo $resschetall['id'];?> "class="schetpere">
            <?$h++;

            ?>
            <td><?echo $h;?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['d'].".".$resschetall['m'].".".$resschetall['y'];?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['ns'];?><?if($resschetall['nomerschetks']!=''){?><p><?php echo $resschetall['nomerschetks'];?></p><?}?></td>
            <td><?echo $resschetall['inn'];?></td>
            <td><?echo $resschetall['kpp'];?></td>
            <td style="width: 30%;"><?echo $resschetall['ogrn'];?></td>
            <td><?echo $resschetall['name'];?></td>
            <td><?echo schet_tip_text($resschetall);?></td>
            <td style="<? echo $color;?>"><?echo $resschetall['priceks'];?></td>
            <td style="<? echo $color;?>"><?echo $resschetall['price'];?></td>
            <?if($resschetall['shetold']!=''){?>
            <td><p id="datpr<?echo $resschetall['rand'];?>"><?$userdatas = mysql_fetch_assoc(mysql_query("SELECT DATE_FORMAT(schet.datasert,'%Y.%m.%d') as sert,DATE_FORMAT(schet.dataprod,'%Y.%m.%d') as prod,dataprod,prodlens,prodlenks,datasert FROM schet WHERE ns = '".$resschetall['shetold']."' "));
                    if($userdatas['dataprod']!=""&&$userdatas['datasert']=="") {echo $userdatas['prod'];}if($userdatas['datasert']!=""&&$userdatas['dataprod']=="") {echo $userdatas['sert'];}if($userdatas['dataprod']!=""&&$userdatas['datasert']!=""){if($userdatas['prodlens']=="1"){echo $userdatas['prod'];}if($userdatas['prodlenks']=="1"){?><br><?echo $userdatas['sert'];}}if($userdatas['dataprod']==""&&$userdatas['datasert']==""){echo "Нет продления";}?></p>
            </td><?}
            if($resschetall['shetold']==''){?>
                    <td><p id="datpr<?echo $resschetall['rand'];?>"><?if($resschetall['datasert']!=""){echo $resschetall['sert'];}if($resschetall['dataprod']!=""){echo $resschetall['prod'];}?></p></td><?}?>
            <script> $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('datpr<?echo $resschetall['rand'];?>').innerHTML;

                document.getElementById('datpr<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');</script>
            <td style="width: 6%;<? echo $color;?>"><?echo $statussi;?></td>
            <td style="width: 7%;"><?echo $resschetall['f_name'].' '.mb_substr($resschetall['l_name'],0,1,'UTF-8'),'. '.mb_substr($resschetall['o_name'],0,1,'UTF-8').'.';?></td>
            <td><ul class="schetkal"><?if($resschetall['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                    <li name="schetkal<?echo $resschetall['rand'];?>"id="schetkal<?echo $resschetall['rand'];?>"><?if($resschetall['datebron']!="0000-00-00"){?><?
                            $dayvis=date('d', strtotime($resschetall['datebron']));
                            $monvis=date('m', strtotime($resschetall['datebron']));
                            $yesrvis=date('Y', strtotime($resschetall['datebron']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadow<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-window<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalbr<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="bron" id="vidbr<?echo $resschetall['rand'];?>">Добавить бронь</label>
                        <input class='form-control datebr<?echo $resschetall['rand'];?>' id="datebr<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datebron']!="0000-00-00"){echo $resschetall['datebron'];}?>">
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scales<?echo $resschetall['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                var sost="";
                $(function(){
                    $('#schetkal<?echo $resschetall['rand'];?>').click(function () {
                        sost="bron";
                        $('.modal-shadow<?echo $resschetall['rand'];?>').show();
                        $('.modal-window<?echo $resschetall['rand'];?>').show();
                        $('#kalbr<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadow<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="bron")
                        {
                            if(document.getElementById("scales<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="bronyes";
                            }
                            datebroni=document.getElementsByClassName("datebr<?echo $resschetall['rand'];?>")[0].value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetkal<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadow<?echo $resschetall['rand'];?>').hide();
                        $('#kalbr<?echo $resschetall['rand'];?>').hide();
                        $('.modal-window<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scales<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td>
                <ul class="schetzvons"><?if($resschetall['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                    <li id="schetzvons<?echo $resschetall['rand'];?>"><?if($resschetall['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resschetall['datezvon']));
                            $monvis=date('m', strtotime($resschetall['datezvon']));
                            $yesrvis=date('Y', strtotime($resschetall['datezvon']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadowzs<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowzs<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalzs<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="zvon" id="vidzs<?echo $resschetall['rand'];?>">Перезвонить</label>
                        <input class='form-control datezs<?echo $resschetall['rand'];?>' id="datezs<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datezvon']!="0000-00-00"){echo $resschetall['datezvon'];}?>">
                        <textarea class='form-control timezs<?echo $resschetall['rand'];?>' id="timezs<?echo $resschetall['rand'];?>" type="time"></textarea>
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scalezs<?echo $resschetall['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                $(function(){
                    $('#schetzvons<?echo $resschetall['rand'];?>').click(function () {
                        sost="zvon";
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').show();
                        $('#kalz<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="zvon")
                        {
                            if(document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="zvonyes";
                            }
                            datezvon=document.getElementsByClassName("datezs<?echo $resschetall['rand'];?>")[0].value;
                            timezvon=document.getElementById("timezs<?echo $resschetall['rand'];?>").value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetzvons<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').hide();
                        $('#kalzs<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td value="<?echo $resschetall['rand'];?>"id="movschet<?echo $resschetall['rand'];?>"><img src="/img/icons8.png"></td>
            <div class="modal-shadowm<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowm<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalm<?echo $resschetall['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resschetall['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resschetall['idkli'];?>&parent=<?echo $resschetall['produkt'];?>&ogrn=<?echo $resschetall['orgh'];?>&inn=<?echo $resschetall['inn'];?>&kpp=<?echo $resschetall['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resschetall['ns']?>')">Продлить</label>
                        <label class="otkaz" id="otkaz<?echo $resschetall['rand'];?>">Отказать</label>
                    </div>
                    <div id="prich<?echo $resschetall['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                        <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                        while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                            <label class="otkaz" id="otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                            <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                            <script>
                                $(function() {
                                    $('#otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                        document.getElementById('modal-shadowkube').style.display = "block";
                                        document.getElementById('kube').style.display = "block";
                                        var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                                        $('#prich<?echo $resschetall['rand'];?>').hide();
                                        $.ajax({
                                            type: "GET",
                                            url: "otkazschet.php",
                                            data: "tipotkaz=" + n + "&rand=<?echo $_GET['rand'];?>&tipschet=pereplata",
                                            success: function (html) {
                                                $.ajax({
                                                    url: "tablschetosn.php",
                                                    cache: false,
                                                    data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                                    success: function (html) {
                                                        $("#tablosn").html(html);
                                                        document.getElementById('modal-shadowkube').style.display = "none";
                                                        document.getElementById('kube').style.display = "none";
                                                    }
                                                });
                                            }
                                        });
                                    });
                                });
                            </script>
                        <?endwhile?>
                    </div>
            </div>

            </form>


            </div>
            <script>
                $(function(){
                    $('#movschet<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').show();
                        $('#kalm<?echo $resschetall['rand'];?>').show();
                    });
                    $('#otkaz<?echo $resschetall['rand'];?>').click(function () {

                        $('#prich<?echo $resschetall['rand'];?>').show();

                    });
                    $('#vist<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();

                    });
                    $('.modal-shadowm<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();
                    });
                });

            </script>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resschetall['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resschetall['ogrn']);?>&kli=<? echo $resschetall['idkli'];?>&lico=<? echo $resschetall['lico'];?>&gr=<? echo $resschetall['gr'];?>&nomerschet=<? echo $resschetall['ns'];?>&produkt=<? echo $resschetall['produkt'];?>&inn=<? echo $resschetall['inn'];?>"><img src="/img/qwerty.png"></a></td>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resschetall['idkli'];?>"><img src="/img/tablsc.png"></a></td>
            <td><a target="_blank" href=<?echo $resschetall['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
        </tr>
            <?}
    if( $resschetall['tipprod']=='Сер/Пос'&& stristr($resschetall['date_se'], $_GET['na'])==true&& stristr($resschetall['date_sey'], $_GET['nay'])==true||stristr($resschetall['date_sey'], $_GET['nay']+1)==true&&$resschetall['prodlenks']=='0'){?>
<tr value="<?php echo $resschetall['id'];?>"class="schetprodlenie">
    <?$h++;?>
    <td><?echo $h;?></td>
    <td style="<? echo $color;?>"><?php echo $resschetall['d'].".".$resschetall['m'].".".$resschetall['y'];?></td>
    <td style="<? echo $color;?>"><?php echo $resschetall['ns'];?><?if($resschetall['nomerschetks']!=''){?><p><?php echo $resschetall['nomerschetks'];?></p><?}?></td>
    <td><?echo $resschetall['inn'];?></td>
    <td><?echo $resschetall['kpp'];?></td>
    <td style="width: 30%;"><?echo $resschetall['ogrn'];?></td>
    <td><?echo $resschetall['name'];?></td>
    <td>Сертификат</td>
    <td style="<? echo $color;?>"><?echo $resschetall['priceks'];?></td>
    <td style="<? echo $color;?>"><?echo $resschetall['price'];?></td>
    <td><p id="datpr<?echo $resschetall['rand'];?>"><?echo $resschetall['sert'];?></p></td>
    <script> $(function() {
            $("#myTable1").tablesorter();
        });
        var dat=document.getElementById('datpr<?echo $resschetall['rand'];?>').innerHTML;

        document.getElementById('datpr<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');</script>
    <td style="width: 6%;<? echo $color;?>"><?echo $statussi;?></td>
    <td style="width: 7%;"><?echo $resschetall['f_name'].' '.mb_substr($resschetall['l_name'],0,1,'UTF-8'),'. '.mb_substr($resschetall['o_name'],0,1,'UTF-8').'.';?></td>
    <td><ul class="schetkal"><?if($resschetall['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
            <li name="schetkal<?echo $resschetall['rand'];?>"id="schetkal<?echo $resschetall['rand'];?>"><?if($resschetall['datebron']!="0000-00-00"){?><?
                    $dayvis=date('d', strtotime($resschetall['datebron']));
                    $monvis=date('m', strtotime($resschetall['datebron']));
                    $yesrvis=date('Y', strtotime($resschetall['datebron']));
                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
        </ul>
    </td>
    <div class="modal-shadow<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-window<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalbr<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                <label class="bron" id="vidbr<?echo $resschetall['rand'];?>">Добавить бронь</label>
                <input class='form-control datebr<?echo $resschetall['rand'];?>' id="datebr<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datebron']!="0000-00-00"){echo $resschetall['datebron'];}?>">
                <div style="padding-bottom: 10px;padding-top: 10px;">
                    <input class="check" id="scales<?echo $resschetall['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                </div>
            </div>
        </form>
    </div>
    <script>
        var sost="";
        $(function(){
            $('#schetkal<?echo $resschetall['rand'];?>').click(function () {
                sost="bron";
                $('.modal-shadow<?echo $resschetall['rand'];?>').show();
                $('.modal-window<?echo $resschetall['rand'];?>').show();
                $('#kalbr<?echo $resschetall['rand'];?>').show();
            });

            $('.modal-shadow<?echo $resschetall['rand'];?>').click(function () {
                if(sost=="bron")
                {
                    if(document.getElementById("scales<?echo $resschetall['rand'];?>").checked==true)
                    {
                        var message="bronyes";
                    }
                    datebroni=document.getElementsByClassName("datebr<?echo $resschetall['rand'];?>")[0].value;
                    $.ajax({
                        url: "bron.php",
                        cache: false,
                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                        success: function(msg){
                            var s = document.getElementById("schetkal<?echo $resschetall['rand'];?>");
                            s.innerHTML = msg;
                        }
                    });
                }
                $('.modal-shadow<?echo $resschetall['rand'];?>').hide();
                $('#kalbr<?echo $resschetall['rand'];?>').hide();
                $('.modal-window<?echo $resschetall['rand'];?>').hide();
                document.getElementById("scales<?echo $resschetall['rand'];?>").checked=false;
            });
        });
    </script>
    <script>
        $(function() {
            $("#myTable1").tablesorter();
        });
        var dat=document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML;
        document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
    </script>
    <td>
        <ul class="schetzvons"><?if($resschetall['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
            <li id="schetzvons<?echo $resschetall['rand'];?>"><?if($resschetall['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resschetall['datezvon']));
                    $monvis=date('m', strtotime($resschetall['datezvon']));
                    $yesrvis=date('Y', strtotime($resschetall['datezvon']));
                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
        </ul>
    </td>
    <div class="modal-shadowzs<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowzs<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalzs<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                <label class="zvon" id="vidzs<?echo $resschetall['rand'];?>">Перезвонить</label>
                <input class='form-control datezs<?echo $resschetall['rand'];?>' id="datezs<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datezvon']!="0000-00-00"){echo $resschetall['datezvon'];}?>">
                <textarea class='form-control timezs<?echo $resschetall['rand'];?>' id="timezs<?echo $resschetall['rand'];?>" type="time"></textarea>
                <div style="padding-bottom: 10px;padding-top: 10px;">
                    <input class="check" id="scalezs<?echo $resschetall['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(function(){
            $('#schetzvons<?echo $resschetall['rand'];?>').click(function () {
                sost="zvon";
                $('.modal-shadowzs<?echo $resschetall['rand'];?>').show();
                $('.modal-windowzs<?echo $resschetall['rand'];?>').show();
                $('#kalz<?echo $resschetall['rand'];?>').show();
            });

            $('.modal-shadowzs<?echo $resschetall['rand'];?>').click(function () {
                if(sost=="zvon")
                {
                    if(document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked==true)
                    {
                        var message="zvonyes";
                    }
                    datezvon=document.getElementsByClassName("datezs<?echo $resschetall['rand'];?>")[0].value;
                    timezvon=document.getElementById("timezs<?echo $resschetall['rand'];?>").value;
                    $.ajax({
                        url: "bron.php",
                        cache: false,
                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                        success: function(msg){
                            var s = document.getElementById("schetzvons<?echo $resschetall['rand'];?>");
                            s.innerHTML = msg;
                        }
                    });
                }
                $('.modal-shadowzs<?echo $resschetall['rand'];?>').hide();
                $('#kalzs<?echo $resschetall['rand'];?>').hide();
                $('.modal-windowzs<?echo $resschetall['rand'];?>').hide();
                document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked=false;
            });
        });
    </script>
    <script>
        $(function() {
            $("#myTable1").tablesorter();
        });
        var dat=document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML;
        document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
    </script>
    <td value="<?echo $resschetall['rand'];?>"id="movschet<?echo $resschetall['rand'];?>"><img src="/img/icons8.png"></td>
    <div class="modal-shadowm<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
    <div class="modal-windowm<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
        <form class="contact_form" method="POST">
            <div id="kalm<?echo $resschetall['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resschetall['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resschetall['idkli'];?>&parent=<?echo $resschetall['produkt'];?>&ogrn=<?echo $resschetall['orgh'];?>&inn=<?echo $resschetall['inn'];?>&kpp=<?echo $resschetall['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resschetall['ns']?>')">Продлить</label>
                <label class="otkaz" id="otkaz<?echo $resschetall['rand'];?>">Отказать</label>
            </div>
            <div id="prich<?echo $resschetall['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                    <label class="otkaz" id="otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                    <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                    <script>
                        $(function() {
                            $('#otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                document.getElementById('modal-shadowkube').style.display = "block";
                                document.getElementById('kube').style.display = "block";
                                var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                                $('#kalm<?echo $resschetall['rand'];?>').hide();
                                $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                                $('#prich<?echo $resschetall['rand'];?>').hide();
                                $.ajax({
                                    type: "GET",
                                    url: "otkazschet.php",
                                    data: "tipotkaz=" + n + "&rand=<?echo $resschetall['rand'];?>&kto=<?echo $_GET['id']?>&tipschet=pereplata",
                                    success: function (html) {
                                        $.ajax({
                                            url: "tablschetosn.php",
                                            cache: false,
                                            data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                            success: function (html) {
                                                $("#tablosn").html(html);
                                                document.getElementById('modal-shadowkube').style.display = "none";
                                                document.getElementById('kube').style.display = "none";
                                            }
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                <?endwhile?>
            </div>
    </div>

    </form>


    </div>
    <script>
        $(function(){
            $('#movschet<?echo $resschetall['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resschetall['rand'];?>').show();
                $('.modal-windowm<?echo $resschetall['rand'];?>').show();
                $('#kalm<?echo $resschetall['rand'];?>').show();
            });
            $('#otkaz<?echo $resschetall['rand'];?>').click(function () {

                $('#prich<?echo $resschetall['rand'];?>').show();

            });
            $('#vist<?echo $resschetall['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                $('#kalm<?echo $resschetall['rand'];?>').hide();
                $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                $('#prich<?echo $resschetall['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resschetall['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                $('#kalm<?echo $resschetall['rand'];?>').hide();
                $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                $('#prich<?echo $resschetall['rand'];?>').hide();
            });
        });

    </script>
    <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resschetall['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resschetall['ogrn']);?>&kli=<? echo $resschetall['idkli'];?>&lico=<? echo $resschetall['lico'];?>&gr=<? echo $resschetall['gr'];?>&nomerschet=<? echo $resschetall['ns'];?>&produkt=<? echo $resschetall['produkt'];?>&inn=<? echo $resschetall['inn'];?>"><img src="/img/qwerty.png"></a></td>
    <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resschetall['idkli'];?>"><img src="/img/tablsc.png"></a></td>
    <td><a target="_blank" href=<?echo $resschetall['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
</tr>
        <?}if( $resschetall['tipprod']=='Сер/Пос'&& stristr($resschetall['date_po'], $_GET['na'])==true&& stristr($resschetall['date_poy'], $_GET['nay'])==true||stristr($resschetall['date_poy'], $_GET['nay']+1)==true||$res['tipprod']=='Сер/Пос'&& stristr($resschetall['date_po'], $_GET['naf'])==true&& stristr($resschetall['date_poy'], $_GET['nayf'])==true||stristr($resschetall['date_poy'], $_GET['nayf']+1)==true){?>
        <?$h++;?>
        <tr value="<?php echo $resschetall['id'];?>"class="schetprodlenie">
            <?$h++;?>
            <td><?echo $h;?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['d'].".".$resschetall['m'].".".$resschetall['y'];?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['ns'];?><?if($resschetall['nomerschetks']!=''){?><p><?php echo $resschetall['nomerschetks'];?></p><?}?></td>
            <td><?echo $resschetall['inn'];?></td>
            <td><?echo $resschetall['kpp'];?></td>
            <td style="width: 30%;"><?echo $resschetall['ogrn'];?></td>
            <td><?echo $resschetall['name'];?></td>
            <td>Поставка</td>
            <td style="<? echo $color;?>"><?echo $resschetall['priceks'];?></td>
            <td style="<? echo $color;?>"><?echo $resschetall['price'];?></td>
            <td><p id="datpr<?echo $resschetall['rand'];?>"><?echo $resschetall['prod'];?></p></td>
            <script> $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('datpr<?echo $resschetall['rand'];?>').innerHTML;

                document.getElementById('datpr<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');</script>
            <td style="width: 6%;<? echo $color;?>"><?echo $statussi;?></td>
            <td style="width: 7%;"><?echo $resschetall['f_name'].' '.mb_substr($resschetall['l_name'],0,1,'UTF-8'),'. '.mb_substr($resschetall['o_name'],0,1,'UTF-8').'.';?></td>
            <td><ul class="schetkal"><?if($resschetall['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                    <li name="schetkal<?echo $resschetall['rand'];?>"id="schetkal<?echo $resschetall['rand'];?>"><?if($resschetall['datebron']!="0000-00-00"){?><?
                            $dayvis=date('d', strtotime($resschetall['datebron']));
                            $monvis=date('m', strtotime($resschetall['datebron']));
                            $yesrvis=date('Y', strtotime($resschetall['datebron']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadow<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-window<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalbr<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="bron" id="vidbr<?echo $resschetall['rand'];?>">Добавить бронь</label>
                        <input class='form-control datebr<?echo $resschetall['rand'];?>' id="datebr<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datebron']!="0000-00-00"){echo $resschetall['datebron'];}?>">
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scales<?echo $resschetall['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                var sost="";
                $(function(){
                    $('#schetkal<?echo $resschetall['rand'];?>').click(function () {
                        sost="bron";
                        $('.modal-shadow<?echo $resschetall['rand'];?>').show();
                        $('.modal-window<?echo $resschetall['rand'];?>').show();
                        $('#kalbr<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadow<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="bron")
                        {
                            if(document.getElementById("scales<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="bronyes";
                            }
                            datebroni=document.getElementsByClassName("datebr<?echo $resschetall['rand'];?>")[0].value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetkal<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadow<?echo $resschetall['rand'];?>').hide();
                        $('#kalbr<?echo $resschetall['rand'];?>').hide();
                        $('.modal-window<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scales<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td>
                <ul class="schetzvons"><?if($resschetall['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                    <li id="schetzvons<?echo $resschetall['rand'];?>"><?if($resschetall['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resschetall['datezvon']));
                            $monvis=date('m', strtotime($resschetall['datezvon']));
                            $yesrvis=date('Y', strtotime($resschetall['datezvon']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadowzs<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowzs<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalzs<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="zvon" id="vidzs<?echo $resschetall['rand'];?>">Перезвонить</label>
                        <input class='form-control datezs<?echo $resschetall['rand'];?>' id="datezs<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datezvon']!="0000-00-00"){echo $resschetall['datezvon'];}?>">
                        <textarea class='form-control timezs<?echo $resschetall['rand'];?>' id="timezs<?echo $resschetall['rand'];?>" type="time"></textarea>
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scalezs<?echo $resschetall['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                $(function(){
                    $('#schetzvons<?echo $resschetall['rand'];?>').click(function () {
                        sost="zvon";
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').show();
                        $('#kalz<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="zvon")
                        {
                            if(document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="zvonyes";
                            }
                            datezvon=document.getElementsByClassName("datezs<?echo $resschetall['rand'];?>")[0].value;
                            timezvon=document.getElementById("timezs<?echo $resschetall['rand'];?>").value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetzvons<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').hide();
                        $('#kalzs<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td value="<?echo $resschetall['rand'];?>"id="movschet<?echo $resschetall['rand'];?>"><img src="/img/icons8.png"></td>
            <div class="modal-shadowm<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowm<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalm<?echo $resschetall['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resschetall['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resschetall['idkli'];?>&parent=<?echo $resschetall['produkt'];?>&ogrn=<?echo $resschetall['orgh'];?>&inn=<?echo $resschetall['inn'];?>&kpp=<?echo $resschetall['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resschetall['ns']?>')">Продлить</label>
                        <label class="otkaz" id="otkaz<?echo $resschetall['rand'];?>">Отказать</label>
                    </div>
                    <div id="prich<?echo $resschetall['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                        <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                        while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                            <label class="otkaz" id="otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                            <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                            <script>
                                $(function() {
                                    $('#otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                        document.getElementById('modal-shadowkube').style.display = "block";
                                        document.getElementById('kube').style.display = "block";
                                        var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                                        $('#prich<?echo $resschetall['rand'];?>').hide();
                                        $.ajax({
                                            type: "GET",
                                            url: "otkazschet.php",
                                            data: "tipotkaz=" + n + "&rand=<?echo $resschetall['rand'];?>&kto=<?echo $_GET['id']?>&tipschet=pereplata",
                                            success: function (html) {
                                                $.ajax({
                                                    url: "tablschetosn.php",
                                                    cache: false,
                                                    data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                                    success: function (html) {
                                                        $("#tablosn").html(html);
                                                        document.getElementById('modal-shadowkube').style.display = "none";
                                                        document.getElementById('kube').style.display = "none";
                                                    }
                                                });
                                            }
                                        });
                                    });
                                });
                            </script>
                        <?endwhile?>
                    </div>
            </div>

            </form>


            </div>
            <script>
                $(function(){
                    $('#movschet<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').show();
                        $('#kalm<?echo $resschetall['rand'];?>').show();
                    });
                    $('#otkaz<?echo $resschetall['rand'];?>').click(function () {

                        $('#prich<?echo $resschetall['rand'];?>').show();

                    });
                    $('#vist<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();

                    });
                    $('.modal-shadowm<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();
                    });
                });

            </script>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resschetall['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resschetall['ogrn']);?>&kli=<? echo $resschetall['idkli'];?>&lico=<? echo $resschetall['lico'];?>&gr=<? echo $resschetall['gr'];?>&nomerschet=<? echo $resschetall['ns'];?>&produkt=<? echo $resschetall['produkt'];?>&inn=<? echo $resschetall['inn'];?>"><img src="/img/qwerty.png"></a></td>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resschetall['idkli'];?>"><img src="/img/tablsc.png"></a></td>
            <td><a target="_blank" href=<?echo $resschetall['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
        </tr>
        <?}?>
    <?php endwhile; ?>
<?}}?>
</tbody>
</table>
</div>

<?
if ($_GET['na']!='10'||$_GET['nay']!='2020')
{?>
<div style="
    color: #626262;
    font-size: 20pt;
    margin-top: 10px;
    margin-bottom: 10px;
">Старые счета</div>
<?
$_monthsList = array(
    "1"=>"Январь","2"=>"Февраль","3"=>"Март",
    "4"=>"Апрель","5"=>"Май", "6"=>"Июнь",
    "7"=>"Июль","8"=>"Август","9"=>"Сентябрь",
    "10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь");
$var1 ="2020-09";
$var2 ="2020-12";
$date1 = strtotime('2020-10-01'); $date2 = strtotime($_GET['ds']); $months = 0; while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2) $months++;
$date = new DateTime('2020-10-01');
for($mon=0;$mon<$months;$mon++)
{
    if($mon>0)
    {
        $kolmonf=1;
    }
    else
    {
        $kolmonf=0;
    }
$date->modify('+' . $kolmonf . ' month');


$day2 = $date->format('Y-m-d');
$dayvis = date('d', strtotime($day2));
$monvis = date('m', strtotime($day2));
$yesrvis = date('Y', strtotime($day2));
$ds = $yesrvis . "-" . $monvis . "-" . $dayvis;
$date3 = new DateTime($ds);
$date3->modify('last day of this month');
$dayf = $date3->format('d');
 $df = $yesrvis . "-" . $monvis . "-" . $dayf;
if($monvis<10) {
    $mons = $monvis{1};
}
else {
    $mons = $monvis;
}

?>
     <div class="schet-table-scroll" style="
    background: #78AFD8;
    font-size: 14pt;
    width: 100%;
    color: #626262;margin-top: 10px;
"><?echo $_monthsList[$mons]." ".$yesrvis;?>
         <table class="schet-table" style="
    background: white;
">
        <thead>
        <tr>
            <th>№</th>
            <?if($_GET['tip']=="prod"&&$_GET['tipi']=="2")
            {?>
                <th>Старый №счета</th>
            <?}?>
            <th>дата счета</th>
            <th>№счета</th>
            <th>инн</th>
            <th>кпп</th>
            <th>наименование</th>
            <th>продукт</th>
            <th>тип</th>
            <th>К</th>
            <th>С</th>
            <th>продление</th>
            <th>статус</th>
            <th>менеджер</th>
            <th style="
    width: 6%;
">контроль бронь</th>
            <th style="
    width: 6%;
">дата звонка</th>
            <th><img src="/img/icons8.png"></th>
            <th><img src="/img/qwerty.png"></th>
            <th><img src="/img/tablsc.png"></th>
            <th><img src="/img/ship.png"style="width: 20px;"></th>
        </tr>
        </thead>
        <tbody>
    <?if($_GET['tip']=="prod") {
        if($_GET['tipi']=="2"){?>
            <?
            $ht=0;
            $r = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.nomerschetks,schet.url,schet.inn,DATE_FORMAT(schet.dataprod,'%m') as date_po,DATE_FORMAT(schet.dataprod,'%Y') as date_poy,DATE_FORMAT(schet.datasert,'%m') as date_se,DATE_FORMAT(schet.datasert,'%Y') as date_sey,DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,schet.dataprod,schet.datasert,users.f_name,users.l_name,users.o_name,schet.prodlenks,schet.prodlens FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id  WHERE uslugi.id='".$_GET['ogr']."' and schet.del!='1'and schet.shetold=''and  schet.tipprod!=''and schet.tipprod!='Нет' and schet.prodlens='0'and schet.prodlenks='0'and (schet.dataprod BETWEEN'$ds' and '$df' or schet.datasert BETWEEN'$ds' and '$df') group by schet.ns");
            while($res = mysql_fetch_assoc($r))  :$ner=($_GET['na']-$months);
                if( $res['tipprod']!='Сер/Пос'){?>
                    <tr>
                        <?$ht++;?>
                        <td><?echo $ht;?></td>
                        <td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
                        <td style="background:#C1BBBB;">Ожидаем</td>
                        <td style="background:#C1BBBB;">Ожидаем</td>
                        <td><?echo $res['inn'];?></td>
                        <td><?echo $res['kpp'];?></td>
                        <td style="width: 30%;"><?echo $res['ogrn'];?></td>
                        <td><?echo $res['name'];?></td>
                        <td><?echo schet_tip_text($res);?></td>
                        <td><?echo $res['priceks'];?></td>
                        <td><?echo $res['price'];?></td>
                        <td style="width: 4%;"><?if($res['dataprod']!=''){echo $res['dataprod'];}if($res['datasert']!=''){echo '<p>';echo $res['datasert'];}echo'</p>';?></td>
                        <td style="width: 7%;background:#C1BBBB;">не продлено</td>
                        <td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
                        <td>
                            <ul class="schetkal">
                                <li id="schetkal<?echo $res['rand'];?>"><?if($res['date_reg']!="00.00.0000"){?><img src="/img/dino.png"><?}echo $res['date_reg'];?></li>
                            </ul>
                            <div class="modal-shadow<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                            <div class="modal-window<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                                <form class="contact_form" method="POST">
                                    <div id="kalbr<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                        <label class="bron" id="vidbr<?echo $res['rand'];?>">Добавить бронь</label>
                                        <input class='form-control datebr<?echo $res['rand'];?>' id="datebr<?echo $res['rand'];?>" type="date">
                                        <div style="padding-bottom: 10px;padding-top: 10px;">
                                            <input class="check" id="scales<?echo $res['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td>
                            <ul class="schetzvon">
                                <li id="schetzvon<?echo $res['rand'];?>"><?if($res['date_zvon']!="00.00.0000"){?><img src="/img/phonegreen.png"><?}echo $res['date_zvon'];?></li>
                            </ul>
                            <div class="modal-shadowz<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                            <div class="modal-windowz<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                                <form class="contact_form" method="POST">
                                    <div id="kalz<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                        <label class="zvon" id="vidz<?echo $res['rand'];?>">Перезвонить</label>
                                        <input class='form-control datez<?echo $res['rand'];?>' id="datez<?echo $res['rand'];?>" type="date">
                                        <textarea class='form-control timez<?echo $res['rand'];?>' id="timez<?echo $res['rand'];?>" type="time"></textarea>
                                        <div style="padding-bottom: 10px;padding-top: 10px;">
                                            <input class="check" id="scalez<?echo $res['rand'];?>" name="scalez" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalez">Отправить письмо на почту</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td value="<?echo $res['rand'];?>"><img src="/img/qwerty.png"></td>
                        <td value="<?echo $res['rand'];?>"><img src="/img/tablsc.png"></td>
                        <td><a target="_blank" href=<?echo $res['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                    </tr>
                <?}
                if( $res['tipprod']=='Сер/Пос'&& stristr($res['date_se'], "10")==true && stristr($res['date_sey'], $_GET['nay'])==true){?>
                    <tr value="<?php echo $res['id'];?>">
                        <?$ht++;?>
                        <td><?echo $ht;?></td>
                        <td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
                        <td style="background:#C1BBBB;">Ожидаем</td>
                        <td style="background:#C1BBBB;">Ожидаем</td>
                        <td><?echo $res['inn'];?></td>
                        <td><?echo $res['kpp'];?></td>
                        <td style="width: 30%;"><?echo $res['ogrn'];?></td>
                        <td><?echo $res['name'];?></td>
                        <td>Сертификат</td>
                        <td><?echo $res['priceks'];?></td>
                        <td><?echo $res['price'];?></td>
                        <td style="width: 4%;"><?echo $res['datasert'];?></td>
                        <td style="width: 7%;background:#C1BBBB;">не продлено</td>
                        <td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
                        <td>
                            <ul class="schetkal">
                                <li id="schetkal<?echo $res['rand'];?>"><?if($res['date_reg']!="00.00.0000"){?><img src="/img/dino.png"><?}echo $res['date_reg'];?></li>
                            </ul>
                            <div class="modal-shadow<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                            <div class="modal-window<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                                <form class="contact_form" method="POST">
                                    <div id="kalbr<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                        <label class="bron" id="vidbr<?echo $res['rand'];?>">Добавить бронь</label>
                                        <input class='form-control datebr<?echo $res['rand'];?>' id="datebr<?echo $res['rand'];?>" type="date">
                                        <div style="padding-bottom: 10px;padding-top: 10px;">
                                            <input class="check" id="scales<?echo $res['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td>
                            <ul class="schetzvon">
                                <li id="schetzvon<?echo $res['rand'];?>"><?if($res['date_zvon']!="00.00.0000"){?><img src="/img/phonegreen.png"><?}echo $res['date_zvon'];?></li>
                            </ul>
                            <div class="modal-shadowz<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                            <div class="modal-windowz<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                                <form class="contact_form" method="POST">
                                    <div id="kalz<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                        <label class="zvon" id="vidz<?echo $res['rand'];?>">Перезвонить</label>
                                        <input class='form-control datez<?echo $res['rand'];?>' id="datez<?echo $res['rand'];?>" type="date">
                                        <textarea class='form-control timez<?echo $res['rand'];?>' id="timez<?echo $res['rand'];?>" type="time"></textarea>
                                        <div style="padding-bottom: 10px;padding-top: 10px;">
                                            <input class="check" id="scalez<?echo $res['rand'];?>" name="scalez" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalez">Отправить письмо на почту</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td value="<?echo $res['rand'];?>"><img src="/img/qwerty.png"></td>
                        <td value="<?echo $res['rand'];?>"><img src="/img/tablsc.png"></td>
                        <td><a target="_blank" href=<?echo $res['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                    </tr>
                <?}
                if( $res['tipprod']=='Сер/Пос' && stristr($res['date_po'], "10")==true && stristr($res['date_poy'], $_GET['nay'])==true){?>
                <?$ht++;?>
                <tr>
                    <td><?echo $ht;?></td>
                    <td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
                    <td style="background:#C1BBBB;">Ожидаем</td>
                    <td style="background:#C1BBBB;">Ожидаем</td>
                    <td><?echo $res['inn'];?></td>
                    <td><?echo $res['kpp'];?></td>
                    <td style="width: 30%;"><?echo $res['ogrn'];?></td>
                    <td><?echo $res['name'];?></td>
                    <td>Поставка</td>
                    <td><?echo $res['priceks'];?></td>
                    <td><?echo $res['price'];?></td>
                    <td style="width: 4%;"><?echo $res['dataprod'];?></td>
                    <td style="width: 7%;background:#C1BBBB;">не продлено</td>
                    <td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
                    <td>
                        <ul class="schetkal">
                            <li id="schetkal<?echo $res['rand'];?>"><?if($res['date_reg']!="00.00.0000"){?><img src="/img/dino.png"><?}echo $res['date_reg'];?></li>
                        </ul>
                        <div class="modal-shadow<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                        <div class="modal-window<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                            <form class="contact_form" method="POST">
                                <div id="kalbr<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                    <label class="bron" id="vidbr<?echo $res['rand'];?>">Добавить бронь</label>
                                    <input class='form-control datebr<?echo $res['rand'];?>' id="datebr<?echo $res['rand'];?>" type="date">
                                    <div style="padding-bottom: 10px;padding-top: 10px;">
                                        <input class="check" id="scales<?echo $res['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                        <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </td>
                    <td>
                        <ul class="schetzvon">
                            <li id="schetzvon<?echo $res['rand'];?>"><?if($res['date_zvon']!="00.00.0000"){?><img src="/img/phonegreen.png"><?}echo $res['date_zvon'];?></li>
                        </ul>
                        <div class="modal-shadowz<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                        <div class="modal-windowz<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                            <form class="contact_form" method="POST">
                                <div id="kalz<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                    <label class="zvon" id="vidz<?echo $res['rand'];?>">Перезвонить</label>
                                    <input class='form-control datez<?echo $res['rand'];?>' id="datez<?echo $res['rand'];?>" type="date">
                                    <textarea class='form-control timez<?echo $res['rand'];?>' id="timez<?echo $res['rand'];?>" type="time"></textarea>
                                    <div style="padding-bottom: 10px;padding-top: 10px;">
                                        <input class="check" id="scalez<?echo $res['rand'];?>" name="scalez" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                        <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalez">Отправить письмо на почту</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </td>
                    <td value="<?echo $res['rand'];?>"><img src="/img/qwerty.png"></td>
                    <td value="<?echo $res['rand'];?>"><img src="/img/tablsc.png"></td>
                    <td><a target="_blank" href=<?echo $res['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?}?>
            <?php endwhile; ?>
            <?}
        if($_GET['tipi']=="1")
        {?>
            <?
            $ht=0;
            $r = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.nomerschetks,schet.url,schetprodlen.dateprod as prodschet,schet.inn,DATE_FORMAT(schet.dataprod,'%m') as date_po,DATE_FORMAT(schet.dataprod,'%y') as date_poy,DATE_FORMAT(schet.datasert,'%m') as date_se,DATE_FORMAT(schet.datasert,'%y') as date_sey,DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,schet.dataprod,schet.datasert,users.f_name,users.l_name,users.o_name,schet.prodlenks,schet.prodlens FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join schetprodlen on schet.shetold=schetprodlen.schetold WHERE $uslugiogrn  schet.del!='1'and schet.shetold!=''and  schet.tipprod!=''and schet.tipprod!='Нет'  and schet.prodlens='1'and schet.prodlenks='1'and (schet.dataprod BETWEEN'$ds' and '$df' or schet.datasert BETWEEN'$ds' and '$df') group by schet.ns");
            while($res = mysql_fetch_assoc($r))  :
                if( $res['tipprod']!='Сер/Пос'){?>
                    <tr>
                        <?$ht++;?>
                        <td><?echo $ht;?></td>
                        <td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
                        <td style="background:#C1BBBB;">Ожидаем</td>
                        <td style="background:#C1BBBB;">Ожидаем</td>
                        <td><?echo $res['inn'];?></td>
                        <td><?echo $res['kpp'];?></td>
                        <td style="width: 30%;"><?echo $res['ogrn'];?></td>
                        <td><?echo $res['name'];?></td>
                        <td><?echo schet_tip_text($res);?></td>
                        <td><?echo $res['priceks'];?></td>
                        <td><?echo $res['price'];?></td>
                        <td style="width: 4%;"><?if($res['dataprod']!=''){echo $res['dataprod'];}if($res['datasert']!=''){echo '<p>';echo $res['datasert'];}echo'</p>';?></td>
                        <td style="width: 7%;background:#C1BBBB;">не продлено</td>
                        <td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
                        <td>
                            <ul class="schetkal">
                                <li id="schetkal<?echo $res['rand'];?>"><?if($res['date_reg']!="00.00.0000"){?><img src="/img/dino.png"><?}echo $res['date_reg'];?></li>
                            </ul>
                            <div class="modal-shadow<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                            <div class="modal-window<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                                <form class="contact_form" method="POST">
                                    <div id="kalbr<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                        <label class="bron" id="vidbr<?echo $res['rand'];?>">Добавить бронь</label>
                                        <input class='form-control datebr<?echo $res['rand'];?>' id="datebr<?echo $res['rand'];?>" type="date">
                                        <div style="padding-bottom: 10px;padding-top: 10px;">
                                            <input class="check" id="scales<?echo $res['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td>
                            <ul class="schetzvon">
                                <li id="schetzvon<?echo $res['rand'];?>"><?if($res['date_zvon']!="00.00.0000"){?><img src="/img/phonegreen.png"><?}echo $res['date_zvon'];?></li>
                            </ul>
                            <div class="modal-shadowz<?echo $res['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                            <div class="modal-windowz<?echo $res['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                                <form class="contact_form" method="POST">
                                    <div id="kalz<?echo $res['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                        <label class="zvon" id="vidz<?echo $res['rand'];?>">Перезвонить</label>
                                        <input class='form-control datez<?echo $res['rand'];?>' id="datez<?echo $res['rand'];?>" type="date">
                                        <textarea class='form-control timez<?echo $res['rand'];?>' id="timez<?echo $res['rand'];?>" type="time"></textarea>
                                        <div style="padding-bottom: 10px;padding-top: 10px;">
                                            <input class="check" id="scalez<?echo $res['rand'];?>" name="scalez" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalez">Отправить письмо на почту</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td value="<?echo $res['rand'];?>"><img src="/img/qwerty.png"></td>
                        <td value="<?echo $res['rand'];?>"><img src="/img/tablsc.png"></td>
                        <td><a target="_blank" href=<?echo $res['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                    </tr>
                <?}
                if( $res['tipprod']=='Сер/Пос'&& stristr($res['date_se'], $_GET['na'])==true&& stristr($res['date_sey'], $_GET['nay'])==true&& $res['prodlenks']=='0'||$res['tipprod']=='Сер/Пос'&& stristr($res['date_se'], $_GET['naf'])==true&& stristr($res['date_sey'], $_GET['nayf'])==true){?>
                    <tr>
                        <?$h++;?>
                        <td><?echo $ht;?></td>
                        <td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
                        <td style="background:#C1BBBB;">Ожидаем</td>
                        <td style="background:#C1BBBB;">Ожидаем</td>
                        <td><?echo $res['inn'];?></td>
                        <td><?echo $res['kpp'];?></td>
                        <td style="width: 30%;"><?echo $res['ogrn'];?></td>
                        <td><?echo $res['name'];?></td>
                        <td><?echo schet_tip_text($res);?></td>
                        <td><?echo $res['priceks'];?></td>
                        <td><?echo $res['price'];?></td>
                        <td style="width: 4%;"><?echo $res['dataprod'];?></td>
                        <td style="width: 7%;background:#C1BBBB;">не продлено</td>
                        <td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
                    </tr>
                <?}if( $res['tipprod']=='Сер/Пос'&& stristr($res['date_po'], $_GET['na'])==true&& stristr($res['date_poy'], $_GET['nay'])==true||$res['tipprod']=='Сер/Пос'&& stristr($res['date_po'], $_GET['naf'])==true&& stristr($res['date_poy'], $_GET['nayf'])==true){?>
                <?$h++;?>
                <tr>
                    <td><?echo $h;?></td>
                    <td style="background:#C1BBBB;"><?echo $res['ns'];?><p><?echo $res['nomerschetks'];?></p></td>
                    <td style="background:#C1BBBB;">Ожидаем</td>
                    <td style="background:#C1BBBB;">Ожидаем</td>
                    <td><?echo $res['inn'];?></td>
                    <td><?echo $res['kpp'];?></td>
                    <td style="width: 30%;"><?echo $res['ogrn'];?></td>
                    <td><?echo $res['name'];?></td>
                    <td><?echo schet_tip_text($res);?></td>
                    <td><?echo $res['priceks'];?></td>
                    <td><?echo $res['price'];?></td>
                    <td style="width: 4%;"><?echo $res['datasert'];?></td>
                    <td style="width: 7%;background:#C1BBBB;">не продлено</td>
                    <td style="width: 7%;"><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
                </tr>
            <?}?>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="schet"){
        if ($_GET['tipi']=="1"){?>
            <?php $rs = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.idkli,DATE_FORMAT(schet.datasert,'%d.%m.%Y') as sert,DATE_FORMAT(schet.dataprod,'%d.%m.%Y') as prod,schet.gr,schet.ogrn,schet.lico,schet.nomerschetks,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.del!='1'AND schet.oplachenks!= '1' and schet.oplachen != '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($ress = mysql_fetch_assoc($rs))  : ?>
                <tr value="<?php echo $ress['id'];?> "class="schetdol">
                    <?$h++;
                    if($ress['yes']=="11"||$ress['yes']=="1")
                    {
                        $color="background:#FFF850;";
                    }
                    else
                    {
                        $color="background:#C1E5FB;";
                    }
                    ?>

                    <td><?echo $h;?></td>
                    <td style="background:#C1E5FB;" id="datasch<?echo $ress['rand'];?>"><?php
                        $dayvis=$ress['d'];
                        $monvis=$ress['m'];
                        $yesrvis=$ress['y'];
                        $dss= $yesrvis.".".$monvis.".".$dayvis;
                        echo $yesrvis.".".$monvis.".".$dayvis;?></td>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('datasch<?echo $ress['rand'];?>').innerHTML;
                        document.getElementById('datasch<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td style="background:#C1E5FB;"><?php echo $ress['ns'];?><?if($ress['nomerschetks']!=''){?><p><?php echo $ress['nomerschetks'];?></p><?}?></td>
                    <td><?echo $ress['inn'];?></td>
                    <td><?echo $ress['kpp'];?></td>
                    <td style="width: 30%;"><?echo $ress['ogrn'];?></td>
                    <td><?echo $ress['name'];?></td>
                    <td><?echo schet_tip_text($ress);?></td>
                    <td style="background:#C1E5FB;"><?echo $ress['priceks'];?></td>
                    <td style="<?echo $color;?>"><?echo $ress['price'];?></td>
                    <td><?$userdatas = mysql_fetch_assoc(mysql_query("SELECT DATE_FORMAT(schet.datasert,'%d.%m.%Y') as sert,DATE_FORMAT(schet.dataprod,'%d.%m.%Y') as prod,dataprod,prodlens,prodlenks,datasert FROM schet WHERE ns = '".$ress['shetold']."' "));
                        if($userdatas['dataprod']!=""&&$userdatas['datasert']=="") {echo $userdatas['prod'];}if($userdatas['datasert']!=""&&$userdatas['dataprod']=="") {echo $userdatas['sert'];}if($userdatas['dataprod']!=""&&$userdatas['datasert']!=""){if($userdatas['prodlens']=="1"){echo $userdatas['prod'];}if($userdatas['prodlenks']=="1"){?><br><?echo $userdatas['sert'];}}if($userdatas['dataprod']==""&&$userdatas['datasert']==""){echo "Нет продления";}?></td>
                    <td style="width: 6%;background:#C1E5FB;">Счет выставлен</td>
                    <td style="width: 7%;"><?echo $ress['f_name'].' '.mb_substr($ress['l_name'],0,1,'UTF-8'),'. '.mb_substr($ress['o_name'],0,1,'UTF-8').'.';?></td>
                    <td style="width: 8%;"><ul class="schetkal"><?if($ress['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $ress['rand'];?>"id="schetkal<?echo $ress['rand'];?>"><?if($ress['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($ress['datebron']));
                                    $monvis=date('m', strtotime($ress['datebron']));
                                    $yesrvis=date('Y', strtotime($ress['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>

                    </td>
                    <div class="modal-shadow<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $ress['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $ress['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $ress['rand'];?>' id="datebr<?echo $ress['rand'];?>" type="date"value="<?if($ress['datebron']!="0000-00-00"){echo $ress['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $ress['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $ress['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $ress['rand'];?>').show();
                                $('.modal-window<?echo $ress['rand'];?>').show();
                                $('#kalbr<?echo $ress['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $ress['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $ress['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $ress['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $ress['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $ress['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $ress['rand'];?>').hide();
                                $('#kalbr<?echo $ress['rand'];?>').hide();
                                $('.modal-window<?echo $ress['rand'];?>').hide();
                                document.getElementById("scales<?echo $ress['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $ress['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td style="width: 8%;">
                        <ul class="schetzvons"><?if($ress['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $ress['rand'];?>"><?if($ress['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($ress['datezvon']));
                                    $monvis=date('m', strtotime($ress['datezvon']));
                                    $yesrvis=date('Y', strtotime($ress['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $ress['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $ress['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $ress['rand'];?>' id="datezs<?echo $ress['rand'];?>" type="date"value="<?if($ress['datezvon']!="0000-00-00"){echo $ress['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $ress['rand'];?>' id="timezs<?echo $ress['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $ress['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $ress['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $ress['rand'];?>').show();
                                $('.modal-windowzs<?echo $ress['rand'];?>').show();
                                $('#kalz<?echo $ress['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $ress['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $ress['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $ress['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $ress['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $ress['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $ress['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $ress['rand'];?>').hide();
                                $('#kalzs<?echo $ress['rand'];?>').hide();
                                $('.modal-windowzs<?echo $ress['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $ress['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $ress['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $ress['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $ress['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $ress['ogrn']);?>&kli=<? echo $ress['idkli'];?>&lico=<? echo $ress['lico'];?>&gr=<? echo $ress['gr'];?>&nomerschet=<? echo $ress['ns'];?>&produkt=<? echo $ress['produkt'];?>&inn=<? echo $ress['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $ress['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $ress['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $ress['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>

            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2")
        {?>
            <?php $rs = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.idkli,schet.gr,schet.ogrn,schet.url,schet.lico,schet.datasert,dataprod,schet.shetold,schet.oplachenks,schet.oplachen,schet.nomerschetks,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,DATE_FORMAT(schet.datebron,'%d.%m.%Y') as date_reg,DATE_FORMAT(schet.datebron,'%d') as date_regd,DATE_FORMAT(schet.datebron,'%m') as date_regm,DATE_FORMAT(schet.datebron,'%Y') as date_regy,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.del!='1'AND schet.oplachenks!= '1' and schet.oplachen != '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns ");
            while($ress = mysql_fetch_assoc($rs))  : ?>
                <tr value="<?php echo $ress['id'];?> "class="schetdol">
                    <?$h++;
                    if($ress['yes']=="11"||$ress['yes']=="1")
                    {
                        $color="background:#FFF850;";
                    }
                    else
                    {
                        $color="background:#C1E5FB;";
                    }
                    ?>

                    <td><?echo $h;?></td>
                    <td style="background:#C1E5FB;" id="datasch<?echo $ress['rand'];?>"><?php
                        $dayvis=$ress['d'];
                        $monvis=$ress['m'];
                        $yesrvis=$ress['y'];
                        $dss= $yesrvis.".".$monvis.".".$dayvis;
                        echo $yesrvis.".".$monvis.".".$dayvis;?></td>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('datasch<?echo $ress['rand'];?>').innerHTML;
                        document.getElementById('datasch<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td style="background:#C1E5FB;"><?php echo $ress['ns'];?><?if($ress['nomerschetks']!=''){?><p><?php echo $ress['nomerschetks'];?></p><?}?></td>
                    <td><?echo $ress['inn'];?></td>
                    <td><?echo $ress['kpp'];?></td>
                    <td style="width: 30%;"><?echo $ress['ogrn'];?></td>
                    <td><?echo $ress['name'];?></td>
                    <td><?echo schet_tip_text($ress);?></td>
                    <td style="background:#C1E5FB;"><?echo $ress['priceks'];?></td>
                    <td style="<?echo $color;?>"><?echo $ress['price'];?></td>
                    <td ><p id="datpr<?echo $ress['rand'];?>"><?$userdatas = mysql_fetch_assoc(mysql_query("SELECT DATE_FORMAT(schet.datasert,'%Y.%m.%d') as sert,DATE_FORMAT(schet.dataprod,'%Y.%m.%d') as prod,dataprod,prodlens,prodlenks,datasert FROM schet WHERE ns = '".$ress['shetold']."' "));
                            if($userdatas['dataprod']!=""&&$userdatas['datasert']=="") {echo $userdatas['prod'];}if($userdatas['datasert']!=""&&$userdatas['dataprod']=="") {echo $userdatas['sert'];}if($userdatas['dataprod']!=""&&$userdatas['datasert']!=""){if($userdatas['prodlens']=="1"){echo $userdatas['prod'];}if($userdatas['prodlenks']=="1"){?><br><?echo $userdatas['sert'];}}if($userdatas['dataprod']==""&&$userdatas['datasert']==""){echo "Нет продления";}?></p></td>
                    <script> $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('datpr<?echo $ress['rand'];?>').innerHTML;

                        document.getElementById('datpr<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');</script>

                    <td style="width: 6%;background:#C1E5FB;">Счет выставлен</td>
                    <td style="width: 7%;"><?echo $ress['f_name'].' '.mb_substr($ress['l_name'],0,1,'UTF-8'),'. '.mb_substr($ress['o_name'],0,1,'UTF-8').'.';?></td>
                    <td style="width: 8%;">
                        <ul class="schetkal"><?if($ress['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $ress['rand'];?>"id="schetkal<?echo $ress['rand'];?>"><?if($ress['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($ress['datebron']));
                                    $monvis=date('m', strtotime($ress['datebron']));
                                    $yesrvis=date('Y', strtotime($ress['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>

                    </td>
                    <div class="modal-shadow<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $ress['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $ress['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $ress['rand'];?>' id="datebr<?echo $ress['rand'];?>" type="date"value="<?if($ress['datebron']!="0000-00-00"){echo $ress['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $ress['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $ress['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $ress['rand'];?>').show();
                                $('.modal-window<?echo $ress['rand'];?>').show();
                                $('#kalbr<?echo $ress['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $ress['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $ress['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $ress['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $ress['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $ress['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $ress['rand'];?>').hide();
                                $('#kalbr<?echo $ress['rand'];?>').hide();
                                $('.modal-window<?echo $ress['rand'];?>').hide();
                                document.getElementById("scales<?echo $ress['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $ress['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td style="width: 8%;">
                        <ul class="schetzvons"><?if($ress['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $ress['rand'];?>"><?if($ress['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($ress['datezvon']));
                                    $monvis=date('m', strtotime($ress['datezvon']));
                                    $yesrvis=date('Y', strtotime($ress['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>

                    </td>
                    <div class="modal-shadowzs<?echo $ress['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $ress['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $ress['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $ress['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $ress['rand'];?>' id="datezs<?echo $ress['rand'];?>" type="date"value="<?if($ress['datezvon']!="0000-00-00"){echo $ress['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $ress['rand'];?>' id="timezs<?echo $ress['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $ress['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $ress['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $ress['rand'];?>').show();
                                $('.modal-windowzs<?echo $ress['rand'];?>').show();
                                $('#kalzs<?echo $ress['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $ress['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $ress['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $ress['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $ress['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $ress['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $ress['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $ress['rand'];?>').hide();
                                $('#kalzs<?echo $ress['rand'];?>').hide();
                                $('.modal-windowzs<?echo $ress['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $ress['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $ress['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $ress['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    </td>
                    <td value="<?echo $ress['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $ress['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $ress['ogrn']);?>&kli=<? echo $ress['idkli'];?>&lico=<? echo $ress['lico'];?>&gr=<? echo $ress['gr'];?>&nomerschet=<? echo $ress['ns'];?>&produkt=<? echo $ress['produkt'];?>&inn=<? echo $ress['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $ress['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $ress['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $ress['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>


            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="oplachen"){
        if ($_GET['tipi']=="1"){
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '' or schet.status = '1'or schet.status = '2'or schet.status = '3'or schet.status = '4'or schet.status = '6'or schet.status = '7'or schet.status = '16'or schet.status = '19'or schet.status = '20')";
            }
            if($_GET['ogr']=="22")
            {
                $status="(schet.status = '' or schet.status = '35'or schet.status = '37'or schet.status = '38'or schet.status = '39'or schet.status = '40'or schet.status = '41'or schet.status = '42'or schet.status = '43')";
            }
            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '' or schet.status = '44'or schet.status = '45'or schet.status = '47'or schet.status = '48'or schet.status = '49')";
            }?>

            <?php $ro = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.idkli,schet.url,schet.gr,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.ogrn,schet.lico,schet.oplachen,schet.nomerschetks,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($reso = mysql_fetch_assoc($ro))  : ?>
                <tr value="<?php echo $reso['id'];?> "class="schetopl">
                    <?$h++;
                    if($reso['yes']=="11"||$reso['yes']=="1")
                    {
                        $color="background:#FFF850;"."color:#666;";
                    }
                    else
                    {
                        $color="background:#FFF850;";
                    }
                    ?>

                    <td><?echo $h;?></td>
                    <td style="background:#FFF850;"><?php echo $reso['d'].".".$reso['m'].".".$reso['y'];?></td>
                    <td style="background:#FFF850;"><?php echo $reso['ns'];?><?if($reso['nomerschetks']!=''){?><p><?php echo $reso['nomerschetks'];?></p><?}?></td>
                    <td><?echo $reso['inn'];?></td>
                    <td><?echo $reso['kpp'];?></td>
                    <td style="width: 30%;"><?echo $reso['ogrn'];?></td>
                    <td><?echo $reso['name'];?></td>
                    <td><?echo schet_tip_text($reso);?></td>
                    <td style="background:#FFF850;"><?echo $reso['priceks'];?></td>
                    <td style="<?echo $color;?>"><?echo $reso['price'];?></td>
                    <td><?echo $reso['dataprod'];?></td>
                    <td style="width: 6%;background:#FFF850;">
                        <?if ($reso['status']==''){
                            echo"в работе";
                        }
                        if ($reso['status']=='1'){
                            echo"Ждем документы";
                        }
                        if ($reso['status']=='2'){
                            echo"На проверке";
                        }
                        if ($reso['status']=='3'){
                            echo"Отклонен";
                        }
                        if ($reso['status']=='4'){
                            echo"Проверен";
                        }
                        if ($reso['status']=='6'){
                            echo"Ожидание кассы";
                        }
                        if ($reso['status']=='7'){
                            echo"Ожидание кассы клиента";
                        }
                        if ($reso['status']=='16'){
                            echo"Выезд";
                        }
                        if ($reso['status']=='19'){
                            echo"Получение в лич.каб.";
                        }
                        if ($reso['status']=='20'){
                            echo"Получение в офисе";
                        }
                        if ($reso['status']=='35'){
                            echo"Заявка";
                        }
                        if ($reso['status']=='37'){
                            echo"Ждем опись";
                        }
                        if ($reso['status']=='38'){
                            echo"Опись принята";
                        }
                        if ($reso['status']=='39'){
                            echo"Опись передана менеджеру";
                        }
                        if ($reso['status']=='40'){
                            echo"Отправить в ГС1";
                        }
                        if ($reso['status']=='41'){
                            echo"Ждем КИЗ";
                        }
                        if ($reso['status']=='42'){
                            echo"Маркировка КИЗ без оборудования";
                        }
                        if ($reso['status']=='43'){
                            echo"Маркировка КИЗ с оборудования";
                        }
                        if ($reso['status']=='44'){
                            echo"Оплачен в ТС";
                        }
                        if ($reso['status']=='45'){
                            echo"Ждем ККТ";
                        }
                        if ($reso['status']=='47'){
                            echo"Товар получен";
                        }
                        if ($reso['status']=='48'){
                            echo"Товар получен без ФН";
                        }
                        if ($reso['status']=='49'){
                            echo"На продлении";
                        }
                        ?>

                    </td>
                    <td style="width: 7%;"><?echo $reso['f_name'].' '.mb_substr($reso['l_name'],0,1,'UTF-8'),'. '.mb_substr($reso['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($reso['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $reso['rand'];?>"id="schetkal<?echo $reso['rand'];?>"><?if($reso['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($reso['datebron']));
                                    $monvis=date('m', strtotime($reso['datebron']));
                                    $yesrvis=date('Y', strtotime($reso['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $reso['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $reso['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $reso['rand'];?>' id="datebr<?echo $reso['rand'];?>" type="date"value="<?if($reso['datebron']!="0000-00-00"){echo $reso['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $reso['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $reso['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $reso['rand'];?>').show();
                                $('.modal-window<?echo $reso['rand'];?>').show();
                                $('#kalbr<?echo $reso['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $reso['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $reso['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $reso['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $reso['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $reso['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $reso['rand'];?>').hide();
                                $('#kalbr<?echo $reso['rand'];?>').hide();
                                $('.modal-window<?echo $reso['rand'];?>').hide();
                                document.getElementById("scales<?echo $reso['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $reso['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $reso['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($reso['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $reso['rand'];?>"><?if($reso['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($reso['datezvon']));
                                    $monvis=date('m', strtotime($reso['datezvon']));
                                    $yesrvis=date('Y', strtotime($reso['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $reso['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $reso['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $reso['rand'];?>' id="datezs<?echo $reso['rand'];?>" type="date"value="<?if($reso['datezvon']!="0000-00-00"){echo $reso['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $reso['rand'];?>' id="timezs<?echo $reso['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $reso['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $reso['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $reso['rand'];?>').show();
                                $('.modal-windowzs<?echo $reso['rand'];?>').show();
                                $('#kalz<?echo $reso['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $reso['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $reso['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $reso['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $reso['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $reso['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $reso['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $reso['rand'];?>').hide();
                                $('#kalzs<?echo $reso['rand'];?>').hide();
                                $('.modal-windowzs<?echo $reso['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $reso['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $reso['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $reso['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $reso['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $reso['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $reso['ogrn']);?>&kli=<? echo $reso['idkli'];?>&lico=<? echo $reso['lico'];?>&gr=<? echo $reso['gr'];?>&nomerschet=<? echo $reso['ns'];?>&produkt=<? echo $reso['produkt'];?>&inn=<? echo $reso['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $reso['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $reso['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $reso['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2"){
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '' or schet.status = '1'or schet.status = '2'or schet.status = '3'or schet.status = '4'or schet.status = '6'or schet.status = '7'or schet.status = '16'or schet.status = '19'or schet.status = '20')";
            }
            if($_GET['ogr']=="22")
            {
                $status="(schet.status = '' or schet.status = '35'or schet.status = '37'or schet.status = '38'or schet.status = '39'or schet.status = '40'or schet.status = '41'or schet.status = '42'or schet.status = '43')";
            }
            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '' or schet.status = '44'or schet.status = '45'or schet.status = '47'or schet.status = '48'or schet.status = '49')";
            }?>
            <?php $ro = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($reso = mysql_fetch_assoc($ro))  : ?>
                <tr value="<?php echo $reso['id'];?> "class="schetopl">
                    <?$h++;
                    if($reso['yes']=="11"||$reso['yes']=="1")
                    {
                        $color="background:#FFF850;"."color:#666;";
                    }
                    else
                    {
                        $color="background:#FFF850;";
                    }
                    ?>

                    <td><?echo $h;?></td>
                    <td style="background:#FFF850;"><?php echo $reso['d'].".".$reso['m'].".".$reso['y'];?></td>
                    <td style="background:#FFF850;"><?php echo $reso['ns'];?><?if($reso['nomerschetks']!=''){?><p><?php echo $reso['nomerschetks'];?></p><?}?></td>
                    <td><?echo $reso['inn'];?></td>
                    <td><?echo $reso['kpp'];?></td>
                    <td style="width: 30%;"><?echo $reso['ogrn'];?></td>
                    <td><?echo $reso['name'];?></td>
                    <td><?echo schet_tip_text($reso);?></td>
                    <td style="background:#FFF850;"><?echo $reso['priceks'];?></td>
                    <td style="<?echo $color;?>"><?echo $reso['price'];?></td>
                    <td><?echo $reso['dataprod'];?></td>
                    <td style="width: 6%;background:#FFF850;"><?if ($reso['status']==''){
                            echo"в работе";
                        }
                        if ($reso['status']=='1'){
                            echo"Ждем документы";
                        }
                        if ($reso['status']=='2'){
                            echo"На проверке";
                        }
                        if ($reso['status']=='3'){
                            echo"Отклонен";
                        }
                        if ($reso['status']=='4'){
                            echo"Проверен";
                        }
                        if ($reso['status']=='6'){
                            echo"Ожидание кассы";
                        }
                        if ($reso['status']=='7'){
                            echo"Ожидание кассы клиента";
                        }
                        if ($reso['status']=='16'){
                            echo"Выезд";
                        }
                        if ($reso['status']=='19'){
                            echo"Получение в лич.каб.";
                        }
                        if ($reso['status']=='20'){
                            echo"Получение в офисе";
                        }
                        if ($reso['status']=='35'){
                            echo"Заявка";
                        }
                        if ($reso['status']=='37'){
                            echo"Ждем опись";
                        }
                        if ($reso['status']=='38'){
                            echo"Опись принята";
                        }
                        if ($reso['status']=='39'){
                            echo"Опись передана менеджеру";
                        }
                        if ($reso['status']=='40'){
                            echo"Отправить в ГС1";
                        }
                        if ($reso['status']=='41'){
                            echo"Ждем КИЗ";
                        }
                        if ($reso['status']=='42'){
                            echo"Маркировка КИЗ без оборудования";
                        }
                        if ($reso['status']=='43'){
                            echo"Маркировка КИЗ с оборудования";
                        }
                        if ($reso['status']=='44'){
                            echo"Оплачен в ТС";
                        }
                        if ($reso['status']=='45'){
                            echo"Ждем ККТ";
                        }
                        if ($reso['status']=='47'){
                            echo"Товар получен";
                        }
                        if ($reso['status']=='48'){
                            echo"Товар получен без ФН";
                        }
                        if ($reso['status']=='49'){
                            echo"На продлении";
                        }
                        ?></td>
                    <td style="width: 7%;"><?echo $reso['f_name'].' '.mb_substr($reso['l_name'],0,1,'UTF-8'),'. '.mb_substr($reso['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($reso['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $reso['rand'];?>"id="schetkal<?echo $reso['rand'];?>"><?if($reso['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($reso['datebron']));
                                    $monvis=date('m', strtotime($reso['datebron']));
                                    $yesrvis=date('Y', strtotime($reso['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $reso['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $reso['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $reso['rand'];?>' id="datebr<?echo $reso['rand'];?>" type="date"value="<?if($reso['datebron']!="0000-00-00"){echo $reso['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $reso['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $reso['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $reso['rand'];?>').show();
                                $('.modal-window<?echo $reso['rand'];?>').show();
                                $('#kalbr<?echo $reso['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $reso['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $reso['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $reso['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $reso['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $reso['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $reso['rand'];?>').hide();
                                $('#kalbr<?echo $reso['rand'];?>').hide();
                                $('.modal-window<?echo $reso['rand'];?>').hide();
                                document.getElementById("scales<?echo $reso['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $reso['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $reso['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($reso['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $reso['rand'];?>"><?if($reso['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($reso['datezvon']));
                                    $monvis=date('m', strtotime($reso['datezvon']));
                                    $yesrvis=date('Y', strtotime($reso['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $reso['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $reso['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $reso['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $reso['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $reso['rand'];?>' id="datezs<?echo $reso['rand'];?>" type="date"value="<?if($reso['datezvon']!="0000-00-00"){echo $reso['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $reso['rand'];?>' id="timezs<?echo $reso['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $reso['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $reso['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $reso['rand'];?>').show();
                                $('.modal-windowzs<?echo $reso['rand'];?>').show();
                                $('#kalz<?echo $reso['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $reso['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $reso['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $reso['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $reso['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $reso['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $reso['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $reso['rand'];?>').hide();
                                $('#kalzs<?echo $reso['rand'];?>').hide();
                                $('.modal-windowzs<?echo $reso['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $reso['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $reso['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $reso['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $reso['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $reso['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $reso['ogrn']);?>&kli=<? echo $reso['idkli'];?>&lico=<? echo $reso['lico'];?>&gr=<? echo $reso['gr'];?>&nomerschet=<? echo $reso['ns'];?>&produkt=<? echo $reso['produkt'];?>&inn=<? echo $reso['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $reso['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $reso['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $reso['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="postavka"){
        if ($_GET['tipi']=="1"){?>
            <?php $rpos = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.status = '5'and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($respos = mysql_fetch_assoc($rpos))  : ?>
                <tr value="<?php echo $respos['id'];?> "class="schetpos">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#E9C3FB;"><?php echo $respos['d'].".".$respos['m'].".".$respos['y'];?></td>
                    <td style="background:#E9C3FB;"><?php echo $respos['ns'];?><?if($respos['nomerschetks']!=''){?><p><?php echo $respos['nomerschetks'];?></p><?}?></td>
                    <td><?echo $respos['inn'];?></td>
                    <td><?echo $respos['kpp'];?></td>
                    <td style="width: 30%;"><?echo $respos['ogrn'];?></td>
                    <td><?echo $respos['name'];?></td>
                    <td><?echo schet_tip_text($respos);?></td>
                    <td style="background:#E9C3FB;"><?echo $respos['priceks'];?></td>
                    <td style="background:#E9C3FB;"><?echo $respos['price'];?></td>
                    <td><?echo $respos['dataprod'];?></td>
                    <td style="width: 6%;background:#E9C3FB;">Поставка</td>
                    <td style="width: 7%;"><?echo $respos['f_name'].' '.mb_substr($respos['l_name'],0,1,'UTF-8'),'. '.mb_substr($respos['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($respos['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $respos['rand'];?>"id="schetkal<?echo $respos['rand'];?>"><?if($respos['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($respos['datebron']));
                                    $monvis=date('m', strtotime($respos['datebron']));
                                    $yesrvis=date('Y', strtotime($respos['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $respos['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $respos['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $respos['rand'];?>' id="datebr<?echo $respos['rand'];?>" type="date"value="<?if($respos['datebron']!="0000-00-00"){echo $respos['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $respos['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $respos['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $respos['rand'];?>').show();
                                $('.modal-window<?echo $respos['rand'];?>').show();
                                $('#kalbr<?echo $respos['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $respos['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $respos['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $respos['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $respos['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $respos['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $respos['rand'];?>').hide();
                                $('#kalbr<?echo $respos['rand'];?>').hide();
                                $('.modal-window<?echo $respos['rand'];?>').hide();
                                document.getElementById("scales<?echo $respos['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $respos['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $respos['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($respos['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $respos['rand'];?>"><?if($respos['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($respos['datezvon']));
                                    $monvis=date('m', strtotime($respos['datezvon']));
                                    $yesrvis=date('Y', strtotime($respos['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $respos['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $respos['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $respos['rand'];?>' id="datezs<?echo $respos['rand'];?>" type="date"value="<?if($respos['datezvon']!="0000-00-00"){echo $respos['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $respos['rand'];?>' id="timezs<?echo $respos['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $respos['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $respos['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $respos['rand'];?>').show();
                                $('.modal-windowzs<?echo $respos['rand'];?>').show();
                                $('#kalz<?echo $respos['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $respos['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $respos['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $respos['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $respos['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $respos['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $respos['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $respos['rand'];?>').hide();
                                $('#kalzs<?echo $respos['rand'];?>').hide();
                                $('.modal-windowzs<?echo $respos['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $respos['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $respos['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $respos['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $respos['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $respos['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $respos['ogrn']);?>&kli=<? echo $respos['idkli'];?>&lico=<? echo $respos['lico'];?>&gr=<? echo $respos['gr'];?>&nomerschet=<? echo $respos['ns'];?>&produkt=<? echo $respos['produkt'];?>&inn=<? echo $respos['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $respos['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $respos['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $respos['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2")
        {?>
            <?php $rpos = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.status = '5'and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($respos = mysql_fetch_assoc($rpos))  : ?>
                <tr value="<?php echo $respos['id'];?> "class="schetpos">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#E9C3FB;"><?php echo $respos['d'].".".$respos['m'].".".$respos['y'];?></td>
                    <td style="background:#E9C3FB;"><?php echo $respos['ns'];?><?if($respos['nomerschetks']!=''){?><p><?php echo $respos['nomerschetks'];?></p><?}?></td>
                    <td><?echo $respos['inn'];?></td>
                    <td><?echo $respos['kpp'];?></td>
                    <td style="width: 30%;"><?echo $respos['ogrn'];?></td>
                    <td><?echo $respos['name'];?></td>
                    <td><?echo schet_tip_text($respos);?></td>
                    <td style="background:#E9C3FB;"><?echo $respos['priceks'];?></td>
                    <td style="background:#E9C3FB;"><?echo $respos['price'];?></td>
                    <td><?echo $respos['dataprod'];?></td>
                    <td style="width: 6%;background:#E9C3FB;">Поставка</td>
                    <td style="width: 7%;"><?echo $respos['f_name'].' '.mb_substr($respos['l_name'],0,1,'UTF-8'),'. '.mb_substr($respos['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($respos['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $respos['rand'];?>"id="schetkal<?echo $respos['rand'];?>"><?if($respos['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($respos['datebron']));
                                    $monvis=date('m', strtotime($respos['datebron']));
                                    $yesrvis=date('Y', strtotime($respos['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $respos['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $respos['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $respos['rand'];?>' id="datebr<?echo $respos['rand'];?>" type="date"value="<?if($respos['datebron']!="0000-00-00"){echo $respos['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $respos['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $respos['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $respos['rand'];?>').show();
                                $('.modal-window<?echo $respos['rand'];?>').show();
                                $('#kalbr<?echo $respos['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $respos['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $respos['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $respos['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $respos['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $respos['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $respos['rand'];?>').hide();
                                $('#kalbr<?echo $respos['rand'];?>').hide();
                                $('.modal-window<?echo $respos['rand'];?>').hide();
                                document.getElementById("scales<?echo $respos['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $respos['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $respos['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($respos['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $respos['rand'];?>"><?if($respos['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($respos['datezvon']));
                                    $monvis=date('m', strtotime($respos['datezvon']));
                                    $yesrvis=date('Y', strtotime($respos['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $respos['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $respos['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $respos['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $respos['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $respos['rand'];?>' id="datezs<?echo $respos['rand'];?>" type="date"value="<?if($respos['datezvon']!="0000-00-00"){echo $respos['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $respos['rand'];?>' id="timezs<?echo $respos['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $respos['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $respos['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $respos['rand'];?>').show();
                                $('.modal-windowzs<?echo $respos['rand'];?>').show();
                                $('#kalz<?echo $respos['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $respos['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $respos['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $respos['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $respos['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $respos['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $respos['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $respos['rand'];?>').hide();
                                $('#kalzs<?echo $respos['rand'];?>').hide();
                                $('.modal-windowzs<?echo $respos['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $respos['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $respos['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $respos['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $respos['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $respos['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $respos['ogrn']);?>&kli=<? echo $respos['idkli'];?>&lico=<? echo $respos['lico'];?>&gr=<? echo $respos['gr'];?>&nomerschet=<? echo $respos['ns'];?>&produkt=<? echo $respos['produkt'];?>&inn=<? echo $respos['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $respos['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $respos['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $respos['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
    }?>
    <?if($_GET['tip']=="ystanovka"){
        if ($_GET['tipi']=="1"){
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '17' or schet.status='18' or schet.status='161')";
            }
            if($_GET['ogr']=="22")
            {
                $status="(schet.status = '36')";
            }
            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '50' or schet.status = '51')";
            }?>
            <?php $ryv = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resyv = mysql_fetch_assoc($ryv))  : ?>
                <tr value="<?php echo $resyv['id'];?> "class="schetyv">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#FFB366;"><?php echo $resyv['d'].".".$resyv['m'].".".$resyv['y'];?></td>
                    <td style="background:#FFB366;"><?php echo $resyv['ns'];?><?if($resyv['nomerschetks']!=''){?><p><?php echo $resyv['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resyv['inn'];?></td>
                    <td><?echo $resyv['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resyv['ogrn'];?></td>
                    <td><?echo $resyv['name'];?></td>
                    <td><?echo schet_tip_text($resyv);?></td>
                    <td style="background:#FFB366;"><?echo $resyv['priceks'];?></td>
                    <td style="background:#FFB366;"><?echo $resyv['price'];?></td>
                    <td><?echo $resyv['dataprod'];?></td>
                    <td style="width: 6%;background:#FFB366;"><?if ($resyv['status']=='17'){
                            echo"Установка";
                        }
                        if ($resyv['status']=='18'){
                            echo"Выезд";
                        }
                        if ($resyv['status']=='161'){
                            echo"Произв.устан.";
                        }
                        if ($resyv['status']=='36'){
                            echo"Регистрация + Настройка";
                        }
                        if ($resyv['status']=='50'){
                            echo"Установка в офисе";
                        }
                        if ($resyv['status']=='51'){
                            echo"Выезд";
                        }
                        ?></td>
                    <td style="width: 7%;"><?echo $resyv['f_name'].' '.mb_substr($resyv['l_name'],0,1,'UTF-8'),'. '.mb_substr($resyv['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resyv['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $respos['rand'];?>"id="schetkal<?echo $resyv['rand'];?>"><?if($resyv['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resyv['datebron']));
                                    $monvis=date('m', strtotime($resyv['datebron']));
                                    $yesrvis=date('Y', strtotime($resyv['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resyv['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resyv['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resyv['rand'];?>' id="datebr<?echo $resyv['rand'];?>" type="date"value="<?if($resyv['datebron']!="0000-00-00"){echo $resyv['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resyv['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resyv['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resyv['rand'];?>').show();
                                $('.modal-window<?echo $resyv['rand'];?>').show();
                                $('#kalbr<?echo $resyv['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resyv['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resyv['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resyv['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resyv['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resyv['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resyv['rand'];?>').hide();
                                $('#kalbr<?echo $resyv['rand'];?>').hide();
                                $('.modal-window<?echo $resyv['rand'];?>').hide();
                                document.getElementById("scales<?echo $resyv['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resyv['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resyv['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resyv['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resyv['rand'];?>"><?if($resyv['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resyv['datezvon']));
                                    $monvis=date('m', strtotime($resyv['datezvon']));
                                    $yesrvis=date('Y', strtotime($resyv['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resyv['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resyv['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resyv['rand'];?>' id="datezs<?echo $resyv['rand'];?>" type="date"value="<?if($resyv['datezvon']!="0000-00-00"){echo $resyv['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resyv['rand'];?>' id="timezs<?echo $resyv['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resyv['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resyv['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resyv['rand'];?>').show();
                                $('.modal-windowzs<?echo $resyv['rand'];?>').show();
                                $('#kalz<?echo $resyv['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resyv['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resyv['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resyv['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resyv['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resyv['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resyv['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resyv['rand'];?>').hide();
                                $('#kalzs<?echo $resyv['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resyv['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resyv['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resyv['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resyv['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resyv['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resyv['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resyv['ogrn']);?>&kli=<? echo $resyv['idkli'];?>&lico=<? echo $resyv['lico'];?>&gr=<? echo $resyv['gr'];?>&nomerschet=<? echo $resyv['ns'];?>&produkt=<? echo $resyv['produkt'];?>&inn=<? echo $resyv['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resyv['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resyv['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resyv['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2"){
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '17' or schet.status='18' or schet.status='161')";
            }
            if($_GET['ogr']=="22")
            {
                $status="(schet.status = '36')";
            }
            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '50' or schet.status = '51')";
            }?>
            <?php $ryv = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.oplachen,schet.url,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resyv = mysql_fetch_assoc($ryv))  : ?>
                <tr value="<?php echo $resyv['id'];?> "class="schetyv">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#FFB366;"><?php echo $resyv['d'].".".$resyv['m'].".".$resyv['y'];?></td>
                    <td style="background:#FFB366;"><?php echo $resyv['ns'];?><?if($resyv['nomerschetks']!=''){?><p><?php echo $resyv['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resyv['inn'];?></td>
                    <td><?echo $resyv['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resyv['ogrn'];?></td>
                    <td><?echo $resyv['name'];?></td>
                    <td><?echo schet_tip_text($resyv);?></td>
                    <td style="background:#FFB366;"><?echo $resyv['priceks'];?></td>
                    <td style="background:#FFB366;"><?echo $resyv['price'];?></td>
                    <td><?echo $resyv['dataprod'];?></td>
                    <td style="width: 6%;background:#FFB366;"><?if ($resyv['status']=='17'){
                            echo"Установка";
                        }
                        if ($resyv['status']=='18'){
                            echo"Выезд";
                        }
                        if ($resyv['status']=='161'){
                            echo"Произв.устан.";
                        }
                        if ($resyv['status']=='36'){
                            echo"Регистрация + Настройка";
                        }
                        if ($resyv['status']=='50'){
                            echo"Установка в офисе";
                        }
                        if ($resyv['status']=='51'){
                            echo"Выезд";
                        }
                        ?></td>
                    <td style="width: 7%;"><?echo $resyv['f_name'].' '.mb_substr($resyv['l_name'],0,1,'UTF-8'),'. '.mb_substr($resyv['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resyv['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resyv['rand'];?>"id="schetkal<?echo $resyv['rand'];?>"><?if($resyv['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resyv['datebron']));
                                    $monvis=date('m', strtotime($resyv['datebron']));
                                    $yesrvis=date('Y', strtotime($resyv['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resyv['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resyv['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resyv['rand'];?>' id="datebr<?echo $resyv['rand'];?>" type="date"value="<?if($resyv['datebron']!="0000-00-00"){echo $resyv['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resyv['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resyv['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resyv['rand'];?>').show();
                                $('.modal-window<?echo $resyv['rand'];?>').show();
                                $('#kalbr<?echo $resyv['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resyv['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resyv['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resyv['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resyv['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resyv['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resyv['rand'];?>').hide();
                                $('#kalbr<?echo $resyv['rand'];?>').hide();
                                $('.modal-window<?echo $resyv['rand'];?>').hide();
                                document.getElementById("scales<?echo $resyv['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resyv['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resyv['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resyv['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resyv['rand'];?>"><?if($resyv['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resyv['datezvon']));
                                    $monvis=date('m', strtotime($resyv['datezvon']));
                                    $yesrvis=date('Y', strtotime($resyv['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resyv['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resyv['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resyv['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resyv['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resyv['rand'];?>' id="datezs<?echo $resyv['rand'];?>" type="date"value="<?if($resyv['datezvon']!="0000-00-00"){echo $resyv['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resyv['rand'];?>' id="timezs<?echo $resyv['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resyv['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resyv['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resyv['rand'];?>').show();
                                $('.modal-windowzs<?echo $resyv['rand'];?>').show();
                                $('#kalz<?echo $resyv['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resyv['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resyv['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resyv['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resyv['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resyv['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resyv['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resyv['rand'];?>').hide();
                                $('#kalzs<?echo $resyv['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resyv['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resyv['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resyv['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resyv['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resyv['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resyv['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resyv['ogrn']);?>&kli=<? echo $resyv['idkli'];?>&lico=<? echo $resyv['lico'];?>&gr=<? echo $resyv['gr'];?>&nomerschet=<? echo $resyv['ns'];?>&produkt=<? echo $resyv['produkt'];?>&inn=<? echo $resyv['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resyv['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resyv['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resyv['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="naatk"){
        if ($_GET['tipi']=="1"){
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '21' or schet.status='65')";
            }
            if($_GET['ogr']=="22")
            {
                $status="(schet.status = '77')";
            }
            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '52' or schet.status = '53'or schet.status = '60')";
            }?>
            <?php $rna = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.nomerschetks,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resrna = mysql_fetch_assoc($rna))  : ?>
                <tr value="<?php echo $resrna['id'];?> "class="schetna">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#85D6D1;"><?php echo $resrna['d'].".".$resrna['m'].".".$resrna['y'];?></td>
                    <td style="background:#85D6D1;"><?php echo $resrna['ns'];?><?if($resrna['nomerschetks']!=''){?><p><?php echo $resrna['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resrna['inn'];?></td>
                    <td><?echo $resrna['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resrna['ogrn'];?></td>
                    <td><?echo $resrna['name'];?></td>
                    <td><?echo schet_tip_text($resrna);?></td>
                    <td style="background:#85D6D1;"><?echo $resrna['priceks'];?></td>
                    <td style="background:#85D6D1;"><?echo $resrna['price'];?></td>
                    <td><?echo $resrna['dataprod'];?></td>
                    <td style="width: 6%;background:#85D6D1;"><?
                        if ($resrna['status']=='21'){
                            echo"Частично на отгрузке";
                        }
                        if ($resrna['status']=='65'){
                            echo"На отгрузке.";
                        }
                        if ($resrna['status']=='77'){
                            echo"На отгрузке.";
                        }
                        if ($resrna['status']=='52'){
                            echo"Выдали";
                        }
                        if ($resrna['status']=='53'){
                            echo"Жем закрывающие документы";
                        }
                        if ($resrna['status']=='60'){
                            echo"На отгрузке.";
                        }
                        ?></td>
                    <td style="width: 7%;"><?echo $resrna['f_name'].' '.mb_substr($resrna['l_name'],0,1,'UTF-8'),'. '.mb_substr($resrna['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resrna['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resrna['rand'];?>"id="schetkal<?echo $resrna['rand'];?>"><?if($resrna['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resrna['datebron']));
                                    $monvis=date('m', strtotime($resrna['datebron']));
                                    $yesrvis=date('Y', strtotime($resrna['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resrna['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resrna['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resrna['rand'];?>' id="datebr<?echo $resrna['rand'];?>" type="date"value="<?if($resrna['datebron']!="0000-00-00"){echo $resrna['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resrna['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resrna['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resrna['rand'];?>').show();
                                $('.modal-window<?echo $resrna['rand'];?>').show();
                                $('#kalbr<?echo $resrna['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resrna['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resrna['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resrna['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resrna['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resrna['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resrna['rand'];?>').hide();
                                $('#kalbr<?echo $resrna['rand'];?>').hide();
                                $('.modal-window<?echo $resrna['rand'];?>').hide();
                                document.getElementById("scales<?echo $resrna['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resrna['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resrna['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resrna['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resrna['rand'];?>"><?if($resrna['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resrna['datezvon']));
                                    $monvis=date('m', strtotime($resrna['datezvon']));
                                    $yesrvis=date('Y', strtotime($resrna['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resrna['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resrna['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resrna['rand'];?>' id="datezs<?echo $resrna['rand'];?>" type="date"value="<?if($resrna['datezvon']!="0000-00-00"){echo $resrna['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resrna['rand'];?>' id="timezs<?echo $resrna['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resrna['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resrna['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resrna['rand'];?>').show();
                                $('.modal-windowzs<?echo $resrna['rand'];?>').show();
                                $('#kalz<?echo $resrna['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resrna['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resrna['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resrna['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resrna['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resrna['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resrna['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resrna['rand'];?>').hide();
                                $('#kalzs<?echo $resrna['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resrna['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resrna['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resrna['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resrna['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resrna['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resrna['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resrna['ogrn']);?>&kli=<? echo $resrna['idkli'];?>&lico=<? echo $resrna['lico'];?>&gr=<? echo $resrna['gr'];?>&nomerschet=<? echo $resrna['ns'];?>&produkt=<? echo $resrna['produkt'];?>&inn=<? echo $resrna['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resrna['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resrna['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resrna['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2"){
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '21' or schet.status='65')";
            }
            if($_GET['ogr']=="22")
            {
                $status="(schet.status = '77')";
            }
            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '52' or schet.status = '53'or schet.status = '60')";
            }?>
            <?php $rna = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.oplachenks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.oplachen,schet.nomerschetks,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resrna = mysql_fetch_assoc($rna))  : ?>
                <tr value="<?php echo $resrna['id'];?> "class="schetna">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#85D6D1;"><?php echo $resrna['d'].".".$resrna['m'].".".$resrna['y'];?></td>
                    <td style="background:#85D6D1;"><?php echo $resrna['ns'];?><?if($resrna['nomerschetks']!=''){?><p><?php echo $resrna['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resrna['inn'];?></td>
                    <td><?echo $resrna['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resrna['ogrn'];?></td>
                    <td><?echo $resrna['name'];?></td>
                    <td><?echo schet_tip_text($resrna);?></td>
                    <td style="background:#85D6D1;"><?echo $resrna['priceks'];?></td>
                    <td style="background:#85D6D1;"><?echo $resrna['price'];?></td>
                    <td><?echo $resrna['dataprod'];?></td>
                    <td style="width: 6%;background:#85D6D1;"><?
                        if ($resrna['status']=='21'){
                            echo"Частично на отгрузке";
                        }
                        if ($resrna['status']=='65'){
                            echo"На отгрузке.";
                        }
                        if ($resrna['status']=='77'){
                            echo"На отгрузке.";
                        }
                        if ($resrna['status']=='52'){
                            echo"Выдали";
                        }
                        if ($resrna['status']=='53'){
                            echo"Жем закрывающие документы";
                        }
                        if ($resrna['status']=='60'){
                            echo"На отгрузке.";
                        }
                        ?></td>
                    <td style="width: 7%;"><?echo $resrna['f_name'].' '.mb_substr($resrna['l_name'],0,1,'UTF-8'),'. '.mb_substr($resrna['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resrna['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resrna['rand'];?>"id="schetkal<?echo $resrna['rand'];?>"><?if($resrna['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resrna['datebron']));
                                    $monvis=date('m', strtotime($resrna['datebron']));
                                    $yesrvis=date('Y', strtotime($resrna['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resrna['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resrna['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resrna['rand'];?>' id="datebr<?echo $resrna['rand'];?>" type="date"value="<?if($resrna['datebron']!="0000-00-00"){echo $resrna['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resrna['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resrna['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resrna['rand'];?>').show();
                                $('.modal-window<?echo $resrna['rand'];?>').show();
                                $('#kalbr<?echo $resrna['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resrna['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resrna['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resrna['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resrna['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resrna['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resrna['rand'];?>').hide();
                                $('#kalbr<?echo $resrna['rand'];?>').hide();
                                $('.modal-window<?echo $resrna['rand'];?>').hide();
                                document.getElementById("scales<?echo $resrna['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resrna['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resrna['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resrna['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resrna['rand'];?>"><?if($resrna['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resrna['datezvon']));
                                    $monvis=date('m', strtotime($resrna['datezvon']));
                                    $yesrvis=date('Y', strtotime($resrna['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resrna['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resrna['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resrna['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resrna['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resrna['rand'];?>' id="datezs<?echo $resrna['rand'];?>" type="date"value="<?if($resrna['datezvon']!="0000-00-00"){echo $resrna['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resrna['rand'];?>' id="timezs<?echo $resrna['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resrna['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resrna['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resrna['rand'];?>').show();
                                $('.modal-windowzs<?echo $resrna['rand'];?>').show();
                                $('#kalz<?echo $resrna['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resrna['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resrna['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resrna['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resrna['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resrna['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resrna['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resrna['rand'];?>').hide();
                                $('#kalzs<?echo $resrna['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resrna['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resrna['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resrna['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resrna['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resrna['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resrna['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resrna['ogrn']);?>&kli=<? echo $resrna['idkli'];?>&lico=<? echo $resrna['lico'];?>&gr=<? echo $resrna['gr'];?>&nomerschet=<? echo $resrna['ns'];?>&produkt=<? echo $resrna['produkt'];?>&inn=<? echo $resrna['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resrna['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resrna['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resrna['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="atk"){
        if ($_GET['tipi']=="1"){?>
            <?php $ratk = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.del='0' AND schet.akt = '1' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resatk = mysql_fetch_assoc($ratk))  : ?>
                <tr value="<?php echo $resatk['id'];?> "class="schetatk">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#85D6A7;"><?php echo $resatk['d'].".".$resatk['m'].".".$resatk['y'];?></td>
                    <td style="background:#85D6A7;"><?php echo $resatk['ns'];?><?if($resatk['nomerschetks']!=''){?><p><?php echo $resatk['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resatk['inn'];?></td>
                    <td><?echo $resatk['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resatk['ogrn'];?></td>
                    <td><?echo $resatk['name'];?></td>
                    <td><?echo schet_tip_text($resatk);?></td>
                    <td style="background:#85D6A7;"><?echo $resatk['priceks'];?></td>
                    <td style="background:#85D6A7;"><?echo $resatk['price'];?></td>
                    <td><?echo $resatk['dataprod'];?></td>
                    <td style="width: 6%;background:#85D6A7;">Отгружен</td>
                    <td style="width: 7%;"><?echo $resatk['f_name'].' '.mb_substr($resatk['l_name'],0,1,'UTF-8'),'. '.mb_substr($resatk['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resatk['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resatk['rand'];?>"id="schetkal<?echo $resatk['rand'];?>"><?if($resatk['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resatk['datebron']));
                                    $monvis=date('m', strtotime($resatk['datebron']));
                                    $yesrvis=date('Y', strtotime($resatk['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resatk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resatk['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resatk['rand'];?>' id="datebr<?echo $resatk['rand'];?>" type="date"value="<?if($resatk['datebron']!="0000-00-00"){echo $resatk['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resatk['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resatk['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resatk['rand'];?>').show();
                                $('.modal-window<?echo $resatk['rand'];?>').show();
                                $('#kalbr<?echo $resatk['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resatk['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resatk['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resatk['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resatk['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resatk['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resatk['rand'];?>').hide();
                                $('#kalbr<?echo $resatk['rand'];?>').hide();
                                $('.modal-window<?echo $resatk['rand'];?>').hide();
                                document.getElementById("scales<?echo $resatk['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resatk['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resatk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resatk['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resatk['rand'];?>"><?if($resatk['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resatk['datezvon']));
                                    $monvis=date('m', strtotime($resatk['datezvon']));
                                    $yesrvis=date('Y', strtotime($resatk['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resatk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resatk['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resatk['rand'];?>' id="datezs<?echo $resatk['rand'];?>" type="date"value="<?if($resatk['datezvon']!="0000-00-00"){echo $resatk['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resatk['rand'];?>' id="timezs<?echo $resatk['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resatk['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resatk['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resatk['rand'];?>').show();
                                $('.modal-windowzs<?echo $resatk['rand'];?>').show();
                                $('#kalz<?echo $resatk['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resatk['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resatk['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resatk['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resatk['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resatk['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resatk['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resatk['rand'];?>').hide();
                                $('#kalzs<?echo $resatk['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resatk['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resatk['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resatk['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resatk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resatk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resatk['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resatk['ogrn']);?>&kli=<? echo $resatk['idkli'];?>&lico=<? echo $resatk['lico'];?>&gr=<? echo $resatk['gr'];?>&nomerschet=<? echo $resatk['ns'];?>&produkt=<? echo $resatk['produkt'];?>&inn=<? echo $resatk['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resatk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resatk['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resatk['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2")
        {?>
            <?php $ratk = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.del='0' AND schet.akt = '1'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resatk = mysql_fetch_assoc($ratk))  : ?>
                <tr value="<?php echo $resatk['id'];?> "class="schetatk">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#85D6A7;"><?php echo $resatk['d'].".".$resatk['m'].".".$resatk['y'];?></td>
                    <td style="background:#85D6A7;"><?php echo $resatk['ns'];?><?if($resatk['nomerschetks']!=''){?><p><?php echo $resatk['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resatk['inn'];?></td>
                    <td><?echo $resatk['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resatk['ogrn'];?></td>
                    <td><?echo $resatk['name'];?></td>
                    <td><?echo schet_tip_text($resatk);?></td>
                    <td style="background:#85D6A7;"><?echo $resatk['priceks'];?></td>
                    <td style="background:#85D6A7;"><?echo $resatk['price'];?></td>
                    <td><?echo $resatk['dataprod'];?></td>
                    <td style="width: 6%;background:#85D6A7;">Отгружен</td>
                    <td style="width: 7%;"><?echo $resatk['f_name'].' '.mb_substr($resatk['l_name'],0,1,'UTF-8'),'. '.mb_substr($resatk['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resatk['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resatk['rand'];?>"id="schetkal<?echo $resatk['rand'];?>"><?if($resatk['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resatk['datebron']));
                                    $monvis=date('m', strtotime($resatk['datebron']));
                                    $yesrvis=date('Y', strtotime($resatk['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resatk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resatk['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resatk['rand'];?>' id="datebr<?echo $resatk['rand'];?>" type="date"value="<?if($resatk['datebron']!="0000-00-00"){echo $resatk['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resatk['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resatk['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resatk['rand'];?>').show();
                                $('.modal-window<?echo $resatk['rand'];?>').show();
                                $('#kalbr<?echo $resatk['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resatk['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resatk['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resatk['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resatk['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resatk['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resatk['rand'];?>').hide();
                                $('#kalbr<?echo $resatk['rand'];?>').hide();
                                $('.modal-window<?echo $resatk['rand'];?>').hide();
                                document.getElementById("scales<?echo $resatk['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resatk['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resatk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resatk['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resatk['rand'];?>"><?if($resatk['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resatk['datezvon']));
                                    $monvis=date('m', strtotime($resatk['datezvon']));
                                    $yesrvis=date('Y', strtotime($resatk['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resatk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resatk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resatk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resatk['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resatk['rand'];?>' id="datezs<?echo $resatk['rand'];?>" type="date"value="<?if($resatk['datezvon']!="0000-00-00"){echo $resatk['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resatk['rand'];?>' id="timezs<?echo $resatk['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resatk['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resatk['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resatk['rand'];?>').show();
                                $('.modal-windowzs<?echo $resatk['rand'];?>').show();
                                $('#kalz<?echo $resatk['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resatk['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resatk['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resatk['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resatk['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resatk['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resatk['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resatk['rand'];?>').hide();
                                $('#kalzs<?echo $resatk['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resatk['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resatk['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resatk['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resatk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resatk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resatk['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resatk['ogrn']);?>&kli=<? echo $resatk['idkli'];?>&lico=<? echo $resatk['lico'];?>&gr=<? echo $resatk['gr'];?>&nomerschet=<? echo $resatk['ns'];?>&produkt=<? echo $resatk['produkt'];?>&inn=<? echo $resatk['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resatk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resatk['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resatk['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="otk"){
        if ($_GET['tipi']=="1"){?>
            <?php $rotk = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.del='0' AND schet.otk = '0' AND schet.cher = '1' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resotk = mysql_fetch_assoc($rotk))  : ?>
                <tr value="<?php echo $resotk['id'];?> "class="schetotk">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#FB9C9C;"><?php echo $resotk['d'].".".$resotk['m'].".".$resotk['y'];?></td>
                    <td style="background:#FB9C9C;"><?php echo $resotk['ns'];?><?if($resotk['nomerschetks']!=''){?><p><?php echo $resotk['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resotk['inn'];?></td>
                    <td><?echo $resotk['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resotk['ogrn'];?></td>
                    <td><?echo $resotk['name'];?></td>
                    <td><?echo schet_tip_text($resotk);?></td>
                    <td style="background:#FB9C9C;"><?echo $resotk['priceks'];?></td>
                    <td style="background:#FB9C9C;"><?echo $resotk['price'];?></td>
                    <td><?echo $resotk['dataprod'];?></td>
                    <td style="width: 6%;background:#FB9C9C;">Отказались</td>
                    <td style="width: 7%;"><?echo $resotk['f_name'].' '.mb_substr($resotk['l_name'],0,1,'UTF-8'),'. '.mb_substr($resotk['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resotk['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resotk['rand'];?>"id="schetkal<?echo $resotk['rand'];?>"><?if($resotk['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resotk['datebron']));
                                    $monvis=date('m', strtotime($resotk['datebron']));
                                    $yesrvis=date('Y', strtotime($resotk['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resotk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resotk['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resotk['rand'];?>' id="datebr<?echo $resotk['rand'];?>" type="date"value="<?if($resotk['datebron']!="0000-00-00"){echo $resotk['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resotk['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resotk['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resotk['rand'];?>').show();
                                $('.modal-window<?echo $resotk['rand'];?>').show();
                                $('#kalbr<?echo $resotk['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resotk['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resotk['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resotk['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resotk['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resotk['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resotk['rand'];?>').hide();
                                $('#kalbr<?echo $resotk['rand'];?>').hide();
                                $('.modal-window<?echo $resotk['rand'];?>').hide();
                                document.getElementById("scales<?echo $resotk['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resotk['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resotk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resotk['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resotk['rand'];?>"><?if($resotk['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resotk['datezvon']));
                                    $monvis=date('m', strtotime($resotk['datezvon']));
                                    $yesrvis=date('Y', strtotime($resotk['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resotk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resotk['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resotk['rand'];?>' id="datezs<?echo $resotk['rand'];?>" type="date"value="<?if($resotk['datezvon']!="0000-00-00"){echo $resotk['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resotk['rand'];?>' id="timezs<?echo $resotk['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resotk['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resotk['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resotk['rand'];?>').show();
                                $('.modal-windowzs<?echo $resotk['rand'];?>').show();
                                $('#kalz<?echo $resotk['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resotk['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resotk['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resotk['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resotk['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resotk['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resotk['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resotk['rand'];?>').hide();
                                $('#kalzs<?echo $resotk['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resotk['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resotk['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resotk['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resotk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resotk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resotk['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resotk['ogrn']);?>&kli=<? echo $resotk['idkli'];?>&lico=<? echo $resotk['lico'];?>&gr=<? echo $resotk['gr'];?>&nomerschet=<? echo $resotk['ns'];?>&produkt=<? echo $resotk['produkt'];?>&inn=<? echo $resotk['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resotk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resotk['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resotk['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2")
        {?>
            <?php $rotk = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.del='0'  AND schet.otk = '0' AND schet.cher = '1' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resotk = mysql_fetch_assoc($rotk))  : ?>
                <tr value="<?php echo $resotk['id'];?> "class="schetotk">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#FB9C9C;"><?php echo $resotk['d'].".".$resotk['m'].".".$resotk['y'];?></td>
                    <td style="background:#FB9C9C;"><?php echo $resotk['ns'];?><?if($resotk['nomerschetks']!=''){?><p><?php echo $resotk['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resotk['inn'];?></td>
                    <td><?echo $resotk['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resotk['ogrn'];?></td>
                    <td><?echo $resotk['name'];?></td>
                    <td><?echo schet_tip_text($resotk);?></td>
                    <td style="background:#FB9C9C;"><?echo $resotk['priceks'];?></td>
                    <td style="background:#FB9C9C;"><?echo $resotk['price'];?></td>
                    <td><?echo $resotk['dataprod'];?></td>
                    <td style="width: 6%;background:#FB9C9C;">Отказались</td>
                    <td style="width: 7%;"><?echo $resotk['f_name'].' '.mb_substr($resotk['l_name'],0,1,'UTF-8'),'. '.mb_substr($resotk['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resotk['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resotk['rand'];?>"id="schetkal<?echo $resotk['rand'];?>"><?if($resotk['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resotk['datebron']));
                                    $monvis=date('m', strtotime($resotk['datebron']));
                                    $yesrvis=date('Y', strtotime($resotk['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resotk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resotk['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resotk['rand'];?>' id="datebr<?echo $resotk['rand'];?>" type="date"value="<?if($resotk['datebron']!="0000-00-00"){echo $resotk['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resotk['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resotk['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resotk['rand'];?>').show();
                                $('.modal-window<?echo $resotk['rand'];?>').show();
                                $('#kalbr<?echo $resotk['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resotk['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resotk['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resotk['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resotk['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resotk['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resotk['rand'];?>').hide();
                                $('#kalbr<?echo $resotk['rand'];?>').hide();
                                $('.modal-window<?echo $resotk['rand'];?>').hide();
                                document.getElementById("scales<?echo $resotk['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resotk['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resotk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resotk['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resotk['rand'];?>"><?if($resotk['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resotk['datezvon']));
                                    $monvis=date('m', strtotime($resotk['datezvon']));
                                    $yesrvis=date('Y', strtotime($resotk['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resotk['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resotk['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resotk['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resotk['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resotk['rand'];?>' id="datezs<?echo $resotk['rand'];?>" type="date"value="<?if($resotk['datezvon']!="0000-00-00"){echo $resotk['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resotk['rand'];?>' id="timezs<?echo $resotk['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resotk['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resotk['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resotk['rand'];?>').show();
                                $('.modal-windowzs<?echo $resotk['rand'];?>').show();
                                $('#kalz<?echo $resotk['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resotk['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resotk['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resotk['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resotk['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resotk['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resotk['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resotk['rand'];?>').hide();
                                $('#kalzs<?echo $resotk['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resotk['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resotk['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resotk['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resotk['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resotk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resotk['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resotk['ogrn']);?>&kli=<? echo $resotk['idkli'];?>&lico=<? echo $resotk['lico'];?>&gr=<? echo $resotk['gr'];?>&nomerschet=<? echo $resotk['ns'];?>&produkt=<? echo $resotk['produkt'];?>&inn=<? echo $resotk['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resotk['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resotk['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resotk['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="cher"){
        if ($_GET['tipi']=="1"){?>
            <?php $rcher = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn schet.del='0' AND schet.otk = '1' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($rescher = mysql_fetch_assoc($rcher))  : ?>
                <tr value="<?php echo $rescher['id'];?> "class="schetcher">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#BC9B79;"><?php echo $rescher['d'].".".$rescher['m'].".".$rescher['y'];?></td>
                    <td style="background:#BC9B79;"><?php echo $rescher['ns'];?><?if($rescher['nomerschetks']!=''){?><p><?php echo $rescher['nomerschetks'];?></p><?}?></td>
                    <td><?echo $rescher['inn'];?></td>
                    <td><?echo $rescher['kpp'];?></td>
                    <td style="width: 30%;"><?echo $rescher['ogrn'];?></td>
                    <td><?echo $rescher['name'];?></td>
                    <td><?echo schet_tip_text($rescher);?></td>
                    <td style="background:#BC9B79;"><?echo $rescher['priceks'];?></td>
                    <td style="background:#BC9B79;"><?echo $rescher['price'];?></td>
                    <td><?echo $rescher['dataprod'];?></td>
                    <td style="width: 6%;background:#BC9B79;">Черновик</td>
                    <td style="width: 7%;"><?echo $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($rescher['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $rescher['rand'];?>"id="schetkal<?echo $rescher['rand'];?>"><?if($rescher['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($rescher['datebron']));
                                    $monvis=date('m', strtotime($rescher['datebron']));
                                    $yesrvis=date('Y', strtotime($rescher['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $rescher['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $rescher['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $rescher['rand'];?>' id="datebr<?echo $rescher['rand'];?>" type="date"value="<?if($rescher['datebron']!="0000-00-00"){echo $rescher['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $rescher['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $rescher['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $rescher['rand'];?>').show();
                                $('.modal-window<?echo $rescher['rand'];?>').show();
                                $('#kalbr<?echo $rescher['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $rescher['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $rescher['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $rescher['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $rescher['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $rescher['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $rescher['rand'];?>').hide();
                                $('#kalbr<?echo $rescher['rand'];?>').hide();
                                $('.modal-window<?echo $rescher['rand'];?>').hide();
                                document.getElementById("scales<?echo $rescher['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $rescher['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $rescher['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($rescher['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $rescher['rand'];?>"><?if($rescher['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($rescher['datezvon']));
                                    $monvis=date('m', strtotime($rescher['datezvon']));
                                    $yesrvis=date('Y', strtotime($rescher['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $rescher['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $rescher['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $rescher['rand'];?>' id="datezs<?echo $rescher['rand'];?>" type="date"value="<?if($rescher['datezvon']!="0000-00-00"){echo $rescher['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $rescher['rand'];?>' id="timezs<?echo $rescher['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $rescher['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $rescher['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $rescher['rand'];?>').show();
                                $('.modal-windowzs<?echo $rescher['rand'];?>').show();
                                $('#kalz<?echo $rescher['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $rescher['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $rescher['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $rescher['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $rescher['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $rescher['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $rescher['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $rescher['rand'];?>').hide();
                                $('#kalzs<?echo $rescher['rand'];?>').hide();
                                $('.modal-windowzs<?echo $rescher['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $rescher['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $rescher['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $rescher['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $rescher['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $rescher['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $rescher['ogrn']);?>&kli=<? echo $rescher['idkli'];?>&lico=<? echo $rescher['lico'];?>&gr=<? echo $rescher['gr'];?>&nomerschet=<? echo $rescher['ns'];?>&produkt=<? echo $rescher['produkt'];?>&inn=<? echo $rescher['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $rescher['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $rescher['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $rescher['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
                </tbody>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2")
        {?>
            <?php $rcher = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn schet.del='0'   AND schet.otk = '1' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($rescher = mysql_fetch_assoc($rcher))  : ?>
                <tr value="<?php echo $rescher['id'];?> "class="schetcher">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#BC9B79;"><?php echo $rescher['d'].".".$rescher['m'].".".$rescher['y'];?></td>
                    <td style="background:#BC9B79;"><?php echo $rescher['ns'];?><?if($rescher['nomerschetks']!=''){?><p><?php echo $rescher['nomerschetks'];?></p><?}?></td>
                    <td><?echo $rescher['inn'];?></td>
                    <td><?echo $rescher['kpp'];?></td>
                    <td style="width: 30%;"><?echo $rescher['ogrn'];?></td>
                    <td><?echo $rescher['name'];?></td>
                    <td><?echo schet_tip_text($rescher);?></td>
                    <td style="background:#BC9B79;"><?echo $rescher['priceks'];?></td>
                    <td style="background:#BC9B79;"><?echo $rescher['price'];?></td>
                    <td><?echo $rescher['dataprod'];?></td>
                    <td style="width: 6%;background:#BC9B79;">Черновик</td>
                    <td style="width: 7%;"><?echo $rescher['f_name'].' '.mb_substr($rescher['l_name'],0,1,'UTF-8'),'. '.mb_substr($rescher['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($rescher['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $rescher['rand'];?>"id="schetkal<?echo $rescher['rand'];?>"><?if($rescher['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($rescher['datebron']));
                                    $monvis=date('m', strtotime($rescher['datebron']));
                                    $yesrvis=date('Y', strtotime($rescher['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $rescher['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $rescher['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $rescher['rand'];?>' id="datebr<?echo $rescher['rand'];?>" type="date"value="<?if($rescher['datebron']!="0000-00-00"){echo $rescher['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $rescher['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $rescher['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $rescher['rand'];?>').show();
                                $('.modal-window<?echo $rescher['rand'];?>').show();
                                $('#kalbr<?echo $rescher['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $rescher['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $rescher['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $rescher['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $rescher['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $rescher['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $rescher['rand'];?>').hide();
                                $('#kalbr<?echo $rescher['rand'];?>').hide();
                                $('.modal-window<?echo $rescher['rand'];?>').hide();
                                document.getElementById("scales<?echo $rescher['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $rescher['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $rescher['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($rescher['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $rescher['rand'];?>"><?if($rescher['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($rescher['datezvon']));
                                    $monvis=date('m', strtotime($rescher['datezvon']));
                                    $yesrvis=date('Y', strtotime($rescher['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $rescher['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $rescher['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $rescher['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $rescher['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $rescher['rand'];?>' id="datezs<?echo $rescher['rand'];?>" type="date"value="<?if($rescher['datezvon']!="0000-00-00"){echo $rescher['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $rescher['rand'];?>' id="timezs<?echo $rescher['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $rescher['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $rescher['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $rescher['rand'];?>').show();
                                $('.modal-windowzs<?echo $rescher['rand'];?>').show();
                                $('#kalz<?echo $rescher['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $rescher['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $rescher['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $rescher['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $rescher['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $rescher['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $rescher['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $rescher['rand'];?>').hide();
                                $('#kalzs<?echo $rescher['rand'];?>').hide();
                                $('.modal-windowzs<?echo $rescher['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $rescher['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $rescher['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $rescher['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $rescher['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $rescher['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $rescher['ogrn']);?>&kli=<? echo $rescher['idkli'];?>&lico=<? echo $rescher['lico'];?>&gr=<? echo $rescher['gr'];?>&nomerschet=<? echo $rescher['ns'];?>&produkt=<? echo $rescher['produkt'];?>&inn=<? echo $rescher['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $rescher['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $rescher['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $rescher['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
                </tr>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="vozvrat"){
        if ($_GET['tipi']=="1"){
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '23')";
            }
            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '12356')";
            }?>
            <?php $rvoz = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resvoz = mysql_fetch_assoc($rvoz))  : ?>
                <tr value="<?php echo $resvoz['id'];?> "class="schetvoz">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#E45A51;"><?php echo $resvoz['d'].".".$resvoz['m'].".".$resvoz['y'];?></td>
                    <td style="background:#E45A51;"><?php echo $resvoz['ns'];?><?if($resvoz['nomerschetks']!=''){?><p><?php echo $resvoz['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resvoz['inn'];?></td>
                    <td><?echo $resvoz['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resvoz['ogrn'];?></td>
                    <td><?echo $resvoz['name'];?></td>
                    <td><?echo schet_tip_text($resvoz);?></td>
                    <td style="background:#E45A51;"><?echo $resvoz['priceks'];?></td>
                    <td style="background:#E45A51;"><?echo $resvoz['price'];?></td>
                    <td><?echo $resvoz['dataprod'];?></td>
                    <td style="width: 6%;background:#E45A51;">Возврат</td>
                    <td style="width: 7%;"><?echo $resvoz['f_name'].' '.mb_substr($resvoz['l_name'],0,1,'UTF-8'),'. '.mb_substr($resvoz['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resvoz['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resvoz['rand'];?>"id="schetkal<?echo $resvoz['rand'];?>"><?if($resvoz['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resvoz['datebron']));
                                    $monvis=date('m', strtotime($resvoz['datebron']));
                                    $yesrvis=date('Y', strtotime($resvoz['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resvoz['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resvoz['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resvoz['rand'];?>' id="datebr<?echo $resvoz['rand'];?>" type="date"value="<?if($resvoz['datebron']!="0000-00-00"){echo $resvoz['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resvoz['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resvoz['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resvoz['rand'];?>').show();
                                $('.modal-window<?echo $resvoz['rand'];?>').show();
                                $('#kalbr<?echo $resvoz['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resvoz['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resvoz['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resvoz['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resvoz['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resvoz['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resvoz['rand'];?>').hide();
                                $('#kalbr<?echo $resvoz['rand'];?>').hide();
                                $('.modal-window<?echo $resvoz['rand'];?>').hide();
                                document.getElementById("scales<?echo $resvoz['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resvoz['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resvoz['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resvoz['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resvoz['rand'];?>"><?if($resvoz['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resvoz['datezvon']));
                                    $monvis=date('m', strtotime($resvoz['datezvon']));
                                    $yesrvis=date('Y', strtotime($resvoz['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resvoz['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resvoz['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resvoz['rand'];?>' id="datezs<?echo $resvoz['rand'];?>" type="date"value="<?if($resvoz['datezvon']!="0000-00-00"){echo $resvoz['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resvoz['rand'];?>' id="timezs<?echo $resvoz['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resvoz['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resvoz['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resvoz['rand'];?>').show();
                                $('.modal-windowzs<?echo $resvoz['rand'];?>').show();
                                $('#kalz<?echo $resvoz['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resvoz['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resvoz['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resvoz['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resvoz['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resvoz['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resvoz['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resvoz['rand'];?>').hide();
                                $('#kalzs<?echo $resvoz['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resvoz['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resvoz['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resvoz['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resvoz['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resvoz['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resvoz['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resvoz['ogrn']);?>&kli=<? echo $resvoz['idkli'];?>&lico=<? echo $resvoz['lico'];?>&gr=<? echo $resvoz['gr'];?>&nomerschet=<? echo $resvoz['ns'];?>&produkt=<? echo $resvoz['produkt'];?>&inn=<? echo $resvoz['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resvoz['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resvoz['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resvoz['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2")
        {
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '23')";
            }
            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '12356')";
            }?>
            <?php $rvoz = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($resvoz = mysql_fetch_assoc($rvoz))  : ?>
                <tr value="<?php echo $resvoz['id'];?> "class="schetvoz">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#E45A51;"><?php echo $resvoz['d'].".".$resvoz['m'].".".$resvoz['y'];?></td>
                    <td style="background:#E45A51;"><?php echo $resvoz['ns'];?><?if($resvoz['nomerschetks']!=''){?><p><?php echo $resvoz['nomerschetks'];?></p><?}?></td>
                    <td><?echo $resvoz['inn'];?></td>
                    <td><?echo $resvoz['kpp'];?></td>
                    <td style="width: 30%;"><?echo $resvoz['ogrn'];?></td>
                    <td><?echo $resvoz['name'];?></td>
                    <td><?echo schet_tip_text($resvoz);?></td>
                    <td style="background:#E45A51;"><?echo $resvoz['priceks'];?></td>
                    <td style="background:#E45A51;"><?echo $resvoz['price'];?></td>
                    <td><?echo $resvoz['dataprod'];?></td>
                    <td style="width: 6%;background:#E45A51;">Возврат</td>
                    <td style="width: 7%;"><?echo $resvoz['f_name'].' '.mb_substr($resvoz['l_name'],0,1,'UTF-8'),'. '.mb_substr($resvoz['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($resvoz['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $resvoz['rand'];?>"id="schetkal<?echo $resvoz['rand'];?>"><?if($resvoz['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($resvoz['datebron']));
                                    $monvis=date('m', strtotime($resvoz['datebron']));
                                    $yesrvis=date('Y', strtotime($resvoz['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $resvoz['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $resvoz['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $resvoz['rand'];?>' id="datebr<?echo $resvoz['rand'];?>" type="date"value="<?if($resvoz['datebron']!="0000-00-00"){echo $resvoz['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $resvoz['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $resvoz['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $resvoz['rand'];?>').show();
                                $('.modal-window<?echo $resvoz['rand'];?>').show();
                                $('#kalbr<?echo $resvoz['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $resvoz['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $resvoz['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $resvoz['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resvoz['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $resvoz['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $resvoz['rand'];?>').hide();
                                $('#kalbr<?echo $resvoz['rand'];?>').hide();
                                $('.modal-window<?echo $resvoz['rand'];?>').hide();
                                document.getElementById("scales<?echo $resvoz['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $resvoz['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $resvoz['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($resvoz['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $resvoz['rand'];?>"><?if($resvoz['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resvoz['datezvon']));
                                    $monvis=date('m', strtotime($resvoz['datezvon']));
                                    $yesrvis=date('Y', strtotime($resvoz['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $resvoz['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $resvoz['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $resvoz['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $resvoz['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $resvoz['rand'];?>' id="datezs<?echo $resvoz['rand'];?>" type="date"value="<?if($resvoz['datezvon']!="0000-00-00"){echo $resvoz['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $resvoz['rand'];?>' id="timezs<?echo $resvoz['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $resvoz['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $resvoz['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $resvoz['rand'];?>').show();
                                $('.modal-windowzs<?echo $resvoz['rand'];?>').show();
                                $('#kalz<?echo $resvoz['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $resvoz['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $resvoz['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $resvoz['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $resvoz['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resvoz['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $resvoz['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $resvoz['rand'];?>').hide();
                                $('#kalzs<?echo $resvoz['rand'];?>').hide();
                                $('.modal-windowzs<?echo $resvoz['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $resvoz['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $resvoz['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $resvoz['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $resvoz['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resvoz['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resvoz['ogrn']);?>&kli=<? echo $resvoz['idkli'];?>&lico=<? echo $resvoz['lico'];?>&gr=<? echo $resvoz['gr'];?>&nomerschet=<? echo $resvoz['ns'];?>&produkt=<? echo $resvoz['produkt'];?>&inn=<? echo $resvoz['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $resvoz['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resvoz['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $resvoz['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="pereplata"){
        if ($_GET['tipi']=="1"){
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '12354')";
            }

            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '12355')";
            }?>
            <?php $rpere = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($respere = mysql_fetch_assoc($rpere))  : ?>
                <tr value="<?php echo $respere['id'];?> "class="schetpere">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#DCF541;"><?php echo $respere['d'].".".$respere['m'].".".$respere['y'];?></td>
                    <td style="background:#DCF541;"><?php echo $respere['ns'];?><?if($respere['nomerschetks']!=''){?><p><?php echo $respere['nomerschetks'];?></p><?}?></td>
                    <td><?echo $respere['inn'];?></td>
                    <td><?echo $respere['kpp'];?></td>
                    <td style="width: 30%;"><?echo $respere['ogrn'];?></td>
                    <td><?echo $respere['name'];?></td>
                    <td><?echo schet_tip_text($respere);?></td>
                    <td style="background:#DCF541;"><?echo $respere['priceks'];?></td>
                    <td style="background:#DCF541;"><?echo $respere['price'];?></td>
                    <td><?echo $respere['dataprod'];?></td>
                    <td style="width: 6%;background:#DCF541;">Переплата</td>
                    <td style="width: 7%;"><?echo $respere['f_name'].' '.mb_substr($respere['l_name'],0,1,'UTF-8'),'. '.mb_substr($respere['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($respere['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $respere['rand'];?>"id="schetkal<?echo $respere['rand'];?>"><?if($respere['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($respere['datebron']));
                                    $monvis=date('m', strtotime($respere['datebron']));
                                    $yesrvis=date('Y', strtotime($respere['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $respere['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $respere['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $respere['rand'];?>' id="datebr<?echo $respere['rand'];?>" type="date"value="<?if($respere['datebron']!="0000-00-00"){echo $respere['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $respere['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $respere['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $respere['rand'];?>').show();
                                $('.modal-window<?echo $respere['rand'];?>').show();
                                $('#kalbr<?echo $respere['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $respere['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $respere['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $respere['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $respere['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $respere['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $respere['rand'];?>').hide();
                                $('#kalbr<?echo $respere['rand'];?>').hide();
                                $('.modal-window<?echo $respere['rand'];?>').hide();
                                document.getElementById("scales<?echo $respere['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $respere['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $respere['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($respere['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $respere['rand'];?>"><?if($respere['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($respere['datezvon']));
                                    $monvis=date('m', strtotime($respere['datezvon']));
                                    $yesrvis=date('Y', strtotime($respere['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $respere['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $respere['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $respere['rand'];?>' id="datezs<?echo $respere['rand'];?>" type="date"value="<?if($respere['datezvon']!="0000-00-00"){echo $respere['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $respere['rand'];?>' id="timezs<?echo $respere['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $respere['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $respere['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $respere['rand'];?>').show();
                                $('.modal-windowzs<?echo $respere['rand'];?>').show();
                                $('#kalz<?echo $respere['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $respere['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $respere['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $respere['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $respere['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $respere['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $respere['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $respere['rand'];?>').hide();
                                $('#kalzs<?echo $respere['rand'];?>').hide();
                                $('.modal-windowzs<?echo $respere['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $respere['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $respere['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $respere['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $respere['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $respere['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $respere['ogrn']);?>&kli=<? echo $respere['idkli'];?>&lico=<? echo $respere['lico'];?>&gr=<? echo $respere['gr'];?>&nomerschet=<? echo $respere['ns'];?>&produkt=<? echo $respere['produkt'];?>&inn=<? echo $respere['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $respere['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $respere['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $respere['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}
        if ($_GET['tipi']=="2")
        {
            if($_GET['ogr']!="22"&&$_GET['ogr']!="24")
            {
                $status="(schet.status = '12354')";
            }

            if($_GET['ogr']=="24")
            {
                $status="(schet.status = '12355')";
            }?>
            <?php $rpere = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold!='' and $uslugiogrn $status and schet.del='0'AND schet.oplachenks= '1'  AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' and (schet.m BETWEEN'".$monvis."' and '".$monvis."' and schet.y BETWEEN'".$yesrvis."' and '".$yesrvis."' ) group by schet.ns");
            while($respere = mysql_fetch_assoc($rpere))  : ?>
                <tr value="<?php echo $respere['id'];?> "class="schetpere">
                    <?$h++;
                    ?>
                    <td><?echo $h;?></td>
                    <td style="background:#DCF541;"><?php echo $respere['d'].".".$respere['m'].".".$respere['y'];?></td>
                    <td style="background:#DCF541;"><?php echo $respere['ns'];?><?if($respere['nomerschetks']!=''){?><p><?php echo $respere['nomerschetks'];?></p><?}?></td>
                    <td><?echo $respere['inn'];?></td>
                    <td><?echo $respere['kpp'];?></td>
                    <td style="width: 30%;"><?echo $respere['ogrn'];?></td>
                    <td><?echo $respere['name'];?></td>
                    <td><?echo schet_tip_text($respere);?></td>
                    <td style="background:#DCF541;"><?echo $respere['priceks'];?></td>
                    <td style="background:#DCF541;"><?echo $respere['price'];?></td>
                    <td><?echo $respere['dataprod'];?></td>
                    <td style="width: 6%;background:#DCF541;">Переплата</td>
                    <td style="width: 7%;"><?echo $respere['f_name'].' '.mb_substr($respere['l_name'],0,1,'UTF-8'),'. '.mb_substr($respere['o_name'],0,1,'UTF-8').'.';?></td>
                    <td><ul class="schetkal"><?if($respere['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                            <li name="schetkal<?echo $respere['rand'];?>"id="schetkal<?echo $respere['rand'];?>"><?if($respere['datebron']!="0000-00-00"){?><?
                                    $dayvis=date('d', strtotime($respere['datebron']));
                                    $monvis=date('m', strtotime($respere['datebron']));
                                    $yesrvis=date('Y', strtotime($respere['datebron']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadow<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-window<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalbr<?echo $respere['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="bron" id="vidbr<?echo $respere['rand'];?>">Добавить бронь</label>
                                <input class='form-control datebr<?echo $respere['rand'];?>' id="datebr<?echo $respere['rand'];?>" type="date"value="<?if($respere['datebron']!="0000-00-00"){echo $respere['datebron'];}?>">
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scales<?echo $respere['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        var sost="";
                        $(function(){
                            $('#schetkal<?echo $respere['rand'];?>').click(function () {
                                sost="bron";
                                $('.modal-shadow<?echo $respere['rand'];?>').show();
                                $('.modal-window<?echo $respere['rand'];?>').show();
                                $('#kalbr<?echo $respere['rand'];?>').show();
                            });

                            $('.modal-shadow<?echo $respere['rand'];?>').click(function () {
                                if(sost=="bron")
                                {
                                    if(document.getElementById("scales<?echo $respere['rand'];?>").checked==true)
                                    {
                                        var message="bronyes";
                                    }
                                    datebroni=document.getElementsByClassName("datebr<?echo $respere['rand'];?>")[0].value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $respere['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetkal<?echo $respere['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadow<?echo $respere['rand'];?>').hide();
                                $('#kalbr<?echo $respere['rand'];?>').hide();
                                $('.modal-window<?echo $respere['rand'];?>').hide();
                                document.getElementById("scales<?echo $respere['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetkal<?echo $respere['rand'];?>').innerHTML;
                        document.getElementById('schetkal<?echo $respere['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td>
                        <ul class="schetzvons"><?if($respere['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                            <li id="schetzvons<?echo $respere['rand'];?>"><?if($respere['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($respere['datezvon']));
                                    $monvis=date('m', strtotime($respere['datezvon']));
                                    $yesrvis=date('Y', strtotime($respere['datezvon']));
                                    $dss= $yesrvis.".".$monvis.".".$dayvis;
                                    echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                        </ul>
                    </td>
                    <div class="modal-shadowzs<?echo $respere['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
                    <div class="modal-windowzs<?echo $respere['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                        <form class="contact_form" method="POST">
                            <div id="kalzs<?echo $respere['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                                <label class="zvon" id="vidzs<?echo $respere['rand'];?>">Перезвонить</label>
                                <input class='form-control datezs<?echo $respere['rand'];?>' id="datezs<?echo $respere['rand'];?>" type="date"value="<?if($respere['datezvon']!="0000-00-00"){echo $respere['datezvon'];}?>">
                                <textarea class='form-control timezs<?echo $respere['rand'];?>' id="timezs<?echo $respere['rand'];?>" type="time"></textarea>
                                <div style="padding-bottom: 10px;padding-top: 10px;">
                                    <input class="check" id="scalezs<?echo $respere['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                                    <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            $('#schetzvons<?echo $respere['rand'];?>').click(function () {
                                sost="zvon";
                                $('.modal-shadowzs<?echo $respere['rand'];?>').show();
                                $('.modal-windowzs<?echo $respere['rand'];?>').show();
                                $('#kalz<?echo $respere['rand'];?>').show();
                            });

                            $('.modal-shadowzs<?echo $respere['rand'];?>').click(function () {
                                if(sost=="zvon")
                                {
                                    if(document.getElementById("scalezs<?echo $respere['rand'];?>").checked==true)
                                    {
                                        var message="zvonyes";
                                    }
                                    datezvon=document.getElementsByClassName("datezs<?echo $respere['rand'];?>")[0].value;
                                    timezvon=document.getElementById("timezs<?echo $respere['rand'];?>").value;
                                    $.ajax({
                                        url: "bron.php",
                                        cache: false,
                                        data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $respere['rand'];?>",
                                        success: function(msg){
                                            var s = document.getElementById("schetzvons<?echo $respere['rand'];?>");
                                            s.innerHTML = msg;
                                        }
                                    });
                                }
                                $('.modal-shadowzs<?echo $respere['rand'];?>').hide();
                                $('#kalzs<?echo $respere['rand'];?>').hide();
                                $('.modal-windowzs<?echo $respere['rand'];?>').hide();
                                document.getElementById("scalezs<?echo $respere['rand'];?>").checked=false;
                            });
                        });
                    </script>
                    <script>
                        $(function() {
                            $("#myTable1").tablesorter();
                        });
                        var dat=document.getElementById('schetzvons<?echo $respere['rand'];?>').innerHTML;
                        document.getElementById('schetzvons<?echo $respere['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
                    </script>
                    <td value="<?echo $respere['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $respere['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $respere['ogrn']);?>&kli=<? echo $respere['idkli'];?>&lico=<? echo $respere['lico'];?>&gr=<? echo $respere['gr'];?>&nomerschet=<? echo $respere['ns'];?>&produkt=<? echo $respere['produkt'];?>&inn=<? echo $respere['inn'];?>"><img src="/img/qwerty.png"></a></td>
                    <td value="<?echo $respere['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $respere['idkli'];?>"><img src="/img/tablsc.png"></a></td>
                    <td><a target="_blank" href=<?echo $respere['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
                </tr>
            <?php endwhile; ?>
        <?}}?>
    <?if($_GET['tip']=="schetall"){
        if ($_GET['tipi']=="1") {
?>
    <?php $rschetall = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.kto,schet.shetold,schet.url,schet.ogrn as orgh,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE  schet.shetold='' and $uslugiogrn  schet.del='0' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$ds."' and '".$df."'  group by schet.ns");
    while($resschetall = mysql_fetch_assoc($rschetall))  :
        if( $resschetall['oplachenks']!="1")
        {
            $color="background:#C1E5FB;";
            $statussi="Счет выставлен";
        }
        if($resschetall['oplachenks'] =="1")
        {

            if ($resschetall['status']==''){
                $statussi="в работе";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='1'){
                $statussi="Ждем документы";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='2'){
                $statussi="На проверке";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='3'){
                $statussi="Отклонен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='4'){
                $statussi="Проверен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='6'){
                $statussi="Ожидание кассы";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='7'){
                $statussi="Ожидание кассы клиента";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='16'){
                $statussi="Выезд";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='19'){
                $statussi="Получение в лич.каб.";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='20'){
                $statussi="Получение в офисе";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='35'){
                $statussi="Заявка";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='37'){
                $statussi="Ждем опись";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='38'){
                $statussi="Опись принята";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='39'){
                $statussi="Опись передана менеджеру";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='40'){
                $statussi="Отправить в ГС1";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='41'){
                $statussi="Ждем КИЗ";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='42'){
                $statussi="Маркировка КИЗ без оборудования";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='43'){
                $statussi="Маркировка КИЗ с оборудования";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='44'){
                $statussi="Оплачен в ТС";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='45'){
                $statussi="Ждем ККТ";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='47'){
                $statussi="Товар получен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='48'){
                $statussi="Товар получен без ФН";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='49'){
                $statussi="На продлении";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='5'){
                $statussi="Поставка";
                $color="background:#E9C3FB;";
            }
            if ($resschetall['status']=='17'){
                $statussi="Установка";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='18'){
                $statussi="Выезд";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='161'){
                $statussi="Произв.устан.";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='36'){
                $statussi="Регистрация + Настройка";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='50'){
                $statussi="Установка в офисе";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='51'){
                $statussi="Выезд";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='21'){
                $statussi="Частично на отгрузке";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='65'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='77'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='52'){
                $statussi="Выдали";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='53'){
                $statussi="Жем закрывающие документы";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='60'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['akt']=='1' && $resrna['cher']=='0'){
                $statussi="Отгруженые";
                $color="background:#85D6A7;";
            }
            if ($resschetall['otk']=='0' && $resrna['cher']=='1'){
                $statussi="Отказ";
                $color="background:#FB9C9C;";
            }
            if ($resschetall['otk']=='1' && $resrna['cher']=='0'){
                $statussi="Черновик";
                $color="background:#BC9B79;";
            }
            if ($resschetall['status']=='23' || $resrna['status']=='12356'){
                $statussi="Возврат";
                $color="background:#E45A51;";
            }
            if ($resschetall['status']=='12354' || $resrna['status']=='12355'){
                $statussi="Переплата";
                $color="background:#DCF541;";
            }

        }
        ?>

        <tr value="<?php echo $resschetall['id'];?> "class="schetpere">
            <?$h++;

            ?>
            <td><?echo $h;?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['d'].".".$resschetall['m'].".".$resschetall['y'];?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['ns'];?><?if($resschetall['nomerschetks']!=''){?><p><?php echo $resschetall['nomerschetks'];?></p><?}?></td>
            <td><?echo $resschetall['inn'];?></td>
            <td><?echo $resschetall['kpp'];?></td>
            <td style="width: 30%;"><?echo $resschetall['ogrn'];?></td>
            <td><?echo $resschetall['name'];?></td>
            <td><?echo schet_tip_text($resschetall);?></td>
            <td style="<? echo $color;?>"><?echo $resschetall['priceks'];?></td>
            <td style="<? echo $color;?>"><?echo $resschetall['price'];?></td>
            <td><?echo $resschetall['dataprod'];?></td>
            <td style="width: 6%;<? echo $color;?>"><?echo $statussi;?></td>
            <td style="width: 7%;"><?echo $resschetall['f_name'].' '.mb_substr($resschetall['l_name'],0,1,'UTF-8'),'. '.mb_substr($resschetall['o_name'],0,1,'UTF-8').'.';?></td>
            <td><ul class="schetkal"><?if($resschetall['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                    <li name="schetkal<?echo $resschetall['rand'];?>"id="schetkal<?echo $resschetall['rand'];?>"><?if($resschetall['datebron']!="0000-00-00"){?><?
                            $dayvis=date('d', strtotime($resschetall['datebron']));
                            $monvis=date('m', strtotime($resschetall['datebron']));
                            $yesrvis=date('Y', strtotime($resschetall['datebron']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadow<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-window<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalbr<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="bron" id="vidbr<?echo $resschetall['rand'];?>">Добавить бронь</label>
                        <input class='form-control datebr<?echo $resschetall['rand'];?>' id="datebr<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datebron']!="0000-00-00"){echo $resschetall['datebron'];}?>">
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scales<?echo $resschetall['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                var sost="";
                $(function(){
                    $('#schetkal<?echo $resschetall['rand'];?>').click(function () {
                        sost="bron";
                        $('.modal-shadow<?echo $resschetall['rand'];?>').show();
                        $('.modal-window<?echo $resschetall['rand'];?>').show();
                        $('#kalbr<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadow<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="bron")
                        {
                            if(document.getElementById("scales<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="bronyes";
                            }
                            datebroni=document.getElementsByClassName("datebr<?echo $resschetall['rand'];?>")[0].value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetkal<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadow<?echo $resschetall['rand'];?>').hide();
                        $('#kalbr<?echo $resschetall['rand'];?>').hide();
                        $('.modal-window<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scales<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td>
                <ul class="schetzvons"><?if($resschetall['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                    <li id="schetzvons<?echo $resschetall['rand'];?>"><?if($resschetall['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resschetall['datezvon']));
                            $monvis=date('m', strtotime($resschetall['datezvon']));
                            $yesrvis=date('Y', strtotime($resschetall['datezvon']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadowzs<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowzs<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalzs<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="zvon" id="vidzs<?echo $resschetall['rand'];?>">Перезвонить</label>
                        <input class='form-control datezs<?echo $resschetall['rand'];?>' id="datezs<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datezvon']!="0000-00-00"){echo $resschetall['datezvon'];}?>">
                        <textarea class='form-control timezs<?echo $resschetall['rand'];?>' id="timezs<?echo $resschetall['rand'];?>" type="time"></textarea>
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scalezs<?echo $resschetall['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                $(function(){
                    $('#schetzvons<?echo $resschetall['rand'];?>').click(function () {
                        sost="zvon";
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').show();
                        $('#kalz<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="zvon")
                        {
                            if(document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="zvonyes";
                            }
                            datezvon=document.getElementsByClassName("datezs<?echo $resschetall['rand'];?>")[0].value;
                            timezvon=document.getElementById("timezs<?echo $resschetall['rand'];?>").value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetzvons<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').hide();
                        $('#kalzs<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td value="<?echo $resschetall['rand'];?>"id="movschet<?echo $resschetall['rand'];?>"><img src="/img/icons8.png"></td>
            <div class="modal-shadowm<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowm<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalm<?echo $resschetall['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resschetall['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resschetall['idkli'];?>&parent=<?echo $resschetall['produkt'];?>&ogrn=<?echo $resschetall['orgh'];?>&inn=<?echo $resschetall['inn'];?>&kpp=<?echo $resschetall['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resschetall['ns']?>')">Продлить</label>
                        <label class="otkaz" id="otkaz<?echo $resschetall['rand'];?>">Отказать</label>
                    </div>
                    <div id="prich<?echo $resschetall['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                        <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                        while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                            <label class="otkaz" id="otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                            <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                            <script>
                                $(function() {
                                    $('#otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                        document.getElementById('modal-shadowkube').style.display = "block";
                                        document.getElementById('kube').style.display = "block";
                                        var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                                        $('#prich<?echo $resschetall['rand'];?>').hide();
                                        $.ajax({
                                            type: "GET",
                                            url: "otkazschet.php",
                                            data: "tipotkaz=" + n + "&rand=<?echo $resschetall['rand'];?>&tipschet=pereplata",
                                            success: function (html) {
                                                $.ajax({
                                                    url: "tablschetosn.php",
                                                    cache: false,
                                                    data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                                    success: function (html) {
                                                        $("#tablosn").html(html);
                                                        document.getElementById('modal-shadowkube').style.display = "none";
                                                        document.getElementById('kube').style.display = "none";
                                                    }
                                                });
                                            }
                                        });
                                    });
                                });
                            </script>
                        <?endwhile?>
                    </div>
            </form>
            </div>




            </div>
            <script>
                $(function(){
                    $('#movschet<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').show();
                        $('#kalm<?echo $resschetall['rand'];?>').show();
                    });
                    $('#otkaz<?echo $resschetall['rand'];?>').click(function () {

                        $('#prich<?echo $resschetall['rand'];?>').show();

                    });
                    $('#vist<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();

                    });
                    $('.modal-shadowm<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();
                    });
                });

            </script>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resschetall['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resschetall['ogrn']);?>&kli=<? echo $resschetall['idkli'];?>&lico=<? echo $resschetall['lico'];?>&gr=<? echo $resschetall['gr'];?>&nomerschet=<? echo $resschetall['ns'];?>&produkt=<? echo $resschetall['produkt'];?>&inn=<? echo $resschetall['inn'];?>"><img src="/img/qwerty.png"></a></td>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resschetall['idkli'];?>"><img src="/img/tablsc.png"></a></td>
            <td><a target="_blank" href=<?echo $resschetall['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
        </tr>
    <?php endwhile; }
    if ($_GET['tipi']=="2") {
       $rschetall = mysql_query("SELECT schet.id,schet.ns,schet.akt,schet.cher,schet.otk,DATE_FORMAT(schet.datasert,'%Y.%m.%d') as sert,DATE_FORMAT(schet.dataprod,'%Y.%m.%d') as prod,schet.rand,schet.kto,schet.prodlenks,schet.prodlens,schet.shetold,schet.url,schet.ogrn as orgh,schet.oplachenks,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,DATE_FORMAT(schet.dataprod,'%m') as date_po,DATE_FORMAT(schet.dataprod,'%Y') as date_poy,DATE_FORMAT(schet.datasert,'%m') as date_se,DATE_FORMAT(schet.datasert,'%Y') as date_sey,schet.dataprod,schet.datasert,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id left join users on schet.kto=users.users_id left join kvobop on schet.rand=kvobop.schet WHERE   $uslugiogrn  schet.del='0' and schet.akt!='1' and schet.akt!='1' and schet.cher!='1' and schet.otk!='1'  and (schet.shetold!='' and CONCAT(schet.y,'-',schet.m,'-',schet.d) between '".$ds."' and '".$df."' or schet.tipprod!=''and schet.tipprod!='Нет'and schet.shetold='' and schet.prodlens='0'and schet.prodlenks='0'and schet.cher='0' and schet.otk='0' and (schet.dataprod BETWEEN'".$ds."' and '".$df."' or schet.datasert BETWEEN'".$ds."' and '".$df."'))  group by schet.ns");
    while($resschetall = mysql_fetch_assoc($rschetall))  :
        if( $resschetall['oplachenks']!="1")
        {
            $color="background:#C1E5FB;";
            $statussi="Счет выставлен";
        }
        if($resschetall['oplachenks'] =="1")
        {

            if ($resschetall['status']==''){
                $statussi="в работе";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='1'){
                $statussi="Ждем документы";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='2'){
                $statussi="На проверке";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='3'){
                $statussi="Отклонен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='4'){
                $statussi="Проверен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='6'){
                $statussi="Ожидание кассы";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='7'){
                $statussi="Ожидание кассы клиента";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='16'){
                $statussi="Выезд";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='19'){
                $statussi="Получение в лич.каб.";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='20'){
                $statussi="Получение в офисе";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='35'){
                $statussi="Заявка";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='37'){
                $statussi="Ждем опись";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='38'){
                $statussi="Опись принята";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='39'){
                $statussi="Опись передана менеджеру";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='40'){
                $statussi="Отправить в ГС1";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='41'){
                $statussi="Ждем КИЗ";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='42'){
                $statussi="Маркировка КИЗ без оборудования";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='43'){
                $statussi="Маркировка КИЗ с оборудования";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='44'){
                $statussi="Оплачен в ТС";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='45'){
                $statussi="Ждем ККТ";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='47'){
                $statussi="Товар получен";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='48'){
                $statussi="Товар получен без ФН";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='49'){
                $statussi="На продлении";
                $color="background:#FFF850;";
            }
            if ($resschetall['status']=='5'){
                $statussi="Поставка";
                $color="background:#E9C3FB;";
            }
            if ($resschetall['status']=='17'){
                $statussi="Установка";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='18'){
                $statussi="Выезд";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='161'){
                $statussi="Произв.устан.";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='36'){
                $statussi="Регистрация + Настройка";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='50'){
                $statussi="Установка в офисе";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='51'){
                $statussi="Выезд";
                $color="background:#FFB366;";
            }
            if ($resschetall['status']=='21'){
                $statussi="Частично на отгрузке";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='65'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='77'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='52'){
                $statussi="Выдали";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='53'){
                $statussi="Жем закрывающие документы";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='60'){
                $statussi="На отгрузке.";
                $color="background:#85D6D1;";
            }
            if ($resschetall['status']=='23' || $resrna['status']=='12356'){
                $statussi="Возврат";
                $color="background:#E45A51;";
            }
            if ($resschetall['status']=='12354' || $resrna['status']=='12355'){
                $statussi="Переплата";
                $color="background:#DCF541;";
            }

        }
        if ($resschetall['shetold']==''){
            $statussi="Не продлено";
            $color="background:#C1BBBB;";
        }
        if ($resschetall['akt']=='1' && $resschetall['cher']=='0' && $resschetall['otk']=='0' &&$resschetall['shetold']!='')
        {
            $statussi="Отгружен";
            $color="background:#85D6A7;";
        }
        if ($resschetall['otk']=='0' && $resschetall['cher']=='1'){
            $statussi="Отказ";
            $color="background:#FB9C9C;";
        }
        if ($resschetall['otk']=='1' && $resschetall['cher']=='0'){
            $statussi="Черновик";
            $color="background:#BC9B79;";
        }
        if( $resschetall['tipprod']!='Сер/Пос'){?>
    <tr value="<?php echo $resschetall['id'];?> "class="schetpere">
        <?$h++;

        ?>
        <td><?echo $h;?></td>
        <td style="<? echo $color;?>"><?php echo $resschetall['d'].".".$resschetall['m'].".".$resschetall['y'];?></td>
        <td style="<? echo $color;?>"><?php echo $resschetall['ns'];?><?if($resschetall['nomerschetks']!=''){?><p><?php echo $resschetall['nomerschetks'];?></p><?}?></td>
        <td><?echo $resschetall['inn'];?></td>
        <td><?echo $resschetall['kpp'];?></td>
        <td style="width: 30%;"><?echo $resschetall['ogrn'];?></td>
        <td><?echo $resschetall['name'];?></td>
        <td><?echo schet_tip_text($resschetall);?></td>
        <td style="<? echo $color;?>"><?echo $resschetall['priceks'];?></td>
        <td style="<? echo $color;?>"><?echo $resschetall['price'];?></td>
        <?if($resschetall['shetold']!=''){?>
            <td><p id="datpr<?echo $resschetall['rand'];?>"><?$userdatas = mysql_fetch_assoc(mysql_query("SELECT DATE_FORMAT(schet.datasert,'%Y.%m.%d') as sert,DATE_FORMAT(schet.dataprod,'%Y.%m.%d') as prod,dataprod,prodlens,prodlenks,datasert FROM schet WHERE ns = '".$resschetall['shetold']."' "));
                if($userdatas['dataprod']!=""&&$userdatas['datasert']=="") {echo $userdatas['prod'];}if($userdatas['datasert']!=""&&$userdatas['dataprod']=="") {echo $userdatas['sert'];}if($userdatas['dataprod']!=""&&$userdatas['datasert']!=""){if($userdatas['prodlens']=="1"){echo $userdatas['prod'];}if($userdatas['prodlenks']=="1"){?><br><?echo $userdatas['sert'];}}if($userdatas['dataprod']==""&&$userdatas['datasert']==""){echo "Нет продления";}?></p>
            </td><?}
        if($resschetall['shetold']==''){?>
            <td><p id="datpr<?echo $resschetall['rand'];?>"><?if($resschetall['datasert']!=""){echo $resschetall['sert'];}if($resschetall['dataprod']!=""){echo $resschetall['prod'];}?></p></td><?}?>
        <td style="width: 6%;<? echo $color;?>"><?echo $statussi;?></td>
        <td style="width: 7%;"><?echo $resschetall['f_name'].' '.mb_substr($resschetall['l_name'],0,1,'UTF-8'),'. '.mb_substr($resschetall['o_name'],0,1,'UTF-8').'.';?></td>
        <td><ul class="schetkal"><?if($resschetall['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                <li name="schetkal<?echo $resschetall['rand'];?>"id="schetkal<?echo $resschetall['rand'];?>"><?if($resschetall['datebron']!="0000-00-00"){?><?
                        $dayvis=date('d', strtotime($resschetall['datebron']));
                        $monvis=date('m', strtotime($resschetall['datebron']));
                        $yesrvis=date('Y', strtotime($resschetall['datebron']));
                        $dss= $yesrvis.".".$monvis.".".$dayvis;
                        echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
            </ul>
        </td>
        <div class="modal-shadow<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
        <div class="modal-window<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
            <form class="contact_form" method="POST">
                <div id="kalbr<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                    <label class="bron" id="vidbr<?echo $resschetall['rand'];?>">Добавить бронь</label>
                    <input class='form-control datebr<?echo $resschetall['rand'];?>' id="datebr<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datebron']!="0000-00-00"){echo $resschetall['datebron'];}?>">
                    <div style="padding-bottom: 10px;padding-top: 10px;">
                        <input class="check" id="scales<?echo $resschetall['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                        <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                    </div>
                </div>
            </form>
        </div>
        <script>
            var sost="";
            $(function(){
                $('#schetkal<?echo $resschetall['rand'];?>').click(function () {
                    sost="bron";
                    $('.modal-shadow<?echo $resschetall['rand'];?>').show();
                    $('.modal-window<?echo $resschetall['rand'];?>').show();
                    $('#kalbr<?echo $resschetall['rand'];?>').show();
                });

                $('.modal-shadow<?echo $resschetall['rand'];?>').click(function () {
                    if(sost=="bron")
                    {
                        if(document.getElementById("scales<?echo $resschetall['rand'];?>").checked==true)
                        {
                            var message="bronyes";
                        }
                        datebroni=document.getElementsByClassName("datebr<?echo $resschetall['rand'];?>")[0].value;
                        $.ajax({
                            url: "bron.php",
                            cache: false,
                            data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                            success: function(msg){
                                var s = document.getElementById("schetkal<?echo $resschetall['rand'];?>");
                                s.innerHTML = msg;
                            }
                        });
                    }
                    $('.modal-shadow<?echo $resschetall['rand'];?>').hide();
                    $('#kalbr<?echo $resschetall['rand'];?>').hide();
                    $('.modal-window<?echo $resschetall['rand'];?>').hide();
                    document.getElementById("scales<?echo $resschetall['rand'];?>").checked=false;
                });
            });
        </script>
        <script>
            $(function() {
                $("#myTable1").tablesorter();
            });
            var dat=document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML;
            document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
        </script>
        <td>
            <ul class="schetzvons"><?if($resschetall['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                <li id="schetzvons<?echo $resschetall['rand'];?>"><?if($resschetall['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resschetall['datezvon']));
                        $monvis=date('m', strtotime($resschetall['datezvon']));
                        $yesrvis=date('Y', strtotime($resschetall['datezvon']));
                        $dss= $yesrvis.".".$monvis.".".$dayvis;
                        echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
            </ul>
        </td>
        <div class="modal-shadowzs<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
        <div class="modal-windowzs<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
            <form class="contact_form" method="POST">
                <div id="kalzs<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                    <label class="zvon" id="vidzs<?echo $resschetall['rand'];?>">Перезвонить</label>
                    <input class='form-control datezs<?echo $resschetall['rand'];?>' id="datezs<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datezvon']!="0000-00-00"){echo $resschetall['datezvon'];}?>">
                    <textarea class='form-control timezs<?echo $resschetall['rand'];?>' id="timezs<?echo $resschetall['rand'];?>" type="time"></textarea>
                    <div style="padding-bottom: 10px;padding-top: 10px;">
                        <input class="check" id="scalezs<?echo $resschetall['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                        <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                    </div>
                </div>
            </form>
        </div>
        <script>
            $(function(){
                $('#schetzvons<?echo $resschetall['rand'];?>').click(function () {
                    sost="zvon";
                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').show();
                    $('.modal-windowzs<?echo $resschetall['rand'];?>').show();
                    $('#kalz<?echo $resschetall['rand'];?>').show();
                });

                $('.modal-shadowzs<?echo $resschetall['rand'];?>').click(function () {
                    if(sost=="zvon")
                    {
                        if(document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked==true)
                        {
                            var message="zvonyes";
                        }
                        datezvon=document.getElementsByClassName("datezs<?echo $resschetall['rand'];?>")[0].value;
                        timezvon=document.getElementById("timezs<?echo $resschetall['rand'];?>").value;
                        $.ajax({
                            url: "bron.php",
                            cache: false,
                            data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                            success: function(msg){
                                var s = document.getElementById("schetzvons<?echo $resschetall['rand'];?>");
                                s.innerHTML = msg;
                            }
                        });
                    }
                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').hide();
                    $('#kalzs<?echo $resschetall['rand'];?>').hide();
                    $('.modal-windowzs<?echo $resschetall['rand'];?>').hide();
                    document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked=false;
                });
            });
        </script>
        <script>
            $(function() {
                $("#myTable1").tablesorter();
            });
            var dat=document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML;
            document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
        </script>
        <td value="<?echo $resschetall['rand'];?>"id="movschet<?echo $resschetall['rand'];?>"><img src="/img/icons8.png"></td>
        <div class="modal-shadowm<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
        <div class="modal-windowm<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
            <form class="contact_form" method="POST">
                <div id="kalm<?echo $resschetall['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resschetall['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resschetall['idkli'];?>&parent=<?echo $resschetall['produkt'];?>&ogrn=<?echo $resschetall['orgh'];?>&inn=<?echo $resschetall['inn'];?>&kpp=<?echo $resschetall['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resschetall['ns']?>')">Продлить</label>
                    <label class="otkaz" id="otkaz<?echo $resschetall['rand'];?>">Отказать</label>
                </div>
                <div id="prich<?echo $resschetall['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                    <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                    while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                        <label class="otkaz" id="otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                        <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                        <script>
                            $(function() {
                                $('#otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                    document.getElementById('modal-shadowkube').style.display = "block";
                                    document.getElementById('kube').style.display = "block";
                                    var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                    $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                                    $('#kalm<?echo $resschetall['rand'];?>').hide();
                                    $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                                    $('#prich<?echo $resschetall['rand'];?>').hide();
                                    $.ajax({
                                        type: "GET",
                                        url: "otkazschet.php",
                                        data: "tipotkaz=" + n + "&rand=<?echo $resschetall['rand'];?>&tipschet=pereplata",
                                        success: function (html) {
                                            $.ajax({
                                                url: "tablschetosn.php",
                                                cache: false,
                                                data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                                success: function (html) {
                                                    $("#tablosn").html(html);
                                                    document.getElementById('modal-shadowkube').style.display = "none";
                                                    document.getElementById('kube').style.display = "none";
                                                }
                                            });
                                        }
                                    });
                                });
                            });
                        </script>
                    <?endwhile?>
                </div>
                </form>
        </div>
    <script>
        $(function(){
            $('#movschet<?echo $resschetall['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resschetall['rand'];?>').show();
                $('.modal-windowm<?echo $resschetall['rand'];?>').show();
                $('#kalm<?echo $resschetall['rand'];?>').show();
            });
            $('#otkaz<?echo $resschetall['rand'];?>').click(function () {

                $('#prich<?echo $resschetall['rand'];?>').show();

            });
            $('#vist<?echo $resschetall['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                $('#kalm<?echo $resschetall['rand'];?>').hide();
                $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                $('#prich<?echo $resschetall['rand'];?>').hide();

            });
            $('.modal-shadowm<?echo $resschetall['rand'];?>').click(function () {
                $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                $('#kalm<?echo $resschetall['rand'];?>').hide();
                $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                $('#prich<?echo $resschetall['rand'];?>').hide();
            });
        });

    </script>
    <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resschetall['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resschetall['ogrn']);?>&kli=<? echo $resschetall['idkli'];?>&lico=<? echo $resschetall['lico'];?>&gr=<? echo $resschetall['gr'];?>&nomerschet=<? echo $resschetall['ns'];?>&produkt=<? echo $resschetall['produkt'];?>&inn=<? echo $resschetall['inn'];?>"><img src="/img/qwerty.png"></a></td>
    <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resschetall['idkli'];?>"><img src="/img/tablsc.png"></a></td>
    <td><a target="_blank" href=<?echo $resschetall['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
    </tr>
<?}if( $resschetall['tipprod']=='Сер/Пос'&& stristr($resschetall['date_se'], "10")==true&& stristr($resschetall['date_sey'], $_GET['nay'])==true||stristr($resschetall['date_sey'], $_GET['nay']+1)==true&&$resschetall['prodlenks']=='0'){?>
        <tr value="<?php echo $resschetall['id'];?>"class="schetprodlenie">
            <?$h++;?>
            <td><?echo $h;?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['d'].".".$resschetall['m'].".".$resschetall['y'];?></td>
            <td style="<? echo $color;?>"><?php echo $resschetall['ns'];?><?if($resschetall['nomerschetks']!=''){?><p><?php echo $resschetall['nomerschetks'];?></p><?}?></td>
            <td><?echo $resschetall['inn'];?></td>
            <td><?echo $resschetall['kpp'];?></td>
            <td style="width: 30%;"><?echo $resschetall['ogrn'];?></td>
            <td><?echo $resschetall['name'];?></td>
            <td>Сертификат</td>
            <td style="<? echo $color;?>"><?echo $resschetall['priceks'];?></td>
            <td style="<? echo $color;?>"><?echo $resschetall['price'];?></td>
            <td><p id="datpr<?echo $resschetall['rand'];?>"><?echo $resschetall['sert'];?></p></td>
            <td style="width: 6%;<? echo $color;?>"><?echo $statussi;?></td>
            <td style="width: 7%;"><?echo $resschetall['f_name'].' '.mb_substr($resschetall['l_name'],0,1,'UTF-8'),'. '.mb_substr($resschetall['o_name'],0,1,'UTF-8').'.';?></td>
            <td><ul class="schetkal"><?if($resschetall['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                    <li name="schetkal<?echo $resschetall['rand'];?>"id="schetkal<?echo $resschetall['rand'];?>"><?if($resschetall['datebron']!="0000-00-00"){?><?
                            $dayvis=date('d', strtotime($resschetall['datebron']));
                            $monvis=date('m', strtotime($resschetall['datebron']));
                            $yesrvis=date('Y', strtotime($resschetall['datebron']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadow<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-window<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalbr<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="bron" id="vidbr<?echo $resschetall['rand'];?>">Добавить бронь</label>
                        <input class='form-control datebr<?echo $resschetall['rand'];?>' id="datebr<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datebron']!="0000-00-00"){echo $resschetall['datebron'];}?>">
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scales<?echo $resschetall['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                var sost="";
                $(function(){
                    $('#schetkal<?echo $resschetall['rand'];?>').click(function () {
                        sost="bron";
                        $('.modal-shadow<?echo $resschetall['rand'];?>').show();
                        $('.modal-window<?echo $resschetall['rand'];?>').show();
                        $('#kalbr<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadow<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="bron")
                        {
                            if(document.getElementById("scales<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="bronyes";
                            }
                            datebroni=document.getElementsByClassName("datebr<?echo $resschetall['rand'];?>")[0].value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetkal<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadow<?echo $resschetall['rand'];?>').hide();
                        $('#kalbr<?echo $resschetall['rand'];?>').hide();
                        $('.modal-window<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scales<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td>
                <ul class="schetzvons"><?if($resschetall['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                    <li id="schetzvons<?echo $resschetall['rand'];?>"><?if($resschetall['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resschetall['datezvon']));
                            $monvis=date('m', strtotime($resschetall['datezvon']));
                            $yesrvis=date('Y', strtotime($resschetall['datezvon']));
                            $dss= $yesrvis.".".$monvis.".".$dayvis;
                            echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
                </ul>
            </td>
            <div class="modal-shadowzs<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowzs<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalzs<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                        <label class="zvon" id="vidzs<?echo $resschetall['rand'];?>">Перезвонить</label>
                        <input class='form-control datezs<?echo $resschetall['rand'];?>' id="datezs<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datezvon']!="0000-00-00"){echo $resschetall['datezvon'];}?>">
                        <textarea class='form-control timezs<?echo $resschetall['rand'];?>' id="timezs<?echo $resschetall['rand'];?>" type="time"></textarea>
                        <div style="padding-bottom: 10px;padding-top: 10px;">
                            <input class="check" id="scalezs<?echo $resschetall['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                            <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                $(function(){
                    $('#schetzvons<?echo $resschetall['rand'];?>').click(function () {
                        sost="zvon";
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').show();
                        $('#kalz<?echo $resschetall['rand'];?>').show();
                    });

                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').click(function () {
                        if(sost=="zvon")
                        {
                            if(document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked==true)
                            {
                                var message="zvonyes";
                            }
                            datezvon=document.getElementsByClassName("datezs<?echo $resschetall['rand'];?>")[0].value;
                            timezvon=document.getElementById("timezs<?echo $resschetall['rand'];?>").value;
                            $.ajax({
                                url: "bron.php",
                                cache: false,
                                data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                                success: function(msg){
                                    var s = document.getElementById("schetzvons<?echo $resschetall['rand'];?>");
                                    s.innerHTML = msg;
                                }
                            });
                        }
                        $('.modal-shadowzs<?echo $resschetall['rand'];?>').hide();
                        $('#kalzs<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowzs<?echo $resschetall['rand'];?>').hide();
                        document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked=false;
                    });
                });
            </script>
            <script>
                $(function() {
                    $("#myTable1").tablesorter();
                });
                var dat=document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML;
                document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
            </script>
            <td value="<?echo $resschetall['rand'];?>"id="movschet<?echo $resschetall['rand'];?>"><img src="/img/icons8.png"></td>
            <div class="modal-shadowm<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
            <div class="modal-windowm<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
                <form class="contact_form" method="POST">
                    <div id="kalm<?echo $resschetall['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resschetall['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resschetall['idkli'];?>&parent=<?echo $resschetall['produkt'];?>&ogrn=<?echo $resschetall['orgh'];?>&inn=<?echo $resschetall['inn'];?>&kpp=<?echo $resschetall['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resschetall['ns']?>')">Продлить</label>
                        <label class="otkaz" id="otkaz<?echo $resschetall['rand'];?>">Отказать</label>
                    </div>
                    <div id="prich<?echo $resschetall['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                        <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                        while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                            <label class="otkaz" id="otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                            <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                            <script>
                                $(function() {
                                    $('#otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                        document.getElementById('modal-shadowkube').style.display = "block";
                                        document.getElementById('kube').style.display = "block";
                                        var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                                        $('#prich<?echo $resschetall['rand'];?>').hide();
                                        $.ajax({
                                            type: "GET",
                                            url: "otkazschet.php",
                                            data: "tipotkaz=" + n + "&rand=<?echo $resschetall['rand'];?>&tipschet=pereplata",
                                            success: function (html) {
                                                $.ajax({
                                                    url: "tablschetosn.php",
                                                    cache: false,
                                                    data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                                    success: function (html) {
                                                        $("#tablosn").html(html);
                                                        document.getElementById('modal-shadowkube').style.display = "none";
                                                        document.getElementById('kube').style.display = "none";
                                                    }
                                                });
                                            }
                                        });
                                    });
                                });
                            </script>
                        <?endwhile?>
                    </div>
            </div>

            </form>
            <script>
                $(function(){
                    $('#movschet<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').show();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').show();
                        $('#kalm<?echo $resschetall['rand'];?>').show();
                    });
                    $('#otkaz<?echo $resschetall['rand'];?>').click(function () {

                        $('#prich<?echo $resschetall['rand'];?>').show();

                    });
                    $('#vist<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();

                    });
                    $('.modal-shadowm<?echo $resschetall['rand'];?>').click(function () {
                        $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                        $('#kalm<?echo $resschetall['rand'];?>').hide();
                        $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                        $('#prich<?echo $resschetall['rand'];?>').hide();
                    });
                });

            </script>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resschetall['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resschetall['ogrn']);?>&kli=<? echo $resschetall['idkli'];?>&lico=<? echo $resschetall['lico'];?>&gr=<? echo $resschetall['gr'];?>&nomerschet=<? echo $resschetall['ns'];?>&produkt=<? echo $resschetall['produkt'];?>&inn=<? echo $resschetall['inn'];?>"><img src="/img/qwerty.png"></a></td>
            <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resschetall['idkli'];?>"><img src="/img/tablsc.png"></a></td>
            <td><a target="_blank" href=<?echo $resschetall['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
        </tr>
            <?}if( $resschetall['tipprod']=='Сер/Пос'&& stristr($resschetall['date_po'], "10")==true&& stristr($resschetall['date_poy'], $_GET['nay'])==true||stristr($resschetall['date_poy'], $_GET['nay']+1)==true||$res['tipprod']=='Сер/Пос'&& stristr($resschetall['date_po'],"10")==true&& stristr($resschetall['date_poy'], $_GET['nayf'])==true||stristr($resschetall['date_poy'], $_GET['nayf']+1)==true){?>
    <?$h++;?>
    <tr value="<?php echo $resschetall['id'];?>"class="schetprodlenie">
        <?$h++;?>
        <td><?echo $h;?></td>
        <td style="<? echo $color;?>"><?php echo $resschetall['d'].".".$resschetall['m'].".".$resschetall['y'];?></td>
        <td style="<? echo $color;?>"><?php echo $resschetall['ns'];?><?if($resschetall['nomerschetks']!=''){?><p><?php echo $resschetall['nomerschetks'];?></p><?}?></td>
        <td><?echo $resschetall['inn'];?></td>
        <td><?echo $resschetall['kpp'];?></td>
        <td style="width: 30%;"><?echo $resschetall['ogrn'];?></td>
        <td><?echo $resschetall['name'];?></td>
        <td>Поставка</td>
        <td style="<? echo $color;?>"><?echo $resschetall['priceks'];?></td>
        <td style="<? echo $color;?>"><?echo $resschetall['price'];?></td>
        <td><p id="datpr<?echo $resschetall['rand'];?>"><?echo $resschetall['prod'];?></p></td>
        <td style="width: 6%;<? echo $color;?>"><?echo $statussi;?></td>
        <td style="width: 7%;"><?echo $resschetall['f_name'].' '.mb_substr($resschetall['l_name'],0,1,'UTF-8'),'. '.mb_substr($resschetall['o_name'],0,1,'UTF-8').'.';?></td>
        <td><ul class="schetkal"><?if($resschetall['datebron']!="0000-00-00"){?><img src="/img/dino.png"><?}?>
                <li name="schetkal<?echo $resschetall['rand'];?>"id="schetkal<?echo $resschetall['rand'];?>"><?if($resschetall['datebron']!="0000-00-00"){?><?
                        $dayvis=date('d', strtotime($resschetall['datebron']));
                        $monvis=date('m', strtotime($resschetall['datebron']));
                        $yesrvis=date('Y', strtotime($resschetall['datebron']));
                        $dss= $yesrvis.".".$monvis.".".$dayvis;
                        echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
            </ul>
        </td>
        <div class="modal-shadow<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
        <div class="modal-window<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
            <form class="contact_form" method="POST">
                <div id="kalbr<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                    <label class="bron" id="vidbr<?echo $resschetall['rand'];?>">Добавить бронь</label>
                    <input class='form-control datebr<?echo $resschetall['rand'];?>' id="datebr<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datebron']!="0000-00-00"){echo $resschetall['datebron'];}?>">
                    <div style="padding-bottom: 10px;padding-top: 10px;">
                        <input class="check" id="scales<?echo $resschetall['rand'];?>" name="scales" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                        <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scales">Отправить письмо на почту</label>
                    </div>
                </div>
            </form>
        </div>
        <script>
            var sost="";
            $(function(){
                $('#schetkal<?echo $resschetall['rand'];?>').click(function () {
                    sost="bron";
                    $('.modal-shadow<?echo $resschetall['rand'];?>').show();
                    $('.modal-window<?echo $resschetall['rand'];?>').show();
                    $('#kalbr<?echo $resschetall['rand'];?>').show();
                });

                $('.modal-shadow<?echo $resschetall['rand'];?>').click(function () {
                    if(sost=="bron")
                    {
                        if(document.getElementById("scales<?echo $resschetall['rand'];?>").checked==true)
                        {
                            var message="bronyes";
                        }
                        datebroni=document.getElementsByClassName("datebr<?echo $resschetall['rand'];?>")[0].value;
                        $.ajax({
                            url: "bron.php",
                            cache: false,
                            data: "tip=bron&message="+message+"&datebron="+datebroni+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                            success: function(msg){
                                var s = document.getElementById("schetkal<?echo $resschetall['rand'];?>");
                                s.innerHTML = msg;
                            }
                        });
                    }
                    $('.modal-shadow<?echo $resschetall['rand'];?>').hide();
                    $('#kalbr<?echo $resschetall['rand'];?>').hide();
                    $('.modal-window<?echo $resschetall['rand'];?>').hide();
                    document.getElementById("scales<?echo $resschetall['rand'];?>").checked=false;
                });
            });
        </script>
        <script>
            $(function() {
                $("#myTable1").tablesorter();
            });
            var dat=document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML;
            document.getElementById('schetkal<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
        </script>
        <td>
            <ul class="schetzvons"><?if($resschetall['datezvon']!="0000-00-00"){?><img src="/img/phonegreen.png"><?}?>
                <li id="schetzvons<?echo $resschetall['rand'];?>"><?if($resschetall['date_zvon']!="00.00.0000"){?><?$dayvis=date('d', strtotime($resschetall['datezvon']));
                        $monvis=date('m', strtotime($resschetall['datezvon']));
                        $yesrvis=date('Y', strtotime($resschetall['datezvon']));
                        $dss= $yesrvis.".".$monvis.".".$dayvis;
                        echo $yesrvis.".".$monvis.".".$dayvis;}?></li>
            </ul>
        </td>
        <div class="modal-shadowzs<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
        <div class="modal-windowzs<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:75%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
            <form class="contact_form" method="POST">
                <div id="kalzs<?echo $resschetall['rand'];?>"style="width: 60%;float: left;background: white;border-radius: 3px;">
                    <label class="zvon" id="vidzs<?echo $resschetall['rand'];?>">Перезвонить</label>
                    <input class='form-control datezs<?echo $resschetall['rand'];?>' id="datezs<?echo $resschetall['rand'];?>" type="date"value="<?if($resschetall['datezvon']!="0000-00-00"){echo $resschetall['datezvon'];}?>">
                    <textarea class='form-control timezs<?echo $resschetall['rand'];?>' id="timezs<?echo $resschetall['rand'];?>" type="time"></textarea>
                    <div style="padding-bottom: 10px;padding-top: 10px;">
                        <input class="check" id="scalezs<?echo $resschetall['rand'];?>" name="scalezs" type="checkbox" style="float: left;height: 25px;width: 25px;margin-top:0px;">
                        <label style="position: relative; font-size: 14pt; color: rgb(83, 101, 112); font-weight: normal;" id="scalesl" for="scalezs">Отправить письмо на почту</label>
                    </div>
                </div>
            </form>
        </div>
        <script>
            $(function(){
                $('#schetzvons<?echo $resschetall['rand'];?>').click(function () {
                    sost="zvon";
                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').show();
                    $('.modal-windowzs<?echo $resschetall['rand'];?>').show();
                    $('#kalz<?echo $resschetall['rand'];?>').show();
                });

                $('.modal-shadowzs<?echo $resschetall['rand'];?>').click(function () {
                    if(sost=="zvon")
                    {
                        if(document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked==true)
                        {
                            var message="zvonyes";
                        }
                        datezvon=document.getElementsByClassName("datezs<?echo $resschetall['rand'];?>")[0].value;
                        timezvon=document.getElementById("timezs<?echo $resschetall['rand'];?>").value;
                        $.ajax({
                            url: "bron.php",
                            cache: false,
                            data: "tip=zvon&massage="+message+"&datezvon="+datezvon+"&timezvon="+timezvon+"&rand=<?echo $resschetall['rand'];?>&users=<?echo $_GET['id'];?>",
                            success: function(msg){
                                var s = document.getElementById("schetzvons<?echo $resschetall['rand'];?>");
                                s.innerHTML = msg;
                            }
                        });
                    }
                    $('.modal-shadowzs<?echo $resschetall['rand'];?>').hide();
                    $('#kalzs<?echo $resschetall['rand'];?>').hide();
                    $('.modal-windowzs<?echo $resschetall['rand'];?>').hide();
                    document.getElementById("scalezs<?echo $resschetall['rand'];?>").checked=false;
                });
            });
        </script>
        <script>
            $(function() {
                $("#myTable1").tablesorter();
            });
            var dat=document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML;
            document.getElementById('schetzvons<?echo $resschetall['rand'];?>').innerHTML=dat.split('.').reverse().join('.');
        </script>
        <td value="<?echo $resschetall['rand'];?>"id="movschet<?echo $resschetall['rand'];?>"><img src="/img/icons8.png"></td>
        <div class="modal-shadowm<?echo $resschetall['rand'];?>" style="display:none;position:fixed;top:0;right:0;bottom:0;left:0;background:#000;opacity:0.5;"></div>
        <div class="modal-windowm<?echo $resschetall['rand'];?>"style=" position: fixed;top:50%;left:95%;margin:-100px 0 0 -200px;width:450px;height:auto;border-radius:10px;display:none;background:transparent; z-index:9999;">
            <form class="contact_form" method="POST">
                <div id="kalm<?echo $resschetall['rand'];?>"style="float: left;background: white;border-radius: 3px;">
                <label class="visto" id="vist<?echo $resschetall['rand'];?>" onclick="window.open('<?php echo VOOVI_MAIN_URL; ?>/newschet.php?id=<?echo $resschetall['idkli'];?>&parent=<?echo $resschetall['produkt'];?>&ogrn=<?echo $resschetall['orgh'];?>&inn=<?echo $resschetall['inn'];?>&kpp=<?echo $resschetall['kpp'];?>&tip=<?echo $_GET['ogr'];?>&head=&oldns=<?echo $resschetall['ns']?>')">Продлить</label>
                    <label class="otkaz" id="otkaz<?echo $resschetall['rand'];?>">Отказать</label>
                </div>
                <div id="prich<?echo $resschetall['rand'];?>"style="display: none;position: relative;float: left;right: 480px;background: white;border-radius: 3px;bottom: 200px;padding-left: 10px;height: 750px;overflow-y: scroll;">
                    <?php $rotkaz = mysql_query("SELECT * FROM `prichotk`");
                    while($resotkaz = mysql_fetch_assoc($rotkaz))  : ?>
                        <label class="otkaz" id="otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>"><? echo $resotkaz['value'];?></label>
                        <input id="otkazp<? echo $resotkaz['id'];?>" value="<? echo $resotkaz['id'];?>" style="display:none;">
                        <script>
                            $(function() {
                                $('#otkazp<?echo $resschetall['rand'];?><? echo $resotkaz['id'];?>').click(function () {

                                    document.getElementById('modal-shadowkube').style.display = "block";
                                    document.getElementById('kube').style.display = "block";
                                    var n = document.getElementById("otkazp<? echo $resotkaz['id'];?>").value;
                                    $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                                    $('#kalm<?echo $resschetall['rand'];?>').hide();
                                    $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                                    $('#prich<?echo $resschetall['rand'];?>').hide();
                                    $.ajax({
                                        type: "GET",
                                        url: "otkazschet.php",
                                        data: "tipotkaz=" + n + "&rand=<?echo $resschetall['rand'];?>&tipschet=pereplata",
                                        success: function (html) {
                                            $.ajax({
                                                url: "tablschetosn.php",
                                                cache: false,
                                                data: "tip=pereplata&na=<?echo $_GET['na'];?>&nay=<?echo $_GET['nay'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&ms=<?echo $_GET['ms'];?>&mf=<?echo $_GET['mf'];?>&ogr=<?echo $_GET['ogr'];?>&id=<?echo $_GET['id'];?>&tipi=<?echo $_GET['tipi'];?>&ds=<?echo $_GET['ds'];?>&df=<?echo $_GET['df'];?>&dayys=<?echo $_GET['dayys'];?>&dayyf=<?echo $_GET['dayyf'];?>",
                                                success: function (html) {
                                                    $("#tablosn").html(html);
                                                    document.getElementById('modal-shadowkube').style.display = "none";
                                                    document.getElementById('kube').style.display = "none";
                                                }
                                            });
                                        }
                                    });
                                });
                            });
                        </script>
                    <?endwhile?>
                </div>
        </div>

        </form>
        <script>
            $(function(){
                $('#movschet<?echo $resschetall['rand'];?>').click(function () {
                    $('.modal-shadowm<?echo $resschetall['rand'];?>').show();
                    $('.modal-windowm<?echo $resschetall['rand'];?>').show();
                    $('#kalm<?echo $resschetall['rand'];?>').show();
                });
                $('#otkaz<?echo $resschetall['rand'];?>').click(function () {

                    $('#prich<?echo $resschetall['rand'];?>').show();

                });
                $('#vist<?echo $resschetall['rand'];?>').click(function () {
                    $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                    $('#kalm<?echo $resschetall['rand'];?>').hide();
                    $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                    $('#prich<?echo $resschetall['rand'];?>').hide();

                });
                $('.modal-shadowm<?echo $resschetall['rand'];?>').click(function () {
                    $('.modal-shadowm<?echo $resschetall['rand'];?>').hide();
                    $('#kalm<?echo $resschetall['rand'];?>').hide();
                    $('.modal-windowm<?echo $resschetall['rand'];?>').hide();
                    $('#prich<?echo $resschetall['rand'];?>').hide();
                });
            });

        </script>
        <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/komnete.php?id=1&rand=<? echo $resschetall['rand'];?>&p=0&ogrn=<? echo str_replace('"', "&quot", $resschetall['ogrn']);?>&kli=<? echo $resschetall['idkli'];?>&lico=<? echo $resschetall['lico'];?>&gr=<? echo $resschetall['gr'];?>&nomerschet=<? echo $resschetall['ns'];?>&produkt=<? echo $resschetall['produkt'];?>&inn=<? echo $resschetall['inn'];?>"><img src="/img/qwerty.png"></a></td>
        <td value="<?echo $resschetall['rand'];?>"><a target="_blank" href="<?php echo VOOVI_MAIN_URL; ?>/kartklient.php?id=<? echo $resschetall['idkli'];?>"><img src="/img/tablsc.png"></a></td>
        <td><a target="_blank" href=<?echo $resschetall['url'];?>><img src="/img/ship.png"style="width: 20px;"></a></td>
    </tr>
<?}?>

<?php endwhile; ?>
    <?}
    }?>
        </tbody>
         </table>
     </div>


<?}
} ?>
<script>
$(function() {
  $("#myTable1").tablesorter();
});

$(document).ready(function() {
  $('#clickme').click(function() {
    var key = Math.floor(Math.random() * 1000);
    $('<tr><td>' + key + '</td></tr>').prependTo($('#tbod1'));
    $("#myTable1").trigger('updateCache');
  });

});

$(function() {
  $("#myTable2").tablesorter();
});</script>
<script>
(function() {
  function isInteractive(node) {
    while (node && node !== document && node.nodeType === 1) {
      var tag = node.tagName ? node.tagName.toLowerCase() : '';
      if (tag === 'a' || tag === 'input' || tag === 'textarea' || tag === 'select' || tag === 'button' || tag === 'label') {
        return true;
      }
      node = node.parentNode;
    }
    return false;
  }

  function initSchetTableScroll() {
    var scrolls = document.querySelectorAll('.schet-table-scroll');
    for (var i = 0; i < scrolls.length; i++) {
      (function(scroll) {
        if (scroll.getAttribute('data-drag-scroll-ready') === '1') {
          return;
        }
        scroll.setAttribute('data-drag-scroll-ready', '1');

        var isDown = false;
        var wasDragged = false;
        var startX = 0;
        var scrollLeft = 0;

        scroll.addEventListener('mousedown', function(e) {
          if (e.button !== 0 || isInteractive(e.target)) {
            return;
          }
          isDown = true;
          wasDragged = false;
          startX = e.pageX;
          scrollLeft = scroll.scrollLeft;
          scroll.className += ' is-dragging';
        });

        function stopDrag() {
          isDown = false;
          scroll.className = scroll.className.replace(' is-dragging', '');
        }

        scroll.addEventListener('mouseleave', stopDrag);
        scroll.addEventListener('mouseup', stopDrag);
        scroll.addEventListener('mousemove', function(e) {
          if (!isDown) {
            return;
          }
          var walk = e.pageX - startX;
          if (Math.abs(walk) > 3) {
            wasDragged = true;
            e.preventDefault();
            scroll.scrollLeft = scrollLeft - walk;
          }
        });
        scroll.addEventListener('click', function(e) {
          if (!wasDragged) {
            return;
          }
          e.preventDefault();
          e.stopPropagation();
          wasDragged = false;
        }, true);
      })(scrolls[i]);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSchetTableScroll);
  } else {
    initSchetTableScroll();
  }
})();
</script>
<script>

/*$( "#datestart" ).change(function () {
	document.getElementById('modal-shadowkube').style.display="block";
	 document.getElementById('kube').style.display="block";
	var date = new Date(document.getElementById('datestart').value); 
var y = date.getFullYear();
 var m = date.getMonth()+1;
 datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=<?echo $_GET['tip'];?>&na="+m+"&nay="+y+"&ms="+datastart+"&mf="+datafinish+"&ogr=<?echo $_GET['ogr'];?>&tipi=<?echo $_GET['tipi'];?>",	
                    success: function(html){  
                        $("#tablosn").html(html);  
						document.getElementById('modal-shadowkube').style.display="none";
						document.getElementById('kube').style.display="none";
                    }  
                });  
            });
$( "#datefinish" ).change(function () {
	document.getElementById('modal-shadowkube').style.display="block";
	 document.getElementById('kube').style.display="block";
	var date = new Date(document.getElementById('datestart').value); 
var y = date.getFullYear();
 var m = date.getMonth()+1;
 datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
                $.ajax({  
                    url: "tablschetosn.php",  
                    cache: false,  
					data: "tip=<?echo $_GET['tip'];?>&na="+m+"&nay="+y+"&ms="+datastart+"&mf="+datafinish+"&ogr=<?echo $_GET['ogr'];?>&tipi=<?echo $_GET['tipi'];?>",	
                    success: function(html){  
                        $("#tablosn").html(html); 
                        document.getElementById('modal-shadowkube').style.display="none";
                        document.getElementById('kube').style.display="none";						
                    }  
                });  
            });*/
		
</script>
