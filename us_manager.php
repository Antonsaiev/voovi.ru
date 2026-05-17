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

$isAdmin = $userdata['adm'] == 1;
$users = mysql_query("
    SELECT users.*, doljnost.name AS doljnost_name
    FROM users
    LEFT JOIN doljnost ON doljnost.id = users.tip
    WHERE users.del_users = '0'
      AND users.uvl = '0'
      AND users.tip != '88'
    ORDER BY users.f_name ASC, users.l_name ASC, users.users_id ASC
");
$usersCount = mysql_num_rows($users);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Пользователи</title>
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
        .users-page {
            box-sizing: border-box;
            width: 100%;
            max-width: 1120px;
            margin: 76px auto 42px;
            padding: 0 15px;
        }
        .users-page *,
        .users-page *:before,
        .users-page *:after {
            box-sizing: border-box;
        }
        .users-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }
        .users-heading h1 {
            margin: 0 0 6px;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 700;
            color: #1f2d3a;
        }
        .users-heading p {
            margin: 0;
            color: #6d7b88;
            font-size: 14px;
        }
        .users-count {
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
        .users-panel {
            max-width: 100%;
            overflow: hidden;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(31, 45, 58, 0.06);
        }
        .users-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .users-col-person {
            width: <?php echo $isAdmin ? '27%' : '34%'; ?>;
        }
        .users-col-login {
            width: 10%;
        }
        .users-col-mail {
            width: <?php echo $isAdmin ? '17%' : '26%'; ?>;
        }
        .users-col-phone {
            width: <?php echo $isAdmin ? '12%' : '16%'; ?>;
        }
        .users-col-role {
            width: <?php echo $isAdmin ? '14%' : '24%'; ?>;
        }
        .users-col-rights {
            width: 10%;
        }
        .users-col-action {
            width: 10%;
        }
        .users-table th {
            padding: 12px;
            border-bottom: 1px solid #edf1f4;
            background: #f8fafb;
            color: #536372;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .users-table td {
            padding: 14px 12px;
            border-top: 1px solid #edf1f4;
            vertical-align: middle;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .users-table td.users-actions {
            padding-right: 8px;
            padding-left: 8px;
        }
        .users-person {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .users-avatar {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            border-radius: 50%;
            border: 3px solid #eef3f6;
            background-color: #eef3f6;
            background-position: center;
            background-size: cover;
        }
        .users-name {
            min-width: 0;
        }
        .users-name a {
            display: inline-block;
            max-width: 100%;
            color: #1f2d3a;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.25;
            text-decoration: none;
            white-space: normal;
            overflow-wrap: anywhere;
        }
        .users-name a:hover {
            color: #2f7fb8;
            text-decoration: none;
        }
        .users-subtitle {
            display: block;
            margin-top: 5px;
            color: #7a8793;
            font-size: 12px;
            line-height: 1.3;
        }
        .users-muted {
            color: #7a8793;
        }
        .users-edit {
            width: 100%;
            min-width: 0;
            padding-right: 8px;
            padding-left: 8px;
            border: 0;
            border-radius: 6px;
            background: #2f7fb8;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        .users-edit:hover,
        .users-edit:focus {
            background: #276b9c;
            color: #fff;
        }
        @media (max-width: 760px) {
            .users-heading {
                display: block;
            }
            .users-count {
                margin-top: 12px;
            }
            .users-panel {
                overflow-x: auto;
            }
            .users-table {
                min-width: <?php echo $isAdmin ? '920px' : '680px'; ?>;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main class="users-page">
    <div class="users-heading">
        <div>
            <h1>Пользователи</h1>
            <p>Активные пользователи.</p>
        </div>
        <span class="users-count">Активных: <?php echo intval($usersCount); ?></span>
    </div>

    <div class="users-panel">
        <table class="users-table">
            <colgroup>
                <col class="users-col-person">
                <?php if ($isAdmin): ?>
                    <col class="users-col-login">
                <?php endif; ?>
                <col class="users-col-mail">
                <col class="users-col-phone">
                <col class="users-col-role">
                <?php if ($isAdmin): ?>
                    <col class="users-col-rights">
                    <col class="users-col-action">
                <?php endif; ?>
            </colgroup>
            <thead>
                <tr>
                    <th>Пользователь</th>
                    <?php if ($isAdmin): ?>
                        <th>Логин</th>
                    <?php endif; ?>
                    <th>E-mail</th>
                    <th>Телефон</th>
                    <th>Должность</th>
                    <?php if ($isAdmin): ?>
                        <th>Права</th>
                        <th></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysql_fetch_array($users)): ?>
                    <?php
                    $avatarFile = basename($row['img'] ? $row['img'] : 'icon-user-default.png');
                    if ($avatarFile === '' || !file_exists(__DIR__.'/img/'.$avatarFile)) {
                        $avatarFile = 'icon-user-default.png';
                    }
                    $fullName = trim($row['f_name'].' '.$row['l_name'].' '.$row['o_name']);
                    if ($fullName === '') {
                        $fullName = $row['users_login'];
                    }
                    $roleName = trim($row['doljnost_name']);
                    $subtitleRole = $roleName;
                    if ($subtitleRole === '') {
                        $subtitleRole = 'Пользователь';
                    }
                    if ($roleName === '') {
                        $roleName = trim($row['pos_pos']);
                    }
                    ?>
                    <tr>
                        <td>
                            <div class="users-person">
                                <span class="users-avatar" style="background-image: url('/img/<?php echo h(rawurlencode($avatarFile)); ?>');"></span>
                                <span class="users-name">
                                    <a href="/profile.php?id=<?php echo intval($row['users_id']); ?>"><?php echo h($fullName); ?></a>
                                    <span class="users-subtitle"><?php echo h($subtitleRole); ?></span>
                                </span>
                            </div>
                        </td>
                        <?php if ($isAdmin): ?>
                            <td><?php echo h($row['users_login']); ?></td>
                        <?php endif; ?>
                        <td><?php echo $row['mail'] !== '' ? h($row['mail']) : '<span class="users-muted">Не указан</span>'; ?></td>
                        <td><?php echo $row['tel'] !== '' ? h($row['tel']) : '<span class="users-muted">Не указан</span>'; ?></td>
                        <td><?php echo $roleName !== '' ? h($roleName) : '<span class="users-muted">Не указана</span>'; ?></td>
                        <?php if ($isAdmin): ?>
                            <td>
                                <?php echo $row['adm'] == 1 ? 'Админ' : 'Пользователь'; ?>
                                <?php if ($row['otvetstven'] == '1'): ?>
                                    <span class="users-muted"> / Ответственный</span>
                                <?php endif; ?>
                            </td>
                            <td class="users-actions">
                                <a class="btn btn-sm users-edit" href="/reduser.php?id=<?php echo intval($row['users_id']); ?>">Изменить</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<script src="/js/jquery-1.11.0.min.js?v=users-list"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
