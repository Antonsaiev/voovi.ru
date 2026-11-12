<?php
if (isset($kartSchetKvobopSum)) {
    $hasSavoirPayments = isset($kartSchetKvobopSum[$row['rand']]);
    $paidTotal = $hasSavoirPayments ? $kartSchetKvobopSum[$row['rand']] : 0;
} else {
    $paymentQuery = mysql_query("SELECT COUNT(*) AS payment_count, SUM(summa) AS paid_total
        FROM kvobop WHERE schet = '".mysql_real_escape_string($row['rand'])."'");
    $paymentTotals = mysql_fetch_assoc($paymentQuery);
    $hasSavoirPayments = $paymentTotals['payment_count'] > 0;
    $paidTotal = (float)$paymentTotals['paid_total'];
}

$savoirPrice = round((float)$row['price'], 2);
$paidTotal = round($paidTotal, 2);
$isSavoirPaid = $savoirPrice === 0.0 || ($hasSavoirPayments && $paidTotal >= $savoirPrice);
