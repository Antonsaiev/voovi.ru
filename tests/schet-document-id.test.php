<?php
// Реальные читающие блоки schet.php: без HTTP, авторизационных подмен и записи файлов.
$page = file_get_contents(dirname(__DIR__).'/schet.php');
$inputStart = strpos($page, "\$id = isset(\$_GET['id'])");
$headerStart = strpos($page, '$pav = ', $inputStart);
$headerEnd = strpos($page, '$kli = ', $headerStart);
$amountsStart = strpos($page, '$invoiceAmounts = voovi_invoice_document_amounts(');
$amountsEnd = strpos($page, ';', $amountsStart)+1;
$rowsStart = strpos($page, "\$sum_vat = \$invoiceAmounts['vat'];");
$rowsEnd = strpos($page, "  if (\$pavoir['skidka'] > 0)", $rowsStart);
identity_test_require($inputStart !== false && $headerStart !== false && $headerEnd !== false &&
    $amountsStart !== false && $amountsEnd > $amountsStart && $rowsStart !== false && $rowsEnd !== false,
    'Не найдены реальные блоки чтения счёта');
$inputBlock = substr($page, $inputStart, $headerStart-$inputStart);
$headerBlock = substr($page, $headerStart, $headerEnd-$headerStart);
$amountsBlock = substr($page, $amountsStart, $amountsEnd-$amountsStart);
$rowsBlock = substr($page, $rowsStart, $rowsEnd-$rowsStart);

// TEMPORARY скрывают только тестовые таблицы на текущем подключении и удаляются ниже.
identity_test_query('CREATE TEMPORARY TABLE schet(id INT PRIMARY KEY,prod INT,kvo VARCHAR(20),summa TEXT,`del` CHAR(1),'.
    implode(',',array_map(function($field) { return '`'.$field.'` VARCHAR(366) NOT NULL DEFAULT \'\''; },
        array('ns','nomerschet','kolichschet','oferta','prodlen','generac','name','lico','rand','otdel','filial','god',
            'nomerdog','data','produkt','price','kto','inn','kpp','koment','ogrn','skidka','d','m','y','goroddd','kvogorod'))).')');
identity_test_query('CREATE TEMPORARY TABLE tarif(id INT PRIMARY KEY,price VARCHAR(20),name VARCHAR(30),shtuk VARCHAR(10))');
try {
    identity_test_query("INSERT INTO tarif VALUES(1,'999','Tariff A','item'),(2,'999','Tariff B','item')");
    foreach (array('0000120261101','2184020261025') as $rand) {
        foreach (array('json','mixed') as $mode) {
            identity_test_query('DELETE FROM schet');
            foreach (array(array('1','3','30.00'),array('2','1.5','70.00')) as $position) {
                $stored = $mode === 'mixed' && $position[0] === '1' ? '0' : json_encode(array(
                    'v'=>1,'p'=>$position[0],'q'=>$position[1],'d'=>'0','a'=>$position[2],'t'=>array()));
                identity_test_query("INSERT INTO schet(id,prod,kvo,summa,`del`,rand,ns,price,skidka,goroddd,kvogorod)
                    VALUES(".$position[0].",".$position[0].",'".$position[1]."','".mysql_real_escape_string($stored).
                    "','0','".$rand."','263A1','100.00','0','0','0')");
            }
            $_GET = array('id'=>$rand,'kli'=>'1');
            eval($inputBlock); eval($headerBlock);
            identity_test_require($id === $rand && $pavoir['rand'] === $rand && $schet['rand'] === $rand,
                'Реальное чтение шапки изменило строковый rand');
            $nds_rate = 20; $nds_col_text = 'НДС: 20%'; $str = '';
            eval($amountsBlock); eval($rowsBlock);
            identity_test_require($invoiceAmounts['gross'] === '100.00' &&
                array_column($invoiceAmounts['lines'],'gross') === array('30.00','70.00') &&
                strpos($str,'Tariff A') !== false && strpos($str,'Tariff B') !== false,
                'Печать потеряла JSON/mixed состав или сохранённый итог');
            preg_match_all('/<tr\b[^>]*>(.*?)<\/tr>/s',$str,$printedRows);
            $printedGross = 0; $printedQty = array();
            foreach ($printedRows[1] as $printedRow) {
                preg_match_all('/<td\b[^>]*>(.*?)<\/td>/s',$printedRow,$cells);
                $cells = array_map(function($cell) { return trim(strip_tags($cell)); },$cells[1]);
                identity_test_require(count($cells) === 7,'Неполная печатная строка');
                $printedQty[] = voovi_decimal($cells[3]);
                $printedGross += voovi_money_cents(str_replace(',','',$cells[6]));
            }
            $quantity = voovi_money_sql('('.implode('+',$printedQty).')=4.5');
            identity_test_require($printedGross === 10000 && $quantity[0] === '1',
                'Реальный шаблон изменил сумму или количество позиций');
        }
    }
} finally {
    identity_test_query('DROP TEMPORARY TABLE tarif');
    identity_test_query('DROP TEMPORARY TABLE schet');
}
foreach (array(null,'',array('1'),"1' OR '1'='1",'1 OR 1=1','../1','1e3','+1',' 1','1.5',"1\n",str_repeat('1',367)) as $input) {
    // Ответ 400 проверяется отдельным PHP-процессом без подключения к БД.
    $program = '$_GET=json_decode(base64_decode('.var_export(base64_encode(json_encode(array('id'=>$input))),true).'),true);'.
        'register_shutdown_function(function(){echo "|HTTP:".http_response_code();});'.$inputBlock.'echo "|PASSED|";';
    $program = 'eval(base64_decode('.var_export(base64_encode($program),true).'));';
    $output = shell_exec(escapeshellarg(PHP_BINARY).' -r '.escapeshellarg($program));
    identity_test_require(strpos($output,'Некорректный идентификатор счёта.') !== false &&
        strpos($output,'|HTTP:400') !== false && strpos($output,'|PASSED|') === false,
        'Некорректный rand допущен до чтения документа');
}
echo "Actual PHP invoice read: leading-zero rand, JSON/mixed composition and invalid input rejection — OK\n";
