<?php
// Интеграционный тест работает только в отдельной БД и очищает только её тестовые таблицы.
// Пример: DB_NAME=voovi_schet_identity_test DB_HOST=db DB_LOGIN=root DB_PASSWORD=... php tests/schet-identity.test.php

function identity_test_require($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function identity_test_connect()
{
    $database = getenv('DB_NAME');
    identity_test_require(is_string($database) && preg_match('/^voovi_schet_identity_test(?:_[a-z0-9_]+)?$/D', $database),
        'Отказ: DB_NAME должен указывать на отдельную voovi_schet_identity_test');
    foreach (array('DB_HOST', 'DB_LOGIN', 'DB_PASSWORD') as $key) {
        identity_test_require(getenv($key) !== false && getenv($key) !== '', 'Не задан '.$key);
    }
    identity_test_require(@mysql_connect(getenv('DB_HOST'), getenv('DB_LOGIN'), getenv('DB_PASSWORD')) !== false,
        'Не удалось подключиться к тестовому серверу MySQL');
    identity_test_require(mysql_select_db($database), 'Тестовая база не создана');
    identity_test_query('SET NAMES utf8mb4');
    identity_test_query("SET SESSION sql_mode='STRICT_ALL_TABLES'");
    date_default_timezone_set(getenv('PHP_DATE_TIMEZONE') ?: 'Europe/Moscow');
}

function identity_test_query($sql)
{
    $result = mysql_query($sql);
    if ($result === false) {
        throw new RuntimeException('Ошибка тестового SQL: '.mysql_error());
    }
    return $result;
}

function identity_test_scalar($sql)
{
    $row = mysql_fetch_row(identity_test_query($sql));
    return $row[0];
}

function identity_test_reset()
{
    voovi_schet_rollback();
    foreach (array('produkti','tarif','tarif_parent') as $table) {
        identity_test_query('DROP TABLE IF EXISTS '.$table);
    }
    foreach (array('av','aktivn','dogovor') as $table) {
        identity_test_query('DROP TABLE IF EXISTS '.$table);
        identity_test_query('CREATE TABLE '.$table.' (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY) ENGINE=InnoDB');
    }
    identity_test_query('DROP TABLE IF EXISTS document_data');
    identity_test_query("CREATE TABLE document_data (id INT AUTO_INCREMENT PRIMARY KEY,rand VARCHAR(366) NOT NULL,
        d_bill DATE NOT NULL,d_contract DATE NOT NULL,d_specification DATE NOT NULL,d_act DATE NOT NULL,d_tn DATE NOT NULL,
        d_invoice_advance DATE NOT NULL,d_upd DATE NOT NULL,type_contract VARCHAR(64) NOT NULL DEFAULT 'oferta',
        bill_message_id VARCHAR(256) NULL,contract_message_id VARCHAR(256) NULL,
        specification_message_id VARCHAR(256) NULL,act_message_id VARCHAR(256) NULL,tn_message_id VARCHAR(256) NULL,
        invoice_advance_message_id VARCHAR(256) NULL,upd_message_id VARCHAR(256) NULL) ENGINE=InnoDB");
    identity_test_query('DROP TABLE IF EXISTS bills_voovi_links');
    // Полные ключи production используют ASCII и укладываются в лимит InnoDB MariaDB 5.5.
    identity_test_query("CREATE TABLE bills_voovi_links (id BIGINT AUTO_INCREMENT PRIMARY KEY,schet_rand VARCHAR(366) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL UNIQUE,
        BillId VARCHAR(128) NULL,legacy_number_before VARCHAR(366) NULL,legacy_amount_before VARCHAR(366) NULL,
        legacy_published_at DATETIME(6) NULL) ENGINE=InnoDB");
    identity_test_query('DROP TABLE IF EXISTS bills');
    identity_test_query('CREATE TABLE bills (BillId VARCHAR(128) PRIMARY KEY) ENGINE=InnoDB');
    identity_test_query('DROP TABLE IF EXISTS jurnalskzi');
    identity_test_query('CREATE TABLE jurnalskzi (id INT AUTO_INCREMENT PRIMARY KEY,schet VARCHAR(366),schet_id BIGINT NULL) ENGINE=InnoDB');
    identity_test_query('DROP TABLE IF EXISTS ogrn');
    identity_test_query('CREATE TABLE ogrn (id INT PRIMARY KEY,budjet_ogrn CHAR(1)) ENGINE=InnoDB');
    identity_test_query("INSERT INTO ogrn VALUES(1,'0')");
    identity_test_query('DROP TABLE IF EXISTS schet_identity_keys');
    identity_test_query('DROP TABLE IF EXISTS schetnum');
    identity_test_query('DROP TABLE IF EXISTS schet');
    identity_test_query("CREATE TABLE schetnum (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        num BIGINT UNSIGNED NOT NULL, god CHAR(2) NOT NULL,
        UNIQUE KEY uq_schetnum_god (god)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    identity_test_query("CREATE TABLE schet_identity_keys (
        kind VARCHAR(4) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
        value VARCHAR(366) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
        schet_rand VARCHAR(366) NULL, schet_ns VARCHAR(366) NULL, schet_client VARCHAR(366) NULL,
        PRIMARY KEY (kind,value)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    identity_test_query("CREATE TABLE schet (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        ns VARCHAR(366) NOT NULL, rand VARCHAR(366) NOT NULL,
        kolichschet BIGINT UNSIGNED NOT NULL, god CHAR(2) NOT NULL,
        idkli VARCHAR(366) NOT NULL DEFAULT '1',
        `del` CHAR(1) NOT NULL DEFAULT '0'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function identity_test_rand($sequence)
{
    return sprintf('%05d', $sequence).date('Ymd');
}

function identity_test_reserved($kind, $value)
{
    return (int)identity_test_scalar("SELECT COUNT(*) FROM schet_identity_keys WHERE kind='".
        mysql_real_escape_string($kind)."' AND value='".mysql_real_escape_string($value)."'") === 1;
}

function identity_test_check_identity($identity, $year, $manager, $department)
{
    identity_test_require(isset($identity['kolichschet'], $identity['rand'], $identity['ns']), 'Не возвращён полный идентификатор');
    $sequence = (int)$identity['kolichschet'];
    identity_test_require($sequence > 0, 'Номер должен быть положительным');
    identity_test_require($identity['ns'] === substr((string)$year, -2).$manager.$department.$sequence,
        'Неверное формирование ns');
    identity_test_require($identity['rand'] === identity_test_rand($sequence), 'Неверный формат rand');
    identity_test_require(identity_test_reserved('ns', $identity['ns']), 'ns не зарезервирован до записи schet');
    identity_test_require(identity_test_reserved('rand', $identity['rand']), 'rand не зарезервирован до записи schet');
}

function identity_test_insert_group_row($id, $identity)
{
    identity_test_query("INSERT INTO schet (id,ns,rand,kolichschet,god) VALUES (".(int)$id.", '".
        mysql_real_escape_string($identity['ns'])."', '".mysql_real_escape_string($identity['rand'])."', ".
        (int)$identity['kolichschet'].", '26')");
}

function identity_test_worker($count, $manager)
{
    $result = array();
    for ($index = 0; $index < $count; $index++) {
        $identity = voovi_allocate_schet_identity('26', $manager, 'A');
        identity_test_check_identity($identity, '26', $manager, 'A');
        $result[] = $identity;
    }
    echo json_encode($result);
}

function identity_test_parallel($workerCount, $perWorker, $creationToken = null)
{
    $workers = array();
    $descriptors = array(0=>array('pipe','r'), 1=>array('pipe','w'), 2=>array('pipe','w'));
    for ($index = 0; $index < $workerCount; $index++) {
        // PHP 5.6 принимает строку команды: каждый аргумент экранируется отдельно.
        $arguments = $creationToken === null ? ' --worker '.(int)$perWorker.' '.escapeshellarg((string)(10 + $index)) :
            ' --create-worker '.escapeshellarg($creationToken);
        $command = escapeshellarg(PHP_BINARY).' '.escapeshellarg(__FILE__).$arguments;
        $process = proc_open($command, $descriptors, $pipes, __DIR__);
        identity_test_require(is_resource($process), 'Не удалось запустить параллельный процесс');
        fclose($pipes[0]);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);
        $workers[] = array('process'=>$process, 'pipes'=>$pipes, 'output'=>'', 'error'=>'', 'exit'=>null);
    }
    $deadline = microtime(true) + 30;
    do {
        $running = false;
        foreach ($workers as &$worker) {
            $worker['output'] .= stream_get_contents($worker['pipes'][1]);
            $worker['error'] .= stream_get_contents($worker['pipes'][2]);
            if ($worker['exit'] !== null) {
                continue;
            }
            $status = proc_get_status($worker['process']);
            if ($status['running']) {
                $running = true;
            } else {
                $worker['exit'] = $status['exitcode'];
            }
        }
        unset($worker);
        if ($running && microtime(true) > $deadline) {
            foreach ($workers as $worker) {
                proc_terminate($worker['process'], 9);
            }
            throw new RuntimeException('Тайм-аут параллельного выделения номеров');
        }
        if ($running) {
            usleep(10000);
        }
    } while ($running);
    $identities = array();
    foreach ($workers as $worker) {
        $worker['output'] .= stream_get_contents($worker['pipes'][1]);
        $worker['error'] .= stream_get_contents($worker['pipes'][2]);
        fclose($worker['pipes'][1]);
        fclose($worker['pipes'][2]);
        proc_close($worker['process']);
        identity_test_require($worker['exit'] === 0, 'Параллельный процесс завершился ошибкой: '.$worker['error']);
        $items = json_decode($worker['output'], true);
        identity_test_require(is_array($items) && count($items) === $perWorker, 'Неполный результат параллельного процесса');
        $identities = array_merge($identities, $items);
    }
    return $identities;
}

$identityTestFinished = false;
register_shutdown_function(function () use (&$identityTestFinished) {
    // die()/фатальная ошибка внутри проверяемого кода не должны выглядеть успешным тестом.
    if (!$identityTestFinished) { exit(1); }
});

try {
    identity_test_connect();
    require dirname(__DIR__).'/schet_identity.php';
    require dirname(__DIR__).'/invoice_money.php';
    if (isset($argv[1]) && in_array($argv[1], array('--edit-worker','--other-worker'), true)) {
        $payload = json_decode(base64_decode($argv[2]), true);
        $_POST = $payload['post']; $_GET = $payload['get'];
        $userdata = array('users_id'=>'3','users_hash'=>'fixture-user-hash','otdel'=>'A','filial'=>'1');
        $page = $argv[1] === '--other-worker' ? 'otherogrn.php' : 'myogrn.php';
        $newschetProductId = 1; $newschetClientId = 1; $schetFormScope = $page.':1:1';
        if (isset($_GET['rand'])) { $pestar = mysql_fetch_assoc(identity_test_query("SELECT * FROM schet WHERE rand='".mysql_real_escape_string($_GET['rand'])."' AND `del`='0' ORDER BY id LIMIT 1")); }
        require dirname(__DIR__).'/tarif_identity.php';
        $script = file_get_contents(dirname(__DIR__).'/'.$page);
        // Создание проверяем с sql_mode='' из conf.php; прежние проверки редактирования остаются строгими.
        if (isset($_POST['submitsch'])) { identity_test_query("SET SESSION sql_mode=''"); }
        $nds = "'20'"; $zs = 1;
        $script = str_replace("'log/voovi.log'", "'/dev/null'", $script);
        $start = strpos($script, "try {\nif(isset(\$_POST['submitsch'])){");
        $end = strpos($script, $page === 'myogrn.php' ? "\n\$depo = " : "\n?>\n<form", $start);
        identity_test_require($start !== false && $end !== false, 'Не найден реальный блок сохранения формы');
        $identityTestFinished = true;
        eval(substr($script, $start, $end-$start));
        exit(0);
    }
    if (isset($argv[1]) && $argv[1] === '--refusal-worker') {
        $_POST = json_decode(base64_decode($argv[2]), true);
        $_COOKIE = array('id'=>'3', 'hash'=>'fixture-user-hash');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $script = file_get_contents(dirname(__DIR__).'/otkazschet.php');
        // Исполняем реальный обработчик, сохранив выбранное тестовое подключение вместо conf.php.
        $script = str_replace(array("require 'conf.php';", "require_once 'schet_identity.php';"), '', $script);
        $identityTestFinished = true;
        eval('?>'.$script);
        exit(0);
    }
    if (isset($argv[1]) && $argv[1] === '--create-worker') {
        $user = array('users_id'=>'3', 'users_hash'=>'fixture-user-hash', 'otdel'=>'A');
        $identity = voovi_begin_schet_creation($argv[2], 'fixture:1:12', $user);
        if (!$identity['reused']) {
            usleep(100000);
            identity_test_query("INSERT INTO schet(ns,rand,kolichschet,god) VALUES('".
                mysql_real_escape_string($identity['ns'])."','".mysql_real_escape_string($identity['rand'])."',".
                (int)$identity['kolichschet'].",'".$identity['god']."')");
            voovi_schet_commit($identity['rand']);
        }
        echo json_encode(array($identity));
        $identityTestFinished = true;
        exit(0);
    }
    if (isset($argv[1]) && $argv[1] === '--worker') {
        identity_test_worker((int)$argv[2], $argv[3]);
        $identityTestFinished = true;
        exit(0);
    }

    identity_test_reset();
    $first = voovi_allocate_schet_identity('2026', '3', 'A');
    $second = voovi_allocate_schet_identity('26', '3', 'A');
    identity_test_check_identity($first, '26', '3', 'A');
    identity_test_check_identity($second, '26', '3', 'A');
    identity_test_require($first['ns'] === '263A1' && $second['ns'] === '263A2', 'Двух- и четырёхзначный годы используют разные счётчики');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schetnum') === 1, 'Создан второй счётчик года');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet') === 0, 'Аллокатор сам создал строку счёта');

    identity_test_reset();
    $oldRand = identity_test_rand(1);
    identity_test_query("INSERT INTO schet_identity_keys (kind,value) VALUES ('ns','263A1'),('rand','".$oldRand."')");
    identity_test_query("INSERT INTO schet (ns,rand,kolichschet,god,`del`) VALUES ('263A1','".$oldRand."',1,'26','1')");
    $afterDeleted = voovi_allocate_schet_identity('26', '3', 'A');
    identity_test_require((int)$afterDeleted['kolichschet'] === 2, 'Повторно выдан номер удалённого счёта');
    identity_test_query('DELETE FROM schet');
    identity_test_query('DELETE FROM schetnum');
    $afterPurged = voovi_allocate_schet_identity('26', '3', 'A');
    identity_test_require((int)$afterPurged['kolichschet'] === 3, 'Удаление истории позволило повторно выдать резерв');
    identity_test_require(identity_test_reserved('ns', '263A1') && identity_test_reserved('rand', $oldRand), 'Утеряны старые резервы');

    identity_test_reset();
    identity_test_query("INSERT INTO schet_identity_keys (kind,value) VALUES ('rand','".identity_test_rand(1)."')");
    $afterRandCollision = voovi_allocate_schet_identity('26', '3', 'A');
    identity_test_require((int)$afterRandCollision['kolichschet'] === 2, 'Коллизия rand не вызвала новый номер');
    identity_test_require(!identity_test_reserved('ns', '263A1'), 'Неудачное резервирование оставило половину пары');
    identity_test_check_identity($afterRandCollision, '26', '3', 'A');

    identity_test_reset();
    $ambiguous = voovi_allocate_schet_identity('26', '12', '1');
    identity_test_require($ambiguous['ns'] === '261211', 'Неверная подготовка коллизии префикса');
    identity_test_query("UPDATE schetnum SET num=10 WHERE god='26'");
    $afterPrefixCollision = voovi_allocate_schet_identity('26', '1', '2');
    identity_test_require((int)$afterPrefixCollision['kolichschet'] === 12, 'Разные префиксы позволили выдать одинаковый ns');
    identity_test_require(!identity_test_reserved('rand', identity_test_rand(11)), 'Коллизия ns оставила резерв rand');
    identity_test_check_identity($afterPrefixCollision, '26', '1', '2');

    identity_test_reset();
    $previousYear = voovi_allocate_schet_identity('25', '3', 'A');
    $currentYear = voovi_allocate_schet_identity('26', '3', 'A');
    identity_test_require((int)$previousYear['kolichschet'] === 1 && (int)$currentYear['kolichschet'] === 2,
        'Повторный rand с тем же порядковым номером другого года не заблокирован');

    identity_test_reset();
    $failedGroup = voovi_allocate_schet_identity('26', '3', 'A');
    identity_test_query('START TRANSACTION');
    identity_test_insert_group_row(100, $failedGroup);
    $duplicate = @mysql_query("INSERT INTO schet (id,ns,rand,kolichschet,god) VALUES (100,'failed','failed',1,'26')");
    identity_test_require($duplicate === false && mysql_errno() === 1062, 'Не удалось воспроизвести ошибку записи группы');
    identity_test_query('ROLLBACK');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet') === 0, 'Не откатилась тестовая группа');
    identity_test_check_identity($failedGroup, '26', '3', 'A');
    $afterFailure = voovi_allocate_schet_identity('26', '3', 'A');
    identity_test_require((int)$afterFailure['kolichschet'] === 2, 'Неудачная запись группы вернула номер в оборот');

    identity_test_reset();
    foreach (array(array('', '3', 'A'), array('26', '', 'A'), array('26', '3', '')) as $input) {
        $rejected = false;
        try {
            call_user_func_array('voovi_allocate_schet_identity', $input);
        } catch (Exception $error) {
            $rejected = true;
        }
        identity_test_require($rejected, 'Пустые обязательные данные не отклонены');
    }
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schetnum') === 0, 'Пустой ввод израсходовал номер');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet_identity_keys') === 0, 'Пустой ввод создал резерв');

    identity_test_reset();
    $parallel = identity_test_parallel(4, 12);
    $numbers = array();
    $rands = array();
    $sequences = array();
    foreach ($parallel as $identity) {
        $numbers[] = $identity['ns'];
        $rands[] = $identity['rand'];
        $sequences[] = (int)$identity['kolichschet'];
    }
    identity_test_require(count(array_unique($numbers)) === 48 && count(array_unique($rands)) === 48,
        'Параллельные процессы выдали повторный ns или rand');
    sort($sequences);
    identity_test_require($sequences === range(1,48), 'Потерян или повторён порядковый номер параллельных процессов');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet_identity_keys') === 96,
        'Не все параллельные номера зарезервированы');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet') === 0,
        'Параллельный резерв потребовал преждевременную запись счёта');

    // Редактирование читает сохранённый номер и не объединяет спорные старые группы.
    identity_test_insert_group_row(100, $parallel[0]);
    identity_test_insert_group_row(101, $parallel[0]);
    identity_test_require(voovi_schet_stored_number($parallel[0]['rand']) === $parallel[0]['ns'],
        'Номер группы не читается из сохранённого поля');
    foreach (array("ns='different'", "idkli='2'") as $change) {
        identity_test_query("UPDATE schet SET ".$change." WHERE id=101");
        $rejected = false;
        try { voovi_schet_stored_number($parallel[0]['rand']); }
        catch (Exception $error) { $rejected = true; }
        identity_test_require($rejected, 'Неоднозначная группа разрешена для сохранения');
        identity_test_query("UPDATE schet SET ns='".mysql_real_escape_string($parallel[0]['ns'])."',idkli='1' WHERE id=101");
    }
    // Повтор формы не создаёт новый документ; откат не оставляет успешную отметку операции.
    identity_test_reset();
    $user = array('users_id'=>'3', 'users_hash'=>'fixture-user-hash', 'otdel'=>'A');
    $scope = 'fixture:1:12';
    $token = voovi_schet_form_token($scope, $user);
    $created = voovi_begin_schet_creation($token, $scope, $user);
    identity_test_require(!$created['reused'], 'Первый запрос принят за повтор');
    identity_test_insert_group_row(100, $created);
    voovi_schet_commit($created['rand']);
    $counter = identity_test_scalar('SELECT num FROM schetnum');
    $repeated = voovi_begin_schet_creation($token, $scope, $user);
    identity_test_require($repeated['reused'] && $repeated['rand'] === $created['rand'], 'Повтор формы получил новый счёт');
    identity_test_require(identity_test_scalar('SELECT num FROM schetnum') === $counter, 'Повтор формы израсходовал номер');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet_identity_keys WHERE kind='post'") === 1,
        'Повтор формы создал новую операцию');

    $failedToken = voovi_schet_form_token($scope, $user);
    $failed = voovi_begin_schet_creation($failedToken, $scope, $user);
    identity_test_insert_group_row(101, $failed);
    voovi_schet_rollback();
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet WHERE id=101') === 0, 'Не откатился состав запроса');
    identity_test_check_identity($failed, date('y'), '3', 'A');
    $retry = voovi_begin_schet_creation($failedToken, $scope, $user);
    identity_test_require(!$retry['reused'] && $retry['rand'] !== $failed['rand'], 'Откат сохранил ложную отметку успеха');
    identity_test_insert_group_row(101, $retry);
    voovi_schet_commit($retry['rand']);

    $parallelToken = voovi_schet_form_token($scope, $user);
    $sameRequest = identity_test_parallel(4, 1, $parallelToken);
    $sameRands = array();
    foreach ($sameRequest as $result) { $sameRands[] = $result['rand']; }
    identity_test_require(count(array_unique($sameRands)) === 1, 'Параллельный повтор формы создал разные документы');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".
        mysql_real_escape_string($sameRands[0])."'") === 1, 'Параллельный повтор формы записал дубли состава');

    // Незавершённая замена состава откатывает скрытие старых строк и новые позиции.
    $oldNumber = voovi_begin_schet_edit($created['rand']);
    identity_test_query("UPDATE schet SET `del`='1' WHERE rand='".mysql_real_escape_string($created['rand'])."'");
    identity_test_insert_group_row(9102, $created);
    voovi_schet_rollback();
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet WHERE id=9102') === 0, 'Осталась часть нового состава');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE id=100 AND `del`='0'") === 1,
        'Откат не восстановил прежний состав');

    // Современные, старые и исторические связи товаров защищают состав одного документа.
    foreach (array("'',100", "'".mysql_real_escape_string($created['rand'])."',NULL",
                   "'".mysql_real_escape_string($created['rand'])."',0") as $values) {
        identity_test_query('INSERT INTO jurnalskzi(schet,schet_id) VALUES ('.$values.')');
        identity_test_require(voovi_schet_has_registered_goods($created['rand']), 'Пропущена привязка товара к счёту');
        identity_test_query('DELETE FROM jurnalskzi');
    }
    identity_test_insert_group_row(9103, $created);
    identity_test_query("UPDATE schet SET `del`='1' WHERE id=9103");
    identity_test_query("INSERT INTO jurnalskzi(schet,schet_id) VALUES ('',9103)");
    identity_test_require(voovi_schet_has_registered_goods($created['rand']), 'Пропущен товар на прежней позиции счёта');
    identity_test_query('DELETE FROM jurnalskzi');
    identity_test_query("INSERT INTO jurnalskzi(schet,schet_id) VALUES ('another-group',NULL)");
    identity_test_require(!voovi_schet_has_registered_goods($created['rand']), 'Чужой товар блокирует изменение счёта');

    foreach (array(array($token, 'other-scope', $user), array($token, $scope, array_merge($user,array('users_id'=>'99'))),
                   array('invalid', $scope, $user)) as $input) {
        $rejected = false;
        try { call_user_func_array('voovi_begin_schet_creation', $input); }
        catch (Exception $error) { $rejected = true; }
        identity_test_require($rejected, 'Токен чужой формы разрешён');
    }
    identity_test_query('ALTER TABLE av ENGINE=MyISAM');
    $rejected = false;
    try { voovi_begin_schet_edit($created['rand']); }
    catch (Exception $error) { $rejected = true; }
    identity_test_require($rejected, 'Разрешена замена состава без транзакционного хранилища');
    identity_test_query('ALTER TABLE av ENGINE=InnoDB');

    // Реальный обработчик отказа: повторный POST и сбой второй строки клона.
    identity_test_reset();
    $extraFields = array('akt','kto','otdel','sortir','filial','nomerdog','nomerschet','nomerschetks','kvo','prod','summa','goroddd','kvogorod','nds',
        'prodlen','skidka','priceks','price','ogrn','name','inn','kpp','produkt','data','d','m','y','time',
        'lico','vladelec','oferta','url','shetold','oplachenks','cher','prichotk','ktootkaz','dateotkaz','koment','prodlens','prodlenks');
    foreach ($extraFields as $field) { identity_test_query("ALTER TABLE schet ADD COLUMN `".$field."` VARCHAR(366) NOT NULL DEFAULT ''"); }
    identity_test_query('DROP TABLE IF EXISTS users');
    identity_test_query("CREATE TABLE users(users_id INT PRIMARY KEY,users_hash VARCHAR(64),del_users VARCHAR(1),uvl VARCHAR(1),tip VARCHAR(3),otdel VARCHAR(10)) ENGINE=InnoDB");
    identity_test_query("INSERT INTO users VALUES(3,'fixture-user-hash','0','0','1','A')");
    $source = voovi_allocate_schet_identity('26', '3', 'A', '1');
    identity_test_insert_group_row(100, $source); identity_test_insert_group_row(101, $source);
    identity_test_query("UPDATE schet SET akt='1',kto='10',otdel='A',y='2026',m='10',d='25',
        nomerschetks='s-fixture',priceks='1500',url='https://example.test/bill',prod=IF(id=100,'good','fail'),
        nds='vat22',goroddd='230',kvogorod='2'");
    $savedCost = mysql_real_escape_string('{"v":1,"p":"historical","q":"1","d":"0","a":"100.00","t":[]}');
    identity_test_query("UPDATE schet SET summa='".$savedCost."'");
    identity_test_query("INSERT INTO bills VALUES ('shared-bill')");
    identity_test_query("INSERT INTO bills_voovi_links(schet_rand,BillId,legacy_number_before,legacy_amount_before,legacy_published_at)
        VALUES ('".mysql_real_escape_string($source['rand'])."','shared-bill','s-fixture','1500','2026-10-25 12:00:00')");
    identity_test_query("INSERT INTO document_data(rand,d_bill,d_contract,d_specification,d_act,d_tn,d_invoice_advance,d_upd,type_contract,bill_message_id)
        VALUES ('".mysql_real_escape_string($source['rand'])."','2026-10-25','2026-10-24','2026-10-23','2026-10-22',
        '2026-10-21','2026-10-20','2026-10-19','contract','source-edo-message')");
    $actionUser = array('users_id'=>'3','users_hash'=>'fixture-user-hash','otdel'=>'A');
    $actionScope = 'otkazschet.php:'.$source['rand'];
    $action = array('rand'=>$source['rand'], 'tipotkaz'=>'1','tipschet'=>'prod',
        'creation_token'=>voovi_schet_form_token($actionScope,$actionUser));
    $runRefusal = function($payload) {
        $command = escapeshellarg(PHP_BINARY).' -d short_open_tag=1 '.escapeshellarg(__FILE__).
            ' --refusal-worker '.escapeshellarg(base64_encode(json_encode($payload)));
        $output = shell_exec($command);
        $result = json_decode($output,true);
        identity_test_require(is_array($result),'Не получен ответ тестового обработчика отказа');
        return $result;
    };
    $clone = $runRefusal($action);
    identity_test_require(isset($clone['rand']) && !$clone['reused'], 'Отказ не создал клон');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".
        mysql_real_escape_string($clone['rand'])."'") === 2, 'Не сохранены обе строки клона');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".
        mysql_real_escape_string($clone['rand'])."' AND kto='10' AND ktootkaz='3'") === 2, 'Изменился автор исходного счёта либо потерян сотрудник отказа');
    $cloneSql = mysql_real_escape_string($clone['rand']);
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".$cloneSql.
        "' AND nds='vat22' AND goroddd='230' AND kvogorod='2'") === 2,
        'Отказной клон потерял НДС или количество выездов');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".$cloneSql.
        "' AND summa='".$savedCost."'") === 2, 'Отказной клон потерял сохранённую стоимость строк');
    identity_test_require(identity_test_scalar("SELECT BillId FROM bills_voovi_links WHERE schet_rand='".$cloneSql."'") === 'shared-bill',
        'Клон не получил связь с общим документом Контура');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM bills') === 1, 'Документ Контура продублирован');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM document_data WHERE rand='".$cloneSql.
        "' AND d_bill='2026-10-25' AND d_contract='2026-10-24' AND d_specification='2026-10-23' AND d_act='2026-10-22'".
        " AND d_tn='2026-10-21' AND d_invoice_advance='2026-10-20' AND d_upd='2026-10-19' AND type_contract='contract' AND bill_message_id IS NULL") === 1,
        'Даты клона потеряны или скопирована отправка исходного документа в ЭДО');
    $cloneAgain = $runRefusal($action);
    identity_test_require($cloneAgain['reused'] && $cloneAgain['rand'] === $clone['rand'], 'Повтор отказа создал второй клон');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet') === 4, 'Повтор отказа добавил строки');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM bills_voovi_links') === 2 &&
        (int)identity_test_scalar('SELECT COUNT(*) FROM document_data') === 2, 'Повтор отказа продублировал связанные данные');
    identity_test_query("UPDATE schet SET prodlens='0',prodlenks='0' WHERE rand='".mysql_real_escape_string($source['rand'])."'");
    identity_test_query("CREATE TRIGGER fixture_fail_clone BEFORE INSERT ON schet FOR EACH ROW BEGIN IF NEW.rand<>'".
        mysql_real_escape_string($source['rand'])."' AND NEW.prod='fail' THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='fixture clone failure'; END IF; END");
    $action['creation_token'] = voovi_schet_form_token($actionScope,$actionUser);
    $failure = $runRefusal($action);
    identity_test_require(isset($failure['error']), 'Сбой второй строки клона не обнаружен');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet') === 4, 'После сбоя осталась часть клона');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".
        mysql_real_escape_string($source['rand'])."' AND (prodlens<>'0' OR prodlenks<>'0')") === 0, 'Не откатились изменения источника');
    identity_test_query('DROP TRIGGER fixture_fail_clone');

    // Ошибка связанного документа откатывает уже вставленные позиции и привязку клона.
    identity_test_query("CREATE TRIGGER fixture_fail_document BEFORE INSERT ON document_data FOR EACH ROW BEGIN IF NEW.rand<>'".
        mysql_real_escape_string($source['rand'])."' THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='fixture document failure'; END IF; END");
    $action['creation_token'] = voovi_schet_form_token($actionScope,$actionUser);
    $failure = $runRefusal($action);
    identity_test_require(isset($failure['error']), 'Сбой записи связанных данных не обнаружен');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM schet') === 4 &&
        (int)identity_test_scalar('SELECT COUNT(*) FROM bills_voovi_links') === 2 &&
        (int)identity_test_scalar('SELECT COUNT(*) FROM document_data') === 2, 'После сбоя осталась часть связанных данных клона');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".
        mysql_real_escape_string($source['rand'])."' AND (prodlens<>'0' OR prodlenks<>'0')") === 0, 'Связанный сбой не откатил источник');
    identity_test_query('DROP TRIGGER fixture_fail_document');

    identity_test_query("UPDATE bills_voovi_links SET BillId=NULL WHERE schet_rand='".mysql_real_escape_string($source['rand'])."'");
    $action['creation_token'] = voovi_schet_form_token($actionScope,$actionUser);
    $pending = $runRefusal($action);
    identity_test_require(isset($pending['rand']) && (int)identity_test_scalar("SELECT COUNT(*) FROM bills_voovi_links WHERE schet_rand='".
        mysql_real_escape_string($pending['rand'])."' AND BillId IS NULL") === 1, 'Не перенесена ожидающая привязка Контура');

    // Без связи номер может быть от другого поставщика: копируем реквизиты, но не придумываем Контур.
    identity_test_query("DELETE FROM bills_voovi_links WHERE schet_rand='".mysql_real_escape_string($source['rand'])."'");
    identity_test_query("DELETE FROM document_data WHERE rand='".mysql_real_escape_string($source['rand'])."'");
    $action['tipschet'] = '';
    $action['creation_token'] = voovi_schet_form_token($actionScope,$actionUser);
    $legacy = $runRefusal($action);
    identity_test_require(isset($legacy['rand']), 'Старый источник без связанных данных не склонирован');
    $legacySql = mysql_real_escape_string($legacy['rand']);
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM bills_voovi_links WHERE schet_rand='".$legacySql.
        "'") === 0, 'Номер другого поставщика создал ложную связь с Контуром');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".$legacySql.
        "' AND nomerschetks='s-fixture' AND url='https://example.test/bill' AND priceks='1500'") === 2,
        'Клон без связи потерял прежние реквизиты поставщика');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM document_data WHERE rand='".$legacySql.
        "' AND d_bill='2026-10-25' AND type_contract='oferta'") === 1, 'Клон старого источника остался без дат');
    identity_test_require((int)identity_test_scalar('SELECT COUNT(*) FROM bills') === 1, 'Клон создал дубликат Контура');

    // Исторические документы до 2024 года не превращаются в оферту при отказе.
    foreach (array(array('2023','0','dogovor'), array('23','1','contract'), array('2024','1','oferta')) as $case) {
        identity_test_query("UPDATE schet SET y='".$case[0]."',m='12',d='31' WHERE rand='".mysql_real_escape_string($source['rand'])."'");
        identity_test_query("UPDATE ogrn SET budjet_ogrn='".$case[1]."' WHERE id=1");
        $action['creation_token'] = voovi_schet_form_token($actionScope,$actionUser);
        $historical = $runRefusal($action);
        identity_test_require(isset($historical['rand']) && identity_test_scalar("SELECT type_contract FROM document_data WHERE rand='".
            mysql_real_escape_string($historical['rand'])."'") === $case[2], 'Изменился исторический тип договора клона');
    }

    require __DIR__.'/schet-edit.test.php';
    require __DIR__.'/invoice-travel-display.test.php';
    require __DIR__.'/schet-financial-guard.test.php';

    require __DIR__.'/schet-number-display.test.php';
    require __DIR__.'/supplier-invoice-display.test.php';
    require __DIR__.'/schet-document-id.test.php';
    echo "Schet identity integration: 48 concurrent allocations, 4 concurrent form retries, goods links, linked refusal rollback — OK\n";
    $identityTestFinished = true;
} catch (Exception $error) {
    fwrite(STDERR, $error->getMessage()."\n");
    exit(1);
}
