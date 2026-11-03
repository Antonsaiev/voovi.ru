<?php
require_once __DIR__.'/schet_identity.php';

function voovi_invoice_manager_allowed($user)
{
    return isset($user['adm']) && (string)$user['adm'] === '1';
}

function voovi_invoice_manager_state($rand, $lock = false)
{
    if (!is_string($rand) || !preg_match('/^[0-9]{1,366}$/D', $rand)) {
        throw new VooviSchetException('Некорректный счёт');
    }
    $key = mysql_real_escape_string($rand);
    $suffix = $lock ? ' FOR UPDATE' : '';
    $links = voovi_schet_query("SELECT id, BillId FROM bills_voovi_links WHERE schet_rand='".$key."' ORDER BY id".$suffix);
    $bills = array(); $related = array();
    while ($link = mysql_fetch_assoc($links)) {
        if (empty($link['BillId'])) continue;
        $id = mysql_real_escape_string($link['BillId']);
        $bill = mysql_fetch_assoc(voovi_schet_query("SELECT BillId, created_by, status FROM bills WHERE BillId='".$id."'".$suffix));
        if (!$bill || $bill['status'] === 'deleted') continue;
        if (in_array($bill['status'], array('draft','sending','unknown'), true)) {
            throw new VooviSchetException('Дождитесь завершения создания счёта Контура и повторите смену менеджера.');
        }
        $bills[] = $bill;
        $others = voovi_schet_query("SELECT DISTINCT l.schet_rand, s.ns FROM bills_voovi_links l JOIN schet s ON s.rand=l.schet_rand AND s.del='0' " .
            "WHERE l.BillId='".$id."' AND l.schet_rand<>'".$key."' ORDER BY l.schet_rand,s.ns");
        while ($other = mysql_fetch_assoc($others)) $related[$other['schet_rand'].':'.$other['ns']] = $other;
    }
    $rows = array(); $owners = array();
    $result = voovi_schet_query("SELECT id,ns,kto FROM schet WHERE rand='".$key."' AND del='0' ORDER BY id".$suffix);
    while ($row = mysql_fetch_assoc($result)) { $rows[]=$row; $owners[(int)$row['kto']]=true; }
    if (!$rows) throw new VooviSchetException('Счёт не найден');
    $names = array();
    foreach (array_keys($owners) as $owner) {
        $user = mysql_fetch_assoc(voovi_schet_query('SELECT f_name,l_name,o_name FROM users WHERE users_id='.(int)$owner));
        $names[] = $user ? trim($user['f_name'].' '.$user['l_name'].' '.$user['o_name']) : 'Не указан';
    }
    $related=array_values($related);
    return array('rows'=>$rows, 'bills'=>$bills, 'related'=>$related,
        'current'=>implode(', ', $names), 'manager_id'=>count($owners)===1 ? (int)key($owners) : null,
        'version'=>hash('sha256',json_encode(array($rows,$bills,$related))));
}

function voovi_invoice_manager_change($rand, $manager, $actor, $version, $sharedConfirmed = false)
{
    if (!voovi_invoice_manager_allowed($actor)) throw new VooviSchetException('Смена менеджера доступна только руководителю.');
    if (!preg_match('/^[1-9][0-9]*$/D', (string)$manager)) throw new VooviSchetException('Выберите менеджера');
    // Счёт, документ Контура и журнал должны сохраняться целиком.
    $engines=voovi_schet_query("SELECT TABLE_NAME,ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME IN ('schet','bills','bills_voovi_links','aktivn')");
    $count=0;
    while ($engine=mysql_fetch_assoc($engines)) {
        if (strtolower($engine['ENGINE'])!=='innodb') throw new VooviSchetException('Для смены менеджера требуется транзакционная база. Обратитесь к администратору.');
        $count++;
    }
    if ($count!==4) throw new VooviSchetException('Не найдены таблицы для сохранения менеджера.');
    voovi_schet_query('START TRANSACTION');
    try {
        $state=voovi_invoice_manager_state($rand,true);
        $user=mysql_fetch_assoc(voovi_schet_query('SELECT users_id,f_name,l_name,o_name FROM users WHERE users_id='.(int)$manager." AND del_users=0 AND uvl<>'1' AND tip<>88 FOR UPDATE"));
        if (!$user) throw new VooviSchetException('Этот менеджер недоступен для назначения.');
        $same=$state['manager_id']===(int)$manager;
        foreach ($state['bills'] as $bill) $same=$same && (string)$bill['created_by']===(string)$manager;
        if (!$same) {
            if ($state['related'] && !$sharedConfirmed) throw new VooviSchetException('Подтвердите смену менеджера общего документа Контура.');
            if (!is_string($version) || !hash_equals($state['version'],$version)) throw new VooviSchetException('Данные счёта изменились. Закройте окно и откройте его заново.');
            voovi_schet_query("UPDATE schet SET kto=".(int)$manager." WHERE rand='".mysql_real_escape_string($rand)."' AND del='0'");
            foreach ($state['bills'] as $bill) {
                voovi_schet_query("UPDATE bills SET created_by=".(int)$manager." WHERE BillId='".mysql_real_escape_string($bill['BillId'])."'");
            }
            $message='Смена менеджера счета '.$state['rows'][0]['ns'].': '.$state['current'].' -> '.trim($user['f_name'].' '.$user['l_name'].' '.$user['o_name']).'; документы Контура: '.count($state['bills']);
            voovi_schet_query("INSERT INTO aktivn (data,deistvie,users) VALUES ('".date('d.m.Y; H:i:s')."','".mysql_real_escape_string($message)."',".(int)$actor['users_id'].")");
        }
        voovi_schet_query('COMMIT');
        return array('ok'=>true);
    } catch (Exception $error) {
        mysql_query('ROLLBACK');
        throw $error;
    }
}
