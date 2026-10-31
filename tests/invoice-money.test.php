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
echo "PHP invoice money: exact total, saved total, travel, discounts and VAT — OK\n";
