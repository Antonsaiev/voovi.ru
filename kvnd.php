<?php
include 'conf.php'; 
include 'invoice_action.php';
include 'nds.php';
$rand = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$kli  = isset($_GET['kli']) ? (int)$_GET['kli'] : 0;
$pav = "SELECT * FROM schet WHERE del='0' AND rand=".$rand;
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);

$sch = "SELECT DISTINCT nomerschet,kolichschet,oferta,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,d,m,y FROM schet WHERE del = '0' AND rand ='".$rand."'  ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult);

$kli = "SELECT * FROM ogrn WHERE id =".$kli;
$kliresult = mysql_query($kli);
$klient = mysql_fetch_array($kliresult);

$sav1 = "SELECT * FROM produkti WHERE id = '".$schet['produkt']."' ORDER BY id DESC";
$savresult1 = mysql_query($sav1);
$savoir1 = mysql_fetch_array($savresult1);

$sav = "SELECT * FROM uslugi WHERE id = '".$savoir1['parent']."' ORDER BY id DESC";
$savresult = mysql_query($sav);
$savoir = mysql_fetch_array($savresult);
// --- НДС (берём из uslugi.nds) ---
$nds_info = nds_parse(isset($savoir['nds']) ? $savoir['nds'] : '');
$nds_code = $nds_info['code'];
$nds_rate = (float)$nds_info['rate'];


$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);

$fsdhhgf1 = "SELECT * FROM users WHERE users_id =".$pavoir['kto'];
$rfsdhhgf1 = mysql_query($fsdhhgf1);
$pfsdhhgf1 = mysql_fetch_array($rfsdhhgf1);


$qr  = "SELECT * FROM kvobop WHERE schet=".$rand;
$queryk = mysql_query($qr);
$res = mysql_fetch_array($queryk);
$dd = getDateDocuments($rand);
$date = new DateTime($dd['d_bill']);
?>

<html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta name=Generator content="Microsoft Excel 14 (filtered)">

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
line-height:normal;text-autospace:none"><b><span style="font-size: 16pt;
font-family:&quot;Times&quot;,&quot;serif&quot;;color:black">КВИТАНЦИЯ НА ПОЛУЧЕНИЕ  ДОКУМЕНТОВ ПРИНЯТЫХ ПО ЗАЯВКЕ № <?php echo $res['nschet'].''.$res['id'];?> от&nbsp;<?php echo $date->format('d.m.Y');?></span></b></p>
<table class="container"style="
    width: 100%;
    font-size: 12px;
">
</table>
<table class="container"style="
    width: 100%;
    font-size: 12px;
">
 <tr style='page-break-inside:avoid'>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <b>ОТ <?php echo $klient['naim'];?></b> <b>ИНН:</b> <?php echo $klient['inn'];?></b>
  </td>
 </tr>
 <tr style='page-break-inside:avoid'>
  <td width=45 valign=top style='width:34.0pt;padding:0cm 0cm 0cm 0cm'>
  <b>В  для обработки приняты следующие документы:</b>
  </td>
 </tr>
</table>
 
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
$sum_gross = 0.0; // всего
$sum_vat   = 0.0; // всего НДС
$i = 1;
$query = mysql_query("SELECT * FROM schet WHERE del='0' AND rand='".$rand."'");
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
$qty = (float)$row['kvo'];
$unit_gross = (float)$personrpod['price'];      // цена за ед. (как ты её печатаешь)
$line_gross = round($unit_gross * $qty, 2);     // сумма строки

$line_vat = 0.0;
if ($nds_rate > 0) {
    // НДС "в том числе" из суммы строки (цена считается как "с НДС")
    $line_vat = nds_included_sum($line_gross, $nds_rate);
}

$sum_gross += $line_gross;
$sum_vat   += $line_vat;

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

if ($nds_rate <= 0) {
    echo 'Без НДС';
} else {
    echo number_format($line_vat, 2, '.', ' ');
}
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
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>Без НДС</span></p>
  </td>
  <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'>"; 
  
  
  $kvogorod = $pavoir['goroddd'] * $pavoir['kvogorod'];
  echo  number_format($kvogorod, 2, '.', ' ');
  
  
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
    text-align:right;line-height:normal;text-autospace:none'>
                <b><span style='font-size:8.0pt;font-family:"Times","serif";color:black'>Итого:</span></b>
            </p>
        </td>
        <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
            <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
    text-align:right;line-height:normal;text-autospace:none'>
      <span style='font-size:8.0pt;font-family:"Times","serif";color:black'>
        <?php echo number_format($sum_gross, 2, '.', ' '); ?>
      </span>
            </p>
        </td>
    </tr>

    <?php if ($nds_rate > 0): ?>
        <tr>
            <td width=604 colspan=6 style='width:453.35pt;border:solid black 1.0pt;
  border-top:none;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
                <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
    text-align:right;line-height:normal;text-autospace:none'>
                    <b><span style='font-size:8.0pt;font-family:"Times","serif";color:black'>
        В т.ч. НДС <?php echo (int)$nds_rate; ?>%:
      </span></b>
                </p>
            </td>
            <td width=76 style='width:56.65pt;border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
                <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
    text-align:right;line-height:normal;text-autospace:none'>
      <span style='font-size:8.0pt;font-family:"Times","serif";color:black'>
        <?php echo number_format($sum_vat, 2, '.', ' '); ?>
      </span>
                </p>
            </td>
        </tr>
    <?php endif; ?>

    <table  class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='margin-left:2.8pt;border-collapse:collapse'>
<tr>
<td>
<img src="/img/13.jpg" style="
    width: 709px;
    border: 1px solid;
    margin-top: 1px;
">
</td>
</tr>
</table>

 
 
</table>
<br>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;float: left; margin-left:2.8pt;
width: 100%;'>
 <tr>
  <td width=333 colspan=2 valign=top style="width:249.4pt;padding:0cm 0cm 0cm 0cm">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;text-autospace:none"><span style='font-size:10.0pt;font-family:"Times","serif";
  color:black'>Менеджер:<span style="border-bottom: 1px solid;">&nbsp;<?php echo $pfsdhhgf1['f_name'].'&nbsp;'.$pfsdhhgf1['l_name'].'&nbsp;'.$pfsdhhgf1['o_name'];?></span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="border-bottom: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></p>
  </td>
 </tr>
  </td>
 </tr>
 <tr height=0>
  <td width=162 style='border:none'></td>
  <td width=170 style='border:none'></td>
  <td width=162 style='border:none'></td>
  <td width=166 style='border:none'></td>
 </tr>

</table>


  <table  class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='margin-left:2.8pt;border-collapse:collapse'>
<tr>
<td>
<img src="/img/14.jpg" style="
    border: 1px solid;
    margin-top: 50px;
	height:250px;
">
</td>
<td>
<td  valign=top style='width:100%;padding:0cm 0cm 0cm 0cm'>
<div style="    width: 100%;
    margin-top: 50px;
    text-align: left;
	margin-left:5px;">
  <b>Доверенность на представление интересов Заказчика в</b><br>
  <b style="font-style: normal; font-weight: 100;">Заказчик:&nbsp;&nbsp;<?php echo $klient['naim'];?></b><br>
  <b style="font-style: normal; font-weight: 100;">ИНН/КПП: &nbsp;&nbsp;<?php echo $klient['inn'];?></b><br>
  <img src="/img/16.jpg" style="
	height:200px;
">
  </div>
  </td>
</td>
</tr>
<tr>
<td>
<img src="/img/15.jpg" style="
    border: 1px solid;
    margin-top: 10px;
	height:450px;
">
</td>
<td>
<td  valign=top style='width:100%;padding:0cm 0cm 0cm 0cm'>
<div style="    width: 100%;
    margin-top: 10px;
    text-align: left;
	margin-left:5px;">
  <b>Расписка о получении возвратных экземпляров</b><br>
  <b style="font-style: normal; font-weight: 100;">Настоящим Стороны подтверждают, что Исполнитель выполнил свои обязательства в  _____ полном объеме и в установленные сроки, а Заказчик принял выполненную работу и претензий к Исполнителю не имеет.</b><br>
  <b style="font-style: normal; font-weight: 100;">Возвратные экземпляры Документов, сформированные в соответствии с Заявкой №<?php echo $res['nschet'].''.$res['id'];?> от&nbsp;<?php echo $date->format('d.m.Y');?></b><br>
  <img src="/img/17.jpg" style="
	height:125px;
	margin-top:10px;
">
  </div>
  <div style="    width: 100%;
    margin-top: 10px;
    text-align: left;
	margin-left:5px;">
  <b>Расписка о выдачи возвратных экземпляров</b><br>
  <b style="font-style: normal; font-weight: 100;">Возвратные экземпляры Документов на основании Заявки № <?php echo $res['nschet'].''.$res['id'];?> от&nbsp;<?php echo $date->format('d.m.Y');?></b><br>
  <img src="/img/18.jpg" style="
	height:170px;
	margin-top:10px;
">
  </div>
  </td>
</td>
</tr>
</table>
</div>


</body>

</html>