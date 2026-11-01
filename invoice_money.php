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

function voovi_decimal_canonical($value)
{
    $value = voovi_decimal($value);
    $negative = substr($value, 0, 1) === '-';
    $parts = explode('.', ltrim($value, '-'), 2);
    $whole = ltrim($parts[0], '0');
    $fraction = isset($parts[1]) ? rtrim($parts[1], '0') : '';
    $number = ($whole === '' ? '0' : $whole).($fraction === '' ? '' : '.'.$fraction);
    return ($negative && $number !== '0' ? '-' : '').$number;
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

function voovi_invoice_print_parts($line, $basis = 'gross')
{
    // Делим только печатную строку: исходное количество, суммы и снимок не меняются.
    if (!in_array($basis, array('gross','net'), true)) { throw new RuntimeException('Неизвестный вид цены документа'); }
    $quantity = voovi_decimal($line['quantity']); $amount = voovi_decimal($line[$basis]);
    $unitField = $basis === 'gross' ? 'price' : 'unit_net';
    if (voovi_decimal_canonical($amount) === '0') { $line[$unitField] = '0.00'; return array($line); }
    if ($quantity <= 0) { throw new RuntimeException('Нельзя согласовать сумму строки с неположительным количеством'); }
    $ratio = 'CAST(('.$amount.') AS DECIMAL(65,30))/CAST(('.$quantity.') AS DECIMAL(65,30))';
    $prices = voovi_money_sql('CAST(ROUND('.$ratio.',2) AS DECIMAL(65,2)),CAST(FLOOR(('.$ratio.')*100)/100 AS DECIMAL(65,2))');
    if ($prices[0] === null || $prices[1] === null) { throw new RuntimeException('Нельзя согласовать количество и стоимость печатной строки'); }
    $check = voovi_money_sql('ROUND(('.$prices[0].')*('.$quantity.'),2)=('.$amount.')');
    if ($check[0] === '1') { $line[$unitField] = $prices[0]; return array($line); }
    $low = voovi_decimal($prices[1]);
    $higher = '(('.$amount.')-('.$low.')*('.$quantity.'))/0.01';
    $fraction = explode('.', voovi_decimal_canonical($quantity), 2);
    $scale = max(2, isset($fraction[1]) ? strlen($fraction[1]) : 0);
    if ($scale > 30) { throw new RuntimeException('Слишком точное количество для печатной строки'); }
    $step = '0.'.str_repeat('0', $scale-1).'1';
    // Соседние деления нужны, когда округление двух половин копейки даёт лишнюю копейку.
    foreach (array($higher, '('.$higher.')-'.$step, '('.$higher.')+'.$step) as $candidate) {
        $values = voovi_money_sql('('.$quantity.')-('.$candidate.'),('.$candidate.'),'.
            'CAST(ROUND((('.$quantity.')-('.$candidate.'))*('.$low.'),2) AS DECIMAL(20,2)),'.
            'CAST(ROUND(('.$candidate.')*(('.$low.')+0.01),2) AS DECIMAL(20,2)),'.
            '(('.$candidate.')>=0 AND ('.$candidate.')<=('.$quantity.'))');
        if (in_array(null, $values, true) || $values[4] !== '1' ||
            voovi_money_cents($values[2])+voovi_money_cents($values[3]) !== voovi_money_cents($amount)) { continue; }
        $parts = array();
        foreach (array(array($values[0],$low,$values[2]),
            array($values[1],voovi_money_from_cents(voovi_money_cents($low)+1),$values[3])) as $part) {
            if (voovi_decimal($part[0]) <= 0) { continue; }
            $copy = $line; $copy['quantity'] = voovi_decimal_canonical($part[0]);
            $copy[$unitField] = $part[1]; $copy[$basis] = $part[2]; $parts[] = $copy;
        }
        $vat = voovi_allocate_cents(array_column($parts, $basis), $line['vat']);
        foreach ($parts as $index=>&$part) {
            $part['vat'] = voovi_money_from_cents($vat[$index]);
            if ($basis === 'gross') { $part['net'] = voovi_money_from_cents(voovi_money_cents($part['gross'])-$vat[$index]); }
            else { $part['gross'] = voovi_money_from_cents(voovi_money_cents($part['net'])+$vat[$index]); }
        }
        unset($part);
        return $parts;
    }
    throw new RuntimeException('Нельзя согласовать количество и стоимость печатной строки');
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
    foreach ($lines as $line) {
        $weights[] = array_key_exists('amount', $line) ? voovi_decimal($line['amount']) :
            'CAST('.voovi_money_weight($line).' AS DECIMAL(40,16))';
    }
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
    $query = mysql_query("SELECT s.id,s.prod,s.kvo,s.skidka,s.summa,t.price FROM schet s LEFT JOIN tarif t ON t.id=s.prod
        WHERE s.`del`='0' AND s.rand='".mysql_real_escape_string((string)$rand)."' AND COALESCE(s.prod,'') NOT IN ('','0') ORDER BY s.id");
    if (!$query) { throw new RuntimeException('Не удалось прочитать состав счёта'); }
    $lines = array(); $ids = array(); $snapshots = array();
    while ($row = mysql_fetch_assoc($query)) {
        $ids[] = $row['id'];
        $lines[] = array('price'=>$row['price'], 'quantity'=>$row['kvo'], 'discount'=>$row['skidka']);
        $stored = trim((string)$row['summa']);
        $snapshot = null;
        if (substr($stored, 0, 1) === '{') {
            $snapshot = json_decode($stored, true);
            if (!is_array($snapshot) || !isset($snapshot['v'],$snapshot['p'],$snapshot['q'],$snapshot['d'],$snapshot['a'],$snapshot['t']) ||
                $snapshot['v'] !== 1 || (string)$snapshot['p'] !== (string)$row['prod'] || !is_array($snapshot['t']) ||
                voovi_decimal_canonical($snapshot['q']) !== voovi_decimal_canonical($row['kvo']) ||
                voovi_decimal_canonical($snapshot['d']) !== voovi_decimal_canonical($row['skidka']) ||
                voovi_decimal_canonical($snapshot['a']) !== voovi_decimal_canonical(voovi_money_round($snapshot['a'])) ||
                voovi_decimal($snapshot['a']) < 0) {
                throw new RuntimeException('Сохранённый состав счёта изменён или повреждён');
            }
        }
        $snapshots[] = $snapshot;
    }
    $frozen = count(array_filter($snapshots));
    $totalCents = 0;
    $travel = !empty($invoice['goroddd']) && $invoice['goroddd'] != 162534 &&
        voovi_decimal($invoice['kvogorod']) > 0;
    if ($frozen) {
        foreach ($snapshots as $index=>$snapshot) {
            if (!$snapshot) { continue; }
            $lines[$index]['amount'] = $snapshot['a'];
            $totalCents += voovi_money_cents($snapshot['a']);
            if ($index > 0 && $snapshot['t']) { throw new RuntimeException('Выезд повторяется в составе счёта'); }
        }
        if ($snapshots[0]) {
            $recorded = $snapshots[0]['t'];
            if ((bool)$recorded !== (bool)$travel || ($recorded && (count($recorded) !== 3 ||
                voovi_decimal_canonical($recorded[1]) !== voovi_decimal_canonical($invoice['kvogorod']) ||
                voovi_decimal_canonical($recorded[2]) !== voovi_decimal_canonical($invoice['goroddd']) ||
                voovi_decimal($recorded[0]) < 0 || voovi_decimal_canonical($recorded[0]) !==
                voovi_decimal_canonical(voovi_money_round($recorded[0]))))) {
                throw new RuntimeException('Выезд изменён после сохранения стоимости счёта');
            }
            if ($recorded) { $totalCents += voovi_money_cents($recorded[0]); }
        }
    }
    if ($travel) {
        $ids[] = 'travel';
        $line = array('price'=>$invoice['goroddd'], 'quantity'=>$invoice['kvogorod'], 'discount'=>$invoice['skidka']);
        if ($frozen && $snapshots[0]) { $line['amount'] = $snapshots[0]['t'][0]; }
        $lines[] = $line;
    }
    if ($frozen) {
        // JSON-суммы неизменны; только оставшийся итог распределяем по старым ценам.
        $remaining = voovi_money_cents($invoice['price']) - $totalCents;
        if ($remaining < 0) { throw new RuntimeException('Сохранённые суммы строк превышают итог счёта'); }
        $legacy = array();
        foreach ($lines as $index=>$line) {
            if (!array_key_exists('amount', $line)) { $legacy[$index] = voovi_money_weight($line); }
        }
        if (!$legacy && $remaining !== 0) {
            throw new RuntimeException('Суммы строк не совпадают с сохранённым итогом счёта');
        }
        if ($legacy) {
            $weight = voovi_money_sql('CAST(('.implode('+', $legacy).') AS DECIMAL(40,16))');
            if ($remaining > 0 && voovi_decimal($weight[0]) <= 0) {
                throw new RuntimeException('Не удалось определить стоимость старых строк счёта');
            }
            $cents = voovi_allocate_cents($legacy, voovi_money_from_cents($remaining));
            foreach ($cents as $index=>$amount) { $lines[$index]['amount'] = voovi_money_from_cents($amount); }
        }
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

function voovi_capture_invoice_prices($rand, $tariffs)
{
    // Вызывается только при выставлении/замене состава: старую историю прайсом не заполняем.
    if (empty($GLOBALS['voovi_schet_transaction'])) { throw new VooviSchetException('Сохранение стоимости счёта не начато.'); }
    $prices = array();
    foreach ($tariffs as $tariff) { $prices[(string)$tariff['id']] = $tariff['price']; }
    $query = voovi_schet_query("SELECT id,prod,kvo,skidka,price,goroddd,kvogorod FROM schet WHERE `del`='0' AND rand='".
        mysql_real_escape_string((string)$rand)."' ORDER BY id");
    $rows = array(); $lines = array();
    while ($row = mysql_fetch_assoc($query)) {
        if (!array_key_exists((string)$row['prod'], $prices)) {
            throw new VooviSchetException('Не удалось сохранить стоимость тарифов счёта.');
        }
        $rows[] = $row;
        $lines[] = array('price'=>$prices[(string)$row['prod']], 'quantity'=>$row['kvo'], 'discount'=>$row['skidka']);
    }
    if (!$rows) { throw new VooviSchetException('Не найден состав для сохранения стоимости.'); }
    $first = $rows[0];
    $travel = voovi_decimal($first['goroddd']) > 0 && voovi_decimal($first['goroddd']) != '162534'
        && voovi_decimal($first['kvogorod']) > 0;
    if ($travel) {
        $lines[] = array('price'=>$first['goroddd'], 'quantity'=>$first['kvogorod'], 'discount'=>$first['skidka']);
    }
    $amounts = voovi_invoice_amounts($lines, $first['price'], 0);
    $weights = array();
    foreach ($lines as $line) { $weights[] = voovi_money_weight($line); }
    $calculated = voovi_money_sql('CAST(ROUND(('.implode('+', $weights).'),2) AS DECIMAL(20,2))');
    $knownPrices = voovi_decimal_canonical($calculated[0]) === voovi_decimal_canonical($first['price']);
    foreach ($rows as $index=>$row) {
        $snapshot = array('v'=>1,'p'=>(string)$row['prod'],'q'=>(string)$row['kvo'],'d'=>(string)$row['skidka'],
            'a'=>$amounts['lines'][$index]['gross'],'t'=>array());
        // При обычном выставлении точная цена известна даже при скидке 100%.
        $snapshot['b'] = $knownPrices ? array(voovi_decimal_canonical($lines[$index]['price']), '1', '0') :
            array($snapshot['a'], voovi_decimal_canonical($row['kvo']), voovi_decimal_canonical($row['skidka']));
        if ($index === 0 && $travel) {
            $snapshot['t'] = array($amounts['lines'][count($lines)-1]['gross'],
                (string)$first['kvogorod'], (string)$first['goroddd']);
            $snapshot['r'] = $knownPrices ? array(voovi_decimal_canonical($first['goroddd']), '1', '0') :
                array($snapshot['t'][0], voovi_decimal_canonical($first['kvogorod']), voovi_decimal_canonical($first['skidka']));
        }
        $encoded = json_encode($snapshot);
        if ($encoded === false || strlen($encoded) > 200) {
            throw new VooviSchetException('Слишком длинные числовые параметры строки счёта.');
        }
        voovi_schet_query("UPDATE schet SET summa='".mysql_real_escape_string($encoded)."' WHERE id=".(int)$row['id']);
    }
}
