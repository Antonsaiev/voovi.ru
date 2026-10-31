<?php
namespace VooviInvoiceNumberTest;
// Проверяем реальные фрагменты отображения без подключения к БД и генерации документов.
define('VOOVI_DOC_URL', 'https://documents.example.test');

function require_display($condition, $message) {
    if (!$condition) { throw new \RuntimeException($message); }
}

function mysql_query($query) {
    throw new \RuntimeException('Отображение номера попыталось изменить или прочитать БД');
}

function render_number_fragment($code, $schet, $row) {
    ob_start();
    eval('namespace '.__NAMESPACE__.'; '.$code);
    return ob_get_clean();
}

$root = dirname(__DIR__);
$files = array('call.php', 'divoplata.php', 'docpage.php', 'inctoha.php', 'kartklients.php',
    'komnete.php', 'leha.php', 'regkartkli.php', 'schet.php', 'schetdataedit.php', 'searchschet.php',
    'setschet.php', 'specifik.php', 'toha.php', 'tohha.php', 'tovarnaklad.php', 'uliya.php', 'vika.php');
$derived = '/\$\w+\[\'god\'\]\s*[.,]\s*\$\w+\[\'(?:kto|filial)\'\].*?\[\'(?:kolichschet|nomerschet)\'\]/';
foreach ($files as $file) {
    $source = file_get_contents($root.'/'.$file);
    require_display(!preg_match($derived, $source), 'Номер снова составляется из изменяемых полей: '.$file);
    require_display(!preg_match('/UPDATE\s+`?schet`?\s+SET\s+`?ns`?\s*=/i', $source), 'Чтение страницы меняет ns: '.$file);
}

$header = file_get_contents($root.'/komnete.php');
require_display(preg_match('/if\(\$schet\[\'nomerschet\'\] != \'В КС\'\)\{(.*?)\}else\{/s', $header, $match), 'Не найдено отображение номера карточки');
$headerCode = $match[1];
$table = file_get_contents($root.'/inctoha.php');
require_display(preg_match('/if\(\$row\[\'nomerschet\'\] != \'В КС\'\)\{(.*?)\}else\{/s', $table, $match), 'Не найдено отображение номера списка');
$tableCode = $match[1];
$stored = '263A21840';
foreach (array('3', '97') as $manager) {
    $invoice = array('ns'=>$stored, 'kto'=>$manager, 'god'=>'99', 'otdel'=>'ZZ', 'kolichschet'=>'999', 'nomerschet'=>'1');
    $headerHtml = render_number_fragment($headerCode, $invoice, $invoice);
    $tableHtml = render_number_fragment($tableCode, $invoice, $invoice);
    require_display(strpos($headerHtml, 'S_<a ') === 0, 'Потерян префикс S_');
    require_display(strpos($headerHtml, '?ns='.$stored.'"') !== false && strpos($headerHtml, '>'.$stored.'</a>') !== false, 'Карточка использует изменённого менеджера');
    require_display(strpos($tableHtml, '?ns='.$stored.'"') !== false && strpos($tableHtml, '>'.$stored.'</a>') !== false, 'Список использует изменённого менеджера');
}
// Даже старое незаполненное значение отображается как сохранено, без записи при чтении.
$empty = array('ns'=>'0', 'kto'=>'97', 'god'=>'99', 'otdel'=>'ZZ', 'kolichschet'=>'999', 'nomerschet'=>'1');
require_display(strpos(render_number_fragment($headerCode, $empty, $empty), '>0</a>') !== false, 'Пустой номер был пересчитан');
echo "Stored Voovi invoice number: manager-independent displays, links and read-only rendering — OK\n";
