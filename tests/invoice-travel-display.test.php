<?php
// Проверяем фактическую строку выезда в обоих PHP-шаблонах, а не только арифметику helper.
$renderTravel = function($file, $invoice, $amounts) {
    $template = file_get_contents(dirname(__DIR__).'/'.$file);
    $start = strpos($template, "  if (\$pavoir['goroddd'] > 0");
    $end = strpos($template, "  if (\$pavoir['skidka'] > 0", $start);
    identity_test_require($start !== false && $end !== false, 'Не найдена строка выезда в '.$file);
    $pavoir = $invoice; $invoiceAmounts = array('items'=>array('travel'=>$amounts));
    $nds_col_text = 'Без НДС'; $str = '';
    ob_start();
    eval(substr($template, $start, $end-$start));
    $html = $str.ob_get_clean();
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
echo "Legacy travel print: trip units, kilometres/cities, discounts and saved totals — OK\n";
