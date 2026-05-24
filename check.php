<?php
include 'conf.php';

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']))
    {
        setcookie('id', '', time() - 60*24*30*12, '/');
        setcookie('hash', '', time() - 60*24*30*12, '/');
        setcookie('errors', '1', time() + 60*24*30*12, '/');
        header('Location: index.php');
        exit();
    }
}
else
{
    setcookie('errors', '2', time() + 60*24*30*12, '/');
    header('Location: index.php');
    exit();
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function read_value($value, $emptyText = 'Не указано') {
    $value = trim((string)$value);
    if ($value === '' || $value === '0' || $value === '???????') {
        return '<span class="company-muted">'.h($emptyText).'</span>';
    }
    return h($value);
}

function short_name($user) {
    $name = trim($user['f_name'].' '.$user['l_name'].' '.$user['o_name']);
    if ($name === '') {
        $name = $user['users_login'];
    }
    return $name;
}

$role = mysql_fetch_assoc(mysql_query("SELECT name FROM doljnost WHERE id = '".intval($userdata['tip'])."' LIMIT 1"));
$roleName = $role && trim($role['name']) !== '' ? trim($role['name']) : trim($userdata['pos_pos']);
if ($roleName === '' || $roleName === '???????') {
    $roleName = 'Должность не указана';
}

$avatarFile = basename($userdata['img'] ? $userdata['img'] : 'icon-user-default.png');
if ($avatarFile === '' || !file_exists(__DIR__.'/img/'.$avatarFile)) {
    $avatarFile = 'icon-user-default.png';
}
$avatarSrc = '/img/'.rawurlencode($avatarFile);

$services = array();
$clientOrgs = array();

if (intval($userdata['tip']) != 88) {
    $serviceQuery = mysql_query("
        SELECT uslugi.id, uslugi.name, uslugi.inn, uslugi.kpp, uslugi.ogrn
        FROM users_access
        INNER JOIN uslugi ON uslugi.id = users_access.uslugi
        WHERE users_access.users = '".intval($userdata['users_id'])."'
          AND uslugi.del != '1'
        GROUP BY uslugi.id
        ORDER BY uslugi.name ASC
    ");
    if ($serviceQuery) {
        while ($service = mysql_fetch_assoc($serviceQuery)) {
            $services[] = $service;
        }
    }
}

if (intval($userdata['tip']) == 88) {
    $clientOrgQuery = mysql_query("
        SELECT ogrn.id, ogrn.naim, ogrn.inn, ogrn.kpp, ogrn.ogrn
        FROM klient
        INNER JOIN klient_ogrn ON klient_ogrn.klient = klient.id
        INNER JOIN ogrn ON ogrn.ogrn = klient_ogrn.ogrn
        WHERE klient.tel = '".mysql_real_escape_string($userdata['users_login'])."'
          AND klient.del = '0'
        GROUP BY ogrn.id
        ORDER BY ogrn.naim ASC
    ");
    if ($clientOrgQuery) {
        while ($clientOrg = mysql_fetch_assoc($clientOrgQuery)) {
            $clientOrgs[] = $clientOrg;
        }
    }
}

$companyCount = count($services) + count($clientOrgs);
$fullName = short_name($userdata);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Я в компании</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico">
    <style>
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }
        body {
            background: #f3f6f8;
            color: #26313d;
        }
        .company-page {
            box-sizing: border-box;
            width: 100%;
            max-width: none;
            margin: 76px auto 44px;
            padding: 0 24px;
        }
        .company-page *,
        .company-page *:before,
        .company-page *:after {
            box-sizing: border-box;
        }
        .company-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 18px;
        }
        .company-heading h1 {
            margin: 0 0 6px;
            font-size: 29px;
            line-height: 1.2;
            font-weight: 700;
            color: #1f2d3a;
        }
        .company-heading p {
            margin: 0;
            color: #6d7b88;
            font-size: 14px;
        }
        .company-badge {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 7px 11px;
            border: 1px solid #d9e0e6;
            border-radius: 6px;
            background: #fff;
            color: #4b5b68;
            font-size: 13px;
            white-space: nowrap;
        }
        .company-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
            padding: 6px;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(31, 45, 58, 0.05);
        }
        .company-tab {
            min-height: 38px;
            padding: 8px 14px;
            border: 1px solid transparent;
            border-radius: 6px;
            background: transparent;
            color: #526170;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
            cursor: pointer;
        }
        .company-tab:hover {
            background: #f3f7fa;
            color: #26313d;
        }
        .company-tab.is-active {
            background: #2f7fb8;
            color: #fff;
        }
        .company-tab-pane {
            display: none;
        }
        .company-tab-pane.is-active {
            display: block;
        }
        .company-remote-pane {
            width: 100%;
            min-height: 240px;
            padding: 14px;
            overflow-x: auto;
            overflow-y: visible;
        }
        .company-remote-pane .by.amt,
        .company-remote-pane #tablschetog,
        .company-remote-pane #tablosn,
        .company-remote-pane #periodschet,
        .company-remote-pane [id^="periodtabl"] {
            box-sizing: border-box;
            clear: both;
            width: 100% !important;
            max-width: 100%;
            overflow-x: auto;
            padding-left: 0 !important;
        }
        .company-remote-pane > .by.amt {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            margin: 0 0 12px !important;
            padding: 0 !important;
        }
        .company-remote-pane table {
            width: max-content !important;
            min-width: 100%;
            max-width: none;
            border-collapse: collapse;
            table-layout: auto;
            font-size: 13px;
        }
        .company-remote-pane table th,
        .company-remote-pane table td {
            padding: 8px 10px;
            vertical-align: middle;
            overflow-wrap: normal;
            white-space: nowrap;
            word-break: keep-all;
        }
        .company-remote-pane table td[style*="width: 30%"],
        .company-remote-pane table th[style*="width: 30%"] {
            min-width: 260px;
            white-space: normal;
        }
        .company-remote-pane .statdate {
            box-sizing: border-box;
            float: none !important;
            width: auto !important;
            min-width: 170px;
            max-width: 100%;
            margin: 0 !important;
        }
        .company-remote-pane .statdate:first-child {
            flex: 1 1 360px;
        }
        .company-remote-pane .statdate .form-control,
        .company-remote-pane .statdate input,
        .company-remote-pane .statdate select {
            width: 100% !important;
            max-width: 100%;
        }
        .company-remote-pane .statdate input.check {
            width: 18px !important;
            height: 18px !important;
            margin: 0 7px 0 0 !important;
            vertical-align: middle;
        }
        .company-remote-pane .statdate label {
            bottom: auto !important;
            margin: 0 !important;
            color: #536570 !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            line-height: 1.3;
            vertical-align: middle;
        }
        .company-remote-pane .tipschet {
            box-sizing: border-box;
            float: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 58px;
            margin: 0 8px 8px 0 !important;
            padding: 10px 12px !important;
            border: 1px solid #dfe6ec !important;
            border-radius: 6px;
            background-clip: padding-box;
            color: #26313d;
            font-size: 14px !important;
            line-height: 1.25;
            text-align: center;
            white-space: normal;
        }
        .company-remote-pane .tipschet p {
            margin: 0 0 4px;
        }
        .company-remote-pane .tipschet p:first-child {
            font-size: 19px;
            font-weight: 700;
        }
        .company-remote-pane .tipschet .form-control {
            width: 100% !important;
            height: 40px !important;
            min-width: 0;
            border: 0 !important;
            box-shadow: none;
            font-size: 14px;
        }
        .company-remote-pane > .by.amt > .tipschet {
            width: 190px !important;
            flex: 0 0 190px;
            padding: 0 !important;
        }
        .company-remote-pane #tablschetog {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            gap: 8px;
        }
        .company-remote-pane #tablschetog .tipschet {
            width: 152px !important;
            height: auto !important;
            flex: 0 0 152px;
            margin: 0 !important;
        }
        .company-remote-pane .procschet {
            width: 100%;
            height: auto;
            min-height: 38px;
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            list-style: none;
        }
        .company-remote-pane .procschet li {
            display: block;
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            background: #f3f7fa;
            color: #2f6f9f;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }
        .company-remote-pane.company-process-pane #periodschet > .by.amt > .by.amt {
            display: grid;
            grid-template-columns: minmax(150px, 1.4fr) minmax(92px, .8fr) repeat(12, minmax(70px, 1fr));
            gap: 6px;
            align-items: stretch;
            margin-bottom: 8px !important;
            overflow-x: auto;
        }
        .company-remote-pane.company-process-pane #periodschet .tipschet {
            width: auto !important;
            min-width: 0;
            min-height: 56px;
            margin: 0 !important;
            padding: 8px 7px !important;
            font-size: 13px !important;
        }
        .company-remote-pane.company-process-pane #periodschet .tipschet p {
            margin: 0 0 3px;
        }
        .company-remote-pane.company-process-pane #periodschet .tipschet p:first-child {
            font-size: 13px;
            font-weight: 700;
        }
        .company-remote-pane.company-process-pane #periodschet .procschet li {
            min-height: 40px;
            padding: 8px 9px;
            font-size: 13px;
        }
        .company-remote-pane img {
            max-width: 24px;
            height: auto;
        }
        .company-table-pane #tablosn,
        .company-table-pane .schet-table-scroll {
            width: 100% !important;
            max-width: 100%;
            overflow-x: auto;
        }
        .company-table-pane .schet-table {
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            table-layout: fixed;
        }
        .company-table-pane .schet-table th,
        .company-table-pane .schet-table td {
            width: auto !important;
            min-width: 0;
            max-width: none;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .company-table-pane .schet-table th.schet-name-col,
        .company-table-pane .schet-table td[style*="width: 30%"] {
            width: 16% !important;
        }
        .company-table-pane .schet-table th:nth-last-child(-n+4),
        .company-table-pane .schet-table td:nth-last-child(-n+4),
        .company-table-pane .schet-table th:first-child,
        .company-table-pane .schet-table td:first-child {
            width: 30px !important;
            text-align: center;
        }
        .company-tab-loading {
            margin: 0;
            padding: 24px;
            color: #6d7b88;
            text-align: center;
        }
        .company-hero {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 18px;
            align-items: stretch;
            margin-bottom: 18px;
        }
        .company-panel {
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(31, 45, 58, 0.06);
        }
        .company-profile {
            padding: 24px;
            text-align: center;
        }
        .company-avatar {
            width: 152px;
            height: 152px;
            display: block;
            margin: 0 auto 16px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #eef3f6;
            background: #eef3f6;
        }
        .company-name {
            margin: 0 0 5px;
            color: #1f2d3a;
            font-size: 20px;
            line-height: 1.25;
            font-weight: 700;
        }
        .company-role {
            margin: 0;
            color: #73818f;
            font-size: 13px;
            line-height: 1.45;
        }
        .company-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            padding: 18px;
        }
        .company-metric {
            min-height: 104px;
            padding: 16px;
            border: 1px solid #e6edf2;
            border-radius: 8px;
            background: #f8fafb;
        }
        .company-metric span {
            display: block;
            margin-bottom: 8px;
            color: #6d7b88;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .company-metric strong {
            display: block;
            color: #20303f;
            font-size: 20px;
            line-height: 1.2;
        }
        .company-section {
            padding: 20px;
            margin-bottom: 18px;
        }
        .company-section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .company-section-title h2 {
            margin: 0;
            color: #1f2d3a;
            font-size: 18px;
            font-weight: 700;
        }
        .company-section-title span {
            color: #7a8793;
            font-size: 13px;
        }
        .company-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .company-info-item {
            min-height: 72px;
            padding: 14px 15px;
            border: 1px solid #e6edf2;
            border-radius: 8px;
            background: #fbfcfd;
        }
        .company-info-item span {
            display: block;
            margin-bottom: 6px;
            color: #6d7b88;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .company-info-item strong {
            display: block;
            color: #26313d;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }
        .company-muted {
            color: #8b98a5;
            font-weight: 500;
        }
        .company-list {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .company-item {
            padding: 12px 14px;
            border: 1px solid #e6edf2;
            border-radius: 8px;
            background: #fbfcfd;
        }
        .company-item-name {
            display: block;
            color: #20303f;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }
        .company-empty {
            margin: 0;
            padding: 18px;
            border: 1px dashed #d6e0e7;
            border-radius: 8px;
            color: #7a8793;
            background: #fbfcfd;
        }
        @media (max-width: 860px) {
            .company-hero,
            .company-info-grid,
            .company-list {
                grid-template-columns: 1fr;
            }
            .company-remote-pane > .by.amt {
                display: block;
            }
            .company-remote-pane .statdate {
                width: 100% !important;
                margin-bottom: 8px !important;
            }
            .company-summary {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 1199px) {
            .company-table-pane .schet-table {
                width: 1180px !important;
                min-width: 1180px !important;
                max-width: none !important;
            }
        }
        @media (max-width: 640px) {
            .company-heading {
                display: block;
            }
            .company-badge {
                margin-top: 12px;
            }
            .company-page {
                margin-top: 68px;
                padding-right: 12px;
                padding-left: 12px;
            }
            .company-tabs {
                display: grid;
                grid-template-columns: 1fr;
            }
            .company-section-title {
                display: block;
            }
            .company-section-title span {
                display: block;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main class="company-page">
    <div class="company-heading">
        <div>
            <h1>Я в компании</h1>
            <p>Профиль, контакты и доступы текущего пользователя.</p>
        </div>
        <span class="company-badge">Статус: в компании</span>
    </div>

    <nav class="company-tabs" aria-label="Разделы">
        <button type="button" class="company-tab is-active" data-company-tab="profile">Я в компании</button>
        <button type="button" class="company-tab" data-company-tab="remote" data-company-url="tablschet.php?id=<?php echo intval($userdata['users_id']); ?>">Таблица счетов</button>
        <button type="button" class="company-tab" data-company-tab="remote" data-company-url="procinschet.php?id=<?php echo intval($userdata['users_id']); ?>">Процесс в счетах</button>
    </nav>

    <div id="companyProfilePane" class="company-tab-pane is-active">
        <section class="company-hero">
            <div class="company-panel company-profile">
                <img class="company-avatar" src="<?php echo h($avatarSrc); ?>" alt="">
                <h2 class="company-name"><?php echo h($fullName); ?></h2>
                <p class="company-role"><?php echo h($roleName); ?></p>
            </div>

            <div class="company-panel company-summary">
                <div class="company-metric">
                    <span>Принят</span>
                    <strong><?php echo read_value($userdata['prinyat']); ?></strong>
                </div>
                <div class="company-metric">
                    <span>Компании</span>
                    <strong><?php echo intval($companyCount); ?></strong>
                </div>
                <div class="company-metric">
                    <span>Ответственность</span>
                    <strong><?php echo $userdata['otvetstven'] == '1' ? 'Ответственный' : 'Обычный доступ'; ?></strong>
                </div>
            </div>
        </section>

        <section class="company-panel company-section">
            <div class="company-section-title">
                <h2>Контактные данные</h2>
                <span>Профиль пользователя</span>
            </div>
            <div class="company-info-grid">
                <div class="company-info-item">
                    <span>Логин</span>
                    <strong><?php echo read_value($userdata['users_login']); ?></strong>
                </div>
                <div class="company-info-item">
                    <span>E-mail</span>
                    <strong><?php echo read_value($userdata['mail']); ?></strong>
                </div>
                <div class="company-info-item">
                    <span>Телефон</span>
                    <strong><?php echo read_value($userdata['tel']); ?></strong>
                </div>
                <div class="company-info-item">
                    <span>Внутренний телефон</span>
                    <strong><?php echo read_value($userdata['iptel']); ?></strong>
                </div>
                <div class="company-info-item">
                    <span>Дата рождения</span>
                    <strong><?php echo read_value($userdata['dr']); ?></strong>
                </div>
                <div class="company-info-item">
                    <span>Должность</span>
                    <strong><?php echo h($roleName); ?></strong>
                </div>
            </div>
        </section>

        <section class="company-panel company-section">
            <div class="company-section-title">
                <h2>Компании и доступы</h2>
            </div>

            <?php if ($companyCount > 0): ?>
                <ul class="company-list">
                    <?php foreach ($services as $service): ?>
                        <li class="company-item">
                            <span class="company-item-name"><?php echo read_value($service['name']); ?></span>
                        </li>
                    <?php endforeach; ?>

                    <?php foreach ($clientOrgs as $clientOrg): ?>
                        <li class="company-item">
                            <span class="company-item-name"><?php echo read_value($clientOrg['naim']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="company-empty">Компании пока не назначены.</p>
            <?php endif; ?>
        </section>
    </div>

    <section id="companyRemotePane" class="company-tab-pane company-panel company-remote-pane"></section>
</main>

<script src="/js/jquery-1.11.0.min.js?v=company-page"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    (function($) {
        var currentRemoteUrl = '';
        var $profilePane = $('#companyProfilePane');
        var $remotePane = $('#companyRemotePane');

        $('.company-tab').on('click', function() {
            var $button = $(this);
            var tabType = $button.data('company-tab');
            var url = $button.data('company-url');

            $('.company-tab').removeClass('is-active');
            $button.addClass('is-active');

            if (tabType === 'profile') {
                $remotePane.removeClass('is-active company-table-pane company-process-pane');
                $profilePane.addClass('is-active');
                return;
            }

            $profilePane.removeClass('is-active');
            $remotePane.addClass('is-active');
            $remotePane
                .toggleClass('company-table-pane', url.indexOf('tablschet.php') !== -1)
                .toggleClass('company-process-pane', url.indexOf('procinschet.php') !== -1);

            if (currentRemoteUrl === url && $.trim($remotePane.html()) !== '') {
                return;
            }

            currentRemoteUrl = url;
            $remotePane.html('<p class="company-tab-loading">Загрузка раздела...</p>');
            $.ajax({
                url: url,
                cache: false,
                success: function(html) {
                    $remotePane.html(html);
                },
                error: function() {
                    $remotePane.html('<p class="company-empty">Не удалось загрузить раздел.</p>');
                }
            });
        });
    })(jQuery);
</script>
</body>
</html>
