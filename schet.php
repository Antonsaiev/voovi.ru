<?php

header('Content-Type: text/html; charset=UTF-8');

# подключаем конфиг
include 'conf.php';

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
include 'invoice_action.php';
include 'nds.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$kli_id = isset($_GET['kli']) ? (int)$_GET['kli'] : 0;


$pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);

$sch = "SELECT DISTINCT nomerschet,kolichschet,oferta,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,d,m,y FROM schet WHERE del = '0' AND rand ='".$id."'  ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult);

$kli = "SELECT * FROM ogrn WHERE id =".$kli_id;
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

$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);
$dir = "doc/".$kli_id."/";
$dir2 = "doc/".$kli_id."/".$id."/";
mkdir ($dir, 0777);   
mkdir ($dir2, 0777);  
$fp = fopen($dir2."schet.doc", 'w+');

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

<body lang=RU style="text-justify-trim:punctuation"><div class="">
<div  id="mydiv">
<p class="MsoNormal" align="center" style="margin-bottom:5.65pt;text-align:center;
line-height:normal;text-autospace:none"><b><span style="font-size: 22pt;
font-family:&quot;Times&quot;,&quot;serif&quot;;color:black">'.$savoir['name'].'</span></b></p>
<br><table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style="border-collapse:collapse">
 <tr style="page-break-inside:avoid">
  <td width=280 colspan=3 valign=top style="width:209.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none;color:#d3d3d3"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:#d3d3d3">Исполнитель:</span></b></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style="width:294.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none;color:#d3d3d3"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:#d3d3d3">Заказчик:</span></b></p>
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
  <td width=348 colspan=1 valign=top style="width:60.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['inn'].'</span></p>
  </td>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b>
';
  if ($savoir['kpp'] > 0){
  $str .= "<span style='font-size:10.0pt;font-family:
  Times;color:black'>КПП:</span>";
  }
  
  $str .= '</b></p></td>

  <td width=348 colspan=2 valign=top style="width:260.7pt;padding:0cm 0cm 0cm 0cm">
     <p class=MsoNormal style=" float: left;margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">КПП:&nbsp;&nbsp;</span></b></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['kpp'].'</span></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid"><td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  
 </tr>

 <tr style="page-break-inside:avoid">
  <td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">Адрес:</span></b></p>
  </td>
  <td width=234 colspan=2 valign=top style="width:375.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$savoir['adres'].'</span></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">Адрес:</span></b></p>
  </td>
  <td width=348 colspan=2 valign=top style="width:260.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['uridadress'].'</span></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid">
  <td width=280 colspan=3 valign=top style="width:209.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">Реквизиты для расчетов:</span></b></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style="width:294.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">Реквизиты для расчетов:</span></b></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid">
  <td width=72 colspan=2 valign=top style="width:53.8pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">Рс. счет:</span></b></p>
  </td>
  <td width=208 valign=top style="width:155.9pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$savoir['r_schet'].'</span></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=72 colspan=2 valign=top style="width:53.8pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">Рс. счет:</span></b></p>
  </td>
  <td width=321 valign=top style="width:240.9pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['r_schet'].'</span></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid">
  <td width=72 colspan=2 valign=top style="width:53.8pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">Корр. счет:</span></b></p>
  </td>
  <td width=208 valign=top style="width:155.9pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$savoir['k_schet'].'</span></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=72 colspan=2 valign=top style="width:53.8pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">Корр. счет:</span></b></p>
  </td>
  <td width=321 valign=top style="width:240.9pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['k_schet'].'</span></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid">
  <td width=280 colspan=3 valign=top style="width:209.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$savoir['bank'].'</span></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">Адрес:'.$savoir['city'].'</span></p>
  </td>
  
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style="width:294.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['bank'].'</span></p>
  </td>
 </tr>
 <tr style="page-break-inside:avoid">
  <td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">БИК:</span></b></p>
  </td>
  <td width=234 colspan=2 valign=top style="width:175.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$savoir['bik'].'</span></p>
  </td>
  <td width=8 valign=top style="width:5.65pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">&nbsp;</span></p>
  </td>
  <td width=45 valign=top style="width:34.0pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:
  "Times","serif";color:black">БИК:</span></b></p>
  </td>
  <td width=348 colspan=2 valign=top style="width:260.7pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$klient['bik'].'</span></p>
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

<p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none"><b><span
style="font-size:10.0pt;font-family:"Times","serif";color:black">СЧЕТ-ОФЕРТА';
/*if($schet['oferta'] == 1){
$str .=  ;
}*/
$date = new DateTime(getDateDocuments($id)['d_bill']);
$str .= '&nbsp;№&nbsp;'.$schet['god'].$schet['kto'].$schet['otdel'].$schet['kolichschet'].'&nbsp;от&nbsp;'.$date->format('d.m.Y').'</span></b></p>

<p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none"><b><span
style="font-style: italic;font-size:10.0pt;font-family:"Times","serif"; color:black">Просьба в назначении платежа указать:&nbsp;№&nbsp;'.$schet['god'].$schet['kto'].$schet['otdel'].$schet['kolichschet'].'&nbsp;ИНН&nbsp;'.$klient['inn'].'&nbsp;КПП&nbsp;'.$klient['kpp'].'&nbsp;&nbsp;'.$klient['naim'].'</span></b></p>

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
$query = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = '".$id."'");
while($row = mysql_fetch_array($query)) {
if(!empty($row['prod'])){
$rpod = "SELECT * FROM tarif WHERE id =".$row['prod'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);

$vowels = array(" "," ");
$price = str_replace($vowels, "", $personrpod['price']);
$qty = (float)$row['kvo'];
$unit_gross = (float)str_replace(',', '.', (string)$price);
$line_gross = round($unit_gross * $qty, 2);
$line_vat = nds_included_sum($line_gross, $nds_rate);
$sum_vat += $line_vat;
//echo number_format($price, 2, '.', ',');

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
  style="font-size:8.0pt;font-family:Times;color:black">'.number_format($price * $row['kvo'], 2, '.', ',').'</span></p></td></tr>';
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
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>'.$nds_col_text.'</span></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";

$kvogorod = $pavoir['goroddd'] * $pavoir['kvogorod'];
  $str .=  number_format($kvogorod, 2, '.', ',');
  
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

// если НДС не "none" — добавляем строку "в т.ч. НДС"
if ($nds_code !== 'none' && $nds_rate >= 0) {
    $nds_sum = 0;
    if ($nds_rate > 0) {
        $nds_sum = round($sum_vat, 2);
    }
    $str .= '<tr>
    <td width=604 colspan=6 style="width:453.35pt;border:solid black 1.0pt;
    border-top:none;padding:1.4pt 2.8pt 1.4pt 2.8pt">
    <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
    text-align:right;line-height:normal;text-autospace:none"><b><span
    style="font-size:8.0pt;font-family:"Times","serif";color:black">В т.ч. НДС '.$nds_rate.'%:</span></b></p>
    </td>
    <td width=76 style="width:56.65pt;border-top:none;border-left:none;
    border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt">
    <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
    text-align:right;line-height:normal;text-autospace:none"><span
    style="font-size:8.0pt;font-family:"Times","serif";color:black">'.number_format($nds_sum, 2, '.', ',').'</span></p>
    </td>
   </tr>';
}

$str .= '

</table>
<br>
';

// если без НДС — выводим старую приписку, иначе можно вообще ничего не писать (или написать "НДС включен")
if ($nds_code === 'none') {
    $str .= '<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none;text-align: center;"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">Не облагается НДС в соответствии с п.2 ст.346.11 Налогового кодекса Российской Федерации</span></p>';
} else {
    // опционально (если хочешь строку под таблицей)
    // $str .= '<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
    // normal;text-autospace:none;text-align: center;"><span style="font-size:10.0pt;font-family:"Times","serif";
    // color:black">Стоимость указана с учетом НДС</span></p>';
}

$str .= '
<br>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style="border-collapse:collapse">

 <tr>
  <td width=333 colspan=2 valign=top style="width:249.4pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style="font-size:10.0pt;font-family:"Times","serif";
  color:black">'.$savoir['office'].'</span></p>
  </td>
 </tr>
 ...
';
$query = [
    'naimenovanie' => $savoir['full_name'],
    'nch'=> $savoir['r_schet'],
    'chkor'=> $savoir['k_schet'],
    'bik'=> $savoir['bik'],
    'innpolu'=> $savoir['inn'],
    /*'uridadress'=> $klient['uridadress'],*/
    'price'=> number_format($pavoir['price'], 2, '.', ','),
    'bank'=> $savoir['bank'],
    'ns'=>$schet['god'].$schet['kto'].$schet['otdel'].$schet['kolichschet'],
];
printf('<img src="genqri.php?%s" style="float: right;margin-top: 20px;width: 150px;">', http_build_query($query));
if($_GET['p']==0){
$str .=  '<img src="/img/'.$savoir['mp'].'" style="
    width: 150px;
    position: relative;
    margin-bottom: -90px;
    top: -100px;
">
<img src="/img/'.$savoir['pc'].'" style="
    width: 150px;
    position: relative;
    margin-bottom: -110px;
    margin-left: -150px;
    top: -126px;
">';

}


if($schet['oferta'] == 1){
$str .=  "<br><br><br><p class=MsoNormal style='margin-bottom:11.3pt;line-height:normal;text-autospace:
none'><span style='font-size:9pt;color:black'>".$savoir['primechan']."</span></p>";
}

$str .= '
<br>
<p class=MsoNormal align=center style="margin-bottom:5.65pt;text-align:center;
line-height:normal;text-autospace:none"><b><span style="font-size:10.0pt;
font-family:"Times","serif";color:black">ТЕЛЕФОН СЕРВИСНОГО ЦЕНТРА&nbsp;'.$savoir['tel'].'</span></b></p>
</div>
</div>
<input type="button" value="P"  onclick="PrintElem(\'#mydiv\')" />
</body>

</html>';

echo $str;

fwrite($fp, $str);

fclose($fp);
?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript">

    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data)
    {
        var mywindow = window.open("", "my div", "height=600,width=1000");
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


