<?php
$q = "SELECT * FROM ogrn WHERE id =$_GET[kli]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
$q1 = "SELECT uslugi.id,produkti.parent FROM produkti inner join uslugi on produkti.parent=uslugi.id  and produkti.id =$_GET[produkt]";
$result1 = mysql_query($q1);
$person1 = mysql_fetch_array($result1);

// Показывать ли вкладку УПД
$showUpdTab = false;

// Показывать ли вкладку Акт
$showAtkTab = true;

$schetRes = mysql_query("SELECT nds FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."' LIMIT 1");
$schetRow = mysql_fetch_array($schetRes);

if ($schetRow && $schetRow['nds'] !== '' && $schetRow['nds'] != 'vat0' && $schetRow['nds'] !== null) {
//    $showUpdTab = true;
    $showAtkTab = false;
}

?><div class="col-md-12" style=" overflow: hidden;">
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li class="active"><a style="font-weight: bold;" href="#scheta" role="tab" data-toggle="tab">Cчета</a></li>
  <li><?php if (!in_array($person1['id'], [4, 21, 25])){?><a style="font-weight: bold;" href="#dogov" role="tab" data-toggle="tab"> <?php if($person['budjet_ogrn'] == '0'){echo 'Договоры';}else{echo 'Контракт';}?></a><?php }?></li>
  <li><?php if (in_array($person1['id'], [4, 21, 25])){?><a style="font-weight: bold;" href="#dogovortks" role="tab" data-toggle="tab">Договор</a><?php }?></li>
  <li><a style="font-weight: bold;" href="#cpecif" role="tab" data-toggle="tab">Спецификация</a></li>
  <li><?php if ($showUpdTab){?><a style="font-weight: bold;" href="#upd" role="tab" data-toggle="tab">УПД</a><?php }?></li>
  <li><?php if ($showAtkTab){?><a style="font-weight: bold;" href="#akti" role="tab" data-toggle="tab">Акты</a><?php }?></li>
  <li><?php if (!in_array($person1['id'], [4, 21, 25]) && !$showUpdTab){?><a style="font-weight: bold;" href="#tn" role="tab" data-toggle="tab">ТН</a><?php }?></li>
  <li><?php if (!in_array($person1['id'], [4, 21, 25])){?><a style="font-weight: bold;" href="#kakl" role="tab" data-toggle="tab">Карты клиента</a><?php }?></li>
  <li><?php if (in_array($person1['id'], [4, 21, 25])){?><a style="font-weight: bold;" href="#kvoo" role="tab" data-toggle="tab">Квитанции об опате</a><?php }?></li>
  <li><?php if (in_array($person1['id'], [4, 21, 25])){?><a style="font-weight: bold;" href="#kvnd" role="tab" data-toggle="tab">Квитанции на получение документов</a><?php }?></li>
  <li><?php if (in_array($person1['id'], [4, 21, 25])){?><a style="font-weight: bold;" href="#dover" role="tab" data-toggle="tab">Довереность</a><?php }?></li>
  <li><?php if (in_array($person1['id'], [4, 21, 25])){?><a style="font-weight: bold;" href="#blank" role="tab" data-toggle="tab">Заявка</a><?php }?></li>
  <li><?php if (in_array($person1['id'], [4, 21, 25])){?><a style="font-weight: bold;" href="#uved" role="tab" data-toggle="tab">Уведомление</a><?php }?></li>
</ul>
<!-- Tab panes -->
</div>
<div class="col-md-12" style="margin-top: 10px;">
   <div class="tab-content">

<!-------------------------------------------------------------------- СЧЕТ ------------------------------------------------>

<div class="tab-pane active" id="scheta">

<table id="tab"   class="table tabli" >
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;"><?php if($person['budjet_ogrn'] == '0'){echo 'Номер договора';}else{echo 'Номер контракта';}?></th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Оплата КС</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="font-size: 14px;">Оплата SAVOIR</th>
<th style="width:1px;"></th>
<th style="width:1px;"></th>
<th style="width:1px;"></th>
<th style="width:1px;"></th>
</tr>
</thead>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        function handleBillClick(e) {
            e.preventDefault();
            var el = e.currentTarget;
            var newUrl = el.getAttribute('data-new-url');
            if (newUrl) window.open(newUrl, '_blank');
            return false;
        }

        document.querySelectorAll('.js-bill-print, .js-bill-view')
            .forEach(function (a) {
                a.addEventListener('click', handleBillClick);
            });
    });
</script>


<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {

	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){


echo '<tr>';
$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$row['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);
echo '<td style="font-size: 14px;" id="ktow'.$row['rand'].'">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
$ns = $row['ns'];
/*if($row['kto'] == $userdata['users_id'] || $userdata['otvetstven'] == '1'){

	//------------------------------------------------------------------------

echo '<select id="ktoiq'.$row['rand'].'" name="ktoiq'.$row['rand'].'" onchange="ktozTakti'.$row['rand'].'(this.value)"  style="display:none;" >';
$query21 = mysql_query("SELECT * from users WHERE users_id < '87' ");
echo '<option value="0"></option>';
while($row21 = mysql_fetch_array($query21)) {
echo '<option  value="'.$row21['users_id'].'">',$row21['f_name']," ",$row21['l_name'],'</option>';
}
echo '<option value="0"></option>';
echo '</select>';

echo '<script type="text/javascript">
$(function() {
$("#ktow'.$row['rand'].'").on("dblclick", function() {
document.getElementById("ktoiq'.$row['rand'].'").style.display="block";
});
});
function ktozTakti'.$row['rand'].'(str) {if (str=="0") {
$.ajax({
type: "GET",
url: "schetkto.php",
data: "lico="+str+"&rand='.$row['rand'].'",
success: function(msg){
document.getElementById("ktoiq'.$row['rand'].'").style.display="none";
setTimeout(function() {
$("#ktoinfo'.$row['rand'].'").load(" #ktoinfo'.$row['rand'].'");}, 1000);
}});} else {
$.ajax({
type: "GET",
url: "schetkto.php",
data: "lico="+str+"&rand='.$row['rand'].'",
success: function(msg){
document.getElementById("ktoiq'.$row['rand'].'").style.display="none";
setTimeout(function() {
$("#ktoinfo'.$row['rand'].'").load(" #ktoinfo'.$row['rand'].'");
}, 1000);
}});}}
</script>';

	//------------------------------------------------------------

}*/
echo '</td>';
echo '<td style="font-size: 14px;">';
//echo $row['d'].'.'.$row['m'].'.'.$row['y'];
$date = new DateTime(getDateDocuments($_GET['rand'])['d_bill']);
echo $date->format('d.m.Y');


echo '<button type="button" class="btn btn-worning btn-xs" data-toggle="modal" data-target="#myModald'.$row['rand'].'" style="
    float: right;
    line-height: 10px;
    font-size: 14px;
">
  Изменить
</button>

<!-- Modal -->
<div class="modal fade" id="myModald'.$row['rand'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form method="post">
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">День:</span>
		  <input class="form-control" name="ddate" type="text" value="'.$date->format('d').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Месяц:</span>
		  <input class="form-control" name="mdate" type="text" value="'.$date->format('m').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Год:</span>
		  <input class="form-control" name="ydate" type="text" value="'.$date->format('Y').'" maxlength="4"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		  <input type="submit" name="submitdate'.$row['rand'].'"  class="btn btn-success" value="Сохранить">
		</form>
		';
		if(isset($_POST['submitdate'.$row['rand']])){
		 mysql_query("UPDATE schet SET `d`='$_POST[ddate]',`m`='$_POST[mdate]',`y`='$_POST[ydate]' WHERE `rand`=".$row['rand']);
         updateDocuments($row['rand'], $_POST[ydate].'-'.$_POST[mdate].'-'.$_POST[ddate]);
		}
		echo'

      </div>
    </div>
  </div>
</div>';
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
$invoiceDetailsId = 'komneteInvoiceDetails'.$row['rand'];
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo ' <button type="button" class="komnete-invoice-toggle" data-target="#'.$invoiceDetailsId.'" aria-expanded="false" aria-controls="'.$invoiceDetailsId.'"><span class="komnete-invoice-toggle-text">Состав</span></button>';
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';

echo '<td  style="font-size: 14px;"';
if ($row['oplachenks'] == 1) {
echo ' class="highlight_success"';
}
echo'>';
if($row['priceks'] > 0){
echo number_format($row['priceks'], 0, ' ', ' ')," руб.";

echo '<input type="checkbox" value="'.$row['rand'].'"';
if ($row['oplachenks'] == 1) {
echo 'checked';
}
echo'>';
}else{

}

echo '</td>';

echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';

echo '<td style="width: 170px; padding: 2px; font-size: 14px;">';


			$rpod2345 = "SELECT * FROM kvobop WHERE schet = '".$row['rand']."'";
			$result57657 = mysql_query($rpod2345);
			$row134 = mysql_fetch_array($result57657);

			$query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row134['schet']);
			$person426 = mysql_result($query544, 0);

			echo number_format($person426, 0, ' ', ' ')," руб. ";

	echo ' <span class="glyphicon glyphicon-ruble" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$row['rand'].'"></span> ';

if ($person426 <= $row['price'] && $person426 != 0) {
	echo ' <span class="glyphicon glyphicon-eye-open" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$row['rand'].'aa"></span> ';
}else{
	echo ' <span class="glyphicon glyphicon-retweet" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$row['rand'].'"></span> ';
}
if ($person426 >= $row['price'] && $person426 != $row['price']) {
	echo '<span class="glyphicon glyphicon-eye-open" style="float: right;margin-right: 5px;" data-toggle="modal" data-target="#myModal'.$row['rand'].'aa"></span>';
}

echo '</td>';
if (strlen($row['inn']) == 12){
$tipf = 1;
} else {
$tipf = 2;
}
$re1f = mysql_query("SELECT count(*) from proddoc WHERE produkt = '".$row['parent']."'  AND tip = '".$tipf."'");
$cl1f = mysql_result($re1f, 0);
$re2f = mysql_query("SELECT count(*) from proddoc WHERE produkt = '".$row['parent']."'  AND tip = '3'");
$cl2f = mysql_result($re2f, 0);
$cl3f = $cl2f + $cl1f;
$redf = mysql_query("SELECT count(*) from dokstamp WHERE ogrn ='".$row['idkli']."' AND schet = '".$row['rand']."'");
$cladf = mysql_result($redf, 0);

/*echo '<td style="text-align: center;font-size: 14px;
padding: 0;';
if($cl3f == $cladf){
echo "background:green; color:#fff;";
}
echo'">';
echo ' <a  onclick = "openImageWindowsdoc'.$row['rand'].'(this.src);"><span class="glyphicon glyphicon-open-file"></span> </a>';

echo '<script type="text/javascript">
  function openImageWindowsdoc'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    var left = (window.innerWidth - width)/2;
    var top = (window.innerHeight - height)/2;
    window.open("/rabotasdoc.php?id='.$_GET['kli'].'&schet='.$row['rand'].'&parent='.$row['produkt'].'&inn='.$row['inn'].'&head=0",src,"width=" + width + ",height=" + height +",left=" + left + ",top=" + top);
  }
</script></td>';*/

//        echo '<td>';
//        echo '<a onclick = "openImageWindowss'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
//        echo '</td>';
//        echo '<td>';
//        echo '<a onclick = "openImageWindows'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
//        echo '</td>';

// формируем новые и старые URL
$billId      = $row['god'] . $row['kto'] . $row['otdel'] . $row['kolichschet'];
$newPdfUrl   = VOOVI_DOC_URL . '/different/dwdocs/' . $billId . '?type=bill_pdf';
$newPdfSigUrl   = VOOVI_DOC_URL . '/different/dwdocs/' . $billId . '?type=bill_pdf_signature';
$oldPrintUrl = '/schet.php?id=' . $row['rand'] . '&p=1&kli=' . urlencode($_GET['kli']);
$oldViewUrl  = '/schet.php?id=' . $row['rand'] . '&p=0&kli=' . urlencode($_GET['kli']);
$syncUrl = VOOVI_DOC_URL . '/different/evrika-bills-synchronization/' . $ns . '/';
echo '<td>';
echo '<a href="' . htmlspecialchars($syncUrl, ENT_QUOTES, 'UTF-8') . '" 
        target="_blank"
        title="Синхронизация">
        <span class="glyphicon glyphicon-refresh" style="cursor:pointer;"></span>
      </a>';
echo '</td>';
// Печать (href = реальный URL, чтобы в статусной строке при наведении показывался analitic; клик обрабатывает JS — открывает в новой вкладке)
echo '<td>';
echo '<a href="' . htmlspecialchars($newPdfUrl, ENT_QUOTES, 'UTF-8') . '" '
    . 'class="js-bill-print" '
    . 'data-new-url="' . htmlspecialchars($newPdfUrl, ENT_QUOTES, 'UTF-8') . '" '
    . 'target="_blank" '
    . 'title="Печать счёта">'
    . '<span style="cursor: pointer;" class="icon-print"></span>'
    . '</a>';
echo '</td>';

// Просмотр
echo '<td>';
echo '<a href="' . htmlspecialchars($newPdfSigUrl, ENT_QUOTES, 'UTF-8') . '" '
    . 'class="js-bill-view" '
    . 'data-new-url="' . htmlspecialchars($newPdfSigUrl, ENT_QUOTES, 'UTF-8') . '" '
    . 'target="_blank" '
    . 'title="Просмотр счёта">'
    . '<span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span>'
    . '</a>';
echo '</td>';

echo '<td>';
//echo '<a href="doc/'.$_GET['kli'].'/'.$row['rand'].'/schet.doc"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>';
$url = VOOVI_DOC_URL . '/different/dwdocs/' . $billId . '?type=bill_docx';
echo '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" title="Скачать docx"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>';

echo "&nbsp;&nbsp;<a target='_blank' href='".$row['url']."'><span class='glyphicon glyphicon-share' aria-hidden='true'></span></a>";
echo '


<script type="text/javascript">
  function openImageWindowss'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    var left = (window.innerWidth - width)/2;
    var top = (window.innerHeight - height)/2;
    window.open("/schet.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height +",left=" + left + ",top=" + top);
  }

</script>

<script type="text/javascript">
  function openImageWindows'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    var left = (window.innerWidth - width)/2;
    var top = (window.innerHeight - height)/2;
    window.open("/schet.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height +",left=" + left + ",top=" + top);
  }
</script>


</td></tr>';
$komneteSchetItems = isset($komneteInvoiceItems) && is_array($komneteInvoiceItems) ? $komneteInvoiceItems : array();
$komneteSchetTotal = isset($komneteInvoiceTotal) ? $komneteInvoiceTotal : 0;
echo '<tr id="'.$invoiceDetailsId.'" class="komnete-invoice-detail-row is-collapsed"><td colspan="12" class="komnete-invoice-detail-cell">';
echo '<div class="komnete-invoice-details">';
echo '<div class="komnete-invoice-details-head"><span>Состав счета</span><span class="komnete-invoice-total">Итого: '.komnete_money($komneteSchetTotal).'</span></div>';
if (count($komneteSchetItems) > 0) {
    echo '<div class="komnete-invoice-table-wrap"><table class="table komnete-invoice-table">';
    echo '<thead><tr>';
    echo '<th>Позиция</th>';
    echo '<th class="komnete-invoice-qty">Кол-во</th>';
    echo '<th class="komnete-invoice-money">Цена</th>';
    echo '<th class="komnete-invoice-money">Скидка</th>';
    echo '<th class="komnete-invoice-money">Сумма</th>';
    echo '</tr></thead><tbody>';
    foreach ($komneteSchetItems as $komneteSchetItem) {
        echo '<tr>';
        echo '<td class="komnete-invoice-name">'.komnete_h($komneteSchetItem['name']).'</td>';
        echo '<td class="komnete-invoice-qty">'.($komneteSchetItem['quantity'] > 0 ? komnete_clean_number($komneteSchetItem['quantity']) : '—').'</td>';
        echo '<td class="komnete-invoice-money">'.($komneteSchetItem['unit_price'] > 0 ? komnete_money($komneteSchetItem['unit_price']) : '—').'</td>';
        echo '<td class="komnete-invoice-money">'.($komneteSchetItem['discount'] > 0 ? komnete_clean_number($komneteSchetItem['discount']).'%' : '—').'</td>';
        echo '<td class="komnete-invoice-money">'.($komneteSchetItem['total'] > 0 ? komnete_money($komneteSchetItem['total']) : '—').'</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
} else {
    echo '<p class="komnete-invoice-empty">В счете нет строк для отображения.</p>';
}
echo '</div></td></tr>';
}}
?>
</table>


    <script>
$(document).ready(function () {
    $(".tabli tr input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) {
            $(this).closest('td').addClass("highlight_success");
			$.ajax({
			   type: "GET",
			   url: "oplachenks.php",
			   data: "id=1&rand="+$(this).closest("input[type='checkbox']").val(),
			   success: function(msg){
				   alert(msg);
			   }
			});
        } else {
            $(this).closest('td').removeClass("highlight_success").addClass("highlights_row");
			$.ajax({
			   type: "GET",
			   url: "oplachenks.php",
			   data: "id=0&rand="+$(this).closest("input[type='checkbox']").val(),
			   success: function(msg){
				alert(msg);
			   }
			});
        }
	});
});
	 </script>
	 <script>
$(document).ready(function () {
    $(".komnete-invoice-toggle").off("click.komneteInvoice").on("click.komneteInvoice", function () {
        var details = $($(this).attr("data-target"));
        var willOpen = details.hasClass("is-collapsed");
        details.toggleClass("is-collapsed", !willOpen);
        $(this)
            .attr("aria-expanded", willOpen ? "true" : "false")
            .find(".komnete-invoice-toggle-text")
            .text(willOpen ? "Скрыть состав" : "Состав");
    });
});
	 </script>

	<?php
$query = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,kto,rand,inn,name FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'");
while($row = mysql_fetch_array($query)) {
if(substr_count($userdata['dotdel'], $row['otdel']) == 1){

echo '<div class="modal fade" id="myModal'.$row['rand'].'aa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 700px;  margin: 30px auto;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Счет: '.$row['god'].$row['filial'].$row['otdel'].$pfsdhhgf['id'].$row['nomerschet'].'</h4>
      </div>
      <div class="modal-body">
        ';

$query56362 = mysql_query("SELECT * FROM kvobop WHERE schet = '".$row['rand']."'");
while($row12342 = mysql_fetch_array($query56362)) {
    $query56362222 = mysql_query("SELECT * FROM kvobop_tip WHERE id = ".$row12342['tip']."");
    $row123422222 = mysql_fetch_array($query56362222);
    echo $row123422222['name_tip'];
//if($row12342['tip'] == 3)
//{
//echo'Безналичные физ.лицо';
//}
//if($row12342['tip'] == 4)
//{
//echo'Безналичные физ.лицо(частично)';
//}
//
//if($row12342['tip'] == 12)
//{
//echo'Возврат Безналичные физ.лицо';
//}
//if($row12342['tip'] == 13)
//{
//echo'Возврат Безналичные счет';
//}
//if($row12342['tip'] == 11)
//{
//echo'Безналичные счет';
//}
//if($row12342['tip'] == 9)
//{
//echo'Кассовый чек';
//}
//if($row12342['tip'] == 1)
//{
//echo'Наличные';
//}
//if($row12342['tip'] == 2)
//{
//echo'Наличные (частично)';
//}
//if($row12342['tip'] == 10)
//{
//echo'Возврат наличные';
//}
//if($row12342['tip'] == 5)
//{
//echo'Гарантийное письмо';
//}
//if($row12342['tip'] == 6)
//{
//echo'Платежное поручение';
//}
//if($row12342['tip'] == 7)
//{
//echo'Служебное письмо';
//}
//if($row12342['tip'] == 8)
//{
//echo'Квитанция';
//}


echo'<br>';
if($row12342['tip'] == 1 || $row12342['tip'] == 3|| $row12342['tip'] == 11){
echo number_format($row12342['polnsumma'], 0, ' ', ' ')," руб. (оплаченно)<br>";
echo " Дата заявления2: ",$row12342['d'],".",$row12342['m'],".",$row12342['y'],"г. <br>";
}else{
$query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$row12342['schet']);
$person426 = mysql_result($query544, 0);
echo "  Оплаченно: <strong>",number_format($row12342['summa'], 0, ' ', ' ')," руб. </strong> ";
if($person426 == $row12342['polnsumma']){
echo "(оплаченно ",number_format($row12342['polnsumma'], 0, ' ', ' '),"руб.)<br>";
}else{
$zcsdcs = $row12342['polnsumma']-$person426;
if($row12342['polnsumma'] < $person426){
$fhfhtjyf = abs($zcsdcs);
echo "(здача: ",number_format($fhfhtjyf, 0, ' ', ' ')," руб. из ",number_format($row12342['polnsumma'], 0, ' ', ' '),"руб.) ";
}else{
echo "(остаток: ",number_format($zcsdcs, 0, ' ', ' ')," руб. из ",number_format($row12342['polnsumma'], 0, ' ', ' '),"руб.) ";
}
}
echo " Дата заявления: ",$row12342['d'],".",$row12342['m'],".",$row12342['y'],"г. <br>";
if($row12342['tip'] != 5 || $row12342['tip'] != 6 || $row12342['tip'] != 7 || $row12342['tip'] != 8){
echo " Дата завершения: ",$row12342['dz'],".",$row12342['mz'],".",$row12342['yz'],"г. <br>";
}
if($row12342['tip'] == 6 || $row12342['tip'] == 8){
$ewfedee = $row12342['d'] + 7;
if($ewfedee > date("j")){
$dsfgrea54 = $row12342['m'] + 1;
echo $ewfedee - date("j"),".";
if($dsfgrea54 > 12){
$rwerwetrf43 = $row12342['y'] + 1;
echo "1.",$rwerwetrf43;
}
}

echo " Дата завершения: ",$row12342['d'],".",$row12342['m'],".",$row12342['y'],"г. <br>";
}
}
}

		echo'
      </div>
    </div>
  </div>
</div>';
//			<option value="9">Кассовый чек</option>
//			<option value="1">Наличные</option>
//			<option value="2">Наличные (частично)</option>
//			<option value="10">Возврат наличные</option>


echo '<div class="modal fade" id="myModal'.$row['rand'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 700px;  margin: 30px auto;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Счет: '.$row['god'].$row['filial'].$row['otdel'].$pfsdhhgf['id'].$row['nomerschet'].'</h4>
      </div>
	  <form  enctype="multipart/form-data"  method="post">
      <div class="modal-body">
        ';
	
		echo '


		<div class="input-group">
		<span class="input-group-addon">Тип оплаты: </span>
		<select id="tipopll'.$row['rand'].'" name="tipopll" onclick="tipopl'.$row['rand'].'()" class="form-control">			
			<option value="5">Наличные (карта Иван Федорович)</option>
			<option value="6">Наличные (карта Майя Исмаиловна)</option>
			<option value="7">Наличные (карта Лейла Исмаиловна)</option>
			<option value="8">Наличные (карта Алексей Иванович)</option>
			<option value="9">Касса № 4</option>
			
		</select></div>



    <script type="text/javascript">
			function tipopl'.$row['rand'].'(){
            if ($("#tipopll'.$row['rand'].' option:selected").val() == "1" || $("#tipopll'.$row['rand'].' option:selected").val() == "3"|| $("#tipopll'.$row['rand'].' option:selected").val() == "11"){
            document.getElementById("dobfile'.$row['rand'].'").style.display="none";
			document.getElementById("dataoff'.$row['rand'].'").style.display="none";
			}if ($("#tipopll'.$row['rand'].' option:selected").val() == "2" || $("#tipopll'.$row['rand'].' option:selected").val() == "4"|| $("#tipopll'.$row['rand'].' option:selected").val() == "13"|| $("#tipopll'.$row['rand'].' option:selected").val() == "10"){
            document.getElementById("dobfile'.$row['rand'].'").style.display="none";
			document.getElementById("dataoff'.$row['rand'].'").style.display="none";
			document.getElementById("summa'.$row['rand'].'").style.display="none";
			}else{
            document.getElementById("dobfile'.$row['rand'].'").style.display="none";
			document.getElementById("dataoff'.$row['rand'].'").style.display="none";
            document.getElementById("summa'.$row['rand'].'").style.display="none";
			}
			}
    </script>';


		echo '<div style="margin-top: 6px;"></div>';
        echo '<div id="summa'.$row['rand'].'" class="input-group" style="display:none;">
		<span class="input-group-addon">Сумма оплаты: </span>
		<input type="text" name="summa" class="col-md-12  form-control" value="'.$row['price'].'">
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="dataon'.$row['rand'].'" class="input-group" >
		<span class="input-group-addon">Дата заявления: </span>
		<span class="input-group-addon"> День</span>
		<select class="col-md-12 form-control"  type="text" name="dy"  />
		<option value="'.date("d").'">'.date("d").'</option>
		<option>01</option>
		<option>02</option>
		<option>03</option>
		<option>04</option>
		<option>05</option>
		<option>06</option>
		<option>07</option>
		<option>08</option>
		<option>09</option>';
		$a = 10;
		$b = 31;
		while($a <= $b) echo '<option>',$a++,'</option>';
		echo'</select>
		<span class="input-group-addon">Мес.</span>
		<select class="col-md-12 form-control"  type="text" name="my"  />
		<option value="'.date("m").'">';

		if (date("m") == "01") {
		echo "Январь";
		} if (date("m") == "02") {
		echo "Февраль";
		} if (date("m") == "03") {
		echo "Март";
		} if (date("m") == "04") {
		echo "Апрель";
		} if (date("m") == "05") {
		echo "Май";
		} if (date("m") == "06") {
		echo "Июнь";
		} if (date("m") == "07") {
		echo "Июль";
		} if (date("m") == "08") {
		echo "Август";
		} if (date("m") == "09") {
		echo "Сентябрь";
		} if (date("m") == "10") {
		echo "Октябрь";
		} if (date("m") == "11") {
		echo "Ноябрь";
		} if (date("m") == "12") {
		echo "Декабрь";
		}
		echo'</option>
		  <option value="01">Январь</option>
		  <option value="02">Февраль</option>
		  <option value="03">Март</option>
		  <option value="04">Апрель</option>
		  <option value="05">Май</option>
		  <option value="06">Июнь</option>
		  <option value="07">Июль</option>
		  <option value="08">Август</option>
		  <option value="09">Сентябрь</option>
		  <option value="10">Октябрь</option>
		  <option value="11">Ноябрь</option>
		  <option value="12">Декабрь</option>
		</select>';
		echo '<span class="input-group-addon">Год</span>
		<select class="col-md-12 form-control" type="text" name="yy"  />';
    for($i=date('Y')-1; $i<=date('Y')+4; $i++){
      if(date('Y') == $i){
        echo '<option selected value="'.$i.'">'.$i.'</option>';
      }else{
        echo '<option value="'.$i.'">'.$i.'</option>';
      }
    }
    echo '</select>
		</div>';
		echo '<div style="margin-top: 6px;"></div>';




		echo '<div id="dataoff'.$row['rand'].'" class="input-group"  style="display:none;">
		<span class="input-group-addon">Дата завершения: </span>
		<span class="input-group-addon"> День</span>
		<select class="col-md-12 form-control"  type="text" name="do"  />
		<option value="'.date("d").'">'.date("d").'</option>
		<option>01</option>
		<option>02</option>
		<option>03</option>
		<option>04</option>
		<option>05</option>
		<option>06</option>
		<option>07</option>
		<option>08</option>
		<option>09</option>';
		$a = 10;
		$b = 31;
		while($a <= $b) echo '<option>',$a++,'</option>';
		echo'</select>
		<span class="input-group-addon">Мес.</span>
		<select class="col-md-12 form-control"  type="text" name="mo"  />
		<option value="'.date("m").'">';

		if (date("m") == "01") {
		echo "Январь";
		} if (date("m") == "02") {
		echo "Февраль";
		} if (date("m") == "03") {
		echo "Март";
		} if (date("m") == "04") {
		echo "Апрель";
		} if (date("m") == "05") {
		echo "Май";
		} if (date("m") == "06") {
		echo "Июнь";
		} if (date("m") == "07") {
		echo "Июль";
		} if (date("m") == "08") {
		echo "Август";
		} if (date("m") == "09") {
		echo "Сентябрь";
		} if (date("m") == "10") {
		echo "Октябрь";
		} if (date("m") == "11") {
		echo "Ноябрь";
		} if (date("m") == "12") {
		echo "Декабрь";
		}
		echo'</option>
		  <option value="01">Январь</option>
		  <option value="02">Февраль</option>
		  <option value="03">Март</option>
		  <option value="04">Апрель</option>
		  <option value="05">Май</option>
		  <option value="06">Июнь</option>
		  <option value="07">Июль</option>
		  <option value="08">Август</option>
		  <option value="09">Сентябрь</option>
		  <option value="10">Октябрь</option>
		  <option value="11">Ноябрь</option>
		  <option value="12">Декабрь</option>
		</select>';
		echo '<span class="input-group-addon">Год</span>
		<select class="col-md-12 form-control" type="text" name="yo"  />
    ';
    for($i=date('Y')-1; $i<=date('Y')+4; $i++){
      if(date('Y') == $i){
        echo '<option selected value="'.$i.'">'.$i.'</option>';
      }else{
        echo '<option value="'.$i.'">'.$i.'</option>';
      }
    }
    echo '
		</select>
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="dataoff2'.$row['rand'].'" class="input-group" style="display:none;">
		<span class="input-group-addon">Дата завершения: </span>
		<span class="input-group-addon"> +7 дней к дате заявления</span>
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="dobfile'.$row['rand'].'" class="input-group" style="display:none;">
		<span class="input-group-addon">Добавить файл: </span>
		<input name="dobfile'.$row['rand'].'" type="file" class="col-md-12 form-control" style="padding-top: 3px;">
		</div>';
		echo '<div style="margin-top: 6px;"></div>';
		echo '<div id="komm'.$row['rand'].'" class="input-group" >
		<span class="input-group-addon">Комментарий: </span>
		<textarea name="komm" value="" class="form-control" rows="5"></textarea></div>';

		echo'
      </div>
      <div class="modal-footer">
        <input type="submit" name="dafsdafsdf'.$row['rand'].'" id="submitnals" class="btn btn-primary col-md-12" value="Создать квитанцию"/>
      </div>
	  </form>

    </div>
  </div>
</div>';

if(isset($_POST['dafsdafsdf'.$row['rand']])){

//$dir537 = 'scheta/garant/'.$row['rand'].'/';
//	if($_FILES['dobfile'.$row['rand']]["size"] > 1024*10*1024){
//		echo "Размер файла превышает 10 мегабайт";
//		exit;
//	}else{
//		if(file_exists($dir537)){
//			if(is_dir($dir537)){
//			$file = $dir537.basename($_FILES['dobfile'.$row['rand']]['name']);
//				if (move_uploaded_file($_FILES['dobfile'.$row['rand']]['tmp_name'], $file)) {
//					echo '<br><div class="alert alert-success"> Файл успешно загружен.</div>';
//				} else {
//					echo "Произошла ошибка";
//				}
//			}
//		}else{
//		$new_dir537 = mkdir ($dir537, 0777);
//			if($new_dir537){
//				echo 'Каталог успешно создан';
//			}else{
//				$cont4653 .= 'Ошибка создания каталога';
//				return $cont4653;
//			}
//		}
//	}
//    mysqli_error(mysqli $mysql): string;

$result542353 = mysql_query("SELECT count(*) from kvobop WHERE schet = ".$row['rand']);
$class413241324 = mysql_result($result542353, 0) + 1;
$u = "INSERT INTO `kvobop`(
`idkli`,
`tip`,
`dataon`,
`dataoff`,
`dz`,
`mz`,
`yz`,
`d`,
`m`,
`y`,
`file`,
`komm`,
`schet`,
`kto`,
`nschet`,
`nkvit`,
`ogrn`,
`polnsumma`,
`produkt`,
`summa`
) VALUES (
'$_GET[id]',
'$_POST[tipopll]',
'$_POST[yy]$_POST[my]$_POST[dy]',
'$_POST[yo]$_POST[mo]$_POST[do]',
'$_POST[do]',
'$_POST[mo]',
'$_POST[yo]',
'$_POST[dy]',
'$_POST[my]',
'$_POST[yy]',
'$_POST[dobfile]',
'$_POST[komm]',
'".$row['rand']."',
'".$userdata['users_id']."',
'".$row['god'].$row['filial'].$row['otdel'].$pfsdhhgf['id'].$row['nomerschet']."',
'".$class413241324."',
'".$person['ogrn']."',
'".$row['price']."',
'".$personrpod['name']."',
'$_POST[summa]'
)";
mysql_query($u) or die(mysql_error($link));
if($_POST['tipopll'] == 1 || $_POST['tipopll'] == 2 || $_POST['tipopll'] == 3|| $_POST['tipopll'] == 4|| $_POST['tipopll'] == 10|| $_POST['tipopll'] == 12){
	$queryq = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = '".$row['rand']."'");
	while($rowq = mysql_fetch_array($queryq)) {
		$srpod = "SELECT * FROM tarif WHERE id =".$rowq['prod'];
		$sresultrpod = mysql_query($srpod);
		$spersonrpod = mysql_fetch_array($sresultrpod);
		//echo $pavoir['rand'].'<br>';
		$us = "INSERT INTO `chec` (
		`rand`,
		`name`,
		`summ`,
		`skidka`,
		`kol`,
		`date`,
		`users_id`,
		`users_fl`,
		`nalog`,
		`nds`,
		`inn`,
		`kpp`,
		`ogrn`,
`sn`,
`kkt`,
`port`,
`obsumm`
		) VALUES (
		'".$row['rand']."',
		'".$spersonrpod['name']."',
		'".$spersonrpod['price']."',
		'".$rowq['skidka']."',
		'".$rowq['kvo']."',
		'".$rowq['data']."',
		'".$userdata['users_id']."',
		'".$userdata['f_name'].' '.$userdata['l_name']."',
'0',
'0',
		'".$rowq['inn']."',
		'".$rowq['name']."',
		'$_POST[tipopll]',
'".$savoir['sn']."',
'".$savoir['kkt']."',
'".$savoir['port']."',
'$_POST[summa]'
		)";
		mysql_query($us) or die(mysql_error($link));
		/*
		echo $spersonrpod['name'].'<br>';
		echo $schet['produkt'].'<br>';
		echo $savoir1['parent'].'<br>';
		echo $savoir['kkt'].'<br>';
		echo $savoir['port'].'<br>';*/
	}

}


}
}
}

?>



  </div>

  <!-------------------------------------------------------------------- ДОГОВОР ------------------------------------------------>

<div class="tab-pane" id="dogov">
<?php $typeContract = getDateDocuments($_GET['rand'])['type_contract']; ?>
<select id="typeContract" style="font-size: 14px; margin-bottom: 10px;">
    <option<?php if ($typeContract == "oferta") echo' selected'?>  value="oferta">Договор оферты</option>
    <option<?php if ($typeContract == "dogovor") echo' selected'?> value="dogovor">Договор</option>
    <option<?php if ($typeContract == "contract") echo' selected'?>  value="contract">Контракт</option>
</select>

<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th id="typeContractName" style="font-size: 14px;"><?php if($person['budjet_ogrn'] == '0'){echo 'Номер договора';}else{echo 'Номер контракта';}?></th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Подписант</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>




<?php
$typeContract = getDateDocuments($_GET['rand'])['type_contract'];
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$dogDate = new DateTime(getDateDocuments($_GET['rand'])['d_contract']);
echo $dogDate->format('d.m.Y');

echo '<button type="button" class="btn btn-worning btn-xs" data-toggle="modal" data-target="#myModald'.$row['rand'].'dog" style="float: right;  line-height: 10px; font-size: 14px;">Изменить</button>

<!-- Modal -->
<div class="modal fade" id="myModald'.$row['rand'].'dog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form method="post">
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">День:</span>
		  <input class="form-control" name="dogddate" type="text" value="'.$dogDate->format('d').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Месяц:</span>
		  <input class="form-control" name="dogmdate" type="text" value="'.$dogDate->format('m').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Год:</span>
		  <input class="form-control" name="dogydate" type="text" value="'.$dogDate->format('Y').'" maxlength="4"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		  <input type="submit" name="submitdate'.$row['rand'].'dog"  class="btn btn-success" value="Сохранить">
		</form>
		';
        if(isset($_POST['submitdate'.$row['rand'].'dog'])){
            updateDocuments($row['rand'], null, $_POST[dogydate].'-'.$_POST[dogmdate].'-'.$_POST[dogddate]);
        }
        echo'

      </div>
    </div>
  </div>
</div>';


echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';


echo '<td id="konttaktpod'.$row['rand'].'" style="font-size: 14px;">';
$lis = "SELECT * FROM klient WHERE id =".$row['podpisant'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo '<div id="konactinfos'.$row['rand'].'" style="font-size: 14px;">';
echo $personlis['fio'];
echo ' (';
echo $personlis['tel'];
echo ') ';
echo $personlis['email'];
echo '</div>';

echo '<select id="kontaktipod'.$row['rand'].'" name="kontaktipod'.$row['rand'].'" onchange="konTakti'.$row['rand'].'(this.value)" style="display: none;">';
echo '<option  value="0"></option>';
$query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".$_GET['kli']."' ORDER BY id DESC");
while($row2 = mysql_fetch_array($query2)) {
$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");
while($row3 = mysql_fetch_array($query3)) {

echo '<option  value="'.$row3['id'].'">',$row3['fio']," (",$row3['dol'],":",$row3['tel'],")",'</option>';
}
}
echo '</select>';
echo '<script>
$("#konttaktpod'.$row['rand'].'").on("dblclick", function() {
document.getElementById("kontaktipod'.$row['rand'].'").style.display="block";
});
function konTakti'.$row['rand'].'(str) {
if (str=="0") {
$.ajax({
   type: "GET",
   url: "podpisant.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("kontaktipod'.$row['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#konactinfos'.$row['rand'].'").load(" #konactinfos'.$row['rand'].'");
	}, 1000);
   }
});
} else {
$.ajax({
   type: "GET",
   url: "podpisant.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("kontaktipod'.$row['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#konactinfos'.$row['rand'].'").load(" #konactinfos'.$row['rand'].'");
	}, 1000);
   }
});
}
}
</script>';
echo '</td>';


echo '<td>';
echo '<a id="printContract0" class="open-image-window" data-rand="'.$row['rand'].'" data-kli="'.htmlspecialchars($_GET['kli']).'" data-print="0" data-typecontract="'.$typeContract.'"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a id="printContract1" class="open-image-window" data-rand="'.$row['rand'].'" data-kli="'.htmlspecialchars($_GET['kli']).'" data-print="1" data-typecontract="'.$typeContract.'"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '</td>';
echo '<td>';


echo'<script>
function openImageWindow(rand, kli, print, selectTypeContract) {
    var width = 700;
    var height = 800;
    var phpType = "dogovor";
    if (selectTypeContract == "oferta") {phpType="dogovora";} else if (selectTypeContract == "dogovor") {phpType="dogovor";} else if (selectTypeContract == "contract") {phpType="kontrakt";}
    var url = "/" + phpType + ".php?id=" + rand + "&p=" + print +"&kli=" + kli;
    window.open(url, "newwindow", "width=" + width + ",height=" + height);
}
</script>';
echo'</td></tr>';
}
}
?>
</table>




  </div>

<!-------------------------------------------------------------------- УПД ------------------------------------------------>

<div class="tab-pane" id="upd">

   <table id="tab" class="table">
       <thead>
       <tr>
           <th style="font-size: 14px;">Создал</th>
           <th style="font-size: 14px;">Дата</th>
           <th style="font-size: 14px;">
               <?php if($person['budjet_ogrn'] == '0'){
                   echo 'Номер договора';
               }else{
                   echo 'Номер контракта';
               }?>
           </th>
           <th style="font-size: 14px;">Номер счета</th>
           <th style="font-size: 14px;">Продукт</th>
           <th style="font-size: 14px;">Сумма SAVOIR</th>
           <th style="font-size: 14px;">Оплата SAVOIR</th>
           <th style="width:1px;"><span class="icon-print"></span></th>
           <th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
           <th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
       </tr>
       </thead>
       <?php
       $docDates = getDateDocuments($_GET['rand']);

       $query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns 
              FROM schet 
              WHERE del = '0' AND rand = '".$_GET['rand']."'  
              ORDER BY id DESC");
       while($row = mysql_fetch_array($query)) {
           if(substr_count($userdata['dotdel'], $row['otdel']) == 1){

               echo '<tr>';

               // Создал
               echo '<td style="font-size: 14px;">';
               $ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
               $resultkto = mysql_query($ktos);
               $personkto = mysql_fetch_array($resultkto);
               echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
               echo '</td>';

               // Дата УПД
               echo '<td style="font-size: 14px;">';

               $updDateStr = '';
               if (!empty($docDates['d_upd'])) {
                   $updDateStr = $docDates['d_upd'];
               } elseif (!empty($docDates['d_act'])) {
                   // запасной вариант – акт
                   $updDateStr = $docDates['d_act'];
               } else {
                   // ещё один fallback – дата счёта из schet
                   $updDateStr = $row['y'].'-'.$row['m'].'-'.$row['d'];
               }
               $updDate = new DateTime($updDateStr);
               echo $updDate->format('d.m.Y');

               echo '
<button type="button" class="btn btn-worning btn-xs" data-toggle="modal" data-target="#myModald'.$row['rand'].'upd" style="float: right;  line-height: 10px; font-size: 14px;">
Изменить
</button>

<!-- Modal -->
<div class="modal fade" id="myModald'.$row['rand'].'upd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-body">
<form method="post">
<div id="inn91" class=" input-group">
  <span class="input-group-addon">День:</span>
  <input class="form-control" name="updddate" type="text" value="'.$updDate->format('d').'" maxlength="2"  style="border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
</div>
<div id="inn91" class=" input-group">
  <span class="input-group-addon">Месяц:</span>
  <input class="form-control" name="updmdate" type="text" value="'.$updDate->format('m').'" maxlength="2"  style="border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
</div>
<div id="inn91" class=" input-group">
  <span class="input-group-addon">Год:</span>
  <input class="form-control" name="updydate" type="text" value="'.$updDate->format('Y').'" maxlength="4"  style="border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
</div>
  <input type="submit" name="submitdate'.$row['rand'].'upd"  class="btn btn-success" value="Сохранить">
</form>
';

               // предполагаем, что updateDocuments умеет принимать ещё параметр d_upd
               // updateDocuments($rand, $d_bill, $d_contract, $d_specification, $d_act, $d_tn, $d_upd)
               if(isset($_POST['submitdate'.$row['rand'].'upd'])){
                   updateDocuments(
                       $row['rand'],
                       null,
                       null,
                       null,
                       null,
                       null,
                       $_POST['updydate'].'-'.$_POST['updmdate'].'-'.$_POST['updddate']
                   );
               }

               echo '

</div>
</div>
</div>
</div>';

               echo '</td>';

               // Номер договора / контракта
               echo '<td style="font-size: 14px;">';
               echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
               echo '</td>';

               // Номер счёта
               echo '<td style="font-size: 14px;">';
               echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
               echo '</td>';

               // Продукт
               echo '<td style="font-size: 14px;">';
               $rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
               $resultrpod = mysql_query($rpod);
               $personrpod = mysql_fetch_array($resultrpod);
               echo $personrpod['name'];
               echo '</td>';

               // Сумма
               echo '<td style="font-size: 14px;">';
               echo number_format($row['price'], 0, ' ', ' ')," руб.";
               echo '</td>';

               // Оплата SAVOIR (если надо что-то выводить – допишешь логику)
               echo '<td style="font-size: 14px;">';
               echo '</td>';

               // --- ссылки DWDocs для УПД ---

               // billId как в счёте
               $billId = $row['god'].$row['kto'].$row['otdel'].$row['kolichschet'];

               $updPdfUrl     = VOOVI_DOC_URL . '/different/dwdocs/' . $billId . '?type=upd_pdf';
               $updPdfSigUrl  = VOOVI_DOC_URL . '/different/dwdocs/' . $billId . '?type=upd_pdf_signature';
               $updDocxUrl    = VOOVI_DOC_URL . '/different/dwdocs/' . $billId . '?type=upd_docx';

               // Печать (pdf без подписи или с подписью — как тебе удобнее)
               echo '<td>';
               echo '<a href="' . htmlspecialchars($updPdfUrl, ENT_QUOTES, 'UTF-8') . '" '
                   . 'target="_blank" title="Печать УПД">'
                   . '<span style="cursor: pointer;" class="icon-print"></span>'
                   . '</a>';
               echo '</td>';

               // Просмотр (подписанный pdf)
               echo '<td>';
               echo '<a href="' . htmlspecialchars($updPdfSigUrl, ENT_QUOTES, 'UTF-8') . '" '
                   . 'target="_blank" title="Просмотр УПД">'
                   . '<span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span>'
                   . '</a>';
               echo '</td>';

               // Скачать docx
               echo '<td>';
               echo '<a href="' . htmlspecialchars($updDocxUrl, ENT_QUOTES, 'UTF-8') . '" '
                   . 'target="_blank" title="Скачать УПД (docx)">'
                   . '<span style="cursor: pointer;" class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>'
                   . '</a>';
               echo '</td>';

               echo '</tr>';
           }
       }
       ?>
   </table>

</div>


       <!-------------------------------------------------------------------- АКТ ------------------------------------------------>

  <div class="tab-pane" id="akti">





<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;"><?php if($person['budjet_ogrn'] == '0'){echo 'Номер договора';}else{echo 'Номер контракта';}?></th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="font-size: 14px;">Оплата SAVOIR</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$aktDate = new DateTime(getDateDocuments($_GET['rand'])['d_act']);
echo $aktDate->format('d.m.Y');

echo '<button type="button" class="btn btn-worning btn-xs" data-toggle="modal" data-target="#myModald'.$row['rand'].'akt" style="float: right;  line-height: 10px; font-size: 14px;">Изменить</button>

<!-- Modal -->
<div class="modal fade" id="myModald'.$row['rand'].'akt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form method="post">
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">День:</span>
		  <input class="form-control" name="aktddate" type="text" value="'.$aktDate->format('d').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Месяц:</span>
		  <input class="form-control" name="aktmdate" type="text" value="'.$aktDate->format('m').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Год:</span>
		  <input class="form-control" name="aktydate" type="text" value="'.$aktDate->format('Y').'" maxlength="4"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		  <input type="submit" name="submitdate'.$row['rand'].'akt"  class="btn btn-success" value="Сохранить">
		</form>
		';
        if(isset($_POST['submitdate'.$row['rand'].'akt'])){
            updateDocuments($row['rand'], null, null, null, $_POST[aktydate].'-'.$_POST[aktmdate].'-'.$_POST[aktddate]);
        }
        echo'

      </div>
    </div>
  </div>
</div>';

echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<a onclick = "oopenImageWindowabdgb5'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "oopenImageWindowaeeewd4'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function oopenImageWindowabdgb5'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/vika.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
  function oopenImageWindowaeeewd4'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/vika.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>
</table>




  </div>
  <!-------------------------------------------------------------------- Бланк ------------------------------------------------>

<div class="tab-pane" id="blank">

<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;"><?php echo 'Номер договора';?></th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="font-size: 14px;">Оплата SAVOIR</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,kto,rand,d,m,y,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$date = new DateTime(getDateDocuments($_GET['rand'])['d_bill']);
echo $date->format('d.m.Y');
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<a onclick = "oopenImageWindowabdgb6'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "oopenImageWindowaeeewd7'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function oopenImageWindowabdgb6'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/blank.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
  function oopenImageWindowaeeewd7'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/blank.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>
</table>



  </div>
  <!-------------------------------------------------------------------- СПЕЦИФИКАЦИЯ ------------------------------------------------>

<div class="tab-pane" id="cpecif">

<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;"><?php if($person['budjet_ogrn'] == '0'){echo 'Номер договора';}else{echo 'Номер контракта';}?></th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="font-size: 14px;">Оплата SAVOIR</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet, url FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$specificationDate = new DateTime(getDateDocuments($_GET['rand'])['d_specification']);
echo $specificationDate->format('d.m.Y');

echo '<button type="button" class="btn btn-worning btn-xs" data-toggle="modal" data-target="#myModald'.$row['rand'].'specification" style="float: right;  line-height: 10px; font-size: 14px;">Изменить</button>

<!-- Modal -->
<div class="modal fade" id="myModald'.$row['rand'].'specification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form method="post">
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">День:</span>
		  <input class="form-control" name="specificationddate" type="text" value="'.$specificationDate->format('d').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Месяц:</span>
		  <input class="form-control" name="specificationmdate" type="text" value="'.$specificationDate->format('m').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Год:</span>
		  <input class="form-control" name="specificationydate" type="text" value="'.$specificationDate->format('Y').'" maxlength="4"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		  <input type="submit" name="submitdate'.$row['rand'].'specification"  class="btn btn-success" value="Сохранить">
		</form>
		';
        if(isset($_POST['submitdate'.$row['rand'].'specification'])){
            updateDocuments($row['rand'], null, null, $_POST[specificationydate].'-'.$_POST[specificationmdate].'-'.$_POST[specificationddate]);
        }
        echo'

      </div>
    </div>
  </div>
</div>';


        echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<a onclick = "sopenImageWspecifiks'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "sopenImageWspecifik'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function sopenImageWspecifiks'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/specifik.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>

<script type="text/javascript">
  function sopenImageWspecifik'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/specifik.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>
</table>







  </div>

  <!-------------------------------------------------------------------- Товарная накладная ------------------------------------------------>

<div class="tab-pane" id="tn">

<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;"><?php if($person['budjet_ogrn'] == '0'){echo 'Номер договора';}else{echo 'Номер контракта';}?></th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="font-size: 14px;">Оплата SAVOIR</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$tnDate = new DateTime(getDateDocuments($_GET['rand'])['d_tn']);
echo $tnDate->format('d.m.Y');

echo '<button type="button" class="btn btn-worning btn-xs" data-toggle="modal" data-target="#myModald'.$row['rand'].'tn" style="float: right;  line-height: 10px; font-size: 14px;">Изменить</button>

<!-- Modal -->
<div class="modal fade" id="myModald'.$row['rand'].'tn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form method="post">
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">День:</span>
		  <input class="form-control" name="tnddate" type="text" value="'.$tnDate->format('d').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Месяц:</span>
		  <input class="form-control" name="tnmdate" type="text" value="'.$tnDate->format('m').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Год:</span>
		  <input class="form-control" name="tnydate" type="text" value="'.$tnDate->format('Y').'" maxlength="4"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		  <input type="submit" name="submitdate'.$row['rand'].'tn"  class="btn btn-success" value="Сохранить">
		</form>
		';
        if(isset($_POST['submitdate'.$row['rand'].'tn'])){
            updateDocuments($row['rand'], null, null, null, null, $_POST[tnydate].'-'.$_POST[tnmdate].'-'.$_POST[tnddate]);
        }
        echo'

      </div>
    </div>
  </div>
</div>';

echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<a onclick = "tsopenImageWspecifiks'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "tsopenImageWspecifik'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function tsopenImageWspecifiks'.$row['rand'].'(src) {
    var width = 1000;
    var height = 800;
    window.open("/tovarnaklad.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>

<script type="text/javascript">
  function tsopenImageWspecifik'.$row['rand'].'(src) {
    var width = 1000;
    var height = 800;
    window.open("/tovarnaklad.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>
</table>







  </div>

    <!-------------------------------------------------------------------- Квитанция на получение документов ------------------------------------------------>

  <div class="tab-pane" id="kvnd">


<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Номер квитанции</th>
<th style="font-size: 14px;">Тип оплаты</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td>';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$date = new DateTime(getDateDocuments($_GET['rand'])['d_bill']);
echo $date->format('d.m.Y');
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<a onclick = "kvnd'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "kvnd'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function kvnd'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/kvnd.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
  function kvnd'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/kvnd.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>

</table>



  </div>

       <!-------------------------------------------------------------------- dover ------------------------------------------------>

<div class="tab-pane" id="dogovortks">

<table id="tab" class="table">
   <thead>
   <tr>
       <th style="font-size: 14px;">Создал</th>
       <th style="font-size: 14px;">Дата</th>
       <th style="font-size: 14px;">Продукт</th>
       <th style="font-size: 14px;">Номер счета</th>
       <th style="font-size: 14px;">Номер доверености</th>
       <th style="font-size: 14px;">Тип оплаты</th>
       <th style="font-size: 14px;">Сумма SAVOIR</th>
       <th style="width:1px;"><span class="icon-print"></span></th>
       <th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
       <th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
   </tr>
   </thead>
<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$dogTksDate = new DateTime(getDateDocuments($_GET['rand'])['d_contract']);
echo $dogTksDate->format('d.m.Y');
        echo '<button type="button" class="btn btn-worning btn-xs" data-toggle="modal" data-target="#myModald'.$row['rand'].'dog" style="float: right;  line-height: 10px; font-size: 14px;">Изменить</button>

<!-- Modal -->
<div class="modal fade" id="myModald'.$row['rand'].'dogTks" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form method="post">
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">День:</span>
		  <input class="form-control" name="dogTksddate" type="text" value="'.$dogTksDate->format('d').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Месяц:</span>
		  <input class="form-control" name="dogTksmdate" type="text" value="'.$dogTksDate->format('m').'" maxlength="2"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		<div id="inn91" class=" input-group">
		  <span class="input-group-addon">Год:</span>
		  <input class="form-control" name="dogTksydate" type="text" value="'.$dogTksDate->format('Y').'" maxlength="4"  style=" border-bottom-right-radius: 4px; border-top-right-radius: 4px; font-size: 14px;">
		</div>
		  <input type="submit" name="submitdate'.$row['rand'].'dogTks"  class="btn btn-success" value="Сохранить">
		</form>
		';
        if(isset($_POST['submitdate'.$row['rand'].'dogTks'])){
            updateDocuments($row['rand'], null, $_POST[dogTksydate].'-'.$_POST[dogTksmdate].'-'.$_POST[dogTksddate]);
        }
        echo'

      </div>
    </div>
  </div>
</div>';

echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<a onclick = "dogovortks'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "dogovortks'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function dogovortks'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/dogovortks.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
  function dogovortks'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/dogovortks.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>
           </table>



       </div>
    <!-------------------------------------------------------------------- dover ------------------------------------------------>

<div class="tab-pane" id="dover">

<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Номер доверености</th>
<th style="font-size: 14px;">Тип оплаты</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-share" aria-hidden="true"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$date = new DateTime(getDateDocuments($_GET['rand'])['d_bill']);
echo $date->format('d.m.Y');
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<a onclick = "dover'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "dover'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function dover'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/dov.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
  function dover'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/dov.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>
</table>



  </div>
      <!-------------------------------------------------------------------- uved------------------------------------------------>

<div class="tab-pane" id="uved">

<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Номер уведомления</th>
<th style="font-size: 14px;">Тип оплаты</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['d'].'.'.$row['m'].'.'.$row['y'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td>';
echo '<a onclick = "uved'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "uved'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function uved'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/uved.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
  function uved'.$row['rand'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/uved.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>
</table>



  </div>
  <!-------------------------------------------------------------------- КВИТАНЦИЯ ОБ ОПЛАТЕ ------------------------------------------------>

<div class="tab-pane" id="kvoo">

<table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Номер квитанции</th>
<th style="font-size: 14px;">Тип оплаты</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>
<?php

$queryk = mysql_query("SELECT * FROM kvobop WHERE schet = '".$_GET['rand']."'  ORDER BY id DESC");
while($rowk = mysql_fetch_array($queryk)) {
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$rowk['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$date = new DateTime(getDateDocuments($_GET['rand'])['d_bill']);
echo $date->format('d.m.Y');
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $rowk['produkt'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $rowk['nschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $rowk['nschet']."".$rowk['id'];
echo '</td>';
echo '<td style="font-size: 14px;">';
if($rowk['tip'] == 3)
{
echo'Безналичные физ.лицо';
}
if($rowk['tip'] == 4)
{
echo'Безналичные физ.лицо(частично)';
}

if($rowk['tip'] == 12)
{
echo'Возврат Безналичные физ.лицо';
}
if($rowk['tip'] == 13)
{
echo'Возврат Безналичные счет';
}
if($rowk['tip'] == 11)
{
echo'Безналичные счет';
}
if($rowk['tip'] == 9)
{
echo'Кассовый чек';
}
if($rowk['tip'] == 1)
{
echo'Наличные';
}
if($rowk['tip'] == 2)
{
echo'Наличные (частично)';
}
if($rowk['tip'] == 10)
{
echo'Возврат наличные';
}
if($rowk['tip'] == 5)
{
echo'Гарантийное письмо';
}
if($rowk['tip'] == 6)
{
echo'Платежное поручение';
}
if($rowk['tip'] == 7)
{
echo'Служебное письмо';
}
if($rowk['tip'] == 8)
{
echo'Квитанция';
}
echo '</td>';
echo '<td style="font-size: 14px;">';
if($rowk['tip'] == 1 || $rowk['tip'] == 3){
echo number_format($rowk['polnsumma'], 0, ' ', ' ')," руб. (оплаченно)";
echo " Дата заявления: ",$rowk['d'],".",$rowk['m'],".",$rowk['y'],"г. <br>";
}else{
$query544 = mysql_query("SELECT SUM(summa) FROM kvobop WHERE schet =".$rowk['schet']);
$person426 = mysql_result($query544, 0);
echo number_format($rowk['summa'], 0, ' ', ' ')," руб. ";
if($person426 == $rowk['polnsumma']){
echo "(оплаченно ",number_format($rowk['polnsumma'], 0, ' ', ' '),"руб.)";
}else{
$zcsdcs = $rowk['polnsumma']-$person426;
echo "(остаток: ",number_format($zcsdcs, 0, ' ', ' ')," руб. из ",number_format($rowk['polnsumma'], 0, ' ', ' '),"руб.)";
}
}
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowabdgb5'.$rowk['schet'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowaeeewd4'.$rowk['schet'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '


<script type="text/javascript">
  function openImageWindowabdgb5'.$rowk['schet'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/kvitancb.php?id='.$rowk['schet'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
  function openImageWindowaeeewd4'.$rowk['schet'].'(src) {
    var width = 700;
    var height = 800;
    window.open("/kvitancb.php?id='.$rowk['schet'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';

}
?>
</table>



  </div>
  <!-------------------------------------------------------------------- КАРТА КЛИЕНТА ------------------------------------------------>

  <div class="tab-pane" id="kakl"><table id="tab" class="table">
<thead>
<tr>
<th style="font-size: 14px;">Создал</th>
<th style="font-size: 14px;">Дата</th>
<th style="font-size: 14px;"><?php if($person['budjet_ogrn'] == '0'){echo 'Номер договора';}else{echo 'Номер контракта';}?></th>
<th style="font-size: 14px;">Номер счета</th>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Сумма SAVOIR</th>
<th style="font-size: 14px;">Оплата SAVOIR</th>
<th style="font-size: 14px;">Ответ. лицо</th>
<th style="width:1px;"><span class="icon-wrench"></span></th>
<th style="width:1px;"><span class="icon-print"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-eye-open"></span></th>
<th style="width:1px;"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></th>
</tr>
</thead>
<?php
$query = mysql_query("SELECT DISTINCT rand,nomerschet,otdel,filial,god,nomerdog,data,produkt,price,inn,priceks,kto,oplachenks,ogrn,d,m,y,kolichschet,url,ns FROM schet WHERE del = '0' AND rand = '".$_GET['rand']."'  ORDER BY id DESC");
while($row = mysql_fetch_array($query)) {
	if(substr_count($userdata['dotdel'], $row['otdel']) == 1){
echo '<tr><td style="font-size: 14px;">';
$ktos = "SELECT * FROM users WHERE users_id =".$row['kto'];
$resultkto = mysql_query($ktos);
$personkto = mysql_fetch_array($resultkto);
echo $personkto['f_name']," ",$personkto['l_name'], " / Когда: ".$row['data'] ;
//if($row['kto'] == $userdata['users_id']){echo '<a href="#" style="float:right;">"Передать счет"</a>';}
echo '</td>';
echo '<td style="font-size: 14px;">';
$date = new DateTime(getDateDocuments($_GET['rand'])['d_bill']);
echo $date->format('d.m.Y');
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['nomerdog'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['god'],$row['kto'],$row['otdel'],$row['kolichschet'];
echo '</td>';
echo '<td style="font-size: 14px;">';
$rpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
echo $personrpod['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo number_format($row['price'], 0, ' ', ' ')," руб.";
echo '</td>';
echo '<td>';

echo '</td>';
echo '<td id="konttaktpodv'.$row['rand'].'">';
$lis = "SELECT * FROM klient WHERE id =".$row['podpisantv'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
echo '<div id="konactinfosv'.$row['rand'].'">';
echo $personlis['fio'];
echo ' (';
echo $personlis['tel'];
echo ') ';
echo $personlis['email'];
echo '</div>';

echo '<select id="kontaktipodv'.$row['rand'].'" name="kontaktipodv'.$row['rand'].'" onchange="konTaktiv'.$row['rand'].'(this.value)" style="display: none;">';
echo '<option  value="0"></option>';
$query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".$_GET['kli']."' ORDER BY id DESC");
while($row2 = mysql_fetch_array($query2)) {
$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");
while($row3 = mysql_fetch_array($query3)) {

echo '<option  value="'.$row3['id'].'">',$row3['fio']," (",$row3['dol'],":",$row3['tel'],")",'</option>';
}
}
echo '</select>';
echo '<script>
$("#konttaktpodv'.$row['rand'].'").on("dblclick", function() {
document.getElementById("kontaktipodv'.$row['rand'].'").style.display="block";
});
function konTaktiv'.$row['rand'].'(str) {
if (str=="0") {
$.ajax({
   type: "GET",
   url: "podpisantv.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("kontaktipodv'.$row['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#konactinfosv'.$row['rand'].'").load(" #konactinfosv'.$row['rand'].'");
	}, 1000);
   }
});
} else {
$.ajax({
   type: "GET",
   url: "podpisantv.php",
   data: "lico="+str+"&rand='.$row['rand'].'",
   success: function(msg){
	 document.getElementById("kontaktipodv'.$row['rand'].'").style.display="none";
	 setTimeout(function() {
	$("#konactinfosv'.$row['rand'].'").load(" #konactinfosv'.$row['rand'].'");
	}, 1000);
   }
});
}
}
</script>';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWSett'.$row['rand'].'(this.src);"><span class="icon-wrench"></span></a>';
echo '';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowssw'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="icon-print"></span></a>';
echo '</td>';
echo '<td>';
echo '<a onclick = "openImageWindowsw'.$row['rand'].'(this.src);"><span style="cursor: pointer;" class="glyphicon glyphicon-eye-open"></span></a>';
echo '</td>';
echo '<td>';
echo '<a href="doc/'.$_GET['kli'].'/'.$row['rand'].'/regkartkli.doc"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>';
echo '


<script type="text/javascript">
  function openImageWSett'.$row['rand'].'(src) {
    var width = 1150;
    var height = 800;
    window.open("/settingskartkli.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>

<script type="text/javascript">
  function openImageWindowssw'.$row['rand'].'(src) {
    var width = 1150;
    var height = 600;
    window.open("/regkartkli.php?id='.$row['rand'].'&p=1&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>

<script type="text/javascript">
  function openImageWindowsw'.$row['rand'].'(src) {
    var width = 1150;
    var height = 600;
    window.open("/regkartkli.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>

<script type="text/javascript">
  function openImageWindowsw'.$row['rand'].'(src) {
    var width = 1150;
    var height = 600;
    window.open("/regkartkli.php?id='.$row['rand'].'&p=0&kli='.$_GET['kli'].'",src,"width=" + width + ",height=" + height);
  }
</script>


</td></tr>';
	}
}
?>
</table>

  </div>
</div>
</div>

<script>
function changeTypeContract(rand, type) {
    $.ajax({
        type: "POST",
        url: "invoice_action.php",
        data: {
            typeContract: type,
            rand: rand
        },
        success: function(msg) {
            console.log(msg);
        }
    });
}
$(document).ready(function() {
    var selectElement = $('#typeContract');
    var typeContractName = $('#typeContractName');
    var printContract0 = $('#printContract0');
    var printContract1 = $('#printContract1');

    selectElement.change(function() {
        var selectedValue = $(this).val();

        if (selectedValue == 'oferta') {
            typeContractName.text('Номер оферты');
            printContract0.attr('data-typecontract', selectedValue);
            printContract1.attr('data-typecontract', selectedValue);
        } else if (selectedValue == 'dogovor') {
            typeContractName.text('Номер договора');
            printContract0.attr('data-typecontract', selectedValue);
            printContract1.attr('data-typecontract', selectedValue);
        } else if (selectedValue == 'contract') {
            typeContractName.text('Номер контракта');
            printContract0.attr('data-typecontract', selectedValue);
            printContract1.attr('data-typecontract', selectedValue);
        }
        changeTypeContract(printContract0.attr('data-rand'), selectedValue);

        // console.log('Выбранное значение:', selectedValue);
        // console.log('Ранд:', printContract0.attr('data-rand'));
    });
});

$(document).ready(function() {
    $('.open-image-window').click(function(e) {
        e.preventDefault();
        var rand = $(this).data('rand');
        var kli = $(this).data('kli');
        var print = $(this).data('print');
        var typecontract = $('#printContract0').attr('data-typecontract');
        openImageWindow(rand, kli, print, typecontract);
    });
});
</script>
