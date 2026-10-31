<?php
namespace VooviSupplierInvoiceTest;
// Выполняем настоящие HTML-фрагменты карточки без БД, API и изменения счетов.
function verify($condition, $message) {
    if (!$condition) { throw new \RuntimeException($message); }
}
function render_fragment($html, $invoice, $link) {
    $schet = $pavoir = $invoice;
    $konturCard = $link;
    ob_start();
    eval('?>'.$html);
    return ob_get_clean();
}
$source = file_get_contents(dirname(__DIR__).'/komnete.php');
$headerStart = strpos($source, '<span class="komnete-meta-item"><b id="kontur-header-label">');
$headerEnd = strpos($source, '<span class="komnete-meta-item"><b>Счет', $headerStart);
$cardStart = strpos($source, '<?php if (!empty($schet[\'rand\'])');
$cardEnd = strpos($source, "<?php include 'docpage.php'; ?>", $cardStart);
verify($headerStart !== false && $headerEnd !== false && $cardStart !== false && $cardEnd !== false,
    'Не найдены реальные фрагменты карточки');
$header = substr($source, $headerStart, $headerEnd-$headerStart);
$card = substr($source, $cardStart, $cardEnd-$cardStart);
$invoice = array('rand'=>'test-group', 'nomerschetks'=>'', 'url'=>'', 'cher'=>'0', 'priceks'=>'0', 'oplachenks'=>'0');

$plain = render_fragment($card, $invoice, false);
verify(strpos($plain, 'Добавить счёт Контура') !== false && strpos($plain, '<details') === false,
    'Счёт только Савоира ошибочно получил документ Контура');
foreach (array('INV-2026/17', 's2693989264') as $number) {
    $supplier = array_merge($invoice, array('nomerschetks'=>$number, 'url'=>'https://supplier.example/invoice/17'));
    $html = render_fragment($header, $supplier, false);
    verify(strpos($html, 'Счёт поставщика') !== false && strpos($html, '>'.$number.'</a>') !== false &&
        strpos($html, 'href="https://supplier.example/invoice/17"') !== false,
        'Нераспознанный номер потерял ссылку или объявлен Контуром');
    $html = render_fragment($card, $supplier, false);
    verify(strpos($html, 'hidden data-lookup="1"') !== false &&
        strpos($html, 'Счёт Контура № '.$number) === false && strpos($html, 'Добавить счёт Контура') !== false,
        'Нераспознанный номер показан как готовый документ Контура');
}
$known = array('status'=>'created', 'bill_number'=>'2693989264', 'bill_id'=>'test-bill');
$html = render_fragment($card, $invoice, $known);
verify(strpos($html, 'hidden data-lookup="1"') !== false && strpos($html, '2693989264') === false,
    'Старая связь показала состав до согласования сохранённых реквизитов');
verify(strpos(render_fragment($header, $invoice, $known), '>Счёт поставщика</b>') !== false,
    'До локального распознавания осталась старая подпись Контура');
$pending = array('status'=>'pending', 'bill_number'=>null, 'bill_id'=>null);
$invoice['nomerschetks'] = 'future-number';
$html = render_fragment($card, $invoice, $pending);
verify(strpos($html, 'hidden data-lookup="1"') !== false,
    'Явное ожидание Контура не согласуется с актуальными реквизитами');
echo "Supplier invoice display: plain, unknown number, linked Kontur and explicit pending link — OK\n";
