<?php
$savoirAmount = number_format($row['price'], 0, ' ', ',').'р.';
echo '<td style="width: 8px; text-align: center; font-size: 14px;"';
$isPartialPayment = $paidTotal > 0 && $paidTotal < $savoirPrice;
if ($isPartialPayment) {
    echo ' class="kart-savoir-partial-payment"';
} elseif ($isSavoirPaid) {
    echo ' class="highlight_success"';
} else {
    echo ' class="alert alert-danger"';
}
echo '>';
if ($hasSavoirPayments) {
    $paymentInvoice = rawurlencode((string)$row['rand']);
    $paymentsUrl = '/divoplata.php?id=0&amp;rand='.$paymentInvoice;
    $paymentHint = $isPartialPayment ? 'Частичная оплата. Посмотреть платежи' : 'Посмотреть платежи';
    echo '<a class="kart-savoir-payments-link" href="'.$paymentsUrl.'" title="'.$paymentHint.'"';
    if ($isPartialPayment && $row['nomerdog'] !== 'В КС') {
        echo ' data-add-payment="/divoplata.php?id=1&amp;rand='.$paymentInvoice.'"';
    }
    echo '>'.$savoirAmount.'</a>';
} else {
    echo $savoirAmount;
}
echo '</td>';
