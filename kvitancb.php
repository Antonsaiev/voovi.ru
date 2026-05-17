


<?php
include 'conf.php';  
include 'invoice_action.php';
include 'nds.php';
$pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);

$sch = "SELECT DISTINCT nomerschet,kolichschet,oferta,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,d,m,y FROM schet WHERE del = '0' AND rand ='".$_GET['id']."'  ORDER BY id DESC";
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

$nds_col_text = ($nds_rate > 0) ? ('НДС: '.(int)$nds_rate.'%') : 'Без НДС';


// На всякий — нормализуем итоговую сумму
$total_sum = (float)str_replace(array(' ', ' ', ','), array('', '', '.'), (string)$pavoir['price']);


$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);

$fsdhhgf1 = "SELECT * FROM users WHERE users_id =".$pavoir['kto'];
$rfsdhhgf1 = mysql_query($fsdhhgf1);
$pfsdhhgf1 = mysql_fetch_array($rfsdhhgf1);


$qr="SELECT * FROM kvobop WHERE schet =$_GET[id]";
$queryk = mysql_query($qr);
$res = mysql_fetch_array($queryk);
$dir = "doc/kvitancop/".$_GET['kli']."/";
$dir2 = "doc/kvitancop/".$_GET['kli']."/".$_GET['id']."/";
mkdir ($dir, 0777);   
mkdir ($dir2, 0777);  
$fp = fopen($dir2."kvitancop.doc", 'w+');
$date = new DateTime(getDateDocuments($_GET['id'])['d_bill']);
$str = '<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name=Generator content="Microsoft Word 14 (filtered)">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:Times;
	panose-1:2 2 6 3 5 4 5 2 3 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	line-height:115%;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";}
.MsoChpDefault
	{font-size:11.0pt;}
.MsoPapDefault 
	{margin-bottom:10.0pt;
	line-height:115%;}
 /* Page Definitions */
 @page WordSection1
	{size:595.25pt 841.85pt;
	margin:31.15pt 31.15pt 31.15pt 45.35pt;}
div.WordSection1
	{page:WordSection1;}
-->
</style>

</head>

<body lang=RU style="text-justify-trim:punctuation"><div class=WordSection1>
<div  id="mydiv">
<p class="MsoNormal" align="center" style="margin-bottom:5.65pt;text-align:center;
line-height:normal;text-autospace:none"><b><span style="font-size: 16pt;
font-family:&quot;Times&quot;,&quot;serif&quot;;color:black"><b>Квитанция об оплате '.$res['nschet'].''.$res['id'].' от '.$date->format('d.m.Y').'</b></span></b></p>
<br>
<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style="border-collapse:collapse">
 <tr style="page-break-inside:avoid">
  <td width=280 colspan=3 valign=top style="width:209.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">ИСПОЛНИТЕЛЬ:</span></b></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style="width:294.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">ЗАКАЗЧИК:</span></b></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid">
  <td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">ИНН:</span></b></p>
  </td>
  <td width=234 colspan=2 valign=top style="width:175.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$savoir['inn'].'</span></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">ИНН:</span></b></p>
  </td>
  <td width=348 colspan=2 valign=top style="width:260.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['inn'].'</span></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid"><td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b>
';
  if ($savoir['kpp'] > 0){
  $str .= "<span style='font-size:10.0pt;font-family:
  Times;color:black'>КПП:</span>";
  }
  
  $str .= '</b></p></td>
  <td width=234 colspan=2 valign=top style="width:175.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$savoir['kpp'].'</span></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">КПП:</span></b></p>
  </td>
  <td width=348 colspan=2 valign=top style="width:260.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['kpp'].'</span></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid">
  <td width=280 colspan=3 valign=top style="width:209.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">'.$savoir['full_name'].'</span></b></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style="width:294.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">'.$klient['naim'].'</span></b></p>
  </td>
 </tr>
 
 <tr height=0>
  <td width=45 style="border:none"></td>
  <td width=26 style="border:none"></td>
  <td width=208 style="border:none"></td>
  <td width=8 style="border:none"></td>
  <td width=45 style="border:none"></td>
  <td width=26 style="border:none"></td>
  <td width=321 style="border:none"></td>
 </tr>
</table>
';

if($savoir['srok'] != 0){
$str .=  "<br><p style='    margin-bottom: 11.3pt;
    text-align: center;line-height:
normal;
    font-size: 29px !important;
    font-weight: bold;'><span style='font-size:9pt;color:black'>Счет действителен в течении ".$savoir['srok']." банковских дней.</span></p>";
}
$str .= '
<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal;text-autospace:none"><span style="font-size:5.0pt;font-family:"Times","serif";
color:black">&nbsp;</span></p>


<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal;text-autospace:none"><span style="font-size:5.0pt;font-family:"Times","serif";
color:black">&nbsp;</span></p>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style="margin-left:2.8pt;border-collapse:collapse">
 <thead>
  <tr>
   <td width=26 style="width:19.8pt;border:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt">
   <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none"><b><span
   style="font-size:10.0pt;font-family:"Times","serif";color:black">№</span></b></p>
   </td>
   <td width=340 style="width:255.1pt;border:solid black 1.0pt;border-left:
   none;padding:1.4pt 2.8pt 1.4pt 2.8pt">
   <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none"><b><span
   style="font-size:10.0pt;font-family:"Times","serif";color:black">Наименование</span></b></p>
   </td>
   <td width=34 style="width:25.5pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt">
   <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none"><b><span
   style="font-size:10.0pt;font-family:"Times","serif";color:black">Ед.</span></b></p>
   </td>
   <td width=53 style="width:39.65pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt">
   <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none"><b><span
   style="font-size:10.0pt;font-family:"Times","serif";color:black">Кол-во</span></b></p>
   </td>
   <td width=76 style="width:56.65pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt">
   <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none"><b><span
   style="font-size:10.0pt;font-family:"Times","serif";color:black">Цена</span></b></p>
   </td>
   <td width=76 style="width:56.65pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt">
   <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none"><b><span
   style="font-size:10.0pt;font-family:"Times","serif";color:black">В т.ч. НДС</span></b></p>
   </td>
   <td width=76 style="width:56.65pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt">
   <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none"><b><span
   style="font-size:10.0pt;font-family:"Times","serif";color:black">Сумма</span></b></p>
   </td>
  </tr>
 </thead>';
$sum_vat = 0.0;
$i = 1;
$query = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = '".$_GET['id']."'");
while($row = mysql_fetch_array($query)) {
if(!empty($row['prod'])){
$rpod = "SELECT * FROM tarif WHERE id =".$row['prod'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);

$vowels = array(" "," ");
$price = str_replace($vowels, "", $personrpod['price']);
//echo number_format($price, 2, '.', ',');
$qty = (float)$row['kvo'];
$unit_gross = (float)str_replace(',', '.', (string)$price);
$line_gross = round($unit_gross * $qty, 2);
$line_vat   = nds_included_sum($line_gross, $nds_rate);
$sum_vat   += $line_vat;

$str .=  "<tr><td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>".$i++.'</span></p></td><td  width=26 style="width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt"><p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;text-justify:inter-ideograph;line-height:normal;text-autospace:none"><span
  style="font-size:8.0pt;font-family:Times;color:black">'.$personrpod['name'].'</span></p></td><td  width=26 style="width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt"><p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none"><span
  style="font-size:8.0pt;font-family:Times;color:black">'.$personrpod['shtuk'].'</span></p></td><td  width=26 style="width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt"><p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none"><span
  style="font-size:8.0pt;font-family:Times;color:black">'.$row['kvo'].'</span></p></td><td  width=26 style="width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt"><p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none"><span
  style="font-size:8.0pt;font-family:Times;color:black">'.number_format($price, 2, '.', ',').'</span></p></td><td  width=26 style="width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt"><p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none"><span
  style="font-size:8.0pt;font-family:Times;color:black">';
$str .= $nds_col_text;
$str .=  '</span></p></td><td  width=26 style="width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt"><p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none"><span
  style="font-size:8.0pt;font-family:Times;color:black">'.number_format($line_gross, 2, '.', ',').'</span></p></td></tr>';
}
}

  if ($pavoir['goroddd'] > 0 && $pavoir['goroddd'] != 162534){
  $str .=  "
 <tr>
  <td width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>-</span></p>
  </td>
  <td width=340 style='width:255.1pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;text-justify:inter-ideograph;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>Выезд</span></p>
  </td>
  <td width=34 style='width:25.5pt;border-top:none;border-left:none;border-bottom:
  solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>км</span></p>
  </td>
  <td width=53 style='width:65.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>"; 
  if($pavoir['goroddd'] == 450){
  $str .=  "Пятигорск (".$pavoir['kvogorod'].")";
  } else if($pavoir['goroddd'] == 750){
  $str .=  "КМВ (".$pavoir['kvogorod'].")";
  } else if($pavoir['goroddd'] == 1500){
  $str .=  "Георгиевск (".$pavoir['kvogorod'].")";
  }else{
  $str .=  $pavoir['goroddd'] / 23 ."(".$pavoir['kvogorod'].")";
  }
  $str .= "</span></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>"; 
  if($pavoir['goroddd'] == 450){
  $str .=  number_format(450, 2, '.', ',');
  } else if($pavoir['goroddd'] == 750){
  $str .=  number_format(750, 2, '.', ',');
  } else if($pavoir['goroddd'] == 1500){
  $str .=  number_format(1500, 2, '.', ',');
  }else{
  $str .=  number_format(23, 2, '.', ',');
  }
  $str .= "</span></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span style='font-size:8.0pt;font-family:Times;color:black'>".$nds_col_text."</span></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";

$line_gross = round($pavoir['goroddd'] * $pavoir['kvogorod'], 2);
$line_vat   = nds_included_sum($line_gross, $nds_rate);
$sum_vat   += $line_vat;

$str .= number_format($line_gross, 2, '.', ',');
  
  $str .= "</span></p>
  </td>
 </tr>
 ";
  }
 
  if ($pavoir['skidka'] > 0){
  $str .=  "<span style='font-size:10.0pt;font-family:
  Times;color:black'>КПП:</span>
 <tr>
  <td width=604 colspan=6 style='width:453.35pt;border:solid black 1.0pt;
  border-top:none;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><b><span
  style='font-size:8.0pt;font-family:Times;color:black'>Скидка:</span></b></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";$str .=  number_format($pavoir['skidka'], 2, '.', ',');$str .= " %</span></p>
  </td>
 </tr>
 ";
  }

 
 $str .= '<tr>
  <td width=604 colspan=6 style="width:453.35pt;border:solid black 1.0pt;
  border-top:none;padding:1.4pt 2.8pt 1.4pt 2.8pt">
  <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none"><b><span
  style="font-size:8.0pt;font-family:"Times","serif";color:black">Итого:</span></b></p>
  </td>
  <td width=76 style="width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt">
  <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none"><span
  style="font-size:8.0pt;font-family:"Times","serif";color:black">'.number_format($pavoir['price'], 2, '.', ',').'</span></p>
  </td>
 </tr>';

// <-- ВОТ СЮДА вставляем строку "В т.ч. НДС ..."
if ($nds_code !== 'none') {
    $nds_sum = round($sum_vat, 2);

    $str .= '<tr>
    <td width=604 colspan=6 style="width:453.35pt;border:solid black 1.0pt;
    border-top:none;padding:1.4pt 2.8pt 1.4pt 2.8pt">
    <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
    text-align:right;line-height:normal;text-autospace:none"><b><span
    style="font-size:8.0pt;font-family:&quot;Times&quot;,&quot;serif&quot;;color:black">В т.ч. НДС '.$nds_rate.'%:</span></b></p>
    </td>
    <td width=76 style="width:56.65pt;border-top:none;border-left:none;
    border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt">
    <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
    text-align:right;line-height:normal;text-autospace:none"><span
    style="font-size:8.0pt;font-family:&quot;Times&quot;,&quot;serif&quot;;color:black">'.number_format($nds_sum, 2, '.', ',').'</span></p>
    </td>
  </tr>';
}

$str .= '

</table>
<br>
<br>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style="border-collapse:collapse">
 <tr>
  <td width=333 colspan=2 valign=top style="width:249.4pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">Менеджер:<span style="border-bottom: 1px solid;">&nbsp;'.$pfsdhhgf1['f_name'].'&nbsp;'.$pfsdhhgf1['l_name'].'&nbsp;'.$pfsdhhgf1['o_name'].'</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="border-bottom: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></p>
  </td>
 </tr>
 <tr>
  <td width=333 colspan=2 valign=top style="width:249.4pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">МП<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="border-bottom: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></p>
  </td>
</tr>

 <tr height=0>
  <td width=162 style="border:none"></td>
  <td width=170 style="border:none"></td>
  <td width=162 style="border:none"></td>
  <td width=166 style="border:none"></td>
 </tr>
</table>

</div>
</div>
</body>

</html>';

echo $str;
/*
<img src="/img/1.jpg" style="
    width: 700px;
	margin-top: 50px;
">
*/
fwrite($fp, "\xEF\xBB\xBF".$str);

fclose($fp);
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