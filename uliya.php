<?php
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
$pav = "SELECT * FROM schet WHERE rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);

$sch = "SELECT DISTINCT nomerschet,oferta,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka FROM schet WHERE rand ='".$_GET['id']."'  ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult);

$kli = "SELECT * FROM ogrn WHERE ogrn =".$schet['ogrn'];
$kliresult = mysql_query($kli);
$klient = mysql_fetch_array($kliresult);

$sav1 = "SELECT * FROM produkti WHERE id = '".$schet['produkt']."' ORDER BY id DESC";
$savresult1 = mysql_query($sav1);
$savoir1 = mysql_fetch_array($savresult1);

$sav = "SELECT * FROM uslugi WHERE id = '".$savoir1['parent']."' ORDER BY id DESC";
$savresult = mysql_query($sav);
$savoir = mysql_fetch_array($savresult);
$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);
?>
<html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
<meta name=Generator content="Microsoft Word 14 (filtered)">

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

<body lang=RU style='text-justify-trim:punctuation'>

<div class=WordSection1>
<p class="MsoNormal" align="center" style="margin-bottom:5.65pt;text-align:center;
line-height:normal;text-autospace:none"><b><span style="font-size: 22pt;
font-family:&quot;Times&quot;,&quot;serif&quot;;color:black">ИЦ SAVOIR</span></b></p>
<p class="MsoNormal" align="center" style="margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none"><b><span style="font-size:10.0pt;font-family:&quot;Times&quot;,&quot;serif&quot;;color:black">ПРОСЬБА В ПЛАТЕЖНОМ
ПОРУЧЕНИИ УКАЗЫВАТЬ НОМЕР И ДАТУ ДАННОГО СЧЕТА</span></b></p>
<br>
<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='border-collapse:collapse'>
 <tr style='page-break-inside:avoid'>
  <td width=280 colspan=3 valign=top style='width:209.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>ЛИЦЕНЗИАР:</span></b></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style='width:294.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>ЛИЦЕНЗИАТ:</span></b></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>ИНН:</span></b></p>
  </td>
  <td width=234 colspan=2 valign=top style='width:175.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['inn'];?></span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>ИНН:</span></b></p>
  </td>
  <td width=348 colspan=2 valign=top style='width:260.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $klient['inn'];?></span></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'><td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b>
  <?php 
  if ($savoir['kpp'] > 0){
  echo "<span style='font-size:10.0pt;font-family:
  Times;color:black'>КПП:</span>";
  }
  ?>
  </b></p></td>
  <td width=234 colspan=2 valign=top style='width:175.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['kpp'];?></span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>КПП:</span></b></p>
  </td>
  <td width=348 colspan=2 valign=top style='width:260.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $klient['kpp'];?></span></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=280 colspan=3 valign=top style='width:209.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'><?php echo $savoir['full_name'];?></span></b></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style='width:294.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'><?php echo $klient['naim'];?></span></b></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Адрес:</span></b></p>
  </td>
  <td width=234 colspan=2 valign=top style='width:175.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['adres'];?></span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Адрес:</span></b></p>
  </td>
  <td width=348 colspan=2 valign=top style='width:260.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $klient['uridadress'];?></span></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=280 colspan=3 valign=top style='width:209.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=72 colspan=2 valign=top style='width:53.8pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Телефон:</span></b></p>
  </td>
  <td width=321 valign=top style='width:240.9pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $klient['tel'];?></span></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=280 colspan=3 valign=top style='width:209.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=72 colspan=2 valign=top style='width:53.8pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Факс:</span></b></p>
  </td>
  <td width=321 valign=top style='width:240.9pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=280 colspan=3 valign=top style='width:209.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Реквизиты для расчетов:</span></b></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style='width:294.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Реквизиты для расчетов:</span></b></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=72 colspan=2 valign=top style='width:53.8pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Рс. счет:</span></b></p>
  </td>
  <td width=208 valign=top style='width:155.9pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['r_schet'];?></span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=72 colspan=2 valign=top style='width:53.8pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Рс. счет:</span></b></p>
  </td>
  <td width=321 valign=top style='width:240.9pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $klient['r_schet'];?></span></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=72 colspan=2 valign=top style='width:53.8pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Корр. счет:</span></b></p>
  </td>
  <td width=208 valign=top style='width:155.9pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['k_schet'];?></span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=72 colspan=2 valign=top style='width:53.8pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>Корр. счет:</span></b></p>
  </td>
  <td width=321 valign=top style='width:240.9pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $klient['k_schet'];?></span></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=280 colspan=3 valign=top style='width:209.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['bank'];?></span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=393 colspan=3 valign=top style='width:294.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $klient['bank'];?></span></p>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>БИК:</span></b></p>
  </td>
  <td width=234 colspan=2 valign=top style='width:175.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['bik'];?></span></p>
  </td>
  <td width=8 valign=top style='width:5.65pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><b><span style='font-size:10.0pt;font-family:
  "Times","serif";color:black'>БИК:</span></b></p>
  </td>
  <td width=348 colspan=2 valign=top style='width:260.7pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $klient['bik'];?></span></p>
  </td>
 </tr>
 <tr height=0>
  <td width=45 style='border:none'></td>
  <td width=26 style='border:none'></td>
  <td width=208 style='border:none'></td>
  <td width=8 style='border:none'></td>
  <td width=45 style='border:none'></td>
  <td width=26 style='border:none'></td>
  <td width=321 style='border:none'></td>
 </tr>
</table>

<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal;text-autospace:none'><span style='font-size:5.0pt;font-family:"Times","serif";
color:black'>&nbsp;</span></p>

<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none'><b><span
style='font-size:10.0pt;font-family:"Times","serif";color:black'>СЧЕТ<?php
if($schet['oferta'] == 1){
echo '-ОФЕРТА';
}?>&nbsp;№&nbsp;<?php echo $schet['god'].$schet['filial'].$schet['otdel'].$pfsdhhgf['id'].$schet['nomerschet'];?></span></b></p>

<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none'><b><span
style='font-size:10.0pt;font-family:"Times","serif";color:black'>от&nbsp;<?php echo substr($schet['data'], 0, 8);?></span></b></p>

<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal;text-autospace:none'><span style='font-size:5.0pt;font-family:"Times","serif";
color:black'>&nbsp;</span></p>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='margin-left:2.8pt;border-collapse:collapse'>
 <thead>
  <tr>
   <td width=26 style='width:19.8pt;border:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
   <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none'><b><span
   style='font-size:10.0pt;font-family:"Times","serif";color:black'>№</span></b></p>
   </td>
   <td width=340 style='width:255.1pt;border:solid black 1.0pt;border-left:
   none;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
   <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none'><b><span
   style='font-size:10.0pt;font-family:"Times","serif";color:black'>Наименование</span></b></p>
   </td>
   <td width=34 style='width:25.5pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt'>
   <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none'><b><span
   style='font-size:10.0pt;font-family:"Times","serif";color:black'>Ед.</span></b></p>
   </td>
   <td width=53 style='width:39.65pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt'>
   <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none'><b><span
   style='font-size:10.0pt;font-family:"Times","serif";color:black'>Кол-во</span></b></p>
   </td>
   <td width=76 style='width:56.65pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt'>
   <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none'><b><span
   style='font-size:10.0pt;font-family:"Times","serif";color:black'>Цена</span></b></p>
   </td>
   <td width=76 style='width:56.65pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt'>
   <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none'><b><span
   style='font-size:10.0pt;font-family:"Times","serif";color:black'>В т.ч. НДС</span></b></p>
   </td>
   <td width=76 style='width:56.65pt;border:solid black 1.0pt;border-left:none;
   padding:1.4pt 2.8pt 1.4pt 2.8pt'>
   <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
   text-align:center;line-height:normal;text-autospace:none'><b><span
   style='font-size:10.0pt;font-family:"Times","serif";color:black'>Сумма</span></b></p>
   </td>
  </tr>
 </thead>
 
 
<?php
$i = 1;
$query = mysql_query("SELECT * FROM schet WHERE rand = '".$_GET['id']."'");
while($row = mysql_fetch_array($query)) {
echo "<tr><td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";

echo $i++;
echo '</span></p></td>';
echo '<td  width=26 style="width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt"><p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;text-justify:inter-ideograph;line-height:normal;text-autospace:none"><span
  style="font-size:8.0pt;font-family:Times;color:black">';
  
$rpod = "SELECT * FROM tarif WHERE id =".$row['prod'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
  
echo '</span></p></td>';
echo "<td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";
echo $personrpod['shtuk'];
echo '</span></p></td>';
echo "<td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";
echo $row['kvo'];
echo '</span></p></td>';
echo "<td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";
  
echo number_format($personrpod['price'], 2, '.', ' ');

echo '</span></p></td>';
echo "<td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";
  
  if($personrpod['shtuk'] == 0){
  echo 'Без НДС';
  } else {
  echo 'НДС: ',$personrpod['shtuk'],'%';
  }
echo '</span></p></td>';
echo "<td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";
  echo number_format($personrpod['price'] * $row['kvo'], 2, '.', ' ');
echo '</span></p></td></tr>';
}


?>
 <?php 
  if ($pavoir['goroddd'] > 0){
  echo "
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
  <td width=53 style='width:39.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>"; 
  if($pavoir['goroddd'] == 450){
  echo "Пятигорск";
  } else if($pavoir['goroddd'] == 750){
  echo "КМВ";
  }else{
  echo $pavoir['goroddd'] / 23;
  }
  echo"</span></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>"; 
  if($pavoir['goroddd'] == 450){
  echo number_format(450, 2, '.', ' ');
  } else if($pavoir['goroddd'] == 750){
  echo number_format(750, 2, '.', ' ');
  }else{
  echo number_format(23, 2, '.', ' ');
  }
  echo"</span></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>Без НДС</span></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>"; echo number_format($pavoir['goroddd'], 2, '.', ' ');echo"</span></p>
  </td>
 </tr>
 ";
  }
  ?>
  <?php 
  if ($pavoir['skidka'] > 0){
  echo "<span style='font-size:10.0pt;font-family:
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
  style='font-size:8.0pt;font-family:Times;color:black'>";echo $pavoir['skidka'];echo" %</span></p>
  </td>
 </tr>
 ";
  }
  ?>
 
 <tr>
  <td width=604 colspan=6 style='width:453.35pt;border:solid black 1.0pt;
  border-top:none;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><b><span
  style='font-size:8.0pt;font-family:"Times","serif";color:black'>Итого:</span></b></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:"Times","serif";color:black'><?php echo number_format($pavoir['price'], 2, '.', ' ');?></span></p>
  </td>
 </tr>

 
 
 
</table>
<br>
<br>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='border-collapse:collapse'>
 <tr>
  <td width=333 colspan=2 valign=top style='width:249.4pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['office'];?></span></p>
  </td>
 </tr>
 <tr>
  <td width=162 valign=top style='width:121.85pt;border:none;border-bottom:
  solid black 1.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=170 valign=bottom style='width:127.55pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;<?php echo $savoir['fio'];?></span></p>
  </td>
  </td>
 </tr>
 <tr>
  <td width=162 valign=top style='width:121.85pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:"Times","serif";color:black'>МП</span></p>
  </td>
  <td style='border:none;padding:0cm 0cm 0cm 0cm' width=499 colspan=3><p class='MsoNormal'>&nbsp;</td>
 </tr>
 <tr height=0>
  <td width=162 style='border:none'></td>
  <td width=170 style='border:none'></td>
  <td width=162 style='border:none'></td>
  <td width=166 style='border:none'></td>
 </tr>
</table>
<?php 
if($_GET['p']==0){
echo '<img src="/img/mp.png" style="
    width: 150px;
    position: relative;
    margin-bottom: -90px;
    top: -100px;
">
<img src="/img/pс.png" style="
    width: 101px;
    position: relative;
    margin-bottom: -90px;
    margin-left: -100px;
    top: -126px;
">';
}
?>

<?php
if($schet['oferta'] == 1){
echo "<p class=MsoNormal style='margin-bottom:11.3pt;line-height:normal;text-autospace:
none'><span style='font-size:9pt;color:black'>Действующая версия Договора является официальным документом и размещается в свободном доступе на стендах в офисах ИЦ «Savoir» и на сайте ИЦ \"\Savoir\"\ в сети Интернет по адресу http://infosavoir.ru/doc/ . Перед подачей очередной Заявки, Заказчик обязан ознакомиться с наличием имеющихся изменений. </span></p>";
}?>

<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none'><b><span
style='font-size:10.0pt;font-family:"Times","serif";color:black'>ПРОСЬБА В ПЛАТЕЖНОМ
ПОРУЧЕНИИ УКАЗЫВАТЬ НОМЕР И ДАТУ ДАННОГО СЧЕТА</span></b></p>

<p class=MsoNormal align=center style='margin-bottom:5.65pt;text-align:center;
line-height:normal;text-autospace:none'><b><span style='font-size:10.0pt;
font-family:"Times","serif";color:black'>ТЕЛЕФОН СЕРВИСНОГО ЦЕНТРА&nbsp;<?php echo $savoir['tel'];?></span></b></p>
</div>

</body>

</html>
