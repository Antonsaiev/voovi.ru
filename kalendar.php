<div style="font-weight:bold; padding:0px 10px 10px 10px">
                   <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Календарь задач
				</div>
<table style=" background: #fff; margin-bottom: 10px; max-height: 229px; width: 100%;">
            <tr>
                
            </tr>
            <tr>
                <td>
<!-- Блок календаря //-->
<?php
        
/*Вызов календаря*/
calendar('form1','ChoiseD');
    
function &calendar($FormName,$InputName)
{
/*Календарь*/
/*
    Параметры:
    $FormName - имя формы с которой будет работать календарь;
    $InputName - имя поля в данной форме, в которое будет возвращено значение выбранной даты.
*/

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
print "<form style='
    margin-top: -2px;
    margin-bottom: -8px;
' name='Calendar' action='' method='POST'>\n";




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

$i=1;
$j=1;
$dname="";
print "\t<tr style='height: 15px;'>\n";
$LastDay = date_format($date,"j");
$LDay = date_format($date,"D");
$DeltaDay = $LastDay-$j;
$days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' );
$modifm = "-$DeltaDay day";
$date -> modify($modifm);
while ($mnth == $NewMonth) {
  $LastDay = date_format($date,"j");
  $FLastDay = date_format($date,"d");
  $dw = date_format($date,"w");
$LDay =date_format($date,"D");
if($LDay=="Mon")
{
	$dname='Пн';
}
if($LDay=="Tue")
{
	$dname='Вт';
}
if($LDay=="Wed")
{
	$dname='Ср';
}
if($LDay=="Thu")
{
	$dname='Чт';
}
if($LDay=="Fri")
{
	$dname='Пт';
}
if($LDay=="Sat")
{
	$dname='Су';
}
if($LDay=="Sun")
{
	$dname='Вс';
}
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
	
	$resultat = mysql_query("SELECT count(*) from napomin WHERE yes = 0 AND dmg ='".$NewYear.$FNewMonth.$LastDay."' AND users='".$_COOKIE['id']."'");
	$resultats = mysql_query("SELECT COUNT(DISTINCT rand,datacar) FROM `schet` WHERE `datacar` LIKE '$NewYear-$FNewMonth-$FLastDay' GROUP BY `rand`");
	$posori = mysql_result($resultat, 0);
	$posoris = mysql_result($resultats, 0);
    echo "<td  id='".$NewYear.$FNewMonth.$LastDay."'  bgcolor=$color 
		onmouseover=\"this.style.backgroundColor='#ccc'; this.style.cursor='pointer'; this.style.color='white'\"
		onmouseout=\"this.style.backgroundColor='$color'; this.style.color='black'\"
		onClick='$NewYear.$FNewMonth.$FLastDay'>";
	echo "<div class='kall' >",$LastDay,"</div>";
	echo "<div>",$dname,"</div>";
	echo '<span style="color:'.$color2.';" class="kallend pull-right">'; 
	$dsfgdsf =  $posori + $posoris;
	echo $dsfgdsf;
	echo '</span>';
	echo "</td>";
	print "<script> 
		$('#$NewYear$FNewMonth$LastDay').live('dblclick', function() {
		window.location.href='./napominday.php?id=$NewYear$FNewMonth$LastDay&day=$LastDay&yers=$NewYear&m=$FNewMonth';
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
/*Конец календаря*/
}
?>
<!-- Конец блока календаря //-->
                </td>
            </tr>
        </table>
