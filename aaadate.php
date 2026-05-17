<?php
# подключаем конфиг
include 'conf.php';  
		$q = "SELECT * FROM ogrn WHERE id =$_GET[kli]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</head>
<body>


<div style="margin-bottom: 15px;  height: 40px; font-size: 21px;  background: #8e3b4b;   padding: 8px;  color: #fff;">
<div style="
    float: left;
	width: 47%;
	overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
"><?php



echo $person['naim'];

?></div> 


<!-- ---------------------------------------------------------- -->
<b id="konttakt" style="font-weight: 500;float: right;">&nbsp;ИНН: <?php echo $person['inn']; ?> </b>
<!-- ---------------------------------------------------------- -->
<div style="
    width: 58%;
    position: fixed;
    line-height: 48px;
    bottom: 0;
    z-index: 999;
    background: #4c97d8;
    text-align: center;
    right: 0;
    height: 50px;
">
<a onclick="billi()">Билли</a> |
<a onclick="cs()">Клиент сервис</a> |
<a onclick="crm()">CRM</a>
</div>
<script>
function cs() {
    document.getElementById('iframez').src='https://cs.kontur.ru/System/PGrid.aspx?mID=06a3e0a2-1870-4f94-a236-e06ec2ffb054&fixedPages=10';
}
function billi() {
    document.getElementById('iframez').src='https://billy-partners.kontur.ru/Search?SearchString=<?php 
echo $person['inn']; 
if(!empty($person['kpp'])){
	echo '-'.$person['kpp'];
}


?>';
}
function crm() {
    document.getElementById('iframez').src='/kartklient.php?id=<?php echo $_GET['kli']; ?>';
}
</script>

<!-- ---------------------------------------------------------- -->
</div>

<?php
$shetrand1 = rand(1000, 9999);
$shetrand2 = rand(1000, 9999) + $shetrand1;
$shetrand3 = rand(1000, 9999) + $shetrand2;
$shetrand4 = $shetrand1 + $shetrand2 + $shetrand3;

if(isset($_GET['rand'])){
    $shetrand = $_GET['rand'];
}else{
    $shetrand = $shetrand4.date("Ymd");
}
if(isset($_POST['go'])){
	if($_POST['nsold']!="") {
        $vowels = array("+", "(", ")", " ", "-", ",", ";", "а", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы", "Ь", "Э", "Ю", "Я");
        $tel = str_replace($vowels, "", $_POST['date']);


        $koment = "INSERT INTO `call_center`(`date`, `4`,`6`,`9`, `10`, `otk`, `ns`,`idogrn`) VALUES ('" . $tel . "','" . $_POST['produkti'] . "','" . $_POST['tip'] . "','" . $_GET['inn'] . "','" . $_GET['kpp'] . "','" . $_POST['status'] . "','" . $_POST['nsold'] . "','".$_GET['kli']."')";
        mysql_query($koment) or die(mysql_error($link));
        $komentu = "INSERT INTO `kol_prodlen`(`ns`, `kol`,`tip`,`date_prodleni`, `randkol`,`status`) VALUES ('" . $_POST['nsold'] . "','" . $_POST['shtprod'] . "','" . $_POST['tip'] . "','" . $_POST['date'] . "','" . $shetrand. "','schet')";
        mysql_query($komentu) or die(mysql_error($link));
        if ($_POST['tip'] == "Сертификат") {
            $query33 = mysql_query("SELECT * from kol_prodlen WHERE ns='" . $_POST['nsold'] . "' and date_prodleni = (SELECT MIN(date_prodleni) FROM kol_prodlen)");
            $res = mysql_fetch_array($query33) ;

                $komenti = "UPDATE schet SET tipprod='" . $res['tip'] . "',dataprod='" . $res['date_prodleni'] . "' WHERE ns='" . $res['ns'] . "'";
                mysql_query($komenti) or die(mysql_error($link));
        }
        if ($_POST['tip'] == "Поставка") {
            $query33 = mysql_query("SELECT * from kol_prodlen WHERE ns='" . $_POST['nsold'] . "' and date_prodleni = (SELECT MIN(date_prodleni) FROM kol_prodlen)");
            $res = mysql_fetch_array($query33) ;
            $komenti = "UPDATE schet SET tipprod='" . $_POST['tip'] . "',datasert='" . $_POST['date'] . "' WHERE ns='" . $_POST['nsold'] . "'";
            mysql_query($komenti) or die(mysql_error($link));
        }
    }
	else
    {
        echo'<div style="background-color:red; text-aligen:center;font-size:15pt;">';
        echo "Номер счета не указан, сохранение не возможно";
        echo'</div>';
    }
}
?>
<div class="container">
<div class="row">
<div class="col-md-5">
<form method="POST" id="form">
<button class="btn btn-primary" name="go" style="
    height: 50px;
    position: fixed;
    width: 42.86666667%;
    bottom: 0;
    left: 0;
    font-size: 19px;
    line-height: 41px;
">Сохранить и продолжить</button> 


<dl class="dl-horizontal">
  <dt>Услуги</dt>
  <dd><?php 

echo '<select id="uslugi" name="uslugi" onchange="staTus(this.value)" style="" class="form-control">';
echo '<option  value="0"></option>';

$query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."'");	
		while($row214 = mysql_fetch_array($query214)) {
		$query32 = mysql_query("SELECT * from uslugi  WHERE del = '0' AND go = '1' AND id = '".$row214['uslugi']."' ORDER BY name ");	
		while($row32 = mysql_fetch_array($query32)) {

    echo '<option  value="'.$row32['id'].'">';
	echo $row32['name'];
	echo "</option>";
  }
  }
echo '</select>';



?></dd>
</dl>
<dl class="dl-horizontal" id="produkti" style="display:none;">
  <dt>Продукт</dt>
  <dd><div id="statusinfo">
</div></dd>
</dl>
<dl class="dl-horizontal" id="status" style="display:none;">
  <dt>Статус</dt>
  <dd><?php 

echo '<select name="status" onchange="statusTus(this.value)" class="form-control">';
echo '<option  value="0">Продление</option>';
echo '<option  value="1">Отказ</option>';
echo '</select>';




?></dd>
</dl>
<dl class="dl-horizontal" id="tip" style="display:none;">
  <dt>Тип продления</dt>
  <dd><?php 

echo '<select name="tip" class="form-control">';
echo '<option>Сертификат</option>';
echo '<option>Поставка</option>';
echo '</select>';




?></dd>
</dl>
<dl class="dl-horizontal" id="date" style="display:none;">
  <dt>Дата продления</dt>
  <dd><input type="date" name="date" class="form-control"></dd>
</dl>
<dl class="dl-horizontal" id="nsold" style="display:none;">
  <dt>Номер счета</dt>
  <dd><input type="text" name="nsold" class="form-control"></dd>
</dl>
<dl class="dl-horizontal" id="shtprod" style="display:none;">
  <dt>Количество</dt>
  <dd><input type="number" name="shtprod" class="form-control"></dd>
</dl>
</form>
<script type="text/javascript">    
/* 		$( "#uslugi" ).change(function() {
			us = $("#uslugi").val();
			pr = $("#produkti").val();
			st = $("#status").val();
			da = $("#date").val();
			ti = $("#tip").val();
			if(us != "0"){
				document.getElementById("tanechka").innerHTML = "Танечка";
			}
		}); */
</script>
<div id="tanechka"></div>


<table class="table">
<thead>
<tr>
<th>Продукт</th>
<th>Тип продления</th>
<th>Дата продления</th>
<th></th>
</tr>
    </thead>
<?php 
$query = mysql_query("SELECT * from call_center WHERE `idogrn` = '".$_GET['kli']."'");
while($row = mysql_fetch_array($query)) {
$yesq1z = "SELECT * FROM `call_status` WHERE id = $row[yes]";
$yesresult1z = mysql_query($yesq1z);
$yesperson1z = mysql_fetch_array($yesresult1z);
if($row['otk'] == '1'){
	echo '<tr style="font-size: 12px;" id="id'.$row['id'].'" class="alert-danger">';
}else{
	echo '<tr style="font-size: 12px;" id="id'.$row['id'].'" class="">';
}
echo '<td>';
$q1z = "SELECT * FROM `produkti` WHERE id = $row[4]";
$result1z = mysql_query($q1z);
$person1z = mysql_fetch_array($result1z);
echo $person1z['name'];
echo '</td>';
echo '<td>';

if($row['6'] == 'Сертификат'){
echo '<select name="tip" class="htext-add">';
echo '<option>Сертификат</option>';
echo '<option>Поставка</option>';
echo '</select>';
}
if($row['6'] == 'Поставка'){
echo '<select name="tip" class="htext-add">';
echo '<option>Поставка</option>';
echo '<option>Сертификат</option>';
echo '</select>';
}


echo '</td>';
echo '<td>';
if(!empty($row['date'])){
echo '<input id="dataproda" type="date" class="htext-add" value="'.substr($row['date'], 0, 4).'-'.substr($row['date'], 4, 2).'-'.substr($row['date'], 6, 2).'">';	
}
echo '</td>';
if(!empty($row['date'])){
echo '<td style="width:1px;"><span class="glyphicon glyphicon-trash" aria-hidden="true"  id="delcall'.$row['id'].'"></span>

<script type="text/javascript">
$("#delcall'.$row['id'].'").click(function() {
document.getElementById("id'.$row['id'].'").style.display="none";
$.ajax({
type: "GET",
url: "pusya.php",
data: "tip=delcoll&id='.$row['id'].'",
success: function(msg){

}});
});
</script>
</td>';
}
echo '</tr>';						
}							
?>
</table>



<form method="POST" id="form" >
<button class="btn btn-warning" name="oldogrn" style="
    height: 35px;
    float: right;
    font-size: 13px;
    margin-top: 20px;
	<?php if($person['oldogrn'] == 1){ echo '    width: 100%;
    color: #333;
    font-weight: bold;
    border: none;
    background: #ccc;';}?>
" <?php if($person['oldogrn'] == 1){ echo 'disabled="disabled"';}?>>Не обслуживаются с 2016г.</button>
</form>

<?php
if(isset($_POST['oldogrn'])){
$koment = "UPDATE `ogrn` SET `oldogrn` = '1' WHERE `id` = '$_GET[kli]'";
mysql_query($koment) or die(mysql_error($link)); 
}
?>

<script>
function staTus(str) {
if (str=="0") {
$.ajax({
type: "GET",
url: "aaaprodukt.php",
data: "status="+str+"",
success: function(msg){
$("#statusinfo").html("");
document.getElementById("produkti").style.display="none";
document.getElementById("status").style.display="none";
document.getElementById("date").style.display="none";
document.getElementById("tip").style.display="none";
document.getElementById("shtprod").style.display="none";
document.getElementById("nsold").style.display="none";
}});
} else {
$.ajax({
type: "GET",
url: "aaaprodukt.php",
data: "status="+str+"",
success: function(msg){
$("#statusinfo").html(msg);
document.getElementById("produkti").style.display="block";
document.getElementById("status").style.display="block";
document.getElementById("status").style.display="block";
document.getElementById("date").style.display="block";
document.getElementById("tip").style.display="block";
document.getElementById("shtprod").style.display="block";
document.getElementById("nsold").style.display="block";
//document.getElementById("tanechka").innerHTML = "Танечка";
}});
}
};
function statusTus(str) {
if (str=="1") {
document.getElementById("date").style.display="none";
document.getElementById("tip").style.display="none";
document.getElementById("shtprod").style.display="none";
document.getElementById("nsold").style.display="none";
} else {
document.getElementById("date").style.display="block";
document.getElementById("tip").style.display="block";
document.getElementById("shtprod").style.display="block";
document.getElementById("nsold").style.display="block";
}
};
</script>




</div>
<div class="col-md-7">
<iframe id="iframez" src="https://billy-partners.kontur.ru/Search?SearchString=<?php 
echo $person['inn']; 
if(!empty($person['kpp'])){
	echo '-'.$person['kpp'];
}


?>" style="
    width: 59%;
    height: 100%;
    top: 40px;
    position: fixed;
    border: 0;
"></iframe>
</div>
</div>
</div>


</body>
</html>