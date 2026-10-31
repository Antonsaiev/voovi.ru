<?php
// Точная арифметика без float и дополнительных расширений PHP 5.6.
function voovi_decimal($value)
{
    $value = str_replace(array(' ', "\xc2\xa0", "\xe2\x80\x89", "\xe2\x80\xaf", ','), array('', '', '', '', '.'), trim((string)$value));
    if ($value === '') { return '0'; }
    if (!preg_match('/^[+-]?(?:[0-9]+(?:\.[0-9]*)?|\.[0-9]+)$/D', $value)) {
        throw new RuntimeException('Некорректная сумма или количество');
    }
    $value = ltrim($value, '+');
    $value = preg_replace('/^(-?)\./', '${1}0.', $value);
    $value = rtrim($value, '.');
    return $value;
}

function voovi_money_sql($expression)
{
    $query = mysql_query('SELECT '.$expression);
    if (!$query) { throw new RuntimeException('Не удалось рассчитать сумму счёта'); }
    return mysql_fetch_row($query);
}

function voovi_money_round($value)
{
    $result = voovi_money_sql('CAST(ROUND('.voovi_decimal($value).',2) AS DECIMAL(20,2))');
    return $result[0];
}

function voovi_money_format($value, $separator = ' ')
{
    $value = voovi_decimal($value);
    $parts = explode('.', $value, 2);
    if (isset($parts[1]) && strlen($parts[1]) > 2) { return voovi_money_format(voovi_money_round($value), $separator); }
    return preg_replace('/\B(?=(\d{3})+(?!\d))/', $separator, $parts[0]).'.'.str_pad(isset($parts[1]) ? $parts[1] : '', 2, '0');
}

function voovi_money_cents($value)
{
    $parts = explode('.', voovi_decimal($value), 2);
    if (isset($parts[1]) && strlen($parts[1]) > 2) { return voovi_money_cents(voovi_money_round($value)); }
    return (int)($parts[0].str_pad(isset($parts[1]) ? $parts[1] : '', 2, '0'));
}

function voovi_money_from_cents($value)
{
    $digits = str_pad((string)abs($value), 3, '0', STR_PAD_LEFT);
    return ($value < 0 ? '-' : '').substr($digits, 0, -2).'.'.substr($digits, -2);
}

function voovi_money_weight($line)
{
    return '('.voovi_decimal($line['price']).')*('.voovi_decimal($line['quantity']).')*(100-('.
        voovi_decimal(isset($line['discount']) ? $line['discount'] : 0).'))/100';
}

function voovi_invoice_form_total($tariffs, $data)
{
    $parts = array();
    foreach ($tariffs as $tariff) {
        $parts[] = voovi_money_weight(array('price'=>$tariff['price'],
            'quantity'=>$data['input'.$tariff['id']], 'discount'=>isset($data['skidka']) ? $data['skidka'] : 0));
    }
    $travel = voovi_decimal(isset($data['goroddd']) ? $data['goroddd'] : 0);
    if ($travel != '162534') {
        $parts[] = voovi_money_weight(array('price'=>$travel,
            'quantity'=>isset($data['kvogorod']) ? $data['kvogorod'] : 0,
            'discount'=>isset($data['skidka']) ? $data['skidka'] : 0));
    }
    $result = voovi_money_sql('CAST(ROUND('.implode('+', $parts).',2) AS DECIMAL(20,2))');
    return $result[0];
}

function voovi_allocate_cents($weights, $total)
{
    if (!$weights) { return array(); }
    $total = voovi_money_round($total);
    $raw = voovi_money_sql('CAST(('.implode('+', $weights).') AS DECIMAL(40,16))');
    $raw = $raw[0];
    $cents = array(); $remainders = array();
    $expressions = array();
    foreach ($weights as $index=>$weight) {
        $exact = (trim($raw, '0.') !== '') ? '('.$weight.')*('.$total.')*100/('.$raw.')' : ($index === 0 ? '('.$total.')*100' : '0');
        $expressions[] = 'FLOOR('.$exact.')';
        $expressions[] = 'CAST(('.$exact.')-FLOOR('.$exact.') AS DECIMAL(30,16))';
    }
    // Арифметика всех позиций одной пачкой, без отдельного SQL на каждую строку.
    $values = voovi_money_sql(implode(',', $expressions));
    foreach (array_keys($weights) as $offset=>$index) {
        $cents[$index] = (int)$values[$offset * 2];
        $remainders[$index] = $values[$offset * 2 + 1];
    }
    $remaining = voovi_money_cents($total) - array_sum($cents);
    $order = array_keys($cents);
    usort($order, function($a, $b) use ($remainders) {
        $comparison = strcmp($remainders[$b], $remainders[$a]);
        return $comparison ?: ($a - $b);
    });
    foreach (array_slice($order, 0, $remaining) as $index) { $cents[$index]++; }
    return $cents;
}

function voovi_invoice_amounts($lines, $total, $rate)
{
    // Общий сохранённый итог первичен. Копейки строк и НДС — по наибольшим остаткам.
    $gross = voovi_money_round($total);
    $rate = voovi_decimal($rate);
    if (!$lines) { return array('lines'=>array(), 'gross'=>$gross, 'vat'=>'0.00', 'net'=>$gross); }
    $weights = array();
    foreach ($lines as $line) { $weights[] = 'CAST('.voovi_money_weight($line).' AS DECIMAL(40,16))'; }
    $weights = voovi_money_sql(implode(',', $weights));
    $cents = voovi_allocate_cents($weights, $gross);
    $vatTotal = '0.00';
    if ($rate > 0) {
        $row = voovi_money_sql('CAST(ROUND(('.$gross.')*'.$rate.'/(100+'.$rate.'),2) AS DECIMAL(20,2))');
        $vatTotal = $row[0];
    }
    $vatCents = voovi_allocate_cents(array_map('voovi_money_from_cents', $cents), $vatTotal);
    $result = array(); $units = array();
    foreach ($lines as $index=>$line) {
        $quantity = voovi_decimal($line['quantity']);
        $amount = voovi_money_from_cents($cents[$index]);
        $net = voovi_money_from_cents($cents[$index]-$vatCents[$index]);
        $units[] = $quantity > 0 ? 'CAST(ROUND(('.$amount.')/'.$quantity.',2) AS DECIMAL(20,2)), CAST(ROUND(('.$net.')/'.$quantity.',2) AS DECIMAL(20,2))' : '0.00,0.00';
        $result[$index] = array('quantity'=>$quantity, 'gross'=>$amount, 'vat'=>voovi_money_from_cents($vatCents[$index]),
            'net'=>$net);
    }
    $values = voovi_money_sql(implode(',', $units));
    foreach (array_keys($lines) as $offset=>$index) {
        $result[$index]['price'] = $values[$offset * 2];
        $result[$index]['unit_net'] = $values[$offset * 2 + 1];
    }
    return array('lines'=>$result, 'gross'=>$gross, 'vat'=>$vatTotal,
        'net'=>voovi_money_from_cents(voovi_money_cents($gross)-voovi_money_cents($vatTotal)));
}

function voovi_invoice_document_amounts($rand, $invoice, $rate)
{
    $query = mysql_query("SELECT s.id,s.kvo,s.skidka,t.price FROM schet s LEFT JOIN tarif t ON t.id=s.prod
        WHERE s.`del`='0' AND s.rand='".mysql_real_escape_string((string)$rand)."' AND COALESCE(s.prod,'') NOT IN ('','0') ORDER BY s.id");
    if (!$query) { throw new RuntimeException('Не удалось прочитать состав счёта'); }
    $lines = array(); $ids = array();
    while ($row = mysql_fetch_assoc($query)) {
        $ids[] = $row['id'];
        $lines[] = array('price'=>$row['price'], 'quantity'=>$row['kvo'], 'discount'=>$row['skidka']);
    }
    if (!empty($invoice['goroddd']) && $invoice['goroddd'] != 162534) {
        $ids[] = 'travel';
        $lines[] = array('price'=>$invoice['goroddd'], 'quantity'=>$invoice['kvogorod'], 'discount'=>$invoice['skidka']);
    }
    $amounts = voovi_invoice_amounts($lines, $invoice['price'], $rate);
    $amounts['items'] = $ids ? array_combine($ids, $amounts['lines']) : array();
    return $amounts;
}

function voovi_invoice_travel_name($invoice)
{
    $price = voovi_decimal($invoice['goroddd']);
    foreach (array(450=>'Пятигорск', 750=>'КМВ', 1500=>'Георгиевск') as $cityPrice=>$city) {
        if ($price == $cityPrice) { return 'Выезд: '.$city; }
    }
    // В goroddd хранится стоимость одной поездки, а kvogorod — число поездок.
    $distance = voovi_money_sql('CAST(('.$price.')/23 AS DECIMAL(20,6))');
    return 'Выезд: '.rtrim(rtrim($distance[0], '0'), '.').' км';
}
