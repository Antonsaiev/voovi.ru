<?php
if (!function_exists('savoir_bottom_count_badge')) {
    function savoir_bottom_count_badge($key, $class = 'badgeee', $style = '') {
        $styleAttr = $style !== '' ? ' style="'.$style.'"' : '';
        return '<span class="'.$class.'"'.$styleAttr.' data-savoir-count-key="'.$key.'">...</span>';
    }
}

if (!function_exists('savoir_bottom_tile')) {
    function savoir_bottom_tile($href, $title, $class, $iconHtml, $countKey, $badgeClass = 'badgeee', $style = '', $badgeStyle = '') {
        $styleAttr = $style !== '' ? ' style="'.$style.'"' : '';
        echo '<a href="'.$href.'" title="'.$title.'" class="'.$class.'"'.$styleAttr.'>';
        echo $iconHtml.' ';
        echo savoir_bottom_count_badge($countKey, $badgeClass, $badgeStyle);
        echo '</a>';
    }
}

savoir_bottom_tile(
    '/toha.php?turbo=1',
    'Ускоренные',
    'macintosh btn btn-xs',
    '<span class="glyphicon glyphicon-plane" aria-hidden="true"></span>',
    'turbo',
    'badgeee',
    'background: #7A00FF;',
    'color: #7A00FF;'
);

if ($userdata['otvetstven'] == '1') {
    echo '<a href="#" onclick="barak(); return false;" style="background: #3FA043;" title="Отгруженные" class="macintosh btn btn-xs" aria-haspopup="dialog">';
    echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> ';
    echo savoir_bottom_count_badge('shipped', 'badgee');
    echo '</a>';
}

$statusHighlightList = array(4, 16, 17, 18, 19, 20, 161, 48, 49, 50, 51, 52);
$statusQuery = mysql_query("SELECT * from status WHERE inv = '0' AND del = '0' AND uslugi = '".mysql_real_escape_string($userdata['inogrn'])."' ORDER BY id DESC");
while ($statusRow = mysql_fetch_array($statusQuery)) {
    $style = in_array($statusRow['id'], $statusHighlightList) ? 'background: #CCE4F7;' : '';
    echo '<a href="/toha.php?status='.$statusRow['id'].'" title="'.$statusRow['name'].'" class="macintosh btn btn-xs"'.($style !== '' ? ' style="'.$style.'"' : '').'>';
    echo $statusRow['ico'];
    echo savoir_bottom_count_badge('status_'.$statusRow['id'], 'badgeee', 'color: #A9A9A9;');
    echo '</a>';
}

savoir_bottom_tile(
    '/toha.php?oplachen=1&gotov=0',
    'Оплаченные',
    'macintosh btn btn-xs',
    '<span class="glyphicon glyphicon-ruble" aria-hidden="true"></span>',
    'paid_ready',
    'badgee',
    'background: #3FA043;'
);

savoir_bottom_tile(
    '/toha.php?neoplachen=0&moy=1',
    'Мои неоплаченные',
    'macintosh btn btn-xs',
    '<span class="glyphicon glyphicon-ruble" aria-hidden="true" title="Мои неоплаченные"></span>',
    'my_unpaid',
    'badge',
    'background: #C42D29;'
);

savoir_bottom_tile(
    '/toha.php?doljenop=1',
    'Должны оплатить',
    'macintoshgavno btn btn-xs',
    '<span class="glyphicon glyphicon-ruble" aria-hidden="true" title="Должны оплатить"></span>',
    'doljenop',
    'badge',
    'background: #fb0000; border-right: 1px solid #fff;'
);

savoir_bottom_tile(
    '/toha.php?doljen=1',
    'Должники по документам',
    'macintoshred btn btn-xs',
    '<span class="glyphicon glyphicon-user" aria-hidden="true" title="Должники по документам"></span>',
    'doljen',
    'badge',
    'border-right: 1px solid #fff;'
);

if ($userdata['users_id'] == 20 || $userdata['users_id'] == 17 || $userdata['users_id'] == 4 || $userdata['users_id'] == 4107 || $userdata['users_id'] == 3 || $userdata['users_id'] == 4120) {
    savoir_bottom_tile(
        '/toha.php?postprod=1',
        'Поставить продление',
        'macintoshgavno btn btn-xs',
        '<span class="glyphicon glyphicon-user" aria-hidden="true"></span>',
        'postprod',
        'badge',
        'background: #5BC0DE;'
    );
}
?>
