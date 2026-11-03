<?php
# подключаем конфиг
include 'conf.php';  

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
    { 
        setcookie('id', '', time() - 60*24*30*12, '/'); 
        setcookie('hash', '', time() - 60*24*30*12, '/');
    setcookie('errors', '1', time() + 60*24*30*12, '/'); 
    header('Location: index.php'); exit();
    } 
} 
else 
{ 
  setcookie('errors', '2', time() + 60*24*30*12, '/');
  header('Location: index.php'); exit();
}
?>
<?php
//Если форма отправлена
if(isset($_POST['submit'])) {

	//Проверка Поля ИМЯ
	if(trim($_POST['contactname']) == '') {
		$hasError = true;
	} else {
		$name = trim($_POST['contactname']);
	}

	//Проверка поля ТЕЛЕФОН
	if(trim($_POST['subject']) == '') {
		$hasError = true;
	} else {
		$subject = trim($_POST['subject']);
	}

	//Если ошибок нет, отправить email
	if(!isset($hasError)) {
		$emailTo = 'gmrxmax@yandex.ru'; //Место для email
		$body = "Имя: $name; \r\nТелефон: $subject;";
		$headers = 'From: GMCMS  <'.$emailTo.'>' . "\n\n" . 'Reply-To: ' . $email;

		mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
	}
}
?>
<?php
// Общий вывод списков сохраняет прежние действия привязки.
function organization_links_escape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function organization_links_page_url($key, $page) {
    $params = array();
    foreach (array('id', 'svyaz', 'q', 'naim', 'inn', 'kpp', 'ogrn', 'email', 'page', 'pagee') as $name) {
        if (isset($_GET[$name]) && is_scalar($_GET[$name])) $params[$name] = (string)$_GET[$name];
    }
    $params[$key] = $page;
    return 'svyaz.php?'.http_build_query($params, '', '&');
}
function organization_links_pagination($key, $page, $pages) {
    if ($pages <= 1) return;
    echo '<nav class="links-pagination" aria-label="Страницы организаций">';
    $numbers = array_unique(array_merge(array(1), range(max(1, $page - 2), min($pages, $page + 2)), array($pages)));
    $previous = 0;
    foreach ($numbers as $number) {
        if ($previous && $number > $previous + 1) echo '<span class="links-ellipsis">…</span>';
        if ($number == $page) echo '<span class="links-page-current" aria-current="page">'.$number.'</span>';
        else echo '<a href="'.organization_links_escape(organization_links_page_url($key, $number)).'">'.$number.'</a>';
        $previous = $number;
    }
    if ($page < $pages) echo '<a href="'.organization_links_escape(organization_links_page_url($key, $page + 1)).'">Следующая</a>';
    echo '</nav>';
}
function organization_links_table($result, $group) {
    if (!$result || !mysql_num_rows($result)) {
        echo '<p class="links-empty">Организации не найдены.</p>';
        return;
    }
    echo '<div class="links-table-scroll" tabindex="0" role="region" aria-label="Список организаций"><table class="links-table"><thead><tr><th>Организация</th><th>ИНН / КПП</th><th>ОГРН</th><th><span class="sr-only">Действие</span></th></tr></thead><tbody>';
    while ($row = mysql_fetch_assoc($result)) {
        $id = (int)$row['id'];
        echo '<tr><td><a class="links-company" href="kartklient.php?id='.$id.'">'.organization_links_escape($row['naim']).'</a></td><td><span class="links-tax">'.organization_links_escape($row['inn']).'</span><small>'.organization_links_escape($row['kpp']).'</small></td><td class="links-ogrn">'.organization_links_escape($row['ogrn']).'</td><td class="links-action-cell">';
        if ((string)$row['rand'] === $group) {
            echo '<span class="links-badge">Основная</span>';
        } else {
            $linked = (string)$row['svyaz'] === $group;
            $label = $linked ? 'Отвязать' : ($row['svyaz'] != 0 ? 'Перевязать' : 'Привязать');
            $url = 'svyazonoff.php?'.http_build_query(array('id'=>$id, 'svyaz'=>$group, 'go'=>$linked ? 0 : 1), '', '&');
            echo '<a class="links-action'.($linked ? ' links-action-remove' : '').'" href="'.organization_links_escape($url).'">'.$label.'</a>';
        }
        echo '</td></tr>';
    }
    echo '</tbody></table></div>';
}
$clientId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$group = isset($_GET['svyaz']) && is_scalar($_GET['svyaz']) ? (string)$_GET['svyaz'] : '';
if (!preg_match('/^[0-9]+$/D', $group)) { http_response_code(400); exit('Не указана группа организаций. Откройте страницу из карточки клиента.'); }
$groupSql = mysql_real_escape_string($group);
if (!isset($_GET['fragment'])) {
$clientResult = mysql_query("SELECT naim, inn FROM ogrn WHERE id=$clientId LIMIT 1");
$client = $clientResult ? mysql_fetch_assoc($clientResult) : false;
}
$filters = array();
foreach (array('naim', 'inn', 'kpp', 'ogrn', 'email') as $name) {
    $filters[$name] = isset($_GET[$name]) && is_scalar($_GET[$name]) ? (string)$_GET[$name] : '';
}
$searchText = isset($_GET['q']) && is_scalar($_GET['q']) ? trim((string)$_GET['q']) : '';
$legacySearch = !isset($_GET['q']) && isset($_GET['inn']);
$searching = $searchText !== '' || $legacySearch;
// Старые ссылки с отдельными фильтрами продолжают работать.
$searchInput = isset($_GET['q']) ? $searchText : trim(implode(' ', array_filter($filters, 'strlen')));
$perPage = 35;
if (!isset($_GET['fragment'])) {
$linkedCountResult = mysql_query("SELECT COUNT(*) FROM ogrn WHERE svyaz='$groupSql'");
$linkedCountRow = mysql_fetch_row($linkedCountResult);
$linkedCount = (int)$linkedCountRow[0];
$linkedPages = max(1, (int)ceil($linkedCount / $perPage));
$linkedPage = max(1, min($linkedPages, isset($_GET['pagee']) ? (int)$_GET['pagee'] : 1));
$linkedStart = ($linkedPage - 1) * $perPage;
$linkedResult = mysql_query("SELECT * FROM ogrn WHERE svyaz='$groupSql' ORDER BY id DESC LIMIT $linkedStart, $perPage");
}
$pages = 1;
$page = 1;
$start = 0;
if (!$searching) {
$totalResult = mysql_query('SELECT COUNT(*) FROM ogrn');
$totalRow = mysql_fetch_row($totalResult);
$pages = max(1, (int)ceil($totalRow[0] / $perPage));
$page = max(1, min($pages, isset($_GET['page']) ? (int)$_GET['page'] : 1));
$start = ($page - 1) * $perPage;
}
if ($searchText !== '') {
    $searchSql = mysql_real_escape_string(str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $searchText));
    $listResult = mysql_query("SELECT * FROM ogrn WHERE naim LIKE '%$searchSql%' OR inn LIKE '%$searchSql%' OR kpp LIKE '%$searchSql%' OR ogrn LIKE '%$searchSql%' ORDER BY id DESC LIMIT 40");
} elseif ($legacySearch) {
    $sqlFilters = array_map('mysql_real_escape_string', $filters);
    // Условия и ограничение поиска оставлены прежними.
    $listResult = mysql_query("SELECT * FROM ogrn WHERE CONCAT(naim,' ',inn) LIKE '%{$sqlFilters['inn']}%' AND naim LIKE '%{$sqlFilters['naim']}%' AND kpp LIKE '%{$sqlFilters['kpp']}%' AND ogrn LIKE '%{$sqlFilters['ogrn']}%' AND email LIKE '%{$sqlFilters['email']}%' LIMIT 40");
} else {
    $listResult = mysql_query("SELECT * FROM ogrn ORDER BY id DESC LIMIT $start, $perPage");
}
ob_start();
?>
        <div class="links-list-heading"><?php echo $searching ? 'Результаты поиска' : 'Организации'; ?><span><?php echo $searching ? 'Показано до 40 организаций' : 'Страница '.$page.' из '.$pages; ?></span></div>
        <?php organization_links_table($listResult, $group); ?>
        <?php if (!$searching) organization_links_pagination('page', $page, $pages); ?>
        <?php if ($searching && $listResult && !mysql_num_rows($listResult) && preg_match('/^[0-9]{10}([0-9]{2})?$/D', $searchText)): ?>
        <p class="links-create"><a href="<?php echo organization_links_escape(VOOVI_MAIN_URL.'/newogrn.php?'.http_build_query(array('id'=>186, 'inn'=>$searchText, 'kpp'=>''), '', '&')); ?>">Создать организацию по ИНН <?php echo organization_links_escape($searchText); ?></a></p>
        <?php endif; ?>
<?php
$resultsHtml = ob_get_clean();
if (isset($_GET['fragment'])) {
    header('Content-Type: application/json; charset=utf-8');
    if (!$listResult) {
        http_response_code(500);
        echo json_encode(array('error'=>'Не удалось выполнить поиск. Попробуйте ещё раз.'));
    } else {
        echo json_encode(array('html'=>$resultsHtml));
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Связанные организации — Voovi</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="organization-links-page">
<?php include 'header.php'; ?>
<link href="css/organization-links.css?v=2" rel="stylesheet">
<main class="links-layout">
    <header class="links-header">
        <a class="links-back" href="kartklient.php?id=<?php echo $clientId; ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Карточка клиента</a>
        <h1>Связанные организации</h1>
        <?php if ($client): ?><p><?php echo organization_links_escape($client['naim']); ?> <span class="links-client-inn">ИНН <?php echo organization_links_escape($client['inn']); ?></span></p><?php endif; ?>
    </header>
    <section class="links-section" aria-labelledby="linked-heading">
        <div class="links-section-heading"><h2 id="linked-heading">Текущие связи</h2><span class="links-count"><?php echo $linkedCount; ?></span></div>
        <?php if ($linkedCount): ?>
            <?php organization_links_table($linkedResult, $group); organization_links_pagination('pagee', $linkedPage, $linkedPages); ?>
        <?php else: ?>
            <p class="links-empty">Пока нет связанных организаций. Найдите нужную организацию ниже и нажмите «Привязать».</p>
        <?php endif; ?>
    </section>
    <section class="links-section links-search-section" aria-label="Поиск организаций">
        <form class="links-search" action="svyaz.php" method="get" role="search">
            <input type="hidden" name="id" value="<?php echo $clientId; ?>">
            <input type="hidden" name="svyaz" value="<?php echo organization_links_escape($group); ?>">
            <label class="links-unified-search" for="links-query">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                <span class="sr-only">Название, ИНН, КПП или ОГРН</span>
                <input id="links-query" name="q" type="search" autocomplete="off" placeholder="Название, ИНН, КПП или ОГРН" value="<?php echo organization_links_escape($searchInput); ?>" aria-controls="links-results">
            </label>
            <span id="links-search-status" class="links-search-status" role="status" aria-live="polite"></span>
        </form>
        <div id="links-results"><?php echo $resultsHtml; ?></div>
    </section>
</main>
<?php include 'footer.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/organization-links-search.js?v=1"></script>
</body>
</html>
