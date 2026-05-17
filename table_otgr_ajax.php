<?php
error_reporting(0);

include 'conf.php';

// если getDateDocuments() и прочие функции живут тут — подключаем
// (у тебя в основной странице есть include invoice_action.php)
if (file_exists(__DIR__ . '/invoice_action.php')) {
    include __DIR__ . '/invoice_action.php';
}

header('Content-Type: application/json; charset=utf-8');

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

$uid    = isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
$inogrn = isset($_GET['inogrn']) ? (int)$_GET['inogrn'] : 0;
// С какого номера продолжать нумерацию (после уже отображённых активных счетов на странице)
$number_start = isset($_GET['number_start']) ? (int)$_GET['number_start'] : 0;

$limit = 5;

if ($id <= 0) {
    echo json_encode(['html' => '', 'fetched' => 0, 'added' => 0], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * 1) Собираем userdata как в обычной странице
 *    (inctoha.php использует $userdata['dotdel'], users_id, inogrn и т.д.)
 */
$userdata = [];
if ($uid > 0) {
    $uq = mysql_query("SELECT * FROM users WHERE users_id = " . (int)$uid . " LIMIT 1");
    if ($uq) {
        $userdata = mysql_fetch_assoc($uq);
    }
}
if (!isset($userdata['users_id'])) $userdata['users_id'] = $uid;
if (!isset($userdata['inogrn']))   $userdata['inogrn']   = $inogrn;
if (!isset($userdata['dotdel']))   $userdata['dotdel']   = '';

/**
 * 2) Фильтр по выбранному отделу (организации) — как в tabl.php:
 *    Выбранный отдел хранится в userdata['inogrn'].
 *    inogrn == 89097565645 → «все отделы», по SQL не фильтруем (доступ потом по dotdel).
 *    inogrn != 89097565645 → только счета, у которых продукт принадлежит этой организации (produkti.parent = inogrn).
 */
$cond = "del='0'
         AND idkli = " . (int)$id . "
         AND ( (akt='1') OR (akt='0' AND (cher='1' OR otk='1')) )";

if ((int)$inogrn !== 89097565645 && $inogrn > 0) {
    $cond .= " AND produkt IN (SELECT id FROM produkti WHERE parent = " . (int)$inogrn . ")";
}

// Сколько строк брать "про запас", т.к. часть отфильтруется inctoha.php по dotdel
$fetch_limit = ($offset + $limit) * 20;
if ($fetch_limit < 100) $fetch_limit = 100;
if ($fetch_limit > 2000) $fetch_limit = 2000;

$sql = "
SELECT s.*
FROM schet s
JOIN (
    SELECT rand, MAX(id) AS max_id
    FROM schet
    WHERE $cond
    GROUP BY rand
    ORDER BY max_id DESC
    LIMIT 0, " . (int)$fetch_limit . "
) t ON t.max_id = s.id
ORDER BY s.id DESC
";

$q = mysql_query($sql);

$html = '';
$fetched = 0; // сколько реально отдали в этой пачке (обычно 0..5)
$added = 0;   // то же самое, оставляю как у тебя
$seen_allowed = 0; // сколько "разрешённых" строк мы увидели (после dotdel-фильтра)

if ($q) {
    // Нумерация: продолжаем после активных счетов (number_start) и учитываем offset по архивным.
    // Первая подгруженная строка в этой пачке = number_start + offset, далее $iz++ в inctoha.php.
    $iz = $number_start + $offset;

    while ($row = mysql_fetch_assoc($q)) {
        // Проверка доступа как в inctoha.php:
        // там: if(substr_count($userdata['dotdel'], $row['otdel']) == 1) { echo <tr>... }
        // Значит: если dotdel пустой, то всё будет пропущено.
        $allowed = (substr_count((string)$userdata['dotdel'], (string)$row['otdel']) == 1);

        // Если супер-пользователь (у тебя inogrn=89097565645) — часто хотят видеть всё.
        // Если так и надо — раскомментируй:
        if ((string)$inogrn === '89097565645') {
            $allowed = true;
        }

        if (!$allowed) {
            continue;
        }

        $seen_allowed++;

        // пропускаем первые offset "разрешённых" строк
        if ($seen_allowed <= $offset) {
            continue;
        }

        // Ровно limit строк добавляем
        if ($fetched >= $limit) {
            break;
        }

        // 3) Генерим TR ровно твоим inctoha.php
        ob_start();
        // ВАЖНО: inctoha.php использует $row, $userdata, $iz (и еще много глобальных функций/includes)
        include __DIR__ . '/inctoha.php';
        $chunk = ob_get_clean();

        if ($chunk !== '') {
            $html .= $chunk;
            $fetched++;
            $added++;
            // $iz инкрементится внутри inctoha.php через echo $iz++;
            // но на всякий случай можно гарантировать рост:
            // $iz++;
        }
    }
}

echo json_encode([
    'html'    => $html,
    'fetched' => $fetched,
    'added'   => $added,
], JSON_UNESCAPED_UNICODE);

exit;