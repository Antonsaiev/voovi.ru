<?php
// Только сообщения этой ошибки разрешено показывать пользователю.
class VooviSchetException extends RuntimeException {}

// Номер выдаётся один раз. Состав счёта хранит готовые ns/rand, а не собирает их при чтении.
function voovi_schet_counter_god($god)
{
    if (!preg_match('/^(?:20)?[0-9]{2}$/D', (string)$god)) {
        throw new VooviSchetException('Некорректный год счёта');
    }
    return substr((string)$god, -2);
}

function voovi_schet_query($sql)
{
    $result = mysql_query($sql);
    if ($result === false) {
        error_log('Сохранение счёта Voovi: '.mysql_error());
        throw new VooviSchetException('Не удалось сохранить счёт. Обратитесь к администратору.');
    }
    return $result;
}

function voovi_schet_stored_number($rand)
{
    $rows = voovi_schet_query("SELECT ns, idkli FROM schet WHERE rand = '".
        mysql_real_escape_string((string)$rand)."' AND del = '0'");
    $numbers = array();
    $clients = array();
    while ($row = mysql_fetch_assoc($rows)) {
        $numbers[(string)$row['ns']] = true;
        $clients[(string)$row['idkli']] = true;
    }
    if (count($numbers) !== 1 || count($clients) !== 1) {
        throw new VooviSchetException('Данные счёта неоднозначны. Требуется проверка номера и группы.');
    }
    $number = (string)key($numbers);
    if ($number === '' || $number === '0') {
        throw new VooviSchetException('Номер счёта не сохранён. Требуется проверка нумерации.');
    }
    $other = voovi_schet_query("SELECT id FROM schet WHERE ns='".mysql_real_escape_string($number)."' AND rand<>'".
        mysql_real_escape_string((string)$rand)."' AND `del`='0' LIMIT 1");
    if (mysql_num_rows($other)) { throw new VooviSchetException('Номер относится к нескольким счетам. Требуется проверка данных.'); }
    return $number;
}

function voovi_schet_find_rand($number, $clientId = null)
{
    // Номер нужен только для поиска. Изменяем найденную группу по rand, не соседние счета.
    $sql = "SELECT DISTINCT rand FROM schet WHERE ns='".mysql_real_escape_string((string)$number)."' AND `del`='0'";
    if ($clientId !== null) { $sql .= " AND idkli='".mysql_real_escape_string((string)$clientId)."'"; }
    $rows = voovi_schet_query($sql." LIMIT 2");
    if (mysql_num_rows($rows) !== 1) { return null; }
    $row = mysql_fetch_assoc($rows);
    return $row['rand'];
}

function voovi_allocate_schet_identity($god, $kto, $otdel, $clientId = null)
{
    $god = voovi_schet_counter_god($god);
    $kto = (string)$kto;
    $otdel = (string)$otdel;
    if (!preg_match('/^[1-9][0-9]*$/D', $kto) || !preg_match('/^[a-zA-Z0-9]+$/D', $otdel)) {
        throw new VooviSchetException('Не указан менеджер или отдел для номера счёта');
    }
    if ($clientId !== null && !preg_match('/^[1-9][0-9]*$/D', (string)$clientId)) {
        throw new VooviSchetException('Не указан клиент счёта');
    }
    $clientSql = $clientId === null ? 'NULL' : "'".mysql_real_escape_string((string)$clientId)."'";
    $year = mysql_real_escape_string($god);
    // Уникальный год и атомарный UPDATE заменяют блокировку всей таблицы schet.
    voovi_schet_query("INSERT IGNORE INTO schetnum (god, num) VALUES ('".$year."', 0)");
    for ($attempt = 0; $attempt < 1000; $attempt++) {
        voovi_schet_query("UPDATE schetnum SET num = LAST_INSERT_ID(num + 1) WHERE god = '".$year."'");
        $number = (string)mysql_insert_id();
        if ($number === '0') {
            throw new VooviSchetException('Счётчик номеров не обновлён');
        }
        $ns = $god.$kto.$otdel.$number;
        $rand = str_pad($number, 5, '0', STR_PAD_LEFT).date('Ymd');
        if (strlen($ns) > 100 || strlen($rand) > 366) {
            throw new VooviSchetException('Превышена допустимая длина номера счёта');
        }
        // Один INSERT InnoDB резервирует оба значения до записи позиций MyISAM.
        // Резерв не удаляется после сбоя: пропуск допустим, повторное использование — нет.
        $reserved = mysql_query("INSERT INTO schet_identity_keys (kind,value,schet_rand,schet_ns,schet_client) VALUES ('ns','".
            mysql_real_escape_string($ns)."','".mysql_real_escape_string($rand)."','".mysql_real_escape_string($ns)."',".$clientSql.
            "),('rand','".mysql_real_escape_string($rand)."','".mysql_real_escape_string($rand)."','".
            mysql_real_escape_string($ns)."',".$clientSql.")");
        if ($reserved !== false) {
            return array('god' => $god, 'counter_god' => $god, 'kolichschet' => $number,
                'rand' => $rand, 'ns' => $ns);
        }
        if (mysql_errno() != 1062) {
            error_log('Резервирование номера Voovi: '.mysql_error());
            throw new VooviSchetException('Не удалось зарезервировать номер счёта. Проверьте схему нумерации.');
        }
    }
    throw new VooviSchetException('Счётчик отстал от выданных номеров. Требуется проверка нумерации.');
}

// Транзакция защищает весь состав и связанные записи. Незавершённый запрос откатывается.
function voovi_schet_require_transactions($additionalTables = array())
{
    $required = array_merge(array('schet', 'av', 'aktivn', 'document_data', 'dogovor', 'schet_identity_keys'), $additionalTables);
    $rows = voovi_schet_query("SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME IN ('".
        implode("','", $required)."')");
    $engines = array();
    while ($row = mysql_fetch_assoc($rows)) { $engines[$row['TABLE_NAME']] = strtolower($row['ENGINE']); }
    foreach ($required as $table) {
        if (!isset($engines[$table]) || $engines[$table] !== 'innodb') {
            throw new VooviSchetException('Для безопасного сохранения счёта требуется обновить структуру базы данных.');
        }
    }
}

function voovi_schet_rollback()
{
    if (!empty($GLOBALS['voovi_schet_transaction'])) {
        mysql_query('ROLLBACK');
        $GLOBALS['voovi_schet_transaction'] = false;
    }
    if (!empty($GLOBALS['voovi_schet_creation_lock'])) {
        mysql_query("SELECT RELEASE_LOCK('".mysql_real_escape_string($GLOBALS['voovi_schet_creation_lock'])."')");
        $GLOBALS['voovi_schet_creation_lock'] = null;
    }
}

function voovi_schet_begin_transaction()
{
    voovi_schet_require_transactions();
    if (!empty($GLOBALS['voovi_schet_transaction'])) { throw new VooviSchetException('Сохранение счёта уже выполняется.'); }
    voovi_schet_query('START TRANSACTION');
    $GLOBALS['voovi_schet_transaction'] = true;
    if (empty($GLOBALS['voovi_schet_shutdown_registered'])) {
        register_shutdown_function('voovi_schet_rollback');
        $GLOBALS['voovi_schet_shutdown_registered'] = true;
    }
}

function voovi_schet_commit($rand)
{
    if (empty($GLOBALS['voovi_schet_transaction'])) { throw new VooviSchetException('Транзакция счёта не открыта.'); }
    voovi_schet_stored_number($rand); // Пустой или неоднозначный состав никогда не фиксируем.
    voovi_schet_query('COMMIT');
    $GLOBALS['voovi_schet_transaction'] = false;
    voovi_schet_rollback(); // Освобождаем блокировку операции после фиксации результата.
}

function voovi_schet_form_token($scope, $user)
{
    $nonce = bin2hex(openssl_random_pseudo_bytes(24));
    return $nonce.'.'.hash_hmac('sha256', $nonce.'|'.$scope.'|'.$user['users_id'], $user['users_hash']);
}

function voovi_schet_request_key($token, $scope, $user)
{
    if (!is_string($token) || !preg_match('/^([a-f0-9]{48})\.([a-f0-9]{64})$/D', $token, $parts) ||
        !hash_equals(hash_hmac('sha256', $parts[1].'|'.$scope.'|'.$user['users_id'], $user['users_hash']), $parts[2])) {
        throw new VooviSchetException('Обновите форму счёта и повторите сохранение.');
    }
    return hash('sha256', $scope.'|'.$user['users_id'].'|'.$token);
}

function voovi_begin_schet_creation($token, $scope, $user, $clientId = null, $numberManager = null, $numberDepartment = null, $operationKey = null)
{
    $key = voovi_schet_request_key($token, $scope, $user);
    // Some transitions (refusal) belong to the source invoice, not to a browser
    // form. The caller supplies this server-derived key after token validation.
    if ($operationKey !== null) {
        if (!is_string($operationKey) || !preg_match('/^[a-f0-9]{64}$/D', $operationKey)) {
            throw new VooviSchetException('Некорректный ключ операции');
        }
        $key = $operationKey;
    }
    voovi_schet_require_transactions();
    $lock = 'voovi.create.'.substr($key, 0, 48);
    $locked = mysql_fetch_row(voovi_schet_query("SELECT GET_LOCK('".$lock."', 20)"));
    if (!$locked || (int)$locked[0] !== 1) { throw new VooviSchetException('Счёт уже создаётся. Повторите запрос позже.'); }
    $GLOBALS['voovi_schet_creation_lock'] = $lock;
    $previous = mysql_fetch_assoc(voovi_schet_query("SELECT schet_rand FROM schet_identity_keys WHERE kind='post' AND value='".$key."'"));
    if ($previous) {
        $row = mysql_fetch_assoc(voovi_schet_query("SELECT ns, rand, god, kolichschet FROM schet WHERE rand='".
            mysql_real_escape_string($previous['schet_rand'])."' ORDER BY `del`,id LIMIT 1"));
        voovi_schet_rollback();
        if (!$row) { throw new VooviSchetException('Эта операция уже обработана. Для нового счёта откройте новую форму.'); }
        $row['reused'] = true;
        return $row;
    }
    // Резервы номеров сохраняются до начала транзакции: после сбоя они не используются снова.
    $identity = voovi_allocate_schet_identity(date('y'), $numberManager === null ? $user['users_id'] : $numberManager,
        $numberDepartment === null ? $user['otdel'] : $numberDepartment, $clientId);
    voovi_schet_begin_transaction();
    voovi_schet_query("INSERT INTO schet_identity_keys(kind,value,schet_rand) VALUES('post','".$key."','".
        mysql_real_escape_string($identity['rand'])."')");
    $identity['reused'] = false;
    return $identity;
}

function voovi_begin_schet_edit($rand)
{
    voovi_schet_begin_transaction();
    voovi_schet_query("SELECT id FROM schet WHERE rand='".mysql_real_escape_string($rand)."' AND `del`='0' FOR UPDATE");
    return voovi_schet_stored_number($rand);
}

function voovi_schet_has_registered_goods($rand)
{
    // В старом журнале хранится rand, в новом — id позиции, включая прежние версии состава.
    $escapedRand = mysql_real_escape_string($rand);
    $rows = voovi_schet_query("SELECT id FROM jurnalskzi WHERE schet='".$escapedRand.
        "' OR schet_id IN (SELECT id FROM schet WHERE rand='".$escapedRand."') LIMIT 1");
    return mysql_num_rows($rows) > 0;
}

function voovi_schet_contract_type($year, $budget)
{
    // Одинаковое правило для старого просмотра и отказного клона без строки дат.
    $year = (int)$year;
    if ($year > 0 && $year < 100) { $year += 2000; }
    return $year >= 2024 ? 'oferta' : ((string)$budget === '1' ? 'contract' : 'dogovor');
}

function voovi_replace_schet_tariffs($rand, $tariffs, $changes)
{
    if (empty($GLOBALS['voovi_schet_transaction'])) { throw new VooviSchetException('Сохранение счёта не начато. Обновите страницу.'); }
    voovi_schet_stored_number($rand);
    if (!$tariffs) { throw new VooviSchetException('Выберите хотя бы один тариф счёта.'); }
    if (voovi_schet_has_registered_goods($rand)) { throw new VooviSchetException('К счёту привязаны товары. Сначала отвяжите их в рег. карте.'); }
    $query = voovi_schet_query("SELECT * FROM schet WHERE rand='".mysql_real_escape_string($rand)."' AND `del`='0' ORDER BY id FOR UPDATE");
    $rows = array(); $byTariff = array();
    while ($row = mysql_fetch_assoc($query)) {
        $rows[] = $row;
        if (!isset($byTariff[$row['prod']])) { $byTariff[$row['prod']] = $row; }
    }
    // Разрешаем менять только поля формы; автора, даты, оплаты, отгрузку и прочие сведения копируем полностью.
    $editable = array('shetold','kvogorod','goroddd','skidka','price','priceks','nomerschetks','tell','url','agent','produkt');
    if (array_diff(array_keys($changes), $editable)) { throw new VooviSchetException('Недопустимые изменения реквизитов счёта.'); }
    foreach ($tariffs as $tariff) {
        $copy = isset($byTariff[$tariff['id']]) ? $byTariff[$tariff['id']] : $rows[0];
        unset($copy['id']);
        foreach ($changes as $field=>$value) { $copy[$field] = $value; }
        $copy['prod'] = $tariff['id']; $copy['kvo'] = $tariff['quantity']; $copy['gen'] = $tariff['gen']; $copy['del'] = '0';
        $values = array();
        foreach ($copy as $value) { $values[] = $value === null ? 'NULL' : "'".mysql_real_escape_string((string)$value)."'"; }
        voovi_schet_query('INSERT INTO schet (`'.implode('`,`', array_keys($copy)).'`) VALUES ('.implode(',', $values).')');
    }
    // Новые строки не должны попасть под скрытие прежнего состава.
    voovi_schet_query("UPDATE schet SET `del`='1' WHERE id IN (".implode(',', array_map(function($row){return (int)$row['id'];}, $rows)).")");
}

function voovi_update_schet_agency($rand, $product, $data, $userId)
{
    // Пустой блок редактора означает «не изменять», а не удаление сохранённых АВ.
    $replacements = array();
    $modes = array('0', '1', '2', '3', '4');
    $mainMode = isset($data['av']) ? (string)$data['av'] : '-1';
    if (in_array($mainMode, $modes, true)) {
        $services = !empty($data['dobusl']) && is_array($data['dobusl']) ? $data['dobusl'] : array('0');
        foreach ($services as $service) {
            if (!is_scalar($service) || !preg_match('/^-?[0-9]+$/D', (string)$service)) {
                throw new VooviSchetException('Проверьте услуги для расчёта АВ.');
            }
            $replacements[''][] = array('mode'=>$mainMode, 'service'=>(string)$service,
                'certificates'=>isset($data['sht']) ? $data['sht'] : '0');
        }
    }
    $products = isset($data['dobprod']) && is_array($data['dobprod']) ? $data['dobprod'] : array();
    foreach ($products as $index=>$additionalProduct) {
        $mode = isset($data['avdob'][$index]) ? (string)$data['avdob'][$index] : '-1';
        if (!in_array($mode, $modes, true) || (string)$additionalProduct === '0' || (string)$additionalProduct === '') { continue; }
        if (!is_scalar($additionalProduct) || !preg_match('/^[1-9][0-9]*$/D', (string)$additionalProduct)) {
            throw new VooviSchetException('Проверьте дополнительный продукт для расчёта АВ.');
        }
        $replacements[(string)$additionalProduct][] = array('mode'=>$mode, 'service'=>'0',
            'certificates'=>isset($data['shtpr'][$index]) ? $data['shtpr'][$index] : '0');
    }
    if (!$replacements) { return; }
    if (empty($GLOBALS['voovi_schet_transaction'])) { throw new VooviSchetException('Сохранение счёта не начато.'); }
    $number = isset($data['nomerschetks']) ? trim((string)$data['nomerschetks']) : '';
    if ($number === '' || $number === '0') { throw new VooviSchetException('Укажите номер счёта Контура для расчёта АВ.'); }
    $invoiceWhere = "rand='".mysql_real_escape_string($rand)."' AND os_prod='".mysql_real_escape_string($product)."'";
    foreach ($replacements as $additionalProduct=>$rows) {
        // Основной продукт и каждый доппродукт меняются независимо; остальные строки не трогаем.
        $scope = $additionalProduct === '' ? "COALESCE(dob_prod,'') IN ('','0')" :
            "dob_prod='".mysql_real_escape_string((string)$additionalProduct)."'";
        voovi_schet_query('DELETE FROM av WHERE '.$invoiceWhere.' AND '.$scope);
        foreach ($rows as $row) {
            $certificates = $row['certificates'] === '' ? '0' : (string)$row['certificates'];
            if (!preg_match('/^[0-9]+$/D', $certificates)) { throw new VooviSchetException('Укажите целое количество сертификатов для расчёта АВ.'); }
            $mode = $row['mode'];
            $withoutAgency = $mode === '3' || $mode === '4';
            $pagent = $mode === '0' ? $number.'L' : ($withoutAgency ? 'Без АВ' : '');
            $sagent = $mode === '0' || $mode === '1' ? $number : ($withoutAgency ? 'Без АВ' : '');
            $dagent = (int)$certificates > 0 ? ($withoutAgency ? 'Без АВ' : $number) : '';
            $roles = ($pagent !== '' ? 1 : 0) + ($sagent !== '' ? 1 : 0) + ($dagent !== '' ? 1 : 0);
            $values = array('os_prod'=>$product, 'dob_prod'=>(string)$additionalProduct,
                'dobprodschet'=>$additionalProduct === '' ? '0' : $number.'dob', 'dob_usl'=>$row['service'],
                'pagent'=>$pagent, 'sagent'=>$sagent, 'dagent'=>$dagent, 'shtsert'=>$certificates, 'chisrol'=>$roles,
                'rand'=>$rand, 'schet'=>$number, 'summschet'=>isset($data['priceks']) ? $data['priceks'] : '0', 'kto'=>$userId);
            $escaped = array();
            foreach ($values as $value) { $escaped[] = "'".mysql_real_escape_string((string)$value)."'"; }
            voovi_schet_query('INSERT INTO av (`'.implode('`,`', array_keys($values)).'`) VALUES ('.implode(',', $escaped).')');
        }
    }
}

function voovi_schet_render_error($error)
{
    voovi_schet_rollback();
    error_log('Ошибка сохранения счёта: '.get_class($error).' '.$error->getFile().':'.$error->getLine());
    $message = $error instanceof VooviSchetException ? $error->getMessage() : 'Не удалось сохранить счёт. Повторите попытку или обратитесь к администратору.';
    echo '<div class="alert alert-danger" id="schetSaveError" role="alert">'.htmlspecialchars($message, ENT_QUOTES, 'UTF-8').'</div>';
    // Ответ уже мог быть отправлен частично: убираем ранее показанный экран ожидания.
    echo '<script>(function(){var node=document.getElementById("schetLoadingOverlay");if(node)node.classList.remove("is-visible");}());</script>';
    $values = array();
    foreach ($_POST as $name=>$value) {
        if (!in_array($name, array('creation_token','shetrand','submitsch','submitsave'), true) && (is_string($value) || is_array($value))) { $values[$name] = $value; }
    }
    $encoded = json_encode($values, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    if ($encoded === false) { $encoded = '{}'; }
    echo '<script>
    document.addEventListener("DOMContentLoaded", function () {
        var form = document.getElementById("schetForm"), values = '.$encoded.';
        if (!form) return;
        form.removeAttribute("data-loading");
        Array.prototype.forEach.call(form.elements, function (input) {
            var key = input.name.replace(/\[\]$/, ""), value = values[key];
            if (input.type === "checkbox" || input.type === "radio") {
                input.checked = Array.isArray(value) ? value.indexOf(input.value) !== -1 : String(value) === input.value;
            } else if (Array.isArray(value) && input.options) {
                Array.prototype.forEach.call(input.options, function (option) { option.selected = value.indexOf(option.value) !== -1; });
            } else if (typeof value === "string") input.value = value;
        });
        Array.prototype.forEach.call(form.querySelectorAll(".newschet-qty-input"), function (input) {
            input.dispatchEvent(new Event("keyup", {bubbles:true}));
        });
    });
    </script>';

}

function voovi_copy_schet_related_data($sourceRand, $targetRand, $source)
{
    // Копируем связь, а не сам документ Контура. Незавершённая привязка остаётся ожидающей.
    $sourceSql = mysql_real_escape_string($sourceRand);
    $targetSql = mysql_real_escape_string($targetRand);
    $link = mysql_fetch_assoc(voovi_schet_query("SELECT BillId,legacy_number_before,legacy_amount_before,legacy_published_at ".
        "FROM bills_voovi_links WHERE schet_rand='".$sourceSql."' FOR UPDATE"));
    if ($link) {
        $fields = array('schet_rand'); $values = array("'".$targetSql."'");
        foreach ($link as $field=>$value) {
            $fields[] = $field;
            $values[] = $value === null ? 'NULL' : "'".mysql_real_escape_string($value)."'";
        }
        voovi_schet_query('INSERT INTO bills_voovi_links (`'.implode('`,`', $fields).'`) VALUES ('.implode(',', $values).')');
    }
    // Даты наследуются, но новый документ Voovi ещё не отправлялся в ЭДО.
    $dates = mysql_fetch_assoc(voovi_schet_query("SELECT * FROM document_data WHERE rand='".$sourceSql."' ORDER BY id LIMIT 1 FOR UPDATE"));
    $year = (int)$source['y'];
    if ($year > 0 && $year < 100) { $year += 2000; }
    $fallback = checkdate((int)$source['m'], (int)$source['d'], $year)
        ? sprintf('%04d-%02d-%02d', $year, $source['m'], $source['d']) : date('Y-m-d');
    $company = $dates ? null : mysql_fetch_assoc(voovi_schet_query("SELECT budjet_ogrn FROM ogrn WHERE id=".(int)$source['idkli']." LIMIT 1"));
    $contractType = voovi_schet_contract_type($year, $company ? $company['budjet_ogrn'] : '0');
    $fields = array('rand'); $values = array("'".$targetSql."'");
    foreach (array('d_bill','d_contract','d_specification','d_act','d_tn','d_invoice_advance','d_upd','type_contract') as $field) {
        $value = $dates ? $dates[$field] : ($field === 'type_contract' ? $contractType : $fallback);
        $fields[] = $field;
        $values[] = $value === null ? 'NULL' : "'".mysql_real_escape_string($value)."'";
    }
    voovi_schet_query('INSERT INTO document_data (`'.implode('`,`', $fields).'`) VALUES ('.implode(',', $values).')');
}

function voovi_schet_previous_rand($number)
{
    if ($number === '' || $number === '0') { return null; }
    $rows = voovi_schet_query("SELECT DISTINCT rand FROM schet WHERE ns='".mysql_real_escape_string($number)."' AND `del`='0'");
    if (mysql_num_rows($rows) !== 1) { throw new VooviSchetException('Предыдущий счёт не найден или его номер неоднозначен.'); }
    $row = mysql_fetch_assoc($rows);
    voovi_schet_stored_number($row['rand']);
    return $row['rand'];
}


function voovi_validate_schet_tariffs($product, $rand, $data)
{
    $selected = array();
    $rows = voovi_schet_query(voovi_tarif_schet_query($product, $rand));
    while ($row = mysql_fetch_assoc($rows)) {
        if (!isset($data[$row['id']]) || (string)$data[$row['id']] !== '1') { continue; }
        $quantity = isset($data['input'.$row['id']]) ? str_replace(',', '.', $data['input'.$row['id']]) : '';
        if (!is_numeric($quantity) || !is_finite((float)$quantity) || (float)$quantity <= 0) {
            throw new VooviSchetException('Укажите положительное количество для каждого выбранного тарифа.');
        }
        $selected[] = $row;
    }
    if (!$selected) { throw new VooviSchetException('Выберите хотя бы один тариф счёта.'); }
    foreach (array('skidka','aallsumm','priceks') as $field) {
        $value = isset($data[$field]) ? str_replace(',', '.', $data[$field]) : '';
        if ($value === '') { continue; }
        if (!is_numeric($value) || !is_finite((float)$value) || (float)$value < 0 ||
            ($field === 'skidka' && (float)$value > 100)) {
            throw new VooviSchetException('Проверьте сумму и скидку счёта.');
        }
    }
    return $selected;
}


function voovi_schet_authenticated_user()
{
    if (!isset($_COOKIE['id'], $_COOKIE['hash']) || !is_string($_COOKIE['id']) || !is_string($_COOKIE['hash'])) {
        http_response_code(403); exit('Войдите в Voovi');
    }
    $user = mysql_fetch_assoc(voovi_schet_query("SELECT * FROM users WHERE users_id=".(int)$_COOKIE['id']." LIMIT 1"));
    if (!$user || (string)$user['users_id'] !== $_COOKIE['id'] || !hash_equals((string)$user['users_hash'], $_COOKIE['hash']) ||
        (string)$user['del_users'] !== '0' || (string)$user['uvl'] === '1' || (string)$user['tip'] === '88') {
        http_response_code(403); exit('Нет доступа');
    }
    return $user;
}
