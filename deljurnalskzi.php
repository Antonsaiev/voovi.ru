<?php
	include 'conf.php';
$jurnalSKZIquery = mysql_query("SELECT * FROM jurnalskzi WHERE id = $_GET[id]");
$jurnalSKZIresult = mysql_fetch_array($jurnalSKZIquery);

$schetQuery = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = ". $jurnalSKZIresult['schet'] ." ");
$schetResult = mysql_fetch_array($schetQuery);

if (!empty($schetResult['priceks']) && !empty($jurnalSKZIresult['vendor_product'])) {
    $vendorProduct = mysql_query("SELECT * FROM `vendor_product` INNER JOIN product_model ON product_model.id=vendor_product.product_model INNER JOIN `product_type` ON product_model.product_type=product_type.id WHERE vendor_product.`id` = ". $jurnalSKZIresult['vendor_product'] ." ");
    $vendorProductResult = mysql_fetch_array($vendorProduct);
    if ($vendorProductResult['subtotal'] === '1') {
        $nowSummSkbKontur = $schetResult['priceks'] - $vendorProductResult['summ'];
        $koment = "UPDATE `schet` SET `priceks`='" . $nowSummSkbKontur . "' WHERE  `del` = '0' AND `rand` ='" . $schetResult['rand'] . "' ";
        mysql_query($koment) or die(mysql_error($link));
    }
}

mysql_query("DELETE FROM jurnalskzi WHERE id = $_GET[id]") or die(mysql_error($link));
header("Location: ".$_SERVER['HTTP_REFERER']);
?>