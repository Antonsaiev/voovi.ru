<?php
# подключаем конфиг
include 'conf.php';  
require_once 'company_invoice_filters.php';
session_start();
$ogrn = isset($_COOKIE['ogrn']) && ctype_digit((string)$_COOKIE['ogrn']) ? $_COOKIE['ogrn'] : '0';
$tips = isset($_COOKIE['tip']) && in_array($_COOKIE['tip'], array('1','2'), true) ? $_COOKIE['tip'] : '2';
list($dss, $dff) = company_invoice_period(isset($_COOKIE['datas']) ? $_COOKIE['datas'] : '', isset($_COOKIE['dataf']) ? $_COOKIE['dataf'] : '');
$idogrn = '0';
$nameogrn = 'Все организации';
if ($ogrn !== '0') {
    $selectedOrg = mysql_fetch_assoc(mysql_query("SELECT uslugi.id,uslugi.name FROM uslugi JOIN users_access ON users_access.uslugi=uslugi.id WHERE users_access.users=".(int)$_GET['id']." AND uslugi.id=".(int)$ogrn." AND uslugi.del!='1' LIMIT 1"));
    if ($selectedOrg) { $idogrn = $selectedOrg['id']; $nameogrn = $selectedOrg['name']; }
}
session_write_close();

?>
<?php include_once 'voovi_spinner.php'; ?>

<div class="by amt" style="
 width: 100%;
        margin-top: 35px;
    padding-left: 0px;
	margin-bottom: 10px;
">

<div class='statdate' style="width:390px;float: left;">
<select class='form-control' id="getOrg">
<option value =<?echo $idogrn;?> selected><? echo $nameogrn;?></option>
<option value="0">Все организации</option>
<?$rep=mysql_query("SELECT uslugi.id,uslugi.name,users_access.users from uslugi left join users_access on uslugi.id=users_access.uslugi where users_access.users='".$_GET['id']."' and uslugi.del!='1'");
   while($resep = mysql_fetch_assoc($rep)) :?>
   <option value ="<?echo $resep['id'];?>"><?echo $resep['name'];?></option>
   <?php endwhile; ?>
</select>
</div>
<div class='statdate' style="width:150px;float: left;">
<input class='form-control firstDate' id="datestart" type="date" value="<?echo $dss;?>">
</div>
<div class='statdate' style="width:150px;float: left;">
<input class='form-control secondDate' id="datefinish" type="date" value="<?echo $dff;?>">
</div>
<div class='statdate' style="width:400px;float: left;">
<input class="check"id="scales"name="scales" type="checkbox" style="height: 25px;
    width: 25px;
    margin-top: 5px;
    padding-top: 15px;
    margin-right: 5px;">
<label style="position: relative;font-size: 14pt;color: #d3d3d3;font-weight: normal;bottom: 5px;" id="scalesl"for="scales">Показать текущий месяц</label>
</div>
</div>
<div class="by amt" style="
   width: 100%;
    padding-left: 0px;
">
<div style="
	border-left: 1px solid #d3d3d3" class="tipschet">
<select class='form-control' id="tip" style="
    height: 53px;
	border: none;
">
<option value="1" <?php if ($tips === "1") echo "selected"; ?>>Новый</option>
<option value="2" <?php if ($tips === "2") echo "selected"; ?>>Продление</option>
</select>
</div>
<div id="tablschetog">

</div>
</div>
<div id="tablosn" style="width:100%; float:left;margin-top: 10px;">
</div>

<script src="js/company-invoice-filters.js"></script>
<script>
VooviCompanyInvoiceFilters(jQuery, <?php echo json_encode((string)(int)$_GET['id']); ?>);
</script>
<script src="https://mottie.github.io/tablesorter/js/jquery.tablesorter.js"></script>
