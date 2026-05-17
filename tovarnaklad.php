<?php
# подключаем конфиг
include 'conf.php';
include 'invoice_action.php';
include 'nds.php';

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
?><?php
include 'conf.php';
$pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);

$sch = "SELECT DISTINCT nomerschet,kolichschet,oferta,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,d,m,y,ns FROM schet WHERE del = '0' AND rand ='".$_GET['id']."'  ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult);

$kli = "SELECT * FROM ogrn WHERE id =".$_GET['kli'];
$kliresult = mysql_query($kli);
$klient = mysql_fetch_array($kliresult);

$sav1 = "SELECT * FROM produkti WHERE id = '".$schet['produkt']."' ORDER BY id DESC";
$savresult1 = mysql_query($sav1);
$savoir1 = mysql_fetch_array($savresult1);

$sav = "SELECT * FROM uslugi WHERE id = '".$savoir1['parent']."' ORDER BY id DESC";
$savresult = mysql_query($sav);
$savoir = mysql_fetch_array($savresult);

// --- НДС из счета ---
$nds_info = nds_parse(isset($pavoir['nds']) ? $pavoir['nds'] : '');
$nds_code = $nds_info['code'];
$nds_rate = (float)$nds_info['rate'];



$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);
$dir = "doc/".$_GET['kli']."/";
$dir2 = "doc/".$_GET['kli']."/".$_GET['id']."/";
mkdir ($dir, 0777);   
mkdir ($dir2, 0777);  
$fp = fopen($dir2."tovarnaklad.doc", 'w+');
$date = new DateTime(getDateDocuments($_GET['id'])['d_tn']);
$dogDate = new DateTime(getDateDocuments($_GET['id'])['d_contract']);

$str = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 15">
<link rel=File-List
href="Товарная%20накладная%20Торг-12%20(3).files/filelist.xml">

    <link href="css/tn.css" rel="stylesheet">
</head>

<body>
<!--[if !excel]>&nbsp;&nbsp;<![endif]-->
<!--Следующие сведения были подготовлены мастером публикации веб-страниц
Microsoft Excel.-->
<!--При повторной публикации этого документа из Excel все сведения между тегами
DIV будут заменены.-->
<!----------------------------->
<!--НАЧАЛО ФРАГМЕНТА ПУБЛИКАЦИИ МАСТЕРА ВЕБ-СТРАНИЦ EXCEL -->
<!----------------------------->

<div id="Товарная накладная Торг-12 (3)_25266" align=center
x:publishsource="Excel">

<table id=nen border=0 cellpadding=0 cellspacing=0 width=922 class=xl6325266
 style="border-collapse:collapse;table-layout:fixed;width:701pt">
 <col class=xl6325266 width=21 style="mso-width-source:userset;mso-width-alt:
 768;width:16pt">
 <col class=xl6325266 width=13 style="mso-width-source:userset;mso-width-alt:
 475;width:10pt">
 <col class=xl6325266 width=21 span=12 style="mso-width-source:userset;
 mso-width-alt:768;width:16pt">
 <col class=xl6325266 width=16 style="mso-width-source:userset;mso-width-alt:
 585;width:12pt">
 <col class=xl6325266 width=19 style="mso-width-source:userset;mso-width-alt:
 694;width:14pt">
 <col class=xl6325266 width=21 span=12 style="mso-width-source:userset;
 mso-width-alt:768;width:16pt">
 <col class=xl6325266 width=20 style="mso-width-source:userset;mso-width-alt:
 731;width:15pt">
 <col class=xl6325266 width=21 span=9 style="mso-width-source:userset;
 mso-width-alt:768;width:16pt">
 <col class=xl6325266 width=25 style="mso-width-source:userset;mso-width-alt:
 914;width:19pt">
 <col class=xl6325266 width=21 span=3 style="mso-width-source:userset;
 mso-width-alt:768;width:16pt">
 <col class=xl6325266 width=16 style="mso-width-source:userset;mso-width-alt:
 585;width:12pt">
 <col class=xl6325266 width=15 style="mso-width-source:userset;mso-width-alt:
 548;width:11pt">
 <col class=xl6325266 width=21 style="width:16pt">
 <tr height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=31 height=13 class=xl6325266 width=635 style="height:10.15pt;
  width:483pt"></td>
  <td colspan=14 class=xl6725266 width=287 style="width:218pt">Унифицированная
  форма N ТОРГ-12</td>
 </tr>
 <tr height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=31 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=14 class=xl6725266>Утверждена постановлением Госкомстата России</td>
 </tr>
 <tr height=13 style="mso-height-source:userset;height:10.15pt"><td colspan=31 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=14 class=xl6725266><span style="mso-spacerun:yes"> </span>от
  25.12.98 N 132</td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=37 height=15 class=xl6325266 style="border-right:.5pt solid black;
  height:11.45pt"></td>
  <td colspan=8 class=xl10925266 style="border-right:.5pt solid black;
  border-left:none">Код</td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=37 height=15 class=xl9425266 style="height:11.45pt">Форма по<span
  style="mso-spacerun:yes">  </span>ОКУД</td>
  <td colspan=8 class=xl14125266 style="border-right:1.0pt solid black">0330212</td>
 </tr>
 <tr height=84 style="mso-height-source:userset;height:63.0pt">
  <td colspan=33 height=84 class=xl9325266 width=677 style="height:63.0pt;
  width:515pt">'.$savoir['full_name'].' ИНН:'.$savoir['inn'].'';
  if ($savoir['kpp'] > 0){
  $str .= "КПП:";
  }
  $str .= "КПП:".$savoir['kpp'].' 
  Адрес:<br>
    '.$savoir['adres'].'<span
  style="mso-spacerun:yes">  </span><br>Рс. счет:
    '.$savoir['r_schet'].' Корр. счет:
    '.$savoir['k_schet'].'<br> '.$savoir['bank'].' БИК:
    '.$savoir['bik'].'<span style="mso-spacerun:yes"> </span></td>
  <td colspan=4 class=xl9425266>по ОКПО</td>
  <td colspan=8 class=xl13425266 style="border-right:1.0pt solid black">0109266498</td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=33 height=15 class=xl17725266 style="height:11.45pt">организация<font
  class="font725266">-грузоотправитель</font><font class="font625266">, адрес, </font><font
  class="font725266">телефон, факс, банковские реквизиты</font></td>
  <td colspan=4 rowspan=2 class=xl9425266 style="border-bottom:.5pt solid black"></td>
  <td colspan=8 rowspan=2 class=xl13625266 style="border-right:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=33 height=15 class=xl9625266 style="height:11.45pt">&nbsp;</td>
 </tr>
 <tr height=16 style="mso-height-source:userset;height:12.0pt">
  <td colspan=29 height=16 class=xl15525266 style="height:12.0pt">структурное
  подразделение</td>
  <td colspan=8 class=xl17925266>Вид деятельности по ОКДП</td>
  <td colspan=8 class=xl17625266 width=161 style="border-right:1.0pt solid black;
  width:122pt"><br>
    </td>
 </tr>
 <tr height=53 style="mso-height-source:userset;height:39.75pt">
  <td colspan=6 height=53 class=xl9025266 style="height:39.75pt">Грузополучатель</td>
  <td colspan=27 class=xl9325266 width=559 style="width:425pt">'.$klient['naim'].' ИНН:'.$klient['inn'].' ';
  if ($klient['kpp'] > 0){
  $str .= "КПП:";
  }
  $str .= "КПП:".$klient['kpp'].' <br>Адрес:
    '.$klient['uridadress'].' <br>Рс.
  счет:'.$klient['r_schet'].' Корр. счет:
    '.$klient['k_schet'].'<span style="mso-spacerun:yes">  </span>БИК:'.$klient['bik'].'</td>
  <td colspan=4 class=xl9425266>по ОКПО</td>
  <td colspan=8 class=xl13425266 style="border-right:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=6 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=27 class=xl15525266>наименование организации, адрес, номер
  телефона, банковские реквизиты</td>
  <td colspan=4 rowspan=2 class=xl6825266>по ОКПО</td>
  <td colspan=8 rowspan=2 class=xl12725266 style="border-right:1.0pt solid black;
  border-bottom:.5pt solid black"></td>
 </tr>
 <tr height=79 style="mso-height-source:userset;height:59.25pt">
  <td colspan=4 height=79 class=xl6725266 style="height:59.25pt">Поставщик</td>
  <td colspan=29 class=xl9325266 width=601 style="width:457pt">'.$savoir['full_name'].' ИНН:'.$savoir['inn'].' ';
  if ($savoir['kpp'] > 0){
  $str .= "КПП:";
  }
  $str .= "КПП:".$savoir['kpp'].' <br>Адрес:
    '.$savoir['adres'].' <span
  style="mso-spacerun:yes">  </span><br>Рс. счет:
    '.$savoir['r_schet'].' Корр. счет:
    '.$savoir['k_schet'].'<br> '.$savoir['bank'].' БИК:
    '.$savoir['bik'].'<span style="mso-spacerun:yes"> </span></td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=4 height=15 class=xl6325266 style="height:11.45pt"></td>
  <td colspan=29 class=xl15525266>наименование организации, адрес, номер
  телефона, банковские реквизиты</td>
  <td colspan=4 rowspan=2 class=xl6825266 style="border-bottom:.5pt solid black">по
  ОКПО</td>
  <td colspan=8 rowspan=2 class=xl13625266 style="border-right:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr height=48 style="mso-height-source:userset;height:36.0pt">
  <td colspan=4 height=48 class=xl6725266 style="height:36.0pt">Плательщик</td>
  <td colspan=29 class=xl9325266 width=601 style="width:457pt">'.$klient['naim'].' ИНН:'.$klient['inn'].' ';
  if ($klient['kpp'] > 0){
  $str .= "КПП:";
  }
  $str .= "КПП:".$klient['kpp'].' <br>Адрес:
    '.$klient['uridadress'].' <br>Рс.
  счет:'.$klient['r_schet'].' Корр. счет:
    '.$klient['k_schet'].'<span style="mso-spacerun:yes">  </span>БИК:'.$klient['bik'].'</td>
 </tr>
 <tr height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=4 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=29 class=xl15525266 style="border-right:.5pt solid black">наименование
  организации, адрес, номер телефона, банковские реквизиты</td>
  <td colspan=4 rowspan=2 class=xl19425266 style="border-bottom:.5pt solid black">номер</td>
  <td colspan=8 rowspan=2 class=xl13625266 style="border-right:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=4 height=13 class=xl6725266 style="height:10.15pt">Основание</td>
  <td colspan=29 class=xl18525266 style="border-right:.5pt solid black">&nbsp;</td>
 </tr>
 <tr height=16 style="mso-height-source:userset;height:12.0pt">
  <td colspan=4 height=16 class=xl6325266 style="height:12.0pt"></td>
  <td colspan=29 class=xl15525266 style="border-right:.5pt solid black">от '.$dogDate->format('d.m.Y').' 
   договор №'.$schet['god'].''.$schet['kto'].''.$schet['otdel'].''.$schet['nomerdog'].'</td>
  <td colspan=4 class=xl15625266 style="border-left:none">дата</td>
  <td colspan=8 class=xl13425266 style="border-right:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr height=16 style="mso-height-source:userset;height:12.0pt">
  <td colspan=33 height=16 class=xl18025266 style="border-right:.5pt solid black;
  height:12.0pt">Транспортная накладная</td>
  <td colspan=4 class=xl15625266 style="border-left:none">номер</td>
  <td colspan=8 class=xl13425266 style="border-right:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr height=16 style="mso-height-source:userset;height:12.0pt">
  <td colspan=33 height=16 class=xl6825266 style="border-right:.5pt solid black;
  height:12.0pt"></td>
  <td colspan=4 class=xl15625266 style="border-left:none">дата</td>
  <td colspan=8 class=xl13425266 style="border-right:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr height=16 style="mso-height-source:userset;height:12.0pt">
  <td colspan=37 height=16 class=xl9425266 style="height:12.0pt">Вид операции</td>
  <td colspan=8 class=xl19125266 style="border-right:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr height=14 style="mso-height-source:userset;height:10.9pt">
  <td colspan=45 height=14 class=xl6325266 style="height:10.9pt"></td>
 </tr>
 <tr class=xl6425266 height=34 style="mso-height-source:userset;height:25.5pt">
  <td colspan=21 height=34 class=xl6425266 style="height:25.5pt"></td>
  <td colspan=6 class=xl19725266 width=126 style="width:96pt">Номер<br>
    документа</td>
  <td colspan=6 class=xl19725266 width=125 style="border-left:none;width:95pt">Дата<br>
    составления</td>
  <td colspan=12 class=xl18725266 style="border-left:none">&nbsp;</td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=21 rowspan=2 height=30 class=xl18925266 style="border-right:1.0pt solid black;
  height:22.9pt">ТОВАРНАЯ НАКЛАДНАЯ<span style="mso-spacerun:yes"> </span></td>
  <td colspan=6 rowspan=2 class=xl20025266 style="border-bottom:1.0pt solid black; text-align:center;">';
  $str .= ''.$schet['god'].$schet['kto'].$schet['otdel'].$schet['kolichschet'].'</td>
  <td colspan=6 rowspan=2 class=xl19825266 style="border-bottom:1.0pt solid black">'.$date->format('d.m.Y').'</td>
  <td colspan=12 class=xl18825266 style="border-left:none">&nbsp;</td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=12 height=15 class=xl18825266 style="height:11.45pt;border-left:
  none">&nbsp;</td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=45 height=15 class=xl6325266 style="height:11.45pt"></td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=45 height=15 class=xl18225266 style="height:11.45pt">&nbsp;</td>
 </tr>
 <tr class=xl7225266 height=18 style="mso-height-source:userset;height:13.9pt">
  <td colspan=2 rowspan=2 height=73 class=xl10325266 width=34 style="height:
  55.35pt;width:26pt">Но-мер по по-рядку</td>
  <td colspan=10 class=xl10425266 width=210 style="border-right:.5pt solid black;
  border-left:none;width:160pt">Товар</td>
  <td colspan=6 class=xl10425266 width=119 style="border-right:.5pt solid black;
  border-left:none;width:90pt">Единица измерения</td>
  <td colspan=3 rowspan=2 class=xl9925266 width=63 style="border-right:.5pt solid black;
  border-bottom:.5pt solid black;width:48pt">Вид упа-<br>
    ковки</td>
  <td colspan=4 class=xl10325266 width=84 style="border-left:none;width:64pt">Количество</td>
  <td colspan=2 rowspan=2 class=xl9925266 width=42 style="border-right:.5pt solid black;
  border-bottom:.5pt solid black;width:32pt">Масса<br>
    брутто</td>
  <td colspan=3 rowspan=2 class=xl9925266 width=62 style="border-right:.5pt solid black;
  border-bottom:.5pt solid black;width:47pt">Коли-<br>
    чество<br>
    (масса нетто)</td>
  <td colspan=3 rowspan=2 class=xl9925266 width=63 style="border-right:.5pt solid black;
  border-bottom:.5pt solid black;width:48pt">Цена,<br>
    <span style="mso-spacerun:yes"> </span>руб. коп.</td>
  <td colspan=3 rowspan=2 class=xl9925266 width=63 style="border-right:.5pt solid black;
  border-bottom:.5pt solid black;width:48pt">Сумма без учета НДС, <br>
    руб. коп.</td>
  <td colspan=5 class=xl10325266 width=109 style="border-left:none;width:83pt">НДС</td>
  <td colspan=4 rowspan=2 class=xl9925266 width=73 style="border-right:.5pt solid black;
  border-bottom:.5pt solid black;width:55pt">Сумма с учетом НДС,<br>
    <span style="mso-spacerun:yes"> </span>руб. коп.</td>
 </tr>
 <tr class=xl7225266 height=55 style="mso-height-source:userset;height:41.45pt">
  <td colspan=8 height=55 class=xl10325266 width=168 style="height:41.45pt;
  border-left:none;width:128pt">наименование,<br>
    <span style="mso-spacerun:yes"> </span>характеристика, сорт, артикул товара</td>
  <td colspan=2 class=xl10325266 width=42 style="border-left:none;width:32pt">код</td>
  <td colspan=3 class=xl10325266 width=58 style="border-left:none;width:44pt">наиме-<br>
    нование</td>
  <td colspan=3 class=xl10325266 width=61 style="border-left:none;width:46pt">код
  по ОКЕИ</td>
  <td colspan=2 class=xl10325266 width=42 style="border-left:none;width:32pt">в
  одном месте</td>
  <td colspan=2 class=xl10325266 width=42 style="border-left:none;width:32pt">мест,<br>
    штук</td>
  <td colspan=2 class=xl10325266 width=42 style="border-left:none;width:32pt">став-<br>
    ка , %</td>
  <td colspan=3 class=xl10325266 width=67 style="border-left:none;width:51pt">сумма,
  руб. коп.</td>
 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=2 height=15 class=xl11925266 style="height:11.45pt">1</td>
  <td colspan=8 class=xl11925266 style="border-left:none">2</td>
  <td colspan=2 class=xl11925266 style="border-left:none">3</td>
  <td colspan=3 class=xl11925266 style="border-left:none">4</td>
  <td colspan=3 class=xl11425266 style="border-left:none">5</td>
  <td colspan=3 class=xl11425266 style="border-left:none">6</td>
  <td colspan=2 class=xl10925266 style="border-right:.5pt solid black;
  border-left:none">7</td>
  <td colspan=2 class=xl10925266 style="border-right:.5pt solid black;
  border-left:none">8</td>
  <td colspan=2 class=xl11425266 style="border-left:none">9</td>
  <td colspan=3 class=xl11425266 style="border-left:none">10</td>
  <td colspan=3 class=xl11425266 style="border-left:none">11</td>
  <td colspan=3 class=xl11425266 style="border-left:none">12</td>
  <td colspan=2 class=xl10925266 style="border-right:.5pt solid black;
  border-left:none">13</td>
  <td colspan=3 class=xl10925266 style="border-right:.5pt solid black;
  border-left:none">14</td>
  <td colspan=4 class=xl11925266 style="border-left:none">15</td>
 </tr>
 
 
 
 
 
 
 ';

$i = 1;
$summ=0;
// --- накопители, чтобы "сумма строк == итого" ---
$sum_gross = 0.0; // сумма с НДС
$sum_net   = 0.0; // сумма без НДС
$sum_vat   = 0.0; // НДС
$query = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = '".$_GET['id']."'");
while($row = mysql_fetch_array($query)) {

if(!empty($row['prod'])){
$rpod = "SELECT * FROM tarif WHERE id =".$row['prod'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);

$qty = (float)$row['kvo'];

$unit_gross = (float)$personrpod['price'] - ((float)$personrpod['price'] * ((float)$row['skidka'] / 100));
// Сумма строки "с НДС"
$line_gross = round($unit_gross * $qty, 2);

$line_vat = 0.0;
$line_net = $line_gross;

$nds_cell_rate = 'Без НДС';
$nds_cell_sum  = '&nbsp;';

if ($nds_rate > 0) {
    // НДС "в том числе" из суммы строки
    $line_vat = nds_included_sum($line_gross, $nds_rate);
    $line_net = round($line_gross - $line_vat, 2);

    $nds_cell_rate = (string)$nds_rate;
    $nds_cell_sum  = number_format($line_vat, 2, '.', ' ');
}

// Цена без НДС за единицу (из net строки)
$unit_net = ($qty > 0) ? round($line_net / $qty, 2) : 0.0;

// --- накопление итогов ---
$summ     += $qty;          // у тебя это "количество" в Итого
$sum_gross+= $line_gross;
$sum_net  += $line_net;
$sum_vat  += $line_vat;
$str .=  '<tr class=xl7125266 height=53 style="mso-height-source:userset;height:39.75pt">
  <td colspan=2 height=53 class=xl13925266 style="height:39.75pt;    vertical-align: middle;">'.$i++.'</td>
  
  <td colspan=8 class=xl14325266 width=168 style="border-right:1.0pt solid black;
  border-left:none;width:128pt;    vertical-align: middle;">'.$personrpod['name'].'</td>
  
  <td colspan=2 class=xl14625266 style="border-right:1.0pt solid black;
  border-left:none;    vertical-align: middle;">9936</td>
  
  <td colspan=3 class=xl13825266 style="border-right:1.0pt solid black;
  border-left:none;    vertical-align: middle;">шт</td>
  
  <td colspan=3 class=xl14125266 style="border-right:1.0pt solid black;
  border-left:none;    vertical-align: middle;">796</td>
  
  <td colspan=3 class=xl14225266 style="border-left:none">&nbsp;</td>
  <td colspan=2 class=xl14825266 style="border-left:none">&nbsp;</td>
  <td colspan=2 class=xl14825266 style="border-left:none">&nbsp;</td>
  <td colspan=2 class=xl14925266 style="border-left:none">&nbsp;</td>
  
  <td colspan=3 class=xl14925266 kol style="border-left:none">'.$row['kvo'].'</td>
  
  <td colspan=3 class=xl14925266 style="border-left:none">'.number_format($unit_net, 2, '.', ' ').'</td>
  
  <td colspan=3 class=xl14925266 style="border-left:none">'.number_format($line_net, 2, '.', ' ').'</td>
  
  <td colspan=2 class=xl11225266 id=m>'.$nds_cell_rate.'</td>
  <td colspan=3 class=xl15225266>'.$nds_cell_sum.'</td>
  <td colspan=4 class=xl14925266 style="border-left:none" >'.number_format($line_gross, 2, '.', ' ').'</td>
 </tr>';
}
}
// --- итоги = сумма по строкам ---
$total_gross = round($sum_gross, 2);
$total_net   = round($sum_net, 2);
$total_vat   = round($sum_vat, 2);

// Если ниже по шаблону используешь $strgorod (num2str($strgorod)),
// приравниваем его к итогу по строкам, чтобы прописью было то же число.
$strgorod = $total_gross;

$nds_total_rate_cell = 'X';
$nds_total_sum_cell  = 'X';

if ($nds_rate > 0) {
    $nds_total_rate_cell = (string)$nds_rate;
    $nds_total_sum_cell  = number_format($total_vat, 2, '.', ' ');
}
  $str .=  '
 
 

 
 
 
 
 
 
 
 <tr class=xl7125266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=23 height=15 class=xl9425266 style="border-right:.5pt solid black;
  height:11.45pt">Итого</td>
  <td colspan=2 class=xl17125266 style="border-right:.5pt solid black;
  border-left:none">0</td>
  <td colspan=2 class=xl16625266 style="border-left:none">0,00</td>
  <td colspan=3 class=xl16625266 style="border-left:none">'.$summ.'</td>
  <td colspan=3 class=xl16225266 style="border-left:none">X</td>
  <td colspan=3 class=xl16325266 style="border-right:.5pt solid black;border-left:none">'.number_format($total_net, 2, '.', ' ').'</td>
  <td colspan=2 class=xl13525266 style="border-left:none">'.$nds_total_rate_cell.'</td>
  <td colspan=3 class=xl16625266 style="border-left:none">'.$nds_total_sum_cell.'</td>
  <td colspan=4 class=xl16325266 style="border-right:.5pt solid black;border-left:none">'.number_format($total_gross, 2, '.', ' ').'</td>

 </tr>
 <tr height=15 style="mso-height-source:userset;height:11.45pt">
  <td height=15 class=xl6325266 style="height:11.45pt"></td>
  <td class=xl6325266></td>
  <td class=xl6725266></td>
  <td class=xl6725266></td>
  <td class=xl6725266></td>
  <td class=xl6725266></td>
  <td class=xl6725266></td>
  <td class=xl6725266></td>
  <td class=xl6725266></td>
  <td class=xl6725266></td>
  <td class=xl6525266></td>
  <td class=xl6525266></td>
  <td class=xl6525266></td>
  <td class=xl6525266></td>
  <td class=xl6525266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl6625266></td>
  <td class=xl7025266></td>
  <td class=xl7025266></td>
  <td class=xl6925266></td>
  <td class=xl6925266></td>
  <td class=xl6925266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
  <td class=xl6325266></td>
 </tr>
 <tr class=xl6325266 height=24 style="mso-height-source:userset;height:18.6pt">
  <td colspan=2 height=24 class=xl6325266 style="height:18.6pt"></td>
  <td colspan=12 class=xl6725266>Товарная накладная имеет приложение на</td>
  <td colspan=18 class=xl9725266>&nbsp;</td>
  <td colspan=13 class=xl9825266>листах</td>
 </tr>
 <tr class=xl6325266 height=21 style="mso-height-source:userset;height:16.15pt">
  <td colspan=2 height=21 class=xl6325266 style="height:16.15pt"></td>
  <td colspan=5 class=xl6725266>и содержит</td>
  <td colspan=25 class=xl7925266>&nbsp;</td>
  <td colspan=13 class=xl6325266>порядковых номеров записей</td>
 </tr>
 <tr class=xl6325266 height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=7 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=25 class=xl8525266>прописью</td>
  <td colspan=13 class=xl6325266></td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=37 height=15 class=xl6325266 style="border-right:1.0pt solid black;
  height:11.45pt"></td>
  <td colspan=8 rowspan=2 class=xl12225266 style="border-right:1.0pt solid black;
  border-bottom:.5pt solid black">&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=13 height=15 class=xl6325266 style="height:11.45pt"></td>
  <td colspan=6 class=xl6725266>Масса груза (нетто)</td>
  <td colspan=17 class=xl7925266>&nbsp;</td>
  <td class=xl6725266></td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=19 height=15 class=xl6325266 style="height:11.45pt"></td>
  <td colspan=17 class=xl8525266>прописью</td>
  <td class=xl6325266></td>
  <td colspan=8 rowspan=2 class=xl12725266 style="border-right:1.0pt solid black;
  border-bottom:1.0pt solid black">&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=2 height=15 class=xl6325266 style="height:11.45pt"></td>
  <td colspan=4 class=xl6725266>Всего мест</td>
  <td colspan=7 class=xl7925266>&nbsp;</td>
  <td colspan=6 class=xl6725266>Масса груза (брутто)</td>
  <td colspan=17 class=xl7925266>&nbsp;</td>
  <td class=xl6725266></td>
 </tr>
 <tr class=xl6325266 height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=6 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=7 class=xl8525266>прописью</td>
  <td colspan=6 class=xl15025266></td>
  <td colspan=17 class=xl8525266>прописью</td>
  <td colspan=9 class=xl6325266></td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=45 height=15 class=xl6325266 style="height:11.45pt"></td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=14 height=15 class=xl6725266 style="height:11.45pt">Приложение
  (паспорта, сертификаты и т.п.) на<span style="mso-spacerun:yes"> </span></td>
  <td colspan=6 class=xl7925266>&nbsp;</td>
  <td colspan=3 class=xl6725266 style="border-right:.5pt solid black">листах</td>
  <td colspan=6 class=xl6725266><span style="mso-spacerun:yes"> </span>По
  доверенности N</td>
  <td colspan=4 class=xl7925266>&nbsp;</td>
  <td class=xl6825266>от</td>
  <td class=xl6825266>&quot;</td>
  <td class=xl7925266>&nbsp;</td>
  <td class=xl6725266>&quot;</td>
  <td colspan=3 class=xl9225266>&nbsp;</td>
  <td colspan=3 class=xl6525266></td>
  <td colspan=2 class=xl6725266>г</td>
 </tr>
 <tr class=xl6325266 height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=14 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=6 class=xl8525266>прописью</td>
  <td colspan=3 class=xl6825266 style="border-right:.5pt solid black"></td>
  <td colspan=22 class=xl9125266 style="border-left:none">&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=23 height=15 class=xl6325266 style="border-right:.5pt solid black;
  height:11.45pt"></td>
  <td colspan=3 class=xl6725266><span style="mso-spacerun:yes"> </span>выданной</td>
  <td colspan=19 class=xl7925266>&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=8 height=15 class=xl6725266 style="height:11.45pt">Всего отпущено
  на сумму</td>
  <td colspan=14 class=xl7925266>
  
  
  
  
  
  
  
  
  
  
  
  
  '.num2str($strgorod).'
  
  
  
  
  
  
  
  
  
  
  
  
  </td>
  <td class=xl7325266>&nbsp;</td>
  <td colspan=3 class=xl9125266 style="border-left:none">&nbsp;</td>
  <td colspan=19 class=xl16125266>кем, кому (организация, должность, фамилия,
  и., о.)</td>
 </tr>
 <tr class=xl6325266 height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=8 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=14 class=xl8525266>прописью</td>
  <td class=xl7325266>&nbsp;</td>
  <td class=xl7725266 style="border-left:none">&nbsp;</td>
  <td colspan=21 class=xl16725266>&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=17 height=15 class=xl7925266 style="height:11.45pt">&nbsp;</td>
  <td class=xl6725266 colspan=2>руб.</td>
  <td colspan=2 class=xl9225266>&nbsp;</td>
  <td class=xl6725266 colspan=2 style="border-right:.5pt solid black">коп.</td>
  <td class=xl7825266 style="border-left:none">&nbsp;</td>
  <td colspan=21 class=xl16725266>&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=23 height=15 class=xl6325266 style="border-right:.5pt solid black;
  height:11.45pt"></td>
  <td class=xl7825266 style="border-left:none">&nbsp;</td>
  <td colspan=21 rowspan=3 class=xl16825266>&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=7 height=15 class=xl6725266 style="height:11.45pt">Отпуск <font
  class="font825266">груза</font><font class="font525266"> разрешил</font></td>
  <td colspan=4 class=xl9225266>&nbsp;</td>
  <td class=xl6325266></td>
  <td colspan=3 class=xl9225266>&nbsp;</td>
  <td class=xl6825266></td>
  <td colspan=6 class=xl9225266>'.$savoir['fio'].'<td>
  <td class=xl7325266>&nbsp;</td>
  <td class=xl7825266 style="border-left:none">&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=7 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=4 class=xl8525266>должность</td>
  <td class=xl7425266></td>
  <td colspan=3 class=xl8525266>подпись</td>
  <td class=xl7525266></td>
  <td colspan=6 class=xl8525266>расшифровка подписи</td>
  <td class=xl7325266>&nbsp;</td>
  <td class=xl7825266 style="border-left:none">&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=11 height=15 class=xl8125266 style="height:11.45pt">Главный
  (старший) бухгалтер)</td>
  <td class=xl8125266></td>
  <td colspan=3 class=xl17025266>&nbsp;</td>
  <td class=xl8225266></td>
  <td colspan=6 class=xl17025266>&nbsp;</td>
  <td class=xl7625266>&nbsp;</td>
  <td colspan=5 class=xl9125266 style="border-left:none"><span
  style="mso-spacerun:yes"> </span>Груз принял</td>
  <td colspan=5 class=xl9225266>&nbsp;</td>
  <td class=xl6325266></td>
  <td colspan=3 class=xl9225266>&nbsp;</td>
  <td class=xl6825266></td>
  <td colspan=7 class=xl9225266>&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=11 height=13 class=xl8125266 style="height:10.15pt"></td>
  <td class=xl8125266></td>
  <td colspan=3 class=xl17325266>подпись</td>
  <td class=xl8325266></td>
  <td colspan=6 class=xl17325266>расшифровка подписи</td>
  <td class=xl7625266>&nbsp;</td>
  <td colspan=5 class=xl7725266 style="border-left:none">&nbsp;</td>
  <td colspan=5 class=xl8525266>должность</td>
  <td class=xl7425266></td>
  <td colspan=3 class=xl8525266>подпись</td>
  <td class=xl7525266></td>
  <td colspan=7 class=xl8525266>расшифровка подписи</td>
 </tr>
 <tr class=xl6325266 height=15 style="mso-height-source:userset;height:11.45pt">
  <td colspan=7 height=15 class=xl8125266 style="height:11.45pt">Отпуск груза
  произвел</td>
  <td colspan=4 class=xl9225266>&nbsp;</td>
  <td class=xl6325266></td>
  <td colspan=3 class=xl9225266>&nbsp;</td>
  <td class=xl6825266></td>
  <td colspan=6 class=xl9225266>'.$savoir['fio'].'</td>
  <td class=xl7325266>&nbsp;</td>
  <td colspan=5 class=xl9125266 style="border-left:none">Груз получил<span
  style="mso-spacerun:yes"> </span></td>
  <td colspan=5 class=xl9225266>&nbsp;</td>
  <td class=xl6325266></td>
  <td colspan=3 class=xl9225266>&nbsp;</td>
  <td class=xl6825266></td>
  <td colspan=7 class=xl9225266>&nbsp;</td>
 </tr>
 <tr class=xl6325266 height=13 style="mso-height-source:userset;height:10.15pt">
  <td colspan=7 height=13 class=xl6325266 style="height:10.15pt"></td>
  <td colspan=4 class=xl8525266>должность</td>
  <td class=xl7425266></td>
  <td colspan=3 class=xl8525266>подпись</td>
  <td class=xl7525266></td>
  <td colspan=6 class=xl8525266>расшифровка подписи</td>
  <td class=xl7325266>&nbsp;</td>
  <td colspan=5 class=xl9125266 style="border-left:none">грузополучатель</td>
  <td colspan=5 class=xl8525266>должность</td>
  <td class=xl7425266></td>
  <td colspan=3 class=xl8525266>подпись</td>
  <td class=xl7525266></td>
  <td colspan=7 class=xl8525266>расшифровка подписи</td>
 </tr>
 <tr class=xl6325266 height=30 style="mso-height-source:userset;height:22.5pt">
  <td colspan=7 height=30 class=xl6525266 style="height:22.5pt">М.П.</td>
  <td class=xl8225266>&quot;</td>
  <td colspan=2 class=xl8625266>&nbsp;</td>
  <td class=xl8425266>&quot;</td>
  <td colspan=6 class=xl8625266>&nbsp;</td>
  <td colspan=5 class=xl8725266>года</td>
  <td class=xl8025266>&nbsp;</td>
  <td colspan=5 class=xl8825266 style="border-left:none"><span
  style="mso-spacerun:yes"> </span>М.П.</td>
  <td class=xl8125266>&quot;</td>
  <td colspan=2 class=xl8925266>&nbsp;</td>
  <td class=xl8125266>&quot;</td>
  <td colspan=6 class=xl8925266>&nbsp;</td>
  <td colspan=7 class=xl9025266>года</td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style="display:none">
  <td width=21 style="width:16pt"></td>
  <td width=13 style="width:10pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=16 style="width:12pt"></td>
  <td width=19 style="width:14pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=20 style="width:15pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=25 style="width:19pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=21 style="width:16pt"></td>
  <td width=16 style="width:12pt"></td>
  <td width=15 style="width:11pt"></td>
  <td width=21 style="width:16pt"></td>
 </tr>
 <![endif]>
</table>

</div>


<!----------------------------->
<!--КОНЕЦ ФРАГМЕНТА ПУБЛИКАЦИИ МАСТЕРА ВЕБ-СТРАНИЦ EXCEL-->
<!----------------------------->
</body>

</html>';
if($_GET['p']==0){
$str .=  '<img src="/img/'.$savoir['mp'].'" style="
    width: 150px;
    position: relative;
    margin-bottom: -90px;
    top: -70px;
">
<img src="/img/'.$savoir['pc'].'" style="
    width: 100px;
    position: relative;
    margin-bottom: -110px;
    margin-left: 80px;
    top: -250px;
">
<img src="/img/'.$savoir['pc'].'" style="
        width: 100px;
    position: relative;
    margin-bottom: -110px;
    margin-left: -100px;
    top: -190px;
">'

;
}
echo $str;

fwrite($fp, $str);

fclose($fp);

function num2str($num) {
	$nul='ноль';
	$ten=array(
		array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
		array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
	);
	$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
	$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
	$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
	$unit=array( // Units
		array('копейка' ,'копейки' ,'копеек',	 1),
		array('рубль'   ,'рубля'   ,'рублей'    ,0),
		array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
		array('миллион' ,'миллиона','миллионов' ,0),
		array('миллиард','милиарда','миллиардов',0),
	);
	//
	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
	$out = array();
	if (intval($rub)>0) {
		foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
			if (!intval($v)) continue;
			$uk = sizeof($unit)-$uk-1; // unit key
			$gender = $unit[$uk][3];
			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
			// mega-logic
			$out[] = $hundred[$i1]; # 1xx-9xx
			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
			// units without rub & kop
			if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
		} //foreach
	}
	else $out[] = $nul;
	$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
	$out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
	return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5) {
	$n = abs(intval($n)) % 100;
	if ($n>10 && $n<20) return $f5;
	$n = $n % 10;
	if ($n>1 && $n<5) return $f2;
	if ($n==1) return $f1;
	return $f5;
}
?>

<script type="text/javascript" src="https://jqueryjs.googlecode.com/files/jquery-1.3.1.min.js" > </script>
<script type="text/javascript">

    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data)
    {
        var mywindow = window.open("", "my div", "height=900,width=1000");
        mywindow.document.write("<html><head><title>Распечатана с GMCRM</title>");
        mywindow.document.write("</head><body >");
        mywindow.document.write(data);
        mywindow.document.write("</body></html>");

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }

</script>


