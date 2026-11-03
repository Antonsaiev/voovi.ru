<?php
require_once __DIR__.'/schet_identity.php';

// Предыдущее значение не позволяет запоздавшему запросу затереть более новый ввод.
function voovi_invoice_field_save($rand, $field, $value, $expected)
{
    $columns=array('owner'=>'vladelec','address'=>'fadress');
    if (!is_string($rand) || !preg_match('/^[0-9]{1,366}$/D',$rand) || !isset($columns[$field]) ||
        !is_string($value) || !is_array($expected) || !$expected || count($expected)>1024) {
        throw new VooviSchetException('Некорректные данные поля.');
    }
    foreach ($expected as $old) if (!is_string($old) || mb_strlen($old,'UTF-8')>1366) throw new VooviSchetException('Некорректное предыдущее значение.');
    if (mb_strlen($value,'UTF-8')>1366) throw new VooviSchetException('Адрес слишком длинный. Максимум 1366 символов.');
    if ($field==='owner' && !preg_match('/^[0-9]{1,10}$/D',$value)) throw new VooviSchetException('Выберите владельца из списка.');
    $engine=mysql_fetch_assoc(voovi_schet_query("SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='schet'"));
    if (!$engine || strtolower($engine['ENGINE'])!=='innodb') throw new VooviSchetException('Для сохранения требуется транзакционная база.');
    $key=mysql_real_escape_string($rand); $column=$columns[$field];
    voovi_schet_query('START TRANSACTION');
    try {
        $rows=voovi_schet_query("SELECT id,idkli,`".$column."` AS value FROM schet WHERE rand='".$key."' AND del='0' ORDER BY id FOR UPDATE");
        $found=false; $clients=array();
        while ($row=mysql_fetch_assoc($rows)) {
            $found=true; $clients[(int)$row['idkli']]=true;
            if ((string)$row['value']!==$value && !in_array((string)$row['value'],$expected,true)) {
                throw new VooviSchetException('Поле изменено в другом окне. Ваш ввод сохранён как черновик; обновите страницу для проверки.');
            }
        }
        if (!$found) throw new VooviSchetException('Счёт не найден.');
        if ($field==='owner' && (int)$value!==0) {
            $contact=voovi_schet_query('SELECT klient FROM klient_ogrn WHERE klient='.(int)$value.' AND idkli IN ('.implode(',',array_keys($clients)).') LIMIT 1');
            if (!mysql_num_rows($contact)) throw new VooviSchetException('Владелец не относится к клиенту этого счёта.');
        }
        voovi_schet_query("UPDATE schet SET `".$column."`='".mysql_real_escape_string($value)."' WHERE rand='".$key."' AND del='0'");
        voovi_schet_query('COMMIT');
        return array('ok'=>true);
    } catch (Exception $error) { mysql_query('ROLLBACK'); throw $error; }
}
