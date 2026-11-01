<?php
// Подключается только из scratch-suite: проверяем реальный POST-блок myogrn.php.
foreach (array('gen','tell','agent','oplachen','date_op','akt_date','doljen','doljenop',
    'gotov','status','tipprod','dataprod','datasert','date_schet','custom_metadata','datebron') as $field) {
    identity_test_query('ALTER TABLE schet ADD COLUMN `'.$field.'` TEXT NULL');
}
identity_test_query('ALTER TABLE av ADD COLUMN rand VARCHAR(366)');
foreach (array('os_prod','dob_prod','dobprodschet','dob_usl','pagent','sagent','dagent','shtsert','chisrol',
    'schet','summschet','kto','is_commission_processed','schetav') as $field) {
    identity_test_query('ALTER TABLE av ADD COLUMN `'.$field.'` VARCHAR(366) NOT NULL DEFAULT \'\'');
}
identity_test_query('ALTER TABLE ogrn ADD COLUMN inn VARCHAR(64), ADD COLUMN kpp VARCHAR(64), ADD COLUMN naim VARCHAR(64)');
identity_test_query("UPDATE ogrn SET inn='fixture',kpp='',naim='Test customer' WHERE id=1");
foreach (array('data','deistvie','users','gmd','tip') as $field) {
    identity_test_query('ALTER TABLE aktivn ADD COLUMN `'.$field.'` VARCHAR(366)');
}
identity_test_query('CREATE TABLE tarif_parent(id INT PRIMARY KEY,product_id INT,active INT) ENGINE=InnoDB');
identity_test_query('CREATE TABLE tarif(id INT PRIMARY KEY,sync_id INT,parent VARCHAR(20),`del` CHAR(1),gen CHAR(1),price VARCHAR(20)) ENGINE=InnoDB');
identity_test_query("INSERT INTO tarif_parent VALUES(1,1,1),(2,1,1),(3,1,1)");
identity_test_query("INSERT INTO tarif VALUES(55,1,'1','0','1','100'),(56,2,'1','0','0','200'),(57,3,'1','0','0','300')");
$sourceSql = mysql_real_escape_string($source['rand']);
identity_test_query("UPDATE schet SET prod=IF(id=100,'55','56'),kvo='1',gen='0',price='500',skidka='12',
    akt='1',akt_date='2610',oplachen='1',oplachenks='1',date_op='2026-10-25',doljen='1',doljenop='1',
    gotov='1',status='5',tipprod='Сер/Пос',dataprod='2027-10-25',datasert='2027-11-25',date_schet='25.10.2026',
    kto='10',otdel='B',filial='old-branch',oferta='1',agent='7',nds='10',goroddd='0',kvogorod='1',datebron=NULL,
    custom_metadata=IF(id=100,'first-position','second-position') WHERE rand='".$sourceSql."'");
identity_test_query("INSERT INTO document_data(rand,d_bill,d_contract,d_specification,d_act,d_tn,d_invoice_advance,d_upd,type_contract)
    VALUES ('".$sourceSql."','2023-01-02','2023-01-03','2023-01-04','2023-01-05','2023-01-06','2023-01-07','2023-01-08','contract')");
$before = array();
$query = identity_test_query("SELECT * FROM schet WHERE rand='".$sourceSql."' AND `del`='0' ORDER BY id");
while ($row = mysql_fetch_assoc($query)) { unset($row['id']); $before[$row['prod']] = $row; }
$form = array('post'=>array('submitsave'=>'1','creation_token'=>voovi_schet_form_token('myogrn.php:1:1',$actionUser),
    '55'=>'1','56'=>'1','input55'=>'2','input56'=>'3','aallsumm'=>'720','skidka'=>'10','nomerschetks'=>'s-fixture',
    'priceks'=>'1500','url'=>'https://example.test/bill','tel'=>"test's phone"),
    'get'=>array('rand'=>$source['rand'],'id'=>'1','parent'=>'1','tip'=>'1'));
$runEdit = function($payload) {
    return shell_exec(escapeshellarg(PHP_BINARY).' -d short_open_tag=1 -d display_errors=0 '.escapeshellarg(__DIR__.'/schet-identity.test.php').
        ' --edit-worker '.escapeshellarg(base64_encode(json_encode($payload))));
};
$output = $runEdit($form);
identity_test_require(strpos($output,'schetSaveError') === false && strpos($output,'window.location.href') !== false,
    'Реальный блок редактирования не завершился успешно');
$query = identity_test_query("SELECT * FROM schet WHERE rand='".$sourceSql."' AND `del`='0' ORDER BY id");
identity_test_require(mysql_num_rows($query) === 2, 'Редактирование потеряло позиции');
while ($row = mysql_fetch_assoc($query)) {
    $expected = $before[$row['prod']]; unset($row['id']);
    $expected['kvo'] = $row['prod'] === '55' ? '2' : '3';
    $expected['gen'] = $row['prod'] === '55' ? '1' : '0';
    $expected['price'] = '720.00'; $expected['skidka'] = '10'; $expected['produkt'] = '1';
    $expected['tell'] = "test's phone";
    $cost = json_decode($row['summa'], true);
    identity_test_require(is_array($cost) && $cost['v'] === 1 && $cost['p'] === $row['prod'] &&
        $cost['q'] === $row['kvo'] && $cost['a'] === ($row['prod'] === '55' ? '180.00' : '540.00'),
        'Редактирование не зафиксировало стоимость строки');
    $expected['summa'] = $row['summa'];
    identity_test_require($row === $expected, 'Изменились флаги, даты, автор или метаданные позиции '.$row['prod']);
}
identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM document_data WHERE rand='".$sourceSql."'") === 1 &&
    identity_test_scalar("SELECT type_contract FROM document_data WHERE rand='".$sourceSql."'") === 'contract',
    'Редактирование изменило даты или создало дубликат document_data');

// Успешное сохранение пустого блока АВ сохраняет все прежние строки и их служебные отметки.
identity_test_query("INSERT INTO av(rand,os_prod,dob_prod,pagent,sagent,schetav,is_commission_processed)
    VALUES ('".$sourceSql."','1','','Без АВ','Без АВ','main-report','1'),
    ('".$sourceSql."','1','16','old-L','old-S','extra-report','1'),
    ('".$sourceSql."','1','17','','old-S-17','other-extra-report','1')");
$agencyRows = function() use ($sourceSql) {
    $result = array(); $query = identity_test_query("SELECT * FROM av WHERE rand='".$sourceSql."' ORDER BY id");
    while ($row = mysql_fetch_assoc($query)) { $result[] = $row; }
    return $result;
};
$agencyBefore = $agencyRows();
$agencyForm = $form; $agencyForm['get']['tip'] = '12';
$agencyForm['post'] += array('av'=>'-1','dobprod'=>array('0'),'avdob'=>array('-1'));
$output = $runEdit($agencyForm);
identity_test_require(strpos($output,'schetSaveError') === false && $agencyRows() === $agencyBefore,
    'Пустой блок успешного PHP-редактирования удалил или изменил прежние АВ');
foreach (array('0','1','2','3','4') as $mode) {
    $agencyForm['post']['av'] = $mode;
    $agencyForm['post']['dobusl'] = array('2','3'); $agencyForm['post']['sht'] = '2';
    $output = $runEdit($agencyForm);
    $after = $agencyRows();
    $extras = array_values(array_filter($after, function($row) { return $row['dob_prod'] !== ''; }));
    identity_test_require(strpos($output,'schetSaveError') === false && $extras === array_slice($agencyBefore,1),
        'Изменение основного АВ удалило или изменило дополнительные продукты');
    $main = array_values(array_filter($after, function($row) { return $row['dob_prod'] === ''; }));
    identity_test_require(count($main) === 2 && array_column($main,'dob_usl') === array('2','3'),
        'Явный выбор АВ не сохранил все выбранные услуги');
    $expectedRoles = array('0'=>array('s-fixtureL','s-fixture','s-fixture','3'),
        '1'=>array('','s-fixture','s-fixture','2'), '2'=>array('','','s-fixture','1'),
        '3'=>array('Без АВ','Без АВ','Без АВ','3'), '4'=>array('Без АВ','Без АВ','Без АВ','3'));
    identity_test_require(array($main[0]['pagent'],$main[0]['sagent'],$main[0]['dagent'],$main[0]['chisrol']) === $expectedRoles[$mode]
        && $main[0]['shtsert'] === '2', 'Изменились роли или количество сертификатов в режиме АВ '.$mode);
}
$mainBefore = $main;
$agencyForm['post']['av'] = '-1';
$agencyForm['post']['dobprod'] = array('16','17');
$agencyForm['post']['avdob'] = array('1','-1'); $agencyForm['post']['shtpr'] = array('0','0');
$output = $runEdit($agencyForm);
$after = $agencyRows();
$main = array_values(array_filter($after, function($row) { return $row['dob_prod'] === ''; }));
$unchangedExtra = array_values(array_filter($after, function($row) { return $row['dob_prod'] === '17'; }));
identity_test_require(strpos($output,'schetSaveError') === false && $main === $mainBefore && $unchangedExtra === array($agencyBefore[2]),
    'Изменение одного доппродукта затронуло основной АВ или другой доппродукт');
identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM av WHERE rand='".$sourceSql.
    "' AND dob_prod='16' AND pagent='' AND sagent='s-fixture' AND chisrol='1'") === 1,
    'Явный выбор доппродукта не заменил его АВ');
identity_test_query("DELETE FROM av WHERE rand='".$sourceSql."'");

// Печать использует сохранённый итог даже после изменения текущего каталога.
$header = mysql_fetch_assoc(identity_test_query("SELECT * FROM schet WHERE rand='".$sourceSql."' AND `del`='0' ORDER BY id LIMIT 1"));
$document = voovi_invoice_document_amounts($source['rand'], $header, 20);
identity_test_require($document['gross'] === '720.00' && $document['vat'] === '120.00' &&
    array_column($document['lines'], 'gross') === array('180.00','540.00'), 'Печать не согласована с сохранением состава');
identity_test_query("UPDATE tarif SET price='999' WHERE id=55");
$document = voovi_invoice_document_amounts($source['rand'], $header, 20);
identity_test_require($document['gross'] === '720.00' && array_sum(array_map('voovi_money_cents', array_column($document['lines'],'gross'))) === 72000,
    'Изменение каталога изменило итог исторического документа');
identity_test_require(array_column($document['lines'], 'gross') === array('180.00','540.00'),
    'Изменение каталога изменило сохранённые суммы отдельных строк');
identity_test_query("UPDATE tarif SET price='100' WHERE id=55");
// Старая история действительно не содержит снимка; не подменяем параметры нового снимка при печати.
$printSnapshots = array();
$query = identity_test_query("SELECT id,summa FROM schet WHERE rand='".$sourceSql."' AND `del`='0'");
while ($row = mysql_fetch_assoc($query)) { $printSnapshots[$row['id']] = $row['summa']; }
identity_test_query("UPDATE schet SET summa='0' WHERE rand='".$sourceSql."' AND `del`='0'");
$travelHeader = array_merge($header, array('price'=>'900.00','goroddd'=>'450','kvogorod'=>'2'));
$document = voovi_invoice_document_amounts($source['rand'], $travelHeader, 20);
identity_test_require(isset($document['items']['travel']) && $document['gross'] === '900.00' &&
    array_sum(array_map('voovi_money_cents', array_column($document['lines'],'gross'))) === 90000,
    'Выезд потерян в составе или изменил сохранённый итог');
$travelHeader['goroddd'] = '162534';
$document = voovi_invoice_document_amounts($source['rand'], $travelHeader, 20);
identity_test_require(!isset($document['items']['travel']), 'Служебное значение отображается как платный выезд');
foreach ($printSnapshots as $id=>$snapshot) {
    identity_test_query("UPDATE schet SET summa='".mysql_real_escape_string($snapshot)."' WHERE id=".(int)$id);
}
foreach (array(array('450','0'), array('450',''), array('162534','2'), array('162534.00','2')) as $case) {
    $withoutTravel = array_merge($header, array('goroddd'=>$case[0], 'kvogorod'=>$case[1]));
    $document = voovi_invoice_document_amounts($source['rand'], $withoutTravel, 20);
    identity_test_require(!isset($document['items']['travel']) && $document['gross'] === '720.00',
        'Нулевой или служебный выезд попал в рассчитанный состав');
}

// В смешанной истории JSON остаётся первичным, старые строки получают только остаток итога.
$priceIds = array_keys($printSnapshots); sort($priceIds, SORT_NUMERIC);
identity_test_require(count($priceIds) === 2, 'Для проверки смешанных цен нужны две строки');
$firstPriceId = (int)$priceIds[0]; $secondPriceId = (int)$priceIds[1];
$legacyHeader = $header; $legacyHeader['price'] = '700.00';
identity_test_query("UPDATE schet SET summa='999999' WHERE id=".$firstPriceId);
$document = voovi_invoice_document_amounts($source['rand'], $legacyHeader, 20);
identity_test_require(array_column($document['lines'],'gross') === array('160.00','540.00') &&
    $document['gross'] === '700.00', 'Legacy summa ошибочно заменяет прайс или изменяет JSON-сумму');

// Если первая строка без JSON, выезд также распределяется по старым весам ровно один раз.
$legacyHeader['price'] = '900.00'; $legacyHeader['goroddd'] = '450'; $legacyHeader['kvogorod'] = '2';
$document = voovi_invoice_document_amounts($source['rand'], $legacyHeader, 20);
identity_test_require(array_column($document['lines'],'gross') === array('65.45','540.00','294.55') &&
    $document['items']['travel']['quantity'] === '2', 'Legacy тариф и выезд неверно делят остаток JSON-счёта');

// JSON первой строки фиксирует и свой тариф, и свой выезд, даже если другая строка старая.
$firstSnapshot = json_decode($printSnapshots[$firstPriceId], true);
$firstSnapshot['t'] = array('810.00','2','450');
identity_test_query("UPDATE schet SET summa='".mysql_real_escape_string(json_encode($firstSnapshot))."' WHERE id=".$firstPriceId);
identity_test_query("UPDATE schet SET summa='0' WHERE id=".$secondPriceId);
$legacyHeader['price'] = '1530.00';
$document = voovi_invoice_document_amounts($source['rand'], $legacyHeader, 20);
identity_test_require(array_column($document['lines'],'gross') === array('180.00','540.00','810.00'),
    'Старая строка изменила JSON-стоимость тарифа или выезда');

$rejectLegacyDocument = function($invoice, $fragment) use ($source) {
    $message = '';
    try { voovi_invoice_document_amounts($source['rand'], $invoice, 20); }
    catch (Exception $error) { $message = $error->getMessage(); }
    identity_test_require(strpos($message, $fragment) !== false,
        'Повреждённая смешанная история не остановлена: '.$fragment);
};
identity_test_query("UPDATE schet SET summa='0' WHERE id=".$firstPriceId);
identity_test_query("UPDATE schet SET summa='".mysql_real_escape_string($printSnapshots[$secondPriceId])."' WHERE id=".$secondPriceId);
$legacyHeader = $header; $legacyHeader['price'] = '500.00';
$rejectLegacyDocument($legacyHeader, 'превышают итог');
$legacyHeader['price'] = '700.00';
identity_test_query("UPDATE tarif SET price='0' WHERE id=55");
$rejectLegacyDocument($legacyHeader, 'стоимость старых строк');
identity_test_query("UPDATE tarif SET price='100' WHERE id=55");
identity_test_query("UPDATE schet SET summa='{broken' WHERE id=".$firstPriceId);
$rejectLegacyDocument($legacyHeader, 'повреждён');
foreach ($printSnapshots as $id=>$snapshot) {
    identity_test_query("UPDATE schet SET summa='".mysql_real_escape_string($snapshot)."' WHERE id=".(int)$id);
}

// После вставки первой новой позиции вторая ломается: старые строки и АВ остаются прежними.
identity_test_query("INSERT INTO av(rand) VALUES ('".$sourceSql."')");
$rowCount = identity_test_scalar('SELECT COUNT(*) FROM schet');
identity_test_query("CREATE TRIGGER fixture_fail_edit BEFORE INSERT ON schet FOR EACH ROW BEGIN IF NEW.prod='57' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='fixture edit failure'; END IF; END");
$failed = $form; $failed['post']['57']='1'; $failed['post']['input57']='1';
$failed['get']['tip']='12'; $failed['post'] += array('av'=>'0','dobusl'=>array('0'),'sht'=>'0');
$output = $runEdit($failed);
identity_test_require(strpos($output,'schetSaveError') !== false && strpos($output,'fixture edit failure') === false &&
    strpos($output,'classList.remove("is-visible")') !== false, 'SQL-ошибка не показана безопасно либо остался экран ожидания');
identity_test_require(identity_test_scalar('SELECT COUNT(*) FROM schet') === $rowCount &&
    (int)identity_test_scalar("SELECT COUNT(*) FROM schet WHERE rand='".$sourceSql."' AND `del`='0'") === 2 &&
    (int)identity_test_scalar("SELECT COUNT(*) FROM av WHERE rand='".$sourceSql."'") === 1,
    'Ошибка второй позиции не откатила состав и АВ');
identity_test_query('DROP TRIGGER fixture_fail_edit');

$invalid = $form; $invalid['post']['creation_token']='invalid';
$output = $runEdit($invalid);
identity_test_require(strpos($output,'Обновите форму счёта') !== false, 'Ошибка подписи не показана пользователю');
identity_test_require(identity_test_scalar('SELECT COUNT(*) FROM schet') === $rowCount, 'Неверная подпись изменила состав');
identity_test_query("INSERT INTO jurnalskzi(schet,schet_id) VALUES ('".$sourceSql."',NULL)");
$output = $runEdit($form);
identity_test_require(strpos($output,'привязаны товары') !== false && identity_test_scalar('SELECT COUNT(*) FROM schet') === $rowCount,
    'Старая связь товара не остановила форму с понятным сообщением');

$other = array('get'=>array('id'=>'1','parent'=>'1','inn'=>'fixture','kpp'=>''),
    'post'=>array('submitsch'=>'1','creation_token'=>'invalid','agent'=>'0'));
$output = shell_exec(escapeshellarg(PHP_BINARY).' -d short_open_tag=1 -d display_errors=0 '.escapeshellarg(__DIR__.'/schet-identity.test.php').
    ' --other-worker '.escapeshellarg(base64_encode(json_encode($other))));
identity_test_require(strpos($output,'schetSaveError') !== false && strpos($output,'Обновите форму счёта') !== false &&
    strpos($output,'classList.remove("is-visible")') !== false && identity_test_scalar('SELECT COUNT(*) FROM schet') === $rowCount,
    'Вторая форма не показала безопасную ошибку без изменения данных');

ob_start();
$_POST = array('creation_token'=>'hidden-signature','55'=>'1','input55'=>'2','url'=>'</script><script>unsafe()</script>');
voovi_schet_render_error(new VooviSchetException('Проверьте <параметр>'));
$errorHtml = ob_get_clean();
identity_test_require(strpos($errorHtml,'&lt;параметр&gt;') !== false && strpos($errorHtml,'hidden-signature') === false &&
    strpos($errorHtml,'<script>unsafe()') === false, 'Ошибка или восстановленные поля выводятся небезопасно');
ob_start();
voovi_schet_render_error(new RuntimeException('SELECT private technical details'));
$unexpectedHtml = ob_get_clean();
identity_test_require(strpos($unexpectedHtml,'private technical details') === false &&
    strpos($unexpectedHtml,'обратитесь к администратору') !== false,
    'Непредвиденная ошибка раскрывает технические сведения');
echo "Legacy invoice POST: metadata preservation, field errors and rollback — OK\n";

// Реальное создание через PHP: номер внешнего поставщика не ограничивает счёт Савоира.
foreach (array('Lagent','sos_opis','kol_opis') as $field) {
    identity_test_query('ALTER TABLE schet ADD COLUMN `'.$field.'` TEXT NULL');
}
foreach (array('god','filial','otdel','nomer','ogrn') as $field) {
    identity_test_query('ALTER TABLE dogovor ADD COLUMN `'.$field.'` VARCHAR(366) NOT NULL DEFAULT \'\'');
}
$numbers = array(); $rands = array();
foreach (array(array('',''), array('s-fixture','https://billy-partners.kontur.ru/billinfo/shared-bill'),
    array('INV-2026/42','https://supplier.example/invoices/42')) as $external) {
    $beforeId = (int)identity_test_scalar('SELECT MAX(id) FROM schet');
    $create = array('get'=>array('id'=>'1','parent'=>'1','tip'=>'1','ogrn'=>'1'),
        'post'=>array('submitsch'=>'1','creation_token'=>voovi_schet_form_token('myogrn.php:1:1',$actionUser),
            '55'=>'1','56'=>'1','input55'=>'2','input56'=>'3','aallsumm'=>'99999','skidka'=>'10',
            'nomerschetks'=>$external[0],'url'=>$external[1],'priceks'=>'1500','nsold'=>'',
            'dog'=>'1','agent'=>'0','tel'=>'fixture','goroddd'=>'0','kvogorod'=>'1','kol_opis'=>'0','sos_opis'=>'0'));
    $output = $runEdit($create);
    identity_test_require(strpos($output,'schetSaveError') === false && strpos($output,'window.location.href') !== false,
        'Новый PHP-счёт не создан: '.$external[0]);
    $query = identity_test_query('SELECT * FROM schet WHERE id>'.$beforeId.' ORDER BY id');
    identity_test_require(mysql_num_rows($query) === 2, 'Новый PHP-счёт потерял тарифные строки');
    $first = mysql_fetch_assoc($query); $second = mysql_fetch_assoc($query);
    identity_test_require($first['ns'] !== '' && $first['ns'] === $second['ns'] && $first['rand'] === $second['rand'],
        'PHP-счёт не получил один сохранённый номер и rand');
    foreach (array($first,$second) as $row) {
        identity_test_require($row['nomerschetks'] === $external[0] && $row['url'] === $external[1] &&
            $row['price'] === '720.00' && $row['priceks'] === '1500' && $row['kto'] === '3',
            'PHP-счёт изменил внешние реквизиты, сумму или автора');
        $cost = json_decode($row['summa'], true);
        identity_test_require(is_array($cost) && $cost['v'] === 1 && $cost['p'] === $row['prod'] &&
            $cost['a'] === ($row['prod'] === '55' ? '180.00' : '540.00'),
            'Создание PHP-счёта не сохранило стоимость строки');
    }
    $createdRandSql = mysql_real_escape_string($first['rand']);
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM document_data WHERE rand='".$createdRandSql."'") === 1,
        'PHP-счёт не сохранил реквизиты документов');
    identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM bills_voovi_links WHERE schet_rand='".$createdRandSql."'") === 0,
        'Новый PHP-счёт создал связь без распознанного документа Контура');
    $count = identity_test_scalar('SELECT COUNT(*) FROM schet');
    $runEdit($create);
    identity_test_require(identity_test_scalar('SELECT COUNT(*) FROM schet') === $count,
        'Повтор нового PHP POST создал дубликат счёта');
    $numbers[] = $first['ns']; $rands[] = $first['rand'];
}
identity_test_require(count(array_unique($numbers)) === 3 && count(array_unique($rands)) === 3,
    'Разным новым PHP-счетам выданы одинаковые номер или rand');
echo "Actual PHP invoice creation: Savoir only, saved Kontur number, other supplier number and repeat POST — OK\n";
