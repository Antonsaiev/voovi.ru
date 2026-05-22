<?php
# подключаем конфиг
include 'conf.php';
setcookie('y', $_GET['y'], time() + 60 * 60 * 24 * 30, "/", VOOVI_COOKIE_DOMAIN);
if ($_GET['orgn'] == "0") {
    $allogrnschet = '';
    $product_orgn = '';
} else {
    $allogrnschet = "uslugi.id='" . $_GET['orgn'] . "' and ";
    $product_orgn = "WHERE parent = '" . $_GET['orgn'] . "' ";
}

$products_query = mysql_query("SELECT id FROM `produkti`" . $product_orgn);
$product_ids = []; // Массив для хранения id продуктов

while ($row = mysql_fetch_assoc($products_query)) {
    $product_ids[] = $row['id'];
}

// Конвертируем массив id в JSON для вывода в консоль
// $product_ids_json = json_encode($product_ids);
// echo "<script>console.log('Product IDs:', $product_ids_json);</script>";

// Преобразуем массив id в строку для использования в запросе с IN
$id_list = implode(',', $product_ids);
// echo "<script>console.log('product_ids: " . $id_list . "');</script>";
?>

<div class="by amt" style="
 width: 100%;
        margin-top: 35px;
    padding-left: 0px;
	margin-bottom: 10px;
">
    <div style="
width: 100%;
    text-align: center;
    font-size: 20pt;
">
        <? echo "Таблица движения счетов за " . $_GET['y'] . " год"; ?>
    </div>
</div>
<div class="period-table-scroll" style="
width: 100%;
margin-top: 20px;
margin-bottom: 20px;
overflow-x: auto;
">
    <?
    $m = date(F);
    $_monthsList = array(
        "1" => "Январь", "2" => "Февраль", "3" => "Март",
        "4" => "Апрель", "5" => "Май", "6" => "Июнь",
        "7" => "Июль", "8" => "Август", "9" => "Сентябрь",
        "10" => "Октябрь", "11" => "Ноябрь", "12" => "Декабрь");
    if ($_GET['y'] == date('Y')) {
        $colmonf = date('n');
    }
    if ($_GET['y'] != date('Y')) {
        $colmonf = 12;
    } ?>
    <?
    $argin = "width:100%;min-width:100%;border-collapse:collapse;";
    if ($_GET['orgn'] != "12") {
        $argin .= "margin:0 auto;";
    } ?>
    <table style="<? echo $argin; ?>">
        <thead>
        <tr>

            <th class="thperiod" style="background-color: white;">год месяц</th>
            <th class="thperiod" style="background-color: white;">всего счетов</th>
            <th class="thperiod" style="background-color: #78AFD8;">без оплаты</th>
            <th class="thperiod" style="background-color: #FFF850;">оплата</th>
            <? if ($_GET['orgn'] == "12") { ?>
                <th class="thperiod" style="background-color: #FFF850;">ждем доки</th>
                <th class="thperiod" style="background-color: #FFF850;">на проверке</th>
                <th class="thperiod" style="background-color: #FFF850;">откл-н</th>
                <th class="thperiod" style="background-color: #FFF850;">пров-ен</th>
                <th class="thperiod" style="background-color: #E9C3FB;">поставк</th>
                <th class="thperiod" style="background-color: #85D6D1;">ожидан.ккт</th>
                <th class="thperiod" style="background-color: #85D6D1;">ожидан ккт клиента</th>
                <th class="thperiod" style="background-color: #FFF850;">выезд</th>
                <th class="thperiod" style="background-color: #FFB366;">устан.в офисе</th>
                <th class="thperiod" style="background-color: #FFB366;">выехали на установ</th>
                <th class="thperiod" style="background-color: #FFF850;">полу-ние в лк</th>
                <th class="thperiod" style="background-color: #FFF850;">получ-ие в офисе</th>
                <th class="thperiod" style="background-color: #E76D74;">возврат</th>
                <th class="thperiod" style="background-color: #E9C3FB;">частич-о на отгрузке</th>
            <? } ?>
            <? if ($_GET['orgn'] == "24") { ?>
                <th class="thperiod" style="background-color: #FFF850;">оплата тс</th>
                <th class="thperiod" style="background-color: #E9C3FB;">ждем ккт</th>
                <th class="thperiod" style="background-color: #85D6D1;">Товар получен</th>
                <th class="thperiod" style="background-color: #85D6D1;">Товар получен без фн</th>
                <th class="thperiod" style="background-color: #FFF850;">на продлении</th>
                <th class="thperiod" style="background-color: #FFB366;">устан. в офисе</th>
                <th class="thperiod" style="background-color: #FFB366;">выезд</th>
                <th class="thperiod" style="background-color: #FFF850;">выдали</th>
                <th class="thperiod" style="background-color: #FFF850;">ждем закрывающие документы</th>
                <th class="thperiod" style="background-color: #E76D74;">возврат</th>
            <? } ?>
            <? if ($_GET['orgn'] == "22") { ?>
                <th class="thperiod" style="background-color: #FFF850;">заявка</th>
                <th class="thperiod" style="background-color: #E9C3FB;">регистрация и настройка</th>
                <th class="thperiod" style="background-color: #85D6D1;">ждем опись</th>
                <th class="thperiod" style="background-color: #85D6D1;">опись принята</th>
                <th class="thperiod" style="background-color: #FFF850;">опсиь передана менеджеру</th>
                <th class="thperiod" style="background-color: #FFB366;">отправить в гс1</th>
                <th class="thperiod" style="background-color: #FFB366;">ждем киз</th>
                <th class="thperiod" style="background-color: #85D6D1;">маркировка киз без оборудова</th>
                <th class="thperiod" style="background-color: #85D6D1;">маркировка
                    киз с оборудования
                </th>
            <? } ?>
            <th class="thperiod" style="background-color: #E9C3FB;">на отгрузке</th>
            <? if ($_GET['orgn'] == "12") { ?>
                <th class="thperiod" style="background-color: #FFB366;">произв-я устан-ка</th>
            <? } ?>


            <th class="thperiod" style="background-color: #90BEA3;">отгр-ен</th>
            <? if ($_GET['orgn'] != "22") { ?>
                <th class="thperiod" style="background-color: #A0D7FF;">пере-плата</th>
            <? } ?>
            <th class="thperiod" style="background-color: #FB9C9C;">отказ</th>
            <th class="thperiod" style="background-color: #BC9B79;">черн-ик</th>
            <th class="thperiod" style="background-color: #85D6A7;">закрыт месяц</th>
            <th class="thperiod" style="background-color: #E9C3FB;">Долги доки</th>
            <th class="thperiod" style="background-color: #E9C3FB;">Долги оплаты</th>

        </tr>
        </thead>
        <tbody>

        <? $kolzak = 0;
        $kolot = 0;
        for ($m = 1;
        $m <= $colmonf;
        $m++) {
        if ($m < 10) {
            $month = "0" . $m;
        } else {
            $month = $m;
        }
        $monlist[] = array($month);
        $rm = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del='0'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and schet.otk!='1' and schet.cher!='1' group by schet.rand");
        $resm = mysql_num_rows($rm);
        $rim = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del='0'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and schet.akt='1'  group by schet.rand");
        $resim = mysql_num_rows($rim);
        $rdoljen = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and schet.doljen = '1'  group by schet.rand");
        $resdoljen = mysql_num_rows($rdoljen);
        $rdoljenop = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and schet.doljenop = '1'  group by schet.rand");
        $resdoljenop = mysql_num_rows($rdoljenop);
        if ($resm <= $resim) {
            ++$kolzak;
            $kolot += $resim;
            $color = "background-color:#6FCF97";
            $colordolo = "background-color:#E9C3FB";
            $colordolop = "background-color:#6FCF97";
            $colorpos = "background-color:#6FCF97";
            $colorop = "background-color:#6FCF97";
            $colordg = "background-color:#6FCF97";
            $colornap = "background-color:#6FCF97";
            $colorotkl = "background-color:#6FCF97";
            $colorprov = "background-color:#6FCF97";
            $colorkkt = "background-color:#6FCF97";
            $colorkktk = "background-color:#6FCF97";
            $colorvie = "background-color:#6FCF97";
            $colorust = "background-color:#6FCF97";
            $colorvust = "background-color:#6FCF97";
            $colorpol = "background-color:#6FCF97";
            $colorpolo = "background-color:#6FCF97";
            $colorvoz = "background-color:#6FCF97";
            $colorcha = "background-color:#6FCF97";
            $colornaa = "background-color:#6FCF97";
            $colorprou = "background-color:#6FCF97";
            $colorpere = "background-color:#6FCF97";
            $colorotk = "background-color:#6FCF97";
            $colorcher = "background-color:#6FCF97";
            $dol = "0";
            $resdol = "0";
            $respos = "0";
            $resop = "0";
            $resgd = "0";
            $resnap = "0";
            $resotkl = "0";
            $resprov = "0";
            $reskkt = "0";
            $reskktk = "0";
            $resvie = "0";
            $resust = "0";
            $resvust = "0";
            $respol = "0";
            $respolo = "0";
            $resvoz = "0";
            $rescha = "0";
            $resnaa = "0";
            $resprou = "0";
            $respere = "0";
            $resotk = "0";
            $rescher = "0";
            $rcher = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and  schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "'  and schet.cher='1'and schet.otk='0' group by schet.rand");
            $rescher = mysql_num_rows($rcher);
            $rotk = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and  schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "'  and schet.otk='1' and schet.cher='0'group by schet.rand");
            $resotk = mysql_num_rows($rotk);
            $rep = mysql_query("SELECT schet.akt_date FROM `schet` WHERE produkt IN ($id_list) and  schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and schet.akt='1' group by schet.rand order by schet.akt_date asc");
            while ($resep = mysql_fetch_assoc($rep)):
                $zak = $resep['akt_date'];
            endwhile;
        }
        if ($resm > $resim) {
            if ($_GET['orgn'] == "12") {
                $statusgd = "schet.status='1'  AND ";
                $statusnap = "schet.status='2'  AND ";
                $statusotkl = "schet.status='3'  AND ";
                $statusprov = "schet.status='4'  AND ";
                $statuspos = "schet.status='5'  AND ";
                $statuskkt = "schet.status='6'  AND ";
                $statuskktk = "schet.status='7'  AND ";
                $statusvie = "schet.status='16'  AND ";
                $statusust = "schet.status='17'  AND ";
                $statusvust = "schet.status='18'  AND ";
                $statuspol = "schet.status='19'  AND ";
                $statuspolo = "schet.status='20'  AND ";
                $statusvoz = "schet.status='23'  AND ";
                $statuscha = "schet.status='21'  AND ";
                $statusnaa = "schet.status='65'  AND ";
                $statusprou = "schet.status='161'  AND ";
                $statuspere = "schet.status='12354'  AND ";
            }
            if ($_GET['orgn'] == "24") {
                $statusgd = "schet.status='44'  AND ";
                $statusnap = "schet.status='45'  AND ";
                $statusotkl = "schet.status='47'  AND ";
                $statusprov = "schet.status='48'  AND ";
                $statuspos = "schet.status='49'  AND ";
                $statuskkt = "schet.status='50'  AND ";
                $statuskktk = "schet.status='51'  AND ";
                $statusvie = "schet.status='52'  AND ";
                $statusust = "schet.status='53'  AND ";
                $statusvoz = "schet.status='12356'  AND ";
                $statusnaa = "schet.status='60'  AND ";
                $statuspere = "schet.status='12355'  AND ";
            }
            if ($_GET['orgn'] == "22") {
                $statusgd = "schet.status='35'  AND ";
                $statusnap = "schet.status='36'  AND ";
                $statusotkl = "schet.status='37'  AND ";
                $statusprov = "schet.status='38'  AND ";
                $statuspos = "schet.status='39'  AND ";
                $statuskkt = "schet.status='40'  AND ";
                $statuskktk = "schet.status='41'  AND ";
                $statusvie = "schet.status='42'  AND ";
                $statusust = "schet.status='43'  AND ";
                $statusnaa = "schet.status='77'  AND ";
            }
            $kolot += $resim;
            $color = "background-color:white";
            $colordol = "background-color:white";
            $colordolop = "background-color:#78AFD8";
            $colordolo = "background-color:#E9C3FB";

            $rdol = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "'  AND schet.oplachenks!= '1' and schet.oplachen != '1' AND schet.status='' AND schet.gotov = '0' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resdol = mysql_num_rows($rdol);
            $rop = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' AND schet.oplachenks = '1' AND schet.status='' AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resop = mysql_num_rows($rop);
            $rgd = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' AND $statusgd schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resgd = mysql_num_rows($rgd);
            $rnap = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' AND $statusnap schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resnap = mysql_num_rows($rnap);
            $rotkl = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' AND $statusotkl schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resotkl = mysql_num_rows($rotkl);
            $rprov = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' AND $statusprov schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resprov = mysql_num_rows($rprov);
            $rpos = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statuspos schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $respos = mysql_num_rows($rpos);
            $rkkt = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statuskkt schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $reskkt = mysql_num_rows($rkkt);
            $rkktk = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statuskktk schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $reskktk = mysql_num_rows($rkktk);
            $rvie = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statusvie schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resvie = mysql_num_rows($rvie);
            $rust = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statusust schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resust = mysql_num_rows($rust);
            $rvust = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statusvust schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resvust = mysql_num_rows($rvust);
            $rpol = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statuspol schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $respol = mysql_num_rows($rpol);
            $rpolo = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statuspolo schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $respolo = mysql_num_rows($rpolo);
            $rvoz = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statusvoz schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resvoz = mysql_num_rows($rvoz);
            $rcha = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statuscha schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $rescha = mysql_num_rows($rcha);
            $rnaa = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statusnaa schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resnaa = mysql_num_rows($rnaa);
            $rprou = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statusprou schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resprou = mysql_num_rows($rprou);
            $rpere = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "' and  schet.del = '0' and $statuspere  schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $respere = mysql_num_rows($rpere);
            $rcher = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "'  and schet.cher='1'and schet.otk='0' group by schet.rand");
            $rescher = mysql_num_rows($rcher);
            $rotk = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and schet.m='" . $month . "'  and schet.otk='1' and schet.cher='0'group by schet.rand");
            $resotk = mysql_num_rows($rotk);
            $colorcher = "background-color:#BC9B79";
            $colorotk = "background-color:#FB9C9C";
            $zak = "";

            if ($resdol == "0") {
                $colordolop = "background-color:#6FCF97";
            }
            if ($resdol != "0") {
                $colordolop = "background-color:#78AFD8";
            }
            if ($resop == "0") {
                $colorop = "background-color:#6FCF97";
            }
            if ($resop != "0") {
                $colorop = "background-color:#FFF850";
            }
            if ($resgd == "0") {
                $colordg = "background-color:#6FCF97";
            }
            if ($_GET['orgn'] == "12") {
                if ($resgd != "0") {
                    $colordg = "background-color:#FFF850";
                }
                if ($resnap == "0") {
                    $colornap = "background-color:#6FCF97";
                }
                if ($resnap != "0") {
                    $colornap = "background-color:#FFF850";
                }
                if ($resotkl == "0") {
                    $colorotkl = "background-color:#6FCF97";
                }
                if ($resotkl != "0") {
                    $colorotkl = "background-color:#FFF850";
                }
                if ($resprov == "0") {
                    $colorprov = "background-color:#6FCF97";
                }
                if ($resprov != "0") {
                    $colorprov = "background-color:#FFF850";
                }
                if ($respos == "0") {
                    $colorpos = "background-color:#6FCF97";
                }
                if ($respos != "0") {
                    $colorpos = "background-color:#E9C3FB";
                }
                if ($reskkt == "0") {
                    $colorkkt = "background-color:#6FCF97";
                }
                if ($reskkt != "0") {
                    $colorkkt = "background-color:#85D6D1";
                }
                if ($reskktk == "0") {
                    $colorkktk = "background-color:#6FCF97";
                }
                if ($reskktk != "0") {
                    $colorkktk = "background-color:#85D6D1";
                }
                if ($resvie == "0") {
                    $colorvie = "background-color:#6FCF97";
                }
                if ($resvie != "0") {
                    $colorvie = "background-color:#FFF850";
                }
                if ($resust == "0") {
                    $colorust = "background-color:#6FCF97";
                }
                if ($resust != "0") {
                    $colorust = "background-color:#FFB366";
                }
                if ($resvust == "0") {
                    $colorvust = "background-color:#6FCF97";
                }
                if ($resvust != "0") {
                    $colorvust = "background-color:#FFB366";
                }

                if ($respol == "0") {
                    $colorpol = "background-color:#6FCF97";
                }
                if ($respol != "0") {
                    $colorpol = "background-color:#FFF850";
                }
                if ($respolo == "0") {
                    $colorpolo = "background-color:#6FCF97";
                }
                if ($respolo != "0") {
                    $colorpolo = "background-color:#FFF850";
                }
                if ($resvoz == "0") {
                    $colorvoz = "background-color:#6FCF97";
                }
                if ($resvoz != "0") {
                    $colorvoz = "background-color:#E76D74";
                }
                if ($rescha == "0") {
                    $colorcha = "background-color:#6FCF97";
                }
                if ($rescha != "0") {
                    $colorcha = "background-color:#E9C3FB";
                }

                if ($resnaa == "0") {
                    $colornaa = "background-color:#6FCF97";
                }

                if ($resnaa != "0") {
                    $colornaa = "background-color:#E9C3FB";
                }
                if ($resprou == "0") {
                    $colorprou = "background-color:#6FCF97";
                }
                if ($resprou != "0") {
                    $colorprou = "background-color:#FFB366";
                }

                if ($respere == "0") {
                    $colorpere = "background-color:#6FCF97";
                }
                if ($respere != "0") {
                    $colorpere = "background-color:#A0D7FF";
                }
            }
            if ($_GET['orgn'] == "24") {
                if ($resgd != "0") {
                    $colordg = "background-color:#FFF850";
                }
                if ($resnap == "0") {
                    $colornap = "background-color:#6FCF97";
                }
                if ($resnap != "0") {
                    $colornap = "background-color:#E9C3FB";
                }
                if ($resotkl == "0") {
                    $colorotkl = "background-color:#6FCF97";
                }
                if ($resotkl != "0") {
                    $colorotkl = "background-color:#85D6D1";
                }
                if ($resprov == "0") {
                    $colorprov = "background-color:#6FCF97";
                }
                if ($resprov != "0") {
                    $colorprov = "background-color:#85D6D1";
                }
                if ($respos == "0") {
                    $colorpos = "background-color:#6FCF97";
                }
                if ($respos != "0") {
                    $colorpos = "background-color:#FFF850";
                }
                if ($reskkt == "0") {
                    $colorkkt = "background-color:#6FCF97";
                }
                if ($reskkt != "0") {
                    $colorkkt = "background-color:#FFB366";
                }
                if ($reskktk == "0") {
                    $colorkktk = "background-color:#6FCF97";
                }
                if ($reskktk != "0") {
                    $colorkktk = "background-color:#FFB366";
                }
                if ($resvie == "0") {
                    $colorvie = "background-color:#6FCF97";
                }
                if ($resvie != "0") {
                    $colorvie = "background-color:#FFF850";
                }
                if ($resust == "0") {
                    $colorust = "background-color:#6FCF97";
                }
                if ($resust != "0") {
                    $colorust = "background-color:#FFF850";
                }
                if ($resvoz == "0") {
                    $colorvoz = "background-color:#6FCF97";
                }
                if ($resvoz != "0") {
                    $colorvoz = "background-color:#FFB366";
                }
                if ($resnaa == "0") {
                    $colornaa = "background-color:#6FCF97";
                }
                if ($resnaa != "0") {
                    $colornaa = "background-color:#E9C3FB";
                }
            }
            if ($_GET['orgn'] == "22") {
                if ($resgd != "0") {
                    $colordg = "background-color:#FFF850";
                }
                if ($resnap == "0") {
                    $colornap = "background-color:#6FCF97";
                }
                if ($resnap != "0") {
                    $colornap = "background-color:#E9C3FB";
                }
                if ($resotkl == "0") {
                    $colorotkl = "background-color:#6FCF97";
                }
                if ($resotkl != "0") {
                    $colorotkl = "background-color:#85D6D1";
                }
                if ($resprov == "0") {
                    $colorprov = "background-color:#6FCF97";
                }
                if ($resprov != "0") {
                    $colorprov = "background-color:#85D6D1";
                }
                if ($respos == "0") {
                    $colorpos = "background-color:#6FCF97";
                }
                if ($respos != "0") {
                    $colorpos = "background-color:#FFF850";
                }
                if ($reskkt == "0") {
                    $colorkkt = "background-color:#6FCF97";
                }
                if ($reskkt != "0") {
                    $colorkkt = "background-color:#FFB366";
                }
                if ($reskktk == "0") {
                    $colorkktk = "background-color:#6FCF97";
                }
                if ($reskktk != "0") {
                    $colorkktk = "background-color:#FFB366";
                }
                if ($resvie == "0") {
                    $colorvie = "background-color:#6FCF97";
                }
                if ($resvie != "0") {
                    $colorvie = "background-color:#85D6D1";
                }
                if ($resust == "0") {
                    $colorust = "background-color:#6FCF97";
                }
                if ($resust != "0") {
                    $colorust = "background-color:#85D6D1";
                }
                if ($resnaa == "0") {
                    $colornaa = "background-color:#6FCF97";
                }
                if ($resnaa != "0") {
                    $colornaa = "background-color:#E9C3FB";
                }
            }

        }
        if ($_GET['orgn'] == "12") {
            $summec = $resim + $rescher + $resotk + $resdol + $resop + $resgd + $resnap + $resotkl + $resprov + $respos + $reskkt + $reskktk + $resvie + $resust + $resvust + $respol + $respolo + $resvoz + $rescha + $resnaa + $resprou + $respere;
        }
        if ($_GET['orgn'] == "24") {
            $summec = $resim + $rescher + $resotk + $resdol + $resop + $resgd + $resnap + $resotkl + $resprov + $respos + $reskkt + $reskktk + $resvie + $resust + $resvoz + $resnaa + $respere;
        }
        if ($_GET['orgn'] == "22") {
            $summec = $resim + $rescher + $resotk + $resdol + $resop + $resgd + $resnap + $resotkl + $resprov + $respos + $reskkt + $reskktk + $resvie + $resust + $resnaa;
        }
//        echo "<script>console.log('Summec: " . $summec . "');</script>";
        ?>
        <tr>
            <td class="tdperiod" style=<? echo $color; ?>>
                <? echo $_GET['y']; ?>
                <p><? echo $_monthsList[$m]; ?> </p>
            </td>
            <td class="tdperiod" style=<? echo $color; ?>>
                <? echo $summec; ?>
            </td>
            <td class="tdperiod" style=<? echo $colordolop; ?>>
                <? echo $resdol; ?>
            </td>
            <!--Оплата!-->
            <td class="tdperiod" style=<? echo $colorop; ?>>
                <? echo $resop; ?>
            </td>
            <? if ($_GET['orgn'] == "12") { ?>
                <!----------!-->
                <!--Ждем доки!-->
                <td class="tdperiod" style=<? echo $colordg; ?>>
                    <? echo $resgd; ?>
                    <!----------!-->
                </td>
                <!--на проверк!-->
                <td class="tdperiod" style=<? echo $colornap; ?>>
                    <? echo $resnap; ?>
                </td>
                <!----------!-->
                <!--откл-н!-->
                <td class="tdperiod" style=<? echo $colorotkl; ?>>
                    <? echo $resotkl; ?>
                </td>
                <!----------!-->
                <!--пров-ен!-->
                <td class="tdperiod" style=<? echo $colorprov; ?>>
                    <? echo $resprov; ?>
                </td>
                <!----------!-->
                <!--Поставка!-->
                <td class="tdperiod" style=<? echo $colorpos; ?>>
                    <? echo $respos; ?>
                </td>
                <!----------!-->
                <!--ожидан. ккт!-->
                <td class="tdperiod" style=<? echo $colorkkt; ?>>
                    <? echo $reskkt; ?>
                </td>
                <!----------!-->
                <!--ожидан ккт  клиента!-->
                <td class="tdperiod" style=<? echo $colorkktk; ?>>
                    <? echo $reskktk; ?>
                </td>
                <!----------!-->
                <!--выезд!-->
                <td class="tdperiod" style=<? echo $colorvie; ?>>
                    <? echo $resvie; ?>
                </td>
                <!--устан. в офисе!-->
                <td class="tdperiod" style=<? echo $colorust; ?>>
                    <? echo $resust; ?>
                </td>
                <!----------!-->
                <!--выехали  на  установ!-->
                <td class="tdperiod" style=<? echo $colorvust; ?>>
                    <? echo $resvust; ?>
                </td>
                <!----------!-->
                <!--полу-ние в лк!-->
                <td class="tdperiod" style=<? echo $colorpol; ?>>
                    <? echo $respol; ?>
                </td>
                <!----------!-->
                <!--получ-ие в офисе!-->
                <td class="tdperiod" style=<? echo $colorpolo; ?>>
                    <? echo $respolo; ?>
                </td>
                <!----------!-->
                <!--возврат!-->
                <td class="tdperiod" style=<? echo $colorvoz; ?>>
                    <? echo $resvoz; ?>
                </td>
                <!----------!-->
                <!--частич-о  на отгрузке!-->
            <td class="tdperiod" style=<? echo $colorcha; ?>>
                <? echo $rescha; ?>
                </td><? } ?>
            <? if ($_GET['orgn'] == "24") { ?>
                <!----------!-->
                <!--Ждем доки!-->
                <td class="tdperiod" style=<? echo $colordg; ?>>
                    <? echo $resgd; ?>
                    <!----------!-->
                </td>
                <!--на проверк!-->
                <td class="tdperiod" style=<? echo $colornap; ?>>
                    <? echo $resnap; ?>
                </td>
                <!----------!-->
                <!--откл-н!-->
                <td class="tdperiod" style=<? echo $colorotkl; ?>>
                    <? echo $resotkl; ?>
                </td>
                <!----------!-->
                <!--пров-ен!-->
                <td class="tdperiod" style=<? echo $colorprov; ?>>
                    <? echo $resprov; ?>
                </td>
                <!----------!-->
                <!--Поставка!-->
                <td class="tdperiod" style=<? echo $colorpos; ?>>
                    <? echo $respos; ?>
                </td>
                <!----------!-->
                <!--ожидан. ккт!-->
                <td class="tdperiod" style=<? echo $colorkkt; ?>>
                    <? echo $reskkt; ?>
                </td>
                <!----------!-->
                <!--ожидан ккт клиента!-->
                <td class="tdperiod" style=<? echo $colorkktk; ?>>
                    <? echo $reskktk; ?>
                </td>
                <!----------!-->
                <!--выезд!-->
                <td class="tdperiod" style=<? echo $colorvie; ?>>
                    <? echo $resvie; ?>
                </td>
                <!--устан. в офисе!-->
                <td class="tdperiod" style=<? echo $colorust; ?>>
                    <? echo $resust; ?>
                </td>
                <!----------!-->
                <!--выехали на установ!-->
                <td class="tdperiod" style=<? echo $colorvoz; ?>>
                    <? echo $resvoz; ?>
                </td>
            <? } ?>
            <? if ($_GET['orgn'] == "22") { ?>
                <!----------!-->
                <!--Ждем доки!-->
                <td class="tdperiod" style=<? echo $colordg; ?>>
                    <? echo $resgd; ?>
                    <!----------!-->
                </td>
                <!--на проверк!-->
                <td class="tdperiod" style=<? echo $colornap; ?>>
                    <? echo $resnap; ?>
                </td>
                <!----------!-->
                <!--откл-н!-->
                <td class="tdperiod" style=<? echo $colorotkl; ?>>
                    <? echo $resotkl; ?>
                </td>
                <!----------!-->
                <!--пров-ен!-->
                <td class="tdperiod" style=<? echo $colorprov; ?>>
                    <? echo $resprov; ?>
                </td>
                <!----------!-->
                <!--Поставка!-->
                <td class="tdperiod" style=<? echo $colorpos; ?>>
                    <? echo $respos; ?>
                </td>
                <!----------!-->
                <!--ожидан. ккт!-->
                <td class="tdperiod" style=<? echo $colorkkt; ?>>
                    <? echo $reskkt; ?>
                </td>
                <!----------!-->
                <!--ожидан ккт клиента!-->
                <td class="tdperiod" style=<? echo $colorkktk; ?>>
                    <? echo $reskktk; ?>
                </td>
                <!----------!-->
                <!--выезд!-->
                <td class="tdperiod" style=<? echo $colorvie; ?>>
                    <? echo $resvie; ?>
                </td>
                <!--устан. в офисе!-->
                <td class="tdperiod" style=<? echo $colorust; ?>>
                    <? echo $resust; ?>
                </td>
                <!----------!-->
            <? } ?>
            <!----------!-->
            <!--на  отгрузке!-->
            <td class="tdperiod" style=<? echo $colornaa; ?>>
                <? echo $resnaa; ?>
            </td>
            <!----------!-->
            <!--произв-я  устан-ка!-->
            <? if ($_GET['orgn'] == "12") { ?>
                <td class="tdperiod" style=<? echo $colorprou; ?>>
                    <? echo $resprou; ?>
                </td>
            <? } ?>
            <!----------!-->
            <!--отгр-ен!-->
            <td class="tdperiod" style=<? echo $color; ?>>
                <? echo $resim; ?>
            </td>
            <!----------!-->
            <? if ($_GET['orgn'] != "22") { ?>
                <!--переплата!-->
                <td class="tdperiod" style=<? echo $colorpere; ?>>
                    <? echo $respere; ?>
                </td>
                <!----------!-->
            <? } ?>
            <!--отказ!-->
            <td class="tdperiod" style=<? echo $colorotk; ?>>
                <? echo $rescher; ?>
            </td>
            <!----------!-->
            <!--черн-ик!-->
            <td class="tdperiod" style=<? echo $colorcher; ?>>
                <? echo $resotk; ?>
            </td>
            <!----------!-->
            <!--закрыт месяц!-->
            <td class="tdperiod" style=<? echo $color; ?>>
                <?
                $_monthsListi = array(
                    "01" => "Январь", "02" => "Февраль", "03" => "Март",
                    "04" => "Апрель", "05" => "Май", "06" => "Июнь",
                    "07" => "Июль", "08" => "Август", "09" => "Сентябрь",
                    "10" => "Октябрь", "11" => "Ноябрь", "12" => "Декабрь");
                if ($zak != "") {
                    $rest = substr($zak, 0, 2);
                    $reste = substr($zak, 2, 4);
                    echo "20" . $rest;
                    echo '<p>' . $_monthsListi[$reste] . '</p>';
                }
                ?>
            </td>
            <!----------!-->
            <!--Долги  доки!-->
            <td class="tdperiod" style=<? echo $colordolo; ?>>
                <? echo $resdoljen; ?>
            </td>
            <!----------!-->
            <!--Долги  оплаты!-->
            <td class="tdperiod" style=<? echo $colordolo; ?>>
                <? echo $resdoljenop; ?>
            </td>
            <!----------!-->
            <? } ?>
        </tr>
        <tr>
            <?
            if ($_GET['orgn'] == "12") {
                $statusgdi = "schet.status='1'  AND ";
                $statusnapi = "schet.status='2'  AND ";
                $statusotkli = "schet.status='3'  AND ";
                $statusprovi = "schet.status='4'  AND ";
                $statusposi = "schet.status='5'  AND ";
                $statuskkti = "schet.status='6'  AND ";
                $statuskktki = "schet.status='7'  AND ";
                $statusviei = "schet.status='16'  AND ";
                $statususti = "schet.status='17'  AND ";
                $statusvusti = "schet.status='18'  AND ";
                $statuspoli = "schet.status='19'  AND ";
                $statuspoloi = "schet.status='20'  AND ";
                $statusvozi = "schet.status='23'  AND ";
                $statuschai = "schet.status='21'  AND ";
                $statusnaai = "schet.status='65'  AND ";
                $statusproui = "schet.status='161'  AND ";
                $statusperei = "schet.status='12354'  AND ";
            }
            if ($_GET['orgn'] == "24") {
                $statusgdi = "schet.status='44'  AND ";
                $statusnapi = "schet.status='45'  AND ";
                $statusotkli = "schet.status='47'  AND ";
                $statusprovi = "schet.status='48'  AND ";
                $statusposi = "schet.status='49'  AND ";
                $statuskkti = "schet.status='50'  AND ";
                $statuskktki = "schet.status='51'  AND ";
                $statusviei = "schet.status='52'  AND ";
                $statususti = "schet.status='53'  AND ";
                $statusvozi = "schet.status='12356'  AND ";
                $statusnaai = "schet.status='60'  AND ";
                $statusperei = "schet.status='12355'  AND ";
            }
            if ($_GET['orgn'] == "22") {
                $statusgdi = "schet.status='35'  AND ";
                $statusnapi = "schet.status='36'  AND ";
                $statusotkli = "schet.status='37'  AND ";
                $statusprovi = "schet.status='38'  AND ";
                $statusposi = "schet.status='39'  AND ";
                $statuskkti = "schet.status='40'  AND ";
                $statuskktki = "schet.status='41'  AND ";
                $statusviei = "schet.status='42'  AND ";
                $statususti = "schet.status='43'  AND ";
                $statusnaai = "schet.status='77'  AND ";
            }
            $ri = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' group by schet.rand");
            $resi = mysql_num_rows($ri);
            $rcheri = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'  and schet.cher='1'and schet.otk='0' group by schet.rand");
            $rescheri = mysql_num_rows($rcheri);
            $rotki = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'   and schet.otk='1' and schet.cher='0'group by schet.rand");
            $resotki = mysql_num_rows($rotki);
            $rdoljeni = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'  and  schet.del = '0' and schet.doljen = '1'  AND schet.akt = '1' group by schet.rand");
            $resdoljeni = mysql_num_rows($rdoljeni);
            $rdoljenopi = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'  and  schet.del = '0' and schet.doljenop = '1'  AND schet.akt = '1' group by schet.rand");
            $resdoljenopi = mysql_num_rows($rdoljenopi);
            $rdoli = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'  AND schet.oplachenks!= '1' and schet.oplachen != '1' AND schet.status=''  AND schet.gotov = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resdoli = mysql_num_rows($rdoli);
            $ropi = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' AND schet.oplachenks = '1' AND schet.status=''  AND schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resopi = mysql_num_rows($ropi);
            $rgdi = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' AND $statusgdi schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resgdi = mysql_num_rows($rgdi);
            $rnapi = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' AND $statusnapi schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resnapi = mysql_num_rows($rnapi);
            $rotkli = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' AND $statusotkli  schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resotkli = mysql_num_rows($rotkli);
            $rprovi = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' AND $statusprovi    schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resprovi = mysql_num_rows($rprovi);
            $rposi = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statusposi  schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resposi = mysql_num_rows($rposi);
            $rkkti = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statuskkti   schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $reskkti = mysql_num_rows($rkkti);
            $rkktki = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statuskktki  schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $reskktki = mysql_num_rows($rkktki);
            $rviei = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statusviei  schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resviei = mysql_num_rows($rviei);
            $rusti = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statususti schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resusti = mysql_num_rows($rusti);
            $rvusti = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statusvusti   schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resvusti = mysql_num_rows($rvusti);
            $rpoli = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'  and  schet.del = '0' and $statuspoli   schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $respoli = mysql_num_rows($rpoli);
            $rpoloi = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statuspoloi  schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $respoloi = mysql_num_rows($rpoloi);
            $rvozi = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'  and  schet.del = '0' and $statusvozi   schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resvozi = mysql_num_rows($rvozi);
            $rchai = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'  and  schet.del = '0' and $statuschai   schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $reschai = mysql_num_rows($rchai);
            $rnaai = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statusnaai   schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resnaai = mysql_num_rows($rnaai);
            $rproui = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "'  and  schet.del = '0' and $statusproui   schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resproui = mysql_num_rows($rproui);
            $rperei = mysql_query("SELECT 1 FROM `schet` WHERE produkt IN ($id_list) and schet.del!='1'and schet.y='" . $_GET['y'] . "' and  schet.del = '0' and $statusperei   schet.akt = '0'  AND schet.otk = '0' AND schet.cher = '0' group by schet.rand");
            $resperei = mysql_num_rows($rperei);
            $rsumm = mysql_query("SELECT schet.id,schet.ns,schet.rand,schet.shetold,schet.oplachenks,schet.url,schet.oplachen,schet.nomerschetks,DATE_FORMAT(schet.datezvon,'%d.%m.%Y') as date_zvon,schet.idkli,schet.gr,schet.ogrn,schet.lico,schet.datebron,schet.datezvon,schet.inn,schet.d,schet.m,schet.y,schet.kpp,schet.name as ogrn,produkti.name,schet.tipprod,schet.price,schet.priceks,users.f_name,users.l_name,users.o_name,kvobop.tip as yes,schet.status,schet.akt,schet.otk,schet.cher FROM `schet` WHERE produkt IN ($id_list) and  schet.del!='1'and schet.y='" . $_GET['y'] . "' group by schet.rand");
            while ($ressumm = mysql_fetch_assoc($rsumm))  :
                $summobs += $ressumm['price'];
                $summobks += $ressumm['priceks'];
                if ($ressumm['akt'] == "0" && $ressumm['cher'] == "0" && $ressumm['otk'] == "0") {

                    if ($ressumm['status'] == "" && $ressumm['oplachenks'] != "1") {
                        $summdols += $ressumm['price'];
                        $summdolks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "" && $ressumm['oplachenks'] == "1") {
                        $summops += $ressumm['price'];
                        $summopks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "1" || $ressumm['status'] == "44" || $ressumm['status'] == "35") {

                        $summgds += $ressumm['price'];
                        $summgdks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "2" || $ressumm['status'] == "45" || $ressumm['status'] == "36") {

                        $summnaps += $ressumm['price'];
                        $summnapks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "3" || $ressumm['status'] == "47" || $ressumm['status'] == "37") {

                        $summotkls += $ressumm['price'];
                        $summotklks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "4" || $ressumm['status'] == "48" || $ressumm['status'] == "38") {

                        $summprovs += $ressumm['price'];
                        $summprovks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "5" || $ressumm['status'] == "49" || $ressumm['status'] == "39") {

                        $summposs += $ressumm['price'];
                        $summposks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "6" || $ressumm['status'] == "50" || $ressumm['status'] == "40") {

                        $summkkts += $ressumm['price'];
                        $summkktvks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "7" || $ressumm['status'] == "51" || $ressumm['status'] == "41") {

                        $summkktks += $ressumm['price'];
                        $summvkktkks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "16" || $ressumm['status'] == "52" || $ressumm['status'] == "42") {
                        $summvies += $ressumm['price'];
                        $summvieks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "17" || $ressumm['status'] == "43" || $ressumm['status'] == "53") {

                        $summusts += $ressumm['price'];
                        $summustks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "18") {
                        $summvusts += $ressumm['price'];
                        $summvustks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "19") {
                        $summpols += $ressumm['price'];
                        $summpolks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "20") {
                        $summpolos += $ressumm['price'];
                        $summpoloks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "23" || $ressumm['status'] == "12356") {
                        $summvozs += $ressumm['price'];
                        $summvozks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "21") {
                        $summchas += $ressumm['price'];
                        $summchaks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "65" || $ressumm['status'] == "60" || $ressumm['status'] == "77") {
                        $summnaas += $ressumm['price'];
                        $summnaaks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "161") {
                        $summpusts += $ressumm['price'];
                        $summpustks += $ressumm['priceks'];
                    }
                    if ($ressumm['status'] == "12354" || $rescher['status'] == "12355") {
                        $summperes += $ressumm['price'];
                        $summpereks += $ressumm['priceks'];
                    }
                    if ($_GET['orgn'] == "12") {
                        $summvrabs = $summops + $summgds + $summnaps + $summotkls + $summprovs + $summposs + $summkkts + $summkktks + $summvies + $summusts + $summvusts + $summpols + $summpolos + $summchas + $summnaas + $summpusts;
                        $summvrabks = $summopks + $summgdks + $summnapks + $summotklks + $summprovks + $summposks + $summkktvks + $summvkktkks + $summvieks + $summustks + $summvustks + $summpolks + $summpoloks + $summchaks + $summnaaks + $summpustks;
                    }
                    if ($_GET['orgn'] == "24") {
                        $summvrabs = $summops + $summgds + $summnaps + $summotkls + $summprovs + $summposs + $summkkts + $summkktks + $summvies + $summusts + $summnaas;
                        $summvrabks = $summopks + $summgdks + $summnapks + $summotklks + $summprovks + $summposks + $summkktvks + $summvkktkks + $summvieks + $summustks + $summnaaks;
                    }
                    if ($_GET['orgn'] == "22") {
                        $summvrabs = $summops + $summgds + $summnaps + $summotkls + $summprovs + $summposs + $summkkts + $summkktks + $summvies + $summusts + $summnaas;
                        $summvrabks = $summopks + $summgdks + $summnapks + $summotklks + $summprovks + $summposks + $summkktvks + $summvkktkks + $summvieks + $summustks + $summnaaks;
                    }
                }
                if ($ressumm['akt'] == '1') {
                    $summakts += $ressumm['price'];
                    $summaktks += $ressumm['priceks'];
                }
                if ($ressumm['akt'] == '1' && $ressumm['doljen'] == '1') {
                    $summdoljens += $ressumm['price'];
                    $summdoljenks += $ressumm['priceks'];
                }
                if ($ressumm['akt'] == '1' && $ressumm['doljenop'] == '1') {
                    $summdoljenops += $ressumm['price'];
                    $summdoljenopks += $ressumm['priceks'];
                }
                if ($ressumm['otk'] == '1' && $ressumm['cher'] == '0') {
                    $summchers += $ressumm['price'];
                    $summcherks += $ressumm['priceks'];
                }
                if ($ressumm['otk'] == '0' && $ressumm['cher'] == '1') {
                    $summotks += $ressumm['price'];
                    $summaotkks += $ressumm['priceks'];
                }
            endwhile;
            ?>
            <td class="tdperiodi">
                <? echo $_GET['y']; ?>
                <p>Итого</p>
            </td>
            <td class="tdperiodi">
                <? echo $resi; ?>
            </td>
            <td class="tdperiodi">
                <? echo $resdoli; ?>
            </td>
            <td class="tdperiodi">
                <? echo $resopi; ?>
            </td>
            <? if ($_GET['orgn'] == "12") { ?>
                <td class="tdperiodi">
                    <? echo $resgdi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resnapi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resotkli; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resprovi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resposi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $reskkti; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $reskktki; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resviei; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resusti; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resvusti; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $respoli; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $respoloi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resvozi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $reschai; ?>
                </td>
            <? } ?>
            <? if ($_GET['orgn'] == "24") { ?>
                <td class="tdperiodi">
                    <? echo $resgdi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resnapi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resotkli; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resprovi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resposi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $reskkti; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $reskktki; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resviei; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resusti; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resvoz; ?>
                </td>
            <? } ?>
            <? if ($_GET['orgn'] == "22") { ?>
                <td class="tdperiodi">
                    <? echo $resgdi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resnapi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resotkli; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resprovi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resposi; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $reskkti; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $reskktki; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resviei; ?>
                </td>
                <td class="tdperiodi">
                    <? echo $resusti; ?>
                </td>
            <? } ?>
            <td class="tdperiodi">
                <? echo $resnaai; ?>
            </td>
            <? if ($_GET['orgn'] == "12") { ?>
                <td class="tdperiodi">
                    <? echo $resproui; ?>
                </td>
            <? } ?>
            <td class="tdperiodi">
                <? echo $kolot; ?>
            </td> <? if ($_GET['orgn'] != "22") { ?>
                <td class="tdperiodi">
                    <? echo $resperei; ?>
                </td>
            <? } ?>
            <td class="tdperiodi">
                <? echo $rescheri; ?>
            </td>
            <td class="tdperiodi">
                <? echo $resotki; ?>
            </td>
            <td class="tdperiodi">
                <? echo $kolzak; ?>
            </td>
            <td class="tdperiodi">
                <? echo $resdoljeni; ?>
            </td>
            <td class="tdperiodi">
                <? echo $resdoljenopi; ?>
            </td>
        </tr>
        <tr>
            <? if ($_GET['orgn'] == "12") { ?>
                <td class="tdperiod" style="background-color:white;border:none;">
                </td>
                <td class="tdperiod" style="background-color:white;border:none;">
                </td>
                <td class="tdperiod" style="background-color:#78AFD8;">
                    Аня
                </td>
                <td class="tdperiod" style="background-color:#FFF850;" colspan="5">
                    Антон
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;">
                    Кристина
                </td>
                <td class="tdperiod" style="background-color:#85D6D1;" colspan="2">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#FFF850;">
                    Антон
                </td>
                <td class="tdperiod" style="background-color:#FFB366;" colspan="2">
                    Сергей Л
                </td>
                <td class="tdperiod" style="background-color:#FFF850;" colspan="2">
                    Антон
                </td>
                <td class="tdperiod" style="background-color:#E76D74;">
                    Аня
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;" colspan="2">
                    Кристина
                </td>
                <td class="tdperiod" style="background-color:#FFB366;">
                    Сергей Л
                </td>
                <td class="tdperiod" style="background-color:#90BEA3;">
                </td>
                <td class="tdperiod" style="background-color:#A0D7FF;">
                    Аня
                </td>
                <td class="tdperiod" style="background-color:#FB9C9C;">
                </td>
                <td class="tdperiod" style="background-color:#BC9B79;">
                </td>
                <td class="tdperiod" style="background-color:#85D6A7;">
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;" colspan="2">
                    Кристина
                </td>
            <? } ?>
            <? if ($_GET['orgn'] == "24") { ?>
                <td class="tdperiod" style="background-color:white;border:none;">
                </td>
                <td class="tdperiod" style="background-color:white;border:none;">
                </td>
                <td class="tdperiod" style="background-color:#78AFD8;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#FFF850;" colspan="2">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#85D6D1;" colspan="2">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#FFF850;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#FFB366;" colspan="2">
                    Сергей Л
                </td>
                <td class="tdperiod" style="background-color:#FFF850;" colspan="2">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#E76D74;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#90BEA3;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#A0D7FF;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#FB9C9C;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#BC9B79;">
                    Иван
                </td>
                <td class="tdperiod" style="background-color:#85D6A7;">
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;" colspan="2">
                    Иван
                </td>
            <? } ?>
            <? if ($_GET['orgn'] == "22") { ?>
                <td class="tdperiod" style="background-color:white;border:none;">
                </td>
                <td class="tdperiod" style="background-color:white;border:none;">
                </td>
                <td class="tdperiod" style="background-color:#78AFD8;">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#FFF850;" colspan="2">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#85D6D1;" colspan="2">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#FFF850;">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#FFB366;" colspan="2">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#85D6D1;" colspan="2">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#90BEA3;">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#FB9C9C;">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#BC9B79;">
                    Роман
                </td>
                <td class="tdperiod" style="background-color:#85D6A7;">
                </td>
                <td class="tdperiod" style="background-color:#E9C3FB;" colspan="2">
                    Роман
                </td>
            <? } ?>
        </tr>
        </tbody>
    </table>
</div>
<div style="
width: 100%;
    text-align: center;
    font-size: 20pt;
	margin-top: 20px;
margin-bottom: 20px;
">
    <? echo "Общий график за " . $_GET['y'] . " год"; ?>
    <div style="
    width: 1000px;
    margin: 0 auto;
    margin-top: 20px;
    margin-bottom: 20px;
	height: 400px;
">
        <table style="
    float: left;position: absolute;
">
            <thead>
            <tr>
                <th class="thobsh"><? echo $_GET['y']; ?></th>
                <th class="thobsh">шт</th>
                <th class="thobsh">%</th>
                <th class="thobsh">С.руб</th>
                <th class="thobsh">К.руб</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="tdobsh" style="background-color:#78AFD8;">неоплачен</td>
                <td class="tdobsh"><? echo $resdoli; ?></td>
                <td class="tdobsh" id="procdol"><? $procdol = ($resdoli * 100) / $resi;
                    echo round($procdol, 1) . "%" ?></td>
                <td class="tdobsh"><? echo $summdols; ?> руб</td>
                <td class="tdobsh"><? echo $summdolks; ?> руб</td>
            </tr>
            <tr>
                <td class="tdobsh" style="background-color:#FFF850;">в работе</td>
                <td class="tdobsh">
                    <?
                    if ($_GET['orgn'] == "12") {
                        $vrab = $resopi + $resgdi + $resnapi + $resotkli + $resprovi + $resposi + $reskkti + $reskktki + $resviei + $resusti + $resvusti + $respoli + $respoloi + $reschai + $resnaai + $resproui;
                    }
                    if ($_GET['orgn'] == "24") {
                        $vrab = $resopi + $resgdi + $resnapi + $resotkli + $resprovi + $resposi + $reskkti + $reskktki + $resviei + $resusti + $resnaai;
                    }
                    if ($_GET['orgn'] == "22") {
                        $vrab = $resopi + $resgdi + $resnapi + $resotkli + $resprovi + $resposi + $reskkti + $reskktki + $resviei + $resusti + $resnaai;
                    }
                    echo $vrab;
                    ?>
                </td>
                <td class="tdobsh"><? $procvrab = ($vrab * 100) / $resi;
                    echo round($procvrab, 1) . "%" ?></td>
                <td class="tdobsh"><? echo $summvrabs; ?> руб</td>
                <td class="tdobsh"><? echo $summvrabks; ?> руб</td>
            </tr>
            <tr>
                <td class="tdobsh" style="background-color:#85D6A7;">отгружен</td>
                <td class="tdobsh"><? echo $kolot; ?></td>
                <td class="tdobsh"><? $prockolot = ($kolot * 100) / $resi;
                    echo round($prockolot, 1) . "%" ?></td>
                <td class="tdobsh"><? echo $summakts; ?> руб</td>
                <td class="tdobsh"><? echo $summaktks; ?> руб</td>
            </tr>
            <tr>
                <td class="tdobsh" style="background-color:#FB9C9C;">отказ</td>
                <td class="tdobsh"><? echo $rescheri; ?></td>
                <td class="tdobsh"><? $procotk = ($rescheri * 100) / $resi;
                    echo round($procotk, 1) . "%" ?></td>
                <td class="tdobsh"><? echo $summotks ?> руб</td>
                <td class="tdobsh"><? echo $summaotkks ?> руб</td>
            </tr>
            <tr>
                <td class="tdobsh" style="background-color:#BC9B79;">черновик</td>
                <td class="tdobsh"><? echo $resotki; ?></td>
                <td class="tdobsh"><? $proccher = ($resotki * 100) / $resi;
                    echo round($proccher, 1) . "%" ?></td>
                <td class="tdobsh"><? echo $summchers ?> руб</td>
                <td class="tdobsh"><? echo $summcherks ?> руб</td>
            </tr>
            <? if ($_GET['orgn'] != "22") {
                ?>
                <tr>
                    <td class="tdobsh" style="background-color:#E76D74;">возврат</td>
                    <td class="tdobsh"><? echo $resvozi; ?></td>
                    <td class="tdobsh"><? $procvozi = ($resvozi * 100) / $resi;
                        echo round($procvozi, 1) . "%" ?></td>
                    <td class="tdobsh">руб</td>
                    <td class="tdobsh">руб</td>
                </tr>
                <tr>
                <td class="tdobsh" style="background-color:#A0D7FF;">переплата</td>
                <td class="tdobsh"><? echo $resperei; ?></td>
                <td class="tdobsh"><? $procpere = ($resperei * 100) / $resi;
                    echo round($procpere, 1) . "%" ?></td>
                <td class="tdobsh">руб</td>
                <td class="tdobsh">руб</td>
                </tr><? } ?>
            <tr>
                <td class="tdobsh" style="background-color:white;">итого</td>
                <td class="tdobsh"><? echo $resi; ?></td>
                <td class="tdobsh"><? $procob = ($resi * 100) / $resi;
                    echo round($procob, 1) . "%" ?></td>
                <td class="tdobsh"><? echo $summobs; ?> руб</td>
                <td class="tdobsh"><? echo $summobks; ?> руб</td>
            </tr>
            <tr>
                <td class="tdobsh" style="background-color:#E9C3FB;">долги</td>
                <td class="tdobsh">
                    <? echo $resdoljeni + $resdoljenopi; ?>
                <td class="tdobsh"><? $procob = (($resdoljeni + $resdoljenopi) * 100) / $resi;
                    echo round($procob, 1) . "%" ?></td>
                <td class="tdobsh"><? echo $summdoljens + $summdoljenks ?> руб</td>
                <td class="tdobsh"><? echo $summdoljenops + $summdoljenopks ?> руб</td>
                </td>
            </tr>
            </tbody>
        </table>
        <div id="diag" style="
    width: 60%;
    float: right;
	bottom: 50px;
    position: relative;
">
            <canvas id="myChart<? echo $_GET['y']; ?>" width="60" height="40"></canvas>
        </div>
    </div>
</div>
<div style="
width: 100%;
    text-align: center;
    font-size: 17pt;
	margin-top: 20px;
margin-bottom: 20px;
">
    <? echo "График за " . $_GET['y'] . " год"; ?>
    <select id="showgrafic<? echo $_GET['y']; ?>" style="
    margin-left: 20px;
">
        <option selected></option>
        <option value="all">всего счетов</option>
        <option value="dol">без оплаты</option>
        <option value="opl">оплата</option>
        <? if ($_GET['orgn'] == "0") {
            $allogrng = '';
        } else {
            $allogrng = "uslugi='" . $_GET['orgn'] . "' and ";
        }
        ?> ?>
        <?php $rs = mysql_query("SELECT * from status where $allogrng del!='1'");
        while ($ress = mysql_fetch_assoc($rs))  : ?>
            <option value="<? echo $ress['id']; ?>"><? echo $ress['name']; ?></option>
        <?php endwhile; ?>
        <option value="atk">отгр-ен</option>
        <option value="otk">отказ</option>
        <option value="cher">черн-ик</option>
    </select>
    <div id="grafick<? echo $_GET['y']; ?>" style="
width: 100%;
	margin-top: 20px;
margin-bottom: 20px;
">
    </div>
</div>
<div style="
width: 100%;
    text-align: center;
    font-size: 17pt;
margin-bottom: 20px;
">
    <? echo "Не обработанные счета за " . $_GET['y'] . " год";
    $_monthsListi = array(
        "01" => "Январь", "02" => "Февраль", "03" => "Март",
        "04" => "Апрель", "05" => "Май", "06" => "Июнь",
        "07" => "Июль", "08" => "Август", "09" => "Сентябрь",
        "10" => "Октябрь", "11" => "Ноябрь", "12" => "Декабрь");
    ?>
    <select id="neob<? echo $_GET['y']; ?>" style="
    margin-left: 20px;
">
        <option selected></option>
        <? for ($i = 1; $i <= count($monlist); $i++) {
            if ($i < 10) {
                $month = "0" . $i;
            } else {
                $month = $i;
            }
            ?>
            <option value="<? echo $month; ?>"><? echo $_monthsListi[$month]; ?></option>

        <? } ?>
    </select>
    <div style="
width: 100%;
margin-top: 20px;
margin-bottom: 20px;
" id="neobs<? echo $_GET['y']; ?>">
    </div>
</div>
<script>
    var ctx = document.getElementById('myChart<?echo $_GET['y'];?>').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: [<?echo round($procdol, 1);?>, <?echo round($procvrab, 1);?>, <?echo round($prockolot, 1);?>, <?echo round($procotk, 1);?>, <?echo round($proccher, 1);?>,<?echo round($procvozi, 1);?>,<?echo round($procpere, 1);?>],
                backgroundColor: ['#78AFD8', '#FFF850', '#85D6A7', '#FB9C9C', '#BC9B79', '#E76D74', '#A0D7FF'],
                borderWidth: 0.5,
                borderColor: '#ddd'
            }]
        },
        options: {
            title: {
                display: true,
                position: 'top',
                fontSize: 16,
                fontColor: '#111',
                padding: 20
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    boxWidth: 20,
                    fontColor: '#111',
                    padding: 15
                }
            },
            tooltips: {
                enabled: false
            },
            plugins: {
                datalabels: {
                    color: '#111',
                    textAlign: 'center',
                    font: {
                        lineHeight: 1.6
                    },
                    formatter: function (value, ctx) {
                        return ctx.chart.data.labels[ctx.dataIndex] + '\n' + value + '%';
                    }
                }
            }
        }
    });
    $("#showgrafic<?echo $_GET['y'];?>").change(function () {
        tip = document.getElementById('showgrafic<?echo $_GET['y'];?>').value;
        document.getElementById('modal-shadowkube').style.display = "block";
        document.getElementById('kube').style.display = "block";
        $.ajax({
            type: "GET",
            url: "periodgrafick.php",
            data: "idg=<?echo $_GET['id'];?>&orgng=<?echo $_GET['orgn'];?>&yg=<?echo $_GET['y'];?>&status=" + tip + "",
            success: function (html) {
                $("#grafick<?echo $_GET['y'];?>").html(html);
                document.getElementById('modal-shadowkube').style.display = "none";
                document.getElementById('kube').style.display = "none";
            }
        });
    });
    $("#neob<?echo $_GET['y'];?>").change(function () {
        month = document.getElementById("neob<?echo $_GET['y'];?>").value;
        tip = document.getElementById('showgrafic<?echo $_GET['y'];?>').value;
        document.getElementById('modal-shadowkube').style.display = "block";
        document.getElementById('kube').style.display = "block";
        $.ajax({
            type: "GET",
            url: "neobchet.php",
            data: "idg=<?echo $_GET['id'];?>&orgnn=<?echo $_GET['orgn'];?>&yn=<?echo $_GET['y'];?>&tip=" + tip + "&mothn=" + month + "",
            success: function (html) {
                $("#neobs<?echo $_GET['y'];?>").html(html);
                document.getElementById('modal-shadowkube').style.display = "none";
                document.getElementById('kube').style.display = "none";
            }
        });
    });
</script>
