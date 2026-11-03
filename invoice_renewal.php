<?php
// Только предложение для интерфейса: просмотр не изменяет сохранённые реквизиты.
function voovi_invoice_renewal_type($invoice, $hasGeneration)
{
    $stored=isset($invoice['tipprod']) ? trim((string)$invoice['tipprod']) : '';
    if ($stored!=='') return $stored;
    $certificate=isset($invoice['dataprod']) && !in_array(trim((string)$invoice['dataprod']),array('','0','0000-00-00'),true);
    $delivery=isset($invoice['datasert']) && !in_array(trim((string)$invoice['datasert']),array('','0','0000-00-00'),true);
    if ($certificate && $delivery) return 'Сер/Пос';
    if ($certificate) return 'Сертификат';
    if ($delivery) return 'Поставка';
    return $hasGeneration ? 'Сертификат' : 'Поставка';
}
