<?php
include 'conf.php';  
$pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult); 
$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf); 
$sch = "SELECT DISTINCT nomerschet,d,m,y,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,podpisant,groupi FROM schet WHERE del = '0' AND rand ='".$_GET['id']."' ORDER BY id DESC";
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
$savoir = mysql_fetch_array($savresult); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div style="margin-bottom: 25px;  font-size: 21px;  background: #26BB84;  text-align: center;  padding: 8px;  color: #fff;">
<?php echo $klient['naim']; ?> <b>(СЧЕТ<?php if($schet['oferta'] == 1){echo  '-ОФЕРТА';} echo ' № '.$schet['god'].$schet['filial'].$schet['otdel'].$pfsdhhgf['id'].$schet['nomerschet'];?>)</b>
</div>
<div class="container">
<div class="row">
<div class="col-md-12">
<p>
	<b>
		<span>
			Дата создания: 
			<i id="dateinfo">
				<?php echo $schet['d'].'.'.$schet['m'].'.'.$schet['y'];?>
			</i>
		</span>
	</b>

	<input id="dateschetin" name="date_schet" style="display:none;" type="date" onchange="btnDateschet(this.value)">
	<a onclick="dateschet()" style="float: right;"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
	
	<script>
function btnDateschet(str)
{
dateschet = $("#dateschetin").val();
$.ajax({
type: "GET",
url: "pusya.php",
data: "date_schet="+dateschet+"&rand=<?php echo $_GET['id']; ?>",
success: function(msg){
$("#dateinfo").html(msg);
document.getElementById("dateschetin").style.display="none";
}});
}
function dateschet()
{
document.getElementById("dateschetin").style.display="block";
}
	</script>
	
</p>
<br>
</div>
<div class="col-md-12">
Дата выполнения акта
</div>
<div class="col-md-12">
Дата договора 
</div>
<div class="col-md-12">
</div>
<div class="col-md-12">
</div>
<div class="col-md-12">
</div>
<div class="col-md-12">
</div>
<div class="col-md-12">
</div>
<div class="col-md-12">
</div>
</div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
</body>
</html>