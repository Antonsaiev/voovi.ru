<?php
include 'conf.php';
include 'invoice_action.php';
include 'nds.php';
header('Content-Type: text/html; charset=UTF-8');
$pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);

$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);

$sch = "SELECT DISTINCT nomerschet,kolichschet,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,podpisant,d,m,y FROM schet WHERE del = '0' AND rand ='".$_GET['id']."'  ORDER BY id DESC";
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
$specificationDate = new DateTime(getDateDocuments($_GET['id'])['d_specification']);

/// --- НДС из счета ---
$nds_info = nds_parse(isset($pavoir['nds']) ? $pavoir['nds'] : '');
$nds_code = $nds_info['code'];
$nds_rate = (float)$nds_info['rate'];
$nds_col_text = ($nds_rate > 0) ? ('НДС: '.(int)$nds_rate.'%') : 'Без НДС';

?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
font-family:&quot;Times&quot;,&quot;serif&quot;;color:black"><?php echo $savoir['name'];?></span></b></p>
<p class=MsoNormal align=center style='margin-bottom:5.65pt;text-align:center;
line-height:normal;text-autospace:none'><b><span style='font-size:10.0pt;
font-family:"Times","serif";color:black'>ТЕЛЕФОН СЕРВИСНОГО ЦЕНТРА&nbsp;<?php echo $savoir['tel'];?></span></b></p>

<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal;text-autospace:none'><span style='font-size:5.0pt;font-family:"Times","serif";
color:black'>&nbsp;</span></p>

<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none'><b><span
style='font-size:10.0pt;font-family:"Times","serif";color:black'>СПЕЦИФИКАЦИЯ &nbsp;№&nbsp;<?php echo $schet['god'].$schet['kto'].$schet['otdel'].$schet['kolichschet'];?></span></b></p>

<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none'><b><span
style='font-size:10.0pt;font-family:"Times","serif";color:black'>к договору&nbsp;№&nbsp;<?php echo $schet['god'].$schet['kto'].$schet['otdel'].$schet['nomerdog'];?></span></b></p>
<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal;text-autospace:none'><b><span
style='font-size:10.0pt;font-family:"Times","serif";color:black'>от&nbsp;<?php echo $specificationDate->format('d.m.Y');?></span></b></p>

<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal;text-autospace:none'><span style='font-size:5.0pt;font-family:"Times","serif";
color:black'>&nbsp;</span></p>
<br>
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
$sum_vat = 0.0;
$i = 1;
$query = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = '".$_GET['id']."'");
while($row = mysql_fetch_array($query)) {
	if(!empty($row['prod'])){
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
$qty = (float)$row['kvo'];
$unit_gross = (float)$personrpod['price'];
$line_gross = round($unit_gross * $qty, 2);        // сумма строки (как в "Сумма")
$line_vat   = nds_included_sum($line_gross, $nds_rate); // НДС строки
$sum_vat   += $line_vat;

echo '</span></p></td>';
echo "<td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";

echo $nds_col_text;
echo '</span></p></td>';
echo "<td  width=26 style='width:19.8pt;border:solid black 1.0pt;border-top:none;
  padding:1.4pt 2.8pt 1.4pt 2.8pt'><p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>";
echo number_format($line_gross, 2, '.', ' ');
echo '</span></p></td></tr>';
}
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
  } else if($pavoir['goroddd'] == 1500){
  echo "Георгиевск";
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
  } else if($pavoir['goroddd'] == 1500){
  echo number_format(1500, 2, '.', ' ');
  }else{
  echo number_format(23, 2, '.', ' ');
  }
  echo"</span></p>
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

      echo number_format($line_gross, 2, '.', ' ');
  echo"</span></p>
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
  style='font-size:8.0pt;font-family:Times;color:black'>";echo number_format($pavoir['skidka'], 2, '.', ' ');echo" %</span></p>
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

<?php
    if ($nds_code !== 'none') { // если не хочешь показывать при 0%, добавь && $nds_rate > 0
        $nds_sum = round($sum_vat, 2);
        ?>
        <tr>
            <td width=604 colspan=6 style='width:453.35pt;border:solid black 1.0pt; border-top:none;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
                <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:right;line-height:normal;text-autospace:none'><b><span
                                style='font-size:8.0pt;font-family:"Times","serif";color:black'>В т.ч. НДС <?php echo $nds_rate; ?>%:</span></b></p>
            </td>
            <td width=76 style='width:56.65pt;border-top:none;border-left:none; border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
                <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:right;line-height:normal;text-autospace:none'><span
                            style='font-size:8.0pt;font-family:"Times","serif";color:black'><?php echo number_format($nds_sum, 2, '.', ' ');?></span></p>
            </td>
        </tr>
<?php } ?>
 
</table>
<br>
<br>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;float: left;
width: 50%;'>
 <tr>
  <td width=333 colspan=2 valign=top style='width:249.4pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php echo $savoir['office'];?></span></p>
  </td>
 </tr>
 <tr>
  <td width=162 valign=top style='width:190pt;border:none;border-bottom:
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
  <td width=162 valign=top style='width:150px;padding:0cm 0cm 0cm 0cm'>
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
</table><table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;float: left;
width: 50%;'>
 <tr>
  <td width=333 colspan=2 valign=top style='width:249.4pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'><?php 
$lis = "SELECT * FROM klient WHERE id =".$schet['podpisant'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo $personlis['dol'];
 ?></span></p>
  </td>
 </tr>
 <tr>
  <td width=162 valign=top style='width:190pt;border:none;border-bottom:
  solid black 1.0pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>&nbsp;</span></p>
  </td>
  <td width=170 valign=bottom style='width:127.55pt;padding:0cm 0cm 0cm 0cm'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none'><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black;padding-top: 1px;'>&nbsp;<?php 
$lis = "SELECT * FROM klient WHERE id =".$schet['podpisant'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
if($schet['podpisant']!=="")
{


echo strstr($personlis['fio']," ", true);
echo mb_substr(strstr($personlis['fio']," "),0,2,'UTF-8');
echo ".";
}
else
{
	echo strstr($personlis['fio']," ", true);
echo mb_substr(strstr($personlis['fio']," "),0,2,'UTF-8');
echo "__________";
}
if($schet['podpisant']!=="")
{


$ssser = mb_substr(strstr($personlis['fio']," "),1,99,'UTF-8');
$srachnah = strstr($ssser," ");
echo mb_substr(strstr($srachnah," "),0,2,'UTF-8');
echo ".";
}
else
{
	$ssser = mb_substr(strstr($personlis['fio']," "),1,99,'UTF-8');
$srachnah = strstr($ssser," ");
echo mb_substr(strstr($srachnah," "),0,2,'UTF-8');
echo "__________";
}

 ?></span></p>
  </td>
  </td>
 </tr>
 <tr>
  <td width=162 valign=top style='width:190pt;padding:0cm 0cm 0cm 0cm'>
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
</table><p class=MsoNormal style='margin-bottom:11.3pt;line-height:normal;text-autospace:
none'><span style='font-size:5.0pt;font-family:"Times","serif";color:black'>&nbsp;</span></p>
<?php 
if($_GET['p']==0){
echo '<img src="/img/'.$savoir['mp'].'"  style="
    width: 150px;
    position: relative;
    margin-bottom: -90px;
    top: -100px;
">
<img src="/img/'.$savoir['pc'].'"  style="
    width: 101px;
    position: relative;
    margin-bottom: -90px;
    margin-left: -100px;
    top: -180px;
">';
}
?>



</div>

</body>

</html>
