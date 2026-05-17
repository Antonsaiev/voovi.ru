<?php

function voovi_schet_escape($value)
{
    return mysql_real_escape_string((string)$value);
}

function voovi_schet_counter_god($god)
{
    $god = (string)$god;
    if (strlen($god) === 4 && substr($god, 0, 2) === '20') {
        return substr($god, 2, 2);
    }

    return $god;
}

function voovi_schet_god_variants($god)
{
    $counterGod = voovi_schet_counter_god($god);
    $variants = array($counterGod);

    if (strlen($counterGod) === 2) {
        $variants[] = '20'.$counterGod;
    }

    if (!in_array((string)$god, $variants, true)) {
        $variants[] = (string)$god;
    }

    return $variants;
}

function voovi_schet_query_or_unlock($query)
{
    $result = mysql_query($query);
    if (!$result) {
        $error = mysql_error();
        @mysql_query('UNLOCK TABLES');
        die($error);
    }

    return $result;
}

function voovi_schet_max_kolichschet($god)
{
    $godWhere = array();
    foreach (voovi_schet_god_variants($god) as $godVariant) {
        $godWhere[] = "'".voovi_schet_escape($godVariant)."'";
    }

    $result = voovi_schet_query_or_unlock(
        "SELECT MAX(CAST(kolichschet AS UNSIGNED)) AS max_num
         FROM schet
         WHERE del = '0'
           AND god IN (".implode(',', $godWhere).")
           AND kolichschet REGEXP '^[0-9]+$'"
    );
    $row = mysql_fetch_assoc($result);

    return isset($row['max_num']) ? (int)$row['max_num'] : 0;
}

function voovi_schet_generate_rand($kolichschet, $attempt)
{
    $rand = sprintf('%05d', (int)$kolichschet).date('Ymd');
    if ((int)$attempt > 0) {
        $rand .= sprintf('%02d', (int)$attempt);
    }

    return $rand;
}

function voovi_schet_rand_exists($rand)
{
    $result = voovi_schet_query_or_unlock(
        "SELECT id FROM schet WHERE rand = '".voovi_schet_escape($rand)."' LIMIT 1"
    );

    return mysql_fetch_assoc($result) ? true : false;
}

function voovi_allocate_schet_identity($god, $kto, $otdel)
{
    $god = (string)$god;
    $kto = (string)$kto;
    $otdel = (string)$otdel;
    $counterGod = voovi_schet_counter_god($god);

    mysql_query('LOCK TABLES schetnum WRITE, schet READ') or die(mysql_error());

    $maxKolichschet = voovi_schet_max_kolichschet($god);
    $counterResult = voovi_schet_query_or_unlock(
        "SELECT id, num FROM schetnum WHERE god = '".voovi_schet_escape($counterGod)."' LIMIT 1"
    );
    $counter = mysql_fetch_assoc($counterResult);

    if (!$counter) {
        voovi_schet_query_or_unlock(
            "INSERT INTO schetnum (`num`, `god`) VALUES ('0', '".voovi_schet_escape($counterGod)."')"
        );
        $currentNum = 0;
    } else {
        $currentNum = (int)$counter['num'];
    }

    if ($currentNum < $maxKolichschet) {
        $currentNum = $maxKolichschet;
        voovi_schet_query_or_unlock(
            "UPDATE schetnum SET `num` = '".$currentNum."' WHERE `god` = '".voovi_schet_escape($counterGod)."'"
        );
    }

    $kolichschet = $currentNum + 1;
    voovi_schet_query_or_unlock(
        "UPDATE schetnum SET `num` = '".$kolichschet."' WHERE `god` = '".voovi_schet_escape($counterGod)."'"
    );

    $attempt = 0;
    do {
        $rand = voovi_schet_generate_rand($kolichschet, $attempt);
        $attempt++;
        if ($attempt > 99) {
            @mysql_query('UNLOCK TABLES');
            die('Не удалось сгенерировать уникальный rand счета');
        }
    } while (voovi_schet_rand_exists($rand));

    voovi_schet_query_or_unlock('UNLOCK TABLES');

    return array(
        'god' => $god,
        'counter_god' => $counterGod,
        'kolichschet' => (string)$kolichschet,
        'rand' => $rand,
        'ns' => $god.$kto.$otdel.$kolichschet,
    );
}
