<?php
// Только арифметические SELECT: тест не читает и не меняет рабочие таблицы.
require_once dirname(__DIR__).'/invoice_money.php';
mysql_connect(getenv('DB_HOST'), getenv('DB_LOGIN'), getenv('DB_PASSWORD')) or die('No local MySQL connection');

function money_test_equal($actual, $expected, $message)
{
    if ($actual !== $expected) { throw new RuntimeException($message.': '.json_encode(array($actual,$expected))); }
}

money_test_equal(voovi_money_round('0,005'), '0.01', 'HALF_UP');
money_test_equal(voovi_money_round(' 1 234,565 '), '1234.57', 'Нормализация');
money_test_equal(array(voovi_decimal('.5'),voovi_decimal('1.'),voovi_decimal('+1')),array('0.5','1','1'),'Прежние форматы дробного количества');
money_test_equal(voovi_invoice_form_total(array(array('id'=>1,'price'=>'120')),
    array('input1'=>'1.01','skidka'=>'1.25')), '119.69', 'Точный новый итог');
money_test_equal(voovi_invoice_form_total(array(array('id'=>1,'price'=>'100')),
    array('input1'=>'2','skidka'=>'10','goroddd'=>'450','kvogorod'=>'2')), '990.00', 'Выезд и единичная скидка');
money_test_equal(voovi_invoice_form_total(array(array('id'=>1,'price'=>'100')),
    array('input1'=>'2','skidka'=>'10','goroddd'=>'162534.00','kvogorod'=>'2')), '180.00', 'Служебное значение без выезда');

$result = voovi_invoice_amounts(array(array('price'=>'0.005','quantity'=>1),array('price'=>'0.005','quantity'=>1)), '0.01', 0);
money_test_equal(array_column($result['lines'],'gross'), array('0.01','0.00'), 'Распределение копейки');
$result = voovi_invoice_amounts(array(array('price'=>100,'quantity'=>1),array('price'=>200,'quantity'=>1)), '500.00', 20);
money_test_equal(array_column($result['lines'],'gross'), array('166.67','333.33'), 'Сохранённый исторический итог');
money_test_equal(array($result['gross'],$result['vat'],$result['net']), array('500.00','83.33','416.67'), 'Итоговый НДС');
$result = voovi_invoice_amounts(array(array('price'=>'999','quantity'=>1,'amount'=>'30'),
    array('price'=>'1','quantity'=>1,'amount'=>'70')), '100.00', 20);
money_test_equal(array_column($result['lines'],'gross'), array('30.00','70.00'), 'Сохранённые суммы строк важнее каталога');
$result = voovi_invoice_amounts(array_fill(0,100,array('price'=>'0.01','quantity'=>1)), '1.00', 22);
money_test_equal($result['vat'], '0.18', 'НДС мелких строк');
$gross = $vat = $net = 0;
foreach ($result['lines'] as $line) {
    $gross += voovi_money_cents($line['gross']);
    $vat += voovi_money_cents($line['vat']);
    $net += voovi_money_cents($line['net']);
    if (voovi_money_cents($line['net']) < 0) { throw new RuntimeException('Отрицательная сумма без НДС'); }
}
money_test_equal(array($gross,$vat,$net),array(100,18,82),'Суммы строк и НДС сходятся');
money_test_equal(voovi_money_format('1234567.89'), '1 234 567.89', 'Отображение без float');
$result = voovi_invoice_amounts(array(array('price'=>'100','quantity'=>'3','amount'=>'100')), '100.00', 20);
$parts = voovi_invoice_print_parts($result['lines'][0]);
money_test_equal(array_column($parts,'quantity'), array('2','1'), 'Количество распределено между прежними позициями');
money_test_equal(array_column($parts,'price'), array('33.33','33.34'), 'Цена единицы содержит только копейки');
money_test_equal(array($result['gross'],$result['vat'],$result['net']),
    array('100.00','16.67','83.33'), 'Разделение печати не меняет исходные суммы и НДС');
// Реальная арифметика скидки: исходную базу не читаем, используем только числовые входы.
$total = voovi_invoice_form_total(array(array('id'=>1,'price'=>'8')),
    array('input1'=>'219','skidka'=>'42.9'));
money_test_equal($total,'1000.39','Скидка сохраняет точный итог строки');
$result = voovi_invoice_amounts(array(array('price'=>'8','quantity'=>'219','discount'=>'42.9')), $total,0);
$parts = voovi_invoice_print_parts($result['lines'][0]);
money_test_equal(array_column($parts,'quantity'),array('44','175'),'Разделение количества после скидки');
money_test_equal(array_column($parts,'price'),array('4.56','4.57'),'Копеечные цены после скидки');
money_test_equal(array_column($parts,'gross'),array('200.64','799.75'),'Части сохраняют итог 1000.39');
foreach (array('gross','net') as $basis) {
    foreach (array(array('3','100.00'),array('1.5','1.00'),array('0.125','1.00'),
        array('0.003','0.01'),array('7','1.00'),array('12345678901234','100.00')) as $case) {
        $line = voovi_invoice_amounts(array(array('price'=>'100','quantity'=>$case[0],'amount'=>$case[1])), $case[1],20);
        $source = $line['lines'][0]; $parts = voovi_invoice_print_parts($source,$basis);
        $quantities = array(); $sums = array('gross'=>0,'net'=>0,'vat'=>0);
        foreach ($parts as $part) {
            $quantities[] = voovi_decimal($part['quantity']);
            $unit = $part[$basis === 'gross' ? 'price' : 'unit_net'];
            money_test_equal(preg_match('/^-?\d+\.\d{2}$/D',$unit),1,'В печатной цене только два знака');
            $product = voovi_money_sql('CAST(ROUND(('.$unit.')*('.voovi_decimal($part['quantity']).'),2) AS DECIMAL(20,2))');
            money_test_equal($product[0],$part[$basis],'Цена × количество соответствует печатной сумме');
            foreach ($sums as $key=>$unused) { $sums[$key] += voovi_money_cents($part[$key]); }
        }
        $quantitySum = voovi_money_sql('('.implode('+',$quantities).')=('.$case[0].')');
        money_test_equal($quantitySum[0],'1','Разделение сохраняет дробное количество');
        foreach ($sums as $key=>$sum) { money_test_equal($sum,voovi_money_cents($source[$key]),'Части сохраняют '.$key); }
    }
}
echo "PHP invoice money: exact total, saved total, travel, discounts and VAT — OK\n";
