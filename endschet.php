<?php
# подключаем конфиг
include 'conf.php';  # проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
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
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 40px;">
<div class="row">

<div class="col-md-1"></div>
<div class="col-md-10"><br>
<!---<a onclick="namerand()" class="btn btn-info col-md-12">Получить клиента</a>
<a onclick="newklient()" class="btn btn-success col-md-12">Добавить клиента</a>-->
<div id="contaicall"></div> 
<script type="text/javascript">
function namerand()
{
    var c = document.getElementById("contaicall");
    var d = document.createElement("iframe");
    var t = document.createTextNode("");
    c.appendChild(t);
    c.appendChild(d);
	d.src = "<?php echo $setsystem['value']; ?>call.php?id=<?php 
	$q = "SELECT * FROM call_center WHERE `3` = '0'"; 
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);
		echo $person['id'];
	?>&p=0&kli=&lico=&gr="; 
	d.width = document.documentElement.clientWidth-1;
	d.height = document.documentElement.clientHeight-25;
	d.className = "iframestylecall";
	d.Name = "f2";
	document.getElementById("contaicall").className = "contaidivcall";
	// d.style.minWidth = "992px";
}
$(document).ready(function(){
    $("#contaicall").click(function(){
        $("#contaicall").empty();
		document.getElementById("contaicall").className = "";
    });
});


function newklient()
{
    var c = document.getElementById("contaicall");
    var d = document.createElement("iframe");
    var t = document.createTextNode("");
    c.appendChild(t);
    c.appendChild(d);
	d.src = "<?php echo $setsystem['value']; ?>call.php?id=0&p=0&kli=&lico=&gr="; 
	d.width = document.documentElement.clientWidth-1;
	d.height = document.documentElement.clientHeight-25;
	d.className = "iframestylecall";
	d.Name = "f2";
	document.getElementById("contaicall6").className = "contaidivcall";
	// d.style.minWidth = "992px";
}
$(document).ready(function(){
    $("#contaicall").click(function(){
        $("#contaicall").empty();
		document.getElementById("contaicall").className = "";
    });
});


</script>


<table style=" background: #fff; margin-bottom: 10px; max-height: 229px; width: 100%;">
	<tr>
		<td>
			<?php
				calendar('form1','ChoiseD');
				function &calendar($FormName,$InputName){
				$menu = array(
					"1,Январь",
					"2,Февраль",
					"3,Март",
					"4,Апрель",
					"5,Май",
					"6,Июнь",
					"7,Июль",
					"8,Август",
					"9,Сентябр",
					"10,Октябрь",
					"11,Ноябрь",
					"12,Декабрь"
					);
				$date = new DateTime(strftime("%Y-%m-%d"));
				$CurDay = date_format($date,"j");
				$FCurDay = date_format($date,"d");
				$CurMonth = date_format($date,"n");
				$FCurMonth = date_format($date,"m");
				$CurYear = date_format($date,"Y");
				$ChValue = "$CurYear-$FCurMonth-$FCurDay";
				if ((isset($_POST['SetMonth'])) && ($_POST['SetMonth'] != $CurMonth) ) {
				  $DeltaMonth = $_POST['SetMonth']-$CurMonth;
				  /* Проверка на 28 дней */
				  $d28 = $CurDay-28;
				  if ($d28 > 0) {
					$modif = "-$d28 day";
					$date -> modify($modif);
				  }
				  /* Конец проверки */
				  $modif = "$DeltaMonth month";
				  if ($DeltaMonth > 0) $modif = "+$modif";
				  $date -> modify($modif);
				}
				if ( (isset($_POST['SetYear'])) && ($_POST['SetYear'] != $CurYear) ) {
				   $DeltaYear = $_POST['SetYear']-$CurYear;
				   $modif = "$DeltaYear year";
				   if ($DeltaYear > 0) $modif = "+$modif";
				   $date -> modify($modif);
				}
				if ( (isset($_POST['ChoiseD'])) ) {
					$ChValue = $_POST['ChoiseD'];
				}
				$NewMonth = date_format($date,"n");
				$FNewMonth = date_format($date,"m");
				$NewYear = date_format($date,"Y");
				print "<form style='margin-top: -2px;margin-bottom: -8px;' name='Calendar' action='' method='POST'>\n";
				print "<table class='qz' style='width: 100%;border: 0;margin-bottom: -5px;color: #3B6A98;font-weight: bold;'>\n";
				print "\t<tr>\n";
				print "\t<td>\n";
				print "\t<select style='border: 0;color: #3B6A98;background: #F5F8FA;font-weight: bold;' name='SetMonth' onchange='this.form.submit()'>\n";
				foreach ($menu as $value) {
					$submenu = preg_split("/\,/", $value);
					$selected = "";
					if ($submenu[0] == $NewMonth) $selected = "selected";
					print "\t\t<option $selected value = '$submenu[0]'>$submenu[1]</option>\n";
				}     
				print "\t</select>\n";
				print "\t</td>\n";
				print "\t<td>\n";
				print "\t<input style='border: 0;background: none;' type='button' value='<' size=1 onclick='SetYear.value=$NewYear-1; this.form.submit()' />\n";
				print "\t<input style='border: 0;background: none;text-align: center;width: 42px;' type='text' name='SetYear' value='$NewYear' size=4 maxlength=4 readonly style='width: 50px;text-align: center;' />\n";
				print "\t<input style='border: 0;background: none;' type='button' value='>' size=1 onclick='SetYear.value=$NewYear+1; this.form.submit()' />\n";
				print "\t</td>\n";
				print "\t</tr>\n";
				print "</table>\n";
				print "<table border='1' style='width: 100%;height: 139px;margin-top: 4px; border: #fff;-ms-user-select: none;-moz-user-select: none;-khtml-user-select: none;-webkit-user-select: none;'>
				<tbody style='border: #fff;'>\n";
				$mnth = $NewMonth;
				print "\t<tr style='text-align: center;background: #7998B6;color: #fff; height: 26px; font-weight: bold; font-size: 14px; padding: 5px;'> 
				<td>Пн</td> 
				<td>Вт</td>
				<td>Ср</td>
				<td>Чт</td>
				<td>Пт</td>
				<td>Сб</td>
				<td>Вс</td>
				</tr>\n";
				$i=1;
				$j=1;
				print "\t<tr style='height: 15px;'>\n";
				$LastDay = date_format($date,"j");
				$DeltaDay = $LastDay-$j;
				$modifm = "-$DeltaDay day";
				$date -> modify($modifm);
				while ($mnth == $NewMonth) {
				  $LastDay = date_format($date,"j");
				  $FLastDay = date_format($date,"d");
				  $dw = date_format($date,"w");
				  if ($dw == $i) {
					if (($LastDay == $CurDay) && ($CurMonth == $NewMonth) && ($CurYear == $NewYear) ) {
					  $color="rgba(59, 106, 152, 0.66)";
					  $color2="#fff";
					} else if (($i == 0) | ($i == 6)) {
					  $color="#eee";
					  $color2="#999";
					} else {
					  $color="#fff";
					  $color2="#999";
					}
					$ymds = $NewYear.$FNewMonth.$FLastDay;
					
					
if(date('m') == 12){
	$ymda = $ymds + 8900;
}else{
	$ymda = $ymds + 100;
}
					$resultat = mysql_query("SELECT count(*) from call_center WHERE `date` ='".$ymda."' AND `otk` != '1'");
					$posori = mysql_result($resultat, 0);
					echo "<td  id='".$NewYear.$FNewMonth.$LastDay."'  bgcolor=$color 
						onmouseover=\"this.style.backgroundColor='#ccc'; this.style.cursor='pointer'; this.style.color='white'\"
						onmouseout=\"this.style.backgroundColor='$color'; this.style.color='black'\"
						onClick=\"$FormName.$InputName.value='$NewYear.$FNewMonth.$FLastDay'\">";
					echo "<div class='kall' >",$LastDay,"</div>";
					echo '<span style="color:'.$color2.';" class="kallend pull-right">'; 
					echo $posori;
					echo '</span>';
					echo "</td>";
					print "<script> 
						$('#$NewYear$FNewMonth$LastDay').live('dblclick', function() {
						window.location.href='./endschet.php?date=$ymda&y=$NewYear&m=$FNewMonth&d=$FLastDay';
						});
						</script>";
					$modifp = "+1 day";
					$date -> modify($modifp);
					$j++;
				  } else {
					if (($i == 0) | ($i == 6)) {
					  $color="#eee";
					} else {
					  $color="#fff";
					}
					print "\t<td  bgcolor=$color></td>\n";
				  }
				  $mnth = date_format($date,"n");
				  $i++;
				  if ($i == 1) print "\t</tr>\n\t<tr>\n";  
				  if ($i > 6) {
					$i=0;
				  }
				}
				print "\t</tr>\n";
				print "\t</tbody>\n";
				print "</table>\n";
				print "</form>\n";
			}
			?>
        </td>
    </tr>
</table>

<br>

<h4 style="border-bottom: 1px #333 solid;">Мои активные клиенты</h4> 

<?php


echo '<table class="table tablehover ">';

if(isset($_GET['y'])){
	//$dateget = "AND dataprod = '$_GET[y]-$_GET[m]-$_GET[d]'";

}
$query = mysql_query("SELECT * FROM schet WHERE `del`!='1'AND dataprod != '' AND `tipprod`!='Нет' AND `tipprod`!='' GROUP BY rand ORDER BY dataprod ASC");	
while($row = mysql_fetch_array($query)) {
		$q1z = "SELECT * FROM `produkti` WHERE id = $row[produkt]";
		$result1z = mysql_query($q1z);
		$person1z = mysql_fetch_array($result1z);

echo '<tr>';

echo '<td>';
if($row["issetprod"] == "1"){
	echo '<span  id="issetprodno'.$row["rand"].'" class="glyphicon glyphicon-ok-sign alert-success" aria-hidden="true"></span>';
}else{
	echo '<span  id="issetprodyes'.$row["rand"].'" class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
}
echo '<script>
		$("#issetprodyes'.$row["rand"].'").click(function () {
			$.ajax({
				type: "GET",
				url: "pusya.php",
				data: "tip=issetprod&rand='.$row["rand"].'",
				success: function(msg){
					document.getElementById("issetprodyes'.$row["rand"].'").className = "";
				}
			});
		});
		$("#issetprodno'.$row["rand"].'").click(function () {
			$.ajax({
				type: "GET",
				url: "pusya.php",
				data: "tip=issetprodno&rand='.$row["rand"].'",
				success: function(msg){
					document.getElementById("issetprodno'.$row["rand"].'").className = "";
				}
			});
		});
	</script>';
echo '</td>';

echo'<td style="width: 70px;">';
$vowels = array("+", "(", ")", " ", "-", ",", "", "а", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы", "Ь", "Э", "Ю", "Я");
				$old = str_replace($vowels, "", $row['dataprod']);
echo $old;
if($old <= date('Ymd')){
	echo "<td>Срочно</td>";
	}else{
		echo "<td>Терпимо</td>";
	}

echo '</td><td>'.$row['inn'].'</td><td>'.$row['name'].'</td><td style="width: 70px;">'.$row['dataprod'].'</td><td style="width: 70px;">'.$row['datasert'].'</td><td style="width: 100px;">'.$row['data'].'</td><td style="width: 200px;">'.$person1z['name'].'</td></tr>';

}
echo '</table>';

?>
</div>
<div class="col-md-1"></div>
<div id="containame"></div>
 



<!--<iframe src="callhistory.php" class="col-md-4 blocknone" style="
position: fixed;
  right: 20px;
  top: 40px;
  height: 100%;
  width: 32.33333333%;
  margin: 0;
  padding: 0;
    border: 1px solid #ccc;
">

</iframe>-->



</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
