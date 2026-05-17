<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
function nds_parse($raw) {
    $rates = array(
        'none'  => 0,
        'vat0'  => 0,
        'vat5'  => 5,
        'vat7'  => 7,
        'vat10' => 10,
        'vat20' => 20,
    );

    $code = 'none';
    $rate = 0;

    if ($raw === null) { $raw = ''; }
    $rawStr = trim((string)$raw);

    $j = json_decode($rawStr, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($j)) {
        $c = isset($j['code']) ? $j['code'] : '';
        $r = isset($j['rate']) ? $j['rate'] : null;

        $c = strtolower(trim((string)$c));
        $c = str_replace(array('_','-',' '), '', $c);

        if ($c !== '' && isset($rates[$c])) {
            $code = $c; $rate = $rates[$c];
        } elseif ($c === 'vat' && is_numeric($r) && isset($rates['vat'.(int)$r])) {
            $code = 'vat'.(int)$r; $rate = $rates[$code];
        } elseif (is_numeric($r) && isset($rates['vat'.(int)$r])) {
            $code = 'vat'.(int)$r; $rate = $rates[$code];
        }
    } else {
        $v = strtolower($rawStr);
        $v = str_replace(array('_','-',' '), '', $v);

        if (isset($rates[$v])) {
            $code = $v; $rate = $rates[$v];
        } elseif (is_numeric($v) && isset($rates['vat'.(int)$v])) {
            $code = 'vat'.(int)$v; $rate = $rates[$code];
        } else {
            // “без” ищем по UTF-8 без mb_stripos
            $rawLower = function_exists('mb_strtolower') ? mb_strtolower($rawStr, 'UTF-8') : strtolower($rawStr);
            if (strpos($rawLower, 'без') !== false) {
                $code = 'none'; $rate = 0;
            }
        }
    }

    return array('code' => $code, 'rate' => (float)$rate);
}

function nds_included_sum($gross, $rate) {
    $gross = (float)$gross;
    $rate  = (float)$rate;

    if ($rate <= 0) { return 0.0; }
    return round($gross * $rate / (100 + $rate), 2);
}
