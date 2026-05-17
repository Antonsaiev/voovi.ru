<?

if(substr_count($userdata['dotdel'], $row['otdel']) == 1){

    echo '<tr for="raz'.$row['rand'].'"';
    if ($row['akt'] == 1){
        echo 'class="alert alert-success" role="alert"';
    } if ($row['otk'] == 1){
        echo 'class="alert alert-gavno" role="alert"';
    }
    if ($row['cher'] == 1){
        echo 'class="alert alert-cher" role="alert"';
    }

    if ($row['oplachenks'] == 1 || $row['oplachen'] == 1){
        echo 'class="alert alert-warning" role="alert"';
    }if ($row['otl3'] != 0){
        echo 'class="alert alert-info" role="alert"';
    }

    $qdsafesd = mysql_query("SELECT SUM(turbo) FROM schet WHERE del = '0' AND turbo = '1' AND rand ='".$row['rand']."' GROUP BY rand");
    $pedfsbfedb = mysql_result($qdsafesd, 0);
    if ($pedfsbfedb >= 1){
        echo ' style="border: 2px solid red; font-size: 14px;"';
    }
    echo '><td style="width: 2px;text-align: center;cursor: pointer; font-size: 14px;"> ';
    echo '<input type="checkbox" name="id[]" id="raz'.$row['rand'].'"  value="'.$row['rand'].'">';
    echo ' </td>';
    echo '<td id="svyaz'.$row['gr'].'" style=" font-size: 14px; width: 1px;text-align: center; background: '.$row['groupi'].';">';

    echo $iz++;
    echo'
<script type="text/javascript">$("#svyaz'.$row['gr'].'").live("dblclick", function() {document.location.href = "/toha.php?name=&inn=&kpp=&groupi='.$row['gr'].'";});</script>
';
    echo '</td>';


    echo '<td style="text-align: left;width: 4%; font-size: 14px;" >';

    if($row['nomerschet'] != 'В КС'){
        //echo '<span class="glyphicon glyphicon-bookmark" aria-hidden="true"  style="display:initial;" ></span>',$row['god'],$row['kto'],$row['otdel'],$row['kolichschet'].'<br>';
        echo '<a style="font-size: 14px;" href="' . VOOVI_DOC_URL . '/different/diadoc?ns=' . $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'] . '"target="_blank">' . $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'] . '</a><br>';
        if($row['ns']=='0'){
            mysql_query("UPDATE schet SET ns='".$row['god'].$row['kto'].$row['otdel'].$row['kolichschet']."' WHERE rand =".$row['rand']);
        }
    }else{

    }
    if(!empty($row['nomerschetks'])){
        //echo $row['nomerschetks'];
        $nomerschetks = array("№", " ", "С", "ч", "е", "т");
        echo '<span class="glyphicon glyphicon-barcode" aria-hidden="true" style="display: initial;"></span>',str_replace($nomerschetks, "", $row['nomerschetks']);
    }else{
        echo 'Забыли';
    }
    echo '</td>';
    echo '<td id="date'.$row['rand'].'" style="text-align: center; width: 4%; font-size: 14px;">';
    $date = new DateTime(getDateDocuments($row['rand'])['d_bill']);
//echo $row['d'].'.'.$row['m'].'.'.$row['y'];
    echo $date->format('d.m.Y');




    echo '</td>';

    echo '<td style=" width: 4%; font-size: 14px;">';
    echo $row['inn'];
    echo '</td>';
    echo '<td  style="font-size: 14px;" >';
    echo $row['kpp'];
    echo '</td>';
    echo '<td onclick="name'.$row['rand'].'()" style="text-align: center; font-size: 14px; width: 10%;" ';
    if ($row['gotov'] > 0){
        echo 'class="alert alert-success" role="alert"';
    }
    echo '>';

    $ns_for_link = $row['ns'];
    if (($row['ns']) == '0') {
        $ns_for_link = $row['god'].$row['kto'].$row['otdel'].$row['kolichschet'];
    }

    echo '<a style="font-size: 14px;" href="' . VOOVI_DOC_URL . '/payments/SearchBill?n_schet=' . $ns_for_link . '">' . $row['name'] . '</a>';



//if($row['kto'] == $userdata['users_id']{
    echo '<script type="text/javascript">
function name'.$row['rand'].'()
{
    var c = document.getElementById("containame'.$row['rand'].'");
    var d = document.createElement("iframe");
    var t = document.createTextNode("11111");
    d.appendChild(t);
    c.appendChild(d);
	d.src = "setschet.php?id='.$row['rand'].'&p=0&kli='.$row['idkli'].'&lico='.$row['lico'].'&gr='.$row['gr'].'&us='. $userdata['users_id'].'";
	d.width = document.documentElement.clientWidth - document.documentElement.clientWidth / 15;
	d.height = document.documentElement.clientHeight+20;
	d.className = "iframestylediv"; 
	d.Name = "f2";
	document.getElementById("containame'.$row['rand'].'").className = "contaidiv";
	d.style.minWidth = "992px";
}
$(document).ready(function(){
    $("#containame'.$row['rand'].'").click(function(){
        $("#containame'.$row['rand'].'").empty();
		document.getElementById("containame'.$row['rand'].'").className = "";
    });
});


</script>
';
//}

    echo '</td>';
    echo '<td style="text-align: center; font-size: 14px;"><div id="none-containame'.$row['rand'].'"></div>';
    $rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
    $resultrpod = mysql_query($rpod);
    $personrpod = mysql_fetch_array($resultrpod);

//echo $personrpod['name'];
    echo '<a style="font-size: 14px;" href="' . VOOVI_DOC_URL . '/payments/EditTarifSchet?n_schet=' . $ns_for_link . '">' . $personrpod['name'] . '</a>';
    if($userdata['users_id'] == 1){
        echo '/'.$row['produkt'].'/'.$udospersonrpod['parent'];
    }
    echo '</td>';



    echo '<td id="komment'.$row['rand'].'"';

    $rpissetkomment = "SELECT * FROM schetoldkomment WHERE schet ='".$row['rand']."' ORDER BY id DESC";
    $reissetkomment = mysql_query($rpissetkomment);
    $peissetkomment = mysql_fetch_array($reissetkomment);
    $issetkomment = mysql_query("SELECT COUNT(*) FROM schetoldkomment WHERE schet ='".$row['rand']."'");
    $yesisset = mysql_result($issetkomment, 0);
    if ($row['doljen'] == 1 && $row['postprod'] == 0){echo 'class="alert alert-danger" role="alert"';}
    if ($row['postprod'] == 1 && $row['doljen'] == 0 && $row['doljenop'] == 0 ){echo 'class="alert alert-prod" role="alert"';}
    if ($row['ust_sert'] == 1 && $row['doljen'] == 0 && $row['doljenop'] == 0 ){echo 'class="alert alert-danger" style="background:lightseagreen"';}
    if ($row['krossprod'] == 1 && $row['doljen'] == 0 && $row['doljenop'] == 0){echo 'class="alert alert-danger" style="background:teal"';}
    if ($row['prodplus'] == 1 && $row['doljen'] == 0 && $row['doljenop'] == 0 && $row['postprod'] == 0){echo 'class="alert alert-danger" style="background:darkorange"';}
    if ($row['incoming'] == 1 && $row['doljen'] == 0 && $row['doljenop'] == 0 ){echo 'class="alert alert-danger" style="background:lightblue"';}
    if ($row['doljenop'] == 1&& $row['postprod'] == 0){echo 'style=" background: #ff3232; color: #000000; "';}
    echo '>';
//-----------------------------------
    echo '<div class="infoblock" style="    background: #dddddd;
    padding: 3px 4px;
    margin: 0 -3px;
    margin-bottom: 3px;">';
    $lgeneracq = "SELECT f_name, l_name FROM users WHERE users_id =".$row['generac'];
    $rgeneracq = mysql_query($lgeneracq);
    $pgeneracq = mysql_fetch_array($rgeneracq);
    if($row['generac']!=546321564){

        if($row['generac']>=1){


            echo '<b style="color: #008010;">';
            echo "Вып.: ";
            $gen = $pgeneracq['f_name'];
            echo mb_substr($gen,0,1,'UTF-8'),'. ';
            echo $pgeneracq['l_name']." | ";
            echo '</b>';


        }else{
            $qdsafsdq = mysql_query("SELECT SUM(kvo) FROM schet WHERE del = '0' AND gen = '1' AND rand ='".$row['rand']."'  GROUP BY rand");
            $pedfsbfdbq = mysql_result($qdsafsdq, 0);
            if ($pedfsbfdbq >= 1){
                echo '<b style="color: #D20000;">';
                echo "Ген. ".$pedfsbfdbq."";
                echo '</b>';
            }
        }
    }else{
        echo"Поставка";
    }

    $query221 = mysql_query("SELECT * from schet WHERE rand = '".$row['rand']."' ");
    while($row221 = mysql_fetch_array($query221)) {
//substr_count($row221[''],'l');
        $rpod = "SELECT * FROM tarif WHERE id =" . $row221['prod'];
        $resultrpod = mysql_query($rpod);
        $personrpod = mysql_fetch_array($resultrpod);
        if ($personrpod['turbo'] == 1) {
            $sql = mysql_query("UPDATE `schet` SET `turbo`='1'  WHERE  `rand` = '" . $row['rand'] . "'");
            if ($row221['pisu'] == "0") {
                for ($pis = 1; $pis <= 2; $pis++) {
//                if($pis==1){
//                    $tema = "Счет с услугой ускоренного выпуска " . $row221['name'] . " работа с оплатой Коневец Кристина Сергеевна";
//                }
                    if($pis==2 ) {
                        $tema = "Счет с услугой ускоренного выпуска " . $row221['name'] . " начните работу с документами";
                    }
                    $to = "infosavoir@ya.ru";
                    $subject = $tema;
                    $charset = "utf-8";
                    $headerss = "Content-type: text/html; charset=$charset\r\n";
                    $headerss .= "MIME-Version: 1.0\r\n";
                    $headerss .= "Date: " . date('D, d M Y h:i:s O') . "\r\n";
                    $headers .= "From:it.savoir<it.savoir@yandex.ru>\r\n";
                    $headers .= "Reply-To:it.savoir@yandex.ru\r\n";
                    $msg =" 
<html>
<head>
</head>
<body>
    <p>Выставлен счет номер " . $row221['ns'] . "</p>
    <p> Организации: " . $row221['name'] . ", ИНН: " . $row221['inn'] . "</p>
    <p> Где есть услуга ускоренного выпуска." . "</p>
    <p><a href='" . VOOVI_MAIN_URL . "/kartklient.php?id=" . $row221['idkli'] . "'> Ссылка на организацию: " . VOOVI_MAIN_URL . "/kartklient.php?id=" . $row221['idkli'] . "</a></p>
</body>
</html>
" . " Это сообщение сформировано автоматически.";
                    mail($to, $subject, $msg, $headerss);
                }
                $sql = mysql_query("UPDATE schet SET `pisu`='1' WHERE `rand` = '" . $row['rand'] . "'");
            }
        }
        if (substr_count($personrpod['name'], 'Выезд') != 0) {
            echo ' | Выезд: ' . $row221['datacar'];
        }
        if (substr_count($personrpod['name'], 'Ускоренный') != 0) {
            echo ' | Ускоренный: ' . $row221['datacar'];
            $sql = mysql_query("UPDATE tarif SET turbo='1' WHERE id = '" . $row221['prod'] . "'");

        }
    }
    if ($row['goroddd'] != 0){
        echo ' | Выезд: '.$row221['datacar'];
    }

    $result42 = "SELECT * from schet_status WHERE schet='$row[rand]' ORDER BY id DESC ";
    $results2 = mysql_query($result42);
    $persons2 = mysql_fetch_array($results2);
    $lis3 = "SELECT * FROM status WHERE id ='".$persons2['status']."' ";
    $resultlis3 = mysql_query($lis3);
    $personlis3 = mysql_fetch_array($resultlis3);

    if(!empty($personlis3['name'])){
        echo " | ".$personlis3['name']." c ".$persons2['data'];
    }

//    echo "<script>console.log('Row:', " . json_encode($row) . ");</script>";
//    echo "<script>console.log('Row status:', " . json_encode($row['status']) . ");</script>";
//    echo "<script>console.log('Persons2 status:', " . json_encode($persons2['status']) . ");</script>";
//    echo "<script>console.log('Personlis3 name:', " . json_encode($personlis3['name']) . ");</script>";
//    echo "<script>console.log('gettype ns:', " . json_encode(gettype((string)$row['ns'])) . ");</script>";
//    echo "<script>console.log('ns:', " . json_encode((string)$row['ns']) . ");</script>";

    if (isset($row['status'], $persons2['status']) && $row['status'] != $persons2['status']) {
        include_once 'send_mail.php';
//        echo "<script>console.log('sendErrorStatusSchet');</script>";
        sendErrorStatusSchet(json_encode((string)$row['ns']), $row['status'], $persons2['status']);
    }

//    echo "<script>console.log('OK');</script>";

// if ($row['akt'] == 1){
// echo 'Акт отгружен';
// } else if ($row['gotov'] > 0){
// echo 'Готов к отгрузке';
// } else
    if ($row['oplachenks'] == 1 || $row['oplachen'] == 1){
    }else{
        echo ' | Ждем оплату';
    }



    $qdsafsd = mysql_query("SELECT SUM(turbo) FROM schet WHERE del = '0' AND turbo = '1' AND rand ='".$row['rand']."' GROUP BY rand");
    $pedfsbfdb = mysql_result($qdsafsd, 0);
    if ($pedfsbfdb >= 1){
        echo ' | Ускоренный выпуск<img src="/upload/image/acceleratedbig.png">';
    }


    if($row['nomerschet'] != 'В КС'){
        $ptarifio = mysql_fetch_array(mysql_query("SELECT * from tarif WHERE id='".$row['prod']."'"));
        if($ptarifio['install']==1){
            $install = "UPDATE `schet` SET `install`='1' WHERE rand = '".$row['rand']."'";
            mysql_query($install) or die(mysql_error($link));
            if($row['install']=='1'){
                echo ' | Уст/Наст';
            }
        }
    }
    if(!empty($row['akt_date'])){
        echo ' | <b> 20'.substr($row['akt_date'], 0, 2).'г. ';
        if(substr($row['akt_date'], 2, 4)=='01'){
            echo 'Январь';
        }if(substr($row['akt_date'], 2, 4)=='02'){
            echo 'Февраль';
        }if(substr($row['akt_date'], 2, 4)=='03'){
            echo 'Март';
        }if(substr($row['akt_date'], 2, 4)=='04'){
            echo 'Апрель';
        }if(substr($row['akt_date'], 2, 4)=='05'){
            echo 'Май';
        }if(substr($row['akt_date'], 2, 4)=='06'){
            echo 'Июнь';
        }if(substr($row['akt_date'], 2, 4)=='07'){
            echo 'Июль';
        }if(substr($row['akt_date'], 2, 4)=='08'){
            echo 'Август';
        }if(substr($row['akt_date'], 2, 4)=='09'){
            echo 'Сентябрь';
        }if(substr($row['akt_date'], 2, 4)=='10'){
            echo 'Октябрь';
        }if(substr($row['akt_date'], 2, 4)=='11'){
            echo 'Ноябрь';
        }if(substr($row['akt_date'], 2, 4)=='12'){
            echo 'Декабрь';
        }
        echo '</b>';
    }
    echo '</div>';
//-----------------------------------

    echo '<div id="refresh'.$row['rand'].'" style="text-align: left; font-size: 14px;" title=" ' . strip_tags($peissetkomment['komment']) . '" >';
    $zktolgenerac = "SELECT * FROM users WHERE users_id =".$row['kto'];
    $zktorgenerac = mysql_query($zktolgenerac);
    $zktopgenerac = mysql_fetch_array($zktorgenerac);
    $zkto = $zktopgenerac['f_name'];

    if($yesisset > 0){
        if(mb_strlen($peissetkomment['komment']) >= 300){
            $string = $peissetkomment['komment'];
            $string = strip_tags($string);
            $string = substr($string, 0, 300);
            $string = rtrim($string, "!,.-");
            $string = substr($string, 0, strrpos($string, ' '));
            echo $string."… ";
        }else{
            echo '<p style="float: left;margin-right: 6px; font-weight: bold; font-size: 14px;">'.$peissetkomment['data'].' / '.mb_substr($zkto,0,1,'UTF-8').'. '.$zktopgenerac['l_name'].' </p> '.$peissetkomment['komment'];
        }
    }else{
        if(mb_strlen($row['koment']) >= 300){
            $string = $row['koment'];
            $string = strip_tags($string);
            $string = substr($string, 0, 300);
            $string = rtrim($string, "!,.-");
            $string = substr($string, 0, strrpos($string, ' '));
            echo $string."… ";
        }else{
            echo $row['komment'];
        }
    }
    /*


    */
    echo"</div>";

    // Куда возвращаться после закрытия окна комментария.
    // По умолчанию — на текущий URL ($_SERVER['REQUEST_URI']),
    // но вызывающий код может передать GET-параметр return с закодированным URL.
    $kommentCloseUrl = $_SERVER['REQUEST_URI'];
    if (!empty($_GET['return'])) {
        $kommentCloseUrl = urldecode($_GET['return']);
    }

    echo'
<script type="text/javascript">
$( "#komment'.$row['rand'].'" ).dblclick(function() {
    var div = document.getElementById("kommenta'.$row['rand'].'");
    var iframe = document.createElement("iframe");
    var a = document.createElement("a");
  div.appendChild(iframe);
    div.appendChild(a);
    a.href = "/komnete.php?id=1&rand='.$row['rand'].'&p=0&ogrn='.$row['ogrn'].'&kli='.$row['idkli'].'&lico='.$row['lico'].'&gr='.$row['gr'].'&nomerschet='.$row['nomerschet'].'&produkt='.$row['produkt'].'&inn='.$row['inn'].'";
    
    a.innerHTML = "Открыть";
    a.target = "MyFrame'.$row['rand'].'";
    iframe.name = "MyFrame'.$row['rand'].'";
    iframe.src = "/komnete.php?id=1&rand='.$row['rand'].'&p=0&ogrn='.$row['ogrn'].'&kli='.$row['idkli'].'&lico='.$row['lico'].'&gr='.$row['gr'].'&nomerschet='.$row['nomerschet'].'&produkt='.$row['produkt'].'&inn='.$row['inn'].'";
    iframe.width = document.documentElement.clientWidth - document.documentElement.clientWidth / 15;
    iframe.height = document.documentElement.clientHeight+20;
    iframe.className = "iframestylediv";
    document.getElementById("kommenta'.$row['rand'].'").className = "contai";
});
$(document).ready(function(){
    $("#kommenti'.$row['rand'].'").click(function(){
        $("#kommenti'.$row['rand'].'").empty();
        document.getElementById("kommenti'.$row['rand'].'").className = "";
        document.location.href = "'.$kommentCloseUrl.'";
    }); 
});
</script>
<div id="kommenti'.$row['rand'].'">
<div id="kommenta'.$row['rand'].'"></div>
</div>
';
    /*
    echo '<div style="display:none;" id="display'.$row['rand'].'">
    <div style="    background: #E5FDFF;
        padding: 10px;
        border: 1px solid #ccc;">';
    $wequery = mysql_query("SELECT * FROM schetoldkomment WHERE schet ='".$row['rand']."' ORDER BY id DESC");
    while($rowzw = mysql_fetch_array($wequery)) {
    $zktolgenerac = "SELECT * FROM users WHERE users_id =".$rowzw['kto'];
    $zktorgenerac = mysql_query($zktolgenerac);
    $zktopgenerac = mysql_fetch_array($zktorgenerac);
    $zkto = $zktopgenerac['f_name'];
    echo '<b>'.$rowzw['data'].' / '.mb_substr($zkto,0,1,'UTF-8').'. '.$zktopgenerac['l_name'].'</b> '.$rowzw['komment'].'<hr>';
    }
    echo'</div>
    <textarea rows="5" name="editor'.$row['rand'].'" id="editor'.$row['rand'].'" >';
    if($yesisset > 0){echo $peissetkomment['komment'];}else{echo $row['koment'];}
    echo'</textarea>
            <input class="btn btn-success" name="submit'.$row['rand'].'" type="submit" value="Сохранить" >
            <div id="otmenit'.$row['rand'].'" class="btn btn-primary" value="Отменить" >Отменить</div>
        </div>
    <script  type="text/javascript">
    $( "#komment'.$row['rand'].'" ).dblclick(function() {
    document.getElementById("display'.$row['rand'].'").style.display="block";
    var ckeditor'.$row['rand'].' = CKEDITOR.replace( "editor'.$row['rand'].'" ).config.toolbarGroups = [
    { name: "tools" },
    { name: "others" },
    { name: "basicstyles", groups: [ "basicstyles", "cleanup" ]},
    { name: "colors" }
    ];
    });
    $("#otmenit'.$row['rand'].'").click(function(){document.getElementById("display'.$row['rand'].'").style.display="none";});
    </script>
    ';
    if(isset($_POST['submit'.$row['rand']])){
    $koment = "UPDATE schet SET `koment`='".$_POST["editor".$row['rand']]." (".$userdata['f_name']." ".$userdata['l_name'].")' WHERE rand ='".$row['rand']."' ";
    mysql_query($koment) or die(mysql_error($link));
    echo'<script type="text/javascript">document.location.href = "'.$_SERVER['REQUEST_URI'].'";</script>';
    $oldkomment = "INSERT INTO `schetoldkomment` (`schet`,`komment`,`kto`,`data`) VALUES ('".$row['rand']."','".$_POST["editor".$row['rand']]."','".$userdata['users_id']."','".date("d.m.Y; H:i")."')";
    mysql_query($oldkomment) or die(mysql_error($linkoldkoment));
    $aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`) VALUES ('".date("d.m.Y; H:i:s")."','Изменен коментарий счета','".$userdata['users_id']."')";
    mysql_query($aktivn) or die(mysql_error($link));
    }*/
    if($row['cher']!='0') {
        $zktolgeneraci = "SELECT * FROM  prichotk WHERE id ='" . $row['prichotk'] . "'";
        $zktorgeneracii = mysql_query($zktolgeneraci);
        $zktopgeneraciii = mysql_fetch_array($zktorgeneracii);
        echo '<p style="color:maroon ;font-size: 14pt;">' .$row['koment']." .Причина:" . $zktopgeneraciii['value'] . '</p>';
    }

    echo '</td>';
    echo '<td>';
    $result = mysql_query("SELECT count(*) FROM schetizbran WHERE kto = '".$userdata['users_id']."' AND schet =".$row['rand']);
    $class = mysql_result($result, 0);
    if($class == 0){
        echo '<a title="В избранные" href="./schetizbran.php?tip=1&id='.$row['rand'].'"><span class="glyphicon glyphicon-star"></span></a>';
    }else{
        echo '<a title="Из избранные" href="./schetizbran.php?tip=2&users='.$userdata['users_id'].'&id='.$row['rand'].'"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span></a>';
    }
    echo '</td>';
    if ($row['nomerdog']=="В КС"){
        echo '<td style="text-align: center; font-size: 14px; padding: 0;">';
        echo '</td>';
    }else{
        $rpod2345 = "SELECT * FROM kvobop WHERE schet = '".$row['rand']."'";
        $result57657 = mysql_query($rpod2345);
        $row134 = mysql_fetch_array($result57657);
        $query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row134['schet']);
        $person426 = mysql_result($query544, 0);
        if ($person426 <= $row['price'] && $person426 != 0) {
            if($row['cher']=='0') {
                echo '<td style="text-align: center;font-size: 14px;padding: 0;background:green;text-align:center;color:#fff;">';
            }
            else
            {echo '<td style="text-align: center;font-size: 14px;padding: 0;">';}
        }else{
            echo '<td style="text-align: center;font-size: 14px;padding: 0;">';
        }
        if ($person426 != $row['price'] && $person426 > 0 && $person426 < $row['price'] && $person426 != 0) {
            echo '<a style="font-size: 14px;" id="rable'.$row['rand'].'" onclick="ruble'.$row['rand'].'()"><span class="glyphicon glyphicon-ruble"></span></a>';
        }
        if ($person426 >= $row['price'] && $person426 != $row['price']) {
            echo '<a onclick="open'.$row['rand'].'()"><span class="glyphicon glyphicon-eye-open"></span></a>';
        }
        if ($person426 <= $row['price'] && $person426 != 0) {
            echo '<a onclick="open'.$row['rand'].'()"><span class="glyphicon glyphicon-eye-open"></span></a>';
        }else{
            echo '';
//echo '<a id="rable'.$row['rand'].'" onclick="ruble'.$row['rand'].'()"><span class="glyphicon glyphicon-ruble"></span></a>';
        }
        echo '<script type="text/javascript">
function ruble'.$row['rand'].'()
{
    var rublec = document.getElementById("rublei'.$row['rand'].'");
    var rubled = document.createElement("iframe");
    var rublet = document.createTextNode("11111");
    rublec.appendChild(rubled);
    rubled.appendChild(rublet);
	rubled.src = "/divoplata.php?id=1&rand='.$row['rand'].'";
	rubled.width = "900px";
	rubled.height = document.documentElement.clientHeight - 100;
	rubled.className = "iframestyle";
	document.getElementById("rublei'.$row['rand'].'").className = "contai";
}
function open'.$row['rand'].'()
{
    var rublec = document.getElementById("rublei'.$row['rand'].'");
    var rubled = document.createElement("iframe");
    var rublet = document.createTextNode("11111");
    rubled.appendChild(rublet);
    rublec.appendChild(rubled);
	rubled.src = "/divoplata.php?id=0&rand='.$row['rand'].'";
	rubled.width = "900px";
	rubled.height = document.documentElement.clientHeight - 100;
	rubled.className = "iframestyle";
	document.getElementById("rublei'.$row['rand'].'").className = "contai";
}
$(document).ready(function(){
    $("#rublei'.$row['rand'].'").click(function(){
        $("#rublei'.$row['rand'].'").empty();
		document.getElementById("rublei'.$row['rand'].'").className = "";
		
    }); 
});
</script>
<div id="rublei'.$row['rand'].'"></div>';

        echo '</td>';

    }
    /*if (strlen($row['inn']) == 12){
    $tipf = 1;
    } else {
    $tipf = 2;
    }
    $re1f = mysql_query("SELECT count(*) from proddoc WHERE produkt = '$row[produkt]'  AND tip = '".$tipf."'");
    $cl1f = mysql_result($re1f, 0);
    $re2f = mysql_query("SELECT count(*) from proddoc WHERE produkt = '$row[produkt]'  AND tip = '3'");
    $cl2f = mysql_result($re2f, 0);
    $cl3f = $cl2f + $cl1f;
    $redf = mysql_query("SELECT count(*) from dokstamp WHERE ogrn ='".$row['idkli']."' AND schet = '".$row['rand']."'");
    $cladf = mysql_result($redf, 0);
    /*echo '<td style="text-align: center;font-size: 14px;
    padding: 0;"';
    if($cl3f == $cladf){
        echo ' class="highlight_success"';
    mysql_query("UPDATE schet SET doki='1' WHERE rand='".$row['rand']."'");
    }else{
        echo ' class="alert alert-danger"';
    }
    echo'>';


    echo '<a  onclick="doc'.$row['rand'].'()"><span class="glyphicon glyphicon-open-file"></span></a>
    <script type="text/javascript">
    function doc'.$row['rand'].'()
    {
        var docc = document.getElementById("doccontai'.$row['rand'].'");
        var docd = document.createElement("iframe");
        var doct = document.createTextNode("11111");
        docd.appendChild(doct);
        docc.appendChild(docd);
        docd.src = "/rabotasdoc.php?id='.$row['idkli'].'&schet='.$row['rand'].'&parent='.$row['produkt'].'&inn='.$row['inn'].'&head=0";
        docd.width = "900px";
        docd.height = document.documentElement.clientHeight - 100;
        docd.className = "iframestyle";
        document.getElementById("doccontai'.$row['rand'].'").className = "contai";
    }
    $(document).ready(function(){
        $("#doccontai'.$row['rand'].'").click(function(){
            $("#doccontai'.$row['rand'].'").empty();
            document.getElementById("doccontai'.$row['rand'].'").className = "";
        });
    });
    </script>
    <div id="doccontai'.$row['rand'].'"></div>
    ';
    echo '</td>';*/

//-------------------------------------------------------------------------------------------------------//
    echo '<td style="width: 8px; text-align: center; font-size: 14px;"';
    if ($row['oplachenks'] == 1) {
        echo ' class="highlight_success"';
    }else if($row['priceks'] == 0){
        echo ' class="highlight_success"';
    }else{
        echo ' class="alert alert-danger"';
    }
    echo'>';
    if($row['priceks'] > 0){
        echo number_format($row['priceks'], 0, ' ', ','),"р.";
    }else{
        echo number_format(0, 0, ' ', ','),"р.";
    }

    echo '</td>';

    echo '<td style="width: 8px; text-align: center; font-size: 14px;"';

    $rpod2345 = "SELECT * FROM kvobop WHERE schet = '".$row['rand']."'";
    $result57657 = mysql_query($rpod2345);
    $row134 = mysql_fetch_array($result57657);

    $query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row134['schet']);
    $person426 = mysql_result($query544, 0);


    if ($person426 <= $row['price'] && $person426 != 0&&$row['cher']=='0') {
        echo ' class="highlight_success"';
    }else if($row['price'] == 0&&$row['cher']=='0') {
        echo ' class="highlight_success"';
    }else{
        echo ' class="alert alert-danger"';
    }
    if($row['ust_sert'] == 0) {
        echo ' class="highlight_success"';
    }else{
        echo ' class="alert alert-danger" style"background:lightseagreen"';
    }






    echo'>';
    echo number_format($row['price'], 0, ' ', ','),"р.";
    echo '</td>';
//-------------------------------------------------------------------------------------------------------//

    echo '<td id="proddlen'.$row['rand'].'" style="text-align: center; font-size: 14px;">';
    echo '<div id="proleninfo'.$row['rand'].'">';
    if($row['prodlen'] == 0){
        echo 'Мы привели';
    }if($row['prodlen'] == 2){
        echo 'Сам пришел';
    }if($row['prodlen'] == 1){
        echo 'Продлен';
    }
    echo '</div>';
    echo '<select id="prodlen'.$row['rand'].'" name="prodlen'.$row['rand'].'" onchange="proDlen'.$row['rand'].'(this.value)" style="display: none; font-size: 14px;">';
    echo '<option  value=""></option>';
    echo '<option  value="2">Сам пришел</option>';
    echo '<option  value="0">Мы привели</option>';
    echo '<option  value="1">Продление</option>';
    echo '<option  value=""></option>';
    echo '</select>';
    echo '<script>
$("#proddlen'.$row['rand'].'").live("dblclick", function() {document.getElementById("prodlen'.$row['rand'].'").style.display="block";});
function proDlen'.$row['rand'].'(str) {
if (str=="0") {
$.ajax({
type: "GET",
url: "prodlen.php", 
data: "prodlen="+str+"&rand='.$row['rand'].'",
success: function(msg){
document.getElementById("prodlen'.$row['rand'].'").style.display="none";
setTimeout(function() {$("#proleninfo'.$row['rand'].'").load(" #proleninfo'.$row['rand'].'");}, 1000);}}); 
} else {
$.ajax({type: "GET",url: "prodlen.php",data: "prodlen="+str+"&rand='.$row['rand'].'",success: function(msg){document.getElementById("prodlen'.$row['rand'].'").style.display="none";setTimeout(function() {$("#proleninfo'.$row['rand'].'").load(" #proleninfo'.$row['rand'].'");}, 1000);}});}}
</script>';
    echo '</td>';
    echo '<td id="proddlen'.$row['rand'].'" style="text-align: center; font-size: 14px;">';

    $ktolgenerac = "SELECT * FROM users WHERE users_id =".$row['kto'];
    $ktorgenerac = mysql_query($ktolgenerac);
    $ktopgenerac = mysql_fetch_array($ktorgenerac);
    $kto = $ktopgenerac['f_name'];
    echo mb_substr($kto,0,1,'UTF-8'),'. ';
    echo $ktopgenerac['l_name'];
    echo '</td>';
    echo'<td style="text-align: center; font-size: 14px;">';
    $ktolgeneraci = "SELECT * FROM agent WHERE id =".$row['agent'];
    $ktorgeneraci = mysql_query($ktolgeneraci);
    $ktopgeneraci = mysql_fetch_array($ktorgeneraci);
    echo $kto = $ktopgeneraci['name'];
    echo'</td>';
    echo '<td id="generac'.$row['rand'].'">';
    $qdsafsd = mysql_query("SELECT SUM(kvo) FROM schet WHERE del = '0' AND gen = '1' AND rand ='".$row['rand']."'  GROUP BY rand");
    $pedfsbfdb = mysql_result($qdsafsd, 0);
    if ($pedfsbfdb >= 1){
        echo "Генераций ".$pedfsbfdb;
    }
    $lgenerac = "SELECT * FROM users WHERE users_id =".$row['generac'];
    $rgenerac = mysql_query($lgenerac);
    $pgenerac = mysql_fetch_array($rgenerac);
    echo '<div style="font-size: 14px; id="generacinfo'.$row['rand'].'">';
    if($row['generac']!=546321564){
        $gen = $pgenerac['f_name'];
        echo mb_substr($gen,0,1,'UTF-8'),'';
        echo $pgenerac['l_name'];
    }else{
        echo"Поставка";
    }
    echo ' </div>';
    echo '<select id="generaci'.$row['rand'].'" name="generaci'.$row['rand'].'" onchange="generaciTakti'.$row['rand'].'(this.value)"  style="display:none;" >';
    $query21 = mysql_query("SELECT * from users WHERE  del_users = 0 and show_executor = 1 ORDER BY users_id DESC");
    echo '<option value="0"></option>';
//echo '<option value="546321564">Поставка</option>';
    while($row21 = mysql_fetch_array($query21)) {
        echo '<option  value="'.$row21['users_id'].'">',$row21['f_name']," ",$row21['l_name'],'</option>';
    }
//echo '<option value="0"></option>';
    echo '</select>';

    echo '<script>
$("#generac'.$row['rand'].'").live("dblclick", function() {
document.getElementById("generaci'.$row['rand'].'").style.display="block";
});
function generaciTakti'.$row['rand'].'(str) {if (str=="0") {
$.ajax({   
type: "GET",   
url: "mari.php",   
data: "lico="+str+"&rand='.$row['rand'].'",   
success: function(msg){ 
document.getElementById("generaci'.$row['rand'].'").style.display="none"; 
setTimeout(function() {
$("#generacinfo'.$row['rand'].'").load(" #generacinfo'.$row['rand'].'");}, 1000);   
}});} else {
$.ajax({   
type: "GET",   
url: "mari.php",   
data: "lico="+str+"&rand='.$row['rand'].'",   
success: function(msg){ 
document.getElementById("generaci'.$row['rand'].'").style.display="none"; 
setTimeout(function() {
$("#generacinfo'.$row['rand'].'").load(" #generacinfo'.$row['rand'].'");
}, 1000);   
}});}}
</script>';
    echo '</td>';
    echo '<td>';
    echo "<a href='kartklient.php?id=".$row['idkli']."'><span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span></a>";
    if($row['url'] == "0"){
    }else{
        echo "&nbsp;&nbsp;<a target='_blank' href='".$row['url']."'><span class='glyphicon glyphicon-share' aria-hidden='true'></span></a>";
    }
    echo '</td>';
    echo '<td class="lab"  onclick="f(this)" id='.$row['god'].$row['kto'].$row['otdel'].$row['kolichschet'].'>'.$row['data_napom'];
    echo'</td></tr>';
}
