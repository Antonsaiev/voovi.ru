<?php
include 'conf.php';
include 'invoice_action.php';

$pav = "SELECT * FROM schet WHERE rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);


$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);

$sch = "SELECT DISTINCT nomerschet,kolichschet,data,d,m,y,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,produkt,price,kto,inn,kpp,koment,ogrn,skidka,podpisant FROM schet WHERE rand ='".$_GET['id']."'  ORDER BY id DESC";
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
// -------------
$ogip = strlen($klient['inn']);
// -------------
$lis = "SELECT * FROM klient WHERE id =".$schet['podpisant'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
// -------------
$ssser = mb_substr(strstr($personlis['fio']," "),1,99,'UTF-8');
$srachnah = strstr($ssser," ");
// -------------
$god = $schet['y']+1;


$dir = "doc/".$_GET['kli']."/";
$dir2 = "doc/".$_GET['kli']."/".$_GET['id']."/";
mkdir ($dir, 0777);   
mkdir ($dir2, 0777);  
$fp = fopen($dir2."dogovor.doc", 'w+');

$dogDate = new DateTime(getDateDocuments($_GET['id'])['d_contract']);
$str ='<html> 
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf8">
<meta name=Generator content="Microsoft Word 14 (filtered)">
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-familyy:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-familyy:"Arial CYR";
	panose-1:2 11 6 4 2 2 2 2 2 4;}
@font-face
	{font-familyy:Verdana;
	panose-1:2 11 6 4 3 5 4 4 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	line-height:100%;
	font-sizee:11.0pt;
	font-familyy:"Calibri","sans-serif";}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:36.0pt;
	line-height:100%;
	font-sizee:11.0pt;
	font-familyy:"Calibri","sans-serif";}
p.MsoListParagraphCxSpFirst, li.MsoListParagraphCxSpFirst, div.MsoListParagraphCxSpFirst
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	line-height:100%;
	font-sizee:11.0pt;
	font-familyy:"Calibri","sans-serif";}
p.MsoListParagraphCxSpMiddle, li.MsoListParagraphCxSpMiddle, div.MsoListParagraphCxSpMiddle
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	line-height:100%;
	font-sizee:11.0pt;
	font-familyy:"Calibri","sans-serif";}
p.MsoListParagraphCxSpLast, li.MsoListParagraphCxSpLast, div.MsoListParagraphCxSpLast
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:36.0pt;
	line-height:100%;
	font-sizee:11.0pt;
	font-familyy:"Calibri","sans-serif";}
.MsoChpDefault
	{font-familyy:"Calibri","sans-serif";}
.MsoPapDefault
	{margin-bottom:10.0pt;
	line-height:100%;} 
@page WordSection1
	{size:595.3pt 841.9pt;
	margin:1.0cm 21.2pt 35.45pt 49.65pt;}
div.WordSection1
	{page:WordSection1;}
-->
</style>
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body lang=RU style="
    font-size: 7pt;;
    margin: 0;
    padding: 0;
	font-family: "HelveticaNeueCyr";
">

<div class=WordSection1>
<p class="MsoNormal" align="center" style="margin-bottom:5.65pt;text-align:center;
line-height:normal;text-autospace:none"><b><span style="font-size: 22pt;
font-familyy:&quot;Times&quot;,&quot;serif&quot;;color:black">'.$savoir['name'].'</span></b></p>
<p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal"><a name="RANGE!C3:U128"><b><span
style="">Договор №
'.$schet['god'].$schet['kto'].$schet['otdel'].$schet['nomerdog'].'</span></b></a></p>

<p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal"><b><span lang=EN-US style="font-sizee:
12.0pt;">&nbsp;</span></b></p>

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style="font-size: 7pt;;border-collapse:collapse;border:none">
 <tr>
  <td width=362 valign=top style="width:271.15pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">'.$savoir['dogorod'].'</span></p>
  </td>
  <td width=362 valign=top style="width:271.2pt;border:none;padding:0cm 5.4pt 0cm 5.4pt">
  <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-size: 7pt;
  ">от '.$dogDate->format('d.m.Y').' г.</span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;font-size: 7pt;text-align:
justify;line-height:normal"><span style="font-size: 7pt;">'.$klient['naim'].'</span><span
style="font-size: 7pt;"> именуем'; 

if($ogip == 12){
$str .= 'ый ';
}else if($ogip == 10){
$str .= 'ое ';
}else{
$str .= $ogip;
}
$str .= 'в дальнейшем «Заказчик» в лице '.$personlis['dol'].' ';
$str .= strstr($personlis['fio']," ", true);
$str .= mb_substr(strstr($personlis['fio']," "),0,2,'UTF-8').".";
$str .= mb_substr(strstr($srachnah," "),0,2,'UTF-8');
$str .= '. действующего на основании</span> '.$personlis['naosnovanii'].' <span
style="font-size: 7pt;">с одной стороны, и 
'.$savoir['name'].', в лице '.$savoir['headog'].'</span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size: 7pt;">&nbsp;</span></p>





';
if($savoir['VOOVID1']==1){
$str .= strstr($savoir['dogovor'],"VOOVID1", true);
$str .= $schet['d'].'.'.$schet['m'].'.'.$god;
$str .= mb_substr(strstr($savoir['dogovor'],"VOOVID1"),7,99999999999999,'UTF-8');
}else{
$str .= $savoir['dogovor'];
}












$str .= '<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=707
 style="width:529.9pt;font-size: 7pt;;border-collapse:collapse">
 <tr style="height:14.25pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:14.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><b><span lang=EN-US style="font-size: 7pt;
  "></span></b><b><span style="font-size: 7pt;
  ">СВЕДЕНИЯ ОБ ОПЕРАТОРЕ</span></b></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:14.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><b><span lang=EN-US style="font-size: 7pt;
  "></span></b><b><span style="font-size: 7pt;
  ">СВЕДЕНИЯ ОБ АБОНЕНТЕ</span></b></p>
  </td>
 </tr>
 <tr style="height:14.25pt">
  <td width=359 valign=top style="width:269.35pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:14.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size: 7pt;">Наименование: </span></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size: 7pt;">'.$savoir['full_name'].'</span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:14.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size: 7pt;">Наименование:
  </span></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size: 7pt;">'.$klient['naim'].'</span></p>
  </td>
 </tr>
 <tr style="height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">Юридический адрес: '.$savoir['uridadress'].'
  <br></span></p> 
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">Фактический адрес: '.$savoir['adres'].'</span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">Юридический адрес: '.$klient['uridadress'].'<br></span></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">Фактический адрес: '.$klient['fakadress'].'</span></p>
  </td>
 </tr>
 <tr style="height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">ИНН
  '.$savoir['inn'].' </span><span lang=EN-US style="font-size: 7pt;">/</span><span
  style="font-size: 7pt;">';
  if ($savoir['kpp'] > 0){
  $str .= "<span style='font-size: 7pt;font-familyy:
  Times;color:black'>КПП:</span>";
  }
  $str .= $savoir['kpp'].'</span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">ИНН
  '.$klient['inn'].'</span><span lang=EN-US style="font-size: 7pt;">/</span><span
  lang=EN-US style="font-size: 7pt;"> </span><span
  style="font-size: 7pt;">КПП '.$klient['kpp'].'</span></p>
  </td>
 </tr>
 <tr style="height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">Р/счет
  № '.$savoir['r_schet'].'</span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">Р/счет
  № '.$klient['r_schet'].'</span></p>
  </td>
 </tr>
 <tr style="height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">в
  '.$savoir['bank'].'</span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">в
  '.$klient['bank'].'</span></p>
  </td>
 </tr>
 <tr style="height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">кор/счет
  № '.$savoir['k_schet'].'</span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">кор/счет
  № '.$klient['k_schet'].'</span></p>
  </td>
 </tr>
 <tr style="height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">БИК
  '.$savoir['bik'].'</span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size: 7pt;">БИК
  '.$klient['bik'].'</span></p>
  </td>
 </tr>

 <tr style="height:12.75pt; margin-top: 20px">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;"></span></p>
  </td>
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;"></span></p>
  </td>
 </tr>
 <tr style="height:12.75pt; margin-top: 20px">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">__________________________ / '.$savoir['fio'].'</span></p>
  </td>
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;">__________________________ / ';
if($schet['podpisant']!=="")
{
$str .= strstr($personlis['fio']," ", true);
$str .= mb_substr(strstr($personlis['fio']," "),0,2,'UTF-8');
$str .= ".";
$str .= mb_substr(strstr($srachnah," "),0,2,'UTF-8');
$str .= ".";
}
else
{
$str .= strstr($personlis['fio']," ", true);
$str .= mb_substr(strstr($personlis['fio']," "),0,2,'UTF-8');
$str .= "______________";
$str .= mb_substr(strstr($srachnah," "),0,2,'UTF-8');
$str .= "______________";	
}
$str .= '</span></p>


  </td>
 </tr> <tr style="height:12.75pt; margin-top: 20px">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;;margin-left:70px">МП</span></p>
  </td>
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size: 7pt;;margin-left:70px">МП</span></p>
  </td>
 </tr>
</table>';

if($_GET['p']==1){
$str .= '<img src="/img/'.$savoir['mp'].'" style="
    width: 150px;
    position: relative;
    margin-bottom: -90px;
    top: -150px;
">
<img src="/img/'.$savoir['pc'].'" style="
    width: 150px;
    position: relative;
    margin-bottom: -90px;
    margin-left: -150px;
    top: -156px;
">'; 
}


$str .= '<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size: 7pt;">&nbsp;</span></p>
</div>
</body>
</html>';

echo $str;

fwrite($fp, $str);

fclose($fp);
?>