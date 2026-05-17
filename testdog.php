<?php
include 'conf.php';
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

$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);

$fsdhhgf1 = "SELECT * FROM users WHERE users_id =".$pavoir['kto'];
$rfsdhhgf1 = mysql_query($fsdhhgf1);
$pfsdhhgf1 = mysql_fetch_array($rfsdhhgf1);


$qr="SELECT * FROM kvobop WHERE schet =$_GET[id]";
$queryk = mysql_query($qr);
$res = mysql_fetch_array($queryk);
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

    <table class="container"style="
    width: 100%;
    font-size: 12px;
">
        <tr>
            <td  valign=top style='width:100%;padding:0cm 0cm 0cm 0cm'>
                <div style="    width: 100%;
    margin-top: 10px;
    text-align: left;
	margin-left:5px;">
                    <img src="/img/28.jpg" style="
	height: 3875px;
">
                </div>
            </td>
        </tr>
    </table>



</div>


</body>

</html>