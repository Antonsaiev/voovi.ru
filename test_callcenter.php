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
						window.location.href='./callcenter.php?date=$ymda';
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


echo '<table class="table table-striped">';

if(isset($_GET['date'])){

	$dateget = "AND date = '$_GET[date]' OR date_call = '$_GET[date]'";

}

$query = mysql_query("SELECT * FROM call_center WHERE `otk`!='1' $dateget ORDER BY `date`");	
while($row = mysql_fetch_array($query)) {
		
	if($row['date_call'] != 0){
		$a = $row['date_call'];
	}else{
		$a = $row['date'];
	}
		$b = date('Ymd');
	if(!isset($_GET['date'])){
		$c = $a - $b;
		
		if(date('m') == 12){ 
			$tipg = 8900;
		}else{
			$tipg = 100;
		}
		
	 $va = $c > 1;
		
	}else{
	 $va = $c == $c ;
	}


	if($c <= $tipg){
		
		$issetkomment = mysql_query("SELECT COUNT(*) FROM ogrn WHERE inn ='".$row['9']."' AND kpp ='".$row['10']."'");
		$yesogrn = mysql_result($issetkomment, 0);
		
		$issetkomm = mysql_query("SELECT * FROM ogrn WHERE inn ='".$row['9']."' AND kpp ='".$row['10']."'");
		$yesogr = mysql_fetch_array($issetkomm);
			
		$q1z = "SELECT * FROM `produkti` WHERE id = $row[4]";
		$result1z = mysql_query($q1z);
		$person1z = mysql_fetch_array($result1z);
				
		$possch = "SELECT * FROM schet WHERE inn ='".$row['9']."' AND kpp ='".$row['10']."' AND produkt ='".$row['4']."'";
		$rpossch = mysql_query($possch);
		$ppossch = mysql_fetch_array($rpossch);

		$dd = substr($row['date'], 6, 2);
		$mm = substr($row['date'], 4, 2);
		$gg = substr($row['date'], 0, 4);
		
		
		$mm1 = $mm - 1;
		$mm2 = $mm - 3;
		if($mm1 == 1 || $mm1 == 2 ||$mm1 == 3 ||$mm1 == 4 ||$mm1 == 5 ||$mm1 == 6 ||$mm1 == 7 ||$mm1 == 8 ||$mm1 == 9){
			$mms1 = "0".$mm1;
		}
		if($mm1 == 0){
			$mms1 = 12;
		}
		if($mm1 == 10 || $mm1 == 11){
			$mms1 = $mm1;
		}
		if($mm2 == 1 || $mm2 == 2 ||$mm2 == 3 ||$mm2 == 4 ||$mm2 == 5 ||$mm2 == 6 ||$mm2 == 7 ||$mm2 == 8 ||$mm2 == 9){
			$mms2 = "0".$mm2;
		}
		if($mm2 == 0){
			$mms2 = 12;
		}
		if($mm2 == -1){
			$mms2 = 11;
		}
		if($mm2 == 10 || $mm2 == 11){
			$mms2 = $mm2;
		}
		
		$wissetkomment = mysql_query("SELECT COUNT(*) FROM schet WHERE inn ='".$row['9']."' AND kpp ='".$row['10']."' AND produkt ='".$row['4']."' AND (`m` = '".$mms1."' OR `m` = '".$mms2."' OR `m` = '".$mm."')");
		$wzyesogrn = mysql_result($wissetkomment, 0);
		
		if($wzyesogrn == 0){
			echo '<div class="calldiv">';
			echo '<div  style="';
			if($yesogrn != 0){
				echo 'background: rgba(63, 160, 67, 0.5);';
			}else{
				echo 'background: rgba(196, 45, 41, 0.5);';
			}
			echo 'color: #fff;float: left;line-height: 50px;margin-top: -1px;margin-left: -5px;margin-right: 7px;height: 51px;border-right: 1px solid #999;padding: 1px 5px 0px 5px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;" ';if($row['1'] == 1){echo 'class="danger"';}echo '>';
			if($yesogrn != 0){
				echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> ';
			}else{
				echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> ';
			}
			echo '</div>';
			echo '<div style="padding: 2px;color: #666;font-size: 15px;">';
			
			if(!empty($row['8'])){
				echo $row['8'];
			}else{
				$oq1z = "SELECT * FROM `ogrn` WHERE inn = $row[9]";
				$oresult1z = mysql_query($oq1z);
				$operson1z = mysql_fetch_array($oresult1z);
				echo $operson1z['naim'];
			}
			
			echo ' | '.$row['9'];
			if(!empty($row['10'])){
			echo '-'.$row['10'];
			}
			echo '- - - '.$c.'/'.$mm1.'/ <span class="label label-info" style="font-size: 12px;position: absolute;padding: 1px 4px;line-height: 200%;right: 51px;height: 27px;border-radius: 0;margin-top: -3px;">';
			echo '<b></b>';
			if($wyesogrn != 0){
				$rowqw = "SELECT DISTINCT ns,kolichschet,d,m,y,nomerschet,nomerschetks,ogrn,status,prodlen,generac,name,lico,rand,otdel,filial,sortir,god,nomerdog,data,produkt,install,akt_date,price,kto,inn,kpp,idkli,goroddd,otk,koment,
				oplachen,oplachenks,priceks,doljen,gotov,akt,url, groupi,gr FROM schet WHERE inn ='".$row['9']."' AND kpp ='".$row['10']."' AND produkt ='".$row['4']."'";
				$rowqws = mysql_query($rowqw);
				$rowe = mysql_fetch_array($rowqws);
						
				$result42 = "SELECT * from schet_status WHERE schet='$rowe[rand]' ORDER BY id DESC ";
				$results2 = mysql_query($result42);
				$persons2 = mysql_fetch_array($results2);
				
				$lis3 = "SELECT * FROM status WHERE id ='".$persons2['status']."' ";
				$resultlis3 = mysql_query($lis3);
				$personlis3 = mysql_fetch_array($resultlis3);
				if(!empty($personlis3['name'])){
					echo " | ".$personlis3['name'];
				}
				if ($rowe['oplachenks'] == 1 || $rowe['oplachen'] == 1){
					
				}else{
					echo ' | Ждем оплату';
				}
			}else{
				if($row['yes'] != 0){
					$result42 = "SELECT * from call_status WHERE id = '".$row['yes']."'";
					$results2 = mysql_query($result42);
					$persons2 = mysql_fetch_array($results2);
					echo $persons2['name'];
				}else{
					echo 'Не обработан';
				}
			}
			echo '<br />';
			echo '</span></div>';
			echo '<div style="float: left;margin-bottom: -5px;color: #989898;margin-top: 4px;">';
				if($wyesogrn != 0){
					echo '<span class="alert alert-success"> <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> ';
				}else{
					if($row['otk'] != 0){
						echo '<span class="alert alert-danger"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> ';
					}else{
						echo '<span class="alert alert-seroe"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> ';
					}
				}
				echo ' <b>Продукт:</b> '.$person1z['name'].'</span>';
				if(!empty($row['11'])){ 
				
					$pizza = $row['11'];
					$pieces = explode("\\", $pizza);
					for($i=0; $i <= 10; $i++){
						if(!empty($pieces[$i])){ 
							$r0 = mysql_query("SELECT * FROM `produkti` WHERE id = ".$pieces[$i]);
							$p0 = mysql_fetch_array($r0);
							$wissetkomment = mysql_query("SELECT COUNT(*) FROM schet WHERE inn ='".$row['9']."' AND kpp ='".$row['10']."' AND produkt ='".$pieces[$i]."'");
							$wyesogrn = mysql_result($wissetkomment, 0);
							if($wyesogrn != 0){
								echo '<span class="alert alert-success"> <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> ';
							}else{
								echo '<span class="alert alert-warning"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> ';
							}
							echo ' <b>Доп. продукт:</b> '.$p0['name'].'</span>';
						}
					}
					
					
				}
				
		echo '<br><br><b style="right: 10px;position: absolute;    margin-top: -25px;"> ';

		if(!empty($row['date_call'])){
			echo 'Дата для звонка: ';
			$y = mb_substr(strstr($row['date_call'],"2"),0,4,'UTF-8'); 
			$m = mb_substr(strstr($row['date_call'],"2"),4,2,'UTF-8');
			$d = mb_substr(strstr($row['date_call'],"2"),6,2,'UTF-8');
			echo $d; 
			echo '.'.$m;
			echo '.'.$y.'г. ';
			echo '- Дата заявки: ';
			$y = mb_substr(strstr($row['date'],"2"),0,4,'UTF-8'); 
			$m = mb_substr(strstr($row['date'],"2"),4,2,'UTF-8');
			$d = mb_substr(strstr($row['date'],"2"),6,2,'UTF-8');
			echo $d; 
			echo '.'.$m;
			echo '.'.$y.'г.';
		}else{
			echo ' Дата заявки: ';
			$y = mb_substr(strstr($row['date'],"2"),0,4,'UTF-8'); 
			$m = mb_substr(strstr($row['date'],"2"),4,2,'UTF-8');
			$d = mb_substr(strstr($row['date'],"2"),6,2,'UTF-8');
			echo $d; 
			echo '.'.$m;
			echo '.'.$y.'г.';
		}
			echo '</b>
			</div>';
			echo '<div style="float: left;padding: 2px;">';
			echo '</div>';
			echo '<div width="1px" style="right: 6px;font-size: 14px;position: absolute;background: #3FA043;color: #fff;padding: 5px 12px;margin-top: -22px;border-top-right-radius: 2px;">';
			echo '<a onclick="name'.$row['id'].'()"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></a>';
			echo '<div id="contaicall'.$row['id'].'" style="float: left;padding: 2px;"></div> 
			<script type="text/javascript">
				function name'.$row['id'].'()
				{
					var c = document.getElementById("contaicall'.$row['id'].'");
					var d = document.createElement("iframe");
					var t = document.createTextNode("");
					c.appendChild(t);
					c.appendChild(d);
					d.src = "'.$setsystem['value'].'call.php?id='.$row['id'].'&p=0&kli='.$row['idkli'].'&lico='.$row['lico'].'&gr='.$row['gr'].'";
					d.width = document.documentElement.clientWidth-1;
					d.height = document.documentElement.clientHeight-40;
					d.className = "iframestylecall";
					d.Name = "f2";
					document.getElementById("contaicall'.$row['id'].'").className = "contaidivcall";
					// d.style.minWidth = "992px";
				}
				$(document).ready(function(){
					$("#contaicall'.$row['id'].'").click(function(){
						$("#contaicall'.$row['id'].'").empty();
						document.getElementById("contaicall'.$row['id'].'").className = "";
					});
				});
			</script>';
			echo '</div>';
			echo '</div>';
		}else{
			$ddatech = date('ym') - $ppossch['akt_date'];
			if($ddatech == 1 || $ddatech == 2 || $ddatech == 91 || $ddatech == 92){
				//echo $ppossch['akt_date'].'<br>';
			}
			//echo $ddatech .'/'. $ppossch['akt_date'];
		}
	}
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
