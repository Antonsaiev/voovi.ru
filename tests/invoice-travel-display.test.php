<?php
// Проверяем фактические строки PHP-шаблонов, а не только арифметику helper.
$renderTravel = function($file, $invoice, $amounts) {
    $template = file_get_contents(dirname(__DIR__).'/'.$file);
    $start = strpos($template, "  if (isset(\$invoiceAmounts['items']['travel']))");
    $end = strpos($template, "  if (\$pavoir['skidka'] > 0", $start);
    identity_test_require($start !== false && $end !== false, 'Не найдена строка выезда в '.$file);
    $pavoir = $invoice; $invoiceAmounts = array('items'=>$amounts === null ? array() : array('travel'=>$amounts));
    $nds_col_text = 'Без НДС'; $str = ''; $i = 1;
    set_error_handler(function($severity, $message) { throw new RuntimeException($message); });
    ob_start();
    try {
        eval(substr($template, $start, $end-$start));
        $html = $str.ob_get_clean();
    } catch (Exception $error) {
        ob_end_clean();
        throw $error;
    } finally { restore_error_handler(); }
    preg_match_all('/<td\b[^>]*>(.*?)<\/td>/s', $html, $matches);
    return array_map(function($value) { return trim(strip_tags($value)); }, $matches[1]);
};
foreach (array(
    array('230','1','0','330.00','Выезд: 10 км'),
    array('230','2','10','504.00','Выезд: 10 км'),
    array('450','2','10','900.00','Выезд: Пятигорск'),
    array('750','2','0','1600.00','Выезд: КМВ'),
    array('1500','1','0','1600.00','Выезд: Георгиевск'),
) as $case) {
    $invoice = array('goroddd'=>$case[0], 'kvogorod'=>$case[1], 'skidka'=>$case[2]);
    $amounts = voovi_invoice_amounts(array(
        array('price'=>'100','quantity'=>'1','discount'=>$case[2]),
        array('price'=>$case[0],'quantity'=>$case[1],'discount'=>$case[2]),
    ), $case[3], 0);
    identity_test_require($amounts['gross'] === $case[3], 'Выезд изменил сохранённый итог');
    foreach (array('schet.php','specifik.php') as $file) {
        $cells = $renderTravel($file, $invoice, $amounts['lines'][1]);
        identity_test_require(count($cells) === 7 && $cells[1] === $case[4] && $cells[2] === 'выезд' && $cells[3] === $case[1],
            'Шаблон смешивает километры, город и количество поездок: '.$file);
        identity_test_require(str_replace(array(',',' '),'',$cells[4]) === $amounts['lines'][1]['price'] &&
            str_replace(array(',',' '),'',$cells[6]) === $amounts['lines'][1]['gross'],
            'Цена или сумма строки не соответствует сохранённому итогу: '.$file);
    }
}
$invoice = array('goroddd'=>'450','kvogorod'=>'3','skidka'=>'0');
$amounts = voovi_invoice_amounts(array(array('price'=>'450','quantity'=>'3')), '100.00', 0);
foreach (array('schet.php','specifik.php') as $file) {
    $rows = array_chunk($renderTravel($file, $invoice, $amounts['lines'][0]), 7);
    identity_test_require(count($rows) === 2 && array_column($rows, 0) === array('1','2') &&
        array_column($rows, 1) === array('Выезд: Пятигорск','Выезд: Пятигорск') &&
        array_column($rows, 3) === array('2','1') && array_column($rows, 4) === array('33.33','33.34') &&
        array_column($rows, 6) === array('66.66','33.34'),
        'Разделённый выезд потерял название, количество, нумерацию или копейки: '.$file);
}
foreach (array(array('450','0'), array('450',''), array('162534','2'), array('162534.00','2')) as $case) {
    $invoice = array('goroddd'=>$case[0], 'kvogorod'=>$case[1], 'skidka'=>'0');
    foreach (array('schet.php','specifik.php') as $file) {
        identity_test_require($renderTravel($file, $invoice, null) === array(),
            'Шаблон напечатал отсутствующий выезд или вызвал предупреждение: '.$file);
    }
}
$template = file_get_contents(dirname(__DIR__).'/tovarnaklad.php');
$start = strpos($template, "\$str .=  '<tr class=xl7125266");
$end = strpos($template, " </tr>';", $start);
identity_test_require($start !== false && $end !== false, 'Не найдена печатная строка накладной');
foreach (array(0,20) as $rate) {
    $amounts = voovi_invoice_amounts(array(array('price'=>'100','quantity'=>'3','amount'=>'100')), '100.00', $rate);
    $str = ''; $i = 1; $personrpod = array('name'=>'Прежний тариф');
    foreach (voovi_invoice_print_parts($amounts['lines'][0], 'net') as $part) {
        $qty = $part['quantity']; $unit_net = $part['unit_net']; $line_net = $part['net']; $line_gross = $part['gross'];
        $nds_cell_rate = $rate ? (string)$rate : 'Без НДС';
        $nds_cell_sum = $rate ? voovi_money_format($part['vat']) : '&nbsp;';
        eval(substr($template, $start, $end-$start+strlen(" </tr>';")));
    }
    preg_match_all('/<td\b[^>]*>(.*?)<\/td>/s', $str, $matches);
    $rows = array_chunk(array_map(function($value) { return trim(strip_tags($value)); }, $matches[1]),15);
    identity_test_require(count($rows) === 2 && array_column($rows,0) === array('1','2') &&
        array_column($rows,1) === array('Прежний тариф','Прежний тариф') &&
        array_column($rows,9) === ($rate ? array('1','2') : array('2','1')) &&
        array_column($rows,10) === ($rate ? array('27.77','27.78') : array('33.33','33.34')) &&
        array_column($rows,14) === ($rate ? array('33.33','66.67') : array('66.66','33.34')),
        'Накладная не сохранила название, количество, копеечные цены или суммы частей');
}
echo "Legacy travel print: trip units, kilometres/cities, discounts and saved totals — OK\n";
