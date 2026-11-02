<?php
require 'conf.php';
require_once 'schet_identity.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(array('error'=>'Используйте форму счёта')); exit;
}
$user = voovi_schet_authenticated_user();
try {
    $rand = isset($_POST['rand']) && is_string($_POST['rand']) ? $_POST['rand'] : '';
    $reason = isset($_POST['tipotkaz']) ? (int)$_POST['tipotkaz'] : 0;
    $extension = isset($_POST['tipschet']) && $_POST['tipschet'] === 'prod';
    $scope = 'otkazschet.php:'.$rand;
    voovi_schet_request_key(isset($_POST['creation_token']) ? $_POST['creation_token'] : '', $scope, $user);
    if ($rand === '' || strlen($rand) > 366 || $reason <= 0) { throw new RuntimeException('Выберите счёт и причину отказа.'); }
    $sourceNumber = voovi_schet_stored_number($rand);
    $escapedRand = mysql_real_escape_string($rand);
    $first = mysql_fetch_assoc(voovi_schet_query("SELECT * FROM schet WHERE rand='".$escapedRand."' AND `del`='0' ORDER BY id LIMIT 1"));
    $shipped = (string)$first['akt'] === '1';
    $identity = null;
    if ($shipped) {
        voovi_schet_require_transactions(array('bills_voovi_links'));
        // Автор и отдел исходного счёта сохраняются; повтор действия возвращает прежний клон.
        $identity = voovi_begin_schet_creation($_POST['creation_token'], $scope, $user, $first['idkli'], $first['kto'], $first['otdel'],
            hash('sha256', 'voovi.refusal:'.$rand));
        if ($identity['reused']) { echo json_encode(array('rand'=>$identity['rand'], 'reused'=>true)); exit; }
        voovi_schet_query("SELECT id FROM schet WHERE rand='".$escapedRand."' AND `del`='0' FOR UPDATE");
        voovi_schet_stored_number($rand);
    } else {
        voovi_begin_schet_edit($rand);
    }
    $rows = voovi_schet_query("SELECT * FROM schet WHERE rand='".$escapedRand."' AND `del`='0' ORDER BY id");
    $source = array();
    while ($row = mysql_fetch_assoc($rows)) {
        if (((string)$row['akt'] === '1') !== $shipped) { throw new RuntimeException('Состояние счёта изменилось. Обновите список.'); }
        if ((string)$row['cher'] === '1' && (int)$row['prichotk'] > 0) {
            // A second form must not replace the original reason/author/date.
            voovi_schet_rollback();
            echo json_encode(array('rand'=>$rand, 'reused'=>true)); exit;
        }
        if ($shipped && !empty($row['dateotkaz']) && $row['dateotkaz'] !== '0000-00-00') {
            throw new RuntimeException('Отказ по этому счёту уже оформлен. Обновите список.');
        }
        $source[] = $row;
    }
    if (!$source) { throw new RuntimeException('Счёт больше не доступен. Обновите список.'); }
    $changes = "dateotkaz='".date('Y-m-d')."',ktootkaz=".(int)$user['users_id'];
    if ($extension) { $changes .= ",prodlens='1',prodlenks='1'"; }
    if (!$shipped) { $changes .= ",cher='1',prichotk=".$reason; }
    voovi_schet_query("UPDATE schet SET ".$changes." WHERE rand='".$escapedRand."' AND `del`='0'");
    if ($shipped) {
        $fields = array('idkli','sortir','otdel','filial','nomerdog','nomerschet','nomerschetks','kvo','prod','summa','goroddd','kvogorod','nds',
            'prodlen','skidka','priceks','price','ogrn','name','inn','kpp','produkt','kto','data','d','m','y','time',
            'lico','vladelec','oferta','url');
        foreach ($source as $row) {
            $copy = array();
            foreach ($fields as $field) { $copy[$field] = $row[$field]; }
            $copy['ns'] = $identity['ns']; $copy['rand'] = $identity['rand']; $copy['god'] = $identity['god'];
            $copy['kolichschet'] = $identity['kolichschet']; $copy['shetold'] = $sourceNumber;
            $copy['oplachenks'] = '0'; $copy['cher'] = '1'; $copy['prichotk'] = $reason;
            $copy['ktootkaz'] = $user['users_id']; $copy['dateotkaz'] = date('Y-m-d');
            $copy['koment'] = 'Отказ от счета '.$sourceNumber;
            if (strlen((string)$copy['y']) === 2) { $copy['y'] = '20'.$copy['y']; }
            if ($extension) {
                $copy['data'] = date('d.m.y в H:i'); $copy['d'] = date('d'); $copy['m'] = date('m'); $copy['y'] = date('Y');
            }
            $values = array();
            foreach ($copy as $value) { $values[] = $value === null ? 'NULL' : "'".mysql_real_escape_string((string)$value)."'"; }
            voovi_schet_query('INSERT INTO schet (`'.implode('`,`', array_keys($copy)).'`) VALUES ('.implode(',', $values).')');
        }
        voovi_copy_schet_related_data($rand, $identity['rand'], $copy);
    }
    $resultRand = $identity ? $identity['rand'] : $rand;
    voovi_schet_commit($resultRand);
    echo json_encode(array('rand'=>$resultRand, 'reused'=>false));
} catch (Exception $error) {
    voovi_schet_rollback();
    http_response_code(400);
    echo json_encode(array('error'=>$error->getMessage()), JSON_UNESCAPED_UNICODE);
}
